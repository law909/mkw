<?php

namespace Controllers;

use Doctrine\ORM\Query\ResultSetMapping;
use Entities\Raktar;
use Entities\TermekFa;
use mkwhelpers\FilterDescriptor;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

/**
 * Minimum készlet alatti termékek riportja.
 *
 * Egy adott raktárban, egy adott napon megmutatja azokat a termékeket/változatokat, amelyek
 * készlete a **raktárra vonatkozó** minimum alatt van (a feloldási létrát lásd
 * \Services\KeszletService). Kiírja a készletet, a minimumig hiányzó mennyiséget, és egy
 * másik, megadott raktárban lévő készletet – hogy látszódjon, van-e honnan átmozgatni.
 *
 * Három kimenet: képernyős nézet, Excel export, és egy szűkebb Excel, amiből bizonylat készíthető
 * (termék id, változat id, cikkszám, vonalkód, feltöltéshez szükséges mennyiség).
 *
 * A változat nélküli termékek is benne vannak: azokra a bizonylattétel a termékre hivatkozik,
 * változat nélkül, ezért a lekérdezés két ágból áll.
 */
class minkeszletlistaController extends \mkwhelpers\Controller
{

    private $datumstr;
    private $raktar;
    private $raktarnev;
    private $masikraktar;
    private $masikraktarnev;
    private $gyarto;
    private $gyartonev;
    /** @var string[] a kijelölt termékfák karkod-előtagjai */
    private $faszuro = [];
    private $fanevek = '';
    /** a minimum készlet helyett figyelt, kézzel megadott küszöb */
    private $limit;
    private $uselimit = false;

    public function view()
    {
        $view = $this->createView('minkeszletlista.tpl');
        $view->setVar('datum', date(\mkw\store::$DateFormat));
        $rc = new raktarController();
        $view->setVar('raktarlist', $rc->getSelectList(\mkw\store::getParameter(\mkw\consts::Raktar)));
        $view->setVar('masikraktarlist', $rc->getSelectList());
        $gyarto = new partnerController();
        $view->setVar('gyartolist', $gyarto->getSzallitoSelectList(0));
        $view->printTemplateResult();
    }

    private function readParams()
    {
        $this->datumstr = $this->params->getStringRequestParam('datum');
        $this->datumstr = date(\mkw\store::$DateFormat, strtotime(\mkw\store::convDate($this->datumstr)));

        // 0 = "Céges készlet": az összes raktár készlete a "Minden raktár" minimumhoz mérve
        $this->raktar = $this->params->getIntRequestParam('raktar');
        $r = $this->getRepo(Raktar::class)->find($this->raktar);
        $this->raktarnev = $r ? $r->getNev() : t('Céges készlet');

        $this->masikraktar = $this->params->getIntRequestParam('masikraktar');
        $mr = $this->getRepo(Raktar::class)->find($this->masikraktar);
        $this->masikraktarnev = $mr ? $mr->getNev() : '';

        $this->gyarto = $this->params->getIntRequestParam('gyarto');
        $gy = $this->getRepo(\Entities\Partner::class)->find($this->gyarto);
        $this->gyartonev = $gy ? $gy->getNev() : '';

        $this->uselimit = $this->params->getBoolRequestParam('keszletszamit');
        $this->limit = $this->params->getFloatRequestParam('keszlet');

        $this->readFaFilter();
    }

