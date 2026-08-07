<?php

namespace Controllers;

use Entities\Bankszamla;

class bankszamlaController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(Bankszamla::class);
        $this->setKarbFormTplName('bankszamlakarbform.tpl');
        $this->setKarbTplName('bankszamlakarb.tpl');
        $this->setListBodyRowTplName('bankszamlalista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct();
    }

    public function loadVars($t, $forKarb = false)
    {
        if (!$t) {
            $t = new Bankszamla();
            $this->getEm()->detach($t);
        }
        $x = $this->getEntityFieldsArray($t);
        $x['valutanemid'] = $t->getValutanem() ? $t->getValutanem()->getId() : null;
        $x['valutanemnev'] = $t->getValutanem() ? $t->getValutanem()->getNev() : '';
        $bank = (string)$t->getBank();
        $x['bankformatumnev'] = banktranzakcioController::IMPORTFORMATUMOK[$bank]['nev'] ?? $bank;
        if ($forKarb) {
            $valutanemc = new valutanemController();
            $x['valutanemlist'] = $valutanemc->getSelectList($x['valutanemid']);
            $x['banklist'] = $this->getBankSelectList($bank);
        }
        return $x;
    }

    /**
     * A választható bankok – ugyanaz a lista, mint a banktranzakció importnál,
     * hogy a kettő összeköthető legyen.
     */
    public function getBankSelectList($sel = null)
    {
        $ret = [];
        foreach (banktranzakcioController::IMPORTFORMATUMOK as $kulcs => $f) {
            $ret[] = [
                'id' => $kulcs,
                'caption' => $f['nev'],
                'selected' => ($kulcs === (string)$sel),
            ];
        }
        return $ret;
    }

    /**
     * @param \Entities\Bankszamla $obj
     *
     * @return \Entities\Bankszamla
     */
    protected function setFields($obj)
    {
        $obj = $this->setEntityFieldsFromRequest($obj);

        $valutanem = $this->getRepo(\Entities\Valutanem::class)->find($this->params->getIntRequestParam('valutanem', 0));
        $obj->setValutanem($valutanem ?: null);

        // csak ismert formátumkulcsot fogadunk el (a setEntityFieldsFromRequest bármit beírna)
        $bank = $this->params->getStringRequestParam('bank', '');
        $obj->setBank(array_key_exists($bank, banktranzakcioController::IMPORTFORMATUMOK) ? $bank : null);

        return $obj;
    }

    public function getlistbody()
    {
        $view = $this->createView('bankszamlalista_tbody.tpl');

        $filter = new \mkwhelpers\FilterDescriptor();
        if (!is_null($this->params->getRequestParam('nevfilter', null))) {
            $filter->addFilter('banknev', 'LIKE', '%' . $this->params->getStringRequestParam('nevfilter') . '%');
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
        $view = $this->createView('bankszamlalista.tpl');

        $view->setVar('pagetitle', t('Bankszámlák'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Bankszámla'));
        $view->setVar('formaction', \mkw\store::getRouter()->generate('adminbankszamlasave'));
        $view->setVar('oper', $oper);
        $record = $this->getRepo()->find($id);
        $view->setVar('egyed', $this->loadVars($record, true));
        return $view->getTemplateResult();
    }

    public function getSelectList($selid = null)
    {
        $rec = $this->getRepo()->getAll([], ['szamlaszam' => 'ASC']);
        $res = [];
        foreach ($rec as $sor) {
            $res[] = ['id' => $sor->getId(), 'caption' => $sor->getSzamlaszam(), 'selected' => ($sor->getId() == $selid)];
        }
        return $res;
    }

    public function htmllist()
    {
        $rec = $this->getRepo()->getAll([], ['szamlaszam' => 'ASC']);
        $ret = '<select>';
        foreach ($rec as $sor) {
            $ret .= '<option value="' . $sor->getId() . '">' . $sor->getSzamlaszam() . '</option>';
        }
        $ret .= '</select>';
        echo $ret;
    }
}
