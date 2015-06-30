<?php

namespace Controllers;

use Entities\Bizonylattetel;
use mkw\store;

class bizonylatfejController extends \mkwhelpers\MattableController {

	public function __construct($params) {
		$this->setEntityName('Entities\Bizonylatfej');
//		$this->setKarbFormTplName('megrendelesfejkarbform.tpl');
//		$this->setKarbTplName('megrendelesfejkarb.tpl');
//		$this->setListBodyRowTplName('megrendelesfejlista_tbody_tr.tpl');
		$this->setListBodyRowVarName('_egyed');
		parent::__construct($params);
	}

    protected function loadFilters($filter) {
        if (!is_null($this->params->getRequestParam('idfilter', NULL))) {
            $filter['fields'][] = 'id';
            $filter['clauses'][] = 'LIKE';
            $filter['values'][] = '%' . $this->params->getStringRequestParam('idfilter');
        }

        $f = $this->params->getStringRequestParam('vevonevfilter');
        if ($f) {
            $filter['fields'][] = 'partnernev';
            $filter['clauses'][] = 'LIKE';
            $filter['values'][] = '%' . $f . '%';
        }

        $f = $this->params->getStringRequestParam('vevoemailfilter');
        if ($f) {
            $filter['fields'][] = 'partneremail';
            $filter['clauses'][] = 'LIKE';
            $filter['values'][] = '%' . $f . '%';
        }
        $f = $this->params->getStringRequestParam('szallitasiirszamfilter');
        if ($f) {
            $filter['fields'][] = 'szallirszam';
            $filter['clauses'][] = 'LIKE';
            $filter['values'][] = '%' . $f . '%';
        }
        $f = $this->params->getStringRequestParam('szallitasivarosfilter');
        if ($f) {
            $filter['fields'][] = 'szallvaros';
            $filter['clauses'][] = 'LIKE';
            $filter['values'][] = '%' . $f . '%';
        }
        $f = $this->params->getStringRequestParam('szallitasiutcafilter');
        if ($f) {
            $filter['fields'][] = 'szallutca';
            $filter['clauses'][] = 'LIKE';
            $filter['values'][] = '%' . $f . '%';
        }
        $f = $this->params->getStringRequestParam('szamlazasiirszamfilter');
        if ($f) {
            $filter['fields'][] = 'partnerirszam';
            $filter['clauses'][] = 'LIKE';
            $filter['values'][] = '%' . $f . '%';
        }
        $f = $this->params->getStringRequestParam('szamlazasivarosfilter');
        if ($f) {
            $filter['fields'][] = 'partnervaros';
            $filter['clauses'][] = 'LIKE';
            $filter['values'][] = '%' . $f . '%';
        }
        $f = $this->params->getStringRequestParam('szamlazasiutcafilter');
        if ($f) {
            $filter['fields'][] = 'partnerutca';
            $filter['clauses'][] = 'LIKE';
            $filter['values'][] = '%' . $f . '%';
        }
        $tip = $this->params->getStringRequestParam('datumtipusfilter');
        $tol = $this->params->getDateRequestParam('datumtolfilter');
        $ig = $this->params->getDateRequestParam('datumigfilter');
        if ($tip && ($tol||$ig)) {
            switch ($tip) {
                case 1:
                    $mezo = 'kelt';
                    break;
                case 2:
                    $mezo = 'teljesites';
                    break;
                case 3:
                    $mezo = 'esedekesseg';
                    break;
                default:
                    $mezo = 'kelt';
                    break;
            }
            if ($tol) {
                $filter['fields'][] = $mezo;
                $filter['clauses'][] = '>=';
                $filter['values'][] = $tol;
            }
            if ($ig) {
                $filter['fields'][] = $mezo;
                $filter['clauses'][] = '<=';
                $filter['values'][] = $ig;
            }
        }
        $f = $this->params->getIntRequestParam('bizonylatstatuszfilter');
        if ($f) {
            $bs = $this->getRepo('Entities\Bizonylatstatusz')->findOneById($f);
            if ($bs) {
                $filter['fields'][] = 'bizonylatstatusz';
                $filter['clauses'][] = '=';
                $filter['values'][] = $bs;
            }
        }
        $f = $this->params->getIntRequestParam('fizmodfilter');
        if ($f) {
            $bs = $this->getRepo('Entities\Fizmod')->findOneById($f);
            if ($bs) {
                $filter['fields'][] = 'fizmod';
                $filter['clauses'][] = '=';
                $filter['values'][] = $bs;
            }
        }
        $f = $this->params->getStringRequestParam('fuvarlevelszamfilter');
        if ($f) {
            $filter['fields'][] = 'fuvarlevelszam';
            $filter['clauses'][] = 'LIKE';
            $filter['values'][] = '%' . $f . '%';
        }
        $f = $this->params->getStringRequestParam('erbizonylatszamfilter');
        if ($f) {
            $filter['fields'][] = 'erbizonylatszam';
            $filter['clauses'][] = 'LIKE';
            $filter['values'][] = '%' . $f . '%';
        }
        return $filter;
    }

