<?php
namespace Entities;

use Doctrine\ORM\Mapping as ORM;
use mkw\store;

/**
 * @ORM\Entity @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="feladat",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="osztaly", type="string", length=30)
 * @ORM\DiscriminatorMap({"teendo"="Teendo", "esemeny"="Esemeny"})
 */
abstract class OsFeladat {
	/**
	 * @ORM\Id @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/** @ORM\Column(type="string",length=255) */
	private $bejegyzes;
	/** @ORM\Column(type="text",nullable=true) */
	private $leiras;
	/** @ORM\Column(type="datetime") */
	private $letrehozva;
	/** @ORM\Column(type="datetime") */
	private $esedekes;

	public function getId() {
		return $this->id;
	}

	public function getBejegyzes() {
		return $this->bejegyzes;
	}

	public function setBejegyzes($bejegyzes) {
		$this->bejegyzes = $bejegyzes;
	}

	public function getLeiras() {
		return $this->leiras;
	}

	public function setLeiras($leiras) {
		$this->leiras = $leiras;
	}

	public function getLetrehozva() {
		return $this->letrehozva;
	}

	/** @ORM\PrePersist */
	public function setLetrehozvaOnPreInsert() {
		$this->letrehozva = new \DateTime();
	}

	public function setLetrehozva($letrehozva) {
		/* üres ld. setLetrehozvaOnPreInsert() */
	}

	public function getEsedekes() {
		return $this->esedekes;
	}

	public function getEsedekesStr() {
		if ($this->getEsedekes()) {
			return $this->getEsedekes()->format(store::$DateFormat);
		}
		return '';
	}

	public function setEsedekes($esedekes='') {
		if ($esedekes=='') $esedekes=date(store::$DateFormat);
		$this->esedekes = new \DateTime(store::convDate($esedekes));
	}
}