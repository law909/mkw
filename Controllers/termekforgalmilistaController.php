<?php

namespace Controllers;


class termekforgalmilistaController extends \mkwhelpers\Controller {

    function view() {
        $view = $this->createView('termekforgalmilista.tpl');

        $view->setVar('pagetitle', t('Termékforgalmi lista'));
        $view->setVar('datumtipus', 'teljesites');
        $rc = new raktarController($this->params);
        $view->setVar('raktarlista', $rc->getSelectList());
        $partner = new partnerController($this->params);
        $view->setVar('partnerlist', $partner->getSelectList());
        $arsav = new termekarController($this->params);
        $view->setVar('arsavlist', $arsav->getSelectList());

        $view->printTemplateResult(false);

    }

    public function refresh() {
        $partnerid = $this->params->getIntRequestParam('partnerid');
        $raktarid = $this->params->getIntRequestParam('raktarid');
        $datumtipus = $this->params->getStringRequestParam('datumtipus');
        $datumtolstr = $this->params->getStringRequestParam('datumtol');
        $datumigstr = $this->params->getStringRequestParam('datumig');
        $forgalomfilter = $this->params->getIntRequestParam('forgalomfilter');
        $keszletfilter = $this->params->getIntRequestParam('keszletfilter');
        $ertektipus = $this->params->getIntRequestParam('ertektipus');
        $arsav = $this->params->getStringRequestParam('arsav');
        $fafilter = $this->params->getArrayRequestParam('fafilter');
        $nevfilter = $this->params->getRequestParam('nevfilter', NULL);

        $tetelek = $this->getRepo('Entities\Bizonylatfej')->getTermekForgalmiLista($raktarid, $partnerid, $datumtipus, $datumtolstr, $datumigstr, $ertektipus,
            $arsav, $fafilter, $nevfilter);

        switch ($keszletfilter) {
            case 1: // van keszleten
                foreach ($tetelek as $key => $tetel) {
                    if ($tetel['zaro'] <= 0) {
                        unset($tetelek[$key]);
                    }
                };
                break;
            case 2: // nincs keszleten
                foreach ($tetelek as $key => $tetel) {
                    if ($tetel['zaro'] > 0) {
                        unset($tetelek[$key]);
                    }
                };
                break;
            case 3: // negativ
                foreach ($tetelek as $key => $tetel) {
                    if ($tetel['zaro'] >= 0) {
                        unset($tetelek[$key]);
                    }
                };
                break;
        }

        switch ($forgalomfilter) {
            case 1: // mozgott
                foreach ($tetelek as $key => $tetel) {
                    if ($tetel['be'] === 0 && $tetel['ki'] === 0) {
                        unset($tetelek[$key]);
                    }
                };
                break;
            case 2: // nem mozgott
                foreach ($tetelek as $key => $tetel) {
                    if ($tetel['be'] !== 0 || $tetel['ki'] !== 0) {
                        unset($tetelek[$key]);
                    }
                };
                break;
        }

        $view = $this->createView('termekforgalmilistatetel.tpl');
        $view->setVar('ertektipus', $ertektipus);
        $view->setVar('tetelek', $tetelek);
        $view->printTemplateResult();
    }

}