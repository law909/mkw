<?php


namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\MenuRepository")
 * @ORM\Table(name="menu",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class Menu {
    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="Menucsoport")
     * @ORM\JoinColumn(name="menucsoport_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $menucsoport;
    /** @ORM\Column(type="string",length=255,nullable=false) */
    private $nev;
    /** @ORM\Column(type="string",length=255,nullable=false) */
    private $url;
    /** @ORM\Column(type="string",length=255,nullable=false) */
    private $routename;
    /** @ORM\Column(type="integer", nullable=true) */
    private $jogosultsag;
    /** @ORM\Column(type="boolean") */
    private $lathato;
    /** @ORM\Column(type="integer", nullable=true) */
    private $sorrend;
    /** @ORM\Column(type="string",length=255,nullable=false) */
    private $class;


    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    public function getMenucsoport() {
        return $this->menucsoport;
    }

    public function getMenucsoportId() {
        if ($this->menucsoport) {
            return $this->menucsoport->getId();
        }
        return '';
    }

    public function getMenucsoportNev() {
        if ($this->menucsoport) {
            return $this->menucsoport->getNev();
        }
        return '';
    }

    public function setMenucsoport($menucsoport) {
        $this->menucsoport = $menucsoport;
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
    public function getUrl() {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url) {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getRoutename() {
        return $this->routename;
    }

    /**
     * @param mixed $routename
     */
    public function setRoutename($routename) {
        $this->routename = $routename;
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

    public function isMenucsoportLathato($jog) {
        if ($this->menucsoport) {
            return $this->menucsoport->isLathato($jog);
        }
        return true;
    }

    public function isLathato($jog = 0) {
        return $this->getLathato() && ($this->getJogosultsag() <= $jog) && $this->isMenucsoportLathato($jog);
    }

    /**
     * @return mixed
     */
    public function getClass() {
        return $this->class;
    }

    /**
     * @param mixed $class
     */
    public function setClass($class) {
        $this->class = $class;
    }

}