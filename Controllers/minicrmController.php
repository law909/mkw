<?php

namespace Controllers;

class minicrmController extends \mkwhelpers\Controller {

    public function view() {
        $view = $this->createView('minicrm.tpl');

        $view->setVar('pagetitle', t('MiniCRM'));

        $view->printTemplateResult(false);
    }

    public function partnerImport() {
        if (\mkw\store::isMiniCRMOn()) {

            $voltkapcsolat = false;
            $vanlog = false;

            @unlink(\mkw\store::storagePath('minicrmimport.txt'));

            require 'busvendor/MiniCRM/minicrm-api.phar';
            $catid = \mkw\store::getParameter(\mkw\consts::MiniCRMPartnertorzs);
            $minicrm = new \MiniCRM_Connection(\mkw\store::getParameter(\mkw\consts::MiniCRMSystemId), \mkw\store::getParameter(\mkw\consts::MiniCRMAPIKey));

            $updatedsince = \mkw\store::getParameter(\mkw\consts::MiniCRMLastPartnerImport, '2015-01-01+12:00:00');

            $page = 0;
            do {
                $res = \MiniCRM_Project::FieldSearch($minicrm,
                    array(
                        'UpdatedSince' => $updatedsince,
                        'CategoryId' => $catid,
                        'Page' => $page
                    )
                );
                if ($res) {
                    $voltkapcsolat = true;
                    $tomb = $res['Results'];
                    foreach($tomb as $adat) {
                        if (!$adat['Deleted']) {
                            $aid = $adat['Id'];
                            $cid = $adat['ContactId'];

                            //$adatlap = new \MiniCRM_Project($minicrm, $aid);
                            $kontakt = new \MiniCRM_Contact($minicrm, $cid);
                            $addrlist = \MiniCRM_Address::AddressList($minicrm, $cid);
                            if ($addrlist['Count'] > 0) {
                                $addr = new \MiniCRM_Address($minicrm, current(array_keys($addrlist['Results'])));
                            }
                            else {
                                $addr = false;
                            }

                            $partner = $this->getRepo('Entities\Partner')->findOneBy(array('minicrmprojectid' => $aid, 'minicrmcontactid' => $cid));
                            $megvan = !is_null($partner);
                            if (!$megvan) {
                                if ($kontakt->Email) {
                                    $partner = $this->getRepo('Entities\Partner')->findOneBy(array('email' => $kontakt->Email));
                                    $megvan = !is_null($partner);
                                    if (!$megvan) {
                                        $partner = new \Entities\Partner();
                                        $partner->setEmail($kontakt->Email);
                                        $partner->setNev($kontakt->LastName . ' ' . $kontakt->FirstName);
                                        $partner->setVezeteknev($kontakt->LastName);
                                        $partner->setKeresztnev($kontakt->FirstName);
                                        $partner->setMobil($kontakt->Phone);
                                        $partner->setHonlap($kontakt->Url);
                                        $partner->setMinicrmcontactid($cid);
                                        $partner->setMinicrmprojectid($aid);
                                        if ($kontakt->Neme === 'Férfi') {
                                            $partner->setNem(1);
                                        }
                                        else {
                                            $partner->setNem(2);
                                        }

                                        if ($addr) {
                                            $partner->setIrszam($addr->PostalCode);
                                            $partner->setVaros($addr->City);
                                            $partner->setUtca($addr->Address);
                                        }

                                        if (!$partner->getIrszam()) {
                                            $partner->setIrszam('1011');
                                        }
                                        if (!$partner->getVaros()) {
                                            $partner->setVaros('Budapest');
                                        }

                                        $this->getEm()->persist($partner);
                                        $this->getEm()->flush();

                                        \mkw\store::writelog('NEW - ' . $kontakt->Email . ' ' . $kontakt->LastName . ' ' . $kontakt->FirstName .
                                            ' projectid: ' . $aid . ' kontaktid: ' . $cid,
                                            'minicrmimport.txt');
                                        $vanlog = true;
                                    }
                                    else {
                                        $partner->setMinicrmcontactid($cid);
                                        $partner->setMinicrmprojectid($aid);
                                        if ($kontakt->Neme === 'Férfi') {
                                            $partner->setNem(1);
                                        }
                                        else {
                                            $partner->setNem(2);
                                        }
                                        $this->getEm()->persist($partner);
                                        $this->getEm()->flush();

                                        \mkw\store::writelog('EDIT - ' . $kontakt->Email . ' ' . $kontakt->LastName . ' ' . $kontakt->FirstName .
                                            ' projectid: ' . $aid . ' kontaktid: ' . $cid . ' BILLY id: ' . $partner->getId(),
                                            'minicrmimport.txt');
                                        $vanlog = true;
                                    }
                                }
                                else {
                                    \mkw\store::writelog('NINCS MEG CRM IDVEL, NINCS EMAIL, KIMARAD - ' . $kontakt->LastName . ' ' . $kontakt->FirstName .
                                        ' projectid: ' . $aid . ' kontaktid: ' . $cid,
                                        'minicrmimport.txt');
                                    $vanlog = true;
                                }
                            }
                        }
                    }
                }
                $page++;
            } while ($res['Results']);

            if ($voltkapcsolat) {
                \mkw\store::setParameter(\mkw\consts::MiniCRMLastPartnerImport, date(\mkw\store::$MiniCRMDateTimeFormat));
            }

            if ($vanlog) {
                echo json_encode(array('url' => \mkw\store::getFullUrl(null, \mkw\store::storagePath('minicrmimport.txt'))));
            }
        }
    }

}