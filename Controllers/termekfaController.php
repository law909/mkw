<?php

namespace Controllers;

use Doctrine\ORM\Query\ResultSetMapping;
use mkwhelpers\FilterDescriptor;

class termekfaController extends \mkwhelpers\MattableController {

	private $fatomb;

	public function __construct($params) {
		$this->setEntityName('Entities\TermekFa');
		$this->setKarbFormTplName('termekfakarbform.tpl');
		$this->setKarbTplName('termekfakarb.tpl');
		parent::__construct($params);
	}

	protected function loadVars($t, $forKarb = false) {
		$x = array();
		if (!$t) {
			$t = new \Entities\TermekFa();
			$this->getEm()->detach($t);
		}
		$x['id'] = $t->getId();
		$x['nev'] = $t->getNev();
		$x['sorrend'] = $t->getSorrend();
		$x['oldalcim'] = $t->getOldalcim();
		$x['rovidleiras'] = $t->getRovidleiras();
		$x['leiras'] = $t->getLeiras();
		$x['leiras2'] = $t->getLeiras2();
		$x['seodescription'] = $t->getSeodescription();
		$x['menu1lathato'] = $t->getMenu1lathato();
		$x['menu2lathato'] = $t->getMenu2lathato();
		$x['menu3lathato'] = $t->getMenu3lathato();
		$x['menu4lathato'] = $t->getMenu4lathato();
		$x['kepurl'] = $t->getKepurl();
		$x['kepurlsmall'] = $t->getKepurlSmall();
		$x['kepurlmedium'] = $t->getKepurlMedium();
		$x['kepurllarge'] = $t->getKepurlLarge();
		$x['kepleiras'] = $t->getKepleiras();
		$x['parentid'] = $t->getParentId();
		$x['parentnev'] = $t->getParentNev();
        $x['inaktiv'] = $t->getInaktiv();
        $x['idegenkod'] = $t->getIdegenkod();
        if (\mkw\store::isMultilang()) {
            $translationsCtrl = new termekfatranslationController($this->params);
            foreach($t->getTranslations() as $tr) {
                $translations[] = $translationsCtrl->loadVars($tr);
            }
            $x['translations'] = $translations;
        }
		return $x;
	}

	protected function setFields($obj) {
		$obj->setNev($this->params->getStringRequestParam('nev'));
		$obj->setOldalcim($this->params->getStringRequestParam('oldalcim'));
		$obj->setRovidleiras($this->params->getStringRequestParam('rovidleiras'));
		$obj->setLeiras($this->params->getOriginalStringRequestParam('leiras'));
		$obj->setLeiras2($this->params->getOriginalStringRequestParam('leiras2'));
		$obj->setSeodescription($this->params->getStringRequestParam('seodescription'));
        $obj->m1lchanged = $obj->getMenu1lathato() !== $this->params->getBoolRequestParam('menu1lathato');
        $obj->m2lchanged = $obj->getMenu2lathato() !== $this->params->getBoolRequestParam('menu2lathato');
        $obj->m3lchanged = $obj->getMenu3lathato() !== $this->params->getBoolRequestParam('menu3lathato');
        $obj->m4lchanged = $obj->getMenu4lathato() !== $this->params->getBoolRequestParam('menu4lathato');
		$obj->setMenu1lathato($this->params->getBoolRequestParam('menu1lathato'));
		$obj->setMenu2lathato($this->params->getBoolRequestParam('menu2lathato'));
		$obj->setMenu3lathato($this->params->getBoolRequestParam('menu3lathato'));
		$obj->setMenu4lathato($this->params->getBoolRequestParam('menu4lathato'));
		$obj->setKepurl($this->params->getStringRequestParam('kepurl'));
		$obj->setKepleiras($this->params->getStringRequestParam('kepleiras'));
		$obj->setSorrend($this->params->getIntRequestParam('sorrend'));
        $obj->setInaktiv($this->params->getBoolRequestParam('inaktiv'));
        if (\mkw\store::isMultilang()) {
            $translationids = $this->params->getArrayRequestParam('translationid');
            foreach ($translationids as $translationid) {
				$oper = $this->params->getStringRequestParam('translationoper_' . $translationid);
				if ($oper == 'add') {
					$translation = new \Entities\TermekFaTranslation();
                    $translation->setLocale($this->params->getStringRequestParam('translationlocale_' . $translationid));
                    $translation->setField('nev');
                    $translation->setContent($this->params->getStringRequestParam('translationnev_' . $translationid));
					$obj->addTranslation($translation);
					$this->getEm()->persist($translation);
				}
				elseif ($oper == 'edit') {
					$translation = $this->getEm()->getRepository('Entities\TermekFaTranslation')->find($translationid);
					if ($translation) {
                        $translation->setLocale($this->params->getStringRequestParam('translationlocale_' . $translationid));
                        $translation->setField('nev');
                        $translation->setContent($this->params->getStringRequestParam('translationnev_' . $translationid));
						$this->getEm()->persist($translation);
					}
				}
            }
        }
		$parent = $this->getRepo()->find($this->params->getIntRequestParam('parentid'));
		if ($parent) {
			$obj->setParent($parent);
		}
		return $obj;
	}

