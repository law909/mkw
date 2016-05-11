<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @Doctrine\ORM\Mapping\Entity(repositoryClass="SzallitasimodHatarRepository")
 * @Doctrine\ORM\Mapping\Table(name="szallitasimod_hatar",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class SzallitasimodHatar {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */private $id;
    /**
     * @ORM\ManyToOne(targetEntity="Szallitasimod",inversedBy="hatarok")
     * @ORM\JoinColumn(name="szallitasimod_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Szallitasimod
     */
    private $szallitasimod;
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

}