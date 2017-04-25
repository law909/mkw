<?php

namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Doctrine\ORM\Mapping\Entity(repositoryClass="PartnerTermekKedvezmenyRepository")
 * @Doctrine\ORM\Mapping\Table(name="partnertermekkedvezmeny",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class PartnerTermekKedvezmeny {

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
     * @ORM\ManyToOne(targetEntity="Partner",inversedBy="termekcsoportkedvezmenyek")
     * @ORM\JoinColumn(name="partner_id",referencedColumnName="id",onDelete="cascade")
     */
    private $partner;

    /**
     * @ORM\ManyToOne(targetEntity="Termek")
     * @ORM\JoinColumn(name="termek_id",referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $termek;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $kedvezmeny = 0;

    public function toLista() {
        $r = array();
        $r['id'] = $this->getId();
        $r['termeknev'] = $this->getTermekNev();
        $r['partnernev'] = $this->getPartnerNev();
        $r['kedvezmeny'] = $this->getKedvezmeny() * 1;
        return $r;
    }
    /**
     * @return mixed
     */
    public function getCreated() {
        return $this->created;
    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getKedvezmeny() {
        return $this->kedvezmeny;
    }

    /**
     * @param mixed $kedvezmeny
     */
    public function setKedvezmeny($kedvezmeny) {
        $this->kedvezmeny = $kedvezmeny;
    }

    /**
     * @return mixed
     */
    public function getLastmod() {
        return $this->lastmod;
    }

    /**
     * @return \Entities\Partner
     */
    public function getPartner() {
        return $this->partner;
    }

    public function getPartnerId() {
        if ($this->getPartner()) {
            return $this->getPartner()->getId();
        }
        return 0;
    }

    public function getPartnerNev() {
        if ($this->getPartner()) {
            return $this->getPartner()->getNev();
        }
        return '';
    }
    /**
     * @param \Entities\Partner $partner
     */
    public function setPartner($partner) {
        $this->partner = $partner;
    }

    /**
     * @return \Entities\Termek
     */
    public function getTermek() {
        return $this->termek;
    }

    public function getTermekId() {
        if ($this->getTermek()) {
            return $this->getTermek()->getId();
        }
        return 0;
    }

    public function getTermekNev() {
        if ($this->getTermek()) {
            return $this->getTermek()->getNev();
        }
        return '';
    }

    public function getTermekCikkszam() {
        if ($this->getTermek()) {
            return $this->getTermek()->getCikkszam();
        }
        return '';
    }

    /**
     * @param \Entities\Termek $termek
     */
    public function setTermek($termek) {
        $this->termek = $termek;
    }

}