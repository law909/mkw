<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="termekszinkep",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class TermekSzinKep
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
     * @ORM\ManyToOne(targetEntity="Termek", inversedBy="termekszinkepek")
     * @ORM\JoinColumn(name="termek_id", referencedColumnName="id", onDelete="cascade")
     */
    private $termek;

    /**
     * @ORM\ManyToOne(targetEntity="Szin")
     * @ORM\JoinColumn(name="szin_id", referencedColumnName="id", onDelete="set null", nullable=true)
     */
    private $szin;

    /**
     * @ORM\ManyToOne(targetEntity="TermekKep")
     * @ORM\JoinColumn(name="termekkep_id", referencedColumnName="id", onDelete="set null", nullable=true)
     */
    private $kep;

    public function getId()
    {
        return $this->id;
    }

    public function getTermek()
    {
        return $this->termek;
    }

    public function setTermek(Termek $termek = null)
    {
        $this->termek = $termek;
    }

    public function removeTermek()
    {
        $this->termek = null;
    }

    public function getSzin()
    {
        return $this->szin;
    }

    public function getSzinId()
    {
        return $this->szin?->getId();
    }

    public function setSzin(Szin $szin = null)
    {
        $this->szin = $szin;
    }

    public function getKep()
    {
        return $this->kep;
    }

    public function getKepId()
    {
        return $this->kep?->getId();
    }

    public function setKep(TermekKep $kep = null)
    {
        $this->kep = $kep;
    }
}