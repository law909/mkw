<?php

namespace Controllers;

use Entities\Bizonylatfej;

class rendbevlistaController extends \mkwhelpers\Controller
{

    public function view()
    {
        $view = $this->createView('rendbevlista.tpl');

        $view->setVar('pagetitle', t('Rendelt / beérkezett kimutatás'));
        $view->setVar('toldatum', date(\mkw\store::$DateFormat));
        $view->setVar('igdatum', date(\mkw\store::$DateFormat));
        $partner = new partnerController();
        $view->setVar('partnerlist', $partner->getSelectList());

        $view->printTemplateResult(false);
    }

    public function refresh()
    {
        $partnerid = $this->params->getIntRequestParam('partner');
        $datumtolstr = $this->params->getStringRequestParam('datumtol');
        $datumigstr = $this->params->getStringRequestParam('datumig');
        $datumtol = $datumtolstr ? \mkw\store::convDate($datumtolstr) : null;
        $datumig = $datumigstr ? \mkw\store::convDate($datumigstr) : null;

        $tetelek = $this->getRepo(Bizonylatfej::class)->getRendeltBeerkezettLista(
            $partnerid,
            $datumtol,
            $datumig,
            \mkw\store::getAdminDataLocale()
        );

        $view = $this->createView('rendbevlistatetel.tpl');
        $view->setVar('tetelek', $tetelek);
        $view->printTemplateResult();
    }

}
