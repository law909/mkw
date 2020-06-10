<?php
namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity(repositoryClass="Entities\PartnerRepository")
 *  @ORM\Table(name="partner",
 * 	options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"},
 * indexes={
 *      @ORM\Index(name="partneremail_idx",columns={"email"})
 * })
 * */
class Partner {

	/**
	 * @ORM\Id @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id = 0;

	/**
	 * @Gedmo\Timestampable(on="create")
	 * @ORM\Column(type="datetime",nullable=true)
	 */
	private $created;

	/**
	 * @Gedmo\Timestampable(on="update")
	 * @ORM\Column(type="datetime",nullable=true)
	 */
	private $lastmod;

	/** @ORM\Column(type="string",length=255,nullable=true) */
	private $idegenkod = '';

	/** @ORM\Column(type="string",length=255,nullable=true) */
	private $sessionid = '';

	/** @ORM\Column(type="string",length=255,nullable=true) */
	private $jelszo;

	/** @ORM\Column(type="smallint",nullable=true) */
	private $inaktiv = 0;

	/** @ORM\Column(type="string",length=255,nullable=true) */
	private $nev = '';

	/** @ORM\Column(type="string",length=255,nullable=true) */
	private $vezeteknev = '';

	/** @ORM\Column(type="string",length=255,nullable=true) */
	private $keresztnev = '';

	/** @ORM\Column(type="string",length=13,nullable=true) */
	private $adoszam = '';

	/** @ORM\Column(type="string",length=30,nullable=true) */
	private $euadoszam = '';

	/** @ORM\Column(type="string",length=20,nullable=true) */
	private $mukengszam = '';

	/** @ORM\Column(type="string",length=20,nullable=true) */
	private $jovengszam = '';

	/** @ORM\Column(type="string",length=20,nullable=true) */
	private $ostermszam = '';

	/** @ORM\Column(type="string",length=20,nullable=true) */
	private $valligszam = '';

	/** @ORM\Column(type="string",length=20,nullable=true) */
	private $fvmszam = '';

	/** @ORM\Column(type="string",length=20,nullable=true) */
	private $cjszam = '';

	/** @ORM\Column(type="string",length=20,nullable=true) */
	private $statszamjel = '';

	/** @ORM\Column(type="string",length=10,nullable=true) */
	private $irszam = '';

	/** @ORM\Column(type="string",length=40,nullable=true) */
	private $varos = '';

	/** @ORM\Column(type="string",length=60,nullable=true) */
	private $utca = '';

    /** @ORM\Column(type="string",length=40,nullable=true) */
    private $hazszam = '';

	/** @ORM\Column(type="string",length=10,nullable=true) */
	private $lirszam = '';

	/** @ORM\Column(type="string",length=40,nullable=true) */
	private $lvaros = '';

	/** @ORM\Column(type="string",length=60,nullable=true) */
	private $lutca = '';

    /** @ORM\Column(type="string",length=40,nullable=true) */
    private $lhazszam = '';

    /** @ORM\Column(type="string",length=40,nullable=true) */
	private $telefon = '';

	/** @ORM\Column(type="string",length=40,nullable=true) */
	private $mobil = '';

    /** @ORM\Column(type="string",length=6,nullable=true) */
    private $telkorzet;

    /** @ORM\Column(type="string",length=40,nullable=true) */
    private $telszam = '';

	/** @ORM\Column(type="string",length=40,nullable=true) */
	private $fax = '';

	/** @ORM\Column(type="string",length=100,nullable=true) */
	private $email = '';

	/** @ORM\Column(type="string",length=200,nullable=true) */
	private $honlap = '';

	/** @ORM\Column(type="text",nullable=true) */
	private $megjegyzes = '';

	/** @ORM\Column(type="string",length=36,nullable=true) */
	private $syncid = '';

	/**
	 * @ORM\ManyToOne(targetEntity="Uzletkoto",inversedBy="partnerek")
	 * @ORM\JoinColumn(name="uzletkoto_id", referencedColumnName="id",nullable=true,onDelete="set null")
	 */
	private $uzletkoto;

	/** @ORM\OneToMany(targetEntity="Teendo", mappedBy="partner", cascade={"persist"}) */
	private $teendok;

	/** @ORM\OneToMany(targetEntity="Esemeny", mappedBy="partner", cascade={"persist"}) */
	private $esemenyek;

	/**
	 * @ORM\ManyToMany(targetEntity="Partnercimketorzs",inversedBy="partnerek")
	 * @ORM\JoinTable(name="partner_cimkek",
	 *  joinColumns={@ORM\JoinColumn(name="partner_id",referencedColumnName="id",onDelete="cascade")},
	 *  inverseJoinColumns={@ORM\JoinColumn(name="cimketorzs_id",referencedColumnName="id",onDelete="cascade")}
	 *  )
	 */
	private $cimkek;

	/**
	 * @ORM\ManyToOne(targetEntity="Fizmod")
	 * @ORM\JoinColumn(name="fizmod_id",referencedColumnName="id",nullable=true,onDelete="set null")
	 */
	private $fizmod;

	/** @ORM\OneToMany(targetEntity="Bizonylatfej", mappedBy="partner",cascade={"persist"}) */
	private $bizonylatfejek;

    /** @ORM\OneToMany(targetEntity="Bankbizonylatfej", mappedBy="partner",cascade={"persist"}) */
    private $bankbizonylatfejek;

    /** @ORM\OneToMany(targetEntity="Bankbizonylattetel", mappedBy="partner",cascade={"persist"}) */
    private $bankbizonylattetelek;

    /** @ORM\OneToMany(targetEntity="Penztarbizonylatfej", mappedBy="partner",cascade={"persist"}) */
    private $penztarbizonylatfejek;

