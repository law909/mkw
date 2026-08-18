<?php

namespace Controllers;

use Entities\Idoponttema;

class idoponttemaController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(Idoponttema::class);
        $this->setKarbFormTplName('idoponttemakarbform.tpl');
        $this->setKarbTplName('idoponttemakarb.tpl');
        $this->setListBodyRowTplName('idoponttemalista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct();
    }

    protected function loadVars($t, $forKarb = false)
    {
        if (!$t) {
            $t = new Idoponttema();
            $this->getEm()->detach($t);
        }
        return $this->getEntityFieldsArray($t);
    }

    /**
     * @param \Entities\Idoponttema $obj
     *
     * @return \Entities\Idoponttema
     */
    protected function setFields($obj)
    {
        return $this->setEntityFieldsFromRequest($obj);
    }

    public function getlistbody()
    {
        $view = $this->createView('idoponttemalista_tbody.tpl');

        $filter = new \mkwhelpers\FilterDescriptor();
        if (!is_null($this->params->getRequestParam('nevfilter', null))) {
            $filter->addFilter('nev', 'LIKE', '%' . $this->params->getStringRequestParam('nevfilter') . '%');
        }
        $f = $this->params->getNumRequestParam('inaktivfilter', 9);
        if ($f != 9) {
            $filter->addFilter('inaktiv', '=', $f);
        }

        $this->initPager($this->getRepo()->getCount($filter));

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
        $view = $this->createView('idoponttemalista.tpl');
        $view->setVar('pagetitle', t('Időpont témák'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname)
    {
        $view = $this->createView($tplname);
        $view->setVar('pagetitle', t('Időpont téma'));
        $view->setVar('oper', $this->params->getRequestParam('oper', ''));
        $view->setVar('egyed', $this->loadVars($this->getRepo()->find($this->params->getRequestParam('id', 0)), true));
        return $view->getTemplateResult();
    }

    public function getSelectList($selid = null, $csakaktiv = true)
    {
        $filter = new \mkwhelpers\FilterDescriptor();
        if ($csakaktiv) {
            $filter->addFilter('inaktiv', '=', false);
        }
        $rec = $this->getRepo()->getAll($filter, ['nev' => 'ASC']);
        $res = [];
        /** @var \Entities\Idoponttema $sor */
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
        $rec = $this->getRepo()->getAll([], ['nev' => 'ASC']);
        $ret = '<select><option value="0">Válasszon</option>';
        /** @var \Entities\Idoponttema $sor */
        foreach ($rec as $sor) {
            $ret .= '<option value="' . $sor->getId() . '">' . $sor->getNev() . '</option>';
        }
        $ret .= '</select>';
        echo $ret;
    }

}
