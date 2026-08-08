<?php

namespace Controllers;

use Services\UnasService;
use Services\UnasTermekImportService;

/**
 * UNAS termékimport admin képernyő. A futás kötegelve, AJAX-ból megy
 * (js/admin/default/unastermekimport.js): download → processBatch … → report.
 */
class unastermekimportController extends \mkwhelpers\Controller
{

    public function view()
    {
        $service = new UnasTermekImportService();

        $view = $this->createView('unastermekimport.tpl');
        $view->setVar('pagetitle', t('UNAS termékimport'));
        $view->setVar('multilang', (bool)\mkw\store::isMultilang());
        $view->setVar('nyelv', UnasService::getLang());
        $view->setVar('nyelvl1', UnasService::getLangL1());
        $view->setVar('fut', $service->isLocked());
        $view->setVar('utolsoletoltes', $service->getLastDownload());
        $view->setVar('figyelmeztetes', $this->settingsWarning());
        $view->printTemplateResult();
    }

    public function testConnection()
    {
        $result = (new UnasService($this->testSettings()))->testConnection();
        $this->json([
            'ok' => $result['ok'],
            'hiba' => $result['hiba'],
            'shopid' => $result['shopid'],
            'subscription' => $result['subscription'],
            'permissions' => $result['permissions'],
            'hianyzo' => $result['hianyzo'],
        ]);
    }

    /** A válaszban a fejléc is megy: abból derül ki, jött-e "Kép link" oszlop. */
    public function download()
    {
        try {
            $result = (new UnasTermekImportService())->downloadProductDB($this->options());
        } catch (\Exception $e) {
            $this->json(['ok' => false, 'hiba' => $e->getMessage()]);
            return;
        }
        $this->json(['ok' => true] + $result);
    }

    /** A böngésző addig hívja, amíg a `kesz` igaz nem lesz. */
    public function processBatch()
    {
        try {
            $result = (new UnasTermekImportService())->processBatch(
                $this->params->getStringRequestParam('fajl'),
                $this->params->getIntRequestParam('tol', 0)
            );
        } catch (\Exception $e) {
            $this->json(['ok' => false, 'hiba' => $e->getMessage()]);
            return;
        }
        $this->json(['ok' => true] + $result);
    }

    public function report()
    {
        $file = $this->params->getStringRequestParam('fajl');
        try {
            $report = (new UnasTermekImportService())->getReport($file);
        } catch (\Exception $e) {
            $this->json(['ok' => false, 'hiba' => $e->getMessage()]);
            return;
        }

        $view = $this->createView('unastermekimport_eredmeny.tpl');
        $view->setVar('riport', $report);
        $view->setVar('fajl', $file);
        $view->setVar('csvurl', '/admin/unastermekimport/nemtalalt?fajl=' . rawurlencode($file));
        $view->setVar('naplourl', '/admin/unastermekimport/letoltottfajl?fajl=' . rawurlencode($file));
        $view->setVar('hianyzooszlopok', implode(', ', $report['hianyzo_oszlopok'] ?? []));
        $view->setVar('reszletek', $this->reportDetails($report));
        $this->json(['ok' => true, 'html' => $view->getTemplateResult()]);
    }

    /** A lista pár száz elemű minta, a számláló a teljes darabszám. */
    private function reportDetails(array $report)
    {
        $blocks = [
            ['lista' => 'nem_talalt', 'db' => 'nem_talalt_db', 'cim' => t('Nem található a törzsben')],
            ['lista' => 'ketertelmu', 'db' => 'ketertelmu_db', 'cim' => t('Kétértelmű találat')],
            ['lista' => 'unasid_utkozes', 'db' => 'unasid_utkozes_db', 'cim' => t('UNAS azonosító felülírva (más azonosító állt ott)')],
            ['lista' => 'harom_tulajdonsagu', 'db' => 'harom_tulajdonsagu_db', 'cim' => t('3 tulajdonságos termékek (a változat-párosítás kimaradt)')],
            ['lista' => 'valtozat_nem_talalt', 'db' => 'valtozat_nem_talalt_db', 'cim' => t('Nem párosítható változat-kombinációk')],
            ['lista' => 'valtozat_nincs_mkw', 'db' => 'valtozat_nincs_mkw_db', 'cim' => t('Az UNAS terméknek van változata, nálunk nincs')],
            ['lista' => 'mkw_valtozat_parositatlan', 'db' => 'mkw_valtozat_parositatlan_db', 'cim' => t('Párosítatlan MKW változatok')],
            ['lista' => 'cikkszam_csere', 'db' => 'cikkszam_csere_db', 'cim' => t('Csak aláhúzás → kötőjel cserével találtuk meg')],
            ['lista' => 'kep_hiba', 'db' => 'kep_hiba_db', 'cim' => t('Képhibák')],
        ];

        $result = [];
        foreach ($blocks as $block) {
            $list = $report[$block['lista']] ?? [];
            if (!$list) {
                continue;
            }
            $total = (int)($report[$block['db']] ?? count($list));
            $result[] = [
                'cim' => $block['cim'],
                'lista' => $list,
                'osszes' => $total,
                'csonkolt' => $total > count($list),
            ];
        }
        return $result;
    }

