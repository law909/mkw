<?php
namespace Entities;

/**
 * @Entity(repositoryClass="Entities\IrszamRepository")
 * @Table(name="irszam")
 */
class Irszam {
	/**
	 * @Id @Column(type="integer")
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/**
	 * @Column(type="string", length=10, nullable=false)
	 */
	private $szam;
	/**
	 * @Column(type="string",length=255)
	 */
	private $nev;

	public function getId() {
		return $this->id;
	}

	public function getNev() {
		return $this->nev;
	}

	public function setNev($nev) {
		$this->nev = $nev;
	}

	public function getSzam() {
		return $this->szam;
	}

	public function setSzam($szam) {
		$this->szam = $szam;
	}
}