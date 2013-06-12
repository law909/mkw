<?php
namespace Entities;

/**
 * @Entity(repositoryClass="Entities\KeresoszologRepository") @HasLifecycleCallbacks
 * @Table(name="keresoszolog")
 */
class Keresoszolog {
	/**
	 * @Id @Column(type="integer")
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/**
	 * @Column(type="string",length=255)
	 */
	private $szo;
	/** @Column(type="datetime",nullable=true) */
	private $datum;

	public function __construct($szo) {
		$this->setSzo($szo);
	}

	public function getId() {
		return $this->id;
	}

	public function getSzo() {
		return $this->szo;
	}

	public function setSzo($szo) {
		$this->szo = $szo;
	}

	public function getDatum() {
		return $this->datum;
	}
	
	/** @PrePersist */
	public function setDatumOnPreInsert() {
		$this->datum = new \DateTime();
	}
}