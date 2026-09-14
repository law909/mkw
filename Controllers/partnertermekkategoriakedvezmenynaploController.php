<?php

namespace Controllers;

use Entities\PartnerTermekkategoriaKedvezmenyNaplo;

/**
 * A partner termékkategória kedvezmény napló. Csak nézni lehet: a sorokat a
 * Listeners\PartnerTermekkategoriaKedvezmenyListener írja.
 */
class partnertermekkategoriakedvezmenynaploController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(PartnerTermekkategoriaKedvezmenyNaplo::class);
        $this->setListBodyRowTplName('partnertermekkategoriakedvezmenynaplolista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct();
    }

    /**
     * @param PartnerTermekkategoriaKedvezmenyNaplo $t
     */
    public function loadVars($t, $forKarb = false)
    {
        return [
            'id' => $t->getId(),
            'createdstr' => $t->getCreatedStr(),
            'partnerid' => $t->getPartnerId(),
            'partnernev' => $t->getPartnernev(),
            'termekfanev' => $t->getTermekfanev(),
            'regikedvezmeny' => $t->getRegikedvezmeny() === null ? null : $t->getRegikedvezmeny() * 1,
            'ujkedvezmeny' => $t->getUjkedvezmeny() === null ? null : $t->getUjkedvezmeny() * 1,
            'esemeny' => $t->getEsemenyNev(),
            'modositonev' => $t->getModositonev(),
        ];
    }

    protected function setFields($obj)
    {
        throw new \RuntimeException('A kedvezmény napló nem szerkeszthető.');
    }

    public function getlistbody()
    {
        $view = $this->createView('partnertermekkategoriakedvezmenynaplolista_tbody.tpl');

        $filter = new \mkwhelpers\FilterDescriptor();
        if ($this->params->getStringRequestParam('partnerfilter') !== '') {
            $filter->addFilter('partnernev', 'LIKE', '%' . $this->params->getStringRequestParam('partnerfilter') . '%');
        }
        if ($this->params->getStringRequestParam('termekfafilter') !== '') {
            $filter->addFilter('termekfanev', 'LIKE', '%' . $this->params->getStringRequestParam('termekfafilter') . '%');
        }
        $datumtol = $this->params->getStringRequestParam('datumtolfilter');
        if ($datumtol) {
            $filter->addFilter('created', '>=', \mkw\store::convDate($datumtol));
        }
        $datumig = $this->params->getStringRequestParam('datumigfilter');
        if ($datumig) {
            // a nap végéig: a created időpontot is tárol
            $filter->addFilter('created', '<', date('Y-m-d', strtotime(\mkw\store::convDate($datumig) . ' +1 day')));
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
        $view = $this->createView('partnertermekkategoriakedvezmenynaplolista.tpl');
        $view->setVar('pagetitle', t('Partner kategória kedvezmény napló'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname)
    {
        return '';
    }
}
