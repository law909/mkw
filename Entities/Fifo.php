<?php
namespace Entities;

/**
 * @Entity(repositoryClass="Entities\FifoRepository")
 * @Table(name="fifo")
 */
class Fifo {
	/**
	 * @Id @Column(type="integer")
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;

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
