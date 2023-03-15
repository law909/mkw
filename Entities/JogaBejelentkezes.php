<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="Entities\JogaBejelentkezesRepository")
 * @ORM\Table(name="jogabejelentkezes",
 * options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class JogaBejelentkezes
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

    /** @ORM\Column(type="date",nullable=true) */
    private $datum;

    /** @ORM\Column(type="boolean") */
    private $megjelent = false;

    /** @ORM\Column(type="integer",nullable=true) */
    private $tipus;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $ar;

    /** @ORM\Column(type="integer",nullable=true) */
    private $jogareszvetelid;

    /** @ORM\Column(type="boolean") */
    private $kesobbfizet = false;

    /** @ORM\Column(type="text",nullable=true) */
    private $megjegyzes;

    /** @ORM\Column(type="integer",nullable=true) */
    private $online = 0;

    /** @ORM\Column(type="boolean") */
    private $lemondva = false;

    public function createJogaReszvetel()
    {
        $rvpartner = \mkw\store::getEm()->getRepository('Entities\Partner')->findOneBy(['email' => $this->getPartneremail()]);
        if (!$rvpartner) {
            $rvpartner = new Partner();
            $rvpartner->setEmail($this->getPartneremail());
            $rvpartner->setNev($this->getPartnernev());
            $rvpartner->setVezeteknev($this->getPartnerVezeteknev());
            $rvpartner->setKeresztnev($this->getPartnerKeresztnev());
            \mkw\store::getEm()->persist($rvpartner);
            \mkw\store::getEm()->flush();
        }
        $jr = new JogaReszvetel();
        $jr->setPartner($rvpartner);
        $jr->setOnline($this->getOnline());
        $jr->setDatum($this->getDatum());
        $jr->setJogaoratipus($this->getOrarend()->getJogaoratipus());
        $jr->setJogaterem($this->getOrarend()->getJogaterem());
        $hr = \mkw\store::getEm()->getRepository(Orarendhelyettesites::class);
        $helyettesito = $hr->getHelyettesito($this->getOrarend(), $this->getDatum());
        if ($helyettesito) {
            $jr->setTanar($helyettesito);
        } else {
            $jr->setTanar($this->getOrarend()->getDolgozo());
        }
        switch (true) {
            case $this->getTipus() == 1:
                /** @var \Entities\Termek $termek */
                $termek = \mkw\store::getEm()->getRepository('Entities\Termek')->find(\mkw\store::getParameter(\mkw\consts::JogaOrajegyTermek));
                if ($termek) {
                    $jr->setTermek($termek);
                }
                $jr->setBruttoegysar($this->getAr());
                break;
            default:
                $filter = new \mkwhelpers\FilterDescriptor();
                $filter->addFilter('partner', '=', $rvpartner);
                $filter->addFilter('lejart', '=', false);
                $berletek = \mkw\store::getEm()->getRepository('Entities\JogaBerlet')->getAll($filter, ['id' => 'ASC']);
                if (count($berletek)) {
                    /** @var \Entities\JogaBerlet $berlet */
                    $berlet = $berletek[0];
                    $jr->setJogaberlet($berlet);
                    $jr->setTermek($berlet->getTermek());
                    $jr->setBruttoegysar($berlet->getElszamoloAr());
                }
                break;
        }
        $jr->calcJutalek($this->getOrarend()->getJutalekszazalek());
        \mkw\store::getEm()->persist($jr);
        \mkw\store::getEm()->flush();
        $this->setJogareszvetelid($jr->getId());
        \mkw\store::getEm()->persist($this);
        \mkw\store::getEm()->flush();
    }

    public function delJogaReszvetel()
    {
        if ($this->getJogareszvetelid()) {
            $obj = \mkw\store::getEm()->getRepository('Entities\JogaReszvetel')->find($this->getJogareszvetelid());
            if ($obj) {
                \mkw\store::getEm()->remove($obj);
                \mkw\store::getEm()->flush();
                $this->setJogareszvetelid(null);
                \mkw\store::getEm()->persist($this);
                \mkw\store::getEm()->flush();
            }
        }
    }

    public function getId()
    {
        return $this->id;
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

    /**
     * @return \Entities\Orarend
     */
    public function getOrarend()
    {
        return $this->orarend;
    }

    public function getOrarendId()
    {
        if ($this->orarend) {
            return $this->orarend->getId();
        }
        return '';
    }

    /**
     * @param \Entities\Orarend $val
     */
    public function setOrarend($val)
    {
        if ($this->orarend !== $val) {
            $this->orarend = $val;
        }
    }

    public function getPartnerKeresztnev()
    {
        $x = explode(' ', $this->getPartnernev());
        return $x[1];
    }

    public function getPartnerVezeteknev()
    {
        $x = explode(' ', $this->getPartnernev());
        return $x[0];
    }

    public function getPartnernev()
    {
        return $this->partnernev;
    }

    public function setPartnernev($val)
    {
        $this->partnernev = $val;
    }

    /**
     * @return mixed
     */
    public function getPartneremail()
    {
        return $this->partneremail;
    }

    /**
     * @param mixed $partneremail
     */
    public function setPartneremail($partneremail)
    {
        $this->partneremail = $partneremail;
    }

    public function getDatum()
    {
        return $this->datum;
    }

    public function getDatumStr()
    {
        if ($this->getDatum()) {
            return $this->getDatum()->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    public function getDatumNapnev()
    {
        if ($this->getDatum()) {
            return \mkw\store::getDayname($this->getDatum()->format('N'));
        }
        return '';
    }

    public function setDatum($adat = '')
    {
        if (is_a($adat, 'DateTime')) {
            $this->datum = $adat;
        } else {
            if ($adat == '') {
                $adat = date(\mkw\store::$DateFormat);
            }
            $this->datum = new \DateTime(\mkw\store::convDate($adat));
        }
    }

    /**
     * @return bool
     */
    public function isMegjelent()
    {
        return $this->megjelent;
    }

    /**
     * @param bool $megjelent
     */
    public function setMegjelent($megjelent)
    {
        $this->megjelent = $megjelent;
    }

    /**
     * @return mixed
     */
    public function getTipus()
    {
        return $this->tipus;
    }

    /**
     * @param mixed $tipus
     */
    public function setTipus($tipus)
    {
        $this->tipus = $tipus;
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
    public function getJogareszvetelid()
    {
        return $this->jogareszvetelid;
    }

    /**
     * @param mixed $jogareszvetelid
     */
    public function setJogareszvetelid($jogareszvetelid)
    {
        $this->jogareszvetelid = $jogareszvetelid;
    }

    /**
     * @return bool
     */
    public function isKesobbfizet()
    {
        return $this->kesobbfizet;
    }

    /**
     * @param bool $kesobbfizet
     */
    public function setKesobbfizet($kesobbfizet)
    {
        $this->kesobbfizet = $kesobbfizet;
    }

    /**
     * @return mixed
     */
    public function getMegjegyzes()
    {
        return $this->megjegyzes;
    }

    /**
     * @param mixed $megjegyzes
     */
    public function setMegjegyzes($megjegyzes)
    {
        $this->megjegyzes = $megjegyzes;
    }

    /**
     * @return integer
     */
    public function getOnline()
    {
        return $this->online;
    }

    /**
     * @param integer $online
     */
    public function setOnline($online): void
    {
        $this->online = $online;
    }

    /**
     * @return bool
     */
    public function isLemondva()
    {
        return $this->lemondva;
    }

    /**
     * @param bool $lemondva
     */
    public function setLemondva($lemondva): void
    {
        $this->lemondva = $lemondva;
    }

}
