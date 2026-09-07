<?php

namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * Termék raktáranként megadott optimális készlete – a `termek.optkeszlet` globális oszlop
 * raktáras párja, a min. készlet mintájára. Sor csak ott van, ahol az admin ténylegesen
 * megadott értéket; a hiányzó sor a globális oszlopra esik vissza.
 *
 * Szándékosan nincs inverz OneToMany a Termek-en: a törlést a DB-szintű cascade intézi.
 *
 * @ORM\Entity(repositoryClass="Entities\TermekOptkeszletRepository")
 * @ORM\Table(name="termekoptkeszlet",
 *  options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"},
 *  uniqueConstraints={@ORM\UniqueConstraint(name="termekoptkeszlet_egyedi",columns={"termek_id","raktar_id"})})
 */
class TermekOptkeszlet
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
     * @ORM\ManyToOne(targetEntity="Termek")
     * @ORM\JoinColumn(name="termek_id",referencedColumnName="id",nullable=false,onDelete="cascade")
     */
    private $termek;

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
     * @return \Entities\Termek
     */
    public function getTermek()
    {
        return $this->termek;
    }

    public function getTermekId()
    {
        return $this->termek?->getId();
    }

    public function setTermek($termek)
    {
        $this->termek = $termek;
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
