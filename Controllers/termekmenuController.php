<?php

namespace Controllers;

use Doctrine\ORM\Query\ResultSetMapping;
use Entities\Szin;
use Entities\Termek;
use Entities\Termekcimketorzs;
use Entities\TermekMenu;
use Entities\TermekMenuFa;
use Entities\TermekValtozat;
use mkw\store;
use mkwhelpers\FilterDescriptor;
use Traits\PublicTermekLista;

class termekmenuController extends \mkwhelpers\MattableController
{
    use PublicTermekLista;

    private $fatomb;

    /** the menu the storefront methods work on: \mkw\store::getTermekmenuController() sets the webshop's one */
    private ?TermekMenuFa $termekMenuFa = null;

    public function setTermekMenuFa(TermekMenuFa $fa): void
    {
        $this->termekMenuFa = $fa;
    }

    public function getTermekMenuFa(): ?TermekMenuFa
    {
        return $this->termekMenuFa;
    }

    public function __construct()
    {
        $this->setEntityName(TermekMenu::class);
        $this->setKarbFormTplName('termekmenukarbform.tpl');
        $this->setKarbTplName('termekmenukarb.tpl');
        parent::__construct();
    }

    /** The menu of an admin request ('fa'), else the first one. */
    private function getRequestFa(): ?TermekMenuFa
    {
        $repo = $this->getRepo(TermekMenuFa::class);
        return $repo->find($this->params->getIntRequestParam('fa')) ?? ($repo->getAllSorted()[0] ?? null);
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
            $t = new TermekMenu();
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
     * @param \Entities\TermekMenu $obj
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
            if ($obj->getTermekmenufa() && $obj->getTermekmenufa() !== $parent->getTermekmenufa()) {
                throw new \mkwhelpers\Exceptions\UserMessageException(t('A csomópont nem kerülhet át másik menübe.'));
            }
            $obj->setParent($parent);
        }
        return $obj;
    }

    protected function beforeRemove($obj)
    {
        if (!$obj->getParent() || !$obj->isDeletable()) {
            throw new \mkwhelpers\Exceptions\UserMessageException(t('A kategória nem törölhető: a menü gyökere, vagy van alkategóriája vagy terméke.'));
        }
    }

    public function viewlist()
    {
        $fa = $this->getRequestFa();
        $view = $this->createView('termekmenulista.tpl');
        $view->setVar('pagetitle', t('Termékmenük'));
        $view->setVar('termekmenufalist', $this->getRepo(TermekMenuFa::class)->getSelectList($fa?->getId()));
        $view->setVar('termekmenufa', $fa?->getId());
        $view->printTemplateResult();
    }

    /** The jstree JSON of one menu ('fa'): its root with the nodes below. */
    public function jsonlist()
    {
        $elotag = $this->params->getStringRequestParam('pre') ?: 'termekmenu_';
        $fa = $this->getRequestFa();
        $sorok = $fa ? $this->getEm()->getConnection()->fetchAllAssociative(
            'SELECT id, parent_id, nev, sorrend FROM termekmenu WHERE termekmenufa_id = ? ORDER BY parent_id, sorrend, nev',
            [$fa->getId()]
        ) : [];
        $this->fatomb = [];
        $gyoker = null;
        foreach ($sorok as $sor) {
            if ($sor['parent_id'] === null) {
                $gyoker = $gyoker ?? $sor;
            } else {
                // szülő szerinti index: enélkül a rekurzió minden szinten végigmegy az egész fán
                $this->fatomb[(int)$sor['parent_id']][] = $sor;
            }
        }
        if (!$gyoker) {
            echo json_encode([]);
            return;
        }
        echo json_encode([
            'data' => ['title' => $gyoker['nev'], 'attr' => ['id' => $elotag . $gyoker['id']]],
            'children' => $this->bejar($gyoker['id'], $elotag)
        ]);
    }

    private function bejar($szuloid, $elotag)
    {
        $ret = [];
        foreach ($this->fatomb[(int)$szuloid] ?? [] as $val) {
            $ret[] = ['data' => ['title' => $val['nev'], 'attr' => ['id' => $elotag . $val['id']]], 'children' => $this->bejar($val['id'], $elotag)];
        }
        return $ret;
    }

    protected function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);
        $view->setVar('pagetitle', t('Termékmenük'));
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
        try {
            $ret = $this->saveData();
        } catch (\mkwhelpers\Exceptions\UserMessageException $e) {
            $this->jsonError($e->getMessage(), 409);
            return;
        }
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

    /** Moves a node under another one of the same menu, not below itself. */
    public function move()
    {
        $fa = $this->getRepo()->find($this->params->getIntRequestParam('eztid'));
        $ide = $this->getRepo()->find($this->params->getIntRequestParam('ideid'));
        if (!$fa || !$ide || !$fa->getParent()) {
            $this->jsonError(t('Válasszon áthelyezendő kategóriát és új helyet.'), 400);
            return;
        }
        if ($fa->getTermekmenufa() !== $ide->getTermekmenufa()) {
            $this->jsonError(t('Kategória csak a saját menüjén belül helyezhető át.'), 409);
            return;
        }
        for ($szulo = $ide; $szulo; $szulo = $szulo->getParent()) {
            if ($szulo === $fa) {
                $this->jsonError(t('Kategória nem helyezhető a saját ága alá.'), 409);
                return;
            }
        }
        $fa->removeParent();
        $fa->setParent($ide);
        $this->getEm()->persist($fa);
        $this->getEm()->flush();
    }

    /** New menu (no 'id') or a new name for one. */
    public function faSave()
    {
        $service = new \Services\TermekMenuFaService();
        try {
            $fa = $this->getRepo(TermekMenuFa::class)->find($this->params->getIntRequestParam('id'));
            if ($fa) {
                $service->rename($fa, $this->params->getStringRequestParam('nev'));
            } else {
                $fa = $service->create($this->params->getStringRequestParam('nev'));
            }
        } catch (\mkwhelpers\Exceptions\UserMessageException $e) {
            $this->jsonError($e->getMessage(), 400);
            return;
        }
        header('Content-Type: application/json');
        echo json_encode(['id' => $fa->getId()]);
    }

    public function faCopy()
    {
        $forras = $this->getRepo(TermekMenuFa::class)->find($this->params->getIntRequestParam('id'));
        if (!$forras) {
            $this->jsonError(t('A menü nem található.'), 404);
            return;
        }
        try {
            $fa = (new \Services\TermekMenuFaService())->copy(
                $forras,
                $this->params->getStringRequestParam('nev'),
                $this->params->getBoolRequestParam('termekekkel')
            );
        } catch (\mkwhelpers\Exceptions\UserMessageException $e) {
            $this->jsonError($e->getMessage(), 400);
            return;
        }
        header('Content-Type: application/json');
        echo json_encode(['id' => $fa->getId()]);
    }

    public function faDelete()
    {
        $fa = $this->getRepo(TermekMenuFa::class)->find($this->params->getIntRequestParam('id'));
        if (!$fa) {
            $this->jsonError(t('A menü nem található.'), 404);
            return;
        }
        try {
            (new \Services\TermekMenuFaService())->delete($fa);
        } catch (\mkwhelpers\Exceptions\UserMessageException $e) {
            $this->jsonError($e->getMessage(), 409);
            return;
        }
        header('Content-Type: application/json');
        echo json_encode(['ok' => true]);
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

    /**
     * Morzsa a menüfában, a /categories/ kategórialapokra hivatkozva.
     *
     * @param ?TermekMenu $kategoria
     */
    public function getMorzsa($kategoria)
    {
        return $this->buildTreeMorzsa($kategoria, 'showtermekmenu');
    }

    public function getNavigator(TermekMenu $parent, $elsourlkell = true)
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

    public function getkatlista(TermekMenu $parent)
    {
        $repo = $this->getRepo();
        $children = $repo->getForParent($parent->getId());
        $t = [];
        foreach ($children as $child) {
            $child['kozepeskepurl'] = \mkw\store::createMediumImageUrl($child['kepurl']);
            $child['kiskepurl'] = \mkw\store::createSmallImageUrl($child['kepurl']);
            $child['kepurl'] = \mkw\store::createBigImageUrl($child['kepurl']);
            $child['childcount'] = $repo->getForParentCount($child['id']);
            $t[] = $child;
        }
        $ret = [
            'children' => $t,
            'navigator' => $this->getNavigator($parent)
        ];
        return $ret;
    }

    /** The menu as nested arrays from its root, in id order like before; inactive branches are left out. */
    public function getTreeAsArray()
    {
        $root = $this->termekMenuFa ? $this->getRepo()->getRoot($this->termekMenuFa) : null;
        if (!$root || $root->getInaktiv()) {
            return [];
        }
        $categoryData = $this->loadVars($root);
        $categoryData['children'] = $this->buildTreeBranch($root->getId());
        return [$categoryData];
    }

    private function buildTreeBranch($parentId)
    {
        $filter = new FilterDescriptor();
        $filter->addFilter('parent', '=', $parentId);

        $children = $this->getRepo()->getAll($filter);
        $branch = [];

        foreach ($children as $child) {
            if (!$child->getInaktiv()) {
                $childData = $this->loadVars($child);
                $childData['children'] = $this->buildTreeBranch($child->getId());
                $branch[] = $childData;
            }
        }

        return $branch;
    }

    public function gettermeklistaforparent($parent, $caller = null)
    {
        return match (true) {
            \mkw\store::isMugenrace2026() => $this->termeklistaMugenrace2026($parent, $caller),
            \mkw\store::isSuperzoneHu() => $this->termeklistaMugenrace2026($parent, $caller),
            \mkw\store::isSuperzoneB2B() => $this->termeklistaSuperzoneB2B($parent),
            default => throw new \Exception('ISMERETLEN THEME: ' . \mkw\store::getTheme()),
        };
    }

    /**
     * Mugenrace2026 terméklista a kategória / keresés / szűrő / márka nézetekhez.
     */
    private function termeklistaMugenrace2026(?TermekMenu $parent, string|null $caller): array
    {
        $ret = [];
        $listVariations = true;

        /** @var \Entities\TermekRepository $termekrepo */
        $termekrepo = $this->getRepo(Termek::class);
        $tc = new termekController();
        $tck = new termekcimkekatController();

        $kiemelttermekdb = \mkw\store::getParameter(\mkw\consts::Kiemelttermekdb, 3);

        // --- request paraméterek ---
        $pElemperpage = $this->params->getIntRequestParam('elemperpage', \mkw\store::getParameter(\mkw\consts::Termeklistatermekdb, 30));
        $pPageno = $this->params->getIntRequestParam('pageno', 1);
        $pOrd = $this->params->getStringRequestParam('order') ?: 'featuredasc';
        $pCimkeszurostr = $this->params->getStringRequestParam('filter');
        $pKeresoszo = $this->params->getStringRequestParam('keresett');
        $pCsakakcios = $this->params->getBoolRequestParam('csakakcios', false);
        [$minarfilter, $maxarfilter] = $this->parseArfilter($this->params->getStringRequestParam('arfilter'));

        // márka nézetnél a márka saját termékszűrője hozzáfűződik a felhasználói szűrőhöz
        $marka = null;
        if ($caller === 'marka') {
            $marka = $this->getRepo(Termekcimketorzs::class)->findOneBySlug($this->params->getStringParam('slug'));
            if ($marka) {
                $pCimkeszurostr = implode(',', [$pCimkeszurostr, $marka->getTermekFilter()]);
            }
        }

        // --- szűrők összeállítása ---
        $kategoriafilter = $this->buildTermekmenuFilter($parent);
        $nativkategoriafilter = $this->buildNativTermekmenuFilter($parent);

        $keresofilter = $this->buildKeresoszoFilter($pKeresoszo);

        $akciosfilter = $this->buildAkciosFilter($pCsakakcios);

        $cimkeszurotomb = $this->decodeCimkeSzuroString($pCimkeszurostr);
        $cimkefilter = $this->buildTermekidFilter($cimkeszurotomb);

        $maxexistingar = $termekrepo->getTermekListaMaxAr($keresofilter->merge($kategoriafilter)->merge($cimkefilter));
        if ($maxarfilter == 0 || \mkw\store::getMainSession()->autoepp) {
            $maxarfilter = $maxexistingar;
        }
        $arfilter = $this->buildArfilter($minarfilter, $maxarfilter);

        // termekdarabszam kategoriaval es cimkevel es arral szurve, lapozohoz kell
        $termekdb = $termekrepo->getTermekListaCount(
            $keresofilter->merge($kategoriafilter)->merge($cimkefilter)->merge($arfilter)->merge($akciosfilter),
            $listVariations
        );
        if ($termekdb > 0) {
            // --- lapozó ---
            $tc->initPager($termekdb, $pElemperpage, $pPageno);
            $pager = $tc->getPager();
            $elemperpage = $pager->getElemPerPage();

            $order = $this->orderMap($pOrd);

            $ujtermekminid = $termekrepo->getUjTermekId();

            // --- kiemelt termékek (csak 1. oldalon, nem szűrő/márka nézetben) ---
            $kiemelt = [];
            if ((($pPageno == 1) || ($pager->getPageCount() == 1)) && ($caller !== 'szuro') && ($caller !== 'marka')) {
                $kiemelt = $this->buildKiemeltTermekLista(
                    $keresofilter->merge($kategoriafilter),
                    $kiemelttermekdb,
                    $ujtermekminid
                );
            }
            $ret['kiemelttermekek'] = $kiemelt;

            // --- a konkrét terméklista (kategória + címke + ár szűrve, lapozva) ---
            $termekek = $termekrepo->getTermekLista(
                $keresofilter->merge($nativkategoriafilter)->merge($cimkefilter)->merge($arfilter)->merge($akciosfilter),
                $order,
                $pager->getOffset(),
                $elemperpage,
                $listVariations
            );
            $termeklista = $this->buildSzinesTermekLista($termekek, $ujtermekminid);

            // --- ár-skála a szűrőhöz ---
            $ret['arfilterstep'] = \mkw\store::getParameter(\mkw\consts::Arfilterstep, 500);
            $ret['maxar'] = \mkw\store::felKerekit($maxexistingar, $ret['arfilterstep']);
            $ret['minarfilter'] = $minarfilter;
            $ret['maxarfilter'] = \mkw\store::felKerekit($maxarfilter, $ret['arfilterstep']);

            // --- caller-függő navigátor / url / címkeszűrő ---
            switch ($caller) {
                case 'categories':
                    $ret['url'] = '/categories/' . $parent->getSlug();
                    $ret['navigator'] = $this->getNavigator($parent);
                    $ret['szurok'] = $tck->getForTermekSzuro(
                        $this->getTermekIdsForCimkeSzuro($keresofilter->merge($kategoriafilter)->merge($arfilter)->merge($akciosfilter)),
                        $cimkeszurotomb
                    );
                    break;
                case 'kereses':
                case 'search':
                    $ret['url'] = '/search';
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
                        $this->getTermekIdsFromTermekListaForCimkeSzuro($keresofilter->merge($nativkategoriafilter)->merge($arfilter)->merge($akciosfilter)),
                        $cimkeszurotomb
                    );
                    break;
                case 'marka':
                    $ret['url'] = '/marka/' . $marka->getSlug();
                    $ret['navigator'] = [['caption' => $marka->getNev()]];
                    $ret['szurok'] = $tck->getForTermekSzuro(
                        $this->getTermekIdsFromTermekListaForCimkeSzuro($keresofilter->merge($nativkategoriafilter)->merge($arfilter)->merge($akciosfilter)),
                        $cimkeszurotomb
                    );
            }

            $ret['keresett'] = $pKeresoszo;
            $ret['vt'] = ($this->params->getIntRequestParam('vt') > 0 ? $this->params->getIntRequestParam('vt') : 1);
            $ret['csakakcios'] = $pCsakakcios;
            if ($pOrd == 'featuredasc') {
                shuffle($termeklista);
            }
            $ret['termekek'] = $termeklista;
            $ret['lapozo'] = $pager->loadValues();
            $ret['order'] = $pOrd;
            $ret['kategoria'] = $parent
                ? [
                    'nev' => $parent->getLocalizedFieldValue('nev'),
                    'leiras2' => $parent->getLocalizedFieldValue('leiras2'),
                    'leiras3' => $parent->getLocalizedFieldValue('leiras3')
                ]
                : ['nev' => '', 'leiras2' => '', 'leiras3' => ''];
        } else {
            $ret['lapozo'] = 0;
        }
        return $ret;
    }

    /**
     * SuperzoneB2B terméklista: egyszerű menü-lista, lapozó / ár-szűrő nélkül.
     */
    private function termeklistaSuperzoneB2B($parent)
    {
        $termekrepo = $this->getEm()->getRepository(Termek::class);

        $kategoriafilter = $this->buildTermekmenuFilter($parent);
        $keresofilter = $this->buildKeresoszoFilter($this->params->getStringRequestParam('keresett'));

        $termekek = $termekrepo->getTermekLista($keresofilter->merge($kategoriafilter), ['_xx.cikkszam' => 'DESC']);
        $t = [];
        /** @var Termek $te */
        foreach ($termekek as $te) {
            $tete = $te->toMenu();
            $tete['kiemelt'] = false;
            $t[] = $tete;
        }
        return $t;
    }

}