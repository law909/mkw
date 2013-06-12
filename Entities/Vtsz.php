<?php
namespace Entities;

/**
 * @Entity(repositoryClass="Entities\VtszRepository")
 * @Table(name="vtsz")
 */
class Vtsz {
	/**
	 * @Id @Column(type="integer")
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/** @Column(type="string",length=255,nullable=false) */
	private $nev;
	/**
	 * @ManyToOne(targetEntity="Afa")
	 * @JoinColumn(name="afa_id", referencedColumnName="id",nullable=true,onDelete="no action")
	 */
	private $afa;
	/** @Column(type="boolean") */
	private $kozvetitett=0;
	/** @OneToMany(targetEntity="Bizonylattetel", mappedBy="vtsz",cascade={"persist","remove"}) */
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

	public function getAfa() {
		return $this->afa;
	}

	public function getAfaId() {
		if ($this->afa) {
			return $this->afa->getId();
		}
		return '';
	}

	public function setAfa($afa) {
		$this->afa = $afa;
	}

	public function getKozvetitett() {
	    return $this->kozvetitett;
	}

	public function setKozvetitett($kozvetitett) {
		if (is_bool($kozvetitett)) {
			$this->kozvetitett = $kozvetitett;
		}
	}
}