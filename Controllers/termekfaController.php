<?php
namespace Controllers;

use Doctrine\ORM\Query\ResultSetMapping;
use matt, matt\Exceptions, SIIKerES\store;

class termekfaController extends matt\MattableController {

	private $fatomb;

	public function __construct($generalDataLoader,$actionName=null,$commandString=null) {
		$this->setEntityName('Entities\TermekFa');
		$this->setEm(store::getEm());
		$this->setTemplateFactory(store::getTemplateFactory());
		$this->setKarbFormTplName('termekfakarbform.tpl');
		$this->setKarbTplName('termekfakarb.tpl');
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
		$x=array();
		if (!$t) {
			$t=new \Entities\TermekFa();
			$this->getEm()->detach($t);
		}
		$x['id']=$t->getId();
		$x['nev']=$t->getNev();
		$x['sorrend']=$t->getSorrend();
		$x['oldalcim']=$t->getOldalcim();
		$x['rovidleiras']=$t->getRovidleiras();
		$x['leiras']=$t->getLeiras();
		$x['seodescription']=$t->getSeodescription();
		$x['seokeywords']=$t->getSeokeywords();
		$x['menu1lathato']=$t->getMenu1lathato();
		$x['menu2lathato']=$t->getMenu2lathato();
		$x['menu3lathato']=$t->getMenu3lathato();
		$x['menu4lathato']=$t->getMenu4lathato();
		$x['kepurl']=$t->getKepurl();
		$x['kepurlsmall']=$t->getKepurlSmall();
		$x['kepurlmedium']=$t->getKepurlMedium();
		$x['kepurllarge']=$t->getKepurlLarge();
		$x['kepleiras']=$t->getKepleiras();
		$x['parentid']=$t->getParentId();
		$x['parentnev']=$t->getParentNev();
		return $x;
	}

	protected function setFields($obj) {
		try {
			$obj->setNev($this->getStringParam('nev'));
			$obj->setOldalcim($this->getStringParam('oldalcim'));
			$obj->setRovidleiras($this->getStringParam('rovidleiras'));
			$obj->setLeiras($this->getStringParam('leiras'));
			$obj->setSeodescription($this->getStringParam('seodescription'));
			$obj->setSeokeywords($this->getStringParam('seokeywords'));
			$obj->setMenu1lathato($this->getBoolParam('menu1lathato'));
			$obj->setMenu2lathato($this->getBoolParam('menu2lathato'));
			$obj->setMenu3lathato($this->getBoolParam('menu3lathato'));
			$obj->setMenu4lathato($this->getBoolParam('menu4lathato'));
			$obj->setKepurl($this->getStringParam('kepurl'));
			$obj->setKepleiras($this->getStringParam('kepleiras'));
			$obj->setSorrend($this->getIntParam('sorrend'));
			$parent=$this->getRepo()->find($this->getIntParam('parentid'));
			if ($parent) {
				$obj->setParent($parent);
			}
		}
		catch (matt\Exceptions\WrongValueTypeException $e){
		}
		return $obj;
	}

	protected function viewlist() {
		$view=$this->createView('termekfalista.tpl');
		$view->setVar('pagetitle',t('Termék kategóriák'));
		$view->printTemplateResult();
	}

	public function jsonlist() {
		$elotag=$this->getStringParam('pre');
		if (!$elotag) {
			$elotag='termekfa_';
		}
		$rsm=new ResultSetMapping();
		$rsm->addScalarResult('id', 'id');
		$rsm->addScalarResult('parent_id','parent_id');
		$rsm->addScalarResult('nev','nev');
		$rsm->addScalarResult('sorrend','sorrend');
		$q=$this->getEm()->createNativeQuery('SELECT id,parent_id,nev,sorrend FROM termekfa ORDER BY parent_id,sorrend,nev',$rsm);
		$this->fatomb=$q->getScalarResult();
		$retomb=array('data'=>array('title'=>$this->fatomb[0]['nev'],'attr'=>array('id'=>$elotag.$this->fatomb[0]['id'])),'children'=>$this->bejar($this->fatomb[0]['id'],$elotag));
		echo json_encode($retomb);
	}

