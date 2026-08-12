<?php

namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * Egy bizonylat élettörténete: mikor, ki, mit csinált vele. Egyetlen tábla mindenre –
 * létrehozás, mentés, nyomtatás, és a pénzügyileg lényeges mezők (fizetési mód, pénzmozgás
 * jelölő, pénztár, státusz) változása.
 *
 * Az esemény kulcsa (`esemeny`) gépi, a neve (`esemenynev`) és az értékek pillanatképként
 * tárolódnak: a törzsadat későbbi átnevezése nem írja át a naplót. Mezőváltozásnál a régi és
 * az új érték is kitöltött, sima eseménynél (mentés, nyomtatás) üresek.
 *
 * Ez váltja ki a korábbi Bizonylatstatusznaplo + Bizonylatvaltozasnaplo párost.
 *
 * @ORM\Entity(repositoryClass="Entities\BizonylatnaploRepository")
 * @ORM\Table(name="bizonylatnaplo",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"},
 *  indexes={@ORM\Index(name="bizonylatnaplo_bizonylat_idx",columns={"bizonylatfej_id","created"})})
 */
class Bizonylatnaplo
{
    /** a bizonylat létrejött */
    public const ESEMENY_LETREHOZAS = 'letrehozas';
    /** a felhasználó mentette a bizonylatot (akkor is, ha nem változott rajta semmi) */
    public const ESEMENY_MENTES = 'mentes';
    /** valamelyik naplózott mező megváltozott – ilyenkor a mezo/regiertek/ujertek is kitöltött */
    public const ESEMENY_MEZOVALTOZAS = 'mezovaltozas';

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
     * Melyik bizonylatnál történt.
     * @ORM\ManyToOne(targetEntity="Bizonylatfej")
     * @ORM\JoinColumn(name="bizonylatfej_id",referencedColumnName="id",nullable=true,onDelete="cascade")
     */
    private $bizonylatfej;

    /**
     * Ki csinálta (bejelentkezett dolgozó, automatikus eseménynél null).
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
     * Az esemény gépi kulcsa (lásd az ESEMENY_* konstansokat).
     * @ORM\Column(type="string",length=30,nullable=true)
     */
    private $esemeny;

    /**
     * Az esemény emberi neve pillanatképként – mezőváltozásnál a mező neve.
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    private $esemenynev;

    /**
     * Mezőváltozásnál a megváltozott mező gépi neve (pl. fizmod, penztmozgat, nyomtatva).
     * @ORM\Column(type="string",length=50,nullable=true)
     */
    private $mezo;

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

    public function getEsemeny()
    {
        return $this->esemeny;
    }

    public function setEsemeny($esemeny)
    {
        $this->esemeny = $esemeny;
    }

    public function getEsemenynev()
    {
        return $this->esemenynev;
    }

    public function setEsemenynev($esemenynev)
    {
        $this->esemenynev = $esemenynev;
    }

    public function getMezo()
    {
        return $this->mezo;
    }

    public function setMezo($mezo)
    {
        $this->mezo = $mezo;
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
