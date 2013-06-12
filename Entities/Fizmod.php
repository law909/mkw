<?php
namespace Entities;
use matt;
/**
 * @Entity(repositoryClass="Entities\FizmodRepository")
 * @Table(name="fizmod")
 */
class Fizmod {
	/**
	 * @Id @Column(type="integer")
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/** @Column(type="string",length=255,nullable=false) */
	private $nev;
	/** @Column(type="string",length=1,nullable=false) */
	private $tipus='B';
	/** @Column(type="integer") */
	private $haladek=0;
	/** @Column(type="boolean") */
	private $webes=true;
	/** @OneToMany(targetEntity="Bizonylatfej", mappedBy="fizmod",cascade={"persist","remove"}) */
	private $bizonylatfejek;

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
		if (matt\Filter::isInt($haladek)) {
	    	$this->haladek = $haladek;
		}
	}

	public function getWebes() {
	    return $this->webes;
	}

	public function setWebes($webes) {
		if (matt\Filter::isBool($webes)) {
	    	$this->webes = $webes;
		}
	}
}