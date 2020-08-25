<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection;

/** @ORM\Entity(repositoryClass="Entities\DolgozoRepository")
 *  @ORM\Table(name="dolgozo",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 * */
class Dolgozo {

    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /** @ORM\Column(type="string",length=255) */
    private $nev;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $jelszo;

    /** @ORM\Column(type="string",length=10,nullable=true) */
    private $irszam;

    /** @ORM\Column(type="string",length=40,nullable=true) */
    private $varos;

    /** @ORM\Column(type="string",length=60,nullable=true) */
    private $utca;

    /** @ORM\Column(type="string",length=40,nullable=true) */
    private $telefon;

    /** @ORM\Column(type="string",length=100,nullable=true) */
    private $email;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $url;

    /**
     * @ORM\ManyToOne(targetEntity="Munkakor",inversedBy="dolgozok")
     * @ORM\JoinColumn(name="munkakor_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $munkakor;

    /** @ORM\Column(type="date",nullable=true) */
    private $szulido;

    /** @ORM\Column(type="string",length=60,nullable=true) */
    private $szulhely;

    /** @ORM\Column(type="integer",nullable=true) */
    private $evesmaxszabi = 0;

    /** @ORM\Column(type="date") */
    private $munkaviszonykezdete;

    /** @ORM\OneToMany(targetEntity="Jelenletiiv", mappedBy="dolgozo") */
    private $jelenletek;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $uitheme;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $havilevonas;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $szamlatad = 1;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $inaktiv = false;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $oraelmaradaskonyvelonek = false;

    public function __construct() {
        $this->jelenletek = new ArrayCollection();
    }

    public function getId() {
        return $this->id;
    }

    public function getNev() {
        return $this->nev;
    }

    public function setNev($nev) {
        $this->nev = $nev;
    }

    public function getIrszam() {
        return $this->irszam;
    }

    public function setIrszam($irszam) {
        $this->irszam = $irszam;
    }

    public function getVaros() {
        return $this->varos;
    }

    public function setVaros($varos) {
        $this->varos = $varos;
    }

    public function getUtca() {
        return $this->utca;
    }

    public function setUtca($utca) {
        $this->utca = $utca;
    }

    public function getTelefon() {
        return $this->telefon;
    }

    public function setTelefon($telefon) {
        $this->telefon = $telefon;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getSzulido() {
        return $this->szulido;
    }

    public function getSzulidoStr() {
        if ($this->getSzulido()) {
            return $this->getSzulido()->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    public function setSzulido($adat) {
        if ($adat == '') {
            $adat = date(\mkw\store::$DateFormat);
        }
        $this->szulido = new \DateTime(\mkw\store::convDate($adat));
    }

    public function getSzulhely() {
        return $this->szulhely;
    }

    public function setSzulhely($adat) {
        $this->szulhely = $adat;
    }

    public function getEvesmaxszabi() {
        return $this->evesmaxszabi;
    }

    public function setEvesmaxszabi($eves) {
        $this->evesmaxszabi = $eves;
    }

    public function getMunkaviszonykezdete() {
        return $this->munkaviszonykezdete;
    }

    public function getMunkaviszonykezdeteStr() {
        if ($this->getMunkaviszonykezdete()) {
            return $this->getMunkaviszonykezdete()->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    public function setMunkaviszonykezdete($adat) {
        if ($adat == '') {
            $adat = date(\mkw\store::$DateFormat);
        }
        $this->munkaviszonykezdete = new \DateTime(\mkw\store::convDate($adat));
    }

    public function getMunkakor() {
        return $this->munkakor;
    }

    public function getMunkakorNev() {
        if ($this->munkakor) {
            return $this->munkakor->getNev();
        }
        return '';
    }

    public function getMunkakorId() {
        if ($this->munkakor) {
            return $this->munkakor->getId();
        }
        return '';
    }

    public function setMunkakor(Munkakor $munkakor) {
        if ($this->munkakor !== $munkakor) {
            $this->munkakor = $munkakor;
            $munkakor->addDolgozo($this);
        }
    }

    public function removeMunkakor() {
        if ($this->munkakor !== null) {
            $munkakor = $this->munkakor;
            $this->munkakor = null;
            $munkakor->removeDolgozo($this);
        }
    }

    public function getJog() {
        if ($this->munkakor) {
            return $this->munkakor->getJog();
        }
        return 0;
    }

    public function getJelenletek() {
        return $this->jelenletek;
    }

    public function addJelenlet(Jelenletiiv $adat) {
        if (!$this->jelenletek->contains($adat)) {
            $this->jelenletek->add($adat);
            $adat->setDolgozo($this);
        }
    }

    public function removeJelenlet(Jelenletiiv $adat) {
        if ($this->jelenletek->removeElement($adat)) {
            $adat->removeDolgozo();
            return true;
        }
        return false;
    }

    public function getJelszo() {
        return $this->jelszo;
    }

    public function setPlainJelszo($adat) {
        $this->jelszo = $adat;
    }

    public function checkPlainJelszo($adat) {
        return $this->jelszo === $adat;
    }

    public function setJelszo($adat) {
        $this->jelszo = sha1(strtoupper(md5($adat)) . \mkw\store::getAdminSalt());
    }

    public function checkJelszo($adat) {
        return $this->jelszo === sha1(strtoupper(md5($adat)) . \mkw\store::getAdminSalt());
    }

    /**
     * @return mixed
     */
    public function getUitheme() {
        return $this->uitheme;
    }

    /**
     * @param mixed $uitheme
     */
    public function setUitheme($uitheme) {
        $this->uitheme = $uitheme;
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
    public function getHavilevonas() {
        return $this->havilevonas;
    }

    /**
     * @param mixed $havilevonas
     */
    public function setHavilevonas($havilevonas) {
        $this->havilevonas = $havilevonas;
    }

    /**
     * @return mixed
     */
    public function getSzamlatad() {
        return $this->szamlatad;
    }

    /**
     * @param mixed $szamlatad
     */
    public function setSzamlatad($szamlatad) {
        $this->szamlatad = $szamlatad;
    }

    /**
     * @return bool
     */
    public function isInaktiv() {
        return $this->inaktiv;
    }

    /**
     * @param bool $inaktiv
     */
    public function setInaktiv($inaktiv) {
        $this->inaktiv = $inaktiv;
    }

    /**
     * @return bool
     */
    public function isOraelmaradaskonyvelonek() {
        return $this->oraelmaradaskonyvelonek;
    }

    /**
     * @param bool $oraelmaradaskonyvelonek
     */
    public function setOraelmaradaskonyvelonek($oraelmaradaskonyvelonek) {
        $this->oraelmaradaskonyvelonek = $oraelmaradaskonyvelonek;
    }

}