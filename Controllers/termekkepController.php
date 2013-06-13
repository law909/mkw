<?php
namespace Controllers;
use Entities\TermekKep;

use mkw\store;

class termekkepController extends \mkwhelpers\MattableController {

	public function __construct($generalDataLoader,$actionName=null,$commandString=null) {
		$this->setEntityName('Entities\TermekKep');
		$this->setEm(store::getEm());
		$this->setTemplateFactory(store::getTemplateFactory());
//		$this->setKarbFormTplName('?howto?karbform.tpl');
//		$this->setKarbTplName('?howto?karb.tpl');
//		$this->setListBodyRowTplName('?howto?lista_tbody_tr.tpl');
//		$this->setListBodyRowVarName('_egyed');
		parent::__construct($generalDataLoader,$actionName,$commandString);
	}

	public function loadVars($t) {
		$x=array();
		if ($t) {
			$x['id']=$t->getId();
			$x['url']=$t->getUrl();
			$x['urlsmall']=$t->getUrlSmall();
			$x['urlmedium']=$t->getUrlMedium();
			$x['urllarge']=$t->getUrlLarge();
			$x['leiras']=$t->getLeiras();
			$x['oper']='edit';
		}
		else {
			$x['id']=store::createUID();
			$x['url']='';
			$x['urlsmall']='';
			$x['urlmedium']='';
			$x['urllarge']='';
			$x['leiras']='';
			$x['oper']='add';
		}
		return $x;
	}

	protected function setFields($obj) {
		$obj->setLeiras($this->getStringParam('leiras'));
		$obj->setUrl($this->getStringParam('url'));
		return $obj;
	}

	protected function getemptyrow() {
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
		$uploadfile=$uploaddir.$this->getStringParam('nev').'.'.$pp['extension'];
		if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
			$imageproc=new matt\Images($uploadfile);
			$imageproc->setJpgquality(store::getParameter('jpgquality'));
			$imageproc->setPngquality(store::getParameter('pngquality'));
			$smallfn=$uploaddir.$this->getStringParam('nev').store::getParameter('smallimgpost','').'.'.$pp['extension'];
			$mediumfn=$uploaddir.$this->getStringParam('nev').store::getParameter('mediumimgpost','').'.'.$pp['extension'];
			$largefn=$uploaddir.$this->getStringParam('nev').store::getParameter('bigimgpost','').'.'.$pp['extension'];
			$imageproc->Resample($smallfn,store::getParameter('smallimagesize',80));
			$imageproc->Resample($mediumfn,store::getParameter('mediumimagesize',200));
			$imageproc->Resample($largefn,store::getParameter('bigimagesize',800));
			$termek=store::getEm()->getRepository('Entities\Termek')->find($this->getIntParam('termek'));
			if ($termek) {
				if ($this->getStringParam('oper')=='add') {
					$tkep=new TermekKep();
				}
				else {
					$tkep=$this->getRepo()->find($this->getIntParam('id'));
				}
				if ($tkep) {
					$tkep->setLeiras($this->getStringParam('leiras'));
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
		$kep=$this->getRepo()->find($this->getIntParam('id'));
		if ($kep) {
			unlink($kep->getUrl(''));
			unlink($kep->getUrlSmall(''));
			unlink($kep->getUrlMedium(''));
			unlink($kep->getUrlLarge(''));
			$this->getEm()->remove($kep);
			$this->getEm()->flush();
		}
		echo $this->getIntParam('id');
	}
}