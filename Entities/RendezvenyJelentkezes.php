<?php

namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\RendezvenyJelentkezesRepository")
 * @ORM\Table(name="rendezvenyjelentkezes",
 * options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"}
 * )
 */
class RendezvenyJelentkezes {

    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id = 0;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $created;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $lastmod;

    /**
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="Dolgozo")
     * @ORM\JoinColumn(name="createdby", referencedColumnName="id")
     */
    private $createdby;

    /**
     * @Gedmo\Blameable(on="update")
     * @ORM\ManyToOne(targetEntity="Dolgozo")
     * @ORM\JoinColumn(name="updatedby", referencedColumnName="id")
     */
    private $updatedby;

    /** @ORM\Column(type="date",nullable=true) */
    private $datum;

    /**
     * @ORM\ManyToOne(targetEntity="Rendezveny")
     * @ORM\JoinColumn(name="rendezveny_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Rendezveny
     */
    private $rendezveny;

    /**
     * @ORM\ManyToOne(targetEntity="Partner")
     * @ORM\JoinColumn(name="partner_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Partner
     */
    private $partner;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $partnernev;

    /** @ORM\Column(type="string",length=100,nullable=true) */
    private $partneremail = '';

    /**
     * @ORM\ManyToOne(targetEntity="Fizmod")
     * @ORM\JoinColumn(name="fizmod_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Fizmod
     */
    private $fizmod;

    /** @ORM\Column(type="text",nullable=true) */
    private $megjegyzes;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $fizetve = false;

    /** @ORM\Column(type="date",nullable=true) */
    private $fizetesdatum;

    /**
     * @Gedmo\Blameable(on="change", field={"fizetve"})
     * @ORM\ManyToOne(targetEntity="Dolgozo")
     * @ORM\JoinColumn(name="fizetesbejegyzo", referencedColumnName="id")
     */
    private $fizetesbejegyzo;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $fizetveosszeghuf;

    /** @ORM\Column(type="string",length=30,nullable=true) */
    private $fizetvebankbizonylatszam;

    /** @ORM\Column(type="integer",nullable=true) */
    private $fizetvebanktetelid;

    /**
     * @ORM\ManyToOne(targetEntity="Bankszamla")
     * @ORM\JoinColumn(name="fizetvebankszamla_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Bankszamla
     */
    private $fizetvebankszamla;

    /** @ORM\Column(type="string",length=30,nullable=true) */
    private $fizetvepenztarbizonylatszam;

    /** @ORM\Column(type="integer",nullable=true) */
    private $fizetvepenztartetelid;

    /**
     * @ORM\ManyToOne(targetEntity="Penztar")
     * @ORM\JoinColumn(name="fizetvepenztar_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Penztar
     */
    private $fizetvepenztar;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $szamlazva = false;

    /** @ORM\Column(type="date",nullable=true) */
    private $szamlazasdatum;

    /**
     * @Gedmo\Blameable(on="change", field={"szamlazva"})
     * @ORM\ManyToOne(targetEntity="Dolgozo")
     * @ORM\JoinColumn(name="szamlazasbejegyzo", referencedColumnName="id")
     */
    private $szamlazasbejegyzo;

    /** @ORM\Column(type="string",length=30,nullable=true) */
    private $szamlaszam;

    /** @ORM\Column(type="string",length=30,nullable=true) */
    private $szamlazvabizonylattipus;

    /** @ORM\Column(type="date",nullable=true) */
    private $szamlazvakelt;

    /** @ORM\Column(type="date",nullable=true) */
    private $szamlazvateljesites;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $szamlazvaosszeghuf;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $lemondva = false;

    /** @ORM\Column(type="date",nullable=true) */
    private $lemondasdatum;

    /**
     * @Gedmo\Blameable(on="change", field={"lemondva"})
     * @ORM\ManyToOne(targetEntity="Dolgozo")
     * @ORM\JoinColumn(name="lemondasbejegyzo", referencedColumnName="id")
     */
    private $lemondasbejegyzo;

    /** @ORM\Column(type="text",nullable=true) */
    private $lemondasoka;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $visszautalva = false;

    /** @ORM\Column(type="date",nullable=true) */
    private $visszautalasdatum;

    /**
     * @ORM\ManyToOne(targetEntity="Fizmod")
     * @ORM\JoinColumn(name="visszautalasfizmod_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Fizmod
     */
    private $visszautalasfizmod;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $visszautalasosszeghuf;

    /** @ORM\Column(type="string",length=30,nullable=true) */
    private $visszautalasbankbizonylatszam;

