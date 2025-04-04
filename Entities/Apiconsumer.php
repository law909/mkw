<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\ApiconsumerRepository")
 * @ORM\Table(name="apiconsumer",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class Apiconsumer
{
    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /** @ORM\Column(type="string",length=255,nullable=false) */
    private $nev;
    /** @ORM\Column(type="string",length=255,nullable=false) */
    private $kulcs;
    /**
     * @ORM\ManyToOne(targetEntity="Partner")
     * @ORM\JoinColumn(name="partner_id", referencedColumnName="id",nullable=true)
     * @var \Entities\Partner
     */
    private $partner;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getNev()
    {
        return $this->nev;
    }

    /**
     * @param mixed $nev
     */
    public function setNev($nev)
    {
        $this->nev = $nev;
    }

    /**
     * @return mixed
     */
    public function getKulcs()
    {
        return $this->kulcs;
    }

    /**
     * @param mixed $kulcs
     */
    public function setKulcs($kulcs)
    {
        $this->kulcs = $kulcs;
    }

    /**
     * @return \Entities\Partner
     */
    public function getPartner()
    {
        return $this->partner;
    }

    /**
     * @param \Entities\Partner $val
     */
    public function setPartner($val)
    {
        $this->partner = $val;
    }

}