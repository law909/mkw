<?php

namespace Controllers;

use Entities\Versenyzo;
use Entities\Csapat;
use mkw\store;

class versenyzoController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(Versenyzo::class);
        $this->setKarbTplName('versenyzokarb.tpl');
        $this->setKarbFormTplName('versenyzokarbform.tpl');
        $this->setListBodyRowVarName('_versenyzo');
        $this->setListBodyRowTplName('versenyzolista_tbody_tr.tpl');
        parent::__construct();
    }

    public function loadVars($t, $forKarb = false)
    {
        $v = [];
        if (!$t) {
            $t = new Versenyzo();
            $this->getEm()->detach($t);
        }
        $v = $this->getEntityFieldsArray($t);
        $v['kepurllarge'] = $t->getKepurlLarge();
        $v['kepurlmedium'] = $t->getKepurlMedium();
        $v['kepurlsmall'] = $t->getKepurlSmall();
        $v['kepurlmini'] = $t->getKepurlMini();
        $v['kepurl400'] = $t->getKepurl400();
        $v['kepurl2000'] = $t->getKepurl2000();

        $v['kepurl1small'] = $t->getKepurl1Small();
        $v['kepurl1medium'] = $t->getKepurl1Medium();
        $v['kepurl1large'] = $t->getKepurl1Large();
        $v['kepurl1mini'] = $t->getKepurl1Mini();
        $v['kepurl1400'] = $t->getKepurl1400();
        $v['kepurl12000'] = $t->getKepurl12000();

        $v['kepurl2small'] = $t->getKepurl2Small();
        $v['kepurl2medium'] = $t->getKepurl2Medium();
        $v['kepurl2large'] = $t->getKepurl2Large();
        $v['kepurl2mini'] = $t->getKepurl2Mini();
        $v['kepurl2400'] = $t->getKepurl2400();
        $v['kepurl22000'] = $t->getKepurl22000();

        $v['kepurl3small'] = $t->getKepurl3Small();
        $v['kepurl3medium'] = $t->getKepurl3Medium();
        $v['kepurl3large'] = $t->getKepurl3Large();
        $v['kepurl3mini'] = $t->getKepurl3Mini();
        $v['kepurl3400'] = $t->getKepurl3400();
        $v['kepurl32000'] = $t->getKepurl32000();
        
        $v['csapatid'] = $t->getCsapat()?->getId();
        $v['csapatnev'] = $t->getCsapat()?->getNev();
        return $v;
    }

    protected function setFields($obj)
    {
        $obj = $this->setEntityFieldsFromRequest($obj);
        $csapat = $this->getEm()->getRepository(Csapat::class)->find($this->params->getIntRequestParam('csapat', 0));
        if ($csapat) {
            $obj->setCsapat($csapat);
        } else {
            $obj->setCsapat(null);
        }
        return $obj;
    }

    public function getlistbody()
    {
        $view = $this->createView('versenyzolista_tbody.tpl');

        $filter = new \mkwhelpers\FilterDescriptor();
        if (!is_null($this->params->getRequestParam('nevfilter', null))) {
            $filter->addFilter('nev', 'LIKE', '%' . $this->params->getStringRequestParam('nevfilter') . '%');
        }

        $this->initPager(
            $this->getRepo()->getCount($filter),
            $this->params->getIntRequestParam('elemperpage', 30),
            $this->params->getIntRequestParam('pageno', 1)
        );

        $crec = $this->getRepo()->getWithJoins($filter, $this->getOrderArray(), $this->getPager()->getOffset(), $this->getPager()->getElemPerPage());

        echo json_encode($this->loadDataToView($crec, 'versenyzolista', $view));
    }

    public function viewlist()
    {
        $view = $this->createView('versenyzolista.tpl');
        $view->setVar('pagetitle', t('Versenyzők'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('oper', $oper);

        $record = $this->getRepo()->findWithJoins(['id' => $id], []);
        $this->setVars($view);
        $view->setVar('versenyzo', $this->loadVars($record, true));
        $csapatctrl = new csapatController();
        $view->setVar('csapatlist', $csapatctrl->getSelectList($record?->getCsapatId()));
        $view->printTemplateResult();
    }

    public function getListAsArray()
    {
        $riders = $this->getRepo()->getWithJoins([], ['nev' => 'ASC']);
        $versenyzok = [];
        foreach ($riders as $rider) {
            $versenyzok[] = $this->loadVars($rider);
        }
        return $versenyzok;
    }

    public function index()
    {
        $view = $this->createMainView('versenyzolist.tpl');
        $view->setVar('versenyzolista', $this->getListAsArray());
        \mkw\store::fillTemplate($view);
        $this->setBreadcrumb($view, [['caption' => t('Szponzorált versenyzők')]]);
        $view->setVar('pagetitle', t('Szponzorált versenyzők') . ' | ' . \mkw\store::getParameter(\mkw\consts::Oldalcim));
        $view->setVar('seodescription', t('Azok a versenyzők, akiket a Mugen Race támogat.'));
        $view->printTemplateResult();
    }

    public function show()
    {
        $slug = $this->params->getStringParam('slug');
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('slug', '=', $slug);
        $record = $this->getRepo()->getWithJoins($filter, []);
        if ($record) {
            $record = $record[0];
        }
        if (!$record) {
            \mkw\store::redirectTo404($slug);
            return;
        }
        $view = $this->createMainView('versenyzo.tpl');
        $versenyzo = $this->loadVars($record, true);
        $view->setVar('versenyzo', $versenyzo);
        \mkw\store::fillTemplate($view);
        $this->setBreadcrumb($view, [
            ['caption' => t('Szponzorált versenyzők'), 'url' => \mkw\store::getRouter()->generate('versenyzoindex')],
            ['caption' => $versenyzo['nev']],
        ]);
        $view->setVar('pagetitle', $versenyzo['nev'] . ' | ' . \mkw\store::getParameter(\mkw\consts::Oldalcim));
        $view->setVar(
            'seodescription',
            \Services\SeoService::plainText($versenyzo['rovidleiras'] ?: $versenyzo['leiras'], 160)
        );
        $this->setOpenGraph($view, 'profile', $versenyzo['kepurl1large'] ?: $versenyzo['kepurllarge'], $versenyzo['nev']);
        $view->setVar(
            'versenyzojsonld',
            \Services\SeoService::personJsonLd($versenyzo, \Services\SeoService::getCanonicalUrl())
        );
        $view->printTemplateResult();
    }
}
