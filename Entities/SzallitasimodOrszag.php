<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @Doctrine\ORM\Mapping\Entity(repositoryClass="SzallitasimodOrszagRepository")
 * @Doctrine\ORM\Mapping\Table(name="szallitasimod_orszag",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class SzallitasimodOrszag {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="Szallitasimod")
     * @ORM\JoinColumn(name="szallitasimod_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Szallitasimod
     */
    private $szallitasimod;
    /**
     * @ORM\ManyToOne(targetEntity="Orszag")
     * @ORM\JoinColumn(name="orszag_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Orszag
     */
    private $orszag;
    /**
     * @ORM\ManyToOne(targetEntity="Valutanem")
     * @ORM\JoinColumn(name="valutanem_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Valutanem
     */
    private $valutanem;
    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $hatarertek;
    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $osszeg;
    /** @ORM\Column(type="integer",nullable=true) */
    private $webshop;

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getHatarertek() {
        return $this->hatarertek;
    }

    /**
     * @param mixed $hatarertek
     */
    public function setHatarertek($hatarertek) {
        $this->hatarertek = $hatarertek;
    }

    /**
     * @return Szallitasimod
     */
    public function getSzallitasimod() {
        return $this->szallitasimod;
    }

    /**
     * @param \Entities\Szallitasimod $val
     */
    public function setSzallitasimod($val) {
        if (!($val instanceof \Entities\Szallitasimod)) {
            $val = \mkw\store::getEm()->getRepository('Entities\Szallitasimod')->find($val);
        }
        $this->szallitasimod = $val;
    }

    /**
     * @return Valutanem
     */
    public function getValutanem() {
        return $this->valutanem;
    }

    public function getValutanemNev() {
        $v = $this->getValutanem();
        if ($v) {
            return $v->getNev();
        }
        return '';
    }

    public function getValutanemId() {
        $v = $this->getValutanem();
        if ($v) {
            return $v->getId();
        }
        return 0;
    }

    /**
     * @param \Entities\Valutanem $val
     */
    public function setValutanem($val) {
        if (!($val instanceof \Entities\Valutanem)) {
            $val = \mkw\store::getEm()->getRepository('Entities\Valutanem')->find($val);
        }
        $this->valutanem = $val;
    }

    /**
     * @return mixed
     */
    public function getOsszeg() {
        return $this->osszeg;
    }

    /**
     * @param mixed $osszeg
     */
    public function setOsszeg($osszeg) {
        $this->osszeg = $osszeg;
    }

    /**
     * @return Orszag
     */
    public function getOrszag() {
        return $this->orszag;
    }

    public function getOrszagNev() {
        $v = $this->getOrszag();
        if ($v) {
            return $v->getNev();
        }
        return '';
    }

    public function getOrszagId() {
        $v = $this->getOrszag();
        if ($v) {
            return $v->getId();
        }
        return 0;
    }

    /**
     * @param \Entities\Orszag $val
     */
    public function setOrszag($val) {
        if (!($val instanceof \Entities\Orszag)) {
            $val = \mkw\store::getEm()->getRepository('Entities\Orszag')->find($val);
        }
        $this->orszag = $val;
    }

    /**
     * @return mixed
     */
    public function getWebshop() {
        return $this->webshop;
    }

    /**
     * @param mixed $webshop
     */
    public function setWebshop($webshop) {
        $this->webshop = $webshop;
    }

}