<?php

namespace Controllers;

class mkwimportController extends \mkwhelpers\Controller {
    private function createMindentkapniGyarto($tomb) {
        if (!$tomb['nev']) {
            return null;
        }
        $gy = \mkw\store::getEm()->getRepository('Entities\Partner')->findByNev($tomb['nev']);
        $gyarto = false;
        if ($gy) {
            $gyarto = $gy[0];
        }
        if (!$gyarto) {
            $gyarto = new \Entities\Partner();
            $gyarto->setAdoszam($tomb['adoszam']);
            $gyarto->setHonlap($tomb['honlap']);
            $gyarto->setIrszam($tomb['irszam']);
            $gyarto->setUtca($tomb['utca']);
            $gyarto->setNev($tomb['nev']);
            $gyarto->setTelefon($tomb['telefon']);
            \mkw\store::getEm()->persist($gyarto);
        }
        return $gyarto;
    }

    private function createMindentkapniMarka($marka, $tomb) {
        $nev = $tomb[0];
        $filenev = $tomb[1];
        if (!$nev) {
            //$nev='Üres';
            return null;
        }
        $cimke1 = \mkw\store::getEm()->getRepository('Entities\Termekcimketorzs')->getByNevAndKategoria($nev, $marka);
        if (!$cimke1) {
            $cimke1 = new Entities\Termekcimketorzs();
            $cimke1->setKategoria($marka);
            $cimke1->setNev($nev);
//			$cimke1->setKepurl($filenev);
            \mkw\store::getEm()->persist($cimke1);
        }
        return $cimke1;
    }

    private function createMindentkapniJelzo($jelzo, $tomb) {
        $nev = $tomb[0];
        $filenev = $tomb[1];
        if (!$nev) {
            //$nev='Üres';
            return null;
        }
        $cimke1 = \mkw\store::getEm()->getRepository('Entities\Termekcimketorzs')->getByNevAndKategoria($nev, $jelzo);
        if (!$cimke1) {
            $cimke1 = new Entities\Termekcimketorzs();
            $cimke1->setKategoria($jelzo);
            $cimke1->setNev($nev);
//			$cimke1->setKepurl($filenev);
            \mkw\store::getEm()->persist($cimke1);
        }
        return $cimke1;
    }

    private function createMindentkapniTVAdatTipus($nev) {
        if (!$nev) {
            return null;
        }
        $at = \mkw\store::getEm()->getRepository('Entities\TermekValtozatAdatTipus')->findOneBy(array('nev' => $nev));
        if (!$at) {
            $at = new Entities\TermekValtozatAdatTipus();
            $at->setNev($nev);
            \mkw\store::getEm()->persist($at);
        }
        return $at;
    }

    private function cartesian($input) {
        $result = array();
        while (list($key, $values) = each($input)) {
            if (empty($values)) {
                continue;
            }
            if (empty($result)) {
                foreach ($values as $value) {
                    $result[] = array($key => $value);
                }
            }
            else {
                $append = array();
                foreach ($result as &$product) {
                    $product[$key] = array_shift($values);
                    $copy = $product;
                    foreach ($values as $item) {
                        $copy[$key] = $item;
                        $append[] = $copy;
                    }
                    array_unshift($values, $product[$key]);
                }
                $result = array_merge($result, $append);
                unset($append);
            }
        }
        return $result;
    }

    private function mindentkapnikep($ebbol) {
        $ebbe = $ebbol;
        $pp = pathinfo($ebbe);
        if (($pp['extension'] == 'gif') || ($pp['extension'] == 'png')) {
            $ebbe = str_replace('.' . $pp['extension'], '.jpg', $ebbe);
            copy(str_replace('.' . $pp['extension'], '_800_600.jpg', $ebbol), $ebbe);
        }
        return $ebbe;
    }

    protected function writelog($mit) {
        $x = fopen('mkw.log', 'a');
        $mit .= "\n";
        fwrite($x, $mit);
        fclose($x);
    }

