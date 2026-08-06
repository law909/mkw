<?php

namespace Controllers;

use Entities\Irszam;

class irszamController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(Irszam::class);
        $this->setKarbFormTplName('irszamkarbform.tpl');
        $this->setKarbTplName('irszamkarb.tpl');
        $this->setListBodyRowTplName('irszamlista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct();
    }

    public function loadVars($t, $forKarb = false)
    {
        if (!$t) {
            $t = new Irszam();
            $this->getEm()->detach($t);
        }
        $x = $this->getEntityFieldsArray($t);
        return $x;
    }

    /**
     * @param \Entities\Irszam $obj
     *
     * @return \Entities\Irszam
     */
    protected function setFields($obj)
    {
        $obj = $this->setEntityFieldsFromRequest($obj);

        return $obj;
    }

    public function getlistbody()
    {
        $view = $this->createView('irszamlista_tbody.tpl');

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
        $view = $this->createView('irszamlista.tpl');

        $view->setVar('pagetitle', t('Irányítószámok'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Irányítószám'));
        $view->setVar('formaction', \mkw\store::getRouter()->generate('adminirszamsave'));
        $view->setVar('oper', $oper);
        $record = $this->getRepo()->find($id);
        $view->setVar('egyed', $this->loadVars($record, true));
        return $view->getTemplateResult();
    }

    public function getSelectList($selid) {
        $rec=$this->getRepo()->getAll(array(),array('nev'=>'ASC'));
        $res=array();
        foreach($rec as $sor) {
            $res[]=array('id'=>$sor->getId(),'caption'=>$sor->getNev(),'selected'=>($sor->getId()==$selid));
        }
        return $res;
    }

    public function htmllist()
    {
        $rec = $this->getRepo()->getAll([], ['nev' => 'asc']);
        $ret = '<select>';
        foreach ($rec as $sor) {
            $ret .= '<option value="' . $sor->getId() . '">' . $sor->getSzam() . ' ' . $sor->getNev() . '</option>';
        }
        $ret .= '</select>';
        echo $ret;
    }

    public function typeaheadList()
    {
        $filter = new \mkwhelpers\FilterDescriptor();
        $ret = [];
        $term = $this->params->getStringRequestParam('term');
        $tip = $this->params->getIntRequestParam('tip');
        if ($term) {
            $filter->addFilter('szam', 'LIKE', trim($term) . '%');
        }
        $rec = $this->getRepo()->getAll($filter, ['szam' => 'asc']);
        if ($tip) {
            foreach ($rec as $sor) {
                $ret[] = [
                    'label' => $sor->getSzam() . ' ' . $sor->getNev(),
                    'value' => $sor->getSzam(),
                    'nev' => $sor->getNev()
                ];
            }
        } else {
            foreach ($rec as $sor) {
                $ret[] = [
                    'szam' => $sor->getSzam(),
                    'nev' => $sor->getNev(),
                    'id' => $sor->getSzam() . ' ' . $sor->getNev()
                ];
            }
        }
        echo json_encode($ret);
    }

    public function varosTypeaheadList()
    {
        $filter = [];
        $ret = [];
        $term = $this->params->getStringRequestParam('term');
        $tip = $this->params->getIntRequestParam('tip');
        if ($term) {
            $filter['fields'][] = 'nev';
//			$filter['clauses'][]='LIKE';
            $filter['values'][] = trim($term);
        }
        $rec = $this->getRepo()->getAll($filter, ['nev' => 'asc']);
        if ($tip) {
            foreach ($rec as $sor) {
                $ret[] = [
                    'label' => $sor->getSzam() . ' ' . $sor->getNev(),
                    'value' => $sor->getNev(),
                    'szam' => $sor->getSzam()
                ];
            }
        } else {
            foreach ($rec as $sor) {
                $ret[] = [
                    'szam' => $sor->getSzam(),
                    'nev' => $sor->getNev(),
                    'id' => $sor->getSzam() . ' ' . $sor->getNev()
                ];
            }
        }
        echo json_encode($ret);
    }
}
