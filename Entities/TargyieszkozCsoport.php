<?php
namespace Entities;

/**
 * @Entity(repositoryClass="Entities\TargyieszkozCsoportRepository")
 * @Table(name="targyieszkozcsoport")
 */
class TargyieszkozCsoport {
	/**
	 * @Id @Column(type="integer")
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/** @Column(type="string",length=255,nullable=false) */
	private $nev;
	/** @Column(type="string",length=8,nullable=true) */
	private $beszerzesiktgfkviszam='';
	/** @Column(type="string",length=8,nullable=true) */
	private $ecsleirasfkviszam='';
	/** @Column(type="string",length=8,nullable=true) */
	private $ecsktgfkviszam='';
	/** @OneToMany(targetEntity="Targyieszkoz", mappedBy="csoport", cascade={"persist","remove"}) */
	private $targyieszkozok;

	public function getId() {
	    return $this->id;
	}

	public function getNev() {
	    return $this->nev;
	}

	public function setNev($nev) {
	    $this->nev = $nev;
	}

	public function getBeszerzesiktgfkviszam()
	{
	    return $this->beszerzesiktgfkviszam;
	}

	public function setBeszerzesiktgfkviszam($beszerzesiktgfkviszam)
	{
	    $this->beszerzesiktgfkviszam = $beszerzesiktgfkviszam;
	}

	public function getEcsleirasfkviszam()
	{
	    return $this->ecsleirasfkviszam;
	}

	public function setEcsleirasfkviszam($ecsleirasfkviszam)
	{
	    $this->ecsleirasfkviszam = $ecsleirasfkviszam;
	}

	public function getEcsktgfkviszam()
	{
	    return $this->ecsktgfkviszam;
	}

	public function setEcsktgfkviszam($ecsktgfkviszam)
	{
	    $this->ecsktgfkviszam = $ecsktgfkviszam;
	}

	public function addTargyieszkoz($targyieszkoz) {
		if (!$this->targyieszkozok->contains($targyieszkoz)) {
			$this->targyieszkozok->add($targyieszkoz);
			$targyieszkoz->setCsoport($this);
		}
	}

	public function getTargyieszkozok() {
		return $this->targyieszkozok;
	}

	public function removeTargyieszkoz($targyieszkoz) {
		if ($this->targyieszkozok->removeElement($targyieszkoz)) {
			$targyieszkoz->removeCsoport();
		}
	}

}