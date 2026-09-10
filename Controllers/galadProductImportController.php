<?php

namespace Controllers;

use Entities\Afa;
use Entities\Arsav;
use Entities\ME;
use Entities\Meret;
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
 * Galád termékimport a "product export" formájú XLSX-ből.
 *
 * Oszlopok: A=Főtermék ("X" – a változatcsoport vezérsora), B=Variáns csoport (az azonos
 * számú sorok egy termék változatai), C=Cikkszám, D=Név (a soré, nem használt),
 * E=Szín, F=Méret, G=Termék név, I=Mértékegység, J=Vonalkód,
 * P=Sorozatszámot kezel (kellegyediazonosito), Y=Küldés UNAS webshop-ba (feltoltheto2),
 * AB=TÍPUS, AC=Nettó eladási ár ("Kisker.ár" ársáv nettó ára), AD=Import típus.
 * A termékfa az AD, ha az üres, akkor az AB oszlop szövegével azonosítódik.
 *
 * - B üres: sima termék változatok nélkül (cikkszám C, név G, vonalkód J).
 * - B kitöltött: változatos termék. A csoport minden sorából változat lesz – az "X"-szel
 *   jelölt sorból is –, a termék adatai (cikkszám, név, kategória, ár) az "X" sorból jönnek.
 */
class galadProductImportController extends \mkwhelpers\Controller
{

    private $galadSzinCache = [];
    private $galadMeretCache = [];
    private $galadTermekfaCache = [];
    private $galadRootTermekfa = false;

    // a soronkénti findOneBy-ok kiváltására előtöltött, már létező kulcsok (érték => true)
    private $galadLetezoValtozatVonalkod = [];
    private $galadLetezoTermekVonalkod = [];
    private $galadLetezoTermekKulcs = [];

    // a findOneBy nem látja a még nem flush-olt változatokat, ezért a csoporton belül
    // már feldolgozott változat-cikkszámokat memóriában tartjuk
    private $handledValtozatCikkszam = [];
    private $skippedRows = 0;

