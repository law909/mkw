<?php
namespace Entities;

/**
 * @Entity @Table(name="kampany")
 */
class Kampany {
	/**
	 * @Id @Column(type="integer")
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/** @Column(type="string",length=255,nullable=true) */
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
}