<?php
namespace Controllers;

class exportController extends \mkwhelpers\Controller {

    public function view() {
        $view = $this->createView('exports.tpl');
        $view->setVar('pagetitle', t('Exportok'));
        $view->printTemplateResult(false);
    }

    public function GrandoExport() {
        header("Content-type: text/csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        $sor = array(
            '"title"',
            '"seller_product_id"',
            '"status"',
            '"price"',
            '"warranty"',
            '"manufacturer"',
            '"photo_url_1"',
            '"photo_url_2"',
            '"photo_url_3"',
            '"photo_url_4"',
            '"photo_url_5"',
            '"photo_url_6"',
            '"photo_url_7"',
            '"photo_url_8"',
            '"seller_category"',
            '"page_link"',
            '"availability_371"',
            '"description"'
        );
        echo implode(';', $sor) . "\n";

        $tr = \mkw\store::getEm()->getRepository('Entities\Termek');
        $res = $tr->getAllForExport();
        /** @var \Entities\Termek $t */
        foreach ($res as $t) {
            $cimke = $t->getCimkeByCategory(\mkw\store::getParameter(\mkw\consts::MarkaCs));
            $leiras = $t->getLeiras();
            $valtozatok = $t->getValtozatok();
            $vszoveg = '';
            /** @var \Entities\TermekValtozat $v */
            foreach ($valtozatok as $v) {
                if ($v->getElerheto()) {
                    $vszoveg = $vszoveg . '<br>' . $v->getNev() . ' ' . bizformat($t->getBruttoAr($v)) . ' Ft';
                }
            }
            if ($vszoveg) {
                $leiras = $leiras . '<p>Jelenleg elérhető termékváltozatok:' . $vszoveg . '</p>';
            }

            $cimkek = $t->getCimkek();
            $cszoveg = '';
            /** @var \Entities\Termekcimketorzs $c */
            foreach ($cimkek as $c) {
                $cszoveg = $cszoveg . '<br>' . $c->getKategoriaNev() . ': ' . $c->getNev();
            }
            if ($cszoveg) {
                $leiras = $leiras . '<p>' . $cszoveg . '</p>';
            }

            $keptomb = array();
            $kepek = $t->getTermekKepek(true);
            /** @var \Entities\TermekKep $k */
            foreach ($kepek as $k) {
                $keptomb[] = \mkw\store::getFullUrl($k->getUrlLarge(), \mkw\store::getConfigValue('mainurl'));
            }

            $leiras = str_replace("\n", '', $leiras);
            $leiras = str_replace("\r", '', $leiras);
            $leiras = str_replace("\n\r", '', $leiras);
            $leiras = str_replace('"', '""', $leiras);

//        $cimke = false;
            $sor = array(
                '"' . $t->getNev() . '"',
                '"' . $t->getCikkszam() . '"',
                '"1"',
                '"' . number_format($t->getBruttoAr(), 0, ',', '') . '"', //number_format($tetel.bruttoegysarhuf,0,',',' ')
                '"1"',
                '"' . ($cimke ? $cimke->getNev() : '') . '"',
                '"' . \mkw\store::getFullUrl($t->getKepurlLarge(), \mkw\store::getConfigValue('mainurl')) . '"',
                '"' . (array_key_exists(0, $keptomb) ? $keptomb[0] : '') . '"',
                '"' . (array_key_exists(1, $keptomb) ? $keptomb[1] : '') . '"',
                '"' . (array_key_exists(2, $keptomb) ? $keptomb[2] : '') . '"',
                '"' . (array_key_exists(3, $keptomb) ? $keptomb[3] : '') . '"',
                '"' . (array_key_exists(4, $keptomb) ? $keptomb[4] : '') . '"',
                '"' . (array_key_exists(5, $keptomb) ? $keptomb[5] : '') . '"',
                '"' . (array_key_exists(6, $keptomb) ? $keptomb[6] : '') . '"',
                '"' . $t->getTermekfa1Nev() . '"',
                '"' . \mkw\store::getFullUrl('/termek/' . $t->getSlug(), \mkw\store::getConfigValue('mainurl')) . '"',
                '"-1"',
                '"' . $leiras . '"'
            );
            echo implode(';', $sor) . "\n";
        }
    }

