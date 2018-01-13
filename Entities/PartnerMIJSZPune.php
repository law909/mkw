<?php

namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Doctrine\ORM\Mapping\Entity(repositoryClass="PartnerMIJSZPuneRepository")
 * @Doctrine\ORM\Mapping\Table(name="partnermijszpune",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class PartnerMIJSZPune {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Partner",inversedBy="mijszpune")
     * @ORM\JoinColumn(name="partner_id",referencedColumnName="id",onDelete="cascade")
     */
    private $partner;

    /** @ORM\Column(type="integer",nullable=true) */
    private $ev = 0;

    /** @ORM\Column(type="integer",nullable=true) */
    private $honap = 0;

    /** @ORM\Column(type="date",nullable=true) */
    private $tol;

    /** @ORM\Column(type="date",nullable=true) */
    private $ig;

    /** @ORM\Column(type="integer",nullable=true) */
    private $napszam = 0;


    public function toLista() {
        $r = array();
        $r['id'] = $this->getId();
        $r['partnernev'] = $this->getPartnerNev();
        $r['ev'] = $this->getEv();
        $r['honap'] = $this->getHonap();
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
    public function getHonap() {
        return $this->honap;
    }

    /**
     * @param mixed $honap
     */
    public function setHonap($honap) {
        $this->honap = $honap;
    }

    /**
     * @return mixed
     */
    public function getNapszam() {
        return $this->napszam;
    }

    /**
     * @param mixed $napszam
     */
    public function setNapszam($napszam) {
        $this->napszam = $napszam;
    }

    public function getTol() {
        return $this->tol;
    }

    public function getTolStr() {
        if ($this->getTol()) {
            return $this->getTol()->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    public function getTolEngStr() {
        if ($this->getTol()) {
            return $this->getTol()->format(\mkw\store::$EngDateFormat);
        }
        return '';
    }

    public function setTol($adat = '') {
        if (is_a($adat, 'DateTime')) {
            $this->tol = $adat;
        }
        else {
            if ($adat == '') {
                $adat = date(\mkw\store::$DateFormat);
            }
            $this->tol = new \DateTime(\mkw\store::convDate($adat));
        }
    }

    public function getIg() {
        return $this->ig;
    }

    public function getIgStr() {
        if ($this->getIg()) {
            return $this->getIg()->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    public function getIgEngStr() {
        if ($this->getIg()) {
            return $this->getIg()->format(\mkw\store::$EngDateFormat);
        }
        return '';
    }

    public function setIg($adat = '') {
        if (is_a($adat, 'DateTime')) {
            $this->ig = $adat;
        }
        else {
            if ($adat == '') {
                $adat = date(\mkw\store::$DateFormat);
            }
            $this->ig = new \DateTime(\mkw\store::convDate($adat));
        }
    }
}