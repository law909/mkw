<?php
namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\JogaoratipusRepository")
 * @ORM\Table(name="jogaoratipus",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class Jogaoratipus {
	/**
	 * @ORM\Id @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/** @ORM\Column(type="string",length=255,nullable=false) */
	private $nev;
    /** @ORM\Column(type="text",nullable=true) */
    private $leiras = '';
    /** @ORM\Column(type="string",length=7,nullable=true) */
    private $szin;
    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
	private $arnovelo;
    /** @ORM\Column(type="boolean", nullable=false) */
    private $inaktiv = false;
    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $url;

	public function getId() {
	    return $this->id;
	}

	public function getNev() {
	    return $this->nev;
	}

	public function setNev($nev) {
	    $this->nev = $nev;
	}

    /**
     * @return mixed
     */
    public function getInaktiv() {
        return $this->inaktiv;
    }

    /**
     * @param mixed $inaktiv
     */
    public function setInaktiv($inaktiv) {
        $this->inaktiv = $inaktiv;
    }

    /**
     * @return mixed
     */
    public function getLeiras() {
        return $this->leiras;
    }

    /**
     * @param mixed $leiras
     */
    public function setLeiras($leiras) {
        $this->leiras = $leiras;
    }

    /**
     * @return mixed
     */
    public function getSzin() {
        return $this->szin;
    }

    /**
     * @param mixed $szin
     */
    public function setSzin($szin) {
        $this->szin = $szin;
    }

    /**
     * @return mixed
     */
    public function getArnovelo() {
        return $this->arnovelo;
    }

    /**
     * @param mixed $arnovelo
     */
    public function setArnovelo($arnovelo) {
        $this->arnovelo = $arnovelo;
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

}