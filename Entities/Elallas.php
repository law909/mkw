<?php

namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\ElallasRepository")
 * @ORM\Table(name="elallas")
 */
class Elallas
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nev;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;
    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $bizonylat;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $szoveg;

    /**
     * @ORM\OneToMany(targetEntity="Elallasnaplo", mappedBy="elallas", cascade={"persist"})
     * @ORM\OrderBy({"created" = "ASC"})
     */
    private $naplok;

    public function __construct()
    {
        $this->naplok = new \Doctrine\Common\Collections\ArrayCollection();
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

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getBizonylat()
    {
        return $this->bizonylat;
    }

    public function setBizonylat($bizonylat)
    {
        $this->bizonylat = $bizonylat;
    }

    public function getSzoveg()
    {
        return $this->szoveg;
    }

    public function setSzoveg($szoveg)
    {
        $this->szoveg = $szoveg;
    }

    public function getNaplok()
    {
        return $this->naplok;
    }

    public function addNaplo(Elallasnaplo $naplo)
    {
        $naplo->setElallas($this);
        $this->naplok[] = $naplo;
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
