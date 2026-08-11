<?php

namespace Controllers;

use Entities\Arfolyam;

class arfolyamController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(Arfolyam::class);
        $this->setKarbFormTplName('arfolyamkarbform.tpl');
        $this->setKarbTplName('arfolyamkarb.tpl');
        $this->setListBodyRowTplName('arfolyamlista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct();
    }

    public function loadVars($t, $forKarb = false)
    {
        if (!$t) {
            $t = new Arfolyam();
            $this->getEm()->detach($t);
        }
        $x = $this->getEntityFieldsArray($t);
        $x['valutanemid'] = $t->getValutanem() ? $t->getValutanem()->getId() : null;
        $x['valutanemnev'] = $t->getValutanem() ? $t->getValutanem()->getNev() : '';
        $x['datumstr'] = $t->getDatum() ? $t->getDatum()->format('Y.m.d') : '';
        if ($forKarb) {
            $valutanemc = new valutanemController();
            $x['valutanemlist'] = $valutanemc->getSelectList($x['valutanemid']);
        }
        return $x;
    }

    /**
     * @param \Entities\Arfolyam $obj
     *
     * @return \Entities\Arfolyam
     */
    protected function setFields($obj)
    {
        $obj = $this->setEntityFieldsFromRequest($obj);
        $obj->setDatum(new \DateTime(str_replace('.', '-', $this->params->getStringRequestParam('datum'))));

        $valutanem = $this->getRepo(\Entities\Valutanem::class)->find($this->params->getIntRequestParam('valutanem', 0));
        $obj->setValutanem($valutanem ?: null);

        return $obj;
    }

    public function getlistbody()
    {
        $view = $this->createView('arfolyamlista_tbody.tpl');

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
        $view = $this->createView('arfolyamlista.tpl');

        $view->setVar('pagetitle', t('Árfolyamok'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Árfolyam'));
        $view->setVar('formaction', \mkw\store::getRouter()->generate('adminarfolyamsave'));
        $view->setVar('oper', $oper);
        $record = $this->getRepo()->find($id);
        $view->setVar('egyed', $this->loadVars($record, true));
        return $view->getTemplateResult();
    }

    public function getarfolyam()
    {
        $arf = $this->getRepo()->getActualArfolyam($this->params->getIntRequestParam('valutanem'), $this->params->getStringRequestParam('datum'));
        if ($arf instanceof \Entities\Arfolyam) {
            echo $arf->getArfolyam();
        } else {
            echo $arf;
        }
    }

    /**
     * A letöltés maga a \Services\ArfolyamService-ben él, mert a `php cron.php arfolyam` is
     * ugyanezt futtatja.
     */
    public function downloadArfolyam()
    {
        $result = (new \Services\ArfolyamService())->download($this->params->getStringRequestParam('datum'));
        if (!$result['valutak']) {
            echo 'nincs valuta';
        }
    }
}
