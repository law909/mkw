<?php

namespace Controllers;

use Doctrine\ORM\Query\ResultSetMapping;
use Entities\Arfolyam;
use Entities\Bizonylatfej;
use Entities\Bizonylattetel;
use Entities\Bizonylattipus;
use Entities\Partner;
use Entities\Raktar;
use Entities\Termek;
use Entities\TermekFa;
use Entities\TermekValtozat;
use Entities\Valutanem;
use mkwhelpers\FilterDescriptor;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

/**
 * Gyártói rendelési javaslat: egy gyártó termékeiből mennyit kell rendelni.
 *
 * Rendelendő = optimális készlet − szabad készlet − érkező mennyiség; csak a pozitív sorok
 * kerülnek a listára. A szabad készlet készlet − foglalás, a Beállítások szerint a min. készlettel
 * is csökkentve (\Services\KeszletService::isSzabadKeszletMinkeszlettel()). Optimális készlet
 * nélküli termék kimarad: az nem ehhez a listához tartozik, a hiányát a „Minimum készlet alatt"
 * mutatja.
 *
 * Három kimenet: képernyős riport, Excel export, és a sorokból képzett szállítói megrendelés.
 *
 * A változat nélküli termékek is benne vannak: azokra a bizonylattétel a termékre hivatkozik,
 * változat nélkül, ezért a lekérdezés két ágból áll – ugyanúgy, mint a minkeszletlistaController.
 */
class gyartoirendelesController extends \mkwhelpers\Controller
{

    private $datumstr;
    private $raktar;
    private $raktarnev;
    private $gyarto;
    private $gyartonev;
    /** @var string[] a kijelölt termékfák karkod-előtagjai */
    private $faszuro = [];
    private $fanevek = '';

    public function view()
    {
        $view = $this->createView('gyartoirendeles.tpl');
        $view->setVar('datum', date(\mkw\store::$DateFormat));
        $rc = new raktarController();
        $view->setVar('raktarlist', $rc->getSelectList(\mkw\store::getParameter(\mkw\consts::Raktar)));
        $gyarto = new partnerController();
        $view->setVar('gyartolist', $gyarto->getGyartoSelectList(0));
        $view->printTemplateResult();
    }

