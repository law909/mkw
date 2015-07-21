<?php

namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\TermekErtesitoRepository")
 * @ORM\Table(name="termekertesito",indexes={
 * 		@ORM\index(name="termekertesitoemail_idx",columns={"email","termek_id"}),
 * 		@ORM\index(name="termekertesitotermek_idx",columns={"termek_id","email"}),
 * 		@ORM\index(name="termekertesitopartner_idx",columns={"partner_id","termek_id"})
 * })
 * @ORM\HasLifecycleCallbacks
 */
class TermekErtesito {

    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id = 0;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $created;

    /** @ORM\Column(type="string",length=100,nullable=true) */
    private $email = '';

    /**
     * @ORM\ManyToOne(targetEntity="Termek",inversedBy="termekertesitok")
     * @ORM\JoinColumn(name="termek_id",referencedColumnName="id",onDelete="cascade")
     */
    private $termek;

    /**
     * @ORM\ManyToOne(targetEntity="Partner",inversedBy="termekertesitok")
     * @ORM\JoinColumn(name="partner_id", referencedColumnName="id",nullable=true,onDelete="cascade")
     */
    private $partner;

    /** @ORM\Column(type="datetime",nullable=true) */
    private $sent;

    public function getId() {
        return $this->id;
    }

    public function getCreated() {
        return $this->created;
    }

    public function getCreatedStr() {
        if ($this->getCreated()) {
            return $this->getCreated()->format(\mkw\Store::$DateFormat);
        }
        return '';
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getPartner() {
        return $this->partner;
    }

    public function getPartnerId() {
        if ($this->partner) {
            return $this->partner->getId();
        }
        return '';
    }

    public function getPartnerNev() {
        if ($this->partner) {
            return $this->partner->getNev();
        }
        return '';
    }

    public function setPartner(Partner $val) {
        if ($this->partner !== $val) {
            $this->partner = $val;
//			$val->addBizonylatfej($this);
        }
    }

    public function removePartner() {
        if ($this->partner !== null) {
//			$val=$this->partner;
            $this->partner = null;
//			$val->removeBizonylatfej($this);
        }
    }

    public function getTermek() {
        return $this->termek;
    }

    public function getTermekId() {
        if ($this->termek) {
            return $this->termek->getId();
        }
        return '';
    }

    public function getTermekNev() {
        if ($this->termek) {
            return $this->termek->getNev();
        }
        return '';
    }

    public function setTermek(Termek $val) {
        if ($this->termek !== $val) {
            $this->termek = $val;
//			$val->addBizonylattetelek($this);
        }
    }

    public function removeTermek() {
        if ($this->termek !== null) {
//			$val=$this->termek;
            $this->termek = null;
//			$val->removeBizonylattetelek($this);
        }
    }

    public function getSent() {
        return $this->sent;
    }

    public function getSentStr() {
        if ($this->getSent()) {
            return $this->getSent()->format(\mkw\Store::$DateFormat);
        }
        return '';
    }

    public function setSent($adat) {
        if ($adat == '') {
            $adat = date(\mkw\Store::$DateFormat);
        }
        $this->sent = new \DateTime(\mkw\Store::convDate($adat));
    }

}
