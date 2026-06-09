<?php

namespace Controllers;

use Doctrine\ORM\Query\ResultSetMapping;
use Entities\Blogposzt;
use Entities\Partnercimketorzs;
use Entities\Termek;
use Entities\Termekcimketorzs;
use Entities\TermekFa;
use Entities\TermekValtozat;
use mkw\store;
use mkwhelpers\FilterDescriptor;
use Traits\PublicTermekLista;

class termekfaController extends \mkwhelpers\MattableController
{

    use PublicTermekLista;

    private $fatomb;

    public function __construct()
    {
        $this->setEntityName(TermekFa::class);
        $this->setKarbFormTplName('termekfakarbform.tpl');
        $this->setKarbTplName('termekfakarb.tpl');
        parent::__construct();
    }

    /**
     * @param \Entities\TermekFa $t
     * @param bool $forKarb
     *
     * @return array
     */
    protected function loadVars($t, $forKarb = false)
    {
        $x = [];
        if (!$t) {
            $t = new \Entities\TermekFa();
            $this->getEm()->detach($t);
        }
        $x = $this->getEntityFieldsArray($t);
        $x['nev_locale'] = $t->getLocalizedFieldValue('nev');
        $x['rovidleiras_locale'] = $t->getLocalizedFieldValue('rovidleiras');
        $x['leiras_locale'] = $t->getLocalizedFieldValue('leiras');
        $x['leiras2_locale'] = $t->getLocalizedFieldValue('leiras2');
        $x['leiras3_locale'] = $t->getLocalizedFieldValue('leiras3');

        $x['kepurlsmall'] = $t->getKepurlSmall();
        $x['kepurlmedium'] = $t->getKepurlMedium();
        $x['kepurllarge'] = $t->getKepurlLarge();

        $x['parentid'] = $t->getParentId();
        $x['parentnev'] = $t->getParentNev();
        $x['parentnev_locale'] = $t->getParentNevLocale();
        $x['path'] = implode('/', $t->getPath($t));
        return $x;
    }

    /**
     * @param \Entities\TermekFa $obj
     *
     * @return mixed
     */
    protected function setFields($obj)
    {
        $this->setEntityFieldsFromRequest($obj, [
            'raw' => ['leiras', 'leiras2', 'leiras3', 'leiras_l1', 'leiras2_l1', 'leiras3_l1'],
        ]);

        $parent = $this->getRepo()->find($this->params->getIntRequestParam('parentid'));
        if ($parent) {
            $obj->setParent($parent);
        }
        return $obj;
    }

    public function viewlist()
    {
        $view = $this->createView('termekfalista.tpl');
        $view->setVar('pagetitle', t('Termék kategóriák'));
        $view->printTemplateResult();
    }

    public function jsonlist()
    {
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
        $retomb = [
            'data' => ['title' => $this->fatomb[0]['nev'], 'attr' => ['id' => $elotag . $this->fatomb[0]['id']]],
            'children' => $this->bejar($this->fatomb[0]['id'], $elotag)
        ];
        echo json_encode($retomb);
    }

    private function bejar($szuloid, $elotag)
    {
        $ret = [];
        foreach ($this->fatomb as $key => $val) {
            if ($val['parent_id'] == $szuloid) {
                $ret[] = ['data' => ['title' => $val['nev'], 'attr' => ['id' => $elotag . $val['id']]], 'children' => $this->bejar($val['id'], $elotag)];
            }
        }
        return $ret;
    }

    protected function _getkarb($tplname)
    {
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
        $view->setVar('egyed', $fatomb);
        $view->printTemplateResult();
    }

