<?php

namespace Services;

use Entities\Termek;
use Entities\TermekAr;
use Entities\TermekDok;
use Entities\TermekKapcsolodo;
use Entities\TermekKapcsolodokoltseg;
use Entities\TermekKep;
use Entities\TermekMenuTermek;
use Entities\TermekMinkeszlet;
use Entities\TermekOptkeszlet;
use Entities\TermekValtozat;
use Entities\TermekValtozatMinkeszlet;
use Entities\TermekValtozatOptkeszlet;
use Entities\ValtozatbolTermekNaplo;
use mkw\store;

/**
 * Termékváltozatból önálló termék. Az új termék a változat termékének másolata (a megadott névvel, cikkszámmal,
 * vonalkóddal), a változatra hivatkozó minden sor az új termékre íródik át, végül a változat törlődik. Egy irányba
 * megy, visszafordítani nem lehet; a ValtozatbolTermekNaplo őrzi a három entitás előtte / utána állapotát.
 *
 * A bizonylattételen csak a termék és a változat azonosítója változik: a név, a cikkszám és a változat értékei a
 * kiállításkori állapotot őrzik (lásd a változat-összevonásnál is), a kiküldött számla szövege nem írható át.
 *
 * Ha a `termekvaltozat` táblára olyan idegen kulcs mutat, amit ez az osztály nem ismer, a művelet elutasítja magát.
 */
class TermekValtozatToTermekService
{

    /** az új termékre átírandó hivatkozások: kulcs => [tábla, változat oszlop, termék oszlop] */
    private const HIVATKOZASOK = [
        'bizonylattetel' => ['bizonylattetel', 'termekvaltozat_id', 'termek_id'],
        'munkalap' => ['bizonylatfej', 'munkalaptermekvaltozat_id', 'munkalaptermek_id'],
        'kosar' => ['kosar', 'termekvaltozat_id', 'termek_id'],
        'leltartetel' => ['leltartetel', 'termekvaltozat_id', 'termek_id'],
        'unastermekszinkron' => ['unastermekszinkron', 'termekvaltozat_id', 'termek_id'],
    ];

    /** ezeket nem kell átírni: a FIFO-t újraszámoljuk, a többi a változattal együtt megy (vagy előtte kerül át) */
    private const TOROLT = [
        'fiforeteg' => 'termekvaltozat_id',
        'fifoertek' => 'termekvaltozat_id',
        'termekvaltozatar' => 'termekvaltozat_id',
        'termekvaltozatminkeszlet' => 'termekvaltozat_id',
        'termekvaltozatoptkeszlet' => 'termekvaltozat_id',
    ];

    /** a termékből nem másolt mezők: azonosítók, statisztika, és amit a művelet maga ad meg */
    private const NEMMASOLT = [
        'id', 'created', 'lastmod', 'contentmod', 'slug', 'idegenkod', 'migrid', 'unasid', 'unasalaptipus',
        'megtekintesdb', 'megvasarlasdb', 'nepszeruseg',
    ];

    /** @return array{nev: string, cikkszam: string, vonalkod: string, sorok: array<string, int>, ismeretlen: string[]} */
    public function collect(TermekValtozat $valtozat): array
    {
        $conn = store::getEm()->getConnection();
        $sorok = [];
        foreach (self::HIVATKOZASOK as $kulcs => [$tabla, $oszlop]) {
            $sorok[$kulcs] = (int)$conn->fetchOne('SELECT COUNT(*) FROM ' . $tabla . ' WHERE ' . $oszlop . ' = ?', [$valtozat->getId()]);
        }
        return [
            'nev' => (string)$valtozat->getTermek()?->getNev(),
            'cikkszam' => (string)$valtozat->getCikkszam(),
            'vonalkod' => (string)$valtozat->getVonalkod(),
            'sorok' => $sorok,
            'ismeretlen' => $this->getIsmeretlenHivatkozasok(),
        ];
    }

