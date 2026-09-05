<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Egy készletcsoport (raktár, termék, változat) FIFO összesítője – ezt joinolják a listák.
 * A sorokat kizárólag a `Services\FifoService` írja.
 *
 * **Minden** mozgással rendelkező csoport kap sort, a nulla és a negatív készletű is: így a
 * "nincs sor" egyértelműen azt jelenti, hogy a csoport soha nem mozgott. Negatív készletnél
 * (nincs elég bevét a kiadásokhoz) a `mennyiseg` az előjeles hiány, az `ertek` 0 – kitalált
 * áron nem értékelünk.
 *
 * A hármason nincs UNIQUE index: a `termekvaltozat_id` nullable, a MySQL pedig a NULL-okat
 * különbözőnek veszi, tehát nem is védene. Az egyediséget az tartja, hogy a service mindig
 * törli a csoport sorát, mielőtt újat ír.
 *
 * @ORM\Entity(repositoryClass="Entities\FifoertekRepository")
 * @ORM\Table(name="fifoertek",
 * options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"},
 * indexes={
 *      @ORM\index(name="fifoertekcsoport_idx",columns={"raktar_id","termek_id","termekvaltozat_id"}),
 *      @ORM\index(name="fifoertektermek_idx",columns={"termek_id"}),
 *      @ORM\index(name="fifoertekvaltozat_idx",columns={"termekvaltozat_id"})
 * })
 */
class Fifoertek
{

    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Raktar")
     * @ORM\JoinColumn(name="raktar_id", referencedColumnName="id", nullable=true, onDelete="cascade")
     */
    private $raktar;

    /**
     * @ORM\ManyToOne(targetEntity="Termek")
     * @ORM\JoinColumn(name="termek_id", referencedColumnName="id", nullable=true, onDelete="cascade")
     */
    private $termek;

    /**
     * @ORM\ManyToOne(targetEntity="TermekValtozat")
     * @ORM\JoinColumn(name="termekvaltozat_id", referencedColumnName="id", nullable=true, onDelete="cascade")
     */
    private $termekvaltozat;

    /** előjeles: negatív = fedezetlen kiadás, ilyenkor nincs rétege
     * @ORM\Column(type="decimal",precision=14,scale=2,nullable=false) */
    private $mennyiseg = 0;

    /** HUF nettó; negatív mennyiségnél 0
     * @ORM\Column(type="decimal",precision=16,scale=2,nullable=false) */
    private $ertek = 0;

    /** súlyozott átlag önköltség, csak pozitív mennyiségnél
     * @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $egysegertek;

    /** van a csoportban pótolt árú réteg
     * @ORM\Column(type="boolean",nullable=false) */
    private $becsult = false;

    /** @ORM\Column(type="integer",nullable=false) */
    private $retegdb = 0;

    /** @ORM\Column(type="datetime",nullable=false) */
    private $szamitva;

    public function getId()
    {
        return $this->id;
    }

    public function getRaktar()
    {
        return $this->raktar;
    }

    public function setRaktar($val)
    {
        $this->raktar = $val;
    }

    public function getTermek()
    {
        return $this->termek;
    }

    public function setTermek($val)
    {
        $this->termek = $val;
    }

    public function getTermekvaltozat()
    {
        return $this->termekvaltozat;
    }

    public function setTermekvaltozat($val)
    {
        $this->termekvaltozat = $val;
    }

    public function getMennyiseg()
    {
        return $this->mennyiseg;
    }

    public function setMennyiseg($val)
    {
        $this->mennyiseg = $val;
    }

    public function getErtek()
    {
        return $this->ertek;
    }

    public function setErtek($val)
    {
        $this->ertek = $val;
    }

    public function getEgysegertek()
    {
        return $this->egysegertek;
    }

    public function setEgysegertek($val)
    {
        $this->egysegertek = $val;
    }

    public function getBecsult()
    {
        return $this->becsult;
    }

    public function setBecsult($val)
    {
        $this->becsult = $val;
    }

    public function getRetegdb()
    {
        return $this->retegdb;
    }

    public function setRetegdb($val)
    {
        $this->retegdb = $val;
    }

    public function getSzamitva()
    {
        return $this->szamitva;
    }

    public function setSzamitva($val)
    {
        $this->szamitva = $val;
    }

}
