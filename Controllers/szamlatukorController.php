<?php
namespace Controllers;
use mkw\store;

class szamlatukorController extends \mkwhelpers\Controller {

	private $repo;

	public function __construct($params) {
		$this->entityName='Entities\Szamlatukor';
		parent::__construct($params);
	}

	private function loadVars($t) {
		$x=array();
		if ($t) {
			$x['id']=$t->getId();
			$x['nev']=$t->getNev();
			$x['merleg']=$t->getMerleg();
			$x['analitikus']=$t->getAnalitikus();
			$x['afanev']=$t->getAfaNev();
		}
		else {
			$x['id']=0;
			$x['nev']='';
			$x['merleg']=false;
			$x['analitikus']=false;
			$x['afanev']='';
		}
		return $x;
	}

	private function setFields($obj) {
		$obj->setId($this->params->getStringRequestParam('id'));
		$obj->setNev($this->params->getStringRequestParam('nev'));
		$obj->setMerleg($this->params->getBoolRequestParam('merleg'));
		$obj->setAnalitikus($this->params->getBoolRequestParam('analitikus'));
		$afa=store::getEm()->getRepository('Entities\Afa')->find($this->params->getIntRequestParam('afa'));
		if ($afa) {
			$obj->setAfa($afa);
		}
		return $obj;
	}

	public function jsonlist() {
		$order=array();
		$filter=array();
		$res->page=1;
		$res->total=1;
		$res->records=1;
		$i=0;
		$rec=$this->repo->getLeafs();
		$levelek=array();
		foreach($rec as $sor) {
			$levelek[$sor->getId()]=$sor->getId();
		}
		$node=$this->params->getIntRequestParam('nodeid');
		$n_lvl=$this->params->getIntRequestParam('n_level');
		if($node >0) {
			$where='p.id='.$node;
			$n_lvl=$n_lvl+1;
		}
		else {
			$where='p.id IS NULL';
		}
		$rec=$this->repo->getForGrid($where);
		foreach($rec as $sor) {
			$res->rows[$i]['id']=$sor->getId();
			$res->rows[$i]['cell']=
				array($sor->getId(),
					$sor->getNev(),
					$sor->getAfaNev(),
					$n_lvl,
					(!$sor->getParent()?'NULL':$sor->getParent()->getId()),
					($sor->getId()==$levelek[$sor->getId()]?'true':'false'),
					'false');
			$i++;
		}
		echo json_encode($res);
	}

	public function getlistbody() {
		$view=$this->createView('szamlatukorlista_tbody.tpl');
		$this->generalDataLoader->loadData($view);

		$orderno=$this->params->getIntRequestParam('order',1);
		$orderdir=$this->params->getStringRequestParam('orderdir','asc');
		$orderarr=$this->repo->getOrder($orderno,$orderdir);

		$filter=array();
		$filter['fields'][]='nev';
		$filter['values'][]=$this->params->getStringRequestParam('nevfilter');

		$elemcount=$this->repo->getCount($filter);
		$elemperpage=$this->getIntRequestParam('elemperpage',30);
		if ($elemperpage==0) {
			$elemperpage=30;
		}
		$pageno=min($this->getIntRequestParam('pageno',1),ceil($elemcount/$elemperpage));
		if ($pageno==0) {
			$pageno=1;
		}
		$offset=$pageno*$elemperpage-$elemperpage;
		$elemperpage=min($elemperpage,$elemcount);

		$partner=$this->repo->getWithJoins($filter,$orderarr,$offset,$elemperpage);

		$vl=array();
		foreach($partner as $t) {
			$vl[]=$this->loadVars($t);
		}
		$view->setVar('szamlatukorlista',$vl);
		$result=array();
		$result['html']=$view->getTemplateResult();
		$result['firstelemno']=$offset+1;
		$result['lastelemno']=$offset+$elemperpage;
		$result['elemperpage']=$elemperpage;
		$result['pageno']=$pageno;
		$result['pagecount']=ceil($elemcount/$elemperpage);
		$result['elemcount']=$elemcount;
		echo json_encode($result);
	}

	public function viewlist() {
		$view=$this->createView('szamlatukorlista.tpl');
		$this->generalDataLoader->loadData($view);
		$view->setVar('pagetitle',t('Számlatükör'));
		$view->setVar('orderselect',$this->repo->getOrdersForTpl());
		$view->setVar('batchesselect',$this->repo->getBatchesForTpl());
		$view->printTemplateResult();
	}

	private function _getkarb($id,$oper,$tplname) {
		$view=$this->createView($tplname);
		$this->generalDataLoader->loadData($view);
		$view->setVar('pagetitle',t('Számlatükör'));
		$view->setVar('oper',$oper);

		$szamlatukor=$this->repo->findWithJoins($id);

		$view->setVar('szamlatukor',$this->loadVars($szamlatukor));
		$view->printTemplateResult();
	}

	public function getkarb() {
		echo $this->_getkarb($this->params->getIntRequestParam('id'),$this->params->getStringRequestParam('oper'),'szamlatukorkarbform.tpl');
	}

	public function viewkarb() {
		echo $this->_getkarb($this->params->getIntRequestParam('id'),$this->params->getStringRequestParam('oper'),'szamlatukorkarb.tpl');
	}

	public function save() {
		$parancs=$this->params->getStringRequestParam('oper');
		$id=$this->params->getIntRequestParam('id');
		switch ($parancs){
			case 'add':
				$cl=$this->entityName;
				$obj=new $cl();
				store::getEm()->persist($this->setFields($obj));
				store::getEm()->flush();
				$this->printlistbodyrow($obj,$parancs);
				break;
			case 'edit':
				$obj=$this->repo->find($id);
				$x=$this->setFields($obj);
				store::getEm()->persist($x);
				store::getEm()->flush();
				$this->printlistbodyrow($obj,$parancs);
				break;
			case 'del':
				$obj=$this->repo->find($id);
				store::getEm()->remove($obj);
				store::getEm()->flush();
				echo $id;
				break;
		}
	}

	private function printlistbodyrow($szamlatukor,$oper) {
		$view=$this->createView('szamlatukorlista_tbody_tr.tpl');
		$this->generalDataLoader->loadData($view);

		$vl=$this->loadVars($szamlatukor);
		$view->setVar('_szamlatukor',$vl);
		$result=array();
		$result['id']=$szamlatukor->getId();
		$result['oper']=$oper;
		$result['html']=$view->getTemplateResult();
		echo json_encode($result);
	}
}