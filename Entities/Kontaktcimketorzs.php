<?php
namespace Entities;

/**
 * @Entity(repositoryClass="Entities\KontaktcimketorzsRepository")
 */
class Kontaktcimketorzs extends Cimketorzs {
	/**
	 * @gedmo:Sluggable
	 * @Column(type="string",length=255,nullable=false)
	 */
	private $nev;
	/**
	 * @gedmo:Slug
	 * @Column(type="string",length=255,nullable=true)
	 */
	private $slug;
	/** @ManyToMany(targetEntity="Kontakt", mappedBy="cimkek", cascade={"persist","remove"}) */
	private $kontaktok;
	/**
	 * @ManyToOne(targetEntity="Kontaktcimkekat",inversedBy="cimkek")
	 * @JoinColumn(name="cimkekat_id",referencedColumnName="id",onDelete="cascade")
	 */
	private $kategoria;

	public function __construct() {
		$this->kontaktok=new \Doctrine\Common\Collections\ArrayCollection();
	}

		public function getKategoria() {
	    return $this->kategoria;
	}

	public function getKategoriaId() {
		if ($this->kategoria) {
			return $this->kategoria->getId();
		}
		return '';
	}

	public function setKategoria(Cimkekat $kategoria) {
		if ($this->kategoria!==$kategoria) {
	    	$this->kategoria=$kategoria;
	    	$kategoria->addCimke($this);
		}
	}

	public function removeKategoria() {
		if ($this->kategoria !== null) {
			$kategoria = $this->kategoria;
			$this->kategoria = null;
			$kategoria->removeCimke($this);
		}
	}

	public function getNev() {
		return $this->nev;
	}

	public function setNev($nev) {
		$this->nev = $nev;
	}

	public function getSlug() {
		return $this->slug;
	}

	public function setSlug($slug) {
		$this->slug=$slug;
	}

	public function getKontaktok() {
		return $this->kontaktok;
	}

	public function addKontakt(Kontakt $kontakt) {
		if (!$this->kontaktok->contains($kontakt)) {
			$this->kontaktok->add($kontakt);
			$kontakt->addCimke($this);
		}
	}

	public function removeKontakt(Kontakt $kontakt) {
		if ($this->kontaktok->removeElement($kontakt)) {
			$kontakt->removeCimke($this);
			return true;
		}
		return false;
	}
}