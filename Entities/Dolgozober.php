<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * One pay line: the employee got this amount on this day under this title. A wrong line is voided, not deleted.
 *
 * @ORM\Entity(repositoryClass="Entities\DolgozoberRepository")
 * @ORM\Table(name="dolgozober",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"},
 *     indexes={@ORM\Index(name="dolgozober_datum_idx",columns={"datum"})})
 */
class Dolgozober
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
     * @ORM\ManyToOne(targetEntity="Dolgozo")
     * @ORM\JoinColumn(name="dolgozo_id", referencedColumnName="id",nullable=false,onDelete="restrict")
     */
    private $dolgozo;

    /** @ORM\Column(type="date",nullable=false) */
    private $datum;

    /**
     * @ORM\ManyToOne(targetEntity="Berjogcim")
     * @ORM\JoinColumn(name="berjogcim_id", referencedColumnName="id",nullable=false,onDelete="restrict")
     */
    private $berjogcim;

    /** @ORM\Column(type="decimal",precision=14,scale=2,nullable=false) */
    private $osszeg = 0;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $megjegyzes;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $rontott = false;

    /** @ORM\Column(type="datetime",nullable=true) */
    private $rontotton;

    /**
     * @ORM\ManyToOne(targetEntity="Dolgozo")
     * @ORM\JoinColumn(name="rontottby", referencedColumnName="id")
     */
    private $rontottby;

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

    public function getLastmod()
    {
        return $this->lastmod;
    }

    public function getLastmodStr()
    {
        return $this->lastmod ? $this->lastmod->format(\mkw\store::$DateTimeFormat) : '';
    }

    public function getCreatedby()
    {
        return $this->createdby;
    }

    public function getCreatedbyNev()
    {
        return $this->createdby ? $this->createdby->getNev() : '';
    }

    public function getUpdatedby()
    {
        return $this->updatedby;
    }

    public function getUpdatedbyNev()
    {
        return $this->updatedby ? $this->updatedby->getNev() : '';
    }

    /**
     * @return \Entities\Dolgozo
     */
    public function getDolgozo()
    {
        return $this->dolgozo;
    }

    public function getDolgozoId()
    {
        return $this->dolgozo ? $this->dolgozo->getId() : null;
    }

    public function getDolgozoNev()
    {
        return $this->dolgozo ? $this->dolgozo->getNev() : '';
    }

    public function setDolgozo($dolgozo)
    {
        $this->dolgozo = $dolgozo;
    }

    public function getDatum()
    {
        return $this->datum;
    }

    public function getDatumStr()
    {
        return $this->datum ? $this->datum->format(\mkw\store::$DateFormat) : '';
    }

    public function setDatum($datum)
    {
        if (!$datum instanceof \DateTime) {
            $datum = $datum ? new \DateTime(\mkw\store::convDate($datum)) : null;
        }
        $this->datum = $datum;
    }

    /**
     * @return \Entities\Berjogcim
     */
    public function getBerjogcim()
    {
        return $this->berjogcim;
    }

    public function getBerjogcimId()
    {
        return $this->berjogcim ? $this->berjogcim->getId() : null;
    }

    public function getBerjogcimNev()
    {
        return $this->berjogcim ? $this->berjogcim->getNev() : '';
    }

    public function setBerjogcim($berjogcim)
    {
        $this->berjogcim = $berjogcim;
    }

    public function getOsszeg()
    {
        return $this->osszeg;
    }

    public function setOsszeg($osszeg)
    {
        $this->osszeg = $osszeg;
    }

    public function getMegjegyzes()
    {
        return $this->megjegyzes;
    }

    public function setMegjegyzes($megjegyzes)
    {
        $this->megjegyzes = $megjegyzes;
    }

    public function getRontott()
    {
        return $this->rontott;
    }

    /** Voiding is final: the stamp records who did it and when. */
    public function ront($dolgozo)
    {
        if ($this->rontott) {
            return;
        }
        $this->rontott = true;
        $this->rontotton = new \DateTime();
        $this->rontottby = $dolgozo;
    }

    public function getRontotton()
    {
        return $this->rontotton;
    }

    public function getRontottonStr()
    {
        return $this->rontotton ? $this->rontotton->format(\mkw\store::$DateTimeFormat) : '';
    }

    public function getRontottby()
    {
        return $this->rontottby;
    }

    public function getRontottbyNev()
    {
        return $this->rontottby ? $this->rontottby->getNev() : '';
    }
}
