<?php

namespace Controllers;

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
        $oper = $this->params->getStringRequestParam('oper');
		$termek = new termekController($this->params);
		$vtsz = new vtszController($this->params);
		$afa = new afaController($this->params);
		$mijszpartner = new partnerController($this->params);
		$x = array();
		if (!$t) {
			$t = new \Entities\Bizonylattetel();
			$this->getEm()->detach($t);
			$x['id'] = \mkw\store::createUID();
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
        $x['mozgat'] = $t->getMozgat();
		$x['me'] = $t->getME();
        if ($oper === 'storno') {
            $x['netto'] = $t->getNetto() * -1;
            $x['afa'] = $t->getAfaertek() * -1;
            $x['brutto'] = $t->getBrutto() * -1;
            $x['nettohuf'] = $t->getNettohuf() * -1;
            $x['afahuf'] = $t->getAfaertekhuf() * -1;
            $x['bruttohuf'] = $t->getBruttohuf() * -1;
            $x['mennyiseg'] = $t->getMennyiseg() * -1;
        }
        else {
            $x['mennyiseg'] = $t->getMennyiseg();
            $x['netto'] = $t->getNetto();
            $x['afa'] = $t->getAfaertek();
            $x['brutto'] = $t->getBrutto();
            $x['nettohuf'] = $t->getNettohuf();
            $x['afahuf'] = $t->getAfaertekhuf();
            $x['bruttohuf'] = $t->getBruttohuf();
        }
		$x['nettoegysar'] = $t->getNettoegysar();
		$x['bruttoegysar'] = $t->getBruttoegysar();
		$x['nettoegysarhuf'] = $t->getNettoegysarhuf();
		$x['bruttoejs-termeklinkgysarhuf'] = $t->getBruttoegysarhuf();

        $x['enettoegysar'] = $t->getEnettoegysar();
        $x['ebruttoegysar'] = $t->getEbruttoegysar();
        $x['enettoegysarhuf'] = $t->getEnettoegysarhuf();
        $x['ebruttoegysarhuf'] = $t->getEbruttoegysarhuf();

        $x['kedvezmeny'] = $t->getKedvezmeny();

		$x['hataridostr'] = $t->getHataridoStr();
        $x['mainurl'] = \mkw\store::getConfigValue('mainurl');
        $x['afanev'] = $t->getAfanev();
        $x['elolegtipus'] = $t->getElolegtipus();
        if (\mkw\store::isMIJSZ()) {
            $x['mijszev'] = $t->getMIJSZEv();
            $x['mijszpartner'] = $t->getMIJSZPartnerId();
            $x['mijszpartnernev'] = $t->getMIJSZPartnernev();
        }
        else {
            $x['mijszev'] = 0;
            $x['mijszpartner'] = 0;
            $x['mijszpartnernev'] = '';
        }
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
			$x['kozepeskepurl'] = $term->getKepurlMedium();
			$x['kiskepurl'] = $term->getKepurlSmall();
			$x['minikepurl'] = $term->getKepurlMini();
			$x['kepurl'] = $term->getKepurlLarge();
			$x['slug'] = $term->getSlug();
            $x['link'] = \mkw\store::getRouter()->generate('showtermek', \mkw\store::getConfigValue('mainurl'), array('slug' => $term->getSlug()));
            $x['kartonurl'] = \mkw\store::getRouter()->generate('admintermekkartonview', false, array(), array('id' => $term->getId()));
        }

		if ($forKarb) {
			$x['valtozatlist'] = $termek->getValtozatList($t->getTermekId(), $t->getTermekvaltozatId());
			$x['vtszlist'] = $vtsz->getSelectList(($t->getVtsz() ? $t->getVtsz()->getId() : 0));
			$x['afalist'] = $afa->getSelectList(($t->getAfa() ? $t->getAfa()->getId() : 0));
			if (\mkw\store::isMIJSZ()) {
                $x['mijszpartnerlist'] = $mijszpartner->getSelectList($t->getMIJSZPartnerId());
            }
		}
		return $x;
	}

	protected function setFields($obj) {
		return $obj;
	}

	public function getemptyrow() {
        $biztipus = $this->params->getStringRequestParam('type');
		$view = $this->createView('bizonylattetelkarb.tpl');
		$tetel = $this->loadVars(null, true);
		if (\mkw\store::isMIJSZ()) {
            $mijszpartner = new partnerController($this->params);
            $partnerid = $this->params->getIntRequestParam('partner');
            $partner = $this->getRepo('Entities\Partner')->find($partnerid);
            if ($partner) {
                if (\mkw\store::isMIJSZ()) {
                    $tetel['mijszpartnerlist'] = $mijszpartner->getSelectList($partnerid);
                }
                $tetel['mijszpartner'] = $partnerid;
                $tetel['mijszpartnernev'] = $partner->getNev();
            }
            $tetel['mijszev'] = date('Y') * 1;
        }
		$view->setVar('tetel', $tetel);
        $bt = $this->getRepo('Entities\Bizonylattipus')->find($biztipus);
        $bt->setTemplateVars($view);
		echo $view->getTemplateResult();
	}

	public function getquickemptyrow() {
        $biztipus = $this->params->getStringRequestParam('type');
		$view = $this->createView('bizonylattetelquickkarb.tpl');
		$view->setVar('tetel', $this->loadVars(null, true));
        $bt = $this->getRepo('Entities\Bizonylattipus')->find($biztipus);
        $bt->setTemplateVars($view);
		echo $view->getTemplateResult();
	}

	public function getar() {
        // Nincsenek ársávok
        if (!\mkw\store::isArsavok()) {
            $termek = $this->getEm()->getRepository('Entities\Termek')->find($this->params->getIntRequestParam('termek'));
            $partner = $this->getEm()->getRepository('Entities\Partner')->find($this->params->getIntRequestParam('partner'));
            $valutanem = $this->getEm()->getRepository('Entities\Valutanem')->find($this->params->getIntRequestParam('valutanem'));
            $valtozat = null;
            if ($this->params->getIntRequestParam('valtozat')) {
                $valtozat = $this->getEm()->getRepository('Entities\TermekValtozat')->find($this->params->getIntRequestParam('valtozat'));
            }
            if ($termek) {
                $r = array(
                    'netto' => $termek->getNettoAr($valtozat),
                    'brutto' => $termek->getBruttoAr($valtozat),
                    'kedvezmeny' => $termek->getKedvezmeny($partner),
                    'enetto' => $termek->getKedvezmenynelkuliNettoAr($valtozat, $partner, $valutanem),
                    'ebrutto' => $termek->getKedvezmenynelkuliBruttoAr($valtozat, $partner, $valutanem)
                );
                echo json_encode($r);
            }
            else {
                echo json_encode(array(
                    'netto' => 0,
                    'brutto' => 0,
                    'kedvezmeny' => 0,
                    'enetto' => 0,
                    'ebrutto' => 0
                ));
            }
        }
        // Vannak ársávok
        else {
            /** @var \Entities\Termek $termek */
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
                    'brutto' => $termek->getBruttoAr($valtozat, $partner, $valutanem),
                    'kedvezmeny' => $termek->getKedvezmeny($partner),
                    'enetto' => $termek->getKedvezmenynelkuliNettoAr($valtozat, $partner, $valutanem),
                    'ebrutto' => $termek->getKedvezmenynelkuliBruttoAr($valtozat, $partner, $valutanem)
                );
                echo json_encode($r);
            }
            else {
                echo json_encode(array(
                    'netto' => 0,
                    'brutto' => 0,
                    'kedvezmeny' => 0,
                    'enetto' => 0,
                    'ebrutto' => 0
                ));
            }
        }
	}

    public function calcAr($afaid, $arfolyam, $nettoegysar, $enettoegysar, $mennyiseg) {
		$afaent = $this->getEm()->getRepository('Entities\Afa')->find($afaid);
        $bruttoegysar = 0;
        $ebruttoegysar = 0;
		if ($afaent) {
            $bruttoegysar = $afaent->calcBrutto($nettoegysar);
            $ebruttoegysar = $afaent->calcBrutto($enettoegysar);
        }
		$netto = $nettoegysar * $mennyiseg;

        $brutto = 0;
        if ($afaent) {
            $brutto = $afaent->calcBrutto($netto);
        }
		$afa = $brutto - $netto;

		$nettoegysarhuf = $nettoegysar * $arfolyam;
		$bruttoegysarhuf = $bruttoegysar * $arfolyam;
        $enettoegysarhuf = $enettoegysar * $arfolyam;
        $ebruttoegysarhuf = $ebruttoegysar * $arfolyam;
		$nettohuf = $netto * $arfolyam;
		$bruttohuf = $brutto * $arfolyam;
		$afahuf = $afa * $arfolyam;
		return array(
            'nettoegysar' => $nettoegysar,
            'bruttoegysar' => $bruttoegysar,
            'enettoegysar' => $enettoegysar,
            'ebruttoegysar' => $ebruttoegysar,
            'netto' => $netto,
            'brutto' => $brutto,
            'afa' => $afa,
            'nettoegysarhuf' => $nettoegysarhuf,
            'bruttoegysarhuf' => $bruttoegysarhuf,
            'enettoegysarhuf' => $enettoegysarhuf,
            'ebruttoegysarhuf' => $ebruttoegysarhuf,
            'nettohuf' => $nettohuf,
            'bruttohuf' => $bruttohuf,
            'afahuf' => $afahuf
		);
    }

	public function calcarforclient() {
        echo json_encode($this->calcAr(
            $this->params->getIntRequestParam('afa'),
            $this->params->getNumRequestParam('arfolyam', 1),
            $this->params->getNumRequestParam('nettoegysar', 0),
            $this->params->getNumRequestParam('enettoegysar', 0),
            $this->params->getNumRequestParam('mennyiseg', 0)
        ));
	}

	public function valtozathtmllist() {
		$tc = new termekController($this->params);
		$tomb = array(
			'id' => $this->params->getRequestParam('tetelid', 0),
			'valtozatlist' => $tc->getValtozatList($this->params->getRequestParam('id', 0), $this->params->getRequestParam('sel', 0), $this->params->getIntRequestParam('raktar', 0))
		);
		$view = $this->createView('bizonylatteteltermekvaltozatselect.tpl');
		$view->setVar('tetel', $tomb);
		echo json_encode(array(
                'html' => $view->getTemplateResult(),
                'db' => count($tomb['valtozatlist'])
        ));
	}

	public function quickvaltozathtmllist() {
        $termekid = $this->params->getRequestParam('id', 0);
        $termektetelid = $this->params->getRequestParam('tetelid', 0);
		$tc = new termekController($this->params);
		$view = $this->createView('bizonylattetelquickvaltozatkarb.tpl');
        $valtozatlist = $tc->getValtozatList($termekid, 0);
        $vlist = array();
        foreach($valtozatlist as $v) {
            $v['tetelid'] = \mkw\store::createUID();
            $v['termekid'] = $termekid;
            $v['termektetelid'] = $termektetelid;
            $vlist[] = $v;
        }
		$view->setVar('valtozatlist', $vlist);
		echo json_encode(array(
                'tetelid' => $termektetelid,
                'html' => $view->getTemplateResult()
        ));
	}

}