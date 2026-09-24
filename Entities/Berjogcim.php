<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\BerjogcimRepository")
 * @ORM\Table(name="berjogcim",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class Berjogcim
{

    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /** @ORM\Column(type="string",length=255,nullable=false) */
    private $nev;

    /** An inactive title is not offered for new pay lines, the old lines keep it. @ORM\Column(type="boolean",nullable=false) */
    private $inaktiv = false;

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

    public function getInaktiv()
    {
        return $this->inaktiv;
    }

    public function setInaktiv($inaktiv)
    {
        $this->inaktiv = (bool)$inaktiv;
    }
}
