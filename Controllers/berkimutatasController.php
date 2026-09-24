<?php

namespace Controllers;

use Entities\Dolgozober;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

/** Paid wages over a period, by year/month, employee and pay title; voided lines never count. */
class berkimutatasController extends \mkwhelpers\Controller
{

    use \Traits\GroupedReport;
    use \Traits\SavedReportViews;

    // the same right as the pay lines themselves
    private const BERJOG = 40;

    private const MAXSZINT = 3;

    protected const KIMUTATAS = 'berkimutatas';

    /** Grouping level => [id key, label key] of the repository rows; the two periods are one dimension. */
    private const DIMENZIOK = [
        'ev' => ['idoszak', 'idoszak'],
        'honap' => ['idoszak', 'idoszak'],
        'dolgozo' => ['dolgozoid', 'dolgozonev'],
        'berjogcim' => ['berjogcimid', 'berjogcimnev'],
    ];

    public function view()
    {
        if (!\mkw\store::haveJog(self::BERJOG)) {
            return;
        }
        $view = $this->createView('berkimutatas.tpl');
        $view->setVar('pagetitle', t('Bér kimutatás'));
        $view->setVar('toldatum', date('Y.01.01'));
        $view->setVar('igdatum', date(\mkw\store::$DateFormat));
        // a former employee's pay is still reported
        $view->setVar('dolgozolist', (new dolgozoController())->getSelectList(0, false));
        $view->setVar('szintlist', $this->getSzintSelectList());
        $view->setVar('maxszint', self::MAXSZINT);
        $view->printTemplateResult(false);
    }

    protected function checkNezetJog(): bool
    {
        if (!\mkw\store::haveJog(self::BERJOG)) {
            $this->jsonError(t('Nincs jogosultsága a művelethez.'), 403);
            return false;
        }
        return true;
    }

    protected function getNezetBeallitas(): array
    {
        return [
            'szint' => $this->getSzintek(),
            'megjelenites' => $this->isPivot() ? 'kereszttabla' : 'lista',
        ];
    }

    private function isPivot(): bool
    {
        return $this->params->getStringRequestParam('megjelenites') === 'kereszttabla';
    }

    /** The grouping levels of the request in order: known dimensions only, each once, at most MAXSZINT. */
    private function getSzintek(): array
    {
        $szintek = [];
        $dimenziok = [];
        foreach ($this->params->getArrayRequestParam('szint') as $szint) {
            $dimenzio = self::DIMENZIOK[$szint][0] ?? null;
            if (!$dimenzio || isset($dimenziok[$dimenzio])) {
                continue;
            }
            $dimenziok[$dimenzio] = true;
            $szintek[] = $szint;
            if (count($szintek) === self::MAXSZINT) {
                break;
            }
        }
        return $szintek;
    }

    private static function getSzintCaptions(): array
    {
        return [
            'ev' => t('Év'),
            'honap' => t('Hónap'),
            'dolgozo' => t('Dolgozó'),
            'berjogcim' => t('Jogcím'),
        ];
    }

    private function getSzintSelectList(): array
    {
        $res = [];
        foreach (self::getSzintCaptions() as $szint => $caption) {
            $res[] = ['id' => $szint, 'caption' => mb_strtolower($caption), 'dim' => self::DIMENZIOK[$szint][0]];
        }
        return $res;
    }

    private function getGroupLevels(array $szintek): array
    {
        $captions = self::getSzintCaptions();
        $levels = [];
        foreach ($szintek as $szint) {
            [$id, $label] = self::DIMENZIOK[$szint];
            $levels[] = ['id' => $id, 'label' => $label, 'caption' => $captions[$szint]];
        }
        return $levels;
    }

