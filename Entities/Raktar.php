<?php
namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\RaktarRepository")
 * @ORM\Table(name="raktar",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class Raktar {
	/**
	 * @ORM\Id @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/** @ORM\Column(type="string",length=50) */
	private $nev;
	/** @ORM\Column(type="boolean",nullable=false) */
	private $mozgat;
    /** @ORM\Column(type="boolean",nullable=true) */
    private $archiv;
    /** @ORM\Column(type="string",length=255) */
    private $idegenkod;
	/**
	 * Whether this warehouse's stock counts towards the webshop with that number
	 * (lathato = webshop 1), the way Termek/TermekValtozat lathato..lathato15 work.
	 */
	/** @ORM\Column(type="boolean",nullable=false,options={"default":1}) */
	private $lathato = true;
	/** @ORM\Column(type="boolean",nullable=false,options={"default":1}) */
	private $lathato2 = true;
	/** @ORM\Column(type="boolean",nullable=false,options={"default":1}) */
	private $lathato3 = true;
	/** @ORM\Column(type="boolean",nullable=false,options={"default":1}) */
	private $lathato4 = true;
	/** @ORM\Column(type="boolean",nullable=false,options={"default":1}) */
	private $lathato5 = true;
	/** @ORM\Column(type="boolean",nullable=false,options={"default":1}) */
	private $lathato6 = true;
	/** @ORM\Column(type="boolean",nullable=false,options={"default":1}) */
	private $lathato7 = true;
	/** @ORM\Column(type="boolean",nullable=false,options={"default":1}) */
	private $lathato8 = true;
	/** @ORM\Column(type="boolean",nullable=false,options={"default":1}) */
	private $lathato9 = true;
	/** @ORM\Column(type="boolean",nullable=false,options={"default":1}) */
	private $lathato10 = true;
	/** @ORM\Column(type="boolean",nullable=false,options={"default":1}) */
	private $lathato11 = true;
	/** @ORM\Column(type="boolean",nullable=false,options={"default":1}) */
	private $lathato12 = true;
	/** @ORM\Column(type="boolean",nullable=false,options={"default":1}) */
	private $lathato13 = true;
	/** @ORM\Column(type="boolean",nullable=false,options={"default":1}) */
	private $lathato14 = true;
	/** @ORM\Column(type="boolean",nullable=false,options={"default":1}) */
	private $lathato15 = true;
	/** @ORM\OneToMany(targetEntity="Bizonylatfej", mappedBy="raktar",cascade={"persist"}) */
	private $bizonylatfejek;

	public function getId() {
		return $this->id;
	}

	public function getNev() {
		return $this->nev;
	}

	public function setNev($nev) {
		$this->nev = $nev;
	}

	public function getMozgat() {
		return $this->mozgat;
	}

	public function setMozgat($val) {
		$this->mozgat=$val;
	}

    /**
     * @return mixed
     */
    public function getArchiv() {
        return $this->archiv;
    }

    /**
     * @param mixed $archiv
     */
    public function setArchiv($archiv) {
        $this->archiv = $archiv;
    }

    /**
     * @return mixed
     */
    public function getIdegenkod() {
        return $this->idegenkod;
    }

    /**
     * @param mixed $idegenkod
     */
    public function setIdegenkod($idegenkod): void {
        $this->idegenkod = $idegenkod;
    }

    public function getLathato() {
        return $this->lathato;
    }

    public function setLathato($val) {
        $this->lathato = $val;
    }

    public function getLathato2() {
        return $this->lathato2;
    }

    public function setLathato2($val) {
        $this->lathato2 = $val;
    }

    public function getLathato3() {
        return $this->lathato3;
    }

    public function setLathato3($val) {
        $this->lathato3 = $val;
    }

    public function getLathato4() {
        return $this->lathato4;
    }

    public function setLathato4($val) {
        $this->lathato4 = $val;
    }

    public function getLathato5() {
        return $this->lathato5;
    }

    public function setLathato5($val) {
        $this->lathato5 = $val;
    }

    public function getLathato6() {
        return $this->lathato6;
    }

    public function setLathato6($val) {
        $this->lathato6 = $val;
    }

    public function getLathato7() {
        return $this->lathato7;
    }

    public function setLathato7($val) {
        $this->lathato7 = $val;
    }

    public function getLathato8() {
        return $this->lathato8;
    }

    public function setLathato8($val) {
        $this->lathato8 = $val;
    }

    public function getLathato9() {
        return $this->lathato9;
    }

    public function setLathato9($val) {
        $this->lathato9 = $val;
    }

    public function getLathato10() {
        return $this->lathato10;
    }

    public function setLathato10($val) {
        $this->lathato10 = $val;
    }

    public function getLathato11() {
        return $this->lathato11;
    }

    public function setLathato11($val) {
        $this->lathato11 = $val;
    }

    public function getLathato12() {
        return $this->lathato12;
    }

    public function setLathato12($val) {
        $this->lathato12 = $val;
    }

    public function getLathato13() {
        return $this->lathato13;
    }

    public function setLathato13($val) {
        $this->lathato13 = $val;
    }

    public function getLathato14() {
        return $this->lathato14;
    }

    public function setLathato14($val) {
        $this->lathato14 = $val;
    }

    public function getLathato15() {
        return $this->lathato15;
    }

    public function setLathato15($val) {
        $this->lathato15 = $val;
    }

}