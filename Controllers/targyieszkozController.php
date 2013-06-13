<?php
namespace Controllers;
use mkw\store;

class targyieszkozController extends \mkwhelpers\MattableController {

	public function __construct($params) {
		$this->setEntityName('Entities\Targyieszkoz');
		$this->setKarbFormTplName('targyieszkozkarbform.tpl');
		$this->setKarbTplName('targyieszkozkarb.tpl');
		$this->setListBodyRowTplName('targyieszkozlista_tbody_tr.tpl');
		$this->setListBodyRowVarName('_egyed');
		parent::__construct($params);
	}

	protected function loadVars($t) {
		$x=array();
		if (!$t) {
			$t=new \Entities\Targyieszkoz();
			$this->getEm()->detach($t);
		}
		$x['id']=$t->getId();
		$x['leltariszam']=$t->getLeltariSzam();
		$x['nev']=$t->getNev();
		$x['sorozatszam']=$t->getSorozatSzam();
		if ($tmp=$t->getAlkalmazott()) {
			$x['alkalmazottnev']=$tmp->getNev();
		}
		else {
			$x['alkalmazottnev']='';
		}
		$x['hasznalatihely']=$t->getHasznalatiHely();
		$x['tipus']=$t->getTipus();
		$x['nincsecs']=$t->getNincsecs();
		if ($tmp=$t->getCsoport()) {
			$x['csoportnev']=$tmp->getNev();
		}
		else {
			$x['csoportnev']='';
		}
		$x['ecselszmod']=$t->getEcselszmod();
		$x['allapot']=$t->getAllapot();
		$x['allapotdatum']=$t->getAllapotDatum();
		$x['allapotdatumstr']=$t->getAllapotDatumStr();
		$x['slug']=$t->getSlug();

		$x['szvtvleirasikulcs']=$t->getSzvtvleirasikulcs();
		$x['szvtvmaradvanyertek']=$t->getSzvtvmaradvanyertek();
		$x['szvtvelszkezdete']=$t->getSzvtvelszkezdete();
		$x['szvtvelszkezdetestr']=$t->getSzvtvelszkezdeteStr();

		$x['tatvleirasikulcs']=$t->getTatvleirasikulcs();
		$x['tatvelszkezdete']=$t->getTatvelszkezdete();
		$x['tatvelszkezdetestr']=$t->getTatvelszkezdeteStr();

		$x['beszerzesertek']=$t->getBeszerzesErtek();
		$x['beszerzesdatum']=$t->getBeszerzesDatum();
		$x['beszerzesdatumstr']=$t->getBeszerzesDatumStr();
		return $x;
	}

	protected function setFields($obj) {
		$obj->setLeltariSzam($this->params->getStringRequestParam('leltariszam'));
		$obj->setNev($this->params->getStringRequestParam('nev'));
		$obj->setSorozatSzam($this->params->getStringRequestParam('sorozatszam'));
		$ck=store::getEm()->getRepository('Entities\Felhasznalo')->find($this->params->getIntRequestParam('alkalmazott'));
		if ($ck) {
			$obj->setAlkalmazott($ck);
		}
		$obj->setHasznalatiHely($this->params->getStringRequestParam('hasznalatihely'));
		$obj->setTipus($this->params->getIntRequestParam('tipus'));
		$obj->setNincsecs($this->params->getBoolRequestParam('nincsecs'));
		$ck=store::getEm()->getRepository('Entities\TargyieszkozCsoport')->find($this->params->getIntRequestParam('csoport'));
		if ($ck) {
			$obj->setCsoport($ck);
		}
		$obj->setEcselszmod($this->params->getIntRequestParam('ecselszmod'));
		//$obj->setSlug($this->params->getStringRequestParam('slug'));

		$obj->setSzvtvleirasikulcs($this->params->getFloatRequestParam('szvtvleirasikulcs'));
		$obj->setSzvtvmaradvanyertek($this->params->getFloatRequestParam('szvtvmaradvanyertek'));
		$obj->setSzvtvelszkezdete($this->params->getDateRequestParam('szvtvelszkezdete'));
		$obj->setTatvleirasikulcs($this->params->getFloatRequestParam('tatvleirasikulcs'));
		$obj->setTatvelszkezdete($this->params->getFloatRequestParam('tatvelszkezdete'));
		$obj->setAllapot($this->params->getIntRequestParam('allapot'));
		$obj->setAllapotDatum($this->params->getDateRequestParam('allapotdatum'));
		$obj->setBeszerzesDatum($this->params->getDateRequestParam('beszerzesdatum'));
		$obj->setBeszerzesErtek($this->params->getFloatRequestParam('beszerzesertek'));
		return $obj;
	}

