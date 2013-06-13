<?php
namespace Controllers;
use matt, matt\Exceptions, mkw\store;

class korhintaController extends matt\MattableController {

	public function __construct($generalDataLoader,$actionName=null,$commandString=null) {
		$this->setEntityName('Entities\Korhinta');
		$this->setEm(store::getEm());
		$this->setTemplateFactory(store::getTemplateFactory());
		$this->setKarbFormTplName('korhintakarbform.tpl');
		$this->setKarbTplName('korhintakarb.tpl');
		$this->setListBodyRowTplName('korhintalista_tbody_tr.tpl');
		$this->setListBodyRowVarName('_egyed');
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
		if (!$t) {
			$t=new \Entities\Korhinta();
			$this->getEm()->detach($t);
		}
		$x['id']=$t->getId();
		$x['nev']=$t->getNev();
		$x['szoveg']=$t->getSzoveg();
		$x['url']=$t->getUrl();
		$x['kepurl']=$t->getKepurl();
		$x['kepleiras']=$t->getKepleiras();
		$x['kepnev']=$t->getKepnev();
		$x['lathato']=$t->getLathato();
		$x['sorrend']=$t->getSorrend();

		return $x;
	}

	protected function setFields($obj) {
		try {
			$obj->setNev($this->getStringParam('nev'));
			$obj->setUrl($this->getStringParam('url'));
			$obj->setSzoveg($this->getStringParam('szoveg'));
			$obj->setKepleiras($this->getStringParam('kepleiras',''));
			$obj->setLathato($this->getBoolParam('lathato',true));
			$obj->setSorrend($this->getIntParam('sorrend',0));
		}
		catch (matt\Exceptions\WrongValueTypeException $e){
		}
		return $obj;
	}

	protected function getlistbody() {
		$view=$this->createView('korhintalista_tbody.tpl');

		$filter=array();
		if (!is_null($this->getParam('nevfilter',NULL))) {
			$filter['fields'][]='nev';
			$filter['values'][]=$this->getStringParam('nevfilter');
		}

		$this->initPager(
			$this->getRepo()->getCount($filter),
			$this->getIntParam('elemperpage',30),
			$this->getIntParam('pageno',1));

		$egyedek=$this->getRepo()->getAll(
			$filter,
			$this->getOrderArray(),
			$this->getPager()->getOffset(),
			$this->getPager()->getElemPerPage());

		echo json_encode($this->loadDataToView($egyedek,'egyedlista',$view));
	}

	protected function viewselect() {
		$view=$this->createView('korhintalista.tpl');

		$view->setVar('pagetitle',t('Körhinta'));
		$view->setVar('controllerscript','korhintalista.js');
		$view->printTemplateResult();
	}

	protected function viewlist() {
		$view=$this->createView('korhintalista.tpl');

		$view->setVar('pagetitle',t('Körhinta'));
		$view->setVar('controllerscript','korhintalista.js');
		$view->setVar('orderselect',$this->getRepo()->getOrdersForTpl());
		$view->setVar('batchesselect',$this->getRepo()->getBatchesForTpl());
		$view->printTemplateResult();
	}

	protected function _getkarb($id,$oper,$tplname) {
		$view=$this->createView($tplname);

		$view->setVar('pagetitle',t('Körhinta'));
		$view->setVar('controllerscript','korhintakarb.js');
		$view->setVar('formaction','/admin/korhinta/save');
		$view->setVar('oper',$oper);
		$record=$this->getRepo()->find($id);
		$view->setVar('egyed',$this->loadVars($record));
		return $view->getTemplateResult();
	}

	protected function savepicture() {
		$fa=$this->getRepo()->find($this->getIntParam('id'));
		if ($fa) {
			$uploaddir=store::getConfigValue('path.korhintakep');
			$pp=pathinfo($_FILES['userfile']['name']);
			$uploadfile=$uploaddir.$this->getStringParam('nev').'.'.$pp['extension'];
			if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
				$imageproc=new matt\Images($uploadfile);
				$imageproc->setJpgquality(store::getParameter('jpgquality'));
				$imageproc->setPngquality(store::getParameter('pngquality'));
				$fn=$uploaddir.$this->getStringParam('nev').'.'.$pp['extension'];
				$imageproc->Resample($fn,store::getParameter('korhintaimagesize',480));
				$fa->setKepnev($this->getStringParam('nev'));
				$fa->setKepleiras($this->getStringParam('leiras'));
				$fa->setKepurl($uploadfile);
				$this->getEm()->persist($fa);
				$this->getEm()->flush();
				$view=$this->createView('korhintaimagekarb.tpl');
				$view->setVar('oper','edit');
				$view->setVar('egyed',$this->loadVars($fa));
				$view->printTemplateResult();
			}
		}
	}

	protected function delpicture() {
		$fa=$this->getRepo()->find($this->getIntParam('id'));
		if ($fa) {
			unlink($fa->getKepurl(''));
			$fa->setKepurl(null);
			$this->getEm()->persist($fa);
			$this->getEm()->flush();
			$view=$this->createView('korhintaimagekarb.tpl');
			$view->setVar('oper','edit');
			$view->printTemplateResult();
		}
	}

	protected function setflag() {
		$id=$this->getIntParam('id');
		$kibe=$this->getBoolParam('kibe');
		$flag=$this->getStringParam('flag');
		$obj=$this->getRepo()->find($id);
		if ($obj) {
			switch ($flag) {
				case 'lathato':
					$obj->setLathato($kibe);
					break;
			}
			$this->getEm()->persist($obj);
			$this->getEm()->flush();
		}
	}

	public function getLista() {
		$t=array();
		$hintak=$this->getRepo()->getAllLathato();
		foreach($hintak as $hinta) {
			$t[]=$hinta->convertToArray();
		}
		return $t;
	}
}