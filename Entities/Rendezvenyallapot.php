<?php

namespace Entities;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Doctrine\ORM\Mapping\Entity(repositoryClass="Entities\RendezvenyallapotRepository")
 * @Doctrine\ORM\Mapping\Table(name="rendezvenytipus",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class Rendezvenyallapot {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string",length=50)
     */
    private $nev;

    /** @ORM\Column(type="integer",nullable=true) */
    private $sorrend;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $orarendbenszerepel = true;

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getNev() {
        return $this->nev;
    }

    /**
     * @param mixed $nev
     */
    public function setNev($nev) {
        $this->nev = $nev;
    }

    /**
     * @return mixed
     */
    public function getSorrend() {
        return $this->sorrend;
    }

    /**
     * @param mixed $sorrend
     */
    public function setSorrend($sorrend) {
        $this->sorrend = $sorrend;
    }

    /**
     * @return bool
     */
    public function isOrarendbenszerepel() {
        return $this->orarendbenszerepel;
    }

    /**
     * @param bool $orarendbenszerepel
     */
    public function setOrarendbenszerepel($orarendbenszerepel) {
        $this->orarendbenszerepel = $orarendbenszerepel;
    }

}