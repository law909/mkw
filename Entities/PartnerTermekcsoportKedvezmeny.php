<?php

namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Doctrine\ORM\Mapping\Entity(repositoryClass="PartnerTermekcsoportKedvezmenyRepository")
 * @Doctrine\ORM\Mapping\Table(name="partnertermekcsoportkedvezmeny")
 */
class PartnerTermekcsoportKedvezmeny {

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
     * @Gedmo\Timestampable(on="create")
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
     * @ORM\ManyToOne(targetEntity="Termekcsoport")
     * @ORM\JoinColumn(name="termekcsoport_id",referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $termekcsoport;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $kedvezmeny = 0;

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

    /**
     * @param \Entities\Partner $partner
     */
    public function setPartner($partner) {
        $this->partner = $partner;
    }

    /**
     * @return \Entities\Termekcsoport
     */
    public function getTermekcsoport() {
        return $this->termekcsoport;
    }

    public function getTermekcsoportId() {
        if ($this->getTermekcsoport()) {
            return $this->getTermekcsoport()->getId();
        }
        return 0;
    }

    public function getTermekcsoportNev() {
        if ($this->getTermekcsoport()) {
            return $this->getTermekcsoport()->getNev();
        }
        return '';
    }

    /**
     * @param \Entities\Termekcsoport $termekcsoport
     */
    public function setTermekcsoport($termekcsoport) {
        $this->termekcsoport = $termekcsoport;
    }

}