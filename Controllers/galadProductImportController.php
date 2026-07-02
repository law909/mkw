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
 * Galád termékimport a "product_export_variants" formájú XLSX-ből.
 *
 * Oszlopok: A=Változat (0/1 – változatos-e a termék), B=Közös cikkszám (a változatos
 * termék cikkszáma ÉS a változatokat összekötő csoportkulcs), C=Méret, D=Szín,
 * E=Maradék név (a változatos termék neve), F=Cikkszám (a sima termék / a változat
 * cikkszáma), G=Név (a sima termék neve), H=Típus (nem használt), I=Mértékegység,
 * J=Vonalkód, K=ÁFA (nem használt), P=Sorozatszámot kezel (kellegyediazonosito),
 * W=Küldés UNAS webshop-ba (feltoltheto2), Z=TÍPUS (kategória / termékfa),
 * AA=Nettó eladási ár ("Kisker.ár" ársáv nettó ára).
 *
 * - A=0: sima termék változatok nélkül (cikkszám F, név G, vonalkód J).
 * - A=1: változatos termék, a sorait a Közös cikkszám (B) köti össze; minden sor egy
 *   szín/méret változat (cikkszám F – önmagában egyedi, méret C, szín D, vonalkód J).
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

    /**
     * Termékimport futtatása a feltöltött XLSX alapján.
     *
     * - Az ár nélküli (üres AA oszlop) sorokat is importálja, csak árat nem állít be hozzájuk.
     * - A=1 esetén a Közös cikkszám (B) köti össze egy termék sorait/változatait.
     * - A kategória (Z oszlop) szövegével hasonló nevű termékfa csomópontot keres, ahhoz
     *   kapcsolja a terméket (termekfa1).
     * - A nettó árat (AA) a "Kisker.ár" ársávba tölti (létrehozza, ha még nincs).
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

        // A sorokat cikkszám szerint csoportosítjuk: változatos terméknél (A=1) a Közös
        // cikkszám (B), sima terméknél (A=0) a Cikkszám (F) a kulcs. Ugyanaz a kulcs
        // ugyanahhoz a termékhez tartozik akkor is, ha a sorok NEM összefüggőek. Ezért az
        // egész fájlt beolvassuk egy kulcs szerinti tömbbe, és csak utána dolgozzuk fel.
        $groups = [];

        for ($row = $dbtol; $row <= $hardMax; ++$row) {
            $valtozat = trim((string)$sheet->getCell('A' . $row)->getValue());
            $kozoscikkszam = trim((string)$sheet->getCell('B' . $row)->getValue());
            $meret = trim((string)$sheet->getCell('C' . $row)->getValue());
            $szin = trim((string)$sheet->getCell('D' . $row)->getValue());
            $maradeknev = trim((string)$sheet->getCell('E' . $row)->getValue());
            $cikkszam = trim((string)$sheet->getCell('F' . $row)->getValue());
            $nev = trim((string)$sheet->getCell('G' . $row)->getValue());
            $vonalkod = trim((string)$sheet->getCell('J' . $row)->getValue());
            $sorozatszam = trim((string)$sheet->getCell('P' . $row)->getValue());
            $unas = trim((string)$sheet->getCell('W' . $row)->getValue());
            $kategoria = trim((string)$sheet->getCell('Z' . $row)->getValue());
            $nettoAr = $sheet->getCell('AA' . $row)->getValue();

            $aaUres = ($nettoAr === null || trim((string)$nettoAr) === '');
            $valtozatos = ($valtozat === '1');

            // teljesen üres sor: az adatok végét egy hosszabb üres sorozat jelzi
            if ($valtozat === '' && $kozoscikkszam === '' && $meret === '' && $szin === ''
                && $maradeknev === '' && $cikkszam === '' && $nev === '' && $vonalkod === ''
                && $kategoria === '' && $aaUres) {
                if (!$dbig && ++$emptyRun >= $emptyLimit) {
                    break;
                }
                continue;
            }
            $emptyRun = 0;

            // a termék neve nélküli sorokat nem importáljuk
            // (változatosnál a Maradék név / E, sima terméknél a Név / G)
            if (($valtozatos ? $maradeknev : $nev) === '') {
                continue;
            }

            // a termék cikkszáma: változatosnál a Közös cikkszám (B), sima terméknél a
            // Cikkszám (F). Ez egyben a csoportosítási kulcs is.
            $termekcikkszam = $valtozatos ? $kozoscikkszam : $cikkszam;
            if ($termekcikkszam === '') {
                continue;
            }
            $kulcs = ($valtozatos ? 'v:' : 's:') . $termekcikkszam;

            $groups[$kulcs][] = [
                'valtozatos' => $valtozatos,
                'termekcikkszam' => $termekcikkszam,
                'cikkszam' => $cikkszam,
                'meret' => $meret,
                'szin' => $szin,
                'nev' => $valtozatos ? $maradeknev : $nev,
                'vonalkod' => $vonalkod,
                'sorozatszam' => $sorozatszam,
                'unas' => $unas,
                'kategoria' => $kategoria,
                'netto' => $nettoAr,
            ];
        }

        // a soronkénti findOneBy-ok kiváltása: egyszerre betöltjük a fájlban előforduló,
        // már létező vonalkódokat és termék-cikkszámokat memóriába
        $mindenVonalkod = [];
        $mindenTermekKulcs = [];
        foreach ($groups as $group) {
            $mindenTermekKulcs[] = $group[0]['termekcikkszam'];
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

        echo 'Kész. ' . $termekdb . ' termék, ' . $valtozatdb . ' változat importálva.';
    }

    /**
     * Egy termékhez tartozó (azonos kulcsú) sorok feldolgozása.
     */
    private function galadImportGroup($group, $afa, $vtsz, $me, $valutanem, $kiskerArsav, $szinAdatTipus, $meretAdatTipus)
    {
        if (!$group) {
            return ['termek' => 0, 'valtozat' => 0];
        }
        $first = $group[0];
        $termekcikkszam = $first['termekcikkszam'];
        if ($termekcikkszam === '') {
            return ['termek' => 0, 'valtozat' => 0];
        }

        // változatos termék-e (A oszlop == 1); sima terméknél a vonalkód a termékre kerül
        $valtozatos = (bool)$first['valtozatos'];
        $termekVonalkod = (!$valtozatos && $first['vonalkod'] !== '') ? $first['vonalkod'] : '';

        $termekrepo = \mkw\store::getEm()->getRepository(Termek::class);
        /** @var Termek $termek */
        $termek = isset($this->galadLetezoTermekKulcs[$termekcikkszam])
            ? $termekrepo->findOneBy(['cikkszam' => $termekcikkszam])
            : null;
        if (!$termek) {
            // vonalkóddal felvitt terméket csak akkor, ha még nincs ilyen vonalkódú termék
            if ($termekVonalkod !== '' && isset($this->galadLetezoTermekVonalkod[$termekVonalkod])) {
                return ['termek' => 0, 'valtozat' => 0];
            }
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
     * Egy változat (TermekValtozat) létrehozása/frissítése szín + méret alapján.
     * A változat cikkszáma az F oszlop (önmagában egyedi). Az azonosítás elsődlegesen a
     * cikkszám, hiányában a vonalkód alapján történik.
     */
    private function galadImportValtozat($termek, $sor, $szinAdatTipus, $meretAdatTipus)
    {
        $vcikkszam = $sor['cikkszam'];

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
     * Termékfa csomópont keresése név (Z/kategória oszlop) alapján: pontos egyezés,
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
