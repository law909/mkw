<?php

namespace Controllers;

use Entities\MNRStatic;
use Entities\MNRStaticPage;
use Entities\MNRStaticPageTranslation;
use Entities\MNRStaticTranslation;
use Entities\TermekValtozat,
	Entities\TermekRecept;
use mkw\store;
use mkwhelpers\FilterDescriptor;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class mnrstaticpageController extends \mkwhelpers\MattableController {

    public function __construct($params) {
		$this->setEntityName(MNRStaticPage::class);
		$this->setKarbFormTplName('mnrstatickarbform.tpl');
		$this->setKarbTplName('mnrstatickarb.tpl');
		$this->setListBodyRowTplName('mnrstaticlista_tbody_tr.tpl');
		$this->setListBodyRowVarName('_mnrstatic');
		parent::__construct($params);
	}

	public function loadVars($t, $forKarb = false) {
        $translationsCtrl = new mnrstatictranslationController($this->params);
        $translations = array();
		$x = array();
		if (!$t) {
			$t = new \Entities\MNRStaticPage();
			$this->getEm()->detach($t);
		}
		$x['id'] = $t->getId();
		$x['szlogen1'] = $t->getSzlogen1();
		$x['szlogen2'] = $t->getSzlogen2();
		$x['tartalom'] = $t->getTartalom();
		$x['szoveg1'] = $t->getSzoveg1();
        $x['szoveg2'] = $t->getSzoveg2();
        $x['szoveg3'] = $t->getSzoveg3();
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
     * @param \Entities\MNRStaticPage $obj
     * @return mixed
     */
	protected function setFields($obj) {
		$obj->setSzlogen1($this->params->getStringRequestParam('szlogen1'));
        $obj->setSzlogen2($this->params->getStringRequestParam('szlogen2'));
		$obj->setKepurl($this->params->getStringRequestParam('kepurl', ''));
        if (\mkw\store::isMultilang()) {
            $_tf = \Entities\MNRStaticPage::getTranslatedFields();
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
					$translation = new \Entities\MNRStaticPageTranslation(
                        $this->params->getStringRequestParam('translationlocale_' . $translationid),
                        $mezo,
                        $mezoertek
                    );
					$obj->addTranslation($translation);
					$this->getEm()->persist($translation);
				}
				elseif ($oper === 'edit') {
					$translation = $this->getEm()->getRepository(MNRStaticPageTranslation::class)->find($translationid);
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

    public function getemptyrow() {
        $mnrstatic = store::getEm()->getRepository(MNRStatic::class)->find($this->params->getIntRequestParam('mnrstaticid'));
        $view = $this->createView('mnrstaticmnrstaticpagekarb.tpl');
        $view->setVar('page', $this->loadVars(null, $mnrstatic, true));
        echo $view->getTemplateResult();
    }

    public function delall() {
	    /** @var MNRStatic $mnrstatic */
        $mnrstatic = store::getEm()->getRepository(MNRStatic::class)->find($this->params->getIntRequestParam('mnrstaticid'));
        $valtozatok = $mnrstatic->getMNRStaticPages();
        foreach ($valtozatok as $valt) {
            $this->getEm()->remove($valt);
        }
        $this->getEm()->flush();
    }

}