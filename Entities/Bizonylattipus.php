<?php
namespace Entities;
use SIIKerES\store;
use Doctrine\Common\Collections\ArrayCollection;

/** @Entity(repositoryClass="Entities\BizonylattipusRepository")
 *  @Table(name="bizonylattipus")
 **/
class Bizonylattipus {
	/**
	 * @Id @Column(type="string",length=30)
	 */
	private $id;
	/** @Column(type="string",length=40) */
	private $nev;
	/** @Column(type="integer") */
	private $irany=-1;
	/** @Column(type="boolean",nullable=false) */
	private $nyomtatni=true;
	/** @Column(type="string",length=10,nullable=true) */
	private $azonosito;
	/** @Column(type="integer") */
	private $kezdosorszam=0;
	/** @Column(type="integer") */
	private $peldanyszam=1;
	/** @Column(type="boolean",nullable=false) */
	private $mozgat=true;
	/** @Column(type="boolean",nullable=false) */
	private $penztmozgat=true;
	/** @Column(type="boolean",nullable=false) */
	private $editprinted=false;

	public function getId() {
		return $this->id;
	}

	public function getNev() {
		return $this->nev;
	}

	public function setNev($val) {
		$this->nev=$val;
	}

	public function getIrany() {
		return $this->irany;
	}

	public function setIrany($val) {
		$this->irany=$val;
	}

	public function getNyomtatni() {
		return $this->nyomtatni;
	}

	public function setNyomtatni($val) {
		$this->nyomtatni=$val;
	}

	public function getAzonosito() {
		return $this->azonosito;
	}

	public function setAzonosito($val) {
		$this->azonosito=$val;
	}

	public function getKezdosorszam() {
		return $this->kezdosorszam;
	}

	public function setKezdosorszam($val) {
		$this->kezdosorszam=$val;
	}

	public function getPeldanyszam() {
		return $this->peldanyszam;
	}

	public function setPeldanyszam($val) {
		$this->peldanyszam=$val;
	}

	public function getMozgat() {
		return $this->mozgat;
	}

	public function setMozgat($val) {
		$this->mozgat=$val;
	}

	public function getPenztmozgat() {
		return $this->penztmozgat;
	}

	public function setPenztmozgat($val) {
		$this->penztmozgat=$val;
	}

	public function getEditprinted() {
		return $this->editprinted;
	}

	public function setEditprinted($val) {
		$this->editprinted=$val;
	}
}