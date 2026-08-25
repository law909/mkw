<?php

namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\TermekArRepository")
 * @ORM\Table(name="termekar",
 * options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"},
 * indexes={
 *	@ORM\index(name="termekarazonosito_idx",columns={"termek_id","valutanem_id","arsav_id"})
 * })
 */
class TermekAr
{
    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

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
     * @ORM\ManyToOne(targetEntity="Termek",inversedBy="termekarak")
     * @ORM\JoinColumn(name="termek_id",referencedColumnName="id",onDelete="cascade")
     */
    private $termek;

    /** @ORM\Column(type="decimal",precision=14,scale=2,nullable=true) */
    private $netto;

    /** @ORM\Column(type="decimal",precision=14,scale=2,nullable=true) */
    private $brutto;

    /**
     * @ORM\ManyToOne(targetEntity="Valutanem",inversedBy="termekarak")
     * @ORM\JoinColumn(name="valutanem_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $valutanem;

    /**
     * @ORM\ManyToOne(targetEntity="Arsav")
     * @ORM\JoinColumn(name="arsav_id", referencedColumnName="id",nullable=true,onDelete="cascade")
     */
    private $arsav;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $azonosito;

    /**
     * Képlettel számolt sor-e. Ha nem, a netto/brutto kézzel megadott fix összeg – ez marad az
     * alapértelmezés.
     *
     * @ORM\Column(type="boolean",nullable=false)
     */
    private $kepletes = false;

    /**
     * A képlet forrás ársávja: ugyanennek a terméknek egy másik, AZONOS VALUTANEMŰ ára.
     *
     * @ORM\ManyToOne(targetEntity="Arsav")
     * @ORM\JoinColumn(name="forrasarsav_id", referencedColumnName="id",nullable=true,onDelete="set null")
     */
    private $forrasarsav;

    /** @ORM\Column(type="decimal",precision=9,scale=4,nullable=true) */
    private $szazalek = 100;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $hozzaad = 0;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $kivon = 0;

    /**
     * A képlethez hozzáadandó kapcsolódó költségek. A választék a termékhez rendelt költségekből
     * jön, az érték a költség egy darab termékre eső része.
     *
     * @ORM\ManyToMany(targetEntity="Kapcsolodokoltseg")
     * @ORM\JoinTable(name="termekar_kapcsolodokoltsegek",
     *  options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"},
     *  joinColumns={@ORM\JoinColumn(name="termekar_id",referencedColumnName="id",onDelete="cascade")},
     *  inverseJoinColumns={@ORM\JoinColumn(name="kapcsolodokoltseg_id",referencedColumnName="id",onDelete="cascade")}
     *  )
     */
    private $kepletkoltsegek;

    public function __construct()
    {
        $this->kepletkoltsegek = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTermek()
    {
        return $this->termek;
    }

    public function getTermekNev()
    {
        if ($this->termek) {
            return $this->termek->getNev();
        }
        return '';
    }

    public function setTermek(Termek $termek)
    {
        $this->termek = $termek;
//		$termek->addTermekKep($this);
    }

    public function removeTermek()
    {
        if ($this->termek !== null) {
            $termek = $this->termek;
            $this->termek = null;
        }
    }

    public function getLastmod()
    {
        return $this->lastmod;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getValutanem()
    {
        return $this->valutanem;
    }

    public function setValutanem($val)
    {
        if (!($val instanceof \Entities\Valutanem)) {
            $val = \mkw\store::getEm()->getRepository(Valutanem::class)->find($val);
        }
        if ($this->valutanem !== $val) {
            $this->valutanem = $val;
        }
    }

    public function removeValutanem()
    {
        if ($this->valutanem !== null) {
            $this->valutanem = null;
        }
    }

    public function getNetto()
    {
        return $this->netto;
    }

    public function setNetto($val)
    {
        $this->netto = $val;
        $this->brutto = $this->getTermek()->getAfa()->calcBrutto($val);
    }

    public function getBrutto()
    {
        return $this->brutto;
    }

    public function setBrutto($val)
    {
        $this->brutto = $val;
        $this->netto = $this->getTermek()->getAfa()->calcNetto($val);
    }

    public function getArsav()
    {
        return $this->arsav;
    }

    public function setArsav($val)
    {
        if (!($val instanceof \Entities\Arsav)) {
            $val = \mkw\store::getEm()->getRepository(Arsav::class)->find($val);
        }
        if ($this->arsav !== $val) {
            $this->arsav = $val;
        }
    }

    public function removeArsav()
    {
        $this->arsav = null;
    }

    public function getAzonosito()
    {
        return $this->azonosito;
    }

    public function setAzonosito($val)
    {
        $this->azonosito = $val;
    }

    public function getKepletes()
    {
        return $this->kepletes;
    }

    public function setKepletes($val)
    {
        $this->kepletes = $val;
    }

    public function getForrasarsav()
    {
        return $this->forrasarsav;
    }

    public function getForrasarsavId()
    {
        return $this->forrasarsav?->getId();
    }

    public function setForrasarsav($val)
    {
        if ($val && !($val instanceof Arsav)) {
            $val = \mkw\store::getEm()->getRepository(Arsav::class)->find($val);
        }
        $this->forrasarsav = $val ?: null;
    }

    public function getSzazalek()
    {
        return $this->szazalek;
    }

    public function setSzazalek($val)
    {
        $this->szazalek = $val;
    }

    public function getHozzaad()
    {
        return $this->hozzaad;
    }

    public function setHozzaad($val)
    {
        $this->hozzaad = $val;
    }

    public function getKivon()
    {
        return $this->kivon;
    }

    public function setKivon($val)
    {
        $this->kivon = $val;
    }

    public function getKepletkoltsegek()
    {
        return $this->kepletkoltsegek;
    }

    public function getAllKepletkoltsegId()
    {
        $res = [];
        foreach ($this->kepletkoltsegek as $koltseg) {
            $res[] = $koltseg->getId();
        }
        return $res;
    }

    public function addKepletkoltseg(Kapcsolodokoltseg $koltseg)
    {
        if (!$this->kepletkoltsegek->contains($koltseg)) {
            $this->kepletkoltsegek->add($koltseg);
        }
    }

    public function removeAllKepletkoltseg()
    {
        $this->kepletkoltsegek->clear();
    }

}