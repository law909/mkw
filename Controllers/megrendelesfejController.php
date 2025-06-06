<?php

namespace Controllers;

use Entities\Bizonylatfej;
use Entities\Bizonylatstatusz;
use Entities\Bizonylattetel;
use Entities\Termek;
use Entities\TermekFa;
use Entities\TermekValtozat;

class megrendelesfejController extends bizonylatfejController
{

    public function __construct($params)
    {
        $this->biztipus = 'megrendeles';
        $this->setPageTitle('Megrendelés');
        $this->setPluralPageTitle('Megrendelések');
        parent::__construct($params);
        $this->getRepo()->addToBatches(['foxpostsend' => 'Küldés Foxpostnak']);
        $this->getRepo()->addToBatches(['foxpostlabel' => 'Foxpost címke letöltés']);
        $this->getRepo()->addToBatches(['glssend' => 'Küldés GLS-nek']);
        $this->getRepo()->addToBatches(['recalcprice' => 'Árak újra számolása']);
        $this->getRepo()->addToBatches(['sendemailek' => 'Email sablon küldés']);
        $this->getRepo()->addToBatches(['rendelesconcat' => 'Megrendelések összevonása']);
    }

    public function setVars($view)
    {
        parent::setVars($view);
        $view->setVar('datumtolfilter', null);
    }

    public function getszamlakarb()
    {
        $megrendszam = $this->params->getStringRequestParams('id');
        $szamlac = new SzamlafejController($this->params);
        $szamlac->getkarb('bizonylatfejkarb.tpl', $megrendszam, 'add');
    }

    public function doPrintelolegbekero()
    {
        $o = $this->getRepo()->findForPrint($this->params->getStringRequestParam('id'));
        if ($o) {
            $biztip = $this->getRepo('Entities\Bizonylattipus')->find($this->biztipus);
            if ($biztip) {
                if (\mkw\store::isSuperzoneB2B()) {
                    $view = $this->createView('biz_elolegbekero_eng.tpl');
                } else {
                    $view = $this->createView('biz_elolegbekero.tpl');
                }
                $this->setVars($view);
                $view->setVar('egyed', $o->toLista());
                $view->setVar('afaosszesito', $this->getRepo()->getAFAOsszesito($o));
                echo $view->getTemplateResult();
            }
        }
    }

    public function sendToFoxPost()
    {
        $ids = $this->params->getArrayRequestParam('ids');
        foreach ($ids as $id) {
            /** @var \Entities\Bizonylatfej $megrendfej */
            $megrendfej = $this->getRepo()->find($id);
            if ($megrendfej && \mkw\store::isFoxpostSzallitasimod($megrendfej->getSzallitasimodId()) && !$megrendfej->getFoxpostBarcode()) {
                $megrendfej->setSimpleedit(true);
                $fpc = new \Controllers\csomagterminalController($this->params);
                $fpres = $fpc->sendMegrendelesToFoxpost($megrendfej);
                if ($fpres) {
                    switch (\mkw\store::getParameter(\mkw\consts::FoxpostApiVersion, 'v2')) {
                        case 'v1':
                            $megrendfej->setFoxpostBarcode($fpres['barcode']);
                            $megrendfej->setFuvarlevelszam($fpres['barcode']);
                            if (array_key_exists('trace', $fpres)) {
                                $megrendfej->setTraceurl($fpres['trace']['href']);
                            }
                            $this->getEm()->persist($megrendfej);
                            $this->getEm()->flush();
                            break;
                        case 'v2':
                            if (is_array($fpres)) {
                                if ($fpres['success']) {
                                    $megrendfej->setFoxpostBarcode($fpres['data']['clFoxId']);
                                    if (array_key_exists('barcodeTof', $fpres) && ($fpres['data']['barcodeTof'])) {
                                        $megrendfej->setFuvarlevelszam($fpres['data']['barcodeTof']);
                                    } else {
                                        $megrendfej->setFuvarlevelszam($fpres['data']['clFoxId']);
                                    }
                                    $megrendfej->setSysmegjegyzes('');
                                    $this->getEm()->persist($megrendfej);
                                    $this->getEm()->flush();
                                } else {
                                    $megrendfej->setSysmegjegyzes(json_encode($fpres['errors']));
                                    $this->getEm()->persist($megrendfej);
                                    $this->getEm()->flush();
                                }
                            }
                            break;
                    }
                }
            }
        }
    }

