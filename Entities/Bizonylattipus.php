<?php
namespace Entities;
use mkw\store;
use Doctrine\Common\Collections\ArrayCollection;

/** @Entity(repositoryClass="Entities\BizonylattipusRepository")
 *  @Table(name="bizonylattipus")
 **/
class Bizonylattipus {
	/**
	 * @Id @Column(type="string",length=30)
	 */
	private $id;
	/** @Column(type="string",length=100) */
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
	/** @Column(type="boolean",nullable=false) */
	private $showteljesites=false;
	/** @Column(type="boolean",nullable=false) */
	private $showesedekesseg=false;
	/** @Column(type="boolean",nullable=false) */
	private $showhatarido=false;
	/** @Column(type="boolean",nullable=false) */
	private $showvalutanem=false;
	/** @OneToMany(targetEntity="Bizonylatfej", mappedBy="bizonylattipus",cascade={"persist","remove"}) */
	private $bizonylatfejek;

	public function __construct() {
		$this->bizonylatfejek=new \Doctrine\Common\Collections\ArrayCollection();
	}

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

	public function getShowteljesites() {
		return $this->showteljesites;
	}

	public function setShowteljesites($show) {
		$this->showteljesites=$show;
	}

	public function getShowesedekesseg() {
		return $this->showesedekesseg;
	}

	public function setShowesedekesseg($show) {
		$this->showesedekesseg=$show;
	}

	public function getShowhatarido() {
		return $this->showhatarido;
	}

	public function setShowhatarido($show) {
		$this->showhatarido=$show;
	}

	public function getShowvalutanem() {
		return $this->showvalutanem;
	}

	public function setShowvalutanem($show) {
		$this->showvalutanem=$show;
	}
}