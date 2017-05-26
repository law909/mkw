<?php
namespace Controllers;

class menuController extends \mkwhelpers\MattableController {

    public function __construct($params) {
        $this->setEntityName('Entities\Menu');
        $this->setKarbFormTplName('menukarbform.tpl');
        $this->setKarbTplName('menukarb.tpl');
        $this->setListBodyRowTplName('menulista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct($params);
    }

    protected function loadVars($t) {
        $x = array();
        if (!$t) {
            $t = new \Entities\Menu();
            $this->getEm()->detach($t);
        }
        $x['id'] = $t->getId();
        $x['nev'] = $t->getNev();
        $x['url'] = $t->getUrl();
        $x['routename'] = $t->getRoutename();
        $x['lathato'] = $t->getLathato();
        $x['jogosultsag'] = $t->getJogosultsag();
        $x['menucsoportnev'] = $t->getMenucsoportNev();
        $x['class'] = $t->getClass();
        return $x;
    }

    /**
     * @param \Entities\Menu $obj
     * @return mixed
     */
    protected function setFields($obj) {
        $obj->setNev($this->params->getStringRequestParam('nev'));
        $obj->setUrl($this->params->getOriginalStringRequestParam('url'));
        $obj->setRoutename($this->params->getStringRequestParam('routename'));
        $obj->setJogosultsag($this->params->getIntRequestParam('jogosultsag'));
        $obj->setLathato($this->params->getBoolRequestParam('lathato'));
        $obj->setClass($this->params->getStringRequestParam('class'));
        $ck = $this->getRepo('Entities\Menucsoport')->find($this->params->getIntRequestParam('menucsoport'));
        if ($ck) {
            $obj->setMenucsoport($ck);
        }
        return $obj;
    }

    public function getlistbody() {
        $view = $this->createView('menulista_tbody.tpl');

        $filter = new \mkwhelpers\FilterDescriptor();
        if (!is_null($this->params->getRequestParam('nevfilter', NULL))) {
            $filter->addFilter('nev', 'LIKE', '%' . $this->params->getStringRequestParam('nevfilter') . '%');
        }

        $this->initPager(
            $this->getRepo()->getCount($filter),
            $this->params->getIntRequestParam('elemperpage', 30),
            $this->params->getIntRequestParam('pageno', 1));

        $egyedek = $this->getRepo()->getWithJoins(
            $filter,
            $this->getOrderArray(),
            $this->getPager()->getOffset(),
            $this->getPager()->getElemPerPage());

        echo json_encode($this->loadDataToView($egyedek, 'egyedlista', $view));
    }

    public function viewselect() {
        $view = $this->createView('menulista.tpl');

        $view->setVar('pagetitle', t('Menü'));
        $view->printTemplateResult();
    }

    public function viewlist() {
        $view = $this->createView('menulista.tpl');

        $view->setVar('pagetitle', t('Menü'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname) {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Menü'));
        $view->setVar('oper', $oper);
        $record = $this->getRepo()->findWithJoins($id);
        $view->setVar('egyed', $this->loadVars($record));
        return $view->getTemplateResult();
    }

    public function getMenu() {
        $menu = array();
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter
            ->addFilter('lathato', '=', true)
            ->addSql('(m.lathato=1) OR (m.lathato IS NULL)');
        $adat = $this->getRepo()->getAll($filter, array('m.sorrend' => 'ASC', 'sorrend' => 'ASC'));
        /** @var \Entities\Menu $rek */
        foreach($adat as $rek) {
            if ($rek->isLathato(\mkw\store::getJog())) {
                $menu[] = array(
                    'mcsid' => $rek->getMenucsoportId(),
                    'mcsnev' => $rek->getMenucsoportNev(),
                    'nev' => $rek->getNev(),
                    'url' => $rek->getUrl(),
                    'class' => $rek->getClass()
                );
            }
        }
        return $menu;
    }

}