	/** @ORM\OneToMany(targetEntity="Kosar", mappedBy="partner",cascade={"persist"}) */
	private $kosarak;

	/** @ORM\Column(type="integer",nullable=true) */
	private $fizhatido = 0;

	/** @ORM\Column(type="string",length=255,nullable=true) */
	private $szallnev = '';

	/** @ORM\Column(type="string",length=10,nullable=true) */
	private $szallirszam = '';

	/** @ORM\Column(type="string",length=40,nullable=true) */
	private $szallvaros = '';

	/** @ORM\Column(type="string",length=60,nullable=true) */
	private $szallutca = '';

    /** @ORM\Column(type="string",length=40,nullable=true) */
    private $szallhazszam = '';

	/** @ORM\Column(type="integer",nullable=true) */
	private $nem;

	/** @ORM\Column(type="date",nullable=true) */
	private $szuletesiido;

	/** @ORM\Column(type="boolean") */
	private $akcioshirlevelkell = false;

	/** @ORM\Column(type="boolean") */
	private $ujdonsaghirlevelkell = false;

	/** @ORM\Column(type="datetime",nullable=true) */
	private $utolsoklikk;

	/** @ORM\OneToMany(targetEntity="TermekErtesito", mappedBy="partner",cascade={"persist"}) */
	private $termekertesitok;

	/** @ORM\Column(type="boolean") */
	private $vendeg = false;

	/** @ORM\Column(type="string",length=32,nullable=true) */
	private $ip;

	/** @ORM\Column(type="string",length=255,nullable=true) */
	private $referrer;

	/** @ORM\Column(type="boolean") */
	private $szallito = false;

    /** @ORM\Column(type="boolean") */
    private $exportbacsakkeszlet = false;

    /** @ORM\Column(type="string",length=64,nullable=true) */
    private $passwordreminder;

    /** @ORM\Column(type="string",length=64,nullable=true) */
    private $oldloginname;

    /** @ORM\Column(type="integer",nullable=true) */
    private $szallitasiido;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $banknev;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $bankcim;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $iban;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $swift;

    /** @ORM\Column(type="integer",nullable=false) */
    private $szamlatipus = 0;

    /** @ORM\Column(type="string",length=10,nullable=true) */
    private $bizonylatnyelv;

    /**
     * @ORM\ManyToOne(targetEntity="Valutanem",inversedBy="partnerek")
     * @ORM\JoinColumn(name="valutanem_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $valutanem;

    /** @ORM\Column(type="string",length=255,nullable=true) */
	private $termekarazonosito;

    /**
     * @ORM\ManyToOne(targetEntity="Szallitasimod",inversedBy="partnerek")
     * @ORM\JoinColumn(name="szallitasimod_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $szallitasimod;

	/** @ORM\OneToMany(targetEntity="PartnerTermekcsoportKedvezmeny", mappedBy="partner", cascade={"persist", "remove"}) */
	private $termekcsoportkedvezmenyek;

    /** @ORM\OneToMany(targetEntity="PartnerTermekKedvezmeny", mappedBy="partner", cascade={"persist", "remove"}) */
    private $termekkedvezmenyek;

    /**
     * @ORM\ManyToOne(targetEntity="Partnertipus",inversedBy="partnerek")
     * @ORM\JoinColumn(name="partnertipus_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $partnertipus;

    /**
     * @ORM\ManyToOne(targetEntity="Orszag",inversedBy="partnerek")
     * @ORM\JoinColumn(name="orszag_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $orszag;

    /** @ORM\Column(type="integer",nullable=true) */
    private $mijszmiotajogazik = 0;

    /** @ORM\Column(type="integer",nullable=true) */
    private $mijszmiotatanit = 0;

	/**
	 * @ORM\Column(type="boolean")
     */
    private $ezuzletkoto = false;

    /** @ORM\OneToMany(targetEntity="PartnerMIJSZOklevel", mappedBy="partner", cascade={"persist", "remove"}) */
    private $mijszoklevelek;

    /** @ORM\OneToMany(targetEntity="PartnerMIJSZPune", mappedBy="partner", cascade={"persist", "remove"}) */
    private $mijszpune;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $mijszmembershipbesideshu;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $mijszbusiness;

    /** @ORM\Column(type="boolean") */
    private $mijszexporttiltva = false;

    /** @ORM\Column(type="integer", nullable=true) */
    private $migrid;

    /** @ORM\Column(type="boolean") */
    private $ktdatvallal = false;

    /** @ORM\Column(type="boolean") */
    private $ktdatalany = false;

    /** @ORM\Column(type="string",length=20,nullable=true) */
    private $ktdszerzszam;

    /** @ORM\OneToMany(targetEntity="Kontakt", mappedBy="partner",cascade={"persist"}) */
    private $kontaktok;

    /** @ORM\Column(type="integer", nullable=true) */
    private $minicrmprojectid;

    /** @ORM\Column(type="integer", nullable=true) */
    private $minicrmcontactid;
    /**
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="Dolgozo")
     * @ORM\JoinColumn(name="createdby", referencedColumnName="id")
     */
    private $createdby;

