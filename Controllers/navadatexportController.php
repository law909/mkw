<?php

// http://njt.hu/cgi_bin/njt_doc.cgi?docid=170220.266028

namespace Controllers;

class navadatexportController extends \mkwhelpers\MattableController {

    private $tolstr;
    private $igstr;
    private $szlaszamtol;
    private $szlaszamig;

    public function view() {
        $view = $this->createView('navadatexport.tpl');

        $view->setVar('toldatum', date(\mkw\store::$DateFormat));
        $view->setVar('igdatum', date(\mkw\store::$DateFormat));

        $view->printTemplateResult();
    }

    protected function createFilter() {
        $this->tolstr = $this->params->getStringRequestParam('tol');
        $this->tolstr = date(\mkw\store::$DateFormat, strtotime(\mkw\store::convDate($this->tolstr)));

        $this->igstr = $this->params->getStringRequestParam('ig');
        $this->igstr = date(\mkw\store::$DateFormat, strtotime(\mkw\store::convDate($this->igstr)));

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
        $filepath = 'navexport.xml';

        $handle = fopen($filepath, "wb");

        $filter = $this->createFilter();

        /** @var \Entities\BizonylatfejRepository $bfrepo */
        $bfrepo = $this->getRepo('Entities\Bizonylatfej');

        $fej = $bfrepo->getWithTetelek($filter, array('id' => 'ASC'));
        $db = 0;

        fwrite($handle, '<?xml version="1.0" encoding="UTF-8"?>' .
        '<szamlak xmlns="http://schemas.nav.gov.hu/2013/szamla">' .
        '<export_datuma>' . \mkw\store::DateToExcel(date(\mkw\store::$DateFormat)) . '</export_datuma>' .
        '<export_szla_db>' . str_pad(count($fej), 1, '0',STR_PAD_LEFT) . '</export_szla_db>' .
        '<kezdo_ido>' . \mkw\store::DateToExcel($fej[0]->getKelt()) . '</kezdo_ido>' .
        '<zaro_ido>' . \mkw\store::DateToExcel($fej[count($fej)-1]->getKelt()) . '</zaro_ido>' .
        '<kezdo_szla_szam>' . $fej[0]->getId() . '</kezdo_szla_szam>' .
        '<zaro_szla_szam>' . $fej[count($fej)-1]->getId() . '</zaro_szla_szam>');

        fclose($handle);
        fopen($filepath, "ab");

        /** @var \Entities\Bizonylatfej $f */
        foreach ($fej as $f) {

            fwrite($handle, '<szamla><fejlec>');
            fwrite($handle, '<szlasorszam>' . substr($f->getId(), 0, 100) . '</szlasorszam>');
            fwrite($handle, '<szlatipus>');
            switch ($f->getStornotipus()) {
                case 0:
                    fwrite($handle, '1');
                    break;
                case 1:
                    fwrite($handle, '6');
                    break;
                case 2:
                    fwrite($handle, '4');
                    break;
                default:
                    fwrite($handle, '1');
                    break;
            }
            fwrite($handle, '</szlatipus>');
            fwrite($handle, '<szladatum>' . \mkw\store::DateToExcel($f->getKelt()) . '</szladatum>');
            fwrite($handle, '<teljdatum>' . \mkw\store::DateToExcel($f->getTeljesites()) . '</teljdatum>');
            fwrite($handle, '</fejlec>');

            fwrite($handle, '<szamlakibocsato>');
            if ($f->getTulajadoszam()) {
                fwrite($handle, '<adoszam>' . substr($f->getTulajadoszam(), 0, 20) . '</adoszam>');
            }
            if ($f->getTulajeuadoszam()) {
                fwrite($handle, '<kozadoszam>' . substr($f->getTulajeuadoszam(), 0, 20) . '</kozadoszam>');
            }
            fwrite($handle, '<kisadozo>' . \mkw\store::toBoolStr($f->getTulajkisadozo()) . '</kisadozo>');
            fwrite($handle, '<nev>' . substr($f->getTulajnev(), 0, 100) . '</nev>');
            fwrite($handle, '<cim><iranyitoszam>' . substr($f->getTulajirszam(), 0, 10) . '</iranyitoszam>' .
                '<telepules>' . substr($f->getTulajvaros(), 0, 100) . '</telepules>' .
                '<kozterulet_neve>' . substr($f->getTulajutca(), 0, 100) . '</kozterulet_neve></cim>');

            fwrite($handle, '<egyeni_vallalkozo>' . \mkw\store::toBoolStr($f->getTulajegyenivallalkozo()) . '</egyeni_vallalkozo>');
            if ($f->getTulajegyenivallalkozo()) {
                if ($f->getTulajevnyilvszam()) {
                    fwrite($handle, '<ev_nyilv_tart_szam>' . substr($f->getTulajevnyilvszam(), 0, 100) . '</ev_nyilv_tart_szam>');
                }
                if ($f->getTulajevnev()) {
                    fwrite($handle, '<ev_neve>' . substr($f->getTulajevnev(), 0, 100) . '</ev_neve>');
                }
            }
            fwrite($handle, '</szamlakibocsato>');

            fwrite($handle, '<vevo>');
            if ($f->getPartneradoszam()) {
                fwrite($handle, '<adoszam>' . substr($f->getPartneradoszam(), 0, 20) . '</adoszam>');
            }
            if ($f->getPartnereuadoszam()) {
                fwrite($handle, '<kozadoszam>' . substr($f->getPartnereuadoszam(), 0, 20) . '</kozadoszam>');
            }
            if ($f->getPartnernev()) {
                fwrite($handle, '<nev>' . substr($f->getPartnernev(), 0, 100) . '</nev>');
            }
            else {
                fwrite($handle, '<nev>' . substr($f->getPartnervezeteknev() . ' ' . $f->getPartnerkeresztnev(), 0, 100) . '</nev>');
            }
            fwrite($handle, '<cim><iranyitoszam>' . substr($f->getPartnerirszam(), 0, 10) . '</iranyitoszam>' .
                '<telepules>' . substr($f->getPartnervaros(), 0, 100) . '</telepules>' .
                '<kozterulet_neve>' . substr($f->getPartnerutca(), 0, 100) . '</kozterulet_neve>' .
            '</cim>');
            fwrite($handle, '</vevo>');

            $bts = $f->getBizonylattetelek();
            /** @var \Entities\Bizonylattetel $bt */
            foreach($bts as $bt) {
                fwrite($handle, '<termek_szolgaltatas_tetelek><termeknev>' . substr($bt->getTermeknev(), 0, 100) . '</termeknev>');
                switch ($bt->getElolegtipus()) {
                    case 'eloleg':
                        fwrite($handle, '<eloleg>1</eloleg>');
                        break;
                    case 'veg':
                        fwrite($handle, '<eloleg>2</eloleg>');
                        break;
                }
                if ($bt->getVtszszam()) {
                    fwrite($handle, '<besorszam>' . substr($bt->getVtszszam(), 0, 100) . '</besorszam>');
                }
                fwrite($handle, '<menny>' . \mkw\store::toXMLNum($bt->getMennyiseg()) . '</menny>');
                if ($bt->getME()) {
                    fwrite($handle, '<mertekegys>' . substr($bt->getME(), 0, 100) . '</mertekegys>');
                }
                else {
                    fwrite($handle, '<mertekegys>ismeretlen</mertekegys>');
                }
                fwrite($handle, '<kozv_szolgaltatas>' . \mkw\store::toBoolStr($bt->getKozvetitett()) . '</kozv_szolgaltatas>' .
                    '<nettoar>' . \mkw\store::toXMLNum($bt->getNetto()) . '</nettoar>' .
                    '<nettoegysar>' . \mkw\store::toXMLNum($bt->getNettoegysar()) . '</nettoegysar>' .
                    '<adokulcs>' . $bt->getAfakulcs() . '</adokulcs>' .
                    '<adoertek>' . \mkw\store::toXMLNum($bt->getAfaertek()) . '</adoertek>' .
                    '<bruttoar>' . \mkw\store::toXMLNum($bt->getBrutto()) . '</bruttoar>');
                fwrite($handle, '</termek_szolgaltatas_tetelek>');
            }

    	    fwrite($handle, '<nem_kotelezo>');
            if ($f->getValutanemnev()) {
                fwrite($handle, '<penznem>' . substr($f->getValutanemnev(), 0, 100) . '</penznem>');
            }
            fwrite($handle, '<fiz_hatido>' . \mkw\store::DateToExcel($f->getEsedekesseg()) . '</fiz_hatido>');
            fwrite($handle, '<fiz_mod>' . substr($f->getFizmodnev(), 0, 100) . '</fiz_mod>');
            fwrite($handle, '</nem_kotelezo>');

            fwrite($handle, '<osszesites>');
            $ao = $bfrepo->getAFAOsszesito($f);
            foreach ($ao as $a) {
                fwrite($handle, '<afarovat>' .
                    '<nettoar>' . \mkw\store::toXMLNum($a['netto']) . '</nettoar>' .
                    '<adokulcs>' . $a['afakulcs'] . '</adokulcs>' .
                    '<adoertek>' . \mkw\store::toXMLNum($a['afa']) . '</adoertek>' .
                    '<bruttoar>' . \mkw\store::toXMLNum($a['brutto']) . '</bruttoar>' .
                    '</afarovat>');
            }

            fwrite($handle, '<vegosszeg>' .
                '<nettoarossz>' . \mkw\store::toXMLNum($f->getNetto()) . '</nettoarossz>' .
                '<afaertekossz>' . \mkw\store::toXMLNum($f->getAfa()) . '</afaertekossz>' .
                '<bruttoarossz>' . \mkw\store::toXMLNum($f->getBrutto()) . '</bruttoarossz>' .
                '</vegosszeg>');
            fwrite($handle, '</osszesites>');
            fwrite($handle, "</szamla>");

//            $f->setFix(true);
//            $this->getEm()->persist($f);
            $db++;

            if ($db % 20) {
//                $this->getEm()->flush();
            }
            fclose($handle);
            fopen($filepath, "ab");
        }
//        $this->getEm()->flush();
        fwrite($handle, '</szamlak>');
        fclose($handle);

        $fileSize = filesize($filepath);

        // Output headers.
        header('Cache-Control: private');
        header('Content-Type: application/stream');
        header('Content-Length: ' . $fileSize);
        header('Content-Disposition: attachment; filename=' . $filepath);

        readfile($filepath);

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

        $told = \mkw\store::toDate($tol);
        $igd = \mkw\store::toDate($ig);

        if (($told->format('Y') < 2016) || ($igd->format('Y') < 2016)) {
            echo json_encode(array(
                'result' => 'error',
                'msg' => t('Az adatszolgáltatás csak 2016.01.01-től működik.')
            ));
            return;
        }

        echo json_encode(array(
            'result' => 'ok',
            'href' => \mkw\store::getRouter()->generate('adminnavadatexportget', false, array(), array(
                'tol' => $tol,
                'ig' => $ig,
                'szlasztol' => $szlasztol,
                'szlaszig' => $szlaszig
            ))
        ));
    }

}