	private function bejar($szuloid,$elotag) {
		$ret=array();
		foreach($this->fatomb as $key=>$val) {
			if ($val['parent_id']==$szuloid) {
				$ret[]=array('data'=>array('title'=>$val['nev'],'attr'=>array('id'=>$elotag.$val['id'])),'children'=>$this->bejar($val['id'],$elotag));
			}
		}
		return $ret;
	}

	protected function _getkarb($id,$oper,$tplname) {
		$view=$this->createView($tplname);
		$view->setVar('pagetitle',t('Termék kategória'));
		$view->setVar('oper',$oper);
		$fa=$this->getRepo()->find($id);
		$fatomb=$this->loadVars($fa,true);
		if (!$fa) {
			$fatomb['parentid']=$this->getIntParam('parentid');
		}
		$view->setVar('fa',$fatomb);
		$view->printTemplateResult();
	}

	protected function save() {
		$ret=$this->saveData();
		// TODO ettol faszom lassu a mentes
//		$this->getRepo()->regenerateKarKod();
		switch ($ret['operation']) {
			case $this->addOperation:
			case $this->editOperation:
				echo json_encode($ret['operation']);
				break;
			case $this->delOperation:
				echo $ret['id'];
				break;
		}
	}

	protected function move() {
		$fa=$this->getRepo()->find($this->getIntParam('eztid'));
		$ide=$this->getRepo()->find($this->getIntParam('ideid'));
		if (($fa)&&($ide)) {
			$fa->removeParent();
			$fa->setParent($ide);
			$this->getEm()->persist($fa);
			$this->getEm()->flush();
//			$this->getRepo()->regenerateKarKod();
		}
	}

	protected function isdeletable() {
		$fa=$this->getRepo()->find($this->getIntParam('id'));
		if ($fa) {
			echo $fa->isDeletable()*1;
		}
		else {
			echo '0';
		}
	}

/*	protected function savepicture() {
		$fa=$this->getRepo()->find($this->getIntParam('id'));
		if ($fa) {
			$uploaddir=store::getConfigValue('path.kategoriakep');
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
				$fa->setKepnev($this->getStringParam('nev'));
				$fa->setKepleiras($this->getStringParam('leiras'));
				$fa->setKepurl($uploadfile);
				$this->getEm()->persist($fa);
				$this->getEm()->flush();
//				$resp=array('kepurl'=>'/'.$largefn,'kepurlsmall'=>'/'.$smallfn,'kepleiras'=>$this->getStringParam('leiras'));
//				echo json_encode($resp);
				$view=$this->createView('termekfaimagekarb.tpl');
				$view->setVar('oper','edit');
				$view->setVar('fa',$this->loadVars($fa));
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
			$view=$this->createView('termekfaimagekarb.tpl');
			$view->setVar('oper','edit');
			$view->printTemplateResult();
		}
	}
*/
	public function getformenu($menunum,$almenunum=0) {
		$repo=$this->getRepo();
		$f=$repo->getForMenu($menunum);
		$t=array();
		foreach($f as $o){
			$o['kozepeskepurl']=store::createMediumImageUrl($o['kepurl']);
			$o['kiskepurl']=store::createSmallImageUrl($o['kepurl']);
			$o['kepurl']=store::createBigImageUrl($o['kepurl']);
			if ($almenunum>0) { // mkw lebegő menüje
				$o['children']=array();
				$children=$repo->getForParent($o['id'],$almenunum);
				foreach ($children as $child) {
					$child['kozepeskepurl']=store::createMediumImageUrl($child['kepurl']);
					$child['kiskepurl']=store::createSmallImageUrl($child['kepurl']);
					$child['kepurl']=store::createBigImageUrl($child['kepurl']);
					$chchildren=$repo->getForParent($child['id'],$almenunum);
					$child['childcount']=count($chchildren);
					foreach ($chchildren as $chchild) {
						$chchild['kozepeskepurl']=store::createMediumImageUrl($chchild['kepurl']);
						$chchild['kiskepurl']=store::createSmallImageUrl($chchild['kepurl']);
						$chchild['kepurl']=store::createBigImageUrl($chchild['kepurl']);
						$chchild['childcount']=0;
						$child['children'][]=$chchild;
					}
					$o['children'][]=$child;
				}
				$o['childcount']=count($o['children']);
			}
			$t[]=$o;
		}
		return $t;
	}

