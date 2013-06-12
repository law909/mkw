<?php
namespace Entities;

/**
 * @Entity(repositoryClass="Entities\KapcsolatfelveteltemaRepository")
 * @Table(name="kapcsolatfelveteltema")
 */
class Kapcsolatfelveteltema {
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