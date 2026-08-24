<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * A NAV bejövő számla import naplója: számlánként egy bejegyzés arról, mit kaptunk a NAV-tól,
 * és mi lett belőle.
 *
 * A `navadat` mindig kitöltött – ez a NAV-tól kapott nyers adat (a számlalista sora, illetve
 * a letöltött InvoiceData JSON). A `fejhiba` és a `tetelhiba` csak akkor, ha volt probléma;
 * hibátlan importnál üresen maradnak.
 *
 * @ORM\Entity(repositoryClass="Entities\KoltsegszamlaimportlogRepository")
 * @ORM\Table(name="koltsegszamlaimportlog",
 *  options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"},
 *  indexes={@ORM\index(name="koltsegszamlaimportlogszamlaszam_idx",columns={"szamlaszam"})})
 */
class Koltsegszamlaimportlog
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

    /** @ORM\Column(type="date",nullable=true) */
    private $idoszaktol;

    /** @ORM\Column(type="date",nullable=true) */
    private $idoszakig;

    /**
     * A szállító számlaszáma (a NAV invoiceNumber mezője).
     *
     * @ORM\Column(type="string",length=255)
     */
    private $szamlaszam = '';

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $szallito;

    /**
     * 'uj' | 'letezo' | 'hiba' – ugyanaz, mint az import eredménylistáján.
     *
     * @ORM\Column(type="string",length=20)
     */
    private $statusz = '';

    /**
     * A létrejött (vagy már meglévő) költségszámla bizonylatszáma.
     *
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    private $bizonylatszam;

    /** @ORM\Column(type="text",nullable=true) */
    private $navadat;

    /** @ORM\Column(type="text",nullable=true) */
    private $fejhiba;

    /** @ORM\Column(type="text",nullable=true) */
    private $tetelhiba;

    public function getId()
    {
        return $this->id;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getCreatedStr()
    {
        return $this->created ? $this->created->format(\mkw\store::$DateTimeFormat) : '';
    }

    public function getIdoszaktol()
    {
        return $this->idoszaktol;
    }

    public function getIdoszaktolStr()
    {
        return $this->idoszaktol ? $this->idoszaktol->format(\mkw\store::$DateFormat) : '';
    }

    public function setIdoszaktol($val)
    {
        $this->idoszaktol = $val;
    }

    public function getIdoszakig()
    {
        return $this->idoszakig;
    }

    public function getIdoszakigStr()
    {
        return $this->idoszakig ? $this->idoszakig->format(\mkw\store::$DateFormat) : '';
    }

    public function setIdoszakig($val)
    {
        $this->idoszakig = $val;
    }

    public function getSzamlaszam()
    {
        return $this->szamlaszam;
    }

    public function setSzamlaszam($val)
    {
        $this->szamlaszam = $val;
    }

    public function getSzallito()
    {
        return $this->szallito;
    }

    public function setSzallito($val)
    {
        $this->szallito = $val;
    }

    public function getStatusz()
    {
        return $this->statusz;
    }

    public function setStatusz($val)
    {
        $this->statusz = $val;
    }

    public function getBizonylatszam()
    {
        return $this->bizonylatszam;
    }

    public function setBizonylatszam($val)
    {
        $this->bizonylatszam = $val;
    }

    public function getNavadat()
    {
        return $this->navadat;
    }

    public function setNavadat($val)
    {
        $this->navadat = $val;
    }

    public function getFejhiba()
    {
        return $this->fejhiba;
    }

    public function setFejhiba($val)
    {
        $this->fejhiba = $val;
    }

    public function getTetelhiba()
    {
        return $this->tetelhiba;
    }

    public function setTetelhiba($val)
    {
        $this->tetelhiba = $val;
    }

    /** Volt-e bármilyen probléma – a listán ez alapján színezünk. */
    public function getHibas(): bool
    {
        return $this->statusz === 'hiba' || (string)$this->fejhiba !== '' || (string)$this->tetelhiba !== '';
    }

}
