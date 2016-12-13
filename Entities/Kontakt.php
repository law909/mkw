<?php
namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity(repositoryClass="Entities\KontaktRepository")
 *  @ORM\Table(name="kontakt",
 * 	options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 * */
class Kontakt {

    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id = 0;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $nev;

    /** @ORM\Column(type="string",length=50,nullable=true) */
    private $beosztas;

    /** @ORM\Column(type="string",length=40,nullable=true) */
    private $telefon = '';

    /** @ORM\Column(type="string",length=40,nullable=true) */
    private $mobil = '';

    /** @ORM\Column(type="string",length=40,nullable=true) */
    private $fax = '';

    /** @ORM\Column(type="string",length=100,nullable=true) */
    private $email = '';

    /** @ORM\Column(type="string",length=200,nullable=true) */
    private $honlap = '';

    /** @ORM\Column(type="text",nullable=true) */
    private $megjegyzes = '';

    /**
     * @ORM\ManyToOne(targetEntity="Partner",inversedBy="kontaktok")
     * @ORM\JoinColumn(name="partner_id",referencedColumnName="id",onDelete="cascade")
     */
    private $partner;

    /** @ORM\Column(type="integer", nullable=true) */
    private $migrid;

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getNev() {
        return $this->nev;
    }

    /**
     * @param mixed $nev
     */
    public function setNev($nev) {
        $this->nev = $nev;
    }

    /**
     * @return mixed
     */
    public function getBeosztas() {
        return $this->beosztas;
    }

    /**
     * @param mixed $beosztas
     */
    public function setBeosztas($beosztas) {
        $this->beosztas = $beosztas;
    }

    /**
     * @return mixed
     */
    public function getTelefon() {
        return $this->telefon;
    }

    /**
     * @param mixed $telefon
     */
    public function setTelefon($telefon) {
        $this->telefon = $telefon;
    }

    /**
     * @return mixed
     */
    public function getMobil() {
        return $this->mobil;
    }

    /**
     * @param mixed $mobil
     */
    public function setMobil($mobil) {
        $this->mobil = $mobil;
    }

    /**
     * @return mixed
     */
    public function getFax() {
        return $this->fax;
    }

    /**
     * @param mixed $fax
     */
    public function setFax($fax) {
        $this->fax = $fax;
    }

    /**
     * @return mixed
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email) {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getHonlap() {
        return $this->honlap;
    }

    /**
     * @param mixed $honlap
     */
    public function setHonlap($honlap) {
        $this->honlap = $honlap;
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
     * @return \Entities\Partner
     */
    public function getPartner() {
        return $this->partner;
    }

    public function getPartnerId() {
        if ($this->getPartner()) {
            return $this->getPartner()->getId();
        }
        return 0;
    }

    public function getPartnerNev() {
        if ($this->getPartner()) {
            return $this->getPartner()->getNev();
        }
        return '';
    }
    /**
     * @param \Entities\Partner $partner
     */
    public function setPartner($partner) {
        $this->partner = $partner;
    }

    /**
     * @return mixed
     */
    public function getMigrid() {
        return $this->migrid;
    }

    /**
     * @param mixed $migrid
     */
    public function setMigrid($migrid) {
        $this->migrid = $migrid;
    }

}