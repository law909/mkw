<?php
namespace Controllers;
use mkw\store;

class templateController extends \mkwhelpers\MattableController {

	private $fajlok;
	private $filedata;

	public function __construct($params) {
//		$this->setEntityName('Entities\?Howto?');
		$this->setKarbFormTplName('templatekarbform.tpl');
		$this->setKarbTplName('templatekarb.tpl');
		$this->setListBodyRowTplName('templatelista_tbody_tr.tpl');
		$this->setListBodyRowVarName('_egyed');
		$theme=store::getConfigValue('main.theme');
		$this->fajlok=array(
			array('id'=>'fejlecreklam','caption'=>'Fejléc reklámcsík','file'=>'tpl/main/'.$theme.'/headerfirstrow.tpl'),
			array('id'=>'lablec','caption'=>'Lábléc sablon','file'=>'tpl/main/'.$theme.'/footer.tpl'),
			array('id'=>'nincstalalat','caption'=>'Nincs keresési találat','file'=>'tpl/main/'.$theme.'/nincstalalat.tpl')
		);
		parent::__construct($params);
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
		$parancs=$this->params->getRequestParam($this->operationName,'');
		$id=$this->params->getRequestParam($this->idName,0);
		$szoveg=$this->params->getOriginalStringRequestParam('szoveg');
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

	public function save() {
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

	public function setFields($obj) {
		$obj['id']=$this->getStringParam('id');
		$obj['szoveg']=$this->getOriginalStringParam('szoveg');
		return $obj;
	}

	public function getlistbody() {
		$view=$this->createView('templatelista_tbody.tpl');
		$this->loadFileData();
		echo json_encode($this->loadDataToView($this->filedata,'egyedlista',$view));
	}

	public function viewselect() {
		$view=$this->createView('templatelista.tpl');

		$view->setVar('pagetitle',t('Sablonok'));
		$view->printTemplateResult();
	}

	public function viewlist() {
		$view=$this->createView('templatelista.tpl');

		$view->setVar('pagetitle',t('Sablonok'));
		$view->setVar('orderselect','');
		$view->setVar('batchesselect','');
		$view->printTemplateResult();
	}

	protected function _getkarb($tplname) {
		$id=$this->params->getRequestParam('id',0);
		$oper=$this->params->getRequestParam('oper','');
		$view=$this->createView($tplname);

		$view->setVar('pagetitle',t('Sablonok'));
		$view->setVar('formaction','/admin/template/save');
		$view->setVar('oper',$oper);
		$record=$this->getFileById($id);
		$view->setVar('egyed',$this->loadVars($record));
		return $view->getTemplateResult();
	}
}