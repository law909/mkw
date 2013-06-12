<?php
namespace Entities;

/**
 * @Entity(repositoryClass="Entities\JelenlettipusRepository")
 * @Table(name="jelenlettipus")
 */
class Jelenlettipus {
	/**
	 * @Id @Column(type="integer")
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
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
}