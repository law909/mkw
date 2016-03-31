<?php

namespace Controllers;

use mkwhelpers\Filter;
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
        $partner = new partnerController($this->params);
        $view->setVar('partnerlist', $partner->getSelectList());

        $view->printTemplateResult(false);

    }

    public function refresh() {
        $termekid = $this->params->getIntRequestParam('termekid');
        $valtozatid = $this->params->getIntRequestParam('valtozatid');
        $mozgat = $this->params->getIntRequestParam('mozgat');
        $rontott = $this->params->getIntRequestParam('rontott');
        $raktarid = $this->params->getIntRequestParam('raktarid');
        $partnerid = $this->params->getIntRequestParam('partnerid');
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

        $nyitofilter = new FilterDescriptor();
        $filter = new FilterDescriptor();
        $nyitofilter->addFilter('bt.termek', '=', $termekid);
        $filter->addFilter('bt.termek', '=', $termekid);
        if ($valtozatid) {
            $nyitofilter->addFilter('bt.termekvaltozat', '=', $valtozatid);
            $filter->addFilter('bt.termekvaltozat', '=', $valtozatid);
        }
        if ($datumtolstr) {
            $nyitofilter->addFilter($datumtipus, '<', $datumtolstr);
            $filter->addFilter($datumtipus, '>=', $datumtolstr);
        }
        if ($datumigstr) {
            $filter->addFilter($datumtipus, '<=', $datumigstr);
        }
        switch ($mozgat) {
            case 1:
                $nyitofilter->addFilter('bt.mozgat', '=', true);
                $filter->addFilter('bt.mozgat', '=', true);
                break;
            case 2:
                $nyitofilter->addFilter('bt.mozgat', '=', false);
                $filter->addFilter('bt.mozgat', '=', false);
                break;
        }
        switch ($rontott) {
            case 1:
                break;
            case 2:
                $nyitofilter->addFilter('bf.rontott', '<>', true);
                $filter->addFilter('bf.rontott', '<>', true);
                break;
        }
        if ($raktarid) {
            $nyitofilter->addFilter('bf.raktar', '=', $raktarid);
            $filter->addFilter('bf.raktar', '=', $raktarid);
        }
        if ($partnerid) {
            $nyitofilter->addFilter('bf.partner', '=', $partnerid);
            $filter->addFilter('bf.partner', '=', $partnerid);
        }
        if ($datumtolstr) {
            $nyito = $this->getRepo('Entities\Termek')->calcKeszlet($nyitofilter);
            $nyito = $nyito[0];
        }
        else {
            $nyito = array('mennyiseg' => 0, 'nettohuf' => 0, 'bruttohuf' => 0);
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
        $view->setVar('maintheme', \mkw\Store::getTheme());
        $view->setVar('nyito', $nyito['mennyiseg']);
        $view->setVar('kartontetelek', $kartontetelek);
        $view->printTemplateResult();
    }

}