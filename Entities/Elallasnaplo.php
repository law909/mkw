<?php

namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\ElallasnaploRepository")
 * @ORM\Table(name="elallasnaplo")
 */
class Elallasnaplo
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
     * @ORM\ManyToOne(targetEntity="Elallas",inversedBy="naplok")
     * @ORM\JoinColumn(name="elallas_id",referencedColumnName="id",onDelete="cascade")
     */
    private $elallas;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $kuldo;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fogado;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $szoveg;
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $esemenyido;
    /**
     * Esemény iránya: 1 = bejövő, -1 = kimenő kommunikáció
     * @ORM\Column(type="integer", nullable=true)
     */
    private $irany;

    public function getId()
    {
        return $this->id;
    }

    public function getElallas()
    {
        return $this->elallas;
    }

    public function setElallas(Elallas $elallas)
    {
        $this->elallas = $elallas;
    }

    public function getKuldo()
    {
        return $this->kuldo;
    }

    public function setKuldo($kuldo)
    {
        $this->kuldo = $kuldo;
    }

    public function getFogado()
    {
        return $this->fogado;
    }

    public function setFogado($fogado)
    {
        $this->fogado = $fogado;
    }

    public function getSzoveg()
    {
        return $this->szoveg;
    }

    public function setSzoveg($szoveg)
    {
        $this->szoveg = $szoveg;
    }

    public function getEsemenyido()
    {
        return $this->esemenyido;
    }

    public function setEsemenyido($adat = null)
    {
        if (is_a($adat, 'DateTime')) {
            $this->esemenyido = $adat;
        } elseif ($adat === null || $adat === '') {
            $this->esemenyido = null;
        } else {
            $this->esemenyido = new \DateTime($adat);
        }
    }

    public function getEsemenyidoStr()
    {
        if ($this->esemenyido) {
            return $this->esemenyido->format(\mkw\store::$DateTimeFormat);
        }
        return '';
    }

    public function getEsemenyidoInput()
    {
        if ($this->esemenyido) {
            return $this->esemenyido->format('Y-m-d\TH:i');
        }
        return '';
    }

    public function getIrany()
    {
        return $this->irany;
    }

    public function setIrany($irany)
    {
        $this->irany = ($irany < 0) ? -1 : 1;
    }

    public function getIranyStr()
    {
        return ($this->irany < 0) ? 'kimenő' : 'bejövő';
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getCreatedStr()
    {
        if ($this->getCreated()) {
            return $this->getCreated()->format(\mkw\store::$DateTimeFormat);
        }
        return '';
    }

    public function clearCreated()
    {
        $this->created = null;
    }

    public function getLastmod()
    {
        return $this->lastmod;
    }

    public function getLastmodStr()
    {
        if ($this->getLastmod()) {
            return $this->getLastmod()->format(\mkw\store::$DateTimeFormat);
        }
        return '';
    }

}
