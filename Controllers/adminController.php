<?php
namespace Controllers;
use mkw\ArCalculator;

use mkwhelpers, mkw\store;
use Entities;

class adminController extends mkwhelpers\Controller {

	private function checkForIE() {
		$u_agent=$_SERVER['HTTP_USER_AGENT'];
		$ub=false;
		if (preg_match('/MSIE/i',$u_agent)) {
			$view=$this->createView('noie.tpl');
			$this->generalDataLoader->loadData($view);
			$view->printTemplateResult();
			$ub=true;
		}
		return $ub;
	}

	private function createMindentkapniGyarto($tomb) {
		if (!$tomb['nev']) {
			return null;
		}
		$gy = store::getEm()->getRepository('Entities\Partner')->findByNev($tomb['nev']);
		$gyarto = false;
		if ($gy) {
			$gyarto = $gy[0];
		}
		if (!$gyarto) {
			$gyarto = new Entities\Partner();
			$gyarto->setAdoszam($tomb['adoszam']);
			$gyarto->setHonlap($tomb['honlap']);
			$gyarto->setIrszam($tomb['irszam']);
			$gyarto->setUtca($tomb['utca']);
			$gyarto->setNev($tomb['nev']);
			$gyarto->setTelefon($tomb['telefon']);
			store::getEm()->persist($gyarto);
		}
		return $gyarto;
	}

	private function createMindentkapniMarka($marka,$tomb) {
		$nev=$tomb[0];
		$filenev=$tomb[1];
		if (!$nev) {
			//$nev='Üres';
			return null;
		}
		$cimke1=store::getEm()->getRepository('Entities\Termekcimketorzs')->getByNevAndKategoria($nev,$marka);
		if (!$cimke1) {
			$cimke1=new Entities\Termekcimketorzs();
			$cimke1->setKategoria($marka);
			$cimke1->setNev($nev);
//			$cimke1->setKepurl($filenev);
			store::getEm()->persist($cimke1);
		}
		return $cimke1;
	}

	private function createMindentkapniJelzo($jelzo,$tomb) {
		$nev=$tomb[0];
		$filenev=$tomb[1];
		if (!$nev) {
			//$nev='Üres';
			return null;
		}
		$cimke1=store::getEm()->getRepository('Entities\Termekcimketorzs')->getByNevAndKategoria($nev,$jelzo);
		if (!$cimke1) {
			$cimke1=new Entities\Termekcimketorzs();
			$cimke1->setKategoria($jelzo);
			$cimke1->setNev($nev);
//			$cimke1->setKepurl($filenev);
			store::getEm()->persist($cimke1);
		}
		return $cimke1;
	}

	private function createMindentkapniTVAdatTipus($nev) {
		if (!$nev) {
			return null;
		}
		$at=store::getEm()->getRepository('Entities\TermekValtozatAdatTipus')->findOneBy(array('nev'=>$nev));
		if (!$at) {
			$at=new Entities\TermekValtozatAdatTipus();
			$at->setNev($nev);
			store::getEm()->persist($at);
		}
		return $at;
	}

	private function cartesian($input) {
		$result = array();
		while (list($key, $values) = each($input)) {
			if (empty($values)) {
				continue;
			}
			if (empty($result)) {
				foreach($values as $value) {
					$result[] = array($key => $value);
				}
			}
			else {
				$append = array();
				foreach($result as &$product) {
					$product[$key] = array_shift($values);
					$copy = $product;
					foreach($values as $item) {
						$copy[$key] = $item;
						$append[] = $copy;
					}
					array_unshift($values, $product[$key]);
				}
				$result = array_merge($result, $append);
				unset($append);
			}
		}
		return $result;
	}

	private function mindentkapnikep($ebbol) {
		$ebbe=$ebbol;
		$pp=pathinfo($ebbe);
		if (($pp['extension']=='gif')||($pp['extension']=='png')) {
			$ebbe=str_replace('.'.$pp['extension'], '.jpg', $ebbe);
			copy(str_replace('.'.$pp['extension'],'_800_600.jpg',$ebbol),$ebbe);
		}
		return $ebbe;
	}

	protected function writelog($mit) {
		$x=fopen('mkw.log','a');
		$mit .= "\n";
		fwrite($x,$mit);
		fclose($x);
	}

