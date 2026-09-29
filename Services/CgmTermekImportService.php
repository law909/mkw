<?php

namespace Services;

use Entities\Afa;
use Entities\Arsav;
use Entities\ME;
use Entities\Meret;
use Entities\Partner;
use Entities\Szin;
use Entities\Termek;
use Entities\TermekAr;
use Entities\TermekFa;
use Entities\TermekValtozat;
use Entities\TermekValtozatAdatTipus;
use Entities\Valutanem;
use Entities\Vtsz;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * CGM termékfeltöltő XLSX importja (docs/termékfeltöltés.xlsx formája), az 1. sor fejléc.
 *
 * Oszlopok: A="X" = a termék sora, B=csoport (az azonos számú sorok egy termék változatai),
 * C+D=a változat cikkszáma (C_D), D=méret, E=név, G=vonalkód, H=szín, I=termékkategória,
 * K=bruttó kisker ár, M=gyártó.
 *
 * A termék adatai az "X" sorból jönnek (ha nincs ilyen, a csoport első sorából): a cikkszáma a C,
 * a neve az E a H (szín) nélkül. Meglévő (azonos cikkszámú) termékhez nem nyúl, csak a még
 * hiányzó változatait veszi fel. A már létező változat-cikkszámú vagy vonalkódú sort kihagyja.
 */
class CgmTermekImportService
{

    private const KISKERARSAV = 'Kisker.ár';

    /** a változat üresen maradt szín/méret jellemzőjének értéke, ha a párja ki van töltve */
    private const UNI = 'Uni';

    private const BATCHSIZE = 100;

    private $em;

    /** @var array<string, int> */
    private $ids = [];

    /** @var array<string, int> név => id */
    private $szinIds = [];
    private $meretIds = [];
    private $termekfaIds = [];
    private $gyartoIds = [];

    /** @var array<string, true> */
    private $existingTermekCikkszam = [];
    private $existingValtozatCikkszam = [];
    private $existingVonalkod = [];

    private $hibak = [];

    public function __construct()
    {
        $this->em = \mkw\store::getEm();
    }

    /**
     * @return array{sorok: int, termek: int, letezotermek: int, valtozat: int, letezovaltozat: int, hibak: string[]}
     */
    public function import(string $filepath): array
    {
        $reader = IOFactory::createReaderForFile($filepath);
        $reader->setReadDataOnly(true);
        $sheet = $reader->load($filepath)->getActiveSheet();

        [$groups, $rowCount] = $this->readGroups($sheet);
        if (!$groups) {
            throw new \RuntimeException(t('A fájlban nincs importálható sor.'));
        }

        $this->prepareShared();
        $this->preloadExisting($groups);
        $this->prepareTorzs($groups);

        $result = ['sorok' => $rowCount, 'termek' => 0, 'letezotermek' => 0, 'valtozat' => 0, 'letezovaltozat' => 0];
        $n = 0;
        foreach ($groups as $group) {
            $this->importGroup($group, $result);
            if (++$n % self::BATCHSIZE === 0) {
                $this->em->flush();
                $this->em->clear();
            }
        }
        $this->em->flush();

        $result['hibak'] = array_values($this->hibak);
        return $result;
    }

