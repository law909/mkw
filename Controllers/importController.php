<?php
namespace Controllers;
use mkw\store, mkw\thumbnail;

class importController extends \mkwhelpers\Controller {

    private function toutf($mit) {
        return mb_convert_encoding($mit, 'UTF8', 'ISO-8859-2');
    }

    private function n($mit) {
        return ord($mit) - 97;
    }

    public function view() {
        $view = $this->createView('imports.tpl');

        $view->setVar('pagetitle', t('Importok'));
        $view->setVar('path', \mkw\Store::getConfigValue('path.termekkep'));

        $gyarto = new partnerController($this->params);
        $view->setVar('gyartolist', $gyarto->getSzallitoSelectList(0));

        $view->printTemplateResult(false);
    }

    public function createKategoria($nev, $parentid) {
        $me = store::getEm()->getRepository('Entities\TermekFa')->findBy(array('nev' => $nev, 'parent' => $parentid));

        if (!$me || $me[0]->getParentId() !== $parentid) {
            $parent = store::getEm()->getRepository('Entities\TermekFa')->find($parentid);
            $me = new \Entities\TermekFa();
            $me->setNev($nev);
            $me->setParent($parent);
            $me->setMenu1lathato(false);
            $me->setMenu2lathato(false);
            $me->setMenu3lathato(false);
            $me->setMenu4lathato(false);
            store::getEm()->persist($me);
            store::getEm()->flush();
        }
        else {
            $me = $me[0];
        }
        return $me;
    }

    public function getKategoriaByIdegenkod($ik) {
        $me = store::getEm()->getRepository('Entities\TermekFa')->findBy(array('idegenkod' => $ik));
        if (!$me) {
            $me = store::getEm()->getRepository('Entities\TermekFa')->find(1);
        }
        return $me[0];
    }

    public function createTermekValtozatAdatTipus($nev) {
        $res = store::getEm()->getRepository('Entities\TermekValtozatAdatTipus')->findByNev($nev);
        if (!$res) {
            $res = new \Entities\TermekValtozatAdatTipus();
            $res->setNev($nev);
            store::getEm()->persist($res);
            store::getEm()->flush();
            return $res;
        }
        return $res[0];
    }

    public function createVtsz($szam, $afa) {
        $res = store::getEm()->getRepository('Entities\Vtsz')->findBySzam($szam);
        if (!$res) {
            $res = new \Entities\Vtsz();
            $res->setSzam($szam);
            $res->setAfa($afa);
            store::getEm()->persist($res);
            store::getEm()->flush();
            return $res;
        }
        return $res[0];
    }

