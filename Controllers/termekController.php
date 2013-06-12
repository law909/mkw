<?php
namespace Controllers;

use Entities\TermekValtozat;

use Entities\TermekAr, Entities\TermekRecept;

use matt, matt\Exceptions, SIIKerES\store;

class termekController extends matt\MattableController {

	public function __construct($generalDataLoader,$actionName=null,$commandString=null) {
		$this->setEntityName('Entities\Termek');
		$this->setEm(store::getEm());
		$this->setTemplateFactory(store::getTemplateFactory());
		$this->setKarbFormTplName('termekkarbform.tpl');
		$this->setKarbTplName('termekkarb.tpl');
		$this->setListBodyRowTplName('termeklista_tbody_tr.tpl');
		$this->setListBodyRowVarName('_termek');
		parent::__construct($generalDataLoader,$actionName,$commandString);
	}

	public function handleRequest() {
		$methodname=$this->getActionName();
		if ($this->mainMethodExists(__CLASS__,$methodname)) {
			$this->$methodname();
		}
		elseif ($this->adminMethodExists(__CLASS__,$methodname)) {
				$this->$methodname();
		}
		else {
			throw new matt\Exceptions\UnknownMethodException('"'.__CLASS__.'->'.$methodname.'" does not exist.');
		}
	}

