<?php
namespace Controllers;
use mkw\store;

class importController extends \mkwhelpers\Controller {

    public function view() {
        $view = $this->createView('imports.tpl');
        $view->setVar('pagetitle', t('Importok'));
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

        $fh = fopen('kreativpuzzlestock.txt', 'r');
        if ($fh) {
            fgetcsv($fh, 0, ';', '"');
            while ($data = fgetcsv($fh, 0, ';', '"')) {
                if ($data[2] * 1 > 0) {
                    $parent = $this->createKategoria(mb_convert_encoding(trim($data[7]), 'UTF8', 'ISO-8859-2'), $parentid);
                    $termek = store::getEm()->getRepository('Entities\Termek')->findByIdegenkod('KP' . $data[0]);
                    if (!$termek) {
                        $termek = new \Entities\Termek();
                        $termek->setFuggoben(true);
                        $termek->setMe('db');
                        $termek->setNev(mb_convert_encoding(trim($data[1]), 'UTF8', 'ISO-8859-2'));
                        $termek->setLeiras(mb_convert_encoding(trim($data[4]), 'UTF8', 'ISO-8859-2'));
                        $termek->setRovidleiras(mb_substr($termek->getLeiras(), 0, 100, 'UTF8') . '...');
                        $termek->setCikkszam($data[0]);
                        $termek->setIdegencikkszam($data[0]);
                        $termek->setIdegenkod('KP' . $data[0]);
                        $termek->setTermekfa1($parent);
                    }
                    else {
                        $termek = $termek[0];
                    }
                    $afa = store::getEm()->getRepository('Entities\Afa')->findByErtek(27);
                    $termek->setAfa($afa[0]);
                    $termek->setNetto($data[3] * 1);
                    $termek->setBrutto(round($termek->getBrutto(), -1));
                    store::getEm()->persist($termek);
                }
            }
            store::getEm()->flush();
        }
        fclose($fh);

    }

}