<?php

namespace Controllers;

use Doctrine\ORM\Query\ResultSetMapping;
use Entities\Raktar;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

/**
 * Minimum készlet alatti termékek riportja.
 *
 * Egy adott raktárban, egy adott napon megmutatja azokat a termékeket/változatokat, amelyek
 * készlete a **raktárra vonatkozó** minimum alatt van (a feloldási létrát lásd
 * \Services\MinBoltiKeszletService). Kiírja a készletet, a minimumig hiányzó mennyiséget, és egy
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

    public function view()
    {
        $view = $this->createView('minkeszletlista.tpl');
        $view->setVar('datum', date(\mkw\store::$DateFormat));
        $rc = new raktarController();
        $view->setVar('raktarlist', $rc->getSelectList(\mkw\store::getParameter(\mkw\consts::Raktar)));
        $view->setVar('masikraktarlist', $rc->getSelectList());
        $view->printTemplateResult();
    }

    private function readParams()
    {
        $this->datumstr = $this->params->getStringRequestParam('datum');
        $this->datumstr = date(\mkw\store::$DateFormat, strtotime(\mkw\store::convDate($this->datumstr)));

        $this->raktar = $this->params->getIntRequestParam('raktar');
        $r = $this->getRepo(Raktar::class)->find($this->raktar);
        $this->raktarnev = $r ? $r->getNev() : '';

        $this->masikraktar = $this->params->getIntRequestParam('masikraktar');
        $mr = $this->getRepo(Raktar::class)->find($this->masikraktar);
        $this->masikraktarnev = $mr ? $mr->getNev() : '';
    }

    /**
     * Készlet egy raktárban, a megadott napig bezárólag. Ugyanaz a szűrés, mint a
     * Termek::getKeszlet()-ben: a lerontott bizonylat tételei is rontottak (Bizonylatfej::setRontott
     * végigviszi), ezért a tétel rontott jelzője elég.
     *
     * @param string $tetelfeltetel a tételt a sorhoz kötő SQL feltétel
     * @param string $raktarparam a raktár kötött paraméterének neve, kettőspont nélkül
     */
    private function getKeszletSql($tetelfeltetel, $raktarparam)
    {
        return 'COALESCE((SELECT SUM(bt.mennyiseg * bt.irany)'
            . ' FROM bizonylattetel bt'
            . ' LEFT OUTER JOIN bizonylatfej bf ON (bt.bizonylatfej_id = bf.id)'
            . ' WHERE bt.mozgat = 1 AND ((bt.rontott = 0) OR (bt.rontott IS NULL))'
            . ' AND bf.teljesites <= :datum AND bf.raktar_id = :' . $raktarparam
            . ' AND ' . $tetelfeltetel . '), 0)';
    }

    protected function getData()
    {
        $this->readParams();
        if (!$this->raktar) {
            return [];
        }

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

        // változatos ág: a tétel a változatra hivatkozik
        $valtozatag = 'SELECT _xx.termek_id AS termek_id, _xx.id AS termekvaltozat_id,'
            . " COALESCE(NULLIF(_xx.cikkszam, ''), t.cikkszam) AS cikkszam,"
            . " COALESCE(NULLIF(_xx.vonalkod, ''), t.vonalkod) AS vonalkod,"
            . ' t.nev AS termeknev, _xx.ertek1 AS ertek1, _xx.ertek2 AS ertek2,'
            . ' ' . $this->getKeszletSql('bt.termekvaltozat_id = _xx.id', 'raktar') . ' AS keszlet,'
            . ' ' . $this->getKeszletSql('bt.termekvaltozat_id = _xx.id', 'masikraktar') . ' AS masikkeszlet,'
            . ' ' . \Services\KeszletService::getMinKeszletSql(
                '_xx.termek_id',
                't.minboltikeszlet',
                '_xx.id',
                '_xx.minboltikeszlet',
                'raktar'
            ) . ' AS minkeszlet'
            . ' FROM termekvaltozat _xx'
            . ' LEFT JOIN termek t ON (t.id = _xx.termek_id)';

        // változat nélküli termékek: a tétel a termékre hivatkozik, változat nélkül
        $termekag = 'SELECT t.id AS termek_id, NULL AS termekvaltozat_id,'
            . ' t.cikkszam AS cikkszam, t.vonalkod AS vonalkod,'
            . " t.nev AS termeknev, '' AS ertek1, '' AS ertek2,"
            . ' ' . $this->getKeszletSql('bt.termek_id = t.id AND bt.termekvaltozat_id IS NULL', 'raktar') . ' AS keszlet,'
            . ' ' . $this->getKeszletSql('bt.termek_id = t.id AND bt.termekvaltozat_id IS NULL', 'masikraktar') . ' AS masikkeszlet,'
            . ' ' . \Services\KeszletService::getMinKeszletSql('t.id', 't.minboltikeszlet', '', '', 'raktar')
            . ' AS minkeszlet'
            . ' FROM termek t'
            . ' WHERE NOT EXISTS (SELECT 1 FROM termekvaltozat v WHERE v.termek_id = t.id)';

        $q = $this->getEm()->createNativeQuery(
            'SELECT * FROM (' . $valtozatag . ' UNION ALL ' . $termekag . ') x'
            . ' WHERE (x.minkeszlet > 0) AND (x.keszlet < x.minkeszlet)'
            . ' ORDER BY x.cikkszam, x.termeknev, x.ertek1, x.ertek2',
            $rsm
        );
        $q->setParameters([
            'datum' => $this->datumstr,
            'raktar' => $this->raktar,
            'masikraktar' => $this->masikraktar ?: 0,
        ]);

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
            ->setCellValue('H1', $this->masikraktarnev ?: t('Másik raktár'));

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
        $filepath = \mkw\store::storagePath(
            uniqid($nevprefix . '-' . \mkw\store::urlize($this->raktarnev)) . '.xlsx'
        );
        $writer->save($filepath);

        header('Cache-Control: private');
        header('Content-Type: application/stream');
        header('Content-Length: ' . filesize($filepath));
        header('Content-Disposition: attachment; filename=' . $filepath);

        readfile($filepath);
        \unlink($filepath);
    }

}