	protected function loadVars($t,$forKarb=false) {
		$kepCtrl=new termekkepController($this->generalDataLoader);
		$receptCtrl=new termekreceptController($this->generalDataLoader);
		$valtozatCtrl=new termekvaltozatController($this->generalDataLoader);
		$kapcsolodoCtrl=new termekkapcsolodoController($this->generalDataLoader);
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
		try {
			$afa=store::getEm()->getRepository('Entities\Afa')->find($this->getIntParam('afa'));
			if ($afa) {
				$obj->setAfa($afa);
			}
			$vtsz=store::getEm()->getRepository('Entities\Vtsz')->find($this->getIntParam('vtsz'));
			if ($vtsz) {
				$obj->setVtsz($vtsz);
			}
			$valt=store::getEm()->getRepository('Entities\TermekValtozatAdatTipus')->find($this->getIntParam('valtozatadattipus'));
			if ($valt) {
				$obj->setValtozatadattipus($valt);
			}
			else {
				$obj->setValtozatadattipus(null);
			}
			$obj->setNev($this->getStringParam('nev'));
			$obj->setMe($this->getStringParam('me'));
			$obj->setCikkszam($this->getStringParam('cikkszam'));
			$obj->setIdegencikkszam($this->getStringParam('idegencikkszam'));
			$obj->setOldalcim($this->getStringParam('oldalcim'));
			$obj->setRovidleiras($this->getStringParam('rovidleiras'));
			$obj->setLeiras($this->getStringParam('leiras'));
			$obj->setSeodescription($this->getStringParam('seodescription'));
			$obj->setSeokeywords($this->getStringParam('seokeywords'));
			$obj->setLathato($this->getBoolParam('lathato',true));
			$obj->setHozzaszolas($this->getBoolParam('hozzaszolas',false));
			$obj->setAjanlott($this->getBoolParam('ajanlott',false));
			$obj->setKiemelt($this->getBoolParam('kiemelt',false));
			$obj->setInaktiv($this->getBoolParam('inaktiv',false));
			$obj->setMozgat($this->getBoolParam('mozgat',true));
			$obj->setHparany($this->getFloatParam('hparany'));
			$obj->setSzelesseg($this->getFloatParam('szelesseg'));
			$obj->setMagassag($this->getFloatParam('magassag'));
			$obj->setHosszusag($this->getFloatParam('hosszusag'));
			$obj->setSuly($this->getFloatParam('suly'));
			$obj->setOsszehajthato($this->getBoolParam('osszehajthato'));
			$obj->setKepurl($this->getStringParam('kepurl',''));
			$obj->setKepleiras($this->getStringParam('kepleiras',''));
			$obj->setTermekexportbanszerepel($this->getBoolParam('termekexportbanszerepel',true));
			$obj->setNemkaphato($this->getBoolParam('nemkaphato',false));
			$farepo=store::getEm()->getRepository('Entities\TermekFa');
			$fa=$farepo->find($this->getIntParam('termekfa1'));
			if ($fa) {
				$obj->setTermekfa1($fa);
			}
			else {
				$obj->setTermekfa1(null);
			}
			$fa=$farepo->find($this->getIntParam('termekfa2'));
			if ($fa) {
				$obj->setTermekfa2($fa);
			}
			else {
				$obj->setTermekfa2(null);
			}
			$fa=$farepo->find($this->getIntParam('termekfa3'));
			if ($fa) {
				$obj->setTermekfa3($fa);
			}
			else {
				$obj->setTermekfa3(null);
			}
			$obj->removeAllCimke();
			$cimkekpar=$this->getArrayParam('cimkek');
			foreach($cimkekpar as $cimkekod) {
				$cimke=$this->getEm()->getRepository('Entities\Termekcimketorzs')->find($cimkekod);
				if ($cimke) {
					$obj->addCimke($cimke);
				}
			}
			$obj->setBrutto($this->getNumParam('brutto'));
			$obj->setNetto($this->getNumParam('netto'));
			$obj->setAkciosnetto($this->getNumParam('akciosnetto'));
			//$obj->setAkciosbrutto($this->getNumParam('akciosbrutto'));
			$obj->setAkciostart($this->getStringParam('akciostart'));
			$obj->setAkciostop($this->getStringParam('akciostop'));
			$kepids=$this->getArrayParam('kepid');
			foreach($kepids as $kepid) {
				if ($this->getStringParam('kepurl_'.$kepid,'')!=='') {
					$oper=$this->getStringParam('kepoper_'.$kepid);
					if ($oper=='add') {
						$kep=new \Entities\TermekKep();
						$obj->addTermekKep($kep);
						$kep->setUrl($this->getStringParam('kepurl_'.$kepid));
						$kep->setLeiras($this->getStringParam('kepleiras_'.$kepid));
						$this->getEm()->persist($kep);
					}
					elseif ($oper=='edit') {
						$kep=store::getEm()->getRepository('Entities\TermekKep')->find($kepid);
						if ($kep) {
							$kep->setUrl($this->getStringParam('kepurl_'.$kepid));
							$kep->setLeiras($this->getStringParam('kepleiras_'.$kepid));
							$this->getEm()->persist($kep);
						}
					}
				}
			}
			$kapcsolodoids=$this->getArrayParam('kapcsolodoid');
			foreach($kapcsolodoids as $kapcsolodoid) {
				if (($this->getIntParam('kapcsolodoaltermek_'.$kapcsolodoid)>0)) {
					$oper=$this->getStringParam('kapcsolodooper_'.$kapcsolodoid);
					$altermek=$this->getEm()->getRepository('Entities\Termek')->find($this->getIntParam('kapcsolodoaltermek_'.$kapcsolodoid));
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
				$receptids=$this->getArrayParam('receptid');
				foreach($receptids as $receptid) {
					if (($this->getIntParam('receptaltermek_'.$receptid)>0)) {
						$oper=$this->getStringParam('receptoper_'.$receptid);
						$altermek=$this->getEm()->getRepository('Entities\Termek')->find($this->getIntParam('receptaltermek_'.$receptid));
						if ($oper=='add') {
							$recept=new TermekRecept();
							$obj->addTermekRecept($recept);
							if ($altermek) {
								$recept->setAlTermek($altermek);
							}
							$recept->setMennyiseg($this->getFloatParam('receptmennyiseg_'.$receptid));
							$recept->setKotelezo($this->getBoolParam('receptkotelezo_'.$receptid));
							$this->getEm()->persist($recept);
						}
						elseif ($oper=='edit') {
							$recept=$this->getEm()->getRepository('Entities\TermekRecept')->find($receptid);
							if ($recept) {
								if ($altermek) {
									$recept->setAlTermek($altermek);
								}
								$recept->setMennyiseg($this->getFloatParam('receptmennyiseg_'.$receptid));
								$recept->setKotelezo($this->getBoolParam('receptkotelezo_'.$receptid));
								$this->getEm()->persist($recept);
							}
						}
					}
				}
			}
			if (store::getSetupValue('termekvaltozat')) {
				$valtozatids=$this->getArrayParam('valtozatid');
				foreach($valtozatids as $valtozatid) {
					$valtdb=0;
					$oper=$this->getStringParam('valtozatoper_'.$valtozatid);
					if ($oper=='add') {
						$valtozat=new TermekValtozat();
						$obj->addValtozat($valtozat);
						$valtozat->setLathato($this->getBoolParam('valtozatlathato_'.$valtozatid));
						if ($obj->getNemkaphato()) {
							$valtozat->setElerheto(false);
						}
						else {
							$valtozat->setElerheto($this->getBoolParam('valtozatelerheto_'.$valtozatid));
						}
//						$valtozat->setBrutto($this->getNumParam('valtozatbrutto_'.$valtozatid));
						$valtozat->setNetto($this->getNumParam('valtozatnetto_'.$valtozatid));
						$valtozat->setTermekfokep($this->getBoolParam('valtozattermekfokep_'.$valtozatid));

						$at=$this->getEm()->getRepository('Entities\TermekValtozatAdatTipus')->find($this->getIntParam('valtozatadattipus1_'.$valtozatid));
						$valtert=$this->getStringParam('valtozatertek1_'.$valtozatid);
						if ($at&&$valtert) {
							$valtozat->setAdatTipus1($at);
							$valtozat->setErtek1($valtert);
							$valtdb++;
						}

						$at=$this->getEm()->getRepository('Entities\TermekValtozatAdatTipus')->find($this->getIntParam('valtozatadattipus2_'.$valtozatid));
						$valtert=$this->getStringParam('valtozatertek2_'.$valtozatid);
						if ($at&&$valtert) {
							$valtozat->setAdatTipus2($at);
							$valtozat->setErtek2($valtert);
							$valtdb++;
						}

						$at=$this->getEm()->getRepository('Entities\TermekKep')->find($this->getIntParam('valtozatkepid_'.$valtozatid));
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
							$valtozat->setLathato($this->getBoolParam('valtozatlathato_'.$valtozatid));
							if ($obj->getNemkaphato()) {
								$valtozat->setElerheto(false);
							}
							else {
								$valtozat->setElerheto($this->getBoolParam('valtozatelerheto_'.$valtozatid));
							}
//							$valtozat->setBrutto($this->getNumParam('valtozatbrutto_'.$valtozatid));
							$valtozat->setNetto($this->getNumParam('valtozatnetto_'.$valtozatid));
							$valtozat->setTermekfokep($this->getBoolParam('valtozattermekfokep_'.$valtozatid));

							$at=$this->getEm()->getRepository('Entities\TermekValtozatAdatTipus')->find($this->getIntParam('valtozatadattipus1_'.$valtozatid));
							$valtert=$this->getStringParam('valtozatertek1_'.$valtozatid);
							if ($at&&$valtert) {
								$valtozat->setAdatTipus1($at);
								$valtozat->setErtek1($valtert);
							}
							else {
								$valtozat->setAdatTipus1(null);
								$valtozat->setErtek1(null);
							}

							$at=$this->getEm()->getRepository('Entities\TermekValtozatAdatTipus')->find($this->getIntParam('valtozatadattipus2_'.$valtozatid));
							$valtert=$this->getStringParam('valtozatertek2_'.$valtozatid);
							if ($at&&$valtert) {
								$valtozat->setAdatTipus2($at);
								$valtozat->setErtek2($valtert);
							}
							else {
								$valtozat->setAdatTipus2(null);
								$valtozat->setErtek2(null);
							}

							$at=$this->getEm()->getRepository('Entities\TermekKep')->find($this->getIntParam('valtozatkepid_'.$valtozatid));
							if ($at) {
								$valtozat->setKep($at);
							}

							$this->getEm()->persist($valtozat);
						}
					}
				}
			}
			$obj->doStuffOnPrePersist();  // ha csak kapcsolódó adat változott, akkor prepresist/preupdate nem hívódik, de cimke gyorsítás miatt nekünk kell
		}
		catch (matt\Exceptions\WrongValueTypeException $e){
		}
		return $obj;
	}

