<?php
namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\MunkakorRepository")
 * @ORM\Table(name="munkakor",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class Munkakor {
	/**
	 * @ORM\Id @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/**
	 * @ORM\Column(type="string",length=255)
	 */
	private $nev;
	/** @ORM\OneToMany(targetEntity="Dolgozo", mappedBy="munkakor") */
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