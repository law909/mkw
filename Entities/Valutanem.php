<?php
namespace Entities;

/**
 * @Entity(repositoryClass="Entities\ValutanemRepository")
 * @Table(name="valutanem")
 */
class Valutanem {
	/**
	 * @Id @Column(type="integer")
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/** @Column(type="string",length=6,nullable=false) */
	private $nev;
	/** @Column(type="boolean") */
	private $kerekit=false;
	/** @Column(type="boolean") */
	private $hivatalos=false;
	/** @Column(type="integer") */
	private $mincimlet=0;
	/**
	 * @ManyToOne(targetEntity="Bankszamla")
	 * @JoinColumn(name="bankszamla_id",referencedColumnName="id",nullable=true,onDelete="set null")
	 */
	private $bankszamla;
	/** @OneToMany(targetEntity="Bizonylatfej", mappedBy="valutanem",cascade={"persist"}) */
	private $bizonylatfejek;
	/** @OneToMany(targetEntity="Bizonylattetel", mappedBy="valutanem",cascade={"persist"}) */
	private $bizonylattetelek;
	/** @OneToMany(targetEntity="Kosar", mappedBy="valutanem",cascade={"persist"}) */
	private $kosarak;

	public function getId() {
		return $this->id;
	}

	public function getNev() {
		return $this->nev;
	}

	public function setNev($nev) {
		$this->nev = $nev;
	}

	public function getKerekit() {
		return $this->kerekit;
	}

	public function setKerekit($kerekit) {
		$this->kerekit = $kerekit;
	}

	public function getHivatalos() {
		return $this->hivatalos;
	}

	public function setHivatalos($hivatalos) {
		$this->hivatalos = $hivatalos;
	}

	public function getMincimlet() {
		return $this->mincimlet;
	}

	public function setMincimlet($mincimlet) {
		$this->mincimlet = $mincimlet;
	}

	public function getBankszamla() {
		return $this->bankszamla;
	}

	public function getBankszamlaId() {
		if ($this->bankszamla) {
			return $this->bankszamla->getId();
		}
		return '';
	}

	public function setBankszamla($bankszamla) {
		$this->bankszamla = $bankszamla;
	}
}