    /**
     * Termékimport futtatása a feltöltött XLSX alapján.
     *
     * - Az ár nélküli (üres AC oszlop) sorokat is importálja, csak árat nem állít be hozzájuk.
     * - A Variáns csoport (B) köti össze egy termék sorait/változatait.
     * - A kategória (AD, hiányában AB) szövegével hasonló nevű termékfa csomópontot keres,
     *   ahhoz kapcsolja a terméket (termekfa1).
     * - A nettó árat (AC) a "Kisker.ár" ársávba tölti (létrehozza, ha még nincs).
     */
    public function import()
    {
        $dbig = $this->params->getIntRequestParam('dbig', 0);
        $dbtol = $this->params->getIntRequestParam('dbtol', 0);
        if ($dbtol < 2) {
            $dbtol = 2; // fejléc sor kihagyása
        }

        $filenev = \mkw\store::moveUploadedFile('toimport', 'productimport');
        if (!$filenev) {
            echo 'Hiányzó vagy nem elfogadott típusú fájl.';
            return;
        }

        $filetype = IOFactory::identify($filenev);
        $reader = IOFactory::createReader($filetype);
        $reader->setReadDataOnly(true);
        $excel = $reader->load($filenev);
        $sheet = $excel->getActiveSheet();

        // az üres sorok a fájl belsejében is előfordulnak, ezért nem szakítjuk meg rájuk a
        // beolvasást – az utolsó adatot tartalmazó sorig megyünk
        $utolsoSor = $dbig ?: $sheet->getHighestDataRow();

        // 27%-os ÁFA (a nettó árból ez alapján számolódik a bruttó)
        $afa = \mkw\store::getEm()->getRepository(Afa::class)->findByErtek(27);
        $afa = $afa ? $afa[0] : null;

        // VTSZ ('-' szám és név) a 27%-os ÁFА-val, ha még nincs ilyen
        $vtsz = $this->getOrCreateVtsz('-', '-', $afa);

        // alapértelmezett valutanem
        $valutanem = \mkw\store::getEm()->getRepository(Valutanem::class)
            ->find(\mkw\store::getParameter(\mkw\consts::Valutanem));

        // mennyiségi egység (db)
        $me = $this->getOrCreateMe('db');

        // ársáv létrehozása, ha még nincs
        $kiskerArsav = $this->galadGetOrCreateArsav('Kisker.ár');

        // változat adattípusok fix színmódhoz (szín / méret): ha nincsenek, létrehozzuk,
        // és az ID-jukat a paraméterek közé is beírjuk
        $szinAdatTipus = $this->getOrCreateAdatTipus(\mkw\consts::ValtozatTipusSzin, 'Szín');
        $meretAdatTipus = $this->getOrCreateAdatTipus(\mkw\consts::ValtozatTipusMeret, 'Méret');

        $termekdb = 0;
        $valtozatdb = 0;
        $existingCount = 0;

        $groups = $this->readGroups($sheet, $dbtol, $utolsoSor);

        // a soronkénti findOneBy-ok kiváltása: egyszerre betöltjük a fájlban előforduló,
        // már létező vonalkódokat és termék-cikkszámokat memóriába
        $mindenVonalkod = [];
        $mindenTermekKulcs = [];
        foreach ($groups as $group) {
            $mindenTermekKulcs[] = self::getFoSor($group)['cikkszam'];
            foreach ($group as $sor) {
                if ($sor['vonalkod'] !== '') {
                    $mindenVonalkod[] = $sor['vonalkod'];
                }
            }
        }
        $this->galadLetezoValtozatVonalkod = $this->galadLetezoHalmaz(TermekValtozat::class, 'vonalkod', $mindenVonalkod);
        $this->galadLetezoTermekVonalkod = $this->galadLetezoHalmaz(Termek::class, 'vonalkod', $mindenVonalkod);
        $this->galadLetezoTermekKulcs = $this->galadLetezoHalmaz(Termek::class, 'cikkszam', $mindenTermekKulcs);

        // kötegelt mentés: 200 termékenként flush + clear, hogy a Unit of Work ne nőjön
        // korlátlanul (a soronkénti flush négyzetes lassulást okozott). A clear() minden
        // entitást leválaszt, ezért a megosztott entitásokat ID alapján újrareferáljuk.
        $em = \mkw\store::getEm();
        $afaId = $afa ? $afa->getId() : null;
        $vtszId = $vtsz ? $vtsz->getId() : null;
        $meId = $me ? $me->getId() : null;
        $valutanemId = $valutanem ? $valutanem->getId() : null;
        $kiskerArsavId = $kiskerArsav ? $kiskerArsav->getId() : null;
        $szinAdatTipusId = $szinAdatTipus ? $szinAdatTipus->getId() : null;
        $meretAdatTipusId = $meretAdatTipus ? $meretAdatTipus->getId() : null;

        $koteg = 0;
        foreach ($groups as $group) {
            $res = $this->galadImportGroup($group, $afa, $vtsz, $me, $valutanem, $kiskerArsav, $szinAdatTipus, $meretAdatTipus);
            $termekdb += $res['termek'];
            $valtozatdb += $res['valtozat'];
            $existingCount += $res['existing'];
            if (++$koteg % 200 === 0) {
                $em->flush();
                $em->clear();
                $afa = $afaId ? $em->getReference(Afa::class, $afaId) : null;
                $vtsz = $vtszId ? $em->getReference(Vtsz::class, $vtszId) : null;
                $me = $meId ? $em->getReference(ME::class, $meId) : null;
                $valutanem = $valutanemId ? $em->getReference(Valutanem::class, $valutanemId) : null;
                $kiskerArsav = $kiskerArsavId ? $em->getReference(Arsav::class, $kiskerArsavId) : null;
                $szinAdatTipus = $szinAdatTipusId ? $em->getReference(TermekValtozatAdatTipus::class, $szinAdatTipusId) : null;
                $meretAdatTipus = $meretAdatTipusId ? $em->getReference(TermekValtozatAdatTipus::class, $meretAdatTipusId) : null;
                $this->galadReattachCaches($em);
            }
        }
        $em->flush();

        echo 'Kész. ' . $termekdb . ' új termék, ' . $valtozatdb . ' új változat létrehozva.'
            . ($existingCount ? ' ' . $existingCount . ' termék már létezett, változatlan maradt.' : '')
            . ($this->skippedRows ? ' ' . $this->skippedRows . ' sor kimaradt (hiányzó cikkszám, név vagy vonalkód).' : '');
    }

