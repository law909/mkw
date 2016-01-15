<?php

namespace Controllers;


use mkw\consts;
use mkw\Store;

class navadatexportController extends \mkwhelpers\MattableController {

    private $tolstr;
    private $igstr;
    private $szlaszamtol;
    private $szlaszamig;

    public function view() {
        $view = $this->createView('navadatexport.tpl');

        $view->setVar('toldatum', date(\mkw\Store::$DateFormat));
        $view->setVar('igdatum', date(\mkw\Store::$DateFormat));

        $view->printTemplateResult();
    }

    protected function createFilter() {
        $this->tolstr = $this->params->getStringRequestParam('tol');
        $this->tolstr = date(\mkw\Store::$DateFormat, strtotime(\mkw\Store::convDate($this->tolstr)));

        $this->igstr = $this->params->getStringRequestParam('ig');
        $this->igstr = date(\mkw\Store::$DateFormat, strtotime(\mkw\Store::convDate($this->igstr)));

        $this->szlaszamtol = $this->params->getStringRequestParam('szlasztol');
        $this->szlaszamig = $this->params->getStringRequestParam('szlaszig');

        $datummezo = 'kelt';

        $filter = new \mkwhelpers\FilterDescriptor();
        if ($this->szlaszamtol && $this->szlaszamig) {
            $filter
                ->addFilter('id', '>=', $this->szlaszamtol)
                ->addFilter('id', '<=', $this->szlaszamig);
        }
        else {
            $filter
                ->addFilter($datummezo, '>=', $this->tolstr)
                ->addFilter($datummezo, '<=', $this->igstr);
        }
        $filter->addFilter('bizonylattipus', '=', 'szamla');

        return $filter;
    }

