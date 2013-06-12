<?php
namespace Entities;

/**
 * @Entity(repositoryClass="Entities\TermekKapcsolodoRepository")
 * @Table(name="termekkapcsolodo")
 */
class TermekKapcsolodo {
	/**
	 * @Id @Column(type="integer")
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/** 
	 * @gedmo:Timestampable(on="create")
	 * @Column(type="datetime",nullable=true) 
	 */
	private $created;	
	/** 
	 * @gedmo:Timestampable(on="create")
	 * @gedmo:Timestampable(on="update")
	 * @Column(type="datetime",nullable=true) 
	 */
	private $lastmod;
	/**
	 * @ManyToOne(targetEntity="Termek",inversedBy="termekkapcsolodok")
	 * @JoinColumn(name="termek_id",referencedColumnName="id",onDelete="cascade")
	 */
	private $termek;
	/**
	 * @ManyToOne(targetEntity="Termek",inversedBy="altermekkapcsolodok")
	 * @JoinColumn(name="altermek_id",referencedColumnName="id",onDelete="no action")
	 */
	private $altermek;

	public function getId() {
		return $this->id;
	}

	public function getTermek() {
		return $this->termek;
	}

	public function getTermekNev() {
		if ($this->termek) {
			return $this->termek->getNev();
		}
		return '';
	}

	public function setTermek(Termek $termek) {
		$this->termek = $termek;
//		$termek->addTermekRecept($this);
	}

	public function removeTermek() {
		if ($this->termek !== null) {
			$termek = $this->termek;
			$this->termek = null;
			$termek->removeTermekRecept($this);
		}
	}

	public function getAlTermek() {
		return $this->altermek;
	}

	public function getAlTermekNev() {
		if ($this->altermek) {
			return $this->altermek->getNev();
		}
		return '';
	}

	public function setAlTermek(Termek $altermek) {
		$this->altermek = $altermek;
//		$altermek->addAlTermekRecept($this);
	}

	public function removeAlTermek() {
		if ($this->altermek !== null) {
			$altermek = $this->altermek;
			$this->altermek = null;
			$altermek->removeAlTermekRecept($this);
		}
	}

	public function getLastmod() {
		return $this->lastmod;
	}
	
	public function getCreated() {
		return $this->created;
	}
}