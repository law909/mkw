<?php

namespace Controllers;

use Entities\TermekValtozat,
	Entities\TermekRecept;
use mkw\store;
use mkwhelpers\FilterDescriptor;

class termekController extends \mkwhelpers\MattableController {

    private $kaphatolett = false;
    private $vanshowarsav = false;

    public function __construct($params) {
		$this->setEntityName('Entities\Termek');
		$this->setKarbFormTplName('termekkarbform.tpl');
		$this->setKarbTplName('termekkarb.tpl');
		$this->setListBodyRowTplName('termeklista_tbody_tr.tpl');
		$this->setListBodyRowVarName('_termek');
		parent::__construct($params);
	}

	protected function loadVars($t, $forKarb = false) {
        $termekarCtrl = new termekarController($this->params);
        $kepCtrl = new termekkepController($this->params);
        $receptCtrl = new termekreceptController($this->params);
        $valtozatCtrl = new termekvaltozatController($this->params);
        $kapcsolodoCtrl = new termekkapcsolodoController($this->params);
        $translationsCtrl = new termektranslationController($this->params);
        $ar = array();
        $kep = array();
        $recept = array();
        $valtozat = array();
        $lvaltozat = array();
        $kapcsolodo = array();
        $translations = array();
		$x = array();
		if (!$t) {
			$t = new \Entities\Termek();
			$this->getEm()->detach($t);
		}
		$x['id'] = $t->getId();
		$x['vtsznev'] = $t->getVtszNev();
		$x['afanev'] = $t->getAfaNev();
		$x['nev'] = $t->getNev();
		$x['slug'] = $t->getSlug();
		$x['me'] = $t->getMe();
		$x['cikkszam'] = $t->getCikkszam();
		$x['idegencikkszam'] = $t->getIdegencikkszam();
        $x['vonalkod'] = $t->getVonalkod();
        $x['idegenkod'] = $t->getIdegenkod();
		$x['oldalcim'] = $t->getOldalcim();
		$x['rovidleiras'] = $t->getRovidleiras();
		$x['leiras'] = $t->getLeiras();
		$x['seodescription'] = $t->getSeodescription();
		$x['lathato'] = $t->getLathato();
		$x['hozzaszolas'] = $t->getHozzaszolas();
		$x['ajanlott'] = $t->getAjanlott();
		$x['kiemelt'] = $t->getKiemelt();
		$x['inaktiv'] = $t->getInaktiv();
		$x['mozgat'] = $t->getMozgat();
        $x['kozvetitett'] = $t->getKozvetitett();
		$x['hparany'] = $t->getHparany();
		$x['cimkek'] = $t->getCimkeNevek();
        if (!\mkw\Store::isArsavok()) {
            $x['netto'] = $t->getNetto();
            $x['brutto'] = $t->getBrutto();
            $x['akciosnetto'] = $t->getAkciosnetto();
            $x['akciosbrutto'] = $t->getAkciosbrutto();
            $x['akciostart'] = $t->getAkciostart();
            $x['akciostartstr'] = $t->getAkciostartStr();
            $x['akciostop'] = $t->getAkciostop();
            $x['akciostopstr'] = $t->getAkciostopStr();
        }
        else {
            $x['netto'] = 0;
            $x['brutto'] = 0;
            if ($this->vanshowarsav) {
                $arsav = $t->getTermekArak();
                if (count($arsav)) {
                    $arsav = $arsav[0];
                    $x['netto'] = $arsav->getNetto();
                    $x['brutto'] = $arsav->getBrutto();
                }
            }
/*            $x['netto'] = 0;
            $x['brutto'] = 0;
            if (!$this->showarsav) {
                $this->showarsav = \mkw\Store::getParameter(\mkw\consts::ShowTermekArsav);
            }
            if (!$this->showarsavvalutanem) {
                $this->showarsavvalutanem = \mkw\Store::getParameter(\mkw\consts::ShowTermekArsavValutanem);
            }
            if ($this->showarsav && $this->showarsavvalutanem) {
                $arsav = \mkw\Store::getEm()->getRepository('Entities\TermekAr')->getArsav($t, $this->showarsavvalutanem, $this->showarsav);
                if ($arsav) {
                    $x['netto'] = $arsav->getNetto();
                    $x['brutto'] = $arsav->getBrutto();
                }
            }
 *
 */
        }
		$x['termekexportbanszerepel'] = $t->getTermekexportbanszerepel();
		$x['nemkaphato'] = $t->getNemkaphato();
        $x['fuggoben'] = $t->getFuggoben();
        $x['szallitasiido'] = $t->getSzallitasiido();
		if ($forKarb) {

			foreach ($t->getTermekKepek() as $kepje) {
				$kep[] = $kepCtrl->loadVars($kepje);
			}
			//$kep[]=$kepCtrl->loadVars(null);
			$x['kepek'] = $kep;

            if (store::getSetupValue('kapcsolodotermekek')) {
                foreach ($t->getTermekKapcsolodok() as $tkapcsolodo) {
                    $kapcsolodo[] = $kapcsolodoCtrl->loadVars($tkapcsolodo, true);
                }
                //$kapcsolodo[]=$kapcsolodoCtrl->loadVars(null,true);
                $x['kapcsolodok'] = $kapcsolodo;
            }

			if (store::getSetupValue('receptura')) {
				foreach ($t->getTermekReceptek() as $trecept) {
					$recept[] = $receptCtrl->loadVars($trecept, true);
				}
				//$recept[]=$receptCtrl->loadVars(null,true);
				$x['receptek'] = $recept;
			}
			if (store::getSetupValue('termekvaltozat')) {
				foreach ($t->getValtozatok() as $tvaltozat) {
					$valtozat[] = $valtozatCtrl->loadVars($tvaltozat, $t);
				}
				//$valtozat[]=$valtozatCtrl->loadVars(null);
				$x['valtozatok'] = $valtozat;
			}
            if (\mkw\Store::isArsavok()) {
                foreach ($t->getTermekArak() as $tar) {
                    $ar[] = $termekarCtrl->loadVars($tar, true);
                }
                $x['arak'] = $ar;
            }
            if (\mkw\Store::isMultilang()) {
                foreach($t->getTranslations() as $tr) {
                    $translations[] = $translationsCtrl->loadVars($tr);
                }
                $x['translations'] = $translations;
            }
		}
		$x['termekfa1nev'] = $t->getTermekfa1Nev();
		$x['termekfa2nev'] = $t->getTermekfa2Nev();
		$x['termekfa3nev'] = $t->getTermekfa3Nev();
		$x['termekfa1'] = $t->getTermekfa1Id();
		$x['termekfa2'] = $t->getTermekfa2Id();
		$x['termekfa3'] = $t->getTermekfa3Id();
		$x['kepurl'] = $t->getKepurl();
		$x['kepurlsmall'] = $t->getKepurlSmall();
		$x['kepurlmedium'] = $t->getKepurlMedium();
		$x['kepurllarge'] = $t->getKepurlLarge();
		$x['kepleiras'] = $t->getKepleiras();
        $x['regikepurl'] = $t->getRegikepurl();
		$x['szelesseg'] = $t->getSzelesseg();
		$x['magassag'] = $t->getMagassag();
		$x['hosszusag'] = $t->getHosszusag();
		$x['suly'] = $t->getSuly();
		$x['osszehajthato'] = $t->getOsszehajthato();
		$x['megtekintesdb'] = $t->getMegtekintesdb();
		$x['megvasarlasdb'] = $t->getMegvasarlasdb();
        $x['gyartonev'] = $t->getGyartoNev();
        $x['keszlet'] = $t->getKeszlet();
		$x['termekcsoportnev'] = $t->getTermekcsoportNev();
        if (store::getSetupValue('termekvaltozat')) {
            foreach ($t->getValtozatok() as $tvaltozat) {
                $mozgasdb = $tvaltozat->getMozgasDb();
                if ($mozgasdb) {
                    $lvaltozat[] = $valtozatCtrl->loadVars($tvaltozat, $t);
                }
            }
            $x['valtozatkeszlet'] = $lvaltozat;
        }
		return $x;
	}