    /**
     * @param array{nev: string, cikkszam: string, vonalkod: string} $adat
     */
    public function convert(TermekValtozat $valtozat, array $adat, bool $kepek, bool $dokumentumok, bool $arak): Termek
    {
        $termek = $valtozat->getTermek();
        $this->check($valtozat, $adat);

        $em = store::getEm();
        $conn = $em->getConnection();
        $valtozatid = $valtozat->getId();

        $conn->beginTransaction();
        try {
            $termekJson = $this->termekToArray($termek);
            $valtozatJson = $this->valtozatToArray($valtozat);

            $uj = $this->createTermek($termek, $valtozat, $adat, $kepek, $arak);
            $em->persist($uj);
            $em->flush();

            if ($kepek) {
                foreach ($this->getCollection($termek, 'termekkepek') as $kep) {
                    $em->persist($this->copyEntity($kep, TermekKep::class, ['termek' => $uj]));
                }
            }
            if ($dokumentumok) {
                foreach ($this->getCollection($termek, 'termekdokok') as $dok) {
                    $em->persist($this->copyEntity($dok, TermekDok::class, ['termek' => $uj]));
                }
            }
            if ($arak && store::isArsavok()) {
                $this->copyArsavArak($termek, $valtozat, $uj);
            }
            $this->moveKeszletszint($valtozat, $uj);
            $em->flush();

            $atiras = [];
            foreach (self::HIVATKOZASOK as $kulcs => [$tabla, $valtozatOszlop, $termekOszlop]) {
                $atiras[$kulcs] = $this->moveRows($tabla, $valtozatOszlop, $termekOszlop, $valtozatid, $uj->getId());
            }
            foreach (['fiforeteg', 'fifoertek'] as $tabla) {
                $atiras[$tabla . '_torolve'] = array_map('intval', $conn->fetchFirstColumn(
                    'SELECT id FROM ' . $tabla . ' WHERE termekvaltozat_id = ?',
                    [$valtozatid]
                ));
                $conn->executeStatement('DELETE FROM ' . $tabla . ' WHERE termekvaltozat_id = ?', [$valtozatid]);
            }

            $valtozatNev = (string)$valtozat->getNev();
            // a DB kaszkád is vinné, de a betöltött sorok a flush-kor a törölt változatra mutatnának
            foreach ($this->getCollection($valtozat, 'arak') as $ar) {
                $em->remove($ar);
            }
            $termek->getValtozatok()?->removeElement($valtozat);
            $em->remove($valtozat);
            $em->flush();

            $naplo = new ValtozatbolTermekNaplo();
            $naplo->setCreatedby(store::getLoggedInDolgozo());
            $naplo->setCreatedbynev(store::getLoggedInDolgozoNev());
            $naplo->setTermek($termek);
            $naplo->setTermekvaltozatid($valtozatid);
            $naplo->setValtozatnev($valtozatNev);
            $naplo->setUjtermek($uj);
            $naplo->setKepekmasolasa($kepek);
            $naplo->setDokumentumokmasolasa($dokumentumok);
            $naplo->setArakmasolasa($arak);
            $naplo->setTermekjson($this->toJson($termekJson));
            $naplo->setValtozatjson($this->toJson($valtozatJson));
            $naplo->setUjtermekjson($this->toJson($this->termekToArray($uj)));
            $naplo->setAtirasjson($this->toJson($atiras));
            $em->persist($naplo);
            $em->flush();

            $conn->commit();
        } catch (\Throwable $e) {
            $conn->rollBack();
            throw $e;
        }

        // saját tranzakciót és zárat nyit, ezért csak a commit után
        if (store::isFifo()) {
            $fifo = new FifoService();
            $fifo->recalculateTermek($termek->getId());
            $fifo->recalculateTermek($uj->getId());
        }
        return $uj;
    }

