<?php

namespace Controllers;

use Entities\JogaBejelentkezes;

class jogabejelentkezesController extends \mkwhelpers\MattableController {

    public function __construct($params) {
        $this->setEntityName(JogaBejelentkezes::class);
        $this->setKarbFormTplName('jogabejelentkezeskarbform.tpl');
        $this->setKarbTplName('jogabejelentkezeskarb.tpl');
        $this->setListBodyRowTplName('jogabejelentkezeslista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct($params);
    }

    protected function loadVars($t, $forKarb = false) {
        $x = array();
        if (!$t) {
            $t = new \Entities\JogaBejelentkezes();
            $this->getEm()->detach($t);
            $x['oper'] = 'add';
            $x['id'] = \mkw\store::createUID();
        }
        else {
            $x['oper'] = 'edit';
            $x['id'] = $t->getId();
        }
        $x['datum'] = $t->getDatumStr();
        $x['napnev'] = $t->getDatumNapnev();

        $x['partnernev'] = $t->getPartnernev();
        $x['partneremail'] = $t->getPartneremail();

        return $x;
    }

    /**
     * @param \Entities\JogaBejelentkezes $obj
     * @param $oper
     * @return mixed
     */
    protected function setFields($obj, $oper) {
        return $obj;
    }

    public function getlistbody() {
        $view = $this->createView('jogabejelentkezeslista_tbody.tpl');

        $filter = new \mkwhelpers\FilterDescriptor();

        $f = $this->params->getStringRequestParam('partnernevfilter');
        if ($f) {
            $filter->addFilter('partnernev', 'LIKE', '%' . $f . '%');
        }

        $f = $this->params->getStringRequestParam('partneremailfilter');
        if ($f) {
            $filter->addFilter('partneremail', 'LIKE', '%' . $f . '%');
        }

        $tol = $this->params->getStringRequestParam('datumtolfilter');
        $ig = $this->params->getStringRequestParam('datumigfilter');
        if ($tol) {
            $filter->addFilter('datum', '>=', $tol);
        }
        if ($ig) {
            $filter->addFilter('datum', '<=', $ig);
        }

        $this->initPager($this->getRepo()->getCount($filter));

        $egyedek = $this->getRepo()->getWithJoins(
            $filter, $this->getOrderArray(), $this->getPager()->getOffset(), $this->getPager()->getElemPerPage());

        echo json_encode($this->loadDataToView($egyedek, 'egyedlista', $view));
    }

    public function getSelectList($selid = null) {
        $rec = $this->getRepo()->getAll(array(), array('partnernev' => 'ASC'));
        $res = array();
        foreach ($rec as $sor) {
            $res[] = array('id' => $sor['id'], 'caption' => $sor['partnernev'], 'selected' => ($sor['id'] == $selid));
        }
        return $res;
    }

    public function viewselect() {
        $view = $this->createView('jogabejelentkezeslista.tpl');

        $view->setVar('pagetitle', t('Óra látogatások'));
        $view->printTemplateResult(false);
    }

    public function viewlist() {
        $view = $this->createView('jogabejelentkezeslista.tpl');

        $view->setVar('pagetitle', t('Óra látogatások'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult(false);
    }

    protected function _getkarb($tplname) {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Óra látogatás'));
        $view->setVar('formaction', '/admin/jogabejelentkezes/save');
        $view->setVar('oper', $oper);
        /** @var \Entities\JogaBejelentkezes $record */
        $record = $this->getRepo()->findWithJoins($id);
        $view->setVar('egyed', $this->loadVars($record));

        return $view->getTemplateResult();
    }

    public function bejelentkezes() {
        $partnernev = $this->params->getStringRequestParam('partnernev');
        $email = $this->params->getStringRequestParam('email');
        $datumstr = $this->params->getStringRequestParam('datum');
        $orarendid = $this->params->getIntRequestParam('id');
        if ($partnernev && $email) {
            $obj = new JogaBejelentkezes();
            $obj->setDatum($datumstr);
            $obj->setPartneremail($email);
            $obj->setPartnernev($partnernev);
            $obj->setOrarend($this->getRepo('Entities\Orarend')->find($orarendid));
            $this->getEm()->persist($obj);
            $this->getEm()->flush();
        }
    }
}