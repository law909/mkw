<?php

namespace Entities;

use mkw\store;

/** @Entity(repositoryClass="Entities\BizonylatfejRepository")
 *  @Table(name="bizonylatfej")
 *  @HasLifecycleCallbacks
 * */
class Bizonylatfej {

	/**
	 * @Id @Column(type="string",length=30,nullable=false)
	 */
	private $id;

	/**
	 * @gedmo:Timestampable(on="create")
	 * @Column(type="datetime",nullable=true)
	 */
	private $created;

	/**
	 * @gedmo:Timestampable(on="create")
	 * @gedmo:Timestampable(on="update")
	 * @Column(type="datetime",nullable=true)
	 */
	private $lastmod;

	/**
	 * @ManyToOne(targetEntity="Bizonylattipus", inversedBy="bizonylatfejek")
	 * @JoinColumn(name="bizonylattipus_id", referencedColumnName="id",nullable=true,onDelete="no action")
	 */
	private $bizonylattipus;

	/** @Column(type="string",length=100,nullable=true) */
	private $bizonylatnev;

	/** @Column(type="integer") */
	private $irany;

	/** @Column(type="boolean",nullable=false) */
	private $nyomtatva = false;

	/** @Column(type="boolean",nullable=false) */
	private $storno = false;

	/** @Column(type="boolean",nullable=false) */
	private $stornozott = false;

	/** @Column(type="boolean",nullable=false) */
	private $penztmozgat;

	/** @Column(type="string",length=255,nullable=false) */
	private $tulajnev;

	/** @Column(type="string",length=10,nullable=false) */
	private $tulajirszam;

	/** @Column(type="string",length=40,nullable=true) */
	private $tulajvaros;

	/** @Column(type="string",length=60,nullable=true) */
	private $tulajutca;

	/** @Column(type="string",length=13,nullable=false) */
	private $tulajadoszam;

	/** @Column(type="string",length=30,nullable=true) */
	private $tulajeuadoszam;

	/** @Column(type="string",length=30,nullable=true) */
	private $erbizonylatszam;

	/** @Column(type="date",nullable=false) */
	private $kelt;

	/** @Column(type="date",nullable=false) */
	private $teljesites;

	/** @Column(type="date",nullable=true) */
	private $esedekesseg;

	/**
	 * @ManyToOne(targetEntity="Fizmod",inversedBy="bizonylatfejek")
	 * @JoinColumn(name="fizmod_id", referencedColumnName="id",nullable=true,onDelete="no action")
	 */
	private $fizmod;

	/** @Column(type="string",length=255,nullable=true) */
	private $fizmodnev;

	/**
	 * @ManyToOne(targetEntity="Szallitasimod",inversedBy="bizonylatfejek")
	 * @JoinColumn(name="szallitasimod_id", referencedColumnName="id",nullable=true,onDelete="no action")
	 */
	private $szallitasimod;

	/** @Column(type="string",length=255,nullable=true) */
	private $szallitasimodnev;

	/** @Column(type="decimal",precision=14,scale=4,nullable=true) */
	private $netto;

	/** @Column(type="decimal",precision=14,scale=4,nullable=true) */
	private $afa;

	/** @Column(type="decimal",precision=14,scale=4,nullable=true) */
	private $brutto;

	/** @Column(type="decimal",precision=14,scale=4,nullable=true) */
	private $fizetendo;

	/**
	 * @ManyToOne(targetEntity="Valutanem",inversedBy="bizonylatfejek")
	 * @JoinColumn(name="valutanem_id", referencedColumnName="id",nullable=true,onDelete="no action")
	 */
	private $valutanem;

	/** @Column(type="string",length=6,nullable=true) */
	private $valutanemnev;

	/** @Column(type="decimal",precision=14,scale=4,nullable=true) */
	private $nettohuf;

	/** @Column(type="decimal",precision=14,scale=4,nullable=true) */
	private $afahuf;

