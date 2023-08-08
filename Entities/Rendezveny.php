<?php

namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use mkwhelpers\FilterDescriptor;

/**
 * @ORM\Entity(repositoryClass="Entities\RendezvenyRepository")
 * @ORM\Table(name="rendezveny",
 * options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"}
 * )
 */
class Rendezveny
{

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

    /**
     * @ORM\ManyToOne(targetEntity="Helyszin")
     * @ORM\JoinColumn(name="helyszin_id",referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $helyszin;

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

    /** @ORM\Column(type="boolean",nullable=false) */
    private $kellszamlazasiadat = true;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $orarendbenszerepel = true;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $varolistavan = true;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $url;

    /**
     * @ORM\Column(type="string",length=255,nullable=false)
     */
    private $onlineurl = '';

    /** @ORM\Column(type="integer",nullable=true) */
    private $maxferohely = 0;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $csomag = false;

    /** @ORM\Column(type="date",nullable=true) */
    private $earlybirdvege;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $earlybirdar;

    public function __construct()
    {
        $this->rendezvenydokok = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function calcSzabadhely()
    {
        $rjr = \mkw\store::getEm()->getRepository(RendezvenyJelentkezes::class);
        $filter = new FilterDescriptor();
        $filter->addFilter('rendezveny', '=', $this);
        $filter->addFilter('lemondva', '=', false);
        $filter->addFilter('varolistas', '=', false);
        return max($this->getMaxferohely() - $rjr->getCount($filter), 0);
    }

    public function generateUId()
    {
        $this->uid = uniqid('', true);
        return $this->uid;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUid()
    {
        return $this->uid;
    }

    /**
     * @return mixed
     */
    public function getNev()
    {
        return $this->nev;
    }

    public function getTeljesNev()
    {
        if ($this->csomag) {
            $r = $this->getNev();
        } else {
            $r = $this->getNev();
            if ($this->getKezdodatumStr()) {
                $r .= ' ' . $this->getKezdodatumStr();
            }
            if ($this->getKezdoido()) {
                $r .= ' ' . $this->getKezdoido();
            }
            if ($this->getTanarNev()) {
                $r .= ' (' . $this->getTanarNev() . ')';
            }
        }
        return $r;
    }

    /**
     * @param mixed $nev
     */
    public function setNev($nev)
    {
        $this->nev = $nev;
    }


    public function getKezdodatum()
    {
        return $this->kezdodatum;
    }

    public function getKezdodatumStr()
    {
        if ($this->getKezdodatum()) {
            return $this->getKezdodatum()->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    public function setKezdodatum($adat = '')
    {
        if ($adat != '') {
            $this->kezdodatum = new \DateTime(\mkw\store::convDate($adat));
        } else {
            $this->kezdodatum = null;
        }
    }

    public function getNap()
    {
        if ($this->kezdodatum) {
            return $this->kezdodatum->format('N');
        }
        return false;
    }

    public function getTanar()
    {
        return $this->tanar;
    }

    public function getTanarNev()
    {
        if ($this->tanar) {
            return $this->tanar->getNev();
        }
        return '';
    }

    public function getTanarId()
    {
        if ($this->tanar) {
            return $this->tanar->getId();
        }
        return '';
    }

    public function getTanarUrl()
    {
        if ($this->tanar) {
            return $this->tanar->getUrl();
        }
        return '';
    }

    public function setTanar($tanar)
    {
        $this->tanar = $tanar;
    }

    public function getTermek()
    {
        return $this->termek;
    }

    public function getTermekId()
    {
        if ($this->termek) {
            return $this->termek->getId();
        }
        return '';
    }


    public function getTermekNev()
    {
        if ($this->termek) {
            return $this->termek->getNev();
        }
        return '';
    }

    /**
     * @param \Entities\Termek $val
     */
    public function setTermek($val)
    {
        if ($this->termek !== $val) {
            if (!$val) {
                $this->removeTermek();
            } else {
                $this->termek = $val;
            }
        }
    }

    public function removeTermek()
    {
        if ($this->termek !== null) {
            $this->termek = null;
        }
    }

    /**
     * @return mixed
     */
    public function getCreatedby()
    {
        return $this->createdby;
    }

    public function getCreatedbyId()
    {
        if ($this->createdby) {
            return $this->createdby->getId();
        }
        return null;
    }

    public function getCreatedbyNev()
    {
        if ($this->createdby) {
            return $this->createdby->getNev();
        }
        return null;
    }

    /**
     * @return mixed
     */
    public function getUpdatedby()
    {
        return $this->updatedby;
    }

    public function getUpdatedbyId()
    {
        if ($this->updatedby) {
            return $this->updatedby->getId();
        }
        return null;
    }

    public function getUpdatedbyNev()
    {
        if ($this->updatedby) {
            return $this->updatedby->getNev();
        }
        return null;
    }

    public function getLastmod()
    {
        return $this->lastmod;
    }

    public function getLastmodStr()
    {
        if ($this->getLastmod()) {
            return $this->getLastmod()->format(\mkw\store::$DateTimeFormat);
        }
        return '';
    }

    public function clearLastmod()
    {
        $this->lastmod = null;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getCreatedStr()
    {
        if ($this->getCreated()) {
            return $this->getCreated()->format(\mkw\store::$DateTimeFormat);
        }
        return '';
    }

    public function clearCreated()
    {
        $this->created = null;
    }

    public function getRendezvenyallapot()
    {
        return $this->rendezvenyallapot;
    }

    public function getRendezvenyallapotNev()
    {
        if ($this->rendezvenyallapot) {
            return $this->rendezvenyallapot->getNev();
        }
        return '';
    }

    public function getRendezvenyallapotId()
    {
        if ($this->rendezvenyallapot) {
            return $this->rendezvenyallapot->getId();
        }
        return '';
    }

    public function setRendezvenyallapot($ra)
    {
        $this->rendezvenyallapot = $ra;
    }

    public function getJogaterem()
    {
        return $this->jogaterem;
    }

    public function getJogateremNev()
    {
        if ($this->jogaterem) {
            return $this->jogaterem->getNev();
        }
        return '';
    }

    public function getJogateremOrarendclass()
    {
        if ($this->jogaterem) {
            return $this->jogaterem->getOrarendclass();
        }
        return '';
    }

    public function getJogateremId()
    {
        if ($this->jogaterem) {
            return $this->jogaterem->getId();
        }
        return '';
    }

    public function setJogaterem($ra)
    {
        $this->jogaterem = $ra;
    }

    public function getHelyszin()
    {
        return $this->helyszin;
    }

    public function getHelyszinNev()
    {
        if ($this->helyszin) {
            return $this->helyszin->getNev();
        }
        return '';
    }

    public function getHelyszinId()
    {
        if ($this->helyszin) {
            return $this->helyszin->getId();
        }
        return '';
    }

    public function setHelyszin($ra)
    {
        $this->helyszin = $ra;
    }

    /**
     * @return mixed
     */
    public function getTodonaptar()
    {
        return $this->todonaptar;
    }

    /**
     * @param mixed $todonaptar
     */
    public function setTodonaptar($todonaptar)
    {
        $this->todonaptar = $todonaptar;
    }

    /**
     * @return mixed
     */
    public function getTodowebposzt()
    {
        return $this->todowebposzt;
    }

    /**
     * @param mixed $todowebposzt
     */
    public function setTodowebposzt($todowebposzt)
    {
        $this->todowebposzt = $todowebposzt;
    }

    /**
     * @return mixed
     */
    public function getTodourlap()
    {
        return $this->todourlap;
    }

    /**
     * @param mixed $todourlap
     */
    public function setTodourlap($todourlap)
    {
        $this->todourlap = $todourlap;
    }

    /**
     * @return mixed
     */
    public function getTodowebslider()
    {
        return $this->todowebslider;
    }

    /**
     * @param mixed $todowebslider
     */
    public function setTodowebslider($todowebslider)
    {
        $this->todowebslider = $todowebslider;
    }

    /**
     * @return mixed
     */
    public function getTodofbevent()
    {
        return $this->todofbevent;
    }

    /**
     * @param mixed $todofbevent
     */
    public function setTodofbevent($todofbevent)
    {
        $this->todofbevent = $todofbevent;
    }

    /**
     * @return mixed
     */
    public function getTodofbhirdetes()
    {
        return $this->todofbhirdetes;
    }

    /**
     * @param mixed $todofbhirdetes
     */
    public function setTodofbhirdetes($todofbhirdetes)
    {
        $this->todofbhirdetes = $todofbhirdetes;
    }

    /**
     * @return mixed
     */
    public function getTodoplakat()
    {
        return $this->todoplakat;
    }

    /**
     * @param mixed $todoplakat
     */
    public function setTodoplakat($todoplakat)
    {
        $this->todoplakat = $todoplakat;
    }

    /**
     * @return mixed
     */
    public function getTodofotobe()
    {
        return $this->todofotobe;
    }

    /**
     * @param mixed $todofotobe
     */
    public function setTodofotobe($todofotobe)
    {
        $this->todofotobe = $todofotobe;
    }

    /**
     * @return mixed
     */
    public function getTodoleirasbe()
    {
        return $this->todoleirasbe;
    }

    /**
     * @param mixed $todoleirasbe
     */
    public function setTodoleirasbe($todoleirasbe)
    {
        $this->todoleirasbe = $todoleirasbe;
    }

    public function getRendezvenyDokok()
    {
        return $this->rendezvenydokok;
    }

    public function addRendezvenyDok(RendezvenyDok $dok)
    {
        $this->rendezvenydokok->add($dok);
        $dok->setRendezveny($this);
    }

    public function removeRendezvenyDok(RendezvenyDok $dok)
    {
        if ($this->rendezvenydokok->removeElement($dok)) {
            $dok->removeRendezveny($this);
            return true;
        }
        return false;
    }

    /**
     * @return mixed
     */
    public function getKezdoido()
    {
        return $this->kezdoido;
    }

    /**
     * @param mixed $kezdoido
     */
    public function setKezdoido($kezdoido)
    {
        $this->kezdoido = $kezdoido;
    }

    /**
     * @return mixed
     */
    public function getAr()
    {
        return $this->ar;
    }

    /**
     * @param mixed $ar
     */
    public function setAr($ar)
    {
        $this->ar = $ar;
    }

    /**
     * @return mixed
     */
    public function getKellszamlazasiadat()
    {
        return $this->kellszamlazasiadat;
    }

    /**
     * @param mixed $kellszamlazasiadat
     */
    public function setKellszamlazasiadat($kellszamlazasiadat)
    {
        $this->kellszamlazasiadat = $kellszamlazasiadat;
    }

    /**
     * @return bool
     */
    public function getOrarendbenszerepel()
    {
        return $this->orarendbenszerepel;
    }

    /**
     * @param bool $orarendbenszerepel
     */
    public function setOrarendbenszerepel($orarendbenszerepel)
    {
        $this->orarendbenszerepel = $orarendbenszerepel;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getOnlineurl()
    {
        return $this->onlineurl;
    }

    /**
     * @param string $onlineurl
     */
    public function setOnlineurl($onlineurl)
    {
        $this->onlineurl = $onlineurl;
    }

    /**
     * @return int
     */
    public function getMaxferohely()
    {
        return $this->maxferohely;
    }

    /**
     * @param int $maxferohely
     */
    public function setMaxferohely($maxferohely): void
    {
        $this->maxferohely = $maxferohely;
    }

    /**
     * @return bool
     */
    public function isVarolistavan()
    {
        return $this->varolistavan;
    }

    /**
     * @param bool $varolistavan
     */
    public function setVarolistavan($varolistavan): void
    {
        $this->varolistavan = $varolistavan;
    }

    /**
     * @return bool
     */
    public function isCsomag()
    {
        return $this->csomag;
    }

    /**
     * @param bool $csomag
     */
    public function setCsomag($csomag): void
    {
        $this->csomag = $csomag;
    }

    public function getEarlybirdvege()
    {
        return $this->earlybirdvege;
    }

    public function getEarlybirdvegeStr()
    {
        if ($this->getEarlybirdvege()) {
            return $this->getEarlybirdvege()->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    public function setEarlybirdvege($adat = '')
    {
        if ($adat != '') {
            $this->earlybirdvege = new \DateTime(\mkw\store::convDate($adat));
        } else {
            $this->earlybirdvege = null;
        }
    }

    /**
     * @return mixed
     */
    public function getEarlybirdar()
    {
        return $this->earlybirdar;
    }

    /**
     * @param mixed $earlybirdar
     */
    public function setEarlybirdar($earlybirdar): void
    {
        $this->earlybirdar = $earlybirdar;
    }

}