    public function OlcsoExport() {
        header("Content-type: text/csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        $sor = array(
            '"title"',
            '"seller_product_id"',
            '"status"',
            '"price"',
            '"warranty"',
            '"manufacturer"',
            '"photo_url_1"',
            '"seller_category"',
            '"page_link"',
            '"availability_371"',
            '"description"'
        );
        echo implode(';', $sor) . "\n";

        $tr = \mkw\store::getEm()->getRepository('Entities\Termek');
        $res = $tr->getAllForExport();
        foreach ($res as $t) {
            $cimke = $t->getCimkeByCategory(\mkw\store::getParameter(\mkw\consts::MarkaCs));
            $leiras = $t->getLeiras();
            $leiras = str_replace("\n", '', $leiras);
            $leiras = str_replace("\r", '', $leiras);
            $leiras = str_replace("\n\r", '', $leiras);
            $leiras = str_replace('"', '""', $leiras);

//        $cimke = false;
            $sor = array(
                '"' . $t->getNev() . '"',
                '"' . $t->getCikkszam() . '"',
                '"1"',
                '"' . number_format($t->getBruttoAr(), 0, ',', '') . '"', //number_format($tetel.bruttoegysarhuf,0,',',' ')
                '"1"',
                '"' . ($cimke ? $cimke->getNev() : '') . '"',
                '"' . \mkw\store::getFullUrl($t->getKepurlLarge(), \mkw\store::getConfigValue('mainurl')) . '"',
                '"' . ($t->getTermekfa1() ? $t->getTermekfa1()->getTeljesNev() : '') . '"',
                '"' . \mkw\store::getFullUrl('/termek/' . $t->getSlug(), \mkw\store::getConfigValue('mainurl')) . '"',
                '"-1"',
                '"' . $leiras . '"'
            );
            echo implode(';', $sor) . "\n";
        }
    }

    public function ShopHunterExport() {
        header("Content-type: text/csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        $sor = array(
            'id',
            'name',
            'description',
            'price',
            'category',
            'image_url',
            'product_url'
        );
        echo implode(';', $sor) . "\n";

        $tr = \mkw\store::getEm()->getRepository('Entities\Termek');
        $res = $tr->getAllForExport();
        foreach ($res as $t) {
            $cimke = $t->getCimkeByCategory(\mkw\store::getParameter(\mkw\consts::MarkaCs));
            $leiras = $t->getLeiras();
            $leiras = str_replace("\n", '', $leiras);
            $leiras = str_replace("\r", '', $leiras);
            $leiras = str_replace("\n\r", '', $leiras);
            $leiras = str_replace('"', '""', $leiras);
            $sor = array(
                '"' . $t->getId() . '"',
                '"' . $t->getNev() . '"',
                '"' . $leiras . '"',
                '"' . number_format($t->getBruttoAr(), 0, ',', '') . '"', //number_format($tetel.bruttoegysarhuf,0,',',' ')
                '"' . $t->getTermekfa1Nev() . '"',
                '"' . \mkw\store::getFullUrl($t->getKepurlLarge(), \mkw\store::getConfigValue('mainurl')) . '"',
                '"' . \mkw\store::getFullUrl('/termek/' . $t->getSlug(), \mkw\store::getConfigValue('mainurl')) . '"'
            );
            echo implode(';', $sor) . "\n";
        }
    }

    public function ArfurkeszExport() {
        header("Content-type: text/csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        $sor = array(
            '"manufacturer"',
            '"name"',
            '"category"',
            '"price"',
            '"product_url"',
            '"image_url"',
            '"description"'
        );
        echo implode(';', $sor) . "\n";

        $sani = new \mkwhelpers\HtmlPurifierSanitizer(array(
            'HTML.Allowed' => 'strong,ul,li,u,i,b,br,em'
        ));

        $tr = \mkw\store::getEm()->getRepository('Entities\Termek');
        $res = $tr->getAllForExport();
        foreach ($res as $t) {
            $cimke = $t->getCimkeByCategory(\mkw\store::getParameter(\mkw\consts::MarkaCs));
            $leiras = $t->getLeiras();
            $leiras = str_replace("\n", '', $leiras);
            $leiras = str_replace("\r", '', $leiras);
            $leiras = str_replace("\n\r", '', $leiras);
            $leiras = str_replace('"', '\"', $leiras);
            $leiras = $sani->sanitize($leiras);
            $sor = array(
                '"' . ($cimke ? $cimke->getNev() : '') . '"',
                '"' . $t->getNev() . '"',
                '"' . $t->getTermekfa1Nev() . '"',
                '"' . number_format($t->getBruttoAr(), 0, ',', '') . '"', //number_format($tetel.bruttoegysarhuf,0,',',' ')
                '"' . \mkw\store::getFullUrl('/termek/' . $t->getSlug(), \mkw\store::getConfigValue('mainurl')) . '"',
                '"' . \mkw\store::getFullUrl($t->getKepurlLarge(), \mkw\store::getConfigValue('mainurl')) . '"',
                '"' . $leiras . '"'
            );
            echo implode(';', $sor) . "\n";
        }
    }

