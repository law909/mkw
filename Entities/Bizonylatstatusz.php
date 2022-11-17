<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\BizonylatstatuszRepository")
 * @ORM\Table(name="bizonylatstatusz",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class Bizonylatstatusz
{

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
    /**
     * @ORM\ManyToOne(targetEntity="Fizmod",inversedBy="bizonylatfejek")
     * @ORM\JoinColumn(name="fizmod_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Fizmod
     */
    private $fizmod;

    /**
     * @ORM\ManyToOne(targetEntity="Szallitasimod",inversedBy="bizonylatfejek")
     * @ORM\JoinColumn(name="szallitasimod_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Szallitasimod
     */
    private $szallitasimod;

    /** @ORM\Column(type="boolean") */
    private $nemertekelheto;

    public function __construct()
    {
        $this->bizonylatfejek = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNev()
    {
        return $this->nev;
    }

    public function setNev($nev)
    {
        $this->nev = $nev;
    }

    public function getEmailtemplate()
    {
        return $this->emailtemplate;
    }

    public function getEmailtemplateId()
    {
        if ($this->emailtemplate) {
            return $this->emailtemplate->getId();
        }
        return null;
    }

    public function getEmailtemplateNev()
    {
        if ($this->emailtemplate) {
            return $this->emailtemplate->getNev();
        }
        return null;
    }

    public function setEmailtemplate($val)
    {
        if ($this->emailtemplate !== $val) {
            $this->emailtemplate = $val;
        }
    }

    public function removeEmailtemplate()
    {
        if ($this->emailtemplate !== null) {
            $this->emailtemplate = null;
        }
    }

    public function getSorrend()
    {
        return $this->sorrend;
    }

    public function setSorrend($s)
    {
        $this->sorrend = $s;
    }

    public function getCsoport()
    {
        return $this->csoport;
    }

    public function setCsoport($adat)
    {
        $this->csoport = $adat;
    }

    public function getFoglal()
    {
        return $this->foglal;
    }

    public function setFoglal($adat)
    {
        $this->foglal = $adat;
    }

    /**
     * @return \Entities\Fizmod
     */
    public function getFizmod()
    {
        if (!$this->id && !$this->fizmod) {
            $this->setFizmod(\mkw\store::getParameter(\mkw\consts::Fizmod));
        }
        return $this->fizmod;
    }

    public function getFizmodnev()
    {
        if ($this->getFizmod()) {
            return $this->getFizmod()->getNev();
        }
        return '';
    }

    public function getFizmodId()
    {
        $fm = $this->getFizmod();
        if ($fm) {
            return $fm->getId();
        }
        return '';
    }

    /**
     * @param \Entities\Fizmod|mixed $val
     */
    public function setFizmod($val)
    {
        if (!($val instanceof \Entities\Fizmod)) {
            $val = \mkw\store::getEm()->getRepository('Entities\Fizmod')->find($val);
        }
        if ($this->fizmod !== $val) {
            if (!$val) {
                $this->removeFizmod();
            } else {
                $this->fizmod = $val;
            }
        }
    }

    public function removeFizmod()
    {
        if ($this->fizmod !== null) {
            $this->fizmod = null;
        }
    }

    /**
     * @return \Entities\Szallitasimod
     */
    public function getSzallitasimod()
    {
        return $this->szallitasimod;
    }

    public function getSzallitasimodnev()
    {
        if ($this->szallitasimod) {
            return $this->szallitasimod->getNev();
        }
        return '';
    }

    public function getSzallitasimodId()
    {
        if ($this->szallitasimod) {
            return $this->szallitasimod->getId();
        }
        return '';
    }

    /**
     * @param \Entities\Szallitasimod $val
     */
    public function setSzallitasimod($val)
    {
        if (!($val instanceof \Entities\Szallitasimod)) {
            $val = \mkw\store::getEm()->getRepository('Entities\Szallitasimod')->find($val);
        }
        if ($this->szallitasimod !== $val) {
            if (!$val) {
                $this->removeSzallitasimod();
            } else {
                $this->szallitasimod = $val;
            }
        }
    }

    public function removeSzallitasimod()
    {
        if ($this->szallitasimod !== null) {
            $this->szallitasimod = null;
        }
    }

    /**
     * @return mixed
     */
    public function getNemertekelheto()
    {
        return $this->nemertekelheto;
    }

    /**
     * @param mixed $nemertekelheto
     */
    public function setNemertekelheto($nemertekelheto): void
    {
        $this->nemertekelheto = $nemertekelheto;
    }

}
