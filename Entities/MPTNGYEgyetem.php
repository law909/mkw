<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\MPTNGYEgyetemRepository")
 * @ORM\Table(name="mptngyegyetem",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class MPTNGYEgyetem
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
     * @ORM\OneToMany(targetEntity="MPTNGYKar", mappedBy="egyetem")
     */
    private $karok;

    public function __construct()
    {
        $this->karok = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getKarok()
    {
        return $this->karok;
    }

}