    /**
     * @param \Entities\Termek $obj
     * @return mixed
     */
	protected function setFields($obj) {
        $oldnemkaphato = $obj->getNemkaphato();
		$afa = store::getEm()->getRepository('Entities\Afa')->find($this->params->getIntRequestParam('afa'));
		if ($afa) {
			$obj->setAfa($afa);
		}
		$vtsz = store::getEm()->getRepository('Entities\Vtsz')->find($this->params->getIntRequestParam('vtsz'));
		if ($vtsz) {
			$obj->setVtsz($vtsz);
		}
		$valt = store::getEm()->getRepository('Entities\TermekValtozatAdatTipus')->find($this->params->getIntRequestParam('valtozatadattipus'));
		if ($valt) {
			$obj->setValtozatadattipus($valt);
		}
		else {
			$obj->setValtozatadattipus(null);
		}
		$ck = store::getEm()->getRepository('Entities\Partner')->find($this->params->getIntRequestParam('gyarto'));
		if ($ck) {
			$obj->setGyarto($ck);
		}
        else {
            $obj->setGyarto(null);
        }
        $csoport = $this->getRepo('Entities\Termekcsoport')->find($this->params->getIntRequestParam('termekcsoport'));
        if ($csoport) {
            $obj->setTermekcsoport($csoport);
        }
        else {
            $obj->setTermekcsoport(null);
        }
		$obj->setNev($this->params->getStringRequestParam('nev'));
		$obj->setMe($this->params->getStringRequestParam('me'));
		$obj->setCikkszam($this->params->getStringRequestParam('cikkszam'));
		$obj->setIdegencikkszam($this->params->getStringRequestParam('idegencikkszam'));
        $obj->setVonalkod($this->params->getStringRequestParam('vonalkod'));
        $obj->setIdegenkod($this->params->getStringRequestParam('idegenkod'));
		$obj->setOldalcim($this->params->getStringRequestParam('oldalcim'));
		$obj->setRovidleiras($this->params->getStringRequestParam('rovidleiras'));
		$obj->setLeiras($this->params->getOriginalStringRequestParam('leiras'));
		$obj->setSeodescription($this->params->getStringRequestParam('seodescription'));
		$obj->setLathato($this->params->getBoolRequestParam('lathato'));
		$obj->setHozzaszolas($this->params->getBoolRequestParam('hozzaszolas'));
		$obj->setAjanlott($this->params->getBoolRequestParam('ajanlott'));
		$obj->setKiemelt($this->params->getBoolRequestParam('kiemelt'));
		$obj->setInaktiv($this->params->getBoolRequestParam('inaktiv'));
		$obj->setMozgat($this->params->getBoolRequestParam('mozgat'));
		$obj->setHparany($this->params->getFloatRequestParam('hparany'));
		$obj->setSzelesseg($this->params->getFloatRequestParam('szelesseg'));
		$obj->setMagassag($this->params->getFloatRequestParam('magassag'));
		$obj->setHosszusag($this->params->getFloatRequestParam('hosszusag'));
		$obj->setSuly($this->params->getFloatRequestParam('suly'));
		$obj->setOsszehajthato($this->params->getBoolRequestParam('osszehajthato'));
		$obj->setKepurl($this->params->getStringRequestParam('kepurl', ''));
		$obj->setKepleiras($this->params->getStringRequestParam('kepleiras', ''));
        $obj->setRegikepurl($this->params->getStringRequestParam('regikepurl', ''));
		$obj->setTermekexportbanszerepel($this->params->getBoolRequestParam('termekexportbanszerepel'));
		$obj->setNemkaphato($this->params->getBoolRequestParam('nemkaphato'));
        $obj->setFuggoben($this->params->getBoolRequestParam('fuggoben'));
        $obj->setSzallitasiido($this->params->getIntRequestParam('szallitasiido'));
        $obj->setKozvetitett($this->params->getBoolRequestParam('kozvetitett'));
		$farepo = store::getEm()->getRepository('Entities\TermekFa');
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
		$obj->removeAllCimke();
		$cimkekpar = $this->params->getArrayRequestParam('cimkek');
		foreach ($cimkekpar as $cimkekod) {
			$cimke = $this->getEm()->getRepository('Entities\Termekcimketorzs')->find($cimkekod);
			if ($cimke) {
				$obj->addCimke($cimke);
			}
		}
		$obj->setBrutto($this->params->getNumRequestParam('brutto'));
		$obj->setNetto($this->params->getNumRequestParam('netto'));
		$obj->setAkciosnetto($this->params->getNumRequestParam('akciosnetto'));
		//$obj->setAkciosbrutto($this->params->getNumRequestParam('akciosbrutto'));
		$obj->setAkciostart($this->params->getStringRequestParam('akciostart'));
		$obj->setAkciostop($this->params->getStringRequestParam('akciostop'));
		$kepids = $this->params->getArrayRequestParam('kepid');
		foreach ($kepids as $kepid) {
			if ($this->params->getStringRequestParam('kepurl_' . $kepid, '') !== '') {
				$oper = $this->params->getStringRequestParam('kepoper_' . $kepid);
				if ($oper == 'add') {
					$kep = new \Entities\TermekKep();
					$obj->addTermekKep($kep);
					$kep->setUrl($this->params->getStringRequestParam('kepurl_' . $kepid));
					$kep->setLeiras($this->params->getStringRequestParam('kepleiras_' . $kepid));
					$this->getEm()->persist($kep);
				}
				elseif ($oper == 'edit') {
					$kep = store::getEm()->getRepository('Entities\TermekKep')->find($kepid);
					if ($kep) {
						$kep->setUrl($this->params->getStringRequestParam('kepurl_' . $kepid));
						$kep->setLeiras($this->params->getStringRequestParam('kepleiras_' . $kepid));
						$this->getEm()->persist($kep);
					}
				}
			}
		}
        if (\mkw\Store::isArsavok()) {
            $arids = $this->params->getArrayRequestParam('arid');
            foreach ($arids as $arid) {
				$oper = $this->params->getStringRequestParam('aroper_' . $arid);
                $valutanem = $this->getEm()->getRepository('Entities\Valutanem')->find($this->params->getIntRequestParam('arvalutanem_' . $arid));
                if (!$valutanem) {
                    $valutanem = $this->getEm()->getRepository('Entities\Valutanem')->find(store::getParameter(\mkw\consts::Valutanem));
                }
				if ($oper == 'add') {
					$ar = new \Entities\TermekAr();
					$obj->addTermekAr($ar);
                    $ar->setAzonosito($this->params->getStringRequestParam('arazonosito_' . $arid));
                    $ar->setNetto($this->params->getNumRequestParam('arnetto_' . $arid));
                    $brutto = $this->params->getNumRequestParam('arbrutto_' . $arid);
                    if ($brutto != 0) {
                        $ar->setBrutto($brutto);
                    }
                    if ($valutanem) {
                        $ar->setValutanem($valutanem);
                    }
					$this->getEm()->persist($ar);
				}
				elseif ($oper == 'edit') {
					$ar = $this->getEm()->getRepository('Entities\TermekAr')->find($arid);
					if ($ar) {
                        $ar->setAzonosito($this->params->getStringRequestParam('arazonosito_' . $arid));
                        $ar->setNetto($this->params->getNumRequestParam('arnetto_' . $arid));
                        $brutto = $this->params->getNumRequestParam('arbrutto_' . $arid);
                        if ($brutto != 0) {
                            $ar->setBrutto($brutto);
                        }
                        if ($valutanem) {
                            $ar->setValutanem($valutanem);
                        }
						$this->getEm()->persist($ar);
					}
				}
            }
        }
        if (\mkw\Store::isMultilang()) {
            $translationids = $this->params->getArrayRequestParam('translationid');
            foreach ($translationids as $translationid) {
				$oper = $this->params->getStringRequestParam('translationoper_' . $translationid);
				if ($oper == 'add') {
					$translation = new \Entities\TermekTranslation(
                        $this->params->getStringRequestParam('translationlocale_' . $translationid),
                        'nev',
                        $this->params->getStringRequestParam('translationnev_' . $translationid)
                    );
					$obj->addTranslation($translation);
					$this->getEm()->persist($translation);
				}
				elseif ($oper == 'edit') {
					$translation = $this->getEm()->getRepository('Entities\TermekTranslation')->find($translationid);
					if ($translation) {
                        $translation->setLocale($this->params->getStringRequestParam('translationlocale_' . $translationid));
                        $translation->setField('nev');
                        $translation->setContent($this->params->getStringRequestParam('translationnev_' . $translationid));
						$this->getEm()->persist($translation);
					}
				}
            }
        }
        if (store::getSetupValue('kapcsolodotermekek')) {
            $kapcsolodoids = $this->params->getArrayRequestParam('kapcsolodoid');
            foreach ($kapcsolodoids as $kapcsolodoid) {
                if (($this->params->getIntRequestParam('kapcsolodoaltermek_' . $kapcsolodoid) > 0)) {
                    $oper = $this->params->getStringRequestParam('kapcsolodooper_' . $kapcsolodoid);
                    $altermek = $this->getEm()->getRepository('Entities\Termek')->find($this->params->getIntRequestParam('kapcsolodoaltermek_' . $kapcsolodoid));
                    if ($oper == 'add') {
                        $kapcsolodo = new \Entities\TermekKapcsolodo();
                        $obj->addTermekKapcsolodo($kapcsolodo);
                        if ($altermek) {
                            $kapcsolodo->setAlTermek($altermek);
                        }
                        $this->getEm()->persist($kapcsolodo);
                    }
                    elseif ($oper == 'edit') {
                        $kapcsolodo = $this->getEm()->getRepository('Entities\TermekKapcsolodo')->find($kapcsolodoid);
                        if ($kapcsolodo) {
                            if ($altermek) {
                                $kapcsolodo->setAlTermek($altermek);
                            }
                            $this->getEm()->persist($kapcsolodo);
                        }
                    }
                }
            }
        }
		if (store::getSetupValue('receptura')) {
			$receptids = $this->params->getArrayRequestParam('receptid');
			foreach ($receptids as $receptid) {
				if (($this->params->getIntRequestParam('receptaltermek_' . $receptid) > 0)) {
					$oper = $this->params->getStringRequestParam('receptoper_' . $receptid);
					$altermek = $this->getEm()->getRepository('Entities\Termek')->find($this->params->getIntRequestParam('receptaltermek_' . $receptid));
					if ($oper == 'add') {
						$recept = new TermekRecept();
						$obj->addTermekRecept($recept);
						if ($altermek) {
							$recept->setAlTermek($altermek);
						}
						$recept->setMennyiseg($this->params->getFloatRequestParam('receptmennyiseg_' . $receptid));
						$recept->setKotelezo($this->params->getBoolRequestParam('receptkotelezo_' . $receptid));
						$this->getEm()->persist($recept);
					}
					elseif ($oper == 'edit') {
						$recept = $this->getEm()->getRepository('Entities\TermekRecept')->find($receptid);
						if ($recept) {
							if ($altermek) {
								$recept->setAlTermek($altermek);
							}
							$recept->setMennyiseg($this->params->getFloatRequestParam('receptmennyiseg_' . $receptid));
							$recept->setKotelezo($this->params->getBoolRequestParam('receptkotelezo_' . $receptid));
							$this->getEm()->persist($recept);
						}
					}
				}
			}
		}
		if (store::getSetupValue('termekvaltozat')) {
			$valtozatids = $this->params->getArrayRequestParam('valtozatid');
			foreach ($valtozatids as $valtozatid) {
				$valtdb = 0;
				$oper = $this->params->getStringRequestParam('valtozatoper_' . $valtozatid);
				if ($oper == 'add') {
					$valtozat = new TermekValtozat();
					$obj->addValtozat($valtozat);
					$valtozat->setLathato($this->params->getBoolRequestParam('valtozatlathato_' . $valtozatid));
					if ($obj->getNemkaphato()) {
						$valtozat->setElerheto(false);
					}
					else {
						$valtozat->setElerheto($this->params->getBoolRequestParam('valtozatelerheto_' . $valtozatid));
					}
//						$valtozat->setBrutto($this->params->getNumRequestParam('valtozatbrutto_'.$valtozatid));
					$valtozat->setNetto($this->params->getNumRequestParam('valtozatnetto_' . $valtozatid));
					$valtozat->setTermekfokep($this->params->getBoolRequestParam('valtozattermekfokep_' . $valtozatid));
					$valtozat->setCikkszam($this->params->getStringRequestParam('valtozatcikkszam_' . $valtozatid));
					$valtozat->setIdegencikkszam($this->params->getStringRequestParam('valtozatidegencikkszam_' . $valtozatid));
                    $valtozat->setVonalkod($this->params->getStringRequestParam('valtozatvonalkod_' . $valtozatid));
                    $valtozat->setBeerkezesdatum($this->params->getStringRequestParam('valtozatbeerkezesdatum_' . $valtozatid));

					$at = $this->getEm()->getRepository('Entities\TermekValtozatAdatTipus')->find($this->params->getIntRequestParam('valtozatadattipus1_' . $valtozatid));
					$valtert = $this->params->getStringRequestParam('valtozatertek1_' . $valtozatid);
					if ($at && $valtert) {
						$valtozat->setAdatTipus1($at);
						$valtozat->setErtek1($valtert);
						$valtdb++;
					}

					$at = $this->getEm()->getRepository('Entities\TermekValtozatAdatTipus')->find($this->params->getIntRequestParam('valtozatadattipus2_' . $valtozatid));
					$valtert = $this->params->getStringRequestParam('valtozatertek2_' . $valtozatid);
					if ($at && $valtert) {
						$valtozat->setAdatTipus2($at);
						$valtozat->setErtek2($valtert);
						$valtdb++;
					}

					$at = $this->getEm()->getRepository('Entities\TermekKep')->find($this->params->getIntRequestParam('valtozatkepid_' . $valtozatid));
					if ($at) {
						$valtozat->setKep($at);
					}

					if ($valtdb > 0) {
						$this->getEm()->persist($valtozat);
					}
					else {
						$obj->removeValtozat($valtozat);
					}
				}
				elseif ($oper == 'edit') {
					$valtozat = $this->getEm()->getRepository('Entities\TermekValtozat')->find($valtozatid);
					if ($valtozat) {
						$valtozat->setLathato($this->params->getBoolRequestParam('valtozatlathato_' . $valtozatid));
						if ($obj->getNemkaphato()) {
							$valtozat->setElerheto(false);
						}
						else {
							$valtozat->setElerheto($this->params->getBoolRequestParam('valtozatelerheto_' . $valtozatid));
						}
//							$valtozat->setBrutto($this->params->getNumRequestParam('valtozatbrutto_'.$valtozatid));
						$valtozat->setNetto($this->params->getNumRequestParam('valtozatnetto_' . $valtozatid));
						$valtozat->setTermekfokep($this->params->getBoolRequestParam('valtozattermekfokep_' . $valtozatid));
						$valtozat->setCikkszam($this->params->getStringRequestParam('valtozatcikkszam_' . $valtozatid));
						$valtozat->setIdegencikkszam($this->params->getStringRequestParam('valtozatidegencikkszam_' . $valtozatid));
                        $valtozat->setVonalkod($this->params->getStringRequestParam('valtozatvonalkod_' . $valtozatid));
                        $valtozat->setBeerkezesdatum($this->params->getStringRequestParam('valtozatbeerkezesdatum_' . $valtozatid));

						$at = $this->getEm()->getRepository('Entities\TermekValtozatAdatTipus')->find($this->params->getIntRequestParam('valtozatadattipus1_' . $valtozatid));
						$valtert = $this->params->getStringRequestParam('valtozatertek1_' . $valtozatid);
						if ($at && $valtert) {
							$valtozat->setAdatTipus1($at);
							$valtozat->setErtek1($valtert);
						}
						else {
							$valtozat->setAdatTipus1(null);
							$valtozat->setErtek1(null);
						}

						$at = $this->getEm()->getRepository('Entities\TermekValtozatAdatTipus')->find($this->params->getIntRequestParam('valtozatadattipus2_' . $valtozatid));
						$valtert = $this->params->getStringRequestParam('valtozatertek2_' . $valtozatid);
						if ($at && $valtert) {
							$valtozat->setAdatTipus2($at);
							$valtozat->setErtek2($valtert);
						}
						else {
							$valtozat->setAdatTipus2(null);
							$valtozat->setErtek2(null);
						}

                        if ($valtozat->getTermekfokep()) {
                            $valtozat->setKep(null);
                        }
                        else {
                            $at = $this->getEm()->getRepository('Entities\TermekKep')->find($this->params->getIntRequestParam('valtozatkepid_' . $valtozatid));
                            if ($at) {
                                $valtozat->setKep($at);
                            }
                            else {
                                $valtozat->setKep(null);
                            }
                        }

						$this->getEm()->persist($valtozat);
					}
				}
			}
		}
        $this->kaphatolett = $oldnemkaphato && !$obj->getNemkaphato();
		$obj->doStuffOnPrePersist();  // ha csak kapcsolódó adat változott, akkor prepresist/preupdate nem hívódik, de cimke gyorsítás miatt nekünk kell
		return $obj;
	}

