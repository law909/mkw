<?php
namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\SzallitasimodRepository")
 * @ORM\Table(name="szallitasimod",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class Szallitasimod {
	/**
	 * @ORM\Id @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/**
	 * @ORM\Column(type="string",length=255)
	 */
	private $nev;
	/** @ORM\Column(type="boolean") */
	private $webes=true;
	/** @ORM\Column(type="boolean") */
	private $vanszallitasiktg=true;
	/**
	 * @ORM\Column(type="text",nullable=true)
	 */
	private $leiras;
	/**
	 * @ORM\Column(type="string",length=255)
	 */
	private $fizmodok;
	/** @ORM\OneToMany(targetEntity="Bizonylatfej", mappedBy="szallitasimod",cascade={"persist"}) */
	private $bizonylatfejek;
	/** @ORM\Column(type="integer") */
	private $sorrend=0;
	/** @ORM\OneToMany(targetEntity="Partner", mappedBy="szallitasimod",cascade={"persist"}) */
	private $partnerek;
	/** @ORM\OneToMany(targetEntity="SzallitasimodHatar", mappedBy="szallitasimod",cascade={"persist"}) */
	private $hatarok;

	public function __construct() {
		$this->bizonylatfejek = new \Doctrine\Common\Collections\ArrayCollection();
		$this->partnerek = new \Doctrine\Common\Collections\ArrayCollection();
		$this->hatarok = new \Doctrine\Common\Collections\ArrayCollection();
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

	public function getHatarok() {
		return $this->getHatarok();
	}
}