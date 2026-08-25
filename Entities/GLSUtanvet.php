<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * A GLS utánvét-kimutatásból importált beszedett utánvétek átmeneti táblája – a
 * `banktranzakcio` mintájára. Csak azok a sorok kerülnek be, amelyeken van beszedett
 * összeg; a párosítás eredménye a `bizonylatszamok`, ami a karbantartón kézzel is
 * javítható.
 *
 * @ORM\Entity(repositoryClass="Entities\GLSUtanvetRepository")
 * @ORM\Table(name="glsutanvet",
 *  options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"},
 *  indexes={@ORM\index(name="glsutanvetcsomagszam_idx",columns={"csomagszam"})})
 */
class GLSUtanvet
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
     * A csomagszám egyben a fuvarlevélszám, és ez az újraimportálás duplikátumszűrője is.
     *
     * @ORM\Column(type="string",length=50)
     */
    private $csomagszam = '';

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $ugyfelhivatkozas;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $utanvethivatkozas;

    /** @ORM\Column(type="string",length=100,nullable=true) */
    private $statusz;

    /** @ORM\Column(type="date",nullable=true) */
    private $felvetel;

    /** @ORM\Column(type="date",nullable=true) */
    private $statuszdatum;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $nev;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $atvevo;

    /** @ORM\Column(type="string",length=20,nullable=true) */
    private $irszam;

    /** @ORM\Column(type="string",length=100,nullable=true) */
    private $varos;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $utca;

    /** @ORM\Column(type="string",length=10,nullable=true) */
    private $orszag;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $regisztraltosszeg;

    /**
     * A beszedett utánvét összege – csak a nem nulla sorok kerülnek be.
     *
     * @ORM\Column(type="decimal",precision=14,scale=4,nullable=true)
     */
    private $osszeg;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $bizonylatszamok;

    /** @ORM\Column(type="boolean") */
    private $bankbizonylatkesz = false;

    /**
     * A tételből képzett bankbizonylat. A bankbizonylatkesz jelzőt nem váltja ki: a régi,
     * a mező bevezetése előtt képzett sorokon üresen marad.
     *
     * @ORM\ManyToOne(targetEntity="Bankbizonylatfej")
     * @ORM\JoinColumn(name="bankbizonylatfej_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     * @var \Entities\Bankbizonylatfej
     */
    private $bankbizonylatfej;

    /** @ORM\Column(type="boolean") */
    private $inaktiv = false;

    public function getId()
    {
        return $this->id;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getLastmod()
    {
        return $this->lastmod;
    }

    public function getCsomagszam()
    {
        return $this->csomagszam;
    }

    public function setCsomagszam($val)
    {
        $this->csomagszam = $val;
    }

    public function getUgyfelhivatkozas()
    {
        return $this->ugyfelhivatkozas;
    }

    public function setUgyfelhivatkozas($val)
    {
        $this->ugyfelhivatkozas = $val;
    }

    public function getUtanvethivatkozas()
    {
        return $this->utanvethivatkozas;
    }

    public function setUtanvethivatkozas($val)
    {
        $this->utanvethivatkozas = $val;
    }

    public function getStatusz()
    {
        return $this->statusz;
    }

    public function setStatusz($val)
    {
        $this->statusz = $val;
    }

    public function getFelvetel()
    {
        return $this->felvetel;
    }

    public function getFelvetelStr()
    {
        return $this->felvetel ? $this->felvetel->format(\mkw\store::$DateFormat) : '';
    }

    public function setFelvetel($val)
    {
        $this->felvetel = $val;
    }

    public function getStatuszdatum()
    {
        return $this->statuszdatum;
    }

    public function getStatuszdatumStr()
    {
        return $this->statuszdatum ? $this->statuszdatum->format(\mkw\store::$DateFormat) : '';
    }

    public function setStatuszdatum($val)
    {
        $this->statuszdatum = $val;
    }

    public function getNev()
    {
        return $this->nev;
    }

    public function setNev($val)
    {
        $this->nev = $val;
    }

    public function getAtvevo()
    {
        return $this->atvevo;
    }

    public function setAtvevo($val)
    {
        $this->atvevo = $val;
    }

    public function getIrszam()
    {
        return $this->irszam;
    }

    public function setIrszam($val)
    {
        $this->irszam = $val;
    }

    public function getVaros()
    {
        return $this->varos;
    }

    public function setVaros($val)
    {
        $this->varos = $val;
    }

    public function getUtca()
    {
        return $this->utca;
    }

    public function setUtca($val)
    {
        $this->utca = $val;
    }

    public function getOrszag()
    {
        return $this->orszag;
    }

    public function setOrszag($val)
    {
        $this->orszag = $val;
    }

    public function getRegisztraltosszeg()
    {
        return $this->regisztraltosszeg;
    }

    public function setRegisztraltosszeg($val)
    {
        $this->regisztraltosszeg = $val;
    }

    public function getOsszeg()
    {
        return $this->osszeg;
    }

    public function setOsszeg($val)
    {
        $this->osszeg = $val;
    }

    public function getBizonylatszamok()
    {
        return $this->bizonylatszamok;
    }

    public function setBizonylatszamok($val)
    {
        $this->bizonylatszamok = $val;
    }

    public function getBankbizonylatkesz()
    {
        return $this->bankbizonylatkesz;
    }

    public function setBankbizonylatkesz($val)
    {
        $this->bankbizonylatkesz = $val;
    }

    public function getBankbizonylatfej()
    {
        return $this->bankbizonylatfej;
    }

    public function setBankbizonylatfej($val)
    {
        $this->bankbizonylatfej = $val;
    }

    public function getBankbizonylatszam()
    {
        return $this->bankbizonylatfej ? $this->bankbizonylatfej->getId() : '';
    }

    /** a bankbizonylat tételeire szűrt tétellista URL-je, vagy null */
    public function getBankbizonylatTetelListaUrl()
    {
        return $this->bankbizonylatfej ? $this->bankbizonylatfej->getTetelListaUrl() : null;
    }

    public function getInaktiv()
    {
        return $this->inaktiv;
    }

    public function setInaktiv($val)
    {
        $this->inaktiv = $val;
    }

    /** a teljes cím egy sorban – a lista és a párosítás magyarázata is ezt mutatja */
    public function getCim()
    {
        return trim($this->irszam . ' ' . $this->varos . ' ' . $this->utca);
    }

}
