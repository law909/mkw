<?php

namespace Controllers;

use mkw\store;

class uzletkotoController extends \mkwhelpers\MattableController {

    public function __construct($params) {
        $this->setEntityName('Entities\Uzletkoto');
        $this->setKarbFormTplName('uzletkotokarbform.tpl');
        $this->setKarbTplName('uzletkotokarb.tpl');
        $this->setListBodyRowTplName('uzletkotolista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_uzletkoto');
        parent::__construct($params);
    }

    protected function loadVars($t) {
        $x = array();
        if (!$t) {
            $t = new \Entities\Uzletkoto();
            $this->getEm()->detach($t);
        }
        $x['id'] = $t->getId();
        $x['nev'] = $t->getNev();
        $x['cim'] = $t->getCim();
        $x['irszam'] = $t->getIrszam();
        $x['varos'] = $t->getVaros();
        $x['utca'] = $t->getUtca();
        $x['telefon'] = $t->getTelefon();
        $x['mobil'] = $t->getMobil();
        $x['fax'] = $t->getFax();
        $x['email'] = $t->getEmail();
        $x['honlap'] = $t->getHonlap();
        $x['megjegyzes'] = $t->getMegjegyzes();
        $x['jutalek'] = $t->getJutalek();
        $x['szamlatipus'] = $t->getPartnerszamlatipus();
        $x['valutanem'] = $t->getPartnervalutanem();
        $x['valutanemnev'] = $t->getPartnervalutanemnev();
        $x['termekarazonosito'] = $t->getPartnertermekarazonosito();
        $x['szallitasimod'] = $t->getPartnerszallitasimod();
        $x['szallitasimodnev'] = $t->getPartnerszallitasimodNev();
        $x['bizonylatnyelv'] = $t->getPartnerbizonylatnyelv();
        $x['fizmodnev'] = $t->getPartnerfizmodNev();
        return $x;
    }

    /*
     *  EntityController->save() hívja, ezért kell protected-nek lennie
     */

    protected function setFields(\Entities\Uzletkoto $obj) {
        $obj->setNev($this->params->getStringRequestParam('nev'));
        $obj->setIrszam($this->params->getStringRequestParam('irszam'));
        $obj->setVaros($this->params->getStringRequestParam('varos'));
        $obj->setUtca($this->params->getStringRequestParam('utca'));
        $obj->setTelefon($this->params->getStringRequestParam('telefon'));
        $obj->setMobil($this->params->getStringRequestParam('mobil'));
        $obj->setFax($this->params->getStringRequestParam('fax'));
        $obj->setEmail($this->params->getStringRequestParam('email'));
        $obj->setHonlap($this->params->getStringRequestParam('honlap'));
        $obj->setMegjegyzes($this->params->getStringRequestParam('megjegyzes'));
        $obj->setJutalek($this->params->getNumRequestParam('jutalek'));
        $obj->setPartnerszamlatipus($this->params->getIntRequestParam('partnerszamlatipus'));
        $obj->setPartnertermekarazonosito($this->params->getStringRequestParam('partnertermekarazonosito'));
        $obj->setPartnerbizonylatnyelv($this->params->getStringRequestParam('partnerbizonylatnyelv'));
        $fizmod = store::getEm()->getRepository('Entities\Fizmod')->find($this->params->getIntRequestParam('partnerfizmod', 0));
        if ($fizmod) {
            $obj->setPartnerfizmod($fizmod);
        }
        $valutanem = store::getEm()->getRepository('Entities\Valutanem')->find($this->params->getIntRequestParam('partnervalutanem', 0));
        if ($valutanem) {
            $obj->setPartnervalutanem($valutanem);
        }
        $szallmod = store::getEm()->getRepository('Entities\Szallitasimod')->find($this->params->getIntRequestParam('partnerszallitasimod', 0));
        if ($szallmod) {
            $obj->setPartnerszallitasimod($szallmod);
        }
        return $obj;
    }

    public function getlistbody() {
        $view = $this->createView('uzletkotolista_tbody.tpl');

        $filter = new \mkwhelpers\FilterDescriptor();

        if (!is_null($this->params->getRequestParam('nevfilter', NULL))) {
            $filter->addFilter('nev', 'LIKE', '%' . $this->params->getStringRequestParam('nevfilter') . '%');
        }

        $this->initPager($this->getRepo()->getCount($filter));

        $uk = $this->getRepo()->getWithJoins(
                $filter, $this->getOrderArray(), $this->getPager()->getOffset(), $this->getPager()->getElemPerPage());

        echo json_encode($this->loadDataToView($uk, 'uzletkotolista', $view));
    }

    public function viewlist() {
        $view = $this->createView('uzletkotolista.tpl');
        $view->setVar('pagetitle', t('Üzletkötők'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    public function getSelectList($selid = null) {
        $rec = $this->getRepo()->getAll(array(), array('nev' => 'ASC'));
        $res = array();
        foreach ($rec as $sor) {
            $res[] = array('id' => $sor->getId(), 'caption' => $sor->getNev(), 'selected' => ($sor->getId() == $selid));
        }
        return $res;
    }

    protected function _getkarb($tplname) {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);
        $view->setVar('pagetitle', t('Üzletkötő'));
        $view->setVar('oper', $oper);

        $partnerrepo = $this->getRepo('\Entities\Partner');
        /** @var \Entities\Uzletkoto $partner */
        $partner = $this->getRepo()->findWithJoins($id);
        // loadVars utan nem abc sorrendben adja vissza

        $fizmod = new fizmodController($this->params);
        $view->setVar('partnerfizmodlist', $fizmod->getSelectList(($partner ? $partner->getPartnerfizmodId() : 0)));
        $valutanem = new valutanemController($this->params);
        $view->setVar('partnervalutanemlist', $valutanem->getSelectList(($partner ? $partner->getPartnervalutanemId() : 0)));
        $termekar = new termekarController($this->params);
        $view->setVar('partnertermekarazonositolist', $termekar->getSelectList(($partner ? $partner->getPartnertermekarazonosito() : '')));
        $szallmod = new szallitasimodController($this->params);
        $view->setVar('partnerszallitasimodlist', $szallmod->getSelectList(($partner ? $partner->getPartnerszallitasimodId() : 0)));
        $view->setVar('partnerszamlatipuslist', $partnerrepo->getSzamlatipusList(($partner ? $partner->getPartnerszamlatipus() : 0)));
        $view->setVar('partnerbizonylatnyelvlist', \mkw\Store::getLocaleSelectList($partner ? $partner->getPartnerbizonylatnyelv() : ''));

        $view->setVar('uzletkoto', $this->loadVars($partner));
        $view->printTemplateResult();
    }

    public function htmllist() {
        $rec = $this->getRepo()->getAll(array(), array('nev' => 'asc'));
        $ret = '<select>';
        foreach ($rec as $sor) {
            $ret .= '<option value="' . $sor->getId() . '">' . $sor->getNev() . '</option>';
        }
        $ret .= '</select>';
        echo $ret;
    }

}