    public function mindentkapnimegvasarlasdb() {
        if (file_exists('mkwimport.lock')) {
            echo 'locked';
            die;
        }

        $x = fopen('mkwimport.lock', 'a');
        fwrite($x, 'fuckerlocker');
        fclose($x);

        $import = fopen('toptermekek-id-db.csv', 'r');
        $data = fgetcsv($import, 0, ';', '"');
        while (($data = fgetcsv($import, 0, ';', '"')) !== false) {
            $this->writelog('megvasarlasdb: ' . trim($data[0]));
            $q = \mkw\store::getEm()->createQuery('UPDATE Entities\Termek x SET x.megvasarlasdb=' . trim($data[1]) . ' WHERE x.idegenkod=' . trim($data[0]) . '');
            $this->writelog($q->getSQL());
            $q->Execute();
        }
        fclose($import);
        unlink('mkwimport.lock');
        echo 'KESZ';
    }

    public function mindentkapnimegrendelesvevo() {
        if (file_exists('mkwimport.lock')) {
            echo 'locked';
            die;
        }

        $import = fopen('megrendeles2vevo.csv', 'r');
        $data = fgetcsv($import, 0, ';', '"');

        $darabolva = $this->params->getBoolRequestParam('darabolva', false);

        if ($darabolva) {
            $record = file_get_contents('mkwrecord.txt');
            $record = (int)$record;

            $vevotomb = array();

            $x = fopen('mkwimport.lock', 'a');
            fwrite($x, 'fuckerlocker');
            fclose($x);

            $vevocikl = 0;
            while ((($buffer = fgetcsv($import, 0, ';', '"')) != false) && ($vevocikl < $record)) {
                $vevocikl++;
            }
            $szam = $record;
        }
        else {
            $szam = 0;
            $record = 1000000000;
        }

        $conn = \mkw\store::getEm()->getConnection();

        while ((($data = fgetcsv($import, 0, ';', '"')) !== false) && ($szam <= $record + 1000)) {
            $this->writelog('1');
            $idegenkod = trim($data[0])*1;
            if ($idegenkod < 3000000) {
                $idegenkod = $idegenkod + 4000000;
            }
            $email = trim($data[9]);
            $q = \mkw\store::getEm()->createQuery('SELECT COUNT(x) FROM Entities\Partner x WHERE x.email=:e');
            $q->setParameters(array('e' => $email));
            $r = $q->getScalarResult();
            if ($r[0][1] == 0) {
                $this->writelog('2');
                $q = \mkw\store::getEm()->createQuery('SELECT COUNT(x) FROM Entities\Partner x WHERE x.idegenkod=:e');
                $q->setParameters(array('e' => $idegenkod));
                $r = $q->getScalarResult();
                if ($r[0][1] == 0) {
                    $this->writelog('3');
                    $this->writelog('szam=' . $szam . ' idegenkod=' . $idegenkod);
                    $szam++;

                    $q = 'INSERT INTO partner (idegenkod,vendeg,vezeteknev,keresztnev,email,nev,irszam,varos,utca,' .
                            'szallnev,szallirszam,szallvaros,szallutca,akcioshirlevelkell,ujdonsaghirlevelkell,telefon) VALUES ' .
                            '(:idegenkod,:vendeg,:vezeteknev,:keresztnev,:email,:nev,:irszam,:varos,:utca,' .
                            ':szallnev,:szallirszam,:szallvaros,:szallutca,:akcioshirlevelkell,:ujdonsaghirlevelkell,:telefon)';

                    $stmt = $conn->prepare($q);
                    $hirlevel = $data[22] == '1';
                    $params = array(
                        'idegenkod' => $idegenkod,
                        'vendeg' => true,
                        'vezeteknev' => mb_convert_encoding(trim($data[7]), 'UTF8', 'ISO-8859-2'),
                        'keresztnev' => mb_convert_encoding(trim($data[8]), 'UTF8', 'ISO-8859-2'),
                        'email' => $email,
                        'nev' => mb_convert_encoding(trim($data[10]), 'UTF8', 'ISO-8859-2'),
                        'irszam' => mb_convert_encoding(trim($data[12]), 'UTF8', 'ISO-8859-2'),
                        'varos' => mb_convert_encoding(trim($data[11]), 'UTF8', 'ISO-8859-2'),
                        'utca' => mb_convert_encoding(trim($data[13] . ' ' . $data[14]), 'UTF8', 'ISO-8859-2'),
                        'szallnev' => mb_convert_encoding(trim($data[15]), 'UTF8', 'ISO-8859-2'),
                        'szallirszam' => mb_convert_encoding(trim($data[17]), 'UTF8', 'ISO-8859-2'),
                        'szallvaros' => mb_convert_encoding(trim($data[16]), 'UTF8', 'ISO-8859-2'),
                        'szallutca' => mb_convert_encoding(trim($data[18] . ' ' . $data[19]), 'UTF8', 'ISO-8859-2'),
                        'akcioshirlevelkell' => $hirlevel,
                        'ujdonsaghirlevelkell' => $hirlevel,
                        'telefon' => mb_convert_encoding(trim($data[24]), 'UTF8', 'ISO-8859-2')
                    );
                    $stmt->executeStatement($params);
                }
            }
        }
        fclose($import);
        file_put_contents('mkwrecord.txt', $szam);
        $this->writelog('KESZ');
        echo 'KESZ';
        unlink('mkwimport.lock');
    }

