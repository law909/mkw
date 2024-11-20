<?php

namespace Controllers;

use Entities\MPTNGYSzakmaianyag;
use Entities\MPTNGYSzerepkor;
use Entities\Partner;
use mkwhelpers\FilterDescriptor;
use mkwhelpers\ParameterHandler;

class mptpartnerController extends partnerController
{

    public function saveRegistration()
    {
        $hibas = false;
        $hibak = [];

        $email = $this->params->getStringRequestParam('email');
        $jelszo1 = $this->params->getStringRequestParam('jelszo1');

        $r = $this->checkPartnerData('jelszo');
        $hibas = $hibas || $r['hibas'];
        $hibak = array_merge($hibak, $r['hibak']);
        if (!$hibas) {
            $ps = $this->getRepo()->findByEmail($email);
            if (count($ps) > 0) {
                $t = $ps[0];
            } else {
                $t = new \Entities\Partner();
            }
            $t = $this->setFields($t, 'add', 'minden');
            $this->getEm()->persist($t);
            $this->getEm()->flush();
            $this->login($email, $jelszo1);
            \Zend_Session::writeClose();
            echo json_encode([
                'url' => \mkw\store::getRouter()->generate('mptngyszakmaianyagok', true)
            ]);
        } else {
            echo json_encode($hibak);
        }
    }

    public function saveAdataim()
    {
        $hibak = [];
        /** @var Partner $p */
        $p = $this->getRepo()->getLoggedInUser();
        if ($p) {
            $p->setSzlanev($this->params->getStringRequestParam('szlanev'));
            $p->setIrszam($this->params->getStringRequestParam('irszam'));
            $p->setVaros($this->params->getStringRequestParam('varos'));
            $p->setUtca($this->params->getStringRequestParam('utca'));
            $this->getEm()->persist($p);
            $this->getEm()->flush();
            echo json_encode([
                'url' => \mkw\store::getRouter()->generate('mptngyszakmaianyagok', true)
            ]);
        } else {
            echo json_encode($hibak);
        }
    }

    public function doLogin()
    {
        if (\mkw\store::mustLogin() && \mkw\store::getMainSession()->redirafterlogin) {
            $route = \mkw\store::getMainSession()->redirafterlogin;
            unset(\mkw\store::getMainSession()->redirafterlogin);
        } else {
            $route = \mkw\store::getRouter()->generate('mptngyszakmaianyagok', true);
        }
        if (!$this->checkloggedin()) {
            if ($this->login($this->params->getStringRequestParam('email'), $this->params->getStringRequestParam('jelszo'))) {
//				\Zend_Session::writeClose();
                /** @var \Entities\Partner $partnerobj */
                $partnerobj = \mkw\store::getEm()->getRepository(Partner::class)->find(\mkw\store::getMainSession()->pk);
                if ($partnerobj) {
                    $mc = new mainController($this->params);
                    $mc->setOrszag($partnerobj->getOrszagId());
                }
            } else {
                \mkw\store::clearLoggedInUser();
                $mc = new mainController($this->params);
                $mc->clearOrszag();
                \mkw\store::getMainSession()->loginerror = true;
                $route = \mkw\store::getRouter()->generate('showlogin', true);
            }
            echo json_encode([
                'url' => $route
            ]);
        } else {
            echo json_encode([
                'url' => $route
            ]);
        }
    }

    public function checkPartnerUnknown()
    {
        $email = $this->params->getStringRequestParam('email');
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter
            ->addFilter('email', '=', $email);
        $cnt = $this->getRepo()->getCount($filter);
        echo json_encode([
            'unknown' => ($cnt === 0)
        ]);
    }

    public function adataim()
    {
        if ($this->checkloggedin()) {
            $v = $this->createMainView('adataim.tpl');
            $v->printTemplateResult();
        }
    }
}
