<?php

namespace Controllers;

class bizsablonfejController extends bizonylatfejController
{

    public function __construct()
    {
        parent::__construct();
        $this->setBiztipus('bizsablon');
        $this->setPageTitle('Biz. sablon');
        $this->setPluralPageTitle('Biz. sablonok');
        $this->getRepo()->addToBatches(['szamlazas' => 'Számlázás']);
    }

    public function setVars($view)
    {
        parent::setVars($view);
        $view->setVar('datumtolfilter', null);
    }

    public function getszamlakarb()
    {
        $megrendszam = $this->params->getStringRequestParam('id');
        $szamlac = new SzamlafejController();
        $szamlac->getkarb('bizonylatfejkarb.tpl', $megrendszam, 'add');
    }

    /**
     * Csoportos számlázás: a kijelölt sablonokból, kijelölés híján a rendszeresekből számla
     * készül. Kérésre a kész számlák ki is mennek emailben; a kiküldött számla nyomtatottá
     * válik, és ezzel megy a NAV beküldés is — külön NAV kapcsoló ezért nincs.
     */
    public function szamlazas()
    {
        $mennyiseg = $this->params->getRequestParam('mennyiseg', null);
        $svc = new \Services\BizsablonSzamlazasService();
        $res = $svc->createSzamlak(
            $this->params->getArrayRequestParam('ids'),
            $this->params->getStringRequestParam('tetelnevtoldat'),
            is_null($mennyiseg) ? null : (float)str_replace(',', '.', $mennyiseg),
            $this->params->getBoolRequestParam('teljesitesazesedekesseg')
        );

        if ($this->params->getBoolRequestParam('sendemail')) {
            foreach ($res['szamlaszamok'] as $szamlaszam) {
                $this->sendPDFTo($szamlaszam);
                $hiba = $this->getPrintService()->setNyomtatva($szamlaszam, true);
                if ($hiba) {
                    $res['hibak'][] = $szamlaszam . ': ' . strip_tags($hiba);
                }
            }
        }

        if ($res['hibak']) {
            $res['uzenet'] .= ' Hibák: ' . implode('; ', $res['hibak']);
        }
        echo json_encode($res);
    }
}
