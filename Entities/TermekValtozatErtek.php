<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\TermekValtozatErtekRepository")
 * @ORM\Table(name="termekvaltozatertek",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class TermekValtozatErtek
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $ertek;

    /**
     * @ORM\ManyToOne(targetEntity="TermekValtozatAdatTipus")
     * @ORM\JoinColumn(name="adattipus_id",referencedColumnName="id",onDelete="restrict")
     */
    private $adattipus;

    /** @ORM\Column(type="integer", nullable=true) */
    private $wcid;

    /** @ORM\Column(type="datetime", nullable=true) */
    private $wcdate;

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
    public function getErtek()
    {
        return $this->ertek;
    }

    /**
     * @param mixed $ertek
     */
    public function setErtek($ertek)
    {
        $this->ertek = $ertek;
    }

    /**
     * @return mixed
     */
    public function getWcid()
    {
        return $this->wcid;
    }

    /**
     * @param mixed $wcid
     */
    public function setWcid($wcid): void
    {
        $this->wcid = $wcid;
    }

    /**
     * @return mixed
     */
    public function getWcdate()
    {
        return $this->wcdate;
    }

    public function getWcdateStr($wcdate)
    {
        return $this->wcdate->format(\mkw\store::$DateTimeFormat);
    }

    /**
     * @param mixed $wcdate
     */
    public function setWcdate($adat = null): void
    {
        if (is_a($adat, 'DateTime')) {
            $this->wcdate = $adat;
        } else {
            if ($adat == '') {
                $adat = date(\mkw\store::$sqlDateTimeFormat);
            }
            $this->wcdate = new \DateTime(\mkw\store::convDate($adat));
        }
    }

    public function getAdatTipus()
    {
        return $this->adattipus;
    }

    public function getAdatTipusId()
    {
        if ($this->adattipus) {
            return $this->adattipus->getId();
        }
        return 0;
    }

    public function getAdatTipusNev()
    {
        if ($this->adattipus) {
            return $this->adattipus->getNev();
        }
        return '';
    }

    public function setAdatTipus($at)
    {
        $this->adattipus = $at;
    }

}