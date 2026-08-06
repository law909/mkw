<?php

namespace Controllers;

use Entities\Valutanem;

class valutanemController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(Valutanem::class);
        $this->setKarbFormTplName('valutanemkarbform.tpl');
        $this->setKarbTplName('valutanemkarb.tpl');
        $this->setListBodyRowTplName('valutanemlista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct();
    }

    public function loadVars($t, $forKarb = false)
    {
        if (!$t) {
            $t = new Valutanem();
            $this->getEm()->detach($t);
        }
        $x = $this->getEntityFieldsArray($t);
        $x['bankszamlaid'] = $t->getBankszamla() ? $t->getBankszamla()->getId() : null;
        $x['bankszamlanev'] = $t->getBankszamla() ? $t->getBankszamla()->getSzamlaszam() : '';
        if ($forKarb) {
            $bankszamlac = new bankszamlaController();
            $x['bankszamlalist'] = $bankszamlac->getSelectList($x['bankszamlaid']);
        }
        return $x;
    }

    /**
     * @param \Entities\Valutanem $obj
     *
     * @return \Entities\Valutanem
     */
    protected function setFields($obj)
    {
        $obj = $this->setEntityFieldsFromRequest($obj);

        $bankszamla = $this->getRepo(\Entities\Bankszamla::class)->find($this->params->getIntRequestParam('bankszamla', 0));
        $obj->setBankszamla($bankszamla ?: null);

        return $obj;
    }

    public function getlistbody()
    {
        $view = $this->createView('valutanemlista_tbody.tpl');

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
        $view = $this->createView('valutanemlista.tpl');

        $view->setVar('pagetitle', t('Valutanemek'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Valutanem'));
        $view->setVar('formaction', \mkw\store::getRouter()->generate('adminvalutanemsave'));
        $view->setVar('oper', $oper);
        $record = $this->getRepo()->find($id);
        $view->setVar('egyed', $this->loadVars($record, true));
        return $view->getTemplateResult();
    }

    public function getSelectList($selid = null)
    {
        $rec = $this->getRepo()->getAll([], ['nev' => 'ASC']);
        $res = [];
        foreach ($rec as $sor) {
            $res[] = [
                'id' => $sor->getId(),
                'caption' => $sor->getNev(),
                'selected' => ($sor->getId() == $selid),
                'bankszamla' => $sor->getBankszamlaId()
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

    public function getRendszerValuta()
    {
        $p = $this->getRepo()->find(store::getParameter(\mkw\consts::Valutanem));
        return $p;
    }
}
