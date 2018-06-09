<?php

namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity(repositoryClass="Entities\BankbizonylattetelRepository")
 * @ORM\Table(name="bankbizonylattetel",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class Bankbizonylattetel {

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
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $lastmod;

    /**
     * @ORM\ManyToOne(targetEntity="Bankbizonylatfej",inversedBy="bizonylattetelek")
     * @ORM\JoinColumn(name="bankbizonylatfej_id", referencedColumnName="id",nullable=true,onDelete="cascade")
     * @var \Entities\Bankbizonylatfej
     */
    private $bizonylatfej;

    /** @ORM\Column(type="integer") */
    private $irany;

    /** @ORM\Column(type="date",nullable=false) */
    private $datum;

    /** @ORM\Column(type="date",nullable=false) */
    private $hivatkozottdatum;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $rontott = false;

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
     * @ORM\ManyToOne(targetEntity="Jogcim")
     * @ORM\JoinColumn(name="jogcim_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Jogcim
     */
    private $jogcim;

    /**
     * @ORM\Column(type="string",length=50)
     */
    private $jogcimnev;

    /**
     * @ORM\Column(type="string",length=30,nullable=true)
     */
    private $hivatkozottbizonylat;

    /** @ORM\OneToMany(targetEntity="Folyoszamla", mappedBy="bizonylatfej",cascade={"persist"}) */
    private $folyoszamlak;

    /**
     * @ORM\ManyToOne(targetEntity="Partner",inversedBy="bankbizonylattetelek")
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

    /** @ORM\Column(type="string",length=40,nullable=true) */
    private $partnerhazszam;

    /** @ORM\Column(type="string",length=30,nullable=true) */
    private $erbizonylatszam;

    public function __construct() {
        $this->folyoszamlak = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId() {
        return $this->id;
    }

    /**
     * @return \Entities\Bankbizonylatfej
     */
    public function getBizonylatfej() {
        return $this->bizonylatfej;
    }

    public function getBizonylatfejId() {
        if ($this->bizonylatfej) {
            return $this->bizonylatfej->getId();
        }
        return '';
    }

    /**
     * @param \Entities\Bizonylatfej $val
     */
    public function setBizonylatfej($val) {
        if ($this->bizonylatfej !== $val) {
            $this->bizonylatfej = $val;
            $val->addBizonylattetel($this);
        }
    }

    public function removeBizonylatfej() {
        if ($this->bizonylatfej !== null) {
            $val = $this->bizonylatfej;
            $this->bizonylatfej = null;
            $val->removeBizonylattetel($this);
        }
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

    public function getDatum() {
        if (!$this->id && !$this->datum) {
            $this->datum = new \DateTime(\mkw\store::convDate(date(\mkw\store::$DateFormat)));
        }
        return $this->datum;
    }

    public function getDatumStr() {
        if ($this->getDatum()) {
            return $this->getDatum()->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    public function setDatum($adat = '') {
        if (is_a($adat, 'DateTime')) {
            $this->datum = $adat;
        }
        else {
            if ($adat == '') {
                $adat = date(\mkw\store::$DateFormat);
            }
            $this->datum = new \DateTime(\mkw\store::convDate($adat));
        }
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
        $this->setPartnerhazszam($val->getHazszam());
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
            $this->partnerhazszam = '';
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
     * @return mixed
     */
    public function getHivatkozottbizonylat() {
        return $this->hivatkozottbizonylat;
    }

    /**
     * @param mixed $hivatkozottbizonylat
     */
    public function setHivatkozottbizonylat($hivatkozottbizonylat) {
        $this->hivatkozottbizonylat = $hivatkozottbizonylat;
    }

    /**
     * @return mixed
     */
    public function getAfa() {
        return $this->afa;
    }

    /**
     * @param mixed $afa
     */
    public function setAfa($afa) {
        $this->afa = $afa;
    }

    /**
     * @return mixed
     */
    public function getBrutto() {
        return $this->brutto;
    }

    /**
     * @param mixed $brutto
     */
    public function setBrutto($brutto) {
        $this->brutto = $brutto;
    }

    /**
     * @return mixed
     */
    public function getCreated() {
        return $this->created;
    }

    /**
     * @param mixed $created
     */
    public function clearCreated() {
        $this->created = null;;
    }

    /**
     * @return mixed
     */
    public function getLastmod() {
        return $this->lastmod;
    }

    /**
     * @param mixed $lastmod
     */
    public function clearLastmod() {
        $this->lastmod = null;
    }

    /**
     * @return mixed
     */
    public function getNetto() {
        return $this->netto;
    }

    /**
     * @param mixed $netto
     */
    public function setNetto($netto) {
        $this->netto = $netto;
    }

    /**
     * @return mixed
     */
    public function getRontott() {
        return $this->rontott;
    }

    /**
     * @param mixed $rontott
     */
    public function setRontott($rontott) {
        $this->rontott = $rontott;
    }

    /**
     * @return mixed
     */
    public function getJogcimnev() {
        return $this->jogcimnev;
    }

    /**
     * @return mixed
     */
    public function getJogcimId() {
        if ($this->jogcim) {
            return $this->jogcim->getId();
        }
        return 0;
    }

    /**
     * @param mixed $jogcimnev
     */
    public function setJogcimnev($jogcimnev) {
        $this->jogcimnev = $jogcimnev;
    }

    /**
     * @return mixed
     */
    public function getJogcim() {
        return $this->jogcim;
    }

    /**
     * @param mixed $jogcim
     */
    public function setJogcim($jogcim) {
        $this->jogcim = $jogcim;
        if (!$jogcim) {
            $this->setJogcimnev('');
        }
        else {
            $this->setJogcimnev($jogcim->getNev());
        }
    }

    public function getHivatkozottdatum() {
        if (!$this->id && !$this->hivatkozottdatum) {
            $this->hivatkozottdatum = new \DateTime(\mkw\store::convDate(date(\mkw\store::$DateFormat)));
        }
        return $this->hivatkozottdatum;
    }

    public function getHivatkozottdatumStr() {
        if ($this->getHivatkozottdatum()) {
            return $this->getHivatkozottdatum()->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    public function setHivatkozottdatum($adat = '') {
        if (is_a($adat, 'DateTime')) {
            $this->hivatkozottdatum = $adat;
        }
        else {
            if ($adat == '') {
                $adat = date(\mkw\store::$DateFormat);
            }
            $this->hivatkozottdatum = new \DateTime(\mkw\store::convDate($adat));
        }
    }

    /**
     * @return mixed
     */
    public function getErbizonylatszam() {
        return $this->erbizonylatszam;
    }

    /**
     * @param mixed $erbizonylatszam
     */
    public function setErbizonylatszam($erbizonylatszam) {
        $this->erbizonylatszam = $erbizonylatszam;
    }

    /**
     * @return mixed
     */
    public function getPartnerhazszam() {
        return $this->partnerhazszam;
    }

    /**
     * @param mixed $partnerhazszam
     */
    public function setPartnerhazszam($partnerhazszam) {
        $this->partnerhazszam = $partnerhazszam;
    }

}