	/** @Column(type="decimal",precision=14,scale=4,nullable=true) */
	private $bruttohuf;

	/** @Column(type="decimal",precision=14,scale=4,nullable=true) */
	private $arfolyam;

	/**
	 * @ManyToOne(targetEntity="Partner",inversedBy="bizonylatfejek")
	 * @JoinColumn(name="partner_id", referencedColumnName="id",nullable=true,onDelete="no action")
	 */
	private $partner;

	/** @Column(type="string",length=255,nullable=true) */
	private $partnernev;

	/** @Column(type="string",length=255,nullable=true) */
	private $partnervezeteknev = '';

	/** @Column(type="string",length=255,nullable=true) */
	private $partnerkeresztnev = '';

	/** @Column(type="string",length=13,nullable=true) */
	private $partneradoszam;

	/** @Column(type="string",length=30,nullable=true) */
	private $partnereuadoszam;

	/** @Column(type="string",length=20,nullable=true) */
	private $partnermukengszam;

	/** @Column(type="string",length=20,nullable=true) */
	private $partnerjovengszam;

	/** @Column(type="string",length=20,nullable=true) */
	private $partnerostermszam;

	/** @Column(type="string",length=20,nullable=true) */
	private $partnervalligszam;

	/** @Column(type="string",length=20,nullable=true) */
	private $partnerfvmszam;

	/** @Column(type="string",length=20,nullable=true) */
	private $partnercjszam;

	/** @Column(type="string",length=20,nullable=true) */
	private $partnerstatszamjel;

	/** @Column(type="string",length=10,nullable=true) */
	private $partnerirszam;

	/** @Column(type="string",length=40,nullable=true) */
	private $partnervaros;

	/** @Column(type="string",length=60,nullable=true) */
	private $partnerutca;

	/** @Column(type="string",length=10,nullable=true) */
	private $partnerlirszam;

	/** @Column(type="string",length=40,nullable=true) */
	private $partnerlvaros;

	/** @Column(type="string",length=60,nullable=true) */
	private $partnerlutca;

	/**
	 * @ManyToOne(targetEntity="Bankszamla",inversedBy="bizonylatfejek")
	 * @JoinColumn(name="bankszamla_id", referencedColumnName="id",nullable=true,onDelete="no action")
	 */
	private $bankszamla;

	/** @Column(type="string",length=255,nullable=true) */
	private $bankszamlanev;

	/** @Column(type="string",length=20,nullable=true) */
	private $swift;

	/**
	 * @ManyToOne(targetEntity="Uzletkoto",inversedBy="bizonylatfejek")
	 * @JoinColumn(name="uzletkoto_id", referencedColumnName="id",nullable=true,onDelete="no action")
	 */
	private $uzletkoto;

	/** @Column(type="string",length=50,nullable=true) */
	private $uzletkotonev;

	/**
	 * @ManyToOne(targetEntity="Raktar",inversedBy="bizonylatfejek")
	 * @JoinColumn(name="raktar_id", referencedColumnName="id",nullable=true,onDelete="no action")
	 */
	private $raktar;

	/** @Column(type="string",length=50,nullable=true) */
	private $raktarnev;

	/** @OneToMany(targetEntity="Bizonylattetel", mappedBy="bizonylatfej",cascade={"persist","remove"}) */
	private $bizonylattetelek;

	/** @Column(type="text",nullable=true) */
	private $megjegyzes;

	/** @Column(type="text",nullable=true) */
	private $webshopmessage;

	/** @Column(type="text",nullable=true) */
	private $couriermessage;

	/** @Column(type="date",nullable=true) */
	private $hatarido;

	/** @Column(type="string",length=255,nullable=true) */
	private $szallnev = '';

	/** @Column(type="string",length=10,nullable=true) */
	private $szallirszam = '';

	/** @Column(type="string",length=40,nullable=true) */
	private $szallvaros = '';