    public function kreativpuzzleImport() {
        $sep = ';';

        $parentid = $this->params->getIntRequestParam('katid', 0);
        $gyartoid = $this->params->getIntRequestParam('gyarto', 0);
        $dbtol = $this->params->getIntRequestParam('dbtol', 0);
        $dbig = $this->params->getIntRequestParam('dbig', 0);
        $editleiras = $this->params->getBoolRequestParam('editleiras', false);
        $createuj = $this->params->getBoolRequestParam('createuj', false);
        $arszaz = $this->params->getNumRequestParam('arszaz', 100);

        $urleleje = \mkw\Store::changeDirSeparator($this->params->getStringRequestParam('path', \mkw\Store::getConfigValue('path.termekkep')));

        $path = \mkw\Store::changeDirSeparator($this->params->getStringRequestParam('path', \mkw\Store::getConfigValue('path.termekkep')));
        $mainpath = \mkw\Store::changeDirSeparator(\mkw\Store::getConfigValue('mainpath'));
        if ($mainpath) {
            $mainpath = rtrim($mainpath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        }
        $path = $mainpath . $path;
        $path = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $urleleje = rtrim($urleleje, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;


        $ch = \curl_init('http://kreativpuzzle.hu/lawstocklist/');
        $fh = fopen('kreativpuzzlestock.txt', 'w');
        \curl_setopt($ch, CURLOPT_FILE, $fh);
        \curl_exec($ch);
        fclose($fh);

        $ch = \curl_init('http://kreativpuzzle.hu/lawstocklist/images.php');
        $fh = fopen('kreativpuzzleimages.txt', 'w');
        \curl_setopt($ch, CURLOPT_FILE, $fh);
        \curl_exec($ch);
        fclose($fh);

        $imagelist = array();
        $fh = fopen('kreativpuzzleimages.txt', 'r');
        if ($fh) {
            fgetcsv($fh, 0, $sep, '"');
            while ($data = fgetcsv($fh, 0, $sep, '"')) {
                if ($data[$this->n('a')]) {
                    if (array_key_exists($data[$this->n('a')], $imagelist)) {
                        $imagelist[$data[$this->n('a')]][] = $data[$this->n('b')];
                    }
                    else {
                        $imagelist[$data[$this->n('a')]] = array($data[$this->n('b')]);
                    }
                }
            }
        }
        fclose($fh);

        $fh = fopen('kreativpuzzlestock.txt', 'r');
        if ($fh) {
            $settings = array(
                'quality'=>80,
                'sizes'=>array('100'=>'100x100','150'=>'150x150','250'=>'250x250','1000'=>'1000x800')
            );
            $afa = store::getEm()->getRepository('Entities\Afa')->findByErtek(27);
            $vtsz = store::getEm()->getRepository('Entities\Vtsz')->findByNev('-');
            $gyarto = store::getEm()->getRepository('Entities\Partner')->find($gyartoid);
            $termekdb = 0;
            fgetcsv($fh, 0, $sep, '"');
            while (($termekdb < $dbtol) && ($data = fgetcsv($fh, 0, $sep, '"'))) {
                $termekdb++;
            }
            while ((($dbig && ($termekdb < $dbig)) || (!$dbig)) && ($data = fgetcsv($fh, 0, $sep, '"'))) {
                $termekdb++;
                if ($data[$this->n('c')] * 1 > 0) {
                    $termek = store::getEm()->getRepository('Entities\Termek')->findByIdegenkod('KP' . $data[$this->n('a')]);
                    if (!$termek) {

                        if ($createuj) {
                            $katnev = $this->toutf(trim($data[$this->n('h')]));
                            $urlkatnev = \mkw\Store::urlize($katnev);
                            \mkw\Store::createDirectoryRecursively($path . $urlkatnev);
                            $parent = $this->createKategoria($katnev, $parentid);
                            $termeknev = $this->toutf(trim($data[$this->n('b')]));

                            $hosszuleiras = $this->toutf(trim($data[$this->n('n')]));
                            $rovidleiras = $this->toutf(trim($data[$this->n('e')]));

                            $idegenkod = 'KP' . $data[$this->n('a')];

                            $termek = new \Entities\Termek();
                            $termek->setFuggoben(true);
                            $termek->setMe('db');
                            $termek->setNev($termeknev);
                            $termek->setLeiras($hosszuleiras);
                            $termek->setRovidleiras(mb_substr($rovidleiras, 0, 100, 'UTF8') . '...');
                            $termek->setCikkszam($data[$this->n('a')]);
                            $termek->setIdegencikkszam($data[$this->n('a')]);
                            $termek->setIdegenkod($idegenkod);
                            $termek->setTermekfa1($parent);
                            $termek->setVtsz($vtsz[0]);
                            $termek->setHparany(3);
                            if ($gyarto) {
                                $termek->setGyarto($gyarto);
                            }
                            // kepek
                            if (array_key_exists($data[$this->n('a')], $imagelist)) {
                                $imgcnt = 0;
                                foreach ($imagelist[$data[$this->n('a')]] as $imgurl) {
                                    $imgcnt++;

                                    $nameWithoutExt = $path . $urlkatnev . DIRECTORY_SEPARATOR . \mkw\Store::urlize($termeknev . '_' . $idegenkod);
                                    $kepnev = \mkw\Store::urlize($termeknev . '_' . $idegenkod);
                                    if (count($imagelist[$data[$this->n('a')]]) > 1) {
                                        $nameWithoutExt = $nameWithoutExt . '_' . $imgcnt;
                                        $kepnev = $kepnev . '_' . $imgcnt;
                                    }

                                    $extension = \mkw\Store::getExtension($imgurl);
                                    $imgpath = $nameWithoutExt . '.' . $extension;

                                    $ch = \curl_init($imgurl);
                                    $ih = fopen($imgpath, 'w');
                                    \curl_setopt($ch, CURLOPT_FILE, $ih);
                                    \curl_exec($ch);
                                    fclose($ih);

                                    foreach ($settings['sizes'] as $k=>$size) {
                                            $newFilePath = $nameWithoutExt."_".$k.".".$extension;
                                            $matches=explode('x',$size);
                                            \mkw\thumbnail::createThumb($imgpath, $newFilePath, $matches[0]*1, $matches[1]*1, $settings['quality'], true) ;
                                    }
                                    if (((count($imagelist[$data[$this->n('a')]]) > 1) && ($imgcnt == 1)) || (count($imagelist[$data[$this->n('a')]]) == 1)) {
                                        $termek->setKepurl($urleleje . $urlkatnev . DIRECTORY_SEPARATOR . $kepnev . '.' . $extension);
                                        $termek->setKepleiras($termeknev);
                                    }
                                    else {
                                        $kep = new \Entities\TermekKep();
                                        $termek->addTermekKep($kep);
                                        $kep->setUrl($urleleje . $urlkatnev . DIRECTORY_SEPARATOR . $kepnev . '.' . $extension);
                                        $kep->setLeiras($termeknev);
                                        store::getEm()->persist($kep);
                                    }
                                }
                            }
                        }
                    }
                    else {
                        $termek = $termek[0];
                        if ($editleiras) {
                            $hosszuleiras = $this->toutf(trim($data[$this->n('n')]));
                            $termek->setLeiras($hosszuleiras);
                            //$rovidleiras = mb_convert_encoding(trim($data[4]), 'UTF8', 'ISO-8859-2');
                            //$termek->setRovidleiras(mb_substr($rovidleiras, 0, 100, 'UTF8') . '...');
                        }
                    }
                    if ($termek) {
                        $termek->setNemkaphato(($data[$this->n('g')] * 1) == 0);
                        //$termek->setAfa($afa[0]);
                        $termek->setNetto($data[$this->n('d')] * 1 * $arszaz / 100);
                        $termek->setBrutto(round($termek->getBrutto(), -1));
                        store::getEm()->persist($termek);
                        store::getEm()->flush();
                    }
                }
                else {
                    $termek = store::getEm()->getRepository('Entities\Termek')->findByIdegenkod('KP' . $data[$this->n('a')]);
                    if ($termek) {
                        $termek = $termek[0];
                        $termek->setNemkaphato(true);
                        $termek->setLathato(false);
                        store::getEm()->persist($termek);
                        store::getEm()->flush();
                    }
                }
            }
        }
        fclose($fh);
    }

    private function fgetdeltoncsv($handle) {
        $escaped = false;
        $enclosed = false;
        $vege = false;
        $str = '';
        while (!$vege && (false !== ($char = fgetc($handle)))) {
            switch ($char) {
                case "\n":
                    if ($escaped || $enclosed) {
                        $str .= $char;
                    }
                    else {
                        $vege = true;
                    }
                    break;
                case '"':
                    if (!$escaped) {
                        $enclosed = !$enclosed;
                    }
                    $str .= $char;
                    break;
                default:
                    $str .= $char;
                    break;
            }
            $escaped = ($char == '\\');
        }
        return str_getcsv($str, chr(163), '"');
    }

    public function deltonImport() {

        $parentid = $this->params->getIntRequestParam('katid', 0);
        $gyartoid = $this->params->getIntRequestParam('gyarto', 0);
        $dbtol = $this->params->getIntRequestParam('dbtol', 0);
        $dbig = $this->params->getIntRequestParam('dbig', 0);
        $editleiras = $this->params->getBoolRequestParam('editleiras', false);
        $createuj = $this->params->getBoolRequestParam('createuj', false);
        $arszaz = $this->params->getNumRequestParam('arszaz', 100);
        $deltondownload = $this->params->getBoolRequestParam('deltondownload',false);

        $urleleje = \mkw\Store::changeDirSeparator($this->params->getStringRequestParam('path', \mkw\Store::getConfigValue('path.termekkep')));

        $path = \mkw\Store::changeDirSeparator($this->params->getStringRequestParam('path', \mkw\Store::getConfigValue('path.termekkep')));
        $mainpath = \mkw\Store::changeDirSeparator(\mkw\Store::getConfigValue('mainpath'));
        if ($mainpath) {
            $mainpath = rtrim($mainpath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        }
        $path = $mainpath . $path;
        $path = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $urleleje = rtrim($urleleje, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

        if ($deltondownload) {
            \unlink('delton.txt');
            $ch = \curl_init('http://delton.hu/csv.php?verzio=2&jelszo=ORJ194');
            $fh = fopen('delton.txt', 'w');
            \curl_setopt($ch, CURLOPT_FILE, $fh);
            \curl_exec($ch);
            fclose($fh);
        }
        $fh = fopen('delton.txt', 'r');
        if ($fh) {
            $settings = array(
                'quality'=>80,
                'sizes'=>array('100'=>'100x100','150'=>'150x150','250'=>'250x250','1000'=>'1000x800')
            );
            $afa = store::getEm()->getRepository('Entities\Afa')->findByErtek(27);
            $vtsz = store::getEm()->getRepository('Entities\Vtsz')->findByNev('-');
            $gyarto = store::getEm()->getRepository('Entities\Partner')->find($gyartoid);
            $termekdb = 0;
            $this->fgetdeltoncsv($fh);
            while (($termekdb < $dbtol) && ($data = $this->fgetdeltoncsv($fh))) {
                $termekdb++;
            }
            while ((($dbig && ($termekdb < $dbig)) || (!$dbig)) && ($data = $this->fgetdeltoncsv($fh))) {
                $termekdb++;
                if ($data[1]) {
                    $termek = store::getEm()->getRepository('Entities\Termek')->findByIdegenkod('DT' . $data[1]);
                    if (!$termek) {

                        if ($createuj) {
                            if ($data[6]) {
                                $katnev = $this->toutf(trim($data[6]));
                            }
                            elseif ($data[5]) {
                                $katnev = $this->toutf(trim($data[5]));
                            }
                            elseif ($data[4]) {
                                $katnev = $this->toutf(trim($data[4]));
                            }
                            $urlkatnev = \mkw\Store::urlize($katnev);
                            \mkw\Store::createDirectoryRecursively($path . $urlkatnev);
                            $parent = $this->createKategoria($katnev, $parentid);
                            $termeknev = $this->toutf(trim($data[0]));

                            $hosszuleiras = $this->toutf(trim($data[3]));
                            $rovidleiras = $this->toutf(trim($data[2]));

                            $idegenkod = 'DT' . $data[1];

                            $termek = new \Entities\Termek();
                            $termek->setFuggoben(true);
                            $termek->setMe($this->toutf(trim($data[9])));
                            $termek->setNev($termeknev);
                            $termek->setLeiras($hosszuleiras);
                            $termek->setRovidleiras(mb_substr($rovidleiras, 0, 100, 'UTF8') . '...');
                            $termek->setCikkszam($data[1]);
                            $termek->setIdegencikkszam($data[1]);
                            $termek->setIdegenkod($idegenkod);
                            $termek->setTermekfa1($parent);
                            $termek->setVtsz($vtsz[0]);
                            $termek->setHparany(1);
                            if ($gyarto) {
                                $termek->setGyarto($gyarto);
                            }
                            // kepek

                            $imgurl = trim($data[14]);
                            if (!strpos($imgurl, 'http://')) {
                                $imgurl = 'http://' . $imgurl;
                            }
                            $nameWithoutExt = $path . $urlkatnev . DIRECTORY_SEPARATOR . \mkw\Store::urlize($termeknev . '_' . $idegenkod);
                            $kepnev = \mkw\Store::urlize($termeknev . '_' . $idegenkod);

                            $extension = \mkw\Store::getExtension($imgurl);
                            $imgpath = $nameWithoutExt . '.' . $extension;

                            $ch = \curl_init($imgurl);
                            $ih = fopen($imgpath, 'w');
                            \curl_setopt($ch, CURLOPT_FILE, $ih);
                            \curl_exec($ch);
                            fclose($ih);

                            foreach ($settings['sizes'] as $k=>$size) {
                                    $newFilePath = $nameWithoutExt . "_" . $k . "." . $extension;
                                    $matches = explode('x',$size);
                                    \mkw\thumbnail::createThumb($imgpath, $newFilePath, $matches[0]*1, $matches[1]*1, $settings['quality'], true) ;
                            }
                            $termek->setKepurl($urleleje . $urlkatnev . DIRECTORY_SEPARATOR . $kepnev . '.' . $extension);
                            $termek->setKepleiras($termeknev);
                        }
                    }
                    else {
                        $termek = $termek[0];
                        if ($editleiras) {
                            $hosszuleiras = $this->toutf(trim($data[3]));
                            $termek->setLeiras($hosszuleiras);
                            //$rovidleiras = mb_convert_encoding(trim($data[4]), 'UTF8', 'ISO-8859-2');
                            //$termek->setRovidleiras(mb_substr($rovidleiras, 0, 100, 'UTF8') . '...');
                        }
                    }
                    //$termek->setNemkaphato(($data[6] * 1) == 0);
                    if ($termek) {
                        //$termek->setAfa($afa[0]);
                        if (substr($data[11],-6) == 'rkezik') {
                            $termek->setNemkaphato(true);
                        }
                        else {
                            $termek->setNemkaphato(false);
                        }
                        $kiskerar = $data[7] * 1;
                        $nagykerar = $data[8] * 1;
                        if (($kiskerar / $nagykerar * 100 < 115) || ($kiskerar / ($nagykerar * $arszaz / 100) * 100 < 115)) {
                            $termek->setNetto($nagykerar * 115 / 100);
                        }
                        else {
                            $termek->setNetto($kiskerar * $arszaz / 100);
                        }
                        $termek->setBrutto(round($termek->getBrutto(), -1));
                        store::getEm()->persist($termek);
                        store::getEm()->flush();
                    }
                }
            }
        }
        fclose($fh);
    }

    public function nomadImport() {

        $parentid = $this->params->getIntRequestParam('katid', 0);
        $gyartoid = $this->params->getIntRequestParam('gyarto', 0);
        $dbtol = $this->params->getIntRequestParam('dbtol', 0);
        $dbig = $this->params->getIntRequestParam('dbig', 0);
        $editleiras = $this->params->getBoolRequestParam('editleiras', false);
        $createuj = $this->params->getBoolRequestParam('createuj', false);
        $arszaz = $this->params->getNumRequestParam('arszaz', 100);

        $urleleje = \mkw\Store::changeDirSeparator($this->params->getStringRequestParam('path', \mkw\Store::getConfigValue('path.termekkep')));

        $path = \mkw\Store::changeDirSeparator($this->params->getStringRequestParam('path', \mkw\Store::getConfigValue('path.termekkep')));
        $mainpath = \mkw\Store::changeDirSeparator(\mkw\Store::getConfigValue('mainpath'));
        if ($mainpath) {
            $mainpath = rtrim($mainpath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        }
        $path = $mainpath . $path;
        $path = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $urleleje = rtrim($urleleje, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;


        $ch = \curl_init('http://www.nomadsport.eu/upload/stocks/nomadsport_6.xml');
        $fh = fopen('nomad.xml', 'w');
        \curl_setopt($ch, CURLOPT_FILE, $fh);
        \curl_exec($ch);
        fclose($fh);

        $xml = simplexml_load_file(temppath."nomad.xml");
        if ($xml) {
            $settings = array(
                'quality'=>80,
                'sizes'=>array('100'=>'100x100','150'=>'150x150','250'=>'250x250','1000'=>'1000x800')
            );
            $afa = store::getEm()->getRepository('Entities\Afa')->findByErtek(27);
            $vtsz = store::getEm()->getRepository('Entities\Vtsz')->findByNev('-');
            $gyarto = store::getEm()->getRepository('Entities\Partner')->find($gyartoid);
            $termekdb = 0;

            $products = $xml->products;

            while (($termekdb < $dbtol)) {
                $termekdb++;
            }
            while ((($dbig && ($termekdb < $dbig)) || (!$dbig))) {
                $data = $products[$termekdb];
                $termekdb++;
                if ($data->sku) {
                    $termek = store::getEm()->getRepository('Entities\Termek')->findBy(array('cikkszam' => $data->catalog_first, 'idegencikkszam' => $data->sku));
                    if (!$termek) {

                        if ($createuj) {

                            if ($data[6]) {
                                $katnev = $this->toutf(trim($data[6]));
                            }
                            elseif ($data[5]) {
                                $katnev = $this->toutf(trim($data[5]));
                            }
                            elseif ($data[4]) {
                                $katnev = $this->toutf(trim($data[4]));
                            }
                            $urlkatnev = \mkw\Store::urlize($katnev);
                            \mkw\Store::createDirectoryRecursively($path . $urlkatnev);
                            $parent = $this->createKategoria($katnev, $parentid);
                            $termeknev = $this->toutf(trim($data[0]));

                            $hosszuleiras = $this->toutf(trim($data[3]));
                            $rovidleiras = $this->toutf(trim($data[2]));

                            $termek = new \Entities\Termek();
                            $termek->setFuggoben(true);
                            $termek->setMe($this->toutf(trim($data[9])));
                            $termek->setNev($termeknev);
                            $termek->setLeiras($hosszuleiras);
                            $termek->setRovidleiras(mb_substr($rovidleiras, 0, 100, 'UTF8') . '...');
                            $termek->setCikkszam($data[1]);
                            $termek->setIdegencikkszam($data[1]);
                            $termek->setTermekfa1($parent);
                            $termek->setVtsz($vtsz[0]);
                            $termek->setHparany(3);
                            if ($gyarto) {
                                $termek->setGyarto($gyarto);
                            }
                            // kepek

                            $imgurl = trim($data[14]);
                            if (!strpos($imgurl, 'http://')) {
                                $imgurl = 'http://' . $imgurl;
                            }
                            $nameWithoutExt = $path . $urlkatnev . DIRECTORY_SEPARATOR . \mkw\Store::urlize($termeknev . '_' . $idegenkod);
                            $kepnev = \mkw\Store::urlize($termeknev . '_' . $idegenkod);

                            $extension = \mkw\Store::getExtension($imgurl);
                            $imgpath = $nameWithoutExt . '.' . $extension;

                            $ch = \curl_init($imgurl);
                            $ih = fopen($imgpath, 'w');
                            \curl_setopt($ch, CURLOPT_FILE, $ih);
                            \curl_exec($ch);
                            fclose($ih);

                            foreach ($settings['sizes'] as $k=>$size) {
                                    $newFilePath = $nameWithoutExt . "_" . $k . "." . $extension;
                                    $matches = explode('x',$size);
                                    \mkw\thumbnail::createThumb($imgpath, $newFilePath, $matches[0]*1, $matches[1]*1, $settings['quality'], true) ;
                            }
                            $termek->setKepurl($urleleje . $urlkatnev . DIRECTORY_SEPARATOR . $kepnev . '.' . $extension);
                            $termek->setKepleiras($termeknev);
                        }
                    }
                    else {
                        $termek = $termek[0];
                        if ($editleiras) {
                            $hosszuleiras = $this->toutf(trim($data[3]));
                            $termek->setLeiras($hosszuleiras);
                            //$rovidleiras = mb_convert_encoding(trim($data[4]), 'UTF8', 'ISO-8859-2');
                            //$termek->setRovidleiras(mb_substr($rovidleiras, 0, 100, 'UTF8') . '...');
                        }
                    }
                    //$termek->setNemkaphato(($data[6] * 1) == 0);
                    if ($termek || $createuj) {
                        $termek->setAfa($afa[0]);
                        $termek->setNetto($data[7] * 1 * $arszaz / 100);
                        $termek->setBrutto(round($termek->getBrutto(), -1));
                        store::getEm()->persist($termek);
                        store::getEm()->flush();
                    }
                }
            }
        }
        fclose($fh);
        \unlink('nomad.xml');
    }

    public function reintexImport() {

        $sep = ';';

        $parentid = $this->params->getIntRequestParam('katid', 0);
        $gyartoid = $this->params->getIntRequestParam('gyarto', 0);
        $dbtol = $this->params->getIntRequestParam('dbtol', 0);
        $dbig = $this->params->getIntRequestParam('dbig', 0);
        $editleiras = $this->params->getBoolRequestParam('editleiras', false);
        $createuj = $this->params->getBoolRequestParam('createuj', false);
        $arszaz = $this->params->getNumRequestParam('arszaz', 100);
        move_uploaded_file($_FILES['toimport']['tmp_name'], 'reinteximport.csv');

        $fh = fopen('reinteximport.csv', 'r');
        if ($fh) {
            $afa = store::getEm()->getRepository('Entities\Afa')->findByErtek(27);
            $vtsz = store::getEm()->getRepository('Entities\Vtsz')->findByNev('-');
            $gyarto = store::getEm()->getRepository('Entities\Partner')->find($gyartoid);
            $parent = store::getEm()->getRepository('Entities\TermekFa')->find($parentid);
            $termekdb = 0;
            $termekdb = 0;
            fgetcsv($fh, 0, $sep, '"');
            while (($termekdb < $dbtol) && ($data = fgetcsv($fh, 0, $sep, '"'))) {
                $termekdb++;
            }
            while ((($dbig && ($termekdb < $dbig)) || (!$dbig)) && ($data = fgetcsv($fh, 0, $sep, '"'))) {
                $termekdb++;
                if ($data[$this->n('a')]) {
                    $termek = store::getEm()->getRepository('Entities\Termek')->findBy(array('cikkszam' => $data[$this->n('a')],'gyarto' => $gyartoid));
                    if (!$termek) {

                        if ($createuj) {

                            $termeknev = $data[$this->n('b')];

                            $termek = new \Entities\Termek();
                            $termek->setFuggoben(true);
                            $termek->setMe('darab');
                            $termek->setNev($termeknev);
                            $termek->setCikkszam($data[$this->n('a')]);
                            $termek->setTermekfa1($parent);
                            $termek->setVtsz($vtsz[0]);
                            $termek->setHparany(3);
                            if ($gyarto) {
                                $termek->setGyarto($gyarto);
                            }
                        }
                    }
                    else {
                        $termek = $termek[0];
                        if ($editleiras) {
                            //$hosszuleiras = mb_convert_encoding(trim($data[3]), 'UTF8', 'ISO-8859-2');
                            //$termek->setLeiras($hosszuleiras);
                            //$rovidleiras = mb_convert_encoding(trim($data[4]), 'UTF8', 'ISO-8859-2');
                            //$termek->setRovidleiras(mb_substr($rovidleiras, 0, 100, 'UTF8') . '...');
                        }
                    }
                    if ($termek) {
                        //$termek->setAfa($afa[0]);
                        $termek->setBrutto(round($data[$this->n('i')] * 1 * $arszaz / 100,-1));
                        store::getEm()->persist($termek);
                        store::getEm()->flush();
                    }
                }
            }
            fclose($fh);
            \unlink('reinteximport.csv');
        }
    }

    public function tutisportImport() {
        $sep = ';';

        $parentid = $this->params->getIntRequestParam('katid', 0);
        $gyartoid = $this->params->getIntRequestParam('gyarto', 0);
        $dbtol = $this->params->getIntRequestParam('dbtol', 0);
        $dbig = $this->params->getIntRequestParam('dbig', 0);
        $editleiras = $this->params->getBoolRequestParam('editleiras', false);
        $createuj = $this->params->getBoolRequestParam('createuj', false);
        $arszaz = $this->params->getNumRequestParam('arszaz', 100);
        move_uploaded_file($_FILES['toimport']['tmp_name'], 'tutisportimport.csv');

        $fh = fopen('tutisportimport.csv', 'r');
        if ($fh) {
            $afa = store::getEm()->getRepository('Entities\Afa')->findByErtek(27);
            $vtsz = store::getEm()->getRepository('Entities\Vtsz')->findByNev('-');
            $gyarto = store::getEm()->getRepository('Entities\Partner')->find($gyartoid);
            $parent = store::getEm()->getRepository('Entities\TermekFa')->find($parentid);
            $termekdb = 0;
            $termekdb = 0;
            fgetcsv($fh, 0, $sep, '"');
            while (($termekdb < $dbtol) && ($data = fgetcsv($fh, 0, $sep, '"'))) {
                $termekdb++;
            }
            while ((($dbig && ($termekdb < $dbig)) || (!$dbig)) && ($data = fgetcsv($fh, 0, $sep, '"'))) {
                $termekdb++;
                if ($data[$this->n('a')]) {
                    $termek = store::getEm()->getRepository('Entities\Termek')->findBy(array('cikkszam' => $data[$this->n('a')],'gyarto' => $gyartoid));
                    if (!$termek) {
                        $csz = str_replace(' ', '', $data[$this->n('a')]);
                        $termek = store::getEm()->getRepository('Entities\Termek')->findBy(array('cikkszam' => $csz, 'gyarto' => $gyartoid));
                    }
                    if ($data[$this->n('d')] * 1 != 0) {
                        if (!$termek) {

                            if ($createuj) {

                                $termeknev = $this->toutf(trim($data[$this->n('b')]));
                                $me = $this->toutf(trim($data[$this->n('c')]));

                                $termek = new \Entities\Termek();
                                $termek->setFuggoben(true);
                                $termek->setMe($me);
                                $termek->setNev($termeknev);
                                $termek->setCikkszam($data[$this->n('a')]);
                                $termek->setTermekfa1($parent);
                                $termek->setVtsz($vtsz[0]);
                                $termek->setHparany(3);
                                if ($gyarto) {
                                    $termek->setGyarto($gyarto);
                                }
                            }
                        }
                        else {
                            $termek = $termek[0];
                            if ($editleiras) {
                                //$hosszuleiras = mb_convert_encoding(trim($data[3]), 'UTF8', 'ISO-8859-2');
                                //$termek->setLeiras($hosszuleiras);
                                //$rovidleiras = mb_convert_encoding(trim($data[4]), 'UTF8', 'ISO-8859-2');
                                //$termek->setRovidleiras(mb_substr($rovidleiras, 0, 100, 'UTF8') . '...');
                            }
                        }
                        if ($termek) {
                            $termek->setCikkszam($data[$this->n('a')]);
                            //$termek->setAfa($afa[0]);
                            $termek->setBrutto(round($data[$this->n('e')] * 1 * $arszaz / 100, -1));
                            store::getEm()->persist($termek);
                            store::getEm()->flush();
                        }
                    }
                    else {
                        if ($termek) {
                            $termek = $termek[0];
                            $termek->setNemkaphato(true);
                            $termek->setLathato(false);
                            store::getEm()->persist($termek);
                            store::getEm()->flush();
                        }
                    }
                }
            }
            fclose($fh);
            \unlink('tutisportimport.csv');
        }

    }

    public function makszutovImport() {
        $sep = ';';

        $parentid = $this->params->getIntRequestParam('katid', 0);
        $gyartoid = $this->params->getIntRequestParam('gyarto', 0);
        $dbtol = $this->params->getIntRequestParam('dbtol', 0);
        $dbig = $this->params->getIntRequestParam('dbig', 0);
        $editleiras = $this->params->getBoolRequestParam('editleiras', false);
        $createuj = $this->params->getBoolRequestParam('createuj', false);
        $arszaz = $this->params->getNumRequestParam('arszaz', 100);

        $urleleje = \mkw\Store::changeDirSeparator($this->params->getStringRequestParam('path', \mkw\Store::getConfigValue('path.termekkep')));

        $path = \mkw\Store::changeDirSeparator($this->params->getStringRequestParam('path', \mkw\Store::getConfigValue('path.termekkep')));
        $mainpath = \mkw\Store::changeDirSeparator(\mkw\Store::getConfigValue('mainpath'));
        if ($mainpath) {
            $mainpath = rtrim($mainpath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        }
        $path = $mainpath . $path;
        $path = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $urleleje = rtrim($urleleje, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

        $ch = \curl_init('http://www.makszutov.hu/partner-arlista?format=csv');
        $fh = fopen('makszutov.txt', 'w');
        \curl_setopt($ch, CURLOPT_FILE, $fh);
        \curl_exec($ch);
        fclose($fh);

        $fh = fopen('makszutov.txt', 'r');
        if ($fh) {
            $settings = array(
                'quality'=>80,
                'sizes'=>array('100'=>'100x100','150'=>'150x150','250'=>'250x250','1000'=>'1000x800')
            );
            //$afa = store::getEm()->getRepository('Entities\Afa')->findByErtek(27);
            $vtsz = store::getEm()->getRepository('Entities\Vtsz')->findByNev('-');
            $gyarto = store::getEm()->getRepository('Entities\Partner')->find($gyartoid);
            $termekdb = 0;
            fgetcsv($fh, 0, $sep, '"');
            while (($termekdb < $dbtol) && ($data = fgetcsv($fh, 0, $sep, '"'))) {
                $termekdb++;
            }
            while ((($dbig && ($termekdb < $dbig)) || (!$dbig)) && ($data = fgetcsv($fh, 0, $sep, '"'))) {
                $idegencikkszam = (string)$data[$this->n('a')];
                $termekdb++;
                $termek = false;
                $valtozat = false;
                $valtozatok = store::getEm()->getRepository('Entities\TermekValtozat')->findBy(array('idegencikkszam' => $idegencikkszam));
                if ($valtozatok) {
                    foreach($valtozatok as $v) {
                        $termek = $v->getTermek();
                        if ($termek && $termek->getGyartoId() == $gyartoid) {
                            $valtozat = $v;
                            break;
                        }
                    }
                }
                else {
                    $termek = store::getEm()->getRepository('Entities\Termek')->findBy(array('idegencikkszam' => $idegencikkszam, 'gyarto' => $gyartoid));
                }
                if ($data[$this->n('j')]) {
                    $ch = \curl_init($data[$this->n('j')]);
                    \curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $le = \curl_exec($ch);

                    $puri = new \mkwhelpers\HtmlPurifierSanitizer(array(
                        'HTML.Allowed' => 'p,ul,li,b,strong,br'
                    ));
                    $leiras = $puri->sanitize($le);

                    $puri2 = store::getSanitizer();
                    $kisleiras = $puri2->sanitize($le);
                }
                else {
                    $leiras = '';
                    $kisleiras = '';
                }

                $kaphato = (substr($data[$this->n('h')],0,5) !== 'Nincs') && (strpos($data[$this->n('h')],'rk.:') === false);

                if (!$termek) {

                    if ($createuj && $kaphato) {
                        $katnev = $data[$this->n('b')];
                        $urlkatnev = \mkw\Store::urlize($katnev);
                        \mkw\Store::createDirectoryRecursively($path . $urlkatnev);
                        $parent = $this->createKategoria($katnev, $parentid);
                        $termeknev = $data[$this->n('d')];

                        $termek = new \Entities\Termek();
                        $termek->setFuggoben(true);
                        $termek->setMe('db');
                        $termek->setNev($termeknev);
                        $termek->setLeiras($leiras);
                        $termek->setRovidleiras(mb_substr($kisleiras, 0, 100, 'UTF8') . '...');
                        $termek->setIdegencikkszam($idegencikkszam);
                        $termek->setTermekfa1($parent);
                        $termek->setVtsz($vtsz[0]);
                        $termek->setHparany(3);
                        if ($gyarto) {
                            $termek->setGyarto($gyarto);
                        }
                        $termek->setNemkaphato(false);
                        $termek->setBrutto(round($data[$this->n('g')] * 1 * $arszaz / 100, -1));
                        // kepek
                        $imagelist = explode(',', $data[$this->n('i')]);
                        $imgcnt = 0;
                        foreach ($imagelist as $imgurl) {
                            $imgcnt++;

                            $nameWithoutExt = $path . $urlkatnev . DIRECTORY_SEPARATOR . \mkw\Store::urlize($termeknev . '_' . $idegencikkszam);
                            $kepnev = \mkw\Store::urlize($termeknev . '_' . $idegencikkszam);
                            if (count($imagelist) > 1) {
                                $nameWithoutExt = $nameWithoutExt . '_' . $imgcnt;
                                $kepnev = $kepnev . '_' . $imgcnt;
                            }

                            $extension = \mkw\Store::getExtension($imgurl);
                            $imgpath = $nameWithoutExt . '.' . $extension;

                            $ch = \curl_init($imgurl);
                            $ih = fopen($imgpath, 'w');
                            \curl_setopt($ch, CURLOPT_FILE, $ih);
                            \curl_exec($ch);
                            fclose($ih);

                            foreach ($settings['sizes'] as $k=>$size) {
                                    $newFilePath = $nameWithoutExt."_".$k.".".$extension;
                                    $matches = explode('x',$size);
                                    \mkw\thumbnail::createThumb($imgpath, $newFilePath, $matches[0]*1, $matches[1]*1, $settings['quality'], true) ;
                            }
                            if (((count($imagelist) > 1) && ($imgcnt == 1)) || (count($imagelist) == 1)) {
                                $termek->setKepurl($urleleje . $urlkatnev . DIRECTORY_SEPARATOR . $kepnev . '.' . $extension);
                                $termek->setKepleiras($termeknev);
                            }
                            else {
                                $kep = new \Entities\TermekKep();
                                $termek->addTermekKep($kep);
                                $kep->setUrl($urleleje . $urlkatnev . DIRECTORY_SEPARATOR . $kepnev . '.' . $extension);
                                $kep->setLeiras($termeknev);
                                store::getEm()->persist($kep);
                            }
                        }
                        store::getEm()->persist($termek);
                        store::getEm()->flush();
                    }
                }
                else {
                    if (is_array($termek)) {
                        $termek = $termek[0];
                    }
                    if ($valtozat) {
                        if ($termek) {
                            $ar = $data[$this->n('g')] * 1 * $arszaz / 100;
                            $termek->setBrutto(round($ar, -1));
                        }
                        if (!$kaphato) {
                            $valtozat->setElerheto(false);
                        }
                        else {
                            $valtozat->setElerheto(true);
                        }
                        store::getEm()->persist($valtozat);
                        store::getEm()->flush();
                    }
                    else {
                        if ($termek) {
                            if ($editleiras) {
                                $termek->setLeiras($leiras);
                            }
                            if (!$kaphato) {
                                $termek->setNemkaphato(true);
                            }
                            else {
                                $termek->setNemkaphato(false);
                            }
                            $termek->setBrutto(round($data[$this->n('g')] * 1 * $arszaz / 100, -1));
                            store::getEm()->persist($termek);
                            store::getEm()->flush();
                        }
                    }
                }
            }
        }
        fclose($fh);
        \unlink('makszutov.txt');
    }

    public function legavenueImport() {

    }

    public function createVateraPartner($pa) {
        $me = store::getEm()->getRepository('Entities\Partner')->findBy(array('email' => $pa['temail']));

        if (!$me) {
            $me = new \Entities\Partner();
            $me->setVendeg(true);
            $me->setReferrer('http://' . $pa['vasarlashelye']);
            $me->setTelefon($pa['ttelefon']);
            $me->setEmail($pa['temail']);

            $nev = explode(' ', $pa['tnev']);
            $me->setKeresztnev(array_pop($nev));
            $me->setVezeteknev(implode(' ', $nev));

            $me->setAkcioshirlevelkell(true);
            $me->setUjdonsaghirlevelkell(true);

            if ($pa['szamlanev']) {
                $me->setNev($pa['szamlanev']);
                $me->setIrszam($pa['szamlairszam']);
                $me->setVaros($pa['szamlavaros']);
                $me->setUtca($pa['szamlautca']);
            }
            else {
                if ($pa['tszamlanev']) {
                    $me->setNev($pa['tszamlanev']);
                    $me->setIrszam($pa['tszamlairszam']);
                    $me->setVaros($pa['tszamlavaros']);
                    $me->setUtca($pa['tszamlautca']);
                }
                else {
                    if ($pa['szallnev']) {
                        $me->setNev($pa['szallnev']);
                        $me->setIrszam($pa['szallirszam']);
                        $me->setVaros($pa['szallvaros']);
                        $me->setUtca($pa['szallutca']);
                    }
                    else {
                        if ($pa['tszallnev']) {
                            $me->setNev($pa['tszallnev']);
                            $me->setIrszam($pa['tszallirszam']);
                            $me->setVaros($pa['tszallvaros']);
                            $me->setUtca($pa['tszallutca']);
                        }
                    }
                }
            }

            if ($pa['szallnev']) {
                $me->setSzallnev($pa['szallnev']);
                $me->setSzallirszam($pa['szallirszam']);
                $me->setSzallvaros($pa['szallvaros']);
                $me->setSzallutca($pa['szallutca']);
            }
            else {
                if ($pa['tszallnev']) {
                    $me->setSzallnev($pa['tszallnev']);
                    $me->setSzallirszam($pa['tszallirszam']);
                    $me->setSzallvaros($pa['tszallvaros']);
                    $me->setSzallutca($pa['tszallutca']);
                }
                else {
                    if ($pa['szamlanev']) {
                        $me->setSzallnev($pa['szamlanev']);
                        $me->setSzallirszam($pa['szamlairszam']);
                        $me->setSzallvaros($pa['szamlavaros']);
                        $me->setSzallutca($pa['szamlautca']);
                    }
                    else {
                        if ($pa['tszamlanev']) {
                            $me->setSzallnev($pa['tszamlanev']);
                            $me->setSzallirszam($pa['tszamlairszam']);
                            $me->setSzallvaros($pa['tszamlavaros']);
                            $me->setSzallutca($pa['tszamlautca']);
                        }
                    }
                }
            }

            store::getEm()->persist($me);
            store::getEm()->flush();
        }
        else {
            $me = $me[0];
        }
        return $me;
    }

    public function createVateraSzallitasimod($nev) {
        $me = store::getEm()->getRepository('Entities\Szallitasimod')->findBy(array('nev' => $nev));
        if (!$me) {
            $me = new \Entities\Szallitasimod();
            $me->setNev($nev);
            $me->setWebes(false);
            $me->setFizmodok('1');

            store::getEm()->persist($me);
            store::getEm()->flush();
        }
        else {
            $me = $me[0];
        }
        return $me;
    }

    public function vateraImport() {
        $sep = ';';

        move_uploaded_file($_FILES['vaterarendeles']['tmp_name'], 'vaterarendeles.csv');
        move_uploaded_file($_FILES['vateratermek']['tmp_name'], 'vateratermek.csv');

        $fhrendeles = fopen('vaterarendeles.csv', 'r');
        $fhtermek = fopen('vateratermek.csv', 'r');
        if ($fhrendeles && $fhtermek) {

            $termekek = array();
            fgetcsv($fhtermek, 0, $sep, '"');
            while ($data = fgetcsv($fhtermek, 0, $sep, '"')) {
                $termekek[$data[$this->n('c')]] = $data;
            }

            $rendelesek = array();
            fgetcsv($fhrendeles, 0, $sep, '"');
            while ($data = fgetcsv($fhrendeles, 0, $sep, '"')) {
                if (array_key_exists($data[$this->n('b')], $termekek)) {
                    $rid = $data[$this->n('a')];
                    if ($data[$this->n('f')]) {
                        $rendelesek[$rid]['datum'] = $data[$this->n('g')];
                        $rendelesek[$rid]['szallmod'] = $this->toutf($data[$this->n('h')]);
                        $rendelesek[$rid]['szallktg'] = $data[$this->n('i')] * 1;
                        $rendelesek[$rid]['szallnev'] = $this->toutf($data[$this->n('j')]);
                        $rendelesek[$rid]['szallirszam'] = $this->toutf($data[$this->n('k')]);
                        $rendelesek[$rid]['szallvaros'] = $this->toutf($data[$this->n('l')]);
                        $rendelesek[$rid]['szallutca'] = $this->toutf($data[$this->n('m')]);
                        $rendelesek[$rid]['szamlanev'] = $this->toutf($data[$this->n('n')]);
                        $rendelesek[$rid]['szamlairszam'] = $this->toutf($data[$this->n('o')]);
                        $rendelesek[$rid]['szamlavaros'] = $this->toutf($data[$this->n('p')]);
                        $rendelesek[$rid]['szamlautca'] = $this->toutf($data[$this->n('q')]);
                        $rendelesek[$rid]['megjegyzes'] = $this->toutf($data[$this->n('r')]);
                    }
                    if (!array_key_exists('termek', $rendelesek[$rid])) {
                        $rendelesek[$rid]['termek'] = array();
                    }
                    $t = $termekek[$data[$this->n('b')]];
                    $rendelesek[$rid]['tusernev'] = $t[$this->n('h')];
                    $rendelesek[$rid]['tnev'] = $this->toutf($t[$this->n('i')]);
                    $rendelesek[$rid]['ttelefon'] = $this->toutf($t[$this->n('j')]);
                    $rendelesek[$rid]['temail'] = $t[$this->n('k')];
                    $rendelesek[$rid]['vasarlashelye'] = $this->toutf($t[$this->n('l')]);
                    $rendelesek[$rid]['tdatum'] = $t[$this->n('o')];
                    $rendelesek[$rid]['tszallmod'] = $this->toutf($t[$this->n('p')]);
                    $rendelesek[$rid]['tszallktg'] = $t[$this->n('q')] * 1;
                    $rendelesek[$rid]['tszallnev'] = $this->toutf($t[$this->n('r')]);
                    $cim = \mkw\Store::explodeCim($this->toutf($t[$this->n('s')]));
                    $rendelesek[$rid]['tszallirszam'] = $cim[0];
                    $rendelesek[$rid]['tszallvaros'] = $cim[1];
                    $rendelesek[$rid]['tszallutca'] = $cim[2];
                    $rendelesek[$rid]['tszamlanev'] = $this->toutf($t[$this->n('t')]);
                    $cim = \mkw\Store::explodeCim($this->toutf($t[$this->n('u')]));
                    $rendelesek[$rid]['tszamlairszam'] = $cim[0];
                    $rendelesek[$rid]['tszamlavaros'] = $cim[1];
                    $rendelesek[$rid]['tszamlautca'] = $cim[2];
                    $rendelesek[$rid]['tmegjegyzes'] = $this->toutf($t[$this->n('v')]);

                    $rendelesek[$rid]['termek'][] = array(
                        'kod' => $data[$this->n('b')],
                        'nev' => $this->toutf($data[$this->n('c')]),
                        'tnev' => $this->toutf($t[$this->n('a')]),
                        'tcikkszam' => $this->toutf($t[$this->n('b')]),
                        'mennyiseg' => $data[$this->n('d')] * 1,
                        'egysar' => $data[$this->n('e')] * 1,
                        'tmennyiseg' => $t[$this->n('d')] * 1,
                        'tegysar' => $t[$this->n('e')] * 1
                    );
                }
            }

            unset($termekek);

            uasort($rendelesek, function($a, $b) {
                if ($a['datum'] == $b['datum']) {
                    return 0;
                }
                return ($a['datum'] < $b['datum']) ? -1 : 1;
            });

            //echo '<pre>';print_r($rendelesek);echo '</pre>';
            //die();

            foreach($rendelesek as $rk => $r) {
                $fej = new \Entities\Bizonylatfej();
                $fej->setBizonylattipus(store::getEm()->getRepository('Entities\Bizonylattipus')->find('megrendeles'));
                $fej->setPersistentData();
        		$fej->setErbizonylatszam($rk);
                $partner = $this->createVateraPartner($r);
                $fej->setPartner($partner);
                $szallmod = $this->createVateraSzallitasimod($r['szallmod']);
                $fej->setSzallitasimod($szallmod);
                $ck = store::getEm()->getRepository('Entities\Raktar')->find(store::getParameter(\mkw\consts::Raktar));
                if ($ck) {
                    $fej->setRaktar($ck);
                }
                $ck = store::getEm()->getRepository('Entities\Fizmod')->find(store::getParameter(\mkw\consts::Fizmod));
                if ($ck) {
                    $fej->setFizmod($ck);
                }
                $fej->setKelt();
                $fej->setTeljesites();
                $fej->setEsedekesseg();
                $fej->setHatarido($r['datum']);
                $ck = store::getEm()->getRepository('Entities\Valutanem')->find(store::getParameter(\mkw\consts::Valutanem));
                if ($ck) {
                    $fej->setValutanem($ck);
                    $fej->setBankszamla($ck->getBankszamla());
                }
                $fej->setArfolyam(1);

                $ck = store::getEm()->getRepository('Entities\Bizonylatstatusz')->find(store::getParameter(\mkw\consts::BizonylatStatuszFuggoben));
                if ($ck) {
                    $fej->setBizonylatstatusz($ck);
                }

                if ($r['megjegyzes'] || $r['tmegjegyzes']) {
                    $fej->setWebshopmessage($r['megjegyzes'] . ' ' . $r['tmegjegyzes']);
                }
                $fej->setBelsomegjegyzes('Vatera ' . $rk);

                $fej->generateId(); // az üres kelt miatt került a végére

                $hibascikkszam = array();
                foreach($r['termek'] as $rtetel) {
                    $termek = store::getEm()->getRepository('Entities\Termek')->findBy(array('cikkszam' => $rtetel['tcikkszam']));
                    if ($termek) {
                        $tetel = new \Entities\Bizonylattetel();
                        $fej->addBizonylattetel($tetel);
                        $tetel->setPersistentData();
                        $tetel->setArvaltoztat(0);
                        $tetel->setTermek($termek[0]);
                        $tetel->setMozgat();
                        $tetel->setMennyiseg($rtetel['mennyiseg']);
                        $tetel->setBruttoegysar($rtetel['egysar']);
                        $tetel->setBruttoegysarhuf($rtetel['egysar']);
                        $tetel->calc();
                        store::getEm()->persist($tetel);
                    }
                    else {
                        $hibascikkszam[] = $rtetel['tcikkszam'];
                    }
                }
                if ($hibascikkszam) {
                    $fej->setBelsomegjegyzes($fej->getBelsomegjegyzes() . ' Hibás cikkszámok: ' . implode(',', $hibascikkszam));
                }
                $fej->doStuffOnPrePersist();
                store::getEm()->persist($fej);
                store::getEm()->flush();
                if ($r['szallktg']) {
                    store::getEm()->getRepository('Entities\Bizonylatfej')->createSzallitasiKtg($fej, false, $r['szallktg']);
                    $fej->doStuffOnPrePersist();
                    store::getEm()->persist($fej);
                    store::getEm()->flush();
                }
/**                $statusz = $fej->getBizonylatstatusz();
                if ($statusz) {
                    $emailtpl = $statusz->getEmailtemplate();
                    $fej->sendStatuszEmail($emailtpl, null, false);
                }
 *
 */
            }

        }

        \unlink('vaterarendeles.csv');
        \unlink('vateratermek.csv');
    }

    public function szAtalakit() {
        header("Content-type: text/csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        $sep = ';';

        move_uploaded_file($_FILES['toimport']['tmp_name'], 'szatalakit.csv');
        $er = fopen('eredmeny.csv', 'w');
        $fh = fopen('szatalakit.csv', 'r');
        if ($fh) {
            fgetcsv($fh, 0, $sep, '"');
            while ($data = fgetcsv($fh, 0, $sep, '"')) {
                $nev = str_getcsv($data[$this->n('e')], ' ');
                $cikkszam = $nev[0];
                $i = count($nev);
                $meret = $nev[$i - 1];
                $szin = $nev[$i - 2];
                unset($nev[$i - 2]);
                unset($nev[$i - 1]);
                unset($nev[0]);
                $out = array(
                    $data[$this->n('a')],
                    $data[$this->n('b')],
                    $data[$this->n('c')],
                    $data[$this->n('d')],
                    $cikkszam,
                    implode(' ', $nev),
                    $szin,
                    $meret,
                    $data[$this->n('f')],
                    $data[$this->n('g')]
                );
                fwrite($er, implode(';', $out) . "\n\r");
            }
        }
        fclose($fh);
        fclose($er);
        \unlink('szatalakit.csv');
    }

    public function szInvarImport() {
        $sep = ',';
        $dbtol = $this->params->getIntRequestParam('dbtol', 0);
        $dbig = $this->params->getIntRequestParam('dbig', 0);
        move_uploaded_file($_FILES['toimport']['tmp_name'], 'szinvarimport.csv');
        $fh = fopen('szinvarimport.csv', 'r');
        if ($fh) {
            $afa = store::getEm()->getRepository('Entities\Afa')->findByErtek(27);
            $afa = $afa[0];
            $szinvalt = $this->createTermekValtozatAdatTipus('Szín');
            $meretvalt = $this->createTermekValtozatAdatTipus('Méret');
            $termekdb = 0;
            while (($termekdb < $dbtol) && ($data = fgetcsv($fh, 0, $sep, '"'))) {
                $termekdb++;
            }
            while ((($dbig && ($termekdb < $dbig)) || (!$dbig)) && ($data = fgetcsv($fh, 0, $sep, '"'))) {
                $termekdb++;
                if ($data[$this->n('i')]) {
                    $vtsz = $this->createVtsz($data[$this->n('i')], $afa);
                }
                else {
                    $vtsz = $this->createVtsz('-', $afa);
                }
                $termek = store::getEm()->getRepository('Entities\Termek')->findOneBy(array('cikkszam' => $data[$this->n('e')], 'nev' => $data[$this->n('f')]));
                if (!$termek) {

                    $parent = $this->getKategoriaByIdegenkod((string)$data[$this->n('a')]);

                    $termek = new \Entities\Termek();
                    $termek->setMe('db');
                    $termek->setNev($data[$this->n('f')]);
                    $termek->setCikkszam($data[$this->n('e')]);
                    $termek->setVonalkod($data[$this->n('d')]);
                    $termek->setIdegenkod($data[$this->n('c')]);
                    $termek->setTermekfa1($parent);
                    $termek->setVtsz($vtsz);

                    $valt = new \Entities\TermekValtozat();
                    $valt->setTermek($termek);
                    $valt->setAdatTipus1($szinvalt);
                    $valt->setErtek1($data[$this->n('h')]);
                    $valt->setAdatTipus2($meretvalt);
                    $valt->setErtek2($data[$this->n('j')]);
                    $valt->setCikkszam($data[$this->n('e')]);
                    $valt->setVonalkod($data[$this->n('d')]);
                    store::getEm()->persist($valt);
                }
                else {
                    $termek->setNev($data[$this->n('f')]);
                    $termek->setCikkszam($data[$this->n('e')]);
                    $termek->setTermekfa1($parent);
                    $termek->setVtsz($vtsz);

                    $valt = store::getEm()->getRepository('Entities\TermekValtozat')->findByVonalkod($data[$this->n('d')]);
                    if (!$valt) {
                        $valt = new \Entities\TermekValtozat();
                        $valt->setTermek($termek);
                        $valt->setVonalkod($data[$this->n('d')]);
                    }
                    else {
                        $valt = $valt[0];
                    }
                    $valt->setAdatTipus1($szinvalt);
                    $valt->setErtek1($data[$this->n('h')]);
                    $valt->setAdatTipus2($meretvalt);
                    $valt->setErtek2($data[$this->n('j')]);
                    $valt->setCikkszam($data[$this->n('e')]);
                    store::getEm()->persist($valt);
                }
                store::getEm()->persist($termek);
                store::getEm()->flush();
            }
        }
        fclose($fh);
        \unlink('szatalakit.csv');
    }

    public function szimport() {
//        $translaterepo = store::getEm()->getRepository('Gedmo\Translatable\Entity\Translation');

        $createuj = $this->params->getBoolRequestParam('createuj', false);
        $parentid = $this->params->getIntRequestParam('katid', 0);
        $parent = store::getEm()->getRepository('Entities\TermekFa')->find($parentid);
        $dbig = $this->params->getIntRequestParam('dbig', 0);
        $dbtol = $this->params->getIntRequestParam('dbtol', 0);
        if ($dbtol < 2) {
            $dbtol = 2;
        }

        $filenev = $_FILES['toimport']['name'];
        move_uploaded_file($_FILES['toimport']['tmp_name'], $filenev);
        //pathinfo

        $filetype = \PHPExcel_IOFactory::identify($filenev);
        $reader = \PHPExcel_IOFactory::createReader($filetype);
        $reader->setReadDataOnly(true);
        $excel = $reader->load($filenev);
        $sheet = $excel->getActiveSheet();
        $maxrow = $sheet->getHighestRow() * 1;
        if (!$dbig) {
            $dbig = $maxrow;
        }
        $maxcol = $sheet->getHighestColumn();
        $maxcolindex = \PHPExcel_Cell::columnIndexFromString($maxcol);

        $afa = store::getEm()->getRepository('Entities\Afa')->findByErtek(27);
        $afa = $afa[0];
        $termekrepo = store::getEm()->getRepository('Entities\Termek');
        $termekarrepo = store::getEm()->getRepository('Entities\TermekAr');
        $valutanemek = array();
        $vnemek = store::getEm()->getRepository('Entities\Valutanem')->getAll(array(), array());
        foreach($vnemek as $vn) {
            $valutanemek[$vn->getNev()] = $vn;
        }

        $fej = array();
        for($col = 0; $col < $maxcolindex; ++$col) {
            $cell = $sheet->getCellByColumnAndRow($col, 1);
            $fej[$col] = $cell->getValue();
        }

        for($row = $dbtol; $row <= $dbig; ++$row) {
            $ujtermek = false;
            $kod = false;
            $vonalkod = false;
            $cikkszam = false;
            $nev = false;
            $vtsz = false;
            $netto = false;
            $brutto = false;

            for($col = 0; $col < $maxcolindex; ++$col) {
                $cell = $sheet->getCellByColumnAndRow($col, $row);
                if ($fej[$col] == 'kod') {
                    $kod = $cell->getValue();
                }
                elseif ($fej[$col] == 'vonalkod') {
                    $vonalkod = $cell->getValue();
                }
                elseif ($fej[$col] == 'cikkszam') {
                    $cikkszam = $cell->getValue();
                }
                elseif ($fej[$col] == 'vtsz') {
                    $vtsz = $cell->getValue();
                }
                elseif ($cell->getValue() && substr($fej[$col], 0, 4) == 'nev_') {
                    $nyelv = strtoupper(substr($fej[$col], 4));
                    $nev[\mkw\Store::getLocale($nyelv)] = $cell->getValue();
                }
                elseif ($cell->getValue() && substr($fej[$col], 0, 3) == 'nev') {
                    $nev[\mkw\Store::getLocale('HU')] = $cell->getValue();
                }
                elseif ($cell->getValue() && substr($fej[$col], 0, 6) == 'netto_') {
                    $n = explode('_', $fej[$col]);
                    $netto[strtoupper($n[1])][$n[2]] = $cell->getValue();
                }
                elseif ($cell->getValue() && substr($fej[$col], 0, 7) == 'brutto_') {
                    $n = explode('_', $fej[$col]);
                    $brutto[strtoupper($n[1])][$n[2]] = $cell->getValue();
                }
            }

            $termek = false;
            if ($kod) {
                $termek = $termekrepo->find($kod);
            }
            elseif ($vonalkod) {
                $termek = $termekrepo->findByVonalkod($vonalkod);
            }

            if ($termek) {
                if (is_array($termek)) {
                    $termek = $termek[0];
                }
            }
            else {
                if ($createuj && is_array($nev) && array_key_exists('HU', $nev)) {
                    $ujtermek = true;
                    $termek = new \Entities\Termek();
                    $termek->setMe('db');
                    if ($parent) {
                        $termek->setTermekfa1($parent);
                    }
                }
            }
            if ($termek) {
                if ($vonalkod) {
                    $termek->setVonalkod($vonalkod);
                }
                if ($cikkszam) {
                    $termek->setCikkszam($cikkszam);
                }
                if ($vtsz) {
                    $vtsz = $this->createVtsz($vtsz, $afa);
                    $termek->setVtsz($vtsz);
                }
                if ($brutto) {
                    foreach($brutto as $evalu => $bruttox) {
                        $valutanem = $valutanemek[$evalu];
                        if ($valutanem) {
                            foreach($bruttox as $ename => $ertek) {
                                if (is_array($netto)) {
                                    unset($netto[$evalu][$ename]);
                                }
                                if (!$ujtermek) {
                                    $ar = $termekarrepo->findBy(array('termek' => $termek->getId(), 'valutanem' => $valutanem->getId(), 'azonosito' => $ename));
                                    if ($ar) {
                                        $ar = $ar[0];
                                    }
                                }
                                if ($ujtermek || !$ar) {
                                    $ar = new \Entities\TermekAr();
                                    $ar->setTermek($termek);
                                    $ar->setValutanem($valutanem);
                                    $ar->setAzonosito($ename);
                                }
                                $ar->setBrutto($ertek);
                                store::getEm()->persist($ar);
                            }
                        }
                    }
                }
                if ($netto) {
                    foreach($netto as $evalu => $nettox) {
                        $valutanem = $valutanemek[$evalu];
                        if ($valutanem) {
                            foreach($nettox as $ename => $ertek) {
                                if (!$ujtermek) {
                                    $ar = $termekarrepo->findBy(array('termek' => $termek->getId(), 'valutanem' => $valutanem->getId(), 'azonosito' => $ename));
                                    if ($ar) {
                                        $ar = $ar[0];
                                    }
                                }
                                if ($ujtermek || !$ar) {
                                    $ar = new \Entities\TermekAr();
                                    $ar->setTermek($termek);
                                    $ar->setValutanem($valutanem);
                                    $ar->setAzonosito($ename);
                                }
                                $ar->setNetto($ertek);
                                store::getEm()->persist($ar);
                            }
                        }
                    }
                }

//                if ($nev) {
//                    foreach($nev as $loc => $text) {
//                        $translaterepo->translate($termek, 'nev', $loc, $text);
//                    }
//                }
//                store::getEm()->persist($termek);
//                store::getEm()->flush();

                if (is_array($nev)) {
                    foreach($nev as $loc => $text) {
                        $termek->setNev($text);
                        $termek->setTranslatableLocale($loc);
                        store::getEm()->persist($termek);
                        store::getEm()->flush();
                    }
                }
                else {
                    store::getEm()->persist($termek);
                    store::getEm()->flush();
                }
            }
        }

    }
}
