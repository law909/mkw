<?php

namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\KosarRepository")
 * @ORM\Table(name="kosar")
 */
class Kosar {

	/**
	 * @ORM\Id @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @Gedmo\Timestampable(on="create")
	 * @ORM\Column(type="datetime",nullable=true)
	 */
	private $created;

	/**
	 * @Gedmo\Timestampable(on="create")
	 * @Gedmo\Timestampable(on="update")
	 * @ORM\Column(type="datetime",nullable=true)
	 */
	private $lastmod;

	/** @ORM\Column(type="string",length=255,nullable=true) */
	private $sessionid;

	/**
	 * @ORM\ManyToOne(targetEntity="Partner",inversedBy="kosarak")
	 * @ORM\JoinColumn(name="partner_id", referencedColumnName="id",nullable=true,onDelete="restrict")
	 */
	private $partner;

	/**
	 * @ORM\ManyToOne(targetEntity="Termek",inversedBy="kosarak")
	 * @ORM\JoinColumn(name="termek_id", referencedColumnName="id",nullable=true,onDelete="restrict")
	 */
	private $termek;

	/**
	 * @ORM\ManyToOne(targetEntity="TermekValtozat",inversedBy="kosarak")
	 * @ORM\JoinColumn(name="termekvaltozat_id", referencedColumnName="id",nullable=true,onDelete="restrict")
	 */
	private $termekvaltozat;

	/** @ORM\Column(type="decimal",precision=14,scale=2,nullable=true) */
	private $mennyiseg = 0;

	/**
	 * @ORM\ManyToOne(targetEntity="Valutanem",inversedBy="kosarak")
	 * @ORM\JoinColumn(name="valutanem_id",referencedColumnName="id",onDelete="restrict")
	 */
	private $valutanem;

	/** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
	private $nettoegysar;

	/** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
	private $bruttoegysar;

    /** @ORM\Column(type="integer",nullable=true) */
	private $sorrend = 0;

	public function toLista($partner = null) {
		$ret = array();
		$termek = $this->getTermek();
		$ret = $ret + $termek->toKosar($this->getTermekvaltozat());
        $ret['noedit'] = $termek->getId() == \mkw\Store::getParameter(\mkw\consts::SzallitasiKtgTermek);
		$ret['id'] = $this->getId();
        if ($partner && $partner->getSzamlatipus()) {
            $ret['bruttoegysarhuf'] = $this->getNettoegysar() * 1;
            $ret['bruttoegysar'] = $this->getNettoegysar() * 1;
            $ret['bruttohuf'] = $this->getNettoegysar() * $this->getMennyiseg() * 1;
            $ret['brutto'] = $this->getNettoegysar() * $this->getMennyiseg() * 1;
        }
        else {
            $ret['bruttoegysarhuf'] = $this->getBruttoegysar() * 1;
            $ret['bruttoegysar'] = $this->getBruttoegysar() * 1;
            $ret['bruttohuf'] = $this->getBruttoegysar() * $this->getMennyiseg() * 1;
            $ret['brutto'] = $this->getBruttoegysar() * $this->getMennyiseg() * 1;
        }
		$ret['mennyiseg'] = $this->getMennyiseg() * 1;
		$valt = $this->getTermekvaltozat();
		$v = array();
		if ($valt) {
			if ($valt->getAdatTipus1()) {
				$v[] = array('nev' => $valt->getAdatTipus1Nev(), 'ertek' => $valt->getErtek1());
			}
			if ($valt->getAdatTipus2()) {
				$v[] = array('nev' => $valt->getAdatTipus2Nev(), 'ertek' => $valt->getErtek2());
			}
		}
		$ret['valtozatok'] = $v;
		$ret['editlink'] = \mkw\Store::getRouter()->generate('kosaredit');
		$ret['showcheckoutlink'] = \mkw\Store::getRouter()->generate('showcheckout');
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

	public function getPartner() {
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

	public function setPartner($val) {
		if ($this->partner !== $val) {
			$this->partner = $val;
//			$val->addBizonylatfej($this);
		}
	}

	public function removePartner() {
		if ($this->partner !== null) {
//			$val=$this->partner;
			$this->partner = null;
//			$val->removeBizonylatfej($this);
		}
	}

	public function getTermek() {
		return $this->termek;
	}

	public function getTermekId() {
		if ($this->termek) {
			return $this->termek->getId();
		}
		return '';
	}

	public function setTermek(Termek $val) {
		if ($this->termek !== $val) {
			$this->termek = $val;
//			$val->addBizonylattetelek($this);
		}
	}

	public function removeTermek() {
		if ($this->termek !== null) {
//			$val=$this->termek;
			$this->termek = null;
//			$val->removeBizonylattetelek($this);
		}
	}

	public function getTermekvaltozat() {
		return $this->termekvaltozat;
	}

	public function getTermekvaltozatId() {
		if ($this->termekvaltozat) {
			return $this->termekvaltozat->getId();
		}
		return '';
	}

	public function setTermekvaltozat(TermekValtozat $val) {
		if ($this->termekvaltozat !== $val) {
			$this->termekvaltozat = $val;
//			$val->addBizonylattetelek($this);
		}
	}

	public function removeTermekvaltozat() {
		if ($this->termekvaltozat !== null) {
//			$val=$this->termek;
			$this->termekvaltozat = null;
//			$val->removeBizonylattetelek($this);
		}
	}

	public function getMennyiseg() {
		return $this->mennyiseg;
	}

	public function novelMennyiseg($added = null) {
        if (!$added) {
            $added = 1;
        }
        $this->mennyiseg = $this->mennyiseg + $added;
	}

	public function setMennyiseg($val) {
		$this->mennyiseg = $val;
	}

	public function getLastmod() {
		return $this->lastmod;
	}

	public function getCreated() {
		return $this->created;
	}

    public function getCreatedStr() {
        if ($this->getCreated()) {
            return $this->getCreated()->format(\mkw\Store::$DateTimeFormat);
        }
        return '';
    }

	public function getNettoegysar() {
		return $this->nettoegysar;
	}

	public function setNettoegysar($netto) {
		$this->nettoegysar = $netto;
		$this->bruttoegysar = $this->termek->getAfa()->calcBrutto($netto);
	}

	public function getBruttoegysar() {
		return $this->bruttoegysar;
	}

	public function setBruttoegysar($brutto) {
		$this->bruttoegysar = $brutto;
		$this->nettoegysar = $this->termek->getAfa()->calcNetto($brutto);
	}

	public function getValutanem() {
		return $this->valutanem;
	}

	public function setValutanem($valutanem) {
		$this->valutanem = $valutanem;
	}

	public function getValutanemNev() {
		if ($this->valutanem) {
			return $this->valutanem->getNev();
		}
		return '';
	}

    public function getSorrend() {
        return $this->sorrend;
    }

    public function setSorrend($s) {
        $this->sorrend = $s;
    }
}