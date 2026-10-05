<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Termékváltozatból készült termék naplója (Services\TermekValtozatToTermekService). A változat a művelet végén
 * törlődik, ezért az azonosítója szám, és a három entitás a művelet előtti / utáni teljes állapotában JSON-ban áll.
 *
 * @ORM\Entity
 * @ORM\Table(name="valtozatboltermeknaplo",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class ValtozatbolTermekNaplo
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
     * @ORM\ManyToOne(targetEntity="Dolgozo")
     * @ORM\JoinColumn(name="createdby",referencedColumnName="id",nullable=true,onDelete="set null")
     */
    private $createdby;

    /** a SYSADMIN belépésnek nincs Dolgozo sora @ORM\Column(type="string",length=255,nullable=true) */
    private $createdbynev;

    /**
     * @ORM\ManyToOne(targetEntity="Termek")
     * @ORM\JoinColumn(name="termek_id",referencedColumnName="id",nullable=true,onDelete="set null")
     */
    private $termek;

    /** @ORM\Column(type="integer",nullable=true) */
    private $termekvaltozatid;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $valtozatnev;

    /**
     * @ORM\ManyToOne(targetEntity="Termek")
     * @ORM\JoinColumn(name="ujtermek_id",referencedColumnName="id",nullable=true,onDelete="set null")
     */
    private $ujtermek;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $kepekmasolasa = false;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $dokumentumokmasolasa = false;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $arakmasolasa = false;

    /** @ORM\Column(type="text",nullable=true) */
    private $termekjson;

    /** @ORM\Column(type="text",nullable=true) */
    private $valtozatjson;

    /** @ORM\Column(type="text",nullable=true) */
    private $ujtermekjson;

    /** táblánként az átírt / törölt sorok azonosítói @ORM\Column(type="text",nullable=true) */
    private $atirasjson;

    public function getId()
    {
        return $this->id;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getCreatedby()
    {
        return $this->createdby;
    }

    public function setCreatedby($createdby)
    {
        $this->createdby = $createdby;
    }

    public function getCreatedbynev()
    {
        return $this->createdbynev;
    }

    public function setCreatedbynev($createdbynev)
    {
        $this->createdbynev = $createdbynev;
    }

    public function getTermek()
    {
        return $this->termek;
    }

    public function setTermek($termek)
    {
        $this->termek = $termek;
    }

    public function getTermekvaltozatid()
    {
        return $this->termekvaltozatid;
    }

    public function setTermekvaltozatid($termekvaltozatid)
    {
        $this->termekvaltozatid = $termekvaltozatid;
    }

    public function getValtozatnev()
    {
        return $this->valtozatnev;
    }

    public function setValtozatnev($valtozatnev)
    {
        $this->valtozatnev = $valtozatnev;
    }

    public function getUjtermek()
    {
        return $this->ujtermek;
    }

    public function setUjtermek($ujtermek)
    {
        $this->ujtermek = $ujtermek;
    }

    public function getKepekmasolasa()
    {
        return $this->kepekmasolasa;
    }

    public function setKepekmasolasa($kepekmasolasa)
    {
        $this->kepekmasolasa = (bool)$kepekmasolasa;
    }

    public function getDokumentumokmasolasa()
    {
        return $this->dokumentumokmasolasa;
    }

    public function setDokumentumokmasolasa($dokumentumokmasolasa)
    {
        $this->dokumentumokmasolasa = (bool)$dokumentumokmasolasa;
    }

    public function getArakmasolasa()
    {
        return $this->arakmasolasa;
    }

    public function setArakmasolasa($arakmasolasa)
    {
        $this->arakmasolasa = (bool)$arakmasolasa;
    }

    public function getTermekjson()
    {
        return $this->termekjson;
    }

    public function setTermekjson($termekjson)
    {
        $this->termekjson = $termekjson;
    }

    public function getValtozatjson()
    {
        return $this->valtozatjson;
    }

    public function setValtozatjson($valtozatjson)
    {
        $this->valtozatjson = $valtozatjson;
    }

    public function getUjtermekjson()
    {
        return $this->ujtermekjson;
    }

    public function setUjtermekjson($ujtermekjson)
    {
        $this->ujtermekjson = $ujtermekjson;
    }

    public function getAtirasjson()
    {
        return $this->atirasjson;
    }

    public function setAtirasjson($atirasjson)
    {
        $this->atirasjson = $atirasjson;
    }
}
