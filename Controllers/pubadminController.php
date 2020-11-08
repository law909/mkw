<?php

namespace Controllers;

use Carbon\Carbon;
use mkw\store;
use mkwhelpers, Entities;

class pubadminController extends mkwhelpers\Controller {

    public function view() {
        $view = $this->createPubAdminView('main.tpl');
        $view->setVar('pagetitle', t('Főoldal'));

        $dolgozo = $this->getRepo('Entities\Dolgozo')->find(\mkw\store::getPubAdminSession()->pk);
        if ($dolgozo) {
            $mainap = new \DateTime();
            $filter = new \mkwhelpers\FilterDescriptor();
            $filter->addFilter('dolgozo', '=', $dolgozo);
            $filter->addFilter('nap', '=', $mainap->format('N'));
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
            $filter->addFilter('datum', '=', $mainap->format(\mkw\store::$SQLDateFormat));
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
        $oraid = $this->params->getIntRequestParam('oraid');
        $resztvevolista = [];
        if ($oraid) {
            $mainap = new \DateTime();
            $filter = new \mkwhelpers\FilterDescriptor();
            $filter->addFilter('orarend', '=', $oraid);
            $filter->addFilter('datum', '=', $mainap->format(\mkw\store::$SQLDateFormat));
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
                    $berletek = $this->getRepo('Entities\JogaBerlet')->getAll($filter);
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
                $resztvevolista[] = $rvtomb;
            }
        }
        $view = $this->createPubAdminView('resztvevolist.tpl');
        $view->setVar('resztvevolist', $resztvevolista);
        $view->printTemplateResult();
    }
}