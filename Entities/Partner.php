<?php
namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use mkw\store;

/** @ORM\Entity(repositoryClass="Entities\PartnerRepository")
 *  @ORM\Table(name="partner",indexes={
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
	 * @Gedmo\Timestampable(on="create")
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

	/** @ORM\Column(type="string",length=10,nullable=true) */
	private $lirszam = '';

	/** @ORM\Column(type="string",length=40,nullable=true) */
	private $lvaros = '';

	/** @ORM\Column(type="string",length=60,nullable=true) */
	private $lutca = '';

	/** @ORM\Column(type="string",length=40,nullable=true) */
	private $telefon = '';

	/** @ORM\Column(type="string",length=40,nullable=true) */
	private $mobil = '';

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

	public function __construct() {
		$this->cimkek = new \Doctrine\Common\Collections\ArrayCollection();
		$this->bizonylatfejek = new \Doctrine\Common\Collections\ArrayCollection();
		$this->kosarak = new \Doctrine\Common\Collections\ArrayCollection();
		$this->termekertesitok = new \Doctrine\Common\Collections\ArrayCollection();
	}

	public function getCim() {
		$cim = $this->irszam;
		if (($cim !== '') && ($this->varos !== '')) {
			$cim.=' ';
		}
		$cim.=$this->varos;
		if (($cim !== '') && ($this->utca !== '')) {
			$cim.=', ';
		}
		$cim.=$this->utca;
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
			return $this->getSzuletesiido()->format(store::$DateFormat);
		}
		return '';
	}

	public function setSzuletesiido($adat = '') {
		if ($adat != '') {
			$this->szuletesiido = new \DateTime(store::convDate($adat));
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
        $this->jelszo = sha1($adat . \mkw\Store::getSalt());
    }

	public function setJelszo($adat) {
		$this->jelszo = sha1(strtoupper(md5($adat)) . \mkw\Store::getSalt());
	}

	public function checkJelszo($adat) {
		return $this->jelszo === sha1(strtoupper(md5($adat)) . \mkw\Store::getSalt());
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
        $this->passwordreminder = sha1(md5(time() . \mkw\Store::getSalt()) . \mkw\Store::getSalt());
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

    public function getValutanem() {
        if (!$this->id && !$this->valutanem) {
            $this->setValutanem(\mkw\Store::getParameter(\mkw\consts::Valutanem));
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

    public function setValutanem($val) {
        if (!($val instanceof \Entities\Valutanem)) {
            $val = \mkw\Store::getEm()->getRepository('Entities\Valutanem')->find($val);
        }
        if ($this->valutanem !== $val) {
            $this->valutanem = $val;
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

}