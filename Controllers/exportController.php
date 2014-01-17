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

}
