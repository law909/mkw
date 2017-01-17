<?php
namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\PartnerTermekcsoportSzerzodesArRepository")
 * @ORM\Table(name="partnertermekcsoportszerzodesar",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class PartnerTermekcsoportSzerzodesAr {

	/**
	 * @ORM\Id @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @Gedmo\Timestampable(on="create")
	 * @ORM\Column(type="datetime",nullable=true)
	 */
	private $created;

	/**
	 * @Gedmo\Timestampable(on="update")
	 * @ORM\Column(type="datetime",nullable=true)
	 */
	private $lastmod;

	/**
	 * @ORM\ManyToOne(targetEntity="PartnerTermekcsoportSzerzodes",inversedBy="arak")
	 * @ORM\JoinColumn(name="partnertermekcsoportszerzodes_id",referencedColumnName="id",nullable=false,onDelete="cascade")
	 */
	private $partnertermekcsoportszerzodes;

	/** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
	private $mennyiseg;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $netto;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $brutto;

	public function getId() {
		return $this->id;
	}

	public function getLastmod() {
		return $this->lastmod;
	}

	public function getCreated() {
		return $this->created;
	}

    /**
     * @return mixed
     */
    public function getPartnertermekcsoportszerzodes() {
        return $this->partnertermekcsoportszerzodes;
    }

    /**
     * @param mixed $partnertermekcsoportszerzodes
     */
    public function setPartnertermekcsoportszerzodes($partnertermekcsoportszerzodes) {
        $this->partnertermekcsoportszerzodes = $partnertermekcsoportszerzodes;
    }

    /**
     * @return mixed
     */
    public function getMennyiseg() {
        return $this->mennyiseg;
    }

    /**
     * @param mixed $mennyiseg
     */
    public function setMennyiseg($mennyiseg) {
        $this->mennyiseg = $mennyiseg;
    }

    /**
     * @return mixed
     */
    public function getNetto() {
        return $this->netto;
    }

    /**
     * @param mixed $netto
     */
    public function setNetto($netto) {
        $this->netto = $netto;
    }

    /**
     * @return mixed
     */
    public function getBrutto() {
        return $this->brutto;
    }

    /**
     * @param mixed $brutto
     */
    public function setBrutto($brutto) {
        $this->brutto = $brutto;
    }

}