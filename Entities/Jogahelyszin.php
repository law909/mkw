<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\JogahelyszinRepository")
 * @ORM\Table(name="jogahelyszin",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class Jogahelyszin
{

    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /** @ORM\Column(type="string",length=255,nullable=false) */
    private $nev = '';

    /** @ORM\Column(type="string",length=10,nullable=true) */
    private $irszam = '';

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $varos = '';

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $utca = '';

    /** @ORM\Column(type="string",length=50,nullable=true) */
    private $hazszam = '';

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $url;

    /** @ORM\Column(type="boolean", nullable=false) */
    private $inaktiv = false;

    /** A helyszínről szóló szövegblokk (megközelítés, parkolás), a levelekben `{$helyszin}`. */
    /** @ORM\Column(type="text",nullable=true) */
    private $emailsablon;

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

    public function getIrszam()
    {
        return $this->irszam;
    }

    public function setIrszam($irszam)
    {
        $this->irszam = $irszam;
    }

    public function getVaros()
    {
        return $this->varos;
    }

    public function setVaros($varos)
    {
        $this->varos = $varos;
    }

    public function getUtca()
    {
        return $this->utca;
    }

    public function setUtca($utca)
    {
        $this->utca = $utca;
    }

    public function getHazszam()
    {
        return $this->hazszam;
    }

    public function setHazszam($hazszam)
    {
        $this->hazszam = $hazszam;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function getInaktiv()
    {
        return $this->inaktiv;
    }

    public function setInaktiv($inaktiv)
    {
        $this->inaktiv = $inaktiv;
    }

    public function getEmailsablon()
    {
        return $this->emailsablon;
    }

    public function setEmailsablon($emailsablon)
    {
        $this->emailsablon = $emailsablon;
    }

    public function getFullAddress()
    {
        return trim(implode(' ', array_filter([$this->irszam, $this->varos, $this->utca, $this->hazszam])));
    }

}
