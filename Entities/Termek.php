<?php
namespace Entities;

use mkw\store;

/**
 * @Entity(repositoryClass="Entities\TermekRepository")
 * @Table(name="termek",indexes={
 *		@index(name="termekfakarkod_idx",columns={"termekfa1karkod","termekfa2karkod","termekfa3karkod"}),
 *		@index(name="termekfacounter_idx",columns={"inaktiv","lathato"}),
 *		@index(name="termekslug_idx",columns={"slug"})
 * })
 * @HasLifecycleCallbacks
*/
class Termek {
	/**
	 * @Id @Column(type="integer")
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id=0;
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
	/** @Column(type="string",length=255,nullable=true) */
	private $idegenkod='';
	/**
	 * @gedmo:Sluggable
	 * @Column(type="string",length=255,nullable=false)
	 */
	private $nev='';
	/** @Column(type="string",length=20,nullable=true) */
	private $me='';
	/** @Column(type="decimal",precision=14,scale=2,nullable=true) */
	private $kiszereles='';
	/**
	 * @ManyToOne(targetEntity="Vtsz")
	 * @JoinColumn(name="vtsz_id",referencedColumnName="id",nullable=true,onDelete="no action")
	 */
	private $vtsz;
	/**
	 * @ManyToOne(targetEntity="Afa")
	 * @JoinColumn(name="afa_id",referencedColumnName="id",nullable=true,onDelete="no action")
	 */
	private $afa;
	/**
	 * @ManyToMany(targetEntity="Termekcimketorzs",inversedBy="termekek")
	 * @JoinTable(name="termek_cimkek",
	 *  joinColumns={@JoinColumn(name="termek_id",referencedColumnName="id",onDelete="cascade",onUpdate="cascade")},
	 *  inverseJoinColumns={@JoinColumn(name="cimketorzs_id",referencedColumnName="id")}
	 *  )
	 */
	private $cimkek;
	/** @Column(type="text",nullable=true) */
	private $cimkenevek='';
	/** @Column(type="string",length=50,nullable=true) */
	private $cikkszam='';
	/** @Column(type="string",length=50,nullable=true) */
	private $idegencikkszam='';
	/** @Column(type="text",nullable=true) */
	private $leiras='';
	/** @Column(type="string",length=255,nullable=true) */
	private $rovidleiras='';
	/** @Column(type="string",length=255,nullable=true) */
	private $oldalcim='';
	/** @Column(type="text",nullable=true) */
	private $seodescription='';
	/** @Column(type="text",nullable=true) */
	private $seokeywords='';
	/**
	 * @gedmo:Slug
	 * @Column(type="string",length=255,nullable=true)
	 */
	private $slug='';
	/** @Column(type="boolean",nullable=false) */
	private $lathato=1;
	/** @Column(type="boolean",nullable=false) */
	private $hozzaszolas=1;
	/** @Column(type="boolean",nullable=false) */
	private $ajanlott=0;
	/** @Column(type="boolean",nullable=false) */
	private $kiemelt=0;
	/** @Column(type="boolean",nullable=false) */
	private $mozgat=1;
	/** @Column(type="boolean",nullable=false) */
	private $inaktiv=0;
	/** @Column(type="boolean",nullable=false) */
	private $termekexportbanszerepel=true;
	/** @Column(type="decimal",precision=14,scale=2,nullable=true) */
	private $hparany=0;
	/** @Column(type="decimal",precision=14,scale=4,nullable=true) */
	private $netto=0;
	/** @Column(type="decimal",precision=14,scale=4,nullable=true) */
	private $brutto=0;
	/** @Column(type="decimal",precision=14,scale=4,nullable=true) */
	private $akciosnetto=0;
	/** @Column(type="decimal",precision=14,scale=4,nullable=true) */
	private $akciosbrutto=0;
	/** @Column(type="date",nullable=true) */
	private $akciostart;
	/** @Column(type="date",nullable=true) */
	private $akciostop;
	/**
	 * @ManyToOne(targetEntity="TermekFa",inversedBy="termekek1")
	 * @JoinColumn(name="termekfa1_id",referencedColumnName="id",nullable=true,onDelete="no action")
	 */
	private $termekfa1;
	/** @Column(type="string",length=255,nullable=true) */
	private $termekfa1karkod='';
	/**
	 * @ManyToOne(targetEntity="TermekFa",inversedBy="termekek2")
	 * @JoinColumn(name="termekfa2_id",referencedColumnName="id",nullable=true,onDelete="no action")
	 */
	private $termekfa2;
	/** @Column(type="string",length=255,nullable=true) */
	private $termekfa2karkod='';
	/**
	 * @ManyToOne(targetEntity="TermekFa",inversedBy="termekek3")
	 * @JoinColumn(name="termekfa3_id",referencedColumnName="id",nullable=true,onDelete="no action")
	 */
	private $termekfa3;
	/** @Column(type="string",length=255,nullable=true) */
	private $termekfa3karkod='';
	/** @Column(type="text",nullable=true) */
	private $kepurl='';
	/** @Column(type="text",nullable=true) */
	private $kepleiras='';
	/** @Column(type="decimal",precision=14,scale=2,nullable=true) */
	private $szelesseg=0;
	/** @Column(type="decimal",precision=14,scale=2,nullable=true) */
	private $magassag=0;
	/** @Column(type="decimal",precision=14,scale=2,nullable=true) */
	private $hosszusag=0;
	/** @Column(type="decimal",precision=14,scale=2,nullable=true) */
	private $suly=0;
	/** @Column(type="boolean",nullable=false) */
	private $osszehajthato=false;
	/** @OneToMany(targetEntity="TermekKep", mappedBy="termek", cascade={"persist","remove"}) */
	private $termekkepek;
	/** @OneToMany(targetEntity="TermekValtozat",mappedBy="termek",cascade={"persist","remove"}) */
	private $valtozatok;
	/** @OneToMany(targetEntity="TermekRecept", mappedBy="termek", cascade={"persist","remove"}) */
	private $termekreceptek;
	/** @OneToMany(targetEntity="TermekRecept", mappedBy="altermek", cascade={"persist","remove"}) */
	private $altermekreceptek;
	/** @OneToMany(targetEntity="Dolgozo", mappedBy="muvelet", cascade={"persist","remove"}) */
	private $dolgozok;
	/** @OneToMany(targetEntity="Bizonylattetel", mappedBy="termek",cascade={"persist","remove"}) */
	private $bizonylattetelek;
	/** @OneToMany(targetEntity="Kosar", mappedBy="termek",cascade={"persist","remove"}) */
	private $kosarak;
	/** @Column(type="integer",nullable=true) */
	private $megtekintesdb;
	/** @Column(type="integer",nullable=true) */
	private $megvasarlasdb;
	/** @OneToMany(targetEntity="TermekKapcsolodo", mappedBy="termek", cascade={"persist","remove"}) */
	private $termekkapcsolodok;
	/** @OneToMany(targetEntity="TermekKapcsolodo", mappedBy="altermek", cascade={"persist","remove"}) */
	private $altermekkapcsolodok;
	/**
	 * @ManyToOne(targetEntity="TermekValtozatAdatTipus")
	 * @JoinColumn(name="valtozatadattipus_id",referencedColumnName="id",onDelete="no action")
	 */
	private $valtozatadattipus;
	/** @Column(type="boolean",nullable=false) */
	private $nemkaphato=false;
	/** @OneToMany(targetEntity="TermekErtesito", mappedBy="termek",cascade={"persist","remove"}) */
	private $termekertesitok;

