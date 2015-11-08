<?php
namespace Controllers;

use mkw\store;

class teendoController extends \mkwhelpers\MattableController {

    public function __construct($params) {
        $this->setEntityName('Entities\Teendo');
        $this->setKarbFormTplName('teendokarbform.tpl');
        $this->setKarbTplName('teendokarb.tpl');
        $this->setListBodyRowTplName('teendolista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct($params);
    }

    protected function loadVars($t) {
        $x = array();
        if (!$t) {
            $t = new \Entities\Teendo();
            $this->getEm()->detach($t);
        }
        $x['id'] = $t->getId();
        $x['bejegyzes'] = $t->getBejegyzes();
        $x['leiras'] = $t->getLeiras();
        $x['letrehozva'] = $t->getLetrehozva();
        $x['elvegezve'] = $t->getElvegezve();
        $x['elvegezve_mikor'] = $t->getElvegezveMikor();
        $x['elvegezve_mikorstr'] = $t->getElvegezveMikorStr();
        $x['esedekes'] = $t->getEsedekes();
        $x['esedekesstr'] = $t->getEsedekesStr();
        $x['partner'] = $t->getPartner();
        $x['partnernev'] = $t->getPartnerNev();
        return $x;
    }

    protected function setFields($obj) {
        $ck = store::getEm()->getRepository('Entities\Partner')->find($this->params->getIntRequestParam('partner'));
        if ($ck) {
            $obj->setPartner($ck);
        }
        $obj->setBejegyzes($this->params->getStringRequestParam('bejegyzes'));
        $obj->setLeiras($this->params->getOriginalStringRequestParam('leiras'));
        $obj->setEsedekes($this->params->getStringRequestParam('esedekes'));
        $obj->setElvegezve($this->params->getBoolRequestParam('elvegezve'));
        return $obj;
    }

    public function getlistbody() {
        $view = $this->createView('teendolista_tbody.tpl');

        $filterarr = new \mkwhelpers\FilterDescriptor();
        if (!is_null($this->params->getRequestParam('bejegyzesfilter', NULL))) {
            $filterarr->addFilter(array('_xx.bejegyzes', '_xx.leiras', 'a.nev'), 'LIKE', '%' . $this->params->getStringRequestParam('bejegyzesfilter') . '%');
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
            $this->getPager()->getElemPerPage());

        echo json_encode($this->loadDataToView($egyedek, 'egyedlista', $view));
    }

    public function viewselect() {
        $view = $this->createView('teendolista.tpl');

        $view->setVar('pagetitle', t('Teendők'));
        $view->printTemplateResult();
    }

    public function viewlist() {
        $view = $this->createView('teendolista.tpl');

        $view->setVar('pagetitle', t('Teendők'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname) {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Teendő'));
        $view->setVar('formaction', '/admin/teendo/save');
        $view->setVar('oper', $oper);
        $record = $this->getRepo()->findWithJoins($id);
        $view->setVar('egyed', $this->loadVars($record));

        $partner = new partnerController($this->params);
        $view->setVar('partnerlist', $partner->getSelectList(($record ? $record->getPartnerId() : 0)));

        return $view->getTemplateResult();
    }

    public function setflag() {
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