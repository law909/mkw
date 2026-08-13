<?php

namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * Termékváltozat raktáranként megadott min. bolti készlete – a `termekvaltozat.minkeszlet`
 * globális oszlop raktáras párja. A feloldási létrában a termék raktáras értéke üti ezt,
 * lásd \Services\KeszletService.
 *
 * @ORM\Entity(repositoryClass="Entities\TermekValtozatMinkeszletRepository")
 * @ORM\Table(name="termekvaltozatminkeszlet",
 *  options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"},
 *  uniqueConstraints={@ORM\UniqueConstraint(name="termekvaltozatminkeszlet_egyedi",columns={"termekvaltozat_id","raktar_id"})})
 */
class TermekValtozatMinkeszlet
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
     * @ORM\ManyToOne(targetEntity="TermekValtozat")
     * @ORM\JoinColumn(name="termekvaltozat_id",referencedColumnName="id",nullable=false,onDelete="cascade")
     */
    private $termekvaltozat;

    /**
     * @ORM\ManyToOne(targetEntity="Raktar")
     * @ORM\JoinColumn(name="raktar_id",referencedColumnName="id",nullable=false,onDelete="cascade")
     */
    private $raktar;

    /** @ORM\Column(type="decimal",precision=14,scale=2,nullable=true) */
    private $minkeszlet;

    public function getId()
    {
        return $this->id;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getLastmod()
    {
        return $this->lastmod;
    }

    /**
     * @return \Entities\TermekValtozat
     */
    public function getTermekvaltozat()
    {
        return $this->termekvaltozat;
    }

    public function getTermekvaltozatId()
    {
        return $this->termekvaltozat?->getId();
    }

    public function setTermekvaltozat($termekvaltozat)
    {
        $this->termekvaltozat = $termekvaltozat;
    }

    /**
     * @return \Entities\Raktar
     */
    public function getRaktar()
    {
        return $this->raktar;
    }

    public function getRaktarId()
    {
        return $this->raktar?->getId();
    }

    public function setRaktar($raktar)
    {
        $this->raktar = $raktar;
    }

    public function getMinkeszlet()
    {
        return $this->minkeszlet;
    }

    public function setMinkeszlet($minkeszlet)
    {
        $this->minkeszlet = $minkeszlet;
    }

}
