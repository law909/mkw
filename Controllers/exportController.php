<?php
namespace Controllers;
use mkw\store;

class exportController extends \mkwhelpers\Controller {

    public function view() {
        $view = $this->createView('exports.tpl');
        $view->setVar('pagetitle', t('Exportok'));
        $view->printTemplateResult();
    }

    public function GrandoExport() {
        $handle = fopen('grando_feed.csv', 'w');
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
        fwrite($handle, implode(',', $sor) . "\n");

        $tr = \mkw\Store::getEm()->getRepository('Entities\Termek');
        $res = $tr->getAllForExport();
        foreach($res as $t) {
            $cimke = $t->getCimkeByCategory(\mkw\Store::getParameter(\mkw\consts::MarkaCs));
            $leiras = $t->getLeiras();
            $leiras = str_replace("\n", '', $leiras);
            $leiras = str_replace("\r", '', $leiras);
            $leiras = str_replace("\n\r", '', $leiras);

//        $cimke = false;
            $sor = array(
                '"' . $t->getNev() . '"',
                '"' . $t->getCikkszam() . '"',
                '"1"',
                '"' . number_format($t->getBruttoAr(), 0, ',', '') . '"', //number_format($tetel.bruttoegysarhuf,0,',',' ')
                '"1"',
                '"' . ($cimke ? $cimke->getNev() : '') . '"',
                '"' . $t->getKepurlLarge() . '"',
                '"' . $t->getTermekfa1Nev() . '"',
                '"' . \mkw\Store::getFullUrl($t->getSlug()). '"',
                '"-1"',
                '"' . $leiras . '"'
            );
            fwrite($handle, implode(',', $sor) . "\n");
        }
        fclose($handle);

        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=grando_feed.csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        readfile('grando_feed.csv');

        \unlink('grando_feed.csv');
    }
}
