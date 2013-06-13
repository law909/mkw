<?php
namespace Controllers;
use mkw\store;

class kosarController extends \mkwhelpers\MattableController {

	public function __construct($generalDataLoader,$actionName=null,$commandString=null) {
		$this->setEntityName('Entities\Kosar');
		$this->setEm(store::getEm());
		$this->setTemplateFactory(store::getTemplateFactory());
//		$this->setKarbFormTplName('kosarkarbform.tpl');
//		$this->setKarbTplName('kosarkarb.tpl');
		$this->setListBodyRowTplName('kosarlista_tbody_tr.tpl');
		$this->setListBodyRowVarName('_egyed');
		parent::__construct($generalDataLoader,$actionName,$commandString);
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
		$ck=store::getEm()->getRepository('Entities\Partner')->find($this->getIntParam('partner'));
		if ($ck) {
			$obj->setPartner($ck);
		}
		$ck=store::getEm()->getRepository('Entities\Termek')->find($this->getIntParam('termek'));
		if ($ck) {
			$obj->setTermek($ck);
		}
		$obj->setMennyiseg($this->getNumParam('mennyiseg'));
		return $obj;
	}

	protected function getlistbody() {
		$view=$this->createView('kosarlista_tbody.tpl');

		$filter=array();
		if (!is_null($this->getParam('nevfilter',NULL))) {
			$filter['fields'][]=array('_xx.sessionid','p.nev','t.nev');
			$filter['clauses'][]='';
			$filter['values'][]=$this->getStringParam('nevfilter');

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
		$view=$this->createView('kosarlista.tpl');

		$view->setVar('pagetitle',t('Kosár'));
		$view->setVar('controllerscript','kosarlista.js');
		$view->printTemplateResult();
	}

	protected function viewlist() {
		$view=$this->createView('kosarlista.tpl');

		$view->setVar('pagetitle',t('Kosár'));
		$view->setVar('controllerscript','kosarlista.js');
		$view->setVar('orderselect',$this->getRepo()->getOrdersForTpl());
		$view->setVar('batchesselect',$this->getRepo()->getBatchesForTpl());
		$view->printTemplateResult();
	}

	protected function _getkarb($id,$oper,$tplname) {
		$view=$this->createView($tplname);

		$view->setVar('pagetitle',t('Kosár'));
		$view->setVar('controllerscript','kosarkarb.js');
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
		$termek=store::getEm()->getRepository('Entities\Termek')->find($this->getIntParam('id'));
		$vid=null;
		switch ($this->getIntParam('jax',0)) {
			case 2:
				$vid=$this->getIntParam('vid',null);
				$termekvaltozat=store::getEm()->getRepository('Entities\TermekValtozat')->find($vid);
				break;
			case 3:
				$tipusok=$this->getArrayParam('tip');
				$ertekek=$this->getArrayParam('val');
				$termekvaltozat=store::getEm()->getRepository('Entities\TermekValtozat')->getByProperties($termek->getId(),$tipusok,$ertekek);
				$vid=$termekvaltozat->getId();
				break;
		}

		if ($termek) {
			$sessionid=\Zend_Session::getId();
			$partnerid=null;
			$termekid=$termek->getId();
			$valutanemid=store::getParameter('valutanem');
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
				$k->setValutanem($valutanem);
				$k->setBruttoegysar($termek->getBruttoAr($termekvaltozat));
				$k->setMennyiseg(1);
			}
			$this->getEm()->persist($k);
			$this->getEm()->flush();
			if ($this->getIntParam('jax',0)>0) {
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
		$id=$this->getIntParam('id');
		$sessionid=\Zend_Session::getId();
		$sor=$this->getRepo()->find($id);
		if ($sor&&$sor->getSessionid()==$sessionid) {
			$this->getEm()->remove($sor);
			$this->getEm()->flush();
			if ($this->getIntParam('jax',0)>0) {
//				$v=$this->getTemplateFactory()->createMainView('minikosar.tpl');
//				$v->setVar('kosar',$this->getMiniData());
//				$v->printTemplateResult();
				echo 'ok';
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

	public function edit() {
		$id=$this->getIntParam('id');
		$menny=$this->getNumParam('mennyiseg');
		$sessionid=\Zend_Session::getId();
		$sor=$this->getRepo()->find($id);
		if ($sor&&$sor->getSessionid()==$sessionid) {
			$sor->setMennyiseg($menny);
			$this->getEm()->persist($sor);
			$this->getEm()->flush();
			if ($this->getIntParam('jax',0)>0) {
//				$v=$this->getTemplateFactory()->createMainView('minikosar.tpl');
//				$v->setVar('kosar',$this->getMiniData());
//				$v->printTemplateResult();
				echo 'ok';
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