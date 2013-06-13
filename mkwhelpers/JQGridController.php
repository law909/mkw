<?php
namespace mkwhelpers;

class JQGridController extends Controller {

	protected $operationName='oper';
	protected $idName='id';
	protected $addOperation='add';
	protected $editOperation='edit';
	protected $delOperation='del';

	private $entityName='';
	private $repo;
	private $em;
	private $pager;

	public function __construct($generalDataLoader,$actionName=null,$commandString=null) {
		parent::__construct($generalDataLoader,$actionName,$commandString);
		$this->setupRepo();
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
			throw new \mkwhelpers\Exceptions\UnknownMethodException('"'.__CLASS__.'->'.$methodname.'" does not exist.');
		}
	}

	public function getEntityName() {
		return $this->entityName;
	}

	public  function setEntityName($ename) {
		$this->entityName=$ename;
	}

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
		$this->repo=$this->em->getRepository($this->entityName);
	}

	protected function getOrderArray() {
		// TODO SQLINJECTION
		$order=array();
		$order[$this->getParam('sidx','nev')]=$this->getParam('sord','ASC');
		return $order;
	}

	protected function loadDataToView($data) {
		$res->page=1;
		$res->total=1;
		$res->records=count($data);
		$i=0;
		foreach($data as $sor) {
			$res->rows[$i]['id']=$sor->getId();
			$res->rows[$i]['cell']=$this->loadCells($sor);
			$i++;
		}
		return $res;
	}

	protected function save() {
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
	}
}