    public function generateFoxpostLabel()
    {
        $ids = $this->params->getArrayRequestParam('ids');
        $clfids = [];
        foreach ($ids as $id) {
            /** @var \Entities\Bizonylatfej $megrendfej */
            $megrendfej = $this->getRepo()->find($id);
            if ($megrendfej && \mkw\store::isFoxpostSzallitasimod($megrendfej->getSzallitasimodId()) && $megrendfej->getFoxpostBarcode()) {
                $clfids[] = $megrendfej->getFoxpostBarcode();
            }
        }
        if ($clfids) {
            $cstc = new csomagterminalController(null);
            $res = $cstc->generateFoxpostLabels($clfids);
            if ($res && $res['success']) {
                foreach ($ids as $id) {
                    /** @var \Entities\Bizonylatfej $megrendfej */
                    $megrendfej = $this->getRepo()->find($id);
                    $megrendfej->setSimpleedit(true);
                    $megrendfej->setGlsparcellabelurl($res['data']);
                    $megrendfej->setSysmegjegyzes(null);
                    $this->getEm()->persist($megrendfej);
                    $this->getEm()->flush();
                }
            } else {
                foreach ($ids as $id) {
                    /** @var \Entities\Bizonylatfej $megrendfej */
                    $megrendfej = $this->getRepo()->find($id);
                    $megrendfej->setSimpleedit(true);
                    $megrendfej->setSysmegjegyzes(json_encode($res['errors']));
                    $this->getEm()->persist($megrendfej);
                    $this->getEm()->flush();
                }
            }
        }
    }

    private function _sendToGLS($glsmegrend, $pdfname)
    {
        $glsapi = new \mkwhelpers\GLSAPI([
                'clientnumber' => \mkw\store::getParameter(\mkw\consts::GLSClientNumber),
                'username' => \mkw\store::getParameter(\mkw\consts::GLSUsername),
                'password' => \mkw\store::getParameter(\mkw\consts::GLSPassword),
                'apiurl' => \mkw\store::getParameter(\mkw\consts::GLSApiURL),
                'pdfdirectory' => \mkw\store::getParameter(\mkw\consts::GLSParcelLabelDir)
            ]
        );
        $glsres = $glsapi->printLabels($glsmegrend, $pdfname);
        $glserror = $glsapi->getLasterrors();
        if ($glserror) {
            \mkw\store::writeLog('GLS API error: ' . json_encode($glserror), 'gls_api_error.txt');
        }
        if ($glsres) {
            $pdfname = implode('/', [
                rtrim($glsapi->getPdfdirectory(), '/'),
                $pdfname
            ]);
            foreach ($glsres as $item) {
                /** @var Bizonylatfej $megrendfej */
                $megrendfej = $this->getRepo()->find($item->ClientReference);
                if ($megrendfej) {
                    $megrendfej->setSimpleedit(true);
                    $megrendfej->setGlsparcelid($item->ParcelId);
                    $megrendfej->setGlsparcellabelurl($pdfname);
                    $megrendfej->setFuvarlevelszam($item->ParcelNumber);
                    $this->getEm()->persist($megrendfej);
                    $this->getEm()->flush();
                }
            }
        }
    }

    public function sendToGLS()
    {
        $ids = $this->params->getArrayRequestParam('ids');
        $db = 0;
        $pdfname = false;
        $glsmegrend = [];
        foreach ($ids as $id) {
            /** @var Bizonylatfej $megrendfej */
            $megrendfej = $this->getRepo()->find($id);
            if ($megrendfej
                && (\mkw\store::isGLSSzallitasimod($megrendfej->getSzallitasimodId())
                    || \mkw\store::isGLSFutarSzallitasimod($megrendfej->getSzallitasimodId()))
                && (!$megrendfej->getGlsparcelid())
            ) {
                if (!$pdfname) {
                    $pdfname = $megrendfej->getSanitizedId() . '_parcel_label.pdf';
                }
                $db++;
                $glsmegrend[] = $megrendfej->toGLSAPI();
                if ($db == 4) {
                    $this->_sendToGLS($glsmegrend, $pdfname);
                    $db = 0;
                    $pdfname = false;
                    $glsmegrend = [];
                }
            }
        }
        if ($glsmegrend) {
            $this->_sendToGLS($glsmegrend, $pdfname);
        }
    }