	public function mindentkapniimport() {

		if (file_exists('mkwimport.lock')) {
			echo 'locked';
			die;
		}

		$x = fopen('mkwimport.lock', 'a');
		fwrite($x, 'fuckerlocker');
		fclose($x);

		$record = file_get_contents('mkwrecord.txt');
		$record = $record * 1;

		$metomb=array();
		$import=fopen('rep_megyseg.csv','r');
		fgetcsv($import,0,';','"');
		while(($data=fgetcsv($import,0,';','"'))!==false) {
			$metomb[$data[0]]=mb_convert_encoding($data[5],'UTF8','ISO-8859-2');
		}
		fclose($import);

		$nemkellmarka = array(
			'Best Body Nutrition',
			'Bremshey',
			'Buff',
			'Columbia',
			'Copag',
			'Daum Electronic',
			'Esso',
			'EURO SuperCompact',
			'Ezekiel',
			'Finnlo',
			'Gold Game',
			'Hammer',
			'Horizon Fitness',
			'Kensho',
			'Kettler',
			'Lando',
			'Life Fitness',
			'Mammut Nutrition',
			'Maranello',
			'Marcy',
			'Millenium',
			'MOL',
			'MrRoy',
			'Omni Fitness',
			'Pro Energy',
			'Quixoft',
			'Rapid',
			'Rebel',
			'Reebok',
			'Robust',
			'Stamm Bodyfit',
			'TAMA',
			'Technogym',
			'Texaco',
			'Thor Steinar',
			'Tunturi',
			'Veneziana',
			'Victorinox',
			'Vision Fitness',
			'Vital Force'
		);
		$markatomb=array();
		$import=fopen('marka.csv','r');
		fgetcsv($import,0,';','"');
		while(($data=fgetcsv($import,0,';','"'))!==false) {
//			$kepneve=$this->mindentkapnikep(mb_convert_encoding($data[6],'UTF8','ISO-8859-2'));
			if (!in_array($data[5], $nemkellmarka)) {
				$kepneve='';
				$markatomb[$data[0]]=array(mb_convert_encoding($data[5],'UTF8','ISO-8859-2'),$kepneve);
			}
		}
		fclose($import);

		$nemkellgyarto = array(
			'Dynamol',
			'GLS',
			'Gold Line Hungary Kft',
			'Hifly Distribution Kft',
			'JELKÉPZŐ',
			'Kormos',
			'Kunerth Károlyné Marcsi',
			'Kunertné',
			'Lovely Shop',
			'Mráz Tamás',
			'Peti',
			'Skolik Ágnes',
			'Smart Direkt Hungária Kft',
			'Tüske Bt',
			'Vital Force'
		);
		$gyartotomb = array();
		$import = fopen('partner.csv', 'r');
		fgetcsv($import, 0, ';', '"');
		while(($data = fgetcsv($import, 0, ';', '"'))!== false) {
			if (!in_array(mb_convert_encoding($data[5], 'UTF8', 'ISO-8859-2'), $nemkellgyarto)) {
				$gyartotomb[$data[0]] = array(
					'nev' => mb_convert_encoding($data[5], 'UTF8', 'ISO-8859-2'),
					'irszam' => $data[9],
					'utca' => mb_convert_encoding($data[10], 'UTF8', 'ISO-8859-2'),
					'adoszam' => $data[6],
					'telefon' => $data[14],
					'honlap' => $data[17]
				);
			}
		}

		$cimkecsoporttomb=array();
		$import=fopen('kategoria_attributum.csv','r');
		fgetcsv($import,0,';','"');
		while(($data=fgetcsv($import,0,';','"'))!==false) {
			$ker=mb_convert_encoding($data[6],'UTF8','ISO-8859-2');
			$cimkecsoporttomb[$data[0]]=$ker;
/*			if (!array_key_exists($ker,$cimkecsoporttomb)) {
				$cimkecsoporttomb[$ker]=array();
			}
			$cimkecsoporttomb[$ker][]=$data[0];
*/		}
		fclose($import);

		$jelzolista=array();
		$import=fopen('jelzo.csv','r');
		fgetcsv($import,0,';','"');
		while(($data=fgetcsv($import,0,';','"'))!==false) {
//			$kepneve=$this->mindentkapnikep(mb_convert_encoding($data[6],'UTF8','ISO-8859-2'));
			$kepneve = '';
			$jelzolista[$data[0]]=array(mb_convert_encoding($data[5],'UTF8','ISO-8859-2'),$kepneve);
		}
		fclose($import);

		$jelzotomb=array();
		$import=fopen('termek_jelzo.csv','r');
		fgetcsv($import,0,';','"');
		while(($data=fgetcsv($import,0,';','"'))!==false) {
			$jelzotomb[$data[5]][]=$jelzolista[$data[6]];
		}
		fclose($import);

		$termekcimketomb=array();
		$import=fopen('termek_attributum.csv','r');
		fgetcsv($import,0,';','"');
		while(($data=fgetcsv($import,0,';','"'))!==false) {
			$adat='';
			if ($data[7]!=='') {
				$adat=$data[7];
			}
			if ($data[8]!=='') {
				$adat=$data[8];
			}
			if ($data[9]!=='') {
				$adat=$data[9];
			}
			if ($data[10]!=='') {
				$adat=$data[10];
			}
			if ($adat!=='') {
				$termekcimketomb[$data[5]][]=array($cimkecsoporttomb[$data[6]],mb_convert_encoding($adat,'UTF8','ISO-8859-2'));
			}
		}
		fclose($import);
		unset($cimkecsoporttomb);

/*		$keptomb=array();
		$import=fopen('termek_kep.csv','r');
		fgetcsv($import,0,';','"');
		while(($data=fgetcsv($import,0,';','"'))!==false) {
			$kepneve=$this->mindentkapnikep(mb_convert_encoding($data[7],'UTF8','ISO-8859-2'));
			$keptomb[$data[5]][]=array(mb_convert_encoding($data[6],'UTF8','ISO-8859-2'),$kepneve);
		}
		fclose($import);
*/
		$termekvertek=array();
		$import=fopen('termek_valtozat_ertek.csv','r');
		fgetcsv($import,0,';','"');
		while(($data=fgetcsv($import,0,';','"'))!==false) {
			$termekvertek[$data[5]][]=array(
				'nev'=>mb_convert_encoding($data[6],'UTF8','ISO-8859-2'),
				'elerheto'=>$data[7]*1
			);
		}
		fclose($import);

		$termekvaltozattomb=array();
		$import=fopen('termek_valtozat.csv','r');
		fgetcsv($import,0,';','"');
		while(($data=fgetcsv($import,0,';','"'))!==false) {
			$termekvaltozattomb[$data[6]][mb_convert_encoding($data[5],'UTF8','ISO-8859-2')]=$termekvertek[$data[0]];
		}
		fclose($import);

		unset($termekvertek);

		foreach($termekvaltozattomb as $vari) {
			foreach($vari as $k=>$v) {
				$this->createMindentkapniTVAdatTipus($k);
				store::getEm()->flush();
			}
		}

		$markakat=store::getEm()->getRepository('Entities\Termekcimkekat')->findOneBynev('Márkák');
		if (!$markakat) {
			$markakat=new Entities\Termekcimkekat();
			$markakat->setLathato(true);
			$markakat->setNev('Márkák');
			store::getEm()->persist($markakat);
		}
		$jelzokat=store::getEm()->getRepository('Entities\Termekcimkekat')->findOneBynev('Jelzők');
		if (!$jelzokat) {
			$jelzokat=new Entities\Termekcimkekat();
			$jelzokat->setLathato(true);
			$jelzokat->setNev('Jelzők');
			store::getEm()->persist($jelzokat);
		}
		$vtsz=store::getEm()->getRepository('Entities\Vtsz')->find(2);
		$afa=store::getEm()->getRepository('Entities\Afa')->find(3);
		$valuta=store::getEm()->getRepository('Entities\Valutanem')->find(1);

		$import=fopen('termek.csv','r');
		$termekcikl = 0;
		while ((($buffer = fgets($import, 4096)) != false) && ($termekcikl < $record)) {
			$termekcikl++;
		}
		$szam=$record;
		while( (($data = fgetcsv($import, 0, ';', '"')) !== false) && ($szam <= $record+400) ) {
			$szam++;
			if (array_key_exists($data[29], $markatomb) && array_key_exists($data[9], $gyartotomb)) {
				$termek=new Entities\Termek();
				$termek->setIdegenkod($data[0]);
				$kat=store::getEm()->getRepository('Entities\TermekFa')->find($data[5]);
				if ($kat) {
					$termek->setTermekfa1($kat);
				}
				$termek->setNev(mb_convert_encoding($data[6],'UTF8','ISO-8859-2'));
				$termek->setRovidleiras(mb_substr(mb_convert_encoding($data[7],'UTF8','ISO-8859-2'),0,255,'UTF-8'));
				$termek->setLeiras(mb_convert_encoding($data[8],'UTF8','ISO-8859-2'));
				$termek->setCikkszam(mb_convert_encoding($data[10],'UTF8','ISO-8859-2'));
	//			$kepneve=$this->mindentkapnikep(mb_convert_encoding($data[11],'UTF8','ISO-8859-2'));
	//			$termek->setKepurl($kepneve);
				$termek->setKiszereles($data[12]);
				$termek->setMe($metomb[$data[13]]);
				$termek->setSzelesseg($data[14]);
				$termek->setMagassag($data[15]);
				$termek->setHosszusag($data[16]);
				$termek->setOsszehajthato($data[17]);
				$termek->setSuly($data[18]);
				$termek->setHparany(ceil($data[19]/$data[28]*100));

				$termek->setAfa($afa);
				$termek->setAjanlott(false);
				$termek->setHozzaszolas(true);
				$termek->setInaktiv(false);
				$termek->setLathato(true);
				$termek->setMozgat(true);
				$termek->setVtsz($vtsz);

				$marka=$this->createMindentkapniMarka($markakat,$markatomb[$data[29]]);
				if ($marka) {
					$termek->addCimke($marka);
				}

				$gyarto = $this->createMindentkapniGyarto($gyartotomb[$data[9]]);
				if ($gyarto) {
					$termek->setGyarto($gyarto);
				}

				$termek->setBrutto($data[28]);

	/*
				if (array_key_exists($data[0],$keptomb)) {
					foreach($keptomb[$data[0]] as $kadat) {
						$tk=new Entities\TermekKep();
						$termek->addTermekKep($tk);
						$tk->setLeiras($kadat[0]);
						$tk->setUrl($kadat[1]);
						store::getEm()->persist($tk);
					}
				}
	*/
				if (array_key_exists($data[0],$jelzotomb)) {
					foreach($jelzotomb[$data[0]] as $kadat) {
						$jelzo=$this->createMindentkapniJelzo($jelzokat, $kadat);
						if ($jelzo) {
							$termek->addCimke($jelzo);
						}
					}
				}

				if (array_key_exists($data[0],$termekcimketomb)) {
					foreach($termekcimketomb[$data[0]] as $cadat) {
						$ckat=store::getEm()->getRepository('Entities\Termekcimkekat')->findOneBynev($cadat[0]);
						if (!$ckat) {
							$ckat=new Entities\Termekcimkekat();
							$ckat->setLathato(true);
							$ckat->setNev($cadat[0]);
							store::getEm()->persist($ckat);
						}
						$cimkex=store::getEm()->getRepository('Entities\Termekcimketorzs')->getByNevAndKategoria($cadat[1],$ckat);
						if (!$cimkex) {
							$cimkex=new Entities\Termekcimketorzs();
							$cimkex->setKategoria($ckat);
							$cimkex->setNev($cadat[1]);
							store::getEm()->persist($cimkex);
						}
						$termek->addCimke($cimkex);
					}
				}

				if (array_key_exists($data[0],$termekvaltozattomb)) {
//					$termek->setLathato(false);
					$valtozattomb=$this->cartesian($termekvaltozattomb[$data[0]]);
					foreach($valtozattomb as $vari) {
						//$valt=new Entities\TermekValtozat();
						//$valt->setTermek($termek);
						$cnt=0;
						$valt=new Entities\TermekValtozat();
						$valt->setTermek($termek);
						$valt->setLathato(false);
						$valt->setBrutto($data[28]);
//						$valt->setCikkszam(mb_convert_encoding($data[10],'UTF8','ISO-8859-2'));
						$elerheto = true;
						foreach($vari as $k=>$v) {
							if ($cnt==0) {
								$adatt=$this->createMindentkapniTVAdatTipus($k);
								$valt->setAdatTipus1($adatt);
								$valt->setErtek1($v['nev']);
								$elerheto = $elerheto && $v['elerheto'];
							}
							if ($cnt==1) {
								$adatt=$this->createMindentkapniTVAdatTipus($k);
								$valt->setAdatTipus2($adatt);
								$valt->setErtek2($v['nev']);
								$elerheto = $elerheto && $v['elerheto'];
							}
							$cnt++;
						}
						$valt->setElerheto($elerheto);
						store::getEm()->persist($valt);
					}
					unset($valtozattomb);
				}

				store::getEm()->persist($termek);
				store::getEm()->flush();
				unset($termek);
				$this->writelog($data[0]);
			}
		}
		fclose($import);
		$this->regeneratekarkod();
		file_put_contents('mkwrecord.txt', $szam);
		$this->writelog('KESZ');
		unlink('mkwimport.lock');
	}

