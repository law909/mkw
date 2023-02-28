<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @Doctrine\ORM\Mapping\Entity(repositoryClass="Entities\MPTNGYTemakorRepository")
 * @Doctrine\ORM\Mapping\Table(name="mptngytemakor",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class MPTNGYTemakor
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string",length=255)
     */
    private $nev;

    /** @ORM\ManyToMany(targetEntity="Dolgozo", mappedBy="mptngytemakorok", cascade={"persist"}) */
    private $dolgozok;

    public function __construct()
    {
        $this->dolgozok = new \Doctrine\Common\Collections\ArrayCollection();
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
    public function setNev($nev)
    {
        $this->nev = $nev;
    }

    public function getDolgozok()
    {
        return $this->dolgozok;
    }

    public function addDolgozo(Dolgozo $dolgozo)
    {
//		if (!$this->dolgozok->contains($dolgozo)) {  // deleted for speed
        $this->dolgozok->add($dolgozo);
        $dolgozo->addMPTNGYTemakor($this);
//		}
    }

    public function removeDolgozo(Dolgozo $dolgozo)
    {
        if ($this->dolgozok->removeElement($dolgozo)) {
            $dolgozo->removeMPTNGYTemakor($this);
            return true;
        }
        return false;
    }

}
