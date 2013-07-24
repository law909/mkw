<?php
namespace Entities;

/**
 * @Entity(repositoryClass="Entities\IrszamRepository")
 * @Table(name="irszam")
 */
class Irszam {
	/**
	 * @Id @Column(type="string", length=10, nullable=false)
	 */
	private $id;
	/**
	 * @Column(type="string",length=255)
	 */
	private $nev;

	public function getId() {
		return $this->id;
	}

	public function setId($val) {
		$this->id = $val;
	}

	public function getNev() {
		return $this->nev;
	}

	public function setNev($nev) {
		$this->nev = $nev;
	}
}