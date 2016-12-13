<?php

namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Doctrine\ORM\Mapping\Entity(repositoryClass="PartnerMIJSZOklevelRepository")
 * @Doctrine\ORM\Mapping\Table(name="partnermijszoklevel",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class PartnerMIJSZOklevel {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Partner",inversedBy="mijszoklevelek")
     * @ORM\JoinColumn(name="partner_id",referencedColumnName="id",onDelete="cascade")
     */
    private $partner;

    /**
     * @ORM\ManyToOne(targetEntity="MIJSZOklevelkibocsajto")
     * @ORM\JoinColumn(name="mijszoklevelkibocsajto_id",referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $mijszoklevelkibocsajto;

    /**
     * @ORM\ManyToOne(targetEntity="MIJSZOklevelszint")
     * @ORM\JoinColumn(name="mijszoklevelszint_id",referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $mijszoklevelszint;

    /** @ORM\Column(type="integer",nullable=true) */
    private $oklevelev = 0;

    public function toLista() {
        $r = array();
        $r['id'] = $this->getId();
        $r['mijszoklevelkibocsajtonev'] = $this->getMIJSZOklevelkibocsajtoNev();
        $r['mijszoklevelszintnev'] = $this->getMIJSZOklevelszintNev();
        $r['partnernev'] = $this->getPartnerNev();
        $r['oklevelev'] = $this->getOklevelev();
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
     * @return \Entities\MIJSZOklevelkibocsajto
     */
    public function getMIJSZOklevelkibocsajto() {
        return $this->mijszoklevelkibocsajto;
    }

    public function getMIJSZOklevelkibocsajtoId() {
        if ($this->getMIJSZOklevelkibocsajto()) {
            return $this->getMIJSZOklevelkibocsajto()->getId();
        }
        return 0;
    }

    public function getMIJSZOklevelkibocsajtoNev() {
        if ($this->getMIJSZOklevelkibocsajto()) {
            return $this->getMIJSZOklevelkibocsajto()->getNev();
        }
        return '';
    }

    /**
     * @param \Entities\MIJSZOklevelkibocsajto $adat
     */
    public function setMIJSZOklevelkibocsajto($adat) {
        $this->mijszoklevelkibocsajto = $adat;
    }

    /**
     * @return \Entities\MIJSZOklevelszint
     */
    public function getMIJSZOklevelszint() {
        return $this->mijszoklevelszint;
    }

    public function getMIJSZOklevelszintId() {
        if ($this->getMIJSZOklevelszint()) {
            return $this->getMIJSZOklevelszint()->getId();
        }
        return 0;
    }

    public function getMIJSZOklevelszintNev() {
        if ($this->getMIJSZOklevelszint()) {
            return $this->getMIJSZOklevelszint()->getNev();
        }
        return '';
    }

    /**
     * @param \Entities\MIJSZOklevelszint $adat
     */
    public function setMIJSZOklevelszint($adat) {
        $this->mijszoklevelszint = $adat;
    }

    /**
     * @return mixed
     */
    public function getOklevelev() {
        return $this->oklevelev;
    }

    /**
     * @param mixed $oklevelev
     */
    public function setOklevelev($oklevelev) {
        $this->oklevelev = $oklevelev;
    }

}