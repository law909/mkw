<?php

namespace Controllers;

use Doctrine\ORM\Query\ResultSetMapping;
use Entities\Partner;
use Entities\SzallitasimodHatar;
use Entities\Termek;
use Entities\TermekFa;
use Entities\TermekKep;
use Entities\TermekValtozat;
use Entities\TermekValtozatErtek;
use Entities\TermekValtozatErtekRepository;
use mkwhelpers\FilterDescriptor;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class exportController extends \mkwhelpers\Controller
{

    public function view()
    {
        $view = $this->createView('exports.tpl');
        $view->setVar('pagetitle', t('Exportok'));
        $view->printTemplateResult(false);
    }

    private function kellKeszletetNezni($pid)
    {
        $pr = \mkw\store::getEm()->getRepository('Entities\Partner');
        if ($pid) {
            /** @var \Entities\Partner $gyarto */
            $gyarto = $pr->find($pid);
            return $gyarto->isExportbacsakkeszlet();
        }
        return false;
    }

    public function VateraHeadExport()
    {
        header("Content-type: text/csv");
        header("Pragma: no-cache");
        header("Expires: 0");
        header("Content-Disposition: attachment; filename=vateramkw.csv");
    }

    public function VateraExport()
    {
        $this->VateraHeadExport();

        $sor = [
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
        ];
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
                } else {
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

                //$leiras = $leiras . '<p><b>A szállítási határidő Foxpost szállítási módnál 1-2 munkanappal meghosszabbodhat.</b></p>';

                $keptomb = [];
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
                $sor = [
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
                ];
                echo implode(';', $sor) . "\n";
            }
        }
    }

    public function GrandoExport()
    {
        header("Content-type: text/csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        $sor = [
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
        ];
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
                } else {
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

                $keptomb = [];
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
                $sor = [
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
                ];
                echo implode(';', $sor) . "\n";
            }
        }
    }

    public function OlcsoExport()
    {
        header("Content-type: text/csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        $sor = [
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
        ];
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
                $sor = [
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
                ];
                echo implode(';', $sor) . "\n";
            }
        }
    }

    public function ShopHunterExport()
    {
        header("Content-type: text/csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        $sor = [
            'id',
            'name',
            'description',
            'price',
            'category',
            'image_url',
            'product_url'
        ];
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
                $sor = [
                    '"' . $t->getId() . '"',
                    '"' . $t->getNev() . '"',
                    '"' . $leiras . '"',
                    '"' . number_format($t->getBruttoAr(), 0, ',', '') . '"', //number_format($tetel.bruttoegysarhuf,0,',',' ')
                    '"' . $t->getTermekfa1Nev() . '"',
                    '"' . \mkw\store::getFullUrl($t->getKepurlLarge(), \mkw\store::getConfigValue('mainurl')) . '"',
                    '"' . \mkw\store::getFullUrl('/termek/' . $t->getSlug(), \mkw\store::getConfigValue('mainurl')) . '"'
                ];
                echo implode(';', $sor) . "\n";
            }
        }
    }

    public function ArfurkeszExport()
    {
        header("Content-type: text/csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        $sor = [
            '"manufacturer"',
            '"name"',
            '"category"',
            '"price"',
            '"product_url"',
            '"image_url"',
            '"description"'
        ];
        echo implode(';', $sor) . "\n";

        $sani = new \mkwhelpers\HtmlPurifierSanitizer([
            'HTML.Allowed' => 'strong,ul,li,u,i,b,br,em'
        ]);

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
                $sor = [
                    '"' . ($cimke ? $cimke->getNev() : '') . '"',
                    '"' . $t->getNev() . '"',
                    '"' . $t->getTermekfa1Nev() . '"',
                    '"' . number_format($t->getBruttoAr(), 0, ',', '') . '"', //number_format($tetel.bruttoegysarhuf,0,',',' ')
                    '"' . \mkw\store::getFullUrl('/termek/' . $t->getSlug(), \mkw\store::getConfigValue('mainurl')) . '"',
                    '"' . \mkw\store::getFullUrl($t->getKepurlLarge(), \mkw\store::getConfigValue('mainurl')) . '"',
                    '"' . $leiras . '"'
                ];
                echo implode(';', $sor) . "\n";
            }
        }
    }

    public function ArmutatoExport()
    {
        header("Content-type: text/csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        $sor = [
            'id',
            'url',
            'price',
            'category',
            'picture',
            'name',
            'manufacturer',
            'description',
            'long_description'
        ];
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
                $sor = [
                    $t->getId(),
                    \mkw\store::getFullUrl('/termek/' . $t->getSlug(), \mkw\store::getConfigValue('mainurl')),
                    number_format($t->getBruttoAr(), 0, ',', ''),
                    $t->getTermekfa1Nev(),
                    \mkw\store::getFullUrl($t->getKepurlLarge(), \mkw\store::getConfigValue('mainurl')),
                    $t->getNev(),
                    ($cimke ? $cimke->getNev() : ''),
                    $t->getRovidLeiras(),
                    $leiras
                ];
                echo implode(';', $sor) . "\n";
            }
        }
    }

    public function ArgepExport()
    {
        header("Content-type: text/csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        $sor = [
            'Cikkszám',
            'Terméknév',
            'Termékleírás',
            'BruttóÁr',
            'Fotólink',
            'Terméklink',
            'SzállításiIdő',
            'SzállításiKöltség'
        ];
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

                $sor = [
                    '"' . $t->getCikkszam() . '"',
                    '"' . $t->getNev() . '"',
                    '"' . $leiras . '"',
                    '"' . number_format($t->getBruttoAr(), 0, ',', '') . '"', //number_format($tetel.bruttoegysarhuf,0,',',' ')
                    '"' . \mkw\store::getFullUrl($t->getKepurlLarge(), \mkw\store::getConfigValue('mainurl')) . '"',
                    '"' . \mkw\store::getFullUrl('/termek/' . $t->getSlug(), \mkw\store::getConfigValue('mainurl')) . '"',
                    '"' . ($szallitasiido ? 'max. ' . $szallitasiido . ' munkanap' : '') . '"',
                    '"0"'
                ];
                echo implode('|', $sor) . "\n";
            }
        }
    }

    public function YuspExport()
    {
        header("Content-type: text/csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        $sor = [
            'Id',
            'Terméknév',
            'Termékleírás',
            'BruttóÁr',
            'Fotólink',
            'Terméklink',
            'SzállításiIdő',
            'SzállításiKöltség'
        ];
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

                $sor = [
                    '"' . $t->getId() . '"',
                    '"' . $t->getNev() . '"',
                    '"' . $leiras . '"',
                    '"' . number_format($t->getBruttoAr(), 0, ',', '') . '"', //number_format($tetel.bruttoegysarhuf,0,',',' ')
                    '"' . \mkw\store::getFullUrl($t->getKepurlLarge(), \mkw\store::getConfigValue('mainurl')) . '"',
                    '"' . \mkw\store::getFullUrl('/termek/' . $t->getSlug(), \mkw\store::getConfigValue('mainurl')) . '"',
                    '"' . ($szallitasiido ? 'max. ' . $szallitasiido . ' munkanap' : '') . '"',
                    '"0"'
                ];
                echo implode('|', $sor) . "\n";
            }
        }
    }

    public function ArukeresoExport()
    {
        header("Content-type: text/csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        $sor = [
            'manufacturer',
            'name',
            'category',
            'product_url',
            'price',
            'image_url',
            'description',
            'delivery_time',
            'identifier',
            'net_price',
            'ean',
            'delivery_cost',
        ];
        echo implode("\t", $sor) . "\n";
        $tr = \mkw\store::getEm()->getRepository(Termek::class);
        $szr = \mkw\store::getEm()->getRepository(SzallitasimodHatar::class);
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

                $termekfanev = '';
                if ($t->getTermekfa1()) {
                    if ($t->getArukeresofanev()) {
                        $levelnev = $t->getArukeresofanev();
                    } else {
                        $levelnev = $t->getTermekfa1()->getArukeresoid();
                    }
                    $termekfanev = $t->getTermekfa1()->getTeljesNev(' > ', $levelnev);
                }

                $vonalkod = $t->getVonalkod();
                if (!$vonalkod) {
                    $valtozatok = $t->getValtozatok();
                    /** @var TermekValtozat $valt */
                    foreach ($valtozatok as $valt) {
                        if (!$vonalkod && $valt->getVonalkod()) {
                            $vonalkod = $valt->getVonalkod();
                        }
                    }
                }

                /** @var SzallitasimodHatar $szallktg */
                $szallktg = $szr->getBySzallitasimodValutanemHatar(
                    \mkw\store::getParameter(\mkw\consts::ArukeresoExportSzallmod),
                    \mkw\store::getParameter(\mkw\consts::Valutanem),
                    $t->getBruttoAr()
                );
                $sor = [
                    '"' . ($cimke ? $cimke->getNev() : '') . '"',
                    '"' . $t->getNev() . '"',
                    '"' . $termekfanev . '"',
                    '"' . \mkw\store::getFullUrl('/termek/' . $t->getSlug(), \mkw\store::getConfigValue('mainurl')) . '"',
                    '"' . number_format($t->getBruttoAr(), 0, ',', '') . '"', //number_format($tetel.bruttoegysarhuf,0,',',' ')
                    '"' . \mkw\store::getFullUrl($t->getKepurlLarge(), \mkw\store::getConfigValue('mainurl')) . '"',
                    '"' . $leiras . '"',
                    '"' . ($szallitasiido ? $szallitasiido . ' munkanap' : '') . '"',
                    '"' . $t->getId() . '"',
                    '"' . number_format($t->getNettoAr(), 0, ',', '') . '"',
                    '"' . $vonalkod . '"',
                    '"' . number_format($szallktg->getOsszeg(), 0, ',', '') . '"',
                ];
                echo implode("\t", $sor) . "\n";
            }
        }
    }

    public function OlcsobbatExport()
    {
        header("Content-type: text/csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        $sor = [
            'manufacturer',
            'name',
            'category',
            'product_url',
            'price',
            'image_url',
            'description'
        ];
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

                $sor = [
                    '"' . ($cimke ? $cimke->getNev() : '') . '"',
                    '"' . $t->getNev() . '"',
                    '"' . ($t->getTermekfa1() ? $t->getTermekfa1()->getTeljesNev(' > ') : '') . '"',
                    '"' . \mkw\store::getFullUrl('/termek/' . $t->getSlug(), \mkw\store::getConfigValue('mainurl')) . '"',
                    '"' . number_format($t->getBruttoAr(), 0, ',', '') . '"', //number_format($tetel.bruttoegysarhuf,0,',',' ')
                    '"' . \mkw\store::getFullUrl($t->getKepurlLarge(), \mkw\store::getConfigValue('mainurl')) . '"',
                    '"' . $leiras . '"'
                ];
                echo implode(";", $sor) . "\n";
            }
        }
    }

    private function encstr($str)
    {
        return mb_convert_encoding($str, 'ISO-8859-2', 'UTF8');
    }

    public function RLBExport()
    {
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

        $r = $bizrepo->getAll($filter, ['id' => 'ASC']);
        foreach ($r as $bizonylat) {
            $mar = $bizonylat->getId();
            $fm = $bizonylat->getFizmod();
            $aossz = $bizrepo->getAFAOsszesito($bizonylat);
            $sor = [
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
            ];

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

    public function MugenraceExport()
    {
        $maxstock = $this->params->getNumRequestParam('max', 0);

        $kodszotarrepo = \mkw\store::getEm()->getRepository('Entities\TermekValtozatErtekKodszotar');

        $ertek1 = array_merge(
            \mkw\store::getEm()->getRepository('Entities\TermekValtozat')->getDistinctErtek1(),
            \mkw\store::getEm()->getRepository('Entities\TermekValtozat')->getDistinctErtek2()
        );

        foreach ($ertek1 as $eee1) {
            $e1 = $eee1['ertek'];

            $kodsz = $kodszotarrepo->findOneBy(['ertek' => $e1]);
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

        $sor = [
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
        ];
        echo implode(";", $sor) . "\n";

        $tr = \mkw\store::getEm()->getRepository('Entities\Termek');

        $filter = new FilterDescriptor();
        $karkod = $this->getRepo('Entities\TermekFa')->getKarkod(\mkw\store::getParameter(\mkw\consts::MugenraceKatId));
        if ($karkod) {
            $filter->addFilter(['termekfa1karkod', 'termekfa2karkod', 'termekfa3karkod'], 'LIKE', $karkod . '%'); // Mugenrace
        }

        $res = $tr->getAllValtozatForExport($filter, \mkw\store::getParameter(\mkw\consts::Locale));

        $eur = \mkw\store::getEm()->getRepository('Entities\Valutanem')->findOneBy(['nev' => 'EUR']);

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
                    $sor = [
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
                    ];
                    echo implode(";", $sor) . "\n";
                }
            } else {
                $keszlet = $t->getKeszlet() - $t->getFoglaltMennyiseg();
                if ($keszlet < 0) {
                    $keszlet = 0;
                }
                if ($maxstock > 0) {
                    $keszlet = min($keszlet, $maxstock);
                }
                $sor = [
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
                ];
                echo implode(";", $sor) . "\n";
            }
        }
    }

    public function SuperzonehuExport()
    {
        //    $this->getEm()->getConfiguration()->setSQLLogger(new \mkwhelpers\FileSQLLogger('superzoneexportsql.log'));

        $oldtranslocale = \mkw\store::getTranslationListener()->getListenerLocale();
        \mkw\store::getTranslationListener()->setTranslatableLocale('hu_hu');

        $maxstock = $this->params->getNumRequestParam('max', 0);

        $kodszotarrepo = \mkw\store::getEm()->getRepository('Entities\TermekValtozatErtekKodszotar');

        $ertek1 = array_merge(
            \mkw\store::getEm()->getRepository('Entities\TermekValtozat')->getDistinctErtek1(),
            \mkw\store::getEm()->getRepository('Entities\TermekValtozat')->getDistinctErtek2()
        );

        foreach ($ertek1 as $eee1) {
            $e1 = $eee1['ertek'];

            $kodsz = $kodszotarrepo->findOneBy(['ertek' => $e1]);
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

        $huf = \mkw\store::getEm()->getRepository('Entities\Valutanem')->findOneBy(['nev' => 'HUF']);

        $tr = \mkw\store::getEm()->getRepository('Entities\Termek');

        $res = $tr->getSuperzonehuExport();

//        $eur = \mkw\store::getEm()->getRepository('Entities\Valutanem')->findOneBy(array('nev' => 'EUR'));

        $sor = [];
        /** @var \Entities\Termek $t */
        foreach ($res as $t) {
            $ford = $t->getTranslationsArray();

            $termekkepek = $t->getTermekKepek();
            $kepek = [];
            /** @var TermekKep $kep */
            foreach ($termekkepek as $kep) {
                if ($kep->getUrl()) {
                    $kepek[] = \mkw\store::getFullUrl($kep->getUrl(), \mkw\store::getConfigValue('mainurl'));
                }
            }

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
                        'images' => $kepek,
                        'price' => $t->getBruttoAr($valt, null, $huf, \mkw\store::getParameter(\mkw\consts::Webshop3Price)),
                        'discountPrice' => $t->getBruttoAr($valt, null, $huf, \mkw\store::getParameter(\mkw\consts::Webshop3Discount)),
                        'articleId' => $t->getId(),
                        'variantId' => $valt->getId()
                    ];
                }
            } else {
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
                    'imageUrl' => ($t->getKepurl() ? \mkw\store::getFullUrl($t->getKepurl(), \mkw\store::getConfigValue('mainurl')) : ''),
                    'images' => $kepek,
                    'price' => $t->getBruttoAr(null, null, $huf, \mkw\store::getParameter(\mkw\consts::Webshop3Price)),
                    'discountPrice' => $t->getBruttoAr(null, null, $huf, \mkw\store::getParameter(\mkw\consts::Webshop3Discount)),
                    'articleId' => $t->getId(),
                    'variantId' => ''
                ];
            }
        }

        //  \mkw\store::getTranslationListener()->setTranslatableLocale($oldtranslocale);

        $this->getEm()->getConfiguration()->setSQLLogger();

        header("Content-type: application/json");
        header("Pragma: no-cache");
        header("Expires: 0");
        header("Content-Disposition: attachment; filename=superzone.json");
        echo json_encode($sor);
    }

    public function DepoExport()
    {
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

    public function KaposimotoExport()
    {
        $maxstock = $this->params->getNumRequestParam('max', 0);

        $kodszotarrepo = \mkw\store::getEm()->getRepository('Entities\TermekValtozatErtekKodszotar');

        $ertek1 = array_merge(
            \mkw\store::getEm()->getRepository('Entities\TermekValtozat')->getDistinctErtek1(),
            \mkw\store::getEm()->getRepository('Entities\TermekValtozat')->getDistinctErtek2()
        );

        foreach ($ertek1 as $eee1) {
            $e1 = $eee1['ertek'];

            $kodsz = $kodszotarrepo->findOneBy(['ertek' => $e1]);
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

        $huf = \mkw\store::getEm()->getRepository('Entities\Valutanem')->findOneBy(['nev' => 'HUF']);

        $tr = \mkw\store::getEm()->getRepository('Entities\Termek');

        $filter = new FilterDescriptor();
        $karkod = $this->getRepo('Entities\TermekFa')->getKarkod(\mkw\store::getParameter(\mkw\consts::MugenraceKatId));
        if ($karkod) {
            $filter->addFilter(['termekfa1karkod', 'termekfa2karkod', 'termekfa3karkod'], 'LIKE', $karkod . '%'); // Mugenrace
        }
        $res = $tr->getKaposimotoExport($filter);

        $partner = $this->getRepo(Partner::class)->find(23827);

//        $eur = \mkw\store::getEm()->getRepository('Entities\Valutanem')->findOneBy(array('nev' => 'EUR'));

        $sor = [];
        /** @var \Entities\Termek $t */
        foreach ($res as $t) {
            $ford = $t->getTranslationsArray();

            $termekkepek = $t->getTermekKepek();
            $kepek = [];
            /** @var TermekKep $kep */
            foreach ($termekkepek as $kep) {
                if ($kep->getUrl()) {
                    $kepek[] = \mkw\store::getFullUrl($kep->getUrl(), \mkw\store::getConfigValue('mainurl'));
                }
            }

            $valtozatok = $t->getValtozatok();

            $valutanem = $t->getArValutanem(null, $partner);
            if ($partner && $partner->getSzamlatipus()) {
                $price = $t->getNettoAr(null, $partner);
                $discountprice = $t->getKedvezmenynelkuliNettoAr(null, $partner, $valutanem);
            } else {
                $price = $t->getBruttoAr(null, $partner);
                $discountprice = $t->getKedvezmenynelkuliBruttoAr(null, $partner, $valutanem);
            }
            $discount = $t->getKedvezmeny($partner);

            $valtozattomb = [];
            if ($valtozatok) {
                /** @var \Entities\TermekValtozat $valt */
                foreach ($valtozatok as $valt) {
                    $keszlet = $valt->getKeszlet() - $valt->getFoglaltMennyiseg();
                    if ($keszlet < 0) {
                        $keszlet = 0;
                    } else {
                        $keszlet = 1;
                    }
                    $valtozattomb[] = [
                        'color' => $valt->getSzin(),
                        'size' => $valt->getMeret(),
                        'visible' => $valt->getLathato(),
                        'stock' => $keszlet,
                        'EANcode' => (string)$valt->getVonalkod(),
                        'imageUrl' => ($valt->getKepurl() ? \mkw\store::getFullUrl($valt->getKepurl(), \mkw\store::getConfigValue('mainurl')) : ''),
                        'Id' => $valt->getId()
                    ];
                }
                $sor[] = [
                    'categoryId' => $t->getTermekfa1Id(),
                    'category' => $t->getTermekfa1()->getTeljesNev(),
                    'categoryVisible' => $t->getTermekfa1()->getLathato(),
                    'articleNumber' => $t->getCikkszam(),
                    'articleNameEN' => $ford['en_us']['nev'],
                    'inactive' => $t->getInaktiv(),
                    'visible' => $t->getLathato(),
                    'pending' => $t->getFuggoben(),
                    'notAvailable' => $t->getNemkaphato(),
                    'descriptionEN' => preg_replace("/(\t|\n|\r)+/", "", $ford['en_us']['leiras']),
                    'imageUrl' => ($t->getKepurl() ? \mkw\store::getFullUrl($t->getKepurl(), \mkw\store::getConfigValue('mainurl')) : ''),
                    'images' => $kepek,
                    'price' => $price,
                    'discountPrice' => $discountprice,
                    'Id' => $t->getId(),
                    'variants' => $valtozattomb
                ];
            } else {
                $keszlet = $t->getKeszlet() - $t->getFoglaltMennyiseg();
                if ($keszlet < 0) {
                    $keszlet = 0;
                } else {
                    $keszlet = 1;
                }
                $sor[] = [
                    'categoryId' => $t->getTermekfa1Id(),
                    'category' => $t->getTermekfa1()->getTeljesNev(),
                    'categoryVisible' => $t->getTermekfa1()->getLathato(),
                    'articleNumber' => $t->getCikkszam(),
                    'articleNameEN' => $ford['en_us']['nev'],
                    'inactive' => $t->getInaktiv(),
                    'visible' => $t->getLathato(),
                    'pending' => $t->getFuggoben(),
                    'notAvailable' => $t->getNemkaphato(),
                    'stock' => $keszlet,
                    'EANcode' => (string)$t->getVonalkod(),
                    'descriptionEN' => preg_replace("/(\t|\n|\r)+/", "", $ford['en_us']['leiras']),
                    'imageUrl' => ($t->getKepurl() ? \mkw\store::getFullUrl($t->getKepurl(), \mkw\store::getConfigValue('mainurl')) : ''),
                    'images' => $kepek,
                    'price' => $price,
                    'discountPrice' => $discountprice,
                    'Id' => $t->getId(),
                ];
            }
        }
        header("Content-type: application/json");
        header("Pragma: no-cache");
        header("Expires: 0");
        header("Content-Disposition: attachment; filename=mugenrace.json");
        echo json_encode($sor);
    }

    public function orderformExport()
    {
        $trsm = new ResultSetMapping();
        $trsm->addScalarResult('id', 'id');
        $trsm->addScalarResult('cikkszam', 'cikkszam');
        $trsm->addScalarResult('vonalkod', 'vonalkod');
        $trsm->addScalarResult('termeknev', 'termeknev');

        $excel = new Spreadsheet();
        $sor = 1;

        $fcmoto = $this->getRepo(Partner::class)->find(\mkw\store::getParameter(\mkw\consts::FCMoto));

        $termekfak = $this->getRepo(TermekFa::class)->getB2BArray();

        $total = [];
        foreach ($termekfak as $termekfa) {
            $vegsor = 0;
            $excel->setActiveSheetIndex(0)
                ->setCellValue('A' . $sor, 'ID')
                ->setCellValue('B' . $sor, 'SKU')
                ->setCellValue('C' . $sor, 'Name');
            $sor++;
            $excel->setActiveSheetIndex(0)
                ->setCellValue('C' . $sor, 'EAN code')
                ->setCellValue('D' . $sor, 'Color')
                ->setCellValue('E' . $sor, 'Size')
                ->setCellValue('F' . $sor, 'Stock')
                ->setCellValue('G' . $sor, 'Retail price')
                ->setCellValue('H' . $sor, 'Discount price')
                ->setCellValue('I' . $sor, 'Ordered Qty.')
                ->setCellValue('J' . $sor, 'Price');
            $sor++;
            $excel->setActiveSheetIndex(0)
                ->setCellValue('B' . $sor, $termekfa['fanev'])
                ->getStyle('B' . $sor)->getFont()->setBold(true)->setSize(20);
            $sor++;
            $termekek = $this->getEm()->createNativeQuery(
                'SELECT t.id,t.cikkszam,t.vonalkod,COALESCE(tt.content,t.nev) AS termeknev '
                . 'FROM termek t '
                . 'LEFT JOIN termek_translations tt ON (t.id=tt.object_id) AND (field="nev") AND (locale="en_us") '
                . 'WHERE (t.termekfa1karkod LIKE "' . $termekfa['karkod'] . '%") AND (t.lathato=1) AND (t.inaktiv=0) AND (t.fuggoben=0) ',
                $trsm
            )->getScalarResult();
            foreach ($termekek as $termek) {
                if ($vegsor) {
                    $excel->setActiveSheetIndex(0)
                        ->setCellValue('H' . $vegsor, 'SUM')
                        ->setCellValue('I' . $vegsor, '=SUM(I' . $kezdosor . ':I' . $vegsor - 1 . ')')
                        ->setCellValue('J' . $vegsor, '=SUM(J' . $kezdosor . ':J' . $vegsor - 1 . ')');
                    $excel->getActiveSheet()->getStyle('H' . $vegsor)->getFont()->setBold(true);
                    $excel->getActiveSheet()->getStyle('I' . $vegsor)->getFont()->setBold(true);
                    $excel->getActiveSheet()->getStyle('J' . $vegsor)->getFont()->setBold(true);
                    $total[] = $vegsor;
                }
                $excel->setActiveSheetIndex(0)
                    ->setCellValue('B' . $sor, strtoupper($termek['cikkszam']))
                    ->setCellValue('C' . $sor, $termek['termeknev']);
                $excel->setActiveSheetIndex(0)
                    ->getStyle('B' . $sor)->getFont()->setBold(true)->setSize(16);
                $excel->setActiveSheetIndex(0)
                    ->getStyle('C' . $sor)->getFont()->setBold(true)->setSize(16);

                $sor++;
                $kezdosor = $sor;
                $valtfilter = new FilterDescriptor();
                $valtfilter->addFilter('termek', '=', $termek['id']);
                $valtfilter->addFilter('lathato', '=', 1);
                $valtozatok = $this->getRepo(TermekValtozat::class)->getAll($valtfilter);
                $valtozattomb = [];
                $termekobj = $this->getRepo(Termek::class)->find($termek['id']);
                /** @var TermekValtozat $valtozat */
                foreach ($valtozatok as $valtozat) {
                    $valtozattomb[] = [
                        'id' => $valtozat->getId(),
                        'valtadattipus1id' => $valtozat->getAdatTipus1Id(),
                        'valtadattipus2id' => $valtozat->getAdatTipus2Id(),
                        'valtertek1' => $valtozat->getErtek1(),
                        'valtertek2' => $valtozat->getErtek2(),
                        'vonalkod' => $valtozat->getVonalkod(),
                        'keszlet' => max($valtozat->getKeszlet() - $valtozat->getFoglaltMennyiseg() - $valtozat->calcMinboltikeszlet(), 0),
                        'retailprice' => $termekobj->getKedvezmenynelkuliNettoAr($valtozat, $fcmoto),
                        'discountprice' => $termekobj->getNettoAr($valtozat, $fcmoto),
                    ];
                }
                $s = \mkw\store::getParameter(\mkw\consts::ValtozatSorrend);
                $rendezendo = \mkw\store::getParameter(\mkw\consts::RendezendoValtozat);
                $sorrend = explode(',', $s);
                $a = $valtozattomb;
                uasort($a, function ($e, $f) use ($sorrend, $rendezendo) {
                    if ($e['valtadattipus1id'] == $rendezendo) {
                        $ertek = $e['valtertek1'];
                        $eszin = $e['valtertek2'];
                    } elseif ($e['valtadattipus2id'] == $rendezendo) {
                        $ertek = $e['valtertek2'];
                        $eszin = $e['valtertek1'];
                    } else {
                        $ertek = false;
                        $eszin = false;
                    }
                    $ve = array_search($ertek, $sorrend);
                    if ($ve === false) {
                        $ve = 0;
                    }
                    $ve = $eszin
                        . str_pad((string)$ve, 6, '0', STR_PAD_LEFT);

                    if ($f['valtadattipus1id'] == $rendezendo) {
                        $ertek = $f['valtertek1'];
                        $fszin = $f['valtertek2'];
                    } elseif ($f['valtadattipus2id'] == $rendezendo) {
                        $ertek = $f['valtertek2'];
                        $fszin = $f['valtertek1'];
                    } else {
                        $ertek = false;
                        $fszin = false;
                    }
                    $vf = array_search($ertek, $sorrend);
                    if ($vf === false) {
                        $vf = 0;
                    }
                    $vf = $fszin
                        . str_pad((string)$vf, 6, '0', STR_PAD_LEFT);

                    if ($ve === $vf) {
                        return 0;
                    }
                    return ($ve < $vf) ? -1 : 1;
                });
                $valtozattomb = array_values($a);
                foreach ($valtozattomb as $valtozat) {
                    $excel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $sor, $valtozat['id'])
                        ->setCellValue('C' . $sor, $valtozat['vonalkod'])
                        ->setCellValue('D' . $sor, $valtozat['valtertek1'])
                        ->setCellValue('E' . $sor, $valtozat['valtertek2'])
                        ->setCellValue('F' . $sor, $valtozat['keszlet'])
                        ->setCellValue('G' . $sor, $valtozat['retailprice'])
                        ->setCellValue('H' . $sor, $valtozat['discountprice'])
                        ->setCellValue('J' . $sor, '=I' . $sor . '*H' . $sor);
                    $excel->setActiveSheetIndex(0)
                        ->getCell('C' . $sor)->setDataType(DataType::TYPE_STRING);
                    $excel->setActiveSheetIndex(0)
                        ->getStyle('C' . $sor)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                    $excel->setActiveSheetIndex(0)
                        ->getStyle('I' . $sor)
                        ->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $sor++;
                }
                $vegsor = $sor;
                $sor++;
            }
        }

        if ($vegsor) {
            $excel->setActiveSheetIndex(0)
                ->setCellValue('H' . $vegsor, 'SUM')
                ->setCellValue('I' . $vegsor, '=SUM(I' . $kezdosor . ':I' . $vegsor - 1 . ')')
                ->setCellValue('J' . $vegsor, '=SUM(J' . $kezdosor . ':J' . $vegsor - 1 . ')');
            $excel->getActiveSheet()->getStyle('H' . $vegsor)->getFont()->setBold(true);
            $excel->getActiveSheet()->getStyle('I' . $vegsor)->getFont()->setBold(true);
            $excel->getActiveSheet()->getStyle('J' . $vegsor)->getFont()->setBold(true);
            $total[] = $vegsor;
        }

        $excel->setActiveSheetIndex(0)
            ->setCellValue('H' . $sor, 'Total')
            ->setCellValue(
                'I' . $sor,
                '=' . implode('+', array_map(function ($val) {
                    return 'I' . $val;
                }, $total))
            )
            ->setCellValue(
                'J' . $sor,
                '=' . implode('+', array_map(function ($val) {
                    return 'J' . $val;
                }, $total))
            );
        $excel->getActiveSheet()->getStyle('H' . $sor)->getFont()->setBold(true)->setSize(16);
        $excel->getActiveSheet()->getStyle('I' . $sor)->getFont()->setBold(true)->setSize(16);
        $excel->getActiveSheet()->getStyle('J' . $sor)->getFont()->setBold(true)->setSize(16);

        $excel->setActiveSheetIndex(0)->getColumnDimension('A')->setVisible(false);
        $excel->setActiveSheetIndex(0)->getColumnDimension('B')->setAutoSize(true);
        $excel->setActiveSheetIndex(0)->getColumnDimension('C')->setAutoSize(true);
        $excel->setActiveSheetIndex(0)->getColumnDimension('D')->setAutoSize(true);

        $writer = IOFactory::createWriter($excel, 'Xlsx');

        $filename = uniqid('orderform') . '.xlsx';
        $filepath = \mkw\store::storagePath($filename);
        $writer->save($filepath);

        $fileSize = filesize($filepath);

        // Output headers.
        header('Cache-Control: private');
        header('Content-Type: application/stream');
        header('Content-Length: ' . $fileSize);
        header('Content-Disposition: attachment; filename=' . $filename);

        readfile($filepath);

        \unlink($filepath);
    }

    public function fcmotostockExport()
    {
        $trsm = new ResultSetMapping();
        $trsm->addScalarResult('id', 'id');
        $trsm->addScalarResult('cikkszam', 'cikkszam');

        /** @var TermekValtozatErtekRepository $tver */
        $tver = $this->getRepo(TermekValtozatErtek::class);

        $excel = new Spreadsheet();
        $sor = 1;

        $termekfak = $this->getRepo(TermekFa::class)->getB2BArray();

        $excel->setActiveSheetIndex(0)
            ->setCellValue('A' . $sor, 'EAN')
            ->setCellValue('B' . $sor, 'Article number')
            ->setCellValue('C' . $sor, 'Quantity');
        $sor++;

        foreach ($termekfak as $termekfa) {
            $termekek = $this->getEm()->createNativeQuery(
                'SELECT t.id,t.cikkszam '
                . 'FROM termek t '
                . 'WHERE (t.termekfa1karkod LIKE "' . $termekfa['karkod'] . '%") AND (t.lathato=1) AND (t.inaktiv=0) AND (t.fuggoben=0) ',
                $trsm
            )->getScalarResult();
            foreach ($termekek as $termek) {
                $valtfilter = new FilterDescriptor();
                $valtfilter->addFilter('termek', '=', $termek['id']);
                $valtfilter->addFilter('lathato', '=', 1);
                $valtozatok = $this->getRepo(TermekValtozat::class)->getAll($valtfilter);
                /** @var TermekValtozat $valtozat */
                foreach ($valtozatok as $valtozat) {
                    $excel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $sor, $valtozat->getVonalkod())
                        ->setCellValue(
                            'B' . $sor,
                            strtoupper($termek['cikkszam']) . '-' . $tver->translateColor($valtozat->getSzin()) . '-' . $valtozat->getMeret()
                        )
                        ->setCellValue('C' . $sor, max($valtozat->getKeszlet() - $valtozat->getFoglaltMennyiseg() - $valtozat->calcMinboltikeszlet(), 0));
                    $sor++;
                }
            }
        }

        $writer = IOFactory::createWriter($excel, 'Xlsx');

        $filename = uniqid('stock') . '.xlsx';
        $filepath = \mkw\store::storagePath($filename);
        $writer->save($filepath);

        $fileSize = filesize($filepath);

        // Output headers.
        header('Cache-Control: private');
        header('Content-Type: application/stream');
        header('Content-Length: ' . $fileSize);
        header('Content-Disposition: attachment; filename=' . $filename);

        readfile($filepath);

        \unlink($filepath);
    }

    public function eanstockExport()
    {
        $trsm = new ResultSetMapping();
        $trsm->addScalarResult('id', 'id');
        $trsm->addScalarResult('cikkszam', 'cikkszam');

        $excel = new Spreadsheet();
        $sor = 1;

        $termekfak = $this->getRepo(TermekFa::class)->getB2BArray();

        $excel->setActiveSheetIndex(0)
            ->setCellValue('A' . $sor, 'EAN')
            ->setCellValue('B' . $sor, 'Quantity');
        $sor++;

        foreach ($termekfak as $termekfa) {
            $termekek = $this->getEm()->createNativeQuery(
                'SELECT t.id,t.cikkszam '
                . 'FROM termek t '
                . 'WHERE (t.termekfa1karkod LIKE "' . $termekfa['karkod'] . '%") AND (t.lathato=1) AND (t.inaktiv=0) AND (t.fuggoben=0) ',
                $trsm
            )->getScalarResult();
            foreach ($termekek as $termek) {
                $valtfilter = new FilterDescriptor();
                $valtfilter->addFilter('termek', '=', $termek['id']);
                $valtfilter->addFilter('lathato', '=', 1);
                $valtozatok = $this->getRepo(TermekValtozat::class)->getAll($valtfilter);
                /** @var TermekValtozat $valtozat */
                foreach ($valtozatok as $valtozat) {
                    $excel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $sor, $valtozat->getVonalkod())
                        ->setCellValue('B' . $sor, max($valtozat->getKeszlet() - $valtozat->getFoglaltMennyiseg() - $valtozat->calcMinboltikeszlet(), 0));
                    $sor++;
                }
            }
        }

        $writer = IOFactory::createWriter($excel, 'Xlsx');

        $filename = uniqid('eanstock') . '.xlsx';
        $filepath = \mkw\store::storagePath($filename);
        $writer->save($filepath);

        $fileSize = filesize($filepath);

        // Output headers.
        header('Cache-Control: private');
        header('Content-Type: application/stream');
        header('Content-Length: ' . $fileSize);
        header('Content-Disposition: attachment; filename=' . $filename);

        readfile($filepath);

        \unlink($filepath);
    }

}