	/** @Column(type="string",length=60,nullable=true) */
	private $szallutca = '';

	/**
	 * @PrePersist
	 * @PreUpdate
	 */
	public function doStuffOnPrePersist() {
		$this->netto = 0;
		$this->afa = 0;
		$this->brutto = 0;
		$this->nettohuf = 0;
		$this->afahuf = 0;
		$this->bruttohuf = 0;
		foreach ($this->bizonylattetelek as $bt) {
			$bt->setMozgat();
			$this->netto+=$bt->getNetto();
			$this->afa+=$bt->getAfaertek();
			$this->brutto+=$bt->getBrutto();
			$this->nettohuf+=$bt->getNettohuf();
			$this->afahuf+=$bt->getAfaertekhuf();
			$this->bruttohuf+=$bt->getBruttohuf();
		}
		$this->fizetendo = $this->brutto;
	}

	public function __construct() {
		$this->bizonylattetelek = new \Doctrine\Common\Collections\ArrayCollection();
		$this->setPersistentData();
	}

	public function setPersistentData() {
		$this->setTulajData();
	}

	protected function setTulajData() {
		$this->setTulajnev(store::getParameter(\mkw\consts::Tulajnev));
		$this->setTulajirszam(store::getParameter(\mkw\consts::Tulajirszam));
		$this->setTulajvaros(store::getParameter(\mkw\consts::Tulajvaros));
		$this->setTulajutca(store::getParameter(\mkw\consts::Tulajutca));
		$this->setTulajadoszam(store::getParameter(\mkw\consts::Tulajadoszam));
		$this->setTulajeuadoszam(store::getParameter(\mkw\consts::Tulajeuadoszam));
	}

	public function getId() {
		return $this->id;
	}

	public function generateId() {
		$bt = $this->getBizonylattipus();
		if ($bt && is_null($this->id)) {
			$azon = $bt->getAzonosito();
			if (is_null($azon)) {
				$azon = '';
			}
			$kezdo = $bt->getKezdosorszam();
			$ev = $this->kelt->format('Y');
			$q = store::getEm()->createQuery('SELECT COUNT(bf) FROM Entities\Bizonylatfej bf WHERE bf.id LIKE \'' . $azon . '%\'');
			if ($q->getSingleScalarResult() > 0) {
				$kezdo = 1;
			}
			if (!$kezdo) {
				$kezdo = 1;
			}
			$szam = $kezdo;
			$q = store::getEm()->createQuery('SELECT MAX(bf.id) FROM Entities\Bizonylatfej bf WHERE (bf.id LIKE \'' . $azon . '%\') AND (YEAR(bf.kelt)=' . $ev . ')');
			$max = $q->getSingleScalarResult();
			if ($max) {
				$szam = explode('/', $max);
				if (is_array($szam)) {
					$szam = $szam[1] + 1;
				}
			}
			$this->id = $azon . $ev . '/' . sprintf('%06d', $szam);
		}
	}

	public function getBizonylattetelek() {
		return $this->bizonylattetelek;
	}

	public function addBizonylattetel(Bizonylattetel $val) {
		if (!$this->bizonylattetelek->contains($val)) {
			$this->bizonylattetelek->add($val);
			$val->setBizonylatfej($this);
		}
	}

	public function removeBizonylattetel(Bizonylattetel $val) {
		if ($this->bizonylattetelek->removeElement($val)) {
			$val->removeBizonylatfej();
			return true;
		}
		return false;
	}

	public function getIrany() {
		return $this->irany;
	}

	public function setIrany($val) {
		$this->irany = $val;
	}

	public function getBizonylattipus() {
		return $this->bizonylattipus;
	}

	public function getBizonylattipusId() {
		if ($this->bizonylattipus) {
			return $this->bizonylattipus->getId();
		}
		return '';
	}

