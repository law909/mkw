<?php

namespace Controllers;

use Entities\Partnercimkekat;

class partnercimkekatController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(Partnercimkekat::class);
        $this->setKarbFormTplName('partnercimkekatkarbform.tpl');
        $this->setKarbTplName('partnercimkekatkarb.tpl');
        $this->setListBodyRowTplName('partnercimkekatlista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct();
    }

    public function loadVars($t, $forKarb = false)
    {
        if (!$t) {
            $t = new Partnercimkekat();
            $this->getEm()->detach($t);
        }
        $x = $this->getEntityFieldsArray($t);
        return $x;
    }

    /**
     * @param \Entities\Partnercimkekat $obj
     *
     * @return \Entities\Partnercimkekat
     */
    protected function setFields($obj)
    {
        $obj = $this->setEntityFieldsFromRequest($obj);
        $obj->setLathato($this->params->getBoolRequestParam('lathato', false));

        return $obj;
    }

    public function getlistbody()
    {
        $view = $this->createView('partnercimkekatlista_tbody.tpl');

        $filter = new \mkwhelpers\FilterDescriptor();
        if (!is_null($this->params->getRequestParam('nevfilter', null))) {
            $filter->addFilter('nev', 'LIKE', '%' . $this->params->getStringRequestParam('nevfilter') . '%');
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
        $view = $this->createView('partnercimkekatlista.tpl');

        $view->setVar('pagetitle', t('Partnercímke csoportok'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Partnercímke csoport'));
        $view->setVar('formaction', \mkw\store::getRouter()->generate('adminpartnercimkekatsave'));
        $view->setVar('oper', $oper);
        $record = $this->getRepo()->find($id);
        $view->setVar('egyed', $this->loadVars($record, true));
        return $view->getTemplateResult();
    }

    public function getSelectList($selid)
    {
        $rec = $this->getRepo()->getAll([], ['nev' => 'ASC']);
        $res = [];
        foreach ($rec as $sor) {
            $res[] = ['id' => $sor->getId(), 'caption' => $sor->getNev(), 'selected' => ($sor->getId() == $selid)];
        }
        return $res;
    }

    public function getWithCimkek($selected = null)
    {
        $cimkekat = $this->getRepo()->getWithJoins([], ['_xx.nev' => 'asc', 'c.nev' => 'asc']);
        $res = [];
        foreach ($cimkekat as $kat) {
            $adat = [];
            $cimkek = $kat->getCimkek();
            foreach ($cimkek as $cimke) {
                $adat[] = [
                    'id' => $cimke->getId(),
                    'caption' => $cimke->getNev(),
                    'selected' => ($selected && ($selected->contains($cimke)) ? true : false)
                ];
            }
            $res[] = [
                'id' => $kat->getId(),
                'caption' => $kat->getNev(),
                'sanitizedcaption' => str_replace('.', '', $kat->getSlug()),
                'cimkek' => $adat
            ];
        }
        return $res;
    }
}
