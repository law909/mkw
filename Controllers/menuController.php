<?php

namespace Controllers;

use Entities\Menu;
use Entities\Menucsoport;

class menuController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(Menu::class);
        $this->setKarbFormTplName('menukarbform.tpl');
        $this->setKarbTplName('menukarb.tpl');
        $this->setListBodyRowTplName('menulista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct();
    }

    protected function loadVars($t)
    {
        if (!$t) {
            $t = new \Entities\Menu();
            $this->getEm()->detach($t);
        }
        return $this->getEntityFieldsArray($t);
    }

    /**
     * @param \Entities\Menu $obj
     *
     * @return mixed
     */
    protected function setFields($obj)
    {
        $obj = $this->setEntityFieldsFromRequest($obj, ['raw' => ['url']]);
        $ck = $this->getRepo(Menucsoport::class)->find($this->params->getIntRequestParam('menucsoport'));
        if ($ck) {
            $obj->setMenucsoport($ck);
        }
        return $obj;
    }

    public function getlistbody()
    {
        $view = $this->createView('menulista_tbody.tpl');

        $filter = new \mkwhelpers\FilterDescriptor();
        if (!is_null($this->params->getRequestParam('nevfilter', null))) {
            $filter->addFilter('nev', 'LIKE', '%' . $this->params->getStringRequestParam('nevfilter') . '%');
        }

        $this->initPager(
            $this->getRepo()->getCount($filter),
            $this->params->getIntRequestParam('elemperpage', 30),
            $this->params->getIntRequestParam('pageno', 1)
        );

        $egyedek = $this->getRepo()->getWithJoins(
            $filter,
            $this->getOrderArray(),
            $this->getPager()->getOffset(),
            $this->getPager()->getElemPerPage()
        );

        echo json_encode($this->loadDataToView($egyedek, 'egyedlista', $view));
    }

    public function viewselect()
    {
        $view = $this->createView('menulista.tpl');

        $view->setVar('pagetitle', t('Menü'));
        $view->printTemplateResult();
    }

    public function viewlist()
    {
        $view = $this->createView('menulista.tpl');

        $view->setVar('pagetitle', t('Menü'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Menü'));
        $view->setVar('oper', $oper);
        $record = $this->getRepo()->findWithJoins($id);
        $view->setVar('egyed', $this->loadVars($record));
        return $view->getTemplateResult();
    }

    public function getMenu()
    {
        $menu = [];
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter
            ->addFilter('lathato', '=', true)
            ->addSql('(m.lathato=1) OR (m.lathato IS NULL)');
        $adat = $this->getRepo()->getAll($filter, ['m.sorrend' => 'ASC', 'sorrend' => 'ASC']);
        /** @var \Entities\Menu $rek */
        foreach ($adat as $rek) {
            if ($rek->isLathato(\mkw\store::getJog())) {
                $menu[] = [
                    'mcsid' => $rek->getMenucsoportId(),
                    'mcsnev' => $rek->getMenucsoportNev(),
                    'nev' => $rek->getNev(),
                    'url' => $rek->getUrl(),
                    'class' => $rek->getClass()
                ];
            }
        }
        return $menu;
    }

}