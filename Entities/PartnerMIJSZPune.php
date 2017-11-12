<?php

namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Doctrine\ORM\Mapping\Entity(repositoryClass="PartnerMIJSZPuneRepository")
 * @Doctrine\ORM\Mapping\Table(name="partnermijszpune",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class PartnerMIJSZPune {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Partner",inversedBy="mijszpune")
     * @ORM\JoinColumn(name="partner_id",referencedColumnName="id",onDelete="cascade")
     */
    private $partner;

    /** @ORM\Column(type="integer",nullable=true) */
    private $ev = 0;

    /** @ORM\Column(type="integer",nullable=true) */
    private $honap = 0;

    public function toLista() {
        $r = array();
        $r['id'] = $this->getId();
        $r['partnernev'] = $this->getPartnerNev();
        $r['ev'] = $this->getEv();
        $r['honap'] = $this->getHonap();
        return $r;
    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
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
     * @return mixed
     */
    public function getEv() {
        return $this->ev;
    }

    /**
     * @param mixed $ev
     */
    public function setEv($ev) {
        $this->ev = $ev;
    }

    /**
     * @return mixed
     */
    public function getHonap() {
        return $this->honap;
    }

    /**
     * @param mixed $honap
     */
    public function setHonap($honap) {
        $this->honap = $honap;
    }

}