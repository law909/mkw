<?php

namespace Controllers;

use Entities\Afa;
use mkw\store;

class afaController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(Afa::class);
        $this->setKarbFormTplName('afakarbform.tpl');
        $this->setKarbTplName('afakarb.tpl');
        $this->setListBodyRowTplName('afalista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct();
    }

    public function loadVars($t, $forKarb = false)
    {
        if (!$t) {
            $t = new \Entities\Afa();
            $this->getEm()->detach($t);
        }
        $x = $this->getEntityFieldsArray($t);

        if ($forKarb) {
            $x['navcaselist'] = $this->getRepo()->getNavcaseList($t->getNavcase());
        }
        return $x;
    }

    /**
     * @param \Entities\Afa $obj
     *
     * @return mixed
     */
    protected function setFields($obj)
    {
        $this->setEntityFieldsFromRequest($obj);
        return $obj;
    }

    public function getlistbody()
    {
        $view = $this->createView('afalista_tbody.tpl');

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
        $view = $this->createView('afalista.tpl');

        $view->setVar('pagetitle', t('ÁFA kulcsok'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('ÁFA kulcs'));
        $view->setVar('formaction', \mkw\store::getRouter()->generate('adminafasave'));
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
                'selected' => ($sor->getId() == $selid),
                'afakulcs' => $sor->getErtek()
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

    public function navcaselist()
    {
        $cases = $this->getRepo()->getNavcaseList();
        $ret = '<select>';
        foreach ($cases as $case) {
            $ret .= '<option value="' . $case['id'] . '">' . $case['caption'] . '</option>';
        }
        $ret .= '</select>';
        echo $ret;
    }
}
