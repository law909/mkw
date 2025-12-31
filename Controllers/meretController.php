<?php

namespace Controllers;

use Entities\Meret;

class meretController extends \mkwhelpers\MattableController
{

    public function __construct($params)
    {
        $this->setEntityName(Meret::class);
        $this->setKarbFormTplName('meretkarbform.tpl');
        $this->setKarbTplName('meretkarb.tpl');
        $this->setListBodyRowTplName('meretlista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_meret');
        parent::__construct($params);
    }

    public function loadVars($t, $forKarb = false)
    {
        $x = [];
        if (!$t) {
            $t = new \Entities\Meret();
            $this->getEm()->detach($t);
        }
        $x['id'] = $t->getId();
        $x['nev'] = $t->getNev();
        $x['sorrend'] = $t->getSorrend();

        $x['kepurl'] = $t->getKepurl();
        $x['kepurlsmall'] = $t->getKepurlSmall();
        $x['kepurlmedium'] = $t->getKepurlMedium();
        $x['kepurllarge'] = $t->getKepurlLarge();
        $x['kepleiras'] = $t->getKepleiras();
        return $x;
    }

    /**
     * @param \Entities\Meret $obj
     *
     * @return mixed
     */
    protected function setFields($obj)
    {
        $obj->setNev($this->params->getStringRequestParam('nev'));
        $obj->setSorrend($this->params->getIntRequestParam('sorrend'));
        $obj->setKepurl($this->params->getStringRequestParam('kepurl', ''));
        $obj->setKepleiras($this->params->getStringRequestParam('kepleiras', ''));
        return $obj;
    }

    public function getlistbody()
    {
        $view = $this->createView('meretlista_tbody.tpl');

        $filter = new \mkwhelpers\FilterDescriptor();
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

        echo json_encode($this->loadDataToView($egyedek, 'meretlista', $view));
    }

    public function getSelectList($selid = null)
    {
        $rec = $this->getRepo()->getAll();
        $res = [];
        foreach ($rec as $sor) {
            $res[] = [
                'id' => $sor['id'],
                'caption' => $sor['nev'],
                'selected' => ($sor['id'] == $selid)
            ];
        }
        return $res;
    }

    public function htmllist()
    {
        $rec = $this->getRepo()->getAll();
        $ret = '<select>';
        foreach ($rec as $sor) {
            $ret .= '<option value="' . $sor['id'] . '">' . $sor['nev'] . '</option>';
        }
        $ret .= '</select>';
        echo $ret;
    }

    public function viewlist()
    {
        $view = $this->createView('meretlista.tpl');
        $view->setVar('pagetitle', t('Méretek'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);
        $view->setVar('pagetitle', t('Méret'));
        $view->setVar('oper', $oper);

        $meret = $this->getRepo()->find($id);

        $view->setVar('egyed', $this->loadVars($meret, true));
        $view->printTemplateResult();
    }

}