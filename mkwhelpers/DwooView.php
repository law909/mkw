<?php
namespace mkwhelpers;

class DwooView extends View {

	public function __construct($compiledtplpath,$tplpath,$tplfilename,$configdir='',$cachedir='') {
		$this->tplengine=new \Dwoo($compiledtplpath);
		$this->tplfile=new \Dwoo_Template_File($tplpath.$tplfilename);
		$this->tpldata=new \Dwoo_Data();
	}

	public function setVar($variable,$data) {
		$this->tpldata->assign($variable,$data);
	}

	public function getTemplateResult() {
		return $this->tplengine->get($this->tplfile,$this->tpldata);
	}

	public function printTemplateResult() {
		$this->tplengine->output($this->tplfile,$this->tpldata);
	}
}