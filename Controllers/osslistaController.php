<?php

namespace Controllers;

use Entities\Bizonylatfej;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Services\EkbArfolyamService;

/**
 * Data of the quarterly OSS (union scheme) return: EU private buyers' invoices with the VAT rate of the member state
 * of consumption, in EUR at the ECB rate of the period's last day. Corrections of earlier periods (storno of an
 * invoice fulfilled then) are listed apart, at the rate of their original period.
 */
class osslistaController extends \mkwhelpers\Controller
{

    private array $rates = [];

    public function view()
    {
        $view = $this->createView('osslista.tpl');
        $view->setVar('pagetitle', t('OSS kimutatás'));
        // the last closed quarter
        $elozo = strtotime('first day of -3 months');
        $view->setVar('ev', (int)date('Y', $elozo));
        $view->setVar('negyedev', intdiv((int)date('n', $elozo) - 1, 3) + 1);
        $view->setVar('evek', range((int)date('Y'), 2021));
        $view->printTemplateResult(false);
    }

    /** Year and quarter as 'YYYY/Q'. */
    private static function quarterOf(string $datum): string
    {
        $time = strtotime($datum);
        return date('Y', $time) . '/' . (intdiv((int)date('n', $time) - 1, 3) + 1);
    }

    private static function quarterEnd(string $negyedev): string
    {
        [$ev, $q] = explode('/', $negyedev);
        return date('Y-m-t', strtotime(sprintf('%d-%02d-01', $ev, $q * 3)));
    }

    /**
     * 1 EUR in $valuta at the end of $negyedev, fetched once per request. The HUF rate of the reported quarter can be
     * given by hand ('ekbhuf'), e.g. when the ECB is not reachable.
     */
    private function getRate(string $valuta, string $negyedev, string $bevallasNegyedev): ?array
    {
        $key = $valuta . ' ' . $negyedev;
        if (!array_key_exists($key, $this->rates)) {
            $kezi = (float)str_replace(',', '.', $this->params->getStringRequestParam('ekbhuf'));
            $datum = self::quarterEnd($negyedev);
            if ($valuta === 'HUF' && $negyedev === $bevallasNegyedev && $kezi > 0) {
                $rate = ['datum' => $datum, 'arfolyam' => $kezi, 'kezi' => true];
            } else {
                $rate = (new EkbArfolyamService())->getRate($valuta, $datum);
            }
            $this->rates[$key] = $rate ? $rate + ['valuta' => $valuta, 'negyedev' => $negyedev, 'kezi' => false] : null;
        }
        return $this->rates[$key];
    }

    private function getData(): array
    {
        $ev = $this->params->getIntRequestParam('ev') ?: (int)date('Y');
        $q = min(4, max(1, $this->params->getIntRequestParam('negyedev') ?: 1));
        $negyedev = $ev . '/' . $q;
        $tol = sprintf('%d-%02d-01', $ev, $q * 3 - 2);
        $ig = self::quarterEnd($negyedev);

        $osszesito = [];
        $korrekciok = [];
        $ellenorizendo = [];
        $tetelek = [];
        $hianyzoArfolyam = [];
        foreach ($this->getRepo(Bizonylatfej::class)->getOssTetelek($tol, $ig) as $row) {
            // the destination country's rate is foreign and not zero; anything else to a private EU buyer is suspect
            if ($row['magyar'] || $row['afakulcs'] <= 0) {
                $ellenorizendo[] = $row;
                continue;
            }
            $eredeti = self::quarterOf($row['storno'] && $row['eredetiteljesites'] ? $row['eredetiteljesites'] : $row['teljesites']);
            $valuta = $row['valutanemnev'] ?: 'HUF';
            $rate = $this->getRate($valuta, $eredeti, $negyedev);
            if (!$rate) {
                $hianyzoArfolyam[$valuta . ' ' . self::quarterEnd($eredeti)] = true;
                continue;
            }
            $row['negyedev'] = $eredeti;
            $row['nettoeur'] = $row['netto'] / $rate['arfolyam'];
            $row['afaeur'] = $row['afaertek'] / $rate['arfolyam'];
            $tetelek[] = $row;

            $key = $row['iso3166'] . ' ' . $row['afakulcs'];
            if ($eredeti === $negyedev) {
                $cel = &$osszesito[$key];
            } else {
                $cel = &$korrekciok[$eredeti . ' ' . $key];
            }
            $cel ??= ['negyedev' => $eredeti, 'iso3166' => $row['iso3166'], 'orszagnev' => $row['orszagnev'],
                'afakulcs' => $row['afakulcs'], 'nettoeur' => 0.0, 'afaeur' => 0.0, 'db' => 0];
            $cel['nettoeur'] += $row['nettoeur'];
            $cel['afaeur'] += $row['afaeur'];
            $cel['db']++;
            unset($cel);
        }
        ksort($osszesito);
        ksort($korrekciok);
        return [
            'negyedev' => $negyedev,
            'tol' => $tol,
            'ig' => $ig,
            'osszesito' => array_values($osszesito),
            'korrekciok' => array_values($korrekciok),
            'ellenorizendo' => $ellenorizendo,
            'tetelek' => $tetelek,
            'arfolyamok' => array_values(array_filter($this->rates)),
            'hianyzoarfolyam' => array_keys($hianyzoArfolyam),
            'osszesnetto' => array_sum(array_column($osszesito, 'nettoeur')),
            'osszesafa' => array_sum(array_column($osszesito, 'afaeur')),
        ];
    }

