<?php
namespace Controllers;
use mkw\store;

class kosarController extends \mkwhelpers\MattableController {

	public function __construct($params) {
		$this->setEntityName('Entities\Kosar');
//		$this->setKarbFormTplName('kosarkarbform.tpl');
//		$this->setKarbTplName('kosarkarb.tpl');
		$this->setListBodyRowTplName('kosarlista_tbody_tr.tpl');
		$this->setListBodyRowVarName('_egyed');
		parent::__construct($params);
	}

	protected function loadVars($t) {
		$x=array();
		if (!$t) {
			$t=new \Entities\Kosar();
			$this->getEm()->detach($t);
		}
		$x['id']=$t->getId();
		$x['session']=$t->getSessionid();
		$x['mennyiseg']=$t->getMennyiseg();
		$x['partner']=$t->getPartnerId();
		$x['partnernev']=$t->getPartnernev();
		$x['created']=$t->getCreated();
		$term=$t->getTermek();
		if ($term) {
			$x['termek']=$term->getId();
			$x['termeknev']=$term->getNev();
			$x['termekurl']=$term->getSlug();
		}
		else {
			$x['termek']=0;
			$x['termeknev']='';
			$x['termekurl']='';
		}
		$v=array();
		$tv=$t->getTermekvaltozat();
		if ($tv) {
			$v[]=array('nev'=>$tv->getAdatTipus1Nev(),'ertek'=>$tv->getErtek1());
			$v[]=array('nev'=>$tv->getAdatTipus2Nev(),'ertek'=>$tv->getErtek2());
		}
		$x['valtozat']=$v;
		return $x;
	}

	protected function setFields($obj) {
		$ck=store::getEm()->getRepository('Entities\Partner')->find($this->params->getIntRequestParam('partner'));
		if ($ck) {
			$obj->setPartner($ck);
		}
		$ck=store::getEm()->getRepository('Entities\Termek')->find($this->params->getIntRequestParam('termek'));
		if ($ck) {
			$obj->setTermek($ck);
		}
		$obj->setMennyiseg($this->params->getNumRequestParam('mennyiseg'));
		return $obj;
	}

	public function getlistbody() {
		$view=$this->createView('kosarlista_tbody.tpl');

		$filter=array();
		if (!is_null($this->params->getRequestParam('nevfilter',NULL))) {
			$filter['fields'][]=array('_xx.sessionid','p.nev','t.nev');
			$filter['clauses'][]='';
			$filter['values'][]=$this->params->getStringRequestParam('nevfilter');
		}

		$this->initPager(
			$this->getRepo()->getCount($filter),$this->params);

		$egyedek=$this->getRepo()->getWithJoins(
			$filter,
			$this->getOrderArray(),
			$this->getPager()->getOffset(),
			$this->getPager()->getElemPerPage());

		echo json_encode($this->loadDataToView($egyedek,'egyedlista',$view));
	}

	public function viewselect() {
		$view=$this->createView('kosarlista.tpl');

		$view->setVar('pagetitle',t('Kosár'));
		$view->printTemplateResult();
	}

	public function viewlist() {
		$view=$this->createView('kosarlista.tpl');

		$view->setVar('pagetitle',t('Kosár'));
		$view->setVar('orderselect',$this->getRepo()->getOrdersForTpl());
		$view->setVar('batchesselect',$this->getRepo()->getBatchesForTpl());
		$view->printTemplateResult();
	}

	protected function _getkarb($tplname) {
		$id=$this->params->getRequestParam('id',0);
		$oper=$this->params->getRequestParam('oper','');
		$view=$this->createView($tplname);

		$view->setVar('pagetitle',t('Kosár'));
		$view->setVar('formaction','/admin/kosar/save');
		$view->setVar('oper',$oper);
		$record=$this->getRepo()->findWithJoins($id);
		$view->setVar('egyed',$this->loadVars($record));
		return $view->getTemplateResult();
	}

	public function getMiniData() {
		$m=$this->getRepo()->getMiniDataBySessionId(\Zend_Session::getId());
		return array('termekdb'=>$m[0][1],'osszeg'=>$m[0][2]*1);
	}

	public function get() {
		$v=$this->getTemplateFactory()->createMainView('kosar.tpl');
		store::fillTemplate($v);
		$sorok=$this->getRepo()->getDataBySessionId(\Zend_Session::getId());
		$s=array();
		foreach($sorok as $sor) {
			$s[]=$sor->toLista();
		}
		$v->setVar('tetellista',$s);
		$v->printTemplateResult();
	}

