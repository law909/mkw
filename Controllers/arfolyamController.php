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

    public function downloadArfolyam()
    {
        $datum = \mkw\store::convDate($this->params->getStringRequestParam('datum'));
        $datum = date(\mkw\store::$DateFormat, strtotime($datum));
        $rvaluta = \mkw\store::getParameter(\mkw\consts::Valutanem);
        $vr = \mkw\store::getEm()->getRepository(Valutanem::class);

        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('id', '<>', $rvaluta);

        $valutak = $vr->getAll($filter);

        $valutanevek = [];
        foreach ($valutak as $v) {
            $valutanevek[] = $v->getNev();
        }
        if ($valutanevek) {
            $srv = new \SoapClient('http://www.mnb.hu/arfolyamok.asmx?WSDL');
            $res = $srv->__soapCall('GetExchangeRates', [
                'parameters' => [
                    'startDate' => $datum,
                    'endDate' => $datum,
                    'currencyNames' => implode(',', $valutanevek)
                ]
            ]);
            if ($res) {
                $rates = simplexml_load_string($res->GetExchangeRatesResult);
                $rates = $rates->Day;
                foreach ($rates->Rate as $rate) {
                    $valutanem = $vr->findOneBy(['nev' => $rate['curr']]);
                    if ($valutanem) {
                        $arf = $this->getRepo()->getArfolyam($valutanem, $datum);
                        if (!$arf) {
                            $arf = new \Entities\Arfolyam();
                            $arf->setValutanem($valutanem);
                            $arf->setDatum(new \DateTime(\mkw\store::convDate($datum)));
                            $arf->setArfolyam((float)str_replace(',', '.', $rate));
                            \mkw\store::getEm()->persist($arf);
                            \mkw\store::getEm()->flush();
                        }
                    }
                }
            }
        } else {
            echo 'nincs valuta';
        }
    }
}
