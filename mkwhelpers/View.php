<?php
namespace mkwhelpers;

abstract class View {

	protected $tplengine;
	protected $tplfile;
	protected $tpldata;

	abstract public function setVar($variable,$data);
	abstract public function getTemplateResult();
	abstract public function printTemplateResult($storePrevUri = false);
}