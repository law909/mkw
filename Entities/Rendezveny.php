<?php

namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\RendezvenyRepository")
 * @ORM\Table(name="rendezveny",
 * options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"}
 * )
 */
class Rendezveny {

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

    /** @ORM\Column(type="date",nullable=true) */
    private $kezdodatum;

    /** @ORM\Column(type="string",length=50,nullable=true) */
    private $kezdoido;

    /**
     * @ORM\Column(type="string",length=255,nullable=false)
     */
    private $nev = '';

    /**
     * @ORM\ManyToOne(targetEntity="Dolgozo")
     * @ORM\JoinColumn(name="tanar_id",referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $tanar;

    /**
     * @ORM\ManyToOne(targetEntity="Termek",inversedBy="bizonylattetelek")
     * @ORM\JoinColumn(name="termek_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Termek
     */
    private $termek;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $ar;

    /**
     * @ORM\ManyToOne(targetEntity="Rendezvenyallapot")
     * @ORM\JoinColumn(name="rendezvenyallapot_id",referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $rendezvenyallapot;

    /**
     * @ORM\ManyToOne(targetEntity="Jogaterem")
     * @ORM\JoinColumn(name="jogaterem_id",referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $jogaterem;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $todonaptar = false;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $todowebposzt = false;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $todourlap = false;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $todowebslider = false;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $todofbevent = false;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $todofbhirdetes = false;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $todoplakat = false;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $todofotobe = false;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $todoleirasbe = false;

    /** @ORM\OneToMany(targetEntity="RendezvenyDok", mappedBy="rendezveny", cascade={"persist", "remove"}) */
    private $rendezvenydokok;

    /** @ORM\Column(type="string", length=23, nullable=false) */
    private $uid;

    public function __construct() {
        $this->rendezvenydokok = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function generateUId() {
        $this->uid = uniqid('', true);
        return $this->uid;
    }

    public function getId() {
        return $this->id;
    }

    public function getUid() {
        return $this->uid;
    }

    /**
     * @return mixed
     */
    public function getNev() {
        return $this->nev;
    }

    public function getTeljesNev() {
        $r = $this->getNev();
        if ($this->getKezdodatumStr()) {
            $r .= ' ' . $this->getKezdodatumStr();
        }
        return $r;
    }

    /**
     * @param mixed $nev
     */
    public function setNev($nev) {
        $this->nev = $nev;
    }


    public function getKezdodatum() {
        return $this->kezdodatum;
    }

    public function getKezdodatumStr() {
        if ($this->getKezdodatum()) {
            return $this->getKezdodatum()->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    public function setKezdodatum($adat = '') {
        if ($adat != '') {
            $this->kezdodatum = new \DateTime(\mkw\store::convDate($adat));
        }
        else {
            $this->kezdodatum = null;
        }
    }

    public function getTanar() {
        return $this->tanar;
    }

    public function getTanarNev() {
        if ($this->tanar) {
            return $this->tanar->getNev();
        }
        return '';
    }

    public function getTanarId() {
        if ($this->tanar) {
            return $this->tanar->getId();
        }
        return '';
    }

    public function setTanar($tanar) {
        $this->tanar = $tanar;
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


    public function getTermekNev() {
        if ($this->termek) {
            return $this->termek->getNev();
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
        }
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

    public function getRendezvenyallapot() {
        return $this->rendezvenyallapot;
    }

    public function getRendezvenyallapotNev() {
        if ($this->rendezvenyallapot) {
            return $this->rendezvenyallapot->getNev();
        }
        return '';
    }

    public function getRendezvenyallapotId() {
        if ($this->rendezvenyallapot) {
            return $this->rendezvenyallapot->getId();
        }
        return '';
    }

    public function setRendezvenyallapot($ra) {
        $this->rendezvenyallapot = $ra;
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

    public function getJogateremId() {
        if ($this->jogaterem) {
            return $this->jogaterem->getId();
        }
        return '';
    }

    public function setJogaterem($ra) {
        $this->jogaterem = $ra;
    }

    /**
     * @return mixed
     */
    public function getTodonaptar() {
        return $this->todonaptar;
    }

    /**
     * @param mixed $todonaptar
     */
    public function setTodonaptar($todonaptar) {
        $this->todonaptar = $todonaptar;
    }

    /**
     * @return mixed
     */
    public function getTodowebposzt() {
        return $this->todowebposzt;
    }

    /**
     * @param mixed $todowebposzt
     */
    public function setTodowebposzt($todowebposzt) {
        $this->todowebposzt = $todowebposzt;
    }

    /**
     * @return mixed
     */
    public function getTodourlap() {
        return $this->todourlap;
    }

    /**
     * @param mixed $todourlap
     */
    public function setTodourlap($todourlap) {
        $this->todourlap = $todourlap;
    }

    /**
     * @return mixed
     */
    public function getTodowebslider() {
        return $this->todowebslider;
    }

    /**
     * @param mixed $todowebslider
     */
    public function setTodowebslider($todowebslider) {
        $this->todowebslider = $todowebslider;
    }

    /**
     * @return mixed
     */
    public function getTodofbevent() {
        return $this->todofbevent;
    }

    /**
     * @param mixed $todofbevent
     */
    public function setTodofbevent($todofbevent) {
        $this->todofbevent = $todofbevent;
    }

    /**
     * @return mixed
     */
    public function getTodofbhirdetes() {
        return $this->todofbhirdetes;
    }

    /**
     * @param mixed $todofbhirdetes
     */
    public function setTodofbhirdetes($todofbhirdetes) {
        $this->todofbhirdetes = $todofbhirdetes;
    }

    /**
     * @return mixed
     */
    public function getTodoplakat() {
        return $this->todoplakat;
    }

    /**
     * @param mixed $todoplakat
     */
    public function setTodoplakat($todoplakat) {
        $this->todoplakat = $todoplakat;
    }

    /**
     * @return mixed
     */
    public function getTodofotobe() {
        return $this->todofotobe;
    }

    /**
     * @param mixed $todofotobe
     */
    public function setTodofotobe($todofotobe) {
        $this->todofotobe = $todofotobe;
    }

    /**
     * @return mixed
     */
    public function getTodoleirasbe() {
        return $this->todoleirasbe;
    }

    /**
     * @param mixed $todoleirasbe
     */
    public function setTodoleirasbe($todoleirasbe) {
        $this->todoleirasbe = $todoleirasbe;
    }

    public function getRendezvenyDokok() {
        return $this->rendezvenydokok;
    }

    public function addRendezvenyDok(RendezvenyDok $dok) {
        $this->rendezvenydokok->add($dok);
        $dok->setRendezveny($this);
    }

    public function removeRendezvenyDok(RendezvenyDok $dok) {
        if ($this->rendezvenydokok->removeElement($dok)) {
            $dok->removeRendezveny($this);
            return true;
        }
        return false;
    }

    /**
     * @return mixed
     */
    public function getKezdoido() {
        return $this->kezdoido;
    }

    /**
     * @param mixed $kezdoido
     */
    public function setKezdoido($kezdoido) {
        $this->kezdoido = $kezdoido;
    }

    /**
     * @return mixed
     */
    public function getAr() {
        return $this->ar;
    }

    /**
     * @param mixed $ar
     */
    public function setAr($ar) {
        $this->ar = $ar;
    }

}