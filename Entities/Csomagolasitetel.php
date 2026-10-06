<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Egy bizonylattétel ennyi darabja ebben a dobozban van.
 *
 * @ORM\Entity
 * @ORM\Table(name="csomagolasitetel",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class Csomagolasitetel
{
    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Csomagolasidoboz", inversedBy="tetelek")
     * @ORM\JoinColumn(name="doboz_id",referencedColumnName="id",nullable=false,onDelete="cascade")
     */
    private $doboz;

    /**
     * @ORM\ManyToOne(targetEntity="Bizonylattetel")
     * @ORM\JoinColumn(name="bizonylattetel_id",referencedColumnName="id",nullable=false,onDelete="cascade")
     */
    private $bizonylattetel;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=false) */
    private $mennyiseg = 0;

    public function getId()
    {
        return $this->id;
    }

    public function getDoboz()
    {
        return $this->doboz;
    }

    public function setDoboz(?Csomagolasidoboz $val)
    {
        $this->doboz = $val;
    }

    public function getBizonylattetel()
    {
        return $this->bizonylattetel;
    }

    public function setBizonylattetel(?Bizonylattetel $val)
    {
        $this->bizonylattetel = $val;
    }

    public function getMennyiseg()
    {
        return $this->mennyiseg;
    }

    public function setMennyiseg($val)
    {
        $this->mennyiseg = $val;
    }
}