	protected function loadVars($t, $forKarb = false) {
		$tetelCtrl = new bizonylattetelController($this->params);
		$tetel = array();
		$x = array();
		if (!$t) {
			$t = new \Entities\Bizonylatfej();
			$this->getEm()->detach($t);
		}
		$x['id'] = $t->getId();
        $x['bizonylattipusid'] = $t->getBizonylattipusId();
        $x['tulajnev'] = $t->getTulajnev();
        $x['tulajirszam'] = $t->getTulajirszam();
        $x['tulajvaros'] = $t->getTulajvaros();
        $x['tulajutca'] = $t->getTulajutca();
        $x['tulajadoszam'] = $t->getTulajadoszam();
		$x['bizonylatnev'] = $t->getBizonylatnev();
		$x['erbizonylatszam'] = $t->getErbizonylatszam();
        $x['fuvarlevelszam'] = $t->getFuvarlevelszam();
		$x['keltstr'] = $t->getKeltStr();
		$x['teljesitesstr'] = $t->getTeljesitesStr();
		$x['esedekessegstr'] = $t->getEsedekessegStr();
		$x['hataridostr'] = $t->getHataridoStr();
		$x['partner'] = $t->getPartnerId();
		$x['partnernev'] = $t->getPartnernev();
        $x['partnervezeteknev'] = $t->getPartnervezeteknev();
        $x['partnerkeresztnev'] = $t->getPartnerkeresztnev();
		$x['partnerirszam'] = $t->getPartnerirszam();
		$x['partnervaros'] = $t->getPartnervaros();
		$x['partnerutca'] = $t->getPartnerutca();
		$x['partnertelefon'] = $t->getPartnertelefon();
		$x['partneremail'] = $t->getPartneremail();
		$x['partneradoszam'] = $t->getPartneradoszam();
		$x['raktar'] = $t->getRaktarId();
		$x['raktarnev'] = $t->getRaktarnev();
		$x['fizmod'] = $t->getFizmodId();
		$x['fizmodnev'] = $t->getFizmodnev();
		$x['szallitasimod'] = $t->getSzallitasimodId();
		$x['szallitasimodnev'] = $t->getSzallitasimodnev();
		$x['valutanem'] = $t->getValutanemId();
		$x['valutanemnev'] = $t->getValutanemNev();
		$x['arfolyam'] = $t->getArfolyam();
		$x['bankszamla'] = $t->getBankszamlaId();
		$x['bankszamlanev'] = $t->getBankszamlaNev();
		$x['netto'] = $t->getNetto();
		$x['afa'] = $t->getAfa();
		$x['brutto'] = $t->getBrutto();
		$x['nettohuf'] = $t->getNettohuf();
		$x['afahuf'] = $t->getAfahuf();
		$x['bruttohuf'] = $t->getBruttohuf();
		$x['megjegyzes'] = $t->getMegjegyzes();
		$x['belsomegjegyzes'] = $t->getBelsomegjegyzes();
		$x['szallnev'] = $t->getSzallnev();
		$x['szallirszam'] = $t->getSzallirszam();
		$x['szallvaros'] = $t->getSzallvaros();
		$x['szallutca'] = $t->getSzallutca();
		$x['webshopmessage'] = $t->getWebshopmessage();
		$x['couriermessage'] = $t->getCouriermessage();
		$x['ip'] = $t->getIp();
		$x['referrer'] = $t->getReferrer();
        $x['fizetve'] = $t->getFizetve();
        $x['otpayid'] = $t->getOTPayId();
        $x['otpaymsisdn'] = $t->getOTPayMSISDN();
        $x['otpaympid'] = $t->getOTPayMPID();
        $x['otpayresult'] = $t->getOTPayResult();
		if ($forKarb) {
			foreach ($t->getBizonylattetelek() as $ttetel) {
				$tetel[] = $tetelCtrl->loadVars($ttetel, true);
			}
//				$tetel[]=$tetelCtrl->loadVars(null,true);
			$x['tetelek'] = $tetel;
		}
		return $x;
	}

