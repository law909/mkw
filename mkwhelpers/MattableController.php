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

	public function __construct() {
		parent::__construct();
		$this->setupRepo();
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

	public function setEm($em) {
		$this->em=$em;
	}

	public function getRepo() {
		return $this->repo;
	}

	protected function setupRepo() {
		if ($this->entityName) {
			$this->repo=$this->em->getRepository($this->entityName);
		}
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

	protected function saveData() {
		$parancs=$this->getParam($this->operationName,'');
		$id=$this->getParam($this->idName,0);
		switch ($parancs){
			case $this->addOperation:
				$cl=$this->entityName;
				$obj=new $cl();
				$this->em->persist($this->setFields($obj));
				$this->em->flush();
				break;
			case $this->editOperation:
				$obj=$this->repo->find($id);
				$this->em->persist($this->setFields($obj));
				$this->em->flush();
				break;
			case $this->delOperation:
				$obj=$this->repo->find($id);
				$this->em->remove($obj);
				$this->em->flush();
				break;
		}
		return array('id'=>$id,'obj'=>$obj,'operation'=>$parancs);
	}

	protected function save() {
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
		return $this->repo->getOrder($this->getParam('order',1),$this->getParam('orderdir','asc'));
	}

	protected function initPager($elemcount,$elemperpage,$pageno) {
		$this->pager=new PagerCalc($elemcount,$elemperpage,$pageno);
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
		$view=$this->getTemplateFactory()->createView($this->listBodyRowTplName);
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

	protected function getkarb() {
		echo $this->_getkarb($this->getParam('id',0),$this->getParam('oper',''),$this->karbFormTplName);
	}

	protected function viewkarb() {
		echo $this->_getkarb($this->getParam('id',0),$this->getParam('oper',''),$this->karbTplName);
	}
}