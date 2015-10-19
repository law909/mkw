<?php
namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\FelhasznaloRepository")
 * @ORM\Table(name="felhasznalo",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class Felhasznalo {
	/**
        * @ORM\Id @ORM\Column(type="string",length=16,nullable=false)
        */
	private $felhasznalonev;
	/** @ORM\Column(type="string",length=16) */
	private $jelszo;
	/** @ORM\Column(type="string",length=255) */
	private $nev;
	/**
	 * @ORM\OneToOne(targetEntity="Uzletkoto")
	 * @ORM\JoinColumn(name="uzletkoto_id", referencedColumnName="id",nullable=true,onDelete="set null")
	 */
	private $uzletkoto;

	public function getId() {
		return $this->getFelhasznalonev();
	}

	public function getFelhasznalonev() {
		return $this->felhasznalonev;
	}

	public function setFelhasznalonev($felhasznalonev) {
		return $this->felhasznalonev = $felhasznalonev;
	}

	public function getJelszo() {
		return $this->jelszo;
	}

	public function setJelszo($jelszo) {
		$this->jelszo = $jelszo;
	}

	public function getNev() {
		return $this->nev;
	}

	public function setNev($nev) {
		$this->nev = $nev;
	}

	public function getUzletkoto() {
		return $this->uzletkoto;
	}

	public function getUzletkotoId() {
		if ($this->uzletkoto) {
			return $this->uzletkoto->getId();
		}
		return '';
	}

	public function getUzletkotoNev() {
		if ($this->uzletkoto) {
			return $this->uzletkoto->getNev();
		}
		return '';
	}

	public function setUzletkoto(Uzletkoto $uzletkoto) {
		if ($this->uzletkoto!==$uzletkoto) {
			$this->uzletkoto=$uzletkoto;
			$uzletkoto->setFelhasznalo($this);
		}
	}

	public function removeUzletkoto() {
		if ($this->uzletkoto !== null) {
			$uzletkoto = $this->uzletkoto;
			$this->uzletkoto = null;
			$uzletkoto->removeFelhasznalo($this);
			return true;
		}
		return false;
	}

}