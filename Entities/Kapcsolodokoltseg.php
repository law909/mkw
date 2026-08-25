<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Kapcsolódó költség (NETA, termékdíj, EPR): a termék valamely adatából – ma a súlyából –
 * számolt, egységáras tétel. Termékhez rendelhető, egy termékhez többet is, és a bizonylat
 * tételére a mentéskor másolat készül róla (`BizonylattetelKapcsolodokoltseg`).
 *
 * @ORM\Entity(repositoryClass="Entities\KapcsolodokoltsegRepository")
 * @ORM\Table(name="kapcsolodokoltseg",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class Kapcsolodokoltseg
{

    /** a csoport lehetséges értékei: azonosító => felirat */
    public const CSOPORTOK = [
        'NETA' => 'NETA',
        'termekdij' => 'Termékdíj',
        'EPR' => 'EPR',
    ];

    /** a számítás alapja: a termék melyik adatával szorzódik az ár */
    public const SZAMITASALAPOK = [
        'suly' => 'Súly',
    ];

    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /** @ORM\Column(type="string",length=255,nullable=false) */
    private $nev = '';

    /** @ORM\Column(type="string",length=50,nullable=true) */
    private $csoport;

    /** @ORM\Column(type="string",length=50,nullable=true) */
    private $szamitasalap = 'suly';

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $ar = 0;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $navfeladando = false;

    /** @ORM\ManyToMany(targetEntity="Termek", mappedBy="kapcsolodokoltsegek") */
    private $termekek;

    public function __construct()
    {
        $this->termekek = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNev()
    {
        return $this->nev;
    }

    public function setNev($val)
    {
        $this->nev = $val;
    }

    public function getCsoport()
    {
        return $this->csoport;
    }

    public function setCsoport($val)
    {
        $this->csoport = $val;
    }

    public function getCsoportNev()
    {
        return self::CSOPORTOK[$this->csoport] ?? '';
    }

    public function getSzamitasalap()
    {
        return $this->szamitasalap;
    }

    public function setSzamitasalap($val)
    {
        $this->szamitasalap = $val;
    }

    public function getSzamitasalapNev()
    {
        return self::SZAMITASALAPOK[$this->szamitasalap] ?? '';
    }

    public function getAr()
    {
        return $this->ar;
    }

    public function setAr($val)
    {
        $this->ar = $val;
    }

    public function getNavfeladando()
    {
        return $this->navfeladando;
    }

    public function setNavfeladando($val)
    {
        $this->navfeladando = $val;
    }

    public function getTermekek()
    {
        return $this->termekek;
    }

    /**
     * A számítás alapjául szolgáló termékadat értéke. Új számítási alapnál ez az egyetlen hely,
     * ahol bővíteni kell – a szorzás és a bizonylattételre másolás onnantól változatlan.
     */
    public function getSzamitasalapErtek(Termek $termek): float
    {
        return match ($this->szamitasalap) {
            'suly' => (float)$termek->getSuly(),
            default => 0,
        };
    }

    /** a termékre eső költség: az egységár szorozva a számítás alapjával */
    public function calcErtek(Termek $termek): float
    {
        return (float)$this->ar * $this->getSzamitasalapErtek($termek);
    }

}
