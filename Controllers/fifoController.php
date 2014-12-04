<?php
namespace Controllers;

use mkw\store;

class fifoController extends \mkwhelpers\MattableController {

	public function __construct($params) {
		$this->setEntityName('Entities\Fifo');
		$this->setKarbFormTplName('fifokarbform.tpl');
		$this->setKarbTplName('fifokarb.tpl');
		$this->setListBodyRowTplName('fifolista_tbody_tr.tpl');
		$this->setListBodyRowVarName('_egyed');
		parent::__construct($params);
	}

    public function teszt() {
        $rep = $this->getRepo();
        $rep->loadData();
        $rep->calculate();
        echo '<pre>' . print_r($rep->getData(), true) . '</pre>';
    }
}