    /**
     * A munkalap sorainak beolvasása termékcsoportokba. A csoport kulcsa a Variáns csoport (B),
     * csoport nélküli sornál a Cikkszám (C) – így az egy termékhez tartozó sorok akkor is
     * összekerülnek, ha a fájlban nem egymás után állnak.
     *
     * @return array kulcs => sorok
     */
    private function readGroups($sheet, $dbtol, $utolsoSor): array
    {
        $groups = [];
        for ($row = $dbtol; $row <= $utolsoSor; ++$row) {
            $fotermek = trim((string)$sheet->getCell('A' . $row)->getValue());
            $csoport = trim((string)$sheet->getCell('B' . $row)->getValue());
            $cikkszam = trim((string)$sheet->getCell('C' . $row)->getValue());
            $szin = trim((string)$sheet->getCell('E' . $row)->getValue());
            $meret = trim((string)$sheet->getCell('F' . $row)->getValue());
            $nev = trim((string)$sheet->getCell('G' . $row)->getValue());
            $vonalkod = trim((string)$sheet->getCell('J' . $row)->getValue());
            $sorozatszam = trim((string)$sheet->getCell('P' . $row)->getValue());
            $unas = trim((string)$sheet->getCell('Y' . $row)->getValue());
            $kategoria = trim((string)$sheet->getCell('AD' . $row)->getValue());
            if ($kategoria === '') {
                $kategoria = trim((string)$sheet->getCell('AB' . $row)->getValue());
            }
            $nettoAr = $sheet->getCell('AC' . $row)->getValue();

            // üres sor: a fájl belsejében is van belőle, egyszerűen átlépjük
            if ($fotermek === '' && $csoport === '' && $cikkszam === '' && $szin === ''
                && $meret === '' && $nev === '' && $vonalkod === '' && $kategoria === ''
                && ($nettoAr === null || trim((string)$nettoAr) === '')) {
                continue;
            }

            // cikkszám vagy név nélkül a sor nem azonosítható
            if ($cikkszam === '' || $nev === '') {
                $this->skippedRows++;
                continue;
            }

            $kulcs = $csoport !== '' ? 'v:' . $csoport : 's:' . $cikkszam;
            $groups[$kulcs][] = [
                // "X" az A oszlopban: ez a csoport vezérsora, a termék adatai innen jönnek
                'fotermek' => (mb_strtoupper($fotermek, 'UTF-8') === 'X'),
                'valtozatos' => ($csoport !== ''),
                'cikkszam' => $cikkszam,
                'meret' => $meret,
                'szin' => $szin,
                'nev' => $nev,
                'vonalkod' => $vonalkod,
                'sorozatszam' => $sorozatszam,
                'unas' => $unas,
                'kategoria' => $kategoria,
                'netto' => $nettoAr,
            ];
        }
        return $groups;
    }

    /**
     * A csoport vezérsora: az "X"-szel jelölt (Főtermék) sor, ennek hiányában az első.
     */
    private static function getFoSor($group)
    {
        foreach ($group as $sor) {
            if ($sor['fotermek']) {
                return $sor;
            }
        }
        return $group[0];
    }

