<?php

namespace Controllers;

use Entities\JogaBerlet;
use mkw\store;
use mkwhelpers\FilterDescriptor;

class jogaberletController extends \mkwhelpers\MattableController {

    public function __construct($params) {
        $this->setEntityName(JogaBerlet::class);
        $this->setKarbFormTplName('jogaberletkarbform.tpl');
        $this->setKarbTplName('jogaberletkarb.tpl');
        $this->setListBodyRowTplName('jogaberletlista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct($params);
    }

    protected function loadVars($t) {
        $x = array();
        if (!$t) {
            $t = new \Entities\JogaBerlet();
            $this->getEm()->detach($t);
        }
        $x['id'] = $t->getId();
        $x['termeknev'] = $t->getTermeknev();
        $x['partnernev'] = $t->getPartnernev();
        $x['vasarlasnapja'] = $t->getVasarlasnapjaStr();
        $x['lejaratdatum'] = $t->getLejaratdatumStr();
        $x['lejart'] = $t->isLejart();
        $x['elfogyottalkalom'] = $t->getElfogyottalkalom();
        $x['offlineelfogyottalkalom'] = $t->getOfflineelfogyottalkalom();
        $x['nincsfizetve'] = $t->isNincsfizetve();
        return $x;
    }

    /** @param \Entities\JogaBerlet $obj */
    protected function setFields($obj) {
        $ck = \mkw\store::getEm()->getRepository('Entities\Termek')->find($this->params->getIntRequestParam('termek'));
        if ($ck) {
            $obj->setTermek($ck);
        }
        else {
            $obj->removeTermek();
        }
        $ck = \mkw\store::getEm()->getRepository('Entities\Partner')->find($this->params->getIntRequestParam('partner'));
        if ($ck) {
            $obj->setPartner($ck);
        }
        else {
            $obj->removePartner();
        }
        $obj->setVasarlasnapja($this->params->getStringRequestParam('vasarlasnapja'));
        $obj->setElfogyottalkalom($this->params->getIntRequestParam('elfogyottalkalom'));
        $obj->setOfflineelfogyottalkalom($this->params->getIntRequestParam('offlineelfogyottalkalom'));
        $obj->setNincsfizetve($this->params->getBoolRequestParam('nincsfizetve', false));
        return $obj;
    }

    public function getlistbody() {
        $view = $this->createView('jogaberletlista_tbody.tpl');

        $filter = new \mkwhelpers\FilterDescriptor();
        if (!is_null($this->params->getRequestParam('vevonevfilter', NULL))) {
            $filter->addFilter('p.nev', 'LIKE', '%' . $this->params->getStringRequestParam('vevonevfilter') . '%');
        }
        if (!is_null($this->params->getIntRequestParam('termekfilter', NULL))) {
            $filter->addFilter('_xx.termek', '=', $this->params->getIntRequestParam('termekfilter', NULL));
        }
        $tol = $this->params->getStringRequestParam('datumtolfilter');
        $ig = $this->params->getStringRequestParam('datumigfilter');
        if ($tol) {
            $filter->addFilter('vasarlasnapja', '>=', $tol);
        }
        if ($ig) {
            $filter->addFilter('vasarlasnapja', '<=', $ig);
        }
        $tol = $this->params->getStringRequestParam('lejarattolfilter');
        $ig = $this->params->getStringRequestParam('lejaratigfilter');
        if ($tol) {
            $filter->addFilter('lejaratdatum', '>=', $tol);
        }
        if ($ig) {
            $filter->addFilter('lejaratdatum', '<=', $ig);
        }
        $f = $this->params->getIntRequestParam('lejartfilter');
        switch ($f) {
            case 1:
                $filter->addFilter('lejart', '=', false);
                break;
            case 2:
                $filter->addFilter('lejart', '=', true);
                break;
        }
        $f = $this->params->getIntRequestParam('nincsfizetvefilter');
        switch ($f) {
            case 1:
                $filter->addFilter('nincsfizetve', '=', false);
                break;
            case 2:
                $filter->addFilter('nincsfizetve', '=', true);
                break;
        }

        $this->initPager(
            $this->getRepo()->getCountWithJoins($filter), $this->params->getIntRequestParam('elemperpage', 30), $this->params->getIntRequestParam('pageno', 1));

        $egyedek = $this->getRepo()->getWithJoins(
            $filter, $this->getOrderArray(), $this->getPager()->getOffset(), $this->getPager()->getElemPerPage());

        echo json_encode($this->loadDataToView($egyedek, 'egyedlista', $view));
    }

    public function viewselect() {
        $view = $this->createView('jogaberletlista.tpl');

        $view->setVar('pagetitle', t('Jóga bérlet'));
        $view->setVar('controllerscript', 'jogaberletlista.js');
        $view->printTemplateResult();
    }

    public function viewlist() {
        $view = $this->createView('jogaberletlista.tpl');

        $view->setVar('pagetitle', t('Jóga bérlet'));
        $view->setVar('controllerscript', 'jogaberletlista.js');
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $termek = new termekController($this->params);
        $view->setVar('termeklist', $termek->getEladhatoSelectList());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname) {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Jóga bérlet'));
        $view->setVar('controllerscript', 'jogaberletkarb.js');
        $view->setVar('formaction', '/admin/jogaberlet/save');
        $view->setVar('oper', $oper);
        /** @var \Entities\JogaBerlet $record */
        $record = $this->getRepo()->findWithJoins($id);
        if (!\mkw\store::isPartnerAutocomplete()) {
            $partner = new partnerController($this->params);
            $view->setVar('partnerlist', $partner->getSelectList(($record ? $record->getPartnerId() : 0)));
        }
        if (!\mkw\store::isTermekAutocomplete()) {
            $termek = new termekController($this->params);
            $view->setVar('termeklist', $termek->getEladhatoSelectList(($record ? $record->getTermekId() : 0)));
        }
        $view->setVar('egyed', $this->loadVars($record));
        return $view->getTemplateResult();
    }

    public function getSelectList($selid = null, $partnerid = null) {
        $filter = new FilterDescriptor();
        $filter->addFilter('lejart', '=', false);
        if ($partnerid) {
            $filter->addFilter('partner', '=', $partnerid);
        }
        $rec = $this->getRepo()->getAll($filter, array('vasarlasnapja' => 'ASC'));
        $res = array();
        /** @var \Entities\JogaBerlet $sor */
        foreach ($rec as $sor) {
            $res[] = array(
                'id' => $sor->getId(),
                'caption' => $sor->getFullNev(),
                'termekid' => $sor->getTermekId(),
                'selected' => ($sor->getId() == $selid));
        }
        return $res;
    }

    public function getSelectHtml() {
        $data = $this->getSelectList(null, $this->params->getIntRequestParam('partnerid'));
        $view = $this->createView('jogareszvetelberletselect.tpl');
        $view->setVar('berletlist', $data);
        echo $view->getTemplateResult();
    }

}