    private function readParams()
    {
        $this->datumstr = $this->params->getStringRequestParam('datum');
        $this->datumstr = date(\mkw\store::$DateFormat, strtotime(\mkw\store::convDate($this->datumstr)));

        // 0 = "Céges készlet": minden raktár együtt, a raktárfüggetlen optimális készlethez mérve
        $this->raktar = $this->params->getIntRequestParam('raktar');
        $r = $this->getRepo(Raktar::class)->find($this->raktar);
        $this->raktarnev = $r ? $r->getNev() : t('Céges készlet');

        $this->gyarto = $this->params->getIntRequestParam('gyarto');
        $gy = $this->getRepo(Partner::class)->find($this->gyarto);
        $this->gyartonev = $gy ? $gy->getNev() : '';

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
        $feltetelek = ['t.inaktiv = 0'];
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
     * Bizonylattételekből összegzett mennyiség a megadott napig bezárólag. A szűrés a
     * \Services\KeszletService megfelelő ágával egyezik, csak natív SQL-ben.
     *
     * @param string $tetelfeltetel a tételt a sorhoz kötő SQL feltétel
     * @param string $mozgasfeltetel a mozgás fajtáját leíró feltétel (készlet / foglalás / érkezés)
     */
    private function getMozgasSql($tetelfeltetel, $mozgasfeltetel)
    {
        return 'COALESCE((SELECT SUM(bt.mennyiseg * bt.irany)'
            . ' FROM bizonylattetel bt'
            . ' LEFT OUTER JOIN bizonylatfej bf ON (bt.bizonylatfej_id = bf.id)'
            . ' WHERE ((bt.rontott = 0) OR (bt.rontott IS NULL))'
            . ' AND bf.teljesites <= :datum'
            . ($this->raktar ? ' AND bf.raktar_id = :raktar' : '')
            . ' AND ' . $tetelfeltetel
            . ' AND ' . $mozgasfeltetel
            . '), 0)';
    }

    /** A foglaló bizonylattípusok kötött paraméterei – a lista a törzsből jön, nem fix. */
    private function getFoglaloTipusSql(array &$parameterek)
    {
        $tipusok = Bizonylattipus::getFoglalIdList();
        if (!$tipusok) {
            return '';
        }
        $nevek = [];
        foreach (array_values($tipusok) as $i => $tipus) {
            $nevek[] = ':foglalotipus' . $i;
            $parameterek['foglalotipus' . $i] = $tipus;
        }
        return 'bf.bizonylattipus_id IN (' . implode(',', $nevek) . ')';
    }

    /**
     * Az adott termékre/változatra már megérkezett mennyiség feltétele: azok a társbizonylatok,
     * amelyek éppen ennek a sornak az érkezését zárják le.
     */
    private function getMegjottSql($oszlop)
    {
        return 'bf.tarsbizonylat_id IN (SELECT ebt.bizonylatfej_id FROM bizonylattetel ebt'
            . ' WHERE (ebt.erkezik = 1) AND (ebt.' . $oszlop . ' = bt.' . $oszlop . '))';
    }

    protected function getData()
    {
        $this->readParams();
        // céges készletnél nincs raktárszűrés, és az optimális készlet a "Minden raktár" érték
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
            'foglalt',
            'erkezik',
            'optkeszlet',
            'minkeszlet',
            'rendelendo',
        ];
        $rsm = new ResultSetMapping();
        foreach ($oszlopok as $oszlop) {
            $rsm->addScalarResult($oszlop, $oszlop);
        }

        $parameterek = [
            'datum' => $this->datumstr,
        ];
        $foglalotipus = $this->getFoglaloTipusSql($parameterek);

        $termekfeltetelek = $this->getTermekFeltetelek();
        $termekszuro = implode(' AND ', $termekfeltetelek);

        $valtozatopt = \Services\KeszletService::getKeszletSzintSql(
            'opt',
            '_xx.termek_id',
            't.optkeszlet',
            '_xx.id',
            '_xx.optkeszlet',
            $raktarparam
        );
        $termekopt = \Services\KeszletService::getKeszletSzintSql(
            'opt',
            't.id',
            't.optkeszlet',
            '',
            '',
            $raktarparam
        );
        $minkeszlettel = \Services\KeszletService::isSzabadKeszletMinkeszlettel();
        $valtozatmin = $minkeszlettel
            ? \Services\KeszletService::getMinKeszletSql('_xx.termek_id', 't.minkeszlet', '_xx.id', '_xx.minkeszlet', $raktarparam)
            : '0';
        $termekmin = $minkeszlettel
            ? \Services\KeszletService::getMinKeszletSql('t.id', 't.minkeszlet', '', '', $raktarparam)
            : '0';

        $agak = [];
        foreach (
            [
                // változatos ág: a tétel a változatra hivatkozik
                [
                    'tetel' => 'bt.termekvaltozat_id = _xx.id',
                    'oszlop' => 'termekvaltozat_id',
                    'opt' => $valtozatopt,
                    'min' => $valtozatmin,
                    'select' => '_xx.termek_id AS termek_id, _xx.id AS termekvaltozat_id,'
                        . " COALESCE(NULLIF(_xx.cikkszam, ''), t.cikkszam) AS cikkszam,"
                        . " COALESCE(NULLIF(_xx.vonalkod, ''), t.vonalkod) AS vonalkod,"
                        . ' t.nev AS termeknev, _xx.ertek1 AS ertek1, _xx.ertek2 AS ertek2',
                    'from' => ' FROM termekvaltozat _xx'
                        . ' LEFT JOIN termek t ON (t.id = _xx.termek_id)'
                        . ' WHERE (_xx.inaktiv = 0) AND ' . $termekszuro,
                ],
                // változat nélküli termékek: a tétel a termékre hivatkozik, változat nélkül
                [
                    'tetel' => 'bt.termek_id = t.id AND bt.termekvaltozat_id IS NULL',
                    'oszlop' => 'termek_id',
                    'opt' => $termekopt,
                    'min' => $termekmin,
                    'select' => 't.id AS termek_id, NULL AS termekvaltozat_id,'
                        . ' t.cikkszam AS cikkszam, t.vonalkod AS vonalkod,'
                        . " t.nev AS termeknev, '' AS ertek1, '' AS ertek2",
                    'from' => ' FROM termek t'
                        . ' WHERE NOT EXISTS (SELECT 1 FROM termekvaltozat v WHERE v.termek_id = t.id)'
                        . ' AND ' . $termekszuro,
                ],
            ] as $ag
        ) {
            $erkezik = $this->getMozgasSql($ag['tetel'], 'bt.erkezik = 1')
                . ' - ' . $this->getMozgasSql($ag['tetel'], $this->getMegjottSql($ag['oszlop']));
            $agak[] = 'SELECT ' . $ag['select'] . ','
                . ' ' . $this->getMozgasSql($ag['tetel'], 'bt.mozgat = 1') . ' AS keszlet,'
                . ' ' . ($foglalotipus
                    ? '-1 * ' . $this->getMozgasSql($ag['tetel'], 'bt.foglal = 1 AND ' . $foglalotipus)
                    : '0') . ' AS foglalt,'
                . ' (' . $erkezik . ') AS erkezik,'
                . ' ' . $ag['opt'] . ' AS optkeszlet,'
                . ' ' . $ag['min'] . ' AS minkeszlet'
                . $ag['from'];
        }

        $q = $this->getEm()->createNativeQuery(
            'SELECT y.* FROM ('
            . ' SELECT x.*, (x.optkeszlet - (x.keszlet - x.foglalt - x.minkeszlet) - x.erkezik) AS rendelendo'
            . ' FROM (' . implode(' UNION ALL ', $agak) . ') x'
            . ') y'
            . ' WHERE (y.optkeszlet > 0) AND (y.rendelendo > 0)'
            . ' ORDER BY y.cikkszam, y.termeknev, y.ertek1, y.ertek2',
            $rsm
        );
        if ($raktarparam) {
            $parameterek['raktar'] = $this->raktar;
        }
        if ($this->gyarto) {
            $parameterek['gyarto'] = $this->gyarto;
        }
        foreach ($this->faszuro as $i => $karkod) {
            $parameterek['fa' . $i] = $karkod;
        }
        $q->setParameters($parameterek);

        $ret = [];
        foreach ($q->getScalarResult() as $sor) {
            $sor['szabadkeszlet'] = $sor['keszlet'] - $sor['foglalt'] - $sor['minkeszlet'];
            $ret[] = $sor;
        }
        return $ret;
    }

