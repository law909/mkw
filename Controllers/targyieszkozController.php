<?php
namespace Controllers;
use mkw\store;

class targyieszkozController extends \mkwhelpers\MattableController {

	public function __construct($generalDataLoader,$actionName=null,$commandString=null) {
		$this->setEntityName('Entities\Targyieszkoz');
		$this->setEm(store::getEm());
		$this->setTemplateFactory(store::getTemplateFactory());
		$this->setKarbFormTplName('targyieszkozkarbform.tpl');
		$this->setKarbTplName('targyieszkozkarb.tpl');
		$this->setListBodyRowTplName('targyieszkozlista_tbody_tr.tpl');
		$this->setListBodyRowVarName('_egyed');
		parent::__construct($generalDataLoader,$actionName,$commandString);
	}

	protected function loadVars($t) {
		$x=array();
		if ($t) {
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
		}
		else {
			$x['id']=0;
			$x['leltariszam']='';
			$x['nev']='';
			$x['sorozatszam']='';
			$x['alkalmazottnev']='';
			$x['hasznalatihely']='';
			$x['tipus']='';
			$x['nincsecs']='';
			$x['csoportnev']='';
			$x['ecselszmod']='';
			$x['allapot']='';
			$x['allapotdatum']=new \DateTime();
			$x['allapotdatumstr']=$x['allapotdatum']->format(store::$DateFormat);
			$x['slug']='';

			$x['szvtvleirasikulcs']=0;
			$x['szvtvmaradvanyertek']=0;
			$x['szvtvelszkezdete']=new \DateTime();
			$x['szvtvelszkezdetestr']=$x['szvtvelszkezdete']->format(store::$DateFormat);

			$x['tatvleirasikulcs']=0;
			$x['tatvelszkezdete']=new \DateTime();
			$x['tatvelszkezdetestr']=$x['tatvelszkezdete']->format(store::$DateFormat);

			$x['beszerzesertek']=0;
			$x['beszerzesdatum']=new \DateTime();
			$x['beszerzesdatumstr']=$x['beszerzesdatum']->format(store::$DateFormat);
		}
		return $x;
	}

	protected function setFields($obj) {
		$obj->setLeltariSzam($this->getStringParam('leltariszam'));
		$obj->setNev($this->getStringParam('nev'));
		$obj->setSorozatSzam($this->getStringParam('sorozatszam'));
		$ck=store::getEm()->getRepository('Entities\Felhasznalo')->find($this->getIntParam('alkalmazott'));
		if ($ck) {
			$obj->setAlkalmazott($ck);
		}
		$obj->setHasznalatiHely($this->getStringParam('hasznalatihely'));
		$obj->setTipus($this->getIntParam('tipus'));
		$obj->setNincsecs($this->getBoolParam('nincsecs'));
		$ck=store::getEm()->getRepository('Entities\TargyieszkozCsoport')->find($this->getIntParam('csoport'));
		if ($ck) {
			$obj->setCsoport($ck);
		}
		$obj->setEcselszmod($this->getIntParam('ecselszmod'));
		//$obj->setSlug($this->getStringParam('slug'));

		$obj->setSzvtvleirasikulcs($this->getFloatParam('szvtvleirasikulcs'));
		$obj->setSzvtvmaradvanyertek($this->getFloatParam('szvtvmaradvanyertek'));
		$obj->setSzvtvelszkezdete($this->getDateParam('szvtvelszkezdete'));
		$obj->setTatvleirasikulcs($this->getFloatParam('tatvleirasikulcs'));
		$obj->setTatvelszkezdete($this->getFloatParam('tatvelszkezdete'));
		$obj->setAllapot($this->getIntParam('allapot'));
		$obj->setAllapotDatum($this->getDateParam('allapotdatum'));
		$obj->setBeszerzesDatum($this->getDateParam('beszerzesdatum'));
		$obj->setBeszerzesErtek($this->getFloatParam('beszerzesertek'));
		return $obj;
	}

	protected function getlistbody() {
		$view=$this->createView('targyieszkozlista_tbody.tpl');

		$filter=array();
		if (!is_null($this->getParam('nevfilter',NULL))) {
			$filter['fields'][]=array('nev','leltariszam');
			$filter['values'][]=$this->getStringParam('nevfilter');
		}

		$this->initPager(
			$this->getRepo()->getCount($filter),
			$this->getIntParam('elemperpage',30),
			$this->getIntParam('pageno',1));

		$egyedek=$this->getRepo()->getWithJoins(
			$filter,
			$this->getOrderArray(),
			$this->getPager()->getOffset(),
			$this->getPager()->getElemPerPage());

		echo json_encode($this->loadDataToView($egyedek,'egyedlista',$view));
	}

	protected function viewselect() {
		$view=$this->createView('targyieszkozlista.tpl');

		$view->setVar('pagetitle',t('Tárgyieszközök'));
		$view->setVar('controllerscript','targyieszkozlista.js');
		$view->printTemplateResult();
	}

	protected function viewlist() {
		$view=$this->createView('targyieszkozlista.tpl');

		$view->setVar('pagetitle',t('Tárgyieszközök'));
		$view->setVar('controllerscript','targyieszkozlista.js');
		$view->setVar('orderselect',$this->getRepo()->getOrdersForTpl());
		$view->setVar('batchesselect',$this->getRepo()->getBatchesForTpl());
		$view->printTemplateResult();
	}

	protected function _getkarb($id,$oper,$tplname) {
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

		$csoport=new targyieszkozcsoportController($this->generalDataLoader);
		$view->setVar('csoportlist',$csoport->getSelectList(($record?$record->getCsoportId():0)));

		$felhasznalo=new felhasznaloController($this->generalDataLoader);
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

	protected function gethasznalatihelyek() {
		$searchterm=$this->getStringParam('term');
		$hhelyek=$this->getRepo()->getHasznalatiHelyek($searchterm);
		$result=array();
		foreach ($hhelyek as $key=>$nev) {
			$result[]=array('id'=>$nev['hasznalatihely'],'label'=>$nev['hasznalatihely'],'value'=>$nev['hasznalatihely']);
		}
		echo json_encode($result);
	}
}