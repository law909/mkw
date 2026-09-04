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

    /**
     * A bal oldali menü sorai. Minden sor viszi a menücsoportja nyitott/zárt állapotát
     * ('mcsnyitva'), amit a dolgozó a fejlécre kattintva állít – az érték dolgozónként
     * tárolódik (\Services\DolgozoParameterService). Alapértelmezés a nyitott állapot,
     * így akinek nincs mentett beállítása, az a régi, teljesen nyitott menüt látja.
     * Csoport nélküli menüpont mindig látszik.
     */
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
            // a médiatár menüpontja mögött mediatar = 0 mellett route sincs
            if ($rek->getClass() === 'js-mediatar' && !\mkw\store::isMediatar()) {
                continue;
            }
            if ($rek->isLathato(\mkw\store::getJog())) {
                $mcsid = $rek->getMenucsoportId();
                $menu[] = [
                    'mcsid' => $mcsid,
                    'mcsnev' => $rek->getMenucsoportNev(),
                    'mcsnyitva' => $this->isMenucsoportNyitva($mcsid),
                    'nev' => $this->menuNev($rek),
                    'url' => $rek->getUrl(),
                    'class' => $rek->getClass()
                ];
            }
        }
        return $menu;
    }

    /**
     * A két termékmenü-fa sora a beállított nevet viszi. A sorokat url, illetve class alapján
     * azonosítjuk, nem a feliratuk szövege alapján: a "Termék menü 2" tartalmazza a
     * "Termék menü"-t, szövegcserénél a sorrend elrontaná.
     *
     * A "rendezése" sor szándékosan az ELSŐ fa nevét viszi: az
     * adminController::regeneratemenukarkod() ma csak a TermekMenu-t rendezi.
     */
    private function menuNev(\Entities\Menu $rek)
    {
        switch (true) {
            case $rek->getUrl() === '/admin/termekmenu/viewlist':
                return \mkw\store::getTermekmenuName();
            case $rek->getUrl() === '/admin/termekmenu2/viewlist':
                return \mkw\store::getTermekmenu2Name();
            case $rek->getClass() === 'js-regeneratemenukarkod':
                return \mkw\store::getTermekmenuName() . ' rendezése';
            case $rek->getClass() === 'js-regeneratemenu2karkod':
                return \mkw\store::getTermekmenu2Name() . ' rendezése';
            default:
                return $rek->getNev();
        }
    }

    private function isMenucsoportNyitva($mcsid)
    {
        if (!$mcsid) {
            return true;
        }
        return \Services\DolgozoParameterService::getBoolParameter(
            \Services\DolgozoParameterService::getMenucsoportKey($mcsid),
            $mcsid !== 7
        );
    }

}