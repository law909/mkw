<?php
namespace mkwhelpers;

class MattableController extends Controller {

	protected $operationName='oper';
	protected $idName='id';
	protected $addOperation='add';
	protected $editOperation='edit';
	protected $delOperation='del';

	private $entityName='';
	private $repo;
	private $em;
	private $pager;
	private $listBodyRowTplName;
	private $listBodyRowVarName;
	private $karbFormTplName;
	private $karbTplName;

	public function __construct($params) {
		parent::__construct($params);
		$this->setTemplateFactory(\mkw\Store::getTemplateFactory());
		$this->em=\mkw\Store::getEm();
		if ($this->entityName) {
			$this->repo=$this->em->getRepository($this->entityName);
		}
	}

	public function getEntityName() {
		return $this->entityName;
	}

	public  function setEntityName($ename) {
		$this->entityName=$ename;
	}

	/**
	 *
	 * @return EntityManager
	 */
	public function getEm() {
		return $this->em;
	}

	public function getRepo() {
		return $this->repo;
	}

	protected function getPager() {
		return $this->pager;
	}

	public function getListBodyRowTplName() {
		return $this->listBodyRowTplName;
	}

	public  function setListBodyRowTplName($name) {
		$this->listBodyRowTplName=$name;
	}

	public function getListBodyRowVarName() {
		return $this->listBodyRowVarName;
	}

	public  function setListBodyRowVarName($name) {
		$this->listBodyRowVarName=$name;
	}

	public function getKarbFormTplName() {
		return $this->karbFormTplName;
	}

	public  function setKarbFormTplName($name) {
		$this->karbFormTplName=$name;
	}

	public function getKarbTplName() {
		return $this->karbTplName;
	}

	public  function setKarbTplName($name) {
		$this->karbTplName=$name;
	}

	protected function setVars($view) {

	}

	protected function beforeRemove($o) {

	}

    protected function afterSave($o) {

    }

	protected function saveData() {
		$parancs=$this->params->getRequestParam($this->operationName,'');
		$id=$this->params->getRequestParam($this->idName,0);
		switch ($parancs){
			case $this->addOperation:
				$cl=$this->entityName;
				$obj=new $cl();
				$this->em->persist($this->setFields($obj));
				$this->em->flush();
                $this->afterSave($obj);
				break;
			case $this->editOperation:
				$obj=$this->repo->find($id);
				$this->em->persist($this->setFields($obj));
				$this->em->flush();
                $this->afterSave($obj);
				break;
			case $this->delOperation:
				$obj=$this->repo->find($id);
				$this->beforeRemove($obj);
				$this->em->remove($obj);
				$this->em->flush();
                $this->afterSave($obj);
				break;
		}
		return array('id'=>$id,'obj'=>$obj,'operation'=>$parancs);
	}

	public function save() {
		$ret=$this->saveData();
		switch ($ret['operation']) {
			case $this->addOperation:
			case $this->editOperation:
				echo json_encode($this->getListBodyRow($ret['obj'],$ret['operation']));
				break;
			case $this->delOperation:
				echo $ret['id'];
		}
	}

	protected function getOrderArray() {
		//TODO SQLINJECTION
		return $this->repo->getOrder($this->params->getRequestParam('order',1),$this->params->getRequestParam('orderdir','asc'));
	}

	protected function initPager($elemcount) {
		$this->pager=new PagerCalc($elemcount,$this->params->getIntRequestParam('elemperpage',30),$this->params->getIntRequestParam('pageno',1));
	}

	protected function loadPagerValues($ide) {
		if ($this->pager) {
			return $this->pager->loadValues($ide);
		}
		return $ide;
	}

	protected function loadDataToView($data,$datavarname='',$view) {
		$vl=array();
		foreach($data as $t) {
			$vl[]=$this->loadVars($t);
		}
		$view->setVar($datavarname,$vl);
		$result=array();
		$result['html']=$view->getTemplateResult();
		$result=$this->loadPagerValues($result);
		return $result;
	}

	protected function getListBodyRow($obj,$oper) {
		$view=$this->createView($this->listBodyRowTplName);
		$this->setVars($view);
		$vl=$this->loadVars($obj);
		$view->setVar($this->listBodyRowVarName,$vl);
		$result=array();
		if (is_object($obj)) {
			$result['id']=$obj->getId();
		}
		else {
			$result['id']=$obj['id'];
		}
		$result['oper']=$oper;
		$result['html']=$view->getTemplateResult();
		return $result;
	}

	public function getkarb() {
		echo $this->_getkarb($this->karbFormTplName);
	}

	public function viewkarb() {
		echo $this->_getkarb($this->karbTplName);
	}
}