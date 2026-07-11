<?php

namespace Controllers;

use Entities\Csapat;
use Entities\CsapatKep;
use mkw\store;

class csapatController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(Csapat::class);
        $this->setKarbTplName('csapatkarb.tpl');
        $this->setKarbFormTplName('csapatkarbform.tpl');
        $this->setListBodyRowVarName('_csapat');
        $this->setListBodyRowTplName('csapatlista_tbody_tr.tpl');
        parent::__construct();
    }

    public function loadVars($t, $forKarb = false)
    {
        $kepCtrl = new csapatkepController();
        $kep = [];
        if (!$t) {
            $t = new Csapat();
            $this->getEm()->detach($t);
        }
        $v = $this->getEntityFieldsArray($t);
        $v['logourlsmall'] = $t->getLogourlSmall();
        $v['logourlmini'] = $t->getLogourlMini();
        $v['logourlmedium'] = $t->getLogourlMedium();
        $v['logourllarge'] = $t->getLogourlLarge();
        $v['logourl400'] = $t->getLogourl400();
        $v['logourl2000'] = $t->getLogourl2000();
        $v['kepurlsmall'] = $t->getKepurlSmall();
        $v['kepurlmini'] = $t->getKepurlMini();
        $v['kepurlmedium'] = $t->getKepurlMedium();
        $v['kepurllarge'] = $t->getKepurlLarge();
        $v['kepurl400'] = $t->getKepurl400();
        $v['kepurl2000'] = $t->getKepurl2000();
        foreach ($t->getCsapatKepek() as $kepje) {
            $kep[] = $kepCtrl->loadVars($kepje);
        }
        $v['kepek'] = $kep;
        if (!$forKarb) {
            $v['versenyzok'] = [];
            $vctrl = new versenyzoController();
            foreach ($t->getVersenyzok() as $versenyzo) {
                $v['versenyzok'][] = $vctrl->loadVars($versenyzo);
            }
        }
        return $v;
    }

    protected function setFields($obj)
    {
        $obj = $this->setEntityFieldsFromRequest($obj);
        $kepids = $this->params->getArrayRequestParam('kepid');
        foreach ($kepids as $kepid) {
            if ($this->params->getStringRequestParam('kepurl_' . $kepid, '') !== '') {
                $oper = $this->params->getStringRequestParam('kepoper_' . $kepid);
                if ($oper == 'add') {
                    $kep = new \Entities\CsapatKep();
                    $obj->addCsapatKep($kep);
                    $kep->setUrl($this->params->getStringRequestParam('kepurl_' . $kepid));
                    $kep->setLeiras($this->params->getStringRequestParam('kepleiras_' . $kepid));
                    $kep->setRejtett($this->params->getBoolRequestParam('keprejtett_' . $kepid));
                    $this->getEm()->persist($kep);
                } elseif ($oper == 'edit') {
                    /** @var CsapatKep $kep */
                    $kep = \mkw\store::getEm()->getRepository(CsapatKep::class)->find($kepid);
                    if ($kep) {
                        $kep->setUrl($this->params->getStringRequestParam('kepurl_' . $kepid));
                        $kep->setLeiras($this->params->getStringRequestParam('kepleiras_' . $kepid));
                        $kep->setRejtett($this->params->getBoolRequestParam('keprejtett_' . $kepid));
                        $this->getEm()->persist($kep);
                    }
                }
            }
        }
        return $obj;
    }

    public function getlistbody()
    {
        $view = $this->createView('csapatlista_tbody.tpl');

        $filter = new \mkwhelpers\FilterDescriptor();
        if (!is_null($this->params->getRequestParam('nevfilter', null))) {
            $filter->addFilter('nev', 'LIKE', '%' . $this->params->getStringRequestParam('nevfilter') . '%');
        }

        $this->initPager(
            $this->getRepo()->getCount($filter),
            $this->params->getIntRequestParam('elemperpage', 30),
            $this->params->getIntRequestParam('pageno', 1)
        );

        $crec = $this->getRepo()->getAll($filter, $this->getOrderArray(), $this->getPager()->getOffset(), $this->getPager()->getElemPerPage());

        echo json_encode($this->loadDataToView($crec, 'csapatlista', $view));
    }

    public function viewlist()
    {
        $view = $this->createView('csapatlista.tpl');
        $view->setVar('pagetitle', t('Csapatok'));
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
        $record = $this->getRepo()->findWithJoins($id);
        $this->setVars($view);
        $view->setVar('csapat', $this->loadVars($record, true));
        $view->printTemplateResult();
    }

    public function getSelectList($selid = null)
    {
        $csapatrepo = $this->getEm()->getRepository(Csapat::class);
        $csapatlist = $csapatrepo->getWithJoins([], ['nev' => 'ASC']);
        $csapatok = [];
        foreach ($csapatlist as $csapat) {
            $csapatok[] = [
                'id' => $csapat->getId(),
                'caption' => $csapat->getNev(),
                'nev' => $csapat->getNev(),
                'selected' => ($csapat->getId() == $selid)
            ];
        }
        return $csapatok;
    }

    public function getListAsArray()
    {
        $crec = $this->getRepo()->getWithJoins([], ['nev' => 'ASC']);
        $res = [];
        foreach ($crec as $csapat) {
            $res[] = $this->loadVars($csapat);
        }

        return $res;
    }

    public function index()
    {
        $view = $this->createMainView('csapatlist.tpl');
        $view->setVar('csapatlista', $this->getListAsArray());
        \mkw\store::fillTemplate($view);
        $view->printTemplateResult();
    }

    public function show()
    {
        $com = $this->params->getStringParam('slug');
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('slug', '=', $com);
        $csapat = $this->getRepo()->getWithJoins($filter);
        if ($csapat) {
            $csapat = $csapat[0];
        }
        $view = $this->createMainView('csapat.tpl');
        $view->setVar('csapat', $this->loadVars($csapat));
        \mkw\store::fillTemplate($view);
        $view->printTemplateResult();
    }
}
