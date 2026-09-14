<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * A partner kedvezménye egy termékfa-ágra: az ág és az alatta lévő ágak minden termékére érvényes.
 *
 * @ORM\Entity(repositoryClass="Entities\PartnerTermekkategoriaKedvezmenyRepository")
 * @ORM\Table(name="partnertermekkategoriakedvezmeny",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class PartnerTermekkategoriaKedvezmeny
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
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
     * @ORM\ManyToOne(targetEntity="Partner",inversedBy="termekkategoriakedvezmenyek")
     * @ORM\JoinColumn(name="partner_id",referencedColumnName="id",nullable=false,onDelete="cascade")
     */
    private $partner;

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

    public function getCreated()
    {
        return $this->created;
    }

    public function getLastmod()
    {
        return $this->lastmod;
    }

    /**
     * @return Partner|null
     */
    public function getPartner()
    {
        return $this->partner;
    }

    public function getPartnerId()
    {
        return $this->partner ? $this->partner->getId() : 0;
    }

    public function getPartnerNev()
    {
        return $this->partner ? $this->partner->getNev() : '';
    }

    public function setPartner($partner)
    {
        $this->partner = $partner;
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

    /** a szülő ág nevével együtt, mert ugyanaz a név több márka alatt is előfordul */
    public function getTermekfaNev()
    {
        if (!$this->termekfa) {
            return '';
        }
        $parent = $this->termekfa->getParent();
        return ($parent && $parent->getParent() ? $parent->getNev() . ' / ' : '') . $this->termekfa->getNev();
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
