<?php
namespace Entities;

/**
 * @Entity(repositoryClass="Entities\BankszamlaRepository")
 * @Table(name="bankszamla")
 */
class Bankszamla {
	/**
	 * @Id @Column(type="integer")
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/** @Column(type="string",length=50) */
	private $banknev;
	/** @Column(type="string",length=70) */
	private $bankcim;
	/** @Column(type="string",length=255,nullable=false) */
	private $szamlaszam;
	/** @Column(type="string",length=20) */
	private $swift;
	/** @Column(type="string",length=20) */
	private $iban;
	/**
	 * @ManyToOne(targetEntity="Valutanem")
	 * @JoinColumn(name="valutanem_id",referencedColumnName="id",nullable=true)
	 */
	private $valutanem;
	/** @OneToMany(targetEntity="Bizonylatfej", mappedBy="bankszamla",cascade={"persist","remove"}) */
	private $bizonylatfejek;

	public function getId() {
		return $this->id;
	}

	public function getBanknev() {
		return $this->banknev;
	}

	public function setBanknev($banknev) {
		$this->banknev = $banknev;
	}

	public function getBankcim() {
		return $this->bankcim;
	}

	public function setBankcim($bankcim) {
		$this->bankcim = $bankcim;
	}

	public function getSzamlaszam() {
		return $this->szamlaszam;
	}

	public function setSzamlaszam($szamlaszam) {
		$this->szamlaszam = $szamlaszam;
	}

	public function getSwift() {
		return $this->swift;
	}

	public function setSwift($swift) {
		$this->swift = $swift;
	}

	public function getIban() {
		return $this->iban;
	}

	public function setIban($iban) {
		$this->iban = $iban;
	}

	public function getValutanem()
	{
	    return $this->valutanem;
	}

	public function setValutanem($valutanem)
	{
	    $this->valutanem = $valutanem;
	}
}