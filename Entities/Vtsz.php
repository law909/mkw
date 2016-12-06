<?php
namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\VtszRepository")
 * @ORM\Table(name="vtsz",
 * options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"},
 * indexes={
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
	/** @ORM\OneToMany(targetEntity="Bizonylattetel", mappedBy="vtsz",cascade={"persist"}) */
	private $bizonylattetelek;
    /** @ORM\Column(type="integer",nullable=true) */
    private $migrid;
    /**
     * @ORM\ManyToOne(targetEntity="Csk")
     * @ORM\JoinColumn(name="csk_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $csk;
    /**
     * @ORM\ManyToOne(targetEntity="Csk")
     * @ORM\JoinColumn(name="kt_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $kt;

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

    /**
     * @return mixed
     */
    public function getMigrid() {
        return $this->migrid;
    }

    /**
     * @param mixed $migrid
     */
    public function setMigrid($migrid) {
        $this->migrid = $migrid;
    }

    public function getCsk() {
        return $this->csk;
    }

    public function getCskId() {
        if ($this->csk) {
            return $this->csk->getId();
        }
        return '';
    }

    public function setCsk($csk) {
        $this->csk = $csk;
    }

    public function getKt() {
        return $this->kt;
    }

    public function getKtId() {
        if ($this->kt) {
            return $this->kt->getId();
        }
        return '';
    }

    public function setKt($kt) {
        $this->kt = $kt;
    }

}