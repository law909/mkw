<?php
namespace Entities;

use mkw\store, Doctrine\Common\Collections\ArrayCollection;

/** @Entity(repositoryClass="Entities\DolgozoRepository")
 *  @Table(name="dolgozo")
 **/
class Dolgozo {
	/**
	 * @Id @Column(type="integer")
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/** @Column(type="string",length=255) */
	private $nev;
	/** @Column(type="string",length=10,nullable=true) */
	private $irszam;
	/** @Column(type="string",length=40,nullable=true) */
	private $varos;
	/** @Column(type="string",length=60,nullable=true) */
	private $utca;
	/** @Column(type="string",length=40,nullable=true) */
	private $telefon;
	/** @Column(type="string",length=100,nullable=true) */
	private $email;
	/**
	 * @ManyToOne(targetEntity="Munkakor",inversedBy="dolgozok")
	 * @JoinColumn(name="munkakor_id", referencedColumnName="id",nullable=true,onDelete="no action")
	 */
	private $munkakor;
	/**
	 * @ManyToOne(targetEntity="Termek",inversedBy="dolgozok")
	 * @JoinColumn(name="termek_id",referencedColumnName="id",nullable=true,onDelete="no action")
	 */
	private $muvelet;
	/** @Column(type="date",nullable=true) */
	private $szulido;
	/** @Column(type="string",length=60,nullable=true) */
	private $szulhely;
	/** @Column(type="integer",nullable=true) */
	private $evesmaxszabi=0;
	/** @Column(type="date") */
	private $munkaviszonykezdete;
	/** @OneToMany(targetEntity="Jelenletiiv", mappedBy="dolgozo") */
	private $jelenletek;

	public function __construct() {
		$this->jelenletek=new ArrayCollection();
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

	public function getIrszam() {
		return $this->irszam;
	}

	public function setIrszam($irszam) {
		$this->irszam = $irszam;
	}

	public function getVaros() {
		return $this->varos;
	}

	public function setVaros($varos) {
		$this->varos = $varos;
	}

	public function getUtca() {
		return $this->utca;
	}

	public function setUtca($utca) {
		$this->utca = $utca;
	}

	public function getTelefon() {
		return $this->telefon;
	}

	public function setTelefon($telefon) {
		$this->telefon = $telefon;
	}

	public function getEmail() {
		return $this->email;
	}

	public function setEmail($email) {
		$this->email = $email;
	}

	public function getSzulido() {
		return $this->szulido;
	}

	public function getSzulidoStr() {
		if ($this->getSzulido()) {
			return $this->getSzulido()->format(store::$DateFormat);
		}
		return '';
	}

	public function setSzulido($adat) {
		if ($adat=='') $adat=date(store::$DateFormat);
		$this->szulido = new \DateTime(store::convDate($adat));
	}

	public function getSzulhely() {
		return $this->szulhely;
	}

	public function setSzulhely($adat) {
		$this->szulhely=$adat;
	}

	public function getEvesmaxszabi() {
		return $this->evesmaxszabi;
	}

	public function setEvesmaxszabi($eves) {
		$this->evesmaxszabi = $eves;
	}

	public function getMunkaviszonykezdete() {
		return $this->munkaviszonykezdete;
	}

	public function getMunkaviszonykezdeteStr() {
		if ($this->getMunkaviszonykezdete()) {
			return $this->getMunkaviszonykezdete()->format(store::$DateFormat);
		}
		return '';
	}

	public function setMunkaviszonykezdete($adat) {
		if ($adat=='') $adat=date(store::$DateFormat);
		$this->munkaviszonykezdete=new \DateTime(store::convDate($adat));
	}

	public function getMunkakor(){
		return $this->munkakor;
	}

	public function getMunkakorNev() {
		if ($this->munkakor) {
			return $this->munkakor->getNev();
		}
		return '';
	}

	public function getMunkakorId() {
		if ($this->munkakor) {
			return $this->munkakor->getId();
		}
		return '';
	}

	public function setMunkakor(Munkakor $munkakor) {
		if ($this->munkakor!==$munkakor) {
			$this->munkakor=$munkakor;
			$munkakor->addDolgozo($this);
		}
	}

	public function removeMunkakor() {
		if ($this->munkakor !== null) {
			$munkakor = $this->munkakor;
			$this->munkakor = null;
			$munkakor->removeDolgozo($this);
		}
	}

	public function getMuvelet(){
		return $this->muvelet;
	}

	public function getMuveletNev() {
		if ($this->muvelet) {
			return $this->muvelet->getNev();
		}
		return '';
	}

	public function getMuveletId() {
		if ($this->muvelet) {
			return $this->muvelet->getId();
		}
		return '';
	}

	public function setMuvelet(Termek $muvelet) {
		if ($this->muvelet!==$muvelet) {
			$this->muvelet=$muvelet;
			$muvelet->addDolgozo($this);
		}
	}

	public function removeMuvelet() {
		if ($this->muvelet !== null) {
			$muvelet = $this->muvelet;
			$this->muvelet = null;
			$muvelet->removeDolgozo($this);
		}
	}

	public function getJelenletek() {
		return $this->jelenletek;
	}

	public function addJelenlet(Jelenletiiv $adat) {
		if (!$this->jelenletek->contains($adat)) {
			$this->jelenletek->add($adat);
			$adat->setDolgozo($this);
		}
	}

	public function removeJelenlet(Jelenletiiv $adat) {
		if ($this->jelenletek->removeElement($adat)) {
			$adat->removeDolgozo();
			return true;
		}
		return false;
	}
}