<?php
namespace Controllers;
use mkw\store;

class kontaktcimkeController extends \mkwhelpers\MattableController {

	public function __construct($params) {
		$this->setEntityName('Entities\Kontaktcimketorzs');
		$this->setKarbFormTplName('cimkekarbform.tpl');
		$this->setKarbTplName('cimkekarb.tpl');
		$this->setListBodyRowTplName('cimkelista_tbody_tr.tpl');
		$this->setListBodyRowVarName('_cimke');
		parent::__construct($params);
	}

	protected function loadVars($t) {
		$x=array();
		if (!$t) {
			$t=new \Entities\Kontaktcimketorzs();
			$this->getEm()->detach($t);
		}
		$x['id']=$t->getId();
		$x['nev']=$t->getNev();
		$x['leiras']=$t->getLeiras();
		$x['oldalcim']=$t->getOldalcim();
		if ($kat=$t->getKategoria()) {
			$x['cimkekatnev']=$kat->getNev();
		}
		else {
			$x['cimkekatnev']='';
		}
		$x['menu1lathato']=$t->getMenu1Lathato();
		$x['menu2lathato']=$t->getMenu2Lathato();
		$x['menu3lathato']=$t->getMenu3Lathato();
		$x['menu4lathato']=$t->getMenu4Lathato();
		return $x;
	}

	protected function setFields($obj) {
		$ck=store::getEm()->getRepository('Entities\Kontaktcimkekat')->find($this->params->getIntRequestParam('cimkecsoport',0));
		if ($ck) {
			$obj->setKategoria($ck);
		}
		$obj->setNev($this->params->getStringRequestParam('nev'));
		$obj->setLeiras($this->params->getStringRequestParam('leiras'));
		$obj->setOldalcim($this->params->getStringRequestParam('oldalcim'));
		$obj->setMenu1Lathato($this->params->getBoolRequestParam('menu1lathato'));
		$obj->setMenu2Lathato($this->params->getBoolRequestParam('menu2lathato'));
		$obj->setMenu3Lathato($this->params->getBoolRequestParam('menu3lathato'));
		$obj->setMenu4Lathato($this->params->getBoolRequestParam('menu4lathato'));
		return $obj;
	}

	public function getlistbody() {
		$view=$this->createView('cimkelista_tbody.tpl');
		$view->setVar('kellkep',false);

		$filter=array();
		if (!is_null($this->params->getRequestParam('nevfilter',NULL))) {
			$filter['fields'][]='nev';
			$filter['values'][]=$this->params->getRequestStringParam('nevfilter');
		}
		$fv=$this->params->getIntRequestParam('ckfilter');
		if ($fv>0) {
			$filter['fields'][]='ck.id';
			$filter['values'][]=$fv;
		}

		$this->initPager($this->getRepo()->getCount($filter));

		$egyedek=$this->getRepo()->getWithJoins(
			$filter,
			$this->getOrderArray(),
			$this->getPager()->getOffset(),
			$this->getPager()->getElemPerPage());

		echo json_encode($this->loadDataToView($egyedek,'cimkelista',$view));
	}

	public function viewlist() {
		$view=$this->createView('cimkelista.tpl');

		$view->setVar('pagetitle',t('Kontaktcímkék'));
		$view->setVar('kellkep',false);
		$view->setVar('controllerscript','kontaktcimke.js');
		$view->setVar('orderselect',$this->getRepo()->getOrdersForTpl());
		$view->setVar('batchesselect',$this->getRepo()->getBatchesForTpl());
		$ckat=new kontaktcimkekatController($this->params);
		$view->setVar('cimkecsoportlist',$ckat->getSelectList(0));
		$view->printTemplateResult();
	}

	protected function _getkarb($tplname) {
		$id=$this->params->getRequestParam('id',0);
		$oper=$this->params->getRequestParam('oper','');
		$view=$this->createView($tplname);

		$view->setVar('pagetitle',t('Kontaktcímke'));
		$view->setVar('controllerscript','kontaktcimke.js');
		$view->setVar('kellkep',false);
		$view->setVar('formaction','/admin/kontaktcimke/save');
		$view->setVar('oper',$oper);
		$record=$this->getRepo()->findWithJoins($id);
		$view->setVar('cimke',$this->loadVars($record));
		$ckat=new kontaktcimkekatController($this->params);
		$view->setVar('cimkecsoportlist',$ckat->getSelectList(($record?$record->getKategoriaId():0)));
		return $view->getTemplateResult();
	}

	public function add() {
		$obj=new Kontaktcimketorzs();
		$this->setFields($obj,$this->params);
		$this->getEm()->persist($obj);
		$this->getEm()->flush();
		$view=$this->createView('cimkeselector.tpl');
		$view->setVar('_cimke',array('id'=>$obj->getId(),'caption'=>$obj->getNev(),'selected'=>false));
		echo $view->getTemplateResult();
	}

	public function setmenulathato() {
		$id=$this->params->getIntRequestParam('id');
		$kibe=$this->params->getBoolRequestParam('kibe');
		$num=$this->params->getIntRequestParam('num');
		$obj=$this->getRepo()->find($id);
		if ($obj) {
			switch ($num) {
				case 1:
					$obj->setMenu1Lathato($kibe);
					break;
				case 2:
					$obj->setMenu2Lathato($kibe);
					break;
				case 3:
					$obj->setMenu3Lathato($kibe);
					break;
				case 4:
					$obj->setMenu4Lathato($kibe);
					break;
			}
			store::getEm()->persist($obj);
			store::getEm()->flush();
		}
	}

	private function cimkekToArray($cimkekat) {
		$res=array();
		foreach($cimkekat as $kat) {
			$adat=array();
			$cimkek=$kat->getCimkek();
			foreach($cimkek as $cimke) {
				$adat[]=array(
					'id'=>$cimke->getId(),
					'caption'=>$cimke->getNev(),
					'slug'=>$cimke->getSlug()
				);
			}
			$res[]=array(
				'id'=>$kat->getId(),
				'caption'=>$kat->getNev(),
				'sanitizedcaption'=>$kat->getSlug(),
				'cimkek'=>$adat
			);
		}
		return $res;
	}

	public function viewselect() {
		$view=$this->createView('cimkelista.tpl');

		$view->setVar('pagetitle',t('Kontaktcímkék'));
		$view->setVar('kellkep',false);
		$view->setVar('controllerscript','kontaktcimke.js');
		$tc=store::getEm()->getRepository('Entities\Kontaktcimkekat')->getWithJoins(array(),array('_xx.nev'=>'asc','c.nev'=>'asc'));
		$view->setVar('cimkekat',$this->cimkekToArray($tc));
		$view->printTemplateResult();
	}

	public function getformenu($menu) {
		$tc=store::getEm()->getRepository('Entities\Kontaktcimkekat')->getAllHasKontakt($menu);
		return $this->cimkekToArray($tc);
	}
}