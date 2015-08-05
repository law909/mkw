<?php

namespace Controllers;

class foxpostController extends \mkwhelpers\MattableController {

	public function __construct($params) {
		$this->setEntityName('Entities\Bizonylatfej');
		$this->setListBodyRowVarName('_egyed');
		parent::__construct($params);
	}

    public function initCurl($resource) {
        $ch = curl_init(\mkw\Store::getParameter(\mkw\consts::FoxpostApiURL) . $resource);

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HEADER, "Accept:application/vnd.cleveron+json; version=1.0");
        curl_setopt($ch, CURLOPT_HEADER, "Content-Type:application/vnd.cleveron+json");
        curl_setopt($ch, CURLOPT_USERPWD, \mkw\Store::getParameter(\mkw\consts::FoxpostUsername) . ":" . \mkw\Store::getParameter(\mkw\consts::FoxpostPassword));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        return $ch;
    }

    public function downloadTerminalList() {
        $ch = $this->initCurl('places');
        $res = curl_exec($ch);
        $res = json_decode($res);
        curl_close($ch);
        $db = 0;
        foreach($res as $r) {
            $db++;
            $terminal = $this->getRepo('Entities\FoxpostTerminal')->find($r->place_id);
            if (!$terminal) {
                $terminal = new \Entities\FoxpostTerminal();
                $terminal->setId($r->place_id);
            }
            $terminal->setNev($r->name);
            $terminal->setCim($r->address);
            $terminal->setCsoport($r->group);
            $terminal->setFindme($r->findme);
            $terminal->setNyitva($r->open);
            $terminal->setGeolat($r->geolat);
            $terminal->setGeolng($r->geolng);
            $this->getEm()->persist($terminal);
            if ($db % 20 === 0) {
                $this->getEm()->flush();
                $this->getEm()->clear();
            }
        }
        $this->getEm()->flush();
        $this->getEm()->clear();
        return $res;

    }
}