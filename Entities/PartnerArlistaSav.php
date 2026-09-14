<?php

namespace Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * A partner árlistájának egy vásárlási sávja (pl. 1.000–5.000); a sávhoz termékkategóriánként kedvezmény tartozik.
 *
 * @ORM\Entity(repositoryClass="Entities\PartnerArlistaSavRepository")
 * @ORM\Table(name="partnerarlistasav",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class PartnerArlistaSav
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Partner",inversedBy="arlistasavok")
     * @ORM\JoinColumn(name="partner_id",referencedColumnName="id",nullable=false,onDelete="cascade")
     */
    private $partner;

    /** @ORM\Column(type="integer",nullable=true) */
    private $sorrend = 0;

    /** @ORM\Column(type="decimal",precision=14,scale=2,nullable=true) */
    private $tol;

    /** @ORM\Column(type="decimal",precision=14,scale=2,nullable=true) */
    private $ig;

    /** @ORM\OneToMany(targetEntity="PartnerArlistaKedvezmeny", mappedBy="sav", cascade={"persist", "remove"}) */
    private $kedvezmenyek;

    public function __construct()
    {
        $this->kedvezmenyek = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getPartner()
    {
        return $this->partner;
    }

    public function setPartner($partner)
    {
        $this->partner = $partner;
    }

    public function getSorrend()
    {
        return $this->sorrend;
    }

    public function setSorrend($sorrend)
    {
        $this->sorrend = $sorrend;
    }

    public function getTol()
    {
        return $this->tol;
    }

    public function setTol($tol)
    {
        $this->tol = $tol;
    }

    public function getIg()
    {
        return $this->ig;
    }

    public function setIg($ig)
    {
        $this->ig = $ig;
    }

    /** a sáv felirata, pl. "1.000-5.000" vagy felső határ nélkül "20.000-" */
    public function getNev()
    {
        $format = fn($value) => number_format((float)$value, fmod((float)$value, 1) ? 2 : 0, ',', '.');
        return $format($this->tol ?? 0) . '-' . ($this->ig === null ? '' : $format($this->ig));
    }

    public function getKedvezmenyek()
    {
        return $this->kedvezmenyek;
    }

    public function addKedvezmeny(PartnerArlistaKedvezmeny $kedvezmeny)
    {
        if (!$this->kedvezmenyek->contains($kedvezmeny)) {
            $this->kedvezmenyek->add($kedvezmeny);
            $kedvezmeny->setSav($this);
        }
    }

    public function removeKedvezmeny(PartnerArlistaKedvezmeny $kedvezmeny)
    {
        $this->kedvezmenyek->removeElement($kedvezmeny);
    }
}
