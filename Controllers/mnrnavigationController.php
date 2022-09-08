<?php

namespace Controllers;

use Entities\MNRNavigation;
use Entities\MNRNavigationTranslation;
use Entities\MNRStatic;
use Entities\MNRStaticPage;
use Entities\MNRStaticPageTranslation;
use Entities\MNRStaticTranslation;
use mkw\store;
use mkwhelpers\FilterDescriptor;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class mnrnavigationController extends \mkwhelpers\MattableController {

    public function __construct($params) {
		$this->setEntityName(MNRNavigation::class);
		$this->setKarbFormTplName('mnrnavigationkarbform.tpl');
		$this->setKarbTplName('mnrnavigationkarb.tpl');
		$this->setListBodyRowTplName('mnrnavigationlista_tbody_tr.tpl');
		$this->setListBodyRowVarName('_mnrnavigation');
		parent::__construct($params);
	}

	protected function loadVars($t, $forKarb = false) {
        $mnrCtrl = new mnrstaticController($this->params);
        $translationsCtrl = new mnrnavigationtranslationController($this->params);
        $translations = array();
		$x = array();
		if (!$t) {
			$t = new \Entities\MNRNavigation();
			$this->getEm()->detach($t);
		}
		$x['id'] = $t->getId();
		$x['nev'] = $t->getNev();
		$x['szam'] = $t->getSzam();
		$x['szlogen'] = $t->getSzlogen();
		$x['mnrstatic'] = $mnrCtrl->loadVars($t->getMnrstatic(), true);
		if ($forKarb) {

            if (\mkw\store::isMultilang()) {
                foreach($t->getTranslations() as $tr) {
                    $translations[] = $translationsCtrl->loadVars($tr, true);
                }
                $x['translations'] = $translations;
            }
		}
		return $x;
	}

    /**
     * @param \Entities\MNRNavigation $obj
     * @return mixed
     */
	protected function setFields($obj) {
	    $obj->setNev($this->params->getStringRequestParam('nev'));
		$obj->setSzam($this->params->getStringRequestParam('szam'));
        $obj->setSzlogen($this->params->getStringRequestParam('szlogen'));
        $ck = \mkw\store::getEm()->getRepository(MNRStatic::class)->find($this->params->getIntRequestParam('mnrstatic'));
        if ($ck) {
            $obj->setMnrstatic($ck);
        }
        else {
            $obj->setMnrstatic(null);
        }

        if (\mkw\store::isMultilang()) {
            $_tf = \Entities\MNRNavigation::getTranslatedFields();
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
					$translation = new \Entities\MNRNavigationTranslation(
                        $this->params->getStringRequestParam('translationlocale_' . $translationid),
                        $mezo,
                        $mezoertek
                    );
					$obj->addTranslation($translation);
					$this->getEm()->persist($translation);
				}
				elseif ($oper === 'edit') {
					$translation = $this->getEm()->getRepository(MNRNavigationTranslation::class)->find($translationid);
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
		$view = $this->createView('mnrnavigationlista_tbody.tpl');

        $this->initPager($this->getRepo()->getCount(array()));
        $egyedek = $this->getRepo()->getWithJoins(
            array(), $this->getOrderArray(), $this->getPager()->getOffset(), $this->getPager()->getElemPerPage());

        echo json_encode($this->loadDataToView($egyedek, 'mnrnavigationlista', $view));
	}

	public function getSelectList($selid = null) {
		$rec = $this->getRepo()->getAll(array(), array('nev' => 'ASC'));
		$res = array();
		foreach ($rec as $sor) {
			$res[] = array(
				'id' => $sor->getId(),
				'caption' => $sor->getNev(),
				'selected' => ($sor->getId() == $selid)
			);
		}
		return $res;
	}

	public function htmllist() {
		$rec = $this->getRepo()->getAll(array(), array('nev' => 'asc'));
		$ret = '<select>';
		foreach ($rec as $sor) {
			$ret.='<option value="' . $sor->getId() . '">' . $sor->getNev() . '</option>';
		}
		$ret.='</select>';
		echo $ret;
	}

	public function viewlist() {
		$view = $this->createView('mnrnavigationlista.tpl');
		$view->setVar('pagetitle', t('MNR Navigáció'));
		$view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
		$view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
		$view->printTemplateResult();
	}

	protected function _getkarb($tplname) {
	    $staticCtrl = new mnrstaticController($this->params);
		$id = $this->params->getRequestParam('id', 0);
		/** @var MNRNavigation $mnrnavigation */
        $mnrnavigation = $this->getRepo()->findWithJoins($id);
		$oper = $this->params->getRequestParam('oper', '');
		$view = $this->createView($tplname);
		$view->setVar('pagetitle', t('MNR Navigáció'));
		$view->setVar('oper', $oper);
        $view->setVar('mnrstaticlist', $staticCtrl->getSelectList($mnrnavigation->getMnrstaticId()));

		$view->setVar('egyed', $this->loadVars($mnrnavigation, true));
        $view->printTemplateResult();
	}

	public function show() {
        $com = $this->params->getStringParam('lap');
        /** @var MNRNavigation $statlap */
        $statlap = $this->getRepo()->findOneBySlug($com);
        if ($statlap) {
            $view = $this->getTemplateFactory()->createMainView('mnrnavigation.tpl');
            \mkw\store::fillTemplate($view);
            $view->setVar('mnrnavigation', $statlap->toPublic());
            $view->printTemplateResult(true);
        }
        else {
            \mkw\store::redirectTo404($com, $this->params);
        }
    }
}