<?php

namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * A partner gyártónkénti kedvezménye. A gyártó maga is partner (szállító), ugyanaz, amire a
 * termék `gyarto` mezője mutat.
 *
 * A termékre érvényes kedvezmény sorrendje: termék, termékcsoport, végül a gyártó – lásd
 * {@see Termek::getKedvezmeny()}.
 *
 * @ORM\Entity(repositoryClass="Entities\PartnerGyartoKedvezmenyRepository")
 * @ORM\Table(name="partnergyartokedvezmeny",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class PartnerGyartoKedvezmeny
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
     * @ORM\ManyToOne(targetEntity="Partner",inversedBy="gyartokedvezmenyek")
     * @ORM\JoinColumn(name="partner_id",referencedColumnName="id",onDelete="cascade")
     */
    private $partner;

    /**
     * @ORM\ManyToOne(targetEntity="Partner")
     * @ORM\JoinColumn(name="gyarto_id",referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $gyarto;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $kedvezmeny = 0;

    public function toLista()
    {
        return [
            'id' => $this->getId(),
            'gyartonev' => $this->getGyartoNev(),
            'partnernev' => $this->getPartnerNev(),
            'kedvezmeny' => $this->getKedvezmeny() * 1,
        ];
    }

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
     * @return \Entities\Partner
     */
    public function getPartner()
    {
        return $this->partner;
    }

    public function getPartnerId()
    {
        return $this->partner?->getId();
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
     * @return \Entities\Partner
     */
    public function getGyarto()
    {
        return $this->gyarto;
    }

    public function getGyartoId()
    {
        return $this->gyarto?->getId();
    }

    public function getGyartoNev()
    {
        return $this->gyarto ? $this->gyarto->getNev() : '';
    }

    public function setGyarto($gyarto)
    {
        $this->gyarto = $gyarto;
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