    protected function afterSave($o) {
        if ($this->kaphatolett) {
            $tec = new termekertesitoController($this->params);
            $tec->sendErtesito($o);
        }
        parent::afterSave($o);
    }

	public function getlistbody() {
		$view = $this->createView('termeklista_tbody.tpl');

		$filter = new \mkwhelpers\FilterDescriptor();
        if (!is_null($this->params->getRequestParam('gyartofilter', null))) {
            $filter->addFilter('gyarto' , '=', $this->params->getIntRequestParam('gyartofilter'));
        }
		if (!is_null($this->params->getRequestParam('nevfilter', NULL))) {
            $filter->addFilter(array('nev', 'rovidleiras', 'cikkszam', 'vonalkod'), 'LIKE', '%' . $this->params->getStringRequestParam('nevfilter') . '%');
		}
        if (!is_null($this->params->getRequestParam('kepurlfilter', null))) {
            $filter->addFilter(array('kepurl'), 'LIKE', '%' . $this->params->getStringRequestParam('kepurlfilter') . '%');
        }
        $f = $this->params->getNumRequestParam('lathatofilter',9);
        if ($f != 9) {
            $filter->addFilter('lathato', '=', $f);
        }
        $f = $this->params->getNumRequestParam('nemkaphatofilter',9);
        if ($f != 9) {
            $filter->addFilter('nemkaphato', '=', $f);
        }
        $f = $this->params->getNumRequestParam('fuggobenfilter',9);
        if ($f != 9) {
            $filter->addFilter('fuggoben', '=', $f);
        }
        $f = $this->params->getNumRequestParam('inaktivfilter',9);
        if ($f != 9) {
            $filter->addFilter('inaktiv', '=', $f);
        }
        $f = $this->params->getNumRequestParam('ajanlottfilter',9);
        if ($f != 9) {
            $filter->addFilter('ajanlott', '=', $f);
        }
        $f = $this->params->getNumRequestParam('kiemeltfilter',9);
        if ($f != 9) {
            $filter->addFilter('kiemelt', '=', $f);
        }

		$fv = $this->params->getArrayRequestParam('cimkefilter');
		if (!empty($fv)) {
			$res = Store::getEm()->getRepository('Entities\Termekcimketorzs')->getTermekIdsWithCimke($fv);
			$cimkefilter = array();
			foreach ($res as $sor) {
				$cimkefilter[] = $sor['id'];
			}
			if ($cimkefilter) {
                $filter->addFilter('id', 'IN', $cimkefilter);
			}
			else {
                $filter->addFilter('id', '=', false);
			}
		}

		$fv = $this->params->getArrayRequestParam('fafilter');
		if (!empty($fv)) {
			$ff = new \mkwhelpers\FilterDescriptor();
            $ff->addFilter('id', 'IN', $fv);
			$res = store::getEm()->getRepository('Entities\TermekFa')->getAll($ff, array());
			$faszuro = array();
			foreach ($res as $sor) {
				$faszuro[] = $sor->getKarkod() . '%';
			}
            $filter->addFilter(array('_xx.termekfa1karkod', '_xx.termekfa2karkod', '_xx.termekfa3karkod'), 'LIKE', $faszuro);
		}

        $this->vanshowarsav = false;

        if (!\mkw\Store::isArsavok()) {
            $this->initPager($this->getRepo()->getCount($filter));
            $egyedek = $this->getRepo()->getWithJoins(
                    $filter, $this->getOrderArray(), $this->getPager()->getOffset(), $this->getPager()->getElemPerPage());
        }
        else {
            $showarsav = \mkw\Store::getParameter(\mkw\consts::ShowTermekArsav);
            $showarsavvalutanem = \mkw\Store::getParameter(\mkw\consts::ShowTermekArsavValutanem);
            if ($showarsav && $showarsavvalutanem) {
                $this->vanshowarsav = true;
                $this->initPager($this->getRepo()->getCount($filter));
                $egyedek = $this->getRepo()->getWithAr($showarsav, $showarsavvalutanem,
                        $filter, $this->getOrderArray(), $this->getPager()->getOffset(), $this->getPager()->getElemPerPage());
            }
            else {
                $this->initPager($this->getRepo()->getCount($filter));
                $egyedek = $this->getRepo()->getWithJoins(
                        $filter, $this->getOrderArray(), $this->getPager()->getOffset(), $this->getPager()->getElemPerPage());
            }
        }

        echo json_encode($this->loadDataToView($egyedek, 'termeklista', $view));
	}