    public function ArmutatoExport() {
        header("Content-type: text/csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        $sor = array(
            'id',
            'url',
            'price',
            'category',
            'picture',
            'name',
            'manufacturer',
            'description',
            'long_description'
        );
        echo implode(';', $sor) . "\n";

        $tr = \mkw\store::getEm()->getRepository('Entities\Termek');
        $res = $tr->getAllForExport();
        foreach ($res as $t) {
            $cimke = $t->getCimkeByCategory(\mkw\store::getParameter(\mkw\consts::MarkaCs));
            $leiras = $t->getLeiras();
            $leiras = str_replace("\n", '', $leiras);
            $leiras = str_replace("\r", '', $leiras);
            $leiras = str_replace("\n\r", '', $leiras);
            $leiras = str_replace('"', '\"', $leiras);
            $sor = array(
                $t->getId(),
                \mkw\store::getFullUrl('/termek/' . $t->getSlug(), \mkw\store::getConfigValue('mainurl')),
                number_format($t->getBruttoAr(), 0, ',', ''),
                $t->getTermekfa1Nev(),
                \mkw\store::getFullUrl($t->getKepurlLarge(), \mkw\store::getConfigValue('mainurl')),
                $t->getNev(),
                ($cimke ? $cimke->getNev() : ''),
                $t->getRovidLeiras(),
                $leiras
            );
            echo implode(';', $sor) . "\n";
        }
    }

    public function ArgepExport() {
        header("Content-type: text/csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        $sor = array(
            'Cikkszám',
            'Terméknév',
            'Termékleírás',
            'BruttóÁr',
            'Fotólink',
            'Terméklink',
            'SzállításiIdő',
            'SzállításiKöltség'
        );
        echo implode('|', $sor) . "\n";

        $tr = \mkw\store::getEm()->getRepository('Entities\Termek');
        $res = $tr->getAllForExport();
        foreach ($res as $t) {

            if ($t->getSzallitasiido()) {
                $szallitasiido = $t->getSzallitasiido();
            }
            else {
                $gyarto = $t->getGyarto();
                if ($gyarto && $gyarto->getSzallitasiido()) {
                    $szallitasiido = $gyarto->getSzallitasiido();
                }
                else {
                    $szallitasiido = 0;
                }
            }

            $leiras = $t->getLeiras();
            $leiras = str_replace("\n", '', $leiras);
            $leiras = str_replace("\r", '', $leiras);
            $leiras = str_replace("\n\r", '', $leiras);
            $leiras = str_replace('"', '""', $leiras);

            $sor = array(
                '"' . $t->getCikkszam() . '"',
                '"' . $t->getNev() . '"',
                '"' . $leiras . '"',
                '"' . number_format($t->getBruttoAr(), 0, ',', '') . '"', //number_format($tetel.bruttoegysarhuf,0,',',' ')
                '"' . \mkw\store::getFullUrl($t->getKepurlLarge(), \mkw\store::getConfigValue('mainurl')) . '"',
                '"' . \mkw\store::getFullUrl('/termek/' . $t->getSlug(), \mkw\store::getConfigValue('mainurl')) . '"',
                '"' . ($szallitasiido ? 'max. ' . $szallitasiido . ' munkanap' : '') . '"',
                '"0"'
            );
            echo implode('|', $sor) . "\n";
        }
    }

    public function ArukeresoExport() {
        header("Content-type: text/csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        $sor = array(
            'manufacturer',
            'name',
            'category',
            'product_url',
            'price',
            'image_url',
            'description',
            'delivery_time',
            'identifier',
            'net_price'
        );
        echo implode("\t", $sor) . "\n";
        $tr = \mkw\store::getEm()->getRepository('Entities\Termek');
        $res = $tr->getAllForExport();
        foreach ($res as $t) {
            $cimke = $t->getCimkeByCategory(\mkw\store::getParameter(\mkw\consts::MarkaCs));

            $leiras = $t->getLeiras();
            $leiras = str_replace("\n", '', $leiras);
            $leiras = str_replace("\r", '', $leiras);
            $leiras = str_replace("\n\r", '', $leiras);
            $leiras = str_replace('"', '""', $leiras);

            if ($t->getSzallitasiido()) {
                $szallitasiido = $t->getSzallitasiido();
            }
            else {
                $gyarto = $t->getGyarto();
                if ($gyarto && $gyarto->getSzallitasiido()) {
                    $szallitasiido = $gyarto->getSzallitasiido();
                }
                else {
                    $szallitasiido = 0;
                }
            }

            $sor = array(
                '"' . ($cimke ? $cimke->getNev() : '') . '"',
                '"' . $t->getNev() . '"',
                '"' . ($t->getTermekfa1() ? $t->getTermekfa1()->getTeljesNev(' > ') : '') . '"',
                '"' . \mkw\store::getFullUrl('/termek/' . $t->getSlug(), \mkw\store::getConfigValue('mainurl')) . '"',
                '"' . number_format($t->getBruttoAr(), 0, ',', '') . '"', //number_format($tetel.bruttoegysarhuf,0,',',' ')
                '"' . \mkw\store::getFullUrl($t->getKepurlLarge(), \mkw\store::getConfigValue('mainurl')) . '"',
                '"' . $leiras . '"',
                '"' . ($szallitasiido ? $szallitasiido . ' munkanap' : '') . '"',
                '"' . $t->getId() . '"',
                '"' . number_format($t->getNettoAr(), 0, ',', '') . '"'
            );
            echo implode("\t", $sor) . "\n";
        }
    }

