<?php
namespace Entities;

/**
 * @Entity(repositoryClass="Entities\KosarRepository")
 * @Table(name="kosar")
 */
class Kosar {
	/**
	 * @Id @Column(type="integer")
	 * @GeneratedValue(strategy="AUTO")
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
	/** @Column(type="string",length=255,nullable=true) */
	private $sessionid;
	/**
	 * @ManyToOne(targetEntity="Partner",inversedBy="kosarak")
	 * @JoinColumn(name="partner_id", referencedColumnName="id",nullable=true,onDelete="no action")
	 */
	private $partner;
	/**
	 * @ManyToOne(targetEntity="Termek",inversedBy="kosarak")
	 * @JoinColumn(name="termek_id", referencedColumnName="id",nullable=true,onDelete="no action")
	 */
	private $termek;
	/**
	 * @ManyToOne(targetEntity="TermekValtozat",inversedBy="kosarak")
	 * @JoinColumn(name="termekvaltozat_id", referencedColumnName="id",nullable=true,onDelete="no action")
	 */
	private $termekvaltozat;
	/** @Column(type="decimal",precision=14,scale=2,nullable=true) */
	private $mennyiseg=0;
	/**
	 * @ManyToOne(targetEntity="Valutanem",inversedBy="kosarak")
	 * @JoinColumn(name="valutanem_id",referencedColumnName="id",onDelete="cascade")
	 */
	private $valutanem;
	/** @Column(type="decimal",precision=14,scale=4,nullable=true) */
	private $nettoegysar;
	/** @Column(type="decimal",precision=14,scale=4,nullable=true) */
	private $bruttoegysar;

	public function toLista() {
		$ret=array();
		$ret['id']=$this->getId();
		$termek=$this->getTermek();
		$ret=$ret+$termek->toKosar($this->getTermekvaltozat());
		$ret['bruttoegysarhuf']=$this->getBruttoegysar();
		$ret['mennyiseg']=$this->getMennyiseg();
		$ret['bruttohuf']=$this->getBruttoegysar()*$this->getMennyiseg();
		$valt=$this->getTermekvaltozat();
		$v=array();
		if ($valt) {
			if ($valt->getAdatTipus1()) {
				$v[]=array('nev'=>$valt->getAdatTipus1Nev(),'ertek'=>$valt->getErtek1());
			}
			if ($valt->getAdatTipus2()) {
				$v[]=array('nev'=>$valt->getAdatTipus2Nev(),'ertek'=>$valt->getErtek2());
			}
		}
		$ret['valtozatok']=$v;
		return $ret;
	}

	public function getId() {
		return $this->id;
	}

	public function getSessionid() {
		return $this->sessionid;
	}

	public function setSessionid($adat) {
		$this->sessionid = $adat;
	}

	public function getPartner(){
		return $this->partner;
	}

	public function getPartnerId() {
		if ($this->partner) {
			return $this->partner->getId();
		}
		return '';
	}

	public function getPartnerNev() {
		if ($this->partner) {
			return $this->partner->getNev();
		}
		return '';
	}

	public function setPartner(Partner $val) {
		if ($this->partner!==$val) {
			$this->partner=$val;
//			$val->addBizonylatfej($this);
		}
	}

	public function removePartner() {
		if ($this->partner!==null) {
//			$val=$this->partner;
			$this->partner=null;
//			$val->removeBizonylatfej($this);
		}
	}

	public function getTermek(){
		return $this->termek;
	}

	public function getTermekId() {
		if ($this->termek) {
			return $this->termek->getId();
		}
		return '';
	}

	public function setTermek(Termek $val) {
		if ($this->termek!==$val) {
			$this->termek=$val;
//			$val->addBizonylattetelek($this);
		}
	}

	public function removeTermek() {
		if ($this->termek !==null) {
//			$val=$this->termek;
			$this->termek=null;
//			$val->removeBizonylattetelek($this);
		}
	}

	public function getTermekvaltozat(){
		return $this->termekvaltozat;
	}

	public function getTermekvaltozatId() {
		if ($this->termekvaltozat) {
			return $this->termekvaltozat->getId();
		}
		return '';
	}

	public function setTermekvaltozat(TermekValtozat $val) {
		if ($this->termekvaltozat!==$val) {
			$this->termekvaltozat=$val;
//			$val->addBizonylattetelek($this);
		}
	}

	public function removeTermekvaltozat() {
		if ($this->termekvaltozat !==null) {
//			$val=$this->termek;
			$this->termekvaltozat=null;
//			$val->removeBizonylattetelek($this);
		}
	}

	public function getMennyiseg() {
		return $this->mennyiseg;
	}

	public function novelMennyiseg() {
		$this->mennyiseg++;
	}

	public function setMennyiseg($val) {
		$this->mennyiseg=$val;
	}

	public function getLastmod() {
		return $this->lastmod;
	}

	public function getCreated() {
		return $this->created;
	}

	public function getNettoegysar() {
		return $this->nettoegysar;
	}

	public function setNettoegysar($netto) {
		$this->nettoegysar=$netto;
		$this->bruttoegysar=$this->termek->getAfa()->calcBrutto($netto);
	}

	public function getBruttoegysar() {
		return $this->bruttoegysar;
	}

	public function setBruttoegysar($brutto) {
		$this->bruttoegysar=$brutto;
		$this->nettoegysar=$this->termek->getAfa()->calcNetto($brutto);
	}

	public function getValutanem() {
		return $this->valutanem;
	}

	public function setValutanem($valutanem) {
		$this->valutanem=$valutanem;
	}

	public function getValutanemNev() {
		if ($this->valutanem) {
			return $this->valutanem->getNev();
		}
		return '';
	}
}