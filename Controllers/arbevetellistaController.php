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
    use \Traits\SavedReportViews;

    private const MAXSZINT = 4;

    // saved views are kept per report under its route base
    protected const KIMUTATAS = 'arbevetellista';

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

    protected function getNezetBeallitas(): array
    {
        return [
            'szint' => $this->getSzintek(),
            'megjelenites' => $this->isPivot() ? 'kereszttabla' : 'lista',
            'pivotertek' => $this->params->getStringRequestParam('pivotertek') === 'ertek' ? 'ertek' : 'mennyiseg',
        ];
    }

    protected function getPdfTitle(): string
    {
        return t('Árbevétel kimutatás');
    }

    /** The table and the chart payload of the request, for refresh() and pdf(). */
    protected function buildReport(): array
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
        return [
            'html' => $html,
            'chart' => $this->buildLevelChart($data['rows'], $this->getGroupLevels($data), 'ertek', t('Árbevétel'), $data['currency']),
        ];
    }

    public function refresh()
    {
        header('Content-Type: application/json');
        echo json_encode($this->buildReport());
    }

    public function pdf()
    {
        $this->outputReportPdf($this->getPdfTitle(), $this->getPdfSzurok(), $this->buildReport(), static::KIMUTATAS . '.pdf');
    }

    /** The filters of the request in words, for the head of the PDF. */
    private function getPdfSzurok(): array
    {
        $nev = function (string $entity, $id) {
            $entitas = $id ? $this->getRepo($entity)->find($id) : null;
            return $entitas ? html_entity_decode((string)$entitas->getNev(), ENT_QUOTES | ENT_HTML5) : null;
        };
        $datumtipusok = ['kelt' => t('kelt'), 'teljesites' => t('teljesítés'), 'esedekesseg' => t('esedékesség')];
        $datumtipus = $datumtipusok[$this->params->getStringRequestParam('datumtipus')] ?? $datumtipusok['teljesites'];
        $szurok = [
            [t('Időszak'), $datumtipus . ': ' . $this->params->getStringRequestParam('tol') . ' – ' . $this->params->getStringRequestParam('ig')],
            [t('Érték'), $this->params->getStringRequestParam('ertektipus') === 'brutto' ? t('bruttó') : t('nettó')],
            [t('Valutanem'), $nev(Valutanem::class, $this->params->getIntRequestParam('valutanem')) ?? t('mindegy, forintra átszámolva')],
            [t('Partner'), $nev(\Entities\Partner::class, $this->params->getIntRequestParam('partner'))],
            [t('Partnertípus'), $nev(\Entities\Partnertipus::class, $this->params->getIntRequestParam('partnertipus'))],
            [t('Üzletkötő'), $nev(\Entities\Uzletkoto::class, $this->params->getIntRequestParam('uzletkoto'))],
            [t('Gyártó'), $nev(\Entities\Partner::class, $this->params->getIntRequestParam('gyarto'))],
            [t('Név'), $this->params->getStringRequestParam('nev') ?: null],
        ];
        $webshopnum = $this->params->getStringRequestParam('webshopnum');
        if ($webshopnum !== '') {
            $szurok[] = [t('Webshop'), \mkw\store::getWebshopNev($webshopnum)];
        }
        if (\mkw\store::isSuperzoneB2B()) {
            $tipusok = array_map(fn($id) => $nev(\Entities\Bizonylattipus::class, $id), $this->params->getArrayRequestParam('bizonylattipus'));
            $szurok[] = [t('Bizonylattípus'), implode(', ', array_filter($tipusok)) ?: null];
        }
        $cimkek = array_filter(array_map('intval', $this->params->getArrayRequestParam('partnercimkefilter')));
        if ($cimkek) {
            $szurok[] = [t('Partnercímke'), implode(', ', $this->getRepo(\Entities\Partnercimketorzs::class)->getCimkeNevek($cimkek))];
        }
        $kategoriak = array_map(fn($id) => $nev(\Entities\TermekFa::class, $id), array_filter(array_map('intval', $this->params->getArrayRequestParam('fafilter'))));
        $szurok[] = [t('Kategória'), implode(', ', array_filter($kategoriak)) ?: null];
        $szurok[] = $this->getGroupingSzuro($this->getGroupLevels(['szintek' => $this->getSzintek()]));
        return array_values(array_filter($szurok, fn($szuro) => $szuro[1] !== null && $szuro[1] !== ''));
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
