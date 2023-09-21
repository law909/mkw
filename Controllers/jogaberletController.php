<?php

namespace Controllers;

use Entities\Dolgozo;
use Entities\JogaBerlet;
use Entities\JogaReszvetel;
use Entities\Partner;
use Entities\Termek;
use Entities\TermekValtozat;
use Entities\Valutanem;
use mkw\store;
use mkwhelpers\FilterDescriptor;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class jogaberletController extends \mkwhelpers\MattableController
{

    public function __construct($params)
    {
        $this->setEntityName(JogaBerlet::class);
        $this->setKarbFormTplName('jogaberletkarbform.tpl');
        $this->setKarbTplName('jogaberletkarb.tpl');
        $this->setListBodyRowTplName('jogaberletlista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct($params);
    }

    protected function loadVars($t)
    {
        $uj = false;
        $x = [];
        if (!$t) {
            $uj = true;
            $t = new \Entities\JogaBerlet();
            $this->getEm()->detach($t);
        }
        $x['id'] = $t->getId();
        $x['termeknev'] = $t->getTermeknev();
        $x['partner'] = $t->getPartnerId();
        $x['partnernev'] = $t->getPartnernev();
        $x['partneremail'] = $t->getPartneremail();
        $x['vasarlasnapja'] = $t->getVasarlasnapjaStr();
        $x['lejaratdatum'] = $t->getLejaratdatumStr();
        $x['lejart'] = $t->isLejart();
        $x['elfogyottalkalom'] = $t->getElfogyottalkalom();
        $x['offlineelfogyottalkalom'] = $t->getOfflineelfogyottalkalom();
        $x['nincsfizetve'] = $t->isNincsfizetve();
        $x['bruttoar'] = $t->getBruttoegysar();
        $x['lastmodstr'] = $t->getLastmodStr();
        $x['createdstr'] = $t->getCreatedStr();
        $x['updatedby'] = $t->getUpdatedbyNev();
        $x['createdby'] = $t->getCreatedbyNev();
        if (!$uj) {
            $filter = new \mkwhelpers\FilterDescriptor();
            $filter->addFilter('jogaberlet', '=', $t);
            $latogatasok = $this->getRepo(JogaReszvetel::class)->getWithJoins($filter, ['datum' => 'ASC']);
            $l = [];
            /** @var JogaReszvetel $latog */
            foreach ($latogatasok as $latog) {
                $l[] = [
                    'datum' => $latog->getDatumStr(),
                    'nap' => $latog->getDatumNapnev(),
                    'tanar' => $latog->getTanarnev(),
                    'oratipus' => $latog->getJogaoratipusNev()
                ];
            }
            $x['latogatasok'] = $l;
        }
        return $x;
    }

    /** @param \Entities\JogaBerlet $obj */
    protected function setFields($obj)
    {
        $ck = \mkw\store::getEm()->getRepository(Termek::class)->find($this->params->getIntRequestParam('termek'));
        if ($ck) {
            $obj->setTermek($ck);
        } else {
            $obj->removeTermek();
        }
        $ck = \mkw\store::getEm()->getRepository(Partner::class)->find($this->params->getIntRequestParam('partner'));
        if ($ck) {
            $obj->setPartner($ck);
        } else {
            $obj->removePartner();
        }
        $obj->setVasarlasnapja($this->params->getStringRequestParam('vasarlasnapja'));
        $obj->setLejaratdatum($this->params->getStringRequestParam('lejaratdatum'));
        $obj->setElfogyottalkalom($this->params->getIntRequestParam('elfogyottalkalom'));
        $obj->setOfflineelfogyottalkalom($this->params->getIntRequestParam('offlineelfogyottalkalom'));
        $obj->setNincsfizetve($this->params->getBoolRequestParam('nincsfizetve', false));
        $obj->setBruttoegysar($this->params->getNumRequestParam('bruttoar'));
        $oldlejart = $obj->isLejart();
        $obj->setLejart($this->params->getBoolRequestParam('lejart'));
        if (!$oldlejart && $oldlejart !== $obj->isLejart()) {
            $obj->sendEmail(\mkw\store::getParameter(\mkw\consts::JogaBerletDatumLejartSablon));
        }
        return $obj;
    }

    protected function afterSave($o, $parancs = null)
    {
        \mkw\store::writelog('jogaberlet aftersave: ' . $parancs);
        if ($parancs === $this->addOperation) {
            $o->sendEmail(\mkw\store::getParameter(\mkw\consts::JogaBerletKoszonoSablon));
        }
    }

    public function getlistbody()
    {
        $view = $this->createView('jogaberletlista_tbody.tpl');

        $filter = new \mkwhelpers\FilterDescriptor();
        if (!is_null($this->params->getRequestParam('vevonevfilter', null))) {
            $filter->addFilter('p.nev', 'LIKE', '%' . $this->params->getStringRequestParam('vevonevfilter') . '%');
        }
        if (!is_null($this->params->getIntRequestParam('termekfilter', null))) {
            $filter->addFilter('_xx.termek', '=', $this->params->getIntRequestParam('termekfilter', null));
        }
        $tol = $this->params->getStringRequestParam('datumtolfilter');
        $ig = $this->params->getStringRequestParam('datumigfilter');
        if ($tol) {
            $filter->addFilter('vasarlasnapja', '>=', $tol);
        }
        if ($ig) {
            $filter->addFilter('vasarlasnapja', '<=', $ig);
        }
        $tol = $this->params->getStringRequestParam('lejarattolfilter');
        $ig = $this->params->getStringRequestParam('lejaratigfilter');
        if ($tol) {
            $filter->addFilter('lejaratdatum', '>=', $tol);
        }
        if ($ig) {
            $filter->addFilter('lejaratdatum', '<=', $ig);
        }
        $f = $this->params->getIntRequestParam('lejartfilter');
        switch ($f) {
            case 1:
                $filter->addFilter('lejart', '=', false);
                break;
            case 2:
                $filter->addFilter('lejart', '=', true);
                break;
        }
        $f = $this->params->getIntRequestParam('nincsfizetvefilter');
        switch ($f) {
            case 1:
                $filter->addFilter('nincsfizetve', '=', false);
                break;
            case 2:
                $filter->addFilter('nincsfizetve', '=', true);
                break;
        }
        $f = $this->params->getStringRequestParam('utolsohasznalatfilter');
        if ($f) {
            $uhdatum = \mkw\store::convDate($f);
            $filter->addSql('(SELECT COUNT(jr) FROM Entities\JogaReszvetel jr WHERE (p.id=jr.partner) AND (jr.datum>=\'' . $uhdatum . '\')) = 0');
        }
        $this->initPager(
            $this->getRepo()->getCountWithJoins($filter),
            $this->params->getIntRequestParam('elemperpage', 30),
            $this->params->getIntRequestParam('pageno', 1)
        );

        $egyedek = $this->getRepo()->getWithJoins(
            $filter,
            $this->getOrderArray(),
            $this->getPager()->getOffset(),
            $this->getPager()->getElemPerPage()
        );

        echo json_encode($this->loadDataToView($egyedek, 'egyedlista', $view));
    }

    public function viewselect()
    {
        $view = $this->createView('jogaberletlista.tpl');

        $view->setVar('pagetitle', t('Jóga bérlet'));
        $view->setVar('controllerscript', 'jogaberletlista.js');
        $view->printTemplateResult();
    }

    public function viewlist()
    {
        $view = $this->createView('jogaberletlista.tpl');

        $view->setVar('pagetitle', t('Jóga bérlet'));
        $view->setVar('controllerscript', 'jogaberletlista.js');
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $termek = new termekController($this->params);
        $view->setVar('termeklist', $termek->getEladhatoSelectList());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Jóga bérlet'));
        $view->setVar('controllerscript', 'jogaberletkarb.js');
        $view->setVar('formaction', '/admin/jogaberlet/save');
        $view->setVar('oper', $oper);
        /** @var \Entities\JogaBerlet $record */
        $record = $this->getRepo()->findWithJoins($id);
        if (!\mkw\store::isPartnerAutocomplete()) {
            $partner = new partnerController($this->params);
            $view->setVar('partnerlist', $partner->getSelectList(($record ? $record->getPartnerId() : 0)));
        }
        if (!\mkw\store::isTermekAutocomplete()) {
            $termek = new termekController($this->params);
            $view->setVar('termeklist', $termek->getEladhatoSelectList(($record ? $record->getTermekId() : 0)));
        }
        $view->setVar('egyed', $this->loadVars($record));
        return $view->getTemplateResult();
    }

    public function getSelectList($selid = null, $partnerid = null)
    {
        $filter = new FilterDescriptor();
        $filter->addFilter('lejart', '=', false);
        if ($partnerid) {
            $filter->addFilter('partner', '=', $partnerid);
        }
        $rec = $this->getRepo()->getAll($filter, ['vasarlasnapja' => 'ASC']);
        $res = [];
        /** @var \Entities\JogaBerlet $sor */
        foreach ($rec as $sor) {
            $res[] = [
                'id' => $sor->getId(),
                'caption' => $sor->getFullNev(),
                'termekid' => $sor->getTermekId(),
                'selected' => ($sor->getId() == $selid)
            ];
        }
        return $res;
    }

    public function getSelectHtml()
    {
        $data = $this->getSelectList(null, $this->params->getIntRequestParam('partnerid'));
        $view = $this->createView('jogareszvetelberletselect.tpl');
        $view->setVar('berletlist', $data);
        echo $view->getTemplateResult();
    }

    public function getar()
    {
        // Nincsenek ársávok
        if (!\mkw\store::isArsavok()) {
            /** @var \Entities\JogaBerlet $berlet */
            $berlet = $this->getEm()->getRepository(JogaBerlet::class)->find($this->params->getIntRequestParam('berlet'));
            if ($berlet) {
                $termek = $berlet->getTermek();
                if ($termek) {
                    $partner = $this->getEm()->getRepository(Partner::class)->find($this->params->getIntRequestParam('partner'));
                    $valutanem = $this->getEm()->getRepository(Valutanem::class)->find($this->params->getIntRequestParam('valutanem'));
                    $valtozat = null;
                    if ($this->params->getIntRequestParam('valtozat')) {
                        $valtozat = $this->getEm()->getRepository(TermekValtozat::class)->find($this->params->getIntRequestParam('valtozat'));
                    }
                    if ($berlet->getElszamoloAr()) {
                        $r = [
                            'netto' => 0,
                            'brutto' => $berlet->getElszamoloAr(),
                            'nettofull' => $berlet->getNettoegysar(),
                            'bruttofull' => $berlet->getTeljesAr($valtozat),
                            'kedvezmeny' => $termek->getKedvezmeny($partner),
                            'enetto' => 0,
                            'ebrutto' => 0,
                        ];
                        echo json_encode($r);
                    } else {
                        if ($termek) {
                            $o = $termek->getJogaalkalom();
                            if (!$o) {
                                $o = 1;
                            }
                            $r = [
                                'netto' => $termek->getNettoAr($valtozat) / $o,
                                'brutto' => $termek->getBruttoAr($valtozat) / $o,
                                'nettofull' => $termek->getNettoAr($valtozat),
                                'bruttofull' => $termek->getBruttoAr($valtozat),
                                'kedvezmeny' => $termek->getKedvezmeny($partner) / $o,
                                'enetto' => 0,
                                'ebrutto' => 0,
                            ];
                            echo json_encode($r);
                        }
                    }
                }
            }
        } // Vannak ársávok
        else {
            $arsavnev = 'folyamatos';
            /** @var \Entities\JogaBerlet $berlet */
            $berlet = $this->getEm()->getRepository(JogaBerlet::class)->find($this->params->getIntRequestParam('berlet'));
            if ($berlet) {
                /** @var \Entities\Termek $termek */
                $termek = $berlet->getTermek();
                if ($termek) {
                    $partner = $this->getEm()->getRepository(Partner::class)->find($this->params->getIntRequestParam('partner'));
                    $valutanem = $this->getEm()->getRepository(Valutanem::class)->find($this->params->getIntRequestParam('valutanem'));
                    $valtozat = null;
                    if ($this->params->getIntRequestParam('valtozat')) {
                        $valtozat = $this->getEm()->getRepository(TermekValtozat::class)->find($this->params->getIntRequestParam('valtozat'));
                    }
                    if ($berlet->getElszamoloAr()) {
                        $r = [
                            'netto' => 0,
                            'brutto' => $berlet->getElszamoloAr(),
                            'nettofull' => 0,
                            'bruttofull' => $berlet->getTeljesAr($valtozat),
                            'kedvezmeny' => $termek->getKedvezmeny($partner),
                            'enetto' => 0,
                            'ebrutto' => 0,
                        ];
                        echo json_encode($r);
                    } else {
                        $o = $termek->getJogaalkalom();
                        if (!$o) {
                            $o = 1;
                        }
                        $r = [
                            'netto' => $termek->getNettoAr($valtozat, $partner, $valutanem, $arsavnev) / $o,
                            'brutto' => $termek->getBruttoAr($valtozat, $partner, $valutanem, $arsavnev) / $o,
                            'nettofull' => $termek->getNettoAr($valtozat, $partner, $valutanem, $arsavnev),
                            'bruttofull' => $termek->getBruttoAr($valtozat, $partner, $valutanem, $arsavnev),
                            'kedvezmeny' => $termek->getKedvezmeny($partner) / $o,
                            'enetto' => 0,
                            'ebrutto' => 0,
                        ];
                        echo json_encode($r);
                    }
                }
            }
        }
        if (!$r) {
            echo json_encode([
                'netto' => 0,
                'brutto' => 0,
                'nettofull' => 0,
                'bruttofull' => 0,
                'kedvezmeny' => 0,
                'enetto' => 0,
                'ebrutto' => 0
            ]);
        }
    }

    public function getBerletAlkalmak()
    {
        $tanar = $this->getRepo(Dolgozo::class)->find($this->params->getIntRequestParam('t'));
        if ($tanar) {
            $filter = new FilterDescriptor();
            $partnerek = $this->getRepo(JogaReszvetel::class)->getTanarhozJarok($tanar);
            $res = [];
            foreach ($partnerek as $partner) {
                $filter->addFilter('partner', '=', $partner);
                $reszvetelek = $this->getRepo(JogaReszvetel::class)->getAll($filter, ['datum' => 'DESC']);
                /** @var \Entities\JogaReszvetel $reszvetel */
                $reszvetel = $reszvetelek[0];
                $berlet = $reszvetel->getJogaberlet();
                if (!$berlet) {
                    $berlet = $this->getRepo()->getAktualisBerlet($partner);
                }
                if ($berlet) {
                    $res[] = [
                        'partnerid' => $reszvetel->getPartnerId(),
                        'partner' => $reszvetel->getPartnernev(),
                        'berlet' => $berlet->getNev(),
                        'elhasznalt' => $berlet->getElfogyottalkalom() + $berlet->getOfflineelfogyottalkalom()
                    ];
                } else {
                    $res[] = [
                        'partnerid' => $reszvetel->getPartnerId(),
                        'partner' => $reszvetel->getPartnernev(),
                        'berlet' => 'nincs bérlete',
                        'elhasznalt' => ''
                    ];
                }
                $filter->clear();
            }
            $excel = new Spreadsheet();
            $excel->setActiveSheetIndex(0)
                ->setCellValue('A1', t('Partner ID'))
                ->setCellValue('B1', t('Partner'))
                ->setCellValue('C1', t('Bérlet'))
                ->setCellValue('D1', t('Felhasznált alkalom'));

            $num = 2;
            foreach ($res as $sor) {
                $excel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $num, $sor['partnerid'])
                    ->setCellValue('B' . $num, $sor['partner'])
                    ->setCellValue('C' . $num, $sor['berlet'])
                    ->setCellValue('D' . $num, $sor['elhasznalt']);
                $num++;
            }
            $writer = IOFactory::createWriter($excel, 'Xlsx');

            $filepath = \mkw\store::storagePath('berletellenor.xlsx');
            $writer->save($filepath);
            $fileSize = filesize($filepath);

            // Output headers.
            header('Cache-Control: private');
            header('Content-Type: application/stream');
            header('Content-Length: ' . $fileSize);
            header('Content-Disposition: attachment; filename=berletellenor.xlsx');

            readfile($filepath);

            \unlink($filepath);
        }
    }

    public function setflag()
    {
        $id = $this->params->getIntRequestParam('id');
        $kibe = $this->params->getBoolRequestParam('kibe');
        $flag = $this->params->getStringRequestParam('flag');
        /** @var \Entities\JogaBerlet $obj */
        $obj = $this->getRepo()->find($id);
        if ($obj) {
            switch ($flag) {
                case 'lejart':
                    $oldlejart = $obj->isLejart();
                    $obj->setLejart($kibe);
                    if (!$oldlejart && $oldlejart !== $obj->isLejart()) {
                        $obj->sendEmail(\mkw\store::getParameter(\mkw\consts::JogaBerletDatumLejartSablon));
                    }
                    break;
                case 'nincsfizetve':
                    $obj->setNincsfizetve($kibe);
                    break;
            }
            $this->getEm()->persist($obj);
            $this->getEm()->flush();
        }
    }

    public function bulkLejarat()
    {
        $dt = new \DateTime();
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('lejaratdatum', '<=', $dt->format(\mkw\store::$SQLDateFormat));
        $filter->addFilter('lejart', '=', false);
        $berletek = $this->getRepo()->getAll($filter);
        /** @var JogaBerlet $berlet */
        foreach ($berletek as $berlet) {
            $berlet->setLejart(true);
            $this->getEm()->persist($berlet);
            $this->getEm()->flush();
            \mkw\store::writelog(
                $berlet->getId() . ': '
                . $berlet->getNev() . ' | '
                . $berlet->getPartnernev()
                . ' (' . $berlet->getPartneremail() . ') | '
                . $berlet->getVasarlasnapjaStr() . ' | '
                . $berlet->getElfogyottalkalom() . ' elfogyott alkalom'
                ,
                'berletlejarat.txt'
            );
            $berlet->sendEmail(\mkw\store::getParameter(\mkw\consts::JogaBerletDatumLejartSablon));
        }
        echo 'ok';
    }
}
