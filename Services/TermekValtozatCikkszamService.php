<?php

namespace Services;

use Doctrine\DBAL\ArrayParameterType;
use Entities\TermekFa;
use Entities\TermekValtozat;

/**
 * A termékváltozatok cikkszámának átírása TERMÉKCIKKSZÁM-színkód-méretkód alakra (TermekValtozat::composeCikkszam)
 * azokon a termékfa ágakon, ahol a szín+méretes cikkszám be van kapcsolva. Csak azokkal a változatokkal foglalkozik,
 * amelyeken a `kodoltcikkszam` jelző hamis – amit már a generátor vagy egy korábbi átírás kódolt, azt nem bántja.
 */
class TermekValtozatCikkszamService
{
    public function countPending(): int
    {
        $termekfaIds = $this->getTermekfaIds();
        return (int)\mkw\store::getEm()->getConnection()->fetchOne(
            'SELECT COUNT(*) FROM termekvaltozat tv INNER JOIN termek t ON t.id = tv.termek_id AND t.termekfa1_id IN (?)'
            . ' WHERE tv.kodoltcikkszam = 0',
            [$termekfaIds],
            [ArrayParameterType::INTEGER]
        );
    }

    /**
     * @return array<string, int> mi hány változattal történt
     */
    public function rewrite(): array
    {
        $conn = \mkw\store::getEm()->getConnection();
        $szinTipus = \mkw\store::getParameter(\mkw\consts::ValtozatTipusSzin);
        $meretTipus = \mkw\store::getParameter(\mkw\consts::ValtozatTipusMeret);
        // szín/méret törzs nélküli változatnál a TermekValtozat::getSzin()/getMeret() szövege
        $ertek = fn(array $row, $tipus) => match (true) {
            $tipus && $row['adattipus1_id'] == $tipus => (string)$row['ertek1'],
            $tipus && $row['adattipus2_id'] == $tipus => (string)$row['ertek2'],
            default => '',
        };
        $rows = $conn->fetchAllAssociative(
            'SELECT tv.id, tv.cikkszam, t.cikkszam AS termekcikkszam, tv.adattipus1_id, tv.ertek1, tv.adattipus2_id, tv.ertek2,'
            . ' tv.szin_id, s.nev AS szinnev, s.charkod AS szincharkod, tv.meret_id, m.nev AS meretnev, m.charkod AS meretcharkod'
            . ' FROM termekvaltozat tv'
            . ' INNER JOIN termek t ON t.id = tv.termek_id AND t.termekfa1_id IN (?)'
            . ' LEFT JOIN szin s ON s.id = tv.szin_id'
            . ' LEFT JOIN meret m ON m.id = tv.meret_id'
            . ' WHERE tv.kodoltcikkszam = 0',
            [$this->getTermekfaIds()],
            [ArrayParameterType::INTEGER]
        );

        $counts = [
            'átírva' => 0,
            'már ilyen volt' => 0,
            'nincs termékcikkszám' => 0,
            'saját cikkszámú, nem változott' => 0,
            'nincs szín és méret' => 0,
            'hosszabb 50-nél' => 0,
        ];
        $conn->beginTransaction();
        try {
            foreach ($rows as $row) {
                $termekCikkszam = trim((string)$row['termekcikkszam']);
                $cikkszam = trim((string)$row['cikkszam']);
                if ($termekCikkszam === '') {
                    $counts['nincs termékcikkszám']++;
                    continue;
                }
                $uj = TermekValtozat::composeCikkszam(
                    $termekCikkszam,
                    $row['szincharkod'],
                    $row['szin_id'] ? $row['szinnev'] : $ertek($row, $szinTipus),
                    $row['meretcharkod'],
                    $row['meret_id'] ? $row['meretnev'] : $ertek($row, $meretTipus)
                );
                if ($uj === '') {
                    $counts['nincs szín és méret']++;
                    continue;
                }
                if (mb_strlen($uj) > 50) {
                    $counts['hosszabb 50-nél']++;
                    continue;
                }
                if (mb_strtolower($cikkszam) === mb_strtolower($uj)) {
                    // egy korábbi átírás már megcsinálta, csak a jelző hiányzik
                    $conn->executeStatement('UPDATE termekvaltozat SET kodoltcikkszam = 1 WHERE id = ?', [$row['id']]);
                    $counts['már ilyen volt']++;
                    continue;
                }
                // amit már kézzel vagy importtal egyedire állítottak, azt nem írjuk felül
                if ($cikkszam !== '' && mb_strtolower($cikkszam) !== mb_strtolower($termekCikkszam)) {
                    $counts['saját cikkszámú, nem változott']++;
                    continue;
                }
                $conn->executeStatement('UPDATE termekvaltozat SET cikkszam = ?, kodoltcikkszam = 1 WHERE id = ?', [$uj, $row['id']]);
                $counts['átírva']++;
            }
            $conn->commit();
        } catch (\Throwable $e) {
            $conn->rollBack();
            throw $e;
        }
        \mkw\store::writelog('termékváltozat cikkszám átírás: ' . json_encode($counts, JSON_UNESCAPED_UNICODE), 'valtozat_cikkszam_migracio.txt');
        return $counts;
    }

    /**
     * @return int[] a szín+méretes cikkszámú ágak; üres lista mellett az IN () hibás lenne, a 0 id-jű ág pedig nem létezik
     */
    private function getTermekfaIds(): array
    {
        return \mkw\store::getEm()->getRepository(TermekFa::class)->getSzinmeretcikkszamIds() ?: [0];
    }
}
