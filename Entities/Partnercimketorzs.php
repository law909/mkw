<?php
namespace Entities;

/**
 * @Entity(repositoryClass="Entities\PartnercimketorzsRepository")
 */
class Partnercimketorzs extends Cimketorzs {
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
	/** @ManyToMany(targetEntity="Partner", mappedBy="cimkek", cascade={"persist"}) */
	private $partnerek;
	/**
	 * @ManyToOne(targetEntity="Partnercimkekat",inversedBy="cimkek")
	 * @JoinColumn(name="cimkekat_id",referencedColumnName="id",onDelete="cascade")
	 */
	private $kategoria;

	public function __construct() {
		$this->partnerek=new \Doctrine\Common\Collections\ArrayCollection();
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

	public function getPartnerek() {
		return $this->partnerek;
	}

	public function addPartner(Partner $partner) {
//		if (!$this->partnerek->contains($partner)) {  // deleted for speed
			$this->partnerek->add($partner);
			$partner->addCimke($this);
//		}
	}

	public function removePartner(Partner $partner) {
		if ($this->partnerek->removeElement($partner)) {
			$partner->removeCimke($this);
			return true;
		}
		return false;
	}
}