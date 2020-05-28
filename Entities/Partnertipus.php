<?php
namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\PartnertipusRepository")
 * @ORM\Table(name="partnertipus",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class Partnertipus {
    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */

    private $id;
    /**
     * @ORM\Column(type="string",length=255)
     */
    private $nev;

    /** @ORM\OneToMany(targetEntity="Partner", mappedBy="szallitasimod",cascade={"persist"}) */
    private $partnerek;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $belephet = 1;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $belephet2 = 1;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $belephet3 = 1;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $belephet4 = 1;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $belephet5 = 1;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $belephet6 = 1;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $belephet7 = 1;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $belephet8 = 1;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $belephet9 = 1;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $belephet10 = 1;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $belephet11 = 1;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $belephet12 = 1;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $belephet13 = 1;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $belephet14 = 1;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $belephet15 = 1;

    public function __construct() {
        $this->partnerek = new \Doctrine\Common\Collections\ArrayCollection();
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

    public function getXBelephet() {
        switch (\mkw\store::getSetupValue('webshopnum', 1)) {
            case 1:
                return $this->getBelephet();
            case 2:
                return $this->getBelephet2();
            case 3:
                return $this->getBelephet3();
            case 4:
                return $this->getBelephet4();
            case 5:
                return $this->getBelephet5();
            case 6:
                return $this->getBelephet6();
            case 7:
                return $this->getBelephet7();
            case 8:
                return $this->getBelephet8();
            case 9:
                return $this->getBelephet9();
            case 10:
                return $this->getBelephet10();
            case 11:
                return $this->getBelephet11();
            case 12:
                return $this->getBelephet12();
            case 13:
                return $this->getBelephet13();
            case 14:
                return $this->getBelephet14();
            case 15:
                return $this->getBelephet15();
            default:
                return $this->getBelephet();
        }
    }

    /**
     * @return int
     */
    public function getBelephet() {
        return $this->belephet;
    }

    /**
     * @param int $belephet
     */
    public function setBelephet($belephet) {
        $this->belephet = $belephet;
    }

    /**
     * @return int
     */
    public function getBelephet2() {
        return $this->belephet2;
    }

    /**
     * @param int $belephet2
     */
    public function setBelephet2($belephet2) {
        $this->belephet2 = $belephet2;
    }

    /**
     * @return int
     */
    public function getBelephet3() {
        return $this->belephet3;
    }

    /**
     * @param int $belephet3
     */
    public function setBelephet3($belephet3) {
        $this->belephet3 = $belephet3;
    }

    /**
     * @return int
     */
    public function getBelephet4() {
        return $this->belephet4;
    }

    /**
     * @param int $belephet4
     */
    public function setBelephet4($belephet4) {
        $this->belephet4 = $belephet4;
    }

    /**
     * @return int
     */
    public function getBelephet5() {
        return $this->belephet5;
    }

    /**
     * @param int $belephet5
     */
    public function setBelephet5($belephet5) {
        $this->belephet5 = $belephet5;
    }

    /**
     * @return int
     */
    public function getBelephet6() {
        return $this->belephet6;
    }

    /**
     * @param int $belephet6
     */
    public function setBelephet6($belephet6) {
        $this->belephet6 = $belephet6;
    }

    /**
     * @return int
     */
    public function getBelephet7() {
        return $this->belephet7;
    }

    /**
     * @param int $belephet7
     */
    public function setBelephet7($belephet7) {
        $this->belephet7 = $belephet7;
    }

    /**
     * @return int
     */
    public function getBelephet8() {
        return $this->belephet8;
    }

    /**
     * @param int $belephet8
     */
    public function setBelephet8($belephet8) {
        $this->belephet8 = $belephet8;
    }

    /**
     * @return int
     */
    public function getBelephet9() {
        return $this->belephet9;
    }

    /**
     * @param int $belephet9
     */
    public function setBelephet9($belephet9) {
        $this->belephet9 = $belephet9;
    }

    /**
     * @return int
     */
    public function getBelephet10() {
        return $this->belephet10;
    }

    /**
     * @param int $belephet10
     */
    public function setBelephet10($belephet10) {
        $this->belephet10 = $belephet10;
    }

    /**
     * @return int
     */
    public function getBelephet11() {
        return $this->belephet11;
    }

    /**
     * @param int $belephet11
     */
    public function setBelephet11($belephet11) {
        $this->belephet11 = $belephet11;
    }

    /**
     * @return int
     */
    public function getBelephet12() {
        return $this->belephet12;
    }

    /**
     * @param int $belephet12
     */
    public function setBelephet12($belephet12) {
        $this->belephet12 = $belephet12;
    }

    /**
     * @return int
     */
    public function getBelephet13() {
        return $this->belephet13;
    }

    /**
     * @param int $belephet13
     */
    public function setBelephet13($belephet13) {
        $this->belephet13 = $belephet13;
    }

    /**
     * @return int
     */
    public function getBelephet14() {
        return $this->belephet14;
    }

    /**
     * @param int $belephet14
     */
    public function setBelephet14($belephet14) {
        $this->belephet14 = $belephet14;
    }

    /**
     * @return int
     */
    public function getBelephet15() {
        return $this->belephet15;
    }

    /**
     * @param int $belephet15
     */
    public function setBelephet15($belephet15) {
        $this->belephet15 = $belephet15;
    }

}