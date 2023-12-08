<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="Entities\BankTranzakcioRepository")
 * @ORM\Table(name="banktranzakcio",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class BankTranzakcio
{
    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
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

    /** @ORM\Column(type="string",length=255) */
    private $azonosito;

    /** @ORM\Column(type="date",nullable=true) */
    private $konyvelesdatum;

    /** @ORM\Column(type="date",nullable=true) */
    private $erteknap;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $kozlemeny1;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $kozlemeny2;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $kozlemeny3;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $osszeg;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $bizonylatszamok;

    /**
     * @ORM\ManyToOne(targetEntity="Partner")
     * @ORM\JoinColumn(name="partner_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Partner
     */
    private $partner;

    /** @ORM\Column(type="boolean") */
    private $bankbizonylatkesz = false;

    /** @ORM\Column(type="boolean") */
    private $inaktiv = false;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getAzonosito()
    {
        return $this->azonosito;
    }

    /**
     * @param mixed $azonosito
     */
    public function setAzonosito($azonosito): void
    {
        $this->azonosito = $azonosito;
    }

    /**
     * @return mixed
     */
    public function getKonyvelesdatum()
    {
        return $this->konyvelesdatum;
    }

    public function getKonyvelesdatumStr()
    {
        if ($this->getKonyvelesdatum()) {
            return $this->getKonyvelesdatum()->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    /**
     * @param mixed $adat
     */
    public function setKonyvelesdatum($adat): void
    {
        if (is_a($adat, 'DateTime')) {
            $this->konyvelesdatum = $adat;
        } else {
            if ($adat == '') {
                $adat = date(\mkw\store::$DateFormat);
            }
            $this->konyvelesdatum = new \DateTime(\mkw\store::convDate($adat));
        }
    }

    /**
     * @return mixed
     */
    public function getErteknap()
    {
        return $this->erteknap;
    }

    public function getErteknapStr()
    {
        if ($this->getErteknap()) {
            return $this->getErteknap()->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    /**
     * @param mixed $erteknap
     */
    public function setErteknap($erteknap): void
    {
        if (is_a($erteknap, 'DateTime')) {
            $this->erteknap = $erteknap;
        } else {
            if ($erteknap == '') {
                $erteknap = date(\mkw\store::$DateFormat);
            }
            $this->erteknap = new \DateTime(\mkw\store::convDate($erteknap));
        }
    }

    /**
     * @return mixed
     */
    public function getKozlemeny1()
    {
        return $this->kozlemeny1;
    }

    /**
     * @param mixed $kozlemeny1
     */
    public function setKozlemeny1($kozlemeny1): void
    {
        $this->kozlemeny1 = $kozlemeny1;
    }

    /**
     * @return mixed
     */
    public function getKozlemeny2()
    {
        return $this->kozlemeny2;
    }

    /**
     * @param mixed $kozlemeny2
     */
    public function setKozlemeny2($kozlemeny2): void
    {
        $this->kozlemeny2 = $kozlemeny2;
    }

    /**
     * @return mixed
     */
    public function getKozlemeny3()
    {
        return $this->kozlemeny3;
    }

    /**
     * @param mixed $kozlemeny3
     */
    public function setKozlemeny3($kozlemeny3): void
    {
        $this->kozlemeny3 = $kozlemeny3;
    }

    /**
     * @return mixed
     */
    public function getOsszeg()
    {
        return $this->osszeg;
    }

    /**
     * @param mixed $osszeg
     */
    public function setOsszeg($osszeg): void
    {
        $this->osszeg = $osszeg;
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

    /**
     * @param \Entities\Partner $val
     */
    public function setPartner($val)
    {
        if ($this->partner !== $val) {
            if (!$val) {
                $this->removePartner();
            } else {
                $this->partner = $val;
            }
        }
    }

    public function removePartner()
    {
        if ($this->partner !== null) {
            $this->partner = null;
        }
    }

    public function getPartnernev()
    {
        return $this->partner?->getNev();
    }

    /**
     * @return mixed
     */
    public function getBizonylatszamok()
    {
        return $this->bizonylatszamok;
    }

    /**
     * @param mixed $bizonylatszamok
     */
    public function setBizonylatszamok($bizonylatszamok): void
    {
        $this->bizonylatszamok = $bizonylatszamok;
    }

    /**
     * @return bool
     */
    public function isBankbizonylatkesz()
    {
        return $this->bankbizonylatkesz;
    }

    /**
     * @param bool $bankbizonylatkesz
     */
    public function setBankbizonylatkesz($bankbizonylatkesz): void
    {
        $this->bankbizonylatkesz = $bankbizonylatkesz;
    }

    /**
     * @return bool
     */
    public function isInaktiv()
    {
        return $this->inaktiv;
    }

    /**
     * @param bool $inaktiv
     */
    public function setInaktiv($inaktiv): void
    {
        $this->inaktiv = $inaktiv;
    }

}