<?php

namespace Controllers;

use Entities\Menu;
use Entities\Menucsoport;
use Entities\Munkakor;

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

    public function loadVars($t, $forKarb = false)
    {
        if (!$t) {
            $t = new Menu();
            $this->getEm()->detach($t);
        }
        $x = $this->getEntityFieldsArray($t);
        $x['menucsoportnev'] = $t->getMenucsoportNev();
        $x['munkakornevek'] = $t->getMunkakorNevek();
        if ($forKarb) {
            $x['menucsoportlist'] = (new menucsoportController())->getSelectList($t->getMenucsoportId());
            $x['munkakorids'] = $t->getMunkakorIds();
            $x['munkakorlist'] = (new munkakorController())->getSelectList();
        }
        return $x;
    }

    /**
     * @param \Entities\Menu $obj
     *
     * @return \Entities\Menu
     */
    protected function setFields($obj)
    {
        $obj = $this->setEntityFieldsFromRequest($obj, ['raw' => ['url']]);
        $obj->setMenucsoport($this->getRepo(Menucsoport::class)->find($this->params->getIntRequestParam('menucsoport')));
        $obj->removeAllMunkakor();
        foreach ($this->params->getArrayRequestParam('munkakorok', []) as $munkakorId) {
            $munkakor = $this->getRepo(Munkakor::class)->find($munkakorId);
            if ($munkakor) {
                $obj->addMunkakor($munkakor);
            }
        }
        return $obj;
    }

    public function getlistbody()
    {
        if (!$this->sysadminOnly()) {
            return;
        }
        $view = $this->createView('menulista_tbody.tpl');

        $filter = new \mkwhelpers\FilterDescriptor();
        if (!is_null($this->params->getRequestParam('nevfilter', null))) {
            $filter->addFilter(['nev', 'url'], 'LIKE', '%' . $this->params->getStringRequestParam('nevfilter') . '%');
        }
        $menucsoport = $this->params->getIntRequestParam('menucsoportfilter');
        if ($menucsoport) {
            $filter->addFilter('menucsoport', '=', $menucsoport);
        }

        $this->initPager(
            $this->getRepo()->getCount($filter),
            $this->params->getIntRequestParam('elemperpage', 30),
            $this->params->getIntRequestParam('pageno', 1)
        );

        $egyedek = $this->getRepo()->getAll(
            $filter,
            $this->getOrderArray(),
            $this->getPager()->getOffset(),
            $this->getPager()->getElemPerPage()
        );

        echo json_encode($this->loadDataToView($egyedek, 'egyedlista', $view));
    }

    public function viewlist()
    {
        if (!$this->sysadminOnly()) {
            return;
        }
        $view = $this->createView('menulista.tpl');

        $view->setVar('pagetitle', t('Menüpontok'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->setVar('menucsoportlist', (new menucsoportController())->getSelectList());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname)
    {
        if (!$this->sysadminOnly()) {
            return '';
        }
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Menüpont'));
        $view->setVar('formaction', \mkw\store::getRouter()->generate('adminmenusave'));
        $view->setVar('oper', $oper);
        $view->setVar('egyed', $this->loadVars($this->getRepo()->find($id), true));
        return $view->getTemplateResult();
    }

    public function save()
    {
        if (!$this->sysadminOnly()) {
            return;
        }
        parent::save();
    }

    /**
     * A bal oldali menü sorai. Minden sor viszi a menücsoportja nyitott/zárt állapotát
     * ('mcsnyitva'), amit a dolgozó a fejlécre kattintva állít – az érték dolgozónként
     * tárolódik (\Services\DolgozoParameterService). Alapértelmezés a nyitott állapot,
     * így akinek nincs mentett beállítása, az a régi, teljesen nyitott menüt látja.
     * A menüpontot az látja, akinek a munkakörét bepipálták rajta (Menu::isLathato()).
     */
    public function getMenu()
    {
        $menu = [];
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter
            ->addFilter('lathato', '=', true)
            ->addSql('(m.lathato=1) OR (m.lathato IS NULL)');
        $adat = $this->getRepo()->getWithJoins($filter, ['m.sorrend' => 'ASC', 'sorrend' => 'ASC']);
        $munkakorId = \mkw\store::getAdminMunkakorId();
        $sysadmin = \mkw\store::isSysadmin();
        /** @var \Entities\Menu $rek */
        foreach ($adat as $rek) {
            // a médiatár menüpontja mögött mediatar = 0 mellett route sincs
            if ($rek->getClass() === 'js-mediatar' && !\mkw\store::isMediatar()) {
                continue;
            }
            if ($rek->getUrl() === '/admin/eppjelszo/viewlist' && !\mkw\store::isEpp()) {
                continue;
            }
            if ($rek->isLathato($munkakorId, $sysadmin)) {
                $mcsid = $rek->getMenucsoportId();
                $menu[] = [
                    'mcsid' => $mcsid,
                    'mcsnev' => $rek->getMenucsoportNev(),
                    'mcsnyitva' => $this->isMenucsoportNyitva($mcsid),
                    'nev' => $rek->getNev(),
                    'url' => $rek->getUrl(),
                    'class' => $rek->getClass()
                ];
            }
        }
        return $menu;
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