    /**
     * Egy termékhez tartozó (azonos kulcsú) sorok feldolgozása.
     */
    private function galadImportGroup($group, $afa, $vtsz, $me, $valutanem, $kiskerArsav, $szinAdatTipus, $meretAdatTipus)
    {
        if (!$group) {
            return ['termek' => 0, 'valtozat' => 0, 'existing' => 0];
        }
        $first = self::getFoSor($group);
        $termekcikkszam = $first['cikkszam'];
        if ($termekcikkszam === '') {
            return ['termek' => 0, 'valtozat' => 0, 'existing' => 0];
        }

        // változatos termék-e (van Variáns csoportja); sima terméknél a vonalkód a termékre kerül
        $valtozatos = (bool)$first['valtozatos'];
        $termekVonalkod = (!$valtozatos && $first['vonalkod'] !== '') ? $first['vonalkod'] : '';

        $termekrepo = \mkw\store::getEm()->getRepository(Termek::class);
        /** @var Termek $termek */
        $termek = isset($this->galadLetezoTermekKulcs[$termekcikkszam])
            ? $termekrepo->findOneBy(['cikkszam' => $termekcikkszam])
            : null;
        $newTermek = false;
        if (!$termek) {
            // vonalkóddal felvitt terméket csak akkor, ha még nincs ilyen vonalkódú termék
            if ($termekVonalkod !== '' && isset($this->galadLetezoTermekVonalkod[$termekVonalkod])) {
                return ['termek' => 0, 'valtozat' => 0, 'existing' => 1];
            }
            $newTermek = true;
            $this->galadLetezoTermekKulcs[$termekcikkszam] = true;
            $termek = new \Entities\Termek();
            $termek->setCikkszam($termekcikkszam);
            $termek->setIdegenkod($termekcikkszam);
            $termek->setNev($first['nev']);
            $termek->setLathato(true);
            $termek->setInaktiv(false);
            $termek->setMozgat(true);
            $termek->setKellegyediazonosito($this->galadIgenNem($first['sorozatszam']));
            $termek->setFeltoltheto2($this->galadIgenNem($first['unas']));
            if ($me) {
                $termek->setMekod($me);
            }
            if (!$termek->getVtsz() && $vtsz) {
                $termek->setVtsz($vtsz);
            }
            if (!$termek->getAfa() && $afa) {
                $termek->setAfa($afa);
            }
            $kategoria = $this->galadFindTermekfaByNev($first['kategoria']);
            if (!$kategoria) {
                // ha nincs kategória-találat, a szülő nélküli főkategóriába kerül
                if ($this->galadRootTermekfa === false) {
                    $this->galadRootTermekfa = TermekFa::getRoot();
                }
                $kategoria = $this->galadRootTermekfa;
            }
            if ($kategoria) {
                $termek->setTermekfa1($kategoria);
            }
            \mkw\store::getEm()->persist($termek);

            $this->galadSetArsavNetto($termek, $valutanem, $kiskerArsav, $first['netto']);
        }

        $valtozatdb = 0;
        $this->handledValtozatCikkszam = [];
        // a vezérsorból is változat lesz, ezért az egész csoporton végigmegyünk
        if ($valtozatos) {
            foreach ($group as $sor) {
                if ($this->galadImportValtozat($termek, $sor, $szinAdatTipus, $meretAdatTipus)) {
                    $valtozatdb++;
                }
            }
        }

        // sima terméknél a vonalkód magára a termékre kerül
        if ($termekVonalkod !== '') {
            $termek->setVonalkod($termekVonalkod);
            $this->galadLetezoTermekVonalkod[$termekVonalkod] = true;
        }
        \mkw\store::getEm()->persist($termek);

        return [
            'termek' => $newTermek ? 1 : 0,
            'valtozat' => $valtozatdb,
            'existing' => $newTermek ? 0 : 1,
        ];
    }

    /**
     * Termék nettó ár beállítása adott ársávba és valutanembe. Megkeresi a meglévőt,
     * vagy újat hoz létre. (A setNetto a termék ÁFА-ja alapján számolja a bruttót,
     * ezért előbb a termeket kell beállítani, és a terméknek ÁFA-val kell rendelkeznie.)
     */
    private function galadSetArsavNetto($termek, $valutanem, $arsav, $netto)
    {
        if (!$arsav || !$termek->getAfa()) {
            return;
        }
        // ár nélküli termékhez nem hozunk létre árbejegyzést
        if ($netto === null || trim((string)$netto) === '') {
            return;
        }
        $termekarrepo = \mkw\store::getEm()->getRepository(TermekAr::class);
        $ar = null;
        if ($termek->getId()) {
            $felt = ['termek' => $termek->getId(), 'arsav' => $arsav->getId()];
            if ($valutanem) {
                $felt['valutanem'] = $valutanem->getId();
            }
            $ar = $termekarrepo->findOneBy($felt);
        }
        if (!$ar) {
            $ar = new \Entities\TermekAr();
            $ar->setTermek($termek);
            if ($valutanem) {
                $ar->setValutanem($valutanem);
            }
            $ar->setArsav($arsav);
        }
        $ar->setNetto((float)$netto);
        \mkw\store::getEm()->persist($ar);
    }

