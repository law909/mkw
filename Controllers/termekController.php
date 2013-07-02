<?php
namespace Controllers;

use Entities\TermekValtozat, Entities\TermekRecept;

use mkw\store;

class termekController extends \mkwhelpers\MattableController {

	public function __construct($params) {
		$this->setEntityName('Entities\Termek');
		$this->setKarbFormTplName('termekkarbform.tpl');
		$this->setKarbTplName('termekkarb.tpl');
		$this->setListBodyRowTplName('termeklista_tbody_tr.tpl');
		$this->setListBodyRowVarName('_termek');
		parent::__construct($params);
	}

	protected function loadVars($t,$forKarb=false) {
		$kepCtrl=new termekkepController($this->params);
		$receptCtrl=new termekreceptController($this->params);
		$valtozatCtrl=new termekvaltozatController($this->params);
		$kapcsolodoCtrl=new termekkapcsolodoController($this->params);
		$ar=array();
		$kep=array();
		$recept=array();
		$valtozat=array();
		$lvaltozat=array();
		$kapcsolodo=array();
		$x=array();
		if (!$t) {
			$t=new \Entities\Termek();
			$this->getEm()->detach($t);
		}
		$x['id']=$t->getId();
		$x['vtsznev']=$t->getVtszNev();
		$x['afanev']=$t->getAfaNev();
		$x['nev']=$t->getNev();
		$x['slug']=$t->getSlug();
		$x['me']=$t->getMe();
		$x['cikkszam']=$t->getCikkszam();
		$x['idegencikkszam']=$t->getIdegencikkszam();
		$x['oldalcim']=$t->getOldalcim();
		$x['rovidleiras']=$t->getRovidleiras();
		$x['leiras']=$t->getLeiras();
		$x['seodescription']=$t->getSeodescription();
		$x['seokeywords']=$t->getSeokeywords();
		$x['lathato']=$t->getLathato();
		$x['hozzaszolas']=$t->getHozzaszolas();
		$x['ajanlott']=$t->getAjanlott();
		$x['kiemelt']=$t->getKiemelt();
		$x['inaktiv']=$t->getInaktiv();
		$x['mozgat']=$t->getMozgat();
		$x['hparany']=$t->getHparany();
		$x['cimkek']=$t->getCimkeNevek();
		$x['netto']=$t->getNetto();
		$x['brutto']=$t->getBrutto();
		$x['akciosnetto']=$t->getAkciosnetto();
		$x['akciosbrutto']=$t->getAkciosbrutto();
		$x['akciostart']=$t->getAkciostart();
		$x['akciostartstr']=$t->getAkciostartStr();
		$x['akciostop']=$t->getAkciostop();
		$x['akciostopstr']=$t->getAkciostopStr();
		$x['termekexportbanszerepel']=$t->getTermekexportbanszerepel();
		$x['nemkaphato']=$t->getNemkaphato();
		if ($forKarb) {

			foreach($t->getTermekKepek() as $kepje) {
				$kep[]=$kepCtrl->loadVars($kepje);
			}
			//$kep[]=$kepCtrl->loadVars(null);
			$x['kepek']=$kep;

			foreach($t->getTermekKapcsolodok() as $tkapcsolodo) {
				$kapcsolodo[]=$kapcsolodoCtrl->loadVars($tkapcsolodo,true);
			}
			//$kapcsolodo[]=$kapcsolodoCtrl->loadVars(null,true);
			$x['kapcsolodok']=$kapcsolodo;

			if (store::getSetupValue('receptura')) {
				foreach($t->getTermekReceptek() as $trecept) {
					$recept[]=$receptCtrl->loadVars($trecept,true);
				}
				//$recept[]=$receptCtrl->loadVars(null,true);
				$x['receptek']=$recept;
			}
			if (store::getSetupValue('termekvaltozat')) {
				foreach($t->getValtozatok() as $tvaltozat) {
					$valtozat[]=$valtozatCtrl->loadVars($tvaltozat,$t);
				}
				//$valtozat[]=$valtozatCtrl->loadVars(null);
				$x['valtozatok']=$valtozat;
			}
		}
		$x['termekfa1nev']=$t->getTermekfa1Nev();
		$x['termekfa2nev']=$t->getTermekfa2Nev();
		$x['termekfa3nev']=$t->getTermekfa3Nev();
		$x['termekfa1']=$t->getTermekfa1Id();
		$x['termekfa2']=$t->getTermekfa2Id();
		$x['termekfa3']=$t->getTermekfa3Id();
		$x['kepurl']=$t->getKepurl();
		$x['kepurlsmall']=$t->getKepurlSmall();
		$x['kepurlmedium']=$t->getKepurlMedium();
		$x['kepurllarge']=$t->getKepurlLarge();
		$x['kepleiras']=$t->getKepleiras();
		$x['szelesseg']=$t->getSzelesseg();
		$x['magassag']=$t->getMagassag();
		$x['hosszusag']=$t->getHosszusag();
		$x['suly']=$t->getSuly();
		$x['osszehajthato']=$t->getOsszehajthato();
		$x['megtekintesdb']=$t->getMegtekintesdb();
		$x['megvasarlasdb']=$t->getMegvasarlasdb();
		return $x;
	}

