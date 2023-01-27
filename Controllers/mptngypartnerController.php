<?php

namespace Controllers;

use Entities\MPTNGYSzakmaianyag;
use mkwhelpers\FilterDescriptor;
use mkwhelpers\ParameterHandler;

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
        if (!$this->checkloggedin()) {
            if ($this->login($this->params->getStringRequestParam('email'), $this->params->getStringRequestParam('jelszo'))) {
//				\Zend_Session::writeClose();
                /** @var \Entities\Partner $partnerobj */
                $partnerobj = \mkw\store::getEm()->getRepository('Entities\Partner')->find(\mkw\store::getMainSession()->pk);
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

    private function countSzerzo($email, $anyag = null)
    {
        if ($email) {
            $filter = new FilterDescriptor();
            $filter->addSql("(_xx.szerzo1email='$email') OR (_xx.szerzo2email='$email') OR (_xx.szerzo3='$email') OR (_xx.szerzo4='$email')");
            if ($anyag) {
                $filter->addFilter('id', '<>', $anyag);
            }
            $filter->addFilter('vegleges', '=', true);
            return $this->getRepo(MPTNGYSzakmaianyag::class)->getCount($filter);
        }
        return 0;
    }

    public function getPartnerInfoForCheck($partner, $anyag, $checktulaj)
    {
        $res = [];
        if ($partner) {
            $partnerid = $partner->getId();

            // egy résztvevő első szerző maximum kétszer lehet
            if ($checktulaj) {
                $filter = new FilterDescriptor();
                $filter->addFilter('tulajdonos', '=', $partner);
                if ($anyag) {
                    $filter->addFilter('id', '<>', $anyag);
                }
                $filter->addFilter('tipus', '<>', \mkw\store::getParameter(\mkw\consts::MPTNGYSzimpoziumEloadasTipus));
                $filter->addFilter('vegleges', '=', true);
                $res['elsoszerzodb'] = $this->getRepo(MPTNGYSzakmaianyag::class)->getCount($filter);
            } else {
                $res['elsoszerzodb'] = 0;
            }
            $res['elsoszerzo'] = $res['elsoszerzodb'] < 2;

            // egy résztvevő egy esetben lehet szimpóziumi elnök
            $filter->clear();
            $filter->addFilter('tulajdonos', '=', $partner);
            if ($anyag) {
                $filter->addFilter('id', '<>', $anyag);
            }
            $filter->addFilter('vegleges', '=', true);
            $filter->addFilter('tipus', '=', \mkw\store::getParameter(\mkw\consts::MPTNGYSzimpoziumTipus));
            $res['szimpoziumelnokdb'] = $this->getRepo(MPTNGYSzakmaianyag::class)->getCount($filter);
            $res['szimpoziumelnok'] = $res['szimpoziumelnokdb'] == 0;

            // egy résztvevő egy esetben lehet opponens
            $filter->clear();
            $filter->addFilter('szerzo5', '=', $partner);
            if ($anyag) {
                $filter->addFilter('id', '<>', $anyag);
            }
            $filter->addFilter('vegleges', '=', true);
            $filter->addFilter('tipus', '=', \mkw\store::getParameter(\mkw\consts::MPTNGYSzimpoziumTipus));
            $res['opponensdb'] = $this->getRepo(MPTNGYSzakmaianyag::class)->getCount($filter);
            $res['opponens'] = $res['opponensdb'] == 0;

            // Egy személy max. 5 helyen lehessen II-III-IV szerző
            $filter->clear();
            $filter->addSql("(_xx.szerzo1=$partnerid) OR (_xx.szerzo2=$partnerid) OR (_xx.szerzo3=$partnerid) OR (_xx.szerzo4=$partnerid)");
            if ($anyag) {
                $filter->addFilter('id', '<>', $anyag);
            }
            $filter->addFilter('vegleges', '=', true);
            $res['szerzodb'] = $this->getRepo(MPTNGYSzakmaianyag::class)->getCount($filter);
            $res['szerzo'] = $res['szerzodb'] < 5;

            $res['szerzo1db'] = $this->countSzerzo($this->params->getStringRequestParam('szerzo1email'), $anyag);
            $res['szerzo1'] = $res['szerzo1db'] < 5;
            $res['szerzo2db'] = $this->countSzerzo($this->params->getStringRequestParam('szerzo2email'), $anyag);
            $res['szerzo2'] = $res['szerzo2db'] < 5;
            $res['szerzo3db'] = $this->countSzerzo($this->params->getStringRequestParam('szerzo3email'), $anyag);
            $res['szerzo3'] = $res['szerzo3db'] < 5;
            $res['szerzo4db'] = $this->countSzerzo($this->params->getStringRequestParam('szerzo4email'), $anyag);
            $res['szerzo4'] = $res['szerzo4db'] < 5;

            $res['success'] = $res['elsoszerzo'] && $res['szimpoziumelnok'] && $res['opponens'] && $res['szerzo'] &&
                $res['szerzo1'] && $res['szerzo2'] && $res['szerzo3'] && $res['szerzo4'];
        }
        return $res;
    }
}
