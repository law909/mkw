<?php

namespace Controllers;

use Entities\Rendezveny;

class rendezvenyController extends \mkwhelpers\MattableController {

    public function __construct($params) {
        $this->setEntityName('Entities\Rendezveny');
        $this->setKarbFormTplName('rendezvenykarbform.tpl');
        $this->setKarbTplName('rendezvenykarb.tpl');
        $this->setListBodyRowTplName('rendezvenylista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct($params);
    }

    protected function loadVars($t, $forKarb = false) {
        $dokCtrl = new rendezvenydokController($this->params);
        $dok = array();
        $x = array();
        if (!$t) {
            $t = new \Entities\Rendezveny();
            $this->getEm()->detach($t);
        }
        $x['id'] = $t->getId();
        $x['nev'] = $t->getNev();
        $x['termeknev'] = $t->getTermekNev();
        $x['ar'] = $t->getAr();
        $x['tanarnev'] = $t->getTanarNev();
        $x['kezdodatum'] = $t->getKezdodatumStr();
        $x['kezdoido'] = $t->getKezdoido();
        $x['jogateremnev'] = $t->getJogateremNev();
        $x['rendezvenyallapotnev'] = $t->getRendezvenyallapotNev();
        $x['todoplakat'] = $t->getTodoplakat();
        $x['todofbevent'] = $t->getTodofbevent();
        $x['todofbhirdetes'] = $t->getTodofbhirdetes();
        $x['todofotobe'] = $t->getTodofotobe();
        $x['todoleirasbe'] = $t->getTodoleirasbe();
        $x['todonaptar'] = $t->getTodonaptar();
        $x['todourlap'] = $t->getTodourlap();
        $x['todowebposzt'] = $t->getTodowebposzt();
        $x['todowebslider'] = $t->getTodowebslider();
        $x['uid'] = $t->getUid();
        $x['reglink'] = '<script src=\'' . \mkw\store::getConfigValue('mainurl') . '/js/main/' . \mkw\store::getConfigValue('main.theme') . '/rendezvenyregloader.js?r=' . $t->getUid() . '\'></script>';

        if ($forKarb) {
            foreach ($t->getRendezvenyDokok() as $kepje) {
                $dok[] = $dokCtrl->loadVars($kepje);
            }
            $x['dokok'] = $dok;
        }
        return $x;
    }

    /**
     * @param \Entities\Rendezveny $obj
     * @param $oper
     * @return mixed
     */
    protected function setFields($obj, $oper) {
        $obj->setNev($this->params->getStringRequestParam('nev'));
        $obj->setKezdodatum($this->params->getStringRequestParam('kezdodatum'));
        $obj->setKezdoido($this->params->getStringRequestParam('kezdoido'));
        $obj->setAr($this->params->getFloatRequestParam('ar'));
        $ck = \mkw\store::getEm()->getRepository('Entities\Termek')->find($this->params->getIntRequestParam('termek', 0));
        if ($ck) {
            $obj->setTermek($ck);
        }
        $ck = \mkw\store::getEm()->getRepository('Entities\Dolgozo')->find($this->params->getIntRequestParam('tanar', 0));
        if ($ck) {
            $obj->setTanar($ck);
        }
        $ck = \mkw\store::getEm()->getRepository('Entities\Rendezvenyallapot')->find($this->params->getIntRequestParam('rendezvenyallapot', 0));
        if ($ck) {
            $obj->setRendezvenyallapot($ck);
        }
        $ck = \mkw\store::getEm()->getRepository('Entities\Jogaterem')->find($this->params->getIntRequestParam('jogaterem', 0));
        if ($ck) {
            $obj->setJogaterem($ck);
        }
        $dokids = $this->params->getArrayRequestParam('dokid');
        foreach ($dokids as $dokid) {
            if (($this->params->getStringRequestParam('dokurl_' . $dokid, '') !== '') ||
                ($this->params->getStringRequestParam('dokpath_' . $dokid, '') !== '')) {
                $dokoper = $this->params->getStringRequestParam('dokoper_' . $dokid);
                if ($dokoper === 'add') {
                    $dok = new \Entities\RendezvenyDok();
                    $obj->addRendezvenyDok($dok);
                    $dok->setUrl($this->params->getStringRequestParam('dokurl_' . $dokid));
                    $dok->setPath($this->params->getStringRequestParam('dokpath_' . $dokid));
                    $dok->setLeiras($this->params->getStringRequestParam('dokleiras_' . $dokid));
                    $this->getEm()->persist($dok);
                }
                elseif ($dokoper === 'edit') {
                    $dok = \mkw\store::getEm()->getRepository('Entities\RendezvenyDok')->find($dokid);
                    if ($dok) {
                        $dok->setUrl($this->params->getStringRequestParam('dokurl_' . $dokid));
                        $dok->setPath($this->params->getStringRequestParam('dokpath_' . $dokid));
                        $dok->setLeiras($this->params->getStringRequestParam('dokleiras_' . $dokid));
                        $this->getEm()->persist($dok);
                    }
                }
            }
        }
        return $obj;
    }

    public function getlistbody() {
        $view = $this->createView('rendezvenylista_tbody.tpl');

        $filterarr = new \mkwhelpers\FilterDescriptor();
        if (!is_null($this->params->getRequestParam('nevfilter', NULL))) {
            $filterarr->addFilter('nev', 'LIKE', '%' . $this->params->getStringRequestParam('nevfilter') . '%');
        }
        if (!is_null($this->params->getRequestParam('tanarfilter', null))) {
            $filterarr->addFilter('tanar', '=', $this->params->getIntRequestParam('tanarfilter'));
        }
        if (!is_null($this->params->getRequestParam('jogateremfilter', null))) {
            $filterarr->addFilter('jogaterem', '=', $this->params->getIntRequestParam('jogateremfilter'));
        }
        if (!is_null($this->params->getRequestParam('rendezvenyallapotfilter', null))) {
            $filterarr->addFilter('rendezvenyallapot', '=', $this->params->getIntRequestParam('rendezvenyallapotfilter'));
        }
        $datumtol = $this->params->getStringRequestParam('tol');
        if ($datumtol) {
            $datumtol = date(\mkw\store::$DateFormat, strtotime(\mkw\store::convDate($datumtol)));
        }
        $datumig = $this->params->getStringRequestParam('ig');
        if ($datumig) {
            $datumig = date(\mkw\store::$DateFormat, strtotime(\mkw\store::convDate($datumig)));
        }

        if ($datumtol) {
            $filterarr->addFilter('kezdodatum', '>=', $datumtol);
        }
        if ($datumig) {
            $filterarr->addFilter('kezdodatum', '<=', $datumig);
        }

        $this->initPager($this->getRepo()->getCount($filterarr));

        $egyedek = $this->getRepo()->getWithJoins(
            $filterarr, $this->getOrderArray(), $this->getPager()->getOffset(), $this->getPager()->getElemPerPage());

        echo json_encode($this->loadDataToView($egyedek, 'egyedlista', $view));
    }

    public function getSelectList($selid = null) {
        $rec = $this->getRepo()->getAll(array(), array('nev' => 'ASC', 'kezdodatum' => 'DESC'));
        $res = array();
        /** @var \Entities\Rendezveny $sor */
        foreach ($rec as $sor) {
            $res[] = array('id' => $sor->getId(), 'caption' => $sor->getTeljesNev(), 'selected' => ($sor->getId() == $selid));
        }
        return $res;
    }

    public function viewselect() {
        $view = $this->createView('rendezvenylista.tpl');

        $view->setVar('pagetitle', t('Rendezvények'));
        $view->printTemplateResult(false);
    }

    public function viewlist() {
        $view = $this->createView('rendezvenylista.tpl');

        $view->setVar('pagetitle', t('Rendezvények'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $dcs = new dolgozoController($this->params);
        $view->setVar('tanarlist', $dcs->getSelectList());
        $termek = new termekController($this->params);
        $view->setVar('termeklist', $termek->getSelectList(null));
        $rcs = new rendezvenyallapotController($this->params);
        $view->setVar('rendezvenyallapotlist', $rcs->getSelectList());
        $jtcs = new jogateremController($this->params);
        $view->setVar('jogateremlist', $jtcs->getSelectList());
        $view->printTemplateResult(false);
    }

    protected function _getkarb($tplname) {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Rendezvény'));
        $view->setVar('formaction', '/admin/rendezveny/save');
        $view->setVar('oper', $oper);
        $record = $this->getRepo()->findWithJoins($id);
        $view->setVar('egyed', $this->loadVars($record, true));
        $tanar = new dolgozoController($this->params);
        $view->setVar('tanarlist', $tanar->getSelectList(($record ? $record->getTanarId() : 0)));
        $termek = new termekController($this->params);
        $view->setVar('termeklist', $termek->getSelectList(($record ? $record->getTermekId() : 0)));
        $rcs = new rendezvenyallapotController($this->params);
        $view->setVar('rendezvenyallapotlist', $rcs->getSelectList(($record ? $record->getRendezvenyallapotId() : 0)));
        $jtcs = new jogateremController($this->params);
        $view->setVar('jogateremlist', $jtcs->getSelectList(($record ? $record->getJogateremId() : 0)));
        return $view->getTemplateResult();
    }

    public function setflag() {
        $id = $this->params->getIntRequestParam('id');
        $kibe = $this->params->getBoolRequestParam('kibe');
        $flag = $this->params->getStringRequestParam('flag');
        /** @var \Entities\Rendezveny $obj */
        $obj = $this->getRepo()->find($id);
        if ($obj) {
            switch ($flag) {
                case 'todonaptar':
                    $obj->setTodonaptar($kibe);
                    break;
                case 'todowebposzt':
                    $obj->setTodowebposzt($kibe);
                    break;
                case 'todowebslider':
                    $obj->setTodowebslider($kibe);
                    break;
                case 'todourlap':
                    $obj->setTodourlap($kibe);
                    break;
                case 'todofbevent':
                    $obj->setTodofbevent($kibe);
                    break;
                case 'todofbhirdetes':
                    $obj->setTodofbhirdetes($kibe);
                    break;
                case 'todoplakat':
                    $obj->setTodoplakat($kibe);
                    break;
                case 'todofotobe':
                    $obj->setTodofotobe($kibe);
                    break;
                case 'todoleirasbe':
                    $obj->setTodoleirasbe($kibe);
                    break;
            }
            $this->getEm()->persist($obj);
            $this->getEm()->flush();
        }
    }

    public function regView() {
        $rid = $this->params->getStringRequestParam('r');
        /** @var \Entities\Rendezveny $rendezveny */
        $rendezveny = $this->getRepo()->findOneBy(array('uid' => $rid));
        if ($rendezveny) {
            $v = $this->getTemplateFactory()->createMainView('rendezvenyreg.tpl');
            $v->setVar('uid', $rendezveny->getUid());
            echo $v->getTemplateResult();
        }
    }

    public function regSave() {
        $rid = $this->params->getStringRequestParam('r');
        /** @var \Entities\Rendezveny $rendezveny */
        $rendezveny = $this->getRepo()->findOneBy(array('uid' => $rid));
        if ($rendezveny) {
            $email = $this->params->getStringRequestParam('email');
            $partner = $this->getRepo('Entities\Partner')->findOneBy(array('email' => $email));
            if (!$partner) {
                $partner = new \Entities\Partner();
            }
            $partnerctrl = new \Controllers\partnerController($this->params);
            $partner = $partnerctrl->setFields($partner, null, 'pubreg');
            $this->getEm()->persist($partner);

            $jel = new \Entities\RendezvenyJelentkezes();
            $jel->setDatum('');
            $jel->setPartner($partner);
            $jel->setRendezveny($rendezveny);
            $jel->setEmailregkoszono(true);
            $this->getEm()->persist($jel);

            $this->getEm()->flush();

            $emailtpl = $this->getRepo('Entities\Emailtemplate')->find(\mkw\store::getParameter(\mkw\consts::RendezvenySablonRegKoszono));
            if ($emailtpl) {
                $tpldata = $jel->toLista();
                $subject = \mkw\store::getTemplateFactory()->createMainView('string:' . $emailtpl->getTargy());
                $subject->setVar('jelentkezes', $tpldata);
                $body = \mkw\store::getTemplateFactory()->createMainView('string:' . str_replace('&#39;', '\'', html_entity_decode($emailtpl->getHTMLSzoveg())));
                $body->setVar('jelentkezes', $tpldata);
                if (\mkw\store::getConfigValue('developer')) {
                    \mkw\store::writelog($subject->getTemplateResult(), 'rendezvenyregkoszonoemail.html');
                    \mkw\store::writelog($body->getTemplateResult(), 'rendezvenyregkoszonoemail.html');
                }
                else {
                    $mailer = \mkw\store::getMailer();
                    $mailer->addTo($jel->getPartneremail());
                    $mailer->setSubject($subject->getTemplateResult());
                    $mailer->setMessage($body->getTemplateResult());
                    $mailer->send();
                }
            }

            $emailtpl = $this->getRepo('Entities\Emailtemplate')->find(\mkw\store::getParameter(\mkw\consts::RendezvenySablonRegErtesito));
            if ($emailtpl) {
                $tpldata = $jel->toLista();
                $subject = \mkw\store::getTemplateFactory()->createMainView('string:' . $emailtpl->getTargy());
                $subject->setVar('jelentkezes', $tpldata);
                $body = \mkw\store::getTemplateFactory()->createMainView('string:' . str_replace('&#39;', '\'', html_entity_decode($emailtpl->getHTMLSzoveg())));
                $body->setVar('jelentkezes', $tpldata);
                if (\mkw\store::getConfigValue('developer')) {
                    \mkw\store::writelog($subject->getTemplateResult(), 'rendezvenyregertesitoemail.html');
                    \mkw\store::writelog($body->getTemplateResult(), 'rendezvenyregertesitoemail.html');
                }
                else {
                    $mailer = \mkw\store::getMailer();
                    $mailer->addTo(\mkw\store::getParameter(\mkw\consts::RendezvenyRegErtesitoEmail));
                    $mailer->setSubject($subject->getTemplateResult());
                    $mailer->setMessage($body->getTemplateResult());
                    $mailer->send();
                }
            }

            $v = $this->getTemplateFactory()->createMainView('rendezvenyregkoszono.tpl');
            echo $v->getTemplateResult();
        }
    }

    public function sendKezdesEmail() {
        $ret = array('msg' => at('A kezdés emlékeztető levelek kiküldve.'));
        $rend = $this->getRepo()->find($this->params->getIntRequestParam('id'));
        if ($rend) {
            $rjc = new \Controllers\rendezvenyjelentkezesController($this->params);
            $filter = new \mkwhelpers\FilterDescriptor();
            $filter->addFilter('rendezveny', '=', $rend);
            $jelek = $this->getRepo('Entities\RendezvenyJelentkezes')->getAll($filter);
            /** @var \Entities\RendezvenyJelentkezes $jel */
            foreach($jelek as $jel) {
                $rjc->sendKezdesEmail($jel->getId());
            }
        }
        echo json_encode($ret);
    }
}