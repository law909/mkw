<?php

namespace Controllers;

use Entities\Dolgozo;
use Entities\Jelenletiiv;
use Entities\Jelenlettipus;

class jelenletiivController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(Jelenletiiv::class);
        $this->setKarbFormTplName('jelenletiivkarbform.tpl');
        $this->setKarbTplName('jelenletiivkarb.tpl');
        $this->setListBodyRowTplName('jelenletiivlista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct();
    }

    protected function loadVars($t)
    {
        if (!$t) {
            $t = new \Entities\Jelenletiiv();
            $this->getEm()->detach($t);
        }
        $x = $this->getEntityFieldsArray($t);
        $x['datumstr'] = $t->getDatumStr();
        $x['dolgozo'] = $t->getDolgozoId();
        $x['dolgozonev'] = $t->getDolgozoNev();
        $x['jelenlettipus'] = $t->getJelenlettipusId();
        $x['jelenlettipusnev'] = $t->getJelenlettipusNev();
        $x['belepesstr'] = $t->getBelepesStr();
        $x['kilepesstr'] = $t->getKilepesStr();
        return $x;
    }

    public function setFields($obj)
    {
        $obj->setDatum($this->params->getStringRequestParam('datum'));
        $obj->setMunkaido($this->params->getIntRequestParam('munkaido'));
        $ck = \mkw\store::getEm()->getRepository(Dolgozo::class)->find($this->params->getIntRequestParam('dolgozo', 0));
        if ($ck) {
            $obj->setDolgozo($ck);
        }
        $ck = \mkw\store::getEm()->getRepository(Jelenlettipus::class)->find($this->params->getIntRequestParam('jelenlettipus', 0));
        if ($ck) {
            $obj->setJelenlettipus($ck);
        }
        return $obj;
    }

    public function getlistbody()
    {
        $view = $this->createView('jelenletiivlista_tbody.tpl');

        $filter = new \mkwhelpers\FilterDescriptor();

        if (!is_null($this->params->getRequestParam('tolfilter', null))) {
            $filter->addFilter('datum', '>=', \mkw\store::convDate(($this->params->getStringRequestParam('tolfilter', ''))));
        }
        if (!is_null($this->params->getRequestParam('igfilter', null))) {
            $filter->addFilter('datum', '<=', \mkw\store::convDate(($this->params->getStringRequestParam('igfilter', ''))));
        }
        $fv = $this->params->getIntRequestParam('dolgozofilter');
        if ($fv > 0) {
            $filter->addFilter('d.id', '=', $fv);
        }
        $fv = $this->params->getIntRequestParam('jelenlettipusfilter');
        if ($fv > 0) {
            $filter->addFilter('j.id', '=', $fv);
        }

        $this->initPager($this->getRepo()->getCount($filter));

        $egyedek = $this->getRepo()->getWithJoins(
            $filter,
            $this->getOrderArray(),
            $this->getPager()->getOffset(),
            $this->getPager()->getElemPerPage()
        );

        echo json_encode($this->loadDataToView($egyedek, 'egyedlista', $view));
    }

    public function viewselect()
    {
        $view = $this->createView('jelenletiivlista.tpl');

        $view->setVar('pagetitle', t('Jelenléti ívek'));
        $view->printTemplateResult(false);
    }

    public function viewlist()
    {
        $view = $this->createView('jelenletiivlista.tpl');

        $view->setVar('pagetitle', t('Jelenléti ívek'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $dolgozo = new dolgozoController();
        $view->setVar('dolgozolist', $dolgozo->getSelectList(0));
        $jt = new jelenlettipusController();
        $view->setVar('jelenlettipuslist', $jt->getSelectList(0));
        $view->printTemplateResult(false);
    }

    protected function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Jelenléti ív'));
        $view->setVar('formaction', '/admin/jelenletiiv/save');
        $view->setVar('oper', $oper);
        $record = $this->getRepo()->findWithJoins($id);
        $view->setVar('egyed', $this->loadVars($record));
        $dolgozo = new dolgozoController();
        $view->setVar('dolgozolist', $dolgozo->getSelectList(($record ? $record->getDolgozoId() : 0)));
        $jt = new jelenlettipusController();
        $view->setVar('jelenlettipuslist', $jt->getSelectList(($record ? $record->getJelenlettipusId() : 0)));
        return $view->getTemplateResult();
    }

    public function generatenapi()
    {
        $nap = $this->params->getStringRequestParam('datum', '');
        $jt = \mkw\store::getEm()->getRepository(Jelenlettipus::class)->find($this->params->getIntRequestParam('jt', 0));
        $egyedek = \mkw\store::getEm()->getRepository(Dolgozo::class)->getWithJoins([], []);
        foreach ($egyedek as $egyed) {
            if ($this->getRepo()->getCount('(_xx.datum=\'' . $nap . '\') AND (d.id=' . $egyed->getId() . ') AND (j.id=' . $jt->getId() . ')') == 0) {
                $jelen = new Jelenletiiv();
                $jelen->setDatum($nap);
                $jelen->setDolgozo($egyed);
                $jelen->setJelenlettipus($jt);
                $jelen->setMunkaido(8);
                $this->getEm()->persist($jelen);
            }
        }
        $this->getEm()->flush();
    }

    public function isDolgozoJelen($dolgozoid)
    {
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('datum', '=', \mkw\store::convDate(date(\mkw\store::$SQLDateFormat)));
        $filter->addFilter('dolgozo', '=', $dolgozoid);
        $filter->addSql('_xx.belepes IS NOT NULL');
        $jelen = false;
        $rec = $this->getRepo()->getAll($filter);
        foreach ($rec as $item) {
            $jelen = $jelen || ($item->getBelepes() && !$item->getKilepes());
        }
        return $jelen;
    }

    public function createBelepes()
    {
        $dolgozo = $this->getRepo(Dolgozo::class)->find($this->params->getIntRequestParam('dolgozo'));
        if ($dolgozo) {
            $jelenlet = new \Entities\Jelenletiiv();
            $jelenlet->setDolgozo($dolgozo);
            $jelenlet->setDatum(date(\mkw\store::$SQLDateFormat));
            $jelenlet->setBelepes(date(\mkw\store::$TimeFormat));
            $jelenlet->setJelenlettipus($this->getRepo(Jelenlettipus::class)->find(\mkw\store::getParameter(\mkw\consts::MunkaJelenlet)));
            $jelenlet->setBeip($_SERVER['REMOTE_ADDR']);
            $this->getEm()->persist($jelenlet);
            $this->getEm()->flush();
        }
    }

    public function createKilepes()
    {
        $dolgozo = $this->getRepo(Dolgozo::class)->find($this->params->getIntRequestParam('dolgozo'));
        if ($dolgozo) {
            $jelenlet = null;
            $jelenletek = $this->getRepo()->findBy(['dolgozo' => $dolgozo, 'datum' => new \DateTime(\mkw\store::convDate(date(\mkw\store::$SQLDateFormat)))]);
            foreach ($jelenletek as $j) {
                if ($j->getBelepes() && !$j->getKilepes()) {
                    $jelenlet = $j;
                }
            }
            if ($jelenlet) {
                $jelenlet->setKilepes(date(\mkw\store::$TimeFormat));
                $jelenlet->setKiip($_SERVER['REMOTE_ADDR']);
                $this->getEm()->persist($jelenlet);
                $this->getEm()->flush();
            }
        }
    }
}