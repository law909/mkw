<?php

namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * Termékváltozat raktáranként megadott optimális készlete – a `termekvaltozat.optkeszlet`
 * globális oszlop raktáras párja, a min. készlet mintájára.
 *
 * @ORM\Entity(repositoryClass="Entities\TermekValtozatOptkeszletRepository")
 * @ORM\Table(name="termekvaltozatoptkeszlet",
 *  options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"},
 *  uniqueConstraints={@ORM\UniqueConstraint(name="termekvaltozatoptkeszlet_egyedi",columns={"termekvaltozat_id","raktar_id"})})
 */
class TermekValtozatOptkeszlet
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
    private $optkeszlet;

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

    public function getOptkeszlet()
    {
        return $this->optkeszlet;
    }

    public function setOptkeszlet($optkeszlet)
    {
        $this->optkeszlet = $optkeszlet;
    }

}
