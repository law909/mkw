<?php
namespace Controllers;

use mkw\store;

class rewrite301Controller extends \mkwhelpers\JQGridController {

	public function __construct($params) {
		$this->setEntityName('Entities\Rewrite301');
		parent::__construct($params);
	}

	protected function loadCells($obj) {
		return array($obj->getFromurl(),$obj->getTourl());
	}

	protected function setFields($obj) {
		$obj->setFromurl($this->params->getStringRequestParam('fromurl',$obj->getFromurl()));
		$obj->setTourl($this->params->getStringRequestParam('tourl',$obj->getTourl()));
		return $obj;
	}

	public function jsonlist() {
		$filter=array();
		if ($this->params->getBoolRequestParam('_search',false)) {
		}
		$rec=$this->getRepo()->getAll($filter,$this->getOrderArray());
		echo json_encode($this->loadDataToView($rec));
	}

    public function rewrite() {
        $req = $_SERVER['REQUEST_URI'];
        $rec = $this->getRepo()->findOneByFromurl($req);
        if ($rec && $rec->getTourl()) {
            header("HTTP/1.1 301 Moved Permanently");
            header('Location: ' . $rec->getTourl());
        }
    }

	/* MINTA ha nem kell, dobd ki
	public function getSelectList($selid) {
		$rec=$this->getRepo()->getAll(array(),array('nev'=>'ASC'));
		$res=array();
		foreach($rec as $sor) {
			$res[]=array('id'=>$sor->getId(),'caption'=>$sor->getNev(),'selected'=>($sor->getId()==$selid));
		}
		return $res;
	}

	public function htmllist() {
		$rec=$this->getRepo()->getAll(array(),array('nev'=>'asc'));
		$ret='<select>';
		foreach($rec as $sor) {
			$ret.='<option value="'.$sor->getId().'">'.$sor->getNev().'</option>';
		}
		$ret.='</select>';
		echo $ret;
	}
	*/
}