	public function view() {
		$view=$this->createView('main.tpl');
		$this->generalDataLoader->loadData($view);
		$view->setVar('pagetitle',t('Főoldal'));
		$view->printTemplateResult();
	}

	public function regeneratekarkod() {
		$farepo=store::getEm()->getRepository('Entities\TermekFa');
		$farepo->regenerateKarKod();
		$this->view();
	}

	public function sanitize() {
		echo \mkwhelpers\Filter::toPermalink($this->params->getStringRequestParam('text',''));
	}

	protected function cropimage() {
		$view=$this->createView('cropimage.tpl');
		$this->generalDataLoader->loadData($view);
		$view->setVar('pagetitle',t('Főoldal'));
		$view->printTemplateResult();
	}

	public function setUITheme() {
		store::setParameter('uitheme',$this->params->getStringRequestParam('uitheme','sunny'));
	}

	public function setGridEditButton() {
		store::setParameter('grideditbutton',$this->params->getStringRequestParam('grideditbutton','small'));
	}

	public function setEditStyle() {
		store::setParameter('editstyle',$this->params->getStringRequestParam('editstyle','dropdown'));
	}

	public function getSmallUrl() {
		echo \mkw\Store::createSmallImageUrl($this->params->getStringRequestParam('url'));
	}

	protected function arcalctest() {
		$t=store::getEm()->getRepository('Entities\Termek')->find(7);
		$v=store::getEm()->getRepository('Entities\Valutanem')->find(1);
		$p=store::getEm()->getRepository('Entities\Partner')->find(1);
		$ac=new ArCalculator($v,$p,$t);
		echo $ac->getPartnerAr();
	}