	public function getselectlist($selid) {
		// TODO sok termek eseten lassu lehet
		$rec = $this->getRepo()->getAllForSelectList(array(), array('nev' => 'ASC'));
		$res = array();
		foreach ($rec as $sor) {
			$res[] = array(
				'id' => $sor['id'],
				'caption' => $sor['nev'],
				'selected' => ($sor['id'] == $selid)
			);
		}
		return $res;
	}

	public function htmllist() {
		$rec = $this->getRepo()->getAllForSelectList(array(), array('nev' => 'asc'));
		$ret = '<select>';
		foreach ($rec as $sor) {
			$ret.='<option value="' . $sor['id'] . '">' . $sor['nev'] . '</option>';
		}
		$ret.='</select>';
		echo $ret;
	}

	public function getValtozatList($termekid, $sel, $raktarid = null) {
		$ret = array();
		$termek = $this->getRepo()->findWithJoins($termekid);
		if ($termek) {
			$valtozatok = $termek->getValtozatok();
            if ($valtozatok) {
                foreach ($valtozatok as $valt) {
                    $ret[] = array(
                        'id' => $valt->getId(),
                        'caption' => $valt->getNev(),
                        'selected' => $sel == $valt->getId(),
                        'elerheto' => $valt->getElerheto(),
                        'keszlet' => $valt->getKeszlet(null, $raktarid) * 1
                    );
                }
            }
		}
		return $ret;
	}

