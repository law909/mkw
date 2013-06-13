<?php
namespace Entities;
use mkw\store;
/**
 * @Entity(repositoryClass="Entities\TargyieszkozRepository")
 * @Table(name="targyieszkoz")
 */
class Targyieszkoz {
	/**
	 * @Id @Column(type="integer")
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/** @Column(type="string",length=255) */
	private $leltariszam;
	/**
	 * @gedmo:Sluggable
	 * @Column(type="string",length=255,nullable=false)
	 */
	private $nev;
	/** @Column(type="string",length=255) */
	private $sorozatszam;
	/**
	 * @ManyToOne(targetEntity="Felhasznalo",inversedBy="targyieszkozok")
	 * @JoinColumn(name="felhasznalo_id", referencedColumnName="felhasznalonev",nullable=true,onDelete="set null")
	 */
	private $alkalmazott;
	/** @Column(type="string",length=255,nullable=true) */
	private $hasznalatihely;
	/** @Column(type="smallint") */
	private $tipus;
	/** @Column(type="boolean") */
	private $nincsecs;
	/**
	 * @ManyToOne(targetEntity="TargyieszkozCsoport",inversedBy="targyieszkozok")
	 * @JoinColumn(name="csoport_id", referencedColumnName="id",nullable=true,onDelete="no action")
	 */
	private $csoport;
	/** @Column(type="smallint") */
	private $ecselszmod;

	/**
	 * @gedmo:Slug
	 * @Column(type="string",length=255,nullable=true)
	 */
	private $slug;

	/** @Column(type="decimal",precision=14,scale=2,nullable=true) */
	private $szvtvleirasikulcs;
	/** @Column(type="decimal",precision=14,scale=4,nullable=true) */
	private $szvtvmaradvanyertek;
	/** @Column(type="date",nullable=true) */
	private $szvtvelszkezdete;

	/** @Column(type="decimal",precision=14,scale=2,nullable=true) */
	private $tatvleirasikulcs;
	/** @Column(type="date",nullable=true) */
	private $tatvelszkezdete;
	/** @Column(type="smallint") */
	private $allapot;
	/** @Column(type="date",nullable=true) */
	private $allapotdatum;
	/** @Column(type="date",nullable=true) */
	private $beszerzesdatum;
	/** @Column(type="decimal",precision=14,scale=4,nullable=true) */
	private $beszerzesertek;

	private static $tipusok_t = array(1=>'Tárgyi eszközök',2=>'Immateriális javak');
	private static $ecselszmod_t = array(1=>'Lineáris',2=>'Egyéni');
	private static $allapot_t = array(1=>'Aktív',2=>'Eladva',3=>'Selejtezve');

	public function getId() {
		return $this->id;
	}

	public function getLeltariSzam() {
		return $this->leltariszam;
	}

	public function setLeltariSzam($leltariszam) {
		$this->leltariszam = $leltariszam;
	}

	public function getNev() {
		return $this->nev;
	}

	public function setNev($nev) {
		$this->nev = $nev;
	}

	public function getSorozatSzam() {
		return $this->sorozatszam;
	}

	public function setSorozatSzam($sorozatszam) {
		$this->sorozatszam = $sorozatszam;
	}

	public function getAlkalmazott() {
		return $this->alkalmazott;
	}

	public function getAlkalmazottId() {
		if ($this->alkalmazott) {
			return $this->alkalmazott->getId();
		}
		return '';
	}

	public function getAlkalmazottNev() {
		if ($this->alkalmazott) {
			return $this->alkalmazott->getNev();
		}
		return '';
	}

	public function setAlkalmazott(Felhasznalo $alkalmazott) {
		if ($this->alkalmazott!==$alkalmazott) {
			$this->alkalmazott=$alkalmazott;
			$alkalmazott->addTargyieszkoz($this);
		}
	}

	public function removeAlkalmazott() {
		if ($this->alkalmazott !== null) {
			$alkalmazott = $this->alkalmazott;
			$this->alkalmazott = null;
			$alkalmazott->removeTargyieszkoz($this);
		}
	}

	public function getHasznalatiHely() {
		return $this->hasznalatihely;
	}

	public function setHasznalatiHely($hasznalatihely) {
		$this->hasznalatihely = $hasznalatihely;
	}

	public function getTipus() {
		return $this->tipus;
	}

	public function getTipusNev() {
		return self::$tipusok_t[$this->getTipus()];
	}

	public function setTipus($tipus) {
		$this->tipus = $tipus;
	}

	public static function getTipusok() {
		return self::$tipusok_t;
	}

	public function getNincsecs() {
		return $this->nincsecs;
	}

	public function setNincsecs($nincsecs) {
		$this->nincsecs = (($nincsecs==='on')||($nincsecs===true)||($nincsecs===1));
	}

