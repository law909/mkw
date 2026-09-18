<?php

namespace Services;

use Entities\TermekValtozat;
use Entities\TermekValtozatMinkeszlet;
use Entities\TermekValtozatOptkeszlet;
use mkw\store;

/**
 * Két termékváltozat összevonása: a forrásra hivatkozó minden sor a célra íródik át, és a forrás
 * változat kérésre törölhető. Egy irányba megy, visszafordítani nem lehet.
 *
 * Az átírás táblánként egy UPDATE: egy változatra több ezer bizonylattétel is mutathat, azokat az
 * ORM a bizonylatfejükkel együtt nem bírja memóriával. A hivatkozók egy részén származtatott mezők
 * is vannak (a bizonylattételen a változat értékei, adattípusai és cikkszáma, a munkalapon a
 * változat neve); ezek a cél változatból jönnek, tehát minden érintett sorra ugyanaz az érték.
 * A mezőlista a merge()-ben van, a hivatkozott setterekkel együtt kell karbantartani.
 *
 * A FIFO rétegeket nem írjuk át, hanem a termékre újraszámoljuk: a rétegsorrend és a
 * tartozás-átvitel miatt két csoport rétegeit nem lehet egyszerűen egymás mellé tenni.
 *
 * Ha a `termekvaltozat` táblára olyan idegen kulcs mutat, amit ez az osztály nem ismer, az
 * összevonás elutasítja magát: inkább ne fusson le, mint hogy árva sorokat hagyjon.
 */
class TermekValtozatMergeService
{

    /** ennyi soronként megy egy flush */
    private const KOTEG = 200;

    /** Amit át kell írni: kulcs => [tábla, oszlop] — a számlálás és az ellenőrzés közös forrása. */
    private const HIVATKOZASOK = [
        'bizonylattetel' => ['bizonylattetel', 'termekvaltozat_id'],
        'munkalap' => ['bizonylatfej', 'munkalaptermekvaltozat_id'],
        'kosar' => ['kosar', 'termekvaltozat_id'],
        'leltartetel' => ['leltartetel', 'termekvaltozat_id'],
        'minkeszlet' => ['termekvaltozatminkeszlet', 'termekvaltozat_id'],
        'optkeszlet' => ['termekvaltozatoptkeszlet', 'termekvaltozat_id'],
        'fiforeteg' => ['fiforeteg', 'termekvaltozat_id'],
        'fifoertek' => ['fifoertek', 'termekvaltozat_id'],
    ];

    /** A két raktáras készletszint tábla ugyanúgy viselkedik, csak az entitás más. */
    private const KESZLETSZINTEK = [
        'minkeszlet' => TermekValtozatMinkeszlet::class,
        'optkeszlet' => TermekValtozatOptkeszlet::class,
    ];

    /**
     * Mi érinti az összevonást. A `db` a forrás sorainak száma, az `utkozes` azoké, amelyek a
     * célon már léteznek (raktáranként egy min./opt. készlet lehet, a forrásé ilyenkor törlődik).
     *
     * @return array{sorok: array<int, array{kulcs: string, db: int, utkozes: int}>, ismeretlen: string[]}
     */
    public function collect(TermekValtozat $forras, TermekValtozat $cel): array
    {
        $conn = store::getEm()->getConnection();
        $sorok = [];
        foreach (self::HIVATKOZASOK as $kulcs => [$tabla, $oszlop]) {
            $db = (int)$conn->fetchOne(
                'SELECT COUNT(*) FROM ' . $tabla . ' WHERE ' . $oszlop . ' = ?',
                [$forras->getId()]
            );
            $sorok[] = [
                'kulcs' => $kulcs,
                'db' => $db,
                'utkozes' => isset(self::KESZLETSZINTEK[$kulcs]) ? $this->countUtkozes($kulcs, $forras, $cel) : 0,
            ];
        }
        return ['sorok' => $sorok, 'ismeretlen' => $this->getIsmeretlenHivatkozasok()];
    }

