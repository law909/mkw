<?php
namespace Entities;

/**
 * @Entity(repositoryClass="Entities\MunkakorRepository")
 * @Table(name="munkakor")
 */
class Munkakor {
	/**
	 * @Id @Column(type="integer")
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/**
	 * @Column(type="string",length=255)
	 */
	private $nev;
	/** @OneToMany(targetEntity="Dolgozo", mappedBy="munkakor") */
	private $dolgozok;

	public function __construct() {
		$this->dolgozok=new \Doctrine\Common\Collections\ArrayCollection();
	}

	public function getId() {
		return $this->id;
	}

	public function getNev() {
		return $this->nev;
	}

	public function setNev($nev) {
		$this->nev = $nev;
	}

	public function getDolgozok() {
		return $this->dolgozok;
	}

	public function addDolgozo(Dolgozo $dolgozo) {
		if (!$this->dolgozok->contains($dolgozo)) {
			$this->dolgozok->add($dolgozo);
			$dolgozo->setMunkakor($this);
		}
	}

	public function removeDolgozo(Dolgozo $dolgozo) {
		if ($this->dolgozok->removeElement($dolgozo)) {
			$dolgozo->removeMunkakor();
			return true;
		}
		return false;
	}
}