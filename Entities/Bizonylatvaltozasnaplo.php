<?php

namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * Egy bizonylat pénzügyileg lényeges mezőinek utólagos változását naplózza: ki, mikor, melyik
 * mezőt, miről mire állította. A régi és az új érték szövegként, pillanatképként kerül be, így a
 * törzsadat későbbi átnevezése nem írja át a naplót.
 *
 * A \Entities\Bizonylatstatusznaplo párja; azért külön tábla, mert ott mindkét oldal
 * státuszentitásra hivatkozik, itt viszont tetszőleges mező tetszőleges értéke áll.
 *
 * @ORM\Entity(repositoryClass="Entities\BizonylatvaltozasnaploRepository")
 * @ORM\Table(name="bizonylatvaltozasnaplo",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class Bizonylatvaltozasnaplo
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
     * A megváltozott mező gépi neve (pl. fizmod, penztmozgat, penztar).
     * @ORM\Column(type="string",length=50,nullable=true)
     */
    private $mezo;

    /**
     * A mező emberi neve pillanatképként.
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    private $mezonev;

    /**
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    private $regiertek;

    /**
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    private $ujertek;

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

    public function getMezo()
    {
        return $this->mezo;
    }

    public function setMezo($mezo)
    {
        $this->mezo = $mezo;
    }

    public function getMezonev()
    {
        return $this->mezonev;
    }

    public function setMezonev($mezonev)
    {
        $this->mezonev = $mezonev;
    }

    public function getRegiertek()
    {
        return $this->regiertek;
    }

    public function setRegiertek($regiertek)
    {
        $this->regiertek = $regiertek;
    }

    public function getUjertek()
    {
        return $this->ujertek;
    }

    public function setUjertek($ujertek)
    {
        $this->ujertek = $ujertek;
    }

}
