<?php

namespace Controllers;

use Doctrine\ORM\Query\ResultSetMapping;
use Entities\Arsav;
use mkwhelpers\FilterDescriptor;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class leltarfejController extends \mkwhelpers\MattableController
{

    private $raktarnev;

    public function __construct($params)
    {
        $this->setEntityName('Entities\Leltarfej');
        $this->setKarbFormTplName('leltarfejkarbform.tpl');
        $this->setKarbTplName('leltarfejkarb.tpl');
        $this->setListBodyRowTplName('leltarfejlista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_leltarfej');
        parent::__construct($params);
    }

    public function viewExport()
    {
        $id = $this->params->getRequestParam('leltar', 0);
        $leltarfej = $this->getRepo()->findWithJoins($id);
        $view = $this->createView('leltarexport.tpl');

        $tac = new arsavController($this->params);
        $view->setVar('arsavlist', $tac->getSelectList());

        $view->setVar('leltarfej', $this->loadVars($leltarfej, true));
        $view->printTemplateResult();
    }

    public function viewImport()
    {
        $id = $this->params->getRequestParam('leltar', 0);
        $leltarfej = $this->getRepo()->findWithJoins($id);
        $view = $this->createView('leltarimport.tpl');
        $view->setVar('leltarfej', $this->loadVars($leltarfej, true));
        $view->printTemplateResult();
    }

    protected function loadVars($t, $forKarb = false)
    {
        $x = [];
        if (!$t) {
            $t = new \Entities\Leltarfej();
            $this->getEm()->detach($t);
        }
        $x['id'] = $t->getId();
        $x['nev'] = $t->getNev();
        $x['raktarnev'] = $t->getRaktarnev();
        $x['nyitasstr'] = $t->getNyitasStr();
        $x['zarasstr'] = $t->getZarasStr();
        $x['zarva'] = $t->getZarva();
        return $x;
    }

    /**
     * @param \Entities\Leltarfej $obj
     *
     * @return \Entities\Leltarfej
     */
    protected function setFields($obj, $parancs, $subject = 'minden')
    {
        $obj->setNev($this->params->getStringRequestParam('nev'));
        $obj->setNyitas();

        $r = \mkw\store::getEm()->getRepository('Entities\Raktar')->find($this->params->getIntRequestParam('raktar', 0));
        if ($r) {
            $obj->setRaktar($r);
        } else {
            $obj->removeRaktar();
        }

        return $obj;
    }

    public function getlistbody()
    {
        $view = $this->createView('leltarfejlista_tbody.tpl');

        $filter = new \mkwhelpers\FilterDescriptor();
        if (!is_null($this->params->getRequestParam('nevfilter', null))) {
            $fv = $this->params->getStringRequestParam('nevfilter');
            $filter->addFilter(['nev', 'keresztnev', 'vezeteknev', 'szallnev'], 'LIKE', '%' . $fv . '%');
        }
        if (!is_null($this->params->getRequestParam('raktarfilter', null))) {
            $filter->addFilter('raktar', '=', $this->params->getIntRequestParam('raktarfilter'));
        }

        $this->initPager($this->getRepo()->getCount($filter));

        $egyedek = $this->getRepo()->getWithJoins(
            $filter,
            $this->getOrderArray(),
            $this->getPager()->getOffset(),
            $this->getPager()->getElemPerPage()
        );

        echo json_encode($this->loadDataToView($egyedek, 'leltarfejlista', $view));
    }

    public function viewlist()
    {
        $view = $this->createView('leltarfejlista.tpl');

        $view->setVar('pagetitle', t('Leltárak'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $raktar = new raktarController($this->params);
        $view->setVar('raktarlist', $raktar->getSelectList(0));
        $view->printTemplateResult();
    }

    public function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Leltár'));
        $view->setVar('oper', $oper);

        $leltarfej = $this->getRepo()->findWithJoins($id);
        $raktar = new raktarController($this->params);
        $view->setVar('raktarlist', $raktar->getSelectList(($leltarfej ? $leltarfej->getRaktarId() : 0)));

        $view->setVar('leltarfej', $this->loadVars($leltarfej, true));
        $view->printTemplateResult();
    }

    public function getSelectList($selid = null, $filter = [])
    {
        $rec = $this->getRepo()->getAllForSelectList($filter, ['nev' => 'ASC']);
        $res = [];
        foreach ($rec as $sor) {
            $res[] = [
                'id' => $sor['id'],
                'caption' => $sor['nev'],
                'nev' => $sor['nev'],
                'selected' => ($sor['id'] == $selid)
            ];
        }
        return $res;
    }

    protected function createFilter()
    {
        $leltar = $this->params->getIntRequestParam('leltarid');
        if ($leltar) {
            $l = $this->getRepo('Entities\Leltarfej')->find($leltar);
            if ($l) {
                $raktar = $l->getRaktarId();
                $this->raktarnev = $l->getRaktarnev();
            }
        }

        $foglalas = $this->params->getBoolRequestParam('foglalasszamit');

        $filter = new FilterDescriptor();
        $filter
            ->addFilter('bf.rontott', '=', false);

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
            $res = \mkw\store::getEm()->getRepository('Entities\TermekFa')->getAll($ff, []);
            $faszuro = [];
            foreach ($res as $sor) {
                $faszuro[] = $sor->getKarkod() . '%';
            }
            if ($faszuro) {
                $filter->addFilter(['t.termekfa1karkod', 't.termekfa2karkod', 't.termekfa3karkod'], 'LIKE', $faszuro);
            }
        }

        return $filter;
    }

    protected function getData()
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('termek_id', 'termek_id');
        $rsm->addScalarResult('id', 'termekvaltozat_id');
        $rsm->addScalarResult('termeknev', 'termeknev');
        $rsm->addScalarResult('keszlet', 'keszlet');
        $rsm->addScalarResult('ertek1', 'ertek1');
        $rsm->addScalarResult('ertek2', 'ertek2');
        $rsm->addScalarResult('cikkszam', 'cikkszam');

        $filter = $this->createFilter();

        $termeknevmezo = 't.nev';
        $translationjoin = '';

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

        $q = $this->getEm()->createNativeQuery(
            'SELECT _xx.termek_id, _xx.id, ' . $termeknevmezo . ' AS termeknev, _xx.ertek1, _xx.ertek2, t.cikkszam,'
            . ' (SELECT SUM(bt.mennyiseg * bt.irany)'
            . ' FROM bizonylattetel bt'
            . ' LEFT JOIN bizonylatfej bf ON (bt.bizonylatfej_id=bf.id)'
            . $filter->getFilterString() . ' AND (_xx.id=bt.termekvaltozat_id) ) AS keszlet'
            . ' FROM termekvaltozat _xx'
            . ' LEFT JOIN termek t ON (_xx.termek_id=t.id)'
            . $translationjoin
            . $termekfilter->getFilterString('_xx', 'r')
            . $keszlettipus
            . ' ORDER BY t.cikkszam, ' . $termeknevmezo . ', _xx.ertek1, _xx.ertek2',
            $rsm
        );

        $q->setParameters(array_merge_recursive($filter->getQueryParameters('p'), $termekfilter->getQueryParameters('r')));
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
        
        $as = explode('_', $this->params->getStringRequestParam('arsav'));
        $arsav = $as[0];
        $arsavobj = $this->getRepo(Arsav::class)->findOneBy(['nev' => $arsav]);
        $valutanem = $as[1];
        $ret = [];
        foreach ($d as $sor) {
            if ($as) {
                /** @var \Entities\Termek $t */
                $t = $this->getRepo('Entities\Termek')->find($sor['termek_id']);
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
            $ret[] = $sor;
        }
        return $ret;
    }

    public function export()
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
            ->setCellValue('A1', t('Termék ID'))
            ->setCellValue('B1', t('Változat ID'))
            ->setCellValue('C1', t('Cikkszám'))
            ->setCellValue('D1', t('Termék'))
            ->setCellValue('E1', t('Változat'))
            ->setCellValue('F1', t('Ár'))
            ->setCellValue('G1', t('Készlet'))
            ->setCellValue('H1', t('Tény készlet'));

        $mind = $this->getData();

        $sor = 2;
        foreach ($mind as $item) {
            $excel->setActiveSheetIndex(0)
                ->setCellValue('A' . $sor, $item['termek_id'])
                ->setCellValue('B' . $sor, $item['termekvaltozat_id'])
                ->setCellValue('C' . $sor, $item['cikkszam'])
                ->setCellValue('D' . $sor, $item['termeknev'])
                ->setCellValue('E' . $sor, $item['ertek1'] . ' ' . $item['ertek2'])
                ->setCellValue('F' . $sor, $item['ar'])
                ->setCellValue('G' . $sor, $item['keszlet'])
                ->setCellValue('H' . $sor, $item['keszlet']);
            $sor++;
        }

        $writer = IOFactory::createWriter($excel, 'Xlsx');

        $filepath = \mkw\store::storagePath(uniqid('leltar-' . \mkw\store::urlize($this->raktarnev)) . '.xlsx');
        $writer->save($filepath);

        $fileSize = filesize($filepath);

        // Output headers.
        header('Cache-Control: private');
        header('Content-Type: application/stream');
        header('Content-Length: ' . $fileSize);
        header('Content-Disposition: attachment; filename=' . $filepath);

        readfile($filepath);

        \unlink($filepath);
    }

    public function import()
    {
        $leltar = $this->params->getIntRequestParam('leltarid');
        if ($leltar) {
            $l = $this->getRepo('Entities\Leltarfej')->find($leltar);
            if ($l) {
                $filenev = \mkw\store::storagePath($_FILES['toimport']['name']);
                move_uploaded_file($_FILES['toimport']['tmp_name'], $filenev);

                $filetype = IOFactory::identify($filenev);
                $reader = IOFactory::createReader($filetype);
                $reader->setReadDataOnly(true);
                $excel = $reader->load($filenev);
                $sheet = $excel->getActiveSheet();
                $maxrow = (int)$sheet->getHighestRow();
                $db = 0;

                for ($row = 0; $row <= $maxrow; ++$row) {
                    $termekid = $sheet->getCell('A' . $row)->getValue();
                    $valtozatid = $sheet->getCell('B' . $row)->getValue();
                    $termek = \mkw\store::getEm()->getRepository('Entities\Termek')->find($termekid);
                    if ($termek) {
                        if ($valtozatid) {
                            $valtozat = \mkw\store::getEm()->getRepository('Entities\TermekValtozat')->find($valtozatid);
                            if ($valtozat) {
                                $tetel = $this->getEm()->getRepository('Entities\Leltartetel')->findOneBy(
                                    ['leltarfej' => $l, 'termek' => $termek, 'termekvaltozat' => $valtozat]
                                );
                                if (!$tetel) {
                                    $tetel = new \Entities\Leltartetel();
                                    $tetel->setLeltarfej($l);
                                    $tetel->setTermek($termek);
                                    $tetel->setTermekvaltozat($valtozat);
                                }
                                $tetel->setGepimennyiseg((float)$sheet->getCell('G' . $row)->getValue());
                                $tetel->setTenymennyiseg((float)$sheet->getCell('H' . $row)->getValue());
                                $this->getEm()->persist($tetel);
                                $db++;
                            }
                        } else {
                            $tetel = $this->getEm()->getRepository('Entities\Leltartetel')->findOneBy(
                                ['leltarfej' => $l, 'termek' => $termek, 'termekvaltozat' => null]
                            );
                            if (!$tetel) {
                                $tetel = new \Entities\Leltartetel();
                                $tetel->setLeltarfej($l);
                                $tetel->setTermek($termek);
                            }
                            $tetel->setGepimennyiseg((float)$sheet->getCell('G' . $row)->getValue());
                            $tetel->setTenymennyiseg((float)$sheet->getCell('H' . $row)->getValue());
                            $this->getEm()->persist($tetel);
                            $db++;
                        }
                    }
                    if (($db % 20) === 0) {
                        $this->getEm()->flush();
                        $this->getEm()->clear();
                        $l = $this->getRepo('Entities\Leltarfej')->find($leltar);
                    }
                }
                $this->getEm()->flush();
                $this->getEm()->clear();
            }
        }
    }

    public function zar()
    {
        $hianybt = $this->getRepo('Entities\Bizonylattipus')->find('leltarhiany');
        $tobbletbt = $this->getRepo('Entities\Bizonylattipus')->find('leltartobblet');

        if ($hianybt && $tobbletbt) {
            $hianyok = [];
            $tobbletek = [];

            $leltarid = $this->params->getIntRequestParam('leltarid');
            $zarasstr = $this->params->getStringRequestParam('datum');

            /** @var \Entities\Leltarfej $leltar */
            $leltar = $this->getRepo()->find($leltarid);
            if ($leltar) {
                $partner = $this->getRepo('Entities\Partner')->find(\mkw\store::getParameter(\mkw\consts::Tulajpartner));
                $raktarid = $leltar->getRaktarId();
                $filter = new FilterDescriptor();
                $filter->addFilter('leltarfej', '=', $leltarid);
                $leltartetelek = $this->getRepo('Entities\Leltartetel')->getWithJoins($filter);
                /** @var \Entities\Leltartetel $tetel */
                foreach ($leltartetelek as $tetel) {
                    $valtozat = $tetel->getTermekvaltozat();
                    $keszlet = $valtozat->getKeszlet($zarasstr, $raktarid);
                    if ($keszlet < $tetel->getTenymennyiseg()) {
                        $tobbletek[] = [
                            'termek' => $tetel->getTermek(),
                            'valtozat' => $tetel->getTermekvaltozat(),
                            'keszlet' => $keszlet,
                            'teny' => $tetel->getTenymennyiseg()
                        ];
                    } elseif ($keszlet > $tetel->getTenymennyiseg()) {
                        $hianyok[] = [
                            'termek' => $tetel->getTermek(),
                            'valtozat' => $tetel->getTermekvaltozat(),
                            'keszlet' => $keszlet,
                            'teny' => $tetel->getTenymennyiseg()
                        ];
                    }
                }

                if ($hianyok) {
                    $fej = new \Entities\Bizonylatfej();
                    $fej->setPersistentData();
                    $fej->setBizonylattipus($hianybt);
                    $fej->setKelt('');
                    $fej->setTeljesites($zarasstr);
                    $fej->setEsedekesseg('');
                    $fej->setArfolyam(1);
                    $fej->setPartner($partner);
                    $valutanemid = \mkw\store::getParameter(\mkw\consts::Valutanem);
                    $valutanem = $this->getRepo('Entities\Valutanem')->find($valutanemid);
                    $fej->setValutanem($valutanem);
                    $fej->setBankszamla($valutanem->getBankszamla());
                    $fej->setRaktar($this->getRepo('Entities\Raktar')->find($raktarid));

                    foreach ($hianyok as $hiany) {
                        $t = new \Entities\Bizonylattetel();
                        $t->setBizonylatfej($fej);
                        $t->setPersistentData();
                        $t->setTermek($hiany['termek']);
                        $t->setTermekvaltozat($hiany['valtozat']);
                        $t->setMennyiseg(abs($hiany['keszlet'] - $hiany['teny']));
                        $t->fillEgysar();
                        $t->calc();
                        $this->getEm()->persist($t);
                    }
                    $this->getEm()->persist($fej);
                    $this->getEm()->flush();
                }
                if ($tobbletek) {
                    $fej = new \Entities\Bizonylatfej();
                    $fej->setPersistentData();
                    $fej->setBizonylattipus($tobbletbt);
                    $fej->setKelt('');
                    $fej->setTeljesites($zarasstr);
                    $fej->setEsedekesseg('');
                    $fej->setArfolyam(1);
                    $fej->setPartner($partner);
                    $valutanemid = \mkw\store::getParameter(\mkw\consts::Valutanem);
                    $valutanem = $this->getRepo('Entities\Valutanem')->find($valutanemid);
                    $fej->setValutanem($valutanem);
                    $fej->setBankszamla($valutanem->getBankszamla());
                    $fej->setRaktar($this->getRepo('Entities\Raktar')->find($raktarid));

                    foreach ($tobbletek as $tobblet) {
                        $t = new \Entities\Bizonylattetel();
                        $t->setBizonylatfej($fej);
                        $t->setPersistentData();
                        $t->setTermek($tobblet['termek']);
                        $t->setTermekvaltozat($tobblet['valtozat']);
                        $t->setMennyiseg(abs($tobblet['keszlet'] - $tobblet['teny']));
                        $t->fillEgysar();
                        $t->calc();
                        $this->getEm()->persist($t);
                    }
                    $this->getEm()->persist($fej);
                    $this->getEm()->flush();
                }
                $leltar->setZaras($zarasstr);
                $leltar->setZarva(true);
                $this->getEm()->persist($leltar);
                $this->getEm()->flush();
            }
        }
    }


}