<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * A partner termékkategória kedvezményeinek változásai: ki, mikor, melyik partner melyik ágán mennyiről mennyire
 * változtatott. Felvitelnél a régi, törlésnél az új kedvezmény üres. A partner, az ág és a módosító neve
 * pillanatképként is megmarad, a törzsadat későbbi átnevezése vagy törlése nem írja át.
 *
 * A sorokat a Listeners\PartnerTermekkategoriaKedvezmenyListener írja.
 *
 * @ORM\Entity(repositoryClass="Entities\PartnerTermekkategoriaKedvezmenyNaploRepository")
 * @ORM\Table(name="partnertermekkategoriakedvezmenynaplo",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"},
 *  indexes={@ORM\Index(name="partnertermekkategoriakedvezmenynaplo_partner_idx",columns={"partner_id","created"})})
 */
class PartnerTermekkategoriaKedvezmenyNaplo
{
    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /** @ORM\Column(type="datetime",nullable=true) */
    private $created;

    /**
     * @ORM\ManyToOne(targetEntity="Partner")
     * @ORM\JoinColumn(name="partner_id",referencedColumnName="id",nullable=true,onDelete="set null")
     */
    private $partner;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $partnernev;

    /**
     * @ORM\ManyToOne(targetEntity="TermekFa")
     * @ORM\JoinColumn(name="termekfa_id",referencedColumnName="id",nullable=true,onDelete="set null")
     */
    private $termekfa;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $termekfanev;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $regikedvezmeny;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $ujkedvezmeny;

    /**
     * @ORM\ManyToOne(targetEntity="Dolgozo")
     * @ORM\JoinColumn(name="dolgozo_id",referencedColumnName="id",nullable=true,onDelete="set null")
     */
    private $dolgozo;

    /** a dolgozó, vagy a b2b fiókban módosító üzletkötő neve. @ORM\Column(type="string",length=255,nullable=true) */
    private $modositonev;

    public function getId()
    {
        return $this->id;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getCreatedStr()
    {
        return $this->created ? $this->created->format('Y.m.d. H:i:s') : '';
    }

    public function setCreated($created)
    {
        $this->created = $created;
    }

    public function getPartner()
    {
        return $this->partner;
    }

    public function getPartnerId()
    {
        return $this->partner ? $this->partner->getId() : null;
    }

    public function setPartner($partner)
    {
        $this->partner = $partner;
        if ($partner) {
            $this->partnernev = $partner->getNev();
        }
    }

    public function getPartnernev()
    {
        return $this->partnernev;
    }

    public function getTermekfa()
    {
        return $this->termekfa;
    }

    public function setTermekfa($termekfa)
    {
        $this->termekfa = $termekfa;
    }

    public function getTermekfanev()
    {
        return $this->termekfanev;
    }

    public function setTermekfanev($termekfanev)
    {
        $this->termekfanev = $termekfanev;
    }

    public function getRegikedvezmeny()
    {
        return $this->regikedvezmeny;
    }

    public function setRegikedvezmeny($regikedvezmeny)
    {
        $this->regikedvezmeny = $regikedvezmeny;
    }

    public function getUjkedvezmeny()
    {
        return $this->ujkedvezmeny;
    }

    public function setUjkedvezmeny($ujkedvezmeny)
    {
        $this->ujkedvezmeny = $ujkedvezmeny;
    }

    public function getDolgozo()
    {
        return $this->dolgozo;
    }

    public function setDolgozo($dolgozo)
    {
        $this->dolgozo = $dolgozo;
    }

    public function getModositonev()
    {
        return $this->modositonev;
    }

    public function setModositonev($modositonev)
    {
        $this->modositonev = $modositonev;
    }

    public function getEsemenyNev()
    {
        if ($this->regikedvezmeny === null) {
            return 'felvitel';
        }
        return $this->ujkedvezmeny === null ? 'törlés' : 'módosítás';
    }
}
