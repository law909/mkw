<?php
namespace Entities;

/**
 * @Entity(repositoryClass="Entities\AfaRepository")
 * @Table(name="afa")
 */
class Afa {
	/**
	 * @Id @Column(type="integer")
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/** @Column(type="string",length=255,nullable=false) */
	private $nev;
	/** @Column(type="integer") */
	private $ertek=0;
	/** @OneToMany(targetEntity="Bizonylattetel", mappedBy="afa",cascade={"persist","remove"}) */
	private $bizonylattetelek;

	public function getId() {
	    return $this->id;
	}

	public function getNev() {
	    return $this->nev;
	}

	public function setNev($nev) {
	    $this->nev = $nev;
	}

	public function getErtek() {
	    return $this->ertek;
	}

	public function setErtek($ertek) {
	   	$this->ertek = $ertek;
	}

	public function calcBrutto($netto) {
		return $netto*(100+$this->ertek)/100;
	}

	public function calcNetto($brutto) {
		return $brutto/(100+$this->ertek)*100;
	}
}