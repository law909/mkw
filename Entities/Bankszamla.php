<?php
namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\BankszamlaRepository")
 * @ORM\Table(name="bankszamla",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class Bankszamla {
    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /** @ORM\Column(type="string",length=50) */
    private $banknev;
    /** @ORM\Column(type="string",length=70) */
    private $bankcim;
    /** @ORM\Column(type="string",length=255,nullable=false) */
    private $szamlaszam;
    /** @ORM\Column(type="string",length=20,nullable=true) */
    private $swift;
    /** @ORM\Column(type="string",length=20,nullable=true) */
    private $iban;
    /**
     * @ORM\ManyToOne(targetEntity="Valutanem")
     * @ORM\JoinColumn(name="valutanem_id",referencedColumnName="id",nullable=true,onDelete="set null")
     */
    private $valutanem;
    /** @ORM\OneToMany(targetEntity="Bizonylatfej", mappedBy="bankszamla") */
    private $bizonylatfejek;
    /** @ORM\Column(type="integer", nullable=true) */
    private $migrid;

    public function getId() {
        return $this->id;
    }

    public function getBanknev() {
        return $this->banknev;
    }

    public function setBanknev($banknev) {
        $this->banknev = $banknev;
    }

    public function getBankcim() {
        return $this->bankcim;
    }

    public function setBankcim($bankcim) {
        $this->bankcim = $bankcim;
    }

    public function getSzamlaszam() {
        return $this->szamlaszam;
    }

    public function setSzamlaszam($szamlaszam) {
        $this->szamlaszam = $szamlaszam;
    }

    public function getSwift() {
        return $this->swift;
    }

    public function setSwift($swift) {
        $this->swift = $swift;
    }

    public function getIban() {
        return $this->iban;
    }

    public function setIban($iban) {
        $this->iban = $iban;
    }

    public function getValutanem() {
        return $this->valutanem;
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