	protected function setFields($obj, $parancs) {
		$obj->setPersistentData(); // a biz. állandó adatait tölti fel (biz.tip-ból, tulaj adatok)

		$obj->setErbizonylatszam($this->params->getStringRequestParam('erbizonylatszam'));
		$ck = store::getEm()->getRepository('Entities\Partner')->find($this->params->getIntRequestParam('partner'));
		if ($ck) {
			$obj->setPartner($ck);
		}
		$ck = store::getEm()->getRepository('Entities\Raktar')->find($this->params->getIntRequestParam('raktar'));
		if ($ck) {
			$obj->setRaktar($ck);
		}
		$ck = store::getEm()->getRepository('Entities\Fizmod')->find($this->params->getIntRequestParam('fizmod'));
		if ($ck) {
			$obj->setFizmod($ck);
		}
		$ck = store::getEm()->getRepository('Entities\Szallitasimod')->find($this->params->getIntRequestParam('szallitasimod'));
		if ($ck) {
			$obj->setSzallitasimod($ck);
		}
        $obj->setKelt($this->params->getStringRequestParam('kelt'));
        $obj->setTeljesites($this->params->getStringRequestParam('teljesites'));
        $obj->setEsedekesseg($this->params->getStringRequestParam('esedekesseg'));
		$obj->setHatarido($this->params->getStringRequestParam('hatarido'));

        $obj->setErbizonylatszam($this->params->getStringRequestParam('erbizonylatszam'));
        $obj->setFuvarlevelszam($this->params->getStringRequestParam('fuvarlevelszam'));

		$obj->setPartnernev($this->params->getStringRequestParam('partnernev'));
		$obj->setPartneradoszam($this->params->getStringRequestParam('partneradoszam'));
		$obj->setPartnerirszam($this->params->getStringRequestParam('partnerirszam'));
		$obj->setPartnervaros($this->params->getStringRequestParam('partnervaros'));
		$obj->setPartnerutca($this->params->getStringRequestParam('partnerutca'));
		$obj->setPartnertelefon($this->params->getStringRequestParam('partnertelefon'));
		$obj->setPartneremail($this->params->getStringRequestParam('partneremail'));

		$obj->setSzallnev($this->params->getStringRequestParam('szallnev'));
		$obj->setSzallirszam($this->params->getStringRequestParam('szallirszam'));
		$obj->setSzallvaros($this->params->getStringRequestParam('szallvaros'));
		$obj->setSzallutca($this->params->getStringRequestParam('szallutca'));

		$ck = store::getEm()->getRepository('Entities\Valutanem')->find($this->params->getIntRequestParam('valutanem'));
		if ($ck) {
			$obj->setValutanem($ck);
		}

		$obj->setArfolyam($this->params->getNumRequestParam('arfolyam'));

		$ck = store::getEm()->getRepository('Entities\Bankszamla')->find($this->params->getIntRequestParam('bankszamla'));
		if ($ck) {
			$obj->setBankszamla($ck);
		}

		$ck = store::getEm()->getRepository('Entities\Bizonylatstatusz')->find($this->params->getIntRequestParam('bizonylatstatusz'));
		if ($ck) {
			$obj->setBizonylatstatusz($ck);
		}

		$obj->setMegjegyzes($this->params->getStringRequestParam('megjegyzes'));
		$obj->setBelsomegjegyzes($this->params->getStringRequestParam('belsomegjegyzes'));
		$obj->setWebshopmessage($this->params->getStringRequestParam('webshopmessage'));
		$obj->setCouriermessage($this->params->getStringRequestParam('couriermessage'));

		$obj->generateId(); // az üres kelt miatt került a végére

        if ($parancs == $this->inheritOperation) {
            $parentbiz = $this->getRepo()->find($this->params->getStringRequestParam('parentid'));
            if ($parentbiz) {
                $obj->setParbizonylatfej($parentbiz);
            }
        }

		$tetelids = $this->params->getArrayRequestParam('tetelid');
		foreach ($tetelids as $tetelid) {
			if (($this->params->getIntRequestParam('teteltermek_' . $tetelid) > 0)) {
				$oper = $this->params->getStringRequestParam('teteloper_' . $tetelid);
				$termek = $this->getEm()->getRepository('Entities\Termek')->find($this->params->getIntRequestParam('teteltermek_' . $tetelid));
				$termekvaltozat = $this->getEm()->getRepository('Entities\TermekValtozat')->find($this->params->getIntRequestParam('tetelvaltozat_' . $tetelid));
				if (($oper == $this->addOperation) || ($oper == $this->inheritOperation)) {
					$tetel = new Bizonylattetel();
					$obj->addBizonylattetel($tetel);
					$tetel->setPersistentData();
					$tetel->setArvaltoztat(0);
					if ($termek) {
						$tetel->setTermek($termek);
					}
					if ($termekvaltozat) {
						$tetel->setTermekvaltozat($termekvaltozat);
					}
					$tetel->setMozgat();
                    $parenttetel = $this->getRepo('Entities\Bizonylattetel')->find($this->params->getStringRequestParam('tetelparentid_' . $tetelid));
                    if ($parenttetel) {
                        $tetel->setParbizonylattetel($parenttetel);
                    }
					$tetel->setMennyiseg($this->params->getFloatRequestParam('tetelmennyiseg_' . $tetelid));
					$tetel->setNettoegysar($this->params->getFloatRequestParam('tetelnettoegysar_' . $tetelid));
					$tetel->setBruttoegysar($this->params->getFloatRequestParam('tetelbruttoegysar_' . $tetelid));
					$tetel->setNetto($this->params->getFloatRequestParam('tetelnetto_' . $tetelid));
					$tetel->setBrutto($this->params->getFloatRequestParam('tetelbrutto_' . $tetelid));
					$tetel->setAfaertek($tetel->getBrutto() - $tetel->getNetto());
					$tetel->setNettoegysarhuf($this->params->getFloatRequestParam('tetelnettoegysarhuf_' . $tetelid));
					$tetel->setBruttoegysarhuf($this->params->getFloatRequestParam('tetelbruttoegysarhuf_' . $tetelid));
					$tetel->setNettohuf($this->params->getFloatRequestParam('tetelnettohuf_' . $tetelid));
					$tetel->setBruttohuf($this->params->getFloatRequestParam('tetelbruttohuf_' . $tetelid));
					$tetel->setAfaertekhuf($tetel->getBruttohuf() - $tetel->getNettohuf());
					$tetel->setHatarido($this->params->getStringRequestParam('tetelhatarido_' . $tetelid));
//						$tetel->setArfolyam($this->params->getFloatRequestParam('arfolyam'));
					$this->getEm()->persist($tetel);
				}
				elseif ($oper == $this->editOperation) {
					$tetel = $this->getEm()->getRepository('Entities\Bizonylattetel')->find($tetelid);
					if ($tetel) {
						$tetel->setPersistentData();
						$tetel->setMozgat();
						if ($termek) {
							$tetel->setTermek($termek);
						}
						if ($termekvaltozat) {
							$tetel->setTermekvaltozat($termekvaltozat);
						}
						$tetel->setMozgat();
						$tetel->setMennyiseg($this->params->getFloatRequestParam('tetelmennyiseg_' . $tetelid));
						$tetel->setNettoegysar($this->params->getFloatRequestParam('tetelnettoegysar_' . $tetelid));
						$tetel->setBruttoegysar($this->params->getFloatRequestParam('tetelbruttoegysar_' . $tetelid));
						$tetel->setNetto($this->params->getFloatRequestParam('tetelnetto_' . $tetelid));
						$tetel->setBrutto($this->params->getFloatRequestParam('tetelbrutto_' . $tetelid));
						$tetel->setAfaertek($tetel->getBrutto() - $tetel->getNetto());
						$tetel->setNettoegysarhuf($this->params->getFloatRequestParam('tetelnettoegysarhuf_' . $tetelid));
						$tetel->setBruttoegysarhuf($this->params->getFloatRequestParam('tetelbruttoegysarhuf_' . $tetelid));
						$tetel->setNettohuf($this->params->getFloatRequestParam('tetelnettohuf_' . $tetelid));
						$tetel->setBruttohuf($this->params->getFloatRequestParam('tetelbruttohuf_' . $tetelid));
						$tetel->setAfaertekhuf($tetel->getBruttohuf() - $tetel->getNettohuf());
						$tetel->setHatarido($this->params->getStringRequestParam('tetelhatarido_' . $tetelid));
//							$tetel->setArfolyam($this->params->getFloatRequestParam('arfolyam'));
						$this->getEm()->persist($tetel);
					}
				}
			}
		}
		return $obj;
	}

