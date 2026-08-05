<?php

namespace Controllers;

use Entities\Bizonylatfej;
use Entities\Bizonylattipus;
use Entities\Penztarbizonylattetel;
use mkw\store;

class penztarbizonylattetelController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(Penztarbizonylattetel::class);
//		$this->setKarbFormTplName('?howto?karbform.tpl');
//		$this->setKarbTplName('?howto?karb.tpl');
        $this->setListBodyRowTplName('penztarbizonylattetellista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct();
    }

    public function loadVars($t, $forKarb = false)
    {
        $oper = $this->params->getStringRequestParam('oper');
        $jogcim = new jogcimController();
        $x = [];
        if (!$t) {
            $t = new \Entities\Penztarbizonylattetel();
            $this->getEm()->detach($t);
            $x['id'] = store::createUID();
            $x['oper'] = 'add';
        } else {
            $x['id'] = $t->getId();
            $x['oper'] = 'edit';
        }
        $x['brutto'] = $t->getBrutto();
        $x['jogcim'] = $t->getJogcimId();
        $x['jogcimnev'] = $t->getJogcimnev();
        $x['hivatkozottdatumstr'] = $t->getHivatkozottdatumStr();
        $x['hivatkozottbizonylat'] = $t->getHivatkozottbizonylat();
        $x['hivatkozottbizonylatlink'] = $this->getHivatkozottListaUrl($x['hivatkozottbizonylat']);
        $x['szoveg'] = $t->getSzoveg();
        $x['nemrossz'] = !$t->getRontott();

        $fej = $t->getBizonylatfej();
        $x['fejid'] = $t->getBizonylatfejId();
        $x['keltstr'] = $fej?->getKeltStr();
        $x['irany'] = $fej?->getIrany();
        $x['erbizonylatszam'] = $fej?->getErbizonylatszam();
        $x['partnernev'] = $fej?->getPartnernev();
        $x['penztarnev'] = $fej?->getPenztarnev();
        $x['valutanemnev'] = $fej?->getValutanemnev();
        $x['megjegyzes'] = $fej?->getMegjegyzes();

        if ($forKarb) {
            $x['jogcimlist'] = $jogcim->getSelectList($t->getJogcimId());
        }

        return $x;
    }

    protected function setFields($obj)
    {
        return $obj;
    }

    /**
     * A hivatkozott bizonylat száma egy Bizonylatfej id. Ha a bizonylat megvan, a
     * típusának megfelelő, erre a számra szűrt listanézet URL-jét adjuk. Kézzel beírt
     * vagy időközben törölt hivatkozásnál null, ilyenkor a lista sima szövegként
     * mutatja a számot.
     */
    private function getHivatkozottListaUrl($bizonylatszam)
    {
        if (!$bizonylatszam) {
            return null;
        }
        return $this->getRepo(Bizonylatfej::class)->find($bizonylatszam)?->getListaUrl();
    }

    public function getemptyrow()
    {
        $view = $this->createView('penztarbizonylattetelkarb.tpl');

        $tetel = $this->loadVars(null, true);
        $view->setVar('tetel', $tetel);

        $bt = $this->getRepo(Bizonylattipus::class)->find('penztar');
        $bt->setTemplateVars($view);

        $res = [
            'html' => $view->getTemplateResult(),
            'id' => $tetel['id']
        ];
        echo json_encode($res);
    }

    public function viewselect()
    {
        $view = $this->createView('penztarbizonylattetellista.tpl');

        $view->setVar('pagetitle', t('Pénztárbizonylat tételek'));
        $this->setVars($view);
        $view->printTemplateResult();
    }

    public function viewlist()
    {
        $view = $this->createView('penztarbizonylattetellista.tpl');

        $view->setVar('pagetitle', t('Pénztárbizonylat tételek'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());

        $partner = new partnerController();
        $valutanem = new valutanemController();
        $penztar = new penztarController();
        $jogcim = new jogcimController();

        $view->setVar('partnerlist', $partner->getSelectList());
        $view->setVar('valutanemlist', $valutanem->getSelectList());
        $view->setVar('penztarlist', $penztar->getSelectList());
        $view->setVar('jogcimlist', $jogcim->getSelectList());

        $this->setVars($view);
        $view->printTemplateResult();
    }

    public function getlistbody()
    {
        $filter = new \mkwhelpers\FilterDescriptor();

        $idfilter = $this->params->getStringRequestParam('idfilter', '');
        if ($idfilter) {
            $filter->addFilter('bf.id', 'LIKE', '%' . $idfilter);
        }

        $datumtolfilter = $this->params->getStringRequestParam('datumtolfilter', '');
        if ($datumtolfilter) {
            $filter->addFilter('bf.kelt', '>=', $datumtolfilter);
        }

        $datumigfilter = $this->params->getStringRequestParam('datumigfilter', '');
        if ($datumigfilter) {
            $filter->addFilter('bf.kelt', '<=', $datumigfilter);
        }

        $erbizonylatszamfilter = $this->params->getStringRequestParam('erbizonylatszamfilter', '');
        if ($erbizonylatszamfilter) {
            $filter->addFilter('bf.erbizonylatszam', 'LIKE', '%' . $erbizonylatszamfilter . '%');
        }

        $valutanemfilter = $this->params->getIntRequestParam('valutanemfilter', 0);
        if ($valutanemfilter) {
            $filter->addFilter('bf.valutanem', '=', $valutanemfilter);
        }

        $partnerfilter = $this->params->getIntRequestParam('partnerfilter', 0);
        if ($partnerfilter) {
            $filter->addFilter('bf.partner', '=', $partnerfilter);
        }

        $penztarfilter = $this->params->getIntRequestParam('penztarfilter', 0);
        if ($penztarfilter) {
            $filter->addFilter('bf.penztar', '=', $penztarfilter);
        }

        $iranyfilter = $this->params->getIntRequestParam('iranyfilter', 0);
        switch ($iranyfilter) {
            case 1:
                $filter->addFilter('bf.irany', '=', 1);
                break;
            case -1:
                $filter->addFilter('bf.irany', '=', -1);
                break;
        }

        $jogcimfilter = $this->params->getIntRequestParam('jogcimfilter', 0);
        if ($jogcimfilter) {
            $filter->addFilter('jogcim', '=', $jogcimfilter);
        }

        $hivatkozottbizonylatfilter = $this->params->getStringRequestParam('hivatkozottbizonylatfilter', '');
        if ($hivatkozottbizonylatfilter) {
            $filter->addFilter('hivatkozottbizonylat', 'LIKE', '%' . $hivatkozottbizonylatfilter . '%');
        }

        $rontottfilter = $this->params->getIntRequestParam('bizonylatrontottfilter', 0);
        switch ($rontottfilter) {
            case 1:
                $filter->addFilter('rontott', '=', false);
                break;
            case 2:
                $filter->addFilter('rontott', '=', true);
                break;
        }

        $this->initPager($this->getRepo()->getCount($filter));

        $egyedek = $this->getRepo()->getWithJoins(
            $filter,
            $this->getOrderArray(),
            $this->getPager()->getOffset(),
            $this->getPager()->getElemPerPage()
        );

        $view = $this->createView('penztarbizonylattetellista_tbody.tpl');
        $this->setVars($view);

        echo json_encode($this->loadDataToView($egyedek, 'egyedlista', $view));
    }

    public function setVars($view)
    {
        $bt = $this->getRepo(Bizonylattipus::class)->find('penztar');
        if ($bt) {
            $bt->setTemplateVars($view);
        }
    }

}