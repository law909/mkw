<?php
namespace Entities;
use mkw\store;
/**
 * @Entity @HasLifecycleCallbacks
 * @Table(name="feladat")
 * @InheritanceType("SINGLE_TABLE")
 * @DiscriminatorColumn(name="osztaly", type="string", length=30)
 * @DiscriminatorMap({"teendo"="Teendo", "esemeny"="Esemeny"})
 */
class OsFeladat {
	/**
	 * @Id @Column(type="integer")
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/** @Column(type="string",length=255) */
	private $bejegyzes;
	/** @Column(type="text",nullable=true) */
	private $leiras;
	/** @Column(type="datetime") */
	private $letrehozva;
	/** @Column(type="datetime") */
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

	/** @PrePersist */
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