<?php

namespace Services;

use Entities\Bankbizonylatfej;
use Entities\Bizonylatfej;
use Entities\Penztarbizonylatfej;

/**
 * Egy rontott bizonylat rontásának visszavétele, a folyószámlával együtt.
 *
 * A bizonylat saját folyószámla sorai a mentéskor a BizonylatfejListener-ben újraképződnek a
 * `rontott` jelzőből, tehát azokat a visszavétel magától rendbe teszi. A rontás viszont a hozzá
 * tartozó pénztár- és bankbizonylatokat is lerontja (Bizonylatfej::rontpenzmozgas), és hogy
 * melyiket, az sehol nincs feljegyezve: a rá hivatkozó rontott pénzmozgások közül a felhasználó
 * választja ki, melyik álljon vissza.
 */
class BizonylatRontasVisszavetelService
{

    /**
     * @return array{ok: bool, error?: string, bizonylat?: array, penzmozgasok?: array, szarmaztatott?: array}
     */
    public function getInfo(string $id): array
    {
        $bf = $this->find($id);
        if (!$bf) {
            return ['ok' => false, 'error' => t('Nincs ilyen bizonylat') . ': ' . $id];
        }
        if (!$bf->getRontott()) {
            return ['ok' => false, 'error' => t('A bizonylat nem rontott') . ': ' . $id];
        }
        return [
            'ok' => true,
            'bizonylat' => [
                'id' => $bf->getId(),
                'tipus' => $bf->getBizonylattipus()?->getNev(),
                'kelt' => $bf->getKeltStr(),
                'partner' => $bf->getPartnernev(),
                'fizetendo' => $bf->getFizetendo() * 1,
                'valutanem' => $bf->getValutanemnev(),
            ],
            'penzmozgasok' => $this->getRontottPenzmozgasok($bf),
            'szarmaztatott' => $this->getSzarmaztatottBizonylatok($bf),
        ];
    }

    /**
     * @param string[] $penztarids a vele együtt visszaállítandó pénztárbizonylatok
     * @param string[] $bankids a vele együtt visszaállítandó bankbizonylatok
     * @param bool $szarmaztatottTudomasul élő származtatott bizonylat (pl. szétbontás) mellett kötelező
     *
     * @return array{ok: bool, error?: string, msg?: string}
     */
    public function restore(string $id, array $penztarids, array $bankids, bool $szarmaztatottTudomasul): array
    {
        $info = $this->getInfo($id);
        if (!$info['ok']) {
            return $info;
        }
        if ($info['szarmaztatott'] && !$szarmaztatottTudomasul) {
            return ['ok' => false, 'error' => t('A bizonylatból élő bizonylat készült, ezt tudomásul kell venni.')];
        }
        // csak a felkínáltak közül, a POST-ban érkezett azonosítóknak nem hiszünk
        $felkinalt = [];
        foreach ($info['penzmozgasok'] as $p) {
            if (!$p['zarolt']) {
                $felkinalt[$p['tipus']][$p['id']] = true;
            }
        }
        $penztarids = array_values(array_filter(array_unique($penztarids), fn($pid) => isset($felkinalt['penztar'][$pid])));
        $bankids = array_values(array_filter(array_unique($bankids), fn($bid) => isset($felkinalt['bank'][$bid])));

        $em = \mkw\store::getEm();
        $conn = $em->getConnection();
        $conn->beginTransaction();
        try {
            // Előbb a pénzmozgások, külön flush-ban: a bizonylat mentésekor a listener az élő
            // pénztárbizonylatot keresi, és ha a régi még rontott, automatikus pénztárbizonylatos
            // típusnál egy másodikat képezne mellé.
            foreach ($penztarids as $pid) {
                $pbiz = $em->find(Penztarbizonylatfej::class, $pid);
                $pbiz->setRontott(false);
                $em->persist($pbiz);
            }
            foreach ($bankids as $bid) {
                $bbiz = $em->find(Bankbizonylatfej::class, $bid);
                $bbiz->setRontott(false);
                $em->persist($bbiz);
            }
            if ($penztarids || $bankids) {
                $em->flush();
            }

            $bf = $this->find($id);
            // a rontás is így menti: a szállítási költség ne számolódjon újra
            $bf->setKellszallitasikoltsegetszamolni(false);
            $bf->setRontott(false);
            $em->persist($bf);
            $em->flush();
            $conn->commit();
        } catch (\Throwable $e) {
            $conn->rollBack();
            \mkw\store::writelog($id . ': ' . $e->getMessage(), 'rontasvisszavetel.txt');
            return ['ok' => false, 'error' => t('A rontás visszavétele nem sikerült') . ': ' . $e->getMessage()];
        }

        $msg = t('A rontás visszavéve') . ': ' . $id;
        if ($penztarids || $bankids) {
            $msg .= '; ' . t('visszaállított pénzmozgás') . ': ' . implode(', ', array_merge($penztarids, $bankids));
        }
        return ['ok' => true, 'msg' => $msg];
    }

