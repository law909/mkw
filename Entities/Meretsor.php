<?php

namespace Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\MeretsorRepository")
 * @ORM\Table(name="meretsor",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class Meretsor
{
    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /** @ORM\Column(type="string",length=255) */
    private $nev;

    /**
     * @ORM\ManyToMany(targetEntity="Meret")
     * @ORM\JoinTable(name="meretsor_meretek",
     *  joinColumns={@ORM\JoinColumn(name="meretsor_id",referencedColumnName="id",onDelete="cascade")},
     *  inverseJoinColumns={@ORM\JoinColumn(name="meret_id",referencedColumnName="id",onDelete="cascade")}
     *  )
     */
    private $meretek;

    public function __construct()
    {
        $this->meretek = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNev()
    {
        return $this->nev;
    }

    public function setNev($nev)
    {
        $this->nev = $nev;
    }

    /**
     * @return Collection|Meret[]
     */
    public function getMeretek()
    {
        return $this->meretek;
    }

    public function getMeretIds()
    {
        $ids = [];
        foreach ($this->meretek as $meret) {
            $ids[] = $meret->getId();
        }
        return $ids;
    }

    public function addMeret(Meret $meret)
    {
        if (!$this->meretek->contains($meret)) {
            $this->meretek->add($meret);
        }
        return $this;
    }

    public function removeMeret(Meret $meret)
    {
        $this->meretek->removeElement($meret);
        return $this;
    }

    public function removeAllMeret()
    {
        $this->meretek->clear();
        return $this;
    }
}