    public function report()
    {
        $report = $this->createView('rep_osslista.tpl');
        foreach ($this->getData() as $key => $value) {
            $report->setVar($key, $value);
        }
        $report->printTemplateResult();
    }

    public function export()
    {
        $data = $this->getData();
        $excel = new Spreadsheet();
        $lapok = [
            [t('Összesítő'), [t('Tagállam'), t('Ország'), t('ÁFA kulcs %'), t('Adóalap EUR'), t('ÁFA EUR'), t('Tételsor')],
                array_map(fn($r) => [$r['iso3166'], $r['orszagnev'], $r['afakulcs'], round($r['nettoeur'], 2), round($r['afaeur'], 2), $r['db']], $data['osszesito'])],
            [t('Korrekciók'), [t('Eredeti időszak'), t('Tagállam'), t('Ország'), t('ÁFA kulcs %'), t('Adóalap EUR'), t('ÁFA EUR')],
                array_map(fn($r) => [$r['negyedev'], $r['iso3166'], $r['orszagnev'], $r['afakulcs'], round($r['nettoeur'], 2), round($r['afaeur'], 2)], $data['korrekciok'])],
            [t('Tételek'), [t('Bizonylat'), t('Kelt'), t('Teljesítés'), t('Időszak'), t('Partner'), t('Tagállam'), t('ÁFA kulcs %'), t('Valuta'), t('Nettó'), t('ÁFA'), t('Nettó EUR'), t('ÁFA EUR')],
                array_map(fn($r) => [$r['id'], $r['kelt'], $r['teljesites'], $r['negyedev'], $r['partnernev'], $r['iso3166'], $r['afakulcs'], $r['valutanemnev'],
                    $r['netto'], $r['afaertek'], round($r['nettoeur'], 2), round($r['afaeur'], 2)], $data['tetelek'])],
            [t('Ellenőrizendő'), [t('Bizonylat'), t('Teljesítés'), t('Partner'), t('Tagállam'), t('ÁFA kulcs %'), t('Valuta'), t('Nettó'), t('ÁFA')],
                array_map(fn($r) => [$r['id'], $r['teljesites'], $r['partnernev'], $r['iso3166'], $r['afakulcs'], $r['valutanemnev'], $r['netto'], $r['afaertek']], $data['ellenorizendo'])],
            [t('Árfolyamok'), [t('Valuta'), t('Időszak'), t('Árfolyam napja'), t('1 EUR'), t('Forrás')],
                array_map(fn($r) => [$r['valuta'], $r['negyedev'], $r['datum'], $r['arfolyam'], $r['kezi'] ? t('kézi') : 'EKB'], $data['arfolyamok'])],
        ];
        foreach ($lapok as $i => [$nev, $fejlec, $sorok]) {
            $sheet = $i ? $excel->createSheet() : $excel->getActiveSheet();
            $sheet->setTitle($nev);
            $sheet->fromArray($fejlec, null, 'A1');
            if ($sorok) {
                $sheet->fromArray($sorok, null, 'A2', true);
            }
        }
        $excel->setActiveSheetIndex(0);

        $filename = 'oss-' . str_replace('/', '-q', $data['negyedev']) . '.xlsx';
        $filepath = \mkw\store::storagePath(uniqid('oss') . '.xlsx');
        IOFactory::createWriter($excel, 'Xlsx')->save($filepath);

        header('Cache-Control: private');
        header('Content-Type: application/stream');
        header('Content-Length: ' . filesize($filepath));
        header('Content-Disposition: attachment; filename=' . $filename);
        readfile($filepath);
        \unlink($filepath);
    }
}
