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
        $id = $this->params->getIntRequestParam('id');
        $vid = $this->params->getIntRequestParam('vid');
        $type = $this->params->getStringRequestParam('type', 'pre');
        $cikksz = $this->params->getStringRequestParam('cikkszam');
        $rep = $this->getRepo();
        $rep->loadData($id, $vid, $cikksz);
        $rep->calculate();
        switch($type) {
            case 'pre':
                echo '<pre>' . print_r($rep->getData(), true) . '</pre>';
                break;
            case 'csv':
                header("Content-type: text/csv");
                header("Pragma: no-cache");
                header("Expires: 0");
                echo implode(';', $rep->getDataHeader()) . "\n";
                $d = $rep->getData();
                foreach($d as $dt) {
                    echo implode(';', $dt) . "\n";
                }
                break;
        }
    }
}