	public function setBizonylattipus(Bizonylattipus $val) {
		if ($this->bizonylattipus !== $val) {
			$this->bizonylattipus = $val;
			$this->setIrany($val->getIrany());
			$this->setBizonylatnev($val->getNev());
			$this->setPenztmozgat($val->getPenztmozgat());
//			$val->addBizonylat($this);
		}
	}

	public function removeBizonylattipus() {
		if ($this->bizonylattipus !== null) {
//			$val=$this->bizonylattipus;
			$this->bizonylattipus = null;
			$this->bizonylatnev = '';
//			$val->removeBizonylat($this);
		}
	}

	public function getBizonylatnev() {
		return $this->bizonylatnev;
	}

	public function setBizonylatnev($val) {
		$this->bizonylatnev = $val;
	}

	public function getNyomtatva() {
		return $this->nyomtatva;
	}

	public function setNyomtatva($val) {
		$this->nyomtatva = $val;
	}

	public function getStorno() {
		return $this->storno;
	}

	public function setStorno($val) {
		$this->storno = $val;
		if ($this->storno) {
			$this->setStornozott(false);
		}
	}

	public function getStornozott() {
		return $this->stornozott;
	}

	public function setStornozott($val) {
		$this->stornozott = $val;
		if ($this->stornozott) {
			$this->setStorno(false);
		}
	}

	public function getMozgat() {
		$bt = $this->getBizonylattipus();
		$raktar = $this->getRaktar();
		if ($bt && $raktar) {
			return $bt->getMozgat() && $raktar->getMozgat();
		}
		return false;
	}

	public function getPenztmozgat() {
		return $this->penztmozgat;
	}

	public function setPenztmozgat($val) {
		$this->penztmozgat = $val;
	}

	public function getTulajnev() {
		return $this->tulajnev;
	}

	public function setTulajnev($val) {
		$this->tulajnev = $val;
	}

	public function getTulajirszam() {
		return $this->tulajirszam;
	}

	public function setTulajirszam($val) {
		$this->tulajirszam = $val;
	}

	public function getTulajvaros() {
		return $this->tulajvaros;
	}

	public function setTulajvaros($val) {
		$this->tulajvaros = $val;
	}

	public function getTulajutca() {
		return $this->tulajutca;
	}

	public function setTulajutca($val) {
		$this->tulajutca = $val;
	}

	public function getTulajadoszam() {
		return $this->tulajadoszam;
	}

	public function setTulajadoszam($val) {
		$this->tulajadoszam = $val;
	}

	public function getTulajeuadoszam() {
		return $this->tulajeuadoszam;
	}

	public function setTulajeuadoszam($val) {
		$this->tulajeuadoszam = $val;
	}

	public function getKelt() {
		return $this->kelt;
	}

	public function getKeltStr() {
		if ($this->getKelt()) {
			return $this->getKelt()->format(store::$DateFormat);
		}
		return '';
	}

	public function setKelt($adat) {
		if ($adat == '')
			$adat = date(store::$DateFormat);
		$this->kelt = new \DateTime(store::convDate($adat));
	}

	public function getTeljesites() {
		return $this->teljesites;
	}

	public function getTeljesitesStr() {
		if ($this->getTeljesites()) {
			return $this->getTeljesites()->format(store::$DateFormat);
		}
		return '';
	}

	public function setTeljesites($adat) {
		if ($adat == '')
			$adat = date(store::$DateFormat);
		$this->teljesites = new \DateTime(store::convDate($adat));
	}

	public function getEsedekesseg() {
		return $this->esedekesseg;
	}

	public function getEsedekessegStr() {
		if ($this->getEsedekesseg()) {
			return $this->getEsedekesseg()->format(store::$DateFormat);
		}
		return '';
	}

	public function setEsedekesseg($adat) {
		if ($adat == '')
			$adat = date(store::$DateFormat);
		$this->esedekesseg = new \DateTime(store::convDate($adat));
	}

	public function getHatarido() {
		return $this->hatarido;
	}

