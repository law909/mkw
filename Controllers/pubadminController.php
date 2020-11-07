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
            $filter->addFilter('datum', '=', $mainap);
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

}