    /**
     * Az összevonás végrehajtása.
     *
     * @param bool $forrasTorles a forrás változat törlése a végén
     *
     * @return array{atirt: array<string, int>, torolt: array<string, int>, forrastorolve: bool}
     */
    public function merge(TermekValtozat $forras, TermekValtozat $cel, bool $forrasTorles): array
    {
        $this->check($forras, $cel);

        $em = store::getEm();
        $conn = $em->getConnection();
        $termekid = $cel->getTermek()?->getId();
        $forrasid = $forras->getId();
        $celid = $cel->getId();
        $atirt = [];
        $torolt = [];

        $conn->beginTransaction();
        try {
            $atirt['bizonylattetel'] = $this->moveRows('bizonylattetel', 'termekvaltozat_id', $forrasid, $celid, [
                // a Bizonylattetel::setTermekvaltozat() származtatott mezői — a kettőt együtt kell karbantartani
                'valtozatertek1' => $cel->getErtek1(),
                'valtozatertek2' => $cel->getErtek2(),
                'valtozatadattipus1_id' => $cel->getAdatTipus1()?->getId(),
                'valtozatadattipus1nev' => $cel->getAdatTipus1()?->getNev() ?? '',
                'valtozatadattipus2_id' => $cel->getAdatTipus2()?->getId(),
                'valtozatadattipus2nev' => $cel->getAdatTipus2()?->getNev() ?? '',
                'valtozatcikkszam' => $cel->getCikkszam(),
            ]);
            $atirt['munkalap'] = $this->moveRows('bizonylatfej', 'munkalaptermekvaltozat_id', $forrasid, $celid, [
                'munkalaptermekvaltozatnev' => $cel->getNev(),
            ]);
            $atirt['kosar'] = $this->moveRows('kosar', 'termekvaltozat_id', $forrasid, $celid, []);
            $atirt['leltartetel'] = $this->moveRows('leltartetel', 'termekvaltozat_id', $forrasid, $celid, []);
            foreach (self::KESZLETSZINTEK as $kulcs => $entity) {
                $eredmeny = $this->moveKeszletszint($entity, $forras, $cel);
                $atirt[$kulcs] = $eredmeny['atirt'];
                $torolt[$kulcs] = $eredmeny['torolt'];
            }
            $em->flush();

            // a forrásra már nincs bizonylattétel, így a FIFO újraszámolás sem nyúlna a sorokhoz
            $torolt['fiforeteg'] = (int)$conn->executeStatement(
                'DELETE FROM fiforeteg WHERE termekvaltozat_id = ?',
                [$forrasid]
            );
            $torolt['fifoertek'] = (int)$conn->executeStatement(
                'DELETE FROM fifoertek WHERE termekvaltozat_id = ?',
                [$forrasid]
            );

            if ($forrasTorles) {
                KeszletSzintService::removeByTermekValtozat($forras);
                $em->remove($forras);
                $em->flush();
            }
            $conn->commit();
        } catch (\Throwable $e) {
            $conn->rollBack();
            throw $e;
        }

        // saját tranzakciót és zárat nyit, ezért csak a commit után
        if (store::isFifo()) {
            (new FifoService())->recalculateTermek($termekid);
        }

        return ['atirt' => $atirt, 'torolt' => $torolt, 'forrastorolve' => $forrasTorles];
    }

    /**
     * @throws \RuntimeException ha az összevonás nem futtatható
     */
    public function check(TermekValtozat $forras, TermekValtozat $cel): void
    {
        if (!$forras->getId() || !$cel->getId()) {
            throw new \RuntimeException(t('Mindkét változatot ki kell választani.'));
        }
        if ($forras->getId() === $cel->getId()) {
            throw new \RuntimeException(t('A két változat nem lehet ugyanaz.'));
        }
        if ($forras->getTermek()?->getId() !== $cel->getTermek()?->getId()) {
            throw new \RuntimeException(t('A két változat nem ugyanahhoz a termékhez tartozik.'));
        }
        $ismeretlen = $this->getIsmeretlenHivatkozasok();
        if ($ismeretlen) {
            throw new \RuntimeException(
                sprintf(t('Ismeretlen hivatkozás a változatra: %s. Az összevonás nem futtatható.'), implode(', ', $ismeretlen))
            );
        }
    }