	protected function getlistbody() {
		$view=$this->createView('termeklista_tbody.tpl');

		$filter=array();
		if (!is_null($this->getParam('nevfilter',NULL))) {
			$filter['fields'][]=array('nev','rovidleiras','cikkszam');
			$filter['clauses'][]='';
			$filter['values'][]=$this->getStringParam('nevfilter');
		}

		if (!is_null($this->getParam('cimkefilter',NULL))) {
			$fv=$this->getArrayParam('cimkefilter');
			$res=Store::getEm()->getRepository('Entities\Termekcimketorzs')->getTermekIdsWithCimke($fv);
			$cimkefilter=array();
			foreach($res as $sor) {
				$cimkefilter[]=$sor['id'];
			}
			$filter['fields'][]='id';
			$filter['clauses'][]='';
			$filter['values'][]=$cimkefilter;
		}

		if (!is_null($this->getParam('fafilter',NULL))) {
			$fv=$this->getArrayParam('fafilter');
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

		$this->initPager(
			$this->getRepo()->getCount($filter),
			$this->getIntParam('elemperpage',30),
			$this->getIntParam('pageno',1));

		$egyedek=$this->getRepo()->getWithJoins(
			$filter,
			$this->getOrderArray(),
			$this->getPager()->getOffset(),
			$this->getPager()->getElemPerPage());

		echo json_encode($this->loadDataToView($egyedek,'termeklista',$view));
	}

	public function getSelectList($selid) {
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

	protected function htmllist() {
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

	protected function viewlist() {
		$view=$this->createView('termeklista.tpl');
		$view->setVar('pagetitle',t('Termékek'));
		$view->setVar('orderselect',$this->getRepo()->getOrdersForTpl());
		$view->setVar('batchesselect',$this->getRepo()->getBatchesForTpl());
		$tcc=new termekcimkekatController($this->generalDataLoader);
		$view->setVar('cimkekat',$tcc->getWithCimkek(null));
		$view->printTemplateResult();
	}

	protected function _getkarb($id,$oper,$tplname) {
		$view=$this->createView($tplname);
		$view->setVar('pagetitle',t('Termék'));
		$view->setVar('oper',$oper);

		$termek=$this->getRepo()->findWithJoins($id);
		// LoadVars utan nem abc sorrendben adja vissza
		$tcc=new termekcimkekatController($this->generalDataLoader);
		$cimkek=$termek?$termek->getAllCimkeId():null;
		$view->setVar('cimkekat',$tcc->getWithCimkek($cimkek));

		$view->setVar('termek',$this->loadVars($termek,true));
		$vtsz=new vtszController($this->generalDataLoader);
		$view->setVar('vtszlist',$vtsz->getSelectList(($termek?$termek->getVtszId():0)));
		$afa=new afaController($this->generalDataLoader);
		$view->setVar('afalist',$afa->getSelectList(($termek?$termek->getAfaId():0)));
		$valtozatadattipus=new termekvaltozatadattipusController($this->generalDataLoader);
		$view->setVar('valtozatadattipuslist',$valtozatadattipus->getSelectList(($termek?$termek->getValtozatadattipusId():0)));
		$kep=new termekkepController($this->generalDataLoader);
		$view->setVar('keplist',$kep->getSelectList($termek, null));
		$view->printTemplateResult();
	}

	protected function setflag() {
		$id=$this->getIntParam('id');
		$kibe=$this->getBoolParam('kibe');
		$flag=$this->getStringParam('flag');
		$obj=$this->getRepo()->find($id);
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

	protected function getBrutto() {
		$id=$this->getIntParam('id');
		$netto=$this->getFloatParam('value');
		$afa=$this->getEm()->getRepository('Entities\Afa')->find($this->getIntParam('afakod'));
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

	protected function getNetto() {
		$id=$this->getIntParam('id');
		$brutto=$this->getFloatParam('value');
		$afa=$this->getEm()->getRepository('Entities\Afa')->find($this->getIntParam('afakod'));
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
				$imageproc=new matt\Images($uploadfile);
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
		$tfc=new termekfaController($this->generalDataLoader);

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

	public function getfeedtermeklist() {
		return $this->getRepo()->getFeedTermek();
	}
}