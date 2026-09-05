<?php

namespace Controllers;

use Doctrine\ORM\Query\ResultSetMapping;
use Entities\Arsav;
use Entities\Raktar;
use Entities\Termek;
use Entities\TermekAr;
use Entities\TermekFa;
use Entities\Valutanem;
use mkwhelpers\FilterDescriptor;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class keszletlistaController extends \mkwhelpers\MattableController
{

    /**
     * A sor készlete a két előre aggregált halmazból: változatos terméknél a változat összege,
     * változat nélkülinél a termék teljes összege. A lista a termékből indul (LEFT JOIN a
     * változatokra), különben a változat nélküli termékek ki sem kerülnének rá.
     */
    private const KESZLETKIFEJEZES = '(CASE WHEN _xx.id IS NULL THEN kt.keszlet ELSE kv.keszlet END)';

    private $datumstr;
    private $raktar;
    private $raktarnev;
    private $nevfilter;
    private $foglalasstr;
    private $minkeszletstr;
    private $arsavstr;
    private $nettobruttostr;

    public function view()
    {
        $view = $this->createView('keszletlista.tpl');

        $view->setVar('datum', date(\mkw\store::$DateFormat));

        $rc = new raktarController();
        $view->setVar('raktarlist', $rc->getSelectList());

        $view->setVar('nyelvlist', \mkw\store::getLocaleSelectList());

        $tac = new termekarController();
        $tacok = $tac->getSelectList();
        $tacok[] = [
            'id' => '---utolsobeszar',
            'caption' => 'Utolsó besz.ár',
            'selected' => false,
            'valutanemid' => 1,
            'valutanem' => 'HUF'
        ];
        if (\mkw\store::isFifo()) {
            $tacok[] = [
                'id' => '---fifo',
                'caption' => 'FIFO érték',
                'selected' => false,
                'valutanemid' => 1,
                'valutanem' => 'HUF'
            ];
        }
        $view->setVar('arsavlist', $tacok);

        $view->printTemplateResult();
    }

    protected function createFilter()
    {
        $this->raktarnev = t('Minden raktár');
        $raktar = $this->params->getIntRequestParam('raktar');
        $this->raktar = $raktar;
        if ($raktar) {
            $r = $this->getRepo(Raktar::class)->find($raktar);
            if ($r) {
                $this->raktarnev = $r->getNev();
            }
        }

        $this->datumstr = $this->params->getStringRequestParam('datum');
        $this->datumstr = date(\mkw\store::$DateFormat, strtotime(\mkw\store::convDate($this->datumstr)));

        $foglalas = $this->params->getBoolRequestParam('foglalasszamit');
        if ($foglalas) {
            $this->foglalasstr = \mkw\store::translate('Foglalás számít');
        }

        $filter = new FilterDescriptor();
        $filter
            ->addFilter('bf.rontott', '=', false)
            ->addFilter('bf.teljesites', '<=', $this->datumstr);

        if ($foglalas) {
            $filter->addSql('((bt.mozgat=1) OR (bt.foglal=1))');
        } else {
            $filter->addFilter('bt.mozgat', '=', true);
        }
        if ($raktar) {
            $filter->addFilter('bf.raktar_id', '=', $raktar);
        }

        return $filter;
    }

    protected function createTermekFilter()
    {
        $filter = new FilterDescriptor();
        $fv = $this->params->getArrayRequestParam('fafilter');
        if (!empty($fv)) {
            $ff = new FilterDescriptor();
            $ff->addFilter('id', 'IN', $fv);
            $res = \mkw\store::getEm()->getRepository(TermekFa::class)->getAll($ff, []);
            $faszuro = [];
            foreach ($res as $sor) {
                $faszuro[] = $sor->getKarkod() . '%';
            }
            if ($faszuro) {
                $filter->addFilter(['t.termekfa1karkod', 't.termekfa2karkod', 't.termekfa3karkod'], 'LIKE', $faszuro);
            }
        }
        $this->nevfilter = $this->params->getRequestParam('nevfilter', null);
        if (!is_null($this->nevfilter)) {
            $filter->addFilter(['t.nev', 't.rovidleiras', 't.cikkszam', 't.vonalkod'], 'LIKE', '%' . $this->nevfilter . '%');
        }

        return $filter;
    }

    /**
     * A készletösszegek CTE-je. Korábban a lista minden sora (termék × változat, mugenrace-en
     * 25 ezer sor) külön korrelált alkérdést futtatott a bizonylattétel táblán, és a CASE-es
     * kapcsolat miatt indexet sem tudott használni – szűrő nélkül percekig futott. Most egy
     * menetben, csoportosítva áll elő minden összeg, és a lista már csak joinol.
     *
     * A `keszletvaltozat` a változatonkénti, a `keszlettermek` a termékenkénti összeg. A
     * szűrőparaméterek egyszer szerepelnek az SQL-ben, ezért CTE és nem kétszer beszúrt
     * származtatott tábla.
     */
    private function getKeszletCte(FilterDescriptor $filter)
    {
        return 'WITH keszletvaltozat AS ('
            . ' SELECT bt.termek_id AS tid, bt.termekvaltozat_id AS vid,'
            . ' SUM(bt.mennyiseg * bt.irany) AS keszlet'
            . ' FROM bizonylattetel bt'
            . ' LEFT JOIN bizonylatfej bf ON (bt.bizonylatfej_id=bf.id)'
            . $filter->getFilterString('_xx', 'p')
            . ' GROUP BY bt.termek_id, bt.termekvaltozat_id),'
            . ' keszlettermek AS ('
            . ' SELECT tid, SUM(keszlet) AS keszlet FROM keszletvaltozat GROUP BY tid)';
    }

    protected function getData()
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('termek_id', 'termek_id');
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('termeknev', 'termeknev');
        $rsm->addScalarResult('keszlet', 'keszlet');
        $rsm->addScalarResult('ertek1', 'ertek1');
        $rsm->addScalarResult('ertek2', 'ertek2');
        $rsm->addScalarResult('cikkszam', 'cikkszam');

        $filter = $this->createFilter();

        $locale = \mkw\store::translateToLongLocaleName($this->params->getStringRequestParam('nyelv', \mkw\store::getAdminDataLocale()));

        $keszlettipus = '';

        switch ($this->params->getIntRequestParam('keszlet')) {
            case 1:
                $keszlettipus = '';
                break;
            case 2:
                $keszlettipus = ' HAVING keszlet>0';
                break;
            case 3:
                $keszlettipus = ' HAVING (keszlet<=0) OR (keszlet IS NULL)';
                break;
            case 4:
                $keszlettipus = ' HAVING keszlet<0';
                break;
        }

        $termekfilter = $this->createTermekFilter();

        $mkparams = [];
        $minkeszletszamit = $this->params->getBoolRequestParam('minkeszletszamit');
        if ($minkeszletszamit) {
            $this->minkeszletstr = 'Min.készlet számít';
            $minexpr = \Services\KeszletService::getMinKeszletSql(
                '_xx.termek_id',
                't.minkeszlet',
                '_xx.id',
                '_xx.minkeszlet',
                $this->raktar ? 'mkraktar' : ''
            );
            if ($this->raktar) {
                // csak ebben az ágban köthető: a createNativeQuery hibát dob olyan paraméterre,
                // ami nincs benne az SQL-ben
                $mkparams = ['mkraktar' => $this->raktar];
            }
            // a minimum a külső SELECT-ben korrelál a külső _xx / t aliasra, ezért tűnt el
            // az aggregáló alkérdésből az azt árnyékoló `LEFT JOIN termek t`
            $keszletsql = ' (' . self::KESZLETKIFEJEZES . ' - ' . $minexpr . ') AS keszlet';
        } else {
            $this->minkeszletstr = '';
            $keszletsql = ' ' . self::KESZLETKIFEJEZES . ' AS keszlet';
        }

        $q = $this->getEm()->createNativeQuery(
            $this->getKeszletCte($filter)
            . ' SELECT t.id AS termek_id, _xx.id, ' . \mkw\store::getLocalizedFieldName('t.nev', $locale) . ' AS termeknev, _xx.ertek1, _xx.ertek2, t.cikkszam,'
            . $keszletsql
            . ' FROM termek t'
            . ' LEFT JOIN termekvaltozat _xx ON (_xx.termek_id=t.id)'
            . ' LEFT JOIN keszletvaltozat kv ON (kv.vid=_xx.id)'
            . ' LEFT JOIN keszlettermek kt ON (kt.tid=t.id)'
            . $termekfilter->getFilterString('_xx', 'r')
            . $keszlettipus
            . ' ORDER BY t.cikkszam, ' . \mkw\store::getLocalizedFieldName('t.nev', $locale) . ', _xx.ertek1, _xx.ertek2',
            $rsm
        );

        $q->setParameters(
            array_merge_recursive(
                $filter->getQueryParameters('p'),
                $termekfilter->getQueryParameters('r'),
                $mkparams
            )
        );
        $d = $q->getScalarResult();

        $nettobrutto = $this->params->getStringRequestParam('nettobrutto');
        switch ($nettobrutto) {
            case 'netto':
                $this->nettobruttostr = t('Nettó');
                break;
            case 'brutto':
                $this->nettobruttostr = t('Bruttó');
                break;
            default:
                break;
        }

        // "mindegy" ársávnál a mező üres, ilyenkor valutanem sincs mögötte
        $as = explode('_', $this->params->getStringRequestParam('arsav'));
        $arsav = $as[0];
        $arsavobj = $this->getRepo(Arsav::class)->findOneBy(['nev' => $arsav]);
        $valutanem = $as[1] ?? null;
        $valutaobj = $this->getRepo(Valutanem::class)->find($valutanem);
        $this->arsavstr = ($arsav === '---fifo') ? t('FIFO érték') : $arsav;
        if ($valutaobj && ($arsav !== '---fifo')) {
            $this->arsavstr .= ' ' . $valutaobj->getNev();
        }
        // Utolsó beszár esetén egyetlen kötegelt lekérdezéssel számoljuk ki az összes
        // változat árát (a korábbi soronkénti getNettoUtolsoBeszar/getBruttoUtolsoBeszar
        // hívások N+1 problémáját elkerülve).
        $beszarmap = [];
        if ($arsav === '---utolsobeszar' && ($nettobrutto === 'netto' || $nettobrutto === 'brutto')) {
            $beszarmap = $this->getRepo(Termek::class)->getUtolsoBeszarByTermek(
                array_column($d, 'termek_id'),
                $this->datumstr,
                true,
                $nettobrutto === 'brutto'
            );
        }

        // Ársávos ár esetén (ársávos deployment) szintén egyetlen kötegelt lekérdezéssel
        // töltjük be az összes érintett termék árát (a getNettoAr/getBruttoAr -> getArsavAr
        // soronkénti N+1 hívása helyett). Ársávnál az ár termékenként (nem változatonként) jön.
        $fifomap = [];
        if ($arsav === '---fifo') {
            $fifomap = $this->getFifoMap($d);
        }

        $arsavarmap = [];
        if ($arsav !== '---utolsobeszar' && $arsav !== '---fifo' && \mkw\store::isArsavok()
            && ($nettobrutto === 'netto' || $nettobrutto === 'brutto')) {
            $arsavarmap = $this->getRepo(TermekAr::class)->getArsavArByTermek(
                array_column($d, 'termek_id'),
                $valutanem,
                $arsavobj
            );
        }

        $ret = [];
        foreach ($d as $sor) {
            if ($arsav === '---fifo') {
                $fifo = $fifomap[$this->rowKey($sor)] ?? null;
                $sor['ar'] = $fifo ? $fifo['egysegertek'] : 0;
                // az összeg a tárolt értékből képződik, nem ár * készlet szorzatból
                $sor['ertek'] = $fifo ? $fifo['ertek'] : 0;
                $sor['becsult'] = $fifo ? $fifo['becsult'] : false;
                $sor['bizid'] = '';
            } elseif ($arsav === '---utolsobeszar') {
                $beszar = $beszarmap[$this->rowKey($sor)] ?? null;
                $sor['ar'] = $beszar ? $beszar['ertek'] : 0;
                $sor['bizid'] = $beszar ? $beszar['id'] : null;
            } else {
                $sor['bizid'] = '';
                if (\mkw\store::isArsavok()) {
                    $ta = isset($arsavarmap[$sor['termek_id']]) ? $arsavarmap[$sor['termek_id']] : null;
                    switch ($nettobrutto) {
                        case 'netto':
                            $sor['ar'] = $ta ? $ta->getNetto() : 0;
                            break;
                        case 'brutto':
                            $sor['ar'] = $ta ? $ta->getBrutto() : 0;
                            break;
                        default:
                            $sor['ar'] = 0;
                            break;
                    }
                } else {
                    /** @var \Entities\Termek $t */
                    $t = $this->getRepo(Termek::class)->find($sor['termek_id']);
                    if ($t) {
                        switch ($nettobrutto) {
                            case 'netto':
                                $sor['ar'] = $t->getNettoAr($sor['id'], null, $valutanem, $arsavobj);
                                break;
                            case 'brutto':
                                $sor['ar'] = $t->getBruttoAr($sor['id'], null, $valutanem, $arsavobj);
                                break;
                            default:
                                $sor['ar'] = 0;
                                break;
                        }
                    }
                }
            }
            $ret[] = $sor;
        }
        return $ret;
    }

    /** A riport sorának kulcsa az árakat adó térképekhez: termék + változat (változat nélkül 0). */
    private function rowKey(array $sor)
    {
        return (int)$sor['termek_id'] . '|' . (int)$sor['id'];
    }

    /**
     * A riport sorainak FIFO értéke. A mai napra a tárolt `fifoertek`-ből, múltbeli dátumra
     * menet közben számolva – utóbbi csak a riporton szereplő termékekre fut.
     *
     * A raktárak összevonása (céges készlet) itt is helyes: az érték additív, az egységérték
     * a súlyozott átlag.
     */
    private function getFifoMap(array $d)
    {
        $termekids = array_values(array_unique(array_filter(array_column($d, 'termek_id'))));
        if (!$termekids) {
            return [];
        }

        $mai = strtotime(\mkw\store::convDate(date(\mkw\store::$DateFormat)));
        $kert = strtotime(\mkw\store::convDate($this->datumstr));
        $osszeg = [];

        if ($kert < $mai) {
            $this->arsavstr .= ' (' . t('menet közben számolva') . ')';
            $eredmeny = (new \Services\FifoService())->calculateAsOf(
                date('Y-m-d', $kert),
                $this->raktar ?: null,
                $termekids
            );
            foreach ($eredmeny as $sor) {
                if ($sor['mennyiseg'] <= 0) {
                    continue;
                }
                $this->addFifoOsszeg($osszeg, $sor['termekid'], $sor['valtozatid'], $sor['mennyiseg'], $sor['ertek'], $sor['becsult']);
            }
        } else {
            $params = [];
            $where = ['termek_id IN (' . implode(',', array_map('intval', $termekids)) . ')', 'mennyiseg > 0'];
            if ($this->raktar) {
                $where[] = 'raktar_id = :raktar';
                $params['raktar'] = $this->raktar;
            }
            $sorok = $this->getEm()->getConnection()->fetchAllAssociative(
                'SELECT termek_id, termekvaltozat_id, SUM(mennyiseg) AS mennyiseg, SUM(ertek) AS ertek,'
                . ' MAX(becsult) AS becsult FROM fifoertek WHERE ' . implode(' AND ', $where)
                . ' GROUP BY termek_id, termekvaltozat_id',
                $params
            );
            foreach ($sorok as $sor) {
                $this->addFifoOsszeg($osszeg, $sor['termek_id'], $sor['termekvaltozat_id'], $sor['mennyiseg'], $sor['ertek'], $sor['becsult']);
            }
        }

        $map = [];
        foreach ($osszeg as $kulcs => $sor) {
            $map[$kulcs] = [
                'ertek' => round($sor['ertek'], 2),
                'egysegertek' => $sor['mennyiseg'] > 0 ? round($sor['ertek'] / $sor['mennyiseg'], 4) : 0,
                'becsult' => (bool)$sor['becsult'],
            ];
        }
        return $map;
    }

    private function addFifoOsszeg(array &$osszeg, $termekid, $valtozatid, $mennyiseg, $ertek, $becsult)
    {
        $kulcs = (int)$termekid . '|' . (int)$valtozatid;
        if (!isset($osszeg[$kulcs])) {
            $osszeg[$kulcs] = ['mennyiseg' => 0, 'ertek' => 0, 'becsult' => false];
        }
        $osszeg[$kulcs]['mennyiseg'] += (float)$mennyiseg;
        $osszeg[$kulcs]['ertek'] += (float)$ertek;
        $osszeg[$kulcs]['becsult'] = $osszeg[$kulcs]['becsult'] || (bool)$becsult;
    }

    public function createLista()
    {
        $report = $this->createView('rep_keszlet.tpl');
        $report->setVar('lista', $this->getData());
        $report->setVar('datumstr', $this->datumstr);
        $report->setVar('raktar', $this->raktarnev);
        $report->setVar('nevfilter', $this->nevfilter);
        $report->setVar('foglalasstr', $this->foglalasstr);
        $report->setVar('minkeszletstr', $this->minkeszletstr);
        $report->setVar('arsav', $this->arsavstr . ' ' . $this->nettobruttostr);
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
            ->setCellValue('A1', t('Cikkszám'))
            ->setCellValue('B1', t('Termék'))
            ->setCellValue('C1', t('Változat 1'))
            ->setCellValue('D1', t('Változat 2'))
            ->setCellValue('E1', t('Készlet'))
            ->setCellValue('F1', t('Ár'))
            ->setCellValue('G1', t('Érték'))
            ->setCellValue('H1', t('Bizonylat'));

        $mind = $this->getData();

        $sor = 2;
        foreach ($mind as $item) {
            $excel->setActiveSheetIndex(0)
                ->setCellValue('A' . $sor, $item['cikkszam'])
                ->setCellValue('B' . $sor, $item['termeknev'])
                ->setCellValue('C' . $sor, $item['ertek1'])
                ->setCellValue('D' . $sor, $item['ertek2'])
                ->setCellValue('E' . $sor, $item['keszlet'])
                ->setCellValue('F' . $sor, $item['ar'])
                ->setCellValue('G' . $sor, $item['ertek'] ?? ($item['ar'] * $item['keszlet']))
                ->setCellValue('H' . $sor, $item['bizid']);
            $sor++;
        }

        $writer = IOFactory::createWriter($excel, 'Xlsx');

        $filename = uniqid('keszlet') . '.xlsx';
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
}