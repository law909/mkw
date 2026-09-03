<?php

namespace Controllers;

use Entities\Bizonylattipus;

class bizonylattipusController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(Bizonylattipus::class);
        $this->setKarbFormTplName('bizonylattipuskarbform.tpl');
        $this->setKarbTplName('bizonylattipuskarb.tpl');
        $this->setListBodyRowTplName('bizonylattipuslista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct();
    }

    public function getSelectList($selid = null)
    {
        $rec = $this->getRepo()->getAll([], ['nev' => 'ASC']);
        $res = [];
        foreach ($rec as $sor) {
            $res[] = [
                'id' => $sor->getId(),
                'caption' => $sor->getNev(),
                'azonosito' => $sor->getAzonosito(),
                'selected' => ($sor->getId() == $selid)
            ];
        }
        return $res;
    }

    /**
     * A bizonylattípus a rendszer viselkedését szabályozza (készletmozgás, pénzmozgás, NAV
     * beküldés), ezért csak a beépített sysadmin nyúlhat hozzá. A jog szintje nem elég: azt
     * egy munkakörre is rá lehet állítani.
     */
    private function sysadminOnly()
    {
        if (\mkw\store::isSysadmin()) {
            return true;
        }
        header('HTTP/1.1 403 Forbidden');
        return false;
    }

    public function loadVars($t, $forKarb = false)
    {
        if (!$t) {
            $t = new Bizonylattipus();
            $this->getEm()->detach($t);
        }
        return $this->getEntityFieldsArray($t);
    }

    /**
     * @param Bizonylattipus $obj
     *
     * @return Bizonylattipus
     */
    protected function setFields($obj)
    {
        // az azonosító kézzel adott szöveg, és csak új típusnál vehető fel: a meglévőre
        // bizonylatok százai hivatkoznak, a kódban is fix azonosítók szerepelnek
        if (!$obj->getId()) {
            $obj->setId($this->params->getStringRequestParam('id'));
        }
        $this->setEntityFieldsFromRequest($obj);
        return $obj;
    }

    protected function validate($obj, $parancs)
    {
        $hibak = [];
        if (!$obj->getId()) {
            $hibak['id'] = t('Az azonosító megadása kötelező.');
        }
        if (!$obj->getNev()) {
            $hibak['nev'] = t('A név megadása kötelező.');
        }
        if ($parancs === $this->addOperation && $obj->getId() && $this->getRepo()->find($obj->getId())) {
            $hibak['id'] = t('Ilyen azonosítójú bizonylattípus már van.');
        }
        return $hibak;
    }

    protected function beforeRemove($o)
    {
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('bizonylattipus', '=', $o->getId());
        if ($this->getRepo(\Entities\Bizonylatfej::class)->getCount($filter)) {
            throw new \mkwhelpers\Exceptions\UserMessageException(
                t('A bizonylattípushoz tartoznak bizonylatok, ezért nem törölhető.')
            );
        }
    }

    public function getlistbody()
    {
        if (!$this->sysadminOnly()) {
            return;
        }
        $view = $this->createView('bizonylattipuslista_tbody.tpl');

        $filter = new \mkwhelpers\FilterDescriptor();
        if (!is_null($this->params->getRequestParam('nevfilter', null))) {
            $f = $this->params->getStringRequestParam('nevfilter');
            $filter->addFilter('nev', 'LIKE', '%' . $f . '%');
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
        $view = $this->createView('bizonylattipuslista.tpl');

        $view->setVar('pagetitle', t('Bizonylattípusok'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
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

        $view->setVar('pagetitle', t('Bizonylattípus'));
        $view->setVar('formaction', \mkw\store::getRouter()->generate('adminbizonylattipussave'));
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
}
