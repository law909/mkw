<?php

namespace Controllers;

use Entities\Bankbizonylatfej;
use Entities\Bankbizonylattetel;
use Entities\Bizonylatfej;
use Entities\Bizonylattetel;
use Entities\Fizmod;
use mkwhelpers\FilterDescriptor;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class bankbizonylatfejController extends \mkwhelpers\MattableController
{

    public function __construct($params)
    {
        $this->setEntityName('Entities\Bankbizonylatfej');
        $this->setKarbFormTplName('bankbizonylatfejkarbform.tpl');
        $this->setKarbTplName('bankbizonylatfejkarb.tpl');
        $this->setListBodyRowTplName('bankbizonylatfejlista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct($params);
    }

    protected function loadVars($t, $forKarb = false)
    {
        $x = [];
        if (!$t) {
            $t = new \Entities\Bankbizonylatfej();
            $this->getEm()->detach($t);
        }
        $x['id'] = $t->getId();
        $x['irany'] = $t->getIrany();
        $x['bizonylattipusid'] = $t->getBizonylattipusId();
        $x['rontott'] = $t->getRontott();
        $x['erbizonylatszam'] = $t->getErbizonylatszam();
        $x['megjegyzes'] = $t->getMegjegyzes();
        $x['keltstr'] = $t->getKeltStr();
        $x['netto'] = $t->getNetto();
        $x['afa'] = $t->getAfa();
        $x['brutto'] = $t->getBrutto();
        $x['valutanemnev'] = $t->getValutanemnev();
        $x['bankszamla'] = $t->getBankszamla();
        $x['bankszamlaszam'] = $t->getTulajbankszamlaszam();
        $x['swift'] = $t->getTulajswift();
        $x['iban'] = $t->getTulajiban();
        $x['partnernev'] = $t->getPartnernev();
        $x['partnerkeresztnev'] = $t->getPartnerkeresztnev();
        $x['partnervezeteknev'] = $t->getPartnervezeteknev();
        $x['partneradoszam'] = $t->getPartneradoszam();
        $x['partnereuadoszam'] = $t->getPartnereuadoszam();
        $x['partnerirszam'] = $t->getPartnerirszam();
        $x['partnervaros'] = $t->getPartnervaros();
        $x['partnerutca'] = $t->getPartnerutca();
        $x['updatedby'] = $t->getUpdatedbyNev();
        $x['createdby'] = $t->getCreatedbyNev();
        $x['lastmodstr'] = $t->getLastmodStr();
        $x['createdstr'] = $t->getCreatedStr();

        $x['nemrossz'] = !$t->getRontott();

        if ($forKarb) {
            $tetelCtrl = new bankbizonylattetelController($this->params);
            foreach ($t->getBizonylattetelek() as $ttetel) {
                $tetel[] = $tetelCtrl->loadVars($ttetel, true);
            }
            $x['tetelek'] = $tetel;
        }
        return $x;
    }

    /**
     * @param \Entities\Bankbizonylatfej $obj
     *
     * @return \Entities\Bankbizonylatfej
     */
    protected function setFields($obj)
    {
        $type = $this->params->getStringRequestParam('type');

        $obj->setErbizonylatszam($this->params->getStringRequestParam('erbizonylatszam'));
        $obj->setMegjegyzes($this->params->getStringRequestParam('megjegyzes'));
        $obj->setKelt($this->params->getStringRequestParam('kelt'));

        $valutanem = \mkw\store::getEm()->getRepository('Entities\Valutanem')->find($this->params->getIntRequestParam('valutanem'));
        if ($valutanem) {
            $obj->setValutanem($valutanem);
        }

        $ck = \mkw\store::getEm()->getRepository('Entities\Bankszamla')->find($this->params->getIntRequestParam('bankszamla'));
        if ($ck) {
            $obj->setBankszamla($ck);
        }

        switch ($type) {
            case 'b':
                $bt = $this->getRepo('Entities\Bizonylattipus')->find('bank');
                $obj->setBizonylattipus($bt);
                break;
            case 'p':
                break;
        }

        switch ($type) {
            case 'b':
                $tetelids = $this->params->getArrayRequestParam('tetelid');
                foreach ($tetelids as $tetelid) {
                    if (($this->params->getIntRequestParam('teteljogcim_' . $tetelid) > 0)) {
                        $oper = $this->params->getStringRequestParam('teteloper_' . $tetelid);
                        $irany = $this->params->getIntRequestParam('tetelirany_' . $tetelid);
                        $jogcim = $this->getEm()->getRepository('Entities\Jogcim')->find($this->params->getIntRequestParam('teteljogcim_' . $tetelid));
                        $partner = $this->getEm()->getRepository('Entities\Partner')->find($this->params->getIntRequestParam('tetelpartner_' . $tetelid));
                        if ($jogcim && $partner) {
                            switch ($oper) {
                                case $this->addOperation:
                                case $this->inheritOperation:
                                    $tetel = new Bankbizonylattetel();
                                    $obj->addBizonylattetel($tetel);

                                    $tetel->setJogcim($jogcim);
                                    if ($irany < 0) {
                                        $tetel->setIrany(-1);
                                    } else {
                                        $tetel->setIrany(1);
                                    }

                                    $tetel->setValutanem($valutanem);
                                    $tetel->setPartner($partner);
                                    $tetel->setDatum($this->params->getStringRequestParam('teteldatum_' . $tetelid));
                                    $tetel->setHivatkozottbizonylat($this->params->getStringRequestParam('tetelhivatkozottbizonylat_' . $tetelid));
                                    $tetel->setHivatkozottdatum($this->params->getStringRequestParam('tetelhivatkozottdatum_' . $tetelid));
                                    $tetel->setErbizonylatszam($this->params->getStringRequestParam('tetelerbizonylatszam_' . $tetelid));

                                    $tetel->setBrutto($this->params->getFloatRequestParam('tetelosszeg_' . $tetelid));

                                    $this->getEm()->persist($tetel);
                                    break;
                                case $this->editOperation:
                                    /** @var \Entities\Bankbizonylattetel $tetel */
                                    $tetel = $this->getEm()->getRepository('Entities\Bankbizonylattetel')->find($tetelid);
                                    if ($tetel) {
                                        $tetel->setJogcim($jogcim);
                                        if ($irany < 0) {
                                            $tetel->setIrany(-1);
                                        } else {
                                            $tetel->setIrany(1);
                                        }
                                        $tetel->setValutanem($valutanem);
                                        $tetel->setPartner($partner);
                                        $tetel->setDatum($this->params->getStringRequestParam('teteldatum_' . $tetelid));
                                        $tetel->setHivatkozottbizonylat($this->params->getStringRequestParam('tetelhivatkozottbizonylat_' . $tetelid));
                                        $tetel->setHivatkozottdatum($this->params->getStringRequestParam('tetelhivatkozottdatum_' . $tetelid));
                                        $tetel->setErbizonylatszam($this->params->getStringRequestParam('tetelerbizonylatszam_' . $tetelid));

                                        $tetel->setBrutto($this->params->getFloatRequestParam('tetelosszeg_' . $tetelid));

                                        $this->getEm()->persist($tetel);
                                    }
                                    break;
                            }
                        } else {
                            \mkw\store::writelog(print_r($this->params->asArray(), true), 'nincsjogcim.log');
                        }
                    }
                }
                break;
        }

        return $obj;
    }

    protected function setVars($view)
    {
        $bt = $this->getRepo('Entities\Bizonylattipus')->find('bank');
        if ($bt) {
            $bt->setTemplateVars($view);
        }

        $vc = new valutanemController($this->params);
        $view->setVar('valutanemlist', $vc->getSelectList());

        $bc = new bankszamlaController($this->params);
        $view->setVar('bankszamlalist', $bc->getSelectList());
    }

    public function getlistbody()
    {
        $view = $this->createView('bankbizonylatfejlista_tbody.tpl');

        $this->setVars($view);

        $filter = new \mkwhelpers\FilterDescriptor();

        if (!is_null($this->params->getRequestParam('idfilter', null))) {
            $filter->addFilter('id', 'LIKE', '%' . $this->params->getStringRequestParam('idfilter'));
        }

        $v = $this->getRepo('Entities\Valutanem')->find($this->params->getIntRequestParam('valutanemfilter'));
        if ($v) {
            $filter->addFilter('valutanem', '=', $v);
        }

        $b = $this->getRepo('Entities\Bankszamla')->find($this->params->getIntRequestParam('bankszamlafilter'));
        if ($b) {
            $filter->addFilter('bankszamla', '=', $b);
        }

        $tol = $this->params->getStringRequestParam('datumtolfilter');
        $ig = $this->params->getStringRequestParam('datumigfilter');
        if ($tol || $ig) {
            if ($tol) {
                $filter->addFilter('kelt', '>=', $tol);
            }
            if ($ig) {
                $filter->addFilter('kelt', '<=', $ig);
            }
        }
        $f = $this->params->getStringRequestParam('erbizonylatszamfilter');
        if ($f) {
            $filter->addFilter('erbizonylatszam', 'LIKE', '%' . $f . '%');
        }
        $f = $this->params->getIntRequestParam('bizonylatrontottfilter');
        switch ($f) {
            case 1:
                $filter->addFilter('rontott', '=', false);
                break;
            case 2:
                $filter->addFilter('rontott', '=', true);
                break;
        }

        $this->initPager(
            $this->getRepo()->getCount($filter),
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
        $view = $this->createView('bankbizonylatfejlista.tpl');

        $this->setVars($view);

        $view->setVar('pagetitle', t('Bankbizonylat'));
        $view->printTemplateResult();
    }

    public function viewlist()
    {
        $view = $this->createView('bankbizonylatfejlista.tpl');

        $this->setVars($view);

        $view->setVar('pagetitle', t('Bankbizonylat'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Bankbizonylat'));
        $view->setVar('oper', $oper);
        $view->setVar('formaction', '/admin/bankbizonylatfej/save');
        $record = $this->getRepo()->findWithJoins($id);
        $view->setVar('egyed', $this->loadVars($record, true));

        $bt = $this->getRepo('Entities\Bizonylattipus')->find('bank');
        $bt->setTemplateVars($view);

        $partner = new partnerController($this->params);
        $view->setVar('partnerlist', $partner->getSelectList(($record ? $record->getPartnerId() : 0)));

        $valutanem = new valutanemController($this->params);
        if (!$record || !$record->getValutanemId()) {
            $valutaid = \mkw\store::getParameter(\mkw\consts::Valutanem, 0);
        } else {
            $valutaid = $record->getValutanemId();
        }
        $view->setVar('valutanemlist', $valutanem->getSelectList($valutaid));

        $bankszla = new bankszamlaController($this->params);
        $bankszlaid = false;
        if ($record && $record->getBankszamlaId()) {
            $bankszlaid = $record->getBankszamlaId();
        } else {
            $valutanem = $this->getRepo('Entities\Valutanem')->find($valutaid);
            if ($valutanem && $valutanem->getBankszamlaId()) {
                $bankszlaid = $valutanem->getBankszamlaId();
            }
        }
        $view->setVar('bankszamlalist', $bankszla->getSelectList($bankszlaid));

        return $view->getTemplateResult();
    }

    public function exportKonyvelo()
    {
        function x($o)
        {
            if ($o <= 26) {
                return chr(65 + $o);
            }
            return chr(65 + floor($o / 26)) . chr(65 + ($o % 26));
        }

        $tol = $this->params->getStringRequestParam('tol');
        $tol = date(\mkw\store::$SQLDateFormat, strtotime(\mkw\store::convDate($tol)));
        \mkw\store::writelog($tol);
        $ig = $this->params->getStringRequestParam('ig');
        $ig = date(\mkw\store::$SQLDateFormat, strtotime(\mkw\store::convDate($ig)));
        \mkw\store::writelog($ig);

        $excel = new Spreadsheet();
        $excel->setActiveSheetIndex(0)
            ->setCellValue('A1', t('bizszam'))
            ->setCellValue('B1', t('partnev'))
            ->setCellValue('C1', t('partadosz'))
            ->setCellValue('D1', t('kelt'))
            ->setCellValue('E1', t('telj'))
            ->setCellValue('F1', t('fizhat'))
            ->setCellValue('G1', t('bruttod'))
            ->setCellValue('H1', t('penznem'))
            ->setCellValue('I1', t('fizmod'))
            ->setCellValue('J1', t('Tényleges kiegyenlítés'));

        $filter = new FilterDescriptor();
        $filter->addFilter('bt.datum', '>=', $tol);
        $filter->addFilter('bt.datum', '<=', $ig);

        $cellIndex = 2;
        $adat = $this->getRepo()->getWithJoins($filter, ['bt.datum' => 'ASC']);
        /** @var Bankbizonylatfej $fej */
        foreach ($adat as $fej) {
            /** @var Bankbizonylattetel $tetel */
            foreach ($fej->getBizonylattetelek() as $tetel) {
                /** @var Bizonylatfej $szamla */
                $szamla = $this->getRepo(Bizonylatfej::class)->find($tetel->getHivatkozottbizonylat());
                if ($szamla) {
                    $excel->getActiveSheet()
                        ->setCellValue('A' . $cellIndex, $tetel->getHivatkozottbizonylat())
                        ->setCellValue('B' . $cellIndex, $szamla->getPartnernev())
                        ->setCellValue('C' . $cellIndex, $szamla->getPartneradoszam())
                        ->setCellValue('D' . $cellIndex, $szamla->getKeltStr())
                        ->setCellValue('E' . $cellIndex, $szamla->getTeljesitesStr())
                        ->setCellValue('F' . $cellIndex, $szamla->getEsedekessegStr())
                        ->setCellValue('G' . $cellIndex, $tetel->getBrutto())
                        ->setCellValue('H' . $cellIndex, $tetel->getValutanemnev())
                        ->setCellValue('I' . $cellIndex, $szamla->getFizmodnev())
                        ->setCellValue('J' . $cellIndex, $tetel->getDatumStr());
                    $cellIndex++;
                }
            }
        }

        $kpfilter = [];
        $kpfm = $this->getRepo(Fizmod::class)->getAllKeszpenzes();
        /** @var Fizmod $fm */
        foreach ($kpfm as $fm) {
            $kpfilter[] = $fm->getId();
        }
        $filter->clear();
        $filter->addFilter('kelt', '>=', $tol);
        $filter->addFilter('kelt', '<=', $ig);
        $filter->addFilter('fizmod', 'IN', $kpfilter);
        $filter->addFilter('bizonylattipus', '=', 'szamla');
        $kpszamlak = $this->getRepo(Bizonylatfej::class)->getAll($filter, ['_xx.kelt' => 'ASC']);
        /** @var Bizonylatfej $tetel */
        foreach ($kpszamlak as $tetel) {
            $excel->getActiveSheet()
                ->setCellValue('A' . $cellIndex, $tetel->getId())
                ->setCellValue('B' . $cellIndex, $tetel->getPartnernev())
                ->setCellValue('C' . $cellIndex, $tetel->getPartneradoszam())
                ->setCellValue('D' . $cellIndex, $tetel->getKeltStr())
                ->setCellValue('E' . $cellIndex, $tetel->getTeljesitesStr())
                ->setCellValue('F' . $cellIndex, $tetel->getEsedekessegStr())
                ->setCellValue('G' . $cellIndex, $tetel->getBrutto())
                ->setCellValue('H' . $cellIndex, $tetel->getValutanemnev())
                ->setCellValue('I' . $cellIndex, $tetel->getFizmodnev())
                ->setCellValue('J' . $cellIndex, $tetel->getKeltStr());
            $cellIndex++;
        }

        $writer = IOFactory::createWriter($excel, 'Xlsx');
        $filename = uniqid('konyveloexport') . '.xlsx';
        $filepath = \mkw\store::storagePath($filename);
        $writer->save($filepath);

        echo json_encode(['url' => \mkw\store::storageUrl($filename)]);
    }

}