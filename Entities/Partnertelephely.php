<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * A partner telephelye: egy szállítási cím, amit a b2b megrendelésnél lehet választani.
 *
 * @ORM\Entity(repositoryClass="Entities\PartnertelephelyRepository")
 * @ORM\Table(name="partnertelephely",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class Partnertelephely
{

    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Partner",inversedBy="telephelyek")
     * @ORM\JoinColumn(name="partner_id",referencedColumnName="id",onDelete="cascade")
     */
    private $partner;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $nev;

    /** @ORM\Column(type="string",length=10,nullable=true) */
    private $irszam;

    /** @ORM\Column(type="string",length=40,nullable=true) */
    private $varos;

    /** @ORM\Column(type="string",length=60,nullable=true) */
    private $utca;

    /**
     * @ORM\ManyToOne(targetEntity="Orszag")
     * @ORM\JoinColumn(name="orszag_id",referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $orszag;

    /** @ORM\Column(type="integer",nullable=true) */
    private $migrid;

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \Entities\Partner
     */
    public function getPartner()
    {
        return $this->partner;
    }

    public function getPartnerId()
    {
        return $this->partner?->getId();
    }

    public function getPartnerNev()
    {
        return $this->partner ? $this->partner->getNev() : '';
    }

    public function setPartner($partner)
    {
        $this->partner = $partner;
    }

    public function getNev()
    {
        return $this->nev;
    }

    public function setNev($nev)
    {
        $this->nev = $nev;
    }

    public function getIrszam()
    {
        return $this->irszam;
    }

    public function setIrszam($irszam)
    {
        $this->irszam = $irszam;
    }

    public function getVaros()
    {
        return $this->varos;
    }

    public function setVaros($varos)
    {
        $this->varos = $varos;
    }

    public function getUtca()
    {
        return $this->utca;
    }

    public function setUtca($utca)
    {
        $this->utca = $utca;
    }

    /**
     * @return \Entities\Orszag
     */
    public function getOrszag()
    {
        return $this->orszag;
    }

    public function getOrszagId()
    {
        return $this->orszag?->getId();
    }

    public function getOrszagNev()
    {
        return $this->orszag ? $this->orszag->getNev() : '';
    }

    public function setOrszag($orszag)
    {
        $this->orszag = $orszag;
    }

    public function getMigrid()
    {
        return $this->migrid;
    }

    public function setMigrid($migrid)
    {
        $this->migrid = $migrid;
    }

    /** A választóban és a bizonylat szállítási címében megjelenő egysoros cím. */
    public function getCim()
    {
        return trim(implode(' ', array_filter([$this->irszam, $this->varos, $this->utca])));
    }

    public function getNevCim()
    {
        return trim(implode(' - ', array_filter([$this->nev, $this->getCim()])), ' -');
    }

}
