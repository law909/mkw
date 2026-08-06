<?php

namespace Controllers;

use Entities\Vtsz;

class vtszController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(Vtsz::class);
        $this->setKarbFormTplName('vtszkarbform.tpl');
        $this->setKarbTplName('vtszkarb.tpl');
        $this->setListBodyRowTplName('vtszlista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct();
    }

    public function loadVars($t, $forKarb = false)
    {
        if (!$t) {
            $t = new Vtsz();
            $this->getEm()->detach($t);
        }
        $x = $this->getEntityFieldsArray($t);
        $x['afaid'] = $t->getAfa() ? $t->getAfa()->getId() : null;
        $x['afanev'] = $t->getAfa() ? $t->getAfa()->getNev() : '';
        $x['cskid'] = $t->getCsk() ? $t->getCsk()->getId() : null;
        $x['csknev'] = $t->getCsk() ? $t->getCsk()->getNev() : '';
        $x['ktid'] = $t->getKt() ? $t->getKt()->getId() : null;
        $x['ktnev'] = $t->getKt() ? $t->getKt()->getNev() : '';
        if ($forKarb) {
            $afac = new afaController();
            $x['afalist'] = $afac->getSelectList($x['afaid']);
            $cskc = new cskController();
            $x['csklist'] = $cskc->getSelectList($x['cskid']);
            $ktc = new cskController();
            $x['ktlist'] = $ktc->getSelectList($x['ktid']);
        }
        return $x;
    }

    /**
     * @param \Entities\Vtsz $obj
     *
     * @return \Entities\Vtsz
     */
    protected function setFields($obj)
    {
        $obj = $this->setEntityFieldsFromRequest($obj);

        $afa = $this->getRepo(\Entities\Afa::class)->find($this->params->getIntRequestParam('afa', 0));
        $obj->setAfa($afa ?: null);

        $csk = $this->getRepo(\Entities\Csk::class)->find($this->params->getIntRequestParam('csk', 0));
        $obj->setCsk($csk ?: null);

        $kt = $this->getRepo(\Entities\Csk::class)->find($this->params->getIntRequestParam('kt', 0));
        $obj->setKt($kt ?: null);

        return $obj;
    }

    public function getlistbody()
    {
        $view = $this->createView('vtszlista_tbody.tpl');

        $filter = new \mkwhelpers\FilterDescriptor();
        if (!is_null($this->params->getRequestParam('nevfilter', null))) {
            $filter->addFilter('szam', 'LIKE', '%' . $this->params->getStringRequestParam('nevfilter') . '%');
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
        $view = $this->createView('vtszlista.tpl');

        $view->setVar('pagetitle', t('VTSZ'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('VTSZ'));
        $view->setVar('formaction', \mkw\store::getRouter()->generate('adminvtszsave'));
        $view->setVar('oper', $oper);
        $record = $this->getRepo()->find($id);
        $view->setVar('egyed', $this->loadVars($record, true));
        return $view->getTemplateResult();
    }

    public function getSelectList($selid)
    {
        // TODO ezen gyorsitani kell, getAll helyett ScalarResult
        $rec = $this->getRepo()->getAll([], ['szam' => 'ASC', 'nev' => 'ASC']);
        $res = [];
        foreach ($rec as $sor) {
            $res[] = [
                'id' => $sor->getId(),
                'caption' => $sor->getSzam() . ' ' . $sor->getNev(),
                'selected' => ($sor->getId() == $selid),
                'afa' => $sor->getAfaId(),
                'csk' => $sor->getCskId(),
                'kt' => $sor->getKtId()
            ];
        }
        return $res;
    }
}
