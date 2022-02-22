<?php
namespace Controllers;

use Entities\Kupon;
use mkw\store;

class kuponController extends \mkwhelpers\MattableController {

    public function __construct($params) {
        $this->setEntityName('Entities\Kupon');
        $this->setKarbFormTplName('kuponkarbform.tpl');
        $this->setKarbTplName('kuponkarb.tpl');
        $this->setListBodyRowTplName('kuponlista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct($params);
    }

    protected function loadVars($t) {
        $x = array();
        if (!$t) {
            $t = new \Entities\Kupon();
            $this->getEm()->detach($t);
        }
        $x['id'] = $t->getId();
        $x['lejart'] = $t->getLejart();
        $x['lejartstr'] = $t->getLejartStr();
        $x['tipus'] = $t->getTipus();
        $x['tipusstr'] = $t->getTipusStr();
        $x['createdstr'] = $t->getCreatedStr();
        $x['osszeg'] = $t->getOsszeg();
        $x['minimumosszeg'] = $t->getMinimumosszeg();
        return $x;
    }

    /**
     * @param Kupon $obj
     * @return mixed
     */
    protected function setFields($obj) {
        $obj->setId($this->params->getStringRequestParam('xid'));
        $obj->setLejart($this->params->getIntRequestParam('lejart'));
        $obj->setTipus($this->params->getIntRequestParam('tipus'));
        $obj->setOsszeg($this->params->getNumRequestParam('osszeg'));
        $obj->setMinimumosszeg($this->params->getNumRequestParam('minimumosszeg'));
        \mkw\store::writelog('setfields' . $obj->getId());
        return $obj;
    }

    public function getlistbody() {
        $view = $this->createView('kuponlista_tbody.tpl');

        $filter = new \mkwhelpers\FilterDescriptor();
        if (!is_null($this->params->getRequestParam('idfilter', NULL))) {
            $filter->addFilter('id', 'LIKE', '%' . $this->params->getStringRequestParam('idfilter') . '%');
        }

        $this->initPager(
            $this->getRepo()->getCount($filter),
            $this->params->getIntRequestParam('elemperpage', 30),
            $this->params->getIntRequestParam('pageno', 1));

        $egyedek = $this->getRepo()->getAll(
            $filter,
            $this->getOrderArray(),
            $this->getPager()->getOffset(),
            $this->getPager()->getElemPerPage());

        echo json_encode($this->loadDataToView($egyedek, 'egyedlista', $view));
    }

    public function viewselect() {
        $view = $this->createView('kuponlista.tpl');

        $view->setVar('pagetitle', t('Kupon'));
        $view->printTemplateResult();
    }

    public function viewlist() {
        $view = $this->createView('kuponlista.tpl');

        $view->setVar('pagetitle', t('Kupon'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname) {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $kupon = $this->getRepo()->find($id);

        $view->setVar('pagetitle', t('Kupon'));
        $view->setVar('oper', $oper);
        $record = $this->getRepo()->find($id);
        $view->setVar('egyed', $this->loadVars($record));

        $view->setVar('tipuslist', $this->getTipusSelectList(($kupon ? $kupon->getTipus() : 0)));
        $view->setVar('lejaratlist', $this->getLejaratSelectList(($kupon ? $kupon->getLejart() : 0)));

        return $view->getTemplateResult();
    }

    public function getTipusSelectList($sel = null) {
        $ret = array();
        foreach (\Entities\Kupon::getTipusLista() as $i => $v) {
            $ret[] = array(
                'id' => $i,
                'caption' => $v,
                'selected' => ($i === $sel)
            );
        }
        return $ret;
    }

    public function getLejaratSelectList($sel = null) {
        $ret = array();
        foreach (\Entities\Kupon::getLejaratLista() as $i => $v) {
            $ret[] = array(
                'id' => $i,
                'caption' => $v,
                'selected' => ($i === $sel)
            );
        }
        return $ret;
    }

    public function doPrint() {
        $o = $this->getRepo()->find($this->params->getStringRequestParam('id'));
        if ($o) {
            $view = $this->createView($o->getReportfilename());
            $this->setVars($view);
            $view->setVar('egyed', $o->toLista());
            echo $view->getTemplateResult();
        }
    }

}