	public function getCsoport() {
		return $this->csoport;
	}

	public function getCsoportId() {
		if ($this->csoport) {
			return $this->csoport->getId();
		}
		return '';
	}

	public function getCsoportNev() {
		if ($this->csoport) {
			return $this->csoport->getNev();
		}
		return '';
	}

	public function setCsoport(TargyieszkozCsoport $csoport) {
		if ($this->csoport!==$csoport) {
			$this->csoport=$csoport;
			$csoport->addTargyieszkoz($this);
		}
	}

	public function removeCsoport() {
		if ($this->csoport !== null) {
			$partner = $this->csoport;
			$this->csoport = null;
			$csoport->removeTargyieszkoz($this);
		}
	}

	public function getEcselszmod() {
		return $this->ecselszmod;
	}

	public function getEcselszmodNev() {
		return self::$ecselszmod_t[$this->getEcselszmod()];
	}

	public static function getEcselszmodok() {
		return self::$ecselszmod_t;
	}

	public function setEcselszmod($ecselszmod) {
		$this->ecselszmod = $ecselszmod;
	}

	public function getSlug() {
		return $this->slug;
	}

	public function setSlug($slug) {
		$this->slug=$slug;
	}

	public function getSzvtvleirasikulcs() {
		return $this->szvtvleirasikulcs;
	}

	public function setSzvtvleirasikulcs($szvtvleirasikulcs) {
		$this->szvtvleirasikulcs = $szvtvleirasikulcs;
	}

	public function getSzvtvmaradvanyertek() {
		return $this->szvtvmaradvanyertek;
	}

	public function setSzvtvmaradvanyertek($szvtvmaradvanyertek) {
		$this->szvtvmaradvanyertek = $szvtvmaradvanyertek;
	}

	public function getSzvtvelszkezdete() {
		return $this->szvtvelszkezdete;
	}

	public function getSzvtvelszkezdeteStr() {
		if ($this->getSzvtvelszkezdete()) {
			return $this->getSzvtvelszkezdete()->format(store::$DateFormat);
		}
		return '';
	}

	public function setSzvtvelszkezdete($szvtvelszkezdete='') {
		if ($szvtvelszkezdete=='') $szvtvelszkezdete=date(store::$DateFormat);
		$this->szvtvelszkezdete = new \DateTime(store::convDate($szvtvelszkezdete));
	}

	public function getTatvleirasikulcs() {
		return $this->tatvleirasikulcs;
	}

	public function setTatvleirasikulcs($tatvleirasikulcs) {
		$this->tatvleirasikulcs = $tatvleirasikulcs;
	}

	public function getTatvelszkezdete() {
		return $this->tatvelszkezdete;
	}

	public function getTatvelszkezdeteStr() {
		if ($this->getTatvelszkezdete()) {
			return $this->getTatvelszkezdete()->format(store::$DateFormat);
		}
		return '';
	}

	public function setTatvelszkezdete($tatvelszkezdete='') {
		if ($tatvelszkezdete=='') $tatvelszkezdete=date(store::$DateFormat);
		$this->tatvelszkezdete = new \DateTime(store::convDate($tatvelszkezdete));
	}

	public function getAllapot() {
		return $this->allapot;
	}

	public function getAllapotNev() {
		return self::$allapot_t[$this->getAllapot()];
	}

	public static function getAllapotok() {
		return self::$allapot_t;
	}

	public function setAllapot($allapot) {
		$this->allapot=$allapot;
	}

	public function getAllapotDatum() {
		return $this->allapotdatum;
	}

	public function setAllapotDatum($allapotdatum) {
		if ($allapotdatum=='') $allapotdatum=date(store::$DateFormat);
		$this->allapotdatum = new \DateTime(store::convDate($allapotdatum));
	}

	public function getAllapotDatumStr() {
		if ($this->getAllapotDatum()) {
			return $this->getAllapotDatum()->format(store::$DateFormat);
		}
		return '';
	}

	public function getBeszerzesDatum() {
		return $this->beszerzesdatum;
	}

	public function setBeszerzesDatum($beszerzesdatum) {
		if ($beszerzesdatum=='') $beszerzesdatum=date(store::$DateFormat);
		$this->beszerzesdatum = new \DateTime(store::convDate($beszerzesdatum));
	}

	public function getBeszerzesDatumStr() {
		if ($this->getBeszerzesDatum()) {
			return $this->getBeszerzesDatum()->format(store::$DateFormat);
		}
		return '';
	}

	public function getBeszerzesErtek() {
		return $this->beszerzesertek;
	}

	public function setBeszerzesErtek($beszerzesertek) {
		$this->beszerzesertek=$beszerzesertek;
	}
}