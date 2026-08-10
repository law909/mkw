<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Egy cron feladat egy futása. A sorokat a `Services\CronService` írja, de nem az ORM-en
 * keresztül, hanem nyers DBAL-lal: ha a feladat Doctrine-hibával áll meg, az EntityManager
 * bezárul, és éppen a hibás futásról nem maradna napló. Az admin lista olvassa csak ORM-mel.
 *
 * @ORM\Entity(repositoryClass="Entities\CronlogRepository")
 * @ORM\Table(name="cronlog",
 * options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"},
 * indexes={
 *      @ORM\index(name="cronlogfeladat_idx",columns={"feladat","kezdet"}),
 *      @ORM\index(name="cronlogkezdet_idx",columns={"kezdet"})
 * })
 */
class Cronlog
{

    /** elindult, de még nem ért véget – ha így ragad, a folyamatot megölték */
    public const ALLAPOTFUT = 'fut';
    public const ALLAPOTOK = 'ok';
    /** lefutott, de van mit megnézni rajta (API hiba, fék, részleges eredmény) */
    public const ALLAPOTFIGYELEM = 'figyelem';
    public const ALLAPOTHIBA = 'hiba';
    /** az előző futás még dolgozott, ez a menet ki sem kezdett */
    public const ALLAPOTZAROLT = 'zarolt';

    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /** @ORM\Column(type="string",length=50,nullable=false) */
    private $feladat;

    /** @ORM\Column(type="string",length=20,nullable=false) */
    private $allapot = self::ALLAPOTFUT;

    /** @ORM\Column(type="datetime",nullable=false) */
    private $kezdet;

    /** @ORM\Column(type="datetime",nullable=true) */
    private $veg;

    /** másodpercben
     * @ORM\Column(type="decimal",precision=10,scale=2,nullable=true) */
    private $idotartam;

    /** @ORM\Column(type="text",nullable=true) */
    private $uzenet;

    /** több gépes telepítésnél melyik gép futtatta
     * @ORM\Column(type="string",length=100,nullable=true) */
    private $host;

    /** a beragadt futás így kereshető meg és lőhető ki a szerveren
     * @ORM\Column(type="integer",nullable=true) */
    private $pid;

    public function getId()
    {
        return $this->id;
    }

    public function getFeladat()
    {
        return $this->feladat;
    }

    public function setFeladat($feladat)
    {
        $this->feladat = $feladat;
    }

    public function getAllapot()
    {
        return $this->allapot;
    }

    public function setAllapot($allapot)
    {
        $this->allapot = $allapot;
    }

    public function getKezdet()
    {
        return $this->kezdet;
    }

    public function getKezdetStr()
    {
        return $this->kezdet ? $this->kezdet->format(\mkw\store::$DateTimeFormat) : '';
    }

    public function setKezdet($kezdet)
    {
        $this->kezdet = $kezdet;
    }

    public function getVeg()
    {
        return $this->veg;
    }

    public function getVegStr()
    {
        return $this->veg ? $this->veg->format(\mkw\store::$DateTimeFormat) : '';
    }

    public function setVeg($veg)
    {
        $this->veg = $veg;
    }

    public function getIdotartam()
    {
        return $this->idotartam;
    }

    public function getIdotartamStr()
    {
        return is_null($this->idotartam) ? '' : number_format((float)$this->idotartam, 2, ',', ' ') . ' s';
    }

    public function setIdotartam($idotartam)
    {
        $this->idotartam = $idotartam;
    }

    public function getUzenet()
    {
        return $this->uzenet;
    }

    public function setUzenet($uzenet)
    {
        $this->uzenet = $uzenet;
    }

    public function getHost()
    {
        return $this->host;
    }

    public function setHost($host)
    {
        $this->host = $host;
    }

    public function getPid()
    {
        return $this->pid;
    }

    public function setPid($pid)
    {
        $this->pid = $pid;
    }
}
