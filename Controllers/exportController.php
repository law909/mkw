<?php
namespace Controllers;

use Entities\Termek;
use mkwhelpers\FilterDescriptor;

class exportController extends \mkwhelpers\Controller {

    public function view() {
        $view = $this->createView('exports.tpl');
        $view->setVar('pagetitle', t('Exportok'));
        $view->printTemplateResult(false);
    }

    private function kellKeszletetNezni($pid) {
        $pr = \mkw\store::getEm()->getRepository('Entities\Partner');
        if ($pid) {
            /** @var \Entities\Partner $gyarto */
            $gyarto = $pr->find($pid);
            return $gyarto->isExportbacsakkeszlet();
        }
        return false;
    }

    public function VateraHeadExport() {
        header("Content-type: text/csv");
        header("Pragma: no-cache");
        header("Expires: 0");
        header("Content-Disposition: attachment; filename=vateramkw.csv");
    }

    public function VateraExport() {

        $this->VateraHeadExport();

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
            $termekmehet = true;
            $keszletetnezni = $this->kellKeszletetNezni($t->getGyartoId());
            $valtozatok = $t->getValtozatok();
            if ($keszletetnezni) {
                $termekmehet = false;
                foreach ($valtozatok as $v) {
                    /** @var \Entities\TermekValtozat $v */
                    if ($v->getElerheto() && $v->getKeszlet() > 0) {
                        $termekmehet = true;
                    }
                }
                if ($t->getKeszlet() > 0) {
                    $termekmehet = true;
                }
            }
            if ($termekmehet) {
                $cimke = $t->getCimkeByCategory(\mkw\store::getParameter(\mkw\consts::MarkaCs));
                $leiras = $t->getLeiras();
                if (count($valtozatok)) {
                    $vszoveg = '';
                    /** @var \Entities\TermekValtozat $v */
                    foreach ($valtozatok as $v) {
                        if ($v->getElerheto() && (($keszletetnezni && $v->getKeszlet() > 0) || (!$keszletetnezni))) {

                            $szallszoveg = false;
                            $szallitasiido = $t->calcSzallitasiido($v);

                            if ($szallitasiido) {
                                $szallszoveg = '<b>Szállítási határidő ' . $szallitasiido . ' munkanap.</b>';
                            }
                            $vszoveg = $vszoveg . '<br>' . $v->getNev() . ' ' . bizformat($t->getBruttoAr($v)) . ' Ft';

                            if ($szallszoveg) {
                                $vszoveg = $vszoveg . ' ' . $szallszoveg;
                            }
                        }
                    }
                    if ($vszoveg) {
                        $leiras = $leiras . '<p>Jelenleg elérhető termékváltozatok:' . $vszoveg . '</p>';
                    }
                }
                else {
                    $szallitasiido = $t->calcSzallitasiido();
                    if ($szallitasiido) {
                        $leiras = $leiras . '<p><b>Szállítási határidő ' . $szallitasiido . ' munkanap.</b></p>';
                    }
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

                $leiras = $leiras . '<p><b>A szállítási határidő Foxpost szállítási módnál 1-2 munkanappal meghosszabbodhat.</b></p>';

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
            $termekmehet = true;
            $keszletetnezni = $this->kellKeszletetNezni($t->getGyartoId());
            $valtozatok = $t->getValtozatok();
            if ($keszletetnezni) {
                $termekmehet = false;
                foreach ($valtozatok as $v) {
                    /** @var \Entities\TermekValtozat $v */
                    if ($v->getElerheto() && $v->getKeszlet() > 0) {
                        $termekmehet = true;
                    }
                }
                if ($t->getKeszlet() > 0) {
                    $termekmehet = true;
                }
            }
            if ($termekmehet) {
                $cimke = $t->getCimkeByCategory(\mkw\store::getParameter(\mkw\consts::MarkaCs));
                $leiras = $t->getLeiras();
                if (count($valtozatok)) {
                    $vszoveg = '';
                    /** @var \Entities\TermekValtozat $v */
                    foreach ($valtozatok as $v) {
                        if ($v->getElerheto() && (($keszletetnezni && $v->getKeszlet() > 0) || (!$keszletetnezni))) {

                            $szallszoveg = false;
                            $szallitasiido = $t->calcSzallitasiido($v);

                            if ($szallitasiido) {
                                $szallszoveg = '<b>Szállítási határidő ' . $szallitasiido . ' munkanap.</b>';
                            }
                            $vszoveg = $vszoveg . '<br>' . $v->getNev() . ' ' . bizformat($t->getBruttoAr($v)) . ' Ft';

                            if ($szallszoveg) {
                                $vszoveg = $vszoveg . ' ' . $szallszoveg;
                            }
                        }
                    }
                    if ($vszoveg) {
                        $leiras = $leiras . '<p>Jelenleg elérhető termékváltozatok:' . $vszoveg . '</p>';
                    }
                }
                else {
                    $szallitasiido = $t->calcSzallitasiido();
                    if ($szallitasiido) {
                        $leiras = $leiras . '<p><b>Szállítási határidő ' . $szallitasiido . ' munkanap.</b></p>';
                    }
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

                $leiras = $leiras . '<p><b>A szállítási határidő Foxpost szállítási módnál 1-2 munkanappal meghosszabbodhat.</b></p>';

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
            $termekmehet = true;
            $keszletetnezni = $this->kellKeszletetNezni($t->getGyartoId());
            $valtozatok = $t->getValtozatok();
            if ($keszletetnezni) {
                $termekmehet = false;
                foreach ($valtozatok as $v) {
                    /** @var \Entities\TermekValtozat $v */
                    if ($v->getElerheto() && $v->getKeszlet() > 0) {
                        $termekmehet = true;
                    }
                }
                if ($t->getKeszlet() > 0) {
                    $termekmehet = true;
                }
            }
            if ($termekmehet) {
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
            $termekmehet = true;
            $keszletetnezni = $this->kellKeszletetNezni($t->getGyartoId());
            $valtozatok = $t->getValtozatok();
            if ($keszletetnezni) {
                $termekmehet = false;
                foreach ($valtozatok as $v) {
                    /** @var \Entities\TermekValtozat $v */
                    if ($v->getElerheto() && $v->getKeszlet() > 0) {
                        $termekmehet = true;
                    }
                }
                if ($t->getKeszlet() > 0) {
                    $termekmehet = true;
                }
            }
            if ($termekmehet) {
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
            $termekmehet = true;
            $keszletetnezni = $this->kellKeszletetNezni($t->getGyartoId());
            $valtozatok = $t->getValtozatok();
            if ($keszletetnezni) {
                $termekmehet = false;
                foreach ($valtozatok as $v) {
                    /** @var \Entities\TermekValtozat $v */
                    if ($v->getElerheto() && $v->getKeszlet() > 0) {
                        $termekmehet = true;
                    }
                }
                if ($t->getKeszlet() > 0) {
                    $termekmehet = true;
                }
            }
            if ($termekmehet) {
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
            $termekmehet = true;
            $keszletetnezni = $this->kellKeszletetNezni($t->getGyartoId());
            $valtozatok = $t->getValtozatok();
            if ($keszletetnezni) {
                $termekmehet = false;
                foreach ($valtozatok as $v) {
                    /** @var \Entities\TermekValtozat $v */
                    if ($v->getElerheto() && $v->getKeszlet() > 0) {
                        $termekmehet = true;
                    }
                }
                if ($t->getKeszlet() > 0) {
                    $termekmehet = true;
                }
            }
            if ($termekmehet) {
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
        /** @var \Entities\Termek $t */
        foreach ($res as $t) {

            $termekmehet = true;
            $keszletetnezni = $this->kellKeszletetNezni($t->getGyartoId());
            $valtozatok = $t->getValtozatok();
            if ($keszletetnezni) {
                $termekmehet = false;
                foreach ($valtozatok as $v) {
                    /** @var \Entities\TermekValtozat $v */
                    if ($v->getElerheto() && $v->getKeszlet() > 0) {
                        $termekmehet = true;
                    }
                }
                if ($t->getKeszlet() > 0) {
                    $termekmehet = true;
                }
            }
            if ($termekmehet) {
                $szallitasiido = $t->calcSzallitasiido();

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
    }

    public function YuspExport() {
        header("Content-type: text/csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        $sor = array(
            'Id',
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
        /** @var \Entities\Termek $t */
        foreach ($res as $t) {

            $termekmehet = true;
            $keszletetnezni = $this->kellKeszletetNezni($t->getGyartoId());
            $valtozatok = $t->getValtozatok();
            if ($keszletetnezni) {
                $termekmehet = false;
                foreach ($valtozatok as $v) {
                    /** @var \Entities\TermekValtozat $v */
                    if ($v->getElerheto() && $v->getKeszlet() > 0) {
                        $termekmehet = true;
                    }
                }
                if ($t->getKeszlet() > 0) {
                    $termekmehet = true;
                }
            }
            if ($termekmehet) {
                $szallitasiido = $t->calcSzallitasiido();

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
                    '"' . \mkw\store::getFullUrl($t->getKepurlLarge(), \mkw\store::getConfigValue('mainurl')) . '"',
                    '"' . \mkw\store::getFullUrl('/termek/' . $t->getSlug(), \mkw\store::getConfigValue('mainurl')) . '"',
                    '"' . ($szallitasiido ? 'max. ' . $szallitasiido . ' munkanap' : '') . '"',
                    '"0"'
                );
                echo implode('|', $sor) . "\n";
            }
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
        /** @var \Entities\Termek $t */
        foreach ($res as $t) {
            $termekmehet = true;
            $keszletetnezni = $this->kellKeszletetNezni($t->getGyartoId());
            $valtozatok = $t->getValtozatok();
            if ($keszletetnezni) {
                $termekmehet = false;
                foreach ($valtozatok as $v) {
                    /** @var \Entities\TermekValtozat $v */
                    if ($v->getElerheto() && $v->getKeszlet() > 0) {
                        $termekmehet = true;
                    }
                }
                if ($t->getKeszlet() > 0) {
                    $termekmehet = true;
                }
            }
            if ($termekmehet) {
                $cimke = $t->getCimkeByCategory(\mkw\store::getParameter(\mkw\consts::MarkaCs));

                $leiras = $t->getLeiras();
                $leiras = str_replace("\n", '', $leiras);
                $leiras = str_replace("\r", '', $leiras);
                $leiras = str_replace("\n\r", '', $leiras);
                $leiras = str_replace('"', '""', $leiras);

                $szallitasiido = $t->calcSzallitasiido();

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
        /** @var \Entities\Termek $t */
        foreach ($res as $t) {
            $termekmehet = true;
            $keszletetnezni = $this->kellKeszletetNezni($t->getGyartoId());
            $valtozatok = $t->getValtozatok();
            if ($keszletetnezni) {
                $termekmehet = false;
                foreach ($valtozatok as $v) {
                    /** @var \Entities\TermekValtozat $v */
                    if ($v->getElerheto() && $v->getKeszlet() > 0) {
                        $termekmehet = true;
                    }
                }
                if ($t->getKeszlet() > 0) {
                    $termekmehet = true;
                }
            }
            if ($termekmehet) {
                $cimke = $t->getCimkeByCategory(\mkw\store::getParameter(\mkw\consts::MarkaCs));

                $leiras = $t->getLeiras();
                $leiras = str_replace("\n", '', $leiras);
                $leiras = str_replace("\r", '', $leiras);
                $leiras = str_replace("\n\r", '', $leiras);
                $leiras = str_replace('"', '""', $leiras);

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

    public function FCMotoExport() {

        $kodszotarrepo = \mkw\store::getEm()->getRepository('Entities\TermekValtozatErtekKodszotar');

        $ertek1 = array_merge(
            \mkw\store::getEm()->getRepository('Entities\TermekValtozat')->getDistinctErtek1(),
            \mkw\store::getEm()->getRepository('Entities\TermekValtozat')->getDistinctErtek2());

        foreach ($ertek1 as $eee1) {
            $e1 = $eee1['ertek'];

            $kodsz = $kodszotarrepo->findOneBy(array('ertek' => $e1));
            if (!$kodsz) {
                $kodsz = new \Entities\TermekValtozatErtekKodszotar();
                $kodsz->setErtek($e1);
                \mkw\store::getEm()->persist($kodsz);
                \mkw\store::getEm()->flush();
                $kodsz->setKod($kodsz->getId());
                \mkw\store::getEm()->persist($kodsz);
                \mkw\store::getEm()->flush();
            }
        }

        header("Content-type: text/csv");
        header("Pragma: no-cache");
        header("Expires: 0");
        header("Content-Disposition: attachment; filename=fcmoto.csv");

        $sor = array(
            'Article Number',
            'Color Number',
            'Article Name',
            'Color',
            'Size',
            'Stock',
            'EAN Code',
            'Description',
            'Image URL'
        );
        echo implode(";", $sor) . "\n";

        $tr = \mkw\store::getEm()->getRepository('Entities\Termek');

        $filter = new FilterDescriptor();
        $karkod = $this->getRepo('Entities\TermekFa')->getKarkod(\mkw\store::getParameter(\mkw\consts::MugenraceKatId));
        if ($karkod) {
            $filter->addFilter(array('termekfa1karkod', 'termekfa2karkod', 'termekfa3karkod'), 'LIKE', $karkod . '%'); // Mugenrace
        }

        $res = $tr->getAllValtozatForExport($filter, \mkw\store::getParameter(\mkw\consts::Locale));

//        $eur = \mkw\store::getEm()->getRepository('Entities\Valutanem')->findOneBy(array('nev' => 'EUR'));

        /** @var \Entities\Termek $t */
        foreach ($res as $t) {

            $valtozatok = $t->getValtozatok();
            if ($valtozatok) {
                /** @var \Entities\TermekValtozat $valt */
                foreach ($valtozatok as $valt) {
                    $keszlet = $valt->getKeszlet() - $valt->getFoglaltMennyiseg();
                    if ($keszlet < 0) {
                        $keszlet = 0;
                    }
                    $sor = array(
                        '"' . $t->getCikkszam() . '"',
                        '"' . $kodszotarrepo->translate($valt->getSzin()) . '"',
                        '"' . $t->getNev() . '"',
                        '"' . $valt->getSzin() . '"',
                        '"' . $valt->getMeret() . '"',
                        '"' . $keszlet . '"',
                        '"' . $valt->getVonalkod() . '"',
                        '"' . preg_replace("/(\t|\n|\r)+/", "", $t->getLeiras()) . '"',
                        '"' . \mkw\store::getFullUrl($valt->getKepurl(), \mkw\store::getConfigValue('mainurl')) . '"'
                        //'"' . $t->getBruttoAr($valt, null, $eur, 'eurar') . '"'
                    );
                    echo implode(";", $sor) . "\n";
                }
            }
            else {
                $keszlet = $t->getKeszlet() - $t->getFoglaltMennyiseg();
                if ($keszlet < 0) {
                    $keszlet = 0;
                }
                $sor = array(
                    '"' . $t->getCikkszam() . '"',
                    '""',
                    '"' . $t->getNev() . '"',
                    '""',
                    '""',
                    '"' . $keszlet . '"',
                    '"' . $t->getVonalkod() . '"',
                    '"' . preg_replace("/(\t|\n|\r)+/", "", $t->getLeiras()) . '"',
                    '"' . \mkw\store::getFullUrl($t->getKepurl(), \mkw\store::getConfigValue('mainurl')) . '"'
                    //'"' . $t->getBruttoAr(null, null, $eur, 'eurar') . '"'
                );
                echo implode(";", $sor) . "\n";
            }
        }
    }

    public function MugenraceExport() {

        $maxstock = $this->params->getNumRequestParam('max', 0);

        $kodszotarrepo = \mkw\store::getEm()->getRepository('Entities\TermekValtozatErtekKodszotar');

        $ertek1 = array_merge(
            \mkw\store::getEm()->getRepository('Entities\TermekValtozat')->getDistinctErtek1(),
            \mkw\store::getEm()->getRepository('Entities\TermekValtozat')->getDistinctErtek2());

        foreach ($ertek1 as $eee1) {
            $e1 = $eee1['ertek'];

            $kodsz = $kodszotarrepo->findOneBy(array('ertek' => $e1));
            if (!$kodsz) {
                $kodsz = new \Entities\TermekValtozatErtekKodszotar();
                $kodsz->setErtek($e1);
                \mkw\store::getEm()->persist($kodsz);
                \mkw\store::getEm()->flush();
                $kodsz->setKod($kodsz->getId());
                \mkw\store::getEm()->persist($kodsz);
                \mkw\store::getEm()->flush();
            }
        }

        header("Content-type: text/csv");
        header("Pragma: no-cache");
        header("Expires: 0");
        header("Content-Disposition: attachment; filename=mugenrace.csv");

        $sor = array(
            'Article Number',
            'Color Number',
            'Article Name',
            'Color',
            'Size',
            'Stock',
            'EAN Code',
            'Description',
            'Image URL'
//            'Price'
        );
        echo implode(";", $sor) . "\n";

        $tr = \mkw\store::getEm()->getRepository('Entities\Termek');

        $filter = new FilterDescriptor();
        $karkod = $this->getRepo('Entities\TermekFa')->getKarkod(\mkw\store::getParameter(\mkw\consts::MugenraceKatId));
        if ($karkod) {
            $filter->addFilter(array('termekfa1karkod', 'termekfa2karkod', 'termekfa3karkod'), 'LIKE', $karkod . '%'); // Mugenrace
        }

        $res = $tr->getAllValtozatForExport($filter, \mkw\store::getParameter(\mkw\consts::Locale));

        $eur = \mkw\store::getEm()->getRepository('Entities\Valutanem')->findOneBy(array('nev' => 'EUR'));

        /** @var \Entities\Termek $t */
        foreach ($res as $t) {

            $valtozatok = $t->getValtozatok();
            if ($valtozatok) {
                /** @var \Entities\TermekValtozat $valt */
                foreach ($valtozatok as $valt) {
                    $keszlet = $valt->getKeszlet() - $valt->getFoglaltMennyiseg();
                    if ($keszlet < 0) {
                        $keszlet = 0;
                    }
                    if ($maxstock > 0) {
                        $keszlet = min($keszlet, $maxstock);
                    }
                    $sor = array(
                        '"' . $t->getCikkszam() . '"',
                        '"' . $kodszotarrepo->translate($valt->getSzin()) . '"',
                        '"' . $t->getNev() . '"',
                        '"' . $valt->getSzin() . '"',
                        '"' . $valt->getMeret() . '"',
                        '"' . $keszlet . '"',
                        '"' . (string)$valt->getVonalkod() . '"',
                        '"' . preg_replace("/(\t|\n|\r)+/", "", $t->getLeiras()) . '"',
                        '"' . \mkw\store::getFullUrl($valt->getKepurl(), \mkw\store::getConfigValue('mainurl')) . '"'
//                        '"' . $t->getNettoAr($valt, null, $eur, 'eurar') . '"'
                    );
                    echo implode(";", $sor) . "\n";
                }
            }
            else {
                $keszlet = $t->getKeszlet() - $t->getFoglaltMennyiseg();
                if ($keszlet < 0) {
                    $keszlet = 0;
                }
                if ($maxstock > 0) {
                    $keszlet = min($keszlet, $maxstock);
                }
                $sor = array(
                    '"' . $t->getCikkszam() . '"',
                    '""',
                    '"' . $t->getNev() . '"',
                    '""',
                    '""',
                    '"' . $keszlet . '"',
                    '"' . (string)$t->getVonalkod() . '"',
                    '"' . preg_replace("/(\t|\n|\r)+/", "", $t->getLeiras()) . '"',
                    '"' . \mkw\store::getFullUrl($t->getKepurl(), \mkw\store::getConfigValue('mainurl')) . '"'
//                    '"' . $t->getNettoAr(null, null, $eur, 'eurar') . '"'
                );
                echo implode(";", $sor) . "\n";
            }
        }
    }

    public function SuperzonehuExport() {

        $maxstock = $this->params->getNumRequestParam('max', 0);

        $kodszotarrepo = \mkw\store::getEm()->getRepository('Entities\TermekValtozatErtekKodszotar');

        $ertek1 = array_merge(
            \mkw\store::getEm()->getRepository('Entities\TermekValtozat')->getDistinctErtek1(),
            \mkw\store::getEm()->getRepository('Entities\TermekValtozat')->getDistinctErtek2());

        foreach ($ertek1 as $eee1) {
            $e1 = $eee1['ertek'];

            $kodsz = $kodszotarrepo->findOneBy(array('ertek' => $e1));
            if (!$kodsz) {
                $kodsz = new \Entities\TermekValtozatErtekKodszotar();
                $kodsz->setErtek($e1);
                \mkw\store::getEm()->persist($kodsz);
                \mkw\store::getEm()->flush();
                $kodsz->setKod($kodsz->getId());
                \mkw\store::getEm()->persist($kodsz);
                \mkw\store::getEm()->flush();
            }
        }

        $huf = \mkw\store::getEm()->getRepository('Entities\Valutanem')->findOneBy(array('nev' => 'HUF'));

        $tr = \mkw\store::getEm()->getRepository('Entities\Termek');

        $res = $tr->getSuperzonehuExport();

//        $eur = \mkw\store::getEm()->getRepository('Entities\Valutanem')->findOneBy(array('nev' => 'EUR'));

        $sor = [];
        /** @var \Entities\Termek $t */
        foreach ($res as $t) {

            $ford = $t->getTranslationsArray();

            $valtozatok = $t->getValtozatok();
            if ($valtozatok) {
                /** @var \Entities\TermekValtozat $valt */
                foreach ($valtozatok as $valt) {
                    $keszlet = $valt->getKeszlet() - $valt->getFoglaltMennyiseg();
                    if ($keszlet < 0) {
                        $keszlet = 0;
                    }
                    if ($maxstock > 0) {
                        $keszlet = min($keszlet, $maxstock);
                    }
                    $sor[] = [
                        'categoryId' => $t->getTermekfa1Id(),
                        'category' => $t->getTermekfa1()->getTeljesNev(),
                        'categoryVisible' => $t->getTermekfa1()->getLathato3(),
                        'articleNumber' => $t->getCikkszam(),
                        'articleName' => $t->getNev(),
                        'articleNameEN' => $ford['en_us']['nev'],
                        'articleNameIT' => $ford['it_it']['nev'],
                        'color' => $valt->getSzin(),
                        'size' => $valt->getMeret(),
                        'inactive' => $t->getInaktiv(),
                        'visible' => ($t->getLathato3() && $valt->getLathato3()),
                        'stock' => $keszlet,
                        'EANcode' => (string)$valt->getVonalkod(),
                        'description' => preg_replace("/(\t|\n|\r)+/", "", $t->getLeiras()),
                        'descriptionEN' => preg_replace("/(\t|\n|\r)+/", "", $ford['en_us']['leiras']),
                        'descriptionIT' => preg_replace("/(\t|\n|\r)+/", "", $ford['it_it']['leiras']),
                        'imageUrl' => ($valt->getKepurl() ? \mkw\store::getFullUrl($valt->getKepurl(), \mkw\store::getConfigValue('mainurl')) : ''),
                        'price' => $t->getBruttoAr($valt, null, $huf, \mkw\store::getParameter(\mkw\consts::Webshop3Price)),
                        'discountPrice' => $t->getBruttoAr($valt, null, $huf, \mkw\store::getParameter(\mkw\consts::Webshop3Discount)),
                        'articleId' => $t->getId(),
                        'variantId' => $valt->getId()
                    ];
                }
            }
            else {
                $keszlet = $t->getKeszlet() - $t->getFoglaltMennyiseg();
                if ($keszlet < 0) {
                    $keszlet = 0;
                }
                if ($maxstock > 0) {
                    $keszlet = min($keszlet, $maxstock);
                }
                $sor[] = [
                    'categoryId' => $t->getTermekfa1Id(),
                    'category' => $t->getTermekfa1()->getTeljesNev(),
                    'categoryVisible' => $t->getTermekfa1()->getLathato3(),
                    'articleNumber' => $t->getCikkszam(),
                    'articleName' => $t->getNev(),
                    'articleNameEN' => $ford['en_us']['nev'],
                    'articleNameIT' => $ford['it_it']['nev'],
                    'color' => '',
                    'size' => '',
                    'inactive' => $t->getInaktiv(),
                    'visible' => $t->getLathato3(),
                    'stock' => $keszlet,
                    'EANcode' => (string)$t->getVonalkod(),
                    'description' => preg_replace("/(\t|\n|\r)+/", "", $t->getLeiras()),
                    'descriptionEN' => preg_replace("/(\t|\n|\r)+/", "", $ford['en_us']['leiras']),
                    'descriptionIT' => preg_replace("/(\t|\n|\r)+/", "", $ford['it_it']['leiras']),
                    'imageUrl' => ($t->getKepurl() ? \mkw\store::getFullUrl($t->getKepurl(), \mkw\store::getConfigValue('mainurl')) : '' ),
                    'price' => $t->getBruttoAr(null, null, $huf, \mkw\store::getParameter(\mkw\consts::Webshop3Price)),
                    'discountPrice' => $t->getBruttoAr(null, null, $huf, \mkw\store::getParameter(\mkw\consts::Webshop3Discount)),
                    'articleId' => $t->getId(),
                    'variantId' => ''
                ];
            }
        }
        header("Content-type: application/json");
        header("Pragma: no-cache");
        header("Expires: 0");
        header("Content-Disposition: attachment; filename=superzone.json");
        echo json_encode($sor);
    }

    public function DepoExport() {
        header("Content-type: text/xml");
        header("Pragma: no-cache");
        header("Expires: 0");

        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<products>';
        echo '<version>1.0</version>';

        $tr = \mkw\store::getEm()->getRepository('Entities\Termek');
        $res = $tr->getAllForExport();
        /** @var \Entities\Termek $t */
        foreach ($res as $t) {
            $termekmehet = true;
            $keszletetnezni = $this->kellKeszletetNezni($t->getGyartoId());
            $valtozatok = $t->getValtozatok();
            if ($keszletetnezni) {
                $termekmehet = false;
                foreach ($valtozatok as $v) {
                    /** @var \Entities\TermekValtozat $v */
                    if ($v->getElerheto() && $v->getKeszlet() > 0) {
                        $termekmehet = true;
                    }
                }
                if ($t->getKeszlet() > 0) {
                    $termekmehet = true;
                }
            }
            if ($termekmehet) {
                $cimke = $t->getCimkeByCategory(\mkw\store::getParameter(\mkw\consts::MarkaCs));

                $szallitasiido = $t->calcSzallitasiido();

                $leiras = $t->getRovidleiras();

                echo '<product>';
                echo '<id><![CDATA[' . $t->getId() . ']]></id>';
                echo '<manufacturer><![CDATA[' . ($cimke ? $cimke->getNev() : '') . ']]></manufacturer>';
                echo '<name><![CDATA[' . $t->getNev() . ']]></name>';
                echo '<category><![CDATA[' . $t->getTermekfa1Nev() . ']]></category>';
                echo '<price>' . number_format($t->getBruttoAr(), 0, ',', '') . '</price>';
                echo '<description><![CDATA[' . $leiras . ']]></description>';
                echo '<photoURL><![CDATA[' . \mkw\store::getFullUrl($t->getKepurlLarge(), \mkw\store::getConfigValue('mainurl')) . ']]></photoURL>';
                echo '<productURL><![CDATA[' . \mkw\store::getFullUrl('/termek/' . $t->getSlug(), \mkw\store::getConfigValue('mainurl')) . ']]></productURL>';
                echo '<delivery_time><![CDATA[' . $szallitasiido . ']]></delivery_time>';
                echo '<delivery_cost><![CDATA[]]></delivery_cost>';
                echo '</product>';
            }
        }
        echo '</products>';
    }

}
