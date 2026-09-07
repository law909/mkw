<?php

namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * A pubadminban eladott órajegy/bérlet, amiből nem készült automatikus számla. A partner adatai
 * denormalizáltan is itt vannak: a sort évek múlva is olvasni kell, akkor is, ha a partnert
 * időközben javították vagy törölték.
 *
 * @ORM\Entity(repositoryClass="Entities\JogaSzamlazatlanEladasRepository")
 * @ORM\Table(name="jogaszamlazatlaneladas",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class JogaSzamlazatlanEladas
{

    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $created;

    /**
     * Az eladó: a pubadminba bejelentkezett tanár.
     *
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="Dolgozo")
     * @ORM\JoinColumn(name="createdby", referencedColumnName="id",nullable=true,onDelete="set null")
     */
    private $createdby;

    /**
     * @ORM\ManyToOne(targetEntity="Partner")
     * @ORM\JoinColumn(name="partner_id", referencedColumnName="id",nullable=true,onDelete="set null")
     */
    private $partner;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $partnernev;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $partneremail;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $megnevezes;

    /** @ORM\Column(type="decimal",precision=14,scale=2,nullable=true) */
    private $osszeg;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $oka;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $megoldva = false;

    /** @ORM\Column(type="datetime",nullable=true) */
    private $megoldasdatum;

    /**
     * @ORM\ManyToOne(targetEntity="Dolgozo")
     * @ORM\JoinColumn(name="megoldotta", referencedColumnName="id",nullable=true,onDelete="set null")
     */
    private $megoldotta;

    public function toLista()
    {
        return [
            'id' => $this->getId(),
            'created' => $this->getCreatedStr(),
            'dolgozonev' => $this->getCreatedbyNev(),
            'partnernev' => $this->getPartnernev(),
            'partneremail' => $this->getPartneremail(),
            'megnevezes' => $this->getMegnevezes(),
            'osszeg' => $this->getOsszeg(),
            'oka' => $this->getOka(),
            'megoldva' => $this->isMegoldva(),
            'megoldasdatum' => $this->getMegoldasdatumStr(),
            'megoldottanev' => $this->getMegoldottaNev(),
        ];
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getCreatedStr()
    {
        return $this->created ? $this->created->format(\mkw\store::$DateTimeFormat) : '';
    }

    /**
     * @return \Entities\Dolgozo
     */
    public function getCreatedby()
    {
        return $this->createdby;
    }

    public function getCreatedbyNev()
    {
        return $this->createdby ? $this->createdby->getNev() : '';
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
        return $this->partner ? $this->partner->getId() : null;
    }

    public function setPartner($partner)
    {
        $this->partner = $partner;
    }

    public function getPartnernev()
    {
        return $this->partnernev;
    }

    public function setPartnernev($partnernev)
    {
        $this->partnernev = $partnernev;
    }

    public function getPartneremail()
    {
        return $this->partneremail;
    }

    public function setPartneremail($partneremail)
    {
        $this->partneremail = $partneremail;
    }

    public function getMegnevezes()
    {
        return $this->megnevezes;
    }

    public function setMegnevezes($megnevezes)
    {
        $this->megnevezes = $megnevezes;
    }

    public function getOsszeg()
    {
        return $this->osszeg;
    }

    public function setOsszeg($osszeg)
    {
        $this->osszeg = $osszeg;
    }

    public function getOka()
    {
        return $this->oka;
    }

    public function setOka($oka)
    {
        $this->oka = $oka;
    }

    public function isMegoldva()
    {
        return $this->megoldva;
    }

    public function setMegoldva($megoldva)
    {
        $this->megoldva = $megoldva;
    }

    public function getMegoldasdatum()
    {
        return $this->megoldasdatum;
    }

    public function getMegoldasdatumStr()
    {
        return $this->megoldasdatum ? $this->megoldasdatum->format(\mkw\store::$DateTimeFormat) : '';
    }

    public function setMegoldasdatum($megoldasdatum = null)
    {
        $this->megoldasdatum = $megoldasdatum ?: new \DateTime();
    }

    /**
     * @return \Entities\Dolgozo
     */
    public function getMegoldotta()
    {
        return $this->megoldotta;
    }

    public function getMegoldottaNev()
    {
        return $this->megoldotta ? $this->megoldotta->getNev() : '';
    }

    public function setMegoldotta($megoldotta)
    {
        $this->megoldotta = $megoldotta;
    }

}