	public function getNavigator($parent,$elsourlkell=false) {
		$navi=array();
		if ($elsourlkell) {
			$navi[]=array('caption'=>$parent->getNev(),'url'=>$parent->getSlug());
		}
		else {
			$navi[]=array('caption'=>$parent->getNev(),'url'=>'');
		}
		$szulo=$parent->getParent();
		while ($szulo) {
			$navi[]=array('caption'=>$szulo->getNev(),'url'=>$szulo->getSlug());
			$szulo=$szulo->getParent();
		}
		return array_reverse($navi);
	}

	public function getkatlista($parent) {
		$repo=$this->getRepo();
		$children=$repo->getForParent($parent->getId(),4);
		$t=array();
		foreach($children as $child) {
			$child['kozepeskepurl']=store::createMediumImageUrl($child['kepurl']);
			$child['kiskepurl']=store::createSmallImageUrl($child['kepurl']);
			$child['kepurl']=store::createBigImageUrl($child['kepurl']);
//			$chchildren=$repo->getForParent($child['id']);
//			$child['childcount']=count($chchildren);
			$child['childcount']=$repo->getForParentCount($child['id']);
			$t[]=$child;
		}
		$ret=array(
			'children'=>$t,
			'navigator'=>$this->getNavigator($parent)
		);
		return $ret;
	}