    /** $periodLast: the period sorted after the other levels, as pivotRows() needs it. */
    private function getRows(array $szintek, bool $periodLast = false): array
    {
        if ($periodLast) {
            usort($szintek, fn($a, $b) => (self::DIMENZIOK[$a][0] === 'idoszak') <=> (self::DIMENZIOK[$b][0] === 'idoszak'));
        }
        return $this->getRepo(Dolgozober::class)->getKimutatas(
            $this->params->getStringRequestParam('tol'),
            $this->params->getStringRequestParam('ig'),
            $szintek,
            $this->params->getIntRequestParam('dolgozo')
        );
    }

    /** The table and the chart payload of the request, for refresh() and pdf(). */
    private function buildReport(): array
    {
        $szintek = $this->getSzintek();
        $levels = $this->getGroupLevels($szintek);
        $rows = $this->getRows($szintek, $this->isPivot());
        $valueheader = t('Összeg HUF');
        $pivot = $this->isPivot() ? $this->pivotRows($rows, $levels, 'ertek') : null;
        if ($pivot) {
            $html = $this->renderPivot($pivot, false, $valueheader, 0);
        } else {
            // the last grouping column is the row label, the ones above it become group headers
            $tableLevels = $levels;
            $rowLevel = array_pop($tableLevels);
            $view = $this->createView('arbevetellistatetel.tpl');
            $view->setVar('items', $this->buildTableItems($rows, $tableLevels, ['ertek']));
            $view->setVar('rowlevel', $rowLevel);
            $view->setVar('levelcount', count($tableLevels));
            $view->setVar('valueheader', $valueheader);
            $view->setVar('decimals', 0);
            $view->setVar('osszesen', array_sum(array_column($rows, 'ertek')));
            $html = $view->getTemplateResult();
        }
        return [
            'html' => $html,
            'chart' => $this->buildLevelChart($rows, $levels, 'ertek', t('Bér'), 'Ft'),
        ];
    }

    public function refresh()
    {
        if (!\mkw\store::haveJog(self::BERJOG)) {
            $this->jsonError(t('Nincs jogosultsága a művelethez.'), 403);
            return;
        }
        header('Content-Type: application/json');
        echo json_encode($this->buildReport());
    }

    public function pdf()
    {
        if (!\mkw\store::haveJog(self::BERJOG)) {
            return;
        }
        $szurok = [[t('Időszak'), $this->params->getStringRequestParam('tol') . ' – ' . $this->params->getStringRequestParam('ig')]];
        $dolgozo = $this->getRepo(\Entities\Dolgozo::class)->find($this->params->getIntRequestParam('dolgozo'));
        if ($dolgozo) {
            $szurok[] = [t('Dolgozó'), $dolgozo->getNev()];
        }
        $szurok[] = $this->getGroupingSzuro($this->getGroupLevels($this->getSzintek()));
        $this->outputReportPdf(t('Bér kimutatás'), $szurok, $this->buildReport(), 'berkimutatas.pdf');
    }

    public function export()
    {
        if (!\mkw\store::haveJog(self::BERJOG)) {
            return;
        }
        $szintek = $this->getSzintek();
        $rows = $this->getRows($szintek);

        $fejlec = array_column($this->getGroupLevels($szintek), 'caption', 'label');
        $fejlec['ertek'] = t('Összeg HUF');

        $excel = new Spreadsheet();
        $sheet = $excel->getActiveSheet();
        $sheet->fromArray(array_values($fejlec), null, 'A1');
        $sor = 2;
        foreach ($rows as $row) {
            $sheet->fromArray(array_map(fn($key) => $row[$key], array_keys($fejlec)), null, 'A' . $sor);
            $sor++;
        }

        $filename = uniqid('berkimutatas') . '.xlsx';
        $filepath = \mkw\store::storagePath($filename);
        IOFactory::createWriter($excel, 'Xlsx')->save($filepath);

        header('Cache-Control: private');
        header('Content-Type: application/stream');
        header('Content-Length: ' . filesize($filepath));
        header('Content-Disposition: attachment; filename=' . $filename);
        readfile($filepath);
        \unlink($filepath);
    }
}
