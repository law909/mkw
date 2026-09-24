<?php

namespace Controllers;


use Entities\Bankbizonylattetel;
use Entities\Bizonylatfej;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class jutaleklistaController extends \mkwhelpers\MattableController
{

    use \Traits\GroupedReport;
    use \Traits\SavedReportViews;

    private const MAXSZINT = 4;

    protected const KIMUTATAS = 'jutaleklista';

    /** Grouping level => [id key, label key] of the grouped rows; the two periods are one dimension. */
    private const DIMENZIOK = [
        'ev' => ['idoszak', 'idoszak'],
        'honap' => ['idoszak', 'idoszak'],
        'uzletkoto' => ['uzletkoto_id', 'uzletkotonev'],
        'partner' => ['partnernev', 'partnernev'],
        'valutanem' => ['valutanem_id', 'valutanemnev'],
    ];

    private $tolstr;
    private $igstr;
    private $cimkek = [];
    private $ukid;
    private $belso;

    public function view()
    {
        $view = $this->createView('jutaleklista.tpl');

        $view->setVar('toldatum', date(\mkw\store::$DateFormat));
        $view->setVar('igdatum', date(\mkw\store::$DateFormat));

        $pcc = new partnercimkekatController();
        $view->setVar('cimkekat', $pcc->getWithCimkek(null));

        $fmc = new uzletkotoController();
        $view->setVar('uklist', $fmc->getSelectList(false));

        $view->setVar('szintlist', $this->getSzintSelectList());
        $view->setVar('maxszint', self::MAXSZINT);

        $view->printTemplateResult();
    }

    protected function createFilter()
    {
        $this->belso = $this->params->getBoolRequestParam('belso');

        $this->tolstr = $this->params->getStringRequestParam('tol');
        $this->tolstr = date(\mkw\store::$DateFormat, strtotime(\mkw\store::convDate($this->tolstr)));

        $this->igstr = $this->params->getStringRequestParam('ig');
        $this->igstr = date(\mkw\store::$DateFormat, strtotime(\mkw\store::convDate($this->igstr)));

        $datummezo = 'datum';

        $filter = new \mkwhelpers\FilterDescriptor();
        $filter
            ->addFilter($datummezo, '>=', $this->tolstr)
            ->addFilter($datummezo, '<=', $this->igstr)
            ->addFilter('irany', '=', 1);

        $this->cimkek = $this->params->getArrayRequestParam('cimkefilter');
        $cimkeSubquery = $this->getRepo('Entities\Partner')->getCimkeSubquery($this->cimkek);
        if ($cimkeSubquery) {
            $filter->addSql('_xx.partner_id IN (' . $cimkeSubquery . ')');
        }

        $uk = $this->getRepo('Entities\Uzletkoto')->find($this->params->getIntRequestParam('uzletkoto'));
        $this->ukid = null;
        if ($uk) {
            if ($this->belso) {
                $filter->addFilter('bf.belsouzletkoto_id', '=', $uk->getId());
            } else {
                $filter->addFilter('bf.uzletkoto_id', '=', $uk->getId());
            }
            $this->ukid = $uk->getId();
        }

        return $filter;
    }

    public function addNegativSzallktg($mihez)
    {
        $bizonylatok = array_column($mihez, 'hivatkozottbizonylat');
        $szallktg = $this->getRepo(Bizonylatfej::class)->getSzallitasiKtgBruttok($bizonylatok);
        $elsoBefizetes = $this->getRepo(Bankbizonylattetel::class)->getFirstDatumByHivatkozottBizonylat($bizonylatok);
        $ret = [];
        foreach ($mihez as $sor) {
            $sor['jutalekosszeg'] = \mkw\store::kerekit($sor['brutto'] * $sor['uzletkotojutalek'] / 100, 0.01);
            $sor['type'] = 'Item';
            $ret[] = $sor;
            $brutto = $szallktg[$sor['hivatkozottbizonylat']] ?? null;
            // only the first payment of an invoice gives back its shipping cost
            $elso = $elsoBefizetes[$sor['hivatkozottbizonylat']] ?? null;
            if ($brutto !== null && ($elso === null || $elso >= $sor['datum'])) {
                $ret[] = [
                    'id' => 0,
                    'bankbizonylatfej_id' => $sor['bankbizonylatfej_id'],
                    'valutanem_id' => $sor['valutanem_id'],
                    'valutanemnev' => $sor['valutanemnev'],
                    'datum' => $sor['datum'],
                    'hivatkozottdatum' => $sor['hivatkozottdatum'],
                    'hivatkozottbizonylat' => $sor['hivatkozottbizonylat'],
                    'uzletkoto_id' => $sor['uzletkoto_id'],
                    'uzletkotonev' => $sor['uzletkotonev'],
                    'uzletkotojutalek' => $sor['uzletkotojutalek'],
                    'partnernev' => $sor['partnernev'],
                    'brutto' => $brutto * -1,
                    'jutalekosszeg' => \mkw\store::kerekit($brutto * $sor['uzletkotojutalek'] / 100 * -1, 0.01),
                    'type' => 'Transport cost'
                ];
            }
        }
        return $ret;
    }

    public function addFakeKifizetes($mihez)
    {
        if (\mkw\store::isFakeKintlevoseg()) {
            $f = $this->getRepo('Entities\Bizonylatfej')->getAllFakeKifizetes($this->tolstr, $this->igstr, $this->cimkek, $this->ukid, $this->belso);
            /** @var \Entities\Bizonylatfej $k */
            foreach ($f as $k) {
                $x = [
                    'id' => 0,
                    'bankbizonylatfej_id' => 0,
                    'valutanem_id' => $k->getValutanemId(),
                    'valutanemnev' => $k->getValutanemnev(),
                    'datum' => $k->getFakekifizetesdatumStr(),
                    'hivatkozottdatum' => $k->getEsedekessegStr(),
                    'hivatkozottbizonylat' => $k->getId(),
                    'partnernev' => $k->getPartnernev(),
                    'brutto' => $k->getBrutto(),
                    'type' => 'Fake'
                ];
                if ($this->belso) {
                    $x['uzletkoto_id'] = $k->getBelsouzletkotoId();
                    $x['uzletkotonev'] = $k->getBelsouzletkotonev();
                    $x['uzletkotojutalek'] = $k->getBelsouzletkotojutalek();
                    $x['jutalekosszeg'] = \mkw\store::kerekit($k->getBrutto() * $k->getBelsouzletkotojutalek() / 100, 0.01);
                } else {
                    $x['uzletkoto_id'] = $k->getUzletkotoId();
                    $x['uzletkotonev'] = $k->getUzletkotonev();
                    $x['uzletkotojutalek'] = $k->getUzletkotojutalek();
                    $x['jutalekosszeg'] = \mkw\store::kerekit($k->getBrutto() * $k->getUzletkotojutalek() / 100, 0.01);
                }
                $mihez[] = $x;
            }
        }
        return $mihez;
    }

    public function addKeszpenzes($mihez)
    {
        $datum = fn($d) => $d ? date(\mkw\store::$DateFormat, strtotime($d)) : '';
        $rows = $this->getRepo(Bizonylatfej::class)->getKeszpenzesJutalekRows($this->tolstr, $this->igstr, $this->cimkek, $this->ukid, $this->belso);
        foreach ($rows as $k) {
            $mihez[] = [
                'id' => 0,
                'bankbizonylatfej_id' => 0,
                'valutanem_id' => $k['valutanem_id'] ?? '',
                'valutanemnev' => $k['valutanemnev'],
                'datum' => $datum($k['fakekifizetesdatum']),
                'hivatkozottdatum' => $datum($k['esedekesseg']),
                'hivatkozottbizonylat' => $k['id'],
                'partnernev' => $k['partnernev'],
                'brutto' => $k['brutto'],
                'type' => 'KP',
                'kelt' => $datum($k['kelt']),
                'uzletkoto_id' => $k['uzletkoto_id'] ?? '',
                'uzletkotonev' => $k['uzletkotonev'],
                'uzletkotojutalek' => $k['uzletkotojutalek'],
                'jutalekosszeg' => \mkw\store::kerekit($k['brutto'] * $k['uzletkotojutalek'] / 100, 0.01),
            ];
        }
        return $mihez;
    }

    private function updateDB()
    {
        if (\mkw\store::isSuperzoneB2B()) {
            $a = $this->getEm()->getConnection()->prepare(
                'UPDATE bizonylatfej SET belsouzletkoto_id=9,belsouzletkotonev="Szász Balázs",belsouzletkotojutalek=3 '
                . 'WHERE (partner_id IN (SELECT partner_id FROM partner_cimkek WHERE cimketorzs_id=20)) AND '
                . '(bizonylattipus_id IN (\'egyeb\',\'keziszamla\',\'szamla\',\'garancialevel\')) AND (kelt>=\'2022-01-01\')'
            );
            $a->executeStatement();

            $b = $this->getEm()->getConnection()->prepare(
                'UPDATE bizonylatfej SET belsouzletkoto_id=9,belsouzletkotonev="Szász Balázs",belsouzletkotojutalek=0.5 '
                . 'WHERE (partner_id IN (SELECT partner_id FROM partner_cimkek WHERE cimketorzs_id IN (2,13))) AND '
                . '(bizonylattipus_id IN (\'egyeb\',\'keziszamla\',\'szamla\')) AND (kelt>=\'2016-07-21\')'
            );
            $b->executeStatement();
        }
    }

    private function getItems(): array
    {
        $this->updateDB();

        $filter = $this->createFilter();

        /** @var \Entities\BankbizonylattetelRepository $btrepo */
        $btrepo = $this->getRepo('Entities\Bankbizonylattetel');

        $mind = $btrepo->getAllHivatkozottJoin($filter, ['datum' => 'ASC'], $this->belso);
        $mind = $this->addNegativSzallktg($mind);
        $mind = $this->addKeszpenzes($mind);
        return $this->addFakeKifizetes($mind);
    }

    public function createLista()
    {
        $mind = $this->getItems();

        $cimkenevek = $this->getRepo('Entities\Partnercimketorzs')->getCimkeNevek($this->params->getArrayRequestParam('cimkefilter'));

        $report = $this->createView('rep_jutalek.tpl');
        $report->setVar('lista', $mind);
        $report->setVar('tolstr', $this->tolstr);
        $report->setVar('igstr', $this->igstr);
        $report->setVar('cimkenevek', $cimkenevek);
        $report->printTemplateResult();
    }

    public function exportLista()
    {
        function x($o)
        {
            if ($o <= 26) {
                return chr(65 + $o);
            }
            return chr(65 + floor($o / 26)) . chr(65 + ($o % 26));
        }

        $excel = new Spreadsheet();
        $excel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Payment Due')
            ->setCellValue('B1', 'Date of income')
            ->setCellValue('C1', 'Invoice nr.')
            ->setCellValue('D1', 'Customer')
            ->setCellValue('E1', 'Agent')
            ->setCellValue('F1', 'Type')
            ->setCellValue('G1', 'Income')
            ->setCellValue('H1', 'Comission %')
            ->setCellValue('I1', 'Comission value');

        $mind = $this->getItems();

        $sor = 2;
        foreach ($mind as $item) {
            $excel->setActiveSheetIndex(0)
                ->setCellValue('A' . $sor, $item['hivatkozottdatum'])
                ->setCellValue('B' . $sor, $item['datum'])
                ->setCellValue('C' . $sor, $item['hivatkozottbizonylat'])
                ->setCellValue('D' . $sor, $item['partnernev'])
                ->setCellValue('E' . $sor, $item['uzletkotonev'])
                ->setCellValue('F' . $sor, $item['type'])
                ->setCellValue('G' . $sor, $item['brutto'])
                ->setCellValue('H' . $sor, $item['uzletkotojutalek'])
                ->setCellValue('I' . $sor, $item['jutalekosszeg']);
            $sor++;
        }

        $writer = IOFactory::createWriter($excel, 'Xlsx');

        $filename = uniqid('comission') . '.xlsx';
        $filepath = \mkw\store::storagePath($filename);
        $writer->save($filepath);

        $fileSize = filesize($filepath);

        // Output headers.
        header('Cache-Control: private');
        header('Content-Type: application/stream');
        header('Content-Length: ' . $fileSize);
        header('Content-Disposition: attachment; filename=' . $filename);

        readfile($filepath);

        \unlink($filepath);
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
            'uzletkoto' => t('Üzletkötő'),
            'partner' => t('Partner'),
            'valutanem' => t('Valutanem'),
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

    protected function getNezetBeallitas(): array
    {
        return [
            'szint' => $this->getSzintek(),
            'megjelenites' => $this->isPivot() ? 'kereszttabla' : 'lista',
        ];
    }

    /**
     * The commission items summed by the levels ('ertek' is the commission), sorted by them: the periods in time
     * order, the rest by name. With $periodLast the period is sorted after the other levels (for a cross table).
     */
    private function groupItems(array $items, array $szintek, bool $periodLast): array
    {
        // some names are stored HTML-encoded ("&amp;"), the templates escape them again
        $decode = fn($nev) => html_entity_decode((string)$nev, ENT_QUOTES | ENT_HTML5);
        $groups = [];
        foreach ($items as $item) {
            // cash invoices have no payment date, they are counted on their issue date
            $datum = $item['datum'] ?: ($item['kelt'] ?? '') ?: $item['hivatkozottdatum'];
            $time = $datum ? strtotime(\mkw\store::convDate($datum)) : false;
            $row = [];
            foreach ($szintek as $szint) {
                switch ($szint) {
                    case 'ev':
                    case 'honap':
                        $row['idoszak'] = $time ? date($szint === 'ev' ? 'Y' : 'Y-m', $time) : '';
                        break;
                    case 'uzletkoto':
                        $row['uzletkoto_id'] = (int)$item['uzletkoto_id'];
                        $row['uzletkotonev'] = $item['uzletkoto_id'] ? $decode($item['uzletkotonev']) : t('nincs üzletkötő');
                        break;
                    case 'partner':
                        $row['partnernev'] = $decode($item['partnernev']);
                        break;
                    case 'valutanem':
                        $row['valutanem_id'] = (int)$item['valutanem_id'];
                        $row['valutanemnev'] = (string)$item['valutanemnev'];
                        break;
                }
            }
            $key = implode("\x1f", $row);
            $groups[$key] ??= $row + ['brutto' => 0, 'ertek' => 0];
            $groups[$key]['brutto'] += $item['brutto'];
            $groups[$key]['ertek'] += $item['jutalekosszeg'];
        }

        $order = $this->getGroupLevels($szintek);
        if ($periodLast) {
            usort($order, fn($a, $b) => ($a['id'] === 'idoszak') <=> ($b['id'] === 'idoszak'));
        }
        $collator = class_exists(\Collator::class) ? new \Collator('hu_HU') : null;
        $compare = fn($a, $b) => $collator ? $collator->compare($a, $b) : strcasecmp($a, $b);
        $groups = array_values($groups);
        usort($groups, function ($a, $b) use ($order, $compare) {
            foreach ($order as $level) {
                $cmp = $level['id'] === 'idoszak'
                    ? strcmp($a['idoszak'], $b['idoszak'])
                    : ($compare((string)$a[$level['label']], (string)$b[$level['label']]) ?: $a[$level['id']] <=> $b[$level['id']]);
                if ($cmp) {
                    return $cmp;
                }
            }
            return 0;
        });
        return $groups;
    }

    /** The table and the chart payload of the request, for refresh() and pdf(). */
    private function buildReport(): array
    {
        // payments without commission (mostly of partners without an agent) would bury the groups
        $items = array_filter($this->getItems(), fn($item) => (float)$item['jutalekosszeg'] != 0);
        $szintek = $this->getSzintek();
        $levels = $this->getGroupLevels($szintek);
        $rows = $this->groupItems($items, $szintek, $this->isPivot());

        $valutanemek = array_values(array_unique(array_filter(array_column($items, 'valutanemnev'))));
        $currency = count($valutanemek) === 1 ? reset($valutanemek) : '';
        $valueheader = trim(t('Jutalék') . ' ' . $currency);
        $pivot = $this->isPivot() ? $this->pivotRows($rows, $levels, 'ertek') : null;
        if ($pivot) {
            $html = $this->renderPivot($pivot, false, $valueheader, 2);
        } else {
            $tableLevels = $levels;
            $rowLevel = array_pop($tableLevels);
            $view = $this->createView('arbevetellistatetel.tpl');
            $view->setVar('items', $this->buildTableItems($rows, $tableLevels, ['ertek']));
            $view->setVar('rowlevel', $rowLevel);
            $view->setVar('levelcount', count($tableLevels));
            $view->setVar('valueheader', $valueheader);
            $view->setVar('decimals', 2);
            $view->setVar('osszesen', array_sum(array_column($rows, 'ertek')));
            $html = $view->getTemplateResult();
        }

        $chart = $this->buildLevelChart($rows, $levels, 'ertek', t('Jutalék'), $currency);
        if (count($valutanemek) > 1 && !in_array('valutanem', $szintek)) {
            $chart['note'] = trim(sprintf(
                t('A jutalék több valutanemben (%s) van, az összegek átszámítás nélkül adódnak össze; csoportosítson valutanemre is.'),
                implode(', ', $valutanemek)
            ) . ' ' . $chart['note']);
        }
        return ['html' => $html, 'chart' => $chart];
    }

    public function refresh()
    {
        header('Content-Type: application/json');
        echo json_encode($this->buildReport());
    }

    public function pdf()
    {
        $report = $this->buildReport();
        $szurok = [[t('Időszak'), $this->tolstr . ' – ' . $this->igstr]];
        if ($this->ukid) {
            $szurok[] = [t('Üzletkötő'), $this->getRepo(\Entities\Uzletkoto::class)->find($this->ukid)->getNev()];
        }
        if ($this->belso) {
            $szurok[] = [t('Elszámolás'), t('belső üzletkötő')];
        }
        $cimkek = array_filter(array_map('intval', $this->cimkek));
        if ($cimkek) {
            $szurok[] = [t('Partnercímke'), implode(', ', $this->getRepo(\Entities\Partnercimketorzs::class)->getCimkeNevek($cimkek))];
        }
        $szurok[] = $this->getGroupingSzuro($this->getGroupLevels($this->getSzintek()));
        $this->outputReportPdf(t('Jutalék elszámolás'), $szurok, $report, 'jutalek.pdf');
    }
}
