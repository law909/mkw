<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Egy bizonylattételre eső kapcsolódó költség. A törzs adatai (név, csoport, ár, …) és a termék
 * számítási alapja is át van másolva ide, hogy a bizonylat a törzs későbbi módosítása után is
 * ugyanazt mutassa. A sorokat a bizonylat minden mentése újragenerálja
 * (`Services\KapcsolodoKoltsegService`, a `BizonylatfejListener` onFlush-ából).
 *
 * @ORM\Entity(repositoryClass="Entities\BizonylattetelKapcsolodokoltsegRepository")
 * @ORM\Table(name="bizonylattetelkapcsolodokoltseg",
 *  options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class BizonylattetelKapcsolodokoltseg
{

    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Bizonylattetel",inversedBy="kapcsolodokoltsegek")
     * @ORM\JoinColumn(name="bizonylattetel_id",referencedColumnName="id",nullable=false,onDelete="cascade")
     */
    private $bizonylattetel;

    /**
     * A törzssor, amiből készült. Csak nyomkövetésre: minden megjelenített adat a másolatból jön,
     * a törzssor törlése nem viszi el a bizonylat adatát.
     *
     * @ORM\ManyToOne(targetEntity="Kapcsolodokoltseg")
     * @ORM\JoinColumn(name="kapcsolodokoltseg_id",referencedColumnName="id",nullable=true,onDelete="set null")
     */
    private $kapcsolodokoltseg;

    /** @ORM\Column(type="string",length=255,nullable=false) */
    private $nev = '';

    /** @ORM\Column(type="string",length=50,nullable=true) */
    private $csoport;

    /** @ORM\Column(type="string",length=50,nullable=true) */
    private $szamitasalap;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $ar = 0;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $navfeladando = false;

    /** a termék vonatkozó adata (ma a súlya), amivel az ár szorzódik @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $szamitasalapertek = 0;

    /** a tétel teljes mennyiségére eső költség: ar * szamitasalapertek * mennyiseg @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $ertek = 0;

    public function getId()
    {
        return $this->id;
    }

    public function getBizonylattetel()
    {
        return $this->bizonylattetel;
    }

    public function setBizonylattetel($val)
    {
        $this->bizonylattetel = $val;
    }

    public function getKapcsolodokoltseg()
    {
        return $this->kapcsolodokoltseg;
    }

    public function setKapcsolodokoltseg($val)
    {
        $this->kapcsolodokoltseg = $val;
    }

    public function getNev()
    {
        return $this->nev;
    }

    public function setNev($val)
    {
        $this->nev = $val;
    }

    public function getCsoport()
    {
        return $this->csoport;
    }

    public function setCsoport($val)
    {
        $this->csoport = $val;
    }

    public function getCsoportNev()
    {
        return Kapcsolodokoltseg::CSOPORTOK[$this->csoport] ?? '';
    }

    public function getSzamitasalap()
    {
        return $this->szamitasalap;
    }

    public function setSzamitasalap($val)
    {
        $this->szamitasalap = $val;
    }

    public function getSzamitasalapNev()
    {
        return Kapcsolodokoltseg::SZAMITASALAPOK[$this->szamitasalap] ?? '';
    }

    public function getAr()
    {
        return $this->ar;
    }

    public function setAr($val)
    {
        $this->ar = $val;
    }

    public function getNavfeladando()
    {
        return $this->navfeladando;
    }

    public function setNavfeladando($val)
    {
        $this->navfeladando = $val;
    }

    public function getSzamitasalapertek()
    {
        return $this->szamitasalapertek;
    }

    public function setSzamitasalapertek($val)
    {
        $this->szamitasalapertek = $val;
    }

    public function getErtek()
    {
        return $this->ertek;
    }

    public function setErtek($val)
    {
        $this->ertek = $val;
    }

}
