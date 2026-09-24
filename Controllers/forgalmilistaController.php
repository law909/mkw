<?php

namespace Controllers;

use Entities\BizonylatfejRepository;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

/** The revenue report's documents and filters, with sold quantity per product / variant. */
class forgalmilistaController extends arbevetellistaController
{

    private const MAXTERMEK = 20;

    public function view()
    {
        $this->showView(
            'forgalmilista.tpl',
            t('Forgalmi kimutatás'),
            array_values(array_diff(BizonylatfejRepository::ARBEVETEL_BIZONYLATTIPUSOK, [\Services\ElolegService::BIZTIPUS]))
        );
    }

    private function termekLabel(array $row): string
    {
        $valtozat = implode(' ', array_filter([$row['ertek1'], $row['ertek2']], fn($e) => $e !== null && $e !== ''));
        return trim(implode(' ', array_filter([$row['cikkszam'], $row['nev'], $valtozat])));
    }

    /** The quantity chart like the revenue one; without grouping one bar per product, the best sellers only. */
    private function buildChart(array $data): array
    {
        $levels = $this->getGroupLevels($data);
        if ($levels) {
            return $this->buildLevelChart($data['rows'], $levels, 'mennyiseg', t('Mennyiség'));
        }
        $rows = array_map(fn($row) => $row + ['termekkulcs' => $row['termekid'] . '-' . $row['termekvaltozatid'], 'termekcimke' => $this->termekLabel($row)], $data['rows']);
        return $this->buildLevelChart($rows, [['id' => 'termekkulcs', 'label' => 'termekcimke']], 'mennyiseg', t('Mennyiség'), '', self::MAXTERMEK);
    }

    public function refresh()
    {
        $data = $this->getData('getForgalmiLista');
        $levels = $this->getGroupLevels($data);
        $view = $this->createView('forgalmilistatetel.tpl');
        $view->setVar('items', $this->buildTableItems($data['rows'], $levels, ['mennyiseg', 'ertek']));
        $view->setVar('levelcount', count($levels));
        $view->setVar('valueheader', $data['valueheader']);
        $view->setVar('decimals', $data['currency'] === 'HUF' ? 0 : 2);
        $mennyisegek = array_column($data['rows'], 'mennyiseg');
        $view->setVar('mennyisegdecimals', array_filter($mennyisegek, fn($m) => floor($m) != $m) ? 2 : 0);
        $view->setVar('osszesenmennyiseg', round(array_sum($mennyisegek), 2));
        $view->setVar('osszesen', array_sum(array_column($data['rows'], 'ertek')));
        header('Content-Type: application/json');
        echo json_encode([
            'html' => $view->getTemplateResult(),
            'chart' => $this->buildChart($data),
        ]);
    }

    public function export()
    {
        $data = $this->getData('getForgalmiLista');

        $fejlec = array_column($this->getGroupLevels($data), 'caption', 'label');
        $fejlec['cikkszam'] = t('Cikkszám');
        $fejlec['nev'] = t('Név');
        $fejlec['ertek1'] = t('Változat 1');
        $fejlec['ertek2'] = t('Változat 2');
        $fejlec['mennyiseg'] = t('Mennyiség');
        $fejlec['me'] = t('ME');
        $fejlec['ertek'] = $data['valueheader'];

        $excel = new Spreadsheet();
        $sheet = $excel->getActiveSheet();
        $sheet->fromArray(array_values($fejlec), null, 'A1');
        $sor = 2;
        foreach ($data['rows'] as $row) {
            $sheet->fromArray(array_map(fn($key) => $row[$key], array_keys($fejlec)), null, 'A' . $sor);
            $sor++;
        }

        $filename = uniqid('forgalmilista') . '.xlsx';
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
