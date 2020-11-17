<?php

namespace Controllers;

use Carbon\Carbon;
use Entities\Partner;
use mkw\store;
use mkwhelpers, Entities;

class pubadminController extends mkwhelpers\Controller {

    public function view() {
        $view = $this->createPubAdminView('main.tpl');
        $view->setVar('pagetitle', t('Főoldal'));

        $view->printTemplateResult();
    }

    public function getOralist() {
        $view = $this->createPubAdminView('oralist.tpl');

        $dolgozo = $this->getRepo('Entities\Dolgozo')->find(\mkw\store::getPubAdminSession()->pk);
        if ($dolgozo) {
            $datum = $this->params->getStringRequestParam('datum');
            $napszam = new \DateTime($datum);
            $filter = new \mkwhelpers\FilterDescriptor();
            $filter->addFilter('dolgozo', '=', $dolgozo);
            $filter->addFilter('nap', '=', $napszam->format('N'));
            $filter->addFilter('inaktiv', '=', false);
            $orak = $this->getRepo('Entities\Orarend')->getAll($filter);
            $oralista = [];
            /** @var Entities\Orarend $ora */
            foreach ($orak as $ora) {
                $oralista[] = [
                    'id' => $ora->getId(),
                    'nev' => $ora->getKezdetStr() . ' ' . $ora->getNev() . ' (' . $ora->getNapNev() . ')'
                ];
            }
            $filter->clear();
            $filter->addFilter('helyettesito', '=', $dolgozo);
            $filter->addFilter('datum', '=', $datum);
            $filter->addFilter('inaktiv', '=', false);
            $helyettek = $this->getRepo('Entities\Orarendhelyettesites')->getAll($filter);
            /** @var Entities\Orarendhelyettesites $helyett*/
            foreach ($helyettek as $helyett) {
                $oralista[] = [
                    'id' => $helyett->getId(),
                    'nev' => $helyett->getOrarendNev()
                ];
            }
            $view->setVar('oralista', $oralista);
        }
        $view->printTemplateResult();
    }

    public function getResztvevolist() {
        $resztvevolista = [];
        $oraid = $this->params->getIntRequestParam('oraid');
        $datum = $this->params->getStringRequestParam('datum');
        $ma = new Carbon();
        $datumdate = Carbon::createFromFormat(\mkw\store::$SQLDateFormat, $datum);
        if ($oraid) {

            /** @var \Entities\Termek $orajegytermek */
            $orajegytermek = $this->getRepo('Entities\Termek')->find(\mkw\store::getParameter(\mkw\consts::JogaOrajegyTermek));
            /** @var \Entities\Termek $berlet4termek */
            $berlet4termek = $this->getRepo('Entities\Termek')->find(\mkw\store::getParameter(\mkw\consts::JogaBerlet4Termek));
            /** @var \Entities\Termek $berlet10termek */
            $berlet10termek = $this->getRepo('Entities\Termek')->find(\mkw\store::getParameter(\mkw\consts::JogaBerlet10Termek));


            $filter = new \mkwhelpers\FilterDescriptor();
            $filter->addFilter('orarend', '=', $oraid);
            $filter->addFilter('datum', '=', $datum);
            $resztvevok = $this->getRepo('Entities\JogaBejelentkezes')->getAll($filter);

            /** @var \Entities\JogaBejelentkezes $resztvevo */
            foreach ($resztvevok as $resztvevo) {
                $rvtomb = [];
                $rvpartner = $this->getRepo('Entities\Partner')->findOneBy(['email' => $resztvevo->getPartneremail()]);
                if ($rvpartner) {
                    $rvtomb['nev'] = $resztvevo->getPartnernev();
                    $rvtomb['email'] = $resztvevo->getPartneremail();
                    $rvtomb['new'] = false;
                    $filter->clear();
                    $filter->addFilter('partner', '=', $rvpartner);
                    $filter->addFilter('lejart', '=', false);
                    $berletek = $this->getRepo('Entities\JogaBerlet')->getAll($filter, array('id' => 'ASC'));
                    if (count($berletek)) {
                        /** @var \Entities\JogaBerlet $berlet */
                        $berlet = $berletek[0];
                        $rvtomb['berlet'] = true;
                        $rvtomb['alkalom'] = $berlet->getAlkalom();
                        $rvtomb['elfogyottalkalom'] = $berlet->getElfogyottalkalom() + $berlet->getOfflineelfogyottalkalom();
                    }
                    else {
                        $rvtomb['berlet'] = false;
                    }
                }
                else {
                    $rvtomb['nev'] = $resztvevo->getPartnernev();
                    $rvtomb['email'] = $resztvevo->getPartneremail();
                    $rvtomb['new'] = true;
                    $rvtomb['berlet'] = false;
                }
                switch (true) {
                    case $resztvevo->getTipus() == 1:
                        $rvtomb['berlet'] = false;
                        $rvtomb['orajegy'] = true;
                        break;
                }
                $rvtomb['id'] = $resztvevo->getId();
                $rvtomb['megjelent'] = $resztvevo->isMegjelent();
                $rvtomb['mustbuy'] = !$rvtomb['berlet'] && !$rvtomb['orajegy'];
                /** @var Entities\Termek $termek */
                $termek = $this->getRepo('Entities\Termek')->find(\mkw\store::getParameter(\mkw\consts::JogaOrajegyTermek));
                if ($termek) {
                    $rvtomb['type1price'] = $termek->getBruttoArByArsav(null, 'normál');
                }
                $termek = $this->getRepo('Entities\Termek')->find(\mkw\store::getParameter(\mkw\consts::JogaBerlet4Termek));
                if ($termek) {
                    $rvtomb['type2price'] = $termek->getBruttoArByArsav(null, 'normál');
                }
                $termek = $this->getRepo('Entities\Termek')->find(\mkw\store::getParameter(\mkw\consts::JogaBerlet10Termek));
                if ($termek) {
                    $rvtomb['type3price'] = $termek->getBruttoArByArsav(null, 'normál');
                }
                $resztvevolista[] = $rvtomb;
            }
        }
        $view = $this->createPubAdminView('resztvevolist.tpl');
        $view->setVar('resztvevolist', $resztvevolista);
        $view->setVar('future', $ma->lessThan($datumdate));
        $view->printTemplateResult();
    }

