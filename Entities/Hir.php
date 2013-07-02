<?php
namespace Entities;

use mkw\store;

/**
 * @Entity(repositoryClass="Entities\HirRepository")
 * @Table(name="hir",indexes={
 *		@index(name="hirslug_idx",columns={"slug"})
 * })
 */
class Hir {
	/**
	 * @Id @Column(type="integer")
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/**
	 * @gedmo:Sluggable
	 * @Column(type="string",length=255)
	 */
	private $cim;
	/**
	 * @gedmo:Slug
	 * @Column(type="string",length=255)
	 */
	private $slug;
	/** @Column(type="integer",nullable=true) */
	private $sorrend;
	/** @Column(type="string",length=255,nullable=true) */
	private $forras;
	/** @Column(type="text",nullable=true) */
	private $lead;
	/** @Column(type="date",nullable=true) */
	private $elsodatum;
	/** @Column(type="date",nullable=true) */
	private $utolsodatum;
	/** @Column(type="date",nullable=true) */
	private $datum;
	/** @Column(type="boolean") */
	private $lathato;
	/** @Column(type="text",nullable=true) */
	private $szoveg;
	/** @Column(type="text",nullable=true) */
	private $seodescription;

	public function convertToArray() {
		$ret=array(
			'slug'=>$this->getSlug(),
			'cim'=>$this->getCim(),
			'lead'=>$this->getLead(),
			'datum'=>$this->getDatumStr(),
			'szoveg'=>$this->getSzoveg()
		);
		return $ret;
	}

	public function getId() {
		return $this->id;
	}

	public function getCim() {
		return $this->cim;
	}

	public function getShowCim() {
		return $this->cim.' - '.\mkw\Store::getParameter('oldalcim');
	}

	public function setCim($cim) {
		$this->cim = $cim;
	}

	public function getSlug() {
		return $this->slug;
	}

	public function setSlug($adat) {
		$this->slug=$adat;
	}

	public function getShowSeodescription() {
		if ($this->seodescription) {
			return $this->seodescription;
		}
		return \mkw\Store::getParameter('seodescription');
	}

	public function getSeodescription() {
		return $this->seodescription;
	}

	public function setSeodescription($adat) {
		$this->seodescription=$adat;
	}

	public function getElsodatum() {
		return $this->elsodatum;
	}

	public function getElsodatumStr() {
		return $this->getElsodatum()->format(store::$DateFormat);
	}

	public function setElsodatum($adat) {
		if ($adat=='') $adat=date(store::$DateFormat);
		$this->elsodatum = new \DateTime(store::convDate($adat));
	}

	public function getUtolsodatum() {
		return $this->utolsodatum;
	}

	public function getUtolsodatumStr() {
		return $this->getUtolsodatum()->format(store::$DateFormat);
	}

	public function setUtolsodatum($adat) {
		if ($adat=='') $adat=date(store::$DateFormat);
		$this->utolsodatum = new \DateTime(store::convDate($adat));
	}

	public function getDatum() {
		return $this->datum;
	}

	public function getDatumStr() {
		return $this->getDatum()->format(store::$DateFormat);
	}

	public function setDatum($adat) {
		if ($adat=='') $adat=date(store::$DateFormat);
		$this->datum = new \DateTime(store::convDate($adat));
	}

	public function getLathato() {
		return $this->lathato;
	}

	public function setLathato($adat) {
		$this->lathato=$adat;
	}

	public function getSzoveg() {
		return $this->szoveg;
	}

	public function setSzoveg($adat) {
		$this->szoveg=$adat;
	}

	public function getForras() {
		return $this->forras;
	}

	public function setForras($adat) {
		$this->forras=$adat;
	}

	public function getLead() {
		return $this->lead;
	}

	public function setLead($adat) {
		$this->lead=$adat;
	}

	public function getSorrend() {
		return $this->sorrend;
	}

	public function setSorrend($adat) {
		$this->sorrend=$adat;
	}
}