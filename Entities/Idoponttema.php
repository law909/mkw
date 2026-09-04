<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\IdoponttemaRepository")
 * @ORM\Table(name="idoponttema",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class Idoponttema
{

    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /** @ORM\Column(type="string",length=255,nullable=false) */
    private $nev = '';

    /** @ORM\Column(type="text",nullable=true) */
    private $leiras = '';

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $url;

    /** @ORM\Column(type="boolean", nullable=false) */
    private $inaktiv = false;

    /** Az új időpontba másolódó kérdőív JSON-ban, lásd \Services\IdopontKerdoivService. */
    /** @ORM\Column(type="text",nullable=true) */
    private $kerdoiv;

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

    public function getLeiras()
    {
        return $this->leiras;
    }

    public function setLeiras($leiras)
    {
        $this->leiras = $leiras;
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

    public function getKerdoiv()
    {
        return $this->kerdoiv;
    }

    public function setKerdoiv($kerdoiv)
    {
        $this->kerdoiv = $kerdoiv;
    }

    public function getKerdoivArray(): array
    {
        return \Services\IdopontKerdoivService::parse($this->kerdoiv);
    }

}