	public function getHataridoStr() {
		if ($this->getHatarido()) {
			return $this->getHatarido()->format(store::$DateFormat);
		}
		return '';
	}

	public function setHatarido($adat) {
		if ($adat == '')
			$adat = date(store::$DateFormat);
		$this->hatarido = new \DateTime(store::convDate($adat));
	}

	public function getFizmod() {
		return $this->fizmod;
	}

	public function getFizmodnev() {
		return $this->fizmodnev;
	}

	public function getFizmodId() {
		if ($this->fizmod) {
			return $this->fizmod->getId();
		}
		return '';
	}

	public function setFizmod(Fizmod $val) {
		if ($this->fizmod !== $val) {
			$this->fizmod = $val;
			$this->fizmodnev = $val->getNev();
//			$val->addBizonylat($this);
		}
	}

	public function removeFizmod() {
		if ($this->fizmod !== null) {
//			$val=$this->fizmod;
			$this->fizmod = null;
			$this->fizmodnev = '';
//			$val->removeBizonylat($this);
		}
	}

	public function getSzallitasimod() {
		return $this->szallitasimod;
	}

	public function getSzallitasimodnev() {
		return $this->szallitasimodnev;
	}

	public function getSzallitasimodId() {
		if ($this->szallitasimod) {
			return $this->szallitasimod->getId();
		}
		return '';
	}

	public function setSzallitasimod(Szallitasimod $val) {
		if ($this->szallitasimod !== $val) {
			$this->szallitasimod = $val;
			$this->szallitasimodnev = $val->getNev();
//			$val->addBizonylat($this);
		}
	}

	public function removeSzallitasimod() {
		if ($this->szallitasimod !== null) {
//			$val=$this->szallitasimod;
			$this->szallitasimod = null;
			$this->szallitasimodnev = '';
//			$val->removeBizonylat($this);
		}
	}

	public function getNetto() {
		return $this->netto;
	}

	public function setNetto($val) {
		$this->netto = $val;
	}

	public function getAfa() {
		return $this->afa;
	}

	public function setAfa($val) {
		$this->afa = $val;
	}

	public function getBrutto() {
		return $this->brutto;
	}

	public function setBrutto($val) {
		$this->brutto = $val;
	}

	public function getFizetendo() {
		return $this->fizetendo;
	}

	public function setFizetendo($val) {
		$this->fizetendo = $val;
	}

	public function getValutanem() {
		return $this->valutanem;
	}

	public function getValutanemnev() {
		return $this->valutanemnev;
	}

	public function getValutanemId() {
		if ($this->valutanem) {
			return $this->valutanem->getId();
		}
		return '';
	}

	public function setValutanem(Valutanem $val) {
		if ($this->valutanem !== $val) {
			$this->valutanem = $val;
			$this->valutanemnev = $val->getNev();
//			$val->addBizonylatfej($this);
		}
	}

	public function removeValutanem() {
		if ($this->valutanem !== null) {
//			$val=$this->valutanem;
			$this->valutanem = null;
			$this->valutanemnev = '';
//			$val->removeBizonylatfej($this);
		}
	}

	public function getNettohuf() {
		return $this->nettohuf;
	}

	public function setNettohuf($val) {
		$this->nettohuf = $val;
	}

	public function getAfahuf() {
		return $this->afahuf;
	}

	public function setAfahuf($val) {
		$this->afahuf = $val;
	}

	public function getBruttohuf() {
		return $this->bruttohuf;
	}

	public function setBruttohuf($val) {
		$this->bruttohuf = $val;
	}

	public function getArfolyam() {
		return $this->arfolyam;
	}

	public function setArfolyam($val) {
		$this->arfolyam = $val;
	}

	public function getPartner() {
		return $this->partner;
	}

	public function getPartnerId() {
		if ($this->partner) {
			return $this->partner->getId();
		}
		return '';
	}

