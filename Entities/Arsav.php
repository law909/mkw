<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\ArsavRepository")
 * @ORM\Table(name="arsav",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class Arsav
{
    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /** @ORM\Column(type="string",length=255,nullable=false) */
    private $nev;

    /**
     * A képlettel számolt ár bruttójának kerekítési egysége (pl. 100 = száz forintra). Üresen
     * vagy nullán nincs kerekítés.
     *
     * @ORM\Column(type="decimal",precision=14,scale=4,nullable=true)
     */
    private $kerekites;

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

    public function getKerekites()
    {
        return $this->kerekites;
    }

    public function setKerekites($val)
    {
        $this->kerekites = ($val === '' || $val === null) ? null : $val;
    }

}