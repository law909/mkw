<?php
namespace Controllers;
use mkw\store, mkw\thumbnail;

class importController extends \mkwhelpers\Controller {

    public function view() {
        $view = $this->createView('imports.tpl');

        $view->setVar('pagetitle', t('Importok'));
        $view->setVar('path', \mkw\Store::getConfigValue('path.termekkep'));

        $gyarto = new partnerController($this->params);
        $view->setVar('gyartolist', $gyarto->getSzallitoSelectList(0));

        $view->printTemplateResult(false);
    }

    public function createKategoria($nev, $parentid) {
        $me = store::getEm()->getRepository('Entities\TermekFa')->findByNev($nev);
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

        $urleleje = \mkw\Store::changeDirSeparator($this->params->getStringRequestParam('path', \mkw\Store::getConfigValue('path.termekkep')));

        $path = \mkw\Store::changeDirSeparator($this->params->getStringRequestParam('path', \mkw\Store::getConfigValue('path.termekkep')));
        $mainpath = \mkw\Store::changeDirSeparator(\mkw\Store::getConfigValue('mainpath'));
        if ($mainpath) {
            $mainpath = rtrim($mainpath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        }
        $path = $mainpath . $path;
        $path = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $urleleje = rtrim($urleleje, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;


        $ch = curl_init('http://kreativpuzzle.hu/lawstocklist/');
        $fh = fopen('kreativpuzzlestock.txt', 'w');
        curl_setopt($ch, CURLOPT_FILE, $fh);
        curl_exec($ch);
        fclose($fh);

        $ch = curl_init('http://kreativpuzzle.hu/lawstocklist/images.php');
        $fh = fopen('kreativpuzzleimages.txt', 'w');
        curl_setopt($ch, CURLOPT_FILE, $fh);
        curl_exec($ch);
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

                        $katnev = mb_convert_encoding(trim($data[7]), 'UTF8', 'ISO-8859-2');
                        $urlkatnev = \mkw\Store::urlize($katnev);
                        \mkw\Store::createDirectoryRecursively($path . $urlkatnev);
                        $parent = $this->createKategoria($katnev, $parentid);
                        $termeknev = mb_convert_encoding(trim($data[1]), 'UTF8', 'ISO-8859-2');

                        $hosszuleiras = mb_convert_encoding(trim($data[13]), 'UTF8', 'ISO-8859-2');
                        $rovidleiras = mb_convert_encoding(trim($data[4]), 'UTF8', 'ISO-8859-2');

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

                                $ch = curl_init($imgurl);
                                $ih = fopen($imgpath, 'w');
                                curl_setopt($ch, CURLOPT_FILE, $ih);
                                curl_exec($ch);
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
                    else {
                        $termek = $termek[0];
                        if ($editleiras) {
                            $hosszuleiras = mb_convert_encoding(trim($data[13]), 'UTF8', 'ISO-8859-2');
                            $termek->setLeiras($hosszuleiras);
                            //$rovidleiras = mb_convert_encoding(trim($data[4]), 'UTF8', 'ISO-8859-2');
                            //$termek->setRovidleiras(mb_substr($rovidleiras, 0, 100, 'UTF8') . '...');
                        }
                    }
                    $termek->setNemkaphato(($data[6] * 1) == 0);
                    $termek->setAfa($afa[0]);
                    $termek->setNetto($data[3] * 1);
                    $termek->setBrutto(round($termek->getBrutto(), -1));
                    store::getEm()->persist($termek);
                    store::getEm()->flush();
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

}
