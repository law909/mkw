<?php


namespace Entities;

use Doctrine\Common\Collections\ArrayCollection;
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
    /**
     * Régi jogszint, csak a runonce 0228 olvassa: a hozzáférést a munkakorok adja.
     * @ORM\Column(type="integer", nullable=true)
     */
    private $jogosultsag;
    /**
     * @ORM\ManyToMany(targetEntity="Munkakor")
     * @ORM\JoinTable(name="menu_munkakorok",
     *  joinColumns={@ORM\JoinColumn(name="menu_id",referencedColumnName="id",onDelete="cascade")},
     *  inverseJoinColumns={@ORM\JoinColumn(name="munkakor_id",referencedColumnName="id",onDelete="cascade")}
     *  )
     */
    private $munkakorok;
    /** @ORM\Column(type="boolean") */
    private $lathato;
    /** @ORM\Column(type="integer", nullable=true) */
    private $sorrend;
    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $class;


    public function __construct() {
        $this->munkakorok = new ArrayCollection();
    }

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

    public function getMunkakorok() {
        return $this->munkakorok;
    }

    public function addMunkakor(Munkakor $munkakor) {
        if (!$this->munkakorok->contains($munkakor)) {
            $this->munkakorok->add($munkakor);
        }
    }

    public function removeAllMunkakor() {
        $this->munkakorok->clear();
    }

    public function getMunkakorIds() {
        $ids = [];
        foreach ($this->munkakorok as $munkakor) {
            $ids[] = $munkakor->getId();
        }
        return $ids;
    }

    public function getMunkakorNevek() {
        $nevek = [];
        foreach ($this->munkakorok as $munkakor) {
            $nevek[] = $munkakor->getNev();
        }
        sort($nevek);
        return implode(', ', $nevek);
    }

    public function isMenucsoportLathato() {
        if ($this->menucsoport) {
            return (bool)$this->menucsoport->getLathato();
        }
        return true;
    }

    /** A beépített sysadmin minden menüpontot lát, más csak a munkaköréhez bepipáltakat. */
    public function isLathato(?int $munkakorId, bool $sysadmin = false) {
        return $this->getLathato()
            && $this->isMenucsoportLathato()
            && ($sysadmin || ($munkakorId && in_array($munkakorId, $this->getMunkakorIds())));
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