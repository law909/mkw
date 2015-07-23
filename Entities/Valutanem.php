<?php
namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\ValutanemRepository")
 * @ORM\Table(name="valutanem")
 */
class Valutanem {
	/**
	 * @ORM\Id @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/** @ORM\Column(type="string",length=6,nullable=false) */
	private $nev;
	/** @ORM\Column(type="boolean") */
	private $kerekit=false;
	/** @ORM\Column(type="boolean") */
	private $hivatalos=false;
	/** @ORM\Column(type="integer") */
	private $mincimlet=0;
	/**
	 * @ORM\ManyToOne(targetEntity="Bankszamla")
	 * @ORM\JoinColumn(name="bankszamla_id",referencedColumnName="id",nullable=true,onDelete="set null")
	 */
	private $bankszamla;
	/** @ORM\OneToMany(targetEntity="Bizonylatfej", mappedBy="valutanem",cascade={"persist"}) */
	private $bizonylatfejek;
	/** @ORM\OneToMany(targetEntity="Bizonylattetel", mappedBy="valutanem",cascade={"persist"}) */
	private $bizonylattetelek;
	/** @ORM\OneToMany(targetEntity="Kosar", mappedBy="valutanem",cascade={"persist"}) */
	private $kosarak;
	/** @ORM\OneToMany(targetEntity="TermekAr", mappedBy="valutanem",cascade={"persist"}) */
	private $termekarak;
	/** @ORM\OneToMany(targetEntity="Partner", mappedBy="valutanem",cascade={"persist"}) */
	private $partnerek;

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