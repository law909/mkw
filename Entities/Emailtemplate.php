<?php
namespace Entities;

/**
 * @Entity(repositoryClass="Entities\EmailtemplateRepository")
 * @Table(name="emailtemplate")
 */
class Emailtemplate {
	/**
	 * @Id @Column(type="integer")
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/**
	 * @Column(type="string",length=255)
	 */
	private $nev;
	/** @Column(type="text",nullable=true) */
	private $szoveg;

	public function getId() {
		return $this->id;
	}

	public function getNev() {
		return $this->nev;
	}

	public function setNev($nev) {
		$this->nev = $nev;
	}
	
	public function getSzoveg() {
		return $this->szoveg;
	}

	public function setSzoveg($adat) {
		$this->szoveg=$adat;
	}
}