<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\MPTNGYKarRepository")
 * @ORM\Table(name="mptngykar",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class MPTNGYKar
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nev;

    /**
     * @ORM\ManyToOne(targetEntity="MPTNGYEgyetem", inversedBy="karok")
     * @ORM\JoinColumn(name="egyetem_id", referencedColumnName="id")
     */
    private $egyetem;

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
    public function setNev($nev): void
    {
        $this->nev = $nev;
    }

    /**
     * @return mixed
     */
    public function getEgyetem()
    {
        return $this->egyetem;
    }

    public function getEgyetemId()
    {
        return $this->egyetem?->getId();
    }

    /**
     * @param mixed $egyetem
     */
    public function setEgyetem($egyetem): void
    {
        $this->egyetem = $egyetem;
    }

}
