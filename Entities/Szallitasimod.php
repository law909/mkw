<?php
namespace Entities;

/**
 * @Entity(repositoryClass="Entities\SzallitasimodRepository")
 * @Table(name="szallitasimod")
 */
class Szallitasimod {
	/**
	 * @Id @Column(type="integer")
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/**
	 * @Column(type="string",length=255)
	 */
	private $nev;
	/** @Column(type="boolean") */
	private $webes=true;
	/** @Column(type="boolean") */
	private $vanszallitasiktg=true;
	/**
	 * @Column(type="text",nullable=true)
	 */
	private $leiras;
	/**
	 * @Column(type="string",length=255)
	 */
	private $fizmodok;
	/** @OneToMany(targetEntity="Bizonylatfej", mappedBy="szallitasimod",cascade={"persist","remove"}) */
	private $bizonylatfejek;
	/** @Column(type="integer") */
	private $sorrend=0;

	public function __construct() {
		$this->bizonylatfejek=new \Doctrine\Common\Collections\ArrayCollection();
	}

	public function getId() {
		return $this->id;
	}

	public function getNev() {
		return $this->nev;
	}

	public function setNev($nev) {
		$this->nev = $nev;
	}

	public function getWebes() {
		return $this->webes;
	}

	public function setWebes($webes) {
		$this->webes = $webes;
	}

	public function getLeiras() {
		return $this->leiras;
	}

	public function setLeiras($leiras) {
		$this->leiras = $leiras;
	}

	public function getFizmodok() {
		return $this->fizmodok;
	}

	public function setFizmodok($fm) {
		$this->fizmodok = $fm;
	}

	public function getSorrend() {
		return $this->sorrend;
	}

	public function setSorrend($val) {
		$this->sorrend=$val;
	}

    public function getVanszallitasiktg() {
        return $this->vanszallitasiktg;
    }

    public function setVanszallitasiktg($adat) {
        $this->vanszallitasiktg = $adat;
    }
}