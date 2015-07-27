<?php

namespace Controllers;

use mkw\ArCalculator;
use mkw\store;

class bizonylattetelController extends \mkwhelpers\MattableController {

	public function __construct($params) {
		$this->setEntityName('Entities\Bizonylattetel');
//		$this->setKarbFormTplName('?howto?karbform.tpl');
//		$this->setKarbTplName('?howto?karb.tpl');
//		$this->setListBodyRowTplName('?howto?lista_tbody_tr.tpl');
//		$this->setListBodyRowVarName('_egyed');
		parent::__construct($params);
	}

	public function loadVars($t, $forKarb = false) {
		$termek = new termekController($this->params);
		$vtsz = new vtszController($this->params);
		$afa = new afaController($this->params);
		$x = array();
		if (!$t) {
			$t = new \Entities\Bizonylattetel();
			$this->getEm()->detach($t);
			$x['id'] = store::createUID();
			$x['oper'] = 'add';
		}
		else {
			$x['id'] = $t->getId();
			$x['oper'] = 'edit';
		}
		$x['termek'] = $t->getTermekId();
		$x['termekvaltozat'] = $t->getTermekvaltozatId();
		$x['termeknev'] = $t->getTermeknev();
		$x['cikkszam'] = $t->getCikkszam();
		$x['me'] = $t->getMe();
		$x['mennyiseg'] = $t->getMennyiseg();
		$x['nettoegysar'] = $t->getNettoegysar();
		$x['bruttoegysar'] = $t->getBruttoegysar();
		$x['netto'] = $t->getNetto();
        $x['afa'] = $t->getAfaertek();
		$x['brutto'] = $t->getBrutto();
		$x['nettoegysarhuf'] = $t->getNettoegysarhuf();
		$x['bruttoegysarhuf'] = $t->getBruttoegysarhuf();
		$x['nettohuf'] = $t->getNettohuf();
        $x['afahuf'] = $t->getAfaertekhuf();
		$x['bruttohuf'] = $t->getBruttohuf();
		$x['hataridostr'] = $t->getHataridoStr();
        $x['mainurl'] = store::getConfigValue('mainurl');
        $x['afanev'] = $t->getAfanev();
		$term = $t->getTermek();
		if ($term) {
            $eb = $term->getBruttoAr($t->getTermekvaltozat(), $t->getBizonylatfej()->getPartner());
            $x['eladasibrutto'] = $eb;
            if ($x['bruttoegysar'] != 0) {
                $x['haszonszazalek'] = $eb / $x['bruttoegysar'] * 100 - 100;
            }
            else {
                $x['haszonszazalek'] = 0;
            }
			$x['kozepeskepurl'] = $term->getKepUrlMedium();
			$x['kiskepurl'] = $term->getKepUrlSmall();
			$x['minikepurl'] = $term->getKepUrlMini();
			$x['kepurl'] = $term->getKepUrlLarge();
			$x['slug'] = $term->getSlug();
            $x['link'] = \mkw\Store::getRouter()->generate('showtermek', store::getConfigValue('mainurl'), array('slug' => $term->getSlug()));
        }

		if ($forKarb) {
			$x['valtozatlist'] = $termek->getValtozatList($t->getTermekId(), $t->getTermekvaltozatId());
			$x['vtszlist'] = $vtsz->getSelectList(($t->getVtsz() ? $t->getVtsz()->getId() : 0));
			$x['afalist'] = $afa->getSelectList(($t->getAfa() ? $t->getAfa()->getId() : 0));
		}
		return $x;
	}

	protected function setFields($obj) {
		return $obj;
	}

	public function getemptyrow() {
        $biztipus = $this->params->getStringRequestParam('type');
		$view = $this->createView('bizonylattetelkarb.tpl');
		$view->setVar('tetel', $this->loadVars(null, true));
		// TODO emeld ki bizonylattipusba
        $bt = $this->getRepo('Entities\Bizonylattipus')->find($biztipus);
        $bt->setTemplateVars($view);
		echo $view->getTemplateResult();
	}