    public function createLista() {
        header("Content-type: text/xml");
        header("Pragma: no-cache");
        header("Expires: 0");
        header("Content-Disposition: attachment; filename=navexport.xml");

        $filter = $this->createFilter();

        /** @var \Entities\BizonylatfejRepository $bfrepo */
        $bfrepo = $this->getRepo('Entities\Bizonylatfej');

        $fej = $bfrepo->getAll($filter, array('id' => 'ASC'));

        echo '<?xml version="1.0" encoding="UTF-8"?>' .
        '<szamlak xmlns="http://schemas.nav.gov.hu/2013/szamla">' .
        '<export_datuma>' . \mkw\Store::DateToExcel(date(\mkw\Store::$DateFormat)) . '</export_datuma>' .
        '<export_szla_db>' . str_pad(count($fej), 1, '0',STR_PAD_LEFT) . '</export_szla_db>' .
        '<kezdo_ido>' . \mkw\Store::DateToExcel($fej[0]->getKelt()) . '</kezdo_ido>' .
        '<zaro_ido>' . \mkw\Store::DateToExcel($fej[count($fej)-1]->getKelt()) . '</zaro_ido>' .
        '<kezdo_szla_szam>' . $fej[0]->getId() . '</kezdo_szla_szam>' .
        '<zaro_szla_szam>' . $fej[count($fej)-1]->getId() . '</zaro_szla_szam>';

        /** @var \Entities\Bizonylatfej $f */
        foreach($fej as $f) {
            echo '<szamla><fejlec>';
            echo '<szlasorszam>' . substr($f->getId(), 0, 100) . '</szlasorszam>';
            echo '<szlatipus>';
            switch ($f->getStornotipus()) {
                case 0:
                    echo '1';
                    break;
                case 1:
                    echo '6';
                    break;
                case 2:
                    echo '4';
                    break;
                default:
                    echo '1';
                    break;
            }
            echo '</szlatipus>';
            echo '<szladatum>' . \mkw\Store::DateToExcel($f->getKelt()) . '</szladatum>';
            echo '<teljdatum>' . \mkw\Store::DateToExcel($f->getTeljesites()) . '</teljdatum>';
            echo '</fejlec>';

            echo '<szamlakibocsato>';
            if ($f->getTulajadoszam()) {
                echo '<adoszam>' . substr($f->getTulajadoszam(), 0, 20) . '</adoszam>';
            }
            if ($f->getTulajeuadoszam()) {
                echo '<kozadoszam>' . substr($f->getTulajeuadoszam(), 0, 20) . '</kozadoszam>';
            }
            echo '<kisadozo>' . \mkw\Store::toBoolStr($f->getTulajkisadozo()) . '</kisadozo>';
            echo '<nev>' . substr($f->getTulajnev(), 0, 100) . '</nev>';
            echo '<cim><iranyitoszam>' . substr($f->getTulajirszam(), 0, 10) . '</iranyitoszam>' .
                '<telepules>' . substr($f->getTulajvaros(), 0, 100) . '</telepules>' .
                '<kozterulet_neve>' . substr($f->getTulajutca(), 0, 100) . '</kozterulet_neve></cim>';

            echo '<egyeni_vallalkozo>' . \mkw\Store::toBoolStr($f->getTulajegyenivallalkozo()) . '</egyeni_vallalkozo>';
            if ($f->getTulajegyenivallalkozo()) {
                if ($f->getTulajevnyilvszam()) {
                    echo '<ev_nyilv_tart_szam>' . substr($f->getTulajevnyilvszam(), 0, 100) . '</ev_nyilv_tart_szam>';
                }
                if ($f->getTulajevnev()) {
                    echo '<ev_neve>' . substr($f->getTulajevnev(), 0, 100) . '</ev_neve>';
                }
            }
            echo '</szamlakibocsato>';

            echo '<vevo>';
            if ($f->getPartneradoszam()) {
                echo '<adoszam>' . substr($f->getPartneradoszam(), 0, 20) . '</adoszam>';
            }
            if ($f->getPartnereuadoszam()) {
                echo '<kozadoszam>' . substr($f->getPartnereuadoszam(), 0, 20) . '</kozadoszam>';
            }
            if ($f->getPartnernev()) {
                echo '<nev>' . substr($f->getPartnernev(), 0, 100) . '</nev>';
            }
            else {
                echo '<nev>' . substr($f->getPartnervezeteknev() . ' ' . $f->getPartnerkeresztnev(), 0, 100) . '</nev>';
            }
            echo '<cim><iranyitoszam>' . substr($f->getPartnerirszam(), 0, 10) . '</iranyitoszam>' .
                '<telepules>' . substr($f->getPartnervaros(), 0, 100) . '</telepules>' .
                '<kozterulet_neve>' . substr($f->getPartnerutca(), 0, 100) . '</kozterulet_neve>' .
            '</cim>';
            echo '</vevo>';

            $bts = $f->getBizonylattetelek();
            /** @var \Entities\Bizonylattetel $bt */
            foreach($bts as $bt) {
                echo '<termek_szolgaltatas_tetelek><termeknev>' . substr($bt->getTermeknev(), 0, 100) . '</termeknev>';
                switch ($bt->getElolegtipus()) {
                    case 'eloleg':
                        echo '<eloleg>1</eloleg>';
                        break;
                    case 'veg':
                        echo '<eloleg>2</eloleg>';
                        break;
                }
                if ($bt->getVtszszam()) {
                    echo '<besorszam>' . substr($bt->getVtszszam(), 0, 100) . '</besorszam>';
                }
                echo '<menny>' . \mkw\Store::toXMLNum($bt->getMennyiseg()) . '</menny>';
                if ($bt->getME()) {
                    echo '<mertekegys>' . substr($bt->getME(), 0, 100) . '</mertekegys>';
                }
                else {
                    echo '<mertekegys>ismeretlen</mertekegys>';
                }
                echo '<kozv_szolgaltatas>' . \mkw\Store::toBoolStr($bt->getKozvetitett()) . '</kozv_szolgaltatas>' .
                    '<nettoar>' . \mkw\Store::toXMLNum($bt->getNetto()) . '</nettoar>' .
                    '<nettoegysar>' . \mkw\Store::toXMLNum($bt->getNettoegysar()) . '</nettoegysar>' .
                    '<adokulcs>' . $bt->getAfakulcs() . '</adokulcs>' .
                    '<adoertek>' . \mkw\Store::toXMLNum($bt->getAfaertek()) . '</adoertek>' .
                    '<bruttoar>' . \mkw\Store::toXMLNum($bt->getBrutto()) . '</bruttoar>';
                echo '</termek_szolgaltatas_tetelek>';
            }

            if ($f->getValutanemnev()) {
                echo '<nem_kotelezo><penznem>' . substr($f->getValutanemnev(), 0, 100) . '</penznem></nem_kotelezo>';
            }

            echo '<osszesites>';
            $ao = $bfrepo->getAFAOsszesito($f);
            foreach ($ao as $a) {
                echo '<afarovat>' .
                    '<nettoar>' . \mkw\Store::toXMLNum($a['netto']) . '</nettoar>' .
                    '<adokulcs>' . $a['afakulcs'] . '</adokulcs>' .
                    '<adoertek>' . \mkw\Store::toXMLNum($a['afa']) . '</adoertek>' .
                    '<bruttoar>' . \mkw\Store::toXMLNum($a['brutto']) . '</bruttoar>' .
                    '</afarovat>';
            }

            echo '<vegosszeg>' .
                '<nettoarossz>' . \mkw\Store::toXMLNum($f->getNetto()) . '</nettoarossz>' .
                '<afaertekossz>' . \mkw\Store::toXMLNum($f->getAfa()) . '</afaertekossz>' .
                '<bruttoarossz>' . \mkw\Store::toXMLNum($f->getBrutto()) . '</bruttoarossz>' .
                '</vegosszeg>';
            echo '</osszesites>';
            echo "</szamla>\n";

            $f->setFix(true);
            $this->getEm()->persist($f);
            $this->getEm()->flush();
        }
        echo '</szamlak>';

    }

