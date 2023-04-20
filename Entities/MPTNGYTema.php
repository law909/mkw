<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @Doctrine\ORM\Mapping\Entity(repositoryClass="Entities\MPTNGYTemaRepository")
 * @Doctrine\ORM\Mapping\Table(name="mptngytema",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class MPTNGYTema
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

    /**
     * @ORM\Column(type="string",length=255)
     */
    private $elnok;

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
    public function getElnok()
    {
        return $this->elnok;
    }

    /**
     * @param mixed $elnok
     */
    public function setElnok($elnok): void
    {
        $this->elnok = $elnok;
    }

}