    public function notFoundCsv()
    {
        try {
            $abs = (new UnasTermekImportService())->getNotFoundCsvPath($this->params->getStringRequestParam('fajl'));
        } catch (\Exception $e) {
            echo $e->getMessage();
            return;
        }
        if (!$abs) {
            echo t('A lista nem található.');
            return;
        }
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="unas_nem_talalt.csv"');
        header('Pragma: no-cache');
        header('Expires: 0');
        readfile($abs);
    }

    /** A fájl webről tiltva van (storage/logs/.htaccess), ezért session-védett végponton adjuk. */
    public function downloadedFile()
    {
        $service = new UnasTermekImportService();
        try {
            $file = $service->checkedFile($this->params->getStringRequestParam('fajl'));
        } catch (\Exception $e) {
            echo $e->getMessage();
            return;
        }
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $file . '"');
        header('Pragma: no-cache');
        header('Expires: 0');
        readfile(\mkw\store::logsPath($file));
    }

    /** Beragadt zárolás feloldása. */
    public function stop()
    {
        (new UnasTermekImportService())->unlock();
        $this->json(['ok' => true]);
    }

    // ------------------------------------------------------------------

    /**
     * A setup gombja a MÉG NEM MENTETT kulcsot küldi (az UNAS 5 sikertelen logint enged óránként);
     * kulcs nélkül a mentett beállítás marad.
     */
    private function testSettings()
    {
        $key = trim($this->params->getStringRequestParam('unasapikey'));
        if ($key === '') {
            return [];
        }
        $url = trim($this->params->getStringRequestParam('unasapiurl'));
        $settings = ['apikey' => $key];
        if ($url !== '') {
            $settings['apiurl'] = $url;
        }
        return $settings;
    }

    private function options()
    {
        return [
            'nyelvsuffix' => $this->params->getStringRequestParam('nyelvsuffix') === '_l1' ? '_l1' : '',
            'szarazfutas' => $this->params->getBoolRequestParam('szarazfutas', false),
            'inkrementalis' => $this->params->getBoolRequestParam('inkrementalis', false),
            'ujraletoltes' => $this->params->getBoolRequestParam('ujraletoltes', false),
            'riportujra' => $this->params->getBoolRequestParam('riportujra', false),
            'sortol' => $this->params->getIntRequestParam('sortol', 0),
            'sorig' => $this->params->getIntRequestParam('sorig', 0),
            'editleiras' => $this->params->getBoolRequestParam('editleiras', false),
            'editseo' => $this->params->getBoolRequestParam('editseo', false),
            'kepek' => $this->params->getBoolRequestParam('kepek', false),
            'kepekujra' => $this->params->getBoolRequestParam('kepekujra', false),
            'kepforras' => $this->params->getStringRequestParam('kepforras', 'auto'),
            'limitnum' => $this->params->getIntRequestParam('limitnum', 0),
        ];
    }

    /** Előre szóljunk, ne az első hibából derüljön ki – a login 5 hiba után 1 órára kizár. */
    private function settingsWarning()
    {
        if (!\mkw\store::isUnas()) {
            return t('Az UNAS integráció nincs bekapcsolva (setup.ini: unas = 1).');
        }
        if (!UnasService::isEnabled()) {
            return t('Nincs beállítva az UNAS API kulcs. Beállítások → UNAS fül.');
        }
        return null;
    }

    private function json(array $data)
    {
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

}
