<?php

namespace Controllers;

use Entities\Partner;
use Entities\Teendo;
use mkw\store;

class teendoController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(Teendo::class);
        $this->setKarbFormTplName('teendokarbform.tpl');
        $this->setKarbTplName('teendokarb.tpl');
        $this->setListBodyRowTplName('teendolista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct();
    }

    protected function loadVars($t)
    {
        $x = [];
        if (!$t) {
            $t = new \Entities\Teendo();
            $this->getEm()->detach($t);
        }
        $x = $this->getEntityFieldsArray($t);
        $x['elvegezve_mikorstr'] = $t->getElvegezveMikorStr();
        $x['esedekesstr'] = $t->getEsedekesStr();
        $x['partnernev'] = $t->getPartner()?->getNev();
        return $x;
    }

    protected function setFields($obj)
    {
        $obj = $this->setEntityFieldsFromRequest($obj, ['raw' => ['leiras']]);
        $ck = store::getEm()->getRepository(Partner::class)->find($this->params->getIntRequestParam('partner'));
        if ($ck) {
            $obj->setPartner($ck);
        }
        return $obj;
    }

    public function getlistbody()
    {
        $view = $this->createView('teendolista_tbody.tpl');

        $filterarr = new \mkwhelpers\FilterDescriptor();
        if (!is_null($this->params->getRequestParam('bejegyzesfilter', null))) {
            $filterarr->addFilter(['_xx.bejegyzes', '_xx.leiras', 'a.nev'], 'LIKE', '%' . $this->params->getStringRequestParam('bejegyzesfilter') . '%');
        }

        $fv = $this->params->getStringRequestParam('dtfilter');
        if ($fv !== '') {
            $filterarr->addFilter('_xx.esedekes', '>=', store::convDate($fv));
        }

        $fv = $this->params->getStringRequestParam('difilter');
        if ($fv !== '') {
            $filterarr->addFilter('_xx.esedekes', '<=', store::convDate($fv));
        }

        $this->initPager($this->getRepo()->getCount($filterarr));

        $egyedek = $this->getRepo()->getWithJoins(
            $filterarr,
            $this->getOrderArray(),
            $this->getPager()->getOffset(),
            $this->getPager()->getElemPerPage()
        );

        echo json_encode($this->loadDataToView($egyedek, 'egyedlista', $view));
    }

    public function viewselect()
    {
        $view = $this->createView('teendolista.tpl');

        $view->setVar('pagetitle', t('Teendők'));
        $view->printTemplateResult();
    }

    public function viewlist()
    {
        $view = $this->createView('teendolista.tpl');

        $view->setVar('pagetitle', t('Teendők'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Teendő'));
        $view->setVar('formaction', '/admin/teendo/save');
        $view->setVar('oper', $oper);
        $record = $this->getRepo()->findWithJoins($id);
        $view->setVar('egyed', $this->loadVars($record));

        $partner = new partnerController();
        $view->setVar('partnerlist', $partner->getSelectList(($record ? $record->getPartnerId() : 0)));

        return $view->getTemplateResult();
    }

    public function setflag()
    {
        $id = $this->params->getIntRequestParam('id');
        $kibe = $this->params->getBoolRequestParam('kibe');
        $flag = $this->params->getStringRequestParam('flag', '');
        $obj = $this->getRepo()->find($id);
        if ($obj) {
            switch ($flag) {
                case 'elvegezve':
                    $obj->setElvegezve($kibe);
                    break;
            }
            store::getEm()->persist($obj);
            store::getEm()->flush();
        }
    }
}