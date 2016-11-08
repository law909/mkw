<?php

namespace Controllers;


class bizomanyosertekesiteslistaController extends \mkwhelpers\Controller {

    public function view() {
        $view = $this->createView('bizomanyosertekesiteslista.tpl');

        $view->setVar('pagetitle', t('Bizományos értékesítés lista'));
        $view->setVar('datumtipus', 'teljesites');
        $partner = new partnerController($this->params);
        $view->setVar('partnerlist', $partner->getSelectList());
        $arsav = new termekarController($this->params);
        $view->setVar('arsavlist', $arsav->getSelectList());
        $pcc = new partnercimkekatController($this->params);
        $view->setVar('cimkekat', $pcc->getWithCimkek());

        $view->printTemplateResult(false);

    }

    public function refresh() {
        $partnerid = $this->params->getIntRequestParam('partnerid');
        $datumtipus = $this->params->getStringRequestParam('datumtipus');
        $datumtolstr = $this->params->getStringRequestParam('datumtol');
        $datumigstr = $this->params->getStringRequestParam('datumig');
        $ertektipus = $this->params->getIntRequestParam('ertektipus');
        $arsav = $this->params->getStringRequestParam('arsav');
        $partnercimkefilter = $this->params->getArrayRequestParam('partnercimkefilter');

        $tetelek = $this->getRepo('Entities\Bizonylatfej')->getBizomanyosErtekesitesLista($partnerid, $datumtipus, $datumtolstr, $datumigstr, $ertektipus,
            $arsav, $partnercimkefilter);

        $view = $this->createView('bizomanyosertekesiteslistatetel.tpl');
        $view->setVar('ertektipus', $ertektipus);
        $view->setVar('tetelek', $tetelek);
        $view->printTemplateResult();
    }

}