    /**
     * Egy változat (TermekValtozat) létrehozása/frissítése szín (E) + méret (F) alapján.
     * A változat cikkszáma a C oszlop (önmagában egyedi). Az azonosítás elsődlegesen a
     * cikkszám, hiányában a vonalkód alapján történik.
     */
    private function galadImportValtozat($termek, $sor, $szinAdatTipus, $meretAdatTipus)
    {
        $vcikkszam = $sor['cikkszam'];

        // vonalkód és cikkszám nélkül a sor nem azonosítható: újraimportnál duplikálódna
        if ($sor['vonalkod'] === '' && $vcikkszam === '') {
            $this->skippedRows++;
            return false;
        }

        $tvr = \mkw\store::getEm()->getRepository(TermekValtozat::class);
        $newValtozat = false;
        // ha van vonalkód: csak akkor importáljuk a változatot, ha még nincs ilyen vonalkódú változat
        if ($sor['vonalkod'] !== '') {
            if (isset($this->galadLetezoValtozatVonalkod[$sor['vonalkod']])) {
                return false;
            }
            $valtozat = new \Entities\TermekValtozat();
            $termek->addValtozat($valtozat);
            $newValtozat = true;
            $this->galadLetezoValtozatVonalkod[$sor['vonalkod']] = true;
        } else {
            // nincs vonalkód: cikkszám alapú azonosítás
            if (isset($this->handledValtozatCikkszam[$vcikkszam])) {
                return false;
            }
            $valtozat = $termek->getId()
                ? $tvr->findOneBy(['termek' => $termek->getId(), 'cikkszam' => $vcikkszam])
                : null;
            if (!$valtozat) {
                $valtozat = new \Entities\TermekValtozat();
                $termek->addValtozat($valtozat);
                $newValtozat = true;
            }
            $this->handledValtozatCikkszam[$vcikkszam] = true;
        }
        if ($vcikkszam !== '') {
            $valtozat->setCikkszam($vcikkszam);
        }
        if ($sor['vonalkod'] !== '') {
            $valtozat->setVonalkod($sor['vonalkod']);
        }
        if ($sor['szin'] !== '') {
            $szin = $this->galadGetOrCreateSzin($sor['szin']);
            $valtozat->setSzin($szin);
            if ($szinAdatTipus) {
                $valtozat->setAdatTipus1($szinAdatTipus);
                $valtozat->setErtek1($szin->getNev());
            }
        }
        if ($sor['meret'] !== '') {
            $meret = $this->galadGetOrCreateMeret($sor['meret']);
            $valtozat->setMeret($meret);
            if ($meretAdatTipus) {
                $valtozat->setAdatTipus2($meretAdatTipus);
                $valtozat->setErtek2($meret->getNev());
            }
        }
        $valtozat->setLathato(true);
        $valtozat->setElerheto(true);
        \mkw\store::getEm()->persist($valtozat);
        return $newValtozat;
    }

    /**
     * "igen"/"nem" szöveg átfordítása 1/0 értékre.
     */
    private function galadIgenNem($val)
    {
        return (mb_strtolower(trim((string)$val), 'UTF-8') === 'igen') ? 1 : 0;
    }

    private function getOrCreateMe($nev)
    {
        $me = \mkw\store::getEm()->getRepository(ME::class)->findOneBy(['nev' => $nev]);
        if (!$me) {
            $me = new \Entities\ME();
            $me->setNev($nev);
            \mkw\store::getEm()->persist($me);
            \mkw\store::getEm()->flush();
        }
        return $me;
    }

