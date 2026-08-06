<?php

namespace Controllers;

use Entities\Penztar;

class penztarController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(Penztar::class);
        $this->setKarbFormTplName('penztarkarbform.tpl');
        $this->setKarbTplName('penztarkarb.tpl');
        $this->setListBodyRowTplName('penztarlista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct();
    }

    public function loadVars($t, $forKarb = false)
    {
        if (!$t) {
            $t = new Penztar();
            $this->getEm()->detach($t);
        }
        $x = $this->getEntityFieldsArray($t);
        $x['valutanemid'] = $t->getValutanem() ? $t->getValutanem()->getId() : null;
        $x['valutanemnev'] = $t->getValutanem() ? $t->getValutanem()->getNev() : '';
        if ($forKarb) {
            $valutanemc = new valutanemController();
            $x['valutanemlist'] = $valutanemc->getSelectList($x['valutanemid']);
        }
        return $x;
    }

    /**
     * @param \Entities\Penztar $obj
     *
     * @return \Entities\Penztar
     */
    protected function setFields($obj)
    {
        $obj = $this->setEntityFieldsFromRequest($obj);

        $valutanem = $this->getRepo(\Entities\Valutanem::class)->find($this->params->getIntRequestParam('valutanem', 0));
        $obj->setValutanem($valutanem ?: null);

        return $obj;
    }

    public function getlistbody()
    {
        $view = $this->createView('penztarlista_tbody.tpl');

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
        $view = $this->createView('penztarlista.tpl');

        $view->setVar('pagetitle', t('Pénztárak'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Pénztár'));
        $view->setVar('formaction', \mkw\store::getRouter()->generate('adminpenztarsave'));
        $view->setVar('oper', $oper);
        $record = $this->getRepo()->find($id);
        $view->setVar('egyed', $this->loadVars($record, true));
        return $view->getTemplateResult();
    }

    public function getSelectList($selid = null)
    {
        $rec = $this->getRepo()->getAll([], ['nev' => 'ASC']);
        $res = [];
        /** @var \Entities\Penztar $sor */
        foreach ($rec as $sor) {
            $res[] = ['id' => $sor->getId(), 'caption' => $sor->getNev(), 'selected' => ($sor->getId() == $selid), 'valutanem' => $sor->getValutanemId()];
        }
        return $res;
    }

    public function htmllist()
    {
        $rec = $this->getRepo()->getAll([], ['nev' => 'ASC']);
        $ret = '<select>';
        foreach ($rec as $sor) {
            $ret .= '<option value="' . $sor->getId() . '">' . $sor->getNev() . '</option>';
        }
        $ret .= '</select>';
        echo $ret;
    }
}
