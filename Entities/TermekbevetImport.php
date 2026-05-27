<?php
namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\TermekbevetImportRepository")
 * @ORM\Table(name="termekbevetimport",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"},
 *     indexes={
 *         @ORM\Index(name="termekbevetimport_cikkszam_idx", columns={"cikkszam"}),
 *         @ORM\Index(name="termekbevetimport_letoltve_idx", columns={"letoltve"}),
 *         @ORM\Index(name="termekbevetimport_forrasfajl_idx", columns={"forrasfajl"})
 *     }
 * )
 */
class TermekbevetImport
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

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $forrasfajl;

    /** @ORM\Column(type="string",length=64,nullable=true) */
    private $cikkszam;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $leiras;

    /** @ORM\Column(type="integer",nullable=true) */
    private $karton;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $mennyiseg;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $egysegar;

    /** @ORM\Column(type="string",length=16,nullable=true) */
    private $adokod;

    /** @ORM\Column(type="decimal",precision=14,scale=2,nullable=true) */
    private $osszesen;

    /** @ORM\Column(type="boolean",nullable=false,options={"default":false}) */
    private $letoltve = false;

    /**
     * @ORM\ManyToOne(targetEntity="Raktar")
     * @ORM\JoinColumn(name="raktar_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Raktar
     */
    private $raktar;

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
        if ($this->getCreated()) {
            return $this->getCreated()->format(\mkw\store::$DateTimeFormat);
        }
        return '';
    }

    public function getForrasfajl()
    {
        return $this->forrasfajl;
    }

    public function setForrasfajl($val)
    {
        $this->forrasfajl = $val;
    }

    public function getCikkszam()
    {
        return $this->cikkszam;
    }

    public function setCikkszam($val)
    {
        $this->cikkszam = $val;
    }

    public function getLeiras()
    {
        return $this->leiras;
    }

    public function setLeiras($val)
    {
        $this->leiras = $val;
    }

    public function getKarton()
    {
        return $this->karton;
    }

    public function setKarton($val)
    {
        $this->karton = $val;
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

    public function getAdokod()
    {
        return $this->adokod;
    }

    public function setAdokod($val)
    {
        $this->adokod = $val;
    }

    public function getOsszesen()
    {
        return $this->osszesen;
    }

    public function setOsszesen($val)
    {
        $this->osszesen = $val;
    }

    public function getLetoltve()
    {
        return $this->letoltve;
    }

    public function setLetoltve($val)
    {
        $this->letoltve = (bool)$val;
    }

    /**
     * @return Raktar|null
     */
    public function getRaktar()
    {
        return $this->raktar;
    }

    public function setRaktar($val)
    {
        $this->raktar = $val;
    }

    public function getRaktarId()
    {
        if ($this->raktar) {
            return $this->raktar->getId();
        }
        return null;
    }
}
