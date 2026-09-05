<?php

namespace Controllers;

use Entities\Fifoertek;
use Entities\Fiforeteg;
use Entities\Raktar;
use Entities\TermekFa;
use mkwhelpers\FilterDescriptor;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Services\FifoService;

/**
 * Készletérték kimutatás FIFO szerint: készletcsoportonként (raktár, termék, változat) a
 * még bent lévő bevételezési rétegek értéke, alatta a rétegbontás – melyik bevétből hány
 * darab van még bent, milyen áron.
 *
 * A mai napra a tárolt `fifoertek` táblából olvasunk (ez a gyakori eset, és ingyen van),
 * múltbeli dátumra a `Services\FifoService` menet közben számol, tárolás nélkül.
 *
 * A fedezetlen csoportok (több a kiadás, mint a bevét) külön blokkban látszanak: ezekre nincs
 * értelmes FIFO érték, kitalált áron nem értékelünk – a lista a könyvelőnek javítási munkalista.
 */
class keszletertekController extends \mkwhelpers\Controller
{

    /** a mennyiség szerinti szűrés lehetőségei */
    private const KESZLETMIND = 1;
    private const KESZLETVAN = 2;
    private const KESZLETFEDEZETLEN = 3;

    private $datumstr;
    private $raktar;
    private $raktarnev;
    private $keszlettipus;
    private $csakbecsult = false;
    private $nevfilter;
    /** @var string[] a kijelölt termékfák karkod-előtagjai */
    private $faszuro = [];
    private $fanevek = '';
    /** múltbeli dátumnál igaz: az érték nem a tárolt, hanem menet közben számolt */
    private $menetkozben = false;

    public function view()
    {
        $view = $this->createView('keszletertek.tpl');
        $view->setVar('datum', date(\mkw\store::$DateFormat));
        $rc = new raktarController();
        $view->setVar('raktarlist', $rc->getSelectList());
        $utolso = $this->getRepo(Fifoertek::class)->getUtolsoSzamitas();
        $view->setVar('utolsoszamitas', $utolso ? date(\mkw\store::$DateTimeFormat, strtotime($utolso)) : '');
        $view->printTemplateResult();
    }

    /** A teljes újraszámolás gombja. */
    public function recalculate()
    {
        try {
            $r = (new FifoService())->recalculateAll();
        } catch (\Exception $e) {
            $this->json(['ok' => false, 'error' => $e->getMessage()]);
            return;
        }
        $this->json([
            'ok' => true,
            'uzenet' => sprintf(
                t('Kész. %s készletcsoport, %s nyitott réteg, %s Ft.'),
                $r['szamolt'],
                $r['reteg'],
                number_format($r['ertek'], 0, ',', ' ')
            ),
            'fedezetlen' => $r['fedezetlen'],
            'becsult' => $r['becsult'],
            'szamitva' => date(\mkw\store::$DateTimeFormat),
        ]);
    }

    /** Egy termék újraszámolása – a termék karbantartóról. */
    public function recalculateTermek()
    {
        $termekid = $this->params->getIntRequestParam('termekid');
        if (!$termekid) {
            $this->json(['ok' => false, 'error' => t('Nincs termék.')]);
            return;
        }
        try {
            $r = (new FifoService())->recalculateTermek($termekid);
        } catch (\Exception $e) {
            $this->json(['ok' => false, 'error' => $e->getMessage()]);
            return;
        }
        $this->json(['ok' => true, 'csoport' => $r['szamolt'], 'szamitva' => date(\mkw\store::$DateTimeFormat)]);
    }

    public function createLista()
    {
        $lista = $this->getData();
        $report = $this->createView('rep_keszletertek.tpl');
        $report->setVar('lista', $lista['sorok']);
        $report->setVar('fedezetlen', $lista['fedezetlen']);
        $report->setVar('osszertek', $lista['osszertek']);
        $report->setVar('osszmennyiseg', $lista['osszmennyiseg']);
        $report->setVar('datumstr', $this->datumstr);
        $report->setVar('raktar', $this->raktarnev);
        $report->setVar('nevfilter', $this->nevfilter);
        $report->setVar('termekfa', $this->fanevek);
        $report->setVar('menetkozben', $this->menetkozben);
        $report->setVar('utolsoszamitas', $lista['utolsoszamitas']);
        $report->setVar('printdatum', date(\mkw\store::$DateTimeFormat));
        $report->printTemplateResult();
    }

