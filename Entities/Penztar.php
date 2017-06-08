<?php
namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\PenztarRepository")
 * @ORM\Table(name="penztar",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class Penztar {
    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /** @ORM\Column(type="string",length=50) */
    private $nev;
    /**
     * @ORM\ManyToOne(targetEntity="Valutanem")
     * @ORM\JoinColumn(name="valutanem_id",referencedColumnName="id",nullable=true,onDelete="set null")
     */
    private $valutanem;
    /** @ORM\Column(type="integer", nullable=true) */
    private $migrid;

    public function getId() {
        return $this->id;
    }

    public function getNev() {
        return $this->nev;
    }

    public function setNev($nev) {
        $this->nev = $nev;
    }

    public function getValutanem() {
        return $this->valutanem;
    }

    public function getValutanemId() {
        if ($this->valutanem) {
            return $this->valutanem->getId();
        }
        return null;
    }

    public function setValutanem($valutanem) {
        $this->valutanem = $valutanem;
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