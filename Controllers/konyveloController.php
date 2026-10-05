<?php

namespace Controllers;

use Entities\Bizonylatfej;
use Entities\Penztarbizonylatfej;
use Services\PenzmozgasService;

/**
 * Könyvelői felület: a számlák és az előlegszámlák csak olvasható listája, PDF-fel és a
 * kapcsolódó bank- és pénztárbizonylatokkal. Mentő útvonala nincs.
 */
class konyveloController extends \mkwhelpers\MattableController
{

    private const TIPUSOK = [
        'szamla' => 'Számlák',
        'elolegszamla' => 'Előlegszámlák',
    ];

    private $tipus;

    public function __construct()
    {
        $this->setEntityName(Bizonylatfej::class);
        $this->setListBodyRowTplName('konyvelolista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct();
        $this->tipus = $this->params->getStringParam('tipus');
        if (!array_key_exists($this->tipus, self::TIPUSOK)) {
            $this->tipus = 'szamla';
        }
    }

    protected function loadVars($t, $forKarb = false)
    {
        $x = [];
        $x['id'] = $t->getId();
        $x['partnernev'] = $t->getPartnernev();
        $x['partneradoszam'] = $t->getPartneradoszam();
        $x['keltstr'] = $t->getKeltStr();
        $x['teljesitesstr'] = $t->getTeljesitesStr();
        $x['esedekessegstr'] = $t->getEsedekessegStr();
        $x['fizmodnev'] = $t->getFizmodnev();
        $x['netto'] = $t->getNetto();
        $x['afa'] = $t->getAfa();
        $x['brutto'] = $t->getBrutto();
        $x['valutanemnev'] = $t->getValutanemnev();
        $x['bruttohuf'] = $t->getBruttohuf();
        $x['rontott'] = $t->getRontott();
        $x['storno'] = $t->getStorno();
        $x['stornozott'] = $t->getStornozott();

        $x['penzmozgasok'] = [];
        foreach ((new PenzmozgasService())->getEloPenzmozgas($t->getId()) as $penzmozgas) {
            $penztar = $penzmozgas instanceof Penztarbizonylatfej;
            $x['penzmozgasok'][] = [
                'id' => $penzmozgas->getId(),
                'tipus' => $penztar ? t('Pénztár') : t('Bank'),
                'keltstr' => $penzmozgas->getKeltStr(),
                'brutto' => $penzmozgas->getBrutto(),
                'printurl' => ($penztar ? '/admin/penztarbizonylatfej/print?id=' : '/admin/bankbizonylatfej/print?id=')
                    . urlencode($penzmozgas->getId()),
            ];
        }
        return $x;
    }

    protected function setFields($obj)
    {
        return $obj;
    }

    public function viewlist()
    {
        $view = $this->createView('konyvelolista.tpl');
        $view->setVar('pagetitle', t(self::TIPUSOK[$this->tipus]));
        $view->setVar('tipus', $this->tipus);
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->printTemplateResult();
    }

    // a /admin/bizonylatfej/pdf letöltésként küldi; a könyvelő a böngésző PDF nézőjében nyomtat
    public function pdf()
    {
        $id = $this->params->getStringRequestParam('id');
        /** @var Bizonylatfej $o */
        $o = $this->getRepo()->find($id);
        if (!$o || !array_key_exists($o->getBizonylattipusId(), self::TIPUSOK)) {
            return;
        }
        $pdf = (new \Services\BizonylatPrintService())->createEngine($id);
        if (!$pdf) {
            return;
        }
        if (method_exists($pdf, 'inline')) {
            $pdf->inline(\mkw\store::urlize($id) . '.pdf');
        } else {
            $pdf->send(\mkw\store::urlize($id) . '.pdf');
        }
    }

    public function getlistbody()
    {
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('bizonylattipus', '=', $this->tipus);

        $f = $this->params->getStringRequestParam('idfilter');
        if ($f) {
            $filter->addFilter('id', 'LIKE', '%' . $f . '%');
        }
        $f = $this->params->getStringRequestParam('vevonevfilter');
        if ($f) {
            $filter->addFilter('partnernev', 'LIKE', '%' . $f . '%');
        }
        $mezo = match ($this->params->getIntRequestParam('datumtipusfilter')) {
            2 => 'teljesites',
            3 => 'esedekesseg',
            default => 'kelt',
        };
        $f = $this->params->getStringRequestParam('datumtolfilter');
        if ($f) {
            $filter->addFilter($mezo, '>=', $f);
        }
        $f = $this->params->getStringRequestParam('datumigfilter');
        if ($f) {
            $filter->addFilter($mezo, '<=', $f);
        }
        switch ($this->params->getIntRequestParam('bizonylatrontottfilter')) {
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

        $view = $this->createView('konyvelolista_tbody.tpl');
        echo json_encode($this->loadDataToView($egyedek, 'egyedlista', $view));
    }

}
