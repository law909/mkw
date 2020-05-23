<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="Entities\JogaBejelentkezesRepository")
 * @ORM\Table(name="jogabejelentkezes",
 * options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class JogaBejelentkezes {

    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id = 0;

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
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="Dolgozo")
     * @ORM\JoinColumn(name="createdby", referencedColumnName="id")
     */
    private $createdby;

    /**
     * @Gedmo\Blameable(on="update")
     * @ORM\ManyToOne(targetEntity="Dolgozo")
     * @ORM\JoinColumn(name="updatedby", referencedColumnName="id")
     */
    private $updatedby;

    /**
     * @ORM\ManyToOne(targetEntity="Orarend", inversedBy="bejelentkezesek")
     * @ORM\JoinColumn(name="orarend_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Orarend
     */
    private $orarend;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $partnernev;

    /** @ORM\Column(type="string",length=100,nullable=true) */
    private $partneremail = '';

    /**
     * @ORM\Column(type="date",nullable=true)
     */
    private $datum;

    public function getId() {
        return $this->id;
    }

    public function getLastmod() {
        return $this->lastmod;
    }

    public function getLastmodStr() {
        if ($this->getLastmod()) {
            return $this->getLastmod()->format(\mkw\store::$DateTimeFormat);
        }
        return '';
    }

    public function clearLastmod() {
        $this->lastmod = null;
    }

    public function getCreated() {
        return $this->created;
    }

    public function getCreatedStr() {
        if ($this->getCreated()) {
            return $this->getCreated()->format(\mkw\store::$DateTimeFormat);
        }
        return '';
    }

    public function clearCreated() {
        $this->created = null;
    }

    /**
     * @return mixed
     */
    public function getCreatedby() {
        return $this->createdby;
    }

    public function getCreatedbyId() {
        if ($this->createdby) {
            return $this->createdby->getId();
        }
        return null;
    }

    public function getCreatedbyNev() {
        if ($this->createdby) {
            return $this->createdby->getNev();
        }
        return null;
    }

    /**
     * @return mixed
     */
    public function getUpdatedby() {
        return $this->updatedby;
    }

    public function getUpdatedbyId() {
        if ($this->updatedby) {
            return $this->updatedby->getId();
        }
        return null;
    }

    public function getUpdatedbyNev() {
        if ($this->updatedby) {
            return $this->updatedby->getNev();
        }
        return null;
    }

    /**
     * @return \Entities\Orarend
     */
    public function getOrarend() {
        return $this->orarend;
    }

    public function getOrarendId() {
        if ($this->orarend) {
            return $this->orarend->getId();
        }
        return '';
    }

    /**
     * @param \Entities\Orarend $val
     */
    public function setOrarend($val) {
        if ($this->orarend !== $val) {
            $this->orarend = $val;
        }
    }

    public function getPartnernev() {
        return $this->partnernev;
    }

    public function setPartnernev($val) {
        $this->partnernev = $val;
    }

    /**
     * @return mixed
     */
    public function getPartneremail() {
        return $this->partneremail;
    }

    /**
     * @param mixed $partneremail
     */
    public function setPartneremail($partneremail) {
        $this->partneremail = $partneremail;
    }

    public function getDatum() {
        return $this->datum;
    }

    public function getDatumStr() {
        if ($this->getDatum()) {
            return $this->getDatum()->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    public function getDatumNapnev() {
        if ($this->getDatum()) {
            return \mkw\store::getDayname($this->getDatum()->format('N'));
        }
        return '';
    }

    public function setDatum($adat = '') {
        if (is_a($adat, 'DateTime')) {
            $this->datum = $adat;
        }
        else {
            if ($adat == '') {
                $adat = date(\mkw\store::$DateFormat);
            }
            $this->datum = new \DateTime(\mkw\store::convDate($adat));
        }
    }
}