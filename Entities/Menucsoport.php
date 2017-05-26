<?php


namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\MenucsoportRepository")
 * @ORM\Table(name="menucsoport",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class Menucsoport {
    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /** @ORM\Column(type="string",length=255,nullable=false) */
    private $nev;
    /** @ORM\Column(type="integer", nullable=true) */
    private $jogosultsag;
    /** @ORM\Column(type="boolean") */
    private $lathato;
    /** @ORM\Column(type="integer", nullable=true) */
    private $sorrend;

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
    public function getJogosultsag() {
        return $this->jogosultsag;
    }

    /**
     * @param mixed $jogosultsag
     */
    public function setJogosultsag($jogosultsag) {
        $this->jogosultsag = $jogosultsag;
    }

    /**
     * @return mixed
     */
    public function getLathato() {
        return $this->lathato;
    }

    /**
     * @param mixed $lathato
     */
    public function setLathato($lathato) {
        $this->lathato = $lathato;
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

    public function isLathato($jog = 0) {
        return $this->getLathato() && $this->getJogosultsag() <= $jog;
    }
}