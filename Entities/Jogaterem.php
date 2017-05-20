<?php
namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\JogateremRepository")
 * @ORM\Table(name="jogaterem",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class Jogaterem {
	/**
	 * @ORM\Id @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/** @ORM\Column(type="string",length=255,nullable=false) */
	private $nev;
    /** @ORM\Column(type="integer",nullable=true) */
	private $maxferohely = 0;
    /** @ORM\Column(type="boolean", nullable=false) */
    private $inaktiv = true;

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
    public function getMaxferohely() {
        return $this->maxferohely;
    }

    /**
     * @param mixed $maxferohely
     */
    public function setMaxferohely($maxferohely) {
        $this->maxferohely = $maxferohely;
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


}