    /**
     * @return array{0: array<string, array[]>, 1: int} csoportkulcs => sorok, és a beolvasott sorok száma
     */
    private function readGroups($sheet): array
    {
        $groups = [];
        $rowCount = 0;
        $lastRow = $sheet->getHighestDataRow();
        for ($row = 2; $row <= $lastRow; ++$row) {
            $cell = fn($col) => self::cellText($sheet->getCell($col . $row)->getValue());
            $sor = [
                'sor' => $row,
                'fotermek' => mb_strtoupper($cell('A'), 'UTF-8') === 'X',
                'csoport' => $cell('B'),
                'cikkszam' => $cell('C'),
                'meret' => $cell('D'),
                'nev' => $cell('E'),
                'vonalkod' => $cell('G'),
                'szin' => $cell('H'),
                'kategoria' => $cell('I'),
                'ar' => $cell('K'),
                'gyarto' => $cell('M'),
            ];
            if ($sor['csoport'] === '' && $sor['cikkszam'] === '' && $sor['nev'] === '') {
                continue;
            }
            ++$rowCount;
            if ($sor['cikkszam'] === '' || $sor['nev'] === '') {
                $this->addHiba(sprintf(t('%d. sor: hiányzó cikkszám (C) vagy név (E), kimaradt.'), $row));
                continue;
            }
            if ($sor['szin'] === '' && $sor['meret'] !== '') {
                $sor['szin'] = self::UNI;
            } elseif ($sor['meret'] === '' && $sor['szin'] !== '') {
                $sor['meret'] = self::UNI;
            }
            $key = $sor['csoport'] !== '' ? 'b:' . $sor['csoport'] : 'c:' . $sor['cikkszam'];
            $groups[$key][] = $sor;
        }
        return [$groups, $rowCount];
    }

    /** az előzetes és a tényleges kör ugyanazt az üzenetet adná */
    private function addHiba(string $msg)
    {
        $this->hibak[$msg] = $msg;
    }

    private static function cellText($value): string
    {
        // a vonalkód számként jön, a float alakja tudományos jelölésre váltana
        if (is_float($value) && floor($value) === $value) {
            return sprintf('%.0f', $value);
        }
        return trim(preg_replace('/\s+/u', ' ', (string)$value));
    }

    private function prepareShared()
    {
        $afa = $this->em->getRepository(Afa::class)->findByErtek(27);
        $afa = $afa ? $afa[0] : null;
        if (!$afa) {
            throw new \RuntimeException(t('Nincs 27%-os ÁFA kulcs.'));
        }
        $valutanem = $this->em->getRepository(Valutanem::class)->find(\mkw\store::getParameter(\mkw\consts::Valutanem));
        $this->ids = [
            'afa' => $afa->getId(),
            'vtsz' => $this->findOrCreate(Vtsz::class, ['szam' => '-', 'nev' => '-'], fn(Vtsz $v) => $v->setAfa($afa))->getId(),
            'me' => $this->findOrCreate(ME::class, ['nev' => 'db'])->getId(),
            'valutanem' => $valutanem ? $valutanem->getId() : null,
            'arsav' => $this->findOrCreate(Arsav::class, ['nev' => self::KISKERARSAV])->getId(),
            'szintipus' => $this->getAdatTipusId(\mkw\consts::ValtozatTipusSzin, 'Szín'),
            'merettipus' => $this->getAdatTipusId(\mkw\consts::ValtozatTipusMeret, 'Méret'),
        ];
        $root = TermekFa::getRoot();
        $this->ids['root'] = $root ? $root->getId() : null;
    }

    private function findOrCreate(string $class, array $criteria, ?callable $init = null)
    {
        $entity = $this->em->getRepository($class)->findOneBy($criteria);
        if (!$entity) {
            $entity = new $class();
            foreach ($criteria as $field => $value) {
                $entity->{'set' . ucfirst($field)}($value);
            }
            if ($init) {
                $init($entity);
            }
            $this->em->persist($entity);
            $this->em->flush();
        }
        return $entity;
    }

    private function getAdatTipusId(string $paramKey, string $nev): int
    {
        $id = \mkw\store::getParameter($paramKey);
        $adatTipus = $id ? $this->em->getRepository(TermekValtozatAdatTipus::class)->find($id) : null;
        if (!$adatTipus) {
            $adatTipus = $this->findOrCreate(TermekValtozatAdatTipus::class, ['nev' => $nev]);
            \mkw\store::setParameter($paramKey, $adatTipus->getId());
        }
        return $adatTipus->getId();
    }