	public function setPartner(Partner $val) {
		if ($this->partner !== $val) {
			$this->partner = $val;
			$this->partnernev = $val->getNev();
			$this->partnervezeteknev = $val->getVezeteknev();
			$this->partnerkeresztnev = $val->getKeresztnev();
			$this->partneradoszam = $val->getAdoszam();
			$this->partnercjszam = $val->getCjszam();
			$this->partnereuadoszam = $val->getEuadoszam();
			$this->partnerfvmszam = $val->getFvmszam();
			$this->partnerirszam = $val->getIrszam();
			$this->partnerjovengszam = $val->getJovengszam();
			$this->partnerlirszam = $val->getLirszam();
			$this->partnerlutca = $val->getLutca();
			$this->partnerlvaros = $val->getLvaros();
			$this->partnermukengszam = $val->getMukengszam();
			$this->partnerostermszam = $val->getOstermszam();
			$this->partnerstatszamjel = $val->getStatszamjel();
			$this->partnerutca = $val->getUtca();
			$this->partnervalligszam = $val->getValligszam();
			$this->partnervaros = $val->getVaros();

			$this->szallnev = $val->getSzallnev();
			$this->szallirszam = $val->getSzallirszam();
			$this->szallvaros = $val->getSzallvaros();
			$this->szallutca = $val->getSzallutca();

			$uk = $val->getUzletkoto();
			if ($uk) {
				$this->setUzletkoto($uk);
			}
			else {
				$this->removeUzletkoto();
			}
			$fm = $val->getFizmod();
			if ($fm) {
				$this->setFizmod($fm);
			}
			else {
				$this->removeFizmod();
			}
//			$val->addBizonylatfej($this);
		}
	}

	public function removePartner() {
		if ($this->partner !== null) {
//			$val=$this->partner;
			$this->partner = null;
			$this->partnernev = '';
			$this->partnervezeteknev = '';
			$this->partnerkeresztnev = '';
			$this->partneradoszam = '';
			$this->partnercjszam = '';
			$this->partnereuadoszam = '';
			$this->partnerfvmszam = '';
			$this->partnerirszam = '';
			$this->partnerjovengszam = '';
			$this->partnerlirszam = '';
			$this->partnerlutca = '';
			$this->partnerlvaros = '';
			$this->partnermukengszam = '';
			$this->partnerostermszam = '';
			$this->partnerstatszamjel = '';
			$this->partnerutca = '';
			$this->partnervalligszam = '';
			$this->partnervaros = '';
			$this->removeUzletkoto();
			$this->removeFizmod();
//			$val->removeBizonylatfej($this);
		}
	}

	public function getPartnernev() {
		return $this->partnernev;
	}

	public function setPartnernev($val) {
		$this->partnernev = $val;
	}

	public function getPartnervezeteknev() {
		return $this->partnervezeteknev;
	}

	public function setPartnervezeteknev($val) {
		$this->partnervezeteknev = $val;
	}

	public function getPartnerkeresztnev() {
		return $this->partnerkeresztnev;
	}

	public function setPartnerkeresztnev($val) {
		$this->partnerkeresztnev = $val;
	}

	public function getPartneradoszam() {
		return $this->partneradoszam;
	}

	public function setPartneradoszam($val) {
		$this->partneradoszam = $val;
	}

	public function getPartnercjszam() {
		return $this->partnercjszam;
	}

	public function getPartnereuadoszam() {
		return $this->partnereuadoszam;
	}

	public function setPartnereuadoszam($val) {
		$this->partnereuadoszam = $val;
	}

	public function getPartnerfvmszam() {
		return $this->partnerfvmszam;
	}

	public function getPartnerirszam() {
		return $this->partnerirszam;
	}

	public function setPartnerirszam($val) {
		$this->partnerirszam = $val;
	}

	public function getPartnerjovengszam() {
		return $this->partnerjovengszam;
	}

	public function getPartnerlirszam() {
		return $this->partnerlirszam;
	}

	public function getPartnerlutca() {
		return $this->partnerlutca;
	}

