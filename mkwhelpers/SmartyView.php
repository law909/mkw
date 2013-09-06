<?php
namespace mkwhelpers;

class SmartyView extends View {

	public function __construct($compiledtplpath,$tplpath,$tplfilename,$configdir='',$cachedir='') {
		$this->tplengine=new \Smarty();
		$this->tplengine->setTemplateDir($tplpath);
		$this->tplengine->setCompileDir($compiledtplpath);
		$this->tplengine->setConfigDir($configdir);
		$this->tplengine->setCacheDir($cachedir);
		$this->tplfile=$tplfilename;
	}

	public function setVar($variable,$data) {
		$this->tplengine->assign($variable,$data);
	}

	public function getTemplateResult() {
		return $this->tplengine->fetch($this->tplfile);
	}

	public function printTemplateResult() {
		$this->tplengine->display($this->tplfile);
		\mkw\Store::storePrevUri();
	}
}