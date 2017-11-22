<?php

namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Doctrine\ORM\Mapping\Entity(repositoryClass="PartnerMIJSZOralatogatasRepository")
 * @Doctrine\ORM\Mapping\Table(name="partnermijszoklevel",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class PartnerMIJSZOralatogatas {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Partner",inversedBy="mijszoklevelek")
     * @ORM\JoinColumn(name="partner_id",referencedColumnName="id",onDelete="cascade")
     */
    private $partner;

    /**
     * @ORM\ManyToOne(targetEntity="Partner",inversedBy="mijszoralatogatas")
     * @ORM\JoinColumn(name="partner_id",referencedColumnName="id",onDelete="cascade")
     */
    private $tanar;

    /** @ORM\Column(type="string",length="255",nullable=true) */
    private $helyszin;

    /** @ORM\Column(type="date",nullable=true) */
    private $datum;

    /** @ORM\Column(type="integer",nullable=true) */
    private $oraszam = 0;

    /** @ORM\Column(type="integer",nullable=false) */
    private $havigyakorisag;

    /** @ORM\Column(type="integer",nullable=false) */
    private $oratipus = 0; // gyakorló óra, workshop

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

    /** @ORM\Column(type="boolean",nullable=false) */
    private $igazolt = false;


    public function toLista() {
        $r = array();
        $r['id'] = $this->getId();
        $r['lastmodstr'] = $this->getLastmodStr();
        $r['createdstr'] = $this->getCreatedStr();
        $r['partnernev'] = $this->getPartnerNev();
        $r['datum'] = $this->getDatumStr();
        $r['datumstr'] = $this->getDatumStr();
        $r['igazolt'] = $this->getIgazolt();
        $r['helyszin'] = $this->getHelyszin();
        $r['oraszam'] = $this->getOraszam();
        $r['havigyakorisag'] = $this->getHavigyakorisag();
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

    public function getDatum() {
        return $this->datum;
    }

    public function getDatumStr() {
        if ($this->getDatum()) {
            return $this->getDatum()->format(\mkw\store::$DateFormat);
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
    public function getHavigyakorisag() {
        return $this->havigyakorisag;
    }

    /**
     * @param mixed $havigyakorisag
     */
    public function setHavigyakorisag($havigyakorisag) {
        $this->havigyakorisag = $havigyakorisag;
    }

    /**
     * @return mixed
     */
    public function getOratipus() {
        return $this->oratipus;
    }

    /**
     * @param mixed $oratipus
     */
    public function setOratipus($oratipus) {
        $this->oratipus = $oratipus;
    }

    /**
     * @return mixed
     */
    public function getIgazolt() {
        return $this->igazolt;
    }

    /**
     * @param mixed $igazolt
     */
    public function setIgazolt($igazolt) {
        $this->igazolt = $igazolt;
    }

}