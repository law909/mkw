<?php
namespace Entities;

/**
 * @Entity(repositoryClass="Entities\KontaktcimkekatRepository")
 */
class Kontaktcimkekat extends Cimkekat {
	/**
	 * @gedmo:Sluggable
	 * @Column(type="string",length=100,nullable=true)
	 */
	private $nev;
	/**
	 * @gedmo:Slug
	 * @Column(type="string",length=255,nullable=true)
	 */
	private $slug;
	/** @OneToMany(targetEntity="Kontaktcimketorzs", mappedBy="kategoria", cascade={"persist","remove"}) */
	private $cimkek;

	public function __construct() {
		$this->cimkek=new \Doctrine\Common\Collections\ArrayCollection();
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

}