    /**
     * @throws \RuntimeException ha a művelet nem futtatható
     */
    public function check(TermekValtozat $valtozat, array $adat): void
    {
        if (!$valtozat->getId() || !$valtozat->getTermek()) {
            throw new \RuntimeException(t('A változat nem található.'));
        }
        if (trim((string)($adat['nev'] ?? '')) === '') {
            throw new \RuntimeException(t('Az új termék nevét meg kell adni.'));
        }
        $ismeretlen = $this->getIsmeretlenHivatkozasok();
        if ($ismeretlen) {
            throw new \RuntimeException(
                sprintf(t('Ismeretlen hivatkozás a változatra: %s. A művelet nem futtatható.'), implode(', ', $ismeretlen))
            );
        }
    }

    /**
     * A termék másolata. A láthatóság és az inaktív jelzés a változatét is figyelembe veszi: ami eddig változatként
     * nem látszott, az termékként sem fog. Az ár nélküli másolat nullás árakkal indul.
     */
    private function createTermek(Termek $termek, TermekValtozat $valtozat, array $adat, bool $kepek, bool $arak): Termek
    {
        $em = store::getEm();
        $vmeta = $em->getClassMetadata(TermekValtozat::class);
        $nemMasolt = array_flip(self::NEMMASOLT);
        $uj = $this->copyEntity($termek, Termek::class, [], $nemMasolt);
        $meta = $em->getClassMetadata(Termek::class);
        $set = fn($mezo, $ertek) => $meta->setFieldValue($uj, $mezo, $ertek);

        $set('nev', trim((string)$adat['nev']));
        $set('cikkszam', trim((string)($adat['cikkszam'] ?? '')));
        $set('vonalkod', trim((string)($adat['vonalkod'] ?? '')));
        $set('idegencikkszam', (string)$vmeta->getFieldValue($valtozat, 'idegencikkszam'));
        // ha az UNAS termék nálunk ez a változat volt, az új termék viszi tovább
        $set('unasid', (string)$vmeta->getFieldValue($valtozat, 'unasid'));
        $set('unasalaptipus', (string)$vmeta->getFieldValue($valtozat, 'unasalaptipus'));
        $set('minkeszlet', $vmeta->getFieldValue($valtozat, 'minkeszlet'));
        $set('optkeszlet', $vmeta->getFieldValue($valtozat, 'optkeszlet'));
        $set('valtozatadattipus', null);
        $set('inaktiv', $termek->getInaktiv() || $valtozat->getInaktiv());
        for ($i = 1; $i <= 15; $i++) {
            $mezo = 'lathato' . ($i > 1 ? $i : '');
            if ($meta->hasField($mezo) && $vmeta->hasField($mezo)) {
                $set($mezo, $meta->getFieldValue($uj, $mezo) && $vmeta->getFieldValue($valtozat, $mezo));
            }
        }

        if (!$kepek) {
            $set('kepurl', '');
            $set('kepleiras', '');
        } elseif ($valtozat->getKep()) {
            $set('kepurl', $valtozat->getKep()->getUrl(''));
            $set('kepleiras', $valtozat->getKep()->getLeiras());
        }

        if (!$arak) {
            foreach (['netto', 'brutto', 'akciosnetto', 'akciosbrutto'] as $mezo) {
                $set($mezo, 0);
            }
            $set('akciostart', null);
            $set('akciostop', null);
        } elseif (!store::isArsavok()) {
            // ársávok nélkül a változat ára felár a termékéhez (Termek::getKedvezmenynelkuliNettoAr)
            $vnetto = (float)$valtozat->getNetto();
            $vbrutto = (float)$valtozat->getBrutto();
            $set('netto', (float)$termek->getNetto() + $vnetto);
            $set('brutto', (float)$termek->getBrutto() + $vbrutto);
            if ((float)$termek->getAkciosnetto()) {
                $set('akciosnetto', (float)$termek->getAkciosnetto() + $vnetto);
            }
            if ((float)$termek->getAkciosbrutto()) {
                $set('akciosbrutto', (float)$termek->getAkciosbrutto() + $vbrutto);
            }
        }

        // a gyűjteményeket a tulajdonos oldalon töltjük: az addCimke() a címke összes termékét betöltené
        foreach (['cimkek', 'blogposztok'] as $gyujtemeny) {
            $cel = $meta->getFieldValue($uj, $gyujtemeny);
            foreach ($this->getCollection($termek, $gyujtemeny) as $elem) {
                $cel->add($elem);
            }
        }
        foreach ($this->getCollection($termek, 'kapcsolodokoltsegek') as $sor) {
            $meta->getFieldValue($uj, 'kapcsolodokoltsegek')->add($this->copyEntity($sor, TermekKapcsolodokoltseg::class, ['termek' => $uj]));
        }
        foreach ($this->getCollection($termek, 'termekmenuk') as $sor) {
            $meta->getFieldValue($uj, 'termekmenuk')->add($this->copyEntity($sor, TermekMenuTermek::class, ['termek' => $uj]));
        }
        foreach ($this->getCollection($termek, 'termekkapcsolodok') as $sor) {
            $em->persist($this->copyEntity($sor, TermekKapcsolodo::class, ['termek' => $uj]));
        }
        return $uj;
    }

