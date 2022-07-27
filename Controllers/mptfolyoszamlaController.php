<?php

namespace Controllers;

use Entities\MPTFolyoszamla;
use Entities\Rendezveny;

class mptfolyoszamlaController extends \mkwhelpers\MattableController {

    public function __construct($params) {
        $this->setEntityName(MPTFolyoszamla::class);
        $this->setKarbFormTplName('mptfolyoszamlakarbform.tpl');
        $this->setKarbTplName('mptfolyoszamlakarb.tpl');
        $this->setListBodyRowTplName('mptfolyoszamlalista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct($params);
    }

    public function loadVars($t, $forKarb = false) {
        $x = array();
        if (!$t) {
            $t = new \Entities\MPTFolyoszamla();
            $this->getEm()->detach($t);
        }
        $x['id'] = $t->getId();
        $x['tipus'] = $t->getTipus();
        $x['tipusnev'] = $t->getTipusnev();
        $x['irany'] = $t->getIrany();
        $x['bizonylatszam'] = $t->getBizonylatszam();
        $x['megjegyzes'] = $t->getMegjegyzes();
        $x['osszeg'] = $t->getOsszeg();
        $x['partner'] = $t->getPartnerId();
        $x['partnernev'] = $t->getPartnernev();
        $x['datum'] = $t->getDatumStr();
        $x['vonatkozoev'] = $t->getVonatkozoev();

        if ($forKarb) {
        }
        return $x;
    }

    /**
     * @param \Entities\Helyszin $obj
     * @param $oper
     * @return mixed
     */
    protected function setFields($obj, $oper) {
        $obj->setNev($this->params->getStringRequestParam('nev'));
        $obj->setEmailsablon($this->params->getOriginalStringRequestParam('emailsablon'));
        $obj->setAr($this->params->getNumRequestParam('ar'));
        return $obj;
    }

    public function getlistbody() {
        $view = $this->createView('mptfolyoszamlalista_tbody.tpl');

        $filterarr = new \mkwhelpers\FilterDescriptor();

        $this->initPager($this->getRepo()->getCount($filterarr));

        $egyedek = $this->getRepo()->getWithJoins(
            $filterarr, $this->getOrderArray(), $this->getPager()->getOffset(), $this->getPager()->getElemPerPage());

        echo json_encode($this->loadDataToView($egyedek, 'egyedlista', $view));
    }

    public function getSelectList($selid = null) {
        $rec = $this->getRepo()->getAll(array(), array('nev' => 'ASC'));
        $res = array();
        /** @var \Entities\Helyszin $sor */
        foreach ($rec as $sor) {
            $res[] = array('id' => $sor->getId(), 'caption' => $sor->getNev(), 'selected' => ($sor->getId() == $selid));
        }
        return $res;
    }

    public function viewselect() {
        $view = $this->createView('helyszinlista.tpl');

        $view->setVar('pagetitle', t('Helyszínek'));
        $view->printTemplateResult(false);
    }

    public function viewlist() {
        $view = $this->createView('helyszinlista.tpl');

        $view->setVar('pagetitle', t('Helyszínek'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult(false);
    }

    protected function _getkarb($tplname) {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Helyszin'));
        $view->setVar('formaction', '/admin/helyszin/save');
        $view->setVar('oper', $oper);
        $record = $this->getRepo()->findWithJoins($id);
        $view->setVar('egyed', $this->loadVars($record, true));
        return $view->getTemplateResult();
    }

}