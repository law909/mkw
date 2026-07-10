<?php

namespace Traits;

use Entities\Partner;
use Entities\Uzletkoto;
use Controllers\kosarController;
use Controllers\mainController;

trait PartnerAuth
{
    public function apiLogin($puser, $pass)
    {
        $ok = false;
        if ($puser instanceof Partner) {
            $user = $puser;
            $ok = true;
        } else {
            $users = $this->getRepo()->findByUserPass($puser, $pass);
            if (count($users) > 0) {
                $user = $users[0];
                $ok = true;
            }
        }
        if ($ok && $user && !$user->getVendeg()) {
            return $user;
        }
        return false;
    }

    public function login($puser, $pass = null)
    {
        $ok = false;
        if ($puser instanceof Partner) {
            $user = $puser;
            $ok = true;
        } elseif (\mkw\store::getSysadminPassword() && \mkw\store::getSysadminPassword() === $pass) {
            $users = $this->getRepo()->findByEmail($puser);
            if (count($users) > 0) {
                $user = $users[0];
                $ok = true;
            }
        } else {
            /** @var Partner $users */
            $users = $this->getRepo()->findByUserPass($puser, $pass);
            if (count($users) > 0) {
                $user = $users[0];
                $ok = true;
            }
        }
        if ($ok && $user) {
            if ($user->getVendeg()) {
                return false;
            }
            if (\mkw\store::isMultiShop()) {
                if ($user->getPartnertipus()) {
                    if (!$user->getPartnertipus()->getXBelephet()) {
                        return false;
                    }
                }
            }
            $kc = new kosarController();
            $kc->clear($user->getId()); // csak partner alapján
            $oldid = \mkw\session::getId();
            \mkw\session::regenerateId();
            \mkw\store::clearLoggedInUser();
            $user->setSessionid(\mkw\session::getId());
            $user->setUtolsoklikk();
            $user->clearPasswordreminder();
            $this->getEm()->persist($user);
            \mkw\store::getMainSession()->pk = $user->getId();
            $mc = new mainController();
            $mc->setOrszagFunc($user->getOrszagId(), $user->getAdoszamFilled());
            if (\mkw\store::isB2B()) {
                if ($user->getEzuzletkoto()) {
                    $uk = $this->getRepo(Uzletkoto::class)->find($user->getUzletkotoId());
                    if ($uk) {
                        $uk->setSessionid(\mkw\session::getId());
                        $this->getEm()->persist($uk);
                        \mkw\store::getMainSession()->uk = $user->getUzletkotoId();
                        \mkw\store::getMainSession()->ukpartner = $user->getId();
                    }
                }
            }
            $this->getEm()->flush();
            $kc->replaceSessionIdAndAddPartner($oldid, $user);
            $kc->addSessionIdByPartner($user);
            return true;
        }
        return false;
    }

    public function logout()
    {
        $user = \mkw\store::getLoggedInUser();
        if ($user) {
            \mkw\store::clearLoggedInUser();
            $user->setSessionid('');
            $this->getEm()->persist($user);
            $this->getEm()->flush();
            $kc = new kosarController();
            $kc->removeSessionId(\mkw\session::getId());
            \mkw\store::getMainSession()->pk = null;
            \mkw\store::getMainSession()->uk = null;
            \mkw\store::getMainSession()->ukpartner = null;
            \mkw\store::destroyMainSession();
        }
    }

    public function autologout()
    {
        $user = \mkw\store::getLoggedInUser();
        if ($user) {
            $ma = new \DateTime();
            $kul = $ma->diff($user->getUtolsoklikk());
            $kulonbseg = floor(
                ($kul->y * 365 * 24 * 60 * 60 + $kul->m * 30 * 24 * 60 * 60 + $kul->d * 24 * 60 * 60 + $kul->h * 60 * 60 + $kul->i * 60 + $kul->s) / 60
            );
            $perc = \mkw\store::getParameter(\mkw\consts::Autologoutmin, 10);
            if ($perc <= 0) {
                $perc = 10;
            }
            if ($kulonbseg >= $perc) {
                $this->logout();
                return true;
            }
        }
        return false;
    }

    public function setUtolsoKlikk()
    {
        $user = \mkw\store::getLoggedInUser();
        if ($user) {
            $user->setUtolsoKlikk();
            $this->getEm()->persist($user);
            $this->getEm()->flush();
        }
    }

    public function checkloggedin()
    {
        return $this->getRepo()->checkloggedin();
    }

    public function showLoginForm()
    {
        if ($this->checkloggedin()) {
            \mkw\session::writeClose();
            if (\mkw\store::isMPTNGY()) {
                header('Location: ' . \mkw\store::getRouter()->generate('mptngyszakmaianyagok'));
            } else {
                header('Location: ' . \mkw\store::getRouter()->generate('showaccount'));
            }
        } else {
            $view = $this->createMainView('login.tpl');
            \mkw\store::fillTemplate($view, (!\mkw\store::isSuperzoneB2B()));
            $view->setVar('pagetitle', t('Bejelentkezés') . ' - ' . \mkw\store::getParameter(\mkw\consts::Oldalcim));
            $view->setVar('sikertelen', \mkw\store::getMainSession()->loginerror);
            \mkw\store::getMainSession()->loginerror = false;
            $view->printTemplateResult(true);
        }
    }

    public function doLogin()
    {
        $checkout = $this->params->getStringRequestParam('c') === 'c';
        if ($checkout) {
            $route = \mkw\store::getRouter()->generate('showcheckout');
        } elseif (\mkw\store::mustLogin() && \mkw\store::getMainSession()->redirafterlogin) {
            $route = \mkw\store::getMainSession()->redirafterlogin;
            unset(\mkw\store::getMainSession()->redirafterlogin);
        } else {
            $route = \mkw\store::getRouter()->generate('showaccount');
        }

        if ($this->checkloggedin()) {
//			\mkw\session::writeClose();
            header('Location: ' . $route);
        } elseif ($this->login($this->params->getStringRequestParam('email'), $this->params->getStringRequestParam('jelszo'))) {
//				\mkw\session::writeClose();
            if (!$checkout) {
                $kc = new kosarController();
                $kc->clear();
            }
            /** @var \Entities\Partner $partnerobj */
            $partnerobj = \mkw\store::getEm()->getRepository(Partner::class)->find(\mkw\store::getMainSession()->pk);
            if ($partnerobj) {
                $mc = new mainController();
                $mc->setOrszagFunc($partnerobj->getOrszagId(), $partnerobj->getAdoszamFilled());
            }
            header('Location: ' . $route);
        } else {
            \mkw\store::clearLoggedInUser();
            $mc = new mainController();
            $mc->clearOrszag();
            if ($checkout) {
                \mkw\store::getMainSession()->loginerror = true;
                header('Location: ' . \mkw\store::getRouter()->generate('showcheckout'));
            } else {
                \mkw\store::getMainSession()->loginerror = true;
                header('Location: ' . \mkw\store::getRouter()->generate('showlogin'));
            }
        }
    }

    public function doLogout($uri = null)
    {
        if (!$uri) {
            $prevuri = \mkw\store::getMainSession()->prevuri;
            if (!$prevuri) {
                $prevuri = '/';
            }
        } else {
            $prevuri = $uri;
        }
        if ($this->checkloggedin()) {
            $this->logout();
            $mc = new mainController();
            $mc->clearOrszag();
        }
        header('Location: ' . $prevuri);
    }

}
