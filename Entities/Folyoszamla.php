<?php

namespace Entities;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Doctrine\ORM\Mapping\Entity(repositoryClass="FolyoszamlaRepository")
 * @Doctrine\ORM\Mapping\Table(name="folyoszamla",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class Folyoszamla {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $datum;

    /**
     * @ORM\Column(type="date",nullable=true)
     */
    private $hivatkozottdatum;

    /**
     * @ORM\Column(type="string",length=30,nullable=true)
     */
    private $hivatkozottbizonylat;

    /**
     * @ORM\ManyToOne(targetEntity="Bizonylatfej",inversedBy="folyoszamlak")
     * @ORM\JoinColumn(name="bizonylatfej_id", referencedColumnName="id",nullable=true,onDelete="cascade")
     * @var \Entities\Bizonylatfej
     */
    private $bizonylatfej;

    /**
     * @ORM\ManyToOne(targetEntity="Bankbizonylatfej",inversedBy="folyoszamlak")
     * @ORM\JoinColumn(name="bankbizonylatfej_id", referencedColumnName="id",nullable=true,onDelete="cascade")
     * @var \Entities\Bankbizonylatfej
     */
    private $bankbizonylatfej;

    /**
     * @ORM\ManyToOne(targetEntity="Bankbizonylattetel",inversedBy="folyoszamlak")
     * @ORM\JoinColumn(name="bankbizonylattetel_id", referencedColumnName="id",nullable=true,onDelete="cascade")
     * @var \Entities\Bankbizonylattetel
     */
    private $bankbizonylattetel;

    /**
     * @ORM\ManyToOne(targetEntity="Penztarbizonylatfej",inversedBy="folyoszamlak")
     * @ORM\JoinColumn(name="penztarbizonylatfej_id", referencedColumnName="id",nullable=true,onDelete="cascade")
     * @var \Entities\Penztarbizonylatfej
     */
    private $penztarbizonylatfej;

    /**
     * @ORM\ManyToOne(targetEntity="Penztarbizonylattetel",inversedBy="folyoszamlak")
     * @ORM\JoinColumn(name="penztarbizonylattetel_id", referencedColumnName="id",nullable=true,onDelete="cascade")
     * @var \Entities\Penztarbizonylattetel
     */
    private $penztarbizonylattetel;

    /**
     * @ORM\ManyToOne(targetEntity="Bizonylattipus")
     * @ORM\JoinColumn(name="bizonylattipus_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Bizonylattipus
     */
    private $bizonylattipus;

    /**
     * @ORM\Column(type="integer")
     */
    private $irany;

    /**
     * @ORM\ManyToOne(targetEntity="Partner")
     * @ORM\JoinColumn(name="partner_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Partner
     */
    private $partner;

    /**
     * @ORM\ManyToOne(targetEntity="Uzletkoto")
     * @ORM\JoinColumn(name="uzletkoto_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Uzletkoto
     */
    private $uzletkoto;

    /**
     * @ORM\ManyToOne(targetEntity="Fizmod")
     * @ORM\JoinColumn(name="fizmod_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Fizmod
     */
    private $fizmod;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $netto;

    /**
     * @ORM\Column(type="decimal",precision=14,scale=4,nullable=true)
     */
    private $afa;

    /**
     * @ORM\Column(type="decimal",precision=14,scale=4,nullable=true)
     */
    private $brutto;

    /**
     * @ORM\ManyToOne(targetEntity="Valutanem")
     * @ORM\JoinColumn(name="valutanem_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Valutanem
     */
    private $valutanem;

    /**
     * @ORM\Column(type="decimal",precision=14,scale=4,nullable=true)
     */
    private $nettohuf;

    /**
     * @ORM\Column(type="decimal",precision=14,scale=4,nullable=true)
     */
    private $afahuf;

    /**
     * @ORM\Column(type="decimal",precision=14,scale=4,nullable=true)
     */
    private $bruttohuf;

    /**
     * @ORM\Column(type="boolean",nullable=false)
     */
    private $storno = false;

    /**
     * @ORM\Column(type="boolean",nullable=false)
     */
    private $stornozott = false;

    /**
     * @ORM\Column(type="boolean",nullable=false)
     */
    private $rontott = false;

    public function getId() {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getDatum() {
        return $this->datum;
    }

    /**
     * @param mixed $datum
     */
    public function setDatum($datum) {
        $this->datum = $datum;
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
     * @return Bizonylatfej
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
        }
    }

    public function removeBizonylatfej() {
        if ($this->bizonylatfej !== null) {
            $val = $this->bizonylatfej;
            $this->bizonylatfej = null;
        }
    }

    /**
     * @return Bankbizonylatfej
     */
    public function getBankbizonylatfej() {
        return $this->bankbizonylatfej;
    }

    public function getBankbizonylatfejId() {
        if ($this->bankbizonylatfej) {
            return $this->bankbizonylatfej->getId();
        }
        return '';
    }

    /**
     * @param \Entities\Bankbizonylatfej $val
     */
    public function setBankbizonylatfej($val) {
        if ($this->bankbizonylatfej !== $val) {
            $this->bankbizonylatfej = $val;
        }
    }

    public function removeBankbizonylatfej() {
        if ($this->bankbizonylatfej !== null) {
            $val = $this->bankbizonylatfej;
            $this->bankbizonylatfej = null;
        }
    }

    /**
     * @return Bankbizonylattetel
     */
    public function getBankbizonylattetel() {
        return $this->bankbizonylattetel;
    }

    public function getBankbizonylattetelId() {
        if ($this->bankbizonylattetel) {
            return $this->bankbizonylattetel->getId();
        }
        return '';
    }

    /**
     * @param \Entities\Bankbizonylattetel $val
     */
    public function setBankbizonylattetel($val) {
        if ($this->bankbizonylattetel !== $val) {
            $this->bankbizonylattetel = $val;
        }
    }

    public function removeBankbizonylattetel() {
        if ($this->bankbizonylattetel !== null) {
            $val = $this->bankbizonylattetel;
            $this->bankbizonylattetel = null;
        }
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
            }
        }
    }

    public function removeBizonylattipus() {
        if ($this->bizonylattipus !== null) {
            $this->bizonylattipus = null;
        }
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
    public function getAfahuf() {
        return $this->afahuf;
    }

    /**
     * @param mixed $afahuf
     */
    public function setAfahuf($afahuf) {
        $this->afahuf = $afahuf;
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
    public function getBruttohuf() {
        return $this->bruttohuf;
    }

    /**
     * @param mixed $bruttohuf
     */
    public function setBruttohuf($bruttohuf) {
        $this->bruttohuf = $bruttohuf;
    }

    /**
     * @return mixed
     */
    public function getIrany() {
        return $this->irany;
    }

    /**
     * @param mixed $irany
     */
    public function setIrany($irany) {
        $this->irany = $irany;
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
    public function getNettohuf() {
        return $this->nettohuf;
    }

    /**
     * @param mixed $nettohuf
     */
    public function setNettohuf($nettohuf) {
        $this->nettohuf = $nettohuf;
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
    public function getStorno() {
        return $this->storno;
    }

    /**
     * @param mixed $storno
     */
    public function setStorno($storno) {
        $this->storno = $storno;
    }

    /**
     * @return mixed
     */
    public function getStornozott() {
        return $this->stornozott;
    }

    /**
     * @param mixed $stornozott
     */
    public function setStornozott($stornozott) {
        $this->stornozott = $stornozott;
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
            }
        }
    }

    public function removePartner() {
        if ($this->partner !== null) {
            $this->partner = null;
        }
    }

    /**
     * @return \Entities\Uzletkoto
     */
    public function getUzletkoto() {
        return $this->uzletkoto;
    }

    public function getUzletkotoId() {
        if ($this->uzletkoto) {
            return $this->uzletkoto->getId();
        }
        return '';
    }

    /**
     * @param \Entities\Uzletkoto $val
     */
    public function setUzletkoto($val) {
        if ($this->uzletkoto !== $val) {
            if (!$val) {
                $this->removeUzletkoto();
            }
            else {
                $this->uzletkoto = $val;
            }
        }
    }

    public function removeUzletkoto() {
        if ($this->uzletkoto !== null) {
            $this->uzletkoto = null;
        }
    }

    public function getFizmod() {
        return $this->fizmod;
    }

    public function getFizmodId() {
        $fm = $this->getFizmod();
        if ($fm) {
            return $fm->getId();
        }
        return '';
    }

    /**
     * @param \Entities\Fizmod $val
     */
    public function setFizmod($val) {
        if ($this->fizmod !== $val) {
            if (!$val) {
                $this->removeFizmod();
            }
            else {
                $this->fizmod = $val;
            }
        }
    }

    public function removeFizmod() {
        if ($this->fizmod !== null) {
            $this->fizmod = null;
        }
    }

    /**
     * @return \Entities\Valutanem
     */
    public function getValutanem() {
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
        if ($this->valutanem !== $val) {
            if (!$val) {
                $this->removeValutanem();
            }
            else {
                $this->valutanem = $val;
            }
        }
    }

    public function removeValutanem() {
        if ($this->valutanem !== null) {
            $this->valutanem = null;
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
     * @return Penztarbizonylatfej
     */
    public function getPenztarbizonylatfej() {
        return $this->penztarbizonylatfej;
    }

    public function getPenztarbizonylatfejId() {
        if ($this->penztarbizonylatfej) {
            return $this->penztarbizonylatfej->getId();
        }
        return '';
    }

    /**
     * @param \Entities\Penztarbizonylatfej $val
     */
    public function setPenztarbizonylatfej($val) {
        if ($this->penztarbizonylatfej !== $val) {
            $this->penztarbizonylatfej = $val;
        }
    }

    public function removePaneztarbizonylatfej() {
        if ($this->penztarbizonylatfej !== null) {
            $val = $this->penztarbizonylatfej;
            $this->penztarbizonylatfej = null;
        }
    }

    /**
     * @return Penztarbizonylattetel
     */
    public function getPenztarbizonylattetel() {
        return $this->penztarbizonylattetel;
    }

    public function getPenztarbizonylattetelId() {
        if ($this->penztarbizonylattetel) {
            return $this->penztarbizonylattetel->getId();
        }
        return '';
    }

    /**
     * @param \Entities\Penztarbizonylattetel $val
     */
    public function setPenztarbizonylattetel($val) {
        if ($this->penztarbizonylattetel !== $val) {
            $this->penztarbizonylattetel = $val;
        }
    }

    public function removePenztarbizonylattetel() {
        if ($this->penztarbizonylattetel !== null) {
            $val = $this->penztarbizonylattetel;
            $this->penztarbizonylattetel = null;
        }
    }

}