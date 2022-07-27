<?php

namespace Entities;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Doctrine\ORM\Mapping\Entity(repositoryClass="Entities\MPTFolyoszamlaRepository")
 * @Doctrine\ORM\Mapping\Table(name="mptfolyoszamla",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class MPTFolyoszamla {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string",length=1)
     */
    private $tipus;

    /**
     * @ORM\ManyToOne(targetEntity="Partner",inversedBy="mptfolyoszamlak")
     * @ORM\JoinColumn(name="partner_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Partner
     */
    private $partner;

    /** @ORM\Column(type="integer") */
    private $irany;

    /**
     * @ORM\Column(type="string",length=255)
     */
    private $bizonylatszam;

    /** @ORM\Column(type="date",nullable=false) */
    private $datum;

    /**
     * @ORM\Column(type="string",length=255)
     */
    private $megjegyzes;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $osszeg;

    /** @ORM\Column(type="integer") */
    private $vonatkozoev;

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getTipus()
    {
        return $this->tipus;
    }

    public function getTipusnev() {
        switch ($this->tipus) {
            case 'E':
                return 'Előírás';
            case 'B':
                return 'Befizetés';
            default:
                return '';
        }
    }

    /**
     * @param mixed $tipus
     */
    public function setTipus($tipus): void
    {
        $this->tipus = $tipus;
    }

    /**
     * @return mixed
     */
    public function getIrany()
    {
        return $this->irany;
    }

    /**
     * @param mixed $irany
     */
    public function setIrany($irany): void
    {
        $this->irany = $irany;
    }

    /**
     * @return mixed
     */
    public function getBizonylatszam()
    {
        return $this->bizonylatszam;
    }

    /**
     * @param mixed $bizonylatszam
     */
    public function setBizonylatszam($bizonylatszam): void
    {
        $this->bizonylatszam = $bizonylatszam;
    }

    /**
     * @return mixed
     */
    public function getMegjegyzes()
    {
        return $this->megjegyzes;
    }

    /**
     * @param mixed $megjegyzes
     */
    public function setMegjegyzes($megjegyzes): void
    {
        $this->megjegyzes = $megjegyzes;
    }

    /**
     * @return mixed
     */
    public function getOsszeg()
    {
        return $this->osszeg;
    }

    /**
     * @param mixed $osszeg
     */
    public function setOsszeg($osszeg): void
    {
        $this->osszeg = $osszeg;
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

    public function getPartnernev() {
        if ($this->partner) {
            return $this->partner->getNev();
        }
        return '';
    }

    public function getPartnervezeteknev() {
        if ($this->partner) {
            return $this->partner->getVezeteknev();
        }
        return '';
    }

    public function getPartnerkeresztnev() {
        if ($this->partner) {
            return $this->partner->getKeresztnev();
        }
        return '';
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
     * @return mixed
     */
    public function getVonatkozoev()
    {
        return $this->vonatkozoev;
    }

    /**
     * @param mixed $vonatkozoev
     */
    public function setVonatkozoev($vonatkozoev): void
    {
        $this->vonatkozoev = $vonatkozoev;
    }

}