    /**
     * A kijelölt termékfák karkod-előtagjai – a termék a három fa-mezője bármelyikével
     * beleeshet a kijelölt ágba, ugyanúgy, mint a készlet kimutatásban.
     */
    private function readFaFilter()
    {
        $this->faszuro = [];
        $this->fanevek = '';
        $fak = $this->params->getArrayRequestParam('fafilter');
        $fak = array_filter(array_map('intval', (array)$fak));
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
     * A termékre vonatkozó szűrések SQL-feltételei. Mindkét ág `t` néven hivatkozik a termékre.
     *
     * @return string[]
     */
    private function getTermekFeltetelek()
    {
        $feltetelek = [];
        if ($this->gyarto) {
            $feltetelek[] = 't.gyarto_id = :gyarto';
        }
        if ($this->faszuro) {
            $agak = [];
            foreach (array_keys($this->faszuro) as $i) {
                foreach (['termekfa1karkod', 'termekfa2karkod', 'termekfa3karkod'] as $mezo) {
                    $agak[] = 't.' . $mezo . ' LIKE :fa' . $i;
                }
            }
            $feltetelek[] = '(' . implode(' OR ', $agak) . ')';
        }
        return $feltetelek;
    }

    /**
     * Készlet egy raktárban, a megadott napig bezárólag. Ugyanaz a szűrés, mint a
     * Termek::getKeszlet()-ben: a lerontott bizonylat tételei is rontottak (Bizonylatfej::setRontott
     * végigviszi), ezért a tétel rontott jelzője elég.
     *
     * @param string $tetelfeltetel a tételt a sorhoz kötő SQL feltétel
     * @param string $raktarparam a raktár kötött paraméterének neve kettőspont nélkül,
     *                            üresen az összes raktár együtt (céges készlet)
     */
    private function getKeszletSql($tetelfeltetel, $raktarparam)
    {
        return 'COALESCE((SELECT SUM(bt.mennyiseg * bt.irany)'
            . ' FROM bizonylattetel bt'
            . ' LEFT OUTER JOIN bizonylatfej bf ON (bt.bizonylatfej_id = bf.id)'
            . ' WHERE bt.mozgat = 1 AND ((bt.rontott = 0) OR (bt.rontott IS NULL))'
            . ' AND bf.teljesites <= :datum'
            . ($raktarparam ? ' AND bf.raktar_id = :' . $raktarparam : '')
            . ' AND ' . $tetelfeltetel . '), 0)';
    }

    protected function getData()
    {
        $this->readParams();
        // céges készletnél nincs raktárszűrés, és a minimum a "Minden raktár" érték
        $raktarparam = $this->raktar ? 'raktar' : '';

        $oszlopok = [
            'termek_id',
            'termekvaltozat_id',
            'cikkszam',
            'vonalkod',
            'termeknev',
            'ertek1',
            'ertek2',
            'keszlet',
            'masikkeszlet',
            'minkeszlet',
        ];
        $rsm = new ResultSetMapping();
        foreach ($oszlopok as $oszlop) {
            $rsm->addScalarResult($oszlop, $oszlop);
        }

        $termekfeltetelek = $this->getTermekFeltetelek();
        $termekszuro = $termekfeltetelek ? implode(' AND ', $termekfeltetelek) : '';

        // bekapcsolt pipánál a beírt érték a küszöb minden soron, egyébként a raktáranként
        // feloldott minimum (a nulla minimum ott „nincs beállítva”, ezért esik ki)
        $valtozatmin = $this->uselimit
            ? ':limit'
            : \Services\KeszletService::getMinKeszletSql(
                '_xx.termek_id',
                't.minkeszlet',
                '_xx.id',
                '_xx.minkeszlet',
                $raktarparam
            );
        $termekmin = $this->uselimit
            ? ':limit'
            : \Services\KeszletService::getMinKeszletSql('t.id', 't.minkeszlet', '', '', $raktarparam);

        // változatos ág: a tétel a változatra hivatkozik
        $valtozatag = 'SELECT _xx.termek_id AS termek_id, _xx.id AS termekvaltozat_id,'
            . " COALESCE(NULLIF(_xx.cikkszam, ''), t.cikkszam) AS cikkszam,"
            . " COALESCE(NULLIF(_xx.vonalkod, ''), t.vonalkod) AS vonalkod,"
            . ' t.nev AS termeknev, _xx.ertek1 AS ertek1, _xx.ertek2 AS ertek2,'
            . ' ' . $this->getKeszletSql('bt.termekvaltozat_id = _xx.id', $raktarparam) . ' AS keszlet,'
            . ' ' . $this->getKeszletSql('bt.termekvaltozat_id = _xx.id', 'masikraktar') . ' AS masikkeszlet,'
            . ' ' . $valtozatmin . ' AS minkeszlet'
            . ' FROM termekvaltozat _xx'
            . ' LEFT JOIN termek t ON (t.id = _xx.termek_id)'
            . ($termekszuro ? ' WHERE ' . $termekszuro : '');

        // változat nélküli termékek: a tétel a termékre hivatkozik, változat nélkül
        $termekag = 'SELECT t.id AS termek_id, NULL AS termekvaltozat_id,'
            . ' t.cikkszam AS cikkszam, t.vonalkod AS vonalkod,'
            . " t.nev AS termeknev, '' AS ertek1, '' AS ertek2,"
            . ' ' . $this->getKeszletSql('bt.termek_id = t.id AND bt.termekvaltozat_id IS NULL', $raktarparam) . ' AS keszlet,'
            . ' ' . $this->getKeszletSql('bt.termek_id = t.id AND bt.termekvaltozat_id IS NULL', 'masikraktar') . ' AS masikkeszlet,'
            . ' ' . $termekmin . ' AS minkeszlet'
            . ' FROM termek t'
            . ' WHERE NOT EXISTS (SELECT 1 FROM termekvaltozat v WHERE v.termek_id = t.id)'
            . ($termekszuro ? ' AND ' . $termekszuro : '');

        $q = $this->getEm()->createNativeQuery(
            'SELECT * FROM (' . $valtozatag . ' UNION ALL ' . $termekag . ') x'
            . ' WHERE ' . ($this->uselimit ? '' : '(x.minkeszlet > 0) AND ') . '(x.keszlet < x.minkeszlet)'
            . ' ORDER BY x.cikkszam, x.termeknev, x.ertek1, x.ertek2',
            $rsm
        );
        $parameterek = [
            'datum' => $this->datumstr,
            'masikraktar' => $this->masikraktar ?: 0,
        ];
        if ($raktarparam) {
            $parameterek['raktar'] = $this->raktar;
        }
        if ($this->gyarto) {
            $parameterek['gyarto'] = $this->gyarto;
        }
        foreach ($this->faszuro as $i => $karkod) {
            $parameterek['fa' . $i] = $karkod;
        }
        if ($this->uselimit) {
            $parameterek['limit'] = $this->limit;
        }
        $q->setParameters($parameterek);

        $ret = [];
        foreach ($q->getScalarResult() as $sor) {
            $sor['hiany'] = $sor['minkeszlet'] - $sor['keszlet'];
            $ret[] = $sor;
        }
        return $ret;
    }

    public function createLista()
    {
        $lista = $this->getData();
        $report = $this->createView('rep_minkeszlet.tpl');
        $report->setVar('lista', $lista);
        $report->setVar('datumstr', $this->datumstr);
        $report->setVar('raktar', $this->raktarnev);
        $report->setVar('masikraktar', $this->masikraktarnev);
        $report->setVar('gyarto', $this->gyartonev);
        $report->setVar('termekfa', $this->fanevek);
        $report->setVar('uselimit', $this->uselimit);
        $report->setVar('limit', $this->limit);
        $report->setVar('printdatum', date(\mkw\store::$DateTimeFormat));
        $report->printTemplateResult();
    }

    /**
     * A képernyős nézettel azonos tartalom Excelben.
     */
    public function exportLista()
    {
        $excel = new Spreadsheet();
        $excel->setActiveSheetIndex(0)
            ->setCellValue('A1', t('Cikkszám'))
            ->setCellValue('B1', t('Vonalkód'))
            ->setCellValue('C1', t('Termék'))
            ->setCellValue('D1', t('Változat'))
            ->setCellValue('E1', t('Készlet'))
            ->setCellValue('F1', t('Min. készlet'))
            ->setCellValue('G1', t('Hiány'))
            ->setCellValue('H1', $this->masikraktarnev ?: t('Ebből a raktárból kiszolgálható'));

        $sor = 2;
        foreach ($this->getData() as $item) {
            $excel->setActiveSheetIndex(0)
                ->setCellValue('A' . $sor, $item['cikkszam'])
                ->setCellValue('B' . $sor, $item['vonalkod'])
                ->setCellValue('C' . $sor, $item['termeknev'])
                ->setCellValue('D' . $sor, trim($item['ertek1'] . ' ' . $item['ertek2']))
                ->setCellValue('E' . $sor, (float)$item['keszlet'])
                ->setCellValue('F' . $sor, (float)$item['minkeszlet'])
                ->setCellValue('G' . $sor, (float)$item['hiany'])
                ->setCellValue('H' . $sor, (float)$item['masikkeszlet']);
            $sor++;
        }

        $this->kuldExcel($excel, 'minkeszlet');
    }

    /**
     * Bizonylatkészítéshez való, szűkebb Excel: azonosítók és a minimumig való feltöltéshez
     * szükséges mennyiség. A fejléc nevei az importok szokásos oszlopai.
     */
    public function exportBizonylat()
    {
        $excel = new Spreadsheet();
        $excel->setActiveSheetIndex(0)
            ->setCellValue('A1', t('Termék ID'))
            ->setCellValue('B1', t('Változat ID'))
            ->setCellValue('C1', t('Cikkszám'))
            ->setCellValue('D1', t('Vonalkód'))
            ->setCellValue('E1', t('Mennyiség'));

        $sor = 2;
        foreach ($this->getData() as $item) {
            $excel->setActiveSheetIndex(0)
                ->setCellValue('A' . $sor, (int)$item['termek_id'])
                ->setCellValue('B' . $sor, $item['termekvaltozat_id'] ? (int)$item['termekvaltozat_id'] : '')
                ->setCellValue('C' . $sor, $item['cikkszam'])
                ->setCellValue('D' . $sor, $item['vonalkod'])
                ->setCellValue('E' . $sor, (float)$item['hiany']);
            $sor++;
        }

        $this->kuldExcel($excel, 'minkeszlet-bizonylat');
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

}
