<?php

namespace Controllers;

use Entities\Partnertipus;

class partnertipusController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(Partnertipus::class);
        $this->setKarbFormTplName('partnertipuskarbform.tpl');
        $this->setKarbTplName('partnertipuskarb.tpl');
        $this->setListBodyRowTplName('partnertipuslista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct();
    }

    public function loadVars($t, $forKarb = false)
    {
        if (!$t) {
            $t = new Partnertipus();
            $this->getEm()->detach($t);
        }
        $x = $this->getEntityFieldsArray($t);
        return $x;
    }

    /**
     * @param \Entities\Partnertipus $obj
     *
     * @return \Entities\Partnertipus
     */
    protected function setFields($obj)
    {
        $obj = $this->setEntityFieldsFromRequest($obj);
        $obj->setBelephet($this->params->getBoolRequestParam('belephet', false));
        $obj->setBelephet2($this->params->getBoolRequestParam('belephet2', false));
        $obj->setBelephet3($this->params->getBoolRequestParam('belephet3', false));
        $obj->setBelephet4($this->params->getBoolRequestParam('belephet4', false));
        $obj->setBelephet5($this->params->getBoolRequestParam('belephet5', false));
        $obj->setBelephet6($this->params->getBoolRequestParam('belephet6', false));
        $obj->setBelephet7($this->params->getBoolRequestParam('belephet7', false));
        $obj->setBelephet8($this->params->getBoolRequestParam('belephet8', false));
        $obj->setBelephet9($this->params->getBoolRequestParam('belephet9', false));
        $obj->setBelephet10($this->params->getBoolRequestParam('belephet10', false));
        $obj->setBelephet11($this->params->getBoolRequestParam('belephet11', false));
        $obj->setBelephet12($this->params->getBoolRequestParam('belephet12', false));
        $obj->setBelephet13($this->params->getBoolRequestParam('belephet13', false));
        $obj->setBelephet14($this->params->getBoolRequestParam('belephet14', false));
        $obj->setBelephet15($this->params->getBoolRequestParam('belephet15', false));

        return $obj;
    }

    public function getlistbody()
    {
        $view = $this->createView('partnertipuslista_tbody.tpl');

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
        $view = $this->createView('partnertipuslista.tpl');

        $view->setVar('pagetitle', t('Partner típusok'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Partner típus'));
        $view->setVar('formaction', \mkw\store::getRouter()->generate('adminpartnertipussave'));
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
            $res[] = [
                'id' => $sor->getId(),
                'caption' => $sor->getNev(),
                'selected' => ($sor->getId() == $selid)
            ];
        }
        return $res;
    }

    public function htmllist()
    {
        $rec = $this->getRepo()->getAll([], ['nev' => 'asc']);
        $ret = '<select>';
        foreach ($rec as $sor) {
            $ret .= '<option value="' . $sor->getId() . '">' . $sor->getNev() . '</option>';
        }
        $ret .= '</select>';
        echo $ret;
    }
}