	public function viewlist() {
		$view = $this->createView('termekfalista.tpl');
		$view->setVar('pagetitle', t('Termék kategóriák'));
		$view->printTemplateResult();
	}

	public function jsonlist() {
		$elotag = $this->params->getStringRequestParam('pre');
		if (!$elotag) {
			$elotag = 'termekfa_';
		}
		$rsm = new ResultSetMapping();
		$rsm->addScalarResult('id', 'id');
		$rsm->addScalarResult('parent_id', 'parent_id');
		$rsm->addScalarResult('nev', 'nev');
		$rsm->addScalarResult('sorrend', 'sorrend');
		$q = $this->getEm()->createNativeQuery('SELECT id,parent_id,nev,sorrend FROM termekfa tf ORDER BY parent_id,sorrend,nev', $rsm);
		$this->fatomb = $q->getScalarResult();
		$retomb = array('data' => array('title' => $this->fatomb[0]['nev'], 'attr' => array('id' => $elotag . $this->fatomb[0]['id'])), 'children' => $this->bejar($this->fatomb[0]['id'], $elotag));
		echo json_encode($retomb);
	}

	private function bejar($szuloid, $elotag) {
		$ret = array();
		foreach ($this->fatomb as $key => $val) {
			if ($val['parent_id'] == $szuloid) {
				$ret[] = array('data' => array('title' => $val['nev'], 'attr' => array('id' => $elotag . $val['id'])), 'children' => $this->bejar($val['id'], $elotag));
			}
		}
		return $ret;
	}

	protected function _getkarb($tplname) {
		$id = $this->params->getRequestParam('id', 0);
		$oper = $this->params->getRequestParam('oper', '');
		$view = $this->createView($tplname);
		$view->setVar('pagetitle', t('Termék kategória'));
		$view->setVar('oper', $oper);
		$fa = $this->getRepo()->find($id);
		$fatomb = $this->loadVars($fa, true);
		if (!$fa) {
			$fatomb['parentid'] = $this->params->getIntRequestParam('parentid');
		}
		$view->setVar('fa', $fatomb);
		$view->printTemplateResult();
	}

