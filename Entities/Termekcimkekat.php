<?php
namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\TermekcimkekatRepository")
 */
class Termekcimkekat extends Cimkekat {
	/**
	 * @ORM\Column(type="string",length=100,nullable=true)
	 */
	private $nev;
	/**
	 * @Gedmo\Slug(fields={"nev"})
	 * @ORM\Column(type="string",length=255,nullable=true)
	 */
	private $slug;
	/** @ORM\OneToMany(targetEntity="Termekcimketorzs", mappedBy="kategoria", cascade={"persist"}) */
	private $cimkek;
	/** @ORM\Column(type="integer",nullable=true) */
    private $emagid;

	public function getCimkek() {
		return $this->cimkek;
	}

	public function AddCimke(Cimketorzs $cimke) {
		if (!$this->cimkek->contains($cimke)) {
			$this->cimkek->add($cimke);
			$cimke->setKategoria($this);
		}
	}

	public function removeCimke(Cimketorzs $cimke) {
		if ($this->cimkek->removeElement($cimke)) {
			$cimke->removeKategoria();
			return true;
		}
		return false;
	}

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