	public function gettermeklistaforparent($parent,$params) {
		$kategoriafilter=array();
		$arfilter=array();
		$termekidfilter=array();
		$ret=array();

		$tc=new termekController($this->generalDataLoader);
		$termekrepo=$tc->getRepo();
		$tck=new termekcimkekatController($this->generalDataLoader);

		$kiemelttermekdb=store::getParameter('kiemelttermekdb',3);

		$elemperpage=$params['elemperpage'];
		$pageno=$params['pageno'];
		$ord=$params['order'];
		$szurostr=$params['filter'];
		if (array_key_exists('cimkekatid',$params)) {
			$klikkeltcimkekatid=$params['cimkekatid'];
		}
		$keresoszo=$params['keresett'];
		$arfiltertomb=explode(';',$params['arfilter']);
		if (count($arfiltertomb)>0) {
			$minarfilter=$arfiltertomb[0]*1;
		}
		else {
			$minarfilter=0;
		}
		if (count($arfiltertomb)>1) {
			$maxarfilter=$arfiltertomb[1]*1;
		}
		else {
			$maxarfilter=0;
		}

		if ($minarfilter==$maxarfilter) {
			$minarfilter=0;
			$maxarfilter=0;
		}

		if ($parent) {
			$kategoriafilter['fields'][]=array('_xx.termekfa1','_xx.termekfa2','_xx.termekfa3');
			$kategoriafilter['clauses'][]='=';
			$kategoriafilter['values'][]=$parent->getId();
		}

		$kategoriafilter['fields'][]='_xx.inaktiv';
		$kategoriafilter['clauses'][]='=';
		$kategoriafilter['values'][]=0;

		$kategoriafilter['fields'][]='_xx.lathato';
		$kategoriafilter['clauses'][]='=';
		$kategoriafilter['values'][]=1;

		$keresofilter=array();
		if ($keresoszo) {
			$keresofilter['fields'][]=array('_xx.nev','_xx.oldalcim','_xx.seokeywords','_xx.cikkszam','_xx.leiras');
			$keresofilter['clauses'][]='LIKE';
			$keresofilter['values'][]='%'.$keresoszo.'%';
		}

		$arfilterstring='(_xx.brutto+IF(v.brutto IS NULL,0,v.brutto)>='.$minarfilter.')';
		if ($maxarfilter>0) {
			$arfilterstring=$arfilterstring.' AND (_xx.brutto+IF(v.brutto IS NULL,0,v.brutto)<='.$maxarfilter.')';
		}
		$arfilterstring='(('.$arfilterstring.') OR (_xx.brutto IS NULL))';
		$arfilter['sql'][]=$arfilterstring;

		$szurok=explode(',',$szurostr);
		$szurotomb=array();
		foreach($szurok as $egyszuro) {
			$egyreszei=explode('_',$egyszuro);
			if (count($egyreszei)>=3) {
				$szurotomb[$egyreszei[1]][]=$egyreszei[2]*1;
			}
		}
		$termekidfiltered=array();
		if (count($szurotomb)>0) {
			$res=$this->getEm()->getRepository('Entities\Termekcimketorzs')->getTermekIdsWithCimkeAnd($szurotomb);
			foreach($res as $sor) {
				$termekidfiltered[]=$sor['termek_id'];
			}
			if (count($termekidfiltered)>0) {
				$termekidfilter['fields'][]='id';
				$termekidfilter['clauses'][]='';
				$termekidfilter['values'][]=$termekidfiltered;
			}
			else {
				$termekidfilter['fields'][]='id';
				$termekidfilter['clauses'][]='=';
				$termekidfilter['values'][]='false';
			}
		}

		$termekdb=$termekrepo->getTermekListaCount(array_merge_recursive($keresofilter,$kategoriafilter,$termekidfilter,$arfilter));
		if ($termekdb>0) {

			// termek max ar kategoriaval es cimkevel szurve
			$maxar=$termekrepo->getTermekListaMaxAr(array_merge_recursive($keresofilter,$kategoriafilter,$termekidfilter));

			if ($maxarfilter==0) {
				$maxarfilter=$maxar;
			}

			// termekdarabszam kategoriaval es cimkevel es arral szurve
			// lapozohoz kell
			$tc->initPager(
				$termekdb,
				$elemperpage,
				$pageno);
			$pager=$tc->getPager();

			switch ($ord) {
				case 'nevasc':
					$order=array('_xx.nev'=>'ASC');
					break;
				case 'nevdesc':
					$order=array('_xx.nev'=>'DESC');
					break;
				case 'arasc':
					$order=array('_xx.brutto'=>'ASC');
					break;
				case 'ardesc':
					$order=array('_xx.brutto'=>'DESC');
					break;
				case 'idasc':
					$order=array('_xx.id'=>'ASC');
					break;
				case 'iddesc':
					$order=array('_xx.id'=>'DESC');
					break;
			}
			// termekek kategoriaval es cimkevel es arral szurve, lapozva
			// ez a konkret termeklista
			$termekek=$termekrepo->getTermekLista(array_merge_recursive($keresofilter,$kategoriafilter,$termekidfilter,$arfilter),$order,$pager->getOffset(),$elemperpage);
			$t=array();
			foreach($termekek as $termek) {
				$term=$termekrepo->find($termek['id']);
				if ($termek['valtozatid']) {
					$valt=$this->getEm()->getRepository('Entities\TermekValtozat')->find($termek['valtozatid']);
				}
				else {
					$valt=null;
				}
				$t[]=$term->toTermekLista($valt);
			}
			// kiemelt termekek, kategoriaszures es kereses
			$kiemelttermekek=$termekrepo->getKiemeltTermekek(array_merge_recursive($keresofilter,$kategoriafilter),$kiemelttermekdb);
			$kt=array();
			foreach($kiemelttermekek as $termek) {
				$kt[]=$termek->toKiemeltLista();
			}
			// termek id-k csak kategoriaval es arral szurve
			// a szuroben szereplo cimkek megallapitasahoz
			$termekids=$termekrepo->getTermekIds(array_merge_recursive($keresofilter,$kategoriafilter,$arfilter),$order);
			$tid=array();
			foreach($termekids as $termek) {
				$tid[]=$termek['id'];
			}

			$ret['maxar']=$maxar;
			$ret['arfilterstep']=store::getParameter('arfilterstep',500);
			$ret['minarfilter']=$minarfilter;
			$ret['maxarfilter']=(floor($maxarfilter/$ret['arfilterstep'])+1)*$ret['arfilterstep'];
			if ($parent) {
				$ret['url']='/termekfa/'.$parent->getSlug();
				$ret['navigator']=$this->getNavigator($parent);
			}
			else {
				$ret['url']='/kereses/';
				$ret['navigator']=array(array('caption'=>t('A keresett kifejezés').': '.$keresoszo));
			}
			$ret['keresett']=$keresoszo;
			$ret['vt']=($params['vt']>0?$params['vt']:1);
			$ret['termekek']=$t;
			$ret['kiemelttermekek']=$kt;
			// $tid = termek id-k csak kategoriaval es arral szurve
			$ret['szurok']=$tck->getForTermekSzuro($tid,$szurotomb,$termekidfiltered);
			$ret['lapozo']=$pager->loadValues();
			$ret['order']=$ord;
		}
		else {
			$ret['lapozo']=0;
		}
		return $ret;
	}
}