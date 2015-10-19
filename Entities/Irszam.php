<?php
namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\IrszamRepository")
 * @ORM\Table(name="irszam",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class Irszam {
	/**
	 * @ORM\Id @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/**
	 * @ORM\Column(type="string", length=10, nullable=false)
	 */
	private $szam;
	/**
	 * @ORM\Column(type="string",length=255)
	 */
	private $nev;

	public function getId() {
		return $this->id;
	}

	public function getNev() {
		return $this->nev;
	}

	public function setNev($nev) {
		$this->nev = $nev;
	}

	public function getSzam() {
		return $this->szam;
	}

	public function setSzam($szam) {
		$this->szam = $szam;
	}
}