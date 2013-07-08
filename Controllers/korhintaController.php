<?php
namespace Controllers;
use mkw\store;

class korhintaController extends \mkwhelpers\MattableController {

	public function __construct($params) {
		$this->setEntityName('Entities\Korhinta');
		$this->setKarbFormTplName('korhintakarbform.tpl');
		$this->setKarbTplName('korhintakarb.tpl');
		$this->setListBodyRowTplName('korhintalista_tbody_tr.tpl');
		$this->setListBodyRowVarName('_egyed');
		parent::__construct($params);
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
		$x['kepurlsmall']=$t->getKepurlSmall();
		$x['kepleiras']=$t->getKepleiras();
		$x['lathato']=$t->getLathato();
		$x['sorrend']=$t->getSorrend();

		return $x;
	}

	protected function setFields($obj) {
		$obj->setNev($this->params->getStringRequestParam('nev'));
		$obj->setUrl($this->params->getStringRequestParam('url'));
		$obj->setSzoveg($this->params->getStringRequestParam('szoveg'));
		$obj->setKepUrl($this->params->getStringRequestParam('kepurl'));
		$obj->setKepleiras($this->params->getStringRequestParam('kepleiras',''));
		$obj->setLathato($this->params->getBoolRequestParam('lathato',true));
		$obj->setSorrend($this->params->getIntRequestParam('sorrend',0));
		return $obj;
	}

	public function getlistbody() {
		$view=$this->createView('korhintalista_tbody.tpl');

		$filter=array();
		if (!is_null($this->params->getRequestParam('nevfilter',NULL))) {
			$filter['fields'][]='nev';
			$filter['values'][]=$this->params->getStringRequestParam('nevfilter');
		}

		$this->initPager($this->getRepo()->getCount($filter));

		$egyedek=$this->getRepo()->getAll(
			$filter,
			$this->getOrderArray(),
			$this->getPager()->getOffset(),
			$this->getPager()->getElemPerPage());

		echo json_encode($this->loadDataToView($egyedek,'egyedlista',$view));
	}

	public function viewselect() {
		$view=$this->createView('korhintalista.tpl');

		$view->setVar('pagetitle',t('Körhinta'));
		$view->printTemplateResult();
	}

	public function viewlist() {
		$view=$this->createView('korhintalista.tpl');

		$view->setVar('pagetitle',t('Körhinta'));
		$view->setVar('orderselect',$this->getRepo()->getOrdersForTpl());
		$view->setVar('batchesselect',$this->getRepo()->getBatchesForTpl());
		$view->printTemplateResult();
	}

	protected function _getkarb($tplname) {
		$id=$this->params->getRequestParam('id',0);
		$oper=$this->params->getRequestParam('oper','');
		$view=$this->createView($tplname);

		$view->setVar('pagetitle',t('Körhinta'));
		$view->setVar('formaction','/admin/korhinta/save');
		$view->setVar('oper',$oper);
		$record=$this->getRepo()->find($id);
		$view->setVar('egyed',$this->loadVars($record));
		return $view->getTemplateResult();
	}

	public function delpicture() {
		$fa=$this->getRepo()->find($this->params->getIntRequestParam('id'));
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

	public function setflag() {
		$id=$this->params->getIntRequestParam('id');
		$kibe=$this->params->getBoolRequestParam('kibe');
		$flag=$this->params->getStringRequestParam('flag');
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