	public function add() {
		$termek=store::getEm()->getRepository('Entities\Termek')->find($this->params->getIntRequestParam('id'));
		$vid=null;
		switch ($this->params->getIntRequestParam('jax',0)) {
			case 2:
				$vid=$this->params->getIntRequestParam('vid',null);
				$termekvaltozat=store::getEm()->getRepository('Entities\TermekValtozat')->find($vid);
				break;
			case 3:
				$tipusok=$this->params->getArrayRequestParam('tip');
				$ertekek=$this->params->getArrayRequestParam('val');
				$termekvaltozat=store::getEm()->getRepository('Entities\TermekValtozat')->getByProperties($termek->getId(),$tipusok,$ertekek);
				$vid=$termekvaltozat->getId();
				break;
			default:
				$termekvaltozat=null;
				break;
		}

		if ($termek) {
			$termekid=$termek->getId();
			$sessionid=\Zend_Session::getId();
			$pc=new partnerController($this->params);
			$partnerid=null;
			$partner=$pc->getLoggedInUser();
			if ($partner) {
				$partnerid=$partner->getId();
			}
			$valutanemid=store::getParameter(\mkw\consts::Valutanem);
			$valutanem=$this->getEm()->getRepository('Entities\Valutanem')->find($valutanemid);

			$k=$this->getRepo()->getTetelsor($sessionid,$partnerid,$termekid,$vid,$valutanemid);
			if ($k) {
				$k->novelMennyiseg();
			}
			else {
				$k=new \Entities\Kosar();
				$k->setTermek($termek);
				if ($vid) {
					$k->setTermekvaltozat($termekvaltozat);
				}
				$k->setSessionid($sessionid);
				$k->setPartner($partner);
				$k->setValutanem($valutanem);
				$k->setBruttoegysar($termek->getBruttoAr($termekvaltozat));
				$k->setMennyiseg(1);
			}
			$this->getEm()->persist($k);
			$this->getEm()->flush();
			if ($this->params->getIntRequestParam('jax',0)>0) {
				$v=$this->getTemplateFactory()->createMainView('minikosar.tpl');
				$v->setVar('kosar',$this->getMiniData());
				$v->printTemplateResult();
			}
			else {
				if (store::getMainSession()->prevuri) {
					Header('Location: '.store::getMainSession()->prevuri);
				}
				else {
					Header('Location: /');
				}
			}
		}
	}

	public function del() {
		$id=$this->params->getIntRequestParam('id');
		$sessionid=\Zend_Session::getId();
		$sor=$this->getRepo()->find($id);
		if ($sor&&$sor->getSessionid()==$sessionid) {
			$this->getEm()->remove($sor);
			$this->getEm()->flush();
			if ($this->params->getIntRequestParam('jax',0)>0) {
//				$v=$this->getTemplateFactory()->createMainView('minikosar.tpl');
//				$v->setVar('kosar',$this->getMiniData());
//				$v->printTemplateResult();
				echo 'ok';
			}
			else {
				if (store::getMainSession()->prevuri) {
					Header('Location: '.store::getRouter()->generate('kosarget'));
				}
				else {
					Header('Location: '.store::getRouter()->generate('kosarget'));
				}
			}
		}
	}

	public function edit() {
		$id=$this->params->getIntRequestParam('id');
		$menny=$this->params->getNumRequestParam('mennyiseg');
		$sessionid=\Zend_Session::getId();
		$sor=$this->getRepo()->find($id);
		if ($sor&&$sor->getSessionid()==$sessionid) {
			$sor->setMennyiseg($menny);
			$this->getEm()->persist($sor);
			$this->getEm()->flush();
			if ($this->params->getIntRequestParam('jax',0)>0) {
//				$v=$this->getTemplateFactory()->createMainView('minikosar.tpl');
//				$v->setVar('kosar',$this->getMiniData());
//				$v->printTemplateResult();
				echo 'ok';
			}
			else {
				if (store::getMainSession()->prevuri) {
					Header('Location: '.store::getRouter()->generate('kosarget'));
				}
				else {
					Header('Location: '.store::getRouter()->generate('kosarget'));
				}
			}
		}
	}

	public function replaceSessionIdAndAddPartner($oldid,$partner) {
		$filter=array();
		$filter['fields'][]='sessionid';
		$filter['clauses'][]='=';
		$filter['values'][]=$oldid;
		$sorok=$this->getRepo()->getAll($filter,array());
		foreach($sorok as $sor) {
			$sor->setSessionid(\Zend_Session::getId());
			$sor->setPartner($partner);
			$this->getEm()->persist($sor);
		}
		$this->getEm()->flush();
	}

	public function addSessionIdByPartner($partner) {
		$filter=array();
		$filter['fields'][]='partner';
		$filter['clauses'][]='=';
		$filter['values'][]=$partner;
		$sorok=$this->getRepo()->getAll($filter,array());
		foreach($sorok as $sor) {
			$sor->setSessionid(\Zend_Session::getId());
			$this->getEm()->persist($sor);
		}
		$this->getEm()->flush();
	}

	public function removeSessionId($id) {
		$filter=array();
		$filter['fields'][]='sessionid';
		$filter['clauses'][]='=';
		$filter['values'][]=$id;
		$sorok=$this->getRepo()->getAll($filter,array());
		foreach($sorok as $sor) {
			$sor->setSessionid(null);
			$this->getEm()->persist($sor);
		}
		$this->getEm()->flush();
	}
}