    public function getKapcsolodoSelectList() {
        $term = trim($this->params->getStringRequestParam('term'));
        $ret = array();
        if ($term) {
            $filter = new FilterDescriptor();
            $filter->addFilter(array('_xx.nev', '_xx.cikkszam', '_xx.vonalkod'), 'LIKE', '%' . $term . '%');
            $r = store::getEm()->getRepository('\Entities\Termek');
            $res = $r->getAllForSelectList($filter);
            foreach ($res as $r) {
                $ret[] = array(
                    'id' => $r['id'],
                    'value' => $r['nev']
                );
            }
        }
        echo json_encode($ret);
    }

	public function getBizonylattetelSelectList() {
		$term = trim($this->params->getStringRequestParam('term'));
		$ret = array();
		if ($term) {
			$r = store::getEm()->getRepository('\Entities\Termek');
			$res = $r->getBizonylattetelLista($term);
            switch (\mkw\Store::getTheme()) {
                case 'mkwcansas':
                    foreach ($res as $r) {
                        $ret[] = array(
                            'value' => $r->getNev(),
                            'id' => $r->getId(),
                            'me' => $r->getMe(),
                            'cikkszam' => $r->getCikkszam(),
                            'vtsz' => $r->getVtszId(),
                            'afa' => $r->getAfaId(),
                            'afakulcs' => $r->getAfa()->getErtek(),
                            'kozepeskepurl' => $r->getKepUrlMedium(),
                            'kiskepurl' => $r->getKepUrlSmall(),
                            'kepurl' => $r->getKepUrlLarge(),
                            'slug' => $r->getSlug(),
                            'link' => \mkw\Store::getRouter()->generate('showtermek', store::getConfigValue('mainurl'), array('slug' => $r->getSlug())),
                            'mainurl' => store::getConfigValue('mainurl'),
                            'nemlathato' => (!$r->getLathato()||$r->getInaktiv()||$r->getNemkaphato()),
                            'defaultmennyiseg' => \mkw\Store::getParameter(\mkw\consts::BizonylatMennyiseg, 0)
                        );
                    }
                    break;
                case 'superzone':
                    foreach ($res as $r) {
                        $ret[] = array(
                            'value' => $r->getNev(),
                            'label' => $r->getCikkszam() . ' ' . $r->getNev(),
                            'id' => $r->getId(),
                            'me' => $r->getMe(),
                            'cikkszam' => $r->getCikkszam(),
                            'vtsz' => $r->getVtszId(),
                            'afa' => $r->getAfaId(),
                            'afakulcs' => $r->getAfa()->getErtek(),
                            'kozepeskepurl' => $r->getKepUrlMedium(),
                            'kiskepurl' => $r->getKepUrlSmall(),
                            'kepurl' => $r->getKepUrlLarge(),
                            'slug' => $r->getSlug(),
                            'link' => \mkw\Store::getRouter()->generate('showtermek', store::getConfigValue('mainurl'), array('slug' => $r->getSlug())),
                            'mainurl' => store::getConfigValue('mainurl'),
                            'nemlathato' => (!$r->getLathato()||$r->getInaktiv()||$r->getNemkaphato()),
                            'defaultmennyiseg' => \mkw\Store::getParameter(\mkw\consts::BizonylatMennyiseg, 0)
                        );
                    }
                    break;
                default:
                    foreach ($res as $r) {
                        $ret[] = array(
                            'value' => $r->getNev(),
                            'id' => $r->getId(),
                            'me' => $r->getMe(),
                            'cikkszam' => $r->getCikkszam(),
                            'vtsz' => $r->getVtszId(),
                            'afa' => $r->getAfaId(),
                            'afakulcs' => $r->getAfa()->getErtek(),
                            'kozepeskepurl' => $r->getKepUrlMedium(),
                            'kiskepurl' => $r->getKepUrlSmall(),
                            'kepurl' => $r->getKepUrlLarge(),
                            'slug' => $r->getSlug(),
                            'link' => \mkw\Store::getRouter()->generate('showtermek', store::getConfigValue('mainurl'), array('slug' => $r->getSlug())),
                            'mainurl' => store::getConfigValue('mainurl'),
                            'nemlathato' => (!$r->getLathato() || $r->getInaktiv() || $r->getNemkaphato()),
                            'defaultmennyiseg' => \mkw\Store::getParameter(\mkw\consts::BizonylatMennyiseg, 0)
                        );
                    }
                    break;
            }
		}
		echo json_encode($ret);
	}

