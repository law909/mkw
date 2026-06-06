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
 * Galád termékimport az oxford-formátumú (megtisztított) XLSX-ből.
 *
 * Oszlopok: A=termékkód (csoportosítási kulcs), B=duplikált(0/1), C=változat cikkszám,
 * D=cikkszám alap (termék cikkszám), E=megnevezés, F=szín, G=méret, H=típus,
 * I=kisker ár, J=akciós ár, K=vonalkód.
 * Az azonos termékkódú sorok — akár nem összefüggő blokkokban is — egy termékhez tartoznak.
 */
class galadOxfordImportController extends \mkwhelpers\Controller
{

    private $galadSzinCache = [];
    private $galadMeretCache = [];
    private $galadTermekfaCache = [];

    /**
     * Termékimport futtatása a feltöltött XLSX alapján.
     *
     * - Az üres kisker árú (I oszlop) sorokat kihagyja.
     * - Az azonos termékkódú (A oszlop) sorok egy termékhez tartoznak; ha duplikált (B) == 1,
     *   ezekből szín/méret változatok jönnek létre.
     * - A típus (H oszlop) szövegével hasonló nevű termékfa csomópontot keres, ahhoz
     *   kapcsolja a terméket (termekfa1).
     * - A kisker árat (I) a "Kisker.ár", az akciós árat (J) az "Akciós ár" ársávba tölti
     *   (mindkét ársávot létrehozza, ha még nincs).
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

        // gyártó partner ("Oxford"), ha még nincs (szállító = 1)
        $gyarto = $this->getOrCreatePartner('OXFORD PRODUCT');

        // alapértelmezett valutanem
        $valutanem = \mkw\store::getEm()->getRepository(Valutanem::class)
            ->find(\mkw\store::getParameter(\mkw\consts::Valutanem));

        // mennyiségi egység (db)
        $me = $this->getOrCreateMe('db');

        // ársávok létrehozása, ha még nincsenek
        $kiskerArsav = $this->galadGetOrCreateArsav('Kisker.ár');
        $akciosArsav = $this->galadGetOrCreateArsav('Akciós ár');

        // változat adattípusok fix színmódhoz (szín / méret): ha nincsenek, létrehozzuk,
        // és az ID-jukat a paraméterek közé is beírjuk
        $szinAdatTipus = $this->getOrCreateAdatTipus(\mkw\consts::ValtozatTipusSzin, 'Szín');
        $meretAdatTipus = $this->getOrCreateAdatTipus(\mkw\consts::ValtozatTipusMeret, 'Méret');

        $termekdb = 0;
        $valtozatdb = 0;

        // A sorokat a termékkód (A oszlop) szerint csoportosítjuk: ugyanaz a termékkód
        // ugyanahhoz a termékhez tartozik akkor is, ha a sorok NEM összefüggőek
        // (pl. 4 db 28-as, majd 3 db 29-es, majd megint 4 db 28-as). Ezért az egész
        // fájlt beolvassuk egy kulcs szerinti tömbbe, és csak utána dolgozzuk fel.
        // Az üres sorok nem zavarnak be (egyszerűen kimaradnak a csoportokból).
        $groups = [];

        for ($row = $dbtol; $row <= $hardMax; ++$row) {
            $termekkod = trim((string)$sheet->getCell('A' . $row)->getValue());
            $duplikalt = trim((string)$sheet->getCell('B' . $row)->getValue());
            $vcikkszam = trim((string)$sheet->getCell('C' . $row)->getValue());
            $tcikkszam = trim((string)$sheet->getCell('D' . $row)->getValue());
            $megnevezes = trim((string)$sheet->getCell('E' . $row)->getValue());
            $szin = trim((string)$sheet->getCell('F' . $row)->getValue());
            $meret = trim((string)$sheet->getCell('G' . $row)->getValue());
            $tipus = trim((string)$sheet->getCell('H' . $row)->getValue());
            $kiskerAr = $sheet->getCell('I' . $row)->getValue();
            $akciosAr = $sheet->getCell('J' . $row)->getValue();
            $vonalkod = trim((string)$sheet->getCell('K' . $row)->getValue());

            $iUres = ($kiskerAr === null || trim((string)$kiskerAr) === '');
            $jUres = ($akciosAr === null || trim((string)$akciosAr) === '');

            // teljesen üres sor: az adatok végét egy hosszabb üres sorozat jelzi
            if ($termekkod === '' && $duplikalt === '' && $vcikkszam === '' && $tcikkszam === ''
                && $megnevezes === '' && $szin === '' && $meret === '' && $tipus === ''
                && $iUres && $jUres && $vonalkod === '') {
                if (!$dbig && ++$emptyRun >= $emptyLimit) {
                    break;
                }
                continue;
            }
            $emptyRun = 0;

            // az üres kisker árú (I oszlop) sorokat kihagyjuk
            if ($iUres) {
                continue;
            }

            // azonosító hiányában nem tudjuk csoportosítani / felvenni a sort
            if ($termekkod === '' && $tcikkszam === '') {
                continue;
            }

            // csoportosítási kulcs: elsődlegesen a termékkód (A), hiányában a cikkszám alap (D)
            $kulcs = $termekkod !== '' ? 'k:' . $termekkod : 'c:' . $tcikkszam;

            $groups[$kulcs][] = [
                'termekkod' => $termekkod,
                'valtozatos' => $duplikalt,
                'vcikkszam' => $vcikkszam,
                'tcikkszam' => $tcikkszam,
                'megnevezes' => $megnevezes,
                'szin' => $szin,
                'meret' => $meret,
                'tipus' => $tipus,
                'kisker' => $kiskerAr,
                'akcios' => $akciosAr,
                'vonalkod' => $vonalkod,
            ];
        }

        foreach ($groups as $group) {
            $res = $this->galadImportGroup($group, $afa, $vtsz, $gyarto, $me, $valutanem, $kiskerArsav, $akciosArsav, $szinAdatTipus, $meretAdatTipus);
            $termekdb += $res['termek'];
            $valtozatdb += $res['valtozat'];
        }

        echo 'Kész. ' . $termekdb . ' termék, ' . $valtozatdb . ' változat importálva.';
    }

    /**
     * Egy termékhez tartozó (azonos termékkódú) sorok feldolgozása.
     */
    private function galadImportGroup($group, $afa, $vtsz, $gyarto, $me, $valutanem, $kiskerArsav, $akciosArsav, $szinAdatTipus, $meretAdatTipus)
    {
        if (!$group) {
            return ['termek' => 0, 'valtozat' => 0];
        }
        $first = $group[0];
        $tcikkszam = $first['tcikkszam'];
        if ($tcikkszam === '') {
            return ['termek' => 0, 'valtozat' => 0];
        }

        $termekrepo = \mkw\store::getEm()->getRepository(Termek::class);
        /** @var Termek $termek */
        $termek = $termekrepo->findOneBy(['idegenkod' => $first['termekkod']]);
        if (!$termek) {
            $termek = new \Entities\Termek();
            $termek->setIdegenkod($first['termekkod']);
            $termek->setCikkszam($tcikkszam);
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
            $kategoria = $this->galadFindTermekfaByNev($first['tipus']);
            if ($kategoria) {
                $termek->setTermekfa1($kategoria);
            }
            \mkw\store::getEm()->persist($termek);

            $this->galadSetArsavAr($termek, $valutanem, $kiskerArsav, $first['kisker']);
            if ($first['akcios'] !== null && trim((string)$first['akcios']) !== '') {
                $this->galadSetArsavAr($termek, $valutanem, $akciosArsav, $first['akcios']);
            }
        }

        $valtozatdb = 0;
        // if ((string)$first['valtozatos'] === '1') {
        if (count($group) > 1) {
            foreach ($group as $sor) {
                if ($this->galadImportValtozat($termek, $sor, $szinAdatTipus, $meretAdatTipus)) {
                    $valtozatdb++;
                }
            }
        }

        // ha a terméknek nincs változata, a vonalkód magára a termékre kerül
        if ($valtozatdb === 0 && $first['vonalkod'] !== '') {
            $termek->setVonalkod($first['vonalkod']);
        }
        \mkw\store::getEm()->persist($termek);
        \mkw\store::getEm()->flush();

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
     */
    private function galadImportValtozat($termek, $sor, $szinAdatTipus, $meretAdatTipus)
    {
        $tvr = \mkw\store::getEm()->getRepository(TermekValtozat::class);
        $valtozat = null;
        if ($termek->getId() && $sor['vcikkszam'] !== '') {
            $valtozat = $tvr->findOneBy(['termek' => $termek->getId(), 'cikkszam' => $sor['vcikkszam']]);
        }
        if (!$valtozat) {
            $valtozat = new \Entities\TermekValtozat();
            $termek->addValtozat($valtozat);
        }
        if ($sor['vcikkszam'] !== '') {
            $valtozat->setCikkszam($sor['vcikkszam']);
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
            \mkw\store::getEm()->flush();
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
            \mkw\store::getEm()->flush();
        }
        $this->galadMeretCache[$nev] = $meret;
        return $meret;
    }

    /**
     * Termékfa csomópont keresése név (G/típus oszlop) alapján: pontos egyezés,
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

}