	public function getar() {
        // Nincsenek ársávok
        if (!\mkw\Store::isArsavok()) {
            $termek = $this->getEm()->getRepository('Entities\Termek')->find($this->params->getIntRequestParam('termek'));
            $valtozat = null;
            if ($this->params->getIntRequestParam('valtozat')) {
                $valtozat = $this->getEm()->getRepository('Entities\TermekValtozat')->find($this->params->getIntRequestParam('valtozat'));
            }
            if ($termek) {
                $r = array(
                    'netto' => $termek->getNettoAr($valtozat),
                    'brutto' => $termek->getBruttoAr($valtozat)
                );
                echo json_encode($r);
            }
            else {
                echo json_encode(array(
                    'netto' => 0,
                    'brutto' => 0
                ));
            }
        }
        // Vannak ársávok
        else {
            $termek = $this->getEm()->getRepository('Entities\Termek')->find($this->params->getIntRequestParam('termek'));
            $partner = $this->getEm()->getRepository('Entities\Partner')->find($this->params->getIntRequestParam('partner'));
            $valutanem = $this->getEm()->getRepository('Entities\Valutanem')->find($this->params->getIntRequestParam('valutanem'));
            $valtozat = null;
            if ($this->params->getIntRequestParam('valtozat')) {
                $valtozat = $this->getEm()->getRepository('Entities\TermekValtozat')->find($this->params->getIntRequestParam('valtozat'));
            }
            if ($termek) {
                $r = array(
                    'netto' => $termek->getNettoAr($valtozat, $partner, $valutanem),
                    'brutto' => $termek->getBruttoAr($valtozat, $partner, $valutanem)
                );
                echo json_encode($r);
            }
            else {
                echo json_encode(array(
                    'netto' => 0,
                    'brutto' => 0
                ));
            }
        }
	}

	public function calcar() {
		$afaent = $this->getEm()->getRepository('Entities\Afa')->find($this->params->getIntRequestParam('afa'));
		$arfolyam = $this->params->getNumRequestParam('arfolyam', 1);
		$nettoegysar = $this->params->getNumRequestParam('nettoegysar', 0);
		$mennyiseg = $this->params->getNumRequestParam('mennyiseg', 0);

		$bruttoegysar = $afaent->calcBrutto($nettoegysar);
		$netto = $nettoegysar * $mennyiseg;
		$brutto = $afaent->calcBrutto($netto);
		$afa = $brutto - $netto;

		$nettoegysarhuf = $nettoegysar * $arfolyam;
		$bruttoegysarhuf = $bruttoegysar * $arfolyam;
		$nettohuf = $netto * $arfolyam;
		$bruttohuf = $brutto * $arfolyam;
		$afahuf = $afa * $arfolyam;
		echo json_encode(
				array(
					'nettoegysar' => $nettoegysar,
					'bruttoegysar' => $bruttoegysar,
					'netto' => $netto,
					'brutto' => $brutto,
					'afa' => $afa,
					'nettoegysarhuf' => $nettoegysarhuf,
					'bruttoegysarhuf' => $bruttoegysarhuf,
					'nettohuf' => $nettohuf,
					'bruttohuf' => $bruttohuf,
					'afahuf' => $afahuf
				)
		);
	}

	public function valtozathtmllist() {
		$tc = new termekController($this->params);
		$tomb = array(
			'id' => $this->params->getRequestParam('tetelid', 0),
			'valtozatlist' => $tc->getValtozatList($this->params->getRequestParam('id', 0), $this->params->getRequestParam('sel', 0))
		);
		$view = $this->createView('bizonylatteteltermekvaltozatselect.tpl');
		$view->setVar('tetel', $tomb);
		echo json_encode(array(
                'html' => $view->getTemplateResult(),
                'db' => count($tomb['valtozatlist'])
        ));
	}

}