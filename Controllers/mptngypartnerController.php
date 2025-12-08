<?php

namespace Controllers;

use Entities\Emailtemplate;
use Entities\MPTNGYSzakmaianyag;
use Entities\MPTNGYSzerepkor;
use Entities\Partner;
use mkwhelpers\FilterDescriptor;

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
            $emailtpl = $this->getRepo(Emailtemplate::class)->find(\mkw\store::getParameter(\mkw\consts::MPTNGYRegVisszaigSablon));
            if ($emailtpl) {
                $subject = \mkw\store::getTemplateFactory()->createMainView('string:' . $emailtpl->getTargy());
                $body = \mkw\store::getTemplateFactory()->createMainView(
                    'string:' . str_replace('&#39;', '\'', html_entity_decode($emailtpl->getHTMLSzoveg()))
                );
                if (\mkw\store::getConfigValue('developer')) {
                    \mkw\store::writelog($subject->getTemplateResult(), 'bizstatuszemail.html');
                    \mkw\store::writelog($body->getTemplateResult(), 'bizstatuszemail.html');
                } else {
                    $mailer = \mkw\store::getMailer();
                    $mailer->addTo($email);
                    if ($emailtpl->isAszfcsatolaskell()) {
                        $mailer->setAttachment(\mkw\store::mainStoragePath(\mkw\consts::ASZFPDFName));
                    }
                    $mailer->setSubject($subject->getTemplateResult());
                    $mailer->setMessage($body->getTemplateResult());
                    $mailer->send();
                }
            }
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
            $p->setNev($this->params->getStringRequestParam('nev'));
            $p->setSzlanev($this->params->getStringRequestParam('szlanev'));
            $p->setIrszam($this->params->getStringRequestParam('irszam'));
            $p->setVaros($this->params->getStringRequestParam('varos'));
            $p->setUtca($this->params->getStringRequestParam('utca'));
            $p->setMptngybankszamlaszam($this->params->getStringRequestParam('mptngybankszamlaszam'));
            $p->setMptngycsoportosfizetes($this->params->getStringRequestParam('mptngycsoportosfizetes'));
            $p->setMptngykapcsolatnev($this->params->getStringRequestParam('mptngykapcsolatnev'));
            $p->setMptMunkahelynev($this->params->getStringRequestParam('mpt_munkahelynev'));
            $p->setVatstatus($this->params->getIntRequestParam('vatstatus'));
            $p->setAdoszam(substr($this->params->getStringRequestParam('adoszam'), 0, 13));
            $p->setCsoportosadoszam($this->params->getStringRequestParam('csoportosadoszam'));
            $p->setMptngyvipvacsora($this->params->getBoolRequestParam('mptngyvipvacsora'));
            $p->setMptngybankett($this->params->getBoolRequestParam('mptngybankett'));
            $p->setMptngynapreszvetel1($this->params->getBoolRequestParam('mptngynapreszvetel1'));
            $p->setMptngynapreszvetel2($this->params->getBoolRequestParam('mptngynapreszvetel2'));
            $p->setMptngynapreszvetel3($this->params->getBoolRequestParam('mptngynapreszvetel3'));
            $p->setMptngynemveszreszt($this->params->getBoolRequestParam('mptngynemveszreszt'));
            $p->setMptngydiak($this->params->getBoolRequestParam('mptngydiak'));
            $p->setMptngynyugdijas($this->params->getBoolRequestParam('mptngynyugdijas'));
            $p->setMptngyphd($this->params->getBoolRequestParam('mptngyphd'));
            $p->setMptngympttag($this->params->getBoolRequestParam('mptngympttag'));
            if ($this->params->getStringRequestParam('jelszo1')) {
                $p->setJelszo($this->params->getStringRequestParam('jelszo1'));
            }
            $mptngyszerepkor = \mkw\store::getEm()->getRepository(MPTNGYSzerepkor::class)->find($this->params->getIntRequestParam('mptngyszerepkor', 0));
            if ($mptngyszerepkor) {
                $p->setMptngyszerepkor($mptngyszerepkor);
            } else {
                $p->setMptngyszerepkor(null);
            }
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

    private function countSzerzo($email, $anyag = null)
    {
        if ($email) {
            $filter = new FilterDescriptor();
            $filter->addSql("(_xx.szerzo2email='$email') OR (_xx.szerzo3email='$email') OR (_xx.szerzo4email='$email')");
            if ($anyag) {
                $filter->addFilter('id', '<>', $anyag);
            }
            $filter->addFilter('vegleges', '=', true);
            return $this->getRepo(MPTNGYSzakmaianyag::class)->getCount($filter);
        }
        return 0;
    }

    public function getPartnerInfoForCheck($partner, $anyag)
    {
        $res = [];
        if ($partner) {
            $partnerid = $partner->getId();

            $isSzimpozium = $this->params->getIntRequestParam('tipus') == \mkw\store::getParameter(\mkw\consts::MPTNGYSzimpoziumTipus);
            $isSzimpoziumEloadas = $this->params->getIntRequestParam('tipus') == \mkw\store::getParameter(\mkw\consts::MPTNGYSzimpoziumEloadasTipus);

            $elsoszerzoemail = $this->params->getStringRequestParam('szerzo1email');
            $opponensemail = $this->params->getStringRequestParam('opponensemail');

            // egy résztvevő első szerző maximum kétszer lehet
            $filter = new FilterDescriptor();
            $filter->addFilter('szerzo1email', '=', $elsoszerzoemail);
            if ($anyag) {
                $filter->addFilter('id', '<>', $anyag);
            }
            if ($this->params->getIntRequestParam('tipus')) {
                $filter->addFilter('tipus', '=', $this->params->getIntRequestParam('tipus'));
            }
            $filter->addFilter('vegleges', '=', true);
            $res['elsoszerzodb'] = $this->getRepo(MPTNGYSzakmaianyag::class)->getCount($filter);
            $res['elsoszerzo'] = $res['elsoszerzodb'] < 1;

            // egy résztvevő egy esetben lehet szimpóziumi elnök
            if ($isSzimpozium) {
                $filter->clear();
                $filter->addFilter('tulajdonos', '=', $partner);
                if ($anyag) {
                    $filter->addFilter('id', '<>', $anyag);
                }
                $filter->addFilter('vegleges', '=', true);
                $filter->addFilter('tipus', '=', \mkw\store::getParameter(\mkw\consts::MPTNGYSzimpoziumTipus));
                $res['szimpoziumelnokdb'] = $this->getRepo(MPTNGYSzakmaianyag::class)->getCount($filter);
            } else {
                $res['szimpoziumelnokdb'] = 0;
            }
            $res['szimpoziumelnok'] = $res['szimpoziumelnokdb'] == 0;

            // egy résztvevő egy esetben lehet opponens
            if ($isSzimpozium) {
                $filter->clear();
                $filter->addFilter('opponensemail', '=', $opponensemail);
                if ($anyag) {
                    $filter->addFilter('id', '<>', $anyag);
                }
                $filter->addFilter('vegleges', '=', true);
                $filter->addFilter('tipus', '=', \mkw\store::getParameter(\mkw\consts::MPTNGYSzimpoziumTipus));
                $res['opponensdb'] = $this->getRepo(MPTNGYSzakmaianyag::class)->getCount($filter);
            } else {
                $res['opponensdb'] = 0;
            }
            $res['opponens'] = $res['opponensdb'] == 0;

            // Egy személy max. 5 helyen lehessen II-III-IV szerző
            $res['szerzo2db'] = $this->countSzerzo($this->params->getStringRequestParam('szerzo2email'), $anyag);
            $res['szerzo2'] = $res['szerzo2db'] < 5;
            $res['szerzo3db'] = $this->countSzerzo($this->params->getStringRequestParam('szerzo3email'), $anyag);
            $res['szerzo3'] = $res['szerzo3db'] < 5;
            $res['szerzo4db'] = $this->countSzerzo($this->params->getStringRequestParam('szerzo4email'), $anyag);
            $res['szerzo4'] = $res['szerzo4db'] < 5;
            $res['szerzo5db'] = $this->countSzerzo($this->params->getStringRequestParam('szerzo5email'), $anyag);
            $res['szerzo5'] = $res['szerzo5db'] < 5;
            $res['szerzo6db'] = $this->countSzerzo($this->params->getStringRequestParam('szerzo6email'), $anyag);
            $res['szerzo6'] = $res['szerzo6db'] < 5;
            $res['szerzo7db'] = $this->countSzerzo($this->params->getStringRequestParam('szerzo7email'), $anyag);
            $res['szerzo7'] = $res['szerzo7db'] < 5;
            $res['szerzo8db'] = $this->countSzerzo($this->params->getStringRequestParam('szerzo8email'), $anyag);
            $res['szerzo8'] = $res['szerzo8db'] < 5;
            $res['szerzo9db'] = $this->countSzerzo($this->params->getStringRequestParam('szerzo9email'), $anyag);
            $res['szerzo9'] = $res['szerzo9db'] < 5;
            $res['szerzo10db'] = $this->countSzerzo($this->params->getStringRequestParam('szerzo10email'), $anyag);
            $res['szerzo10'] = $res['szerzo10db'] < 5;

            // az opponens nem lehet szerzője azoknak a szimpózium előadásoknak amelyiknek opponense
            if ($isSzimpozium) {
                $eloadasids = [];
                for ($c = 1; $c < 5; $c++) {
                    if ($this->params->getIntRequestParam("eloadas$c")) {
                        $eloadasids[] = $this->params->getIntRequestParam("eloadas$c");
                    }
                }
                $filter->clear();
                $filter->addSql(
                    "((_xx.szerzo1email='$opponensemail') OR (_xx.szerzo2email='$opponensemail') OR " .
                    "(_xx.szerzo3email='$opponensemail') OR (_xx.szerzo4email='$opponensemail') OR (_xx.szerzo5email='$opponensemail') OR " .
                    "(_xx.szerzo6email='$opponensemail') OR (_xx.szerzo7email='$opponensemail') OR " .
                    "(_xx.szerzo8email='$opponensemail') OR (_xx.szerzo9email='$opponensemail') OR (_xx.szerzo10email='$opponensemail'))"
                );
                $filter->addFilter('id', 'IN', $eloadasids);
                $res['opponensszerzodb'] = $this->getRepo(MPTNGYSzakmaianyag::class)->getCount($filter);
            } else {
                $res['opponensszerzodb'] = 0;
            }
            $res['opponensszerzo'] = $res['opponensszerzodb'] == 0;

            $res['success'] = $res['elsoszerzo'] && $res['szimpoziumelnok'] && $res['opponens'] &&
                $res['szerzo2'] && $res['szerzo3'] && $res['szerzo4'] && $res['szerzo5'] &&
                $res['szerzo6'] && $res['szerzo7'] && $res['szerzo8'] && $res['szerzo9'] && $res['szerzo10'] &&
                $res['opponensszerzo'];
        }
        return $res;
    }

    public function adataim()
    {
        if ($this->checkloggedin()) {
            $v = $this->createMainView('adataim.tpl');
            $v->printTemplateResult();
        }
    }
}
