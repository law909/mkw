<?php

namespace Controllers;

use Entities\Szotar;

class szotarController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(Szotar::class);
        $this->setKarbFormTplName('szotarkarbform.tpl');
        $this->setKarbTplName('szotarkarb.tpl');
        $this->setListBodyRowTplName('szotarlista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct();
    }

    public function loadVars($t, $forKarb = false)
    {
        if (!$t) {
            $t = new Szotar();
            $this->getEm()->detach($t);
        }
        $x = $this->getEntityFieldsArray($t);
        return $x;
    }

    /**
     * @param \Entities\Szotar $obj
     *
     * @return \Entities\Szotar
     */
    protected function setFields($obj)
    {
        $obj = $this->setEntityFieldsFromRequest($obj);
        if ($this->params->getRequestParam('oper', '') === $this->addOperation) {
            $obj->setMit($this->params->getStringRequestParam('mit'));
        }

        return $obj;
    }

    public function getlistbody()
    {
        $view = $this->createView('szotarlista_tbody.tpl');

        $filter = new \mkwhelpers\FilterDescriptor();
        if (!is_null($this->params->getRequestParam('nevfilter', null))) {
            $filter->addFilter('mit', 'LIKE', '%' . $this->params->getStringRequestParam('nevfilter') . '%');
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
        $view = $this->createView('szotarlista.tpl');

        $view->setVar('pagetitle', t('Szótár'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Szótár bejegyzés'));
        $view->setVar('formaction', \mkw\store::getRouter()->generate('adminszotarsave'));
        $view->setVar('oper', $oper);
        $record = $this->getRepo()->find($id);
        $view->setVar('egyed', $this->loadVars($record, true));
        return $view->getTemplateResult();
    }

    public function getSelectList($selid)
    {
        $rec = $this->getRepo()->getAll([], ['mit' => 'ASC']);
        $res = [];
        foreach ($rec as $sor) {
            $res[] = [
                'id' => $sor->getId(),
                'mit' => $sor->getMit(),
                'selected' => ($sor->getId() == $selid),
                'mire' => $sor->getMire()
            ];
        }
        return $res;
    }

    public function htmllist()
    {
        $rec = $this->getRepo()->getAll([], ['mit' => 'asc']);
        $ret = '<select>';
        foreach ($rec as $sor) {
            $ret .= '<option value="' . $sor->getId() . '">' . $sor->getMire() . '</option>';
        }
        $ret .= '</select>';
        echo $ret;
    }
}
