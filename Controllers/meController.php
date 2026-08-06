<?php

namespace Controllers;

use Entities\ME;

class meController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(ME::class);
        $this->setKarbFormTplName('mekarbform.tpl');
        $this->setKarbTplName('mekarb.tpl');
        $this->setListBodyRowTplName('melista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct();
    }

    public function loadVars($t, $forKarb = false)
    {
        if (!$t) {
            $t = new ME();
            $this->getEm()->detach($t);
        }
        $x = $this->getEntityFieldsArray($t);
        if ($forKarb) {
            $x['navtipuslist'] = $this->getNavtipusList($t->getNavtipus());
        }
        return $x;
    }

    /**
     * @param \Entities\ME $obj
     *
     * @return \Entities\ME
     */
    protected function setFields($obj)
    {
        $obj = $this->setEntityFieldsFromRequest($obj);

        return $obj;
    }

    public function getlistbody()
    {
        $view = $this->createView('melista_tbody.tpl');

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
        $view = $this->createView('melista.tpl');

        $view->setVar('pagetitle', t('Mennyiségi egységek'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Mennyiségi egység'));
        $view->setVar('formaction', \mkw\store::getRouter()->generate('adminmesave'));
        $view->setVar('oper', $oper);
        $record = $this->getRepo()->find($id);
        $view->setVar('egyed', $this->loadVars($record, true));
        return $view->getTemplateResult();
    }

    private const NAVTIPUSOK = [
        'PIECE', 'KILOGRAM', 'TON', 'KWH', 'DAY', 'HOUR', 'MINUTE', 'MONTH', 'LITER',
        'KILOMETER', 'CUBIC_METER', 'METER', 'LINEAR_METER', 'CARTON', 'PACK', 'OWN'
    ];

    public function getNavtipusList($sel = null)
    {
        $res = [];
        foreach (self::NAVTIPUSOK as $e) {
            $res[] = ['id' => $e, 'caption' => $e, 'selected' => ($e === $sel)];
        }
        return $res;
    }

    public function navtipuslist()
    {
        $ret = '<select>';
        foreach (self::NAVTIPUSOK as $e) {
            $ret .= '<option value="' . $e . '">' . $e . '</option>';
        }
        $ret .= '</select>';
        echo $ret;
    }

    public function getSelectList($selid)
    {
        $rec = $this->getRepo()->getAll([], ['nev' => 'ASC']);
        $res = [];
        foreach ($rec as $sor) {
            $res[] = ['id' => $sor->getId(), 'caption' => $sor->getNev(), 'selected' => ($sor->getId() == $selid)];
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
