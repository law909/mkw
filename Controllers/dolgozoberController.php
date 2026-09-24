<?php

namespace Controllers;

use Entities\Berjogcim;
use Entities\Dolgozo;
use Entities\Dolgozober;

class dolgozoberController extends \mkwhelpers\MattableController
{

    // pay is personal data: the menu hides it below this, the endpoints refuse it too
    private const BERJOG = 40;

    public function __construct()
    {
        $this->setEntityName(Dolgozober::class);
        $this->setKarbFormTplName('dolgozoberkarbform.tpl');
        $this->setKarbTplName('dolgozoberkarb.tpl');
        $this->setListBodyRowTplName('dolgozoberlista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct();
    }

    public function loadVars($t, $forKarb = false)
    {
        if (!$t) {
            $t = new Dolgozober();
            $t->setDatum(new \DateTime());
            $this->getEm()->detach($t);
        }
        $x = $this->getEntityFieldsArray($t);
        $x['datumstr'] = $t->getDatumStr();
        $x['dolgozo'] = $t->getDolgozoId();
        $x['dolgozonev'] = $t->getDolgozoNev();
        $x['berjogcim'] = $t->getBerjogcimId();
        $x['berjogcimnev'] = $t->getBerjogcimNev();
        $x['createdby'] = $t->getCreatedbyNev();
        $x['createdstr'] = $t->getCreatedStr();
        $x['updatedby'] = $t->getUpdatedbyNev();
        $x['lastmodstr'] = $t->getLastmodStr();
        $x['rontottby'] = $t->getRontottbyNev();
        $x['rontottonstr'] = $t->getRontottonStr();
        return $x;
    }

    /**
     * @param \Entities\Dolgozober $obj
     *
     * @return \Entities\Dolgozober
     */
    protected function setFields($obj)
    {
        // voiding has its own endpoint, a save must not clear it
        $obj = $this->setEntityFieldsFromRequest($obj, ['skip' => ['rontott', 'rontotton', 'lastmod']]);
        $dolgozo = $this->getRepo(Dolgozo::class)->find($this->params->getIntRequestParam('dolgozo'));
        if ($dolgozo) {
            $obj->setDolgozo($dolgozo);
        }
        $berjogcim = $this->getRepo(Berjogcim::class)->find($this->params->getIntRequestParam('berjogcim'));
        if ($berjogcim) {
            $obj->setBerjogcim($berjogcim);
        }
        return $obj;
    }

    protected function validate($obj, $parancs)
    {
        $errors = [];
        if (!$obj->getDolgozo()) {
            $errors['dolgozo'] = t('Válasszon dolgozót.');
        }
        if (!$obj->getDatum()) {
            $errors['datum'] = t('Adja meg a dátumot.');
        }
        if (!$obj->getBerjogcim()) {
            $errors['berjogcim'] = t('Válasszon jogcímet.');
        }
        return $errors;
    }

    protected function beforeRemove($o)
    {
        throw new \mkwhelpers\Exceptions\UserMessageException(t('A bér nem törölhető, csak rontható.'));
    }

    protected function isReadonly($record)
    {
        return parent::isReadonly($record) || ((bool)$record && $record->getRontott());
    }

    private function getFilter(): \mkwhelpers\FilterDescriptor
    {
        $filter = new \mkwhelpers\FilterDescriptor();
        if ($this->params->getStringRequestParam('tolfilter')) {
            $filter->addFilter('datum', '>=', \mkw\store::convDate($this->params->getStringRequestParam('tolfilter')));
        }
        if ($this->params->getStringRequestParam('igfilter')) {
            $filter->addFilter('datum', '<=', \mkw\store::convDate($this->params->getStringRequestParam('igfilter')));
        }
        if ($this->params->getIntRequestParam('dolgozofilter')) {
            $filter->addFilter('d.id', '=', $this->params->getIntRequestParam('dolgozofilter'));
        }
        if ($this->params->getIntRequestParam('berjogcimfilter')) {
            $filter->addFilter('j.id', '=', $this->params->getIntRequestParam('berjogcimfilter'));
        }
        switch ($this->params->getIntRequestParam('rontottfilter')) {
            case 1:
                $filter->addFilter('rontott', '=', false);
                break;
            case 2:
                $filter->addFilter('rontott', '=', true);
                break;
        }
        return $filter;
    }

    public function getlistbody()
    {
        if (!\mkw\store::haveJog(self::BERJOG)) {
            $this->jsonFail(t('Nincs jogosultsága a művelethez.'));
            return;
        }
        $view = $this->createView('dolgozoberlista_tbody.tpl');
        $filter = $this->getFilter();

        $this->initPager($this->getRepo()->getCount($filter));

        $egyedek = $this->getRepo()->getWithJoins(
            $filter,
            $this->getOrderArray(),
            $this->getPager()->getOffset(),
            $this->getPager()->getElemPerPage()
        );
        $view->setVar('osszeg', $this->getRepo()->getOsszeg($filter));

        echo json_encode($this->loadDataToView($egyedek, 'egyedlista', $view));
    }

    public function viewlist()
    {
        if (!\mkw\store::haveJog(self::BERJOG)) {
            return;
        }
        $view = $this->createView('dolgozoberlista.tpl');

        $view->setVar('pagetitle', t('Bérek'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        // the filter also finds a former employee's pay
        $view->setVar('dolgozolist', (new dolgozoController())->getSelectList(0, false));
        $view->setVar('berjogcimlist', (new berjogcimController())->getFilterSelectList());
        $view->printTemplateResult(false);
    }

    protected function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Bér'));
        $view->setVar('formaction', \mkw\store::getRouter()->generate('admindolgozobersave'));
        $view->setVar('oper', $oper);
        $record = $this->getRepo()->find($id);
        $view->setVar('egyed', $this->loadVars($record, true));
        $view->setVar('readonly', $this->isReadonly($record));
        $view->setVar('dolgozolist', (new dolgozoController())->getSelectList($record ? $record->getDolgozoId() : 0));
        $view->setVar('berjogcimlist', (new berjogcimController())->getSelectList($record ? $record->getBerjogcimId() : 0));
        return $view->getTemplateResult();
    }

    public function getkarb()
    {
        if (\mkw\store::haveJog(self::BERJOG)) {
            parent::getkarb();
        }
    }

    public function viewkarb()
    {
        if (\mkw\store::haveJog(self::BERJOG)) {
            parent::viewkarb();
        }
    }

    public function save()
    {
        if (!\mkw\store::haveJog(self::BERJOG)) {
            $this->jsonError(t('Nincs jogosultsága a művelethez.'), 403);
            return;
        }
        parent::save();
    }

    public function ront()
    {
        if (!\mkw\store::haveJog(self::BERJOG)) {
            $this->jsonError(t('Nincs jogosultsága a művelethez.'), 403);
            return;
        }
        /** @var Dolgozober|null $ber */
        $ber = $this->getRepo()->find($this->params->getIntRequestParam('id'));
        if (!$ber) {
            $this->jsonError(t('A rekord nem található.'), 404);
            return;
        }
        $ber->ront(\mkw\store::getLoggedInDolgozo());
        $this->getEm()->persist($ber);
        $this->getEm()->flush();
        echo json_encode($this->getListBodyRow($ber, 'edit'));
    }
}
