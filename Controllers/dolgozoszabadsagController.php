<?php

namespace Controllers;

use Entities\Dolgozo;
use Entities\Dolgozoszabadsag;
use Traits\Munkanap;

class dolgozoszabadsagController extends \mkwhelpers\MattableController
{
    use Munkanap;

    /** the holidays of the listed page, loaded once; null = look them up per row */
    private $unnepnapok = null;

    public function __construct()
    {
        $this->setEntityName(Dolgozoszabadsag::class);
        $this->setKarbFormTplName('dolgozoszabadsagkarbform.tpl');
        $this->setKarbTplName('dolgozoszabadsagkarb.tpl');
        $this->setListBodyRowTplName('dolgozoszabadsaglista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct();
    }

    public function loadVars($t, $forKarb = false)
    {
        if (!$t) {
            $t = new Dolgozoszabadsag();
            $this->getEm()->detach($t);
        }
        $x = $this->getEntityFieldsArray($t);
        $x['datumtolstr'] = $t->getDatumtolStr();
        $x['datumigstr'] = $t->getDatumigStr();
        $x['dolgozo'] = $t->getDolgozoId();
        $x['dolgozonev'] = $t->getDolgozoNev();
        $x['tipusnev'] = $t->getTipusNev();
        $x['napok'] = $this->countNapok($t);
        return $x;
    }

    /**
     * @param \Entities\Dolgozoszabadsag $obj
     *
     * @return \Entities\Dolgozoszabadsag
     */
    protected function setFields($obj)
    {
        $obj = $this->setEntityFieldsFromRequest($obj);
        // egy napra elég a tól: az ig ilyenkor ugyanaz
        if (!$this->params->getStringRequestParam('datumig')) {
            $obj->setDatumig($this->params->getStringRequestParam('datumtol'));
        }
        $dolgozo = $this->getRepo(Dolgozo::class)->find($this->params->getIntRequestParam('dolgozo', 0));
        if ($dolgozo) {
            $obj->setDolgozo($dolgozo);
        }
        return $obj;
    }

    public function getlistbody()
    {
        $view = $this->createView('dolgozoszabadsaglista_tbody.tpl');

        $filter = new \mkwhelpers\FilterDescriptor();
        if ($this->params->getStringRequestParam('tolfilter')) {
            $filter->addFilter('datumig', '>=', \mkw\store::convDate($this->params->getStringRequestParam('tolfilter')));
        }
        if ($this->params->getStringRequestParam('igfilter')) {
            $filter->addFilter('datumtol', '<=', \mkw\store::convDate($this->params->getStringRequestParam('igfilter')));
        }
        $fv = $this->params->getIntRequestParam('dolgozofilter');
        if ($fv > 0) {
            $filter->addFilter('d.id', '=', $fv);
        }
        $fv = $this->params->getStringRequestParam('tipusfilter');
        if ($fv) {
            $filter->addFilter('tipus', '=', $fv);
        }

        $this->initPager($this->getRepo()->getCount($filter));

        $egyedek = $this->getRepo()->getWithJoins(
            $filter,
            $this->getOrderArray(),
            $this->getPager()->getOffset(),
            $this->getPager()->getElemPerPage()
        );

        $this->unnepnapok = $this->getUnnepnapokFor($egyedek);
        echo json_encode($this->loadDataToView($egyedek, 'egyedlista', $view));
    }

    /** Munkanapok száma, ahogy a szabadság kimutatás és a jelenléti ív is számolja. */
    private function countNapok(Dolgozoszabadsag $t)
    {
        $dolgozo = $t->getDolgozo();
        $tol = $t->getDatumtol();
        $ig = $t->getDatumig();
        if (!$dolgozo || !$tol || !$ig) {
            return '';
        }
        return $this->countMunkanapok($dolgozo, $tol, $ig, $this->unnepnapok ?? $this->getUnnepnapok($tol, $ig));
    }

    /** @param Dolgozoszabadsag[] $egyedek */
    private function getUnnepnapokFor(array $egyedek)
    {
        $tolok = array_filter(array_map(fn($e) => $e->getDatumtol(), $egyedek));
        $igek = array_filter(array_map(fn($e) => $e->getDatumig(), $egyedek));
        return ($tolok && $igek) ? $this->getUnnepnapok(min($tolok), max($igek)) : [];
    }

    public function viewlist()
    {
        $view = $this->createView('dolgozoszabadsaglista.tpl');

        $view->setVar('pagetitle', t('Szabadságok'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $dolgozo = new dolgozoController();
        $view->setVar('dolgozolist', $dolgozo->getSelectList(0));
        $view->setVar('tipuslist', $this->getTipusSelectList());
        $view->printTemplateResult(false);
    }

    protected function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Szabadság'));
        $view->setVar('formaction', '/admin/dolgozoszabadsag/save');
        $view->setVar('oper', $oper);
        $record = $this->getRepo()->findWithJoins($id);
        $view->setVar('egyed', $this->loadVars($record, true));
        $dolgozo = new dolgozoController();
        $view->setVar('dolgozolist', $dolgozo->getSelectList(($record ? $record->getDolgozoId() : 0)));
        $view->setVar('tipuslist', $this->getTipusSelectList($record ? $record->getTipus() : Dolgozoszabadsag::TIPUS_SZABADSAG));
        return $view->getTemplateResult();
    }

    public function getTipusSelectList($selid = null)
    {
        $res = [];
        foreach (Dolgozoszabadsag::getTipusok() as $id => $caption) {
            $res[] = ['id' => $id, 'caption' => t($caption), 'selected' => ($id === $selid)];
        }
        return $res;
    }
}
