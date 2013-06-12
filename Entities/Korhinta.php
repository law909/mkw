<?php
namespace Entities;

/**
 * @Entity(repositoryClass="Entities\KorhintaRepository")
 * @Table(name="korhinta")
 */
class Korhinta {
	/**
	 * @Id @Column(type="integer")
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/**
	 * @Column(type="string",length=255)
	 */
	private $nev;
	/** @Column(type="text",nullable=true) */
	private $szoveg;
	/** @Column(type="string",length=255,nullable=true) */
	private $url;
	/** @Column(type="text",nullable=true) */
	private $kepurl='';
	/** @Column(type="text",nullable=true) */
	private $kepleiras='';
	/** @Column(type="string",length=255,nullable=true) */
	private $kepnev='';
	/** @Column(type="boolean",nullable=true) */
	private $lathato;
	/** @Column(type="integer",nullable=true) */
	private $sorrend;

	public function convertToArray() {
		$ret=array(
			'nev'=>$this->getNev(),
			'szoveg'=>$this->getSzoveg(),
			'url'=>$this->getUrl(),
			'kepurl'=>$this->getKepurl(),
			'kepleiras'=>$this->getKepleiras()
		);
		return $ret;
	}

	public function getId() {
		return $this->id;
	}

	public function getNev() {
		return $this->nev;
	}

	public function setNev($nev) {
		$this->nev = $nev;
	}

	public function getSzoveg() {
		return $this->szoveg;
	}

	public function setSzoveg($adat) {
		$this->szoveg = $adat;
	}

	public function getUrl() {
		return $this->url;
	}

	public function setUrl($adat) {
		$this->url = $adat;
	}

	public function getKepurl($pre='/') {
		if ($this->kepurl) {
			return $pre.$this->kepurl;
		}
		return '';
	}

	public function setKepurl($kepurl) {
		$this->kepurl = $kepurl;
		if (!$kepurl) {
			$this->setKepleiras(null);
			$this->setKepnev(null);
		}
	}

	public function getKepleiras() {
		return $this->kepleiras;
	}

	public function setKepleiras($kepleiras) {
		$this->kepleiras = $kepleiras;
	}

	public function getKepnev() {
		return $this->kepnev;
	}

	public function setKepnev($kepnev) {
		$this->kepnev = $kepnev;
	}

	public function getLathato() {
		return $this->lathato;
	}

	public function setLathato($adat) {
		$this->lathato = $adat;
	}

	public function getSorrend() {
		return $this->sorrend;
	}

	public function setSorrend($adat) {
		$this->sorrend = $adat;
	}
}