	/**
	 * @PrePersist
	 * @PreUpdate
	 */
	public function doStuffOnPrePersist() {
		$res=array();
		foreach($this->cimkek as $cimke) {
			$res[]=$cimke->getNev();
		}
		$this->cimkenevek=implode('; ',$res);
	}

	public function __construct() {
		$this->cimkek=new \Doctrine\Common\Collections\ArrayCollection();
		$this->termekkepek=new \Doctrine\Common\Collections\ArrayCollection();
		$this->valtozatok=new \Doctrine\Common\Collections\ArrayCollection();
		$this->termekreceptek=new \Doctrine\Common\Collections\ArrayCollection();
		$this->altermekreceptek=new \Doctrine\Common\Collections\ArrayCollection();
		$this->dolgozok=new \Doctrine\Common\Collections\ArrayCollection();
		$this->bizonylattetelek=new \Doctrine\Common\Collections\ArrayCollection();
		$this->kosarak=new \Doctrine\Common\Collections\ArrayCollection();
		$this->termekkapcsolodok=new \Doctrine\Common\Collections\ArrayCollection();
		$this->altermekkapcsolodok=new \Doctrine\Common\Collections\ArrayCollection();
		$this->termekertesitok=new \Doctrine\Common\Collections\ArrayCollection();
	}