    public function setResztvevoMegjelent() {
        /** @var \Entities\JogaBejelentkezes $rv */
        $rv = $this->getRepo('Entities\JogaBejelentkezes')->find($this->params->getIntRequestParam('id'));
        if ($rv) {
            $megje = $rv->isMegjelent();
            $rv->setMegjelent(!$rv->isMegjelent());
            $this->getEm()->persist($rv);
            $this->getEm()->flush();
            if ($megje) {
                $rv->delJogaReszvetel();
            }
            else {
                $rv->createJogaReszvetel();
            }
        }
    }

    public function setResztvevoOrajegy() {
        $type = $this->params->getIntRequestParam('type');
        $price = $this->params->getNumRequestParam('price');
        /** @var \Entities\JogaBejelentkezes $rv */
        $rv = $this->getRepo('Entities\JogaBejelentkezes')->find($this->params->getIntRequestParam('id'));
        if ($rv) {
            $rv->setTipus($type);
            $rv->setAr($price);
            $this->getEm()->persist($rv);
            $this->getEm()->flush();

            if ($type === 2 || $type === 3) {
                $berlet = new Entities\JogaBerlet();
                $rvpartner = \mkw\store::getEm()->getRepository('Entities\Partner')->findOneBy(['email' => $rv->getPartneremail()]);
                if (!$rvpartner) {
                    $rvpartner = new Partner();
                    $rvpartner->setEmail($rv->getPartneremail());
                    $rvpartner->setNev($rv->getPartnernev());
                    $rvpartner->setVezeteknev($rv->getPartnerVezeteknev());
                    $rvpartner->setKeresztnev($rv->getPartnerKeresztnev());
                    \mkw\store::getEm()->persist($rvpartner);
                    \mkw\store::getEm()->flush();
                }
                $berlet->setPartner($rvpartner);
                switch ($type) {
                    case 2:
                        $termek = $this->getRepo('Entities\Termek')->find(\mkw\store::getParameter(\mkw\consts::JogaBerlet4Termek));
                        break;
                    case 3:
                        $termek = $this->getRepo('Entities\Termek')->find(\mkw\store::getParameter(\mkw\consts::JogaBerlet10Termek));
                        break;
                }
                $berlet->setTermek($termek);
                $berlet->setBruttoegysar($price);
                $berlet->setVasarlasnapja();
                $this->getEm()->persist($berlet);
                $this->getEm()->flush();
            }

        }
    }

    public function getPartnerData() {
        $result = [];
        $q = $this->params->getStringRequestParam('q');
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter(array('nev', 'keresztnev', 'vezeteknev'), 'like', '%' . $q . '%');
        $partnerek = $this->getRepo('Entities\Partner')->getAll($filter, array('nev' => 'ASC'));
        /** @var \Entities\Partner $partner */
        foreach ($partnerek as $partner) {
            $result[] = [
                'value' => $partner->getId(),
                'text' => $partner->getNev() . ' (' . $partner->getEmail() . ')'
            ];
        }
        header('Content-Type: application/json');
        echo json_encode($result);
    }

    public function newBejelentkezes() {
        $oraid = $this->params->getIntRequestParam('oraid');
        $ora = $this->getRepo('Entities\Orarend')->find($oraid);
        $datum = $this->params->getStringRequestParam('datum');
        $partnerid = $this->params->getIntRequestParam('partnerid');
        /** @var Entities\Partner $partner */
        $partner = $this->getRepo('Entities\Partner')->find($partnerid);
        if ($partner && $ora) {
            $obj = new \Entities\JogaBejelentkezes();
            $obj->setDatum($datum);
            $obj->setPartnernev($partner->getNev());
            $obj->setPartneremail($partner->getEmail());
            $obj->setOrarend($ora);
            $this->getEm()->persist($obj);
            $this->getEm()->flush();
        }
    }

    public function newBejelentkezesWNewPartner() {
        $oraid = $this->params->getIntRequestParam('oraid');
        $ora = $this->getRepo('Entities\Orarend')->find($oraid);
        $datum = $this->params->getStringRequestParam('datum');
        if ($ora) {
            $obj = new \Entities\JogaBejelentkezes();
            $obj->setDatum($datum);
            $obj->setPartnernev($this->params->getStringRequestParam('nev'));
            $obj->setPartneremail($this->params->getStringRequestParam('email'));
            $obj->setOrarend($ora);
            $this->getEm()->persist($obj);
            $this->getEm()->flush();
        }
    }
}