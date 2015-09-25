<?php
namespace Controllers;

use mkw\store;

class arfolyamController extends \mkwhelpers\JQGridController {

	public function __construct($params) {
		$this->setEntityName('Entities\Arfolyam');
		parent::__construct($params);
	}

	protected function loadCells($sor) {
		$valuta=$sor->getValutanem();
		return array($sor->getDatumString(),(isset($valuta)?$valuta->getNev():''),$sor->getArfolyam());
	}

	protected function setFields($obj) {
		$obj->setDatum(new \DateTime(str_replace('.','-',$this->params->getStringRequestParam('datum'))));
		$obj->setArfolyam($this->params->getNumRequestParam('arfolyam'));
		$valutanem=store::getEm()->getReference('Entities\Valutanem',$this->params->getIntRequestParam('valutanem',0));
		try {
			$valutanem->getId();
			$obj->setValutanem($valutanem);
		}
		catch (\Doctrine\ORM\EntityNotFoundException $e) {
		}
		return $obj;
	}

	public function jsonlist() {
		$filter=array();
		if ($this->params->getBoolRequestParam('_search',false)) {
			if ($this->params->getStringRequestParam('datum','')!='') {
				$filter['fields'][]='datum';
				$filter['values'][]=$this->params->getStringRequestParam('datum','');
			}
			if ($this->params->getStringRequestParam('valutanem','')!='') {
				$filter['fields'][]='v.nev';
				$filter['values'][]=$this->params->getStringRequestParam('valutanem','');
			}
		}
		$rec=$this->getRepo()->getAll($filter,$this->getOrderArray());
		echo json_encode($this->loadDataToView($rec));
	}

	public function getSelectList($selid) {
		$rec=$this->getRepo()->getAll(array(),array('datum'=>'ASC'));
		$res=array();
		foreach($rec as $sor) {
			$res[]=array('id'=>$sor->getId(),'caption'=>$sor->getNev(),'selected'=>($sor->getId()==$selid));
		}
		return $res;
	}

	public function htmllist() {
		$rec=$this->getRepo()->getAll(array(),array('datum'=>'asc'));
		$ret='<select>';
		foreach($rec as $sor) {
			$ret.='<option value="'.$sor->getId().'">'.$sor->getSzamlaszam().'</option>';
		}
		$ret.='</select>';
		echo $ret;
	}

	public function getarfolyam() {
		$arf = $this->getRepo()->getActualArfolyam($this->params->getIntRequestParam('valutanem'), $this->params->getStringRequestParam('datum'));
		if ($arf instanceof \Entities\Arfolyam) {
			echo $arf->getArfolyam();
		}
		else {
			echo $arf;
		}
	}

    public function downloadArfolyam() {
        $datum = \mkw\Store::convDate($this->params->getStringRequestParam('datum'));
        $datum = date(\mkw\Store::$DateFormat, strtotime($datum));
        $rvaluta = \mkw\Store::getParameter(\mkw\consts::Valutanem);
        $vr = \mkw\Store::getEm()->getRepository('Entities\Valutanem');
        $filter = array();
        $filter['fields'][] = 'id';
        $filter['clauses'][] = '<>';
        $filter['values'][] = $rvaluta;
        $valutak = $vr->getAll($filter);
        $valutanevek = array();
        foreach($valutak as $v) {
            $valutanevek[] = $v->getNev();
        }
        if ($valutanevek) {

            $srv = new \SoapClient('http://www.mnb.hu/arfolyamok.asmx?WSDL');
            $res = $srv->__soapCall('GetExchangeRates', array('parameters' => array(
                'startDate' => $datum,
                'endDate' => $datum,
                'currencyNames' => implode(',', $valutanevek)
            )));
            if ($res) {
                $rates = simplexml_load_string($res->GetExchangeRatesResult);
                $rates = $rates->Day;
                foreach($rates->Rate as $rate) {
                    $valutanem = $vr->findOneBy(array('nev' => $rate['curr']));
                    if ($valutanem) {
                        $arf = $this->getRepo()->getArfolyam($valutanem, $datum);
                        if (!$arf) {
                            $arf = new \Entities\Arfolyam();
                            $arf->setValutanem($valutanem);
                            $arf->setDatum(new \DateTime(\mkw\Store::convDate($datum)));
                            $arf->setArfolyam(str_replace(',', '.', $rate) * 1);
                            \mkw\Store::getEm()->persist($arf);
                            \mkw\Store::getEm()->flush();
                        }
                    }
                }
            }
        }
        else {
            echo 'nincs valuta';
        }
    }
}