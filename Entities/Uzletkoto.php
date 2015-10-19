<?php
namespace Entities;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/** @ORM\Entity(repositoryClass="Entities\UzletkotoRepository")
 *  @ORM\Table(name="uzletkoto",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 **/
class Uzletkoto {
	/**
	 * @ORM\Id @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/** @ORM\Column(type="string",length=50,nullable=true) */
	private $nev;
	/** @ORM\Column(type="string",length=6,nullable=true) */
	private $irszam;
	/** @ORM\Column(type="string",length=40,nullable=true) */
	private $varos;
	/** @ORM\Column(type="string",length=60,nullable=true) */
	private $utca;
	/** @ORM\Column(type="string",length=40,nullable=true) */
	private $telefon;
	/** @ORM\Column(type="string",length=40,nullable=true) */
	private $mobil;
	/** @ORM\Column(type="string",length=40,nullable=true) */
	private $fax;
	/** @ORM\Column(type="string",length=100,nullable=true) */
	private $email;
	/** @ORM\Column(type="string",length=200,nullable=true) */
	private $honlap;
	/** @ORM\Column(type="string",length=40,nullable=true) */
	private $pw;
	/** @ORM\Column(type="text",nullable=true) */
	private $megjegyzes;
	/** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
	private $jutalek;
	/** @ORM\OneToMany(targetEntity="Partner", mappedBy="uzletkoto") */
	private $partnerek;
	/**
	 * @ORM\OneToOne(targetEntity="Felhasznalo")
 	 * @ORM\JoinColumn(name="felhasznalonev", referencedColumnName="felhasznalonev",nullable=true,onDelete="set null")
	 */
	private $felhasznalo;
	/** @ORM\OneToMany(targetEntity="Bizonylatfej", mappedBy="uzletkoto",cascade={"persist"}) */
	private $bizonylatfejek;
	/** @ORM\Column(type="string",length=255,nullable=true) */
	private $sessionid = '';
	/**
	 * @ORM\ManyToOne(targetEntity="Valutanem")
	 * @ORM\JoinColumn(name="partnervalutanem_id", referencedColumnName="id",nullable=true,onDelete="restrict")
	 */
	private $partnervalutanem;
	/**
	 * @ORM\ManyToOne(targetEntity="Fizmod")
	 * @ORM\JoinColumn(name="partnerfizmod_id",referencedColumnName="id",nullable=true,onDelete="set null")
	 */
	private $partnerfizmod;
	/** @ORM\Column(type="string",length=255,nullable=true) */
	private $partnertermekarazonosito;
	/** @ORM\Column(type="string",length=10,nullable=true) */
	private $partnerbizonylatnyelv;
	/**
	 * @ORM\ManyToOne(targetEntity="Szallitasimod")
	 * @ORM\JoinColumn(name="partnerszallitasimod_id", referencedColumnName="id",nullable=true,onDelete="restrict")
	 */
	private $partnerszallitasimod;
    /** @ORM\Column(type="integer",nullable=false) */
    private $partnerszamlatipus = 0;

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
	 * @ORM\return ArrayCollection
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

    /**
     * @return mixed
     */
    public function getJutalek() {
        return $this->jutalek;
    }

    /**
     * @param mixed $jutalek
     */
    public function setJutalek($jutalek) {
        $this->jutalek = $jutalek;
    }

	/**
	 * @return mixed
	 */
	public function getSessionid() {
		return $this->sessionid;
	}

	/**
	 * @param mixed $sessionid
	 */
	public function setSessionid($sessionid) {
		$this->sessionid = $sessionid;
	}

	public function getPartnerszamlatipus() {
		return $this->partnerszamlatipus;
	}

	public function setPartnerszamlatipus($val) {
		$this->partnerszamlatipus = $val;
	}

	/**
	 * @return \Entities\Valutanem
	 */
	public function getPartnervalutanem() {
		if (!$this->id && !$this->partnervalutanem) {
			$this->setPartnervalutanem(\mkw\Store::getParameter(\mkw\consts::Valutanem));
		}
		return $this->partnervalutanem;
	}

	public function getPartnervalutanemnev() {
		$vn = $this->getPartnervalutanem();
		if ($vn) {
			return $vn->getNev();
		}
		return '';
	}

	public function getPartnervalutanemId() {
		$vn = $this->getPartnervalutanem();
		if ($vn) {
			return $vn->getId();
		}
		return '';
	}

	/**
	 * @param \Entities\Valutanem $val
	 */
	public function setPartnervalutanem($val) {
		if (!($val instanceof \Entities\Valutanem)) {
			$val = \mkw\Store::getEm()->getRepository('Entities\Valutanem')->find($val);
		}
		if ($this->partnervalutanem !== $val) {
			$this->partnervalutanem = $val;
		}
	}

	public function removePartnervalutanem() {
		if ($this->partnervalutanem !== null) {
			$this->partnervalutanem = null;
		}
	}

	public function getPartnertermekarazonosito() {
		return $this->partnertermekarazonosito;
	}

	public function setPartnertermekarazonosito($v) {
		$this->partnertermekarazonosito = $v;
	}

	public function getPartnerszallitasimod() {
		return $this->partnerszallitasimod;
	}

	public function getPartnerszallitasimodNev() {
		if ($this->partnerszallitasimod) {
			return $this->partnerszallitasimod->getNev();
		}
		return '';
	}

	public function getPartnerszallitasimodId() {
		if ($this->partnerszallitasimod) {
			return $this->partnerszallitasimod->getId();
		}
		return '';
	}

	public function setPartnerszallitasimod($val) {
		if ($this->partnerszallitasimod !== $val) {
			$this->partnerszallitasimod = $val;
		}
	}

	public function removePartnerszallitasimod() {
		if ($this->partnerszallitasimod !== null) {
			$this->partnerszallitasimod = null;
		}
	}

	public function getPartnerbizonylatnyelv() {
		return $this->partnerbizonylatnyelv;
	}

	public function setPartnerbizonylatnyelv($adat) {
		$this->partnerbizonylatnyelv = $adat;
	}

	/**
	 *
	 * @ORM\return Fizmod
	 */
	public function getPartnerfizmod() {
		return $this->partnerfizmod;
	}

	public function getPartnerfizmodNev() {
		if ($this->partnerfizmod) {
			return $this->partnerfizmod->getNev();
		}
		return '';
	}

	public function getPartnerfizmodId() {
		if ($this->partnerfizmod) {
			return $this->partnerfizmod->getId();
		}
		return '';
	}

	public function setPartnerfizmod(Fizmod $fizmod) {
		$this->partnerfizmod = $fizmod;
	}

}