	protected function setFields($obj) {
		$afa=store::getEm()->getRepository('Entities\Afa')->find($this->params->getIntRequestParam('afa'));
		if ($afa) {
			$obj->setAfa($afa);
		}
		$vtsz=store::getEm()->getRepository('Entities\Vtsz')->find($this->params->getIntRequestParam('vtsz'));
		if ($vtsz) {
			$obj->setVtsz($vtsz);
		}
		$valt=store::getEm()->getRepository('Entities\TermekValtozatAdatTipus')->find($this->params->getIntRequestParam('valtozatadattipus'));
		if ($valt) {
			$obj->setValtozatadattipus($valt);
		}
		else {
			$obj->setValtozatadattipus(null);
		}
		$obj->setNev($this->params->getStringRequestParam('nev'));
		$obj->setMe($this->params->getStringRequestParam('me'));
		$obj->setCikkszam($this->params->getStringRequestParam('cikkszam'));
		$obj->setIdegencikkszam($this->params->getStringRequestParam('idegencikkszam'));
		$obj->setOldalcim($this->params->getStringRequestParam('oldalcim'));
		$obj->setRovidleiras($this->params->getStringRequestParam('rovidleiras'));
		$obj->setLeiras($this->params->getOriginalStringRequestParam('leiras'));
		$obj->setSeodescription($this->params->getStringRequestParam('seodescription'));
		$obj->setSeokeywords($this->params->getStringRequestParam('seokeywords'));
		$obj->setLathato($this->params->getBoolRequestParam('lathato',true));
		$obj->setHozzaszolas($this->params->getBoolRequestParam('hozzaszolas',false));
		$obj->setAjanlott($this->params->getBoolRequestParam('ajanlott',false));
		$obj->setKiemelt($this->params->getBoolRequestParam('kiemelt',false));
		$obj->setInaktiv($this->params->getBoolRequestParam('inaktiv',false));
		$obj->setMozgat($this->params->getBoolRequestParam('mozgat',true));
		$obj->setHparany($this->params->getFloatRequestParam('hparany'));
		$obj->setSzelesseg($this->params->getFloatRequestParam('szelesseg'));
		$obj->setMagassag($this->params->getFloatRequestParam('magassag'));
		$obj->setHosszusag($this->params->getFloatRequestParam('hosszusag'));
		$obj->setSuly($this->params->getFloatRequestParam('suly'));
		$obj->setOsszehajthato($this->params->getBoolRequestParam('osszehajthato'));
		$obj->setKepurl($this->params->getStringRequestParam('kepurl',''));
		$obj->setKepleiras($this->params->getStringRequestParam('kepleiras',''));
		$obj->setTermekexportbanszerepel($this->params->getBoolRequestParam('termekexportbanszerepel',true));
		$obj->setNemkaphato($this->params->getBoolRequestParam('nemkaphato',false));
		$farepo=store::getEm()->getRepository('Entities\TermekFa');
		$fa=$farepo->find($this->params->getIntRequestParam('termekfa1'));
		if ($fa) {
			$obj->setTermekfa1($fa);
		}
		else {
			$obj->setTermekfa1(null);
		}
		$fa=$farepo->find($this->params->getIntRequestParam('termekfa2'));
		if ($fa) {
			$obj->setTermekfa2($fa);
		}
		else {
			$obj->setTermekfa2(null);
		}
		$fa=$farepo->find($this->params->getIntRequestParam('termekfa3'));
		if ($fa) {
			$obj->setTermekfa3($fa);
		}
		else {
			$obj->setTermekfa3(null);
		}
		$obj->removeAllCimke();
		$cimkekpar=$this->params->getArrayRequestParam('cimkek');
		foreach($cimkekpar as $cimkekod) {
			$cimke=$this->getEm()->getRepository('Entities\Termekcimketorzs')->find($cimkekod);
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
		$kepids=$this->params->getArrayRequestParam('kepid');
		foreach($kepids as $kepid) {
			if ($this->params->getStringRequestParam('kepurl_'.$kepid,'')!=='') {
				$oper=$this->params->getStringRequestParam('kepoper_'.$kepid);
				if ($oper=='add') {
					$kep=new \Entities\TermekKep();
					$obj->addTermekKep($kep);
					$kep->setUrl($this->params->getStringRequestParam('kepurl_'.$kepid));
					$kep->setLeiras($this->params->getStringRequestParam('kepleiras_'.$kepid));
					$this->getEm()->persist($kep);
				}
				elseif ($oper=='edit') {
					$kep=store::getEm()->getRepository('Entities\TermekKep')->find($kepid);
					if ($kep) {
						$kep->setUrl($this->params->getStringRequestParam('kepurl_'.$kepid));
						$kep->setLeiras($this->params->getStringRequestParam('kepleiras_'.$kepid));
						$this->getEm()->persist($kep);
					}
				}
			}
		}
		$kapcsolodoids=$this->params->getArrayRequestParam('kapcsolodoid');
		foreach($kapcsolodoids as $kapcsolodoid) {
			if (($this->params->getIntRequestParam('kapcsolodoaltermek_'.$kapcsolodoid)>0)) {
				$oper=$this->params->getStringRequestParam('kapcsolodooper_'.$kapcsolodoid);
				$altermek=$this->getEm()->getRepository('Entities\Termek')->find($this->params->getIntRequestParam('kapcsolodoaltermek_'.$kapcsolodoid));
				if ($oper=='add') {
					$kapcsolodo=new \Entities\TermekKapcsolodo();
					$obj->addTermekKapcsolodo($kapcsolodo);
					if ($altermek) {
						$kapcsolodo->setAlTermek($altermek);
					}
					$this->getEm()->persist($kapcsolodo);
				}
				elseif ($oper=='edit') {
					$kapcsolodo=$this->getEm()->getRepository('Entities\TermekKapcsolodo')->find($kapcsolodoid);
					if ($kapcsolodo) {
						if ($altermek) {
							$kapcsolodo->setAlTermek($altermek);
						}
						$this->getEm()->persist($kapcsolodo);
					}
				}
			}
		}
		if (store::getSetupValue('receptura')) {
			$receptids=$this->params->getArrayRequestParam('receptid');
			foreach($receptids as $receptid) {
				if (($this->params->getIntRequestParam('receptaltermek_'.$receptid)>0)) {
					$oper=$this->params->getStringRequestParam('receptoper_'.$receptid);
					$altermek=$this->getEm()->getRepository('Entities\Termek')->find($this->params->getIntRequestParam('receptaltermek_'.$receptid));
					if ($oper=='add') {
						$recept=new TermekRecept();
						$obj->addTermekRecept($recept);
						if ($altermek) {
							$recept->setAlTermek($altermek);
						}
						$recept->setMennyiseg($this->params->getFloatRequestParam('receptmennyiseg_'.$receptid));
						$recept->setKotelezo($this->params->getBoolRequestParam('receptkotelezo_'.$receptid));
						$this->getEm()->persist($recept);
					}
					elseif ($oper=='edit') {
						$recept=$this->getEm()->getRepository('Entities\TermekRecept')->find($receptid);
						if ($recept) {
							if ($altermek) {
								$recept->setAlTermek($altermek);
							}
							$recept->setMennyiseg($this->params->getFloatRequestParam('receptmennyiseg_'.$receptid));
							$recept->setKotelezo($this->params->getBoolRequestParam('receptkotelezo_'.$receptid));
							$this->getEm()->persist($recept);
						}
					}
				}
			}
		}
		if (store::getSetupValue('termekvaltozat')) {
			$valtozatids=$this->params->getArrayRequestParam('valtozatid');
			foreach($valtozatids as $valtozatid) {
				$valtdb=0;
				$oper=$this->params->getStringRequestParam('valtozatoper_'.$valtozatid);
				if ($oper=='add') {
					$valtozat=new TermekValtozat();
					$obj->addValtozat($valtozat);
					$valtozat->setLathato($this->params->getBoolRequestParam('valtozatlathato_'.$valtozatid));
					if ($obj->getNemkaphato()) {
						$valtozat->setElerheto(false);
					}
					else {
						$valtozat->setElerheto($this->params->getBoolRequestParam('valtozatelerheto_'.$valtozatid));
					}
//						$valtozat->setBrutto($this->params->getNumRequestParam('valtozatbrutto_'.$valtozatid));
					$valtozat->setNetto($this->params->getNumRequestParam('valtozatnetto_'.$valtozatid));
					$valtozat->setTermekfokep($this->params->getBoolRequestParam('valtozattermekfokep_'.$valtozatid));
					$valtozat->setCikkszam($this->params->getStringRequestParam('valtozatcikkszam_'.$valtozatid));
					$valtozat->setIdegencikkszam($this->params->getStringRequestParam('valtozatidegencikkszam_'.$valtozatid));

					$at=$this->getEm()->getRepository('Entities\TermekValtozatAdatTipus')->find($this->params->getIntRequestParam('valtozatadattipus1_'.$valtozatid));
					$valtert=$this->params->getStringRequestParam('valtozatertek1_'.$valtozatid);
					if ($at&&$valtert) {
						$valtozat->setAdatTipus1($at);
						$valtozat->setErtek1($valtert);
						$valtdb++;
					}

					$at=$this->getEm()->getRepository('Entities\TermekValtozatAdatTipus')->find($this->params->getIntRequestParam('valtozatadattipus2_'.$valtozatid));
					$valtert=$this->params->getStringRequestParam('valtozatertek2_'.$valtozatid);
					if ($at&&$valtert) {
						$valtozat->setAdatTipus2($at);
						$valtozat->setErtek2($valtert);
						$valtdb++;
					}

					$at=$this->getEm()->getRepository('Entities\TermekKep')->find($this->params->getIntRequestParam('valtozatkepid_'.$valtozatid));
					if ($at) {
						$valtozat->setKep($at);
					}

					if ($valtdb>0) {
						$this->getEm()->persist($valtozat);
					}
					else {
						$obj->removeValtozat($valtozat);
					}
				}
				elseif ($oper=='edit') {
					$valtozat=$this->getEm()->getRepository('Entities\TermekValtozat')->find($valtozatid);
					if ($valtozat) {
						$valtozat->setLathato($this->params->getBoolRequestParam('valtozatlathato_'.$valtozatid));
						if ($obj->getNemkaphato()) {
							$valtozat->setElerheto(false);
						}
						else {
							$valtozat->setElerheto($this->params->getBoolRequestParam('valtozatelerheto_'.$valtozatid));
						}
//							$valtozat->setBrutto($this->params->getNumRequestParam('valtozatbrutto_'.$valtozatid));
						$valtozat->setNetto($this->params->getNumRequestParam('valtozatnetto_'.$valtozatid));
						$valtozat->setTermekfokep($this->params->getBoolRequestParam('valtozattermekfokep_'.$valtozatid));
						$valtozat->setCikkszam($this->params->getStringRequestParam('valtozatcikkszam_'.$valtozatid));
						$valtozat->setIdegencikkszam($this->params->getStringRequestParam('valtozatidegencikkszam_'.$valtozatid));

						$at=$this->getEm()->getRepository('Entities\TermekValtozatAdatTipus')->find($this->params->getIntRequestParam('valtozatadattipus1_'.$valtozatid));
						$valtert=$this->params->getStringRequestParam('valtozatertek1_'.$valtozatid);
						if ($at&&$valtert) {
							$valtozat->setAdatTipus1($at);
							$valtozat->setErtek1($valtert);
						}
						else {
							$valtozat->setAdatTipus1(null);
							$valtozat->setErtek1(null);
						}

						$at=$this->getEm()->getRepository('Entities\TermekValtozatAdatTipus')->find($this->params->getIntRequestParam('valtozatadattipus2_'.$valtozatid));
						$valtert=$this->params->getStringRequestParam('valtozatertek2_'.$valtozatid);
						if ($at&&$valtert) {
							$valtozat->setAdatTipus2($at);
							$valtozat->setErtek2($valtert);
						}
						else {
							$valtozat->setAdatTipus2(null);
							$valtozat->setErtek2(null);
						}

						$at=$this->getEm()->getRepository('Entities\TermekKep')->find($this->params->getIntRequestParam('valtozatkepid_'.$valtozatid));
						if ($at) {
							$valtozat->setKep($at);
						}

						$this->getEm()->persist($valtozat);
					}
				}
			}
		}
		$obj->doStuffOnPrePersist();  // ha csak kapcsolódó adat változott, akkor prepresist/preupdate nem hívódik, de cimke gyorsítás miatt nekünk kell
		return $obj;
	}

	public function getlistbody() {
		$view=$this->createView('termeklista_tbody.tpl');

		$filter=array();
		if (!is_null($this->params->getRequestParam('nevfilter',NULL))) {
			$filter['fields'][]=array('nev','rovidleiras','cikkszam');
			$filter['clauses'][]='';
			$filter['values'][]=$this->params->getStringRequestParam('nevfilter');
		}

		if (!is_null($this->params->getRequestParam('cimkefilter',NULL))) {
			$fv=$this->params->getArrayRequestParam('cimkefilter');
			$res=Store::getEm()->getRepository('Entities\Termekcimketorzs')->getTermekIdsWithCimke($fv);
			$cimkefilter=array();
			foreach($res as $sor) {
				$cimkefilter[]=$sor['id'];
			}
			$filter['fields'][]='id';
			$filter['clauses'][]='';
			$filter['values'][]=$cimkefilter;
		}

		if (!is_null($this->params->getRequestParam('fafilter',NULL))) {
			$fv=$this->params->getArrayRequestParam('fafilter');
			$ff=array();
			$ff['fields'][]='id';
			$ff['values'][]=$fv;
			$res=store::getEm()->getRepository('Entities\TermekFa')->getAll($ff,array());
			$faszuro=array();
			foreach($res as $sor) {
				$faszuro[]=$sor->getKarkod().'%';
			}
			$filter['fields'][]=array('_xx.termekfa1karkod','_xx.termekfa2karkod','_xx.termekfa3karkod');
			$filter['clauses'][]='LIKE';
			$filter['values'][]=$faszuro;
		}

		$this->initPager($this->getRepo()->getCount($filter));

		$egyedek=$this->getRepo()->getWithJoins(
			$filter,
			$this->getOrderArray(),
			$this->getPager()->getOffset(),
			$this->getPager()->getElemPerPage());

		echo json_encode($this->loadDataToView($egyedek,'termeklista',$view));
	}

	public function getselectlist($selid) {
		// TODO sok termek eseten lassu lehet
		$rec=$this->getRepo()->getAllForSelectList(array(),array('nev'=>'ASC'));
		$res=array();
		foreach($rec as $sor) {
			$res[]=array(
				'id'=>$sor['id'],
				'caption'=>$sor['nev'],
				'selected'=>($sor['id']==$selid)
			);
		}
		return $res;
	}

	public function htmllist() {
		$rec=$this->getRepo()->getAllForSelectList(array(),array('nev'=>'asc'));
		$ret='<select>';
		foreach($rec as $sor) {
			$ret.='<option value="'.$sor['id'].'">'.$sor['nev'].'</option>';
		}
		$ret.='</select>';
		echo $ret;
	}

	public function getBizonylattetelSelectList($selid) {
		$rec=$this->getRepo()->getAllForBizonylattetel();
		$res=array();
		foreach($rec as $sor) {
			$res[]=array(
				'id'=>$sor['id'],
				'caption'=>$sor['nev'],
				'selected'=>($sor['id']==$selid),
				'me'=>$sor['me'],
				'vtsz'=>$sor['vtsz'],
				'afa'=>$sor['afa'],
				'kiszereles'=>$sor['kiszereles'],
				'cikkszam'=>$sor['cikkszam']
			);
		}
		return $res;
	}

	public function viewlist() {
		$view=$this->createView('termeklista.tpl');
		$view->setVar('pagetitle',t('Termékek'));
		$view->setVar('orderselect',$this->getRepo()->getOrdersForTpl());
		$view->setVar('batchesselect',$this->getRepo()->getBatchesForTpl());
		$tcc=new termekcimkekatController($this->params);
		$view->setVar('cimkekat',$tcc->getWithCimkek(null));
		$view->printTemplateResult();
	}

	protected function _getkarb($tplname) {
		$id=$this->params->getRequestParam('id',0);
		$oper=$this->params->getRequestParam('oper','');
		$view=$this->createView($tplname);
		$view->setVar('pagetitle',t('Termék'));
		$view->setVar('oper',$oper);

		$termek=$this->getRepo()->findWithJoins($id);
		// LoadVars utan nem abc sorrendben adja vissza
		$tcc=new termekcimkekatController($this->params);
		$cimkek=$termek?$termek->getAllCimkeId():null;
		$view->setVar('cimkekat',$tcc->getWithCimkek($cimkek));

		$view->setVar('termek',$this->loadVars($termek,true));
		$vtsz=new vtszController($this->params);
		$view->setVar('vtszlist',$vtsz->getSelectList(($termek?$termek->getVtszId():0)));
		$afa=new afaController($this->params);
		$view->setVar('afalist',$afa->getSelectList(($termek?$termek->getAfaId():0)));
		$valtozatadattipus=new termekvaltozatadattipusController($this->params);
		$view->setVar('valtozatadattipuslist',$valtozatadattipus->getSelectList(($termek?$termek->getValtozatadattipusId():0)));
		$kep=new termekkepController($this->params);
		$view->setVar('keplist',$kep->getSelectList($termek, null));
		$view->printTemplateResult();
	}

	public function setflag() {
		$id=$this->params->getIntRequestParam('id');
		$kibe=$this->params->getBoolRequestParam('kibe');
		$flag=$this->params->getStringRequestParam('flag');
		$obj=$this->params->getRepo()->find($id);
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
					$obj->setNemkaphato($kibe);
					if ($obj->getNemkaphato()) {
						$valtozatok=$obj->getValtozatok();
						foreach($valtozatok as $valt) {
							$valt->setElerheto(false);
							$this->getEm()->persist($valt);
						}
					}
					break;
			}
			$this->getEm()->persist($obj);
			$this->getEm()->flush();
		}
	}

	public function getbrutto() {
		$id=$this->params->getIntRequestParam('id');
		$netto=$this->params->getFloatRequestParam('value');
		$afa=$this->getEm()->getRepository('Entities\Afa')->find($this->params->getIntRequestParam('afakod'));
		if (!$afa) {
			$termek=$this->getRepo()->find($id);
			if ($termek) {
				$afa=$termek->getAfa();
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
		$id=$this->params->getIntRequestParam('id');
		$brutto=$this->params->getFloatRequestParam('value');
		$afa=$this->getEm()->getRepository('Entities\Afa')->find($this->params->getIntRequestParam('afakod'));
		if (!$afa) {
			$termek=$this->getRepo()->find($id);
			if ($termek) {
				$afa=$termek->getAfa();
			}
		}
		if ($afa) {
			echo $afa->calcNetto($brutto);
		}
		else {
			echo $brutto;
		}
	}

/*	protected function savepicture() {
		$fa=$this->getRepo()->find($this->getIntParam('id'));
		if ($fa) {
			$uploaddir=store::getConfigValue('path.termekkep');
			$pp=pathinfo($_FILES['userfile']['name']);
			$uploadfile=$uploaddir.$this->getStringParam('nev').'.'.$pp['extension'];
			if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
				$imageproc=new \mkwhelpers\Images($uploadfile);
				$imageproc->setJpgquality(store::getParameter('jpgquality'));
				$imageproc->setPngquality(store::getParameter('pngquality'));
				$smallfn=$uploaddir.$this->getStringParam('nev').store::getParameter('smallimgpost','').'.'.$pp['extension'];
				$mediumfn=$uploaddir.$this->getStringParam('nev').store::getParameter('mediumimgpost','').'.'.$pp['extension'];
				$largefn=$uploaddir.$this->getStringParam('nev').store::getParameter('bigimgpost','').'.'.$pp['extension'];
				$imageproc->Resample($smallfn,store::getParameter('smallimagesize',80));
				$imageproc->Resample($mediumfn,store::getParameter('mediumimagesize',200));
				$imageproc->Resample($largefn,store::getParameter('bigimagesize',800));
				$fa->setKepleiras($this->getStringParam('leiras'));
				$fa->setKepurl($uploadfile);
				$this->getEm()->persist($fa);
				$this->getEm()->flush();
//				$resp=array('kepurl'=>'/'.$largefn,'kepurlsmall'=>'/'.$smallfn,'kepleiras'=>$this->getStringParam('leiras'));
//				echo json_encode($resp);
				$view=$this->createView('termekimagekarb.tpl');
				$view->setVar('oper','edit');
				$view->setVar('termek',$this->loadVars($fa));
				$view->printTemplateResult();
			}
		}
	}

	protected function delpicture() {
		$fa=$this->getRepo()->find($this->getIntParam('id'));
		if ($fa) {
			unlink($fa->getKepurl(''));
			unlink($fa->getKepurlSmall(''));
			unlink($fa->getKepurlMedium(''));
			unlink($fa->getKepurlLarge(''));
			$fa->setKepurl(null);
			$this->getEm()->persist($fa);
			$this->getEm()->flush();
			$view=$this->createView('termekimagekarb.tpl');
			$view->setVar('oper','edit');
			$view->printTemplateResult();
		}
	}
*/
	public function getTermekLap($termek) {
		$tfc=new termekfaController($this->params);

		$ret=array();

		if ($termek->getTermekfa1()) {
			$ret['navigator']=$tfc->getNavigator($termek->getTermekfa1(),true);
		}
		else {
			$ret['navigator']=array();
		}
		$ret['termek']=$termek->toTermekLap();

		$termek->incMegtekintesdb();
		$this->getEm()->persist($termek);
		$this->getEm()->flush();
		return $ret;
	}

	public function getAjanlottLista() {
		$termekek=$this->getRepo()->getAjanlottTermekek(store::getParameter('fooldalajanlotttermekdb',5));
		$ret=array();
		foreach($termekek as $termek) {
			$ret[]=$termek->toTermekLista();
		}
		return $ret;
	}

	public function getLegnepszerubbLista() {
		$termekek=$this->getRepo()->getLegnepszerubbTermekek(store::getParameter('fooldalnepszerutermekdb',5));
		$ret=array();
		foreach($termekek as $termek) {
			$ret[]=$termek->toTermekLista();
		}
		return $ret;
	}

	public function feed() {
		$feedview=$this->getTemplateFactory()->createMainView('feed.tpl');
		$view=$this->getTemplateFactory()->createMainView('termekfeed.tpl');
		$feedview->setVar('title',store::getParameter('feedtermektitle',t('Termékeink')));
		$feedview->setVar('link',store::getRouter()->generate('termekfeed',true));
		$d=new \DateTime();
		$feedview->setVar('pubdate',$d->format('D, d M Y H:i:s'));
		$feedview->setVar('lastbuilddate',$d->format('D, d M Y H:i:s'));
		$feedview->setVar('description',store::getParameter('feedtermekdescription',''));
		$entries=array();
		$termekek=$this->getRepo()->getFeedTermek();
		foreach($termekek as $termek) {
			$view->setVar('kepurl',$termek->getKepUrlSmall());
			$view->setVar('szoveg',$termek->getRovidLeiras());
			$view->setVar('url',store::getRouter()->generate('showtermek',true,array('slug'=>$termek->getSlug())));
			$entries[]=array(
				'title'=>$termek->getNev(),
				'link'=>store::getRouter()->generate('showtermek',true,array('slug'=>$termek->getSlug())),
				'guid'=>store::getRouter()->generate('showtermek',true,array('slug'=>$termek->getSlug())),
				'description'=>$view->getTemplateResult(),
				'pubdate'=>$d->format('D, d M Y H:i:s')
			);
		}
		$feedview->setVar('entries',$entries);
		header('Content-type: text/xml');
		$feedview->printTemplateResult();
	}
}