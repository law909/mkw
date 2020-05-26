<?php

namespace Controllers;

class csomagterminalController extends \mkwhelpers\MattableController {

    public function __construct($params) {
        $this->setEntityName('Entities\CsomagTerminal');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct($params);
    }

    public function initFoxpostCurl($resource) {
        $ch = curl_init(\mkw\store::getParameter(\mkw\consts::FoxpostApiURL) . $resource);

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HEADER, "Accept:application/vnd.cleveron+json; version=1.0");
        curl_setopt($ch, CURLOPT_HEADER, "Content-Type:application/vnd.cleveron+json");
        curl_setopt($ch, CURLOPT_USERPWD, \mkw\store::getParameter(\mkw\consts::FoxpostUsername) . ":" . \mkw\store::getParameter(\mkw\consts::FoxpostPassword));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        return $ch;
    }

    public function downloadFoxpostTerminalList() {
        $ch = $this->initFoxpostCurl('places');
        $res = curl_exec($ch);
        $res = json_decode($res);
        curl_close($ch);
        if ($res && is_array($res)) {
            $db = 0;
            foreach ($res as $r) {
                $db++;
                $terminal = $this->getRepo('Entities\CsomagTerminal')->findOneBy(array('idegenid' => $r->place_id, 'tipus' => 'foxpost'));
                if (!$terminal) {
                    $terminal = new \Entities\CsomagTerminal();
                }
                $terminal->setIdegenid($r->place_id);
                $terminal->setNev($r->name);
                $terminal->setCim($r->address);
                $terminal->setCsoport($r->group);
                $terminal->setFindme($r->findme);
                $terminal->setNyitva($r->open);
                $terminal->setGeolat($r->geolat);
                $terminal->setGeolng($r->geolng);
                $terminal->setInaktiv(false);
                $terminal->setTipus('foxpost');
                $this->getEm()->persist($terminal);
                if ($db % 20 === 0) {
                    $this->getEm()->flush();
                    $this->getEm()->clear();
                }
            }
            $this->getEm()->flush();
            $this->getEm()->clear();

            $filter = new \mkwhelpers\FilterDescriptor();
            $filter->addFilter('tipus', '=', 'foxpost');
            $terminalok = $this->getRepo('\Entities\CsomagTerminal')->getAll($filter);
            /** @var \Entities\CsomagTerminal $terminal */
            foreach ($terminalok as $terminal) {
                $megvan = false;
                foreach ($res as $r) {
                    $megvan = $megvan || ($r->place_id == $terminal->getIdegenid());
                }
                if (!$megvan) {
                    $terminal->setInaktiv(!$megvan);
                    $this->getEm()->persist($terminal);
                }
            }
            $this->getEm()->flush();
            $this->getEm()->clear();
        }
        return $res;
    }

    /**
     * @param \Entities\Bizonylatfej $fej
     * @return mixed
     */
    public function sendMegrendelesToFoxpost($fej) {
        $ch = $this->initFoxpostCurl('orders');
        $fields = array(
            'place_id' => (int)$fej->getCsomagterminalIdegenId(),
            'name' => ( \mkw\store::getConfigValue('developer') ? 'teszt' : $fej->getPartnernev()),
            'phone' => $fej->getPartnertelefon(),
            'email' => $fej->getPartneremail(),
            'refcode' => $fej->getId()
        );
        if (\mkw\store::isUtanvetFizmod($fej->getFizmodId())) {
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

    public function downloadGLSTerminalList() {
        $sep = ';';
        $ch = curl_init(\mkw\store::getParameter(\mkw\consts::GLSTerminalURL));
        $fh = fopen(\mkw\store::storagePath('glscsomagpont.csv'), 'w');
        curl_setopt($ch, CURLOPT_FILE, $fh);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_exec($ch);
        fclose($fh);
        $fh = fopen(\mkw\store::storagePath('glscsomagpont.csv'), 'r');
        if ($fh) {

            $pontok = array();
            fgetcsv($fh, 0, $sep);
            while ($data = fgetcsv($fh, 0, $sep)) {
                $pontok[] = $data;
            }
            $db = 0;
            foreach ($pontok as $i => $r) {
                $db++;
                $terminal = $this->getRepo('Entities\CsomagTerminal')->findOneBy(array('idegenid' => $r[\mkw\store::n('a')], 'tipus' => 'gls'));
                if (!$terminal) {
                    $terminal = new \Entities\CsomagTerminal();
                }
                $terminal->setIdegenid($r[\mkw\store::n('a')]);
                $terminal->setNev(\mkw\store::toutf($r[\mkw\store::n('f')]));
                $terminal->setCim(\mkw\store::toutf($r[\mkw\store::n('e')]));
                $terminal->setCsoport(\mkw\store::toutf($r[\mkw\store::n('d')]));
                $terminal->setFindme(\mkw\store::toutf($r[\mkw\store::n('g')] . ' ' . $r[\mkw\store::n('h')]));
                $terminal->setInaktiv(false);
                $terminal->setTipus('gls');
                $this->getEm()->persist($terminal);
                if ($db % 20 === 0) {
                    $this->getEm()->flush();
                    $this->getEm()->clear();
                }
            }
            $this->getEm()->flush();
            $this->getEm()->clear();

            $filter = new \mkwhelpers\FilterDescriptor();
            $filter->addFilter('tipus', '=', 'gls');
            $terminalok = $this->getRepo('\Entities\CsomagTerminal')->getAll($filter);
            /** @var \Entities\CsomagTerminal $terminal */
            foreach ($terminalok as $terminal) {
                $megvan = false;
                foreach ($pontok as $r) {
                    $megvan = $megvan || ($r[\mkw\store::n('a')] == $terminal->getIdegenid());
                }
                if (!$megvan) {
                    $terminal->setInaktiv(!$megvan);
                    $this->getEm()->persist($terminal);
                }
            }
            $this->getEm()->flush();
            $this->getEm()->clear();
        }
    }

    public function getCsoportok() {
        $szmid = $this->params->getIntRequestParam('szmid');
        $szm = $this->getRepo('Entities\Szallitasimod')->find($szmid);
        $tipus = null;

        if ($szm) {
            $tipus = $szm->getTerminaltipus();
        }

        $rec = $this->getRepo('Entities\CsomagTerminal')->getCsoportok($tipus);
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
		$view = \mkw\store::getTemplateFactory()->createMainView('checkout' . $tipus . 'csoportlist.tpl');
		$view->setVar($tipus . 'csoportlist', $res);
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

        $rec = $this->getRepo('Entities\CsomagTerminal')->getByCsoport($this->params->getStringRequestParam('cs'), $tipus);
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
		$view = \mkw\store::getTemplateFactory()->createMainView('checkout' . $tipus . 'terminallist.tpl');
		$view->setVar($tipus . 'terminallist', $res);
		echo json_encode(array(
            'html' => $view->getTemplateResult()
        ));
    }

    public function getSelectList($selid, $tipus = null) {
        if (!is_null($tipus)) {
            $filter = new \mkwhelpers\FilterDescriptor();
            $filter->addFilter('tipus', '=', $tipus);
            $rec = $this->getRepo()->getAll($filter, array('csoport' => 'ASC', 'nev' => 'ASC'));
            $res = array();
            foreach ($rec as $sor) {
                $res[] = array(
                    'id' => $sor->getId(),
                    'caption' => $sor->getCsoport() . ' ' . $sor->getNev() . ' ' . $sor->getCim(),
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

    public function getTerminalId() {
        $tipus = $this->params->getStringRequestParam('tipus');
        $id = $this->params->getStringRequestParam('id');
        $obj = $this->getRepo()->findBy(array('tipus' => $tipus, 'idegenid' => $id));
        if ($obj) {
            $obj = $obj[0];
        }
        else {
            $obj = new \Entities\CsomagTerminal();
            $obj->setTipus($tipus);
            $obj->setIdegenid($id);
            $obj->setNev($this->params->getStringRequestParam('nev'));
            $obj->setCim($this->params->getStringRequestParam('cim'));
            $obj->setCsoport($this->params->getStringRequestParam('csoport'));
            $obj->setNyitva($this->params->getStringRequestParam('nyitva'));
            $obj->setFindme($this->params->getStringRequestParam('findme'));
            $obj->setGeolat(str_replace(',', '.', $this->params->getStringRequestParam('geolat')));
            $obj->setGeolng(str_replace(',', '.', $this->params->getStringRequestParam('geolng')));
            $this->getEm()->persist($obj);
            $this->getEm()->flush();
        }
        echo json_encode(array('id' => $obj->getId()));
    }
}
