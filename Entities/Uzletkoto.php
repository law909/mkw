<?php
namespace Entities;

use Doctrine\Common\Collections\ArrayCollection;

/** @Entity(repositoryClass="Entities\UzletkotoRepository")
 *  @Table(name="uzletkoto")
 **/
class Uzletkoto {
	/**
	 * @Id @Column(type="integer")
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/** @Column(type="string",length=50,nullable=true) */
	private $nev;
	/** @Column(type="string",length=6,nullable=true) */
	private $irszam;
	/** @Column(type="string",length=40,nullable=true) */
	private $varos;
	/** @Column(type="string",length=60,nullable=true) */
	private $utca;
	/** @Column(type="string",length=40,nullable=true) */
	private $telefon;
	/** @Column(type="string",length=40,nullable=true) */
	private $mobil;
	/** @Column(type="string",length=40,nullable=true) */
	private $fax;
	/** @Column(type="string",length=100,nullable=true) */
	private $email;
	/** @Column(type="string",length=200,nullable=true) */
	private $honlap;
	/** @Column(type="string",length=40,nullable=true) */
	private $pw;
	/** @Column(type="text",nullable=true) */
	private $megjegyzes;
	/** @OneToMany(targetEntity="Partner", mappedBy="uzletkoto") */
	private $partnerek;
	/**
	 * @OneToOne(targetEntity="Felhasznalo")
 	 * @JoinColumn(name="felhasznalonev", referencedColumnName="felhasznalonev",nullable=true,onDelete="set null")
	 */
	private $felhasznalo;
	/** @OneToMany(targetEntity="Bizonylatfej", mappedBy="uzletkoto",cascade={"persist"}) */
	private $bizonylatfejek;

	public function __construct() {
		$this->partnerek=new ArrayCollection();
		$this->bizonylatfejek=new ArrayCollection();
	}

	public function getCim() {
		$cim=$this->irszam;
		if (($cim!=='')&&($this->varos!=='')) {
			$cim.=' ';
		}
		$cim.=$this->varos;
		if (($cim!=='')&&($this->utca!=='')) {
			$cim.=', ';
		}
		$cim.=$this->utca;
		return $cim;
	}

	public function getId() {
		return $this->id;
	}

	public function getNev() {
		return $this->nev;
	}

	public function setNev($nev) {
		$this->nev=$nev;
	}

	public function getIrszam() {
		return $this->irszam;
	}

	public function setIrszam($irszam) {
		$this->irszam=$irszam;
	}

	public function getVaros() {
		return $this->varos;
	}

	public function setVaros($varos) {
		$this->varos=$varos;
	}

	public function getUtca() {
		return $this->utca;
	}

	public function setUtca($utca) {
		$this->utca=$utca;
	}

	public function getTelefon()
	{
		return $this->telefon;
	}

	public function setTelefon($telefon)
	{
		$this->telefon = $telefon;
	}

	public function getMobil()
	{
		return $this->mobil;
	}

	public function setMobil($mobil)
	{
		$this->mobil = $mobil;
	}

	public function getFax()
	{
		return $this->fax;
	}

	public function setFax($fax)
	{
		$this->fax = $fax;
	}

	public function getEmail()
	{
		return $this->email;
	}

	public function setEmail($email)
	{
		$this->email = $email;
	}

	public function getHonlap()
	{
		return $this->honlap;
	}

	public function setHonlap($honlap)
	{
		$this->honlap = $honlap;
	}

	public function getPw()
	{
		return $this->pw;
	}

	public function setPw($pw)
	{
		$this->pw = md5($pw);
	}

	/**
	 *
	 * @return ArrayCollection
	 */
	public function getPartnerek() {
		return $this->partnerek;
	}

	public function addPartner(Partner $partner) {
		if (!$this->partnerek->contains($partner)) {
			$this->partnerek->add($partner);
			$partner->setUzletkoto($this);
		}
	}

	public function removePartner(Partner $partner) {
		if ($this->partnerek->removeElement($partner)) {
			$partner->removeUzletkoto();
			return true;
		}
		return false;
	}

	public function setFelhasznalo(Felhasznalo $felhasznalo) {
		if ($this->felhasznalo!==$felhasznalo) {
			$this->felhasznalo = $felhasznalo;
			$felhasznalo->setUzletkoto($this);
		}
	}

	public function removeFelhasznalo() {
		if ($this->felhasznalo!==null) {
			$felhasznalo=$this->felhasznalo;
			$this->felhasznalo=null;
			$felhasznalo->removeUzletkoto($this);
			return true;
		}
		return false;
	}

	public function getMegjegyzes()
	{
	    return $this->megjegyzes;
	}

	public function setMegjegyzes($megjegyzes)
	{
	    $this->megjegyzes = $megjegyzes;
	}
}
?>