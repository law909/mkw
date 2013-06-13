<?php
namespace Controllers;
use Entities\TermekKep;

use mkw\store;

class termekkepController extends \mkwhelpers\MattableController {

	public function __construct($params) {
		$this->setEntityName('Entities\TermekKep');
//		$this->setKarbFormTplName('?howto?karbform.tpl');
//		$this->setKarbTplName('?howto?karb.tpl');
//		$this->setListBodyRowTplName('?howto?lista_tbody_tr.tpl');
//		$this->setListBodyRowVarName('_egyed');
		parent::__construct($params);
	}

	public function loadVars($t) {
		$x=array();
		if (!$t) {
			$t=new \Entities\TermekKep();
			$this->getEm()->detach($t);
			$x['oper']='add';
			$x['id']=store::createUID();
		}
		else {
			$x['oper']='edit';
			$x['id']=$t->getId();
		}
		$x['url']=$t->getUrl();
		$x['urlsmall']=$t->getUrlSmall();
		$x['urlmedium']=$t->getUrlMedium();
		$x['urllarge']=$t->getUrlLarge();
		$x['leiras']=$t->getLeiras();
		return $x;
	}

	protected function setFields($obj) {
		$obj->setLeiras($this->params->getStringRequestParam('leiras'));
		$obj->setUrl($this->params->getStringRequestParam('url'));
		return $obj;
	}

	public function getemptyrow() {
		$view=$this->createView('termektermekkepkarb.tpl');
		$view->setVar('kep',$this->loadVars(null));
		echo $view->getTemplateResult();
	}

	public function getSelectList($termek,$selid) {
		$kepek=$this->getRepo()->getByTermek($termek);
		$keplista=array();
		foreach($kepek as $kep) {
			$keplista[]=array('id'=>$kep->getId(),'caption'=>$kep->getUrl(),'selected'=>$kep->getId()==$selid,'url'=>$kep->getUrlSmall());
		}
		return $keplista;
	}

/*	protected function gettablerow($kep) {
		$view=$this->createView('termektermekkepkarb.tpl');
		$view->setVar('oper','edit');
		$view->setVar('kep',$this->loadVars($kep));
		return $view->getTemplateResult();
	}

	protected function save() {
		$uploaddir=store::getConfigValue('path.termekkep');
		$pp=pathinfo($_FILES['userfile']['name']);
		$uploadfile=$uploaddir.$this->getStringRequestParam('nev').'.'.$pp['extension'];
		if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
			$imageproc=new \mkwhelpers\Images($uploadfile);
			$imageproc->setJpgquality(store::getParameter('jpgquality'));
			$imageproc->setPngquality(store::getParameter('pngquality'));
			$smallfn=$uploaddir.$this->getStringRequestParam('nev').store::getParameter('smallimgpost','').'.'.$pp['extension'];
			$mediumfn=$uploaddir.$this->getStringRequestParam('nev').store::getParameter('mediumimgpost','').'.'.$pp['extension'];
			$largefn=$uploaddir.$this->getStringRequestParam('nev').store::getParameter('bigimgpost','').'.'.$pp['extension'];
			$imageproc->Resample($smallfn,store::getParameter('smallimagesize',80));
			$imageproc->Resample($mediumfn,store::getParameter('mediumimagesize',200));
			$imageproc->Resample($largefn,store::getParameter('bigimagesize',800));
			$termek=store::getEm()->getRepository('Entities\Termek')->find($this->getIntRequestParam('termek'));
			if ($termek) {
				if ($this->getStringRequestParam('oper')=='add') {
					$tkep=new TermekKep();
				}
				else {
					$tkep=$this->getRepo()->find($this->getIntRequestParam('id'));
				}
				if ($tkep) {
					$tkep->setLeiras($this->getStringRequestParam('leiras'));
					$tkep->setUrl($uploadfile);
					$tkep->setTermek($termek);
					$this->getEm()->persist($tkep);
					$this->getEm()->flush();
					echo $this->gettablerow($tkep).$this->gettablerow(null);
				}
			}
		}
	}
*/
	protected function del() {
		$kep=$this->getRepo()->find($this->params->getIntRequestParam('id'));
		if ($kep) {
			unlink($kep->getUrl(''));
			unlink($kep->getUrlSmall(''));
			unlink($kep->getUrlMedium(''));
			unlink($kep->getUrlLarge(''));
			$this->getEm()->remove($kep);
			$this->getEm()->flush();
		}
		echo $this->params->getIntRequestParam('id');
	}
}