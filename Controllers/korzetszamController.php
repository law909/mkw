<?php

namespace Controllers;

use Entities\Korzetszam;

class korzetszamController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(Korzetszam::class);
        $this->setKarbFormTplName('korzetszamkarbform.tpl');
        $this->setKarbTplName('korzetszamkarb.tpl');
        $this->setListBodyRowTplName('korzetszamlista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct();
    }

    public function loadVars($t, $forKarb = false)
    {
        if (!$t) {
            $t = new Korzetszam();
            $this->getEm()->detach($t);
        }
        $x = $this->getEntityFieldsArray($t);
        return $x;
    }

    /**
     * @param \Entities\Korzetszam $obj
     *
     * @return \Entities\Korzetszam
     */
    protected function setFields($obj)
    {
        $obj = $this->setEntityFieldsFromRequest($obj);
        if ($this->params->getRequestParam('oper', '') === $this->addOperation) {
            $obj->setId($this->params->getStringRequestParam('id'));
        }

        return $obj;
    }

    public function getlistbody()
    {
        $view = $this->createView('korzetszamlista_tbody.tpl');

        $filter = new \mkwhelpers\FilterDescriptor();
        if (!is_null($this->params->getRequestParam('nevfilter', null))) {
            $filter->addFilter('id', 'LIKE', '%' . $this->params->getStringRequestParam('nevfilter') . '%');
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
        $view = $this->createView('korzetszamlista.tpl');

        $view->setVar('pagetitle', t('Körzetszámok'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Körzetszám'));
        $view->setVar('formaction', \mkw\store::getRouter()->generate('adminkorzetszamsave'));
        $view->setVar('oper', $oper);
        $record = $this->getRepo()->find($id);
        $view->setVar('egyed', $this->loadVars($record, true));
        return $view->getTemplateResult();
    }

    public function getSelectList($selid)
    {
        $rec = $this->getRepo()->getAll([], ['sorrend' => 'ASC', 'id' => 'ASC']);
        $res = [];
        foreach ($rec as $sor) {
            $res[] = [
                'id' => $sor->getId(),
                'caption' => $sor->getId(),
                'selected' => ($sor->getId() == $selid),
                'hossz' => $sor->getHossz()
            ];
        }
        return $res;
    }

    public function htmllist()
    {
        $rec = $this->getRepo()->getAll([], ['sorrend' => 'ASC', 'id' => 'ASC']);
        $ret = '<select>';
        foreach ($rec as $sor) {
            $ret .= '<option value="' . $sor->getId() . '" data-hossz="' . $sor->getHossz() . '">' . $sor->getId() . '</option>';
        }
        $ret .= '</select>';
        echo $ret;
    }
}