    public function check() {
        $tol = $this->params->getStringRequestParam('tol');
        $ig = $this->params->getStringRequestParam('ig');
        $szlasztol = $this->params->getStringRequestParam('szlasztol');
        $szlaszig = $this->params->getStringRequestParam('szlaszig');

        if ((($szlasztol != '') && ($szlaszig == '')) || (($szlasztol == '') && ($szlaszig != ''))) {
            echo json_encode(array(
                'result' => 'error',
                'msg' => t('Adja meg a számlaszám intervallum mindkét végét.')
            ));
            return;
        }

        /** @var \Entities\BizonylatfejRepository $rep */
        $rep = $this->getRepo('Entities\Bizonylatfej');

        if ($szlasztol != '') {
            $r = $rep->getSzamlaKelt($szlasztol);
            if ($r) {
                echo json_encode(array(
                    'result' => 'error',
                    'msg' => t('Az adatszolgáltatás csak 2016.01.01-től működik.')
                ));
                return;
            }
        }
        if ($szlaszig != '') {
            $r = $rep->getSzamlaKelt($szlaszig);
            if ($r) {
                echo json_encode(array(
                    'result' => 'error',
                    'msg' => t('Az adatszolgáltatás csak 2016.01.01-től működik.')
                ));
                return;
            }
        }

        $told = \mkw\Store::toDate($tol);
        $igd = \mkw\Store::toDate($ig);

        if (($told->format('Y') < 2016) || ($igd->format('Y') < 2016)) {
            echo json_encode(array(
                'result' => 'error',
                'msg' => t('Az adatszolgáltatás csak 2016.01.01-től működik.')
            ));
            return;
        }

        echo json_encode(array(
            'result' => 'ok',
            'href' => \mkw\Store::getRouter()->generate('adminnavadatexportget', false, array(), array(
                'tol' => $tol,
                'ig' => $ig,
                'szlasztol' => $szlasztol,
                'szlaszig' => $szlaszig
            ))
        ));
    }

}