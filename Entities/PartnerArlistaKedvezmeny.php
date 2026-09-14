<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Egy termékkategória (termékfa-ág) kedvezménye a partner árlistájának egy sávjában.
 *
 * @ORM\Entity(repositoryClass="Entities\PartnerArlistaKedvezmenyRepository")
 * @ORM\Table(name="partnerarlistakedvezmeny",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class PartnerArlistaKedvezmeny
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="PartnerArlistaSav",inversedBy="kedvezmenyek")
     * @ORM\JoinColumn(name="partnerarlistasav_id",referencedColumnName="id",nullable=false,onDelete="cascade")
     */
    private $sav;

    /**
     * @ORM\ManyToOne(targetEntity="TermekFa")
     * @ORM\JoinColumn(name="termekfa_id",referencedColumnName="id",nullable=false,onDelete="cascade")
     */
    private $termekfa;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $kedvezmeny = 0;

    public function getId()
    {
        return $this->id;
    }

    public function getSav()
    {
        return $this->sav;
    }

    public function setSav($sav)
    {
        $this->sav = $sav;
    }

    /**
     * @return TermekFa|null
     */
    public function getTermekfa()
    {
        return $this->termekfa;
    }

    public function getTermekfaId()
    {
        return $this->termekfa ? $this->termekfa->getId() : 0;
    }

    public function setTermekfa($termekfa)
    {
        $this->termekfa = $termekfa;
    }

    public function getKedvezmeny()
    {
        return $this->kedvezmeny;
    }

    public function setKedvezmeny($kedvezmeny)
    {
        $this->kedvezmeny = $kedvezmeny;
    }
}