	public function save() {
		$ret = $this->saveData();
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

	public function move() {
		$fa = $this->getRepo()->find($this->params->getIntRequestParam('eztid'));
		$ide = $this->getRepo()->find($this->params->getIntRequestParam('ideid'));
		if (($fa) && ($ide)) {
			$fa->removeParent();
			$fa->setParent($ide);
			$this->getEm()->persist($fa);
			$this->getEm()->flush();
//			$this->getRepo()->regenerateKarKod();
		}
	}

    public function regenerateSlug() {
        $this->getRepo()->regenerateSlug();
        echo 'OK';
    }

	public function isdeletable() {
		$fa = $this->getRepo()->find($this->params->getIntRequestParam('id'));
		if ($fa) {
			echo $fa->isDeletable() * 1;
		}
		else {
			echo '0';
		}
	}

    public function getformenu($menunum, $almenunum = 0) {
        switch (\mkw\store::getTheme()) {
            case 'mkwcansas':
                $repo = $this->getRepo();
                $f = $repo->getForMenu($menunum);
                $t = array();
                foreach ($f as $o) {
                    $o['kozepeskepurl'] = \mkw\store::createMediumImageUrl($o['kepurl']);
                    $o['kiskepurl'] = \mkw\store::createSmallImageUrl($o['kepurl']);
                    $o['kepurl'] = \mkw\store::createBigImageUrl($o['kepurl']);
                    if ($almenunum > 0) { // mkw lebegő menüje
                        $o['children'] = array();
                        $children = $repo->getForParent($o['id'], $almenunum);
                        foreach ($children as $child) {
                            $child['kozepeskepurl'] = \mkw\store::createMediumImageUrl($child['kepurl']);
                            $child['kiskepurl'] = \mkw\store::createSmallImageUrl($child['kepurl']);
                            $child['kepurl'] = \mkw\store::createBigImageUrl($child['kepurl']);
                            $chchildren = $repo->getForParent($child['id'], $almenunum);
                            $child['childcount'] = count($chchildren);
                            foreach ($chchildren as $chchild) {
                                $chchild['kozepeskepurl'] = \mkw\store::createMediumImageUrl($chchild['kepurl']);
                                $chchild['kiskepurl'] = \mkw\store::createSmallImageUrl($chchild['kepurl']);
                                $chchild['kepurl'] = \mkw\store::createBigImageUrl($chchild['kepurl']);
                                $chchild['childcount'] = 0;
                                $child['children'][] = $chchild;
                            }
                            $o['children'][] = $child;
                        }
                        $o['childcount'] = count($o['children']);
                    }
                    $t[] = $o;
                }
        		return $t;
            case 'superzone':
                $repo = $this->getRepo();
                $f = $repo->getForMenu($menunum);
                $x = array();
                foreach ($f as $o) {
                    $o['kozepeskepurl'] = \mkw\store::createMediumImageUrl($o['kepurl']);
                    $o['kiskepurl'] = \mkw\store::createSmallImageUrl($o['kepurl']);
                    $o['kepurl'] = \mkw\store::createBigImageUrl($o['kepurl']);
                    $o['children'] = $this->gettermeklistaforparent($o);
                    $x[] = $o;
                }
        		return $x;
        }
        return false;
	}

	public function getNavigator($parent, $elsourlkell = true) {
		$navi = array();
		if ($elsourlkell) {
			$navi[] = array('caption' => $parent->getNev(), 'url' => $parent->getSlug());
		}
		else {
			$navi[] = array('caption' => $parent->getNev(), 'url' => '');
		}
		$szulo = $parent->getParent();
		while ($szulo) {
			$navi[] = array('caption' => $szulo->getNev(), 'url' => $szulo->getSlug());
			$szulo = $szulo->getParent();
		}
		return array_reverse($navi);
	}

	public function getkatlista($parent) {
		$repo = $this->getRepo();
		$children = $repo->getForParent($parent->getId(), 4);
		$t = array();
		foreach ($children as $child) {
			$child['kozepeskepurl'] = \mkw\store::createMediumImageUrl($child['kepurl']);
			$child['kiskepurl'] = \mkw\store::createSmallImageUrl($child['kepurl']);
			$child['kepurl'] = \mkw\store::createBigImageUrl($child['kepurl']);
//			$chchildren=$repo->getForParent($child['id']);
//			$child['childcount']=count($chchildren);
			$child['childcount'] = $repo->getForParentCount($child['id']);
			$t[] = $child;
		}
		$ret = array(
			'children' => $t,
			'navigator' => $this->getNavigator($parent)
		);
		return $ret;
	}

	public function gettermeklistaforparent($parent, $caller = null) {
        switch (\mkw\store::getTheme()) {
            case 'mkwcansas':
                $kategoriafilter = new FilterDescriptor();
                $nativkategoriafilter = new FilterDescriptor();
                $arfilter = new FilterDescriptor();
                $termekidfilter = new FilterDescriptor();
                $akciosfilter = new FilterDescriptor();
                $ret = array();

                $tc = new termekController($this->params);
                $termekrepo = $tc->getRepo();
                $tck = new termekcimkekatController($this->params);

                $kiemelttermekdb = \mkw\store::getParameter(\mkw\consts::Kiemelttermekdb, 3);
                /**
                  'elemperpage'=>$this->params->getIntRequestParam('elemperpage',20),
                  'pageno'=>$this->params->getIntRequestParam('pageno',1),
                  'order'=>$this->params->getStringRequestParam('order','ardesc'),
                  'filter'=>$this->params->getStringRequestParam('filter',''),
                  'klikkeltcimkekatid'=>$this->params->getIntRequestParam('cimkekatid'),
                  'arfilter'=>$this->params->getStringRequestParam('arfilter',''),
                  'keresett'=>$this->params->getStringRequestParam('keresett',''),
                  'vt'=>$this->params->getIntRequestParam('vt',1)
                 */
                $elemperpage = $this->params->getIntRequestParam('elemperpage', \mkw\store::getParameter(\mkw\consts::Termeklistatermekdb, 30));

                $pageno = $this->params->getIntRequestParam('pageno', 1);
                $ord = $this->params->getStringRequestParam('order');
                $szurostr = $this->params->getStringRequestParam('filter');

                if ($caller === 'marka') {
                    $markatc = new termekcimkeController($this->params);
                    $marka = $markatc->getRepo()->findOneBySlug($this->params->getStringParam('slug'));
                    if ($marka) {
                        $szuroarr = array($szurostr, $marka->getTermekFilter());
                        $szurostr = implode(',', $szuroarr);
                    }
                }

                if ($this->params->getIntRequestParam('cimkekatid')) {
                    $klikkeltcimkekatid = $this->params->getIntRequestParam('cimkekatid', false);
                }
                $keresoszo = $this->params->getStringRequestParam('keresett');
                $arfiltertomb = explode(';', $this->params->getStringRequestParam('arfilter'));
                if (count($arfiltertomb) > 0) {
                    $minarfilter = $arfiltertomb[0] * 1;
                }
                else {
                    $minarfilter = 0;
                }
                if (count($arfiltertomb) > 1) {
                    $maxarfilter = $arfiltertomb[1] * 1;
                }
                else {
                    $maxarfilter = 0;
                }

                if ($minarfilter == $maxarfilter) {
                    $minarfilter = 0;
                    $maxarfilter = 0;
                }

                if ($parent) {
                    $kategoriafilter->addFilter(array('_xx.termekfa1', '_xx.termekfa2', '_xx.termekfa3'), '=', $parent->getId());
                    $nativkategoriafilter->addFilter(array('_xx.termekfa1_id', '_xx.termekfa2_id', '_xx.termekfa3_id'), '=', $parent->getId());
                }

                $keresofilter = new FilterDescriptor();
                if ($keresoszo) {
                    $keresofilter->addFilter(array('_xx.nev', '_xx.oldalcim', '_xx.cikkszam', '_xx.leiras'), 'LIKE', '%' . $keresoszo . '%');
                }

                if ($this->params->getBoolRequestParam('csakakcios', false)) {
                    $mastr = date(\mkw\store::$SQLDateFormat);
                    $akciosfilter->addSql('
                        (
                            (_xx.akciostart <> \'\' AND (_xx.akciostart IS NOT NULL)) OR (_xx.akciostop <> \'\' AND (_xx.akciostop IS NOT NULL))
                        ) AND 
                        (
                            (_xx.akciostart <= \'' . $mastr . '\' AND _xx.akciostop >= \'' . $mastr . '\') OR
                            (_xx.akciostart <= \'' . $mastr . '\' AND (_xx.akciostop = \'\' OR (_xx.akciostop IS NULL))) OR
                            ((_xx.akciostart = \'\' OR (_xx.akciostart IS NULL)) AND _xx.akciostop >= \'' . $mastr . '\')
                        )
                ');
                }
                $szurok = explode(',', $szurostr);
                $szurotomb = array();
                foreach ($szurok as $egyszuro) {
                    $egyreszei = explode('_', $egyszuro);
                    if (count($egyreszei) >= 3) {
                        $szurotomb[$egyreszei[1]][] = $egyreszei[2] * 1;
                    }
                }
                $termekidfiltered = array();
                if (count($szurotomb) > 0) {
                    $res = $this->getEm()->getRepository('Entities\Termekcimketorzs')->getTermekIdsWithCimkeAnd($szurotomb);
                    foreach ($res as $sor) {
                        $termekidfiltered[] = $sor['termek_id'];
                    }
                    if (count($termekidfiltered) > 0) {
                        $termekidfilter->addFilter('id', null, $termekidfiltered);
                    }
                    else {
                        $termekidfilter->addFilter('id', '=', false);
                    }
                }

                // termek max ar kategoriaval es cimkevel szurve
                $maxar = $termekrepo->getTermekListaMaxAr($keresofilter->merge($kategoriafilter)->merge($termekidfilter));

                if ($maxarfilter == 0 || \mkw\store::getMainSession()->autoepp) {
                    $maxarfilter = $maxar;
                }

                $arfilterstring = '(_xx.brutto+IF(v.brutto IS NULL,0,v.brutto)>=' . $minarfilter . ')';
                if ($maxarfilter > 0) {
                    $arfilterstring = $arfilterstring . ' AND (_xx.brutto+IF(v.brutto IS NULL,0,v.brutto)<=' . $maxarfilter . ')';
                }
                $arfilterstring = '((' . $arfilterstring . ') OR (_xx.brutto IS NULL))';
                $arfilter->addSql($arfilterstring);

                $termekdb = $termekrepo->getTermekListaCount($keresofilter->merge($kategoriafilter)->merge($termekidfilter)->merge($arfilter)->merge($akciosfilter));
                if ($termekdb > 0) {

                    // termekdarabszam kategoriaval es cimkevel es arral szurve
                    // lapozohoz kell
                    $tc->initPager(
                            $termekdb, $elemperpage, $pageno);
                    $pager = $tc->getPager();
                    $elemperpage = $pager->getElemPerPage();

                    switch ($ord) {
                        case 'nevasc':
                            $order = array('_xx.nev' => 'ASC');
                            break;
                        case 'nevdesc':
                            $order = array('_xx.nev' => 'DESC');
                            break;
                        case 'arasc':
                            $order = array('_xx.brutto' => 'ASC');
                            break;
                        case 'ardesc':
                            $order = array('_xx.brutto' => 'DESC');
                            break;
                        case 'idasc':
                            $order = array('_xx.id' => 'ASC');
                            break;
                        case 'iddesc':
                            $order = array('_xx.id' => 'DESC');
                            break;
                        default:
                            $order = array('_xx.nev' => 'ASC');
                            break;
                    }

                    $ujtermekminid = $termekrepo->getUjTermekId();
                    $top10min = $termekrepo->getTop10Mennyiseg();

                    // kiemelt termekek, kategoriaszures es kereses
                    $t = array();
                    $kiemelt = array();
                    $kiemeltdb = 0;
                    if (($kiemelttermekdb>0) && (($pageno == 1) || ($pager->getPageCount() == 1)) && ($caller !== 'szuro') && ($caller !=='marka')) {
                        $kiemelttermekek = $termekrepo->getKiemeltTermekek($keresofilter->merge($kategoriafilter), $kiemelttermekdb);
                        $kt = array();
                        foreach ($kiemelttermekek as $termek) {
                            $term = $termekrepo->find($termek['id']);
                            if ($termek['valtozatid']) {
                                $valt = $this->getEm()->getRepository('Entities\TermekValtozat')->find($termek['valtozatid']);
                            }
                            else {
                                $valt = null;
                            }
                            $tete = $term->toTermekLista($valt, $ujtermekminid, $top10min);
                            $tete['kiemelt'] = true;
                            $kiemelt[] = $tete;
                            $kiemeltdb++;
                        }
                    }
                    $ret['kiemelttermekek'] = $kiemelt;
                    // termekek kategoriaval es cimkevel es arral szurve, lapozva
                    // ez a konkret termeklista
                    $osszestermekid = array();
                    $termekek = $termekrepo->getTermekLista($keresofilter->merge($nativkategoriafilter)->merge($termekidfilter)->merge($arfilter)->merge($akciosfilter), $order, $pager->getOffset(), $elemperpage);
                    foreach ($termekek as $termek) {
                        $osszestermekid[] = $termek['id'];
                        $term = $termekrepo->find($termek['id']);
                        if ($termek['valtozatid']) {
                            $valt = $this->getEm()->getRepository('Entities\TermekValtozat')->find($termek['valtozatid']);
                        }
                        else {
                            $valt = null;
                        }
                        $tete = $term->toTermekLista($valt, $ujtermekminid, $top10min);
                        $tete['kiemelt'] = false;
                        $t[] = $tete;
                    }
                    if (($caller === 'marka')||($caller === 'szuro')) {
                        $osszeslapozatlantermekid = array();
                        $termekek = $termekrepo->getTermekLista($keresofilter->merge($nativkategoriafilter)->merge($termekidfilter)->merge($arfilter)->merge($akciosfilter), $order);
                        foreach ($termekek as $termek) {
                            $osszeslapozatlantermekid[] = $termek['id'];
                        }
                    }
                    // termek id-k csak kategoriaval es arral szurve
                    // a szuroben szereplo cimkek megallapitasahoz
                    $termekids = $termekrepo->getTermekIds($keresofilter->merge($kategoriafilter)->merge($arfilter)->merge($akciosfilter), $order);
                    $tid = array();
                    foreach ($termekids as $termek) {
                        $tid[] = $termek['id'];
                    }

                    $ret['arfilterstep'] = \mkw\store::getParameter(\mkw\consts::Arfilterstep, 500);
                    if (($maxar % $ret['arfilterstep']) != 0) {
                        $ret['maxar'] = (floor($maxar / $ret['arfilterstep']) + 1) * $ret['arfilterstep'];
                    }
                    else {
                        $ret['maxar'] = $maxar;
                    }
                    $ret['minarfilter'] = $minarfilter;
                    if (($maxarfilter % $ret['arfilterstep']) != 0) {
                        $ret['maxarfilter'] = (floor($maxarfilter / $ret['arfilterstep']) + 1) * $ret['arfilterstep'];
                    }
                    else {
                        $ret['maxarfilter'] = $maxarfilter;
                    }
                    switch ($caller) {
                        case 'termekfa':
                            $ret['url'] = '/termekfa/' . $parent->getSlug();
                            $ret['navigator'] = $this->getNavigator($parent);
                            // $tid = termek id-k csak kategoriaval es arral szurve
                            $ret['szurok'] = $tck->getForTermekSzuro($tid, $szurotomb);
                            break;
                        case 'kereses':
                            $ret['url'] = '/kereses';
                            $ret['navigator'] = array(array('caption' => t('A keresett kifejezés') . ': ' . $keresoszo));
                            // $tid = termek id-k csak kategoriaval es arral szurve
                            $ret['szurok'] = $tck->getForTermekSzuro($tid, $szurotomb);
                            break;
                        case 'szuro':
                            $ret['url'] = '/szuro';
                            $ret['navigator'] = array(array('caption' => t('Szűrő')));
                            $ret['szurok'] = $tck->getForTermekSzuro($osszeslapozatlantermekid, $szurotomb);
                            break;
                        case 'marka':
                            $ret['url'] = '/marka/' . $marka->getSlug();
                            $ret['navigator'] = array(array('caption' => $marka->getNev()));
                            $ret['szurok'] = $tck->getForTermekSzuro($osszeslapozatlantermekid, $szurotomb);
                    }
                    $ret['keresett'] = $keresoszo;
                    $ret['vt'] = ($this->params->getIntRequestParam('vt') > 0 ? $this->params->getIntRequestParam('vt') : 1);
                    $ret['csakakcios'] = $this->params->getBoolRequestParam('csakakcios', false);
                    $ret['termekek'] = $t;
                    $ret['lapozo'] = $pager->loadValues();
                    $ret['order'] = $ord;
                    if ($parent) {
                        $ret['kategoria'] = array(
                            'nev' => $parent->getNev(),
                            'leiras2' => $parent->getLeiras2()
                        );
                    }
                    else {
                        $ret['kategoria'] = array(
                            'nev' => '',
                            'leiras2' => ''
                        );
                    }
                }
                else {
                    $ret['lapozo'] = 0;
                }
                return $ret;

            case 'superzone':
                $termekrepo = $this->getEm()->getRepository('Entities\Termek');
                $order = array();
                $kategoriafilter = new FilterDescriptor();
                $nativkategoriafilter = new FilterDescriptor();
                if ($parent) {
                    $kategoriafilter->addFilter(array('_xx.termekfa1', '_xx.termekfa2', '_xx.termekfa3'), '=', $parent['id']);
                    $nativkategoriafilter->addFilter(array('_xx.termekfa1_id', '_xx.termekfa2_id', '_xx.termekfa3_id'), '=', $parent['id']);
                }
                $keresofilter = new FilterDescriptor();
                if ($this->params) {
                    $keresoszo = $this->params->getStringRequestParam('keresett');
                }
                if ($keresoszo) {
                    $keresofilter->addFilter(array('_xx.nev', '_xx.oldalcim', '_xx.cikkszam', '_xx.leiras'), 'LIKE', '%' . $keresoszo . '%');
                }
                $termekek = $termekrepo->getTermekLista($keresofilter->merge($kategoriafilter), array('_xx.cikkszam' => 'ASC'));
                $t = array();
                foreach ($termekek as $te) {
                    $tete = $te->toMenu();
                    $tete['kiemelt'] = false;
                    $t[] = $tete;
                }
                return $t;
            default :
                throw new Exception('ISMERETLEN THEME: ' . \mkw\store::getTheme());
        }
	}

    public function redirectOldUrl() {
        $faid = $this->params->getStringRequestParam('pcat_id');
        if ($faid) {
            $fa = $this->getRepo()->findOneById($faid);
            if ($fa) {
                $newlink = \mkw\store::getRouter()->generate('showtermekfa', false, array('slug' => $fa->getSlug()));
            }
            else {
                $newlink = \mkw\store::getRouter()->generate('show404');
            }
        }
        else {
            $newlink = \mkw\store::getRouter()->generate('show404');
        }
        header("HTTP/1.1 301 Moved Permanently");
        header('Location: ' . $newlink);
    }

}