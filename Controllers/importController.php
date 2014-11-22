<?php
namespace Controllers;
use mkw\store, mkw\thumbnail;

class importController extends \mkwhelpers\Controller {

    private function toutf($mit) {
        return mb_convert_encoding($mit, 'UTF8', 'ISO-8859-2');
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

    public function kreativpuzzleImport() {

        $parentid = $this->params->getIntRequestParam('katid', 0);
        $gyartoid = $this->params->getIntRequestParam('gyarto', 0);
        $dbtol = $this->params->getIntRequestParam('dbtol', 0);
        $dbig = $this->params->getIntRequestParam('dbig', 0);
        $editleiras = $this->params->getBoolRequestParam('editleiras', false);
        $createuj = $this->params->getBoolRequestParam('createuj', false);

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
            fgetcsv($fh, 0, ';', '"');
            while ($data = fgetcsv($fh, 0, ';', '"')) {
                if ($data[0]) {
                    if (array_key_exists($data[0], $imagelist)) {
                        $imagelist[$data[0]][] = $data[1];
                    }
                    else {
                        $imagelist[$data[0]] = array($data[1]);
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
            fgetcsv($fh, 0, ';', '"');
            while (($termekdb < $dbtol) && ($data = fgetcsv($fh, 0, ';', '"'))) {
                $termekdb++;
            }
            while ((($dbig && ($termekdb < $dbig)) || (!$dbig)) && ($data = fgetcsv($fh, 0, ';', '"'))) {
                $termekdb++;
                if ($data[2] * 1 > 0) {
                    $termek = store::getEm()->getRepository('Entities\Termek')->findByIdegenkod('KP' . $data[0]);
                    if (!$termek) {

                        if ($createuj) {
                            $katnev = $this->toutf(trim($data[7]));
                            $urlkatnev = \mkw\Store::urlize($katnev);
                            \mkw\Store::createDirectoryRecursively($path . $urlkatnev);
                            $parent = $this->createKategoria($katnev, $parentid);
                            $termeknev = $this->toutf(trim($data[1]));

                            $hosszuleiras = $this->toutf(trim($data[13]));
                            $rovidleiras = $this->toutf(trim($data[4]));

                            $idegenkod = 'KP' . $data[0];

                            $termek = new \Entities\Termek();
                            $termek->setFuggoben(true);
                            $termek->setMe('db');
                            $termek->setNev($termeknev);
                            $termek->setLeiras($hosszuleiras);
                            $termek->setRovidleiras(mb_substr($rovidleiras, 0, 100, 'UTF8') . '...');
                            $termek->setCikkszam($data[0]);
                            $termek->setIdegencikkszam($data[0]);
                            $termek->setIdegenkod($idegenkod);
                            $termek->setTermekfa1($parent);
                            $termek->setVtsz($vtsz[0]);
                            $termek->setHparany(3);
                            if ($gyarto) {
                                $termek->setGyarto($gyarto);
                            }
                            // kepek
                            if (array_key_exists($data[0], $imagelist)) {
                                $imgcnt = 0;
                                foreach ($imagelist[$data[0]] as $imgurl) {
                                    $imgcnt++;

                                    $nameWithoutExt = $path . $urlkatnev . DIRECTORY_SEPARATOR . \mkw\Store::urlize($termeknev . '_' . $idegenkod);
                                    $kepnev = \mkw\Store::urlize($termeknev . '_' . $idegenkod);
                                    if (count($imagelist[$data[0]]) > 1) {
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
                                    if (((count($imagelist[$data[0]]) > 1) && ($imgcnt == 1)) || (count($imagelist[$data[0]]) == 1)) {
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
                            $hosszuleiras = $this->toutf(trim($data[13]));
                            $termek->setLeiras($hosszuleiras);
                            //$rovidleiras = mb_convert_encoding(trim($data[4]), 'UTF8', 'ISO-8859-2');
                            //$termek->setRovidleiras(mb_substr($rovidleiras, 0, 100, 'UTF8') . '...');
                        }
                    }
                    if ($termek) {
                        $termek->setNemkaphato(($data[6] * 1) == 0);
                        $termek->setAfa($afa[0]);
                        $termek->setNetto($data[3] * 1);
                        $termek->setBrutto(round($termek->getBrutto(), -1));
                        store::getEm()->persist($termek);
                        store::getEm()->flush();
                    }
                }
                else {
                    $termek = store::getEm()->getRepository('Entities\Termek')->findByIdegenkod('KP' . $data[0]);
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

        $urleleje = \mkw\Store::changeDirSeparator($this->params->getStringRequestParam('path', \mkw\Store::getConfigValue('path.termekkep')));

        $path = \mkw\Store::changeDirSeparator($this->params->getStringRequestParam('path', \mkw\Store::getConfigValue('path.termekkep')));
        $mainpath = \mkw\Store::changeDirSeparator(\mkw\Store::getConfigValue('mainpath'));
        if ($mainpath) {
            $mainpath = rtrim($mainpath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        }
        $path = $mainpath . $path;
        $path = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $urleleje = rtrim($urleleje, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;


        $ch = \curl_init('http://www.holdpeak.hu/csv.php?verzio=2');
        $fh = fopen('delton.txt', 'w');
        \curl_setopt($ch, CURLOPT_FILE, $fh);
        \curl_exec($ch);
        fclose($fh);
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
                    if ($termek) {
                        $termek->setAfa($afa[0]);
                        $termek->setNetto($data[7] * 1);
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
                        $termek->setNetto($data[7] * 1);
                        $termek->setBrutto(round($termek->getBrutto(), -1));
                        store::getEm()->persist($termek);
                        store::getEm()->flush();
                    }
                }
            }
        }
        fclose($fh);
    }

    public function reintexImport() {

        $sep = ';';

        $parentid = $this->params->getIntRequestParam('katid', 0);
        $gyartoid = $this->params->getIntRequestParam('gyarto', 0);
        $dbtol = $this->params->getIntRequestParam('dbtol', 0);
        $dbig = $this->params->getIntRequestParam('dbig', 0);
        $editleiras = $this->params->getBoolRequestParam('editleiras', false);
        $createuj = $this->params->getBoolRequestParam('createuj', false);
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
                if ($data[0]) {
                    $termek = store::getEm()->getRepository('Entities\Termek')->findBy(array('cikkszam' => $data[0],'gyarto' => $gyartoid));
                    if (!$termek) {

                        if ($createuj) {

                            $termeknev = $data[1];

                            $termek = new \Entities\Termek();
                            $termek->setFuggoben(true);
                            $termek->setMe('darab');
                            $termek->setNev($termeknev);
                            $termek->setCikkszam($data[0]);
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
                        $termek->setAfa($afa[0]);
                        $termek->setBrutto(round($data[8] * 1,-1));
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
                if ($data[0]) {
                    $termek = store::getEm()->getRepository('Entities\Termek')->findBy(array('cikkszam' => $data[0],'gyarto' => $gyartoid));
                    if (!$termek) {
                        $csz = str_replace(' ', '', $data[0]);
                        $termek = store::getEm()->getRepository('Entities\Termek')->findBy(array('cikkszam' => $csz, 'gyarto' => $gyartoid));
                    }
                    if ($data[3] * 1 != 0) {
                        if (!$termek) {

                            if ($createuj) {

                                $termeknev = $this->toutf(trim($data[1]));
                                $me = $this->toutf(trim($data[2]));

                                $termek = new \Entities\Termek();
                                $termek->setFuggoben(true);
                                $termek->setMe($me);
                                $termek->setNev($termeknev);
                                $termek->setCikkszam($data[0]);
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
                            $termek->setCikkszam($data[0]);
                            $termek->setAfa($afa[0]);
                            $termek->setBrutto(round($data[4], -1));
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
        $parentid = $this->params->getIntRequestParam('katid', 0);
        $gyartoid = $this->params->getIntRequestParam('gyarto', 0);
        $dbtol = $this->params->getIntRequestParam('dbtol', 0);
        $dbig = $this->params->getIntRequestParam('dbig', 0);
        $editleiras = $this->params->getBoolRequestParam('editleiras', false);
        $createuj = $this->params->getBoolRequestParam('createuj', false);

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
            $afa = store::getEm()->getRepository('Entities\Afa')->findByErtek(27);
            $vtsz = store::getEm()->getRepository('Entities\Vtsz')->findByNev('-');
            $gyarto = store::getEm()->getRepository('Entities\Partner')->find($gyartoid);
            $termekdb = 0;
            fgetcsv($fh, 0, ';', '"');
            while (($termekdb < $dbtol) && ($data = fgetcsv($fh, 0, ';', '"'))) {
                $termekdb++;
            }
            while ((($dbig && ($termekdb < $dbig)) || (!$dbig)) && ($data = fgetcsv($fh, 0, ';', '"'))) {
                $termekdb++;
                $termek = false;
                $valtozat = false;
                $valtozatok = store::getEm()->getRepository('Entities\TermekValtozat')->findBy(array('idegencikkszam' => $data[0]));
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
                    $termek = store::getEm()->getRepository('Entities\Termek')->findBy(array('idegencikkszam' => $data[0]));
                }
                if ($data[8]) {
                    $ch = \curl_init($data[8]);
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

                $kaphato = (bool)trim($data[6]);

                if (!$termek) {

                    if ($createuj && $kaphato) {
                        $katnev = $data[1];
                        $urlkatnev = \mkw\Store::urlize($katnev);
                        \mkw\Store::createDirectoryRecursively($path . $urlkatnev);
                        $parent = $this->createKategoria($katnev, $parentid);
                        $termeknev = $data[2];

                        $termek = new \Entities\Termek();
                        $termek->setFuggoben(true);
                        $termek->setMe('db');
                        $termek->setNev($termeknev);
                        $termek->setLeiras($leiras);
                        $termek->setRovidleiras(mb_substr($kisleiras, 0, 100, 'UTF8') . '...');
                        $termek->setIdegencikkszam($data[0]);
                        $termek->setTermekfa1($parent);
                        $termek->setVtsz($vtsz[0]);
                        $termek->setHparany(3);
                        if ($gyarto) {
                            $termek->setGyarto($gyarto);
                        }
                        $termek->setNemkaphato(false);
                        $termek->setBrutto(round($data[5] * 1, -1));
                        // kepek
                        $imagelist = explode(',', $data[7]);
                        $imgcnt = 0;
                        foreach ($imagelist as $imgurl) {
                            $imgcnt++;

                            $nameWithoutExt = $path . $urlkatnev . DIRECTORY_SEPARATOR . \mkw\Store::urlize($termeknev . '_' . $data[0]);
                            $kepnev = \mkw\Store::urlize($termeknev . '_' . $data[0]);
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
                    if ($valtozat) {
                        $valtozat->setBrutto(round($data[5] * 1, -1));
                        if (!$kaphato) {
                            $valtozat->setElerheto(false);
                        }
                        store::getEm()->persist($valtozat);
                        store::getEm()->flush();
                    }
                    else {
                        $termek = $termek[0];
                        if ($termek) {
                            if ($editleiras) {
                                $termek->setLeiras($leiras);
                            }
                            if (!$kaphato) {
                                $termek->setNemkaphato(true);
                            }
                            $termek->setBrutto(round($data[5] * 1, -1));
                            store::getEm()->persist($termek);
                            store::getEm()->flush();
                        }
                    }
                }
            }
        }
        fclose($fh);
    }

    public function legavenueImport() {

    }

    public function createVateraPartner($pa) {
        $me = store::getEm()->getRepository('Entities\Partner')->findBy(array('email' => $pa[10]));

        if (!$me) {
            $me = new \Entities\Partner();
            $me->setVendeg(true);
            $me->setReferrer('http://' . $pa[11]);
            $me->setTelefon($pa[9]);

            $me->setVezeteknev($this->toutf($pa[]));
            $me->setKeresztnev($this->toutf($pa[]));

            $me->setNev($this->toutf($pa[]));
            $me->setIrszam($this->toutf($pa[]));
            $me->setVaros($this->toutf($pa[]));
            $me->setUtca($this->toutf($pa[]));

            $me->setSzallnev($this->toutf($pa[]));
            $me->setSzallirszam($this->toutf($pa[]));
            $me->setSzallvaros($this->toutf($pa[]));
            $me->setSzallutca($this->toutf($pa[]));

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

            $rendelesek = array();
            fgetcsv($fhrendeles, 0, $sep, '"');
            while ($data = fgetcsv($fhrendeles, 0, $sep, '"')) {
                $rendelesek[$data[0]] = $data;
            }

            $termekek = array();
            fgetcsv($fhtermek, 0, $sep, '"');
            while ($data = fgetcsv($fhtermek, 0, $sep, '"')) {
                $termekek[$data[2]] = $data;
            }

            foreach($rendelesek as $rk => $r) {
                $fej = new \Entities\Bizonylatfej();
                $fej->setBizonylattipus($this->getRepo('Entities\Bizonylattipus')->find('megrendeles'));
                $fej->setPersistentData();
        		$fej->setErbizonylatszam($rk);
                $partner = $this->createVateraPartner($termekek[$r[1]]);
                $fej->setPartner($partner);
                $szallmod = $this->createVateraSzallitasimod($this->toutf($r[7]));
                $fej->setSzallitasimod($szallmod);
                $ck = store::getParameter(\mkw\consts::Raktar);
                if ($ck) {
                    $fej->setRaktar($ck);
                }
                $ck = store::getParameter(\mkw\consts::Fizmod);
                if ($ck) {
                    $fej->setFizmod($ck);
                }
                $fej->setKelt();
                $fej->setTeljesites();
                $fej->setEsedekesseg();
                $fej->setHatarido();
                $ck = store::getParameter(\mkw\consts::Valutanem);
                if ($ck) {
                    $fej->setValutanem($ck);
                }
                $fej->setArfolyam(1);
/*                $ck = store::getEm()->getRepository('Entities\Bankszamla')->find($this->params->getIntRequestParam('bankszamla'));
                if ($ck) {
                    $obj->setBankszamla($ck);
                }
*/
                $ck = store::getParameter(\mkw\consts::BizonylatStatuszFuggoben);
                if ($ck) {
                    $fej->setBizonylatstatusz($ck);
                }

                $fej->setWebshopmessage();

                $fej->generateId(); // az üres kelt miatt került a végére

                foreach($r as $rtetel) {
					$tetel = new Bizonylattetel();
					$fej->addBizonylattetel($tetel);
					$tetel->setPersistentData();
					$tetel->setArvaltoztat(0);

                    $termek = store::getEm()->getRepository('Entities\Termek')->findBy(array('cikkszam' => $rtetel[1]));
					if ($termek) {
						$tetel->setTermek($termek);
					}
					$tetel->setMozgat();
					$tetel->setMennyiseg($rtetel[3] * 1);
					$tetel->setBruttoegysar($rtetel[4] * 1);
					$tetel->setBruttoegysarhuf($rtetel[4] * 1);
					$this->getEm()->persist($tetel);
                }
                $fej->doStuffOnPrePersist();
                $this->getEm()->persist($fej);
                $this->getEm()->flush();
            }

        }

        \unlink('vaterarendeles.csv');
        \unlink('vateratermek.csv');
    }

}