    private function preloadExisting(array $groups)
    {
        $termekCikkszamok = [];
        $valtozatCikkszamok = [];
        $vonalkodok = [];
        foreach ($groups as $group) {
            $termekCikkszamok[] = self::getFoSor($group)['cikkszam'];
            foreach ($group as $sor) {
                $valtozatCikkszamok[] = self::getValtozatCikkszam($sor);
                $vonalkodok[] = $sor['vonalkod'];
            }
        }
        $this->existingTermekCikkszam = $this->existingValues(Termek::class, 'cikkszam', $termekCikkszamok);
        $this->existingValtozatCikkszam = $this->existingValues(TermekValtozat::class, 'cikkszam', $valtozatCikkszamok);
        $this->existingVonalkod = $this->existingValues(TermekValtozat::class, 'vonalkod', $vonalkodok)
            + $this->existingValues(Termek::class, 'vonalkod', $vonalkodok);
    }

    /**
     * A szín, méret, kategória és gyártó törzselemek a termékek előtt jönnek létre, hogy a flush ne
     * írjon ki félkész terméket vagy változatot.
     */
    private function prepareTorzs(array $groups)
    {
        foreach ($groups as $group) {
            $fo = self::getFoSor($group);
            if (!self::isIn($this->existingTermekCikkszam, $fo['cikkszam'])) {
                $this->getTermekfaId($fo);
                $this->getGyartoId($fo['gyarto']);
            }
            foreach ($group as $sor) {
                if ($sor['szin'] !== '') {
                    $this->getNevId(Szin::class, $this->szinIds, $sor['szin']);
                }
                if ($sor['meret'] !== '') {
                    $this->getNevId(Meret::class, $this->meretIds, $sor['meret']);
                }
            }
        }
        $this->em->flush();
        $this->em->clear();
    }

    private function existingValues(string $class, string $field, array $values): array
    {
        $set = [];
        $values = array_values(array_unique(array_filter($values, fn($v) => $v !== '')));
        foreach (array_chunk($values, 1000) as $chunk) {
            $rows = $this->em->createQueryBuilder()
                ->select('e.' . $field . ' AS val')
                ->from($class, 'e')
                ->where('e.' . $field . ' IN (:vals)')
                ->setParameter('vals', $chunk)
                ->getQuery()->getScalarResult();
            foreach ($rows as $r) {
                // a kolláció miatt a DB kis- és nagybetűtől függetlenül egyeztet
                $set[mb_strtoupper((string)$r['val'], 'UTF-8')] = true;
            }
        }
        return $set;
    }

    private static function isIn(array $set, string $value): bool
    {
        return isset($set[mb_strtoupper($value, 'UTF-8')]);
    }

    private static function getFoSor(array $group): array
    {
        foreach ($group as $sor) {
            if ($sor['fotermek']) {
                return $sor;
            }
        }
        return $group[0];
    }

    private static function getValtozatCikkszam(array $sor): string
    {
        return $sor['meret'] !== '' && $sor['meret'] !== self::UNI ? $sor['cikkszam'] . '_' . $sor['meret'] : $sor['cikkszam'];
    }

    /** A termék neve: az E oszlop a H (szín) nélkül; a szín jellemzően a név végén áll. */
    public static function getTermekNev(string $nev, string $szin): string
    {
        if ($szin === '' || $szin === self::UNI) {
            return $nev;
        }
        $pos = mb_strripos($nev, $szin, 0, 'UTF-8');
        if ($pos === false) {
            return $nev;
        }
        $result = trim(preg_replace('/\s+/u', ' ', mb_substr($nev, 0, $pos, 'UTF-8') . mb_substr($nev, $pos + mb_strlen($szin, 'UTF-8'), null, 'UTF-8')));
        return $result !== '' ? $result : $nev;
    }

