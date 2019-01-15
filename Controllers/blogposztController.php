<?php

namespace Controllers;

use mkw\store;
use mkwhelpers\FilterDescriptor;

class blogposztController extends \mkwhelpers\MattableController {

    public function __construct($params) {
        $this->setEntityName('Entities\Blogposzt');
        $this->setKarbFormTplName('blogposztkarbform.tpl');
        $this->setKarbTplName('blogposztkarb.tpl');
        $this->setListBodyRowTplName('blogposztlista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_blogposzt');
        parent::__construct($params);
    }

    protected function loadVars($t, $forKarb = false) {
        $x = array();
        if (!$t) {
            $t = new \Entities\Blogposzt();
            $this->getEm()->detach($t);
        }
        $x['id'] = $t->getId();
        $x['megjelenesdatumstr'] = $t->getMegjelenesdatumStr();
        $x['cim'] = $t->getCim();
        $x['szoveg'] = $t->getSzoveg();
        $x['kivonat'] = $t->getKivonat();
        $x['lathato'] = $t->getLathato();
        $x['termekfa1nev'] = $t->getTermekfa1Nev();
        $x['termekfa2nev'] = $t->getTermekfa2Nev();
        $x['termekfa3nev'] = $t->getTermekfa3Nev();
        $x['termekfa1'] = $t->getTermekfa1Id();
        $x['termekfa2'] = $t->getTermekfa2Id();
        $x['termekfa3'] = $t->getTermekfa3Id();
        $x['slug'] = $t->getSlug();
        $x['kepurl'] = $t->getKepurl();
        $x['kepurlsmall'] = $t->getKepurlSmall();
        $x['kepurlmedium'] = $t->getKepurlMedium();
        $x['kepurllarge'] = $t->getKepurlLarge();
        $x['kepleiras'] = $t->getKepleiras();
        return $x;
    }

    /**
     * @param \Entities\Blogposzt $obj
     * @return mixed
     */
    protected function setFields($obj) {
        $obj->setCim($this->params->getStringRequestParam('cim'));
        $obj->setKivonat($this->params->getStringRequestParam('kivonat'));
        $obj->setSzoveg($this->params->getOriginalStringRequestParam('szoveg'));
        $obj->setLathato($this->params->getBoolRequestParam('lathato'));
        $obj->setKepurl($this->params->getStringRequestParam('kepurl', ''));
        $obj->setKepleiras($this->params->getStringRequestParam('kepleiras', ''));
        $obj->setMegjelenesdatum($this->params->getStringRequestParam('megjelenesdatum'));

        $farepo = \mkw\store::getEm()->getRepository('Entities\TermekFa');
        $fa = $farepo->find($this->params->getIntRequestParam('termekfa1'));
        if ($fa) {
            $obj->setTermekfa1($fa);
        }
        else {
            $obj->setTermekfa1(null);
        }
        $fa = $farepo->find($this->params->getIntRequestParam('termekfa2'));
        if ($fa) {
            $obj->setTermekfa2($fa);
        }
        else {
            $obj->setTermekfa2(null);
        }
        $fa = $farepo->find($this->params->getIntRequestParam('termekfa3'));
        if ($fa) {
            $obj->setTermekfa3($fa);
        }
        else {
            $obj->setTermekfa3(null);
        }
        return $obj;
    }

    public function getlistbody() {
        $view = $this->createView('blogposztlista_tbody.tpl');

        $filter = new \mkwhelpers\FilterDescriptor();
        $f = $this->params->getNumRequestParam('lathatofilter', 9);
        if ($f != 9) {
            $filter->addFilter('lathato', '=', $f);
        }

        $cf = $this->params->getStringRequestParam('cimfilter');
        if ($cf) {
            $filter->addFilter('cim', 'LIKE', '%' . $cf . '%');
        }

        $fv = $this->params->getArrayRequestParam('fafilter');
        if (!empty($fv)) {
            $ff = new \mkwhelpers\FilterDescriptor();
            $ff->addFilter('id', 'IN', $fv);
            $res = \mkw\store::getEm()->getRepository('Entities\TermekFa')->getAll($ff, array());
            $faszuro = array();
            foreach ($res as $sor) {
                $faszuro[] = $sor->getKarkod() . '%';
            }
            $filter->addFilter(array('_xx.termekfa1karkod', '_xx.termekfa2karkod', '_xx.termekfa3karkod'), 'LIKE', $faszuro);
        }

        $this->initPager($this->getRepo()->getCount($filter));
        $egyedek = $this->getRepo()->getWithJoins(
            $filter, $this->getOrderArray(), $this->getPager()->getOffset(), $this->getPager()->getElemPerPage());

        echo json_encode($this->loadDataToView($egyedek, 'blogposztlista', $view));
    }

    public function getSelectList($selid = null) {
        $rec = $this->getRepo()->getAllForSelectList(array(), array('megjelenesdatum' => 'DESC', 'cim' => 'ASC'));
        $res = array();
        foreach ($rec as $sor) {
            $res[] = array(
                'id' => $sor['id'],
                'caption' => $sor['cim'],
                'selected' => ($sor['id'] == $selid)
            );
        }
        return $res;
    }