    /**
     * Ársávonként a változat saját ára, ha van (≠0), különben a termék sora a képletével együtt – így az új termék
     * minden sávban azt az árat kapja, amit a változat eddig ténylegesen kapott. A változat olyan sávjai is
     * átkerülnek, amelyekben a terméknek nincs sora.
     */
    private function copyArsavArak(Termek $termek, TermekValtozat $valtozat, Termek $uj): void
    {
        $em = store::getEm();
        $kulcs = fn($sor) => $sor->getArsav()?->getId() . '|' . $sor->getValutanem()?->getId();
        $valtozatArak = [];
        foreach ($this->getCollection($valtozat, 'arak') as $sor) {
            if ((float)$sor->getNetto() || (float)$sor->getBrutto()) {
                $valtozatArak[$kulcs($sor)] = $sor;
            }
        }
        $meta = $em->getClassMetadata(TermekAr::class);
        foreach ($this->getCollection($termek, 'termekarak') as $sor) {
            $vsor = $valtozatArak[$kulcs($sor)] ?? null;
            unset($valtozatArak[$kulcs($sor)]);
            if ($vsor) {
                $ar = $this->newTermekAr($uj, $vsor);
            } else {
                $ar = $this->copyEntity($sor, TermekAr::class, ['termek' => $uj]);
                $koltsegek = $meta->getFieldValue($ar, 'kepletkoltsegek');
                foreach ($this->getCollection($sor, 'kepletkoltsegek') as $koltseg) {
                    $koltsegek->add($koltseg);
                }
            }
            $em->persist($ar);
        }
        foreach ($valtozatArak as $vsor) {
            $em->persist($this->newTermekAr($uj, $vsor));
        }
    }

    private function newTermekAr(Termek $uj, $valtozatAr): TermekAr
    {
        $ar = new TermekAr();
        $meta = store::getEm()->getClassMetadata(TermekAr::class);
        $meta->setFieldValue($ar, 'termek', $uj);
        $meta->setFieldValue($ar, 'arsav', $valtozatAr->getArsav());
        $meta->setFieldValue($ar, 'valutanem', $valtozatAr->getValutanem());
        $meta->setFieldValue($ar, 'netto', $valtozatAr->getNetto());
        $meta->setFieldValue($ar, 'brutto', $valtozatAr->getBrutto());
        return $ar;
    }

    /** A változat raktáras min./opt. készlete az új termék raktáras beállítása lesz. */
    private function moveKeszletszint(TermekValtozat $valtozat, Termek $uj): void
    {
        $em = store::getEm();
        $parok = [
            TermekValtozatMinkeszlet::class => [TermekMinkeszlet::class, 'minkeszlet'],
            TermekValtozatOptkeszlet::class => [TermekOptkeszlet::class, 'optkeszlet'],
        ];
        foreach ($parok as $forrasEntity => [$celEntity, $mezo]) {
            $sorok = $em->getRepository($forrasEntity)->getRowsByTermekValtozatIds([$valtozat->getId()])[$valtozat->getId()] ?? [];
            $meta = $em->getClassMetadata($celEntity);
            foreach ($sorok as $sor) {
                $cel = new $celEntity();
                $meta->setFieldValue($cel, 'termek', $uj);
                $meta->setFieldValue($cel, 'raktar', $em->getClassMetadata($forrasEntity)->getFieldValue($sor, 'raktar'));
                $meta->setFieldValue($cel, $mezo, $em->getClassMetadata($forrasEntity)->getFieldValue($sor, $mezo));
                $em->persist($cel);
            }
        }
        KeszletSzintService::removeByTermekValtozat($valtozat);
    }

