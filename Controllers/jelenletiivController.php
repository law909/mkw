<?php
namespace Controllers;
use Entities\DolgozoRepository;

use Entities\Jelenletiiv;

use matt, matt\Exceptions, mkw\store;

class jelenletiivController extends matt\MattableController {

	public function __construct($generalDataLoader,$actionName=null,$commandString=null) {
		$this->setEntityName('Entities\Jelenletiiv');
		$this->setEm(store::getEm());
		$this->setTemplateFactory(store::getTemplateFactory());
		$this->setKarbFormTplName('jelenletiivkarbform.tpl');
		$this->setKarbTplName('jelenletiivkarb.tpl');
		$this->setListBodyRowTplName('jelenletiivlista_tbody_tr.tpl');
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
		if ($t) {
			$x['id']=$t->getId();
			$x['datum']=$t->getDatum();
			$x['datumstr']=$t->getDatumStr();
			$x['munkaido']=$t->getMunkaido();
			$x['dolgozo']=$t->getDolgozoId();
			$x['dolgozonev']=$t->getDolgozoNev();
			$x['jelenlettipus']=$t->getJelenlettipusId();
			$x['jelenlettipusnev']=$t->getJelenlettipusNev();
		}
		else {
			$x['id']=0;
			$x['datum']=new \DateTime();
			$x['datumstr']='';
			$x['munkaido']=0;
			$x['dolgozo']=0;
			$x['dolgozonev']='';
			$x['jelenlettipus']=0;
			$x['jelenlettipusnev']='';
		}
		return $x;
	}

	protected function setFields($obj) {
		try {
			$obj->setDatum($this->getDateParam('datum'));
			$obj->setMunkaido($this->getIntParam('munkaido'));
			$ck=store::getEm()->getRepository('Entities\Dolgozo')->find($this->getIntParam('dolgozo',0));
			if ($ck) {
				$obj->setDolgozo($ck);
			}
			$ck=store::getEm()->getRepository('Entities\Jelenlettipus')->find($this->getIntParam('jelenlettipus',0));
			if ($ck) {
				$obj->setJelenlettipus($ck);
			}
		}
		catch (matt\Exceptions\WrongValueTypeException $e){
		}
		return $obj;
	}

	protected function getlistbody() {
		$view=$this->createView('jelenletiivlista_tbody.tpl');

		$filter=array();

		if (!is_null($this->getParam('tolfilter',NULL))) {
			$filter['fields'][]='datum';
			$filter['clauses'][]='>=';
			$filter['values'][]=Store::convDate(($this->getStringParam('tolfilter','')));
		}
		if (!is_null($this->getParam('igfilter',NULL))) {
			$filter['fields'][]='datum';
			$filter['clauses'][]='<=';
			$filter['values'][]=Store::convDate(($this->getStringParam('igfilter','')));
		}
		$fv=$this->getIntParam('dolgozofilter');
		if ($fv>0) {
			$filter['fields'][]='d.id';
			$filter['values'][]=$fv;
		}
		$fv=$this->getIntParam('jelenlettipusfilter');
		if ($fv>0) {
			$filter['fields'][]='j.id';
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

		echo json_encode($this->loadDataToView($egyedek,'egyedlista',$view));
	}

	protected function viewselect() {
		$view=$this->createView('jelenletiivlista.tpl');

		$view->setVar('pagetitle',t('Jelenléti ívek'));
		$view->setVar('controllerscript','jelenletiivlista.js');
		$view->printTemplateResult();
	}

	protected function viewlist() {
		$view=$this->createView('jelenletiivlista.tpl');

		$view->setVar('pagetitle',t('Jelenléti ívek'));
		$view->setVar('controllerscript','jelenletiivlista.js');
		$view->setVar('orderselect',$this->getRepo()->getOrdersForTpl());
		$view->setVar('batchesselect',$this->getRepo()->getBatchesForTpl());
		$dolgozo=new dolgozoController($this->generalDataLoader);
		$view->setVar('dolgozolist',$dolgozo->getSelectList(0));
		$jt=new jelenlettipusController($this->generalDataLoader);
		$view->setVar('jelenlettipuslist',$jt->getSelectList(0));
		$view->printTemplateResult();
	}

	protected function _getkarb($id,$oper,$tplname) {
		$view=$this->createView($tplname);

		$view->setVar('pagetitle',t('Jelenléti ív'));
		$view->setVar('controllerscript','jelenletiivkarb.js');
		$view->setVar('formaction','/admin/jelenletiiv/save');
		$view->setVar('oper',$oper);
		$record=$this->getRepo()->findWithJoins($id);
		$view->setVar('egyed',$this->loadVars($record));
		$dolgozo=new dolgozoController($this->generalDataLoader);
		$view->setVar('dolgozolist',$dolgozo->getSelectList(($record?$record->getDolgozoId():0)));
		$jt=new jelenlettipusController($this->generalDataLoader);
		$view->setVar('jelenlettipuslist',$jt->getSelectList(($record?$record->getJelenlettipusId():0)));
		return $view->getTemplateResult();
	}

	public function generatenapi() {
		$nap=$this->getStringParam('datum','');
		$jt=store::getEm()->getRepository('Entities\Jelenlettipus')->find($this->getIntParam('jt',0));
		$egyedek=store::getEm()->getRepository('Entities\Dolgozo')->getWithJoins(array(),array());
		foreach($egyedek as $egyed) {
			if ($this->getRepo()->getCount('(_xx.datum=\''.$nap.'\') AND (d.id='.$egyed->getId().') AND (j.id='.$jt->getId().')')==0) {
				$jelen=new Jelenletiiv();
				$jelen->setDatum($nap);
				$jelen->setDolgozo($egyed);
				$jelen->setJelenlettipus($jt);
				$jelen->setMunkaido(8);
				$this->getEm()->persist($jelen);
			}
		}
		$this->getEm()->flush();
	}
}