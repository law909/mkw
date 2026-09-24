<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * A named grouping of a report, shared by everyone: how to look at the data (levels, display), not the filters.
 *
 * @ORM\Entity(repositoryClass="Entities\KimutatasnezetRepository")
 * @ORM\Table(name="kimutatasnezet",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"},
 *     uniqueConstraints={@ORM\UniqueConstraint(name="kimutatasnezet_nev_uq",columns={"kimutatas","nev"})})
 */
class Kimutatasnezet
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
     * @ORM\JoinColumn(name="createdby", referencedColumnName="id",onDelete="set null")
     */
    private $createdby;

    /** The report's route base, e.g. 'arbevetellista'. @ORM\Column(type="string",length=30,nullable=false) */
    private $kimutatas;

    /** @ORM\Column(type="string",length=100,nullable=false) */
    private $nev;

    /** JSON. @ORM\Column(type="text",nullable=false) */
    private $beallitas = '{}';

    public function getId()
    {
        return $this->id;
    }

    public function getCreatedbyNev()
    {
        return $this->createdby ? $this->createdby->getNev() : '';
    }

    public function getKimutatas()
    {
        return $this->kimutatas;
    }

    public function setKimutatas($kimutatas)
    {
        $this->kimutatas = $kimutatas;
    }

    public function getNev()
    {
        return $this->nev;
    }

    public function setNev($nev)
    {
        $this->nev = $nev;
    }

    public function getBeallitas(): array
    {
        return json_decode($this->beallitas ?: '{}', true) ?: [];
    }

    public function setBeallitas(array $beallitas)
    {
        $this->beallitas = json_encode($beallitas, JSON_UNESCAPED_UNICODE);
    }
}
