<?php

namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * Egy bizonylat bizonylatstátuszának változását naplózza: ki, mikor, milyen
 * státuszról milyen státuszra állította. A státuszok nevét snapshotként is
 * eltároljuk, így ha a státusz neve később megváltozik, a napló nem változik.
 *
 * @ORM\Entity(repositoryClass="Entities\BizonylatstatusznaploRepository")
 * @ORM\Table(name="bizonylatstatusznaplo",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class Bizonylatstatusznaplo
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
     * Melyik bizonylatnál történt a változás.
     * @ORM\ManyToOne(targetEntity="Bizonylatfej")
     * @ORM\JoinColumn(name="bizonylatfej_id",referencedColumnName="id",nullable=true,onDelete="cascade")
     */
    private $bizonylatfej;

    /**
     * Ki változtatta (bejelentkezett dolgozó, automatikus változásnál null).
     * @ORM\ManyToOne(targetEntity="Dolgozo")
     * @ORM\JoinColumn(name="dolgozo_id",referencedColumnName="id",nullable=true,onDelete="set null")
     */
    private $dolgozo;

    /**
     * A dolgozó nevének pillanatképe (marad akkor is, ha a dolgozó törlődik/átnevezik).
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    private $dolgozonev;

    /**
     * A korábbi (kiinduló) státusz.
     * @ORM\ManyToOne(targetEntity="Bizonylatstatusz")
     * @ORM\JoinColumn(name="registatusz_id",referencedColumnName="id",nullable=true,onDelete="set null")
     */
    private $registatusz;

    /**
     * A korábbi státusz nevének pillanatképe.
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    private $registatusznev;

    /**
     * Az új státusz.
     * @ORM\ManyToOne(targetEntity="Bizonylatstatusz")
     * @ORM\JoinColumn(name="ujstatusz_id",referencedColumnName="id",nullable=true,onDelete="set null")
     */
    private $ujstatusz;

    /**
     * Az új státusz nevének pillanatképe.
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    private $ujstatusznev;

    public function getId()
    {
        return $this->id;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function setCreated($created)
    {
        $this->created = $created;
    }

    public function getCreatedStr()
    {
        if ($this->created) {
            return $this->created->format(\mkw\store::$DateTimeFormat);
        }
        return '';
    }

    /**
     * @return \Entities\Bizonylatfej
     */
    public function getBizonylatfej()
    {
        return $this->bizonylatfej;
    }

    public function getBizonylatfejId()
    {
        if ($this->bizonylatfej) {
            return $this->bizonylatfej->getId();
        }
        return null;
    }

    public function setBizonylatfej($bizonylatfej)
    {
        $this->bizonylatfej = $bizonylatfej;
    }

    /**
     * @return \Entities\Dolgozo
     */
    public function getDolgozo()
    {
        return $this->dolgozo;
    }

    public function getDolgozoId()
    {
        if ($this->dolgozo) {
            return $this->dolgozo->getId();
        }
        return null;
    }

    public function setDolgozo($dolgozo)
    {
        $this->dolgozo = $dolgozo;
        if ($dolgozo) {
            $this->dolgozonev = $dolgozo->getNev();
        }
    }

    public function getDolgozonev()
    {
        return $this->dolgozonev;
    }

    public function setDolgozonev($dolgozonev)
    {
        $this->dolgozonev = $dolgozonev;
    }

    /**
     * @return \Entities\Bizonylatstatusz
     */
    public function getRegistatusz()
    {
        return $this->registatusz;
    }

    public function getRegistatuszId()
    {
        if ($this->registatusz) {
            return $this->registatusz->getId();
        }
        return null;
    }

    public function setRegistatusz($registatusz)
    {
        $this->registatusz = $registatusz;
        if ($registatusz) {
            $this->registatusznev = $registatusz->getNev();
        }
    }

    public function getRegistatusznev()
    {
        return $this->registatusznev;
    }

    public function setRegistatusznev($registatusznev)
    {
        $this->registatusznev = $registatusznev;
    }

    /**
     * @return \Entities\Bizonylatstatusz
     */
    public function getUjstatusz()
    {
        return $this->ujstatusz;
    }

    public function getUjstatuszId()
    {
        if ($this->ujstatusz) {
            return $this->ujstatusz->getId();
        }
        return null;
    }

    public function setUjstatusz($ujstatusz)
    {
        $this->ujstatusz = $ujstatusz;
        if ($ujstatusz) {
            $this->ujstatusznev = $ujstatusz->getNev();
        }
    }

    public function getUjstatusznev()
    {
        return $this->ujstatusznev;
    }

    public function setUjstatusznev($ujstatusznev)
    {
        $this->ujstatusznev = $ujstatusznev;
    }

}
