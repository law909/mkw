<?php

namespace Controllers;

use Entities\Bizonylattipus;
use Services\BackorderService;
use Services\BizonylatConcatService;
use Services\BizonylatCalculatorService;
use Services\FoxpostService;
use Services\GLSService;

class megrendelesfejController extends bizonylatfejController
{

    public function __construct()
    {
        $this->biztipus = 'megrendeles';
        $this->setPageTitle('Megrendelés');
        $this->setPluralPageTitle('Megrendelések');
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

    public function doPrintelolegbekero()
    {
        $o = $this->getRepo()->findForPrint($this->params->getStringRequestParam('id'));
        if ($o) {
            $biztip = $this->getRepo(Bizonylattipus::class)->find($this->biztipus);
            if ($biztip) {
                if (\mkw\store::isSuperzoneB2B()) {
                    $view = $this->createView('biz_elolegbekero_eng.tpl');
                } else {
                    $view = $this->createView('biz_elolegbekero.tpl');
                }
                $this->setVars($view);
                $view->setVar('egyed', $o->toLista());
                $view->setVar('afaosszesito', $this->getRepo()->getAFAOsszesito($o));
                echo $view->getTemplateResult();
            }
        }
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
