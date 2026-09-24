<?php

namespace Controllers;

use Entities\Bizonylatfej;
use Entities\BizonylatfejRepository;
use Entities\Valutanem;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class arbevetellistaController extends \mkwhelpers\Controller
{

    use \Traits\GroupedReport;

    private const MAXSZINT = 4;

    public function view()
    {
        $this->showView('arbevetellista.tpl', t('Árbevétel kimutatás'), BizonylatfejRepository::ARBEVETEL_BIZONYLATTIPUSOK);
    }

    /** @param array $bizonylattipusok ticked by default in the document type filter (superzoneb2b only) */
    protected function showView(string $tplname, string $pagetitle, array $bizonylattipusok)
    {
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', $pagetitle);
        $view->setVar('datumtipus', 'teljesites');
        $view->setVar('toldatum', date('Y.01.01'));
        $view->setVar('igdatum', date(\mkw\store::$DateFormat));
        $partner = new partnerController();
        $view->setVar('partnerlist', $partner->getSelectList());
        $view->setVar('gyartolist', $partner->getGyartoSelectList(0));
        $view->setVar('partnertipuslist', (new partnertipusController())->getSelectList(0));
        $view->setVar('webshopfilterlist', \mkw\store::getWebshopSelectList('', true));
        $view->setVar('uklist', (new uzletkotoController())->getSelectList());
        $view->setVar('valutanemlist', (new valutanemController())->getSelectList());
        $view->setVar('cimkekat', (new partnercimkekatController())->getWithCimkek());
        $view->setVar('bizonylattipusfilter', \mkw\store::isSuperzoneB2B());
        $view->setVar('bizonylattipuschecked', array_fill_keys($bizonylattipusok, true));
        $view->setVar('szintlist', $this->getSzintSelectList());
        $view->setVar('maxszint', self::MAXSZINT);

        $view->printTemplateResult(false);
    }

    protected function getData(string $repoMethod = 'getArbevetelLista', array $repoArgs = []): array
    {
        $csoport = $this->getSzintek();
        if ($this->isPivot()) {
            // pivotRows() needs the rows sorted by the other levels, the period last
            $idoszak = array_intersect($csoport, ['ev', 'honap']);
            $csoport = array_merge(array_values(array_diff($csoport, $idoszak)), array_values($idoszak));
        }
        $brutto = $this->params->getStringRequestParam('ertektipus') === 'brutto';
        $valutanemid = $this->params->getIntRequestParam('valutanem');
        $valutanem = $valutanemid ? $this->getRepo(Valutanem::class)->find($valutanemid) : null;

        $rows = $this->getRepo(Bizonylatfej::class)->$repoMethod(
            $this->params->getStringRequestParam('datumtipus'),
            $this->params->getStringRequestParam('tol'),
            $this->params->getStringRequestParam('ig'),
            $brutto,
            $csoport,
            [
                'partner' => $this->params->getIntRequestParam('partner'),
                'partnertipus' => $this->params->getIntRequestParam('partnertipus'),
                'partnercimke' => $this->params->getArrayRequestParam('partnercimkefilter'),
                'uzletkoto' => $this->params->getIntRequestParam('uzletkoto'),
                'valutanem' => $valutanem?->getId(),
                'bizonylattipus' => \mkw\store::isSuperzoneB2B() ? $this->params->getArrayRequestParam('bizonylattipus') : [],
                'gyarto' => $this->params->getIntRequestParam('gyarto'),
                'fafilter' => $this->params->getArrayRequestParam('fafilter'),
                'webshopnum' => $this->params->getStringRequestParam('webshopnum'),
                'nev' => $this->params->getStringRequestParam('nev'),
            ],
            ...$repoArgs
        );

        $currency = $valutanem ? $valutanem->getNev() : 'HUF';
        return [
            'rows' => $rows,
            'brutto' => $brutto,
            'currency' => $currency,
            'valueheader' => ($brutto ? t('Bruttó') : t('Nettó')) . ' ' . $currency,
            'szintek' => $csoport,
        ];
    }

    protected function isPivot(): bool
    {
        return $this->params->getStringRequestParam('megjelenites') === 'kereszttabla';
    }

    /** The cross table of pivotRows(); $termeksor: the rows are products under all the levels. */
    protected function renderPivot(array $pivot, bool $termeksor, string $valueheader, int $decimals): string
    {
        $levels = $pivot['levels'];
        $rowLevel = $termeksor ? null : array_pop($levels);
        $view = $this->createView('arbevetelpivot.tpl');
        $view->setVar('items', $this->buildTableItems($pivot['rows'], $levels, $pivot['sumkeys']));
        $view->setVar('rowlevel', $rowLevel);
        $view->setVar('levelcount', count($levels));
        $view->setVar('termeksor', $termeksor);
        $view->setVar('periods', $pivot['periods']);
        $view->setVar('coltotals', $pivot['coltotals']);
        $view->setVar('total', $pivot['total']);
        $view->setVar('valueheader', $valueheader);
        $view->setVar('decimals', $decimals);
        return $view->getTemplateResult();
    }

    /** The grouping levels of the request in order: known dimensions only, each once, at most MAXSZINT. */
    private function getSzintek(): array
    {
        $szintek = [];
        $dimenziok = [];
        foreach ($this->params->getArrayRequestParam('szint') as $szint) {
            $dimenzio = BizonylatfejRepository::ARBEVETEL_DIMENZIOK[$szint][0] ?? null;
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
            'fokategoria' => t('Főkategória'),
            'kategoria' => t('Kategória'),
            'gyarto' => t('Gyártó'),
            'webshop' => t('Webshop'),
            'partner' => t('Partner'),
            'partnertipus' => t('Partnertípus'),
            'uzletkoto' => t('Üzletkötő'),
            'bizonylattipus' => t('Bizonylattípus'),
            'valutanem' => t('Valutanem'),
        ];
    }

    /** The level dropdowns' options; 'dim' is what makes two of them the same dimension. */
    private function getSzintSelectList(): array
    {
        $res = [];
        foreach (self::getSzintCaptions() as $szint => $caption) {
            $res[] = ['id' => $szint, 'caption' => mb_strtolower($caption), 'dim' => BizonylatfejRepository::ARBEVETEL_DIMENZIOK[$szint][0]];
        }
        return $res;
    }

    /** The chosen grouping columns in order: id decides where a group ends, label is what is shown. */
    protected function getGroupLevels(array $data): array
    {
        $captions = self::getSzintCaptions();
        $levels = [];
        foreach ($data['szintek'] as $szint) {
            [$id, $label] = BizonylatfejRepository::ARBEVETEL_DIMENZIOK[$szint];
            $levels[] = ['id' => $id, 'label' => $label, 'caption' => $captions[$szint]];
        }
        return $levels;
    }

    public function refresh()
    {
        $data = $this->getData();
        $levels = $this->getGroupLevels($data);
        $decimals = $data['currency'] === 'HUF' ? 0 : 2;
        $pivot = $this->isPivot() ? $this->pivotRows($data['rows'], $levels, 'ertek') : null;
        if ($pivot) {
            $html = $this->renderPivot($pivot, false, $data['valueheader'], $decimals);
        } else {
            // the last grouping column is the row label, the ones above it become group headers
            $tableLevels = $levels;
            $rowLevel = array_pop($tableLevels);
            $view = $this->createView('arbevetellistatetel.tpl');
            $view->setVar('items', $this->buildTableItems($data['rows'], $tableLevels, ['ertek']));
            $view->setVar('rowlevel', $rowLevel);
            $view->setVar('levelcount', count($tableLevels));
            $view->setVar('valueheader', $data['valueheader']);
            $view->setVar('decimals', $decimals);
            $view->setVar('osszesen', array_sum(array_column($data['rows'], 'ertek')));
            $html = $view->getTemplateResult();
        }
        header('Content-Type: application/json');
        echo json_encode([
            'html' => $html,
            'chart' => $this->buildLevelChart($data['rows'], $this->getGroupLevels($data), 'ertek', t('Árbevétel'), $data['currency']),
        ]);
    }

    public function export()
    {
        $data = $this->getData();

        $fejlec = array_column($this->getGroupLevels($data), 'caption', 'label');
        $fejlec['ertek'] = $data['valueheader'];

        $excel = new Spreadsheet();
        $sheet = $excel->getActiveSheet();
        $sheet->fromArray(array_values($fejlec), null, 'A1');
        $sor = 2;
        foreach ($data['rows'] as $row) {
            $sheet->fromArray(array_map(fn($key) => $row[$key], array_keys($fejlec)), null, 'A' . $sor);
            $sor++;
        }

        $filename = uniqid('arbevetel') . '.xlsx';
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