    /** A képernyős nézettel azonos tartalom Excelben, rétegenként egy sorral. */
    public function exportLista()
    {
        $lista = $this->getData();

        $excel = new Spreadsheet();
        $excel->setActiveSheetIndex(0)
            ->setCellValue('A1', t('Raktár'))
            ->setCellValue('B1', t('Cikkszám'))
            ->setCellValue('C1', t('Termék'))
            ->setCellValue('D1', t('Változat'))
            ->setCellValue('E1', t('Készlet'))
            ->setCellValue('F1', t('Egységérték'))
            ->setCellValue('G1', t('Érték'))
            ->setCellValue('H1', t('Becsült ár'))
            ->setCellValue('I1', t('Bevét'))
            ->setCellValue('J1', t('Bevét teljesítés'))
            ->setCellValue('K1', t('Réteg mennyiség'))
            ->setCellValue('L1', t('Réteg egységár'));

        $sor = 2;
        foreach (array_merge($lista['sorok'], $lista['fedezetlen']) as $item) {
            $alap = [
                $item['raktarnev'],
                $item['cikkszam'],
                $item['termeknev'],
                trim($item['ertek1'] . ' ' . $item['ertek2']),
                (float)$item['mennyiseg'],
                $item['egysegertek'] === null ? '' : (float)$item['egysegertek'],
                (float)$item['ertek'],
                $item['becsult'] ? t('igen') : '',
            ];
            if (!$item['retegek']) {
                $this->excelSor($excel, $sor++, array_merge($alap, ['', '', '', '']));
                continue;
            }
            foreach ($item['retegek'] as $reteg) {
                $this->excelSor($excel, $sor++, array_merge($alap, [
                    $reteg['bizonylatszam'],
                    $reteg['teljesites'],
                    (float)$reteg['mennyiseg'],
                    (float)$reteg['egysegar'],
                ]));
            }
        }

        $this->kuldExcel($excel, 'keszletertek');
    }

    private function excelSor(Spreadsheet $excel, $sor, array $ertekek)
    {
        $oszlopok = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L'];
        $lap = $excel->setActiveSheetIndex(0);
        foreach ($ertekek as $i => $ertek) {
            $lap->setCellValue($oszlopok[$i] . $sor, $ertek);
        }
    }

    // ------------------------------------------------------------------

    private function readParams()
    {
        $this->datumstr = $this->params->getStringRequestParam('datum');
        $this->datumstr = date(\mkw\store::$DateFormat, strtotime(\mkw\store::convDate($this->datumstr)));

        // 0 = "Céges készlet": a raktárak összevonva, egy sorban
        $this->raktar = $this->params->getIntRequestParam('raktar');
        $r = $this->getRepo(Raktar::class)->find($this->raktar);
        $this->raktarnev = $r ? $r->getNev() : t('Céges készlet');

        $this->keszlettipus = $this->params->getIntRequestParam('keszlet', self::KESZLETVAN);
        $this->csakbecsult = $this->params->getBoolRequestParam('csakbecsult');
        $this->nevfilter = trim($this->params->getStringRequestParam('nevfilter'));

        $this->readFaFilter();
    }

    /** A kijelölt termékfák karkod-előtagjai – a készlet kimutatáséval azonos szűrő. */
    private function readFaFilter()
    {
        $this->faszuro = [];
        $this->fanevek = '';
        $fak = array_filter(array_map('intval', (array)$this->params->getArrayRequestParam('fafilter')));
        if (!$fak) {
            return;
        }
        $ff = new FilterDescriptor();
        $ff->addFilter('id', 'IN', $fak);
        $nevek = [];
        /** @var TermekFa $sor */
        foreach ($this->getRepo(TermekFa::class)->getAll($ff, []) as $sor) {
            $this->faszuro[] = $sor->getKarkod() . '%';
            $nevek[] = $sor->getNev();
        }
        $this->fanevek = implode(', ', $nevek);
    }

    /**
     * @return array{sorok: array, fedezetlen: array, osszertek: float, osszmennyiseg: float, utolsoszamitas: string}
     */
    protected function getData()
    {
        $this->readParams();

        $mai = date(\mkw\store::$DateFormat);
        $this->menetkozben = strtotime(\mkw\store::convDate($this->datumstr)) < strtotime(\mkw\store::convDate($mai));

        $csoportok = $this->menetkozben ? $this->getSzamoltCsoportok() : $this->getTaroltCsoportok();
        $csoportok = $this->enrich($csoportok);

        $sorok = [];
        $fedezetlen = [];
        $osszertek = 0;
        $osszmennyiseg = 0;
        foreach ($csoportok as $sor) {
            if ($sor['mennyiseg'] < 0) {
                $fedezetlen[] = $sor;
                continue;
            }
            $sorok[] = $sor;
            $osszertek += $sor['ertek'];
            $osszmennyiseg += $sor['mennyiseg'];
        }

        $utolso = $this->getRepo(Fifoertek::class)->getUtolsoSzamitas();

        return [
            'sorok' => $sorok,
            'fedezetlen' => $fedezetlen,
            'osszertek' => round($osszertek, 2),
            'osszmennyiseg' => round($osszmennyiseg, 2),
            'utolsoszamitas' => $utolso ? date(\mkw\store::$DateTimeFormat, strtotime($utolso)) : '',
        ];
    }

