<?php
namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\FoxpostTerminalRepository")
 * @ORM\Table(name="foxpostterminal")
 */
class FoxpostTerminal {
	/**
	 * @ORM\Id @ORM\Column(type="integer")
	 */
	private $id;

	/** @ORM\Column(type="string",length=70,nullable=true) */
	private $nev;

    /** @ORM\Column(type="string",length=100,nullable=true) */
	private $cim;

    /** @ORM\Column(type="string",length=35,nullable=true) */
	private $group;

    /** @ORM\Column(type="string",length=35,nullable=true) */
	private $subgroup;

    /** @ORM\Column(type="string",length=75,nullable=true) */
	private $open;

    /** @ORM\Column(type="string",length=254,nullable=true) */
	private $findme;

    /** @ORM\Column(type="decimal",precision=8,scale=6,nullable=true) */
    private $geolat;

    /** @ORM\Column(type="decimal",precision=8,scale=6,nullable=true) */
    private $geolng;

    /** @ORM\OneToMany(targetEntity="Bizonylatfej", mappedBy="foxpostterminal",cascade={"persist"}) */
	private $bizonylatfejek;

	public function __construct() {
		$this->bizonylatfejek=new \Doctrine\Common\Collections\ArrayCollection();
	}

    public function getId() {
        return $this->id;
    }

    public function getNev() {
        return $this->nev;
    }

    public function setNev($adat) {
        $this->nev = $adat;
    }

    public function getCim() {
        return $this->cim;
    }

    public function setCim($adat) {
        $this->cim = $adat;
    }

    public function getGroup() {
        return $this->group;
    }

    public function setGroup($adat) {
        $this->group = $adat;
    }

    public function getSubgroup() {
        return $this->subgroup;
    }

    public function setSubgroup($adat) {
        $this->subgroup = $adat;
    }

    public function getOpen() {
        return $this->open;
    }

    public function setOpen($adat) {
        $this->open = $adat;
    }

    public function getFindme() {
        return $this->findme;
    }

    public function setFindme($adat) {
        $this->findme = $adat;
    }

    public function getGeolat() {
        return $this->geolat;
    }

    public function setGeolat($adat) {
        $this->geolat = $adat;
    }

    public function getGeolng() {
        return $this->geolng;
    }

    public function setGeolng($adat) {
        $this->geolng = $adat;
    }

}