    /**
     * A `termekvaltozat` táblára mutató idegen kulcsok, amiket a HIVATKOZASOK nem fed le. Séma-
     * bővítéskor ez akad fel elsőként, nem a felhasználó adata.
     *
     * @return string[] "tábla.oszlop" alakban
     */
    private function getIsmeretlenHivatkozasok(): array
    {
        $conn = store::getEm()->getConnection();
        $sorok = $conn->fetchAllAssociative(
            'SELECT TABLE_NAME, COLUMN_NAME FROM information_schema.KEY_COLUMN_USAGE'
            . ' WHERE TABLE_SCHEMA = DATABASE() AND REFERENCED_TABLE_NAME = ?',
            ['termekvaltozat']
        );
        $ismert = [];
        foreach (self::HIVATKOZASOK as [$tabla, $oszlop]) {
            $ismert[strtolower($tabla . '.' . $oszlop)] = true;
        }
        $ret = [];
        foreach ($sorok as $sor) {
            $nev = strtolower($sor['TABLE_NAME'] . '.' . $sor['COLUMN_NAME']);
            if (!isset($ismert[$nev])) {
                $ret[] = $nev;
            }
        }
        return $ret;
    }

    private function countUtkozes(string $kulcs, TermekValtozat $forras, TermekValtozat $cel): int
    {
        $repo = store::getEm()->getRepository(self::KESZLETSZINTEK[$kulcs]);
        $forrasSorok = $repo->getRowsByTermekValtozatIds([$forras->getId()])[$forras->getId()] ?? [];
        $celSorok = $repo->getRowsByTermekValtozatIds([$cel->getId()])[$cel->getId()] ?? [];
        return count(array_intersect_key($forrasSorok, $celSorok));
    }

    /**
     * A tábla forrásra mutató sorainak átírása a célra, egyetlen UPDATE-tel. A származtatott
     * mezők a cél változatból jönnek, ezért minden érintett sorra ugyanaz az érték.
     *
     * Szándékosan nyers SQL: egy változatra több ezer bizonylattétel is mutathat, azokat a
     * hozzájuk töltődő bizonylatfejjel együtt az ORM nem bírja memóriával. Cserébe a
     * származtatott mezőket itt kell felsorolni – a hivatkozott setterrel együtt karbantartva.
     *
     * @param array $szarmaztatott oszlop => érték
     *
     * @return int az átírt sorok száma
     */
    private function moveRows(string $tabla, string $oszlop, $forrasid, $celid, array $szarmaztatott): int
    {
        $conn = store::getEm()->getConnection();
        $set = [$oszlop . ' = ?'];
        $ertekek = [$celid];
        foreach ($szarmaztatott as $mezo => $ertek) {
            $set[] = $mezo . ' = ?';
            $ertekek[] = $ertek;
        }
        if ($this->hasColumn($tabla, 'lastmod')) {
            $set[] = 'lastmod = ?';
            $ertekek[] = date('Y-m-d H:i:s');
        }
        $ertekek[] = $forrasid;
        return (int)$conn->executeStatement(
            'UPDATE ' . $tabla . ' SET ' . implode(', ', $set) . ' WHERE ' . $oszlop . ' = ?',
            $ertekek
        );
    }

    private function hasColumn(string $tabla, string $oszlop): bool
    {
        return (bool)store::getEm()->getConnection()->fetchOne(
            'SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?',
            [$tabla, $oszlop]
        );
    }

    /**
     * Raktáras min./opt. készlet: raktáranként egy sor lehet, ezért ahol a célnak már van
     * beállítása, ott a célé marad, és a forrásé törlődik.
     *
     * @return array{atirt: int, torolt: int}
     */
    private function moveKeszletszint(string $entity, TermekValtozat $forras, TermekValtozat $cel): array
    {
        $em = store::getEm();
        $repo = $em->getRepository($entity);
        $forrasSorok = $repo->getRowsByTermekValtozatIds([$forras->getId()])[$forras->getId()] ?? [];
        $celSorok = $repo->getRowsByTermekValtozatIds([$cel->getId()])[$cel->getId()] ?? [];
        $atirt = 0;
        $torolt = 0;
        foreach ($forrasSorok as $raktarid => $sor) {
            if (isset($celSorok[$raktarid])) {
                $em->remove($sor);
                $torolt++;
            } else {
                $sor->setTermekvaltozat($cel);
                $em->persist($sor);
                $atirt++;
            }
        }
        return ['atirt' => $atirt, 'torolt' => $torolt];
    }

}