    private function find(string $id): ?Bizonylatfej
    {
        return $id === '' ? null : \mkw\store::getEm()->getRepository(Bizonylatfej::class)->find($id);
    }

    /**
     * A bizonylatra hivatkozó rontott pénztár- és bankbizonylatok. A teljes fejet listázzuk, mert a
     * rontás is az egész fejet rontotta – a más bizonylatra szóló tételeivel együtt.
     *
     * @return array<int, array{tipus: string, id: string, kelt: string, brutto: float, sajat: float,
     *                          masik: string[], zarolt: bool}>
     */
    private function getRontottPenzmozgasok(Bizonylatfej $bf): array
    {
        $conn = \mkw\store::getEm()->getConnection();
        $ret = [];
        $forrasok = [
            'penztar' => ['penztarbizonylatfej', 'penztarbizonylattetel', 'penztarbizonylatfej_id'],
            'bank' => ['bankbizonylatfej', 'bankbizonylattetel', 'bankbizonylatfej_id'],
        ];
        foreach ($forrasok as $tipus => [$fejtabla, $tetteltabla, $fk]) {
            $sorok = $conn->fetchAllAssociative(
                "SELECT f.id, f.kelt, f.brutto, f.valutanemnev,"
                . ($tipus === 'penztar' ? ' f.penztar_id,' : ' NULL AS penztar_id,')
                . " SUM(CASE WHEN t.hivatkozottbizonylat = ? THEN t.brutto ELSE 0 END) AS sajat,"
                . " GROUP_CONCAT(DISTINCT CASE WHEN t.hivatkozottbizonylat <> ? THEN t.hivatkozottbizonylat END SEPARATOR ', ') AS masik"
                . " FROM $fejtabla f JOIN $tetteltabla t ON t.$fk = f.id"
                . " WHERE f.rontott = 1"
                . " AND f.id IN (SELECT t2.$fk FROM $tetteltabla t2 WHERE t2.hivatkozottbizonylat = ?)"
                . " GROUP BY f.id, f.kelt, f.brutto, f.valutanemnev" . ($tipus === 'penztar' ? ', f.penztar_id' : '')
                . " ORDER BY f.kelt, f.id",
                [$bf->getId(), $bf->getId(), $bf->getId()]
            );
            foreach ($sorok as $sor) {
                $ret[] = [
                    'tipus' => $tipus,
                    'id' => $sor['id'],
                    'kelt' => $sor['kelt'],
                    'brutto' => $sor['brutto'] * 1,
                    'sajat' => $sor['sajat'] * 1,
                    'valutanem' => $sor['valutanemnev'],
                    'masik' => $sor['masik'] ? explode(', ', $sor['masik']) : [],
                    'zarolt' => $tipus === 'penztar' && $this->penztarZarolt($sor['penztar_id'], $sor['kelt']),
                ];
            }
        }
        return $ret;
    }

    /** A pénztár lezárt időszakába eső pénztárbizonylathoz nem nyúlunk, ugyanaz a szabály, mint a listenerben. */
    private function penztarZarolt($penztarid, $kelt): bool
    {
        if (!$penztarid) {
            return false;
        }
        $zart = \mkw\store::getParameter(\mkw\consts::PenztarZarva . $penztarid);
        return $zart && $kelt && substr((string)$kelt, 0, 10) <= $zart;
    }

    /**
     * Élő bizonylatok, amik ebből készültek (parbizonylatfej). A szétbontás (BackorderService,
     * BizonylatSliceService) a tartalmat új bizonylatokra teszi és az eredetit lerontja: azt
     * visszavéve a tételek kétszer szerepelnének – a továbbalakításnál (megrendelés → számla) ugyanígy.
     *
     * @return array<int, array{id: string, tipus: string, kelt: string}>
     */
    private function getSzarmaztatottBizonylatok(Bizonylatfej $bf): array
    {
        return \mkw\store::getEm()->getConnection()->fetchAllAssociative(
            'SELECT b.id, bt.nev AS tipus, b.kelt FROM bizonylatfej b'
            . ' LEFT JOIN bizonylattipus bt ON bt.id = b.bizonylattipus_id'
            . ' WHERE b.parbizonylatfej_id = ? AND (b.rontott = 0 OR b.rontott IS NULL)'
            . ' ORDER BY b.kelt, b.id',
            [$bf->getId()]
        );
    }
}
