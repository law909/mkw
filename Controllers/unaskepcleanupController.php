<?php

namespace Controllers;

use Services\MediatarService;
use Services\UnasKepService;
use Services\UnasService;
use Services\UnasTermekImportService;

/**
 * UNAS képtakarítás: az UNAS képmappájának árva fájljai (a dedup által lemezen hagyott
 * duplikátumok, a régi néven letöltött és a törölt termékek képei).
 *
 * A mappa szándékosan nem választható: élőnek csak az számít, amire az adatbázis hivatkozik,
 * így egy sablonból vagy CSS-ből hivatkozott képet más mappában árvának látnánk. Másik mappához
 * ott a parancssori `unaskepcleanup.php --dir=…`.
 */
class unaskepcleanupController extends \mkwhelpers\Controller
{

    public function view()
    {
        $view = $this->createView('unaskepcleanup.tpl');
        $view->setVar('pagetitle', t('UNAS képtakarítás'));
        $view->setVar('mappa', UnasService::getKepPath());
        $view->setVar('fut', (new UnasTermekImportService())->isLocked());
        $view->setVar('figyelmeztetes', $this->settingsWarning());
        $view->printTemplateResult();
    }

    /** Riport és törlés is ide jön: a `torles` nélküli hívás csak számol. */
    public function run()
    {
        $apply = $this->params->getBoolRequestParam('torles', false);
        if ($apply && (new UnasTermekImportService())->isLocked()) {
            $this->json(['ok' => false, 'hiba' => t('Éppen fut egy UNAS import, a takarítás megvárja a végét.')]);
            return;
        }

        // az adatbázis végigpásztázása nagy törzsön percekig is eltarthat
        @set_time_limit(600);
        try {
            $report = (new UnasKepService())->cleanupOrphans(
                $apply,
                $this->params->getBoolRequestParam('force', false)
            );
        } catch (\Exception $e) {
            $this->json(['ok' => false, 'hiba' => $e->getMessage()]);
            return;
        }

        $view = $this->createView('unaskepcleanup_eredmeny.tpl');
        $view->setVar('riport', $report);
        $view->setVar('torles', $apply);
        $view->setVar('meret', [
            'osszes' => MediatarService::formatSize($report['megtartva_meret'] + $report['arva_meret']),
            'megtartva' => MediatarService::formatSize($report['megtartva_meret']),
            'arva' => MediatarService::formatSize($report['arva_meret']),
            'torolve' => MediatarService::formatSize($report['torolve_meret']),
        ]);
        $this->json(['ok' => true, 'html' => $view->getTemplateResult(), 'megallt' => $report['megallt']]);
    }

    // ------------------------------------------------------------------

    private function settingsWarning()
    {
        if (!\mkw\store::isUnas()) {
            return t('Az UNAS integráció nincs bekapcsolva (setup.ini: unas = 1).');
        }
        return null;
    }

    private function json(array $data)
    {
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

}