    /**
     * VTSZ keresése szám + név alapján; ha nincs ilyen, létrehozza a megadott ÁFА-val.
     */
    private function getOrCreateVtsz($szam, $nev, $afa)
    {
        $vtsz = \mkw\store::getEm()->getRepository(Vtsz::class)->findOneBy(['szam' => $szam, 'nev' => $nev]);
        if (!$vtsz) {
            $vtsz = new \Entities\Vtsz();
            $vtsz->setSzam($szam);
            $vtsz->setNev($nev);
            if ($afa) {
                $vtsz->setAfa($afa);
            }
            \mkw\store::getEm()->persist($vtsz);
            \mkw\store::getEm()->flush();
        }
        return $vtsz;
    }

    /**
     * A paraméterben tárolt változat adattípus megkeresése; ha nincs (a paraméter üres
     * vagy elavult ID-ra mutat), létrehozza a megadott néven (vagy egy már létező azonos
     * nevűt használ), és az ID-ját visszaírja a paraméterbe.
     */
    private function getOrCreateAdatTipus($paramKey, $nev)
    {
        $repo = \mkw\store::getEm()->getRepository(TermekValtozatAdatTipus::class);
        $id = \mkw\store::getParameter($paramKey);
        $at = $id ? $repo->find($id) : null;
        if (!$at) {
            $at = $repo->findOneBy(['nev' => $nev]);
            if (!$at) {
                $at = new \Entities\TermekValtozatAdatTipus();
                $at->setNev($nev);
                \mkw\store::getEm()->persist($at);
                \mkw\store::getEm()->flush();
            }
            \mkw\store::setParameter($paramKey, $at->getId());
        }
        return $at;
    }

    private function galadGetOrCreateArsav($nev)
    {
        $arsav = $this->getRepo(Arsav::class)->findOneBy(['nev' => $nev]);
        if (!$arsav) {
            $arsav = new \Entities\Arsav();
            $arsav->setNev($nev);
            \mkw\store::getEm()->persist($arsav);
            \mkw\store::getEm()->flush();
        }
        return $arsav;
    }

    private function galadGetOrCreateSzin($nev)
    {
        if (isset($this->galadSzinCache[$nev])) {
            return $this->galadSzinCache[$nev];
        }
        $szin = \mkw\store::getEm()->getRepository(Szin::class)->findOneBy(['nev' => $nev]);
        if (!$szin) {
            $szin = new \Entities\Szin();
            $szin->setNev($nev);
            \mkw\store::getEm()->persist($szin);
        }
        $this->galadSzinCache[$nev] = $szin;
        return $szin;
    }

    private function galadGetOrCreateMeret($nev)
    {
        if (isset($this->galadMeretCache[$nev])) {
            return $this->galadMeretCache[$nev];
        }
        $meret = \mkw\store::getEm()->getRepository(Meret::class)->findOneBy(['nev' => $nev]);
        if (!$meret) {
            $meret = new \Entities\Meret();
            $meret->setNev($nev);
            \mkw\store::getEm()->persist($meret);
        }
        $this->galadMeretCache[$nev] = $meret;
        return $meret;
    }

