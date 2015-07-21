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
	/** @ORM\OneToMany(targetEntity="Bizonylatfej", mappedBy="fizmod",cascade={"persist"}) */
	private $bizonylatfejek;
	/** @ORM\Column(type="text",nullable=true) */
	private $leiras;
	/** @ORM\Column(type="integer") */
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

}