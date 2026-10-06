<?php

namespace Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * A csomagolási lista egy doboza: a bizonylat tételei dobozszám szerint ide kerülnek. Súly kg-ban, méret cm-ben.
 *
 * @ORM\Entity
 * @ORM\Table(name="csomagolasidoboz",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"},
 *  uniqueConstraints={@ORM\UniqueConstraint(name="csomagolasidoboz_szam_uq",columns={"bizonylatfej_id","dobozszam"})})
 */
class Csomagolasidoboz
{
    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Bizonylatfej")
     * @ORM\JoinColumn(name="bizonylatfej_id",referencedColumnName="id",nullable=false,onDelete="cascade")
     */
    private $bizonylatfej;

    /** @ORM\Column(type="integer",nullable=false) */
    private $dobozszam;

    /** @ORM\Column(type="decimal",precision=14,scale=3,nullable=true) */
    private $nettosuly;

    /** @ORM\Column(type="decimal",precision=14,scale=3,nullable=true) */
    private $bruttosuly;

    /** @ORM\Column(type="decimal",precision=10,scale=2,nullable=true) */
    private $szelesseg;

    /** @ORM\Column(type="decimal",precision=10,scale=2,nullable=true) */
    private $magassag;

    /** @ORM\Column(type="decimal",precision=10,scale=2,nullable=true) */
    private $melyseg;

    /** @ORM\OneToMany(targetEntity="Csomagolasitetel", mappedBy="doboz", cascade={"persist","remove"}) */
    private $tetelek;

    public function __construct()
    {
        $this->tetelek = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getBizonylatfej()
    {
        return $this->bizonylatfej;
    }

    public function setBizonylatfej(?Bizonylatfej $val)
    {
        $this->bizonylatfej = $val;
    }

    public function getDobozszam()
    {
        return $this->dobozszam;
    }

    public function setDobozszam($val)
    {
        $this->dobozszam = (int)$val;
    }

    public function getNettosuly()
    {
        return $this->nettosuly;
    }

    public function setNettosuly($val)
    {
        $this->nettosuly = $val;
    }

    public function getBruttosuly()
    {
        return $this->bruttosuly;
    }

    public function setBruttosuly($val)
    {
        $this->bruttosuly = $val;
    }

    public function getSzelesseg()
    {
        return $this->szelesseg;
    }

    public function setSzelesseg($val)
    {
        $this->szelesseg = $val;
    }

    public function getMagassag()
    {
        return $this->magassag;
    }

    public function setMagassag($val)
    {
        $this->magassag = $val;
    }

    public function getMelyseg()
    {
        return $this->melyseg;
    }

    public function setMelyseg($val)
    {
        $this->melyseg = $val;
    }

    /** m³, or 0 while a dimension is missing */
    public function getTerfogat(): float
    {
        return (float)$this->szelesseg * (float)$this->magassag * (float)$this->melyseg / 1000000;
    }

    public function getTetelek()
    {
        return $this->tetelek;
    }

    public function addTetel(Csomagolasitetel $tetel)
    {
        $this->tetelek->add($tetel);
        $tetel->setDoboz($this);
    }
}
