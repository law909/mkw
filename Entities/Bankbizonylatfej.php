<?php

namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity(repositoryClass="Entities\BankbizonylatfejRepository")
 * @ORM\Table(name="bankbizonylatfej",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 * */
class Bankbizonylatfej {

    /**
     * @ORM\Id @ORM\Column(type="string",length=30,nullable=false)
     */
    private $id;

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

    /**
     * @ORM\ManyToOne(targetEntity="Bizonylattipus", inversedBy="bankbizonylatfejek")
     * @ORM\JoinColumn(name="bizonylattipus_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Bizonylattipus
     */
    private $bizonylattipus;

    /** @ORM\Column(type="integer") */
    private $irany;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $rontott = false;

    /** @ORM\Column(type="string",length=30,nullable=true) */
    private $erbizonylatszam;

    /** @ORM\Column(type="text",nullable=true) */
    private $megjegyzes;

    /** @ORM\Column(type="date",nullable=false) */
    private $kelt;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $netto;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $afa;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $brutto;

    /**
     * @ORM\ManyToOne(targetEntity="Valutanem")
     * @ORM\JoinColumn(name="valutanem_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Valutanem
     */
    private $valutanem;

    /** @ORM\Column(type="string",length=6,nullable=true) */
    private $valutanemnev;

    /**
     * @ORM\ManyToOne(targetEntity="Bankszamla")
     * @ORM\JoinColumn(name="bankszamla_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Bankszamla
     */
    private $bankszamla;

    /** @ORM\Column(type="string",length=50,nullable=true) */
    private $tulajbanknev;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $tulajbankszamlaszam;

    /** @ORM\Column(type="string",length=20,nullable=true) */
    private $tulajswift;

    /** @ORM\Column(type="string",length=20,nullable=true) */
    private $tulajiban;

    /**
     * @ORM\ManyToOne(targetEntity="Partner",inversedBy="bankbizonylatfejek")
     * @ORM\JoinColumn(name="partner_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Partner
     */
    private $partner;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $partnernev;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $partnervezeteknev;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $partnerkeresztnev;

    /** @ORM\Column(type="string",length=13,nullable=true) */
    private $partneradoszam;

    /** @ORM\Column(type="string",length=30,nullable=true) */
    private $partnereuadoszam;

    /** @ORM\Column(type="string",length=10,nullable=true) */
    private $partnerirszam;

    /** @ORM\Column(type="string",length=40,nullable=true) */
    private $partnervaros;

    /** @ORM\Column(type="string",length=60,nullable=true) */
    private $partnerutca;

    /** @ORM\OneToMany(targetEntity="Bankbizonylattetel", mappedBy="bizonylatfej",cascade={"persist"}) */
    private $bizonylattetelek;

    /** @ORM\OneToMany(targetEntity="Folyoszamla", mappedBy="bankbizonylatfej",cascade={"persist"}) */
    private $folyoszamlak;

    public function getId() {
        return $this->id;
    }

    public function setId($val) {
        if (!$this->id) {
            $this->id = $val;
        }
    }

    public function __construct() {
        $this->bizonylattetelek = new \Doctrine\Common\Collections\ArrayCollection();
        $this->folyoszamlak = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getBizonylattetelek() {
        return $this->bizonylattetelek;
    }

    public function addBizonylattetel(Bankbizonylattetel $val) {
        if (!$this->bizonylattetelek->contains($val)) {
            $val->setIrany($this->getIrany());
            $this->bizonylattetelek->add($val);
            $val->setBizonylatfej($this);
        }
    }

    public function removeBizonylattetel(Bankbizonylattetel $val) {
        if ($this->bizonylattetelek->removeElement($val)) {
            $val->removeBizonylatfej();
            return true;
        }
        return false;
    }

    public function clearBizonylattetelek() {
        $this->bizonylattetelek->clear();
    }

    public function getFolyoszamlak() {
        return $this->folyoszamlak;
    }

    public function addFolyoszamla(Folyoszamla $val) {
        if (!$this->folyoszamlak->contains($val)) {
            $this->folyoszamlak->add($val);
            $val->setBizonylatfej($this);
        }
    }

    public function removeFolyoszamla(Folyoszamla $val) {
        if ($this->folyoszamlak->removeElement($val)) {
            $val->removeBizonylatfej();
            return true;
        }
        return false;
    }

    public function clearFolyoszamlak() {
        $this->folyoszamlak->clear();
    }

    public function getIrany() {
        return $this->irany;
    }

    public function setIrany($val) {
        $this->irany = $val;
    }

    /**
     * @return \Entities\Bizonylattipus
     */
    public function getBizonylattipus() {
        return $this->bizonylattipus;
    }

    public function getBizonylattipusId() {
        if ($this->bizonylattipus) {
            return $this->bizonylattipus->getId();
        }
        return '';
    }

    /**
     * @param \Entities\Bizonylattipus $val
     */
    public function setBizonylattipus($val) {
        if ($this->bizonylattipus !== $val) {
            if (!$val) {
                $this->removeBizonylattipus();
            }
            else {
                $this->bizonylattipus = $val;
                $this->setIrany($val->getIrany());
            }
        }
    }

    public function removeBizonylattipus() {
        if ($this->bizonylattipus !== null) {
            $this->bizonylattipus = null;
        }
    }

    public function getKelt() {
        if (!$this->id && !$this->kelt) {
            $this->kelt = new \DateTime(\mkw\store::convDate(date(\mkw\store::$DateFormat)));
        }
        return $this->kelt;
    }

    public function getKeltStr() {
        if ($this->getKelt()) {
            return $this->getKelt()->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    public function setKelt($adat = '') {
        if (is_a($adat, 'DateTime')) {
            $this->kelt = $adat;
        }
        else {
            if ($adat == '') {
                $adat = date(\mkw\store::$DateFormat);
            }
            $this->kelt = new \DateTime(\mkw\store::convDate($adat));
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
        return $this->valutanemnev;
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
            if (!$val) {
                $this->removeValutanem();
            }
            else {
                $this->valutanem = $val;
                $this->valutanemnev = $val->getNev();
            }
        }
    }

    public function removeValutanem() {
        if ($this->valutanem !== null) {
            $this->valutanem = null;
            $this->valutanemnev = '';
        }
    }

    /**
     * @return \Entities\Partner
     */
    public function getPartner() {
        return $this->partner;
    }

    public function getPartnerId() {
        if ($this->partner) {
            return $this->partner->getId();
        }
        return '';
    }

    public function setPartnerLeiroadat($val) {
        $this->setPartnernev($val->getNev());
        $this->setPartnervezeteknev($val->getVezeteknev());
        $this->setPartnerkeresztnev($val->getKeresztnev());
        $this->setPartneradoszam($val->getAdoszam());
        $this->setPartnereuadoszam($val->getEuadoszam());
        $this->setPartnerirszam($val->getIrszam());
        $this->setPartnerutca($val->getUtca());
        $this->setPartnervaros($val->getVaros());
    }

    /**
     * @param \Entities\Partner $val
     */
    public function setPartner($val) {
        if ($this->partner !== $val) {
            if (!$val) {
                $this->removePartner();
            }
            else {
                $this->partner = $val;
                $this->setPartnerLeiroadat($val);

                $v = $val->getValutanem();
                if ($v) {
                    $this->setValutanem($v);
                }
                else {
                    $this->removeValutanem();
                }
            }
        }
    }

    public function removePartner() {
        if ($this->partner !== null) {
            $this->partner = null;
            $this->partnernev = '';
            $this->partnervezeteknev = '';
            $this->partnerkeresztnev = '';
            $this->partneradoszam = '';
            $this->partnercjszam = '';
            $this->partnereuadoszam = '';
            $this->partnerirszam = '';
            $this->partnerutca = '';
            $this->partnervaros = '';
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

    public function getPartnereuadoszam() {
        return $this->partnereuadoszam;
    }

    public function setPartnereuadoszam($val) {
        $this->partnereuadoszam = $val;
    }

    public function getPartnerirszam() {
        return $this->partnerirszam;
    }

    public function setPartnerirszam($val) {
        $this->partnerirszam = $val;
    }

    public function getPartnerutca() {
        return $this->partnerutca;
    }

    public function setPartnerutca($val) {
        $this->partnerutca = $val;
    }

    public function getPartnervaros() {
        return $this->partnervaros;
    }

    public function setPartnervaros($val) {
        $this->partnervaros = $val;
    }

    /**
     * @return \Entities\Bankszamla
     */
    public function getBankszamla() {
        return $this->bankszamla;
    }

    public function getTulajbankszamlaszam() {
        return $this->tulajbankszamlaszam;
    }

    public function getBankszamlaId() {
        if ($this->bankszamla) {
            return $this->bankszamla->getId();
        }
        return '';
    }

    /**
     * @param \Entities\Bankszamla|null $val
     */
    public function setBankszamla($val = null) {
        if ($this->bankszamla !== $val) {
            if (!$val) {
                $this->removeBankszamla();
            }
            else {
                $this->bankszamla = $val;
                $this->tulajbanknev = $val->getBanknev();
                $this->tulajbankszamlaszam = $val->getSzamlaszam();
                $this->tulajswift = $val->getSwift();
                $this->tulajiban = $val->getIban();
            }
        }
    }

    public function removeBankszamla() {
        if ($this->bankszamla !== null) {
            $this->bankszamla = null;
            $this->tulajbanknev = '';
            $this->tulajbankszamlaszam = '';
            $this->tulajswift = '';
            $this->tulajiban = '';
        }
    }

    public function getTulajswift() {
        return $this->tulajswift;
    }

    public function getTulajbanknev() {
        return $this->tulajbanknev;
    }

    public function getTulajiban() {
        return $this->tulajiban;
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

    public function clearLastmod() {
        $this->lastmod = null;
    }

    public function getCreated() {
        return $this->created;
    }

    public function clearCreated() {
        $this->created = null;
    }

    public function getRontott() {
        return $this->rontott;
    }

    public function setRontott($adat) {
        $this->rontott = $adat;
        foreach ($this->bizonylattetelek as $bt) {
            $bt->setRontott($adat);
            \mkw\store::getEm()->persist($bt);
        }
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

    public function getLastmodStr() {
        if ($this->getLastmod()) {
            return $this->getLastmod()->format(\mkw\store::$DateTimeFormat);
        }
        return '';
    }

    public function getCreatedStr() {
        if ($this->getCreated()) {
            return $this->getCreated()->format(\mkw\store::$DateTimeFormat);
        }
        return '';
    }

}