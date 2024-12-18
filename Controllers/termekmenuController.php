<?php

namespace Controllers;

use Automattic\WooCommerce\Client;
use Automattic\WooCommerce\HttpClient\HttpClientException;
use Doctrine\ORM\Query\ResultSetMapping;
use Entities\TermekMenu;
use Entities\TermekMenuTranslation;
use mkw\store;
use mkwhelpers\FilterDescriptor;

class termekmenuController extends \mkwhelpers\MattableController
{

    private $fatomb;

    public function __construct($params)
    {
        $this->setEntityName(TermekMenu::class);
        $this->setKarbFormTplName('termekmenukarbform.tpl');
        $this->setKarbTplName('termekmenukarb.tpl');
        parent::__construct($params);
    }

    /**
     * @param \Entities\TermekMenu $t
     * @param bool $forKarb
     *
     * @return array
     */
    protected function loadVars($t, $forKarb = false)
    {
        $x = [];
        if (!$t) {
            $t = new \Entities\TermekMenu();
            $this->getEm()->detach($t);
        }
        $x['id'] = $t->getId();
        $x['nev'] = $t->getNev();
        $x['sorrend'] = $t->getSorrend();
        $x['oldalcim'] = $t->getOldalcim();
        $x['rovidleiras'] = $t->getRovidleiras();
        $x['leiras'] = $t->getLeiras();
        $x['leiras2'] = $t->getLeiras2();
        $x['leiras3'] = $t->getLeiras3();
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
        $x['emagid'] = $t->getEmagid();
        $x['arukeresoid'] = $t->getArukeresoid();
        $x['lathato'] = $t->getLathato();
        $x['lathato1'] = $t->getLathato();
        $x['lathato2'] = $t->getLathato2();
        $x['lathato3'] = $t->getLathato3();
        $x['lathato4'] = $t->getLathato4();
        $x['lathato5'] = $t->getLathato5();
        $x['lathato6'] = $t->getLathato6();
        $x['lathato7'] = $t->getLathato7();
        $x['lathato8'] = $t->getLathato8();
        $x['lathato9'] = $t->getLathato9();
        $x['lathato10'] = $t->getLathato10();
        $x['lathato11'] = $t->getLathato11();
        $x['lathato12'] = $t->getLathato12();
        $x['lathato13'] = $t->getLathato13();
        $x['lathato14'] = $t->getLathato14();
        $x['lathato15'] = $t->getLathato15();
        $x['path'] = implode('/', $t->getPath($t));
        if (\mkw\store::isMultilang()) {
            $translations = [];
            $translationsCtrl = new termekmenutranslationController($this->params);
            foreach ($t->getTranslations() as $tr) {
                $translations[] = $translationsCtrl->loadVars($tr, true);
            }
            $x['translations'] = $translations;
        }
        return $x;
    }

