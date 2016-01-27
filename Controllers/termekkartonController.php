<?php

namespace Controllers;

use mkwhelpers\FilterDescriptor;

class termekkartonController extends \mkwhelpers\Controller {

    public function view() {
        $termekid = $this->params->getIntRequestParam('id');
        $termek = $this->getRepo('Entities\Termek')->find($termekid);

        $view = $this->createView('termekkarton.tpl');

        $view->setVar('pagetitle', t('Termék karton'));
        $view->setVar('datumtipus', 'teljesites');
        $view->setVar('termekid', $termekid);
        $view->setVar('termeknev', $termek->getNev());
        $view->setVar('cikkszam', $termek->getCikkszam());
        if ($termek) {
            $tc = new termekController($this->params);
            $view->setVar('valtozatlista', $tc->getValtozatList($termekid, null));
        }
        $rc = new raktarController($this->params);
        $view->setVar('raktarlista', $rc->getSelectList());

        $view->printTemplateResult(false);

    }

    public function refresh() {
        $termekid = $this->params->getIntRequestParam('termekid');
        $valtozatid = $this->params->getIntRequestParam('valtozatid');
        $mozgat = $this->params->getIntRequestParam('mozgat');
        $raktarid = $this->params->getIntRequestParam('raktar');
        $datumtipus = $this->params->getStringRequestParam('datumtipus');
        switch ($datumtipus) {
            case 'kelt':
            case 'teljesites':
            case 'esedekesseg':
                $datumtipus = 'bf.' . $datumtipus;
                break;
            default:
                $datumtipus = 'bf.kelt';
                break;
        }
        $datumtolstr = $this->params->getStringRequestParam('datumtol');
        $datumigstr = $this->params->getStringRequestParam('datumig');

        $filter = new FilterDescriptor();
        $filter->addFilter('bt.termek', '=', $termekid);
        if ($valtozatid) {
            $filter->addFilter('bt.termekvaltozat', '=', $valtozatid);
        }
        if ($datumtolstr) {
            $filter->addFilter($datumtipus, '>=', $datumtolstr);
        }
        if ($datumigstr) {
            $filter->addFilter($datumtipus, '<=', $datumigstr);
        }
        switch ($mozgat) {
            case 1:
                $filter->addFilter('bt.mozgat', '=', true);
                break;
            case 2:
                $filter->addFilter('bt.mozgat', '=', false);
                break;
        }
        if ($raktarid) {
            $filter->addFilter('bf.raktar', '=', $raktarid);
        }
        $tetelek = $this->getRepo('Entities\Termek')->getKarton($filter, array($datumtipus => 'ASC'));
        $kartontetelek = array();
        foreach($tetelek as $tetel) {
            $r = array(
                'tetel' => $tetel->toLista(),
                'fej' => $tetel->getBizonylatfej()->toLista()
            );
            $kartontetelek[] = $r;
        }

        $view = $this->createView('termekkartontetel.tpl');
        $view->setVar('kartontetelek', $kartontetelek);
        $view->printTemplateResult();
    }

}