    public function delGLSParcel()
    {
        $id = $this->params->getStringRequestParam('id');
        /** @var \Entities\Bizonylatfej $megrendfej */
        $megrendfej = $this->getRepo()->find($id);
        if ($megrendfej) {
            $glsapi = new \mkwhelpers\GLSAPI([
                    'clientnumber' => \mkw\store::getParameter(\mkw\consts::GLSClientNumber),
                    'username' => \mkw\store::getParameter(\mkw\consts::GLSUsername),
                    'password' => \mkw\store::getParameter(\mkw\consts::GLSPassword),
                    'apiurl' => \mkw\store::getParameter(\mkw\consts::GLSApiURL),
                    'pdfdirectory' => \mkw\store::getParameter(\mkw\consts::GLSParcelLabelDir)
                ]
            );
            $glsres = $glsapi->deleteLabels([$megrendfej->getGlsparcelid()]);
            if ($glsres && $glsres[0]->ParcelId == $megrendfej->getGlsparcelid()) {
                $megrendfej->setSimpleedit(true);
                $megrendfej->setGlsparcellabelurl(null);
                $megrendfej->setGlsparcelid(null);
                $megrendfej->setFuvarlevelszam(null);
                $this->getEm()->persist($megrendfej);
                $this->getEm()->flush();
            }
        }
    }

