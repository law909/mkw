<?php

namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Doctrine\ORM\Mapping\Entity(repositoryClass="PartnerMIJSZOralatogatasRepository")
 * @Doctrine\ORM\Mapping\Table(name="partnermijszoralatogatas",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class PartnerMIJSZOralatogatas {

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

    /**
     * @ORM\ManyToOne(targetEntity="Partner",inversedBy="mijszoralatogatastanar")
     * @ORM\JoinColumn(name="tanar_id",referencedColumnName="id",onDelete="cascade")
     */
    private $tanar;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $tanaregyeb;

    /** @ORM\Column(type="integer",nullable=true) */
    private $ev;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $helyszin;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $mikor;

    /** @ORM\Column(type="decimal",precision=14,scale=1,nullable=true) */
    private $oraszam = 0;

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
        $r['tanarnev'] = $this->getTanarNev();
        $r['tanaregyeb'] = $this->getTanaregyeb();
        $r['mikor'] = $this->getMikor();
        $r['oraszam'] = $this->getOraszam();
        $r['ev'] = $this->getEv();
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
     * @return \Entities\Partner
     */
    public function getTanar() {
        return $this->tanar;
    }

    public function getTanarId() {
        if ($this->getTanar()) {
            return $this->getTanar()->getId();
        }
        return 0;
    }

    public function getTanarNev() {
        if ($this->getTanar()) {
            return $this->getTanar()->getNev();
        }
        return '';
    }
    /**
     * @param \Entities\Partner $partner
     */
    public function setTanar($partner) {
        $this->tanar = $partner;
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
    public function getOraszam() {
        return $this->oraszam;
    }

    /**
     * @param mixed $oraszam
     */
    public function setOraszam($oraszam) {
        $this->oraszam = $oraszam;
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
    public function getEv() {
        return $this->ev;
    }

    /**
     * @param mixed $ev
     */
    public function setEv($ev) {
        $this->ev = $ev;
    }

    /**
     * @return mixed
     */
    public function getTanaregyeb() {
        return $this->tanaregyeb;
    }

    /**
     * @param mixed $tanaregyeb
     */
    public function setTanaregyeb($tanaregyeb) {
        $this->tanaregyeb = $tanaregyeb;
    }

}