	protected function elfinder() {
		require_once 'elfinder/elFinderConnector.class.php';
		require_once 'elfinder/elFinder.class.php';
		require_once 'elfinder/elFinderVolumeDriver.class.php';
		require_once 'elfinder/elFinderVolumeLocalFileSystem.class.php';

		function access($attr, $path, $data, $volume) {
			return strpos(basename($path), '.') === 0       // if file/folder begins with '.' (dot)
				? !($attr == 'read' || $attr == 'write')    // set read+write to false, other (locked+hidden) set to true
				:  null;                                    // else elFinder decide it itself
		}
		$opts = array(
			'debug' => true,
			'roots' => array(
				array(
					'driver'        => 'LocalFileSystem',   // driver for accessing file system (REQUIRED)
					'path'          => '/var/www/mkw/termek',         // path to files (REQUIRED)
					'URL'           => dirname($_SERVER['PHP_SELF']) . '/termek', // URL to files (REQUIRED)
					'accessControl' => 'access',             // disable and hide dot starting files (OPTIONAL)
					'uploadAllow'	=> array('image'),
					'tmbPath'		=> 'tmb',
					'tmbUrl'		=> '/tmb',
					'attributes'	=> array(
						array(
							'pattern'=>'/\.php$/',
							'read'=>false,
							'write'=>false,
							'hidden'=>true,
							'locked'=>true
						)
					)
				)
			)
		);

		// run elFinder
		$connector = new \elFinderConnector(new \elFinder($opts));
		$connector->run();
	}
}