    private function importGroup(array $group, array &$result)
    {
        $fo = self::getFoSor($group);
        if (!$fo['fotermek'] && $fo['csoport'] !== '') {
            $this->addHiba(sprintf(t('%s. csoport: nincs "X"-es sora, a termék adatai a %d. sorból jönnek.'), $fo['csoport'], $fo['sor']));
        }

        if (self::isIn($this->existingTermekCikkszam, $fo['cikkszam'])) {
            $termek = $this->em->getRepository(Termek::class)->findOneBy(['cikkszam' => $fo['cikkszam']]);
            ++$result['letezotermek'];
        } else {
            $termek = $this->createTermek($fo);
            $this->existingTermekCikkszam[mb_strtoupper($fo['cikkszam'], 'UTF-8')] = true;
            ++$result['termek'];
        }

        foreach ($group as $sor) {
            $vcikkszam = self::getValtozatCikkszam($sor);
            if (self::isIn($this->existingValtozatCikkszam, $vcikkszam)
                || ($sor['vonalkod'] !== '' && self::isIn($this->existingVonalkod, $sor['vonalkod']))) {
                ++$result['letezovaltozat'];
                continue;
            }
            $this->createValtozat($termek, $sor, $vcikkszam);
            $this->existingValtozatCikkszam[mb_strtoupper($vcikkszam, 'UTF-8')] = true;
            if ($sor['vonalkod'] !== '') {
                $this->existingVonalkod[mb_strtoupper($sor['vonalkod'], 'UTF-8')] = true;
            }
            ++$result['valtozat'];
        }
    }

    private function createTermek(array $fo): Termek
    {
        $nev = self::getTermekNev($fo['nev'], $fo['szin']);
        if ($nev === $fo['nev'] && $fo['szin'] !== '' && $fo['szin'] !== self::UNI) {
            $this->addHiba(sprintf(t('%d. sor: a név nem tartalmazza a színt ("%s"), a teljes név lett a termék neve.'), $fo['sor'], $fo['szin']));
        }

        $termek = new Termek();
        $termek->setCikkszam($fo['cikkszam']);
        $termek->setIdegenkod($fo['cikkszam']);
        $termek->setNev($nev);
        $termek->setLathato(true);
        $termek->setInaktiv(false);
        $termek->setMozgat(true);
        $termek->setAfa($this->em->getReference(Afa::class, $this->ids['afa']));
        $termek->setVtsz($this->em->getReference(Vtsz::class, $this->ids['vtsz']));
        $termek->setMekod($this->em->getReference(ME::class, $this->ids['me']));
        $termekfaId = $this->getTermekfaId($fo);
        if ($termekfaId) {
            $termek->setTermekfa1($this->em->getReference(TermekFa::class, $termekfaId));
        }
        $gyartoId = $this->getGyartoId($fo['gyarto']);
        if ($gyartoId) {
            $termek->setGyarto($this->em->getReference(Partner::class, $gyartoId));
        }
        $this->em->persist($termek);

        $ar = self::parseAr($fo['ar']);
        if ($ar === null) {
            if ($fo['ar'] !== '') {
                $this->addHiba(sprintf(t('%d. sor: az ár (K) nem szám: "%s".'), $fo['sor'], $fo['ar']));
            }
        } else {
            $termekAr = new TermekAr();
            $termekAr->setTermek($termek);
            $termekAr->setArsav($this->em->getReference(Arsav::class, $this->ids['arsav']));
            if ($this->ids['valutanem']) {
                $termekAr->setValutanem($this->em->getReference(Valutanem::class, $this->ids['valutanem']));
            }
            $termekAr->setBrutto($ar);
            $this->em->persist($termekAr);
        }
        return $termek;
    }

    private static function parseAr(string $value): ?float
    {
        $value = str_replace([' ', "\u{00A0}", ','], ['', '', '.'], $value);
        return is_numeric($value) ? (float)$value : null;
    }

    private function createValtozat(Termek $termek, array $sor, string $vcikkszam)
    {
        $valtozat = new TermekValtozat();
        $termek->addValtozat($valtozat);
        $valtozat->setCikkszam($vcikkszam);
        if ($sor['vonalkod'] !== '') {
            $valtozat->setVonalkod($sor['vonalkod']);
        }
        if ($sor['szin'] !== '') {
            $valtozat->setSzin($this->em->getReference(Szin::class, $this->getNevId(Szin::class, $this->szinIds, $sor['szin'])));
            $valtozat->setAdatTipus1($this->em->getReference(TermekValtozatAdatTipus::class, $this->ids['szintipus']));
            $valtozat->setErtek1($sor['szin']);
        }
        if ($sor['meret'] !== '') {
            $valtozat->setMeret($this->em->getReference(Meret::class, $this->getNevId(Meret::class, $this->meretIds, $sor['meret'])));
            $valtozat->setAdatTipus2($this->em->getReference(TermekValtozatAdatTipus::class, $this->ids['merettipus']));
            $valtozat->setErtek2($sor['meret']);
        }
        $valtozat->setLathato(true);
        $valtozat->setElerheto(true);
        $this->em->persist($valtozat);
    }

