<?php

namespace Controllers;

use Entities\Partner;

class partnermergeController extends \mkwhelpers\MattableController {

    public function __construct($params) {
        $this->setEntityName('Entities\Partner');
        parent::__construct($params);
    }

    public function view() {
        $view = $this->createView('partnermerge.tpl');

        $view->setVar('partnerbol', date(\mkw\store::$DateFormat));
        $view->setVar('igdatum', date(\mkw\store::$DateFormat));
        $view->setVar('befdatum', date(\mkw\store::$DateFormat));
        $view->setVar('datumtipus', 'teljesites');

        $partner = new partnerController($this->params);
        $view->setVar('partnerlist', $partner->getSelectList());

        $view->printTemplateResult();
    }

    public function doIt() {
        $partnerrolid = $this->params->getIntRequestParam('partnerrol');
        $partnerreid = $this->params->getIntRequestParam('partnerre');
        if ($partnerreid != $partnerrolid) {
            /** @var Partner $partnerrol */
            $partnerrol = $this->getRepo('Entities\Partner')->find($partnerrolid);
            /** @var Partner $partnerre */
            $partnerre = $this->getRepo('Entities\Partner')->find($partnerreid);

            $conn = \mkw\store::getEm()->getConnection();

            $st = $conn->prepare('UPDATE bankbizonylatfej SET'
                . ' partner_id=' . $partnerreid
                . ', partnernev="' . $partnerre->getNev() . '"'
                . ', partnervezeteknev="' . $partnerre->getVezeteknev() . '"'
                . ', partnerkeresztnev="' . $partnerre->getKeresztnev() . '"'
                . ', partneradoszam="' . $partnerre->getAdoszam() . '"'
                . ', partnereuadoszam="' . $partnerre->getEuadoszam() . '"'
                . ', partnerirszam="' . $partnerre->getIrszam() . '"'
                . ', partnervaros="' . $partnerre->getVaros() . '"'
                . ', partnerutca="' . $partnerre->getUtca() . '"'
                . ' WHERE partner_id=' . $partnerrolid);
            $st->executeStatement();

            $st = $conn->prepare('UPDATE bankbizonylattetel SET'
                . ' partner_id=' . $partnerreid
                . ', partnernev="' . $partnerre->getNev() . '"'
                . ', partnervezeteknev="' . $partnerre->getVezeteknev() . '"'
                . ', partnerkeresztnev="' . $partnerre->getKeresztnev() . '"'
                . ', partneradoszam="' . $partnerre->getAdoszam() . '"'
                . ', partnereuadoszam="' . $partnerre->getEuadoszam() . '"'
                . ', partnerirszam="' . $partnerre->getIrszam() . '"'
                . ', partnervaros="' . $partnerre->getVaros() . '"'
                . ', partnerutca="' . $partnerre->getUtca() . '"'
                . ', partnerhazszam="' . $partnerre->getHazszam() . '"'
                . ' WHERE partner_id=' . $partnerrolid);
            $st->executeStatement();

            if ($this->params->getBoolRequestParam('bizupdate')) {
                $st = $conn->prepare('UPDATE bizonylatfej SET'
                    . ' partner_id=' . $partnerreid
                    . ', partnernev="' . $partnerre->getNev() . '"'
                    . ', partnervezeteknev="' . $partnerre->getVezeteknev() . '"'
                    . ', partnerkeresztnev="' . $partnerre->getKeresztnev() . '"'
                    . ', partnermukengszam="' . $partnerre->getMukengszam() . '"'
                    . ', partnerjovengszam="' . $partnerre->getJovengszam() . '"'
                    . ', partnerostermszam="' . $partnerre->getOstermszam() . '"'
                    . ', partnervalligszam="' . $partnerre->getValligszam() . '"'
                    . ', partnerfvmszam="' . $partnerre->getFvmszam() . '"'
                    . ', partnercjszam="' . $partnerre->getCjszam() . '"'
                    . ', partnerstatszamjel="' . $partnerre->getStatszamjel() . '"'
                    . ', partneradoszam="' . $partnerre->getAdoszam() . '"'
                    . ', partnereuadoszam="' . $partnerre->getEuadoszam() . '"'
                    . ', partnerirszam="' . $partnerre->getIrszam() . '"'
                    . ', partnervaros="' . $partnerre->getVaros() . '"'
                    . ', partnerutca="' . $partnerre->getUtca() . '"'
                    . ', partnerhazszam="' . $partnerre->getHazszam() . '"'
                    . ', partnerlirszam="' . $partnerre->getLirszam() . '"'
                    . ', partnerlvaros="' . $partnerre->getLvaros() . '"'
                    . ', partnerlutca="' . $partnerre->getLutca() . '"'
                    . ', partnerlhazszam="' . $partnerre->getLhazszam() . '"'
                    . ', partneremail="' . $partnerre->getEmail() . '"'
                    . ', partnertelefon="' . $partnerre->getTelefon() . '"'
                    . ', partnerktdszerzszam="' . $partnerre->getKtdszerzszam() . '"'
                    . ', partnerktdatvallal=' . ($partnerre->getKtdatvallal() ? '1' : '0')
                    . ', partnerktdatalany=' . ($partnerre->getKtdatalany() ? '1' : '0')
                    . ', partnerorszag_id=' . $partnerre->getOrszagId()
                    . ', partnerorszagnev="' . $partnerre->getOrszagNev() . '"'
                    . ', partnerorszagiso3166="' . ($partnerre->getOrszag() ? $partnerre->getOrszag()->getIso3166() : '') . '"'
                    . ', szallnev="' . $partnerre->getSzallnev() . '"'
                    . ', szallirszam="' . $partnerre->getSzallirszam() . '"'
                    . ', szallvaros="' . $partnerre->getSzallvaros() . '"'
                    . ', szallutca="' . $partnerre->getSzallutca() . '"'
                    . ', szallhazszam="' . $partnerre->getSzallhazszam() . '"'
                    . ' WHERE (partner_id=' . $partnerrolid . ') AND (bizonylattipus_id<>"szamla") AND (bizonylattipus_id<>"esetiszamla")');
                $st->executeStatement();
            }
            else {
                $st = $conn->prepare('UPDATE bizonylatfej SET'
                    . ' partner_id=' . $partnerreid
                    . ' WHERE partner_id=' . $partnerrolid);
                $st->executeStatement();
            }

            $st = $conn->prepare('UPDATE bizonylattetel SET'
                . ' mijszpartner_id=' . $partnerreid
                . ', mijszpartnernev="' . $partnerre->getNev() . '"'
                . ' WHERE mijszpartner_id=' . $partnerrolid);
            $st->executeStatement();

            $st = $conn->prepare('UPDATE cimketorzs SET'
                . ' gyarto_id=' . $partnerreid
                . ' WHERE gyarto_id=' . $partnerrolid);
            $st->executeStatement();

            $st = $conn->prepare('UPDATE dokumentumtar SET'
                . ' partner_id=' . $partnerreid
                . ' WHERE partner_id=' . $partnerrolid);
            $st->executeStatement();

            $st = $conn->prepare('UPDATE folyoszamla SET'
                . ' partner_id=' . $partnerreid
                . ' WHERE partner_id=' . $partnerrolid);
            $st->executeStatement();

            $st = $conn->prepare('UPDATE jogaberlet SET'
                . ' partner_id=' . $partnerreid
                . ' WHERE partner_id=' . $partnerrolid);
            $st->executeStatement();

            $st = $conn->prepare('UPDATE jogareszvetel SET'
                . ' partner_id=' . $partnerreid
                . ', partnernev="' . $partnerre->getNev() . '"'
                . ', partnervezeteknev="' . $partnerre->getVezeteknev() . '"'
                . ', partnerkeresztnev="' . $partnerre->getKeresztnev() . '"'
                . ', partneremail="' . $partnerre->getEmail() . '"'
                . ' WHERE partner_id=' . $partnerrolid);
            $st->executeStatement();

            $st = $conn->prepare('UPDATE kontakt SET'
                . ' partner_id=' . $partnerreid
                . ' WHERE partner_id=' . $partnerrolid);
            $st->executeStatement();

            $st = $conn->prepare('UPDATE kosar SET'
                . ' partner_id=' . $partnerreid
                . ' WHERE partner_id=' . $partnerrolid);
            $st->executeStatement();

            $st = $conn->prepare('UPDATE partner_cimkek SET'
                . ' partner_id=' . $partnerreid
                . ' WHERE partner_id=' . $partnerrolid);
            $st->executeStatement();

            $st = $conn->prepare('UPDATE partnermijszoklevel SET'
                . ' partner_id=' . $partnerreid
                . ' WHERE partner_id=' . $partnerrolid);
            $st->executeStatement();

            $st = $conn->prepare('UPDATE partnermijszoralatogatas SET'
                . ' partner_id=' . $partnerreid
                . ' WHERE partner_id=' . $partnerrolid);
            $st->executeStatement();

            $st = $conn->prepare('UPDATE partnermijszpune SET'
                . ' partner_id=' . $partnerreid
                . ' WHERE partner_id=' . $partnerrolid);
            $st->executeStatement();

            $st = $conn->prepare('UPDATE partnermijsztanitas SET'
                . ' partner_id=' . $partnerreid
                . ' WHERE partner_id=' . $partnerrolid);
            $st->executeStatement();

            $st = $conn->prepare('UPDATE partnertermekcsoportkedvezmeny SET'
                . ' partner_id=' . $partnerreid
                . ' WHERE partner_id=' . $partnerrolid);
            $st->executeStatement();

            $st = $conn->prepare('UPDATE partnertermekcsoportszerzodes SET'
                . ' partner_id=' . $partnerreid
                . ' WHERE partner_id=' . $partnerrolid);
            $st->executeStatement();

            $st = $conn->prepare('UPDATE partnertermekkedvezmeny SET'
                . ' partner_id=' . $partnerreid
                . ' WHERE partner_id=' . $partnerrolid);
            $st->executeStatement();

            $st = $conn->prepare('UPDATE partnertermekszerzodes SET'
                . ' partner_id=' . $partnerreid
                . ' WHERE partner_id=' . $partnerrolid);
            $st->executeStatement();

            $st = $conn->prepare('UPDATE penztarbizonylatfej SET'
                . ' partner_id=' . $partnerreid
                . ', partnernev="' . $partnerre->getNev() . '"'
                . ', partnervezeteknev="' . $partnerre->getVezeteknev() . '"'
                . ', partnerkeresztnev="' . $partnerre->getKeresztnev() . '"'
                . ', partneradoszam="' . $partnerre->getAdoszam() . '"'
                . ', partnereuadoszam="' . $partnerre->getEuadoszam() . '"'
                . ', partnerirszam="' . $partnerre->getIrszam() . '"'
                . ', partnervaros="' . $partnerre->getVaros() . '"'
                . ', partnerutca="' . $partnerre->getUtca() . '"'
                . ', partnerhazszam="' . $partnerre->getHazszam() . '"'
                . ' WHERE partner_id=' . $partnerrolid);
            $st->executeStatement();

            $st = $conn->prepare('UPDATE rendezvenyjelentkezes SET'
                . ' partner_id=' . $partnerreid
                . ', partnernev="' . $partnerre->getNev() . '"'
                . ', partneremail="' . $partnerre->getEmail() . '"'
                . ', partnertelefon="' . $partnerre->getTelefon() . '"'
                . ' WHERE partner_id=' . $partnerrolid);
            $st->executeStatement();

            $st = $conn->prepare('UPDATE termek SET'
                . ' gyarto_id=' . $partnerreid
                . ' WHERE gyarto_id=' . $partnerrolid);
            $st->executeStatement();

            $st = $conn->prepare('UPDATE termekertesito SET'
                . ' partner_id=' . $partnerreid
                . ' WHERE partner_id=' . $partnerrolid);
            $st->executeStatement();

            if ($this->params->getBoolRequestParam('nevcsere')) {
                if ($partnerrol->getNev()) {
                    $partnerre->setNev($partnerrol->getNev());
                }
                if ($partnerrol->getVezeteknev()) {
                    $partnerre->setVezeteknev($partnerrol->getVezeteknev());
                }
                if ($partnerrol->getKeresztnev()) {
                    $partnerre->setKeresztnev($partnerrol->getKeresztnev());
                }
            }
            if ($this->params->getBoolRequestParam('cimcsere')) {
                if ($partnerrol->getIrszam()) {
                    $partnerre->setIrszam($partnerrol->getIrszam());
                }
                if ($partnerrol->getVaros()) {
                    $partnerre->setVaros($partnerrol->getVaros());
                }
                if ($partnerrol->getUtca()) {
                    $partnerre->setUtca($partnerrol->getUtca());
                }
                if ($partnerrol->getHazszam()) {
                    $partnerre->setHazszam($partnerrol->getHazszam());
                }
            }
            if ($this->params->getBoolRequestParam('emailcsere')) {
                if ($partnerrol->getEmail() && strpos($partnerrol->getEmail(), 'mail.local') === false) {
                    $partnerre->setEmail($partnerrol->getEmail());
                }
            }

            $this->getEm()->persist($partnerre);
            if ($this->params->getBoolRequestParam('roldel')) {
                $this->getEm()->remove($partnerrol);
            }
            $this->getEm()->flush();
        }
    }

}