    /**
     * @Gedmo\Blameable(on="update")
     * @ORM\ManyToOne(targetEntity="Dolgozo")
     * @ORM\JoinColumn(name="updatedby", referencedColumnName="id")
     */
    private $updatedby;
    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $munkahelyneve;
    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $foglalkozas;
    /** @ORM\OneToMany(targetEntity="PartnerMIJSZOralatogatas", mappedBy="partner", cascade={"persist", "remove"}) */
    private $mijszoralatogatas;
    /** @ORM\OneToMany(targetEntity="PartnerMIJSZOralatogatas", mappedBy="tanar", cascade={"persist", "remove"}) */
    private $mijszoralatogatastanar;
    /** @ORM\OneToMany(targetEntity="PartnerMIJSZTanitas", mappedBy="partner", cascade={"persist", "remove"}) */
    private $mijsztanitas;
    /** @ORM\OneToMany(targetEntity="PartnerDok", mappedBy="partner", cascade={"persist", "remove"}) */
    private $partnerdokok;
    /** @ORM\Column(type="integer",nullable=true) */
    private $emagid;
    /** @ORM\Column(type="boolean") */
    private $anonymizalnikell = false;
    /** @ORM\Column(type="date",nullable=true) */
    private $anonymkeresdatum;
    /** @ORM\Column(type="boolean") */
    private $anonym = false;
    /** @ORM\Column(type="date",nullable=true) */
    private $anonymdatum;
    /** @ORM\Column(type="boolean") */
    private $apireg = false;
    /**
     * @ORM\ManyToOne(targetEntity="Apiconsumer")
     * @ORM\JoinColumn(name="apiconsumer_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Apiconsumer
     */
    private $apiconsumer;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $apiconsumernev;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $szamlalevelmegszolitas;

    /** @ORM\Column(type="boolean") */
    private $kulsos = false;


    public function __construct() {
		$this->cimkek = new \Doctrine\Common\Collections\ArrayCollection();
		$this->bizonylatfejek = new \Doctrine\Common\Collections\ArrayCollection();
        $this->bankbizonylatfejek = new \Doctrine\Common\Collections\ArrayCollection();
        $this->bankbizonylattetelek = new \Doctrine\Common\Collections\ArrayCollection();
        $this->penztarbizonylatfejek = new \Doctrine\Common\Collections\ArrayCollection();
		$this->kosarak = new \Doctrine\Common\Collections\ArrayCollection();
		$this->termekertesitok = new \Doctrine\Common\Collections\ArrayCollection();
		$this->termekcsoportkedvezmenyek = new \Doctrine\Common\Collections\ArrayCollection();
        $this->termekkedvezmenyek = new \Doctrine\Common\Collections\ArrayCollection();
        $this->mijszoklevelek = new \Doctrine\Common\Collections\ArrayCollection();
        $this->kontaktok = new \Doctrine\Common\Collections\ArrayCollection();
        $this->mijszpune = new \Doctrine\Common\Collections\ArrayCollection();
        $this->mijszoralatogatas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->mijszoralatogatastanar = new \Doctrine\Common\Collections\ArrayCollection();
        $this->mijsztanitas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->partnerdokok = new \Doctrine\Common\Collections\ArrayCollection();
	}

	public function doAnonym() {
        $this->vezeteknev = \mkw\store::generateRandomStr(strlen($this->vezeteknev));
        $this->keresztnev = \mkw\store::generateRandomStr(strlen($this->keresztnev));
        $this->nev = implode(' ', array($this->vezeteknev, $this->keresztnev));
        $this->adoszam = \mkw\store::generateRandomStr(strlen($this->adoszam), '1234567890');
        $this->euadoszam = \mkw\store::generateRandomStr(strlen($this->euadoszam), '1234567890');
        $this->mukengszam = \mkw\store::generateRandomStr(strlen($this->mukengszam));
        $this->jovengszam = \mkw\store::generateRandomStr(strlen($this->jovengszam));
        $this->ostermszam = \mkw\store::generateRandomStr(strlen($this->ostermszam));
        $this->valligszam = \mkw\store::generateRandomStr(strlen($this->valligszam));
        $this->fvmszam = \mkw\store::generateRandomStr(strlen($this->fvmszam));
        $this->cjszam = \mkw\store::generateRandomStr(strlen($this->cjszam));
        $this->statszamjel = \mkw\store::generateRandomStr(strlen($this->statszamjel));
        $this->irszam = \mkw\store::generateRandomStr(strlen($this->irszam), '1234567890');
        $this->varos = \mkw\store::generateRandomStr(strlen($this->varos));
        $this->utca = \mkw\store::generateRandomStr(strlen($this->utca));
        $this->hazszam = \mkw\store::generateRandomStr(strlen($this->hazszam), '1234567890');
        $this->clearGDPRData();
    }

    public function clearGDPRData() {
        $this->lirszam = '';
        $this->lvaros = '';
        $this->lutca = '';
        $this->lhazszam = '';
        $this->telefon = '';
        $this->mobil = '';
        $this->fax = '';
        $this->email = uniqid($this->vezeteknev . '.' . $this->keresztnev, true) . '@mail.local';
        $this->honlap = '';
        $this->szallnev = '';
        $this->szallirszam = '';
        $this->szallvaros = '';
        $this->szallutca = '';
        $this->szallhazszam = '';
        $this->nem = null;
        $this->szuletesiido = null;
        $this->ip = '';
        $this->referrer = '';
        $this->banknev = '';
        $this->bankcim = '';
        $this->iban = '';
        $this->swift = '';
        $this->minicrmprojectid = 0;
        $this->minicrmcontactid = 0;
        $this->munkahelyneve = '';
        $this->foglalkozas = '';
    }

    public function toA2a() {
        $x = array();
        $x['id'] = $this->getId();
        $x['nev'] = $this->getNev();
        $x['vezeteknev'] = $this->getVezeteknev();
        $x['keresztnev'] = $this->getKeresztnev();
        $x['irszam'] = $this->getIrszam();
        $x['varos'] = $this->getVaros();
        $x['utca'] = $this->getUtca();
        $x['hazszam'] = $this->getHazszam();
        $x['adoszam'] = $this->getAdoszam();
        $x['euadoszam'] = $this->getEuadoszam();
        $x['email'] = $this->getEmail();
        $x['telefon'] = $this->getTelefon();
        $x['szallnev'] = $this->getSzallnev();
        $x['szallirszam'] = $this->getSzallirszam();
        $x['szallvaros'] = $this->getSzallvaros();
        $x['szallutca'] = $this->getSzallutca();
        $x['szallhazszam'] = $this->getSzallhazszam();
        $x['vendeg'] = $this->getVendeg();
        return $x;
    }