    /**
     * A változatra mutató sorok átírása az új termékre, egyetlen UPDATE-tel (lásd TermekValtozatMergeService::moveRows()).
     *
     * @return int[] az átírt sorok azonosítói
     */
    private function moveRows(string $tabla, string $valtozatOszlop, string $termekOszlop, int $valtozatid, int $termekid): array
    {
        $conn = store::getEm()->getConnection();
        $idk = array_map('intval', $conn->fetchFirstColumn('SELECT id FROM ' . $tabla . ' WHERE ' . $valtozatOszlop . ' = ?', [$valtozatid]));
        if (!$idk) {
            return [];
        }
        $set = $termekOszlop . ' = ?, ' . $valtozatOszlop . ' = NULL';
        $ertekek = [$termekid];
        if ($this->hasColumn($tabla, 'lastmod')) {
            $set .= ', lastmod = ?';
            $ertekek[] = date('Y-m-d H:i:s');
        }
        $ertekek[] = $valtozatid;
        $conn->executeStatement('UPDATE ' . $tabla . ' SET ' . $set . ' WHERE ' . $valtozatOszlop . ' = ?', $ertekek);
        return $idk;
    }

    private function hasColumn(string $tabla, string $oszlop): bool
    {
        return (bool)store::getEm()->getConnection()->fetchOne(
            'SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?',
            [$tabla, $oszlop]
        );
    }

