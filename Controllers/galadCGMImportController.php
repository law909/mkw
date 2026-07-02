<?php

namespace Controllers;

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
 * Galád termékimport a CGM-formátumú (megtisztított) XLSX-ből.
 *
 * Oszlopok: A=többszörös (0/1 – változatos-e a termék), B=változat cikkszám,
 * C=közös cikkszám (a termék cikkszáma ÉS a változatokat összekötő csoportkulcs),
 * D=méret, E=megnevezés, F=Codice Variante (nem használt), G=vonalkód, H=szín,
 * I=kategória (termékfa), J=Prezzo Riservato (nem használt), K=KISKERÁR (bruttó).
 *
 * Az azonos közös cikkszámú (C oszlop) sorok egy termékhez tartoznak.
 */
class galadCGMImportController extends \mkwhelpers\Controller
{

    private $galadSzinCache = [];
    private $galadMeretCache = [];
    private $galadTermekfaCache = [];
    private $galadRootTermekfa = false;

    // a soronkénti findOneBy-ok kiváltására előtöltött, már létező kulcsok (érték => true)
    private $galadLetezoValtozatVonalkod = [];
    private $galadLetezoTermekVonalkod = [];
    private $galadLetezoTermekKulcs = [];

    /**
     * Termékimport futtatása a feltöltött XLSX alapján.
     *
     * - Az ár nélküli (üres K oszlop) sorokat is importálja, csak árat nem állít be hozzájuk.
     * - A közös cikkszám (C oszlop) köti össze egy termék sorait.
     * - Ha a többszörös (A oszlop) == 1, a termék sorai szín/méret változatok.
     *   A változat cikkszáma a B oszlop + "-" + méret (D), hogy terméken belül egyedi legyen.
     * - A kategória (I oszlop) szövegével hasonló nevű termékfa csomópontot keres, ahhoz
     *   kapcsolja a terméket (termekfa1).
     * - A kisker árat (K) a "Kisker.ár" ársávba tölti (létrehozza, ha még nincs).
     */
    public function import()
    {
        $dbig = $this->params->getIntRequestParam('dbig', 0);
        $dbtol = $this->params->getIntRequestParam('dbtol', 0);
        if ($dbtol < 2) {
            $dbtol = 2; // fejléc sor kihagyása
        }

        $filenev = \mkw\store::storagePath($_FILES['toimport']['name']);
        move_uploaded_file($_FILES['toimport']['tmp_name'], $filenev);

        $filetype = IOFactory::identify($filenev);
        $reader = IOFactory::createReader($filetype);
        $reader->setReadDataOnly(true);
        $excel = $reader->load($filenev);
        $sheet = $excel->getActiveSheet();

        // A getHighestRow() gyakran a munkalap teljes kiterjedését adja vissza
        // (akár 1 048 576 sort), ezért nem használjuk felső korlátként. Ha a
        // felhasználó nem ad meg dbig-et, egy hosszabb üres sorozatra állunk meg.
        $hardMax = $dbig ?: 1048576;
        $emptyLimit = 100;
        $emptyRun = 0;

        // 27%-os ÁFA (a bruttó árakból ez alapján számolódik a nettó)
        $afa = \mkw\store::getEm()->getRepository(Afa::class)->findByErtek(27);
        $afa = $afa ? $afa[0] : null;

        // VTSZ ('-' szám és név) a 27%-os ÁFА-val, ha még nincs ilyen
        $vtsz = $this->getOrCreateVtsz('-', '-', $afa);

        // gyártó partner ("CGM"), ha még nincs (szállító = 1)
        $gyarto = $this->getOrCreatePartner('CGMITALIA S.R.L.');

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

        // A sorokat a közös cikkszám (C oszlop) szerint csoportosítjuk: ugyanaz a közös
        // cikkszám ugyanahhoz a termékhez tartozik akkor is, ha a sorok NEM összefüggőek.
        // Ezért az egész fájlt beolvassuk egy kulcs szerinti tömbbe, és csak utána
        // dolgozzuk fel. Az üres sorok nem zavarnak be (kimaradnak a csoportokból).
        $groups = [];

        for ($row = $dbtol; $row <= $hardMax; ++$row) {
            $tobbszoros = trim((string)$sheet->getCell('A' . $row)->getValue());
            $vcikkszambase = trim((string)$sheet->getCell('B' . $row)->getValue());
            $cikkszam = trim((string)$sheet->getCell('C' . $row)->getValue());
            $meret = trim((string)$sheet->getCell('D' . $row)->getValue());
            $megnevezes = trim((string)$sheet->getCell('E' . $row)->getValue());
            $vonalkod = trim((string)$sheet->getCell('G' . $row)->getValue());
            $szin = trim((string)$sheet->getCell('H' . $row)->getValue());
            $kategoria = trim((string)$sheet->getCell('I' . $row)->getValue());
            $kiskerAr = $sheet->getCell('K' . $row)->getValue();

            $kUres = ($kiskerAr === null || trim((string)$kiskerAr) === '');

            // teljesen üres sor: az adatok végét egy hosszabb üres sorozat jelzi
            if ($tobbszoros === '' && $vcikkszambase === '' && $cikkszam === '' && $meret === ''
                && $megnevezes === '' && $vonalkod === '' && $szin === '' && $kategoria === ''
                && $kUres) {
                if (!$dbig && ++$emptyRun >= $emptyLimit) {
                    break;
                }
                continue;
            }
            $emptyRun = 0;

            // a megnevezés (E oszlop) nélküli sorokat nem importáljuk
            if ($megnevezes === '') {
                continue;
            }

            // közös cikkszám nélkül nem tudjuk csoportosítani / felvenni a sort
            if ($cikkszam === '') {
                continue;
            }

            $groups['k:' . $cikkszam][] = [
                'valtozatos' => $tobbszoros,
                'vcikkszambase' => $vcikkszambase,
                'cikkszam' => $cikkszam,
                'meret' => $meret,
                'megnevezes' => $megnevezes,
                'vonalkod' => $vonalkod,
                'szin' => $szin,
                'kategoria' => $kategoria,
                'kisker' => $kiskerAr,
            ];
        }

        // a soronkénti findOneBy-ok kiváltása: egyszerre betöltjük a fájlban előforduló,
        // már létező vonalkódokat és termék-cikkszámokat memóriába
        $mindenVonalkod = [];
        $mindenTermekKulcs = [];
        foreach ($groups as $group) {
            $mindenTermekKulcs[] = $group[0]['cikkszam'];
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
        $gyartoId = $gyarto ? $gyarto->getId() : null;
        $meId = $me ? $me->getId() : null;
        $valutanemId = $valutanem ? $valutanem->getId() : null;
        $kiskerArsavId = $kiskerArsav ? $kiskerArsav->getId() : null;
        $szinAdatTipusId = $szinAdatTipus ? $szinAdatTipus->getId() : null;
        $meretAdatTipusId = $meretAdatTipus ? $meretAdatTipus->getId() : null;

        $koteg = 0;
        foreach ($groups as $group) {
            $res = $this->galadImportGroup($group, $afa, $vtsz, $gyarto, $me, $valutanem, $kiskerArsav, $szinAdatTipus, $meretAdatTipus);
            $termekdb += $res['termek'];
            $valtozatdb += $res['valtozat'];
            if (++$koteg % 200 === 0) {
                $em->flush();
                $em->clear();
                $afa = $afaId ? $em->getReference(Afa::class, $afaId) : null;
                $vtsz = $vtszId ? $em->getReference(Vtsz::class, $vtszId) : null;
                $gyarto = $gyartoId ? $em->getReference(Partner::class, $gyartoId) : null;
                $me = $meId ? $em->getReference(ME::class, $meId) : null;
                $valutanem = $valutanemId ? $em->getReference(Valutanem::class, $valutanemId) : null;
                $kiskerArsav = $kiskerArsavId ? $em->getReference(Arsav::class, $kiskerArsavId) : null;
                $szinAdatTipus = $szinAdatTipusId ? $em->getReference(TermekValtozatAdatTipus::class, $szinAdatTipusId) : null;
                $meretAdatTipus = $meretAdatTipusId ? $em->getReference(TermekValtozatAdatTipus::class, $meretAdatTipusId) : null;
                $this->galadReattachCaches($em);
            }
        }
        $em->flush();

        echo 'Kész. ' . $termekdb . ' termék, ' . $valtozatdb . ' változat importálva.';
    }

    /**
     * Egy termékhez tartozó (azonos közös cikkszámú) sorok feldolgozása.
     */
    private function galadImportGroup($group, $afa, $vtsz, $gyarto, $me, $valutanem, $kiskerArsav, $szinAdatTipus, $meretAdatTipus)
    {
        if (!$group) {
            return ['termek' => 0, 'valtozat' => 0];
        }
        $first = $group[0];
        $cikkszam = $first['cikkszam'];
        if ($cikkszam === '') {
            return ['termek' => 0, 'valtozat' => 0];
        }

        // változatos termék-e (A oszlop == 1); sima terméknél a vonalkód a termékre kerül
        $valtozatos = ((string)$first['valtozatos'] === '1');
        $termekVonalkod = (!$valtozatos && $first['vonalkod'] !== '') ? $first['vonalkod'] : '';

        $termekrepo = \mkw\store::getEm()->getRepository(Termek::class);
        /** @var Termek $termek */
        $termek = isset($this->galadLetezoTermekKulcs[$cikkszam])
            ? $termekrepo->findOneBy(['cikkszam' => $cikkszam])
            : null;
        if (!$termek) {
            // vonalkóddal felvitt terméket csak akkor, ha még nincs ilyen vonalkódú termék
            if ($termekVonalkod !== '' && isset($this->galadLetezoTermekVonalkod[$termekVonalkod])) {
                return ['termek' => 0, 'valtozat' => 0];
            }
            $this->galadLetezoTermekKulcs[$cikkszam] = true;
            $termek = new \Entities\Termek();
            $termek->setCikkszam($cikkszam);
            $termek->setIdegenkod($cikkszam);
            $termek->setNev($first['megnevezes']);
            $termek->setLathato(true);
            $termek->setInaktiv(false);
            $termek->setMozgat(true);
            if ($me) {
                $termek->setMekod($me);
            }
            if ($gyarto) {
                $termek->setGyarto($gyarto);
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

            $this->galadSetArsavAr($termek, $valutanem, $kiskerArsav, $first['kisker']);
        }

        $valtozatdb = 0;
        // változatos termék: A oszlop == 1
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

        return ['termek' => 1, 'valtozat' => $valtozatdb];
    }

    /**
     * Termék ár beállítása adott ársávba és valutanembe (bruttó). Megkeresi a meglévőt,
     * vagy újat hoz létre.
     */
    private function galadSetArsavAr($termek, $valutanem, $arsav, $brutto)
    {
        if (!$arsav || !$termek->getAfa()) {
            return;
        }
        // ár nélküli termékhez nem hozunk létre árbejegyzést
        if ($brutto === null || trim((string)$brutto) === '') {
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
        $ar->setBrutto((float)$brutto);
        \mkw\store::getEm()->persist($ar);
    }

    /**
     * Egy változat (TermekValtozat) létrehozása/frissítése szín + méret alapján.
     * A változat cikkszáma a B oszlop + "-" + méret, hogy terméken belül egyedi legyen.
     * Az azonosítás elsődlegesen a (mindig egyedi) vonalkód alapján történik.
     */
    private function galadImportValtozat($termek, $sor, $szinAdatTipus, $meretAdatTipus)
    {
        $vcikkszam = $sor['vcikkszambase'];
        if ($sor['meret'] !== '') {
            $vcikkszam .= '-' . $sor['meret'];
        }

        $tvr = \mkw\store::getEm()->getRepository(TermekValtozat::class);
        // ha van vonalkód: csak akkor importáljuk a változatot, ha még nincs ilyen vonalkódú változat
        if ($sor['vonalkod'] !== '') {
            if (isset($this->galadLetezoValtozatVonalkod[$sor['vonalkod']])) {
                return false;
            }
            $valtozat = new \Entities\TermekValtozat();
            $termek->addValtozat($valtozat);
            $this->galadLetezoValtozatVonalkod[$sor['vonalkod']] = true;
        } else {
            // nincs vonalkód: minden úgy mint eddig (cikkszám alapú azonosítás)
            $valtozat = null;
            if ($termek->getId() && $vcikkszam !== '') {
                $valtozat = $tvr->findOneBy(['termek' => $termek->getId(), 'cikkszam' => $vcikkszam]);
            }
            if (!$valtozat) {
                $valtozat = new \Entities\TermekValtozat();
                $termek->addValtozat($valtozat);
            }
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
        return true;
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
     * Partner keresése név alapján; ha nincs ilyen, létrehozza szállítóként (szallito=1).
     */
    private function getOrCreatePartner($nev)
    {
        $partner = \mkw\store::getEm()->getRepository(Partner::class)->findOneBy(['nev' => $nev]);
        if (!$partner) {
            $partner = new \Entities\Partner();
            $partner->setNev($nev);
            $partner->setSzallito(1);
            \mkw\store::getEm()->persist($partner);
            \mkw\store::getEm()->flush();
        } elseif (!$partner->getSzallito()) {
            $partner->setSzallito(1);
            \mkw\store::getEm()->persist($partner);
            \mkw\store::getEm()->flush();
        }
        return $partner;
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
     * Termékfa csomópont keresése név (I/kategória oszlop) alapján: pontos egyezés,
     * majd tartalmazás (LIKE), végül leghasonlóbb név (fuzzy).
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

        // 2) tartalmazás (LIKE)
        if (!$found) {
            $res = \mkw\store::getEm()->createQueryBuilder()
                ->select('tf')->from(TermekFa::class, 'tf')
                ->where('tf.nev LIKE :p')
                ->setParameter('p', '%' . $nev . '%')
                ->setMaxResults(1)
                ->getQuery()->getResult();
            if ($res) {
                $found = $res[0];
            }
        }

        // 3) leghasonlóbb név (fuzzy)
        if (!$found) {
            $needle = $this->galadNormalizeNev($nev);
            $best = null;
            $bestScore = 0;
            foreach ($repo->findAll() as $tf) {
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
