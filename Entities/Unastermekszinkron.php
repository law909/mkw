<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * UNAS termékenként a legutóbb kiküldött készlet és ár – a `Services\UnasKeszletArService` ebből
 * dönti el, mit kell újra feltölteni. A kulcs az UNAS azonosító, nem a mi termékünk: a párosítás
 * változhat, az UNAS-beli állapot viszont az UNAS termékhez tartozik.
 * A sorokat a service nyers DBAL-lal írja, a több ezer soros menet ne a UnitOfWork-ön menjen.
 *
 * @ORM\Entity
 * @ORM\Table(name="unastermekszinkron",
 * options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"},
 * uniqueConstraints={
 *      @ORM\UniqueConstraint(name="unastermekszinkronunasid_idx",columns={"unasid"})
 * })
 */
class Unastermekszinkron
{

    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /** @ORM\Column(type="string",length=50,nullable=false) */
    private $unasid;

    /**
     * @ORM\ManyToOne(targetEntity="Termek")
     * @ORM\JoinColumn(name="termek_id", referencedColumnName="id",nullable=true,onDelete="set null")
     * @var \Entities\Termek
     */
    private $termek;

    /**
     * @ORM\ManyToOne(targetEntity="TermekValtozat")
     * @ORM\JoinColumn(name="termekvaltozat_id", referencedColumnName="id",nullable=true,onDelete="set null")
     * @var \Entities\TermekValtozat
     */
    private $termekvaltozat;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $keszlet;

    /** @ORM\Column(type="datetime",nullable=true) */
    private $keszletkuldve;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $netto;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $brutto;

    /** @ORM\Column(type="datetime",nullable=true) */
    private $arkuldve;

    /** a legutóbb kiküldött akciós ár; null, ha lejárttá tettük vagy nem volt
     * @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $akciosnetto;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $akciosbrutto;

    /** null: az akciós ár állapota még nem ment ki, tehát az UNAS-beli akcióról nem tudunk semmit
     * @ORM\Column(type="datetime",nullable=true) */
    private $akcioskuldve;

    /** egymás utáni sikertelen küldések száma; a MAXHIBA fölött a sor csak --teljes futással megy újra
     * @ORM\Column(type="integer",nullable=false) */
    private $hibadb = 0;

    /** @ORM\Column(type="text",nullable=true) */
    private $hiba;

    /** @ORM\Column(type="datetime",nullable=true) */
    private $hibaido;

    public function getId()
    {
        return $this->id;
    }

    public function getUnasid()
    {
        return $this->unasid;
    }

    public function getTermek()
    {
        return $this->termek;
    }

    public function getTermekvaltozat()
    {
        return $this->termekvaltozat;
    }

    public function getKeszlet()
    {
        return $this->keszlet;
    }

    public function getKeszletkuldve()
    {
        return $this->keszletkuldve;
    }

    public function getNetto()
    {
        return $this->netto;
    }

    public function getBrutto()
    {
        return $this->brutto;
    }

    public function getArkuldve()
    {
        return $this->arkuldve;
    }

    public function getAkciosnetto()
    {
        return $this->akciosnetto;
    }

    public function getAkciosbrutto()
    {
        return $this->akciosbrutto;
    }

    public function getAkcioskuldve()
    {
        return $this->akcioskuldve;
    }

    public function getHibadb()
    {
        return $this->hibadb;
    }

    public function getHiba()
    {
        return $this->hiba;
    }

    public function getHibaido()
    {
        return $this->hibaido;
    }
}