    /** A tárolt értékek – a mai nap esete. */
    private function getTaroltCsoportok()
    {
        $conn = $this->getEm()->getConnection();
        $params = [];
        $where = [];

        if ($this->raktar) {
            $where[] = 'f.raktar_id = :raktar';
            $params['raktar'] = $this->raktar;
        }
        if ($this->keszlettipus == self::KESZLETVAN) {
            $where[] = 'f.mennyiseg > 0';
        } elseif ($this->keszlettipus == self::KESZLETFEDEZETLEN) {
            $where[] = 'f.mennyiseg < 0';
        }
        if ($this->csakbecsult) {
            $where[] = 'f.becsult = 1';
        }
        foreach ($this->getTermekFeltetelek($params) as $felt) {
            $where[] = $felt;
        }

        // A raktárakat NEM vonjuk össze: van Raktár oszlop, és az összevonás félrevezetne –
        // egy másik raktár fedezetlen készlete kinullázná a mennyiséget úgy, hogy az érték
        // (a máshol tényleg meglévő rétegeké) ott marad mellette.
        $sql = 'SELECT f.raktar_id AS raktarid, f.termek_id AS termekid,'
            . ' f.termekvaltozat_id AS valtozatid, f.mennyiseg, f.ertek, f.egysegertek,'
            . ' f.becsult, f.retegdb'
            . ' FROM fifoertek f'
            . ' INNER JOIN termek t ON (t.id = f.termek_id)'
            . ($where ? ' WHERE ' . implode(' AND ', $where) : '');

        $ret = [];
        foreach ($conn->fetchAllAssociative($sql, $params) as $sor) {
            $ret[] = [
                'raktarid' => $sor['raktarid'] ? (int)$sor['raktarid'] : null,
                'termekid' => (int)$sor['termekid'],
                'valtozatid' => $sor['valtozatid'] ? (int)$sor['valtozatid'] : null,
                'mennyiseg' => (float)$sor['mennyiseg'],
                'ertek' => (float)$sor['ertek'],
                'egysegertek' => $sor['egysegertek'] === null ? null : (float)$sor['egysegertek'],
                'becsult' => (bool)$sor['becsult'],
                'retegdb' => (int)$sor['retegdb'],
            ];
        }
        return $ret;
    }

    /** Múltbeli dátum: menet közben számolunk, a szűrt termékekre szűkítve. */
    private function getSzamoltCsoportok()
    {
        $termekids = $this->getSzurtTermekIds();
        $eredmeny = (new FifoService())->calculateAsOf(
            date('Y-m-d', strtotime(\mkw\store::convDate($this->datumstr))),
            $this->raktar ?: null,
            $termekids
        );

        $ret = [];
        foreach ($eredmeny as $sor) {
            $sor['retegek'] = array_map(fn($reteg) => [
                'bizonylatszam' => $reteg['fejid'],
                'teljesites' => $reteg['teljesites'],
                'mennyiseg' => $reteg['mennyiseg'] / 100,
                'egysegar' => $reteg['egysegar'],
                'becsult' => $reteg['becsult'],
            ], $sor['retegek']);
            if (($this->keszlettipus == self::KESZLETVAN) && ($sor['mennyiseg'] <= 0)) {
                continue;
            }
            if (($this->keszlettipus == self::KESZLETFEDEZETLEN) && ($sor['mennyiseg'] >= 0)) {
                continue;
            }
            if ($this->csakbecsult && !$sor['becsult']) {
                continue;
            }
            $sor['egysegertek'] = $sor['mennyiseg'] > 0 ? round($sor['ertek'] / $sor['mennyiseg'], 4) : null;
            $ret[] = $sor;
        }
        return $ret;
    }