    public function htmllist() {
        $rec = $this->getRepo()->getAllForSelectList(array(), array('megjelenesdatum' => 'DESC', 'cim' => 'ASC'));
        $ret = '<select>';
        foreach ($rec as $sor) {
            $ret .= '<option value="' . $sor['id'] . '">' . $sor['cim'] . '</option>';
        }
        $ret .= '</select>';
        echo $ret;
    }

    public function viewlist() {
        $view = $this->createView('blogposztlista.tpl');
        $view->setVar('pagetitle', t('Blogposztok'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname) {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);
        $view->setVar('pagetitle', t('Blogposzt'));
        $view->setVar('oper', $oper);

        $bp = $this->getRepo()->findWithJoins($id);

        $view->setVar('egyed', $this->loadVars($bp, true));

        $view->printTemplateResult();
    }

    public function setflag() {
        $id = $this->params->getIntRequestParam('id');
        $kibe = $this->params->getBoolRequestParam('kibe');
        $flag = $this->params->getStringRequestParam('flag');
        /** @var \Entities\Blogposzt $obj */
        $obj = $this->getRepo()->find($id);
        if ($obj) {
            switch ($flag) {
                case 'lathato':
                    $obj->setLathato($kibe);
                    break;
            }
            $this->getEm()->persist($obj);
            $this->getEm()->flush();
        }
    }

    public function show() {
        $com = $this->params->getStringParam('blogposzt');
        /** @var \Entities\Blogposzt $blogposzt */
        $blogposzt = $this->getRepo()->findOneBySlug($com);
        if ($blogposzt) {
            $view = $this->getTemplateFactory()->createMainView('blogposzt.tpl');
            \mkw\store::fillTemplate($view);
            $view->setVar('pagetitle', $blogposzt->getShowCim());
            $view->setVar('seodescription', $blogposzt->getShowSeodescription());
            $view->setVar('blogposzt', $blogposzt->convertToArray());
            $view->printTemplateResult(false);
        }
        else {
            \mkw\store::redirectTo404($com, $this->params);
        }
    }

    public function showBlogposztList() {
        $elemperpage = $this->params->getIntRequestParam('elemperpage', \mkw\store::getParameter(\mkw\consts::Blogposztdb, 15));

        $pageno = $this->params->getIntRequestParam('pageno', 1);

        $view = $this->getTemplateFactory()->createMainView('blogposztlist.tpl');

        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('lathato', '=', true);

        $t = array();
        $posztdb = $this->getRepo()->getCount($filter);
        if ($posztdb > 0) {

            // termekdarabszam kategoriaval es cimkevel es arral szurve
            // lapozohoz kell
            $this->initPager($posztdb, $elemperpage, $pageno);
            $pager = $this->getPager();
            $elemperpage = $pager->getElemPerPage();

            $blogposztok = $this->getRepo()->getWithJoins($filter, array('megjelenesdatum' => 'DESC', 'id' => 'DESC'), $pager->getOffset(), $elemperpage);
            /** @var \Entities\Blogposzt $poszt */
            foreach ($blogposztok as $poszt) {
                $t[] = $poszt->convertToArray();
            }
            $view->setVar('lapozo', $pager->loadValues());
        }

        \mkw\store::fillTemplate($view);

        $mpt = \mkw\store::getParameter(\mkw\consts::Blogoldalcim);
        if ($mpt) {
            $mpt = str_replace('[global]', \mkw\store::getParameter(\mkw\consts::Oldalcim), $mpt);
        }
        else {
            $mpt = \mkw\store::getParameter(\mkw\consts::Oldalcim);
        }
        $view->setVar('pagetitle', $mpt);

        $msd = \mkw\store::getParameter(\mkw\consts::Blogseodescription);
        if ($msd) {
            $msd = str_replace('[global]', \mkw\store::getParameter(\mkw\consts::Seodescription), $msd);
        }
        else {
            $msd = \mkw\store::getParameter(\mkw\consts::Seodescription);
        }
        $view->setVar('seodescription', $msd);

        $view->setVar('children', $t);
        $view->printTemplateResult(false);
    }

    public function feed() {
        $feedview = $this->getTemplateFactory()->createMainView('feed.tpl');
        $feedview->setVar('title', \mkw\store::getParameter(\mkw\consts::Feedblogtitle, t('Blog')));
        $feedview->setVar('link', \mkw\store::getRouter()->generate('blogposztfeed', true));
        $d = new \DateTime();
        $feedview->setVar('pubdate', $d->format('D, d M Y H:i:s'));
        $feedview->setVar('lastbuilddate', $d->format('D, d M Y H:i:s'));
        $feedview->setVar('description', \mkw\store::getParameter(\mkw\consts::Feedblogdescription, ''));
        $entries = array();
        $blogposztok = $this->getRepo()->getFeedBlogposztok();
        /** @var \Entities\Blogposzt $poszt */
        foreach ($blogposztok as $poszt) {
            $entries[] = array(
                'title' => $poszt->getCim(),
                'link' => \mkw\store::getRouter()->generate('showblogposzt', true, array('blogposzt' => $poszt->getSlug())),
                'guid' => \mkw\store::getRouter()->generate('showblogposzt', true, array('blogposzt' => $poszt->getSlug())),
                'description' => $poszt->getSzoveg(),
                'pubdate' => $poszt->getMegjelenesdatum()->format('D, d M Y H:i:s')
            );
        }
        $feedview->setVar('entries', $entries);
        header('Content-type: text/xml');
        $feedview->printTemplateResult(false);
    }

}