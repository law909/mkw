<?php

namespace Controllers;

use Entities\Bizonylattetel;
use mkw\store;

class bizonylatfejController extends \mkwhelpers\MattableController {

    protected $biztipus;

	public function __construct($params) {
		$this->setEntityName('Entities\Bizonylatfej');
        $this->setKarbFormTplName('bizonylatfejkarbform.tpl');
        $this->setKarbTplName('bizonylatfejkarb.tpl');
        $this->setListBodyRowTplName('bizonylatfejlista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
		parent::__construct($params);
	}

    public function viewselect() {
        $view = $this->createView('bizonylatfejlista.tpl');

        $view->setVar('pagetitle', $this->getPluralPageTitle());
        $view->setVar('controllerscript', $this->biztipus . 'fej.js');
        $this->setVars($view);
        $view->printTemplateResult();
    }

    public function viewlist() {
        $view = $this->createView('bizonylatfejlista.tpl');

        $view->setVar('pagetitle', $this->getPluralPageTitle());
        $view->setVar('controllerscript', $this->biztipus . 'fej.js');
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $this->setVars($view);
        $view->printTemplateResult();
    }

    public function setVars($view) {
        $bt = $this->getRepo('Entities\Bizonylattipus')->find($this->biztipus);
        $bt->setTemplateVars($view);

        $fmc = new fizmodController($this->params);
        $view->setVar('fizmodlist', $fmc->getSelectList());

        $fmc = new szallitasimodController($this->params);
        $view->setVar('szallitasimodlist', $fmc->getSelectList(false));

        $fmc = new uzletkotoController($this->params);
        $view->setVar('uzletkotolist', $fmc->getSelectList(false));

        switch (\mkw\Store::getTheme()) {
            case 'mkwcansas':
                $a = date(\mkw\Store::$DateFormat, strtotime('-1 week'));
                break;
            case 'superzone':
                $a = date(\mkw\Store::$DateFormat);
                break;
        }
        $view->setVar('datumtolfilter', $a);
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
        $f = $this->params->getIntRequestParam('szallitasimodfilter');
        if ($f) {
            $bs = $this->getRepo('Entities\Szallitasimod')->findOneById($f);
            if ($bs) {
                $filter['fields'][] = 'szallitasimod';
                $filter['clauses'][] = '=';
                $filter['values'][] = $bs;
            }
        }
        $f = $this->params->getIntRequestParam('uzletkotofilter');
        if ($f) {
            $bs = $this->getRepo('Entities\Uzletkoto')->findOneById($f);
            if ($bs) {
                $filter['fields'][] = 'uzletkoto';
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

        $f = $this->params->getIntRequestParam('bizonylatrontottfilter');
        switch ($f) {
            case 1:
                $filter['fields'][] = 'rontott';
                $filter['clauses'][] = '=';
                $filter['values'][] = false;
                break;
            case 2:
                $filter['fields'][] = 'rontott';
                $filter['clauses'][] = '=';
                $filter['values'][] = true;
                break;
        }

        $f = $this->params->getIntRequestParam('bizonylatstornofilter');
        switch ($f) {
            case 1:
                $filter['fields'][] = array('storno', 'stornozott');
                $filter['clauses'][] = '=';
                $filter['values'][] = false;
                break;
            case 2:
                $filter['fields'][] = array('storno', 'stornozott');
                $filter['clauses'][] = '=';
                $filter['values'][] = true;
                break;
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
        $x['storno'] = $t->getStorno();
        $x['stornozott'] = $t->getStornozott();
        $x['rontott'] = $t->getRontott();
        $x['nemrossz'] = !$t->getRontott() && !$t->getStorno() && !$t->getStornozott();
        $x['tulajnev'] = $t->getTulajnev();
        $x['tulajirszam'] = $t->getTulajirszam();
        $x['tulajvaros'] = $t->getTulajvaros();
        $x['tulajutca'] = $t->getTulajutca();
        $x['tulajadoszam'] = $t->getTulajadoszam();
        $x['tulajeuadoszam'] = $t->getTulajeuadoszam();
		$x['bizonylatnev'] = $t->getBizonylatnev();
		$x['erbizonylatszam'] = $t->getErbizonylatszam();
        $x['fuvarlevelszam'] = $t->getFuvarlevelszam();
		$x['keltstr'] = $t->getKeltStr();
		$x['teljesitesstr'] = $t->getTeljesitesStr();
		$x['esedekessegstr'] = $t->getEsedekessegStr();
        $x['esedekesseg1str'] = $t->getEsedekesseg1Str();
        $x['fizetendo1'] = $t->getFizetendo1();
        $x['esedekesseg2str'] = $t->getEsedekesseg2Str();
        $x['fizetendo2'] = $t->getFizetendo2();
        $x['esedekesseg3str'] = $t->getEsedekesseg3Str();
        $x['fizetendo3'] = $t->getFizetendo3();
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
        $x['tulajbanknev'] = $t->getTulajbanknev();
		$x['tulajbankszamlaszam'] = $t->getTulajbankszamlaszam();
        $x['tulajswift'] = $t->getTulajswift();
        $x['tulajiban'] = $t->getTulajiban();
        $x['tulajeorinr'] = $t->getTulajeorinr();
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
        $x['otpayresulttext'] = $t->getOTPayResultText();
        $x['showotpay'] = ($t->getFizmodId() == Store::getParameter(\mkw\consts::OTPayFizmod));
        $x['trxid'] = $t->getTrxId();
        $x['fix'] = $t->getFix();
        $x['uzletkoto'] = $t->getUzletkotoId();
        $x['uzletkotonev'] = $t->getUzletkotonev();
        $x['uzletkotoemail'] = $t->getUzletkotoemail();
        $x['bizonylatnyelv'] = $t->getBizonylatnyelv();
        $x['reportfile'] = $t->getReportfile();
		if ($forKarb) {
            if ($t->getPartner() && ($t->getPartner()->getSzamlatipus() > 0)) {
                $afa = $this->getRepo('Entities\Afa')->find(\mkw\Store::getParameter(\mkw\consts::NullasAfa));
                $x['partnerafa'] = $afa->getId();
                $x['partnerafakulcs'] = $afa->getErtek();
            }
			foreach ($t->getBizonylattetelek() as $ttetel) {
				$tetel[] = $tetelCtrl->loadVars($ttetel, true);
			}
//				$tetel[]=$tetelCtrl->loadVars(null,true);
			$x['tetelek'] = $tetel;
		}
		return $x;
	}

	protected function setFields($obj, $parancs) {
        $partnerkod = $this->params->getIntRequestParam('partner');

        if ($partnerkod == -1) {
            $partnerobj = new \Entities\Partner();
            $partnerobj->setAdoszam($this->params->getStringRequestParam('partneradoszam'));
            $partnerobj->setEuadoszam($this->params->getStringRequestParam('partnereuadoszam'));
            $partnerobj->setEmail($this->params->getStringRequestParam('partneremail'));
            $partnerobj->setTelefon($this->params->getStringRequestParam('partnertelefon'));
            $partnerobj->setNev($this->params->getStringRequestParam('partnernev'));
            $partnerobj->setVezeteknev($this->params->getStringRequestParam('partnervezeteknev'));
            $partnerobj->setKeresztnev($this->params->getStringRequestParam('partnerkeresztnev'));
            $partnerobj->setIrszam($this->params->getStringRequestParam('partnerirszam'));
            $partnerobj->setVaros($this->params->getStringRequestParam('partnervaros'));
            $partnerobj->setUtca($this->params->getStringRequestParam('partnerutca'));
            $ck = store::getEm()->getRepository('Entities\Fizmod')->find($this->params->getIntRequestParam('fizmod'));
            if ($ck) {
                $partnerobj->setFizmod($ck);
            }
            $partnerobj->setSzallnev($this->params->getStringRequestParam('szallnev'));
            $partnerobj->setSzallirszam($this->params->getStringRequestParam('szallirszam'));
            $partnerobj->setSzallvaros($this->params->getStringRequestParam('szallvaros'));
            $partnerobj->setSzallutca($this->params->getStringRequestParam('szallutca'));
            $ck = store::getEm()->getRepository('Entities\Szallitasimod')->find($this->params->getIntRequestParam('szallitasimod'));
            if ($ck) {
                $partnerobj->setSzallitasimod($ck);
            }
            $ck = store::getEm()->getRepository('Entities\Valutanem')->find($this->params->getIntRequestParam('valutanem'));
            if ($ck) {
                $partnerobj->setValutanem($ck);
            }
            $partnerobj->setBizonylatnyelv($this->params->getStringRequestParam('bizonylatnyelv'));

            $this->getEm()->persist($partnerobj);
        }

        $obj->setBizonylattipus($this->getRepo('Entities\Bizonylattipus')->find($this->biztipus));

        $obj->setPersistentData(); // a biz. állandó adatait tölti fel (biz.tip-ból, tulaj adatok)

        $obj->setFix($this->params->getBoolRequestParam('fix'));

        if ($partnerkod > 0) {
            $ck = store::getEm()->getRepository('Entities\Partner')->find($partnerkod);
            if ($ck) {
                $obj->setPartner($ck);
            }
        }
        else {
            $obj->setPartner($partnerobj);
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
		$ck = store::getEm()->getRepository('Entities\Uzletkoto')->find($this->params->getIntRequestParam('uzletkoto'));
		if ($ck) {
			$obj->setUzletkoto($ck);
		}
        else {
            $obj->removeUzletkoto();
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

        $obj->setBizonylatnyelv($this->params->getStringRequestParam('bizonylatnyelv'));
        $obj->setReportfile($this->params->getStringRequestParam('reportfile'));

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

        switch ($parancs) {
            case $this->inheritOperation:
                $parentbiz = $this->getRepo()->find($this->params->getStringRequestParam('parentid'));
                if ($parentbiz) {
                    $obj->setParbizonylatfej($parentbiz);
                }
                break;
            case $this->stornoOperation:
                $obj->setSysmegjegyzes($this->params->getStringRequestParam('parentid') . ' stornó bizonylata.');
                $obj->setBizonylatnev('Storno számla');
                $obj->setStorno(true);
                $parentbiz = $this->getRepo()->find($this->params->getStringRequestParam('parentid'));
                if ($parentbiz) {
                    $obj->setParbizonylatfej($parentbiz);
                }
                break;
        }

        $quick = $this->params->getBoolRequestParam('quick');
		$tetelids = $this->params->getArrayRequestParam('tetelid');
        $biztetelcontroller = new bizonylattetelController($this->params);
		foreach ($tetelids as $tetelid) {
			if (($this->params->getIntRequestParam('teteltermek_' . $tetelid) > 0)) {
				$oper = $this->params->getStringRequestParam('teteloper_' . $tetelid);
				$termek = $this->getEm()->getRepository('Entities\Termek')->find($this->params->getIntRequestParam('teteltermek_' . $tetelid));
                if ($termek) {
                    $termekvaltozat = $this->getEm()->getRepository('Entities\TermekValtozat')->find($this->params->getIntRequestParam('tetelvaltozat_' . $tetelid));
                    switch ($oper) {
                        case $this->addOperation:
                        case $this->inheritOperation:
                        case $this->stornoOperation:
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
                            $tetel->setFoglal();

                            if (!$quick) {
                                $tetel->setVtsz($this->params->getIntRequestParam('tetelvtsz_' . $tetelid));
                                $tetel->setAfa($this->params->getIntRequestParam('tetelafa_' . $tetelid));
                                $tetel->setME($this->params->getStringRequestParam('tetelme_' . $tetelid));
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
                                $tetel->setArfolyam($this->params->getFloatRequestParam('arfolyam'));
                                if ($oper == $this->stornoOperation) {
                                    $tetel->setStorno(true);
                                }
                            }
                            else {
                                $tetel->setAfa($this->params->getIntRequestParam('tetelafa_' . $tetelid));
                                $tetel->setArfolyam($this->params->getFloatRequestParam('arfolyam'));
                                $tetel->setMennyiseg($this->params->getFloatRequestParam('tetelmennyiseg_' . $tetelid));
                                $tetel->setNettoegysar($this->params->getFloatRequestParam('tetelnettoegysar_' . $tetelid));
                                $tetel->setBruttoegysar($this->params->getFloatRequestParam('tetelbruttoegysar_' . $tetelid));
                                $arak = $biztetelcontroller->calcAr(
                                    $tetel->getAfaId(),
                                    $tetel->getArfolyam(),
                                    $tetel->getNettoegysar(),
                                    $tetel->getMennyiseg()
                                );
                                $tetel->setNetto($arak['netto']);
                                $tetel->setBrutto($arak['brutto']);
                                $tetel->setAfaertek($arak['afa']);
                                $tetel->setNettoegysarhuf($arak['nettoegysarhuf']);
                                $tetel->setBruttoegysarhuf($arak['bruttoegysarhuf']);
                                $tetel->setNettohuf($arak['nettohuf']);
                                $tetel->setBruttohuf($arak['bruttohuf']);
                                $tetel->setAfaertekhuf($arak['afahuf']);
                                $tetel->setHatarido($this->params->getStringRequestParam('tetelhatarido_' . $tetelid));
                            }

                            $this->getEm()->persist($tetel);
                            break;
                        case $this->editOperation:
                            $tetel = $this->getEm()->getRepository('Entities\Bizonylattetel')->find($tetelid);
                            if ($tetel) {
                                $tetel->setPersistentData();
                                if ($termek) {
                                    $tetel->setTermek($termek);
                                }
                                if ($termekvaltozat) {
                                    $tetel->setTermekvaltozat($termekvaltozat);
                                }
                                $tetel->setMozgat();
                                $tetel->setFoglal();

                                if (!$quick) {
                                    $tetel->setVtsz($this->params->getIntRequestParam('tetelvtsz_' . $tetelid));
                                    $tetel->setAfa($this->params->getIntRequestParam('tetelafa_' . $tetelid));
                                    $tetel->setME($this->params->getStringRequestParam('tetelme_' . $tetelid));
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
                                    $tetel->setArfolyam($this->params->getFloatRequestParam('arfolyam'));
                                }
                                else {
                                    $tetel->setAfa($this->params->getIntRequestParam('tetelafa_' . $tetelid));
                                    $tetel->setArfolyam($this->params->getFloatRequestParam('arfolyam'));
                                    $tetel->setMennyiseg($this->params->getFloatRequestParam('tetelmennyiseg_' . $tetelid));
                                    $tetel->setNettoegysar($this->params->getFloatRequestParam('tetelnettoegysar_' . $tetelid));
                                    $tetel->setBruttoegysar($this->params->getFloatRequestParam('tetelbruttoegysar_' . $tetelid));
                                    $arak = $biztetelcontroller->calcAr(
                                        $tetel->getAfaId(),
                                        $tetel->getArfolyam(),
                                        $tetel->getNettoegysar(),
                                        $tetel->getMennyiseg()
                                    );
                                    $tetel->setNetto($arak['netto']);
                                    $tetel->setBrutto($arak['brutto']);
                                    $tetel->setAfaertek($arak['afa']);
                                    $tetel->setNettoegysarhuf($arak['nettoegysarhuf']);
                                    $tetel->setBruttoegysarhuf($arak['bruttoegysarhuf']);
                                    $tetel->setNettohuf($arak['nettohuf']);
                                    $tetel->setBruttohuf($arak['bruttohuf']);
                                    $tetel->setAfaertekhuf($arak['afahuf']);
                                    $tetel->setHatarido($this->params->getStringRequestParam('tetelhatarido_' . $tetelid));
                                }

                                $this->getEm()->persist($tetel);
                            }
                            break;
                    }
                }
                else {
                    \mkw\Store::writelog(print_r($this->params->asArray(), true), 'nincstermek.log');
                }
			}
		}
		return $obj;
	}

    protected function afterSave($o) {
        $oper = $this->params->getStringRequestParam('oper');
        parent::afterSave($o);
        if ($oper === 'storno') {
            $parentid = $this->params->getStringRequestParam('parentid');
            if ($parentid) {
                $parent = $this->getRepo()->find($parentid);
                if ($parent) {
                    $parent->setStornozott(true);
                    $this->getEm()->persist($parent);
                    $this->getEm()->flush();
                }
            }
        }
        else {
            if ($this->params->getBoolRequestParam('szallitasiktgkell')) {
                $this->getRepo()->createSzallitasiKtg($o, $o->getSzallitasimodId());
                $o->doStuffOnPrePersist();
                $this->getEm()->persist($o);
                $this->getEm()->flush();
            }
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

    public function ront() {
        $id = $this->params->getStringRequestParam('id');
        if ($id) {
            $bf = $this->getRepo()->find($id);
            if ($bf) {
                $bf->setRontott(true);
                $this->getEm()->persist($bf);
                $this->getEm()->flush();
            }
        }
    }

    public function getlistbody() {
        $view = $this->createView('bizonylatfejlista_tbody.tpl');

        $this->setVars($view);

        $filter = array();
        $filter = $this->loadFilters($filter);

        $filter['fields'][] = 'bizonylattipus';
        $filter['clauses'][] = '=';
        $filter['values'][] = $this->getRepo('Entities\Bizonylattipus')->find($this->biztipus);

        $this->initPager($this->getRepo()->getCount($filter));

        $egyedek = $this->getRepo()->getWithJoins(
                $filter, $this->getOrderArray(), $this->getPager()->getOffset(), $this->getPager()->getElemPerPage());

        echo json_encode($this->loadDataToView($egyedek, 'egyedlista', $view));
    }

    public function doPrint() {
        $o = $this->getRepo()->findForPrint($this->params->getStringRequestParam('id'));
        if ($o) {
            if ($o->getReportfile()) {
                $tplname = $o->getReportfile();
            }
            else {
                $biztip = $this->getRepo('Entities\Bizonylattipus')->find($this->biztipus);
                if ($biztip && $biztip->getTplname()) {
                    $tplname = $biztip->getTplname();
                }
            }
            $view = $this->createView($tplname);
            $this->setVars($view);
            $view->setVar('egyed', $o->toLista());
            $view->setVar('afaosszesito',$this->getRepo()->getAFAOsszesito($o));
            echo $view->getTemplateResult();
        }
    }

    public function getFiokList() {
        $filter = array();
        $filter['fields'][] = 'partner';
        $filter['clauses'][] = '=';
        $filter['values'][] = $this->getRepo('Entities\Partner')->getLoggedInUser();
        $filter['fields'][] = 'bizonylattipus';
        $filter['clauses'][] = '=';
        $filter['values'][] = $this->getRepo('Entities\Bizonylattipus')->find($this->biztipus);
        $l = $this->getRepo()->getWithJoins($filter, array('kelt' => 'ASC'));
        $ret = array();
        foreach ($l as $it) {
            $ret[] = $it->toLista();
        }
        return $ret;
    }

    public function viewkarb() {
        $this->getkarb($this->getKarbTplName());
    }

    protected function setVarsForKarb($view, $record) {
        $bt = $this->getRepo('Entities\Bizonylattipus')->find($this->biztipus);
        $bt->setTemplateVars($view);

        $partner = new partnerController($this->params);
        $view->setVar('partnerlist', $partner->getSelectList(($record ? $record->getPartnerId() : 0)));

        $raktar = new raktarController($this->params);
        if (!$record || !$record->getRaktarId()) {
            $raktarid = store::getParameter(\mkw\consts::Raktar, 0);
        }
        else {
            $raktarid = $record->getRaktarId();
        }
        $view->setVar('raktarlist', $raktar->getSelectList($raktarid));

        $fizmod = new fizmodController($this->params);
        if (!$record || !$record->getFizmodId()) {
            $fmid = \mkw\Store::getParameter(\mkw\consts::Fizmod);
        }
        else {
            $fmid = $record->getFizmodId();
        }
        $view->setVar('fizmodlist', $fizmod->getSelectList($fmid));

        $szallitasimod = new szallitasimodController($this->params);
        $view->setVar('szallitasimodlist', $szallitasimod->getSelectList(($record ? $record->getSzallitasimodId() : 0), true));

        $valutanem = new valutanemController($this->params);
        if (!$record || !$record->getValutanemId()) {
            $valutaid = store::getParameter(\mkw\consts::Valutanem, 0);
        }
        else {
            $valutaid = $record->getValutanemId();
        }
        $view->setVar('valutanemlist', $valutanem->getSelectList($valutaid));

        $uk = new uzletkotoController($this->params);
        if ($record && $record->getUzletkotoId()) {
            $ukid = $record->getUzletkotoId();
        }
        $view->setVar('uzletkotolist', $uk->getSelectList($ukid));

        $bankszla = new bankszamlaController($this->params);
        $view->setVar('bankszamlalist', $bankszla->getSelectList(($record ? $record->getBankszamlaId() : 0)));

        $view->setVar('esedekessegalap', store::getParameter(\mkw\consts::Esedekessegalap, 1));
        $view->setVar('reportfilelist', $this->getRepo()->getReportfileSelectList(($record ? $record->getReportfile() : ''), ($record ? $record->getBizonylattipusId() : $this->biztipus)));
        $view->setVar('bizonylatnyelvlist', store::getLocaleSelectList(($record ? $record->getBizonylatnyelv() : '')));

    }

    public function getkarb($tplname = null, $id = null, $oper = null, $quick = null) {
        if (!$tplname) {
            $tplname = $this->getKarbFormTplName();
        }
        if (!$id) {
            $id = $this->params->getRequestParam('id', 0);
        }
        if (!$oper) {
            $oper = $this->params->getRequestParam('oper', '');
        }
        if (!$quick) {
            $quick = $this->params->getBoolRequestParam('quick');
        }
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', $this->getPageTitle());
        $view->setVar('controllerscript', $this->biztipus . 'fej.js');
        $view->setVar('formaction', '/admin/' . $this->biztipus . 'fej/save');
        $view->setVar('oper', $oper);
        $view->setVar('quick', $quick);
 //       $this->setVars($view);

        $record = $this->getRepo()->findWithJoins($id);
        $egyed = $this->loadVars($record, true);

        $this->setVarsForKarb($view, $record);

        if (method_exists($this, 'onGetKarb')) {
            $egyed = $this->onGetKarb($view, $record, $egyed, $oper, $id);
        }

        $view->setVar('egyed', $egyed);

        $view->setVar('esedekessegalap', store::getParameter(\mkw\consts::Esedekessegalap, 1));
        $view->printTemplateResult();
    }

}