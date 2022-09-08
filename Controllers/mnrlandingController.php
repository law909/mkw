<?php

namespace Controllers;

use Entities\MNRLanding;
use Entities\MNRLandingTranslation;
use mkw\store;
use mkwhelpers\FilterDescriptor;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class mnrlandingController extends \mkwhelpers\MattableController {

    public function __construct($params) {
		$this->setEntityName(MNRLanding::class);
		$this->setKarbFormTplName('mnrlandingkarbform.tpl');
		$this->setKarbTplName('mnrlandingkarb.tpl');
		$this->setListBodyRowTplName('mnrlandinglista_tbody_tr.tpl');
		$this->setListBodyRowVarName('_mnrlanding');
		parent::__construct($params);
	}

	public function loadVars($t, $forKarb = false) {
        $translationsCtrl = new mnrlandingtranslationController($this->params);
        $translations = array();
		$x = array();
		if (!$t) {
			$t = new \Entities\MNRLanding();
			$this->getEm()->detach($t);
		}
		$x['id'] = $t->getId();
		$x['nev'] = $t->getNev();
		$x['szlogen'] = $t->getSzlogen();
        if ($forKarb) {

            if (\mkw\store::isMultilang()) {
                foreach($t->getTranslations() as $tr) {
                    $translations[] = $translationsCtrl->loadVars($tr, true);
                }
                $x['translations'] = $translations;
            }
        }
		$x['kepurl'] = $t->getKepurl();
		return $x;
	}

    /**
     * @param \Entities\MNRLanding $obj
     * @return mixed
     */
	protected function setFields($obj) {
	    $obj->setNev($this->params->getStringRequestParam('nev'));
		$obj->setSzlogen($this->params->getStringRequestParam('szlogen'));
		$obj->setKepurl($this->params->getStringRequestParam('kepurl', ''));
        if (\mkw\store::isMultilang()) {
            $_tf = \Entities\MNRLanding::getTranslatedFields();
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
					$translation = new \Entities\MNRLandingTranslation(
                        $this->params->getStringRequestParam('translationlocale_' . $translationid),
                        $mezo,
                        $mezoertek
                    );
					$obj->addTranslation($translation);
					$this->getEm()->persist($translation);
				}
				elseif ($oper === 'edit') {
					$translation = $this->getEm()->getRepository(MNRLandingTranslation::class)->find($translationid);
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
		$view = $this->createView('mnrlandinglista_tbody.tpl');

        $this->initPager($this->getRepo()->getCount(array()));
        $egyedek = $this->getRepo()->getWithJoins(
            array(), $this->getOrderArray(), $this->getPager()->getOffset(), $this->getPager()->getElemPerPage());

        echo json_encode($this->loadDataToView($egyedek, 'mnrlandinglista', $view));
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
		$view = $this->createView('mnrlandinglista.tpl');
		$view->setVar('pagetitle', t('MNR Landing page'));
		$view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
		$view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
		$view->printTemplateResult();
	}

	protected function _getkarb($tplname) {
		$id = $this->params->getRequestParam('id', 0);
		$oper = $this->params->getRequestParam('oper', '');
		$view = $this->createView($tplname);
		$view->setVar('pagetitle', t('MNR Landing page'));
		$view->setVar('oper', $oper);

		$mnrlanding = $this->getRepo()->findWithJoins($id);

		$view->setVar('egyed', $this->loadVars($mnrlanding, true));
        $view->printTemplateResult();
	}

	public function show() {
        $com = $this->params->getStringParam('lap');
        /** @var MNRLanding $statlap */
        $statlap = $this->getRepo()->findOneBySlug($com);
        if ($statlap) {
            $view = $this->getTemplateFactory()->createMainView('mnrlanding.tpl');
            \mkw\store::fillTemplate($view);
            $view->setVar('mnrlanding', $statlap->toPublic());
            $view->printTemplateResult(true);
        }
        else {
            \mkw\store::redirectTo404($com, $this->params);
        }
    }
}