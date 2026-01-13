<?php

namespace Controllers;

class hirController extends \mkwhelpers\MattableController
{

    public function __construct($params)
    {
        $this->setEntityName('Entities\Hir');
        $this->setKarbFormTplName('hirkarbform.tpl');
        $this->setKarbTplName('hirkarb.tpl');
        $this->setListBodyRowTplName('hirlista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct($params);
    }

    protected function loadVars($t)
    {
        $x = [];
        if (!$t) {
            $t = new \Entities\Hir();
            $this->getEm()->detach($t);
        }
        $x['id'] = $t->getId();
        $x['cim'] = $t->getCim();
        $x['slug'] = $t->getSlug();
        $x['sorrend'] = $t->getSorrend();
        $x['forras'] = $t->getForras();
        $x['lead'] = $t->getLead();
        $x['szoveg'] = $t->getSzoveg();
        $x['datum'] = $t->getDatum();
        $x['datumstr'] = $t->getDatumStr();
        $x['elsodatum'] = $t->getElsodatum();
        $x['elsodatumstr'] = $t->getElsodatumStr();
        $x['utolsodatum'] = $t->getUtolsodatum();
        $x['utolsodatumstr'] = $t->getUtolsodatumStr();
        $x['lathato'] = $t->getLathato();
        $x['seodescription'] = $t->getSeodescription();
        $x['kepurl'] = $t->getKepurl();
        $x['kepurlmini'] = $t->getKepurlMini();
        $x['kepurlsmall'] = $t->getKepurlSmall();
        $x['kepurlmedium'] = $t->getKepurlMedium();
        $x['kepurllarge'] = $t->getKepurlLarge();
        $x['kepleiras'] = $t->getKepleiras();
        $x['link'] = $t->getLink();
        return $x;
    }

    protected function setFields($obj)
    {
        $obj->setCim($this->params->getStringRequestParam('cim'));
        $obj->setSorrend($this->params->getIntRequestParam('sorrend'));
        $obj->setForras($this->params->getStringRequestParam('forras'));
        $obj->setLead($this->params->getOriginalStringRequestParam('lead'));
        $obj->setSzoveg($this->params->getOriginalStringRequestParam('szoveg'));
        $obj->setLathato($this->params->getBoolRequestParam('lathato'));
        $obj->setDatum($this->params->getStringRequestParam('datum'));
        $obj->setElsodatum($this->params->getStringRequestParam('elsodatum'));
        $obj->setUtolsodatum($this->params->getStringRequestParam('utolsodatum'));
        $obj->setSeodescription($this->params->getStringRequestParam('seodescription'));
        $obj->setKepurl($this->params->getStringRequestParam('kepurl'));
        $obj->setKepleiras($this->params->getStringRequestParam('kepleiras'));
        return $obj;
    }

    public function getlistbody()
    {
        $view = $this->createView('hirlista_tbody.tpl');

        $filter = new \mkwhelpers\FilterDescriptor();
        if (!is_null($this->params->getRequestParam('nevfilter', null))) {
            $filter->addFilter('cim', 'LIKE', '%' . $this->params->getStringRequestParam('nevfilter') . '%');
        }

        $this->initPager($this->getRepo()->getCount($filter));

        $egyedek = $this->getRepo()->getAll(
            $filter,
            $this->getOrderArray(),
            $this->getPager()->getOffset(),
            $this->getPager()->getElemPerPage()
        );

        echo json_encode($this->loadDataToView($egyedek, 'egyedlista', $view));
    }

    public function viewselect()
    {
        $view = $this->createView('hirlista.tpl');

        $view->setVar('pagetitle', t('Hírek'));
        $view->printTemplateResult(false);
    }

    public function viewlist()
    {
        $view = $this->createView('hirlista.tpl');

        $view->setVar('pagetitle', t('Hír'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult(false);
    }

    protected function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Hír'));
        $view->setVar('formaction', '/admin/hir/save');
        $view->setVar('oper', $oper);
        $record = $this->getRepo()->find($id);
        $view->setVar('egyed', $this->loadVars($record));
        return $view->getTemplateResult();
    }

    public function setlathato()
    {
        $id = $this->params->getIntRequestParam('id');
        $kibe = $this->params->getBoolRequestParam('kibe');
        $obj = $this->getRepo()->find($id);
        if ($obj) {
            $obj->setLathato($kibe);
            \mkw\store::getEm()->persist($obj);
            \mkw\store::getEm()->flush();
        }
    }

    public function gethirlist()
    {
        $t = [];
        $hirek = $this->getRepo()->getMaiHirek();
        foreach ($hirek as $hir) {
            $t[] = $hir->convertToArray();
        }
        return $t;
    }

    public function getfeedhirlist()
    {
        return $this->getRepo()->getFeedHirek();
    }

    public function show()
    {
        $com = $this->params->getStringParam('hir');
        $hir = $this->getRepo()->findOneBySlug($com);
        if ($hir) {
            $view = $this->getTemplateFactory()->createMainView('hir.tpl');
            \mkw\store::fillTemplate($view);
            $view->setVar('pagetitle', $hir->getShowCim());
            $view->setVar('seodescription', $hir->getShowSeodescription());
            $view->setVar('hir', $hir->convertToArray());
            $view->printTemplateResult(false);
        } else {
            \mkw\store::redirectTo404($com, $this->params);
        }
    }

    public function showHirList()
    {
        $view = $this->getTemplateFactory()->createMainView('hirlist.tpl');
        $t = [];
        $hirek = $this->getRepo()->getFeedHirek();
        foreach ($hirek as $hir) {
            $t[] = $hir->convertToArray();
        }
        \mkw\store::fillTemplate($view);

        $mpt = \mkw\store::getParameter(\mkw\consts::Hirekoldalcim);
        if ($mpt) {
            $mpt = str_replace('[global]', \mkw\store::getParameter(\mkw\consts::Oldalcim), $mpt);
        } else {
            $mpt = \mkw\store::getParameter(\mkw\consts::Oldalcim);
        }
        $view->setVar('pagetitle', $mpt);

        $msd = \mkw\store::getParameter(\mkw\consts::Hirekseodescription);
        if ($msd) {
            $msd = str_replace('[global]', \mkw\store::getParameter(\mkw\consts::Seodescription), $msd);
        } else {
            $msd = \mkw\store::getParameter(\mkw\consts::Seodescription);
        }
        $view->setVar('seodescription', $msd);

        $view->setVar('children', $t);
        $view->printTemplateResult(false);
    }

    public function feed()
    {
        $feedview = $this->getTemplateFactory()->createMainView('feed.tpl');
        $feedview->setVar('title', \mkw\store::getParameter(\mkw\consts::Feedhirtitle, t('Híreink')));
        $feedview->setVar('link', \mkw\store::getRouter()->generate('hirfeed', true));
        $d = new \DateTime();
        $feedview->setVar('pubdate', $d->format('D, d M Y H:i:s'));
        $feedview->setVar('lastbuilddate', $d->format('D, d M Y H:i:s'));
        $feedview->setVar('description', \mkw\store::getParameter(\mkw\consts::Feedhirdescription, ''));
        $entries = [];
        $hirek = $this->getfeedhirlist();
        foreach ($hirek as $hir) {
            $entries[] = [
                'title' => $hir->getCim(),
                'link' => \mkw\store::getRouter()->generate('showhir', true, ['hir' => $hir->getSlug()]),
                'guid' => \mkw\store::getRouter()->generate('showhir', true, ['hir' => $hir->getSlug()]),
                'description' => $hir->getSzoveg(),
                'pubdate' => $hir->getDatum()->format('D, d M Y H:i:s')
            ];
        }
        $feedview->setVar('entries', $entries);
        header('Content-type: text/xml');
        $feedview->printTemplateResult(false);
    }

    public function redirectOldRSSUrl()
    {
        $newlink = \mkw\store::getRouter()->generate('hirfeed');
        header("HTTP/1.1 301 Moved Permanently");
        header('Location: ' . $newlink);
    }

}
