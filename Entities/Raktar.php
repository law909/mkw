<?php
namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\RaktarRepository")
 * @ORM\Table(name="raktar",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class Raktar {
	/**
	 * @ORM\Id @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/** @ORM\Column(type="string",length=50) */
	private $nev;
	/** @ORM\Column(type="boolean",nullable=false) */
	private $mozgat;
	/** @ORM\OneToMany(targetEntity="Bizonylatfej", mappedBy="raktar",cascade={"persist"}) */
	private $bizonylatfejek;

	public function getId() {
		return $this->id;
	}

	public function getNev() {
		return $this->nev;
	}

	public function setNev($nev) {
		$this->nev = $nev;
	}

	public function getMozgat() {
		return $this->mozgat;
	}

	public function setMozgat($val) {
		$this->mozgat=$val;
	}
}