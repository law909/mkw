<?php
namespace Entities;

use Doctrine\ORM\Mapping as ORM;
use mkw\store;

/**
 * @ORM\Entity(repositoryClass="Entities\JelenletiivRepository")
 * @ORM\Table(name="jelenletiiv")
 */
class Jelenletiiv {
	/**
	 * @ORM\Id @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/** @ORM\Column(type="date") */
	private $datum;
	/**
	 * @ORM\ManyToOne(targetEntity="Dolgozo",inversedBy="jelenletek")
	 * @ORM\JoinColumn(name="dolgozo_id", referencedColumnName="id",nullable=true,onDelete="cascade")
	 */
	private $dolgozo;
	/** @ORM\Column(type="integer") */
	private $munkaido;
	/**
	 * @ORM\ManyToOne(targetEntity="Jelenlettipus")
	 * @ORM\JoinColumn(name="jelenlettipus_id", referencedColumnName="id",nullable=true,onDelete="cascade")
	 */
	private $jelenlettipus;

	public function getId() {
		return $this->id;
	}

	public function getDatum() {
		return $this->datum;
	}

	public function getDatumStr() {
		if ($this->getDatum()) {
			return $this->getDatum()->format(store::$DateFormat);
		}
		return '';
	}

	public function setDatum($adat) {
		if ($adat=='') $adat=date(store::$DateFormat);
		$this->datum = new \DateTime(store::convDate($adat));
	}

	public function getDolgozo(){
		return $this->dolgozo;
	}

	public function getDolgozoNev() {
		if ($this->dolgozo) {
			return $this->dolgozo->getNev();
		}
		return '';
	}

	public function getDolgozoId() {
		if ($this->dolgozo) {
			return $this->dolgozo->getId();
		}
		return '';
	}

	public function setDolgozo(Dolgozo $adat) {
		if ($this->dolgozo!==$adat) {
			$this->dolgozo=$adat;
			$adat->addJelenlet($this);
		}
	}

	public function removeDolgozo() {
		if ($this->dolgozo !== null) {
			$adat = $this->dolgozo;
			$this->dolgozo = null;
			$adat->removeJelenlet($this);
		}
	}

	public function getMunkaido() {
		return $this->munkaido;
	}

	public function setMunkaido($adat) {
		$this->munkaido=$adat;
	}

	public function getJelenlettipus(){
		return $this->jelenlettipus;
	}

	public function getJelenlettipusNev() {
		if ($this->jelenlettipus) {
			return $this->jelenlettipus->getNev();
		}
		return '';
	}

	public function getJelenlettipusId() {
		if ($this->jelenlettipus) {
			return $this->jelenlettipus->getId();
		}
		return '';
	}

	public function setJelenlettipus(Jelenlettipus $adat) {
		if ($this->jelenlettipus!==$adat) {
			$this->jelenlettipus=$adat;
//			$adat->addJelenlet($this);
		}
	}

	public function removeJelenlettipus() {
		if ($this->jelenlettipus !== null) {
//			$adat = $this->jelenlettipus;
			$this->jelenlettipus= null;
//			$adat->removeJelenlet($this);
		}
	}
}