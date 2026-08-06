<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Dolgozó szintű paraméterek – a `parameterek` tábla dolgozónkénti párja.
 *
 * A `parameterek` globális beállításokat tárol (egy sor = egy kulcs), itt viszont
 * ugyanaz a kulcs minden dolgozónál más értéket vehet fel. Tipikus tartalom a
 * felhasználói felület megjegyzett állapota (pl. a mattable listák "Mindig nyitva"
 * pipája lista-URL-enként).
 *
 * Olvasás/írás a {@see \Services\DolgozoParameterService} statikus metódusaival,
 * ne közvetlenül a repository-n keresztül.
 *
 * @ORM\Entity(repositoryClass="Entities\DolgozoparameterekRepository")
 * @ORM\Table(name="dolgozoparameterek",
 *  options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"},
 *  uniqueConstraints={@ORM\UniqueConstraint(name="dolgozoparameterek_egyedi",columns={"dolgozo_id","par"})})
 */
class Dolgozoparameterek
{

    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Dolgozo")
     * @ORM\JoinColumn(name="dolgozo_id", referencedColumnName="id",nullable=false,onDelete="cascade")
     */
    private $dolgozo;

    /** @ORM\Column(type="string",length=255,nullable=false) */
    private $par;

    /** @ORM\Column(type="text",nullable=true) */
    private $ertek;

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \Entities\Dolgozo
     */
    public function getDolgozo()
    {
        return $this->dolgozo;
    }

    public function getDolgozoId()
    {
        if ($this->dolgozo) {
            return $this->dolgozo->getId();
        }
        return null;
    }

    public function setDolgozo($dolgozo)
    {
        $this->dolgozo = $dolgozo;
    }

    public function getPar()
    {
        return $this->par;
    }

    public function setPar($par)
    {
        $this->par = $par;
    }

    public function getErtek()
    {
        return $this->ertek;
    }

    public function setErtek($ertek)
    {
        $this->ertek = $ertek;
    }

}