	public function toTermekLista($valtozat=null) {
		$x=array();
		$x['id']=$this->getId();
		$x['kozepeskepurl']=$this->getKepUrlMedium();
		$x['kiskepurl']=$this->getKepUrlSmall();
		$x['kepurl']=$this->getKepUrlLarge();
		$x['slug']=$this->getSlug();
		$x['caption']=$this->getNev();
		$x['rovidleiras']=$this->getRovidLeiras();
		$x['akcios']=$this->getAkcios();
		$x['akciostart']=$this->getAkciostartStr();
		$x['akciostop']=$this->getAkciostopStr();
		$x['akciosbrutto']=$this->getAkciosbrutto();
		$x['bruttohuf']=$this->getBruttoAr($valtozat);
		$x['eredetibruttohuf']=$this->getBruttoAr($valtozat,true);
		$x['nemkaphato']=$this->getNemkaphato();

		$listaban=array();
		foreach($this->getCimkek() as $cimke) {
			$kat=$cimke->getKategoria();
			if ($kat->getTermeklistabanlathato()) {
				$listaban[]=$cimke->toLista();
			}
		}
		$x['cimkelistaban']=$listaban;

		if (!is_null($valtozat)) {
			$x['kiskepurl']=$valtozat->getKepurlSmall();
			$x['kepurl']=$valtozat->getKepurl();
			$x['valtozatid']=$valtozat->getId();
			if ($this->getValtozatadattipusId()==$valtozat->getAdatTipus1Id()) {
				$ertek=$valtozat->getErtek1();
				$x['valtozatok']['fixname']=$valtozat->getAdatTipus1Nev();
				$x['valtozatok']['fixvalue']=$ertek;
				$x['valtozatok']['name']=$valtozat->getAdatTipus2Nev();
			}
			elseif ($this->getValtozatadattipusId()==$valtozat->getAdatTipus2Id()) {
				$ertek=$valtozat->getErtek2();
				$x['valtozatok']['fixname']=$valtozat->getAdatTipus2Nev();
				$x['valtozatok']['fixvalue']=$ertek;
				$x['valtozatok']['name']=$valtozat->getAdatTipus1Nev();
			}
			$adatt=array();
			$valtozatok=$this->getValtozatok();
			foreach($valtozatok as $valt) {
				if ($valt->getElerheto()) {
					if ($this->getValtozatadattipusId()==$valt->getAdatTipus1Id()&&$valt->getErtek1()==$ertek&&
							$valt->getAdatTipus2Id()) {
						$adatt[]=array('id'=>$valt->getId(),'value'=>$valt->getErtek2());
					}
					elseif ($this->getValtozatadattipusId()==$valt->getAdatTipus2Id()&&$valt->getErtek2()==$ertek&&
							$valt->getAdatTipus1Id()) {
						$adatt[]=array('id'=>$valt->getId(),'value'=>$valt->getErtek1());
					}
				}
			}
			$x['valtozatok']['data']=$adatt;
		}
		else {
			$vtt=array();
			$valtozatok=$this->getValtozatok();
			foreach($valtozatok as $valt) {
				if ($valt->getElerheto()) {
					if ($valt->getAdatTipus1Id()&&$valt->getAdatTipus1Nev()) {
						$vtt[$valt->getAdatTipus1Id()]['tipusid']=$valt->getAdatTipus1Id();
						$vtt[$valt->getAdatTipus1Id()]['name']=$valt->getAdatTipus1Nev();
						$vtt[$valt->getAdatTipus1Id()]['value'][$valt->getErtek1()]=$valt->getErtek1();
					}
					if ($valt->getAdatTipus2Id()&&$valt->getAdatTipus2Nev()) {
						$vtt[$valt->getAdatTipus2Id()]['tipusid']=$valt->getAdatTipus2Id();
						$vtt[$valt->getAdatTipus2Id()]['name']=$valt->getAdatTipus2Nev();
						$vtt[$valt->getAdatTipus2Id()]['value'][$valt->getErtek2()]=$valt->getErtek2();
					}
				}
			}
			$x['mindenvaltozat']=$vtt;
		}
		return $x;
	}

	public function toKiemeltLista() {
		$x=array();
		$x['id']=$this->getId();
		$x['kozepeskepurl']=$this->getKepUrlMedium();
		$x['kiskepurl']=$this->getKepUrlSmall();
		$x['kepurl']=$this->getKepUrlLarge();
		$x['slug']=$this->getSlug();
		$x['caption']=$this->getNev();
		$x['rovidleiras']=$this->getRovidLeiras();
		$x['akcios']=$this->getAkcios();
		$x['akciostart']=$this->getAkciostartStr();
		$x['akciostop']=$this->getAkciostopStr();
		$x['akciosbrutto']=$this->getAkciosbrutto();
		$x['bruttohuf']=$this->getBruttoAr();
		$x['eredetibruttohuf']=$this->getBruttoAr($valtozat,true);
		$x['nemkaphato']=$this->getNemkaphato();

		$listaban=array();
		foreach($this->getCimkek() as $cimke) {
			$kat=$cimke->getKategoria();
			if ($kat->getTermeklistabanlathato()) {
				$listaban[]=$cimke->toLista();
			}
		}
		$x['cimkelistaban']=$listaban;

		return $x;
	}