    public function mindentkapnivevo() {
        if (file_exists('mkwimport.lock')) {
            echo 'locked';
            die;
        }

        $import = fopen('vevo.csv', 'r');
        $data = fgetcsv($import, 0, ';', '"');

        $darabolva = $this->params->getBoolRequestParam('darabolva', false);

        if ($darabolva) {
            $record = file_get_contents('mkwrecord.txt');
            $record = (int)$record;

            $vevotomb = array();

            $x = fopen('mkwimport.lock', 'a');
            fwrite($x, 'fuckerlocker');
            fclose($x);

            $vevocikl = 0;
            while ((($buffer = fgets($import, 4096)) != false) && ($vevocikl < $record)) {
                $vevocikl++;
            }
            $szam = $record;
        }
        else {
            $szam = 0;
            $record = 1000000000;
        }

        while ((($data = fgetcsv($import, 0, ';', '"')) !== false) && ($szam <= $record + 1000)) {
            $ppp = \mkw\store::getEm()->getRepository('Entities\Partner')->findByIdegenkod(trim($data[0]));
            if (!$ppp) {
                \mkw\store::writelog('szam=' . $szam . ' idegenkod=' . trim($data[0]));
                $szam++;
                $p = new Entities\Partner();
                $p->setIdegenkod(trim($data[0]));
                $p->setVezeteknev(mb_convert_encoding(trim($data[7]), 'UTF8', 'ISO-8859-2'));
                $p->setKeresztnev(mb_convert_encoding(trim($data[8]), 'UTF8', 'ISO-8859-2'));
                $p->setNev(mb_convert_encoding(trim($data[10]), 'UTF8', 'ISO-8859-2'));
                $p->setIrszam(mb_convert_encoding(trim($data[12]), 'UTF8', 'ISO-8859-2'));
                $p->setVaros(mb_convert_encoding(trim($data[11]), 'UTF8', 'ISO-8859-2'));
                $p->setUtca(mb_convert_encoding(trim($data[13] . ' ' . $data[14]), 'UTF8', 'ISO-8859-2'));
                $p->setSzallnev(mb_convert_encoding(trim($data[15]), 'UTF8', 'ISO-8859-2'));
                $p->setSzallirszam(mb_convert_encoding(trim($data[17]), 'UTF8', 'ISO-8859-2'));
                $p->setSzallvaros(mb_convert_encoding(trim($data[16]), 'UTF8', 'ISO-8859-2'));
                $p->setSzallutca(mb_convert_encoding(trim($data[18] . ' ' . $data[19]), 'UTF8', 'ISO-8859-2'));
                if ($data[22] == '1') {
                    $p->setAkcioshirlevelkell(true);
                    $p->setUjdonsaghirlevelkell(true);
                }
                else {
                    $p->setAkcioshirlevelkell(false);
                    $p->setUjdonsaghirlevelkell(false);
                }
                $p->setMegjegyzes(mb_convert_encoding(trim($data[23]), 'UTF8', 'ISO-8859-2'));
                $p->setTelefon(mb_convert_encoding(trim($data[24]), 'UTF8', 'ISO-8859-2'));
                $p->setOldloginname(trim($data[5]));
                $p->setMkwJelszo(trim($data[6]));
                $p->setEmail(trim($data[9]));
                \mkw\store::getEm()->persist($p);
                \mkw\store::getEm()->flush();
            }
        }
        fclose($import);
        file_put_contents('mkwrecord.txt', $szam);
        $this->writelog('KESZ');
        echo 'KESZ';
        unlink('mkwimport.lock');
    }