    public function createLista()
    {
        $lista = $this->getData();
        $report = $this->createView('rep_gyartoirendeles.tpl');
        $report->setVar('lista', $lista);
        $report->setVar('datumstr', $this->datumstr);
        $report->setVar('raktar', $this->raktarnev);
        $report->setVar('gyarto', $this->gyartonev);
        $report->setVar('termekfa', $this->fanevek);
        $report->setVar('szabadkeszletfelirat', \Services\KeszletService::getSzabadKeszletFelirat());
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
            ->setCellValue('F1', t('Foglalt'))
            ->setCellValue('G1', t('Szabad készlet'))
            ->setCellValue('H1', t('Érkezik'))
            ->setCellValue('I1', t('Opt. készlet'))
            ->setCellValue('J1', t('Rendelendő'));

        $sor = 2;
        foreach ($this->getData() as $item) {
            $excel->setActiveSheetIndex(0)
                ->setCellValue('A' . $sor, $item['cikkszam'])
                ->setCellValue('B' . $sor, $item['vonalkod'])
                ->setCellValue('C' . $sor, $item['termeknev'])
                ->setCellValue('D' . $sor, trim($item['ertek1'] . ' ' . $item['ertek2']))
                ->setCellValue('E' . $sor, (float)$item['keszlet'])
                ->setCellValue('F' . $sor, (float)$item['foglalt'])
                ->setCellValue('G' . $sor, (float)$item['szabadkeszlet'])
                ->setCellValue('H' . $sor, (float)$item['erkezik'])
                ->setCellValue('I' . $sor, (float)$item['optkeszlet'])
                ->setCellValue('J' . $sor, (float)$item['rendelendo']);
            $sor++;
        }

        $writer = IOFactory::createWriter($excel, 'Xlsx');
        $filename = uniqid('gyartoirendeles-' . \mkw\store::urlize($this->gyartonev ?: $this->raktarnev)) . '.xlsx';
        $filepath = \mkw\store::storagePath($filename);
        $writer->save($filepath);

        header('Cache-Control: private');
        header('Content-Type: application/stream');
        header('Content-Length: ' . filesize($filepath));
        header('Content-Disposition: attachment; filename=' . $filename);

        readfile($filepath);
        \unlink($filepath);
    }

