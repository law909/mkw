<?php

namespace Controllers;

use Entities\MNRStatic;
use Entities\MNRStaticPage;
use Entities\MNRStaticPageKep;
use Entities\MNRStaticPageTranslation;
use Entities\MNRStaticTranslation;
use mkw\store;
use mkwhelpers\FilterDescriptor;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class mnrstaticController extends \mkwhelpers\MattableController
{

    public function __construct($params)
    {
        $this->setEntityName(MNRStatic::class);
        $this->setKarbFormTplName('mnrstatickarbform.tpl');
        $this->setKarbTplName('mnrstatickarb.tpl');
        $this->setListBodyRowTplName('mnrstaticlista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_mnrstatic');
        parent::__construct($params);
    }

    public function loadVars($t, $forKarb = false)
    {
        $mnrCtrl = new mnrstaticpageController($this->params);
        $translationsCtrl = new mnrstatictranslationController($this->params);
        $translations = [];
        $x = [];
        if (!$t) {
            $t = new \Entities\MNRStatic();
            $this->getEm()->detach($t);
        }
        $x['id'] = $t->getId();
        $x['nev'] = $t->getNev();
        $x['szlogen1'] = $t->getSzlogen1();
        $x['szlogen2'] = $t->getSzlogen2();
        $x['slug'] = $t->getSlug();
        $x['tipus'] = $t->getTipus();
        if ($forKarb) {
            $valtozat = [];
            foreach ($t->getMNRStaticPages() as $tvaltozat) {
                $valtozat[] = $mnrCtrl->loadVars($tvaltozat, $t, $forKarb);
            }
            $x['mnrstaticpages'] = $valtozat;

            if (\mkw\store::isMultilang()) {
                foreach ($t->getTranslations() as $tr) {
                    $translations[] = $translationsCtrl->loadVars($tr, true);
                }
                $x['translations'] = $translations;
            }
        }
        $x['kepurl'] = $t->getKepurl();
        $x['kepurlsmall'] = $t->getKepurlSmall();
        return $x;
    }

    /**
     * @param \Entities\MNRStatic $obj
     *
     * @return mixed
     */
    protected function setFields($obj)
    {
        $obj->setNev($this->params->getStringRequestParam('nev'));
        $obj->setSzlogen1($this->params->getStringRequestParam('szlogen1'));
        $obj->setSzlogen2($this->params->getStringRequestParam('szlogen2'));
        $obj->setKepurl($this->params->getStringRequestParam('kepurl', ''));
        $obj->setTipus($this->params->getIntRequestParam('tipus', 1));
        $pageids = $this->params->getArrayRequestParam('mnrstaticpageid');
        foreach ($pageids as $pageid) {
            $oper = $this->params->getStringRequestParam('mnrstaticpageoper_' . $pageid);
            if ($oper == 'add') {
                $page = new MNRStaticPage();
                $obj->addMNRStaticPage($page);
                $page->setNev($this->params->getStringRequestParam('mnrstaticpagenev_' . $pageid));
                $page->setSzlogen1($this->params->getStringRequestParam('mnrstaticpageszlogen1_' . $pageid));
                $page->setSzlogen2($this->params->getStringRequestParam('mnrstaticpageszlogen2_' . $pageid));
                $page->setSzoveg1($this->params->getStringRequestParam('mnrstaticpageszoveg1_' . $pageid));
                $page->setSzoveg2($this->params->getStringRequestParam('mnrstaticpageszoveg2_' . $pageid));
                $page->setSzoveg3($this->params->getStringRequestParam('mnrstaticpageszoveg3_' . $pageid));
                $page->setTartalom($this->params->getStringRequestParam('mnrstaticpagetartalom_' . $pageid));
                $page->setKepurl($this->params->getStringRequestParam('mnrstaticpagekepurl_' . $pageid));
                $this->getEm()->persist($page);
            } elseif ($oper == 'edit') {
                $page = $this->getEm()->getRepository(MNRStaticPage::class)->find($pageid);
                if ($page) {
                    $page->setNev($this->params->getStringRequestParam('mnrstaticpagenev_' . $pageid));
                    $page->setSzlogen1($this->params->getStringRequestParam('mnrstaticpageszlogen1_' . $pageid));
                    $page->setSzlogen2($this->params->getStringRequestParam('mnrstaticpageszlogen2_' . $pageid));
                    $page->setSzoveg1($this->params->getStringRequestParam('mnrstaticpageszoveg1_' . $pageid));
                    $page->setSzoveg2($this->params->getStringRequestParam('mnrstaticpageszoveg2_' . $pageid));
                    $page->setSzoveg3($this->params->getStringRequestParam('mnrstaticpageszoveg3_' . $pageid));
                    $page->setTartalom($this->params->getStringRequestParam('mnrstaticpagetartalom_' . $pageid));
                    $page->setKepurl($this->params->getStringRequestParam('mnrstaticpagekepurl_' . $pageid));
                    $this->getEm()->persist($page);
                }
            }
            if (\mkw\store::isMultilang()) {
                $_tf = \Entities\MNRStaticPage::getTranslatedFields();
                $translationids = $this->params->getArrayRequestParam('pagetranslationid_' . $pageid);
                foreach ($translationids as $translationid) {
                    $oper = $this->params->getStringRequestParam('pagetranslationoper_' . $translationid . '_' . $pageid);
                    $mezo = $this->params->getStringRequestParam('pagetranslationfield_' . $translationid . '_' . $pageid);
                    $mezotype = $_tf[$mezo]['type'];
                    switch ($mezotype) {
                        case 1:
                        case 3:
                            $mezoertek = $this->params->getStringRequestParam('pagetranslationcontent_' . $translationid . '_' . $pageid);
                            break;
                        case 2:
                            $mezoertek = $this->params->getOriginalStringRequestParam('pagetranslationcontent_' . $translationid . '_' . $pageid);
                            break;
                        default:
                            $mezoertek = $this->params->getStringRequestParam('pagetranslationcontent_' . $translationid . '_' . $pageid);
                            break;
                    }
                    if ($oper === 'add') {
                        $translation = new \Entities\MNRStaticPageTranslation(
                            $this->params->getStringRequestParam('pagetranslationlocale_' . $translationid . '_' . $pageid),
                            $mezo,
                            $mezoertek
                        );
                        $page->addTranslation($translation);
                        $this->getEm()->persist($translation);
                    } elseif ($oper === 'edit') {
                        $translation = $this->getEm()->getRepository(MNRStaticPageTranslation::class)->find($translationid);
                        if ($translation) {
                            $translation->setLocale($this->params->getStringRequestParam('pagetranslationlocale_' . $translationid . '_' . $pageid));
                            $translation->setField($mezo);
                            $translation->setContent($mezoertek);
                            $this->getEm()->persist($translation);
                        }
                    }
                }
            }
            $kepids = $this->params->getArrayRequestParam('kepid_' . $pageid);
            foreach ($kepids as $kepid) {
                $oper = $this->params->getStringRequestParam('kepoper_' . $kepid . '_' . $pageid);
                if ($oper === 'add') {
                    $kep = new \Entities\MNRStaticPageKep();
                    $kep->setUrl($this->params->getStringRequestParam('kepurl_' . $kepid . '_' . $pageid));
                    $kep->setRejtett($this->params->getBoolRequestParam('keprejtett_' . $kepid . '_' . $pageid));
                    $page->addMNRStaticPageKep($kep);
                    $this->getEm()->persist($kep);
                } elseif ($oper === 'edit') {
                    $kep = $this->getEm()->getRepository(MNRStaticPageKep::class)->find($kepid);
                    if ($kep) {
                        $kep->setUrl($this->params->getStringRequestParam('kepurl_' . $kepid . '_' . $pageid));
                        $kep->setRejtett($this->params->getBoolRequestParam('keprejtett_' . $kepid . '_' . $pageid));
                        $this->getEm()->persist($kep);
                    }
                }
            }
        }
        if (\mkw\store::isMultilang()) {
            $_tf = \Entities\MNRStatic::getTranslatedFields();
            $translationids = $this->params->getArrayRequestParam('translationid');
            foreach ($translationids as $translationid) {
                $oper = $this->params->getStringRequestParam('translationoper_' . $translationid);
                $mezo = $this->params->getStringRequestParam('translationfield_' . $translationid);
                $mezotype = $_tf[$mezo]['type'];
                switch ($mezotype) {
                    case 1:
                    case 3:
                        $mezoertek = $this->params->getStringRequestParam('translationcontent_' . $translationid);
                        break;
                    case 2:
                        $mezoertek = $this->params->getOriginalStringRequestParam('translationcontent_' . $translationid);
                        break;
                    default:
                        $mezoertek = $this->params->getStringRequestParam('translationcontent_' . $translationid);
                        break;
                }
                if ($oper === 'add') {
                    $translation = new \Entities\MNRStaticTranslation(
                        $this->params->getStringRequestParam('translationlocale_' . $translationid),
                        $mezo,
                        $mezoertek
                    );
                    $obj->addTranslation($translation);
                    $this->getEm()->persist($translation);
                } elseif ($oper === 'edit') {
                    $translation = $this->getEm()->getRepository(MNRStaticTranslation::class)->find($translationid);
                    if ($translation) {
                        $translation->setLocale($this->params->getStringRequestParam('translationlocale_' . $translationid));
                        $translation->setField($mezo);
                        $translation->setContent($mezoertek);
                        $this->getEm()->persist($translation);
                    }
                }
            }
        }
        return $obj;
    }

    public function getlistbody()
    {
        $view = $this->createView('mnrstaticlista_tbody.tpl');

        $this->initPager($this->getRepo()->getCount([]));
        $egyedek = $this->getRepo()->getWithJoins(
            [],
            $this->getOrderArray(),
            $this->getPager()->getOffset(),
            $this->getPager()->getElemPerPage()
        );

        echo json_encode($this->loadDataToView($egyedek, 'mnrstaticlista', $view));
    }

    public function getSelectList($selid = null)
    {
        $rec = $this->getRepo()->getAll([], ['nev' => 'ASC']);
        $res = [];
        foreach ($rec as $sor) {
            $res[] = [
                'id' => $sor->getId(),
                'caption' => $sor->getNev(),
                'selected' => ($sor->getId() == $selid)
            ];
        }
        return $res;
    }

    public function htmllist()
    {
        $rec = $this->getRepo()->getAll([], ['nev' => 'asc']);
        $ret = '<select>';
        foreach ($rec as $sor) {
            $ret .= '<option value="' . $sor->getId() . '">' . $sor->getNev() . '</option>';
        }
        $ret .= '</select>';
        echo $ret;
    }

    public function viewlist()
    {
        $view = $this->createView('mnrstaticlista.tpl');
        $view->setVar('pagetitle', t('MNR Statikus menü'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);
        $view->setVar('pagetitle', t('MNR Statikus menü'));
        $view->setVar('oper', $oper);

        $mnrstatic = $this->getRepo()->findWithJoins($id);

        $view->setVar('egyed', $this->loadVars($mnrstatic, true));
        $view->printTemplateResult();
    }

    public function show()
    {
        $com = $this->params->getStringParam('lap');
        /** @var MNRStatic $statlap */
        $statlap = $this->getRepo()->findOneBySlug($com);
        if ($statlap) {
            $view = $this->getTemplateFactory()->createMainView('mnrstatic.tpl');
            \mkw\store::fillTemplate($view);
            $view->setVar('mnrstatic', $statlap->toPublic());
            $view->printTemplateResult(true);
        } else {
            \mkw\store::redirectTo404($com, $this->params);
        }
    }
}