    /**
     * @ORM\ManyToOne(targetEntity="Bankszamla")
     * @ORM\JoinColumn(name="visszautalasbankszamla_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Bankszamla
     */
    private $visszautalasbankszamla;

    /** @ORM\Column(type="string",length=30,nullable=true) */
    private $visszautalaspenztarbizonylatszam;

    /**
     * @ORM\ManyToOne(targetEntity="Penztar")
     * @ORM\JoinColumn(name="penztar_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Penztar
     */
    private $visszautalaspenztar;

    /**
     * @Gedmo\Blameable(on="change", field={"visszautalva"})
     * @ORM\ManyToOne(targetEntity="Dolgozo")
     * @ORM\JoinColumn(name="visszautalasbejegyzo", referencedColumnName="id")
     */
    private $visszautalasbejegyzo;

    /** @ORM\Column(type="integer", nullable=true) */
    private $minicrmprojectid;

    /** @ORM\Column(type="integer", nullable=true) */
    private $minicrmcontactid;

    public function getId() {
        return $this->id;
    }

    public function getDatum() {
        return $this->datum;
    }

    public function getDatumStr() {
        if ($this->getDatum()) {
            return $this->getDatum()->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    public function setDatum($adat = '') {
        if ($adat != '') {
            $this->datum = new \DateTime(\mkw\store::convDate($adat));
        }
        else{
            $this->datum = null;
        }
    }

    /**
     * @return mixed
     */
    public function getCreatedby() {
        return $this->createdby;
    }

    public function getCreatedbyId() {
        if ($this->createdby) {
            return $this->createdby->getId();
        }
        return null;
    }

    public function getCreatedbyNev() {
        if ($this->createdby) {
            return $this->createdby->getNev();
        }
        return null;
    }

    /**
     * @return mixed
     */
    public function getUpdatedby() {
        return $this->updatedby;
    }

    public function getUpdatedbyId() {
        if ($this->updatedby) {
            return $this->updatedby->getId();
        }
        return null;
    }

    public function getUpdatedbyNev() {
        if ($this->updatedby) {
            return $this->updatedby->getNev();
        }
        return null;
    }

    public function getLastmod() {
        return $this->lastmod;
    }

    public function getLastmodStr() {
        if ($this->getLastmod()) {
            return $this->getLastmod()->format(\mkw\store::$DateTimeFormat);
        }
        return '';
    }

    public function clearLastmod() {
        $this->lastmod = null;
    }

    public function getCreated() {
        return $this->created;
    }

    public function getCreatedStr() {
        if ($this->getCreated()) {
            return $this->getCreated()->format(\mkw\store::$DateTimeFormat);
        }
        return '';
    }

    public function clearCreated() {
        $this->created = null;
    }

    /**
     * @return mixed
     */
    public function getMegjegyzes() {
        return $this->megjegyzes;
    }

    /**
     * @param mixed $megjegyzes
     */
    public function setMegjegyzes($megjegyzes) {
        $this->megjegyzes = $megjegyzes;
    }

    /**
     * @return mixed
     */
    public function getFizetve() {
        return $this->fizetve;
    }

    /**
     * @param mixed $fizetve
     */
    public function setFizetve($fizetve) {
        $this->fizetve = $fizetve;
    }

    /**
     * @return mixed
     */
    public function getSzamlazva() {
        return $this->szamlazva;
    }

    /**
     * @param mixed $szamlazva
     */
    public function setSzamlazva($szamlazva) {
        $this->szamlazva = $szamlazva;
    }

    /**
     * @return mixed
     */
    public function getSzamlaszam() {
        return $this->szamlaszam;
    }

    /**
     * @param mixed $szamlaszam
     */
    public function setSzamlaszam($szamlaszam) {
        $this->szamlaszam = $szamlaszam;
    }

    /**
     * @return mixed
     */
    public function getLemondva() {
        return $this->lemondva;
    }

    /**
     * @param mixed $lemondva
     */
    public function setLemondva($lemondva) {
        $this->lemondva = $lemondva;
    }

    /**
     * @return mixed
     */
    public function getVisszautalva() {
        return $this->visszautalva;
    }

    /**
     * @param mixed $visszautalva
     */
    public function setVisszautalva($visszautalva) {
        $this->visszautalva = $visszautalva;
    }

    /**
     * @return \Entities\Partner
     */
    public function getPartner() {
        return $this->partner;
    }

    public function getPartnerId() {
        if ($this->partner) {
            return $this->partner->getId();
        }
        return '';
    }

    /**
     * @param \Entities\Partner $val
     */
    public function setPartner($val) {
        if ($this->partner !== $val) {
            if (!$val) {
                $this->removePartner();
            }
            else {
                $this->partner = $val;
                $this->partnernev = $val->getNev();
                $this->partneremail = $val->getEmail();
            }
        }
    }

    public function removePartner() {
        if ($this->partner !== null) {
            $this->partner = null;
            $this->partnernev = null;
            $this->partneremail = null;
        }
    }

    public function getPartnernev() {
        return $this->partnernev;
    }

    public function getPartneremail() {
        return $this->partneremail;
    }

    public function getPartnerCim() {
        if ($this->getPartner()) {
            return $this->getPartner()->getCim();
        }
        return null;
    }

    /**
     * @return \Entities\Fizmod
     */
    public function getFizmod() {
        return $this->fizmod;
    }

    public function getFizmodNev() {
        if ($this->getFizmod()) {
            return $this->getFizmod()->getNev();
        }
        return null;
    }

    public function getFizmodId() {
        $fm = $this->getFizmod();
        if ($fm) {
            return $fm->getId();
        }
        return null;
    }

    /**
     * @param \Entities\Fizmod $val
     */
    public function setFizmod($val) {
        if (!($val instanceof \Entities\Fizmod)) {
            $val = \mkw\store::getEm()->getRepository('Entities\Fizmod')->find($val);
        }
        if ($this->fizmod !== $val) {
            if (!$val) {
                $this->removeFizmod();
            }
            else {
                $this->fizmod = $val;
            }
        }
    }

    public function removeFizmod() {
        if ($this->fizmod !== null) {
            $this->fizmod = null;
        }
    }

    public function getFizetesdatum() {
        return $this->fizetesdatum;
    }

    public function getFizetesdatumStr() {
        if ($this->getFizetesdatum()) {
            return $this->getFizetesdatum()->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    public function setFizetesdatum($adat = '') {
        if ($adat != '') {
            $this->fizetesdatum = new \DateTime(\mkw\store::convDate($adat));
        }
        else{
            $this->fizetesdatum = null;
        }
    }

    public function getSzamlazasdatum() {
        return $this->szamlazasdatum;
    }

    public function getSzamlazasdatumStr() {
        if ($this->getSzamlazasdatum()) {
            return $this->getSzamlazasdatum()->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    public function setSzamlazasdatum($adat = '') {
        if ($adat == '') {
            $adat = date(\mkw\store::$DateFormat);
        }
        $this->szamlazasdatum = new \DateTime(\mkw\store::convDate($adat));
    }

    public function getLemondasdatum() {
        return $this->lemondasdatum;
    }

    public function getLemondasdatumStr() {
        if ($this->getLemondasdatum()) {
            return $this->getLemondasdatum()->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    public function setLemondasdatum($adat = '') {
        if ($adat == '') {
            $adat = date(\mkw\store::$DateFormat);
        }
        $this->lemondasdatum = new \DateTime(\mkw\store::convDate($adat));
    }

    public function getVisszautalasdatum() {
        return $this->visszautalasdatum;
    }

    public function getVisszautalasdatumStr() {
        if ($this->getVisszautalasdatum()) {
            return $this->getVisszautalasdatum()->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    public function setVisszautalasdatum($adat = '') {
        if ($adat == '') {
            $adat = date(\mkw\store::$DateFormat);
        }
        $this->visszautalasdatum = new \DateTime(\mkw\store::convDate($adat));
    }

    /**
     * @return mixed
     */
    public function getFizetesbejegyzo() {
        return $this->fizetesbejegyzo;
    }

    public function getFizetesbejegyzoId() {
        if ($this->fizetesbejegyzo) {
            return $this->fizetesbejegyzo->getId();
        }
        return null;
    }

    public function getFizetesbejegyzoNev() {
        if ($this->fizetesbejegyzo) {
            return $this->fizetesbejegyzo->getNev();
        }
        return null;
    }

    /**
     * @return mixed
     */
    public function getSzamlazasbejegyzo() {
        return $this->szamlazasbejegyzo;
    }

    public function getSzamlazasbejegyzoId() {
        if ($this->szamlazasbejegyzo) {
            return $this->szamlazasbejegyzo->getId();
        }
        return null;
    }

    public function getSzamlazasbejegyzoNev() {
        if ($this->szamlazasbejegyzo) {
            return $this->szamlazasbejegyzo->getNev();
        }
        return null;
    }

    /**
     * @return mixed
     */
    public function getLemondasbejegyzo() {
        return $this->lemondasbejegyzo;
    }

    public function getLemondasbejegyzoId() {
        if ($this->lemondasbejegyzo) {
            return $this->lemondasbejegyzo->getId();
        }
        return null;
    }

    public function getLemondasbejegyzoNev() {
        if ($this->lemondasbejegyzo) {
            return $this->lemondasbejegyzo->getNev();
        }
        return null;
    }

    /**
     * @return mixed
     */
    public function getVisszautalasbejegyzo() {
        return $this->visszautalasbejegyzo;
    }

    public function getVisszautalasbejegyzoId() {
        if ($this->visszautalasbejegyzo) {
            return $this->visszautalasbejegyzo->getId();
        }
        return null;
    }

    public function getVisszautalasbejegyzoNev() {
        if ($this->visszautalasbejegyzo) {
            return $this->visszautalasbejegyzo->getNev();
        }
        return null;
    }

    /**
     * @param \Entities\Rendezveny $val
     */
    public function setRendezveny($val) {
        if ($this->rendezveny !== $val) {
            if (!$val) {
                $this->removeRendezveny();
            }
            else {
                $this->rendezveny = $val;
            }
        }
    }

    /**
     * @return Rendezveny
     */
    public function getRendezveny() {
        return $this->rendezveny;
    }

    public function removeRendezveny() {
        if ($this->rendezveny !== null) {
            $this->rendezveny = null;
        }
    }

    public function getRendezvenyId() {
        if ($this->getRendezveny()) {
            return $this->getRendezveny()->getId();
        }
        return null;
    }

    public function getRendezvenyNev() {
        if ($this->getRendezveny()) {
            return $this->getRendezveny()->getNev();
        }
        return null;
    }

    public function getRendezvenyTeljesNev() {
        if ($this->getRendezveny()) {
            return $this->getRendezveny()->getTeljesNev();
        }
        return null;
    }

    public function getRendezvenyDatumStr() {
        if ($this->getRendezveny()) {
            return $this->getRendezveny()->getKezdodatumStr();
        }
        return null;
    }

    public function getRendezvenyTanarNev() {
        if ($this->getRendezveny()) {
            return $this->getRendezveny()->getTanarNev();
        }
        return null;
    }

    /**
     * @return mixed
     */
    public function getFizetveosszeghuf() {
        return $this->fizetveosszeghuf;
    }

    /**
     * @param mixed $fizetveosszeghuf
     */
    public function setFizetveosszeghuf($fizetveosszeghuf) {
        $this->fizetveosszeghuf = $fizetveosszeghuf;
    }

    /**
     * @return mixed
     */
    public function getFizetvebankbizonylatszam() {
        return $this->fizetvebankbizonylatszam;
    }

    /**
     * @param mixed $fizetvebankbizonylatszam
     */
    public function setFizetvebankbizonylatszam($fizetvebankbizonylatszam) {
        $this->fizetvebankbizonylatszam = $fizetvebankbizonylatszam;
    }

    /**
     * @return mixed
     */
    public function getFizetvepenztarbizonylatszam() {
        return $this->fizetvepenztarbizonylatszam;
    }

    /**
     * @param mixed $fizetvepenztarbizonylatszam
     */
    public function setFizetvepenztarbizonylatszam($fizetvepenztarbizonylatszam) {
        $this->fizetvepenztarbizonylatszam = $fizetvepenztarbizonylatszam;
    }

    /**
     * @return mixed
     */
    public function getSzamlazvabizonylattipus() {
        return $this->szamlazvabizonylattipus;
    }

    public function getSzamlazvabizonylattipusNev() {
        /** @var \Entities\Bizonylattipus $bt */
        $bt = \mkw\store::getEm()->getRepository('\Entities\Bizonylattipus')->find($this->szamlazvabizonylattipus);
        if ($bt) {
            return $bt->getNev();
        }
        return null;
    }

    /**
     * @param mixed $szamlazvabizonylattipus
     */
    public function setSzamlazvabizonylattipus($szamlazvabizonylattipus) {
        $this->szamlazvabizonylattipus = $szamlazvabizonylattipus;
    }

    /**
     * @return mixed
     */
    public function getLemondasoka() {
        return $this->lemondasoka;
    }

    /**
     * @param mixed $lemondasoka
     */
    public function setLemondasoka($lemondasoka) {
        $this->lemondasoka = $lemondasoka;
    }

    /**
     * @return mixed
     */
    public function getVisszautalasosszeghuf() {
        return $this->visszautalasosszeghuf;
    }

    /**
     * @param mixed $visszautalasosszeghuf
     */
    public function setVisszautalasosszeghuf($visszautalasosszeghuf) {
        $this->visszautalasosszeghuf = $visszautalasosszeghuf;
    }

    /**
     * @return mixed
     */
    public function getVisszautalasbankbizonylatszam() {
        return $this->visszautalasbankbizonylatszam;
    }

    /**
     * @param mixed $visszautalasbankbizonylatszam
     */
    public function setVisszautalasbankbizonylatszam($visszautalasbankbizonylatszam) {
        $this->visszautalasbankbizonylatszam = $visszautalasbankbizonylatszam;
    }

    /**
     * @return mixed
     */
    public function getVisszautalaspenztarbizonylatszam() {
        return $this->visszautalaspenztarbizonylatszam;
    }

    /**
     * @param mixed $visszautalaspenztarbizonylatszam
     */
    public function setVisszautalaspenztarbizonylatszam($visszautalaspenztarbizonylatszam) {
        $this->visszautalaspenztarbizonylatszam = $visszautalaspenztarbizonylatszam;
    }

    /**
     * @return \Entities\Penztar
     */
    public function getFizetvepenztar() {
        return $this->fizetvepenztar;
    }

    public function getFizetvepenztarId() {
        if ($this->fizetvepenztar) {
            return $this->fizetvepenztar->getId();
        }
        return null;
    }

    /**
     * @param \Entities\Penztar|null $val
     */
    public function setFizetvepenztar($val = null) {
        if ($this->fizetvepenztar !== $val) {
            if (!$val) {
                $this->removeFizetvepenztar();
            }
            else {
                $this->fizetvepenztar = $val;
            }
        }
    }

    public function removeFizetvepenztar() {
        if ($this->fizetvepenztar !== null) {
            $this->fizetvepenztar = null;
        }
    }

    public function getFizetvepenztarNev() {
        if ($this->getFizetvepenztar()) {
            return $this->getFizetvepenztar()->getNev();
        }
        return null;
    }

    /**
     * @return \Entities\Bankszamla
     */
    public function getFizetvebankszamla() {
        return $this->fizetvebankszamla;
    }

    public function getFizetvebankszamlaId() {
        if ($this->fizetvebankszamla) {
            return $this->fizetvebankszamla->getId();
        }
        return '';
    }

    public function getFizetvebankszamlaSzam() {
        if ($this->getFizetvebankszamla()) {
            return $this->getFizetvebankszamla()->getSzamlaszam();
        }
        return '';
    }

    /**
     * @param \Entities\Bankszamla|null $val
     */
    public function setFizetvebankszamla($val = null) {
        if ($this->fizetvebankszamla !== $val) {
            if (!$val) {
                $this->removeFizetvebankszamla();
            }
            else {
                $this->fizetvebankszamla = $val;
            }
        }
    }

    public function removeFizetvebankszamla() {
        if ($this->fizetvebankszamla !== null) {
            $this->fizetvebankszamla = null;
        }
    }

    /**
     * @return \Entities\Penztar
     */
    public function getVisszautalaspenztar() {
        return $this->visszautalaspenztar;
    }

    public function getVisszautalaspenztarId() {
        if ($this->visszautalaspenztar) {
            return $this->visszautalaspenztar->getId();
        }
        return null;
    }

    /**
     * @param \Entities\Penztar|null $val
     */
    public function setVisszautalaspenztar($val = null) {
        if ($this->visszautalaspenztar !== $val) {
            if (!$val) {
                $this->removeVisszautalaspenztar();
            }
            else {
                $this->visszautalaspenztar = $val;
            }
        }
    }

    public function removeVisszautalaspenztar() {
        if ($this->visszautalaspenztar !== null) {
            $this->visszautalaspenztar = null;
        }
    }

    public function getVisszautalaspenztarNev() {
        if ($this->getVisszautalaspenztar()) {
            return $this->getVisszautalaspenztar()->getNev();
        }
        return null;
    }

    /**
     * @return \Entities\Bankszamla
     */
    public function getVisszautalasbankszamla() {
        return $this->visszautalasbankszamla;
    }

    public function getVisszautalasbankszamlaId() {
        if ($this->visszautalasbankszamla) {
            return $this->visszautalasbankszamla->getId();
        }
        return '';
    }

    public function getVisszautalasbankszamlaSzam() {
        if ($this->getVisszautalasbankszamla()) {
            return $this->getVisszautalasbankszamla()->getSzamlaszam();
        }
        return '';
    }

    /**
     * @param \Entities\Bankszamla|null $val
     */
    public function setVisszautalasbankszamla($val = null) {
        if ($this->visszautalasbankszamla !== $val) {
            if (!$val) {
                $this->removeVisszautalasbankszamla();
            }
            else {
                $this->visszautalasbankszamla = $val;
            }
        }
    }

    public function removeVisszautalasbankszamla() {
        if ($this->visszautalasbankszamla !== null) {
            $this->visszautalasbankszamla = null;
        }
    }

    /**
     * @return \Entities\Fizmod
     */
    public function getVisszautalasfizmod() {
        return $this->visszautalasfizmod;
    }

    public function getVisszautalasfizmodNev() {
        if ($this->getVisszautalasfizmod()) {
            return $this->getVisszautalasfizmod()->getNev();
        }
        return null;
    }

    public function getVisszautalasfizmodId() {
        $fm = $this->getVisszautalasfizmod();
        if ($fm) {
            return $fm->getId();
        }
        return null;
    }

    /**
     * @param \Entities\Fizmod $val
     */
    public function setVisszautalasfizmod($val) {
        if (!($val instanceof \Entities\Fizmod)) {
            $val = \mkw\store::getEm()->getRepository('Entities\Fizmod')->find($val);
        }
        if ($this->visszautalasfizmod !== $val) {
            if (!$val) {
                $this->removeVisszautalasfizmod();
            }
            else {
                $this->visszautalasfizmod = $val;
            }
        }
    }

    public function removeVisszautalasfizmod() {
        if ($this->visszautalasfizmod !== null) {
            $this->visszautalasfizmod = null;
        }
    }

    public function getSzamlazvakelt() {
        return $this->szamlazvakelt;
    }

    public function getSzamlazvakeltStr() {
        if ($this->getSzamlazvakelt()) {
            return $this->getSzamlazvakelt()->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    public function setSzamlazvakelt($adat = '') {
        if ($adat != '') {
            $this->szamlazvakelt = new \DateTime(\mkw\store::convDate($adat));
        }
        else{
            $this->szamlazvakelt = null;
        }
    }

    public function getSzamlazvateljesites() {
        return $this->szamlazvateljesites;
    }

    public function getSzamlazvateljesitesStr() {
        if ($this->getSzamlazvateljesites()) {
            return $this->getSzamlazvateljesites()->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    public function setSzamlazvateljesites($adat = '') {
        if ($adat != '') {
            $this->szamlazvateljesites = new \DateTime(\mkw\store::convDate($adat));
        }
        else{
            $this->szamlazvateljesites = null;
        }
    }

    /**
     * @return mixed
     */
    public function getFizetvebanktetelid() {
        return $this->fizetvebanktetelid;
    }

    /**
     * @param mixed $fizetvebanktetelid
     */
    public function setFizetvebanktetelid($fizetvebanktetelid) {
        $this->fizetvebanktetelid = $fizetvebanktetelid;
    }

    /**
     * @return mixed
     */
    public function getFizetvepenztartetelid() {
        return $this->fizetvepenztartetelid;
    }

    /**
     * @param mixed $fizetvepenztartetelid
     */
    public function setFizetvepenztartetelid($fizetvepenztartetelid) {
        $this->fizetvepenztartetelid = $fizetvepenztartetelid;
    }

    /**
     * @return mixed
     */
    public function getSzamlazvaosszeghuf() {
        return $this->szamlazvaosszeghuf;
    }

    /**
     * @param mixed $szamlazvaosszeghuf
     */
    public function setSzamlazvaosszeghuf($szamlazvaosszeghuf) {
        $this->szamlazvaosszeghuf = $szamlazvaosszeghuf;
    }

    /**
     * @return mixed
     */
    public function getMinicrmprojectid() {
        return $this->minicrmprojectid;
    }

    /**
     * @param mixed $minicrmprojectid
     */
    public function setMinicrmprojectid($minicrmprojectid) {
        $this->minicrmprojectid = $minicrmprojectid;
    }

    /**
     * @return mixed
     */
    public function getMinicrmcontactid() {
        return $this->minicrmcontactid;
    }

    /**
     * @param mixed $minicrmcontactid
     */
    public function setMinicrmcontactid($minicrmcontactid) {
        $this->minicrmcontactid = $minicrmcontactid;
    }

}