	public function viewlist() {
		$view = $this->createView('termeklista.tpl');
		$view->setVar('pagetitle', t('Termékek'));
		$view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
		$view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
		$tcc = new termekcimkekatController($this->params);
		$view->setVar('cimkekat', $tcc->getWithCimkek(null));
        $gyarto = new partnerController($this->params);
        $view->setVar('gyartolist', $gyarto->getSzallitoSelectList(0));
		$tcs = new termekcsoportController($this->params);
		$view->setVar('termekcsoportlist', $tcs->getSelectList());
		$view->printTemplateResult();
	}

	protected function _getkarb($tplname) {
		$id = $this->params->getRequestParam('id', 0);
		$oper = $this->params->getRequestParam('oper', '');
		$view = $this->createView($tplname);
		$view->setVar('pagetitle', t('Termék'));
		$view->setVar('oper', $oper);

		$termek = $this->getRepo()->findWithJoins($id);
		// LoadVars utan nem abc sorrendben adja vissza
		$tcc = new termekcimkekatController($this->params);
		$cimkek = $termek ? $termek->getAllCimkeId() : null;
		$view->setVar('cimkekat', $tcc->getWithCimkek($cimkek));

		$view->setVar('termek', $this->loadVars($termek, true));

        $vtsz = new vtszController($this->params);
		$view->setVar('vtszlist', $vtsz->getSelectList(($termek ? $termek->getVtszId() : 0)));

        $afa = new afaController($this->params);
		$view->setVar('afalist', $afa->getSelectList(($termek ? $termek->getAfaId() : 0)));

        $valtozatadattipus = new termekvaltozatadattipusController($this->params);
		$view->setVar('valtozatadattipuslist', $valtozatadattipus->getSelectList(($termek ? $termek->getValtozatadattipusId() : 0)));

        $kep = new termekkepController($this->params);
		$view->setVar('keplist', $kep->getSelectList($termek, null));

        $gyarto = new partnerController($this->params);
        $view->setVar('gyartolist', $gyarto->getSzallitoSelectList(($termek ? $termek->getGyartoId() : 0)));

        $csoport = new termekcsoportController($this->params);
        $view->setVar('termekcsoportlist', $csoport->getSelectList(($termek ? $termek->getTermekcsoportId() : 0)));

        $view->printTemplateResult();
	}

