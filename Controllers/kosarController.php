<?php

namespace Controllers;

class kosarController extends \mkwhelpers\MattableController {

    public function __construct($params) {
        $this->setEntityName('Entities\Kosar');
//		$this->setKarbFormTplName('kosarkarbform.tpl');
//		$this->setKarbTplName('kosarkarb.tpl');
        $this->setListBodyRowTplName('kosarlista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct($params);
    }

    protected function loadVars($t) {
        $x = array();
        if (!$t) {
            $t = new \Entities\Kosar();
            $this->getEm()->detach($t);
        }
        $x['id'] = $t->getId();
        $x['session'] = $t->getSessionid();
        $x['mennyiseg'] = $t->getMennyiseg();
        $x['partner'] = $t->getPartnerId();
        $x['partnernev'] = $t->getPartnerNev();
        $x['created'] = $t->getCreated();
        $x['createdstr'] = $t->getCreatedStr();
        $term = $t->getTermek();
        if ($term) {
            $x['termek'] = $term->getId();
            $x['termeknev'] = $term->getNev();
            $x['termekurl'] = $term->getSlug();
        }
        else {
            $x['termek'] = 0;
            $x['termeknev'] = '';
            $x['termekurl'] = '';
        }
        $v = array();
        $tv = $t->getTermekvaltozat();
        if ($tv) {
            $v[] = array('nev' => $tv->getAdatTipus1Nev(), 'ertek' => $tv->getErtek1());
            $v[] = array('nev' => $tv->getAdatTipus2Nev(), 'ertek' => $tv->getErtek2());
        }
        $x['valtozat'] = $v;
        return $x;
    }

    protected function setFields($obj) {
        $ck = $this->getRepo('Entities\Partner')->find($this->params->getIntRequestParam('partner'));
        if ($ck) {
            $obj->setPartner($ck);
        }
        $ck = $this->getRepo('Entities\Termek')->find($this->params->getIntRequestParam('termek'));
        if ($ck) {
            $obj->setTermek($ck);
        }
        $obj->setMennyiseg($this->params->getNumRequestParam('mennyiseg'));
        return $obj;
    }

    public function getlistbody() {
        $view = $this->createView('kosarlista_tbody.tpl');

        $filter = new \mkwhelpers\FilterDescriptor();
        if (!is_null($this->params->getRequestParam('nevfilter', NULL))) {
            $filter->addFilter(array('_xx.sessionid', 'p.nev', 't.nev'), 'LIKE', '%' . $this->params->getStringRequestParam('nevfilter') . '%');
        }

        $this->initPager($this->getRepo()->getCount($filter));

        $egyedek = $this->getRepo()->getWithJoins(
            $filter, $this->getOrderArray(), $this->getPager()->getOffset(), $this->getPager()->getElemPerPage());

        echo json_encode($this->loadDataToView($egyedek, 'egyedlista', $view));
    }

    public function viewselect() {
        $view = $this->createView('kosarlista.tpl');

        $view->setVar('pagetitle', t('Kosár'));
        $view->printTemplateResult();
    }

    public function viewlist() {
        $view = $this->createView('kosarlista.tpl');

        $view->setVar('pagetitle', t('Kosár'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname) {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Kosár'));
        $view->setVar('formaction', '/admin/kosar/save');
        $view->setVar('oper', $oper);
        $record = $this->getRepo()->findWithJoins($id);
        $view->setVar('egyed', $this->loadVars($record));
        return $view->getTemplateResult();
    }

    public function getMiniData() {
        switch (true) {
            case \mkw\store::isMindentkapni():
                $m = $this->getRepo()->getMiniDataBySessionId(\Zend_Session::getId());
                $megingyeneshez = 0;
                $hatar = \mkw\store::getParameter(\mkw\consts::SzallitasiKtg3Tol, 0);
                $partner = \mkw\store::getLoggedInUser();
                if ($partner && $partner->getSzamlatipus()) {
                    $osszeg = $m[0][3] * 1;
                }
                else {
                    $osszeg = $m[0][2] * 1;
                }
                $valutanem = 'Ft';
                if ($partner) {
                    $valutanem = $partner->getValutanemnev();
                }
                if ($hatar && $osszeg && $osszeg < $hatar) {
                    $megingyeneshez = $hatar - $osszeg;
                }
                return array(
                    'termekdb' => $m[0][1],
                    'osszeg' => $osszeg,
                    'megingyeneshez' => $megingyeneshez,
                    'valutanem' => $valutanem
                );
            case \mkw\store::isSuperzoneB2B():
                $m = $this->getRepo()->getMiniDataBySessionId(\Zend_Session::getId());
                $partner = \mkw\store::getLoggedInUser();
                $valutanem = '';
                $valutanemid = \mkw\store::getParameter(\mkw\consts::Valutanem);
                $valutanemobj = $this->getRepo('Entities\Valutanem')->find($valutanemid);
                if ($valutanemobj) {
                    $valutanem = $valutanemobj->getNev();
                }
                if ($partner && $partner->getValutanem()) {
                    $valutanem = $partner->getValutanemnev();
                }
                return array(
                    'termekdb' => $m[0][1],
                    'netto' => $m[0][3],
                    'brutto' => $m[0][2],
                    'valutanem' => $valutanem
                );
            case \mkw\store::isMugenrace():
                $m = $this->getRepo()->getMiniDataBySessionId(\Zend_Session::getId());
                return array(
                    'termekdb' => $m[0][1],
                    'netto' => $m[0][3],
                    'brutto' => $m[0][2],
                    'valutanem' => \mkw\store::getMainSession()->valutanemnev
                );
            default:
                return false;
        }
    }

    public function get() {
        $v = $this->getTemplateFactory()->createMainView('kosar.tpl');
        \mkw\store::fillTemplate($v);
        $this->getRepo()->remove(\mkw\store::getParameter(\mkw\consts::SzallitasiKtgTermek));
        $partner = \mkw\store::getLoggedInUser();
        $valutanem = 'Ft';
        if ($partner) {
            $valutanem = $partner->getValutanemnev();
        }
        $sorok = $this->getRepo()->getDataBySessionId(\Zend_Session::getId());
        $s = array();
        $tids = array();
        $szallido = 1;
        /** @var \Entities\Kosar $sor */
        foreach ($sorok as $sor) {
            $sorszallido = $sor->getTermek()->calcSzallitasiido($sor->getTermekvaltozat(), $sor->getMennyiseg());
            if ($szallido < $sorszallido) {
                $szallido = $sorszallido;
            }
            $s[] = $sor->toLista($partner);
            $tids[] = $sor->getTermekId();
        }
        if (date(\mkw\store::$TimeFormat) > '13:00') {
            $szallido = $szallido + 1;
        }

        $v->setVar('szallitasiido', $szallido);
        $v->setVar('tetellista', $s);
        $v->setVar('valutanem', $valutanem);
        $tc = new termekController($this->params);
        $v->setVar('hozzavasarolttermekek', $tc->getHozzavasaroltLista($tids));
        $v->printTemplateResult(false);
    }

    public function clear($partnerid = false) {
        $this->getRepo()->clear($partnerid);
    }

    public function add() {
        $termek = $this->getRepo('Entities\Termek')->find($this->params->getIntRequestParam('id'));
        $vid = null;
        switch ($this->params->getIntRequestParam('jax', 0)) {
            case 2:
                $vid = $this->params->getIntRequestParam('vid', null);
                $termekvaltozat = $this->getRepo('Entities\TermekValtozat')->find($vid);
                break;
            case 3:
                $tipusok = $this->params->getArrayRequestParam('tip');
                $ertekek = $this->params->getArrayRequestParam('val');
                $termekvaltozat = $this->getRepo('Entities\TermekValtozat')->getByProperties($termek->getId(), $tipusok, $ertekek);
                $vid = $termekvaltozat->getId();
                break;
            default:
                $termekvaltozat = null;
                break;
        }

        if ($termek) {
            $termekid = $termek->getId();

            $this->getRepo()->add($termekid, $vid);

            if ($this->params->getIntRequestParam('jax', 0) > 0) {
                $minidata = $this->getMiniData();
                $v = $this->getTemplateFactory()->createMainView('minikosar.tpl');
                $v->setVar('kosar', $minidata);

                $v2 = $this->getTemplateFactory()->createMainView('minikosaringyenes.tpl');
                $v2->setVar('kosar', $minidata);

                echo json_encode(array(
                    'minikosar' => $v->getTemplateResult(),
                    'minikosaringyenes' => $v2->getTemplateResult()
                ));
            }
            else {
                if (\mkw\store::getMainSession()->prevuri) {
                    Header('Location: ' . \mkw\store::getMainSession()->prevuri);
                }
                else {
                    Header('Location: /');
                }
            }
        }
    }

    // Superzone
    public function multiAdd() {
        $termekid = $this->params->getIntRequestParam('termek');
        if ($termekid) {
            $termek = $this->getRepo('Entities\Termek')->find($termekid);
            if ($termek) {
                $vids = $this->params->getArrayRequestParam('ids');
                $values = $this->params->getArrayRequestParam('values');
                $kedvezmenyek = $this->params->getArrayRequestParam('kedv');

                for ($cikl = 0; $cikl < count($vids); $cikl++) {
                    $vid = $vids[$cikl];
                    $value = $values[$cikl];
                    $kedv = $kedvezmenyek[$cikl];
                    $termekvaltozat = $this->getRepo('Entities\TermekValtozat')->find($vid);
                    if ($termekvaltozat) {
                        $this->getRepo()->addTo($termekid, $vid, null, $value, $kedv);
                    }
                }
            }
            $minidata = $this->getMiniData();
            $v = $this->getTemplateFactory()->createMainView('minikosar.tpl');
            $v->setVar('kosar', $minidata);

            echo json_encode(array(
                'minikosar' => $v->getTemplateResult()
            ));
        }
    }

    public function del() {
        $id = $this->params->getIntRequestParam('id');
        if ($this->getRepo()->del($id)) {
            if ($this->params->getIntRequestParam('jax', 0) > 0) {
//				$v=$this->getTemplateFactory()->createMainView('minikosar.tpl');
//				$v->setVar('kosar',$this->getMiniData());
//				$v->printTemplateResult();
                echo 'ok';
            }
            else {
                if (\mkw\store::getMainSession()->prevuri) {
                    Header('Location: ' . \mkw\store::getRouter()->generate('kosarget'));
                }
                else {
                    Header('Location: ' . \mkw\store::getRouter()->generate('kosarget'));
                }
            }
        }
    }

    public function edit() {
        $id = $this->params->getIntRequestParam('id');
        $menny = $this->params->getNumRequestParam('mennyiseg', false);
        $kedvezmeny = $this->params->getNumRequestParam('kedvezmeny', false);
        if ($this->getRepo()->edit($id, $menny, $kedvezmeny)) {
            if ($this->params->getIntRequestParam('jax', 0) > 0) {
//				$v=$this->getTemplateFactory()->createMainView('minikosar.tpl');
//				$v->setVar('kosar',$this->getMiniData());
//				$v->printTemplateResult();
                echo 'ok';
            }
            else {
                $partner = \mkw\store::getLoggedInUser();
                $minidata = $this->getMiniData();
                $v = $this->getTemplateFactory()->createMainView('minikosar.tpl');
                $v->setVar('kosar', $minidata);

                $v2 = $this->getTemplateFactory()->createMainView('minikosaringyenes.tpl');
                $v2->setVar('kosar', $minidata);

                $sum = 0;
                $mennyisegsum = 0;
                $m = $this->getRepo()->calcSumBySessionId(\Zend_Session::getId());
                if ($m) {
                    if ($partner && $partner->getSzamlatipus()) {
                        $sum = $m['nettosum'];
                    }
                    else {
                        $sum = $m['bruttosum'];
                    }
                    $mennyisegsum = $m['mennyisegsum'];
                }
                $valutanemnev = 'Ft';
                if (\mkw\store::getTheme() !== 'mkwcansas') {
                    if ($partner) {
                        $valutanemnev = $partner->getValutanemnev();
                        $valutanem = $partner->getValutanem();
                    }
                    if (!$valutanem) {
                        $valutanem = $this->getRepo('\Entities\Valutanem')->find(\mkw\store::getMainSession()->valutanem);
                        $valutanemnev = \mkw\store::getMainSession()->valutanemnev;
                    }
                }
                $ker = 0;
                if (!$valutanem) {
                    $valutanem = $this->getRepo('Entities\Valutanem')->find(\mkw\store::getParameter(\mkw\consts::Valutanem));
                }
                if ($valutanem) {
                    $ker = 2;
                    if ($valutanem->getKerekit()) {
                        $ker = 0;
                    }
                }

                $sorok = $this->getRepo()->find($id);
                $s = $sorok->toLista($partner);
                echo json_encode(array(
                    'tetelegysegar' => number_format($s['bruttoegysarhuf'], $ker, ',', ' ') . ' ' . $valutanemnev,
                    'tetelertek' => number_format($s['bruttohuf'], $ker, ',', ' ') . ' ' . $valutanemnev,
                    'tetelnettoertek' => number_format($s['nettohuf'], $ker, ',', ' ') . ' ' . $valutanemnev,
                    'tetelbruttoertek' => number_format($s['bruttohuf'], $ker, ',', ' ') . ' ' . $valutanemnev,
                    'kosarertek' => number_format($sum, $ker, ',', ' ') . ' ' . $valutanemnev,
                    'kosarnetto' => number_format($m['nettosum'], $ker, ',', ' ') . ' ' . $valutanemnev,
                    'kosarbrutto' => number_format($m['bruttosum'], $ker, ',', ' ') . ' ' . $valutanemnev,
                    'mennyisegsum' => number_format($mennyisegsum, 0, ',', ' '),
                    'minikosar' => $v->getTemplateResult(),
                    'minikosaringyenes' => $v2->getTemplateResult(),
                ));
            }
        }
    }

    public function replaceSessionIdAndAddPartner($oldid, $partner) {
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('sessionid', '=', $oldid);
        $sorok = $this->getRepo()->getAll($filter, array());
        foreach ($sorok as $sor) {
            $sor->setSessionid(\Zend_Session::getId());
            $sor->setPartner($partner);
            $this->getEm()->persist($sor);
        }
        $this->getEm()->flush();
    }

    public function addSessionIdByPartner($partner) {
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('partner', '=', $partner);
        $sorok = $this->getRepo()->getAll($filter, array());
        foreach ($sorok as $sor) {
            $sor->setSessionid(\Zend_Session::getId());
            $this->getEm()->persist($sor);
        }
        $this->getEm()->flush();
    }

    public function removeSessionId($id) {
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('sessionid', '=', $id);
        $sorok = $this->getRepo()->getAll($filter, array());
        foreach ($sorok as $sor) {
            $sor->setSessionid(null);
            $this->getEm()->persist($sor);
        }
        $this->getEm()->flush();
    }

    public function getHash() {
        $sorok = $this->getRepo()->getHash();
        echo json_encode($sorok);
    }

    public function recalcPrices() {
        $sorok = $this->getRepo()->getDataBySessionId(\Zend_Session::getId());
        /** @var \Entities\Kosar $sor */
        foreach ($sorok as $sor) {
            if (\mkw\store::isMugenrace()) {
                $sor->setBruttoegysar($sor->getTermek()->getBruttoAr(
                    $sor->getTermekvaltozat(),
                    \mkw\store::getLoggedInUser(),
                    \mkw\store::getMainSession()->valutanem,
                    \mkw\store::getParameter(\mkw\consts::Webshop2Price)));
                $sor->setValutanem($this->getRepo('\Entities\Valutanem')->find(\mkw\store::getMainSession()->valutanem));
                $this->getEm()->persist($sor);
            }
        }
        $this->getEm()->flush();
    }

}
