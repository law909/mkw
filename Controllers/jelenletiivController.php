<?php
namespace Controllers;

use Entities\Jelenletiiv;

use mkw\store;

class jelenletiivController extends \mkwhelpers\MattableController {

    public function __construct($params) {
        $this->setEntityName('Entities\Jelenletiiv');
        $this->setKarbFormTplName('jelenletiivkarbform.tpl');
        $this->setKarbTplName('jelenletiivkarb.tpl');
        $this->setListBodyRowTplName('jelenletiivlista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct($params);
    }

    protected function loadVars($t) {
        $x = array();
        if (!$t) {
            $t = new \Entities\Jelenletiiv();
            $this->getEm()->detach($t);
        }
        $x['id'] = $t->getId();
        $x['datum'] = $t->getDatum();
        $x['datumstr'] = $t->getDatumStr();
        $x['munkaido'] = $t->getMunkaido();
        $x['dolgozo'] = $t->getDolgozoId();
        $x['dolgozonev'] = $t->getDolgozoNev();
        $x['jelenlettipus'] = $t->getJelenlettipusId();
        $x['jelenlettipusnev'] = $t->getJelenlettipusNev();
        return $x;
    }

    public function setFields($obj) {
        $obj->setDatum($this->params->getStringRequestParam('datum'));
        $obj->setMunkaido($this->params->getIntRequestParam('munkaido'));
        $ck = store::getEm()->getRepository('Entities\Dolgozo')->find($this->params->getIntRequestParam('dolgozo', 0));
        if ($ck) {
            $obj->setDolgozo($ck);
        }
        $ck = store::getEm()->getRepository('Entities\Jelenlettipus')->find($this->params->getIntRequestParam('jelenlettipus', 0));
        if ($ck) {
            $obj->setJelenlettipus($ck);
        }
        return $obj;
    }

    public function getlistbody() {
        $view = $this->createView('jelenletiivlista_tbody.tpl');

        $filter = new \mkwhelpers\FilterDescriptor();

        if (!is_null($this->params->getRequestParam('tolfilter', NULL))) {
            $filter->addFilter('datum', '>=', Store::convDate(($this->params->getStringRequestParam('tolfilter', ''))));
        }
        if (!is_null($this->params->getRequestParam('igfilter', NULL))) {
            $filter->addFilter('datum', '<=', Store::convDate(($this->params->getStringRequestParam('igfilter', ''))));
        }
        $fv = $this->params->getIntRequestParam('dolgozofilter');
        if ($fv > 0) {
            $filter->addFilter('d.id', '=', $fv);
        }
        $fv = $this->params->getIntRequestParam('jelenlettipusfilter');
        if ($fv > 0) {
            $filter->addFilter('j.id', '=', $fv);
        }

        $this->initPager($this->getRepo()->getCount($filter));

        $egyedek = $this->getRepo()->getWithJoins(
            $filter,
            $this->getOrderArray(),
            $this->getPager()->getOffset(),
            $this->getPager()->getElemPerPage());

        echo json_encode($this->loadDataToView($egyedek, 'egyedlista', $view));
    }

    public function viewselect() {
        $view = $this->createView('jelenletiivlista.tpl');

        $view->setVar('pagetitle', t('Jelenléti ívek'));
        $view->printTemplateResult(false);
    }

    public function viewlist() {
        $view = $this->createView('jelenletiivlista.tpl');

        $view->setVar('pagetitle', t('Jelenléti ívek'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $dolgozo = new dolgozoController($this->params);
        $view->setVar('dolgozolist', $dolgozo->getSelectList(0));
        $jt = new jelenlettipusController($this->params);
        $view->setVar('jelenlettipuslist', $jt->getSelectList(0));
        $view->printTemplateResult(false);
    }

    protected function _getkarb($tplname) {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Jelenléti ív'));
        $view->setVar('formaction', '/admin/jelenletiiv/save');
        $view->setVar('oper', $oper);
        $record = $this->getRepo()->findWithJoins($id);
        $view->setVar('egyed', $this->loadVars($record));
        $dolgozo = new dolgozoController($this->params);
        $view->setVar('dolgozolist', $dolgozo->getSelectList(($record ? $record->getDolgozoId() : 0)));
        $jt = new jelenlettipusController($this->params);
        $view->setVar('jelenlettipuslist', $jt->getSelectList(($record ? $record->getJelenlettipusId() : 0)));
        return $view->getTemplateResult();
    }

    public function generatenapi() {
        $nap = $this->params->getStringRequestParam('datum', '');
        $jt = store::getEm()->getRepository('Entities\Jelenlettipus')->find($this->params->getIntRequestParam('jt', 0));
        $egyedek = store::getEm()->getRepository('Entities\Dolgozo')->getWithJoins(array(), array());
        foreach ($egyedek as $egyed) {
            if ($this->getRepo()->getCount('(_xx.datum=\'' . $nap . '\') AND (d.id=' . $egyed->getId() . ') AND (j.id=' . $jt->getId() . ')') == 0) {
                $jelen = new Jelenletiiv();
                $jelen->setDatum($nap);
                $jelen->setDolgozo($egyed);
                $jelen->setJelenlettipus($jt);
                $jelen->setMunkaido(8);
                $this->getEm()->persist($jelen);
            }
        }
        $this->getEm()->flush();
    }
}