    /**
     * @param \Entities\TermekMenu $obj
     *
     * @return mixed
     */
    protected function setFields($obj)
    {
        $obj->setNev($this->params->getStringRequestParam('nev'));
        $obj->setOldalcim($this->params->getStringRequestParam('oldalcim'));
        $obj->setRovidleiras($this->params->getStringRequestParam('rovidleiras'));
        $obj->setLeiras($this->params->getOriginalStringRequestParam('leiras'));
        $obj->setLeiras2($this->params->getOriginalStringRequestParam('leiras2'));
        $obj->setLeiras3($this->params->getOriginalStringRequestParam('leiras3'));
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
        $obj->setEmagid($this->params->getIntRequestParam('emagid'));
        $obj->setArukeresoid($this->params->getStringRequestParam('arukeresoid'));
        $obj->setLathato($this->params->getBoolRequestParam('lathato'));
        $obj->setLathato2($this->params->getBoolRequestParam('lathato2'));
        $obj->setLathato3($this->params->getBoolRequestParam('lathato3'));
        $obj->setLathato4($this->params->getBoolRequestParam('lathato4'));
        $obj->setLathato5($this->params->getBoolRequestParam('lathato5'));
        $obj->setLathato6($this->params->getBoolRequestParam('lathato6'));
        $obj->setLathato7($this->params->getBoolRequestParam('lathato7'));
        $obj->setLathato8($this->params->getBoolRequestParam('lathato8'));
        $obj->setLathato9($this->params->getBoolRequestParam('lathato9'));
        $obj->setLathato10($this->params->getBoolRequestParam('lathato10'));
        $obj->setLathato11($this->params->getBoolRequestParam('lathato11'));
        $obj->setLathato12($this->params->getBoolRequestParam('lathato12'));
        $obj->setLathato13($this->params->getBoolRequestParam('lathato13'));
        $obj->setLathato14($this->params->getBoolRequestParam('lathato14'));
        $obj->setLathato15($this->params->getBoolRequestParam('lathato15'));
        if (\mkw\store::isMultilang()) {
            $translationids = $this->params->getArrayRequestParam('translationid');
            $_tf = \Entities\TermekMenu::getTranslatedFields();
            foreach ($translationids as $translationid) {
                $oper = $this->params->getStringRequestParam('translationoper_' . $translationid);
                $loca = $this->params->getStringRequestParam('translationlocale_' . $translationid);
                $mezo = $this->params->getStringRequestParam('translationfield_' . $translationid);
                $mezotype = $_tf[$mezo]['type'];
                switch ($mezotype) {
                    case 1:
                    case 3:
                        $mezoertek = $this->params->getStringRequestParam('translationcontent_' . $translationid);
                        break;
                    case 2:
                        $mezoertek = $this->params->getOriginalStringRequestParam('translationcontent_' . $translationid);
                        break;
                    default:
                        $mezoertek = $this->params->getStringRequestParam('translationcontent_' . $translationid);
                        break;
                }
                if ($oper === 'add') {
                    $translation = new \Entities\TermekMenuTranslation(
                        $loca,
                        $mezo,
                        $mezoertek
                    );
                    $obj->addTranslation($translation);
                    $this->getEm()->persist($translation);
                } elseif ($oper === 'edit') {
                    $translation = $this->getEm()->getRepository(TermekMenuTranslation::class)->find($translationid);
                    if ($translation) {
                        $translation->setLocale($loca);
                        $translation->setField($mezo);
                        $translation->setContent($mezoertek);
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

    public function viewlist()
    {
        $view = $this->createView('termekmenulista.tpl');
        $view->setVar('pagetitle', t('Termék menük'));
        $view->printTemplateResult();
    }

    public function jsonlist()
    {
        $elotag = $this->params->getStringRequestParam('pre');
        if (!$elotag) {
            $elotag = 'termekmenu_';
        }
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('parent_id', 'parent_id');
        $rsm->addScalarResult('nev', 'nev');
        $rsm->addScalarResult('sorrend', 'sorrend');
        $q = $this->getEm()->createNativeQuery('SELECT id,parent_id,nev,sorrend FROM termekmenu tf ORDER BY parent_id,sorrend,nev', $rsm);
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
        $view->setVar('pagetitle', t('Termék menü'));
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

    public function getNavigator($parent, $elsourlkell = true)
    {
        $navi = [];
        if ($elsourlkell) {
            $navi[] = ['caption' => $parent->getNev(), 'url' => $parent->getSlug()];
        } else {
            $navi[] = ['caption' => $parent->getNev(), 'url' => ''];
        }
        $szulo = $parent->getParent();
        while ($szulo) {
            $navi[] = ['caption' => $szulo->getNev(), 'url' => $szulo->getSlug()];
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

    public static function walkCategoryTree($parentId = null, $wcparentid = null, $wc = null)
    {
        $filter = new FilterDescriptor();
        if (!$parentId) {
            $filter->addSql('(_xx.parent IS NULL)');
        } else {
            $filter->addFilter('parent', '=', $parentId);
        }
        $categories = \mkw\store::getEm()->getRepository(TermekMenu::class)->getAll($filter);

        \mkw\store::writelog('walkCategoryTree:parentid: ' . $parentId);

        /** @var TermekMenu $category */
        foreach ($categories as $category) {
            \mkw\store::writelog('walkCategoryTree: ' . $category->getId() . ' : ' . $category->getNev());
            if (!$category->getWcid()) {
                \mkw\store::writelog('walkCategoryTree:nlathato: ' . $category->getNLathato(\mkw\store::getWcWebshopNum()));

                if ($category->getNLathato(\mkw\store::getWcWebshopNum())) {
                    $ford = $category->getTranslationsArray();
                    $nev = $category->getNevForditas($ford, 'en_us');
                    $leiras = $category->getLeirasForditas($ford, 'en_us');
                    if ($nev) {
                        $data = [
                            'name' => $nev,
                            'parent' => $wcparentid,
                            'descrition' => $leiras,
                            'menu_order' => $category->getSorrend(),
                        ];
                        if ($category->getKepurl()) {
                            if ($category->getKepwcid()) {
                                $data['image'] = [
                                    'id' => $category->getKepwcid()
                                ];
                            } else {
                                $data['image'] = [
                                    'src' => \mkw\store::getWcImageUrlPrefix() . $category->getKepurl(),
                                    'name' => $category->getKepurl(),
                                    'alt' => $nev
                                ];
                            }
                        }

                        try {
                            $result = $wc->post('products/categories', $data);
                        } catch (HttpClientException $e) {
                            \mkw\store::writelog($category->getWcid() . ' :termékkategória POST:HIBA: ' . $e->getMessage());
                        }

                        $category->setWcid($result->id);
                        $category->setKepwcid($result->image->id);
                        $category->setWcdate('');
                        \mkw\store::getEm()->persist($category);
                        \mkw\store::getEm()->flush();

                        self::walkCategoryTree($category->getId(), $result->id, $wc);
                    } else {
                        self::walkCategoryTree($category->getId(), null, $wc);
                    }
                } else {
                    self::walkCategoryTree($category->getId(), null, $wc);
                }
            } elseif ($category->getWcdate() < $category->getLastmod()) {
                $ford = $category->getTranslationsArray();
                $nev = $category->getNevForditas($ford, 'en_us');
                $leiras = $category->getLeirasForditas($ford, 'en_us');
                $data = [
                    'name' => $nev,
                    'parent' => $wcparentid,
                    'descrition' => $leiras,
                    'menu_order' => $category->getSorrend(),
                ];
                if ($category->getKepurl()) {
                    if ($category->getKepwcid()) {
                        $data['image'] = [
                            'id' => $category->getKepwcid()
                        ];
                    } else {
                        $data['image'] = [
                            'src' => \mkw\store::getWcImageUrlPrefix() . $category->getKepurl(),
                            'name' => $category->getKepurl(),
                            'alt' => $nev
                        ];
                    }
                }
                try {
                    $wc->put('products/categories/' . $category->getWcid(), $data);
                } catch (HttpClientException $e) {
                    \mkw\store::writelog($category->getWcid() . ' :termékkategória PUT:HIBA: ' . $e->getMessage());
                }
                $category->setKepwcid($result->image->id);
                $category->setWcdate('');
                \mkw\store::getEm()->persist($category);
                \mkw\store::getEm()->flush();

                self::walkCategoryTree($category->getId(), $category->getWcid(), $wc);
            } else {
                self::walkCategoryTree($category->getId(), $category->getWcid(), $wc);
            }
        }
    }

    public function uploadToWc()
    {
        if (\mkw\store::isWoocommerceOn()) {
            /** @var Client $wc */
            $wc = store::getWcClient();
            self::walkCategoryTree(null, null, $wc);
            echo 'Ready.';
        }
    }

}