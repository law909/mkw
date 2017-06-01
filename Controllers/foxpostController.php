<?php

namespace Controllers;

class foxpostController extends \mkwhelpers\MattableController {

    public function __construct($params) {
        $this->setEntityName('Entities\FoxpostTerminal');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct($params);
    }

    public function initCurl($resource) {
        $ch = curl_init(\mkw\store::getParameter(\mkw\consts::FoxpostApiURL) . $resource);

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HEADER, "Accept:application/vnd.cleveron+json; version=1.0");
        curl_setopt($ch, CURLOPT_HEADER, "Content-Type:application/vnd.cleveron+json");
        curl_setopt($ch, CURLOPT_USERPWD, \mkw\store::getParameter(\mkw\consts::FoxpostUsername) . ":" . \mkw\store::getParameter(\mkw\consts::FoxpostPassword));
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
        foreach ($res as $r) {
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

    public function sendMegrendeles($fej) {
        $ch = $this->initCurl('orders');
        $fields = array(
            'place_id' => (int)$fej->getFoxpostterminalId(),
            'name' => ( \mkw\store::getConfigValue('developer') ? 'teszt' : $fej->getPartnernev()),
            'phone' => $fej->getPartnertelefon(),
            'email' => $fej->getPartneremail(),
            'refcode' => $fej->getId()
        );
        if ($fej->getFizmodId() == \mkw\store::getParameter(\mkw\consts::UtanvetFizmod)) {
            $fields['cod_amount'] = (int)$fej->getBrutto() * 100;
            $fields['cod_currency'] = 'HUF';
        }
        $tosend = json_encode($fields);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $tosend);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Length: ' . strlen($tosend),
            'Content-Type:application/vnd.cleveron+json'
        ));
        $res = curl_exec($ch);
        curl_close($ch);
        $res = json_decode($res, true);
        return $res;
    }

    public function getCsoportok() {
        $szmid = $this->params->getIntRequestParam('szmid');
        $szm = $this->getRepo('Entities\Szallitasimod')->find($szmid);
        $tipus = null;

        if ($szm) {
            $tipus = $szm->getTerminaltipus();
        }

        $rec = $this->getRepo('Entities\FoxpostTerminal')->getCsoportok($tipus);
        $res = array();
        $vanvalasztott = false;
        foreach ($rec as $sor) {
            $r = array(
                'id' => $sor['csoport'],
                'caption' => $sor['csoport']
            );
            if (!$vanvalasztott) {
                $r['selected'] = true;
                $vanvalasztott = true;
            }
            else {
                $r['selected'] = false;
            }
            $res[] = $r;
        }
		$view = \mkw\store::getTemplateFactory()->createMainView('checkoutfoxpostcsoportlist.tpl');
		$view->setVar('foxpostcsoportlist', $res);
		echo json_encode(array(
            'html' => $view->getTemplateResult()
        ));
    }

    public function getTerminalok() {
        $szmid = $this->params->getIntRequestParam('szmid');
        $szm = $this->getRepo('Entities\Szallitasimod')->find($szmid);
        $tipus = null;

        if ($szm) {
            $tipus = $szm->getTerminaltipus();
        }

        $rec = $this->getRepo('Entities\FoxpostTerminal')->getByCsoport($this->params->getStringRequestParam('cs'), $tipus);
        $res = array();
        $vanvalasztott = false;
        foreach ($rec as $sor) {
            $r = array(
                'id' => $sor->getId(),
                'caption' => $sor->getNev(),
                'cim' => $sor->getCim()
            );
            if (!$vanvalasztott) {
                $r['selected'] = true;
                $vanvalasztott = true;
            }
            else {
                $r['selected'] = false;
            }
            $res[] = $r;
        }
		$view = \mkw\store::getTemplateFactory()->createMainView('checkoutfoxpostterminallist.tpl');
		$view->setVar('foxpostterminallist', $res);
		echo json_encode(array(
            'html' => $view->getTemplateResult()
        ));
    }

    public function getSelectList($selid, $tipus = null) {
        if (!is_null($tipus)) {
            $filter = new \mkwhelpers\FilterDescriptor();
            $filter->addFilter('inaktiv', '=', false);
            $filter->addFilter('tipus', '=', $tipus);
            $rec = $this->getRepo()->getAll($filter, array('nev' => 'ASC'));
            $res = array();
            foreach ($rec as $sor) {
                $res[] = array(
                    'id' => $sor->getId(),
                    'caption' => $sor->getNev() . ' ' . $sor->getCim(),
                    'selected' => ($sor->getId() == $selid)
                );
            }
            return $res;
        }
        return null;
    }

    public function getHTMLList() {
        $szmid = $this->params->getIntRequestParam('szmid');
        $szm = $this->getRepo('Entities\Szallitasimod')->find($szmid);
        $tipus = null;

        if ($szm) {
            $tipus = $szm->getTerminaltipus();
        }

        $res = $this->getSelectList(null, $tipus);

        $view = \mkw\store::getTemplateFactory()->createView('csomagterminalselect.tpl');
        $view->setVar('terminallist', $res);
        $view->setVar('variable', $tipus . 'terminal');
        $view->setVar('tagid', 'CsomagTerminalEdit');

        echo json_encode(array(
            'html' => $view->getTemplateResult()
        ));
    }

}