	public function getPartnerlvaros() {
		return $this->partnerlvaros;
	}

	public function getPartnermukengszam() {
		return $this->partnermukengszam;
	}

	public function getPartnerostermszam() {
		return $this->partnerostermszam;
	}

	public function getPartnerstatszamjel() {
		return $this->partnerstatszamjel;
	}

	public function getPartnerutca() {
		return $this->partnerutca;
	}

	public function setPartnerutca($val) {
		$this->partnerutca = $val;
	}

	public function getPartnervalligszam() {
		return $this->partnervalligszam;
	}

	public function getPartnervaros() {
		return $this->partnervaros;
	}

	public function setPartnervaros($val) {
		$this->partnervaros = $val;
	}

	public function getBankszamla() {
		return $this->bankszamla;
	}

	public function getBankszamlanev() {
		return $this->bankszamlanev;
	}

	public function getBankszamlaId() {
		if ($this->bankszamla) {
			return $this->bankszamla->getId();
		}
		return '';
	}

	public function setBankszamla(Bankszamla $val = null) {
		if ($this->bankszamla !== $val && $val) {
			$this->bankszamla = $val;
			$this->bankszamlanev = $val->getSzamlaszam();
			$this->swift = $val->getSwift();
//			$val->addBizonylatfejek($this);
		}
	}

	public function removeBankszamla() {
		if ($this->bankszamla !== null) {
//			$val=$this->bankszamla;
			$this->bankszamla = null;
			$this->bankszamlanev = '';
			$this->swift = '';
//			$val->removeBizonylatfejek($this);
		}
	}

	public function getSwift() {
		return $this->swift;
	}

	public function getUzletkoto() {
		return $this->uzletkoto;
	}

	public function getUzletkotonev() {
		return $this->uzletkotonev;
	}

	public function getUzletkotoId() {
		if ($this->uzletkoto) {
			return $this->uzletkoto->getId();
		}
		return '';
	}

	public function setUzletkoto(Uzletkoto $val) {
		if ($this->uzletkoto !== $val) {
			$this->uzletkoto = $val;
			$this->uzletkotonev = $val->getNev();
//			$val->addBizonylatfejek($this);
		}
	}

	public function removeUzletkoto() {
		if ($this->uzletkoto !== null) {
//			$val=$this->uzletkoto;
			$this->uzletkoto = null;
			$this->uzletkotonev = '';
//			$val->removeBizonylatfejek($this);
		}
	}

	public function getRaktar() {
		return $this->raktar;
	}

	public function getRaktarnev() {
		return $this->raktarnev;
	}

	public function getRaktarId() {
		if ($this->raktar) {
			return $this->raktar->getId();
		}
		return '';
	}

	public function setRaktar(Raktar $val) {
		if ($this->raktar !== $val) {
			$this->raktar = $val;
			$this->raktarnev = $val->getNev();
//			$val->addBizonylatfejek($this);
		}
	}

	public function removeRaktar() {
		if ($this->raktar !== null) {
//			$val=$this->raktar;
			$this->raktar = null;
			$this->raktarnev = '';
//			$val->removeBizonylatfejek($this);
		}
	}

	public function getErbizonylatszam() {
		return $this->erbizonylatszam;
	}

	public function setErbizonylatszam($val) {
		$this->erbizonylatszam = $val;
	}

	public function getMegjegyzes() {
		return $this->megjegyzes;
	}

	public function setMegjegyzes($val) {
		$this->megjegyzes = $val;
	}

	public function getLastmod() {
		return $this->lastmod;
	}

	public function getCreated() {
		return $this->created;
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

	public function getWebshopmessage() {
		return $this->webshopmessage;
	}

	public function setWebshopmessage($val) {
		$this->webshopmessage = $val;
	}

	public function getCouriermessage() {
		return $this->couriermessage;
	}

	public function setCouriermessage($val) {
		$this->couriermessage = $val;
	}
}