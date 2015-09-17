<?php
namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\BizonylatstatuszRepository")
 * @ORM\Table(name="bizonylatstatusz")
 */
class Bizonylatstatusz {

    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string",length=255)
     */
    private $nev;

    /** @ORM\Column(type="integer"),nullable=true) */
    private $sorrend;

    /**
     * @ORM\Column(type="string",length=255, nullable=true)
     */
    private $csoport;

    /** @ORM\Column(type="boolean") */
    private $foglal;

    /**
     * @ORM\ManyToOne(targetEntity="Emailtemplate",inversedBy="bizonylatstatuszok")
     * @ORM\JoinColumn(name="emailtemplate_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $emailtemplate;
	/** @ORM\OneToMany(targetEntity="Bizonylatfej", mappedBy="bizonylatstatusz",cascade={"persist"}) */
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

    public function setNev($nev) {
        $this->nev = $nev;
    }

    public function getEmailtemplate() {
        return $this->emailtemplate;
    }

    public function getEmailtemplateId() {
        if ($this->emailtemplate) {
            return $this->emailtemplate->getId();
        }
        return null;
    }

    public function getEmailtemplateNev() {
        if ($this->emailtemplate) {
            return $this->emailtemplate->getNev();
        }
        return null;
    }

    public function setEmailtemplate($val) {
        if ($this->emailtemplate !== $val) {
            $this->emailtemplate = $val;
        }
    }

    public function removeEmailtemplate() {
        if ($this->emailtemplate !== null) {
            $this->emailtemplate = null;
        }
    }

    public function getSorrend() {
        return $this->sorrend;
    }

    public function setSorrend($s) {
        $this->sorrend = $s;
    }

    public function getCsoport() {
        return $this->csoport;
    }

    public function setCsoport($adat) {
        $this->csoport = $adat;
    }

    public function getFoglal() {
        return $this->foglal;
    }

    public function setFoglal($adat) {
        $this->foglal = $adat;
    }

}
