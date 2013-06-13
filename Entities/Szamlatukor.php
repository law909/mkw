<?php
namespace Entities;

/**
 * @Entity(repositoryClass="Entities\SzamlatukorRepository")
 * @Table(name="szamlatukor")
 */
class Szamlatukor {
	/**
	 * @Id @Column(type="string",length=8,nullable=false)
	 */
	private $id;
	/**
	 * @OneToMany(targetEntity="Szamlatukor", mappedBy="parent")
	 */
	private $children;
	/**
	 * @ManyToOne(targetEntity="Szamlatukor", inversedBy="children")
	 * @JoinColumn(name="parent_id", referencedColumnName="id",onDelete="no action")
	 */
	private $parent;
	/** @Column(type="string",length=255,nullable=false) */
	private $nev;
	/** @Column(type="boolean") */
	private $merleg;
	/** @Column(type="boolean") */
	private $analitikus;
	/**
	 * @ManyToOne(targetEntity="Afa")
	 * @JoinColumn(name="afa_id", referencedColumnName="id",nullable=true,onDelete="set null")
	 */
	private $afa;

	public function __construct() {
		$this->children=new \Doctrine\Common\Collections\ArrayCollection();
	}

	public function getId() {
	    return $this->id;
	}

	public function getChildren() {
		return $this->children;
	}

	public function addChild(Szamlatukor $fkvichild) {
		if (!$this->children->contains($fkvichild)) {
			$this->children->add($fkvichild);
			$fkvichild->setParent($this);
		}
	}

	public function removeChild(Szamlatukor $fkvichild) {
		if ($this->children->removeElement($fkvichild)) {
			$fkvichild->removeParent();
			return true;
		}
		return false;
	}

	public function getParent() {
		return $this->parent;
	}

	public function setParent(Szamlatukor $fkviparent) {
		if ($this->parent!==$fkviparent) {
			$this->parent=$fkviparent;
			$fkviparent->addChild($this);
		}
	}

	public function removeParent() {
		if ($this->parent !== null) {
			$parent = $this->parent;
			$this->parent = null;
			$parent->removeChild($this);
		}
	}

	public function getNev() {
	    return $this->nev;
	}

	public function setNev($nev) {
	    $this->nev = $nev;
	}


	public function getMerleg()
	{
	    return $this->merleg;
	}

	public function setMerleg($merleg)
	{
	    $this->merleg = $merleg;
	}

	public function getAnalitikus()
	{
	    return $this->analitikus;
	}

	public function setAnalitikus($analitikus)
	{
	    $this->analitikus = $analitikus;
	}

	public function getAfa()
	{
	    return $this->afa;
	}

	public function getAfaNev() {
		if ($this->afa) {
			return $this->afa->getNev();
		}
		return '';
	}

	public function setAfa($afa)
	{
	    $this->afa = $afa;
	}
}