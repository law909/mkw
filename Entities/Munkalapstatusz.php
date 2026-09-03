<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\MunkalapstatuszRepository")
 * @ORM\Table(name="munkalapstatusz",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class Munkalapstatusz
{

    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /** @ORM\Column(type="string",length=30,nullable=true) */
    private $kod;

    /** @ORM\Column(type="string",length=255) */
    private $nev;

    /** @ORM\Column(type="integer",nullable=true) */
    private $sorrend;

    /**
     * @ORM\ManyToOne(targetEntity="Emailtemplate")
     * @ORM\JoinColumn(name="emailtemplate_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $emailtemplate;

    public function getId()
    {
        return $this->id;
    }

    public function getKod()
    {
        return $this->kod;
    }

    public function setKod($kod)
    {
        $this->kod = $kod;
    }

    public function getNev()
    {
        return $this->nev;
    }

    public function setNev($nev)
    {
        $this->nev = $nev;
    }

    public function getSorrend()
    {
        return $this->sorrend;
    }

    public function setSorrend($sorrend)
    {
        $this->sorrend = $sorrend;
    }

    /** @return \Entities\Emailtemplate|null */
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
        return '';
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

}
