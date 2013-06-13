<?php
namespace Controllers;
use mkw\store;

class templateController extends \mkwhelpers\MattableController {

	private $fajlok;
	private $filedata;

	public function __construct($generalDataLoader,$actionName=null,$commandString=null) {
//		$this->setEntityName('Entities\?Howto?');
//		$this->setEm(store::getEm());
		$this->setTemplateFactory(store::getTemplateFactory());
		$this->setKarbFormTplName('templatekarbform.tpl');
		$this->setKarbTplName('templatekarb.tpl');
		$this->setListBodyRowTplName('templatelista_tbody_tr.tpl');
		$this->setListBodyRowVarName('_egyed');
		$theme=$store::getConfigValue('theme');
		$this->fajlok=array(
			array('id'=>'fejlecreklam','caption'=>'Fejléc reklámcsík','file'=>'tpl/main'.$theme.'/headerfirstrow.tpl'),
			array('id'=>'lablec','caption'=>'Lábléc sablon','file'=>'tpl/main/'.$theme.'/footer.tpl'),
			array('id'=>'nincstalalat','caption'=>'Nincs keresési találat','file'=>'tpl/main/'.$theme.'/nincstalalat.tpl')
		);
		parent::__construct($generalDataLoader,$actionName,$commandString);
	}

	protected function getFileById($id) {
		$obj=array();
		foreach($this->fajlok as $fajl) {
			if ($id==$fajl['id']) {
				$filename=$fajl['file'];
				$obj['szoveg']=file_get_contents($filename);
				$obj['id']=$id;
				$obj['caption']=$fajl['caption'];
			}
		}
		return $obj;
	}

	protected function loadFileData() {
		$this->filedata=array();
		foreach($this->fajlok as $fajl) {
			$filename=$fajl['file'];
			$obj=array();
			$obj['id']=$fajl['id'];
			$obj['caption']=$fajl['caption'];
			$obj['szoveg']=file_get_contents($filename);
			$obj['file']=$fajl['file'];
			$this->filedata[]=$obj;
		}
	}

	protected function saveData() {
		$parancs=$this->getParam($this->operationName,'');
		$id=$this->getParam($this->idName,0);
		$szoveg=$this->getStringParam('szoveg');
		$obj=array();
		foreach($this->fajlok as $fajl) {
			if ($id==$fajl['id']) {
				$filename=$fajl['file'];
				switch ($parancs) {
					case $this->editOperation:
						file_put_contents($filename,$szoveg);
						break;
				}
			}
		}
		return array('id'=>$id,'obj'=>$obj,'operation'=>$parancs);
	}

	protected function save() {
		$ret=$this->saveData();
		switch ($ret['operation']) {
			case $this->editOperation:
				echo json_encode($this->getListBodyRow($ret['obj'],$ret['operation']));
				break;
		}
	}

	protected function loadVars($t) {
		$x=array();
		if ($t) {
			$x['id']=$t['id'];
			$x['caption']=$t['caption'];
			$x['szoveg']=$t['szoveg'];
		}
		else {
			$x['id']='';
			$x['caption']='';
			$x['szoveg']='';
		}
		return $x;
	}

	protected function setFields($obj) {
		$obj['id']=$this->getStringParam('id');
		$obj['szoveg']=$this->getStringParam('szoveg');
		return $obj;
	}

	protected function getlistbody() {
		$view=$this->createView('templatelista_tbody.tpl');
		$this->loadFileData();
		echo json_encode($this->loadDataToView($this->filedata,'egyedlista',$view));
	}

	protected function viewselect() {
		$view=$this->createView('templatelista.tpl');

		$view->setVar('pagetitle',t('Sablonok'));
		$view->setVar('controllerscript','templatelista.js');
		$view->printTemplateResult();
	}

	protected function viewlist() {
		$view=$this->createView('templatelista.tpl');

		$view->setVar('pagetitle',t('Sablonok'));
		$view->setVar('controllerscript','templatelista.js');
		$view->setVar('orderselect','');
		$view->setVar('batchesselect','');
		$view->printTemplateResult();
	}

	protected function _getkarb($id,$oper,$tplname) {
		$view=$this->createView($tplname);

		$view->setVar('pagetitle',t('Sablonok'));
		$view->setVar('controllerscript','templatekarb.js');
		$view->setVar('formaction','/admin/template/save');
		$view->setVar('oper',$oper);
		$record=$this->getFileById($id);
		$view->setVar('egyed',$this->loadVars($record));
		return $view->getTemplateResult();
	}
}