    public function backOrder()
    {
        $id = $this->params->getStringRequestParam('id');
        $regibiz = $this->getRepo()->find($id);
        if ($regibiz) {
            $nominkeszlet = \mkw\store::getParameter(\mkw\consts::NoMinKeszlet);
            $nominkeszletkat = $this->getRepo(TermekFa::class)->find(\mkw\store::getParameter(\mkw\consts::NoMinKeszletTermekkat))?->getKarkod();
            $teljesitheto = $this->getRepo(Bizonylatstatusz::class)->find(\mkw\store::getParameter(\mkw\consts::BizonylatStatuszTeljesitheto));
            $backorder = $this->getRepo(Bizonylatstatusz::class)->find(\mkw\store::getParameter(\mkw\consts::BizonylatStatuszBackorder));
            $this->getEm()->beginTransaction();
            try {
                $ujdb = 0;
                $regidb = 0;
                /** @var \Entities\Bizonylattetel $regitetel */
                foreach ($regibiz->getBizonylattetelek() as $regitetel) {
                    /** @var \Entities\Termek $t */
                    $t = $regitetel->getTermek();
                    if ($t && $t->getMozgat()) {
                        $v = $regitetel->getTermekvaltozat();
                        $keszlet = 0;
                        if ($nominkeszlet && $t->isInTermekKategoria($nominkeszletkat)) {
                            if ($v) {
                                $keszlet = $v->getKeszlet() - $v->getFoglaltMennyiseg($regibiz->getId());
                            } else {
                                $keszlet = $t->getKeszlet() - $t->getFoglaltMennyiseg($regibiz->getId());
                            }
                        } else {
                            if ($v) {
                                $keszlet = $v->getKeszlet() - $v->getFoglaltMennyiseg($regibiz->getId()) - $v->calcMinboltikeszlet();
                            } else {
                                $keszlet = $t->getKeszlet() - $t->getFoglaltMennyiseg($regibiz->getId()) - $t->getMinboltikeszlet();
                            }
                        }
                    }
                    if ($keszlet < 0) {
                        $keszlet = 0;
                    }
                    if ($keszlet < $regitetel->getMennyiseg()) {
                        $ujdb++;
                        if ($keszlet > 0) {
                            $regidb++;
                        }
                    } else {
                        $regidb++;
                    }
                }
                if ($regidb == 0 || $ujdb == 0) {
                    $result = 0;
                    if ($ujdb == 0) {
                        $regibiz->setBizonylatstatusz($teljesitheto);
                        foreach ($regibiz->getBizonylattetelek() as $regitetel) {
                            $regitetel->fillEgysar();
                            $regitetel->calc();
                            $this->getEm()->persist($regitetel);
                        }
                    } elseif ($regidb == 0) {
                        $regibiz->setBizonylatstatusz($backorder);
                        $result = 1;
                    }
                    $this->getEm()->persist($regibiz);
                    $this->getEm()->flush();
                    $this->getEm()->commit();
                    echo json_encode(['refresh' => $result]);
                } else {
                    $ujbiz = new \Entities\Bizonylatfej();
                    $ujbiz->duplicateFrom($regibiz);
                    $ujbiz->clearId();
                    $ujbiz->clearCreated();
                    $ujbiz->clearLastmod();
                    $ujbiz->setKelt();
                    $ujbiz->setBizonylatstatusz($backorder);
                    /** @var \Entities\Bizonylattetel $regitetel */
                    foreach ($regibiz->getBizonylattetelek() as $regitetel) {
                        /** @var \Entities\Termek $t */
                        $t = $regitetel->getTermek();
                        if ($t && $t->getMozgat()) {
                            $v = $regitetel->getTermekvaltozat();
                            $keszlet = 0;
                            if ($nominkeszlet && $t->isInTermekKategoria($nominkeszletkat)) {
                                if ($v) {
                                    $keszlet = $v->getKeszlet() - $v->getFoglaltMennyiseg($regibiz->getId());
                                } else {
                                    $keszlet = $t->getKeszlet() - $t->getFoglaltMennyiseg($regibiz->getId());
                                }
                            } else {
                                if ($v) {
                                    $keszlet = $v->getKeszlet() - $v->getFoglaltMennyiseg($regibiz->getId()) - $v->calcMinboltikeszlet();
                                } else {
                                    $keszlet = $t->getKeszlet() - $t->getFoglaltMennyiseg($regibiz->getId()) - $t->getMinboltikeszlet();
                                }
                            }
                        }
                        if ($keszlet < 0) {
                            $keszlet = 0;
                        }
                        if ($keszlet < $regitetel->getMennyiseg()) {
                            $ujtetel = new \Entities\Bizonylattetel();
                            $ujtetel->duplicateFrom($regitetel);
                            $ujtetel->clearCreated();
                            $ujtetel->clearLastmod();
                            foreach ($regitetel->getTranslations() as $trans) {
                                $ujtrans = clone $trans;
                                $ujtetel->addTranslation($ujtrans);
                                $this->getEm()->persist($ujtrans);
                            }
                            $ujtetel->setMennyiseg($regitetel->getMennyiseg() - $keszlet);
                            $ujtetel->calc();
                            $ujbiz->addBizonylattetel($ujtetel);
                            $this->getEm()->persist($ujtetel);
                            if ($keszlet <= 0) {
                                $regibiz->removeBizonylattetel($regitetel);
                                $this->getEm()->remove($regitetel);
                            } else {
                                $regitetel->setMennyiseg($keszlet);
                                $regitetel->fillEgysar();
                                $regitetel->calc();
                                $this->getEm()->persist($regitetel);
                            }
                        } else {
                            $regitetel->fillEgysar();
                            $regitetel->calc();
                            $this->getEm()->persist($regitetel);
                        }
                    }
                    $regibiz->setBizonylatstatusz($teljesitheto);
                    $this->getEm()->persist($regibiz);
                    $this->getEm()->persist($ujbiz);
                    $this->getEm()->flush();
                    $this->getEm()->commit();
                    echo json_encode(['refresh' => 1]);
                }
            } catch (\Exception $e) {
                $this->getEm()->rollback();
                throw $e;
            }
        } else {
            echo json_encode(['refresh' => 0]);
        }
    }