    protected function afterSave($o) {
        parent::afterSave($o);
        if ($this->params->getBoolRequestParam('szallitasiktgkell')) {
            $this->getRepo()->createSzallitasiKtg($o, $o->getSzallitasimodId());
            $o->doStuffOnPrePersist();
            $this->getEm()->persist($o);
            $this->getEm()->flush();
        }
    }

    public function checkKelt() {
        $ret = array('response' => 'error');
        $keltstr = \mkw\Store::convDate($this->params->getDateRequestParam('kelt'));
        $kelt = strtotime($keltstr);
        $biztipid = $this->params->getStringRequestParam('biztipus');
        $bt = $this->getRepo('Entities\Bizonylattipus')->find($biztipid);
        if ($bt) {
            $filter = array();
            $filter['fields'][] = 'bizonylattipus';
            $filter['clauses'][] = '=';
            $filter['values'][] = $bt;
            $filter['fields'][] = 'kelt';
            $filter['clauses'][] = '>';
            $filter['values'][] = $keltstr;
            $filter['sql'][] = '(YEAR(_xx.kelt)=' . date('Y', $kelt) . ')';
            $db = $this->getRepo()->getCount($filter);
            if ($db == 0) {
                $ret = array('response' => 'ok');
            }
        }
        echo json_encode($ret);
    }

    public function calcEsedekesseg() {
        $kelt = $this->params->getDateRequestParam('kelt');
        $fm = $this->getRepo('Entities\Fizmod')->find($this->params->getIntRequestParam('fizmod'));
        $partner = $this->getRepo('Entities\Partner')->find($this->params->getIntRequestParam('partner'));
        echo json_encode(array('esedekesseg' => \mkw\Store::calcEsedekesseg($kelt, $fm, $partner)));
    }

}