	public function setflag() {
        $kaphatolett = false;
		$id = $this->params->getIntRequestParam('id');
		$kibe = $this->params->getBoolRequestParam('kibe');
		$flag = $this->params->getStringRequestParam('flag');
		$obj = $this->getRepo()->find($id);
		if ($obj) {
			switch ($flag) {
				case 'inaktiv':
					$obj->setInaktiv($kibe);
					break;
				case 'lathato':
					$obj->setLathato($kibe);
					break;
				case 'ajanlott':
					$obj->setAjanlott($kibe);
					break;
				case 'hozzaszolas':
					$obj->setHozzaszolas($kibe);
					break;
				case 'mozgat':
					$obj->setMozgat($kibe);
					break;
				case 'kiemelt':
					$obj->setKiemelt($kibe);
					break;
				case 'nemkaphato':
                    $oldnemkaphato = $obj->getNemkaphato();
					$obj->setNemkaphato($kibe);
                    $kaphatolett = $oldnemkaphato && !$obj->getNemkaphato();
					if ($obj->getNemkaphato()) {
                        $obj->setAjanlott(false);
                        $obj->setKiemelt(false);
						$valtozatok = $obj->getValtozatok();
						foreach ($valtozatok as $valt) {
							$valt->setElerheto(false);
							$this->getEm()->persist($valt);
						}
					}
					break;
                case 'fuggoben':
                    $obj->setFuggoben($kibe);
                    break;
			}
			$this->getEm()->persist($obj);
			$this->getEm()->flush();
            if ($kaphatolett) {
                $tec = new termekertesitoController($this->params);
                $tec->sendErtesito($obj);
            }
		}
	}

	public function getbrutto() {
		$id = $this->params->getIntRequestParam('id');
		$netto = $this->params->getFloatRequestParam('value');
		$afa = $this->getEm()->getRepository('Entities\Afa')->find($this->params->getIntRequestParam('afakod'));
		if (!$afa) {
			$termek = $this->getRepo()->find($id);
			if ($termek) {
				$afa = $termek->getAfa();
			}
		}
		if ($afa) {
			echo $afa->calcBrutto($netto);
		}
		else {
			echo $netto;
		}
	}

	public function getnetto() {
		$id = $this->params->getIntRequestParam('id');
		$brutto = $this->params->getFloatRequestParam('value');
		$afa = $this->getEm()->getRepository('Entities\Afa')->find($this->params->getIntRequestParam('afakod'));
		if (!$afa) {
			$termek = $this->getRepo()->find($id);
			if ($termek) {
				$afa = $termek->getAfa();
			}
		}
		if ($afa) {
			echo $afa->calcNetto($brutto);
		}
		else {
			echo $brutto;
		}
	}

	public function getTermekLap($termek) {
        $ujtermekminid = $this->getRepo()->getUjTermekId();
        $top10min = $this->getRepo()->getTop10Mennyiseg();

        $tfc = new termekfaController($this->params);

		$ret = array();

		if ($termek->getTermekfa1()) {
			$ret['navigator'] = $tfc->getNavigator($termek->getTermekfa1(), true);
		}
		else {
			$ret['navigator'] = array();
		}
		$ret['termek'] = $termek->toTermekLap(null, $ujtermekminid, $top10min);

		$termek->incMegtekintesdb();
		$this->getEm()->persist($termek);
		$this->getEm()->flush();
		return $ret;
	}

	public function getAjanlottLista() {
		$termekek = $this->getRepo()->getAjanlottTermekek(store::getParameter(\mkw\consts::Fooldalajanlotttermekdb, 5));
		$ret = array();
		foreach ($termekek as $termek) {
			$ret[] = $termek->toTermekLista();
		}
		return $ret;
	}

	public function getLegnepszerubbLista($db) {
		$termekek = $this->getRepo()->getLegnepszerubbTermekek($db);
		$ret = array();
		foreach ($termekek as $termek) {
			$ret[] = $termek->toKapcsolodo();
		}
		return $ret;
	}

	public function getLegujabbLista() {
		$termekek = $this->getRepo()->getLegujabbTermekek(store::getParameter(\mkw\consts::Fooldalnepszerutermekdb, 5));
		$ret = array();
		foreach ($termekek as $termek) {
			$ret[] = $termek->toTermekLista();
		}
		return $ret;
	}

    public function getHozzavasaroltLista($termek) {
		$termekek = $this->getRepo()->getHozzavasaroltTermekek($termek);
		$ret = array();
        if ($termekek) {
            foreach ($termekek as $termek) {
                $ret[] = $termek->toKapcsolodo();
            }
        }
		return $ret;
    }

    public function feed() {
		$feedview = $this->getTemplateFactory()->createMainView('feed.tpl');
		$view = $this->getTemplateFactory()->createMainView('termekfeed.tpl');
		$feedview->setVar('title', store::getParameter(\mkw\consts::Feedtermektitle, t('Termékeink')));
		$feedview->setVar('link', store::getRouter()->generate('termekfeed', true));
		$d = new \DateTime();
		$feedview->setVar('pubdate', $d->format('D, d M Y H:i:s'));
		$feedview->setVar('lastbuilddate', $d->format('D, d M Y H:i:s'));
		$feedview->setVar('description', store::getParameter(\mkw\consts::Feedtermekdescription, ''));
		$entries = array();
		$termekek = $this->getRepo()->getFeedTermek();
		foreach ($termekek as $termek) {
			$view->setVar('kepurl', $termek->getKepUrlSmall());
			$view->setVar('szoveg', $termek->getRovidLeiras());
			$view->setVar('url', store::getRouter()->generate('showtermek', true, array('slug' => $termek->getSlug())));
			$entries[] = array(
				'title' => $termek->getNev(),
				'link' => store::getRouter()->generate('showtermek', true, array('slug' => $termek->getSlug())),
				'guid' => store::getRouter()->generate('showtermek', true, array('slug' => $termek->getSlug())),
				'description' => $view->getTemplateResult(),
				'pubdate' => $d->format('D, d M Y H:i:s')
			);
		}
		$feedview->setVar('entries', $entries);
		header('Content-type: text/xml');
		$feedview->printTemplateResult(false);
	}

