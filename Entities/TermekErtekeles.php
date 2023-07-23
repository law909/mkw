<?php

namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\TermekErtekelesRepository")
 * @ORM\Table(name="termekertekeles",
 * options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 * @ORM\HasLifecycleCallbacks
 */
class TermekErtekeles
{

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

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $lastmod;

    /** @ORM\Column(type="text",nullable=true) */
    private $szoveg = '';
    /** @ORM\Column(type="text",nullable=true) */
    private $elony = '';
    /** @ORM\Column(type="text",nullable=true) */
    private $hatrany = '';
    /** @ORM\Column(type="text",nullable=true) */
    private $valasz = '';
    /** @ORM\Column(type="integer",nullable=true) */
    private $ertekeles = 0;

    /**
     * @ORM\ManyToOne(targetEntity="Termek",inversedBy="termekertekelesek")
     * @ORM\JoinColumn(name="termek_id",referencedColumnName="id",onDelete="cascade")
     */
    private $termek;

    /**
     * @ORM\ManyToOne(targetEntity="Partner")
     * @ORM\JoinColumn(name="partner_id", referencedColumnName="id",nullable=true,onDelete="cascade")
     */
    private $partner;
    /** @ORM\Column(type="boolean",nullable=true) */
    private $elutasitva = false;
    /** @ORM\Column(type="boolean",nullable=true) */
    private $anonim = false;

    public function toLista()
    {
        return [
            'id' => $this->getId(),
            'termeknev' => $this->getTermekNev(),
            'termekid' => $this->getTermekId(),
            'termekslug' => $this->getTermekSlug(),
            'termekminikepurl' => $this->getTermek()?->getKepurlMini(),
            'termekkiskepurl' => $this->getTermek()?->getKepurlSmall(),
            'termeklink' => \mkw\store::getRouter()->generate('showtermek', false, ['slug' => $this->getTermekSlug()]),
            'partnernev' => $this->isAnonim() ? $this->getPartner()?->getMonogram() : $this->getPartnerNev(),
            'partnerid' => $this->getPartnerId(),
            'ertekeles' => $this->getErtekeles(),
            'elony' => $this->getElony(),
            'hatrany' => $this->getHatrany(),
            'szoveg' => $this->getSzoveg(),
            'datum' => $this->getCreatedDateStr()
        ];
    }

    public function getCreatedDateStr()
    {
        if ($this->getCreated()) {
            return $this->getCreated()->format(\mkw\store::$DateFormat);
        }
        return '';
    }

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
        if ($this->getCreated()) {
            return $this->getCreated()->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    public function getLastmod()
    {
        return $this->lastmod;
    }

    public function getLastmodStr()
    {
        if ($this->getLastmod()) {
            return $this->getLastmod()->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    public function getPartner()
    {
        return $this->partner;
    }

    public function getPartnerId()
    {
        if ($this->partner) {
            return $this->partner->getId();
        }
        return '';
    }

    public function getPartnerNev()
    {
        if ($this->partner) {
            return $this->partner->getNev();
        }
        return '';
    }

    public function setPartner(Partner $val)
    {
        if ($this->partner !== $val) {
            $this->partner = $val;
        }
    }

    public function removePartner()
    {
        if ($this->partner !== null) {
            $this->partner = null;
        }
    }

    /**
     * @return Termek
     */
    public function getTermek()
    {
        return $this->termek;
    }

    public function getTermekId()
    {
        if ($this->termek) {
            return $this->termek->getId();
        }
        return '';
    }

    public function getTermekNev()
    {
        if ($this->termek) {
            return $this->termek->getNev();
        }
        return '';
    }

    public function getTermekSlug()
    {
        if ($this->termek) {
            return $this->termek->getSlug();
        }
        return '';
    }

    public function setTermek(Termek $val)
    {
        if ($this->termek !== $val) {
            $this->termek = $val;
        }
    }

    public function removeTermek()
    {
        if ($this->termek !== null) {
            $this->termek = null;
        }
    }

    /**
     * @return string
     */
    public function getSzoveg()
    {
        return $this->szoveg;
    }

    /**
     * @param string $szoveg
     */
    public function setSzoveg($szoveg): void
    {
        $this->szoveg = $szoveg;
    }

    /**
     * @return string
     */
    public function getElony()
    {
        return $this->elony;
    }

    /**
     * @param string $elony
     */
    public function setElony($elony): void
    {
        $this->elony = $elony;
    }

    /**
     * @return string
     */
    public function getHatrany()
    {
        return $this->hatrany;
    }

    /**
     * @param string $hatrany
     */
    public function setHatrany($hatrany): void
    {
        $this->hatrany = $hatrany;
    }

    /**
     * @return string
     */
    public function getValasz()
    {
        return $this->valasz;
    }

    /**
     * @param string $valasz
     */
    public function setValasz($valasz): void
    {
        $this->valasz = $valasz;
    }

    /**
     * @return int
     */
    public function getErtekeles()
    {
        return $this->ertekeles;
    }

    /**
     * @param int $ertekeles
     */
    public function setErtekeles($ertekeles): void
    {
        $this->ertekeles = $ertekeles;
    }

    /**
     * @return bool
     */
    public function isElutasitva()
    {
        return $this->elutasitva;
    }

    /**
     * @param bool $elutasitva
     */
    public function setElutasitva($elutasitva): void
    {
        $this->elutasitva = $elutasitva;
    }

    /**
     * @return bool
     */
    public function isAnonim()
    {
        return $this->anonim;
    }

    /**
     * @param bool $anonim
     */
    public function setAnonim($anonim): void
    {
        $this->anonim = $anonim;
    }

}