    public function getTeljesithetoBackorderLista()
    {
        $ret = [];
        $backorder = $this->getRepo('Entities\Bizonylatstatusz')->find(\mkw\store::getParameter(\mkw\consts::BizonylatStatuszBackorder));
        if ($backorder) {
            $nominkeszlet = \mkw\store::getParameter(\mkw\consts::NoMinKeszlet);
            $nominkeszletkat = $this->getRepo(TermekFa::class)->find(\mkw\store::getParameter(\mkw\consts::NoMinKeszletTermekkat))?->getKarkod();

            $filter = new \mkwhelpers\FilterDescriptor();
            $filter->addFilter('bizonylatstatusz', '=', $backorder);
            $filter->addFilter('bizonylattipus', '=', 'megrendeles');
            $filter->addFilter('rontott', '=', false);
            $fejek = $this->getRepo()->getWithTetelek($filter, ['hatarido' => 'ASC']);
            if ($fejek) {
                /** @var \Entities\Bizonylatfej $fej */
                foreach ($fejek as $fej) {
                    $vankeszlet = false;
                    $tetelek = $fej->getBizonylattetelek();
                    /** @var \Entities\Bizonylattetel $tetel */
                    foreach ($tetelek as $tetel) {
                        /** @var \Entities\TermekValtozat $termekv */
                        $termekv = $tetel->getTermekvaltozat();
                        if ($termekv) {
                            if ($nominkeszlet && $tetel->getTermek()?->isInTermekKategoria($nominkeszletkat)) {
                                if ($termekv->getKeszlet() - $termekv->getFoglaltMennyiseg() > 0) {
                                    $vankeszlet = true;
                                    break;
                                }
                            } else {
                                if ($termekv->getKeszlet() - $termekv->getFoglaltMennyiseg() - $termekv->calcMinboltikeszlet() > 0) {
                                    $vankeszlet = true;
                                    break;
                                }
                            }
                        } else {
                            $termek = $tetel->getTermek();
                            if ($termek) {
                                if ($nominkeszlet && $termek->isInTermekKategoria($nominkeszletkat)) {
                                    if ($termek->getKeszlet() - $termek->getFoglaltMennyiseg() > 0) {
                                        $vankeszlet = true;
                                        break;
                                    }
                                } else {
                                    if ($termek->getKeszlet() - $termek->getFoglaltMennyiseg() - $termek->getMinboltikeszlet() > 0) {
                                        $vankeszlet = true;
                                        break;
                                    }
                                }
                            }
                        }
                    }
                    if ($vankeszlet) {
                        $ret[] = [
                            'id' => $fej->getId(),
                            'kelt' => $fej->getKeltStr(),
                            'hatarido' => $fej->getHataridoStr(),
                            'partnernev' => $fej->getPartnernev(),
                            'printurl' => \mkw\store::getRouter()->generate('adminmegrendelesfejprint', false, [], [
                                'id' => $fej->getId()
                            ]),
                            'editurl' => \mkw\store::getRouter()->generate('adminmegrendelesfejviewkarb', false, [], [
                                'id' => $fej->getId(),
                                'oper' => 'edit'
                            ])
                        ];
                    }
                }
            }
        }
        return $ret;
    }

    public function recalcPrice()
    {
        $ids = $this->params->getArrayRequestParam('ids');
        foreach ($ids as $id) {
            /** @var Bizonylatfej $megrendfej */
            $megrendfej = $this->getRepo()->find($id);
            if ($megrendfej) {
                $this->getEm()->beginTransaction();
                try {
                    /** @var Bizonylattetel $bt */
                    foreach ($megrendfej->getBizonylattetelek() as $bt) {
                        $bt->fillEgysar();
                        $bt->calc();
                        $this->getEm()->persist($bt);
                    }
                    $megrendfej->setNetto(0);
                    $this->getEm()->persist($megrendfej);
                    $this->getEm()->flush();
                    $this->getEm()->commit();
                } catch (\Exception $e) {
                    $this->getEm()->rollback();
                    throw $e;
                }
            }
        }
    }

