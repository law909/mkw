<?php

namespace Controllers;

use Entities\Unnepnap;

class unnepnapController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(Unnepnap::class);
        $this->setKarbFormTplName('unnepnapkarbform.tpl');
        $this->setKarbTplName('unnepnapkarb.tpl');
        $this->setListBodyRowTplName('unnepnaplista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct();
    }

    public function loadVars($t, $forKarb = false)
    {
        if (!$t) {
            $t = new Unnepnap();
            $this->getEm()->detach($t);
        }
        $x = $this->getEntityFieldsArray($t);
        $x['datumstr'] = $t->getDatum() ? $t->getDatum()->format('Y.m.d') : '';
        return $x;
    }

    /**
     * @param \Entities\Unnepnap $obj
     *
     * @return \Entities\Unnepnap
     */
    protected function setFields($obj)
    {
        $obj = $this->setEntityFieldsFromRequest($obj);
        $obj->setDatum(new \DateTime(str_replace('.', '-', $this->params->getStringRequestParam('datum'))));

        return $obj;
    }

    public function getlistbody()
    {
        $view = $this->createView('unnepnaplista_tbody.tpl');

        $filter = new \mkwhelpers\FilterDescriptor();

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
        $view = $this->createView('unnepnaplista.tpl');

        $view->setVar('pagetitle', t('Ünnepnapok'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Ünnepnap'));
        $view->setVar('formaction', \mkw\store::getRouter()->generate('adminunnepnapsave'));
        $view->setVar('oper', $oper);
        $record = $this->getRepo()->find($id);
        $view->setVar('egyed', $this->loadVars($record, true));
        return $view->getTemplateResult();
    }

    public function getSelectList($selid)
    {
        $rec = $this->getRepo()->getAll([], ['datum' => 'ASC']);
        $res = [];
        /** @var \Entities\Unnepnap $sor */
        foreach ($rec as $sor) {
            $res[] = ['id' => $sor->getId(), 'caption' => $sor->getDatumString(), 'selected' => ($sor->getId() == $selid)];
        }
        return $res;
    }

    public function htmllist()
    {
        $rec = $this->getRepo()->getAll([], ['datum' => 'asc']);
        $ret = '<select>';
        /** @var \Entities\Unnepnap $sor */
        foreach ($rec as $sor) {
            $ret .= '<option value="' . $sor->getId() . '">' . $sor->getDatumString() . '</option>';
        }
        $ret .= '</select>';
        echo $ret;
    }
}
