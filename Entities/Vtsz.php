<?php
namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\VtszRepository")
 * @ORM\Table(name="vtsz",indexes={
 *      @ORM\index(name="vtszszam_idx",columns={"szam"})
 * })
 */
class Vtsz {
	/**
	 * @ORM\Id @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

    /** @ORM\Column(type="string",length=255,nullable=false) */
	private $szam;

	/** @ORM\Column(type="string",length=255,nullable=true) */
	private $nev;
	/**
	 * @ORM\ManyToOne(targetEntity="Afa")
	 * @ORM\JoinColumn(name="afa_id", referencedColumnName="id",nullable=true,onDelete="restrict")
	 */
	private $afa;
	/** @ORM\Column(type="boolean") */
	private $kozvetitett=0;
	/** @ORM\OneToMany(targetEntity="Bizonylattetel", mappedBy="vtsz",cascade={"persist"}) */
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

	public function getSzam() {
		return $this->szam;
	}

	public function setSzam($nev) {
		$this->szam = $nev;
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