<?php
namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\CsomagTerminalRepository")
 * @ORM\Table(name="csomagterminal",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class CsomagTerminal {
	/**
	 * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

    /** @ORM\Column(type="string",length=100) */
    private $idegenid;

    /** @ORM\Column(type="string",length=20,nullable=true) */
    private $tipus;

	/** @ORM\Column(type="string",length=254,nullable=true) */
	private $nev;

    /** @ORM\Column(type="string",length=254,nullable=true) */
	private $cim;

    /** @ORM\Column(type="string",length=100,nullable=true) */
	private $csoport;

    /** @ORM\Column(type="string",length=100,nullable=true) */
	private $subgroup;

    /** @ORM\Column(type="string",length=75,nullable=true) */
	private $nyitva;

    /** @ORM\Column(type="string",length=254,nullable=true) */
	private $findme;

    /** @ORM\Column(type="decimal",precision=8,scale=6,nullable=true) */
    private $geolat;

    /** @ORM\Column(type="decimal",precision=8,scale=6,nullable=true) */
    private $geolng;

    /** @ORM\OneToMany(targetEntity="Bizonylatfej", mappedBy="csomagterminal",cascade={"persist"}) */
	private $bizonylatfejek;

	/** @ORM\Column(type="boolean",nullable=true) */
	private $inaktiv = false;

	public function __construct() {
		$this->bizonylatfejek = new \Doctrine\Common\Collections\ArrayCollection();
	}

    public function getId() {
        return $this->id;
    }

    public function getNev() {
        return $this->nev;
    }

    public function setNev($adat) {
        $this->nev = $adat;
    }

    public function getCim() {
        return $this->cim;
    }

    public function setCim($adat) {
        $this->cim = $adat;
    }

    public function getCsoport() {
        return $this->csoport;
    }

    public function setCsoport($adat) {
        $this->csoport = $adat;
    }

    public function getSubgroup() {
        return $this->subgroup;
    }

    public function setSubgroup($adat) {
        $this->subgroup = $adat;
    }

    public function getNyitva() {
        return $this->nyitva;
    }

    public function setNyitva($adat) {
        $this->nyitva = $adat;
    }

    public function getFindme() {
        return $this->findme;
    }

    public function setFindme($adat) {
        $this->findme = $adat;
    }

    public function getGeolat() {
        return $this->geolat;
    }

    public function setGeolat($adat) {
        $this->geolat = $adat;
    }

    public function getGeolng() {
        return $this->geolng;
    }

    public function setGeolng($adat) {
        $this->geolng = $adat;
    }

    /**
     * @return mixed
     */
    public function getInaktiv() {
        return $this->inaktiv;
    }

    /**
     * @param mixed $inaktiv
     */
    public function setInaktiv($inaktiv) {
        $this->inaktiv = $inaktiv;
    }

    /**
     * @return mixed
     */
    public function getTipus() {
        return $this->tipus;
    }

    /**
     * @param mixed $tipus
     */
    public function setTipus($tipus) {
        $this->tipus = $tipus;
    }

    /**
     * @return mixed
     */
    public function getIdegenid() {
        return $this->idegenid;
    }

    /**
     * @param mixed $idegenid
     */
    public function setIdegenid($idegenid) {
        $this->idegenid = $idegenid;
    }

}