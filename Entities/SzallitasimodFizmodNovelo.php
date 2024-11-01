<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @Doctrine\ORM\Mapping\Entity(repositoryClass="SzallitasimodFizmodNoveloRepository")
 * @Doctrine\ORM\Mapping\Table(name="szallitasimod_fizmodnovelo",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class SzallitasimodFizmodNovelo
{

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
     * @ORM\ManyToOne(targetEntity="Fizmod")
     * @ORM\JoinColumn(name="fizmod_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Fizmod
     */
    private $fizmod;
    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $osszeg;
    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $maxhatar;
    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $ertekszazalek;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Szallitasimod
     */
    public function getSzallitasimod()
    {
        return $this->szallitasimod;
    }

    /**
     * @param \Entities\Szallitasimod $val
     */
    public function setSzallitasimod($val)
    {
        if (!($val instanceof \Entities\Szallitasimod)) {
            $val = \mkw\store::getEm()->getRepository('Entities\Szallitasimod')->find($val);
        }
        $this->szallitasimod = $val;
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
    public function setOsszeg($osszeg)
    {
        $this->osszeg = $osszeg;
    }

    /**
     * @return Fizmod
     */
    public function getFizmod()
    {
        return $this->fizmod;
    }

    public function getFizmodNev()
    {
        $v = $this->getFizmod();
        if ($v) {
            return $v->getNev();
        }
        return '';
    }

    public function getFizmodId()
    {
        $v = $this->getFizmod();
        if ($v) {
            return $v->getId();
        }
        return 0;
    }

    /**
     * @param \Entities\Fizmod $val
     */
    public function setFizmod($val)
    {
        if (!($val instanceof \Entities\Fizmod)) {
            $val = \mkw\store::getEm()->getRepository('Entities\Fizmod')->find($val);
        }
        $this->fizmod = $val;
    }

    /**
     * @return mixed
     */
    public function getMaxhatar()
    {
        return $this->maxhatar;
    }

    /**
     * @param mixed $maxhatar
     */
    public function setMaxhatar($maxhatar): void
    {
        $this->maxhatar = $maxhatar;
    }

    /**
     * @return mixed
     */
    public function getErtekszazalek()
    {
        return $this->ertekszazalek;
    }

    /**
     * @param mixed $ertekszazalek
     */
    public function setErtekszazalek($ertekszazalek): void
    {
        $this->ertekszazalek = $ertekszazalek;
    }
    
}