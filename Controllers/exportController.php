<?php
namespace Controllers;
use mkw\store;

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
            '"seller_category"',
            '"page_link"',
            '"availability_371"',
            '"description"'
        );
        echo implode(';', $sor) . "\n";

        $tr = \mkw\Store::getEm()->getRepository('Entities\Termek');
        $res = $tr->getAllForExport();
        foreach($res as $t) {
            $cimke = $t->getCimkeByCategory(\mkw\Store::getParameter(\mkw\consts::MarkaCs));
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
                '"' . \mkw\Store::getFullUrl($t->getKepurlLarge(), \mkw\Store::getConfigValue('mainurl')) . '"',
                '"' . $t->getTermekfa1Nev() . '"',
                '"' . \mkw\Store::getFullUrl('/termek/' . $t->getSlug(), \mkw\Store::getConfigValue('mainurl')). '"',
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

        $tr = \mkw\Store::getEm()->getRepository('Entities\Termek');
        $res = $tr->getAllForExport();
        foreach($res as $t) {
            $cimke = $t->getCimkeByCategory(\mkw\Store::getParameter(\mkw\consts::MarkaCs));
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
                '"' . \mkw\Store::getFullUrl($t->getKepurlLarge(), \mkw\Store::getConfigValue('mainurl')) . '"',
                '"' . \mkw\Store::getFullUrl('/termek/' . $t->getSlug(), \mkw\Store::getConfigValue('mainurl')). '"'
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

        $tr = \mkw\Store::getEm()->getRepository('Entities\Termek');
        $res = $tr->getAllForExport();
        foreach($res as $t) {
            $cimke = $t->getCimkeByCategory(\mkw\Store::getParameter(\mkw\consts::MarkaCs));
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
                '"' . \mkw\Store::getFullUrl('/termek/' . $t->getSlug(), \mkw\Store::getConfigValue('mainurl')). '"',
                '"' . \mkw\Store::getFullUrl($t->getKepurlLarge(), \mkw\Store::getConfigValue('mainurl')) . '"',
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

        $tr = \mkw\Store::getEm()->getRepository('Entities\Termek');
        $res = $tr->getAllForExport();
        foreach($res as $t) {
            $cimke = $t->getCimkeByCategory(\mkw\Store::getParameter(\mkw\consts::MarkaCs));
            $leiras = $t->getLeiras();
            $leiras = str_replace("\n", '', $leiras);
            $leiras = str_replace("\r", '', $leiras);
            $leiras = str_replace("\n\r", '', $leiras);
            $leiras = str_replace('"', '\"', $leiras);
            $sor = array(
                $t->getId(),
                \mkw\Store::getFullUrl('/termek/' . $t->getSlug(), \mkw\Store::getConfigValue('mainurl')),
                number_format($t->getBruttoAr(), 0, ',', ''),
                $t->getTermekfa1Nev(),
                \mkw\Store::getFullUrl($t->getKepurlLarge(), \mkw\Store::getConfigValue('mainurl')),
                $t->getNev(),
                ($cimke ? $cimke->getNev() : ''),
                $t->getRovidLeiras(),
                $leiras
            );
            echo implode(';', $sor) . "\n";
        }
    }

    private function encstr($str) {
        return mb_convert_encoding($str, 'ISO-8859-2', 'UTF8');
    }

    public function RLBExport() {
        header("Content-type: text/csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        $bizrepo = \mkw\Store::getEm()->getRepository('Entities\Bizonylatfej');
        $bt = \mkw\Store::getEm()->getRepository('Entities\Bizonylattipus')->find('szamla');

        $filter = array();
        $filter['fields'][] = 'bizonylattipus';
        $filter['clauses'][] = '=';
        $filter['values'][] = $bt;

        $mar = \mkw\Store::getParameter(\mkw\consts::RLBUtolsoSzamlaszam);
        if ($mar) {
            $filter['fields'][] = 'id';
            $filter['clauses'][] = '>';
            $filter['values'][] = $mar;
        }

        $r = $bizrepo->getAll($filter, array('id' => 'ASC'));
        foreach($r as $bizonylat) {
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
            foreach($aossz as $ao) {
                $sor[] = $ao['rlbkod'];
                $sor[] = $ao['netto'];
                $sor[] = $ao['afa'];
                $i++;
                if ($i > 4) {
                    break;
                }
            }
            for($i; $i<=4; $i++) {
                $sor[] = 0;
                $sor[] = 0;
                $sor[] = 0;
            }
            echo implode(';', $sor) . "\n";
        }
        if ($mar) {
            \mkw\Store::setParameter(\mkw\consts::RLBUtolsoSzamlaszam, $mar);
        }
    }

}
