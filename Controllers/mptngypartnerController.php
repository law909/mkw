<?php

namespace Controllers;

class mptngypartnerController extends partnerController
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

    public function doLogin()
    {
        if (\mkw\store::mustLogin() && \mkw\store::getMainSession()->redirafterlogin) {
            $route = \mkw\store::getMainSession()->redirafterlogin;
            unset(\mkw\store::getMainSession()->redirafterlogin);
        } else {
            $route = \mkw\store::getRouter()->generate('mptngyszakmaianyagok', true);
        }
        \mkw\store::writelog('dologin 1');
        if (!$this->checkloggedin()) {
            \mkw\store::writelog('dologin 2');
            if ($this->login($this->params->getStringRequestParam('email'), $this->params->getStringRequestParam('jelszo'))) {
                \mkw\store::writelog('dologin 3');
//				\Zend_Session::writeClose();
                /** @var \Entities\Partner $partnerobj */
                $partnerobj = \mkw\store::getEm()->getRepository('Entities\Partner')->find(\mkw\store::getMainSession()->pk);
                if ($partnerobj) {
                    \mkw\store::writelog('dologin 31');
                    $mc = new mainController($this->params);
                    $mc->setOrszag($partnerobj->getOrszagId());
                }
            } else {
                \mkw\store::writelog('dologin 4');
                \mkw\store::clearLoggedInUser();
                $mc = new mainController($this->params);
                $mc->clearOrszag();
                \mkw\store::getMainSession()->loginerror = true;
                $route = \mkw\store::getRouter()->generate('showlogin', true);
            }
            \mkw\store::writelog('dologin 5');
            echo json_encode([
                'url' => $route
            ]);
        } else {
            echo json_encode([
                'error' => true
            ]);
        }
    }

    public function checkPartnerUnknown() {
        $email = $this->params->getStringRequestParam('email');
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter
            ->addFilter('email', '=', $email);
        $cnt = $this->getRepo()->getCount($filter);
        echo json_encode([
            'unknown' => ($cnt === 0)
        ]);
    }
}
