<?php
namespace Entities;

/**
 * @Entity(repositoryClass="Entities\FelhasznaloRepository")
 * @Table(name="felhasznalo")
 */
class Felhasznalo {
	/**
        * @Id @Column(type="string",length=16,nullable=false)
        */
	private $felhasznalonev;
	/** @Column(type="string",length=16) */
	private $jelszo;
	/** @Column(type="string",length=255) */
	private $nev;
	/**
	 * @OneToOne(targetEntity="Uzletkoto")
	 * @JoinColumn(name="uzletkoto_id", referencedColumnName="id",nullable=true,onDelete="set null")
	 */
	private $uzletkoto;
	/** @OneToMany(targetEntity="Targyieszkoz", mappedBy="alkalmazott", cascade={"persist","remove"}) */
	private $targyieszkozok;

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

	public function addTargyieszkoz($targyieszkoz) {
		if (!$this->targyieszkozok->contains($targyieszkoz)) {
			$this->targyieszkozok->add($targyieszkoz);
			$targyieszkoz->setAlkalmazott($this);
		}
	}

	public function getTargyieszkozok() {
		return $this->targyieszkozok;
	}

	public function removeTargyieszkoz($targyieszkoz) {
		if ($this->targyieszkozok->removeElement($targyieszkoz)) {
			$targyieszkoz->removeAlkalmazott();
		}
	}

}