    /** @return string[] "tábla.oszlop" alakban */
    private function getIsmeretlenHivatkozasok(): array
    {
        $sorok = store::getEm()->getConnection()->fetchAllAssociative(
            'SELECT TABLE_NAME, COLUMN_NAME FROM information_schema.KEY_COLUMN_USAGE'
            . ' WHERE TABLE_SCHEMA = DATABASE() AND REFERENCED_TABLE_NAME = ?',
            ['termekvaltozat']
        );
        $ismert = [];
        foreach (self::HIVATKOZASOK as [$tabla, $oszlop]) {
            $ismert[$tabla . '.' . $oszlop] = true;
        }
        foreach (self::TOROLT as $tabla => $oszlop) {
            $ismert[$tabla . '.' . $oszlop] = true;
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

    /**
     * Új entitás a forrás mezőivel és egyértékű kapcsolataival. Reflexióval ír, nem setterrel: a setterek
     * mellékhatásai (pl. a setKepurl() a képleírást is nullázza) itt nem kellenek.
     */
    private function copyEntity(object $forras, string $class, array $felulir, array $kihagy = []): object
    {
        $em = store::getEm();
        $em->getUnitOfWork()->initializeObject($forras);
        $fmeta = $em->getClassMetadata(get_class($forras));
        $meta = $em->getClassMetadata($class);
        $kihagy += array_flip(['id', 'created', 'lastmod']);
        $uj = new $class();
        foreach ($fmeta->getFieldNames() as $mezo) {
            if (!isset($kihagy[$mezo]) && $meta->hasField($mezo)) {
                $meta->setFieldValue($uj, $mezo, $fmeta->getFieldValue($forras, $mezo));
            }
        }
        foreach ($fmeta->getAssociationNames() as $kapcsolat) {
            if (!isset($kihagy[$kapcsolat]) && $fmeta->isSingleValuedAssociation($kapcsolat)
                && $meta->hasAssociation($kapcsolat) && $meta->isSingleValuedAssociation($kapcsolat)) {
                $meta->setFieldValue($uj, $kapcsolat, $fmeta->getFieldValue($forras, $kapcsolat));
            }
        }
        foreach ($felulir as $mezo => $ertek) {
            $meta->setFieldValue($uj, $mezo, $ertek);
        }
        return $uj;
    }

    private function getCollection(object $entity, string $mezo): iterable
    {
        $em = store::getEm();
        $em->getUnitOfWork()->initializeObject($entity);
        return $em->getClassMetadata(get_class($entity))->getFieldValue($entity, $mezo) ?? [];
    }

    /** Mezők és egyértékű kapcsolatok (azonosítóval); a gyűjtemények közül csak amit a hívó kér. */
    private function entityToArray(object $entity): array
    {
        $em = store::getEm();
        $em->getUnitOfWork()->initializeObject($entity);
        $meta = $em->getClassMetadata(get_class($entity));
        $ret = [];
        foreach ($meta->getFieldNames() as $mezo) {
            $ertek = $meta->getFieldValue($entity, $mezo);
            $ret[$mezo] = $ertek instanceof \DateTimeInterface ? $ertek->format('Y-m-d H:i:s') : $ertek;
        }
        foreach ($meta->getAssociationNames() as $kapcsolat) {
            if ($meta->isSingleValuedAssociation($kapcsolat)) {
                $cel = $meta->getFieldValue($entity, $kapcsolat);
                $ret[$kapcsolat . '_id'] = $cel ? ($em->getUnitOfWork()->getEntityIdentifier($cel)['id'] ?? null) : null;
            }
        }
        return $ret;
    }

    private function collectionToArray(object $entity, string $mezo, bool $csakId = false): array
    {
        $ret = [];
        foreach ($this->getCollection($entity, $mezo) as $elem) {
            $ret[] = $csakId ? $elem->getId() : $this->entityToArray($elem);
        }
        return $ret;
    }

    private function termekToArray(Termek $termek): array
    {
        $ret = $this->entityToArray($termek);
        $ret['cimkek'] = $this->collectionToArray($termek, 'cimkek', true);
        $ret['blogposztok'] = $this->collectionToArray($termek, 'blogposztok', true);
        $ret['kepek'] = $this->collectionToArray($termek, 'termekkepek');
        $ret['dokumentumok'] = $this->collectionToArray($termek, 'termekdokok');
        $ret['arak'] = [];
        foreach ($this->getCollection($termek, 'termekarak') as $ar) {
            $ret['arak'][] = $this->entityToArray($ar) + ['kepletkoltsegek' => $this->collectionToArray($ar, 'kepletkoltsegek', true)];
        }
        $ret['kapcsolodokoltsegek'] = $this->collectionToArray($termek, 'kapcsolodokoltsegek');
        $ret['termekmenuk'] = $this->collectionToArray($termek, 'termekmenuk');
        $ret['kapcsolodotermekek'] = $this->collectionToArray($termek, 'termekkapcsolodok');
        $ret['valtozatok'] = $this->collectionToArray($termek, 'valtozatok', true);
        $em = store::getEm();
        foreach (['minkeszletek' => TermekMinkeszlet::class, 'optkeszletek' => TermekOptkeszlet::class] as $kulcs => $entity) {
            $ret[$kulcs] = array_map(fn($sor) => $this->entityToArray($sor), array_values($em->getRepository($entity)->getRowsByTermek($termek->getId())));
        }
        return $ret;
    }

    private function valtozatToArray(TermekValtozat $valtozat): array
    {
        $ret = $this->entityToArray($valtozat);
        $ret['arak'] = $this->collectionToArray($valtozat, 'arak');
        $em = store::getEm();
        foreach (['minkeszletek' => TermekValtozatMinkeszlet::class, 'optkeszletek' => TermekValtozatOptkeszlet::class] as $kulcs => $entity) {
            $sorok = $em->getRepository($entity)->getRowsByTermekValtozatIds([$valtozat->getId()])[$valtozat->getId()] ?? [];
            $ret[$kulcs] = array_map(fn($sor) => $this->entityToArray($sor), array_values($sorok));
        }
        return $ret;
    }

    private function toJson(array $adat): string
    {
        return json_encode($adat, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PARTIAL_OUTPUT_ON_ERROR);
    }
}
