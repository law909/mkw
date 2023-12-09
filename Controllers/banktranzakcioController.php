<?php

namespace Controllers;

use Entities\Bankbizonylatfej;
use Entities\Bankbizonylattetel;
use Entities\BankTranzakcio;
use Entities\Bizonylatfej;
use Entities\Bizonylattipus;
use Entities\Jogcim;
use Entities\Partner;
use Entities\Valutanem;
use mkwhelpers\FilterDescriptor;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class banktranzakcioController extends \mkwhelpers\MattableController
{

    public function __construct($params)
    {
        $this->setEntityName(BankTranzakcio::class);
        $this->setKarbFormTplName('banktranzakciokarbform.tpl');
        $this->setKarbTplName('banktranzakciokarb.tpl');
        $this->setListBodyRowTplName('banktranzakciolista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct($params);
    }

    public function loadVars($t, $forKarb = false)
    {
        $letezik = true;
        $x = [];
        if (!$t) {
            $letezik = false;
            $t = new \Entities\BankTranzakcio();
            $this->getEm()->detach($t);
        }
        $x['id'] = $t->getId();
        $x['azonosito'] = $t->getAzonosito();
        $x['kozlemeny1'] = $t->getKozlemeny1();
        $x['kozlemeny2'] = $t->getKozlemeny2();
        $x['kozlemeny3'] = $t->getKozlemeny3();
        $x['konyvelesdatumstr'] = $t->getKonyvelesdatumStr();
        $x['erteknapstr'] = $t->getErteknapStr();
        $x['osszeg'] = $t->getOsszeg();
        $x['bizonylatszamok'] = $t->getBizonylatszamok();
        $x['bankbizonylatkesz'] = $t->isBankbizonylatkesz();
        $x['inaktiv'] = $t->isInaktiv();
        return $x;
    }

    /**
     * @param \Entities\BankTranzakcio $obj
     *
     * @return mixed
     */
    protected function setFields($obj)
    {
        $obj->setInaktiv($this->params->getBoolRequestParam('inaktiv'));
        $obj->setBizonylatszamok($this->params->getStringRequestParam('bizonylatszamok'));
        $partner = $this->getRepo(Partner::class)->find($this->params->getIntRequestParam('partner'));
        if ($partner) {
            $obj->setPartner($partner);
        } else {
            $obj->setPartner(null);
        }
        return $obj;
    }

    public function getlistbody()
    {
        $view = $this->createView('banktranzakciolista_tbody.tpl');

        $filter = new FilterDescriptor();

        $this->initPager(
            $this->getRepo()->getCount($filter),
            $this->params->getIntRequestParam('elemperpage', 30),
            $this->params->getIntRequestParam('pageno', 1)
        );

        $egyedek = $this->getRepo()->getAll(
            $filter,
            $this->getOrderArray(),
            $this->getPager()->getOffset(),
            $this->getPager()->getElemPerPage()
        );

        echo json_encode($this->loadDataToView($egyedek, 'egyedlista', $view));
    }

    public function viewlist()
    {
        $view = $this->createView('banktranzakciolista.tpl');

        $view->setVar('pagetitle', t('Bank tranzakciók'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Bank tranzakciók'));
        $view->setVar('formaction', '/admin/banktranzakcio/save');
        $view->setVar('oper', $oper);
        $record = $this->getRepo()->find($id);

        if (!\mkw\store::isPartnerAutocomplete()) {
            $partnerc = new partnerController($this->params);
            $view->setVar('partnerlist', $partnerc->getSelectList(($record ? $record->getPartnerId() : 0)));
        }

        $view->setVar('egyed', $this->loadVars($record, true));
        return $view->getTemplateResult();
    }

    public function viewupload()
    {
        $view = $this->createView('banktranzakcioupload.tpl');
        $view->printTemplateResult();
    }

    public function upload()
    {
        $filenev = \mkw\store::storagePath($_FILES['toimport']['name']);
        move_uploaded_file($_FILES['toimport']['tmp_name'], $filenev);

        $filetype = IOFactory::identify($filenev);
        $reader = IOFactory::createReader($filetype);
        $reader->setReadDataOnly(true);
        $excel = $reader->load($filenev);
        $sheet = $excel->getActiveSheet();
        $maxrow = (int)$sheet->getHighestRow();

        /** @var Bizonylattipus $szamlatipus */
        $szamlatipus = $this->getRepo(Bizonylattipus::class)->find('szamla');
        // '/[Ss]?[Zz]?\d{4}\/\d{1,6}/'
        $regexp = '/' . $szamlatipus->getAzonositoForRegexp() . '\d{4}\/\d{1,6}/';
        $repo = $this->getRepo();
        $partnerrepo = $this->getRepo(Partner::class);
        $bizrepo = $this->getRepo(Bizonylatfej::class);

        for ($row = 0; $row <= $maxrow; ++$row) {
            $osszeg = (float)$sheet->getCell('E' . $row)->getValue();
            if ($osszeg && $osszeg > 0) {
                $azon = (string)$sheet->getCell('D' . $row)->getValue();
                if ($azon && !$repo->findOneBy(['azonosito' => $azon])) {
                    $o = new BankTranzakcio();
                    $o->setAzonosito($azon);
                    $o->setOsszeg($osszeg);

                    $o->setKozlemeny1((string)$sheet->getCell('F' . $row)->getValue());
                    $o->setKozlemeny2((string)$sheet->getCell('G' . $row)->getValue());
                    $o->setKozlemeny3((string)$sheet->getCell('H' . $row)->getValue());

                    $o->setKonyvelesdatum(Date::excelToDateTimeObject($sheet->getCell('B' . $row)->getValue()));
                    $o->setErteknap(Date::excelToDateTimeObject($sheet->getCell('C' . $row)->getValue()));

                    $partner = $partnerrepo->findOneBy(['iban' => (string)$sheet->getCell('F' . $row)->getValue()]);
                    if ($partner) {
                        $o->setPartner($partner);
                    }

                    $bizszamarr = [];
                    $trimmedbizsz = trim((string)$sheet->getCell('H' . $row)->getValue());
                    /** @var Bizonylatfej $biz */
                    $biz = $bizrepo->find($trimmedbizsz);
                    if ($biz) {
                        $bizszamarr[] = $biz->getId();
                    } elseif (is_numeric($trimmedbizsz)) {
                        $convertedB = $szamlatipus->getAzonosito() . $o->getErteknap()->format('Y') . '/' . str_pad($trimmedbizsz, 6, '0', STR_PAD_LEFT);

                        /** @var Bizonylatfej $biz */
                        $biz = $bizrepo->find($convertedB);
                        if ($biz) {
                            if (!$partner || ($partner && $partner->getId() == $biz->getPartnerId())) {
                                $bizszamarr[] = $biz->getId();
                            }
                        }
                    } else {
                        $matchcnt = preg_match_all($regexp, str_replace(' ', '', $trimmedbizsz), $bizsz);
                        if ($matchcnt) {
                            foreach ($bizsz[0] as $b) {
                                $convertedB = strtoupper($b);
                                $szamlatipusAzonosito = $szamlatipus->getAzonosito();

                                if (substr($convertedB, 0, 2) !== $szamlatipusAzonosito) {
                                    $convertedB = $szamlatipusAzonosito . $convertedB;
                                }
                                $partsofB = explode('/', $convertedB);
                                $partsofB[1] = sprintf('%06d', (int)$partsofB[1]);
                                $convertedB = implode('/', $partsofB);

                                /** @var Bizonylatfej $biz */
                                $biz = $bizrepo->find($convertedB);
                                if ($biz) {
                                    if (!$partner || ($partner && $partner->getId() == $biz->getPartnerId())) {
                                        $bizszamarr[] = $biz->getId();
                                    }
                                }
                            }
                        }
                    }
                    if ($bizszamarr) {
                        $o->setBizonylatszamok(implode(';', $bizszamarr));
                    }

                    $this->getEm()->persist($o);
                    $this->getEm()->flush();
                }
            }
        }
        $excel->disconnectWorksheets();
        \unlink($filenev);
    }

    public function generateBankbizonylat()
    {
        $jogcim = $this->getRepo(Jogcim::class)->find(1);
        $ids = $this->params->getArrayRequestParam('ids');
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('bankbizonylatkesz', '=', false);
        $filter->addFilter('inaktiv', '=', false);
        if ($ids) {
            $filter->addFilter('id', 'IN', $ids);
        }
        $trs = $this->getRepo()->getAll($filter, ['erteknap' => 'ASC']);
        /** @var BankTranzakcio $tr */
        foreach ($trs as $tr) {
            $mehet = true;
            $szlaszamok = explode(';', $tr->getBizonylatszamok());
            foreach ($szlaszamok as $szlaszam) {
                /** @var Bizonylatfej $szamla */
                $szamla = $this->getRepo(Bizonylatfej::class)->find($szlaszam);
                if (!$szamla) {
                    $mehet = false;
                }
            }
            if ($mehet) {
                $bb = new Bankbizonylatfej();
                $bb->setBizonylattipus($this->getRepo(Bizonylattipus::class)->find('bank'));
                $bb->setKelt();
                if ($tr->getPartner()) {
                    $bb->setPartner($tr->getPartner());
                }
                $bb->setValutanem($this->getRepo(Valutanem::class)->find(\mkw\store::getParameter(\mkw\consts::Valutanem)));
                $befosszeg = $tr->getOsszeg();
                foreach ($szlaszamok as $szlaszam) {
                    /** @var Bizonylatfej $szamla */
                    $szamla = $this->getRepo(Bizonylatfej::class)->find($szlaszam);
                    if ($szamla) {
                        if (!$bb->getPartner()) {
                            $bb->setPartner($szamla->getPartner());
                        }
                        $bb->setBankszamla($szamla->getBankszamla());
                        $bt = new Bankbizonylattetel();
                        $bt->setBizonylatfej($bb);
                        $bt->setPartner($szamla->getPartner());
                        $bt->setDatum($tr->getErteknapStr());
                        $bt->setHivatkozottbizonylat($szlaszam);
                        $bt->setHivatkozottdatum($szamla->getEsedekesseg());
                        $bt->setJogcim($jogcim);
                        $bt->setValutanem($szamla->getValutanem());
                        $bt->setErbizonylatszam($tr->getAzonosito());
                        $bt->setIrany(1);
                        $needed = min($szamla->getBrutto(), $befosszeg);
                        $bt->setBrutto($needed);
                        $this->getEm()->persist($bt);
                        $befosszeg = $befosszeg - $needed;
                        if ($befosszeg <= 0) {
                            break;
                        }
                    }
                }
                $tr->setBankbizonylatkesz(true);
                $this->getEm()->persist($tr);
                $this->getEm()->persist($bb);
                $this->getEm()->flush();
            }
        }
    }
}