    /**
     * Termékfa csomópont keresése név (AD, hiányában AB oszlop) alapján: pontos egyezés,
     * majd kezdet, majd tartalmazás (LIKE), majd a szövegben előforduló leghosszabb
     * csomópontnév, végül leghasonlóbb név (fuzzy).
     */
    private function galadFindTermekfaByNev($nev)
    {
        $nev = trim((string)$nev);
        if ($nev === '') {
            return null;
        }
        if (array_key_exists($nev, $this->galadTermekfaCache)) {
            return $this->galadTermekfaCache[$nev];
        }
        $repo = \mkw\store::getEm()->getRepository(TermekFa::class);

        // 1) pontos egyezés (a kolláció kis/nagybetűt nem különböztet meg)
        $found = $repo->findOneBy(['nev' => $nev]);

        // 2) kezdet, majd tartalmazás, a legrövidebb névvel kezdve: az "ALKATRÉSZ" így az
        //    "ALKATRÉSZEK"-be kerül, nem a "BUKÓSISAK ALKATRÉSZ"-be
        foreach ([$nev . '%', '%' . $nev . '%'] as $minta) {
            if ($found) {
                break;
            }
            $res = \mkw\store::getEm()->createQueryBuilder()
                ->select('tf')->from(TermekFa::class, 'tf')
                ->where('tf.nev LIKE :p')
                ->orderBy('LENGTH(tf.nev)', 'ASC')
                ->setParameter('p', $minta)
                ->setMaxResults(1)
                ->getQuery()->getResult();
            if ($res) {
                $found = $res[0];
            }
        }

        if (!$found) {
            $needle = $this->galadNormalizeNev($nev);
            $mind = $repo->findAll();

            // 3) a leghosszabb csomópontnév, ami benne van a keresett szövegben: a "FÉRFI CSIZMA"
            //    így a "CSIZMA" alá kerül, nem a hozzá hasonló nevű "FÉRFI KESZTYŰ" alá
            $bestLen = 0;
            foreach ($mind as $tf) {
                $tfnev = $this->galadNormalizeNev($tf->getNev());
                $hossz = mb_strlen($tfnev, 'UTF-8');
                if ($hossz > 2 && $hossz > $bestLen && mb_strpos($needle, $tfnev, 0, 'UTF-8') !== false) {
                    $bestLen = $hossz;
                    $found = $tf;
                }
            }

            // 4) leghasonlóbb név (fuzzy)
            if (!$found) {
                $best = null;
                $bestScore = 0;
                foreach ($mind as $tf) {
                    $pct = 0;
                    similar_text($needle, $this->galadNormalizeNev($tf->getNev()), $pct);
                    if ($pct > $bestScore) {
                        $bestScore = $pct;
                        $best = $tf;
                    }
                }
                if ($best && $bestScore >= 50) {
                    $found = $best;
                }
            }
        }

        $this->galadTermekfaCache[$nev] = $found;
        return $found;
    }

    private function galadNormalizeNev($nev)
    {
        return mb_strtolower(trim(preg_replace('/\s+/', ' ', (string)$nev)), 'UTF-8');
    }

    /**
     * Adott entitás egy mezőjének már létező értékei halmazként (érték => true), a megadott
     * lehetséges értékekre szűrve. Egyetlen (1000-esével darabolt) lekérdezéssorozattal
     * tölti be a fájlban előforduló kulcsokat, hogy ne kelljen soronként findOneBy-t futtatni.
     */
    private function galadLetezoHalmaz($entityClass, $field, $values)
    {
        $set = [];
        $values = array_values(array_unique(array_filter($values, static function ($v) {
            return $v !== '' && $v !== null;
        })));
        foreach (array_chunk($values, 1000) as $chunk) {
            $rows = \mkw\store::getEm()->createQueryBuilder()
                ->select('e.' . $field . ' AS val')
                ->from($entityClass, 'e')
                ->where('e.' . $field . ' IN (:vals)')
                ->setParameter('vals', $chunk)
                ->getQuery()->getScalarResult();
            foreach ($rows as $r) {
                $set[(string)$r['val']] = true;
            }
        }
        return $set;
    }

    /**
     * A kötegelt clear() után a memóriában tartott szín/méret/termékfa cache leváló
     * entitásait ID alapján újrareferálja (getReference nem indít lekérdezést), hogy a
     * cache-ek a következő kötegben is használhatók maradjanak.
     */
    private function galadReattachCaches($em)
    {
        foreach ($this->galadSzinCache as $k => $e) {
            $this->galadSzinCache[$k] = $em->getReference(Szin::class, $e->getId());
        }
        foreach ($this->galadMeretCache as $k => $e) {
            $this->galadMeretCache[$k] = $em->getReference(Meret::class, $e->getId());
        }
        foreach ($this->galadTermekfaCache as $k => $e) {
            if ($e) {
                $this->galadTermekfaCache[$k] = $em->getReference(TermekFa::class, $e->getId());
            }
        }
        if ($this->galadRootTermekfa) {
            $this->galadRootTermekfa = $em->getReference(TermekFa::class, $this->galadRootTermekfa->getId());
        }
    }

}