    /** Szín vagy méret törzselem id-je; a hiányzót azonnal létrehozza, mert a nev unique. */
    private function getNevId(string $class, array &$cache, string $nev): int
    {
        $key = mb_strtoupper($nev, 'UTF-8');
        if (!isset($cache[$key])) {
            $entity = $this->em->getRepository($class)->findOneBy(['nev' => $nev]);
            if (!$entity) {
                $entity = new $class();
                $entity->setNev($nev);
                $this->em->persist($entity);
                $this->em->flush();
            }
            $cache[$key] = $entity->getId();
        }
        return $cache[$key];
    }

    /** A kategória pontos névegyezéssel; ha nincs ilyen, a termékfa gyökere. */
    private function getTermekfaId(array $sor): ?int
    {
        $nev = $sor['kategoria'];
        if ($nev === '') {
            $this->addHiba(sprintf(t('%d. sor: nincs termékkategória (I), a termék a kategóriafa gyökerébe került.'), $sor['sor']));
            return $this->ids['root'];
        }
        $key = mb_strtoupper($nev, 'UTF-8');
        if (!array_key_exists($key, $this->termekfaIds)) {
            $ids = $this->em->getConnection()->fetchFirstColumn('SELECT id FROM termekfa WHERE nev = ? ORDER BY id', [$nev]);
            if (!$ids) {
                $this->addHiba(sprintf(t('"%s" termékkategória nincs a kategóriafában, ezek a termékek a gyökérbe kerültek.'), $nev));
            } elseif (count($ids) > 1) {
                $this->addHiba(sprintf(t('"%s" néven több termékkategória is van, a legrégebbi (id %d) lett beállítva.'), $nev, $ids[0]));
            }
            $this->termekfaIds[$key] = $ids ? (int)$ids[0] : $this->ids['root'];
        }
        return $this->termekfaIds[$key];
    }

    /** A gyártó partner névegyezéssel (a szóközök nem számítanak); ha nincs, gyártóként létrehozza. */
    private function getGyartoId(string $nev): ?int
    {
        if ($nev === '') {
            return null;
        }
        $key = self::normalizeNev($nev);
        if (!array_key_exists($key, $this->gyartoIds)) {
            $talalat = null;
            $rows = $this->em->getConnection()->fetchAllAssociative(
                'SELECT id, nev, gyarto FROM partner WHERE inaktiv = 0 ORDER BY gyarto DESC, id'
            );
            foreach ($rows as $row) {
                if (self::normalizeNev($row['nev']) === $key) {
                    $talalat = $row;
                    break;
                }
            }
            if ($talalat) {
                $partner = $this->em->find(Partner::class, $talalat['id']);
                if (!$talalat['gyarto']) {
                    // a termék űrlapja csak a gyártónak jelölt partnert kínálja, mentéskor elveszne
                    $partner->setGyarto(true);
                    $this->addHiba(sprintf(t('"%s" partner gyártónak jelölve.'), $partner->getNev()));
                }
            } else {
                $partner = new Partner();
                $partner->setNev($nev);
                $partner->setGyarto(true);
                $this->em->persist($partner);
                $this->addHiba(sprintf(t('"%s" gyártó partner létrehozva.'), $nev));
            }
            $this->em->flush();
            $this->gyartoIds[$key] = $partner->getId();
        }
        return $this->gyartoIds[$key];
    }

    private static function normalizeNev(string $nev): string
    {
        return preg_replace('/\s+/u', '', mb_strtoupper(trim($nev), 'UTF-8'));
    }

}