    public function save()
    {
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

    public function move()
    {
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

    public function regenerateSlug()
    {
        $this->getRepo()->regenerateSlug();
        echo 'OK';
    }

    public function isdeletable()
    {
        $fa = $this->getRepo()->find($this->params->getIntRequestParam('id'));
        if ($fa) {
            echo $fa->isDeletable() * 1;
        } else {
            echo '0';
        }
    }

    public function getformenu($menunum, $almenunum = 0)
    {
        switch (true) {
            case \mkw\store::isMindentkapni():
                $repo = $this->getRepo();
                $f = $repo->getForMenu($menunum);
                $t = [];
                foreach ($f as $o) {
                    $o['kozepeskepurl'] = \mkw\store::createMediumImageUrl($o['kepurl']);
                    $o['kiskepurl'] = \mkw\store::createSmallImageUrl($o['kepurl']);
                    $o['kepurl'] = \mkw\store::createBigImageUrl($o['kepurl']);
                    if ($almenunum > 0) { // mkw lebegő menüje
                        $o['children'] = [];
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
            case \mkw\store::isMugenrace2026():
            case \mkw\store::isMugenrace():
                $repo = $this->getRepo();
                $f = $repo->getForMenu($menunum, \mkw\store::getWebshopNum());
                $t = [];
                foreach ($f as $o) {
                    $o['kozepeskepurl'] = \mkw\store::createMediumImageUrl($o['kepurl']);
                    $o['kiskepurl'] = \mkw\store::createSmallImageUrl($o['kepurl']);
                    $o['kepurl'] = \mkw\store::createBigImageUrl($o['kepurl']);
                    if ($almenunum > 0) { // mkw lebegő menüje
                        $o['children'] = [];
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
            case \mkw\store::isSuperzoneB2B():
                $kulfoldi = false;
                $partner = \mkw\store::getLoggedInUser();
                if ($partner) {
                    $cimkek = $partner->getCimkek();
                    // TODO b2b beegetve ideiglenesen: kulf.nagyker, spanyol ugynok, ugynok
                    $cimke1 = $this->getRepo(Partnercimketorzs::class)->find(2);
                    $cimke2 = $this->getRepo(Partnercimketorzs::class)->find(14);
                    $cimke3 = $this->getRepo(Partnercimketorzs::class)->find(32);
                    $kulfoldi = $cimkek->contains($cimke1) || $cimkek->contains($cimke2) || $cimkek->contains($cimke3);
                }
                $repo = $this->getRepo();
                $f = $repo->getForMenu($menunum, \mkw\store::getWebshopNum());
                $x = [];
                foreach ($f as $o) {
                    // TODO
                    if (!$kulfoldi || ($kulfoldi && (strncmp($o['karkod'], '0000100032', strlen('0000100032')) === 0))) {
                        $o['kozepeskepurl'] = \mkw\store::createMediumImageUrl($o['kepurl']);
                        $o['kiskepurl'] = \mkw\store::createSmallImageUrl($o['kepurl']);
                        $o['kepurl'] = \mkw\store::createBigImageUrl($o['kepurl']);
                        $o['children'] = $this->gettermeklistaforparent($o);
                        $x[] = $o;
                    }
                }
                return $x;
        }
        return false;
    }

    public function getNavigator($parent, $elsourlkell = true)
    {
        $navi = [];
        if ($elsourlkell) {
            $navi[] = ['caption' => $parent->getLocalizedFieldValue('nev'), 'url' => $parent->getSlug()];
        } else {
            $navi[] = ['caption' => $parent->getLocalizedFieldValue('nev'), 'url' => ''];
        }
        $szulo = $parent->getParent();
        while ($szulo) {
            $navi[] = ['caption' => $szulo->getLocalizedFieldValue('nev'), 'url' => $szulo->getSlug()];
            $szulo = $szulo->getParent();
        }
        return array_reverse($navi);
    }

    public function getkatlista($parent)
    {
        $repo = $this->getRepo();
        $children = $repo->getForParent($parent->getId(), 4);
        $t = [];
        foreach ($children as $child) {
            $child['kozepeskepurl'] = \mkw\store::createMediumImageUrl($child['kepurl']);
            $child['kiskepurl'] = \mkw\store::createSmallImageUrl($child['kepurl']);
            $child['kepurl'] = \mkw\store::createBigImageUrl($child['kepurl']);
//			$chchildren=$repo->getForParent($child['id']);
//			$child['childcount']=count($chchildren);
            $child['childcount'] = $repo->getForParentCount($child['id']);
            $t[] = $child;
        }
        $ret = [
            'children' => $t,
            'navigator' => $this->getNavigator($parent)
        ];
        return $ret;
    }

    public function gettermeklistaforparent($parent, $caller = null)
    {
        $pKeresoszo = $this->params->getStringRequestParam('keresett');
        $pArfilterstring = $this->params->getStringRequestParam('arfilter');
        $pPageno = $this->params->getIntRequestParam('pageno', 1);
        $pOrd = $this->params->getStringRequestParam('order');
        $pCimkeszurostr = $this->params->getStringRequestParam('filter');
        $pElemperpage = $this->params->getIntRequestParam('elemperpage', \mkw\store::getParameter(\mkw\consts::Termeklistatermekdb, 30));
        $pCsakakcios = $this->params->getBoolRequestParam('csakakcios', false);
        $kiemelttermekdb = \mkw\store::getParameter(\mkw\consts::Kiemelttermekdb, 3);

        /** @var \Entities\TermekRepository $termekrepo */
        $termekrepo = \mkw\store::getEm()->getRepository(Termek::class);
        $tc = new termekController();
        $tck = new termekcimkekatController();

        switch (true) {
            case \mkw\store::isMindentkapni():
                $ret = [];

                /**
                 * 'elemperpage'=>$this->params->getIntRequestParam('elemperpage',20),
                 * 'pageno'=>$this->params->getIntRequestParam('pageno',1),
                 * 'order'=>$this->params->getStringRequestParam('order','ardesc'),
                 * 'filter'=>$this->params->getStringRequestParam('filter',''),
                 * 'klikkeltcimkekatid'=>$this->params->getIntRequestParam('cimkekatid'),
                 * 'arfilter'=>$this->params->getStringRequestParam('arfilter',''),
                 * 'keresett'=>$this->params->getStringRequestParam('keresett',''),
                 * 'vt'=>$this->params->getIntRequestParam('vt',1)
                 */

                if ($caller === 'marka') {
                    $marka = $this->getRepo(Termekcimketorzs::class)->findOneBySlug($this->params->getStringParam('slug'));
                    if ($marka) {
                        $szuroarr = [$pCimkeszurostr, $marka->getTermekFilter()];
                        $pCimkeszurostr = implode(',', $szuroarr);
                    }
                }

                [$minarfilter, $maxarfilter] = $this->parseArfilter($pArfilterstring);

                $kategoriafilter = $this->buildTermekfaFilter($parent);
                $nativkategoriafilter = $this->buildNativTermekfaFilter($parent);

                $keresofilter = $this->buildKeresoszoFilter($pKeresoszo);
                $akciosfilter = $this->buildAkciosFilter($pCsakakcios);

                $cimkeszurotomb = $this->decodeCimkeSzuroString($pCimkeszurostr);
                $cimkefilter = $this->buildTermekIdFilter($cimkeszurotomb);

                // termek max ar kategoriaval es cimkevel szurve
                $maxexistingar = $termekrepo->getTermekListaMaxAr($keresofilter->merge($kategoriafilter)->merge($cimkefilter));

                if ($maxarfilter == 0 || \mkw\store::getMainSession()->autoepp) {
                    $maxarfilter = $maxexistingar;
                }
                $arfilter = $this->buildArfilter($minarfilter, $maxarfilter);

                $termekdb = $termekrepo->getTermekListaCount(
                    $keresofilter->merge($kategoriafilter)->merge($cimkefilter)->merge($arfilter)->merge($akciosfilter)
                );
                if ($termekdb > 0) {
                    $tc->initPager($termekdb, $pElemperpage, $pPageno);
                    $pager = $tc->getPager();
                    $elemperpage = $pager->getElemPerPage();

                    $order = $this->orderMap($pOrd);

                    $ujtermekminid = $termekrepo->getUjTermekId();
                    $top10min = $termekrepo->getTop10Mennyiseg();

                    // kiemelt termekek, kategoriaszures es kereses
                    $termeklista = [];
                    $kiemelt = [];
                    if ((($pPageno == 1) || ($pager->getPageCount() == 1)) && ($caller !== 'szuro') && ($caller !== 'marka')) {
                        $kiemelt = $this->buildKiemeltTermekLista(
                            $keresofilter->merge($kategoriafilter),
                            $kiemelttermekdb,
                            $ujtermekminid
                        );
                    }
                    $ret['kiemelttermekek'] = $kiemelt;

                    // termekek kategoriaval es cimkevel es arral szurve, lapozva
                    // ez a konkret termeklista
                    $termekek = $termekrepo->getTermekLista(
                        $keresofilter->merge($nativkategoriafilter)->merge($cimkefilter)->merge($arfilter)->merge($akciosfilter),
                        $order,
                        $pager->getOffset(),
                        $elemperpage
                    );
                    $termeklista = $this->buildTermekLista($termekek, $ujtermekminid, $top10min);

                    $ret['arfilterstep'] = \mkw\store::getParameter(\mkw\consts::Arfilterstep, 500);
                    $ret['maxar'] = \mkw\store::felKerekit($maxexistingar, $ret['arfilterstep']);
                    $ret['minarfilter'] = $minarfilter;
                    $ret['maxarfilter'] = \mkw\store::felKerekit($maxarfilter, $ret['arfilterstep']);

                    switch ($caller) {
                        case 'termekfa':
                            $ret['url'] = '/termekfa/' . $parent->getSlug();
                            $ret['navigator'] = $this->getNavigator($parent);
                            $ret['szurok'] = $tck->getForTermekSzuro(
                                $this->getTermekIdsForCimkeSzuro($keresofilter->merge($kategoriafilter)->merge($arfilter)->merge($akciosfilter)),
                                $cimkeszurotomb
                            );
                            break;
                        case 'kereses':
                            $ret['url'] = '/kereses';
                            $ret['navigator'] = [['caption' => t('A keresett kifejezés') . ': ' . $pKeresoszo]];
                            $ret['szurok'] = $tck->getForTermekSzuro(
                                $this->getTermekIdsForCimkeSzuro($keresofilter->merge($kategoriafilter)->merge($arfilter)->merge($akciosfilter)),
                                $cimkeszurotomb
                            );
                            break;
                        case 'szuro':
                            $ret['url'] = '/szuro';
                            $ret['navigator'] = [['caption' => t('Szűrő')]];
                            $ret['szurok'] = $tck->getForTermekSzuro(
                                $this->getTermekIdsFromTermekListaForCimkeSzuro(
                                    $keresofilter->merge($nativkategoriafilter)->merge($arfilter)->merge($akciosfilter)
                                ),
                                $cimkeszurotomb
                            );
                            break;
                        case 'marka':
                            $ret['url'] = '/marka/' . $marka->getSlug();
                            $ret['navigator'] = [['caption' => $marka->getNev()]];
                            $ret['szurok'] = $tck->getForTermekSzuro(
                                $this->getTermekIdsFromTermekListaForCimkeSzuro(
                                    $keresofilter->merge($nativkategoriafilter)->merge($arfilter)->merge($akciosfilter)
                                ),
                                $cimkeszurotomb
                            );
                    }
                    $ret['keresett'] = $pKeresoszo;
                    $ret['vt'] = ($this->params->getIntRequestParam('vt') > 0 ? $this->params->getIntRequestParam('vt') : 1);
                    $ret['csakakcios'] = $pCsakakcios;
                    $ret['termekek'] = $termeklista;
                    $ret['lapozo'] = $pager->loadValues();
                    $ret['order'] = $pOrd;
                    $ret['blogposztok'] = $this->buildBlogposztLista($parent);

                    if ($parent) {
                        $ret['kategoria'] = [
                            'nev' => $parent->getNev(),
                            'leiras2' => $parent->getLeiras2(),
                            'leiras3' => $parent->getLeiras3()
                        ];
                    } else {
                        $ret['kategoria'] = [
                            'nev' => '',
                            'leiras2' => '',
                            'leiras3' => ''
                        ];
                    }
                } else {
                    switch ($caller) {
                        case 'termekfa':
                            $ret['url'] = '/termekfa/' . $parent->getSlug();
                            $ret['navigator'] = $this->getNavigator($parent);
                            $ret['szurok'] = $tck->getForTermekSzuro(
                                [],
                                $cimkeszurotomb
                            );
                            break;
                        case 'kereses':
                            $ret['url'] = '/kereses';
                            $ret['navigator'] = [['caption' => t('A keresett kifejezés') . ': ' . $pKeresoszo]];
                            $ret['szurok'] = $tck->getForTermekSzuro(
                                [],
                                $cimkeszurotomb
                            );
                            break;
                        case 'szuro':
                            $ret['url'] = '/szuro';
                            $ret['navigator'] = [['caption' => t('Szűrő')]];
                            $ret['szurok'] = $tck->getForTermekSzuro(
                                [],
                                $cimkeszurotomb
                            );
                            break;
                        case 'marka':
                            $ret['url'] = '/marka/' . $marka->getSlug();
                            $ret['navigator'] = [['caption' => $marka->getNev()]];
                            $ret['szurok'] = $tck->getForTermekSzuro(
                                [],
                                $cimkeszurotomb
                            );
                    }
                    $ret['lapozo'] = 0;
                    $ret['blogposztok'] = $this->buildBlogposztLista($parent);

                    $ret['kiemelttermekek'] = [];

                    if ($parent) {
                        $ret['kategoria'] = [
                            'nev' => $parent->getNev(),
                            'leiras2' => $parent->getLeiras2(),
                            'leiras3' => $parent->getLeiras3()
                        ];
                    } else {
                        $ret['kategoria'] = [
                            'nev' => '',
                            'leiras2' => '',
                            'leiras3' => ''
                        ];
                    }
                }
                return $ret;

            case \mkw\store::isMugenrace2026():
            case \mkw\store::isMugenrace():
                $arfilter = new FilterDescriptor();
                $cimkefilter = new FilterDescriptor();
                $akciosfilter = new FilterDescriptor();
                $ret = [];

                $tc = new termekController();
                /** @var \Entities\TermekRepository $termekrepo */
                $termekrepo = $tc->getRepo();
                $tck = new termekcimkekatController();

                $kiemelttermekdb = \mkw\store::getParameter(\mkw\consts::Kiemelttermekdb, 3);
                /**
                 * 'elemperpage'=>$this->params->getIntRequestParam('elemperpage',20),
                 * 'pageno'=>$this->params->getIntRequestParam('pageno',1),
                 * 'order'=>$this->params->getStringRequestParam('order','ardesc'),
                 * 'filter'=>$this->params->getStringRequestParam('filter',''),
                 * 'klikkeltcimkekatid'=>$this->params->getIntRequestParam('cimkekatid'),
                 * 'arfilter'=>$this->params->getStringRequestParam('arfilter',''),
                 * 'keresett'=>$this->params->getStringRequestParam('keresett',''),
                 * 'vt'=>$this->params->getIntRequestParam('vt',1)
                 */
                $pElemperpage = $this->params->getIntRequestParam('elemperpage', \mkw\store::getParameter(\mkw\consts::Termeklistatermekdb, 30));

                $pPageno = $this->params->getIntRequestParam('pageno', 1);
                $pOrd = $this->params->getStringRequestParam('order');
                $pCimkeszurostr = $this->params->getStringRequestParam('filter');

                if ($caller === 'marka') {
                    $marka = $this->getRepo(Termekcimketorzs::class)->findOneBySlug($this->params->getStringParam('slug'));
                    if ($marka) {
                        $szuroarr = [$pCimkeszurostr, $marka->getTermekFilter()];
                        $pCimkeszurostr = implode(',', $szuroarr);
                    }
                }

                if ($this->params->getIntRequestParam('cimkekatid')) {
                    $klikkeltcimkekatid = $this->params->getIntRequestParam('cimkekatid', false);
                }
                $pKeresoszo = $this->params->getStringRequestParam('keresett');

                [$minarfilter, $maxarfilter] = $this->parseArfilter($this->params->getStringRequestParam('arfilter'));

                $kategoriafilter = $this->buildTermekfaFilter($parent);
                $nativkategoriafilter = $this->buildNativTermekfaFilter($parent);

                $keresofilter = $this->buildKeresoszoFilter($pKeresoszo);

                if ($this->params->getBoolRequestParam('csakakcios', false)) {
                    $akciosfilter->addSql($termekrepo->getAkciosFilterSQL());
                }
                $szurok = explode(',', $pCimkeszurostr);
                $cimkeszurotomb = [];
                foreach ($szurok as $egyszuro) {
                    $egyreszei = explode('_', $egyszuro);
                    if (count($egyreszei) >= 3) {
                        $cimkeszurotomb[$egyreszei[1]][] = $egyreszei[2] * 1;
                    }
                }
                $termekidfiltered = [];
                if (count($cimkeszurotomb) > 0) {
                    $res = $this->getRepo(Termekcimketorzs::class)->getTermekIdsWithCimkeAnd($cimkeszurotomb);
                    foreach ($res as $sor) {
                        $termekidfiltered[] = $sor['termek_id'];
                    }
                    if (count($termekidfiltered) > 0) {
                        $cimkefilter->addFilter('id', null, $termekidfiltered);
                    } else {
                        $cimkefilter->addFilter('id', '=', false);
                    }
                }

                // termek max ar kategoriaval es cimkevel szurve
                $maxexistingar = $termekrepo->getTermekListaMaxAr($keresofilter->merge($kategoriafilter)->merge($cimkefilter));

                if ($maxarfilter == 0 || \mkw\store::getMainSession()->autoepp) {
                    $maxarfilter = $maxexistingar;
                }

                $pArfilterstring = '(_xx.brutto>=' . $minarfilter . ')';
                if ($maxarfilter > 0) {
                    $pArfilterstring = $pArfilterstring . ' AND (_xx.brutto<=' . $maxarfilter . ')';
                }
                $pArfilterstring = '((' . $pArfilterstring . ') OR (_xx.brutto IS NULL))';
                $arfilter->addSql($pArfilterstring);

                $termekdb = $termekrepo->getTermekListaCount(
                    $keresofilter->merge($kategoriafilter)->merge($cimkefilter)->merge($arfilter)->merge($akciosfilter)
                );
                if ($termekdb > 0) {
                    // termekdarabszam kategoriaval es cimkevel es arral szurve
                    // lapozohoz kell
                    $tc->initPager(
                        $termekdb,
                        $pElemperpage,
                        $pPageno
                    );
                    $pager = $tc->getPager();
                    $pElemperpage = $pager->getElemPerPage();

                    switch ($pOrd) {
                        case 'nevasc':
                            $order = ['_xx.nev' => 'ASC'];
                            break;
                        case 'nevdesc':
                            $order = ['_xx.nev' => 'DESC'];
                            break;
                        case 'arasc':
                            $order = ['_xx.brutto' => 'ASC'];
                            break;
                        case 'ardesc':
                            $order = ['_xx.brutto' => 'DESC'];
                            break;
                        case 'idasc':
                            $order = ['_xx.id' => 'ASC'];
                            break;
                        case 'iddesc':
                            $order = ['_xx.id' => 'DESC'];
                            break;
                        default:
                            $order = ['_xx.nev' => 'ASC'];
                            break;
                    }

                    $ujtermekminid = $termekrepo->getUjTermekId();
                    $top10min = $termekrepo->getTop10Mennyiseg();

                    // kiemelt termekek, kategoriaszures es kereses
                    $termeklista = [];
                    $kiemelt = [];
                    $kiemeltdb = 0;
                    if (($kiemelttermekdb > 0) && (($pPageno == 1) || ($pager->getPageCount() == 1)) && ($caller !== 'szuro') && ($caller !== 'marka')) {
                        $kiemelttermekek = $termekrepo->getKiemeltTermekek($keresofilter->merge($kategoriafilter), $kiemelttermekdb);
                        $kt = [];
                        foreach ($kiemelttermekek as $termek) {
                            $term = $termekrepo->find($termek['id']);
                            if ($termek['valtozatid']) {
                                $valt = $this->getEm()->getRepository(TermekValtozat::class)->find($termek['valtozatid']);
                            } else {
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
                    $osszestermekid = [];
                    $termekek = $termekrepo->getTermekLista(
                        $keresofilter->merge($nativkategoriafilter)->merge($cimkefilter)->merge($arfilter)->merge($akciosfilter),
                        $order,
                        $pager->getOffset(),
                        $pElemperpage
                    );
                    foreach ($termekek as $termek) {
                        $osszestermekid[] = $termek['id'];
                        /** @var \Entities\Termek $term */
                        $_termekidfilter = new FilterDescriptor();
                        $_termekidfilter->addFilter('id', '=', $termek['id']);
                        $term = $termekrepo->getWithJoins($_termekidfilter);
                        if (is_array($term)) {
                            $term = $term[0];
                        }
                        // $term = $termekrepo->find($termek['id']);
                        if ($termek['valtozatid']) {
                            /** @var \Entities\TermekValtozat $valt */
                            $valt = $this->getEm()->getRepository(TermekValtozat::class)->find($termek['valtozatid']);
                        } else {
                            $valt = null;
                        }
                        $tete = $term->toTermekLista($valt, $ujtermekminid, $top10min);
                        $tete['kiemelt'] = false;
                        $termeklista[] = $tete;
                    }
                    if (($caller === 'marka') || ($caller === 'szuro')) {
                        $osszeslapozatlantermekid = [];
                        $termekek = $termekrepo->getTermekLista(
                            $keresofilter->merge($nativkategoriafilter)->merge($cimkefilter)->merge($arfilter)->merge($akciosfilter),
                            $order
                        );
                        foreach ($termekek as $termek) {
                            $osszeslapozatlantermekid[] = $termek['id'];
                        }
                    }
                    // termek id-k csak kategoriaval es arral szurve
                    // a szuroben szereplo cimkek megallapitasahoz
                    $termekids = $termekrepo->getTermekIds($keresofilter->merge($kategoriafilter)->merge($arfilter)->merge($akciosfilter));
                    $tid = [];
                    foreach ($termekids as $termek) {
                        $tid[] = $termek['id'];
                    }

                    $ret['arfilterstep'] = \mkw\store::getParameter(\mkw\consts::Arfilterstep, 500);
                    if (($maxexistingar % $ret['arfilterstep']) != 0) {
                        $ret['maxar'] = (floor($maxexistingar / $ret['arfilterstep']) + 1) * $ret['arfilterstep'];
                    } else {
                        $ret['maxar'] = $maxexistingar;
                    }
                    $ret['minarfilter'] = $minarfilter;
                    if (($maxarfilter % $ret['arfilterstep']) != 0) {
                        $ret['maxarfilter'] = (floor($maxarfilter / $ret['arfilterstep']) + 1) * $ret['arfilterstep'];
                    } else {
                        $ret['maxarfilter'] = $maxarfilter;
                    }
                    switch ($caller) {
                        case 'termekfa':
                            $ret['url'] = '/termekfa/' . $parent->getSlug();
                            $ret['navigator'] = $this->getNavigator($parent);
                            // $tid = termek id-k csak kategoriaval es arral szurve
                            $ret['szurok'] = $tck->getForTermekSzuro($tid, $cimkeszurotomb);
                            break;
                        case 'kereses':
                            $ret['url'] = '/kereses';
                            $ret['navigator'] = [['caption' => t('A keresett kifejezés') . ': ' . $pKeresoszo]];
                            // $tid = termek id-k csak kategoriaval es arral szurve
                            $ret['szurok'] = $tck->getForTermekSzuro($tid, $cimkeszurotomb);
                            break;
                        case 'szuro':
                            $ret['url'] = '/szuro';
                            $ret['navigator'] = [['caption' => t('Szűrő')]];
                            $ret['szurok'] = $tck->getForTermekSzuro($osszeslapozatlantermekid, $cimkeszurotomb);
                            break;
                        case 'marka':
                            $ret['url'] = '/marka/' . $marka->getSlug();
                            $ret['navigator'] = [['caption' => $marka->getNev()]];
                            $ret['szurok'] = $tck->getForTermekSzuro($osszeslapozatlantermekid, $cimkeszurotomb);
                    }
                    $ret['keresett'] = $pKeresoszo;
                    $ret['vt'] = ($this->params->getIntRequestParam('vt') > 0 ? $this->params->getIntRequestParam('vt') : 1);
                    $ret['csakakcios'] = $this->params->getBoolRequestParam('csakakcios', false);
                    $ret['termekek'] = $termeklista;
                    $ret['lapozo'] = $pager->loadValues();
                    $ret['order'] = $pOrd;
                    if ($parent) {
                        $ret['kategoria'] = [
                            'nev' => $parent->getNev(),
                            'leiras2' => $parent->getLeiras2(),
                            'leiras3' => $parent->getLeiras3()
                        ];
                    } else {
                        $ret['kategoria'] = [
                            'nev' => '',
                            'leiras2' => '',
                            'leiras3' => ''
                        ];
                    }
                } else {
                    $ret['lapozo'] = 0;
                }
                return $ret;

            case \mkw\store::isSuperzoneB2B():
                $termekrepo = $this->getEm()->getRepository(Termek::class);
                $order = [];
                $kategoriafilter = new FilterDescriptor();
                $nativkategoriafilter = new FilterDescriptor();
                if ($parent) {
                    $kategoriafilter->addFilter(['_xx.termekfa1', '_xx.termekfa2', '_xx.termekfa3'], '=', $parent['id']);
                    $nativkategoriafilter->addFilter(['_xx.termekfa1_id', '_xx.termekfa2_id', '_xx.termekfa3_id'], '=', $parent['id']);
                }
                $keresofilter = new FilterDescriptor();
                if ($this->params) {
                    $pKeresoszo = $this->params->getStringRequestParam('keresett');
                    if ($pKeresoszo) {
                        $keresofilter->addFilter(['_xx.nev', '_xx.oldalcim', '_xx.cikkszam', '_xx.leiras'], 'LIKE', '%' . $pKeresoszo . '%');
                    }
                }
                $termekek = $termekrepo->getTermekLista($keresofilter->merge($kategoriafilter), ['_xx.cikkszam' => 'DESC']);
                $termeklista = [];
                foreach ($termekek as $te) {
                    $tete = $te->toMenu();
                    $tete['kiemelt'] = false;
                    $termeklista[] = $tete;
                }
                return $termeklista;
            default :
                throw new \Exception('ISMERETLEN THEME: ' . \mkw\store::getTheme());
        }
    }

    public function redirectOldUrl()
    {
        $faid = $this->params->getStringRequestParam('pcat_id');
        if ($faid) {
            $fa = $this->getRepo()->findOneById($faid);
            if ($fa) {
                $newlink = \mkw\store::getRouter()->generate('showtermekfa', false, ['slug' => $fa->getSlug()]);
            } else {
                $newlink = \mkw\store::getRouter()->generate('show404');
            }
        } else {
            $newlink = \mkw\store::getRouter()->generate('show404');
        }
        header("HTTP/1.1 301 Moved Permanently");
        header('Location: ' . $newlink);
    }

}