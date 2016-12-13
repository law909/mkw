<?php

namespace Entities;


use Doctrine\ORM\Mapping as ORM;

/**
 * @Doctrine\ORM\Mapping\Entity(repositoryClass="Entities\TermekcsoportRepository")
 * @Doctrine\ORM\Mapping\Table(name="termekcsoport",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class Termekcsoport {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $nev;

    /** @ORM\OneToMany(targetEntity="Termek", mappedBy="termekcsoport",cascade={"persist"}) */
    private $termekek;

    /** @ORM\OneToMany(targetEntity="PartnerTermekcsoportKedvezmeny", mappedBy="termekcsoport",cascade={"persist"}) */
    private $kedvezmenyek;

    /** @ORM\Column(type="integer", nullable=true) */
    private $migrid;


    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id) {
        $this->id = $id;
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
    public function getMigrid() {
        return $this->migrid;
    }

    /**
     * @param mixed $migrid
     */
    public function setMigrid($migrid) {
        $this->migrid = $migrid;
    }

}