	public function toTermekLap($valtozat=null) {
		$x=array();

		$x['id']=$this->getId();
		$x['caption']=$this->getNev();
		$x['slug']=$this->getSlug();
		$x['kepurl']=$this->getKepurl();
		$x['kozepeskepurl']=$this->getKepUrlMedium();
		$x['rovidleiras']=$this->getRovidleiras();
		$x['leiras']=$this->getLeiras();
		$x['cikkszam']=$this->getCikkszam();
		$x['me']=$this->getMe();
		$x['hozzaszolas']=$this->getHozzaszolas();
		$x['akcios']=$this->getAkcios();
		$x['akciostart']=$this->getAkciostartStr();
		$x['akciostop']=$this->getAkciostopStr();
		$x['akciosbrutto']=$this->getAkciosbrutto();
		$x['bruttohuf']=$this->getBruttoAr($valtozat);
		$x['eredetibruttohuf']=$this->getBruttoAr($valtozat, true);
		$x['nemkaphato']=$this->getNemkaphato();

		$altomb=array();
		foreach($this->getTermekKepek() as $kep) {
			$egyed=array();
			$egyed['kepurl']=$kep->getUrlLarge();
			$egyed['kozepeskepurl']=$kep->getUrlMedium();
			$egyed['kiskepurl']=$kep->getUrlSmall();
			$egyed['leiras']=$kep->getLeiras();
			$altomb[]=$egyed;
		}
		$x['kepek']=$altomb;

		$altomb=array();
		foreach($this->getTermekKapcsolodok() as $kapcsolodo) {
			$altomb[]=$kapcsolodo->getAlTermek()->toKapcsolodo();
		}
		$x['kapcsolodok']=$altomb;

		$lapon=array();
		$akciodobozban=array();
		foreach($this->getCimkek() as $cimke) {
			$kat=$cimke->getKategoria();
			if ($kat->getTermeklaponlathato()) {
				$lapon[]=$cimke->toLista();
			}
			if ($kat->getTermekakciodobozbanlathato()) {
				$akciodobozban[]=$cimke->toLista();
			}
		}
		$x['cimkelapon']=$lapon;
		$x['cimkeakciodobozban']=$akciodobozban;

		$vtt=array();
		$valtozatok=$this->getValtozatok();
		foreach($valtozatok as $valt) {
			if ($valt->getElerheto()) {
				if ($valt->getAdatTipus1Id()&&$valt->getAdatTipus1Nev()) {
					$vtt[$valt->getAdatTipus1Id()]['tipusid']=$valt->getAdatTipus1Id();
					$vtt[$valt->getAdatTipus1Id()]['name']=$valt->getAdatTipus1Nev();
					$vtt[$valt->getAdatTipus1Id()]['value'][$valt->getErtek1()]=$valt->getErtek1();
				}
				if ($valt->getAdatTipus2Id()&&$valt->getAdatTipus2Nev()) {
					$vtt[$valt->getAdatTipus2Id()]['tipusid']=$valt->getAdatTipus2Id();
					$vtt[$valt->getAdatTipus2Id()]['name']=$valt->getAdatTipus2Nev();
					$vtt[$valt->getAdatTipus2Id()]['value'][$valt->getErtek2()]=$valt->getErtek2();
				}
			}
		}
		$x['valtozatok']=$vtt;
		return $x;
	}

	public function toKapcsolodo() {
		$x=array();
		$x['id']=$this->getId();
		$x['kozepeskepurl']=$this->getKepUrlMedium();
		$x['kiskepurl']=$this->getKepUrlSmall();
		$x['kepurl']=$this->getKepUrlLarge();
		$x['slug']=$this->getSlug();
		$x['caption']=$this->getNev();
		$x['rovidleiras']=$this->getRovidLeiras();
		$x['akcios']=$this->getAkcios();
		$x['bruttohuf']=$this->getBruttoAr();
		$x['eredetibruttohuf']=$this->getBruttoAr($valtozat,true);
		return $x;
	}

	public function toKosar($valtozat) {
		$x=array();
		$x['id']=$this->getId();
		$x['kozepeskepurl']=$this->getKepUrlMedium();
		$x['kiskepurl']=$this->getKepUrlSmall();
		$x['kepurl']=$this->getKepUrlLarge();
		$x['slug']=$this->getSlug();
		$x['caption']=$this->getNev();
		$x['rovidleiras']=$this->getRovidLeiras();
		$x['cikkszam']=$this->getCikkszam();
		$x['me']=$this->getMe();
		if ($valtozat) {
			$x['kozepeskepurl']=$valtozat->getKepUrlMedium();
			$x['kiskepurl']=$valtozat->getKepurlSmall();
			$x['kepurl']=$valtozat->getKepurl();
		}
		return $x;
	}

