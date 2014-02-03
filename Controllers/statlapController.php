<?php
namespace Controllers;
use mkw\store;

class statlapController extends \mkwhelpers\MattableController {

	public function __construct($params) {
		$this->setEntityName('Entities\Statlap');
		$this->setKarbFormTplName('statlapkarbform.tpl');
		$this->setKarbTplName('statlapkarb.tpl');
		$this->setListBodyRowTplName('statlaplista_tbody_tr.tpl');
		$this->setListBodyRowVarName('_egyed');
		parent::__construct($params);
	}

	protected function loadVars($t) {
		$x=array();
		if (!$t) {
			$t=new \Entities\Statlap();
			$this->getEm()->detach($t);
		}
		$x['id']=$t->getId();
		$x['oldalcim']=$t->getOldalcim();
		$x['slug']=$t->getSlug();
		$x['szoveg']=$t->getSzoveg();
		$x['seodescription']=$t->getSeodescription();
        $x['oldurl'] = $t->getOldurl();
		return $x;
	}

	protected function setFields($obj) {
		$obj->setOldalcim($this->params->getStringRequestParam('oldalcim'));
		$obj->setSzoveg($this->params->getOriginalStringRequestParam('szoveg'));
		$obj->setSeodescription($this->params->getStringRequestParam('seodescription'));
        $obj->setOldurl($this->params->getStringRequestParam('oldurl'));
		return $obj;
	}

	public function getlistbody() {
		$view=$this->createView('statlaplista_tbody.tpl');

		$filter=array();
		if (!is_null($this->params->getRequestParam('nevfilter',NULL))) {
			$filter['fields'][]='oldalcim';
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
		$view=$this->createView('statlaplista.tpl');

		$view->setVar('pagetitle',t('Statikus lapok'));
		$view->printTemplateResult();
	}

	public function viewlist() {
		$view=$this->createView('statlaplista.tpl');

		$view->setVar('pagetitle',t('Statikus lapok'));
		$view->setVar('orderselect',$this->getRepo()->getOrdersForTpl());
		$view->setVar('batchesselect',$this->getRepo()->getBatchesForTpl());
		$view->printTemplateResult();
	}

	protected function _getkarb($tplname) {
		$id=$this->params->getRequestParam('id',0);
		$oper=$this->params->getRequestParam('oper','');
		$view=$this->createView($tplname);

		$view->setVar('pagetitle',t('Statikus lap'));
		$view->setVar('formaction','/admin/statlap/save');
		$view->setVar('oper',$oper);
		$record=$this->getRepo()->find($id);
		$view->setVar('egyed',$this->loadVars($record));
		return $view->getTemplateResult();
	}

	public function getstatlap($statlap) {
		$t=array();
		$t['szoveg']=$statlap->getSzoveg();
		return $t;
	}

	public function show() {
		$com=$this->params->getStringParam('lap');
		$statlap=$this->getRepo()->findOneBySlug($com);
		if ($statlap) {
			$view=$this->getTemplateFactory()->createMainView('statlap.tpl');
			store::fillTemplate($view);
			$view->setVar('pagetitle',$statlap->getShowOldalcim());
			$view->setVar('seodescription',$statlap->getShowSeodescription());
			$view->setVar('statlap',$this->getstatlap($statlap));
			$view->printTemplateResult(true);
		}
		else {
			store::redirectTo404($com,$this->params);
		}
	}

	public function showPopup() {
		$com=$this->params->getStringParam('lap');
		$statlap=$this->getRepo()->findOneBySlug($com);
		if ($statlap) {
			$view=$this->getTemplateFactory()->createMainView('statlappopup.tpl');
			store::fillTemplate($view);
			$view->setVar('szoveg',$statlap->getSzoveg());
			$view->printTemplateResult(false);
		}
		else {
			echo '';
		}
	}

    public function redirectOldUrl() {
        $lapid = $this->params->getStringRequestParam('page');
        if ($lapid) {
            $lap = $this->getRepo()->findOneByOldurl($lapid);
            if ($lap) {
                $newlink = \mkw\Store::getRouter()->generate('showstatlap', false, array('lap' => $lap->getSlug()));
                header("HTTP/1.1 301 Moved Permanently");
                header('Location: ' . $newlink);
                return;
            }
        }
        $mc = new mainController($this->params);
        $mc->show404('HTTP/1.1 410 Gone');
    }

}