    public function mindentkapniimport() {

        if (file_exists('mkwimport.lock')) {
            echo 'locked';
            die;
        }

        $x = fopen('mkwimport.lock', 'a');
        fwrite($x, 'fuckerlocker');
        fclose($x);

        $record = file_get_contents('mkwrecord.txt');
        $record = (int)$record;

        $metomb = array();
        $import = fopen('rep_megyseg.csv', 'r');
        fgetcsv($import, 0, ';', '"');
        while (($data = fgetcsv($import, 0, ';', '"')) !== false) {
            $metomb[$data[0]] = mb_convert_encoding($data[5], 'UTF8', 'ISO-8859-2');
        }
        fclose($import);

        $nemkellmarka = array(
            'Best Body Nutrition',
            'Bremshey',
            'Buff',
            'Columbia',
            'Copag',
            'Daum Electronic',
            'Esso',
            'EURO SuperCompact',
            'Ezekiel',
            'Finnlo',
            'Gold Game',
            'Hammer',
            'Horizon Fitness',
            'Kensho',
            'Kettler',
            'Lando',
            'Life Fitness',
            'Mammut Nutrition',
            'Maranello',
            'Marcy',
            'Millenium',
            'MOL',
            'MrRoy',
            'Omni Fitness',
            'Pro Energy',
            'Quixoft',
            'Rapid',
            'Rebel',
            'Reebok',
            'Robust',
            'Stamm Bodyfit',
            'TAMA',
            'Technogym',
            'Texaco',
            'Thor Steinar',
            'Tunturi',
            'Veneziana',
            'Victorinox',
            'Vision Fitness',
            'Vital Force'
        );
        $markatomb = array();
        $import = fopen('marka.csv', 'r');
        fgetcsv($import, 0, ';', '"');
        while (($data = fgetcsv($import, 0, ';', '"')) !== false) {
//			$kepneve=$this->mindentkapnikep(mb_convert_encoding($data[6],'UTF8','ISO-8859-2'));
            if (!in_array($data[5], $nemkellmarka)) {
                $kepneve = '';
                $markatomb[$data[0]] = array(mb_convert_encoding($data[5], 'UTF8', 'ISO-8859-2'), $kepneve);
            }
        }
        fclose($import);

        $nemkellgyarto = array(
            'Dynamol',
            'GLS',
            'Gold Line Hungary Kft',
            'Hifly Distribution Kft',
            'JELKÉPZŐ',
            'Kormos',
            'Kunerth Károlyné Marcsi',
            'Kunertné',
            'Lovely Shop',
            'Mráz Tamás',
            'Peti',
            'Skolik Ágnes',
            'Smart Direkt Hungária Kft',
            'Tüske Bt',
            'Vital Force'
        );
        $gyartotomb = array();
        $import = fopen('partner.csv', 'r');
        fgetcsv($import, 0, ';', '"');
        while (($data = fgetcsv($import, 0, ';', '"')) !== false) {
            if (!in_array(mb_convert_encoding($data[5], 'UTF8', 'ISO-8859-2'), $nemkellgyarto)) {
                $gyartotomb[$data[0]] = array(
                    'nev' => mb_convert_encoding($data[5], 'UTF8', 'ISO-8859-2'),
                    'irszam' => $data[9],
                    'utca' => mb_convert_encoding($data[10], 'UTF8', 'ISO-8859-2'),
                    'adoszam' => $data[6],
                    'telefon' => $data[14],
                    'honlap' => $data[17]
                );
            }
        }

        $cimkecsoporttomb = array();
        $import = fopen('kategoria_attributum.csv', 'r');
        fgetcsv($import, 0, ';', '"');
        while (($data = fgetcsv($import, 0, ';', '"')) !== false) {
            $ker = mb_convert_encoding($data[6], 'UTF8', 'ISO-8859-2');
            $cimkecsoporttomb[$data[0]] = $ker;
            /* 			if (!array_key_exists($ker,$cimkecsoporttomb)) {
              $cimkecsoporttomb[$ker]=array();
              }
              $cimkecsoporttomb[$ker][]=$data[0];
             */
        }
        fclose($import);

        $jelzolista = array();
        $import = fopen('jelzo.csv', 'r');
        fgetcsv($import, 0, ';', '"');
        while (($data = fgetcsv($import, 0, ';', '"')) !== false) {
//			$kepneve=$this->mindentkapnikep(mb_convert_encoding($data[6],'UTF8','ISO-8859-2'));
            $kepneve = '';
            $jelzolista[$data[0]] = array(mb_convert_encoding($data[5], 'UTF8', 'ISO-8859-2'), $kepneve);
        }
        fclose($import);

        $jelzotomb = array();
        $import = fopen('termek_jelzo.csv', 'r');
        fgetcsv($import, 0, ';', '"');
        while (($data = fgetcsv($import, 0, ';', '"')) !== false) {
            $jelzotomb[$data[5]][] = $jelzolista[$data[6]];
        }
        fclose($import);

        $termekcimketomb = array();
        $import = fopen('termek_attributum.csv', 'r');
        fgetcsv($import, 0, ';', '"');
        while (($data = fgetcsv($import, 0, ';', '"')) !== false) {
            $adat = '';
            if ($data[7] !== '') {
                $adat = $data[7];
            }
            if ($data[8] !== '') {
                $adat = $data[8];
            }
            if ($data[9] !== '') {
                $adat = $data[9];
            }
            if ($data[10] !== '') {
                $adat = $data[10];
            }
            if ($adat !== '') {
                $termekcimketomb[$data[5]][] = array($cimkecsoporttomb[$data[6]], mb_convert_encoding($adat, 'UTF8', 'ISO-8859-2'));
            }
        }
        fclose($import);
        unset($cimkecsoporttomb);

        /* 		$keptomb=array();
          $import=fopen('termek_kep.csv','r');
          fgetcsv($import,0,';','"');
          while(($data=fgetcsv($import,0,';','"'))!==false) {
          $kepneve=$this->mindentkapnikep(mb_convert_encoding($data[7],'UTF8','ISO-8859-2'));
          $keptomb[$data[5]][]=array(mb_convert_encoding($data[6],'UTF8','ISO-8859-2'),$kepneve);
          }
          fclose($import);
         */
        $termekvertek = array();
        $import = fopen('termek_valtozat_ertek.csv', 'r');
        fgetcsv($import, 0, ';', '"');
        while (($data = fgetcsv($import, 0, ';', '"')) !== false) {
            $termekvertek[$data[5]][] = array(
                'nev' => mb_convert_encoding($data[6], 'UTF8', 'ISO-8859-2'),
                'elerheto' => (int)$data[7]
            );
        }
        fclose($import);

        $termekvaltozattomb = array();
        $import = fopen('termek_valtozat.csv', 'r');
        fgetcsv($import, 0, ';', '"');
        while (($data = fgetcsv($import, 0, ';', '"')) !== false) {
            $termekvaltozattomb[$data[6]][mb_convert_encoding($data[5], 'UTF8', 'ISO-8859-2')] = $termekvertek[$data[0]];
        }
        fclose($import);

        unset($termekvertek);

        foreach ($termekvaltozattomb as $vari) {
            foreach ($vari as $k => $v) {
                $this->createMindentkapniTVAdatTipus($k);
                \mkw\store::getEm()->flush();
            }
        }

        $markakat = \mkw\store::getEm()->getRepository('Entities\Termekcimkekat')->findOneBynev('Márkák');
        if (!$markakat) {
            $markakat = new Entities\Termekcimkekat();
            $markakat->setLathato(true);
            $markakat->setNev('Márkák');
            \mkw\store::getEm()->persist($markakat);
        }
        $jelzokat = \mkw\store::getEm()->getRepository('Entities\Termekcimkekat')->findOneBynev('Jelzők');
        if (!$jelzokat) {
            $jelzokat = new Entities\Termekcimkekat();
            $jelzokat->setLathato(true);
            $jelzokat->setNev('Jelzők');
            \mkw\store::getEm()->persist($jelzokat);
        }
        $vtsz = \mkw\store::getEm()->getRepository('Entities\Vtsz')->find(2);
        $afa = \mkw\store::getEm()->getRepository('Entities\Afa')->find(3);
        $valuta = \mkw\store::getEm()->getRepository('Entities\Valutanem')->find(1);

        $import = fopen('termek.csv', 'r');
        $termekcikl = 0;
        while ((($buffer = fgets($import, 4096)) != false) && ($termekcikl < $record)) {
            $termekcikl++;
        }
        $szam = $record;
        while ((($data = fgetcsv($import, 0, ';', '"')) !== false) && ($szam <= $record + 400)) {
            $szam++;
            if ((array_key_exists($data[29], $markatomb) || (!$data[29])) && array_key_exists($data[9], $gyartotomb)) {
//			if (true) {
                $termek = new Entities\Termek();
                $termek->setIdegenkod($data[0]);
                $kat = \mkw\store::getEm()->getRepository('Entities\TermekFa')->find($data[5]);
                if ($kat) {
                    $termek->setTermekfa1($kat);
                }
                $termek->setNev(mb_convert_encoding($data[6], 'UTF8', 'ISO-8859-2'));
                $termek->setRovidleiras(mb_substr(mb_convert_encoding($data[7], 'UTF8', 'ISO-8859-2'), 0, 255, 'UTF-8'));
                $termek->setLeiras(mb_convert_encoding($data[8], 'UTF8', 'ISO-8859-2'));
                $termek->setCikkszam(mb_convert_encoding($data[10], 'UTF8', 'ISO-8859-2'));
                //			$kepneve=$this->mindentkapnikep(mb_convert_encoding($data[11],'UTF8','ISO-8859-2'));
                //			$termek->setKepurl($kepneve);
                $termek->setKiszereles($data[12]);
                $termek->setMe($metomb[$data[13]]);
                $termek->setSzelesseg($data[14]);
                $termek->setMagassag($data[15]);
                $termek->setHosszusag($data[16]);
                $termek->setOsszehajthato($data[17]);
                $termek->setSuly($data[18]);
                $termek->setHparany(ceil($data[19] / $data[28] * 100));

                $termek->setAfa($afa);
                $termek->setAjanlott(false);
                $termek->setHozzaszolas(true);
                $termek->setInaktiv(false);
                $termek->setLathato(true);
                $termek->setMozgat(true);
                $termek->setVtsz($vtsz);

                $marka = $this->createMindentkapniMarka($markakat, $markatomb[$data[29]]);
                if ($marka) {
                    $termek->addCimke($marka);
                }

                $gyarto = $this->createMindentkapniGyarto($gyartotomb[$data[9]]);
                if ($gyarto) {
                    $termek->setGyarto($gyarto);
                }

                $termek->setBrutto($data[28]);

                /*
                  if (array_key_exists($data[0],$keptomb)) {
                  foreach($keptomb[$data[0]] as $kadat) {
                  $tk=new Entities\TermekKep();
                  $termek->addTermekKep($tk);
                  $tk->setLeiras($kadat[0]);
                  $tk->setUrl($kadat[1]);
                  \mkw\store::getEm()->persist($tk);
                  }
                  }
                 */
                if (array_key_exists($data[0], $jelzotomb)) {
                    foreach ($jelzotomb[$data[0]] as $kadat) {
                        $jelzo = $this->createMindentkapniJelzo($jelzokat, $kadat);
                        if ($jelzo) {
                            $termek->addCimke($jelzo);
                        }
                    }
                }

                if (array_key_exists($data[0], $termekcimketomb)) {
                    foreach ($termekcimketomb[$data[0]] as $cadat) {
                        $ckat = \mkw\store::getEm()->getRepository('Entities\Termekcimkekat')->findOneBynev($cadat[0]);
                        if (!$ckat) {
                            $ckat = new Entities\Termekcimkekat();
                            $ckat->setLathato(true);
                            $ckat->setNev($cadat[0]);
                            \mkw\store::getEm()->persist($ckat);
                        }
                        $cimkex = \mkw\store::getEm()->getRepository('Entities\Termekcimketorzs')->getByNevAndKategoria($cadat[1], $ckat);
                        if (!$cimkex) {
                            $cimkex = new Entities\Termekcimketorzs();
                            $cimkex->setKategoria($ckat);
                            $cimkex->setNev($cadat[1]);
                            \mkw\store::getEm()->persist($cimkex);
                        }
                        $termek->addCimke($cimkex);
                    }
                }

                if (array_key_exists($data[0], $termekvaltozattomb)) {
//					$termek->setLathato(false);
                    $valtozattomb = $this->cartesian($termekvaltozattomb[$data[0]]);
                    foreach ($valtozattomb as $vari) {
                        //$valt=new Entities\TermekValtozat();
                        //$valt->setTermek($termek);
                        $cnt = 0;
                        $valt = new Entities\TermekValtozat();
                        $valt->setTermek($termek);
                        $valt->setLathato(false);
//						$valt->setBrutto($data[28]);
//						$valt->setCikkszam(mb_convert_encoding($data[10],'UTF8','ISO-8859-2'));
                        $elerheto = true;
                        foreach ($vari as $k => $v) {
                            if ($cnt == 0) {
                                $adatt = $this->createMindentkapniTVAdatTipus($k);
                                $valt->setAdatTipus1($adatt);
                                $valt->setErtek1($v['nev']);
                                $elerheto = $elerheto && $v['elerheto'];
                            }
                            if ($cnt == 1) {
                                $adatt = $this->createMindentkapniTVAdatTipus($k);
                                $valt->setAdatTipus2($adatt);
                                $valt->setErtek2($v['nev']);
                                $elerheto = $elerheto && $v['elerheto'];
                            }
                            $cnt++;
                        }
                        $valt->setElerheto($elerheto);
                        \mkw\store::getEm()->persist($valt);
                    }
                    unset($valtozattomb);
                }

                \mkw\store::getEm()->persist($termek);
                \mkw\store::getEm()->flush();
                unset($termek);
                $this->writelog($data[0]);
            }
        }
        fclose($import);
        $this->regeneratekarkod();
        file_put_contents('mkwrecord.txt', $szam);
        $this->writelog('KESZ');
        unlink('mkwimport.lock');
    }

}