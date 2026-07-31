<?php

namespace Controllers;

use Services\KoltsegszamlaJSONImportService;
use Services\NAVKoltsegszamlaImportService;

class koltsegszamlaimportController extends \mkwhelpers\Controller
{

    public function view()
    {
        $idoszak = (new NAVKoltsegszamlaImportService())->kovetkezoIdoszak();
        $this->show($idoszak['tol'], $idoszak['ig']);
    }

    /**
     * Az importálás AJAX-ból indul (lásd js/admin/default/koltsegszamlaimport.js), és csak az
     * eredményblokkot adja vissza a következő időszak dátumaival együtt. Így a böngésző URL-je
     * nem változik, és az oldal frissítése sem indítja újra a letöltést.
     */
    public function process()
    {
        $service = new NAVKoltsegszamlaImportService();

        $tol = $this->datumParam('tol');
        $ig = $this->datumParam('ig');
        if (!$tol || !$ig) {
            $idoszak = $service->kovetkezoIdoszak();
            $this->sendEredmeny($tol ?: $idoszak['tol'], $ig ?: $idoszak['ig'], null, t('Hibás dátum.'));
            return;
        }

        try {
            $eredmeny = $service->import($tol, $ig);
        } catch (\Exception $e) {
            $this->sendEredmeny($tol, $ig, null, $e->getMessage());
            return;
        }

        $kovetkezo = $eredmeny['datummentve'] ? $service->kovetkezoIdoszak() : ['tol' => $tol, 'ig' => $ig];
        $this->sendEredmeny($kovetkezo['tol'], $kovetkezo['ig'], $eredmeny);
    }

    private function show(\DateTime $tol, \DateTime $ig)
    {
        $view = $this->createView('koltsegszamlaimport.tpl');
        $view->setVar('pagetitle', t('NAV bejövő számla import'));
        $view->setVar('toldatum', $tol->format(\mkw\store::$DateFormat));
        $view->setVar('igdatum', $ig->format(\mkw\store::$DateFormat));
        $view->setVar('maxnap', NAVKoltsegszamlaImportService::MAX_IDOSZAK_NAP);
        $view->setVar('figyelmeztetes', $this->beallitasFigyelmeztetes());
        $view->printTemplateResult();
    }

    /**
     * Az eredményblokk HTML-je és a következő import felkínált időszaka, JSON-ban.
     */
    private function sendEredmeny(\DateTime $tol, \DateTime $ig, ?array $eredmeny = null, ?string $hibauzenet = null)
    {
        $view = $this->createView('koltsegszamlaimport_eredmeny.tpl');
        $view->setVar('hibauzenet', $hibauzenet);
        $view->setVar('eredmeny', $eredmeny);

        echo json_encode([
            'toldatum' => $tol->format(\mkw\store::$DateFormat),
            'igdatum' => $ig->format(\mkw\store::$DateFormat),
            'html' => $view->getTemplateResult(),
        ]);
    }

    /**
     * A költség termék hiányában egyetlen számla sem importálható – szóljunk róla előre.
     */
    private function beallitasFigyelmeztetes(): ?string
    {
        try {
            (new KoltsegszamlaJSONImportService())->getKoltsegTermek();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        return null;
    }

    private function datumParam(string $nev): ?\DateTime
    {
        $ertek = trim($this->params->getStringRequestParam($nev));
        if ($ertek === '') {
            return null;
        }
        try {
            return \mkw\store::toDate($ertek);
        } catch (\Exception $e) {
            return null;
        }
    }

}
