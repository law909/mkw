<?php
namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\KorzetszamRepository")
 * @ORM\Table(name="korzetszam",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class Korzetszam {
    /**
     * @ORM\Id @ORM\Column(type="string",length=6,nullable=false)
     */
    private $id;
    /** @ORM\Column(type="integer") */
    private $hossz = 0;
	/** @ORM\Column(type="integer") */
	private $sorrend = 0;

	public function getId() {
	    return $this->id;
	}

	public function setId($value) {
	    $this->id = $value;
    }

    /**
     * @return mixed
     */
    public function getHossz() {
        return $this->hossz;
    }

    /**
     * @param mixed $hossz
     */
    public function setHossz($hossz) {
        $this->hossz = $hossz;
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


}