<?php

namespace Controllers;

use Services\BackorderService;
use Services\BizonylatConcatService;
use Services\BizonylatCalculatorService;
use Services\FoxpostService;
use Services\GLSService;

class webshopbizfejController extends bizonylatfejController
{

    public function __construct()
    {
        $this->biztipus = 'webshopbiz';
        $this->setPageTitle('Webshop rendelés');
        $this->setPluralPageTitle('Webshop rendelések');
        parent::__construct();
        $this->getRepo()->addToBatches(['foxpostsend' => 'Küldés Foxpostnak']);
        $this->getRepo()->addToBatches(['foxpostlabel' => 'Foxpost címke letöltés']);
        $this->getRepo()->addToBatches(['glssend' => 'Küldés GLS-nek']);
        $this->getRepo()->addToBatches(['recalcprice' => 'Árak újra számolása']);
        $this->getRepo()->addToBatches(['sendemailek' => 'Email sablon küldés']);
        $this->getRepo()->addToBatches(['rendelesconcat' => 'Megrendelések összevonása']);
    }

    public function setVars($view)
    {
        parent::setVars($view);
        $view->setVar('datumtolfilter', null);
    }

    public function getszamlakarb()
    {
        $megrendszam = $this->params->getStringRequestParam('id');
        $szamlac = new SzamlafejController();
        $szamlac->getkarb('bizonylatfejkarb.tpl', $megrendszam, 'add');
    }

    public function sendToFoxPost()
    {
        $foxpostSvc = new FoxpostService();
        $foxpostSvc->sendToFoxPost($this->params->getArrayRequestParam('ids'));
    }

    public function generateFoxpostLabel()
    {
        $foxpostSvc = new FoxpostService();
        $foxpostSvc->generateFoxpostLabel($this->params->getArrayRequestParam('ids'));
    }

    public function sendToGLS()
    {
        $glssvc = new GLSService();
        $glssvc->sendToGLS($this->params->getArrayRequestParam('ids'));
    }

    public function delGLSParcel()
    {
        $glssvc = new GLSService();
        $glssvc->delGLSParcel($this->params->getStringRequestParam('id'));
    }

    public function backOrder()
    {
        $backorderSvc = new BackorderService();
        echo json_encode(
            $backorderSvc->backOrder(
                $this->params->getStringRequestParam('id')
            )
        );
    }

    public function recalcPrice()
    {
        $bizrecalcSvc = new BizonylatCalculatorService();
        $bizrecalcSvc->recalcPrice($this->params->getArrayRequestParam('ids'));
    }

    public function concat()
    {
        $concatSvc = new BizonylatConcatService();
        $concatSvc->concat($this->params->getArrayRequestParam('ids'));
    }

}
