<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;
use mkw\store;

/**
 * @ORM\Entity(repositoryClass="Entities\PartnerDokRepository")
 */
class PartnerDok extends Dokumentumtar {

    /**
     * @ORM\ManyToOne(targetEntity="partner",inversedBy="partnerdokok")
     * @ORM\JoinColumn(name="partner_id",referencedColumnName="id",onDelete="cascade")
     */
    private $partner;

    public function getPartner() {
        return $this->partner;
    }

    public function getPartnerNev() {
        if ($this->partner) {
            return $this->partner->getNev();
        }
        return '';
    }

    public function setPartner(Partner $r) {
        $this->partner = $r;
    }

    public function removePartner() {
        if ($this->partner !== null) {
            $r = $this->partner;
            $this->partner = null;
            $r->removePartnerDok($this);
        }
    }

}