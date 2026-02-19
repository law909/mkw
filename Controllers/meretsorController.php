<?php

namespace Controllers;

use Entities\Meret;
use Entities\Meretsor;
use mkwhelpers\FilterDescriptor;
use mkwhelpers\MattableController;

class meretsorController extends MattableController
{
    public function __construct($params)
    {
        $this->setEntityName(Meretsor::class);
        $this->setKarbFormTplName('meretsorkarbform.tpl');
        $this->setKarbTplName('meretsorkarb.tpl');
        $this->setListBodyRowTplName('meretsorlista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_meretsor');
        parent::__construct($params);
    }

    public function loadVars($t, $forKarb = false)
    {
        $x = [];
        if (!$t) {
            $t = new Meretsor();
            $this->getEm()->detach($t);
        }
        $x['id'] = $t->getId();
        $x['nev'] = $t->getNev();
        $x['meretids'] = $t->getMeretIds();
        $meretnevek = [];
        foreach ($t->getMeretek() as $meret) {
            $meretnevek[] = $meret->getNev();
        }
        $x['meretek'] = implode(', ', $meretnevek);

        return $x;
    }

    public function getSelectList($selid = null)
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

    /**
     * @param Meretsor $obj
     *
     * @return mixed
     */
    protected function setFields($obj)
    {
        $obj->setNev($this->params->getStringRequestParam('nev'));

        $meretIds = $this->params->getArrayRequestParam('meretek', []);
        $obj->removeAllMeret();
        if ($meretIds) {
            foreach ($meretIds as $meretId) {
                $meret = $this->getRepo(Meret::class)->find($meretId);
                if ($meret) {
                    $obj->addMeret($meret);
                }
            }
        }
        return $obj;
    }

    public function getlistbody()
    {
        $view = $this->createView('meretsorlista_tbody.tpl');

        $filter = new FilterDescriptor();
        if (!is_null($this->params->getRequestParam('nevfilter', null))) {
            $filter->addFilter(['nev'],
                'LIKE',
                '%' . $this->params->getStringRequestParam('nevfilter') . '%');
        }
        $this->initPager($this->getRepo()->getCount($filter));
        $egyedek = $this->getRepo()->getAll(
            $filter,
            $this->getOrderArray(),
            $this->getPager()->getOffset(),
            $this->getPager()->getElemPerPage()
        );

        echo json_encode($this->loadDataToView($egyedek, 'meretsorlista', $view));
    }

    public function viewlist()
    {
        $view = $this->createView('meretsorlista.tpl');
        $view->setVar('pagetitle', t('Méret sorok'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);
        $view->setVar('pagetitle', t('Méret sor'));
        $view->setVar('oper', $oper);

        $meretsor = $this->getRepo()->find($id);
        $view->setVar('egyed', $this->loadVars($meretsor, true));

        $meretek = $this->getRepo(Meret::class)->getAll([], ['sorrend' => 'ASC', 'nev' => 'ASC']);
        $view->setVar('meretek', $meretek);

        $view->printTemplateResult();
    }
}