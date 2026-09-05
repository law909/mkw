<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Egy nyitott FIFO készletréteg: egy bevételezésből még bent lévő mennyiség a maga
 * beszerzési árán. A sorokat kizárólag a `Services\FifoService` írja (nyers DBAL-lal,
 * kötegelve), az ORM csak olvassa.
 *
 * A réteg mennyisége mindig pozitív; ami elfogyott, annak nincs sora. A csoport
 * (raktár, termék, változat) összesített értéke a `Fifoertek`-ben áll.
 *
 * @ORM\Entity(repositoryClass="Entities\FiforetegRepository")
 * @ORM\Table(name="fiforeteg",
 * options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"},
 * indexes={
 *      @ORM\index(name="fiforetegcsoport_idx",columns={"raktar_id","termek_id","termekvaltozat_id"})
 * })
 */
class Fiforeteg
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

    /**
     * @ORM\ManyToOne(targetEntity="Bizonylatfej")
     * @ORM\JoinColumn(name="bebizonylatfej_id", referencedColumnName="id", nullable=true, onDelete="cascade")
     */
    private $bebizonylatfej;

    /**
     * @ORM\ManyToOne(targetEntity="Bizonylattetel")
     * @ORM\JoinColumn(name="bebizonylattetel_id", referencedColumnName="id", nullable=true, onDelete="cascade")
     */
    private $bebizonylattetel;

    /** a bevét teljesítése – a rétegbontás e szerint sorrendezhető join nélkül
     * @ORM\Column(type="date",nullable=true) */
    private $teljesites;

    /** @ORM\Column(type="decimal",precision=14,scale=2,nullable=false) */
    private $mennyiseg = 0;

    /** HUF nettó önköltség
     * @ORM\Column(type="decimal",precision=14,scale=4,nullable=false) */
    private $egysegar = 0;

    /** az ár nem a bevét sajátja, hanem pótolt – lásd FifoService ársorrend
     * @ORM\Column(type="boolean",nullable=false) */
    private $becsult = false;

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

    public function getBebizonylatfej()
    {
        return $this->bebizonylatfej;
    }

    public function setBebizonylatfej($val)
    {
        $this->bebizonylatfej = $val;
    }

    public function getBebizonylattetel()
    {
        return $this->bebizonylattetel;
    }

    public function setBebizonylattetel($val)
    {
        $this->bebizonylattetel = $val;
    }

    public function getTeljesites()
    {
        return $this->teljesites;
    }

    public function setTeljesites($val)
    {
        $this->teljesites = $val;
    }

    public function getMennyiseg()
    {
        return $this->mennyiseg;
    }

    public function setMennyiseg($val)
    {
        $this->mennyiseg = $val;
    }

    public function getEgysegar()
    {
        return $this->egysegar;
    }

    public function setEgysegar($val)
    {
        $this->egysegar = $val;
    }

    public function getBecsult()
    {
        return $this->becsult;
    }

    public function setBecsult($val)
    {
        $this->becsult = $val;
    }

}