    public function concat()
    {
        $filter = new \mkwhelpers\FilterDescriptor();
        $ids = $this->params->getArrayRequestParam('ids');
        \mkw\store::writelog('ORDER_CONCAT:START: ' . implode(',', $ids));
        if ($ids) {
            $filter->addFilter('id', 'IN', $ids);
        }
        $fejek = $this->getRepo()->getWithJoins($filter, []);
        $partnerek = [];
        $rendelesidk = [];
        /** @var Bizonylatfej $fej */
        foreach ($fejek as $fej) {
            $partnerek[$fej->getPartnerId()] = $fej->getPartnernev();
            $rendelesidk[] = $fej->getId();
        }
        if (count($partnerek) == 1) {
            $termekek = [];
            foreach ($fejek as $fej) {
                /** @var Bizonylattetel $tetel */
                foreach ($fej->getBizonylattetelek() as $tetel) {
                    $kulcs = $tetel->getTermekId() . '-' . $tetel->getTermekvaltozatId() . '-' . $tetel->getNettoegysar();
                    if (!isset($termekek[$kulcs])) {
                        $termekek[$kulcs] = [
                            'termekid' => $tetel->getTermekId(),
                            'termekvaltozatid' => $tetel->getTermekvaltozatId(),
                            'afaid' => $tetel->getAfaId(),
                            'vtszid' => $tetel->getVtszId(),
                            'nettoegysar' => $tetel->getNettoegysar(),
                            'nettoegysarhuf' => $tetel->getNettoegysarhuf(),
                            'mennyiseg' => $tetel->getMennyiseg(),
                            'enettoegysar' => $tetel->getEnettoegysar(),
                            'enettoegysarhuf' => $tetel->getEnettoegysarhuf(),
                            'kedvezmeny' => $tetel->getKedvezmeny(),
                        ];
                    } else {
                        $termekek[$kulcs]['mennyiseg'] += $tetel->getMennyiseg();
                    }
                }
            }
            \mkw\store::writelog('ORDER_CONCAT:transaction start');
            $this->getEm()->beginTransaction();
            try {
                $vantetel = false;
                $fej = $fejek[0];
                $ujfej = new Bizonylatfej();
                $ujfej->duplicateFrom($fej);
                $ujfej->clearId();
                $ujfej->setKelt('');
                $ujfej->setTeljesites('');
                $ujfej->setEsedekesseg('');
                $ujfej->setHatarido('');
                $ujfej->removeBizonylatstatusz();
                $ujfej->setBelsomegjegyzes(implode(', ', $rendelesidk));
                foreach ($termekek as $termek) {
                    $biztetel = new Bizonylattetel();
                    $ujfej->addBizonylattetel($biztetel);
                    $biztetel->setPersistentData();
                    $biztetel->setTermek($this->getRepo(Termek::class)->find($termek['termekid']));
                    $biztetel->setTermekvaltozat($this->getRepo(TermekValtozat::class)->find($termek['termekvaltozatid']));
                    $biztetel->setFoglal();
                    $biztetel->setVtsz($termek['vtszid']);
                    $biztetel->setAfa($termek['afaid']);
                    $biztetel->setMennyiseg($termek['mennyiseg']);

                    $biztetel->setEnettoegysar($termek['enettoegysar']);
                    $biztetel->setEnettoegysarhuf($termek['enettoegysarhuf']);
                    $biztetel->setKedvezmeny($termek['kedvezmeny']);
                    $biztetel->setNettoegysar($termek['nettoegysar']);
                    $biztetel->setNettoegysarhuf($termek['nettoegysarhuf']);
                    $biztetel->calc();
                    $this->getEm()->persist($biztetel);
                    $vantetel = true;
                }
                if ($vantetel) {
                    $ujfej->calcOsszesen();
                    $this->getEm()->persist($ujfej);
                    \mkw\store::writelog('ORDER_CONCAT:flush start');
                    $this->getEm()->flush();
                    \mkw\store::writelog('ORDER_CONCAT:flush stop ' . $ujfej->getId());
                }
                \mkw\store::writelog('ORDER_CONCAT:commit start');
                $this->getEm()->commit();
                \mkw\store::writelog('ORDER_CONCAT:commit stop');
            } catch (\Exception $e) {
                $this->getEm()->rollback();
                throw $e;
            }
        }
        \mkw\store::writelog('ORDER_CONCAT:STOP: ' . implode(',', $ids));
    }
}