	public function getId()
	{
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

	public function getMe()
	{
		return $this->me;
	}

	public function setMe($me)
	{
		$this->me = $me;
	}

	public function getVtsz()
	{
		return $this->vtsz;
	}

	public function getVtszNev() {
		if ($this->vtsz) {
			return $this->vtsz->getNev();
		}
		return '';
	}

	public function getVtszId() {
		if ($this->vtsz) {
			return $this->vtsz->getId();
		}
		return '';
	}

	public function setVtsz($vtsz) {
		$this->vtsz = $vtsz;
		if ($vtsz) {
			$afa=$vtsz->getAfa();
			if ($afa) {
				$this->setAfa=$afa;
			}
		}
	}

	public function getAfa() {
		return $this->afa;
	}

	public function getAfaNev() {
		if ($this->afa) {
			return $this->afa->getNev();
		}
		return '';
	}

	public function getAfaId() {
		if ($this->afa) {
			return $this->afa->getId();
		}
		return '';
	}

	public function setAfa($afa) {
		$this->afa = $afa;
	}

	public function getCikkszam() {
		return $this->cikkszam;
	}

	public function setCikkszam($cikkszam) {
		$this->cikkszam = $cikkszam;
	}

	public function getIdegencikkszam() {
		return $this->idegencikkszam;
	}

	public function setIdegencikkszam($idegencikkszam) {
		$this->idegencikkszam = $idegencikkszam;
	}

	public function getLeiras() {
		return $this->leiras;
	}

	public function setLeiras($leiras)
	{
		$this->leiras = $leiras;
	}

	public function getRovidleiras() {
		return $this->rovidleiras;
	}

	public function setRovidleiras($rovidleiras) {
		$this->rovidleiras = $rovidleiras;
	}

	public function getOldalcim() {
		return $this->oldalcim;
	}

	public function getShowOldalcim() {
		if ($this->oldalcim) {
			return $this->oldalcim;
		}
		else {
			$result=store::getParameter('termekoldalcim');
			if ($result) {
				$result=str_replace('[termeknev]', $this->getNev(),$result);
				$result=str_replace('[global]', store::getParameter('oldalcim'), $result);
				$result=str_replace('[bruttoar]', number_format($this->getBruttoAr(null,false),0,',',' '), $result);
				return $result;
			}
			else {
				return store::getParameter('oldalcim');
			}
		}
	}

	public function setOldalcim($oldalcim) {
		$this->oldalcim = $oldalcim;
	}

	public function getSeodescription() {
		return $this->seodescription;
	}

	public function getShowSeodescription() {
		if ($this->seodescription) {
			return $this->seodescription;
		}
		else {
			$result=store::getParameter('termekseodescription');
			if ($result) {
				$result=str_replace('[termeknev]', $this->getNev(),$result);
				$result=str_replace('[global]', store::getParameter('seodescription'), $result);
				$result=str_replace('[bruttoar]', number_format($this->getBruttoAr(null,false),0,',',' '), $result);
				return $result;
			}
			else {
				return store::getParameter('seodescription');
			}
		}
	}

	public function setSeodescription($seodescription)
	{
		$this->seodescription = $seodescription;
	}

	public function getSeokeywords() {
		return $this->seokeywords;
	}
	
	public function getShowSeokeywords() {
		if ($this->seokeywords) {
			return $this->seokeywords;
		}
		else {
			$result=store::getParameter('termekseokeywords');
			if ($result) {
				$result=str_replace('[termeknev]', $this->getNev(),$result);
				$result=str_replace('[global]', store::getParameter('seokeywords'), $result);
				$result=str_replace('[bruttoar]', number_format($this->getBruttoAr(null,false),0,',',' '), $result);
				return $result;
			}
			else {
				return store::getParameter('seokeywords');
			}
		}
	}

	public function setSeokeywords($seokeywords)
	{
		$this->seokeywords = $seokeywords;
	}

	public function getSlug()
	{
		return $this->slug;
	}

	public function setSlug($slug)
	{
		$this->slug = $slug;
	}

	public function getLathato()
	{
		return $this->lathato;
	}

	public function setLathato($lathato)
	{
		$this->lathato = $lathato;
	}

	public function getHozzaszolas()
	{
		return $this->hozzaszolas;
	}

	public function setHozzaszolas($hozzaszolas)
	{
		$this->hozzaszolas = $hozzaszolas;
	}

	public function getAjanlott()
	{
		return $this->ajanlott;
	}

	public function setAjanlott($ajanlott)
	{
		$this->ajanlott = $ajanlott;
	}

	public function getMozgat()
	{
		return $this->mozgat;
	}

	public function setMozgat($mozgat)
	{
		$this->mozgat = $mozgat;
	}

	public function getInaktiv()
	{
		return $this->inaktiv;
	}

	public function setInaktiv($inaktiv)
	{
		$this->inaktiv = $inaktiv;
	}

	public function getTermekexportbanszerepel() {
		return $this->termekexportbanszerepel;
	}

	public function setTermekexportbanszerepel($adat) {
		$this->termekexportbanszerepel = $adat;
	}

	public function getHparany()
	{
		return $this->hparany;
	}

	public function setHparany($hparany)
	{
		$this->hparany = $hparany;
	}

	public function getNetto() {
		return $this->netto;
	}

	public function setNetto($netto) {
		$this->netto=$netto;
		$this->brutto=$this->getAfa()->calcBrutto($netto);
	}

	public function getBrutto() {
		return $this->brutto;
	}

	public function setBrutto($brutto) {
		$this->brutto=$brutto;
		$this->netto=$this->getAfa()->calcNetto($brutto);
	}

	public function getAkcios() {
		return $this->getAkciostartStr()<=date(store::$DateFormat)&&$this->getAkciostopStr()>=date(store::$DateFormat);
	}

	public function getAkciosnetto() {
		return $this->akciosnetto;
	}

	public function setAkciosnetto($netto) {
		$this->akciosnetto=$netto;
		$this->akciosbrutto=$this->getAfa()->calcBrutto($netto);
	}

	public function getAkciosbrutto() {
		return $this->akciosbrutto;
	}

	public function setAkciosbrutto($brutto) {
		$this->akciosbrutto=$brutto;
		$this->akciosnetto=$this->getAfa()->calcNetto($brutto);
	}

	public function getAkciostart() {
		return $this->akciostart;
	}

	public function getAkciostartStr() {
		if ($this->getAkciostart()) {
			return $this->getAkciostart()->format(store::$DateFormat);
		}
		return '';
	}

	public function setAkciostart($adat = '') {
		if ($adat != '') {
			$this->akciostart = new \DateTime(store::convDate($adat));
		}
	}

	public function getAkciostop() {
		return $this->akciostop;
	}

	public function getAkciostopStr() {
		if ($this->getAkciostop()) {
			return $this->getAkciostop()->format(store::$DateFormat);
		}
		return '';
	}

	public function setAkciostop($adat = '') {
		if ($adat != '') {
			$this->akciostop = new \DateTime(store::convDate($adat));
		}
	}

	/**
	 *
	 * @return ArrayCollection
	 */
	public function getCimkek() {
		return $this->cimkek;
	}

	public function getAllCimkeId() {
		$res=array();
		foreach($this->cimkek as $cimke) {
			$res[]=$cimke->getId();
		}
		return $res;
	}

	public function setCimkeNevek($cimkenevek) {
		$this->cimkenevek=$cimkenevek;
	}

	public function getCimkeNevek() {
		return $this->cimkenevek;
	}

	public function addCimke(Cimketorzs $cimke) {
		if (!$this->cimkek->contains($cimke)) {
			$this->cimkek->add($cimke);
			$cimke->addTermek($this);
			$this->setCimkeNevek('');
		}
	}

	public function removeCimke(Cimketorzs $cimke) {
		if ($this->cimkek->removeElement($cimke)) {
			//$cimke->removeTermek($this);  // deleted for speed
			return true;
		}
		return false;
	}

	public function removeAllCimke() {
//		$this->cimkek->clear();
//		$this->setCimkeNevek('');
		foreach($this->cimkek as $cimke) {
			$this->removeCimke($cimke);
		}
	}

	public function getIdegenkod()
	{
		return $this->idegenkod;
	}

	public function setIdegenkod($idegenkod)
	{
		$this->idegenkod = $idegenkod;
	}

	public function getKiszereles()
	{
		return $this->kiszereles;
	}

	public function setKiszereles($kiszereles)
	{
		$this->kiszereles = $kiszereles;
	}

	public function getTermekfa1()
	{
		return $this->termekfa1;
	}

	public function getTermekfa1Nev() {
		if ($this->termekfa1) {
			if ($this->termekfa1->getId()>1) {
				return $this->termekfa1->getNev();
			}
		}
		return '';
	}

	public function getTermekfa1Id() {
		if ($this->termekfa1) {
			return $this->termekfa1->getId();
		}
		return 1;
	}

	public function setTermekfa1($termekfa)
	{
		$this->termekfa1 = $termekfa;
		if ($termekfa) {
			$this->termekfa1karkod=$termekfa->getKarkod();
			$termekfa->addTermek1($this);
		}
		else {
			$this->termekfa1karkod='';
		}
	}

	public function getTermekfa2()
	{
		return $this->termekfa2;
	}

	public function getTermekfa2Nev() {
		if ($this->termekfa2) {
			if ($this->termekfa2->getId()>1) {
				return $this->termekfa2->getNev();
			}
		}
		return '';
	}

	public function getTermekfa2Id() {
		if ($this->termekfa2) {
			return $this->termekfa2->getId();
		}
		return 1;
	}

	public function setTermekfa2($termekfa)
	{
		$this->termekfa2 = $termekfa;
		if ($termekfa) {
			$this->termekfa2karkod=$termekfa->getKarkod();
			$termekfa->addTermek2($this);
		}
		else {
			$this->termekfa2karkod='';
		}
	}

	public function getTermekfa3()
	{
		return $this->termekfa3;
	}

	public function getTermekfa3Nev() {
		if ($this->termekfa3) {
			if ($this->termekfa3->getId()>1) {
				return $this->termekfa3->getNev();
			}
		}
		return '';
	}

	public function getTermekfa3Id() {
		if ($this->termekfa3) {
			return $this->termekfa3->getId();
		}
		return 1;
	}

	public function setTermekfa3($termekfa)
	{
		$this->termekfa3 = $termekfa;
		if ($termekfa) {
			$this->termekfa3karkod=$termekfa->getKarkod();
			$termekfa->addTermek3($this);
		}
		else {
			$this->termekfa3karkod='';
		}
	}

	public function getTermekAr($valtozat) {
		$ret=array('netto'=>$this->getNettoAr($valtozat),'brutto'=>$this->getBruttoAr($valtozat));
		return $ret;
	}

	public function getTermekKepek() {
		return $this->termekkepek;
	}

	public function addTermekKep(TermekKep $kep) {
//		if (!$this->termekkepek->contains($kep)) {
			$this->termekkepek->add($kep);
			$kep->setTermek($this);
//		}
	}

	public function removeTermekKep(TermekKep $kep) {
		if ($this->termekkepek->removeElement($kep)) {
			$kep->removeTermek($this);
			return true;
		}
		return false;
	}

	public function getKepurl($pre='/')
	{
		if ($this->kepurl) {
			if ($this->kepurl[0]!==$pre) {
				return $pre.$this->kepurl;
			}
			else {
				return $this->kepurl;
			}
		}
		return '';
	}

	public function getKepurlSmall($pre='/') {
		$kepurl=$this->getKepurl($pre);
		if ($kepurl) {
			$t=explode('.',$kepurl);
			$ext=array_pop($t);
			return implode('.',$t).store::getParameter('smallimgpost','').'.'.$ext;
		}
		return '';
	}

	public function getKepurlMedium($pre='/') {
		$kepurl=$this->getKepurl($pre);
		if ($kepurl) {
			$t=explode('.',$kepurl);
			$ext=array_pop($t);
			return implode('.',$t).store::getParameter('mediumimgpost','').'.'.$ext;
		}
		return '';
	}

	public function getKepurlLarge($pre='/') {
		$kepurl=$this->getKepurl($pre);
		if ($kepurl) {
			$t=explode('.',$kepurl);
			$ext=array_pop($t);
			return implode('.',$t).store::getParameter('bigimgpost','').'.'.$ext;
		}
		return '';
	}

	public function setKepurl($kepurl)
	{
		$this->kepurl = $kepurl;
		if (!$kepurl) {
			$this->setKepleiras(null);
		}
	}

	public function getKepleiras()
	{
		return $this->kepleiras;
	}

	public function setKepleiras($kepleiras)
	{
		$this->kepleiras = $kepleiras;
	}

	public function getSzelesseg() {
		return $this->szelesseg;
	}

	public function setSzelesseg($szelesseg) {
		$this->szelesseg=$szelesseg;
	}

	public function getMagassag() {
		return $this->magassag;
	}

	public function setMagassag($magassag) {
		$this->magassag=$magassag;
	}

	public function getHosszusag() {
		return $this->hosszusag;
	}

	public function setHosszusag($hosszusag) {
		$this->hosszusag=$hosszusag;
	}

	public function getOsszehajthato() {
		return $this->osszehajthato;
	}

	public function setOsszehajthato($osszehajthato) {
		$this->osszehajthato=$osszehajthato;
	}

	public function getSuly() {
		return $this->suly;
	}

	public function setSuly($suly) {
		$this->suly=$suly;
	}

	public function getValtozatok() {
		return $this->valtozatok;
	}

	public function addValtozat(TermekValtozat $valt) {
//		if (!$this->valtozatok->contains($valt)) {
			$this->valtozatok->add($valt);
			$valt->setTermek($this);
//		}
	}

	public function removeValtozat(TermekValtozat $valt) {
		if ($this->valtozatok->removeElement($valt)) {
			$valt->setTermek(null);
			return true;
		}
		return false;
	}

	public function getTermekReceptek() {
		return $this->termekreceptek;
	}

	public function addTermekRecept(TermekRecept $recept) {
//		if (!$this->termekreceptek->contains($recept)) {
			$this->termekreceptek->add($recept);
			$recept->setTermek($this);
//		}
	}

	public function removeTermekRecept(TermekRecept $recept) {
		if ($this->termekreceptek->removeElement($recept)) {
			$recept->removeTermek($this);
			return true;
		}
		return false;
	}

	public function getAlTermekReceptek() {
		return $this->altermekreceptek;
	}

	public function addAlTermekRecept(TermekRecept $recept) {
//		if (!$this->altermekreceptek->contains($recept)) {
			$this->altermekreceptek->add($recept);
			$recept->setAlTermek($this);
//		}
	}

	public function removeAlTermekRecept(TermekRecept $recept) {
		if ($this->altermekreceptek->removeElement($recept)) {
			$recept->removeAlTermek($this);
			return true;
		}
		return false;
	}

	public function getDolgozok() {
		return $this->dolgozok;
	}

	public function addDolgozo(Dolgozo $dolgozo) {
			if (!$this->dolgozok->contains($dolgozo)) {
			$this->dolgozok->add($dolgozo);
			$dolgozo->setMuvelet($this);
		}
	}

	public function removeDolgozo(Dolgozo $dolgozo) {
		if ($this->dolgozok->removeElement($dolgozo)) {
			$dolgozo->removeMuvelet();
			return true;
		}
		return false;
	}

	public function getMegtekintesdb() {
		return $this->megtekintesdb;
	}

	public function setMegtekintesdb($adat) {
		$this->megtekintesdb=$adat;
	}

	public function incMegtekintesdb() {
		$this->megtekintesdb++;
	}

	public function getMegvasarlasdb() {
		return $this->megvasarlasdb;
	}

	public function setMegvasarlasdb($adat) {
		$this->megvasarlasdb=$adat;
	}

	public function incMegvasarlasdb() {
		$this->megvasarlasdb++;
	}

	public function getKiemelt() {
		return $this->kiemelt;
	}

	public function setKiemelt($adat) {
		$this->kiemelt=$adat;
	}

	public function getTermekKapcsolodok() {
		return $this->termekkapcsolodok;
	}

	public function addTermekKapcsolodo(TermekKapcsolodo $adat) {
//		if (!$this->termekreceptek->contains($adat)) {
			$this->termekkapcsolodok->add($adat);
			$adat->setTermek($this);
//		}
	}

	public function removeTermekKapcsolodo(TermekKapcsolodo $adat) {
		if ($this->termekkapcsolodok->removeElement($adat)) {
			$adat->removeTermek($this);
			return true;
		}
		return false;
	}

	public function getAlTermekKapcsolodok() {
		return $this->altermekkapcsolodok;
	}

	public function addAlTermekKapcsolodo(TermekKapcsolodo $adat) {
//		if (!$this->altermekkapcsolodok->contains($adat)) {
			$this->altermekkapcsolodok->add($adat);
			$adat->setAlTermek($this);
//		}
	}

	public function removeAlTermekKapcsolodo(TermekKapcsolodo $adat) {
		if ($this->altermekkapcsolodok->removeElement($adat)) {
			$adat->removeAlTermek($this);
			return true;
		}
		return false;
	}

	public function getLastmod() {
		return $this->lastmod;
	}

	public function getCreated() {
		return $this->created;
	}

	public function getValtozatadattipus()
	{
		return $this->valtozatadattipus;
	}

	public function getValtozatadattipusNev() {
		if ($this->valtozatadattipus) {
			if ($this->valtozatadattipus->getId()>1) {
				return $this->valtozatadattipus->getNev();
			}
		}
		return '';
	}

	public function getValtozatadattipusId() {
		if ($this->valtozatadattipus) {
			return $this->valtozatadattipus->getId();
		}
		return 0;
	}

	public function setValtozatadattipus($a)
	{
		$this->valtozatadattipus = $a;
	}

	public function getNettoAr($valtozat) {
		$netto=$this->getNetto();
		if ($this->getAkcios()) {
			$netto=$this->getAkciosnetto();
		}
		if ($valtozat) {
			return $netto+$valtozat->getNetto();
		}
		return $netto;
	}

	public function getBruttoAr($valtozat,$eredeti=false) {
		$brutto=$this->getBrutto();
		if (!$eredeti&&$this->getAkcios()) {
			$brutto=$this->getAkciosbrutto();
		}
		if ($valtozat) {
			return $brutto+$valtozat->getBrutto();
		}
		return $brutto;
	}

	public function getNemkaphato() {
		return $this->nemkaphato;
	}

	public function setNemkaphato($val) {
		$this->nemkaphato=$val;
	}
}