	public function getCim() {
		$cim = $this->irszam;
		if (($cim !== '') && ($this->varos !== '')) {
			$cim.=' ';
		}
		$cim .= $this->varos;
		if (($cim !== '') && ($this->utca !== '')) {
			$cim .= ', ';
		}
		$cim .= $this->utca;
		if (($cim !== '') && ($this->hazszam !== '')) {
		    $cim .= ' ';
        }
        $cim .= $this->hazszam;
		return $cim;
	}

	public function getLCim() {
		$cim = $this->lirszam;
		if (($cim !== '') && ($this->lvaros !== '')) {
			$cim.=' ';
		}
		$cim.=$this->lvaros;
		if (($cim !== '') && ($this->lutca !== '')) {
			$cim.=', ';
		}
		$cim.=$this->lutca;
        if (($cim !== '') && ($this->lhazszam !== '')) {
            $cim .= ' ';
        }
        $cim .= $this->lhazszam;
		return $cim;
	}

	public function getId() {
		return $this->id;
	}

	public function getInaktiv() {
		return $this->inaktiv;
	}

	public function setInaktiv($inaktiv) {
		$this->inaktiv = $inaktiv;
	}

	public function getNev() {
		return $this->nev;
	}

	public function setNev($nev) {
		$this->nev = $nev;
	}

	public function getVezeteknev() {
		return $this->vezeteknev;
	}

	public function setVezeteknev($adat) {
		$this->vezeteknev = $adat;
	}

	public function getKeresztnev() {
		return $this->keresztnev;
	}

	public function setKeresztnev($adat) {
		$this->keresztnev = $adat;
	}

	public function getAdoszam() {
		return $this->adoszam;
	}

	public function setAdoszam($adoszam) {
		$this->adoszam = $adoszam;
	}

	public function getEuadoszam() {
		return $this->euadoszam;
	}

	public function setEuadoszam($euadoszam) {
		$this->euadoszam = $euadoszam;
	}

	public function getMukengszam() {
		return $this->mukengszam;
	}

	public function setMukengszam($mukengszam) {
		$this->mukengszam = $mukengszam;
	}

	public function getJovengszam() {
		return $this->jovengszam;
	}

	public function setJovengszam($jovengszam) {
		$this->jovengszam = $jovengszam;
	}

	public function getOstermszam() {
		return $this->ostermszam;
	}

	public function setOstermszam($ostermszam) {
		$this->ostermszam = $ostermszam;
	}

	public function getValligszam() {
		return $this->valligszam;
	}

	public function setValligszam($valligszam) {
		$this->valligszam = $valligszam;
	}

	public function getFvmszam() {
		return $this->fvmszam;
	}

	public function setFvmszam($fvmszam) {
		$this->fvmszam = $fvmszam;
	}

	public function getCjszam() {
		return $this->cjszam;
	}