    public function OlcsobbatExport() {
        header("Content-type: text/csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        $sor = array(
            'manufacturer',
            'name',
            'category',
            'product_url',
            'price',
            'image_url',
            'description'
        );
        echo implode(";", $sor) . "\n";
        $tr = \mkw\store::getEm()->getRepository('Entities\Termek');
        $res = $tr->getAllForExport();
        foreach ($res as $t) {
            $cimke = $t->getCimkeByCategory(\mkw\store::getParameter(\mkw\consts::MarkaCs));

            $leiras = $t->getLeiras();
            $leiras = str_replace("\n", '', $leiras);
            $leiras = str_replace("\r", '', $leiras);
            $leiras = str_replace("\n\r", '', $leiras);
            $leiras = str_replace('"', '""', $leiras);

            if ($t->getSzallitasiido()) {
                $szallitasiido = $t->getSzallitasiido();
            }
            else {
                $gyarto = $t->getGyarto();
                if ($gyarto && $gyarto->getSzallitasiido()) {
                    $szallitasiido = $gyarto->getSzallitasiido();
                }
                else {
                    $szallitasiido = 0;
                }
            }

            $sor = array(
                '"' . ($cimke ? $cimke->getNev() : '') . '"',
                '"' . $t->getNev() . '"',
                '"' . ($t->getTermekfa1() ? $t->getTermekfa1()->getTeljesNev(' > ') : '') . '"',
                '"' . \mkw\store::getFullUrl('/termek/' . $t->getSlug(), \mkw\store::getConfigValue('mainurl')) . '"',
                '"' . number_format($t->getBruttoAr(), 0, ',', '') . '"', //number_format($tetel.bruttoegysarhuf,0,',',' ')
                '"' . \mkw\store::getFullUrl($t->getKepurlLarge(), \mkw\store::getConfigValue('mainurl')) . '"',
                '"' . $leiras . '"'
            );
            echo implode(";", $sor) . "\n";
        }
    }

    private function encstr($str) {
        return mb_convert_encoding($str, 'ISO-8859-2', 'UTF8');
    }

    public function RLBExport() {
        header("Content-type: text/csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        $bizrepo = \mkw\store::getEm()->getRepository('Entities\Bizonylatfej');
        $bt = \mkw\store::getEm()->getRepository('Entities\Bizonylattipus')->find('szamla');

        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('bizonylattipus', '=', $bt);

        $mar = \mkw\store::getParameter(\mkw\consts::RLBUtolsoSzamlaszam);
        if ($mar) {
            $filter->addFilter('id', '>', $mar);
        }

        $r = $bizrepo->getAll($filter, array('id' => 'ASC'));
        foreach ($r as $bizonylat) {
            $mar = $bizonylat->getId();
            $fm = $bizonylat->getFizmod();
            $aossz = $bizrepo->getAFAOsszesito($bizonylat);
            $sor = array(
                $bizonylat->getKeltStr(),
                $bizonylat->getTeljesitesStr(),
                $bizonylat->getEsedekessegStr(),
                $bizonylat->getId(),
                $bizonylat->getPartnerId(),
                $this->encstr($bizonylat->getPartnernev()),
                $this->encstr($bizonylat->getPartnerirszam()),
                $this->encstr($bizonylat->getPartnervaros()),
                $this->encstr($bizonylat->getPartnerutca()),
                $this->encstr('Értékesítés árbevétele'),
                ($fm->getTipus() == 'P' ? 1 : 2)
            );

            $i = 1;
            foreach ($aossz as $ao) {
                $sor[] = $ao['rlbkod'];
                $sor[] = $ao['netto'];
                $sor[] = $ao['afa'];
                $i++;
                if ($i > 4) {
                    break;
                }
            }
            for ($i; $i <= 4; $i++) {
                $sor[] = 0;
                $sor[] = 0;
                $sor[] = 0;
            }
            echo implode(';', $sor) . "\n";
        }
        if ($mar) {
            \mkw\store::setParameter(\mkw\consts::RLBUtolsoSzamlaszam, $mar);
        }
    }

}
