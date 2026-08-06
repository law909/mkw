<?php

namespace Controllers;

use Entities\Rendezvenyallapot;

class rendezvenyallapotController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(Rendezvenyallapot::class);
        $this->setKarbFormTplName('rendezvenyallapotkarbform.tpl');
        $this->setKarbTplName('rendezvenyallapotkarb.tpl');
        $this->setListBodyRowTplName('rendezvenyallapotlista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct();
    }

    public function loadVars($t, $forKarb = false)
    {
        if (!$t) {
            $t = new Rendezvenyallapot();
            $this->getEm()->detach($t);
        }
        $x = $this->getEntityFieldsArray($t);
        return $x;
    }

    /**
     * @param \Entities\Rendezvenyallapot $obj
     *
     * @return \Entities\Rendezvenyallapot
     */
    protected function setFields($obj)
    {
        $obj = $this->setEntityFieldsFromRequest($obj);
        $obj->setOrarendbenszerepel($this->params->getBoolRequestParam('orarendbenszerepel', false));

        return $obj;
    }

    public function getlistbody()
    {
        $view = $this->createView('rendezvenyallapotlista_tbody.tpl');

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
        $view = $this->createView('rendezvenyallapotlista.tpl');

        $view->setVar('pagetitle', t('Rendezvény állapotok'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Rendezvény állapot'));
        $view->setVar('formaction', \mkw\store::getRouter()->generate('adminrendezvenyallapotsave'));
        $view->setVar('oper', $oper);
        $record = $this->getRepo()->find($id);
        $view->setVar('egyed', $this->loadVars($record, true));
        return $view->getTemplateResult();
    }

    public function getSelectList($selid = null)
    {
        $rec = $this->getRepo()->getAll([], ['sorrend' => 'ASC']);
        $res = [];
        foreach ($rec as $sor) {
            $res[] = ['id' => $sor->getId(), 'caption' => $sor->getNev(), 'selected' => ($sor->getId() == $selid)];
        }
        return $res;
    }

    public function htmllist()
    {
        $rec = $this->getRepo()->getAll([], ['sorrend' => 'asc']);
        $ret = '<select>';
        foreach ($rec as $sor) {
            $ret .= '<option value="' . $sor->getId() . '">' . $sor->getNev() . '</option>';
        }
        $ret .= '</select>';
        echo $ret;
    }
}
