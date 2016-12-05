<?php
namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\CskRepository")
 * @ORM\Table(name="csk",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class Csk {
	/**
	 * @ORM\Id @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/** @ORM\Column(type="string",length=255,nullable=false) */
	private $nev;
    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
	private $ertek=0;
    /** @ORM\Column(type="integer") */
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

	public function getErtek() {
	    return $this->ertek;
	}

	public function setErtek($ertek) {
	   	$this->ertek = $ertek;
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