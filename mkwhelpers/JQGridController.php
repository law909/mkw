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

	protected function getOrderArray($params) {
		// TODO SQLINJECTION
		$order=array();
		$order[$params->getParam('sidx','nev')]=$params->getParam('sord','ASC');
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

	public function save($params) {
		$parancs=$params->getRequestParam($this->operationName,'');
		$id=$params->getRequestParam($this->idName,0);
		switch ($parancs){
			case $this->addOperation:
				$cl=$this->entityName;
				$obj=new $cl();
				$this->em->persist($this->setFields($obj,$params));
				$this->em->flush();
				break;
			case $this->editOperation:
				$obj=$this->repo->find($id);
				$this->em->persist($this->setFields($obj,$params));
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