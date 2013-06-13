<?php
namespace Entities;

/**
 * @Entity(repositoryClass="Entities\PartnerCsoportRepository")
 * @Table(name="partnercsoport")
 */
class PartnerCsoport {
	/**
	 * @Id @Column(type="integer")
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/** @Column(type="string",length=255,nullable=false) */
	private $nev;
	/** @Column(type="string",length=20,nullable=false) */
	private $tipus='vevo';
	/** @Column(type="string",length=8,nullable=true) */
	private $fkviszam='';

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

	public function getTipusnev() {
		switch ($this->tipus) {
			case 'vevo':
				return 'Vevő';
			case 'szallito':
				return 'Szállító';
		}
	}

	public function getFkviszam()
	{
	    return $this->fkviszam;
	}

	public function setFkviszam($fkviszam)
	{
	    $this->fkviszam = $fkviszam;
	}
}