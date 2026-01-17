<?php

namespace Controllers;

use Entities\Versenyzo;
use Entities\Csapat;
use mkw\store;

class versenyzoController extends \mkwhelpers\MattableController
{

    public function __construct($params)
    {
        $this->setEntityName(Versenyzo::class);
        $this->setKarbTplName('versenyzokarb.tpl');
        $this->setKarbFormTplName('versenyzokarbform.tpl');
        $this->setListBodyRowVarName('_versenyzo');
        $this->setListBodyRowTplName('versenyzolista_tbody_tr.tpl');
        parent::__construct($params);
    }

    public function loadVars($t, $forKarb = false)
    {
        $v = [];
        if (!$t) {
            $t = new Versenyzo();
            $this->getEm()->detach($t);
        }
        $v['id'] = $t->getId();
        $v['nev'] = $t->getNev();
        $v['slug'] = $t->getSlug();
        $v['versenysorozat'] = $t->getVersenysorozat();
        $v['rovidleiras'] = $t->getRovidleiras();
        $v['leiras'] = $t->getLeiras();
        $v['kepurl'] = $t->getKepurl();
        $v['kepurlsmall'] = $t->getKepurlSmall();
        $v['kepurlmini'] = $t->getKepurlMini();
        $v['kepleiras'] = $t->getKepleiras();
        $v['kepurl1'] = $t->getKepurl1();
        $v['kepurl1small'] = $t->getKepurl1Small();
        $v['kepleiras1'] = $t->getKepleiras1();
        $v['kepurl2'] = $t->getKepurl2();
        $v['kepurl2small'] = $t->getKepurl2Small();
        $v['kepleiras2'] = $t->getKepleiras2();
        $v['kepurl3'] = $t->getKepurl3();
        $v['kepurl3small'] = $t->getKepurl3Small();
        $v['kepleiras3'] = $t->getKepleiras3();
        $v['csapatid'] = $t->getCsapatId();
        $v['csapatnev'] = $t->getCsapatNev();
        return $v;
    }

    protected function setFields($obj)
    {
        $obj->setNev($this->params->getStringRequestParam('nev'));
        $obj->setVersenysorozat($this->params->getStringRequestParam('versenysorozat'));
        $obj->setRovidleiras($this->params->getStringRequestParam('rovidleiras'));
        $obj->setLeiras($this->params->getStringRequestParam('leiras'));
        $obj->setKepurl($this->params->getStringRequestParam('kepurl'));
        $obj->setKepleiras($this->params->getStringRequestParam('kepleiras'));
        $obj->setKepurl1($this->params->getStringRequestParam('kepurl1'));
        $obj->setKepleiras1($this->params->getStringRequestParam('kepleiras1'));
        $obj->setKepurl2($this->params->getStringRequestParam('kepurl2'));
        $obj->setKepleiras2($this->params->getStringRequestParam('kepleiras2'));
        $obj->setKepurl3($this->params->getStringRequestParam('kepurl3'));
        $obj->setKepleiras3($this->params->getStringRequestParam('kepleiras3'));
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
        $csapatctrl = new csapatController($this->params);
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
        $view->printTemplateResult();
    }

    public function show()
    {
        $slug = $this->params->getStringRequestParam('slug');
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('slug', '=', $slug);
        $record = $this->getRepo()->getWithJoins($filter, []);
        if ($record) {
            $record = $record[0];
        }
        $view = $this->createMainView('versenyzo.tpl');
        $view->setVar('versenyzo', $this->loadVars($record, true));
        \mkw\store::fillTemplate($view);
        $view->printTemplateResult();
    }
}
