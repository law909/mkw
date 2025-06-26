<?php

namespace Controllers;

use Entities\Bankbizonylattetel;
use mkw\store;

class bankbizonylattetelController extends \mkwhelpers\MattableController
{

    public function __construct($params)
    {
        $this->setEntityName('Entities\Bankbizonylattetel');
//		$this->setKarbFormTplName('?howto?karbform.tpl');
//		$this->setKarbTplName('?howto?karb.tpl');
        $this->setListBodyRowTplName('bankbizonylattetellista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct($params);
    }

    public function loadVars($t, $forKarb = false)
    {
        $oper = $this->params->getStringRequestParam('oper');
        $partner = new partnerController($this->params);
        $jogcim = new jogcimController($this->params);
        $valutanem = new valutanemController($this->params);
        $x = [];
        if (!$t) {
            $t = new \Entities\Bankbizonylattetel();
            $this->getEm()->detach($t);
            $x['id'] = store::createUID();
            $x['oper'] = 'add';
        } else {
            $x['id'] = $t->getId();
            $x['oper'] = 'edit';
        }
        $x['fejid'] = $t->getBizonylatfejId();
        $x['keltstr'] = $t->getBizonylatfej()->getKeltStr();
        $x['irany'] = $t->getIrany();
        $x['datumstr'] = $t->getDatumStr();
        $x['netto'] = $t->getNetto();
        $x['afa'] = $t->getAfa();
        $x['brutto'] = $t->getBrutto();
        $x['partner'] = $t->getPartnerId();
        $x['partnernev'] = $t->getPartnernev();
        $x['jogcim'] = $t->getJogcimId();
        $x['jogcimnev'] = $t->getJogcimnev();
        $x['hivatkozottdatumstr'] = $t->getHivatkozottdatumStr();
        $x['hivatkozottbizonylat'] = $t->getHivatkozottbizonylat();
        $x['valutanem'] = $t->getValutanemId();
        $x['valutanemnev'] = $t->getValutanemnev();
        $x['erbizonylatszam'] = $t->getErbizonylatszam();

        if ($forKarb) {
            $x['partnerlist'] = $partner->getSelectList($t->getPartnerId());
            $x['jogcimlist'] = $jogcim->getSelectList($t->getJogcimId());
            $x['valutanemlist'] = $valutanem->getSelectList($t->getValutanemId());
        }

        return $x;
    }

    protected function setFields($obj)
    {
        return $obj;
    }

    public function getemptyrow()
    {
        $biztipus = $this->params->getStringRequestParam('type');
        $view = $this->createView('bankbizonylattetelkarb.tpl');

        $tetel = $this->loadVars(null, true);
        $view->setVar('tetel', $tetel);

        $bt = $this->getRepo('Entities\Bizonylattipus')->find($biztipus);
        $bt->setTemplateVars($view);

        $res = [
            'html' => $view->getTemplateResult(),
            'id' => $tetel['id']
        ];
        echo json_encode($res);
    }

    public function viewselect()
    {
        $view = $this->createView('bankbizonylattetellista.tpl');

        $view->setVar('pagetitle', 'Bankbizonylat tételek');
        $view->setVar('controllerscript', 'bankbizonylattetel.js');
        $this->setVars($view);
        $view->printTemplateResult();
    }

    public function viewlist()
    {
        $view = $this->createView('bankbizonylattetellista.tpl');

        $view->setVar('pagetitle', 'Bankbizonylat tételek');
        $view->setVar('controllerscript', 'bankbizonylattetel.js');
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());

        $partner = new partnerController($this->params);
        $valutanem = new valutanemController($this->params);

        $view->setVar('partnerlist', $partner->getSelectList());
        $view->setVar('valutanemlist', $valutanem->getSelectList());

        $this->setVars($view);
        $view->printTemplateResult();
    }

    public function getlistbody()
    {
        $filter = new \mkwhelpers\FilterDescriptor();

        $idfilter = $this->params->getStringRequestParam('idfilter', '');
        if ($idfilter) {
            $filter->addFilter('id', '=', $idfilter);
        }

        $datumtolfilter = $this->params->getStringRequestParam('datumtolfilter', '');
        if ($datumtolfilter) {
            $filter->addFilter('datum', '>=', $datumtolfilter);
        }

        $datumigfilter = $this->params->getStringRequestParam('datumigfilter', '');
        if ($datumigfilter) {
            $filter->addFilter('datum', '<=', $datumigfilter);
        }

        $erbizonylatszamfilter = $this->params->getStringRequestParam('erbizonylatszamfilter', '');
        if ($erbizonylatszamfilter) {
            $filter->addFilter('erbizonylatszam', 'LIKE', '%' . $erbizonylatszamfilter . '%');
        }

        $valutanemfilter = $this->params->getIntRequestParam('valutanemfilter', 0);
        if ($valutanemfilter) {
            $filter->addFilter('valutanem', '=', $valutanemfilter);
        }

        $partnerfilter = $this->params->getIntRequestParam('partnerfilter', 0);
        if ($partnerfilter) {
            $filter->addFilter('partner', '=', $partnerfilter);
        }

        $hivatkozottbizonylatfilter = $this->params->getStringRequestParam('hivatkozottbizonylatfilter', '');
        if ($hivatkozottbizonylatfilter) {
            $filter->addFilter('hivatkozottbizonylat', 'LIKE', '%' . $hivatkozottbizonylatfilter . '%');
        }

        $rontottfilter = $this->params->getIntRequestParam('bizonylatrontottfilter', 0);
        switch ($rontottfilter) {
            case 1:
                $filter->addFilter('rontott', '=', false);
                break;
            case 2:
                $filter->addFilter('rontott', '=', true);
                break;
        }

        $this->initPager($this->getRepo()->getCount($filter));

        $egyedek = $this->getRepo()->getWithJoins(
            $filter,
            $this->getOrderArray(),
            $this->getPager()->getOffset(),
            $this->getPager()->getElemPerPage()
        );

        $view = $this->createView('bankbizonylattetellista_tbody.tpl');

        echo json_encode($this->loadDataToView($egyedek, 'egyedlista', $view));
    }

    public function setVars($view)
    {
    }

}
