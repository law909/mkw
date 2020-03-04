<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="Entities\JogaReszvetelRepository")
 * @ORM\Table(name="jogareszvetel",
 * options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class JogaReszvetel {

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

    /** @ORM\Column(type="string",length=100,nullable=true) */
    private $partneremail = '';

    /**
     * @ORM\ManyToOne(targetEntity="Dolgozo")
     * @ORM\JoinColumn(name="tanar_id",referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $tanar;

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
     * @ORM\ManyToOne(targetEntity="Fizmod")
     * @ORM\JoinColumn(name="fizmod_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Fizmod
     */
    private $fizmod;

    /**
     * @ORM\ManyToOne(targetEntity="Penztar")
     * @ORM\JoinColumn(name="penztar_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Penztar
     */
    private $penztar;

    /**
     * @ORM\ManyToOne(targetEntity="Termek",inversedBy="reszvetelek")
     * @ORM\JoinColumn(name="termek_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Termek
     */
    private $termek;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $nettoegysar;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $bruttoegysar;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $jutalek;

    /** @ORM\Column(type="date",nullable=false) */
    private $datum;

    /** @ORM\Column(type="boolean",nullable=true) */
    private $uresterem = false;

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
                $this->setPartneremail($val->getEmail());
            }
        }
    }

    public function removePartner() {
        if ($this->partner !== null) {
            $this->partner = null;
            $this->partnernev = '';
            $this->partnervezeteknev = '';
            $this->partnerkeresztnev = '';
            $this->partneremail = '';
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
     * @return \Entities\Dolgozo
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
     * @param \Entities\Dolgozo $val
     */
    public function setTanar($val) {
        if ($this->tanar !== $val) {
            if (!$val) {
                $this->removeTanar();
            }
            else {
                $this->tanar = $val;
            }
        }
    }

    public function removeTanar() {
        if ($this->tanar !== null) {
            $this->tanar = null;
        }
    }

    public function getTanarnev() {
        if ($this->tanar) {
            return $this->tanar->getNev();
        }
        return '';
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
            }
        }
    }

    public function removeTermek() {
        if ($this->termek !== null) {
            $this->termek = null;
            $this->setNettoegysar(0);
            $this->setBruttoegysar(0);
        }
    }

    public function getTermeknev() {
        if ($this->termek) {
            return $this->termek->getNev();
        }
        return '';
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

    /**
     * @return \Entities\Fizmod
     */
    public function getFizmod() {
        return $this->fizmod;
    }

    public function getFizmodnev() {
        $fm = $this->getFizmod();
        if ($fm) {
            return $fm->getNev();
        }
        return '';
    }

    public function getFizmodId() {
        $fm = $this->getFizmod();
        if ($fm) {
            return $fm->getId();
        }
        return '';
    }

    /**
     * @param \Entities\Fizmod $val
     */
    public function setFizmod($val) {
        if (!($val instanceof \Entities\Fizmod)) {
            $val = \mkw\store::getEm()->getRepository('Entities\Fizmod')->find($val);
        }
        if ($this->fizmod !== $val) {
            if (!$val) {
                $this->removeFizmod();
            }
            else {
                $this->fizmod = $val;
            }
        }
    }

    public function removeFizmod() {
        if ($this->fizmod !== null) {
            $this->fizmod = null;
        }
    }

    /**
     * @return \Entities\Penztar
     */
    public function getPenztar() {
        return $this->penztar;
    }

    public function getPenztarId() {
        if ($this->penztar) {
            return $this->penztar->getId();
        }
        return '';
    }

    /**
     * @param \Entities\Penztar|null $val
     */
    public function setPenztar($val = null) {
        if ($this->penztar !== $val) {
            if (!$val) {
                $this->removePenztar();
            }
            else {
                $this->penztar = $val;
            }
        }
    }

    public function removePenztar() {
        if ($this->penztar !== null) {
            $this->penztar = null;
        }
    }

    public function getPenztarnev() {
        if ($this->penztar) {
            return $this->penztar->getNev();
        }
        return '';
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
            return \mkw\store::getDayname($this->getDatum()->format('D'));
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
    public function getUresterem() {
        return $this->uresterem;
    }

    /**
     * @param mixed $uresterem
     */
    public function setUresterem($uresterem) {
        $this->uresterem = $uresterem;
    }

    public function calcJutalek() {
        if ($this->getUresterem()) {
            $this->setJutalek(\mkw\store::getParameter(\mkw\consts::JogaUresTeremJutalek, 3000));
        }
        else {
            if (\mkw\store::isAYCMFizmod($this->getFizmodId())) {
                $this->setJutalek(\mkw\store::getParameter(\mkw\consts::JogaAYCMJutalek, 500));
            }
            else {
                $jutalekszaz = \mkw\store::getParameter(\mkw\consts::JogaJutalek, 47);
                $this->setJutalek($this->getBruttoegysar() * $jutalekszaz / 100);
            }
        }
    }
}