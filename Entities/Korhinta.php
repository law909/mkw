<?php
namespace Entities;

use mkw\Store;

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
			'kepurlsmall'=>$this->getKepurlSmall(),
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
			if ($this->url[0]!==$pre) {
				return $pre.$this->kepurl;
			}
			return $this->kepurl;
		}
		return '';
	}

	public function getKepurlSmall($pre='/') {
		$url=$this->getKepurl($pre);
		if ($url) {
			$t=explode('.',$url);
			$ext=array_pop($t);
			return implode('.',$t).store::getParameter(\mkw\consts::Smallimgpost,'').'.'.$ext;
		}
		return '';
	}

	public function getKepurlMedium($pre='/') {
		$url=$this->getKepurl($pre);
		if ($url) {
			$t=explode('.',$url);
			$ext=array_pop($t);
			return implode('.',$t).store::getParameter(\mkw\consts::Mediumimgpost,'').'.'.$ext;
		}
		return '';
	}

	public function getKepurlLarge($pre='/') {
		$url=$this->getKepurl($pre);
		if ($url) {
			$t=explode('.',$url);
			$ext=array_pop($t);
			return implode('.',$t).store::getParameter(\mkw\consts::Bigimgpost,'').'.'.$ext;
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