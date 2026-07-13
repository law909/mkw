<?php

namespace Controllers;

use Entities\Partner;
use Entities\Termek;
use Entities\Termekcimkekat;
use Entities\Termekcimketorzs;
use mkw\store;

class termekcimkeController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(Termekcimketorzs::class);
        $this->setKarbFormTplName('cimkekarbform.tpl');
        $this->setKarbTplName('cimkekarb.tpl');
        $this->setListBodyRowTplName('cimkelista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_cimke');
        parent::__construct();
    }

    protected function loadVars($t)
    {
        if (!$t) {
            $t = new \Entities\Termekcimketorzs();
            $this->getEm()->detach($t);
        }
        $x = $this->getEntityFieldsArray($t);
        $x['cimkekatnev'] = $t->getKategoria()?->getNev();
        $x['kepurlsmall'] = $t->getKepurlSmall();
        $x['kepurlmedium'] = $t->getKepurlMedium();
        $x['kepurllarge'] = $t->getKepurlLarge();
        $x['gyartonev'] = $t->getGyarto()?->getNev();
        return $x;
    }

    protected function setFields($obj)
    {
        $obj = $this->setEntityFieldsFromRequest($obj, ['raw' => ['leiras']]);
        $ck = store::getEm()->getRepository(Termekcimkekat::class)->find($this->params->getIntRequestParam('cimkecsoport'));
        if ($ck) {
            $obj->setKategoria($ck);
        }
        $ck = \mkw\store::getEm()->getRepository(Partner::class)->find($this->params->getIntRequestParam('gyarto'));
        if ($ck) {
            $obj->setGyarto($ck);
        } else {
            $obj->setGyarto(null);
        }
        return $obj;
    }

    public function getlistbody()
    {
        $view = $this->createView('cimkelista_tbody.tpl');
        $view->setVar('cimketipus', 'termek');

        $filter = new \mkwhelpers\FilterDescriptor();
        if (!is_null($this->params->getRequestParam('nevfilter', null))) {
            $filter->addFilter('nev', 'LIKE', '%' . $this->params->getStringRequestParam('nevfilter') . '%');
        }
        $fv = $this->params->getIntRequestParam('ckfilter');
        if ($fv > 0) {
            $filter->addFilter('ck.id', '=', $fv);
        }
        if (!is_null($this->params->getRequestParam('gyartofilter', null))) {
            $filter->addFilter('gyarto', '=', $this->params->getIntRequestParam('gyartofilter'));
        }

        $this->initPager($this->getRepo()->getCount($filter));

        $egyedek = $this->getRepo()->getWithJoins(
            $filter,
            $this->getOrderArray(),
            $this->getPager()->getOffset(),
            $this->getPager()->getElemPerPage()
        );

        echo json_encode($this->loadDataToView($egyedek, 'cimkelista', $view));
    }

    public function viewlist()
    {
        $view = $this->createView('cimkelista.tpl');

        $view->setVar('pagetitle', t('Termékcímkék'));
        $view->setVar('cimketipus', 'termek');
        $view->setVar('controllerscript', 'termekcimke.js');
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $ckat = new termekcimkekatController();
        $view->setVar('cimkecsoportlist', $ckat->getSelectList(0));
        $gyarto = new partnerController();
        $view->setVar('gyartolist', $gyarto->getSzallitoSelectList(0));
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);
        $view->setVar('pagetitle', t('Termékcímke'));
        $view->setVar('controllerscript', 'termekcimke.js');
        $view->setVar('cimketipus', 'termek');
        $view->setVar('formaction', '/admin/termekcimke/save');
        $view->setVar('oper', $oper);
        $record = $this->getRepo()->findWithJoins($id);
        $view->setVar('cimke', $this->loadVars($record));
        $ckat = new termekcimkekatController();
        $view->setVar('cimkecsoportlist', $ckat->getSelectList($record?->getKategoria()?->getId()));
        $gyarto = new partnerController();
        $view->setVar('gyartolist', $gyarto->getSzallitoSelectList($record?->getGyarto()?->getId()));
        return $view->getTemplateResult();
    }

    public function setmenulathato()
    {
        $id = $this->params->getIntRequestParam('id');
        $kibe = $this->params->getBoolRequestParam('kibe');
        $num = $this->params->getIntRequestParam('num');
        $obj = $this->getRepo()->find($id);
        if ($obj) {
            switch ($num) {
                case 1:
                    $obj->setMenu1Lathato($kibe);
                    break;
                case 2:
                    $obj->setMenu2Lathato($kibe);
                    break;
                case 3:
                    $obj->setMenu3Lathato($kibe);
                    break;
                case 4:
                    $obj->setMenu4Lathato($kibe);
                    break;
                case 5:
                    $obj->setKiemelt($kibe);
                    break;
            }
            $this->getEm()->persist($obj);
            $this->getEm()->flush();
        }
    }

    public function add()
    {
        $obj = new Termekcimketorzs();
        $this->setFields($obj);
        $this->getEm()->persist($obj);
        $this->getEm()->flush();
        $view = $this->createView('cimkeselector.tpl');
        $view->setVar('_cimke', ['id' => $obj->getId(), 'caption' => $obj->getNev(), 'selected' => false]);
        echo $view->getTemplateResult();
    }

    private function cimkekToArray($cimkekat)
    {
        $res = [];
        foreach ($cimkekat as $kat) {
            $adat = [];
            $cimkek = $kat->getCimkek();
            foreach ($cimkek as $cimke) {
                $adat[] = [
                    'id' => $cimke->getId(),
                    'caption' => $cimke->getNev(),
                    'slug' => $cimke->getSlug()
                ];
            }
            $res[] = [
                'id' => $kat->getId(),
                'caption' => $kat->getNev(),
                'sanitizedcaption' => $kat->getSlug(),
                'cimkek' => $adat
            ];
        }
        return $res;
    }

    public function viewselect()
    {
        $view = $this->createView('cimkelista.tpl');

        $view->setVar('pagetitle', t('Termékcímkék'));
        $view->setVar('cimketipus', 'termek');
        $view->setVar('controllerscript', 'termekcimke.js');
        $tc = store::getEm()->getRepository(Termekcimkekat::class)->getWithJoins([], ['_xx.nev' => 'asc', 'c.nev' => 'asc']);
        $view->setVar('cimkekat', $this->cimkekToArray($tc));
        $view->printTemplateResult();
    }

    public function getformenu($menu)
    {
        $tc = $this->getRepo(Termekcimkekat::class)->getAllHasTermek($menu);
        return $this->cimkekToArray($tc);
    }

    public function getKiemeltList()
    {
        $tc = $this->getRepo()->getKiemelt();
        $ret = [];
        foreach ($tc as $cimke) {
            $ret[] = $cimke->toLista();
        }
        return $ret;
    }

    public function showMarkak()
    {
        $view = $this->getTemplateFactory()->createMainView('markak.tpl');
        store::fillTemplate($view);
        $termekrepo = $this->getRepo(Termek::class);
        $tc = $this->getRepo()->getMarkak();
        $m = [];
        foreach ($tc as $c) {
            if ($termekrepo->getMarkaCount($c->getId())) {
                $m[] = $c->toLista();
            }
        }
        $view->setVar('markalista', $m);
        $view->printTemplateResult();
    }
}
