<?php
namespace Entities;

use Doctrine\Common\Collections\ArrayCollection;

/** @Entity(repositoryClass="Entities\KontaktRepository")
 *  @Table(name="kontakt")
 **/
class Kontakt {
	/**
	 * @Id @Column(type="integer")
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/** @Column(type="string",length=50,nullable=true) */
	private $nev;
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
	/** @Column(type="text",nullable=true) */
	private $megjegyzes;
	/**
	 * @ManyToOne(targetEntity="Partner",inversedBy="kontaktok")
	 * @JoinColumn(name="partner_id", referencedColumnName="id",onDelete="cascade")
	 */
	private $partner;
	/**
	 * @ManyToMany(targetEntity="Kontaktcimketorzs", inversedBy="kontaktok")
	 * @JoinTable(name="kontakt_cimkek",
	 *  joinColumns={@JoinColumn(name="kontakt_id",referencedColumnName="id",onDelete="cascade")},
	 *  inverseJoinColumns={@JoinColumn(name="cimketorzs_id",referencedColumnName="id")}
	 *  )
	 */
	private $cimkek;

	public function __construct() {
		$this->cimkek=new \Doctrine\Common\Collections\ArrayCollection();
	}

	public function getId() {
		return $this->id;
	}

	public function getNev()
	{
		return $this->nev;
	}

	public function setNev($nev)
	{
		$this->nev = $nev;
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

	public function getMegjegyzes()
	{
		return $this->megjegyzes;
	}

	public function setMegjegyzes($megjegyzes)
	{
		$this->megjegyzes = $megjegyzes;
	}

	/**
	 *
	 * @return Partner
	 */
	public function getPartner()
	{
		return $this->partner;
	}

	public function setPartner(Partner $partner) {
		if ($this->partner!==$partner) {
			$this->partner = $partner;
			$partner->addKontakt($this);
		}
	}

	public function removePartner() {
		if ($this->partner !== null) {
			$partner = $this->partner;
			$this->partner = null;
			$partner->removeKontakt($this);
		}
	}

	/**
	 *
	 * @return ArrayCollection
	 */
	public function getCimkek() {
		return $this->cimkek;
	}

	public function addCimke(Cimketorzs $cimke) {
		if (!$this->cimkek->contains($cimke)) {
			$this->cimkek->add($cimke);
			$cimke->addKontakt($this);
		}
	}

	public function removeCimke(Cimketorzs $cimke) {
		if ($this->cimkek->removeElement($cimke)) {
			$cimke->removeKontakt($this);
			return true;
		}
		return false;
	}
}
?>