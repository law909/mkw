<?php
namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\AfaRepository")
 * @ORM\Table(name="afa",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class Afa {
	/**
	 * @ORM\Id @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/** @ORM\Column(type="string",length=255,nullable=false) */
	private $nev;
	/** @ORM\Column(type="integer") */
	private $ertek=0;
    /** @ORM\Column(type="integer", nullable=true) */
    private $rlbkod;
    /** @ORM\Column(type="integer", nullable=true) */
    private $migrid;
    /** @ORM\Column(type="integer",nullable=true) */
    private $emagid;
	/** @ORM\OneToMany(targetEntity="Bizonylattetel", mappedBy="afa") */
	private $bizonylattetelek;

	public function getId() {
	    return $this->id;
	}

	public function getNev() {
	    return $this->nev;
	}

	public function setNev($nev) {
	    $this->nev = $nev;
	}

	public function getErtek() {
	    return $this->ertek;
	}

	public function setErtek($ertek) {
	   	$this->ertek = $ertek;
	}

    public function getRLBkod() {
        return $this->rlbkod;
    }

    public function setRLBKod($d) {
        $this->rlbkod = $d;
    }

	public function calcBrutto($netto) {
		return $netto * (100 + $this->ertek) / 100;
	}

	public function calcNetto($brutto) {
		return $brutto / (100 + $this->ertek) * 100;
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

    /**
     * @return mixed
     */
    public function getEmagid() {
        return $this->emagid;
    }

    /**
     * @param mixed $emagid
     */
    public function setEmagid($emagid) {
        $this->emagid = $emagid;
    }

}