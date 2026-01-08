<?php
namespace Controllers;

class statlapController extends \mkwhelpers\MattableController {

    public function __construct($params) {
        $this->setEntityName('Entities\Statlap');
        $this->setKarbFormTplName('statlapkarbform.tpl');
        $this->setKarbTplName('statlapkarb.tpl');
        $this->setListBodyRowTplName('statlaplista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct($params);
    }

    protected function loadVars($t, $forKarb = false) {
        $x = array();
        $translationsCtrl = new statlaptranslationController($this->params);
        $translations = array();
        if (!$t) {
            $t = new \Entities\Statlap();
            $this->getEm()->detach($t);
        }
        $x['id'] = $t->getId();
        $x['oldalcim'] = $t->getOldalcim();
        $x['slug'] = $t->getSlug();
        $x['szoveg'] = $t->getSzoveg();
        $x['seodescription'] = $t->getSeodescription();
        $x['oldurl'] = $t->getOldurl();
        if (\mkw\store::isMultilang()) {
            foreach($t->getTranslations() as $tr) {
                $translations[] = $translationsCtrl->loadVars($tr, true);
            }
            $x['translations'] = $translations;
        }
        return $x;
    }

    protected function setFields($obj) {
        $obj->setOldalcim($this->params->getStringRequestParam('oldalcim'));
        $obj->setSzoveg($this->params->getOriginalStringRequestParam('szoveg'));
        $obj->setSeodescription($this->params->getStringRequestParam('seodescription'));
        $obj->setOldurl($this->params->getStringRequestParam('oldurl'));
        if (\mkw\store::isMultilang()) {
            $_tf = \Entities\Statlap::getTranslatedFields();
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
                    $translation = new \Entities\StatlapTranslation(
                        $this->params->getStringRequestParam('translationlocale_' . $translationid),
                        $mezo,
                        $mezoertek
                    );
                    $obj->addTranslation($translation);
                    $this->getEm()->persist($translation);
                }
                elseif ($oper === 'edit') {
                    $translation = $this->getEm()->getRepository('Entities\StatlapTranslation')->find($translationid);
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

    public function getlistbody() {
        $view = $this->createView('statlaplista_tbody.tpl');

        $filter = new \mkwhelpers\FilterDescriptor();
        if (!is_null($this->params->getRequestParam('nevfilter', NULL))) {
            $filter->addFilter('oldalcim', 'LIKE', '%' . $this->params->getStringRequestParam('nevfilter') . '%');
        }

        $this->initPager($this->getRepo()->getCount($filter));

        $egyedek = $this->getRepo()->getAll(
            $filter,
            $this->getOrderArray(),
            $this->getPager()->getOffset(),
            $this->getPager()->getElemPerPage());

        echo json_encode($this->loadDataToView($egyedek, 'egyedlista', $view));
    }

    public function viewselect() {
        $view = $this->createView('statlaplista.tpl');

        $view->setVar('pagetitle', t('Statikus lapok'));
        $view->printTemplateResult();
    }

    public function viewlist() {
        $view = $this->createView('statlaplista.tpl');

        $view->setVar('pagetitle', t('Statikus lapok'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname) {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Statikus lap'));
        $view->setVar('formaction', '/admin/statlap/save');
        $view->setVar('oper', $oper);
        $record = $this->getRepo()->find($id);
        $view->setVar('egyed', $this->loadVars($record));
        return $view->getTemplateResult();
    }

    public function getstatlap($statlap) {
        $t = array();
        $t['szoveg'] = $statlap->getSzoveg();
        $t['oldalcim'] = $statlap->getOldalcim();
        return $t;
    }

    public function show() {
        $com = $this->params->getStringParam('lap');
        $statlap = $this->getRepo()->findOneBySlug($com);
        if ($statlap) {
            $view = $this->getTemplateFactory()->createMainView('statlap.tpl');
            \mkw\store::fillTemplate($view);
            $view->setVar('pagetitle', $statlap->getShowOldalcim());
            $view->setVar('seodescription', $statlap->getShowSeodescription());
            $view->setVar('statlap', $this->getstatlap($statlap));
            $view->printTemplateResult(true);
        }
        else {
            \mkw\store::redirectTo404($com, $this->params);
        }
    }

    public function showPopup() {
        $com = $this->params->getStringParam('lap');
        $statlap = $this->getRepo()->findOneBySlug($com);
        if ($statlap) {
            $view = $this->getTemplateFactory()->createMainView('statlappopup.tpl');
            \mkw\store::fillTemplate($view);
            $view->setVar('szoveg', $statlap->getSzoveg());
            $view->printTemplateResult(false);
        }
        else {
            echo '';
        }
    }

    public function redirectOldUrl() {
        $lapid = $this->params->getStringRequestParam('page');
        if ($lapid) {
            switch ($lapid) {
                case 'hirek':
                    $newlink = \mkw\store::getRouter()->generate('showhirlist', false, array());
                    header("HTTP/1.1 301 Moved Permanently");
                    header('Location: ' . $newlink);
                    return;
                case 'markak':
                    $newlink = \mkw\store::getRouter()->generate('markak', false, array());
                    header("HTTP/1.1 301 Moved Permanently");
                    header('Location: ' . $newlink);
                    return;
                default:
                    $lap = $this->getRepo()->findOneByOldurl($lapid);
                    if ($lap) {
                        $newlink = \mkw\store::getRouter()->generate('showstatlap', false, array('lap' => $lap->getSlug()));
                        header("HTTP/1.1 301 Moved Permanently");
                        header('Location: ' . $newlink);
                        return;
                    }
            }
        }
        $mc = new mainController($this->params);
        $mc->show404('HTTP/1.1 410 Gone');
    }

    public function getSelectList($selid) {
        $rec = $this->getRepo()->getAll(array(), array('oldalcim' => 'ASC'));
        $res = array();
        foreach ($rec as $sor) {
            $res[] = array(
                'id' => $sor->getId(),
                'caption' => $sor->getOldalcim(),
                'selected' => ($sor->getId() == $selid)
            );
        }
        return $res;
    }

}