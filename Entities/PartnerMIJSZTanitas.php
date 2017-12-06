<?php

namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Doctrine\ORM\Mapping\Entity(repositoryClass="PartnerMIJSZTanitasRepository")
 * @Doctrine\ORM\Mapping\Table(name="partnermijsztanitas",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class PartnerMIJSZTanitas {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Partner",inversedBy="mijszoralatogatas")
     * @ORM\JoinColumn(name="partner_id",referencedColumnName="id",onDelete="cascade")
     */
    private $partner;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $helyszin;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $mikor;

    /** @ORM\Column(type="integer",nullable=true) */
    private $nap;

    /**
     * @ORM\ManyToOne(targetEntity="MIJSZGyakorlasszint",inversedBy="mijszgyakorlasszint")
     * @ORM\JoinColumn(name="mijszgyakorlasszint_id",referencedColumnName="id",onDelete="cascade")
     */
    private $szint;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $szintegyeb;

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


    public function toLista() {
        $r = array();
        $r['id'] = $this->getId();
        $r['lastmodstr'] = $this->getLastmodStr();
        $r['createdstr'] = $this->getCreatedStr();
        $r['partnernev'] = $this->getPartnerNev();
        $r['helyszin'] = $this->getHelyszin();
        $r['mikor'] = $this->getMikor();
        $r['napnev'] = $this->getNapnev();
        $r['szintnev'] = $this->getSzintNev();
        return $r;
    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return \Entities\Partner
     */
    public function getPartner() {
        return $this->partner;
    }

    public function getPartnerId() {
        if ($this->getPartner()) {
            return $this->getPartner()->getId();
        }
        return 0;
    }

    public function getPartnerNev() {
        if ($this->getPartner()) {
            return $this->getPartner()->getNev();
        }
        return '';
    }

    /**
     * @param \Entities\Partner $partner
     */
    public function setPartner($partner) {
        $this->partner = $partner;
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

    /**
     * @return mixed
     */
    public function getHelyszin() {
        return $this->helyszin;
    }

    /**
     * @param mixed $helyszin
     */
    public function setHelyszin($helyszin) {
        $this->helyszin = $helyszin;
    }

    /**
     * @return mixed
     */
    public function getMikor() {
        return $this->mikor;
    }

    /**
     * @param mixed $mikor
     */
    public function setMikor($mikor) {
        $this->mikor = $mikor;
    }

    /**
     * @return mixed
     */
    public function getNap() {
        return $this->nap;
    }

    /**
     * @param mixed $nap
     */
    public function setNap($nap) {
        $this->nap = $nap;
    }

    public function getNapNev() {
        return \mkw\store::getDayname($this->nap);
    }

    public function getSzintNev() {
        if ($this->szint) {
            return $this->szint->getNev();
        }
        return '';
    }

    public function getSzintId() {
        if ($this->szint) {
            return $this->szint->getId();
        }
        return null;
    }

    /**
     * @return mixed
     */
    public function getSzint() {
        return $this->szint;
    }

    /**
     * @param mixed $szint
     */
    public function setSzint($szint) {
        $this->szint = $szint;
    }

    /**
     * @return mixed
     */
    public function getSzintegyeb() {
        return $this->szintegyeb;
    }

    /**
     * @param mixed $szintegyeb
     */
    public function setSzintegyeb($szintegyeb) {
        $this->szintegyeb = $szintegyeb;
    }

}