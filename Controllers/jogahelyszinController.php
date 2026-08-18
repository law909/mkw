<?php

namespace Controllers;

use Entities\Jogahelyszin;

class jogahelyszinController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(Jogahelyszin::class);
        $this->setKarbFormTplName('jogahelyszinkarbform.tpl');
        $this->setKarbTplName('jogahelyszinkarb.tpl');
        $this->setListBodyRowTplName('jogahelyszinlista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct();
    }

    protected function loadVars($t, $forKarb = false)
    {
        if (!$t) {
            $t = new Jogahelyszin();
            $this->getEm()->detach($t);
        }
        $x = $this->getEntityFieldsArray($t);
        $x['cim'] = $t->getFullAddress();
        return $x;
    }

    /**
     * @param \Entities\Jogahelyszin $obj
     *
     * @return \Entities\Jogahelyszin
     */
    protected function setFields($obj)
    {
        return $this->setEntityFieldsFromRequest($obj, ['raw' => ['emailsablon']]);
    }

    public function getlistbody()
    {
        $view = $this->createView('jogahelyszinlista_tbody.tpl');

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
        $view = $this->createView('jogahelyszinlista.tpl');
        $view->setVar('pagetitle', t('Helyszínek'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname)
    {
        $view = $this->createView($tplname);
        $view->setVar('pagetitle', t('Helyszín'));
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
        /** @var \Entities\Jogahelyszin $sor */
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
        /** @var \Entities\Jogahelyszin $sor */
        foreach ($rec as $sor) {
            $ret .= '<option value="' . $sor->getId() . '">' . $sor->getNev() . '</option>';
        }
        $ret .= '</select>';
        echo $ret;
    }

}
