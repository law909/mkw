<?php
namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\FizmodRepository")
 * @ORM\Table(name="fizmod")
 */
class Fizmod {
	/**
	 * @ORM\Id @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/** @ORM\Column(type="string",length=255,nullable=false) */
	private $nev;
	/** @ORM\Column(type="string",length=1,nullable=false) */
	private $tipus='B';
	/** @ORM\Column(type="integer") */
	private $haladek=0;
	/** @ORM\Column(type="boolean") */
	private $webes=true;
	/** @ORM\Column(type="boolean") */
	private $rugalmas = true;
	/** @ORM\OneToMany(targetEntity="Bizonylatfej", mappedBy="fizmod",cascade={"persist"}) */
	private $bizonylatfejek;
	/** @ORM\Column(type="text",nullable=true) */
	private $leiras;
	/** @ORM\Column(type="integer") */
	private $sorrend=0;
    /** @ORM\Column(type="integer",nullable=true) */
    private $osztotthaladek1;
    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $osztottszazalek1;
    /** @ORM\Column(type="integer",nullable=true) */
    private $osztotthaladek2;
    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $osztottszazalek2;
    /** @ORM\Column(type="integer",nullable=true) */
    private $osztotthaladek3;
    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $osztottszazalek3;

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

	public function getTipus() {
	    return $this->tipus;
	}

	public function setTipus($tipus) {
	    $this->tipus = $tipus;
	}

	public function getHaladek() {
	    return $this->haladek;
	}

	public function setHaladek($haladek) {
    	$this->haladek = $haladek;
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

	public function getSorrend() {
		return $this->sorrend;
	}

	public function setSorrend($val) {
		$this->sorrend=$val;
	}

    public function getOsztotthaladek1() {
        return $this->osztotthaladek1;
    }

    public function setOsztotthaladek1($adat) {
        $this->osztotthaladek1 = $adat;
    }

    public function getOsztottszazalek1() {
        return $this->osztottszazalek1;
    }

    public function setOsztottszazalek1($adat) {
        $this->osztottszazalek1 = $adat;
    }

    public function getOsztotthaladek2() {
        return $this->osztotthaladek2;
    }

    public function setOsztotthaladek2($adat) {
        $this->osztotthaladek2 = $adat;
    }

    public function getOsztottszazalek2() {
        return $this->osztottszazalek2;
    }

    public function setOsztottszazalek2($adat) {
        $this->osztottszazalek2 = $adat;
    }

    public function getOsztotthaladek3() {
        return $this->osztotthaladek3;
    }

    public function setOsztotthaladek3($adat) {
        $this->osztotthaladek3 = $adat;
    }

    public function getOsztottszazalek3() {
        return $this->osztottszazalek3;
    }

    public function setOsztottszazalek3($adat) {
        $this->osztottszazalek3 = $adat;
    }

	/**
	 * @return mixed
	 */
	public function getRugalmas() {
		return $this->rugalmas;
	}

	/**
	 * @param mixed $rugalmas
	 */
	public function setRugalmas($rugalmas) {
		$this->rugalmas = $rugalmas;
	}

}