<?php
namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\KeresoszologRepository") @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="keresoszolog")
 */
class Keresoszolog {
	/**
	 * @ORM\Id @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/**
	 * @ORM\Column(type="string",length=255)
	 */
	private $szo;
	/** @ORM\Column(type="datetime",nullable=true) */
	private $datum;

	public function __construct($szo) {
		$this->setSzo($szo);
	}

	public function getId() {
		return $this->id;
	}

	public function getSzo() {
		return $this->szo;
	}

	public function setSzo($szo) {
		$this->szo = $szo;
	}

	public function getDatum() {
		return $this->datum;
	}

	/** @ORM\PrePersist */
	public function setDatumOnPreInsert() {
		$this->datum = new \DateTime();
	}
}