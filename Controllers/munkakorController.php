<?php

namespace Controllers;

use Entities\Munkakor;

class munkakorController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(Munkakor::class);
        $this->setKarbFormTplName('munkakorkarbform.tpl');
        $this->setKarbTplName('munkakorkarb.tpl');
        $this->setListBodyRowTplName('munkakorlista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct();
    }

    public function loadVars($t, $forKarb = false)
    {
        if (!$t) {
            $t = new Munkakor();
            $this->getEm()->detach($t);
        }
        $x = $this->getEntityFieldsArray($t);
        if ($forKarb && self::canEditMenuJog()) {
            $x['menucsoportok'] = $this->getMenuLista($t->getId());
            $x['menukorlatozott'] = self::getSzerkeszthetoMenuIds() !== null;
        }
        return $x;
    }

    /**
     * A menüpontok menücsoportonként, bepipálva, amit a munkakör elér. A "mindenki" menüpontot pipa nélkül is
     * mindenki eléri, ezért az nem állítható.
     */
    private function getMenuLista($munkakorId)
    {
        $szerkesztheto = self::getSzerkeszthetoMenuIds();
        $csoportok = [];
        /** @var \Entities\Menu $menu */
        foreach ($this->getRepo(\Entities\Menu::class)->getWithJoins([], ['m.sorrend' => 'ASC', 'sorrend' => 'ASC']) as $menu) {
            $mcsid = $menu->getMenucsoportId() ?: 0;
            $csoportok[$mcsid] ??= ['id' => $mcsid, 'nev' => $menu->getMenucsoportNev() ?: t('Csoport nélkül'), 'menuk' => []];
            $csoportok[$mcsid]['menuk'][] = [
                'id' => $menu->getId(),
                'nev' => $menu->getNev(),
                'lathato' => (bool)$menu->getLathato() && $menu->isMenucsoportLathato(),
                'mindenki' => (bool)$menu->getMindenki(),
                'checked' => $munkakorId && in_array($munkakorId, $menu->getMunkakorIds()),
                'szerkesztheto' => $szerkesztheto === null || isset($szerkesztheto[$menu->getId()]),
            ];
        }
        return array_values($csoportok);
    }

    /** Aki a munkaköröket karbantarthatja, az a menüjogaikat is (a saját munkaköréét is). */
    private static function canEditMenuJog(): bool
    {
        return \mkw\store::haveMenuJog('/admin/munkakor/viewlist', 90);
    }

    /**
     * A menüpontok, amelyeket a bejelentkezett felhasználó kioszthat vagy elvehet: a sysadmin bármelyiket (null),
     * más csak azokat, amelyeket a saját munkaköre is elér — így senki nem adhat nagyobb jogot, mint a sajátja.
     *
     * @return array<int, true>|null
     */
    private static function getSzerkeszthetoMenuIds(): ?array
    {
        if (\mkw\store::isSysadmin()) {
            return null;
        }
        $munkakorId = \mkw\store::getAdminMunkakorId();
        if (!$munkakorId) {
            return [];
        }
        $ids = \mkw\store::getEm()->getConnection()->fetchFirstColumn(
            'SELECT menu_id FROM menu_munkakorok WHERE munkakor_id = ?',
            [$munkakorId]
        );
        return array_fill_keys(array_map('intval', $ids), true);
    }

    /** A menüjogok csak akkor íródnak, ha a fül a formon volt, különben a meglévők maradnak. */
    protected function afterSave($o, $parancs = null)
    {
        if ($parancs === $this->delOperation || !self::canEditMenuJog() || !$this->params->getBoolRequestParam('menujogok')) {
            return;
        }
        $conn = $this->getEm()->getConnection();
        $menuIds = array_map('intval', $this->params->getArrayRequestParam('menuk', []));
        $szerkesztheto = self::getSzerkeszthetoMenuIds();
        if ($szerkesztheto === null) {
            $conn->executeStatement('DELETE FROM menu_munkakorok WHERE munkakor_id = ?', [$o->getId()]);
        } else {
            // a többi menüpont pipája marad, ahogy volt, akármit küldött a form
            $menuIds = array_values(array_filter($menuIds, fn($id) => isset($szerkesztheto[$id])));
            if ($szerkesztheto) {
                $conn->executeStatement(
                    'DELETE FROM menu_munkakorok WHERE munkakor_id = ? AND menu_id IN (?)',
                    [$o->getId(), array_keys($szerkesztheto)],
                    [\Doctrine\DBAL\ParameterType::INTEGER, \Doctrine\DBAL\ArrayParameterType::INTEGER]
                );
            }
        }
        if ($menuIds) {
            $conn->executeStatement(
                'INSERT INTO menu_munkakorok (menu_id, munkakor_id) SELECT id, ? FROM menu WHERE id IN (?)',
                [$o->getId(), $menuIds],
                [\Doctrine\DBAL\ParameterType::INTEGER, \Doctrine\DBAL\ArrayParameterType::INTEGER]
            );
        }
    }

    /**
     * @param \Entities\Munkakor $obj
     *
     * @return \Entities\Munkakor
     */
    protected function setFields($obj)
    {
        return $this->setEntityFieldsFromRequest($obj);
    }

    public function getlistbody()
    {
        $view = $this->createView('munkakorlista_tbody.tpl');

        $filter = new \mkwhelpers\FilterDescriptor();
        if (!is_null($this->params->getRequestParam('nevfilter', null))) {
            $filter->addFilter('nev', 'LIKE', '%' . $this->params->getStringRequestParam('nevfilter') . '%');
        }

        $this->initPager(
            $this->getRepo()->getCount($filter),
            $this->params->getIntRequestParam('elemperpage', 30),
            $this->params->getIntRequestParam('pageno', 1)
        );

        $egyedek = $this->getRepo()->getAll(
            $filter,
            $this->getOrderArray(),
            $this->getPager()->getOffset(),
            $this->getPager()->getElemPerPage()
        );

        echo json_encode($this->loadDataToView($egyedek, 'egyedlista', $view));
    }

    public function viewlist()
    {
        $view = $this->createView('munkakorlista.tpl');

        $view->setVar('pagetitle', t('Munkakörök'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Munkakör'));
        $view->setVar('formaction', \mkw\store::getRouter()->generate('adminmunkakorsave'));
        $view->setVar('oper', $oper);
        $record = $this->getRepo()->find($id);
        $view->setVar('egyed', $this->loadVars($record, true));
        return $view->getTemplateResult();
    }

    public function getSelectList($selid = null)
    {
        $rec = $this->getRepo()->getAll([], ['nev' => 'ASC']);
        $res = [];
        foreach ($rec as $sor) {
            $res[] = ['id' => $sor->getId(), 'caption' => $sor->getNev(), 'selected' => ($sor->getId() == $selid)];
        }
        return $res;
    }
}