    /**
     * A csoportok kiegészítése névvel, cikkszámmal, változatértékekkel és – a tárolt ágon –
     * a rétegbontással. Egy lekérdezés az egészre, nem soronként.
     */
    private function enrich(array $csoportok)
    {
        if (!$csoportok) {
            return [];
        }
        $conn = $this->getEm()->getConnection();

        $termekids = array_values(array_unique(array_column($csoportok, 'termekid')));
        $termekek = [];
        foreach ($conn->fetchAllAssociative(
            'SELECT id, cikkszam, nev FROM termek WHERE id IN (' . implode(',', array_map('intval', $termekids)) . ')'
        ) as $sor) {
            $termekek[(int)$sor['id']] = $sor;
        }

        $valtozatids = array_values(array_filter(array_unique(array_column($csoportok, 'valtozatid'))));
        $valtozatok = [];
        if ($valtozatids) {
            foreach ($conn->fetchAllAssociative(
                'SELECT id, ertek1, ertek2 FROM termekvaltozat WHERE id IN (' . implode(',', array_map('intval', $valtozatids)) . ')'
            ) as $sor) {
                $valtozatok[(int)$sor['id']] = $sor;
            }
        }

        $raktarak = [];
        foreach ($conn->fetchAllAssociative('SELECT id, nev FROM raktar') as $sor) {
            $raktarak[(int)$sor['id']] = $sor['nev'];
        }

        $retegek = [];
        if (!$this->menetkozben) {
            $kulcsok = array_map(fn($cs) => [$cs['raktarid'], $cs['termekid'], $cs['valtozatid']], $csoportok);
            foreach ($this->getRepo(Fiforeteg::class)->getRetegek($kulcsok) as $reteg) {
                $kulcs = (int)$reteg['raktarid'] . '|' . (int)$reteg['termekid'] . '|' . (int)$reteg['valtozatid'];
                $retegek[$kulcs][] = [
                    'bizonylatszam' => $reteg['bizonylatszam'],
                    'teljesites' => $reteg['teljesites'],
                    'mennyiseg' => (float)$reteg['mennyiseg'],
                    'egysegar' => (float)$reteg['egysegar'],
                    'becsult' => (bool)$reteg['becsult'],
                ];
            }
        }

        $ret = [];
        foreach ($csoportok as $sor) {
            $termek = $termekek[$sor['termekid']] ?? null;
            if ($this->nevfilter && $termek
                && (mb_stripos($termek['nev'], $this->nevfilter) === false)
                && (mb_stripos((string)$termek['cikkszam'], $this->nevfilter) === false)) {
                continue;
            }
            $valtozat = $sor['valtozatid'] ? ($valtozatok[$sor['valtozatid']] ?? null) : null;
            $kulcs = (int)$sor['raktarid'] . '|' . $sor['termekid'] . '|' . (int)$sor['valtozatid'];
            $sor['cikkszam'] = $termek['cikkszam'] ?? '';
            $sor['termeknev'] = $termek['nev'] ?? '';
            $sor['ertek1'] = $valtozat['ertek1'] ?? '';
            $sor['ertek2'] = $valtozat['ertek2'] ?? '';
            $sor['raktarnev'] = $sor['raktarid'] ? ($raktarak[$sor['raktarid']] ?? '') : t('Céges készlet');
            $sor['retegek'] = $sor['retegek'] ?? ($retegek[$kulcs] ?? []);
            $ret[] = $sor;
        }

        usort($ret, fn($a, $b) => [$a['cikkszam'], $a['ertek1'], $a['ertek2'], $a['raktarnev']]
            <=> [$b['cikkszam'], $b['ertek1'], $b['ertek2'], $b['raktarnev']]);

        return $ret;
    }

    /** A termékre vonatkozó szűrések SQL-feltételei – a termékre `t` néven hivatkozunk. */
    private function getTermekFeltetelek(array &$params)
    {
        $feltetelek = [];
        if ($this->faszuro) {
            $agak = [];
            foreach ($this->faszuro as $i => $ertek) {
                foreach (['termekfa1karkod', 'termekfa2karkod', 'termekfa3karkod'] as $mezo) {
                    $agak[] = 't.' . $mezo . ' LIKE :fa' . $i;
                }
                $params['fa' . $i] = $ertek;
            }
            $feltetelek[] = '(' . implode(' OR ', $agak) . ')';
        }
        return $feltetelek;
    }

    /** A menet közbeni számoláshoz: mely termékekre kell egyáltalán futtatni. */
    private function getSzurtTermekIds()
    {
        $params = [];
        $where = $this->getTermekFeltetelek($params);
        if (!$where && !$this->nevfilter) {
            return [];
        }
        if ($this->nevfilter) {
            $where[] = '(t.nev LIKE :nev OR t.cikkszam LIKE :nev)';
            $params['nev'] = '%' . $this->nevfilter . '%';
        }
        return array_map('intval', $this->getEm()->getConnection()->fetchFirstColumn(
            'SELECT t.id FROM termek t WHERE ' . implode(' AND ', $where),
            $params
        ));
    }

    private function kuldExcel(Spreadsheet $excel, $nevprefix)
    {
        $writer = IOFactory::createWriter($excel, 'Xlsx');
        $filename = uniqid($nevprefix . '-' . \mkw\store::urlize($this->raktarnev)) . '.xlsx';
        $filepath = \mkw\store::storagePath($filename);
        $writer->save($filepath);

        header('Cache-Control: private');
        header('Content-Type: application/stream');
        header('Content-Length: ' . filesize($filepath));
        header('Content-Disposition: attachment; filename=' . $filename);

        readfile($filepath);
        \unlink($filepath);
    }

    private function json(array $data)
    {
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

}
