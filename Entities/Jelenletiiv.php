<?php
namespace Entities;
use mkw\store;

/**
 * @Entity(repositoryClass="Entities\JelenletiivRepository")
 * @Table(name="jelenletiiv")
 */
use Doctrine\ORM\Query\Expr\Func;

class Jelenletiiv {
	/**
	 * @Id @Column(type="integer")
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/** @Column(type="date") */
	private $datum;
	/**
	 * @ManyToOne(targetEntity="Dolgozo",inversedBy="jelenletek")
	 * @JoinColumn(name="dolgozo_id", referencedColumnName="id",nullable=true,onDelete="cascade")
	 */
	private $dolgozo;
	/** @Column(type="integer") */
	private $munkaido;
	/**
	 * @ManyToOne(targetEntity="Jelenlettipus")
	 * @JoinColumn(name="jelenlettipus_id", referencedColumnName="id",nullable=true,onDelete="cascade")
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