    /**
     * A lista soraiból szállítói megrendelés. A mennyiségek itt is a getData()-ból jönnek, nem
     * a kliensről: a képernyőn látott lista azóta elavulhatott.
     */
    public function createBizonylat()
    {
        $lista = $this->getData();
        if (!$lista) {
            $this->jsonFail(t('Nincs megrendelendő tétel.'));
            return;
        }
        /** @var Partner $partner */
        $partner = $this->getRepo(Partner::class)->find($this->gyarto);
        if (!$partner) {
            $this->jsonFail(t('A szállítói megrendeléshez gyártót kell választani.'));
            return;
        }

        $em = $this->getEm();
        $conn = $em->getConnection();
        $conn->beginTransaction();
        try {
            $fej = $this->createFej($partner);
            foreach ($lista as $sor) {
                $tetel = new Bizonylattetel();
                $tetel->setBizonylatfej($fej);
                $tetel->setPersistentData();
                $tetel->setTermek($this->getRepo(Termek::class)->find($sor['termek_id']));
                $tetel->setTermekvaltozat(
                    $sor['termekvaltozat_id']
                        ? $this->getRepo(TermekValtozat::class)->find($sor['termekvaltozat_id'])
                        : null
                );
                $tetel->setMennyiseg($sor['rendelendo']);
                $tetel->fillEgysar();
                $tetel->calc();
                $em->persist($tetel);
            }
            $em->flush();
            $conn->commit();
        } catch (\Exception $e) {
            $conn->rollBack();
            $this->jsonFail(t('A szállítói megrendelés nem készült el: ') . $e->getMessage());
            return;
        }

        echo json_encode([
            'ok' => true,
            'bizonylatszam' => $fej->getId(),
            'tetel' => count($lista),
        ]);
    }

    private function createFej(Partner $partner)
    {
        $fej = new Bizonylatfej();
        $fej->setBizonylattipus($this->getRepo(Bizonylattipus::class)->find(Bizonylattipus::SZALLITOIMEGRENDELES));
        $fej->setPersistentData();
        $fej->setPartner($partner);
        $fej->setSzallitasimod($partner->getSzallitasimod());
        $fej->setKelt();
        $fej->setTeljesites();
        $fej->setEsedekesseg();
        if (!$fej->getValutanem()) {
            $fej->setValutanem($this->getRepo(Valutanem::class)->find(\mkw\store::getParameter(\mkw\consts::Valutanem)));
        }
        $arf = $this->getRepo(Arfolyam::class)->getActualArfolyam($fej->getValutanem(), $fej->getTeljesites());
        $fej->setArfolyam($arf->getArfolyam());
        $raktar = $this->getRepo(Raktar::class)->find($this->raktar ?: \mkw\store::getDefaultRaktarId());
        if ($raktar) {
            $fej->setRaktar($raktar);
        }
        $this->getEm()->persist($fej);

        return $fej;
    }

}
