<?php
namespace Controllers;
use matt, matt\Exceptions, SIIKerES\store;

class kontaktcimkeController extends matt\MattableController {

	public function __construct($generalDataLoader,$actionName=null,$commandString=null) {
		$this->setEntityName('Entities\Kontaktcimketorzs');
		$this->setEm(store::getEm());
		$this->setTemplateFactory(store::getTemplateFactory());
		$this->setKarbFormTplName('cimkekarbform.tpl');
		$this->setKarbTplName('cimkekarb.tpl');
		$this->setListBodyRowTplName('cimkelista_tbody_tr.tpl');
		$this->setListBodyRowVarName('_cimke');
		parent::__construct($generalDataLoader,$actionName,$commandString);
	}

	public function handleRequest() {
		$methodname=$this->getActionName();
		if ($this->mainMethodExists(__CLASS__,$methodname)) {
			$this->$methodname();
		}
		elseif ($this->adminMethodExists(__CLASS__,$methodname)) {
				$this->$methodname();
		}
		else {
			throw new matt\Exceptions\UnknownMethodException('"'.__CLASS__.'->'.$methodname.'" does not exist.');
		}
	}

	protected function loadVars($t) {
		$x=array();
		if ($t) {
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
		}
		else {
			$x['id']=0;
			$x['nev']='';
			$x['leiras']='';
			$x['oldalcim']='';
			$x['cimkekatnev']='';
			$x['menu1lathato']=false;
			$x['menu2lathato']=false;
			$x['menu3lathato']=false;
			$x['menu4lathato']=false;
		}
		return $x;
	}

	protected function setFields($obj) {
		try {
			$ck=store::getEm()->getRepository('Entities\Kontaktcimkekat')->find($this->getIntParam('cimkecsoport',0));
			if ($ck) {
				$obj->setKategoria($ck);
			}
			$obj->setNev($this->getStringParam('nev'));
			$obj->setLeiras($this->getStringParam('leiras'));
			$obj->setOldalcim($this->getStringParam('oldalcim'));
			$obj->setMenu1Lathato($this->getBoolParam('menu1lathato'));
			$obj->setMenu2Lathato($this->getBoolParam('menu2lathato'));
			$obj->setMenu3Lathato($this->getBoolParam('menu3lathato'));
			$obj->setMenu4Lathato($this->getBoolParam('menu4lathato'));
		}
		catch (matt\Exceptions\WrongValueTypeException $e){
		}
		return $obj;
	}

	protected function getlistbody() {
		$view=$this->createView('cimkelista_tbody.tpl');
		$view->setVar('kellkep',false);

		$filter=array();
		if (!is_null($this->getParam('nevfilter',NULL))) {
			$filter['fields'][]='nev';
			$filter['values'][]=$this->getStringParam('nevfilter');
		}
		$fv=$this->getIntParam('ckfilter');
		if ($fv>0) {
			$filter['fields'][]='ck.id';
			$filter['values'][]=$fv;
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

		echo json_encode($this->loadDataToView($egyedek,'cimkelista',$view));
	}

	protected function viewlist() {
		$view=$this->createView('cimkelista.tpl');

		$view->setVar('pagetitle',t('Kontaktcímkék'));
		$view->setVar('kellkep',false);
		$view->setVar('controllerscript','kontaktcimkelista.js');
		$view->setVar('orderselect',$this->getRepo()->getOrdersForTpl());
		$view->setVar('batchesselect',$this->getRepo()->getBatchesForTpl());
		$ckat=new kontaktcimkekatController($this->generalDataLoader);
		$view->setVar('cimkecsoportlist',$ckat->getSelectList(0));
		$view->printTemplateResult();
	}

	protected function _getkarb($id,$oper,$tplname) {
		$view=$this->createView($tplname);

		$view->setVar('pagetitle',t('Kontaktcímke'));
		$view->setVar('controllerscript','kontaktcimkekarb.js');
		$view->setVar('kellkep',false);
		$view->setVar('formaction','/admin/kontaktcimke/save');
		$view->setVar('oper',$oper);
		$record=$this->getRepo()->findWithJoins($id);
		$view->setVar('cimke',$this->loadVars($record));
		$ckat=new kontaktcimkekatController($this->generalDataLoader);
		$view->setVar('cimkecsoportlist',$ckat->getSelectList(($record?$record->getKategoriaId():0)));
		return $view->getTemplateResult();
	}

	protected function add() {
		$obj=new Kontaktcimketorzs();
		$this->setFields($obj);
		$this->getEm()->persist($obj);
		$this->getEm()->flush();
		$view=$this->createView('cimkeselector.tpl');
		$view->setVar('_cimke',array('id'=>$obj->getId(),'caption'=>$obj->getNev(),'selected'=>false));
		echo $view->getTemplateResult();
	}

	protected function setmenulathato() {
		$id=$this->getIntParam('id');
		$kibe=$this->getBoolParam('kibe');
		$num=$this->getIntParam('num');
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
				'sanitizedcaption'=>$kat->getSlug(), //matt\Filter::toPermalink($kat->getNev()),
				'cimkek'=>$adat
			);
		}
		return $res;
	}

	protected function viewselect() {
		$view=$this->createView('cimkelista.tpl');

		$view->setVar('pagetitle',t('Kontaktcímkék'));
		$view->setVar('kellkep',false);
		$view->setVar('controllerscript','kontaktcimkelista.js');
		$tc=store::getEm()->getRepository('Entities\Kontaktcimkekat')->getWithJoins(array(),array('_xx.nev'=>'asc','c.nev'=>'asc'));
		$view->setVar('cimkekat',$this->cimkekToArray($tc));
		$view->printTemplateResult();
	}

	public function getformenu($menu) {
		$tc=store::getEm()->getRepository('Entities\Kontaktcimkekat')->getAllHasKontakt($menu);
		return $this->cimkekToArray($tc);
	}
}