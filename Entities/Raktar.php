<?php
namespace Entities;

/**
 * @Entity(repositoryClass="Entities\RaktarRepository")
 * @Table(name="raktar")
 */
class Raktar {
	/**
	 * @Id @Column(type="integer")
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/** @Column(type="string",length=50) */
	private $nev;
	/** @Column(type="boolean",nullable=false) */
	private $mozgat;
	/** @OneToMany(targetEntity="Bizonylatfej", mappedBy="raktar",cascade={"persist","remove"}) */
	private $bizonylatfejek;

	public function getId() {
		return $this->id;
	}

	public function getNev() {
		return $this->nev;
	}

	public function setNev($nev) {
		$this->nev = $nev;
	}

	public function getMozgat() {
		return $this->mozgat;
	}

	public function setMozgat($val) {
		$this->mozgat=$val;
	}
}