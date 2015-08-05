<?php

namespace Controllers;

class foxpostController extends \mkwhelpers\MattableController {

	public function __construct($params) {
		$this->setEntityName('Entities\Bizonylatfej');
		$this->setListBodyRowVarName('_egyed');
		parent::__construct($params);
	}

    public function getData($resource) {
        $ch = curl_init(\mkw\Store::getParameter(\mkw\consts::FoxpostApiURL) . $resource);

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HEADER,"Accept:application/vnd.cleveron+json; version=1.0");
        curl_setopt($ch, CURLOPT_HEADER,"Content-Type:application/vnd.cleveron+json");
        curl_setopt($ch, CURLOPT_USERPWD, \mkw\Store::getParameter(\mkw\consts::FoxpostUsername) . ":" . \mkw\Store::getParameter(\mkw\consts::FoxpostPassword));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $res = curl_exec($ch);
        $res = json_decode($res);
        curl_close($ch);
        return $res;
    }

    public function downloadTerminalList() {

    }
}