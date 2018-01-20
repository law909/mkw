<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="Entities\ReszvetelRepository")
 * @ORM\Table(name="reszvetel",
 * options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class Reszvetel {

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
     * @ORM\ManyToOne(targetEntity="Partner",inversedBy="reszvetelek_partner")
     * @ORM\JoinColumn(name="partner_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Partner
     */
    private $partner;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $partnernev;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $partnervezeteknev = '';

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $partnerkeresztnev = '';

    /**
     * @ORM\ManyToOne(targetEntity="Partner",inversedBy="reszvetelek_tanar")
     * @ORM\JoinColumn(name="tanar_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Partner
     */
    private $tanar;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $tanarnev;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $tanarvezeteknev = '';

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $tanarkeresztnev = '';

    /**
     * @ORM\ManyToOne(targetEntity="Jogaterem")
     * @ORM\JoinColumn(name="jogaterem_id",referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $jogaterem;

    /**
     * @ORM\ManyToOne(targetEntity="Jogaoratipus")
     * @ORM\JoinColumn(name="jogaoratipus_id",referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $jogaoratipus;

    /**
     * @ORM\ManyToOne(targetEntity="Termek",inversedBy="reszvetelek")
     * @ORM\JoinColumn(name="termek_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Termek
     */
    private $termek;

    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="string",length=255,nullable=false)
     */
    private $termeknev;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $nettoegysar;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $bruttoegysar;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $jutalek;

    /** @ORM\Column(type="time",nullable=false) */
    private $datum;

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
     * @return \Entities\Partner
     */
    public function getPartner() {
        return $this->partner;
    }

    public function getPartnerId() {
        if ($this->partner) {
            return $this->partner->getId();
        }
        return '';
    }

    /**
     * @param \Entities\Partner $val
     */
    public function setPartner($val) {
        if ($this->partner !== $val) {
            if (!$val) {
                $this->removePartner();
            }
            else {
                $this->partner = $val;
                $this->setPartnernev($val->getNev());
                $this->setPartnervezeteknev($val->getVezeteknev());
                $this->setPartnerkeresztnev($val->getKeresztnev());
            }
        }
    }

    public function removePartner() {
        if ($this->partner !== null) {
            $this->partner = null;
            $this->partnernev = '';
            $this->partnervezeteknev = '';
            $this->partnerkeresztnev = '';
        }
    }

    public function getPartnernev() {
        return $this->partnernev;
    }

    public function setPartnernev($val) {
        $this->partnernev = $val;
    }

    public function getPartnervezeteknev() {
        return $this->partnervezeteknev;
    }

    public function setPartnervezeteknev($val) {
        $this->partnervezeteknev = $val;
    }

    public function getPartnerkeresztnev() {
        return $this->partnerkeresztnev;
    }

    public function setPartnerkeresztnev($val) {
        $this->partnerkeresztnev = $val;
    }

    /**
     * @return \Entities\Partner
     */
    public function getTanar() {
        return $this->tanar;
    }

    public function getTanarId() {
        if ($this->tanar) {
            return $this->tanar->getId();
        }
        return '';
    }

    /**
     * @param \Entities\Partner $val
     */
    public function setTanar($val) {
        if ($this->tanar !== $val) {
            if (!$val) {
                $this->removeTanar();
            }
            else {
                $this->tanar = $val;
                $this->setTanarnev($val->getNev());
                $this->setTanarvezeteknev($val->getVezeteknev());
                $this->setTanarkeresztnev($val->getKeresztnev());
            }
        }
    }

    public function removeTanar() {
        if ($this->tanar !== null) {
            $this->tanar = null;
            $this->tanarnev = '';
            $this->tanarvezeteknev = '';
            $this->tanarkeresztnev = '';
        }
    }

    public function getTanarnev() {
        return $this->tanarnev;
    }

    public function setTanarnev($val) {
        $this->tanarnev = $val;
    }

    public function getTanarvezeteknev() {
        return $this->tanarvezeteknev;
    }

    public function setTanarvezeteknev($val) {
        $this->tanarvezeteknev = $val;
    }

    public function getTanarkeresztnev() {
        return $this->tanarkeresztnev;
    }

    public function setTanarkeresztnev($val) {
        $this->tanarkeresztnev = $val;
    }

    public function getJogaterem() {
        return $this->jogaterem;
    }

    public function getJogateremNev() {
        if ($this->jogaterem) {
            return $this->jogaterem->getNev();
        }
        return '';
    }

    public function getJogateremOrarendclass() {
        if ($this->jogaterem) {
            return $this->jogaterem->getOrarendclass();
        }
        return '';
    }

    public function getJogateremId() {
        if ($this->jogaterem) {
            return $this->jogaterem->getId();
        }
        return '';
    }

    public function setJogaterem($jogaterem) {
        $this->jogaterem = $jogaterem;
    }

    public function getJogaoratipus() {
        return $this->jogaoratipus;
    }

    public function getJogaoratipusNev() {
        if ($this->jogaoratipus) {
            return $this->jogaoratipus->getNev();
        }
        return '';
    }

    public function getJogaoratipusId() {
        if ($this->jogaoratipus) {
            return $this->jogaoratipus->getId();
        }
        return '';
    }

    public function setJogaoratipus($jogaoratipus) {
        $this->jogaoratipus = $jogaoratipus;
        if (!$this->nev) {
            $this->setNev($this->getJogaoratipusNev());
        }
    }

    public function getJogaoratipusUrl() {
        if ($this->jogaoratipus) {
            return $this->jogaoratipus->getUrl();
        }
        return '';
    }

    public function getTermek() {
        return $this->termek;
    }

    public function getTermekId() {
        if ($this->termek) {
            return $this->termek->getId();
        }
        return '';
    }

    /**
     * @param \Entities\Termek $val
     */
    public function setTermek($val) {
        if ($this->termek !== $val) {
            if (!$val) {
                $this->removeTermek();
            }
            else {
                $this->termek = $val;
                $this->setTermeknev($val->getNev());
                $this->setNettoegysar($val->getNettoAr());
                $this->setBruttoegysar($val->getBruttoAr());
            }
        }
    }

    public function removeTermek() {
        if ($this->termek !== null) {
            $this->termek = null;
            $this->termeknev = '';
            $this->setNettoegysar(0);
            $this->setBruttoegysar(0);
        }
    }

    public function getTermeknev() {
        return $this->termeknev;
    }

    public function setTermeknev($val) {
        $this->termeknev = $val;
    }

    public function getNettoegysar() {
        return $this->nettoegysar;
    }

    public function setNettoegysar($val) {
        $this->nettoegysar = $val;
    }

    public function getBruttoegysar() {
        return $this->bruttoegysar;
    }

    public function setBruttoegysar($val) {
        $this->bruttoegysar = $val;
    }

    /**
     * @return mixed
     */
    public function getJutalek() {
        return $this->jutalek;
    }

    /**
     * @param mixed $jutalek
     */
    public function setJutalek($jutalek) {
        $this->jutalek = $jutalek;
    }

}