	public function getlistbody() {
		$view=$this->createView('targyieszkozlista_tbody.tpl');

		$filter=array();
		if (!is_null($this->params->getRequestParam('nevfilter',NULL))) {
			$filter['fields'][]=array('nev','leltariszam');
			$filter['values'][]=$this->params->getStringRequestParam('nevfilter');
		}

		$this->initPager($this->getRepo()->getCount($filter));

		$egyedek=$this->getRepo()->getWithJoins(
			$filter,
			$this->getOrderArray(),
			$this->getPager()->getOffset(),
			$this->getPager()->getElemPerPage());

		echo json_encode($this->loadDataToView($egyedek,'egyedlista',$view));
	}

	public function viewselect() {
		$view=$this->createView('targyieszkozlista.tpl');

		$view->setVar('pagetitle',t('Tárgyieszközök'));
		$view->setVar('controllerscript','targyieszkozlista.js');
		$view->printTemplateResult();
	}

	public function viewlist() {
		$view=$this->createView('targyieszkozlista.tpl');

		$view->setVar('pagetitle',t('Tárgyieszközök'));
		$view->setVar('controllerscript','targyieszkozlista.js');
		$view->setVar('orderselect',$this->getRepo()->getOrdersForTpl());
		$view->setVar('batchesselect',$this->getRepo()->getBatchesForTpl());
		$view->printTemplateResult();
	}

	protected function _getkarb($tplname) {
		$id=$this->params->getRequestParam('id',0);
		$oper=$this->params->getRequestParam('oper','');
		$view=$this->createView($tplname);

		$view->setVar('pagetitle',t('Tárgyieszköz'));
		$view->setVar('controllerscript','targyieszkozkarb.js');
		$view->setVar('formaction','/admin/targyieszkoz/save');
		$view->setVar('oper',$oper);
		$record=$this->getRepo()->findWithJoins($id);
		$egyedtomb=$this->loadVars($record);
		$egyedtomb['tipuslista']=$this->tipustoselectlist(($record)?$record->getTipus():0);
		$egyedtomb['ecselszmodlista']=$this->ecselszmodtoselectlist(($record)?$record->getEcselszmod():0);
		$egyedtomb['allapotlista']=$this->allapottoselectlist(($record)?$record->getAllapot():0);

		$csoport=new targyieszkozcsoportController($this->params);
		$view->setVar('csoportlist',$csoport->getSelectList(($record?$record->getCsoportId():0)));

		$felhasznalo=new felhasznaloController($this->params);
		$view->setVar('alkalmazottlist',$felhasznalo->getSelectList(($record?$record->getAlkalmazottId():'')));

		$view->setVar('egyed',$egyedtomb);
		return $view->getTemplateResult();
	}

	private function tipustoselectlist($selectedid) {
		$tipusok=$this->getRepo()->getTipusok();
		$result=array();
		foreach ($tipusok as $id=>$nev) {
			$result[]=array('id'=>$id,'caption'=>$nev,'selected'=>($id==$selectedid?true:false));
		}
		return $result;
	}

	private function ecselszmodtoselectlist($selectedid) {
		$ecselszmodok=$this->getRepo()->getEcselszmodok();
		$result=array();
		foreach ($ecselszmodok as $id=>$nev) {
			$result[]=array('id'=>$id,'caption'=>$nev,'selected'=>($id==$selectedid?true:false));
		}
		return $result;
	}

	private function allapottoselectlist($selectedid) {
		$allapotok=$this->getRepo()->getAllapotok();
		$result=array();
		foreach ($allapotok as $id=>$nev) {
			$result[]=array('id'=>$id,'caption'=>$nev,'selected'=>($id==$selectedid?true:false));
		}
		return $result;
	}

	public function gethasznalatihelyek() {
		$searchterm=$this->params->getStringRequestParam('term');
		$hhelyek=$this->getRepo()->getHasznalatiHelyek($searchterm);
		$result=array();
		foreach ($hhelyek as $key=>$nev) {
			$result[]=array('id'=>$nev['hasznalatihely'],'label'=>$nev['hasznalatihely'],'value'=>$nev['hasznalatihely']);
		}
		echo json_encode($result);
	}
}