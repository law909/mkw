<?php

namespace Controllers;

use Doctrine\ORM\Query\ResultSetMapping;
use mkw\store;
use mkwhelpers\FilterDescriptor;

class munkaidolistaController extends \mkwhelpers\MattableController {

    private $tolstr;
    private $igstr;
    private $dolgozonev;

    public function view() {
        $view = $this->createView('munkaidolista.tpl');

        $view->setVar('toldatum', date(\mkw\store::$DateFormat));
        $view->setVar('igdatum', date(\mkw\store::$DateFormat));

        $d = new dolgozoController($this->params);
        $view->setVar('dolgozolist', $d->getSelectList());

        $view->printTemplateResult();
    }

    protected function createFilter($tol, $ig, $dolgozo) {
        $filter = new FilterDescriptor();

        $this->tolstr = date(\mkw\store::$DateFormat, strtotime(\mkw\store::convDate($tol)));

        $this->igstr = date(\mkw\store::$DateFormat, strtotime(\mkw\store::convDate($ig)));

        if ($dolgozo) {
            $filter->addFilter('dolgozo', '=', $dolgozo);
            $d = $this->getRepo('Entities\Dolgozo')->find($dolgozo);
            if ($d) {
                $this->dolgozonev = $d->getNev();
            }
        }

        $filter
            ->addFilter('dolgozo', '=', $dolgozo)
            ->addFilter('datum', '>=', $this->tolstr)
            ->addFilter('datum', '<=', $this->igstr);
        return $filter;
    }

    public function getData() {
        $tol = $this->params->getStringRequestParam('tol');
        $ig = $this->params->getStringRequestParam('ig');
        $dolgozo = $this->params->getIntRequestParam('dolgozo');
        $filter = $this->createFilter($tol, $ig, $dolgozo);

        $mind = $this->getRepo('Entities\Jelenletiiv')->getAll($filter, array('datum' => 'ASC', 'belepes' => 'ASC'));

        $ret = array();
        /** @var \Entities\Jelenletiiv $m */
        foreach ($mind as $m) {
            /** @var \DateInterval $difi */
            $difi = $m->getBelepes()->diff($m->getKilepes());
            $ret[] = array(
                'datum' => $m->getDatumStr(),
                'belepes' => $m->getBelepesStr(),
                'kilepes' => $m->getKilepesStr(),
                'jelenlettipus' => $m->getJelenlettipusNev(),
                'dolgozonev' => $m->getDolgozoNev(),
                'ido' => $difi->h * 60 + $difi->i
            );
        }
        return $ret;
    }

    public function createLista() {

        $d = $this->getData();
        $report = $this->createView('rep_munkaido.tpl');
        $report->setVar('lista', $d);
        $report->setVar('tolstr', $this->tolstr);
        $report->setVar('igstr', $this->igstr);
        $report->setVar('dolgozonev', $this->dolgozonev);
        $report->printTemplateResult();
    }

}