	public function setCjszam($cjszam) {
		$this->cjszam = $cjszam;
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

	public function getLirszam() {
		return $this->lirszam;
	}

	public function setLirszam($lirszam) {
		$this->lirszam = $lirszam;
	}

	public function getLvaros() {
		return $this->lvaros;
	}

	public function setLvaros($lvaros) {
		$this->lvaros = $lvaros;
	}

	public function getLutca() {
		return $this->lutca;
	}

	public function setLutca($lutca) {
		$this->lutca = $lutca;
	}

	public function getTelefon() {
		return $this->telefon;
	}

	public function setTelefon($telefon) {
		$this->telefon = $telefon;
	}

	public function getMobil() {
		return $this->mobil;
	}

	public function setMobil($mobil) {
		$this->mobil = $mobil;
	}

	public function getFax() {
		return $this->fax;
	}

	public function setFax($fax) {
		$this->fax = $fax;
	}

	public function getEmail() {
		return $this->email;
	}

	public function setEmail($email) {
		$this->email = $email;
	}

	public function getHonlap() {
		return $this->honlap;
	}

	public function setHonlap($honlap) {
		$this->honlap = $honlap;
	}

	public function getMegjegyzes() {
		return $this->megjegyzes;
	}

	public function setMegjegyzes($megjegyzes) {
		$this->megjegyzes = $megjegyzes;
	}

	public function getSyncid() {
		return $this->syncid;
	}

	public function setSyncid($syncid) {
		$this->syncid = $syncid;
	}

	/**
	 *
	 * @ORM\return Uzletkoto
	 */
	public function getUzletkoto() {
		return $this->uzletkoto;
	}

	public function getUzletkotoNev() {
		if ($this->uzletkoto) {
			return $this->uzletkoto->getNev();
		}
		return '';
	}

	public function getUzletkotoId() {
		if ($this->uzletkoto) {
			return $this->uzletkoto->getId();
		}
		return '';
	}

	public function setUzletkoto(Uzletkoto $uzletkoto) {
		if ($this->uzletkoto !== $uzletkoto) {
			$this->uzletkoto = $uzletkoto;
			$uzletkoto->addPartner($this);
		}
	}

	public function removeUzletkoto() {
		if ($this->uzletkoto !== null) {
			$uzletkoto = $this->uzletkoto;
			$this->uzletkoto = null;
			$uzletkoto->removePartner($this);
		}
	}

	public function addEsemeny($esemeny) {
		if (!$this->esemenyek->contains($esemeny)) {
			$this->esemenyek->add($esemeny);
			$esemeny->setPartner($this);
		}
	}

	public function getEsemenyek() {
		return $this->esemenyek;
	}

	public function removeEsemeny($esemeny) {
		if ($this->esemenyek->removeElement($esemeny)) {
			$esemeny->removePartner();
		}
	}

	public function addTeendo($teendo) {
		if (!$this->teendok->contains($teendo)) {
			$this->teendok->add($teendo);
			$teendo->setPartner($this);
		}
	}

	public function getTeendok() {
		return $this->teendok;
	}

	public function removeTeendo($teendo) {
		if ($this->teendok->removeElement($teendo)) {
			$teendo->removePartner();
			return true;
		}
		return false;
	}

	/**
	 *
	 * @ORM\return ArrayCollection
	 */
	public function getCimkek() {
		return $this->cimkek;
	}

	public function getCimkeNevek() {
		$res = array();
		foreach ($this->cimkek as $cimke) {
			$res[] = array('nev' => $cimke->getNev());
		}
		return $res;
	}

	public function addCimke(Cimketorzs $cimke) {
		if (!$this->cimkek->contains($cimke)) {
			$this->cimkek->add($cimke);
			$cimke->addPartner($this);
		}
	}

	public function removeCimke(Cimketorzs $cimke) {
		if ($this->cimkek->removeElement($cimke)) {
//			$cimke->removePartner($this);  // deleted for speed
			return true;
		}
		return false;
	}

	public function removeAllCimke() {
		foreach ($this->cimkek as $cimke) {
			$this->removeCimke($cimke);
		}
	}

	/**
	 *
	 * @ORM\return Fizmod
	 */
	public function getFizmod() {
		return $this->fizmod;
	}

	public function getFizmodNev() {
		if ($this->fizmod) {
			return $this->fizmod->getNev();
		}
		return '';
	}

	public function getFizmodId() {
		if ($this->fizmod) {
			return $this->fizmod->getId();
		}
		return '';
	}

	public function setFizmod(Fizmod $fizmod) {
		$this->fizmod = $fizmod;
	}

	public function getIdegenkod() {
		return $this->idegenkod;
	}

	public function setIdegenkod($idegenkod) {
		$this->idegenkod = $idegenkod;
	}

	public function getStatszamjel() {
		return $this->statszamjel;
	}

	public function setStatszamjel($statszamjel) {
		$this->statszamjel = $statszamjel;
	}

	public function getFizhatido() {
		return $this->fizhatido;
	}

	public function setFizhatido($val) {
		$this->fizhatido = $val;
	}

	public function getLastmod() {
		return $this->lastmod;
	}

    public function getLastmodStr() {
        if ($this->getLastmod()) {
            return $this->getLastmod()->format(\mkw\store::$DateTimeFormat);
        }
        return '';
    }

	public function getCreated() {
		return $this->created;
	}

    public function getCreatedStr() {
        if ($this->getCreated()) {
            return $this->getCreated()->format(\mkw\store::$DateTimeFormat);
        }
        return '';
    }

	public function getSzallnev() {
		return $this->szallnev;
	}

	public function setSzallnev($adat) {
		$this->szallnev = $adat;
	}

	public function getSzallirszam() {
		return $this->szallirszam;
	}

	public function setSzallirszam($adat) {
		$this->szallirszam = $adat;
	}

	public function getSzallvaros() {
		return $this->szallvaros;
	}

	public function setSzallvaros($adat) {
		$this->szallvaros = $adat;
	}

	public function getSzallutca() {
		return $this->szallutca;
	}

	public function setSzallutca($adat) {
		$this->szallutca = $adat;
	}

	public function getNem() {
		return $this->nem;
	}

	public function setNem($adat) {
		$this->nem = $adat;
	}

	public function getSzuletesiido() {
		return $this->szuletesiido;
	}

	public function getSzuletesiidoStr() {
		if ($this->getSzuletesiido()) {
			return $this->getSzuletesiido()->format(\mkw\store::$DateFormat);
		}
		return '';
	}

	public function setSzuletesiido($adat = '') {
		if ($adat != '') {
			$this->szuletesiido = new \DateTime(\mkw\store::convDate($adat));
		}
	}

	public function getAkcioshirlevelkell() {
		return $this->akcioshirlevelkell;
	}

	public function setAkcioshirlevelkell($adat) {
		$this->akcioshirlevelkell = $adat;
	}

	public function getUjdonsaghirlevelkell() {
		return $this->ujdonsaghirlevelkell;
	}

	public function setUjdonsaghirlevelkell($adat) {
		$this->ujdonsaghirlevelkell = $adat;
	}

	public function getSessionid() {
		return $this->sessionid;
	}

	public function setSessionid($adat) {
		$this->sessionid = $adat;
	}

	public function getJelszo() {
		return $this->jelszo;
	}

    public function setMkwJelszo($adat) {
        $this->jelszo = sha1($adat . \mkw\store::getSalt());
    }

	public function setJelszo($adat) {
		$this->jelszo = sha1(strtoupper(md5($adat)) . \mkw\store::getSalt());
	}

	public function checkJelszo($adat) {
        $so = \mkw\store::getSalt();
		$v = sha1(strtoupper(md5($adat)) . $so);
		return $this->jelszo === $v;
	}

	public function getUtolsoklikk() {
		return $this->utolsoklikk;
	}

	public function setUtolsoklikk() {
		$this->utolsoklikk = new \DateTime();
	}

	public function getVendeg() {
		return $this->vendeg;
	}

	public function setVendeg($val) {
		$this->vendeg = $val;
	}

	public function getIp() {
		return $this->ip;
	}

	public function setIp($val) {
		$this->ip = $val;
	}

	public function getReferrer() {
		return $this->referrer;
	}

	public function setReferrer($val) {
		$this->referrer = $val;
	}

	public function getSzallito() {
		return $this->szallito;
	}

	public function setSzallito($val) {
		$this->szallito = $val;
	}

    public function getPasswordreminder() {
        return $this->passwordreminder;
    }

    public function setPasswordreminder() {
        $this->passwordreminder = sha1(md5(time() . \mkw\store::getSalt()) . \mkw\store::getSalt());
        return $this->passwordreminder;
    }

    public function clearPasswordreminder() {
        unset($this->passwordreminder);
    }

    public function getOldloginname() {
        return $this->oldloginname;
    }

    public function setOldloginname($name) {
        $this->oldloginname = $name;
    }

    public function getSzallitasiido() {
        return $this->szallitasiido;
    }

    public function setSzallitasiido($adat) {
        $this->szallitasiido = $adat;
    }

    public function getBanknev() {
        return $this->banknev;
    }

    public function setBanknev($val) {
        $this->banknev = $val;
    }

    public function getBankcim() {
        return $this->bankcim;
    }

    public function setBankcim($val) {
        $this->bankcim = $val;
    }

    public function getIban() {
        return $this->iban;
    }

    public function setIban($val) {
        $this->iban = $val;
    }

    public function getSwift() {
        return $this->swift;
    }

    public function setSwift($val) {
        $this->swift = $val;
    }

    public function getSzamlatipus() {
        return $this->szamlatipus;
    }

    public function setSzamlatipus($val) {
        $this->szamlatipus = $val;
    }

    /**
     * @return \Entities\Valutanem
     */
    public function getValutanem() {
        if (!$this->id && !$this->valutanem) {
            $this->setValutanem(\mkw\store::getParameter(\mkw\consts::Valutanem));
        }
        return $this->valutanem;
    }

    public function getValutanemnev() {
        $vn = $this->getValutanem();
        if ($vn) {
            return $vn->getNev();
        }
        return '';
    }

    public function getValutanemId() {
        $vn = $this->getValutanem();
        if ($vn) {
            return $vn->getId();
        }
        return '';
    }

    /**
     * @param \Entities\Valutanem $val
     */
    public function setValutanem($val) {
        if (!($val instanceof \Entities\Valutanem)) {
            $val = \mkw\store::getEm()->getRepository('Entities\Valutanem')->find($val);
        }
        if ($this->valutanem !== $val) {
            $this->valutanem = $val;
        }
    }

    public function removeValutanem() {
        if ($this->valutanem !== null) {
//			$val=$this->valutanem;
            $this->valutanem = null;
//			$val->removeBizonylatfej($this);
        }
    }

    public function getTermekarazonosito() {
        return $this->termekarazonosito;
    }

    public function setTermekarazonosito($v) {
        $this->termekarazonosito = $v;
    }

    public function getSzallitasimod() {
        return $this->szallitasimod;
    }

    public function getSzallitasimodNev() {
        if ($this->szallitasimod) {
            return $this->szallitasimod->getNev();
        }
        return '';
    }

    public function getSzallitasimodId() {
        if ($this->szallitasimod) {
            return $this->szallitasimod->getId();
        }
        return '';
    }

    public function setSzallitasimod($val) {
        if ($this->szallitasimod !== $val) {
            $this->szallitasimod = $val;
        }
    }

    public function removeSzallitasimod() {
        if ($this->szallitasimod !== null) {
            $this->szallitasimod = null;
        }
    }

    public function getBizonylatnyelv() {
        return $this->bizonylatnyelv;
    }

    public function setBizonylatnyelv($adat) {
        $this->bizonylatnyelv = $adat;
    }

	/**
	 * @return mixed
	 */
	public function getEzuzletkoto() {
		return $this->ezuzletkoto;
	}

	/**
	 * @param mixed $ezuzletkoto
	 */
	public function setEzuzletkoto($ezuzletkoto) {
		$this->ezuzletkoto = $ezuzletkoto;
	}

    /**
     * @return \Entities\PartnerTermekcsoportKedvezmeny
     */
    public function getTermekcsoportkedvezmenyek() {
        return $this->termekcsoportkedvezmenyek;
    }

    /**
     * @return \Entities\PartnerTermekKedvezmeny
     */
    public function getTermekkedvezmenyek() {
        return $this->termekkedvezmenyek;
    }

    /**
     * @return \Entities\Partnertipus
     */
    public function getPartnertipus() {
        return $this->partnertipus;
    }

    public function getPartnertipusNev() {
        if ($this->partnertipus) {
            return $this->partnertipus->getNev();
        }
        return '';
    }

    public function getPartnertipusId() {
        if ($this->partnertipus) {
            return $this->partnertipus->getId();
        }
        return '';
    }

    public function setPartnertipus($val) {
        if ($this->partnertipus !== $val) {
            $this->partnertipus = $val;
        }
    }

    public function removePartnertipus() {
        if ($this->partnertipus !== null) {
            $this->partnertipus = null;
        }
    }

    public function isDefaultOrszag() {
        if ($this->orszag) {
            return $this->orszag->isDefault();
        }
        return true;
    }

    public function getOrszag() {
        return $this->orszag;
    }

    public function getOrszagNev() {
        if ($this->orszag) {
            return $this->orszag->getNev();
        }
        return '';
    }

    public function getOrszagId() {
        if ($this->orszag) {
            return $this->orszag->getId();
        }
        return '';
    }

    public function setOrszag($val) {
        if ($this->orszag !== $val) {
            $this->orszag = $val;
        }
    }

    public function removeOrszag() {
        if ($this->orszag !== null) {
            $this->orszag = null;
        }
    }

    /**
     * @return mixed
     */
    public function getMijszmiotajogazik() {
        return $this->mijszmiotajogazik;
    }

    /**
     * @param mixed $mijszmiotajogazik
     */
    public function setMijszmiotajogazik($mijszmiotajogazik) {
        $this->mijszmiotajogazik = $mijszmiotajogazik;
    }

    /**
     * @return mixed
     */
    public function getMijszmiotatanit() {
        return $this->mijszmiotatanit;
    }

    /**
     * @param mixed $mijszmiotatanit
     */
    public function setMijszmiotatanit($mijszmiotatanit) {
        $this->mijszmiotatanit = $mijszmiotatanit;
    }

    /**
     * @return \Entities\PartnerMIJSZOklevel
     */
    public function getMijszoklevelek() {
        return $this->mijszoklevelek;
    }

    /**
     * @return \Entities\PartnerMIJSZPune
     */
    public function getMijszpune() {
        return $this->mijszpune;
    }

    /**
     * @return \Entities\PartnerMIJSZOralatogatas
     */
    public function getMijszoralatogatas() {
        return $this->mijszoralatogatas;
    }

    /**
     * @return \Entities\PartnerMIJSZOralatogatas
     */
    public function getMijszoralatogatastanar() {
        return $this->mijszoralatogatastanar;
    }

    /**
     * @return \Entities\PartnerMIJSZTanitas
     */
    public function getMijsztanitas() {
        return $this->mijsztanitas;
    }

    /**
     * @return mixed
     */
    public function getMijszmembershipbesideshu() {
        return $this->mijszmembershipbesideshu;
    }

    /**
     * @param mixed $mijszmembershipbesideshu
     */
    public function setMijszmembershipbesideshu($mijszmembershipbesideshu) {
        $this->mijszmembershipbesideshu = $mijszmembershipbesideshu;
    }

    /**
     * @return mixed
     */
    public function getMijszbusiness() {
        return $this->mijszbusiness;
    }

    /**
     * @param mixed $mijszbusiness
     */
    public function setMijszbusiness($mijszbusiness) {
        $this->mijszbusiness = $mijszbusiness;
    }

    /**
     * @return mixed
     */
    public function getMijszexporttiltva() {
        return $this->mijszexporttiltva;
    }

    /**
     * @param mixed $mijszexporttiltva
     */
    public function setMijszexporttiltva($mijszexporttiltva) {
        $this->mijszexporttiltva = $mijszexporttiltva;
    }

    /**
     * @return mixed
     */
    public function getMigrid() {
        return $this->migrid;
    }

    /**
     * @param mixed $migrid
     */
    public function setMigrid($migrid) {
        $this->migrid = $migrid;
    }

    /**
     * @return mixed
     */
    public function getKtdatvallal() {
        return $this->ktdatvallal;
    }

    /**
     * @param mixed $ktdatvallal
     */
    public function setKtdatvallal($ktdatvallal) {
        $this->ktdatvallal = $ktdatvallal;
    }

    /**
     * @return mixed
     */
    public function getKtdatalany() {
        return $this->ktdatalany;
    }

    /**
     * @param mixed $ktdatalany
     */
    public function setKtdatalany($ktdatalany) {
        $this->ktdatalany = $ktdatalany;
    }

    /**
     * @return mixed
     */
    public function getKtdszerzszam() {
        return $this->ktdszerzszam;
    }

    /**
     * @param mixed $ktdszerzszam
     */
    public function setKtdszerzszam($ktdszerzszam) {
        $this->ktdszerzszam = $ktdszerzszam;
    }

    /**
     * @return mixed
     */
    public function getKontaktok() {
        return $this->kontaktok;
    }

    /**
     * @return mixed
     */
    public function getHazszam() {
        return $this->hazszam;
    }

    /**
     * @param mixed $hazszam
     */
    public function setHazszam($hazszam) {
        $this->hazszam = $hazszam;
    }

    /**
     * @return mixed
     */
    public function getLhazszam() {
        return $this->lhazszam;
    }

    /**
     * @param mixed $lhazszam
     */
    public function setLhazszam($lhazszam) {
        $this->lhazszam = $lhazszam;
    }

    /**
     * @return mixed
     */
    public function getSzallhazszam() {
        return $this->szallhazszam;
    }

    /**
     * @param mixed $szallhazszam
     */
    public function setSzallhazszam($szallhazszam) {
        $this->szallhazszam = $szallhazszam;
    }

    /**
     * @return mixed
     */
    public function getMinicrmprojectid() {
        return $this->minicrmprojectid;
    }

    /**
     * @param mixed $minicrmprojectid
     */
    public function setMinicrmprojectid($minicrmprojectid) {
        $this->minicrmprojectid = $minicrmprojectid;
    }

    /**
     * @return mixed
     */
    public function getMinicrmcontactid() {
        return $this->minicrmcontactid;
    }

    /**
     * @param mixed $minicrmcontactid
     */
    public function setMinicrmcontactid($minicrmcontactid) {
        $this->minicrmcontactid = $minicrmcontactid;
    }

    /**
     * @return mixed
     */
    public function getCreatedby() {
        return $this->createdby;
    }

    public function getCreatedbyId() {
        if ($this->createdby) {
            return $this->createdby->getId();
        }
        return null;
    }

    public function getCreatedbyNev() {
        if ($this->createdby) {
            return $this->createdby->getNev();
        }
        return null;
    }

    /**
     * @return mixed
     */
    public function getUpdatedby() {
        return $this->updatedby;
    }

    public function getUpdatedbyId() {
        if ($this->updatedby) {
            return $this->updatedby->getId();
        }
        return null;
    }

    public function getUpdatedbyNev() {
        if ($this->updatedby) {
            return $this->updatedby->getNev();
        }
        return null;
    }

    /**
     * @return mixed
     */
    public function getEmagid() {
        return $this->emagid;
    }

    /**
     * @param mixed $emagid
     */
    public function setEmagid($emagid) {
        $this->emagid = $emagid;
    }

    /**
     * @return mixed
     */
    public function getMunkahelyneve() {
        return $this->munkahelyneve;
    }

    /**
     * @param mixed $munkahelyneve
     */
    public function setMunkahelyneve($munkahelyneve) {
        $this->munkahelyneve = $munkahelyneve;
    }

    /**
     * @return mixed
     */
    public function getFoglalkozas() {
        return $this->foglalkozas;
    }

    /**
     * @param mixed $foglalkozas
     */
    public function setFoglalkozas($foglalkozas) {
        $this->foglalkozas = $foglalkozas;
    }

    public function getPartnerDokok() {
        return $this->partnerdokok;
    }

    public function addPartnerDok(PartnerDok $dok) {
        $this->partnerdokok->add($dok);
        $dok->setPartner($this);
    }

    public function removePartnerDok(PartnerDok $dok) {
        if ($this->partnerdokok->removeElement($dok)) {
            $dok->removePartner($this);
            return true;
        }
        return false;
    }

    /**
     * @return mixed
     */
    public function getAnonymizalnikell() {
        return $this->anonymizalnikell;
    }

    /**
     * @param mixed $anonymizalnikell
     */
    public function setAnonymizalnikell($anonymizalnikell) {
        $this->anonymizalnikell = $anonymizalnikell;
    }

    /**
     * @return mixed
     */
    public function getAnonymkeresdatum() {
        return $this->anonymkeresdatum;
    }

    public function getAnonymkeresdatumStr() {
        if ($this->getAnonymkeresdatum()) {
            return $this->getAnonymkeresdatum()->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    /**
     * @param mixed $anonymkeresdatum
     */
    public function setAnonymkeresdatum($anonymkeresdatum = '') {
        if ($anonymkeresdatum != '') {
            $this->anonymkeresdatum = new \DateTime(\mkw\store::convDate($anonymkeresdatum));
        }
    }

    /**
     * @return mixed
     */
    public function getAnonym() {
        return $this->anonym;
    }

    /**
     * @param mixed $anonym
     */
    public function setAnonym($anonym) {
        $this->anonym = $anonym;
    }

    /**
     * @return mixed
     */
    public function getAnonymdatum() {
        return $this->anonymdatum;
    }

    public function getAnonymdatumStr() {
        if ($this->getAnonymdatum()) {
            return $this->getAnonymdatum()->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    /**
     * @param mixed $anonymdatum
     */
    public function setAnonymdatum($anonymdatum = '') {
        if ($anonymdatum != '') {
            $this->anonymdatum = new \DateTime(\mkw\store::convDate($anonymdatum));
        }
    }

    /**
     * @return mixed
     */
    public function getBizonylatfejek() {
        return $this->bizonylatfejek;
    }

    /**
     * @return mixed
     */
    public function getBankbizonylatfejek() {
        return $this->bankbizonylatfejek;
    }

    /**
     * @return mixed
     */
    public function getBankbizonylattetelek() {
        return $this->bankbizonylattetelek;
    }

    /**
     * @return mixed
     */
    public function getPenztarbizonylatfejek() {
        return $this->penztarbizonylatfejek;
    }

    /**
     * @return mixed
     */
    public function getApireg() {
        return $this->apireg;
    }

    /**
     * @param mixed $apireg
     */
    public function setApireg($apireg) {
        $this->apireg = $apireg;
    }
    /**
     * @return Apiconsumer
     */
    public function getApiconsumer() {
        return $this->apiconsumer;
    }

    /**
     * @param Apiconsumer $apiconsumer
     */
    public function setApiconsumer($apiconsumer) {
        if ($this->apiconsumer !== $apiconsumer) {
            if (!$apiconsumer) {
                $this->removeApiconsumer();
            }
            else {
                $this->apiconsumer = $apiconsumer;
                $this->setApiconsumernev($apiconsumer->getNev());
            }
        }
    }

    public function removeApiconsumer() {
        if (!is_null($this->apiconsumer)) {
            $this->apiconsumer = null;
            $this->setApiconsumernev(null);
        }
    }

    /**
     * @return mixed
     */
    public function getApiconsumernev() {
        return $this->apiconsumernev;
    }

    /**
     * @param mixed $apiconsumernev
     */
    public function setApiconsumernev($apiconsumernev) {
        $this->apiconsumernev = $apiconsumernev;
    }

    /**
     * @return mixed
     */
    public function getSzamlalevelmegszolitas() {
        return $this->szamlalevelmegszolitas;
    }

    /**
     * @param mixed $szamlalevelmegszolitas
     */
    public function setSzamlalevelmegszolitas($szamlalevelmegszolitas) {
        $this->szamlalevelmegszolitas = $szamlalevelmegszolitas;
    }

    /**
     * @return mixed
     */
    public function getTelkorzet() {
        return $this->telkorzet;
    }

    /**
     * @param mixed $telkorzet
     */
    public function setTelkorzet($telkorzet) {
        $this->telkorzet = $telkorzet;
    }

    /**
     * @return mixed
     */
    public function getTelszam() {
        return $this->telszam;
    }

    /**
     * @param mixed $telszam
     */
    public function setTelszam($telszam) {
        $this->telszam = $telszam;
    }

    /**
     * @return bool
     */
    public function isExportbacsakkeszlet() {
        return $this->exportbacsakkeszlet;
    }

    /**
     * @param bool $exportbacsakkeszlet
     */
    public function setExportbacsakkeszlet($exportbacsakkeszlet) {
        $this->exportbacsakkeszlet = $exportbacsakkeszlet;
    }

    /**
     * @return bool
     */
    public function getKulsos() {
        return $this->kulsos;
    }

    /**
     * @param bool $kulsos
     */
    public function setKulsos($kulsos) {
        $this->kulsos = $kulsos;
    }
}