    public function redirectOldUrl() {
        $tid = $this->params->getStringRequestParam('pid');
        if ($tid) {
            $termek = $this->getRepo()->findOneByIdegenkod($tid);
            if ($termek) {
                $newlink = \mkw\Store::getRouter()->generate('showtermek', false, array('slug' => $termek->getSlug()));
                header("HTTP/1.1 301 Moved Permanently");
                header('Location: ' . $newlink);
                return;
            }
        }
        $mc = new mainController($this->params);
        $mc->show404('HTTP/1.1 410 Gone');
    }

    public function redirectOldRSSUrl() {
        $newlink = \mkw\Store::getRouter()->generate('termekfeed');
        header("HTTP/1.1 301 Moved Permanently");
        header('Location: ' . $newlink);
    }

    public function redirectRegikepUrl() {
        $filename = $this->params->getStringRequestParam('filename');
        if ($filename) {
            $termek = $this->getRepo()->findOneByRegikepurl($filename);
            if ($termek) {
                $newlink = \mkw\Store::getFullUrl($termek->getKepurlLarge(), \mkw\Store::getConfigValue('mainurl'));
                header("HTTP/1.1 301 Moved Permanently");
                header('Location: ' . $newlink);
                return;
            }
        }
        $mc = new mainController($this->params);
        $mc->show404('HTTP/1.1 410 Gone');
    }

    public function getKeszletByRaktar() {
        $termekid = $this->params->getIntRequestParam('termekid');
        $termek = $this->getRepo()->find($termekid);
        $raktarak = $this->getRepo('Entities\Raktar')->getAll();
        if ($termek) {
            $klist = array();
            foreach($raktarak as $raktar) {
                $klist[] = array(
                    'raktarnev' => $raktar->getNev(),
                    'keszlet' => $termek->getKeszlet(null, $raktar->getId())
                );
            }
    		$view = $this->createView('termekkeszletreszletezo.tpl');
            $view->setVar('lista', $klist);
            $view->printTemplateResult();
        }
    }

    public function arexport() {

        function x($o) {
            if ($o <= 26) {
                return chr(65 + $o);
            }
            return chr(65 + floor($o / 26)) . chr(65 + ($o % 26));
        }

        $ids = $this->params->getStringRequestParam('ids');
        $ids = explode(',', $ids);

        $arsavok = $this->getRepo('Entities\TermekAr')->getExistingArsavok();
        $defavaluta = \mkw\Store::getParameter(\mkw\consts::Valutanem);

        $excel = new \PHPExcel();
        $excel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'kod');
        $oszlop = 3;
        foreach($arsavok as $arsav) {
            if ($arsav['valutanemid'] == $defavaluta) {
                $nettobrutto = 'brutto';
            }
            else {
                $nettobrutto = 'netto';
            }
            $excel->setActiveSheetIndex(0)
                ->setCellValue(x($oszlop) . '1', $nettobrutto . '_' . $arsav['valutanem'] . '_' . $arsav['azonosito']);
            $oszlop++;
        }

        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('id', 'IN', $ids);
        $termekek = $this->getRepo()->getWithArak($filter, array());
        $sor = 2;
        foreach($termekek as $termek) {
            $excel->setActiveSheetIndex(0)
                ->setCellValue(x(0) . $sor, $termek->getId())
                ->setCellValue(x(1) . $sor, $termek->getCikkszam())
                ->setCellValue(x(2) . $sor, $termek->getNev());
            $arak = $termek->getTermekArak();
            foreach($arak as $ar) {
                $i = array_search(array('valutanemid' => $ar->getValutanemId(), 'valutanem' => $ar->getValutanemnev(), 'azonosito' => $ar->getAzonosito()),
                    $arsavok);
                if ($i !== false) {
                    if ($arsavok[$i]['valutanemid'] == $defavaluta) {
                        $nettobrutto = $ar->getBrutto();
                    }
                    else {
                        $nettobrutto = $ar->getNetto();
                    }
                    $excel->setActiveSheetIndex(0)
                        ->setCellValue(x(3 + $i) . $sor, $nettobrutto);
                }
            }
            $sor++;
        }

        $writer = \PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

        $filepath = uniqid('termekarak') . '.xlsx';
        $writer->save($filepath);

        $fileSize = filesize($filepath);

        // Output headers.
        header("Cache-Control: private");
        header("Content-Type: application/stream");
        header("Content-Length: " . $fileSize);
        header("Content-Disposition: attachment; filename=" . $filepath);

        readfile($filepath);

        \unlink($filepath);
    }

	public function setTermekcsoport() {
		$ids = $this->params->getArrayRequestParam('ids');
		//$ids = explode(',', $ids);
        if ($ids) {
            $tcsid = $this->params->getIntRequestParam('tcs');
            $tcs = $this->getRepo('Entities\Termekcsoport')->find($tcsid);

            $filter = new \mkwhelpers\FilterDescriptor();
            $filter->addFilter('id', 'IN', $ids);
            $termekek = $this->getRepo()->getAll($filter, array());
            $termekdb = 0;
            $batchsize = 20;
            foreach ($termekek as $termek) {
                $termekdb++;
                if ($tcs) {
                    $termek->setTermekcsoport($tcs);
                }
                else {
                    $termek->setTermekcsoport(null);
                }
                $this->getEm()->persist($termek);
                if (($termekdb % $batchsize) === 0) {
                    $this->getEm()->flush();
                }
            }
            $this->getEm()->flush();
            $this->getEm()->clear();
        }
    }
}