<?php

namespace Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="Entities\IdopontRepository")
 * @ORM\Table(name="idopont",
 * options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"},
 * indexes={
 *      @ORM\index(name="idopontkezdet_idx",columns={"kezdet"})
 * })
 */
class Idopont
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

    /**
     * @ORM\ManyToOne(targetEntity="Dolgozo", fetch = "EAGER")
     * @ORM\JoinColumn(name="dolgozo_id",referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $dolgozo;

    /**
     * @ORM\ManyToOne(targetEntity="Idoponttema")
     * @ORM\JoinColumn(name="idoponttema_id",referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $idoponttema;

    /**
     * @ORM\ManyToOne(targetEntity="Jogahelyszin")
     * @ORM\JoinColumn(name="jogahelyszin_id",referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $jogahelyszin;

    /** @ORM\Column(type="boolean", nullable=false) */
    private $onlinevalaszthato = false;

    /** @ORM\Column(type="boolean", nullable=false) */
    private $ismetlodo = false;

    /** Csak ismétlődőnél: a hét napja (1 = hétfő), az órarendével azonos számozással. */
    /** @ORM\Column(type="integer",nullable=false) */
    private $nap = 0;

    /** @ORM\Column(type="time",nullable=true) */
    private $kezdetido;

    /** @ORM\Column(type="time",nullable=true) */
    private $vegido;

    /** Csak egyszerinél. */
    /** @ORM\Column(type="datetime",nullable=true) */
    private $kezdet;

    /** @ORM\Column(type="datetime",nullable=true) */
    private $veg;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $ar = 0;

    /** @ORM\Column(type="integer",nullable=false,options={"default":1}) */
    private $maxresztvevo = 1;

    /** @ORM\Column(type="boolean", nullable=false) */
    private $inaktiv = false;

    /** @ORM\OneToMany(targetEntity="Idopontfoglalas", mappedBy="idopont",cascade={"persist"}) */
    private $foglalasok;

    public function __construct()
    {
        $this->foglalasok = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getFoglalasok()
    {
        return $this->foglalasok;
    }

    /**
     * @return Dolgozo
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
        return '';
    }

    public function getDolgozoNev()
    {
        if ($this->dolgozo) {
            return $this->dolgozo->getNev();
        }
        return '';
    }

    public function getDolgozoUrl()
    {
        if ($this->dolgozo) {
            return $this->dolgozo->getUrl();
        }
        return '';
    }

    public function getDolgozoEmail()
    {
        if ($this->dolgozo) {
            return $this->dolgozo->getEmail();
        }
        return '';
    }

    public function setDolgozo($dolgozo)
    {
        $this->dolgozo = $dolgozo;
    }

    /**
     * @return Idoponttema
     */
    public function getIdoponttema()
    {
        return $this->idoponttema;
    }

    public function getIdoponttemaId()
    {
        if ($this->idoponttema) {
            return $this->idoponttema->getId();
        }
        return '';
    }

    public function getIdoponttemaNev()
    {
        if ($this->idoponttema) {
            return $this->idoponttema->getNev();
        }
        return '';
    }

    public function getIdoponttemaUrl()
    {
        if ($this->idoponttema) {
            return $this->idoponttema->getUrl();
        }
        return '';
    }

    public function setIdoponttema($idoponttema)
    {
        $this->idoponttema = $idoponttema;
    }

    /**
     * @return Jogahelyszin
     */
    public function getJogahelyszin()
    {
        return $this->jogahelyszin;
    }

    public function getJogahelyszinId()
    {
        if ($this->jogahelyszin) {
            return $this->jogahelyszin->getId();
        }
        return '';
    }

    public function getJogahelyszinNev()
    {
        if ($this->jogahelyszin) {
            return $this->jogahelyszin->getNev();
        }
        return '';
    }

    public function getJogahelyszinCim()
    {
        if ($this->jogahelyszin) {
            return $this->jogahelyszin->getFullAddress();
        }
        return '';
    }

    public function setJogahelyszin($jogahelyszin)
    {
        $this->jogahelyszin = $jogahelyszin;
    }

    public function isOnlinevalaszthato()
    {
        return $this->onlinevalaszthato;
    }

    public function setOnlinevalaszthato($onlinevalaszthato)
    {
        $this->onlinevalaszthato = $onlinevalaszthato;
    }

    public function isIsmetlodo()
    {
        return $this->ismetlodo;
    }

    public function setIsmetlodo($ismetlodo)
    {
        $this->ismetlodo = $ismetlodo;
    }

    public function getNap()
    {
        return $this->nap;
    }

    public function setNap($nap)
    {
        $this->nap = $nap;
    }

    public function getKezdetido()
    {
        return $this->kezdetido;
    }

    public function getKezdetidoStr()
    {
        if ($this->kezdetido) {
            return $this->kezdetido->format(\mkw\store::$TimeFormat);
        }
        return '';
    }

    public function setKezdetido($kezdetido)
    {
        $this->kezdetido = self::toTime($kezdetido);
    }

    public function getVegido()
    {
        return $this->vegido;
    }

    public function getVegidoStr()
    {
        if ($this->vegido) {
            return $this->vegido->format(\mkw\store::$TimeFormat);
        }
        return '';
    }

    public function setVegido($vegido)
    {
        $this->vegido = self::toTime($vegido);
    }

    public function getKezdet()
    {
        return $this->kezdet;
    }

    public function getKezdetStr()
    {
        if ($this->kezdet) {
            return $this->kezdet->format(\mkw\store::$DateTimeFormat);
        }
        return '';
    }

    /**
     * A datetime-local input value formátuma – nem a felhasználónak szóló megjelenítés.
     */
    public function getKezdetInputStr()
    {
        if ($this->kezdet) {
            return $this->kezdet->format('Y-m-d\TH:i');
        }
        return '';
    }

    public function setKezdet($kezdet)
    {
        $this->kezdet = self::toDateTime($kezdet);
    }

    public function getVeg()
    {
        return $this->veg;
    }

    public function getVegStr()
    {
        if ($this->veg) {
            return $this->veg->format(\mkw\store::$DateTimeFormat);
        }
        return '';
    }

    public function getVegInputStr()
    {
        if ($this->veg) {
            return $this->veg->format('Y-m-d\TH:i');
        }
        return '';
    }

    public function setVeg($veg)
    {
        $this->veg = self::toDateTime($veg);
    }

    public function getAr()
    {
        return $this->ar;
    }

    public function setAr($ar)
    {
        $this->ar = $ar;
    }

    public function getMaxresztvevo()
    {
        return $this->maxresztvevo;
    }

    public function setMaxresztvevo($maxresztvevo)
    {
        $this->maxresztvevo = $maxresztvevo;
    }

    public function getInaktiv()
    {
        return $this->inaktiv;
    }

    public function setInaktiv($inaktiv)
    {
        $this->inaktiv = $inaktiv;
    }

    /**
     * Egyszeri időpont naptári napja; ismétlődőnél nincs ilyen (az alkalom napját a hívó adja meg).
     */
    public function getDatumStr()
    {
        if (!$this->ismetlodo && $this->kezdet) {
            return $this->kezdet->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    public function getNapNev()
    {
        if ($this->ismetlodo) {
            return $this->nap ? \mkw\store::getDayname($this->nap) : '';
        }
        if ($this->kezdet) {
            return \mkw\store::getDayname($this->kezdet->format('N'));
        }
        return '';
    }

    /**
     * @return \DateTime|null a kezdés ideje – ismétlődőnél a kezdetido, egyszerinél a kezdet időrésze
     */
    public function getStartTime()
    {
        return $this->ismetlodo ? $this->kezdetido : $this->kezdet;
    }

    public function getEndTime()
    {
        return $this->ismetlodo ? $this->vegido : $this->veg;
    }

    public function getStartTimeStr()
    {
        $t = $this->getStartTime();
        return $t ? $t->format(\mkw\store::$TimeFormat) : '';
    }

    public function getEndTimeStr()
    {
        $t = $this->getEndTime();
        return $t ? $t->format(\mkw\store::$TimeFormat) : '';
    }

    public function getIdotartamStr()
    {
        return trim($this->getStartTimeStr() . '-' . $this->getEndTimeStr(), '-');
    }

    public function isDelelottKezdodik()
    {
        $t = $this->getStartTime();
        return $t && $t->format(\mkw\store::$FullTimeFormat) <= '12:00:00';
    }

    /**
     * Melyik napra esik ez az időpont a megadott héten? Ismétlődőnél a hét `nap`-odik napja,
     * egyszerinél a saját dátuma – de csak ha a hétbe esik.
     *
     * @return \DateTime|null
     */
    public function getOccurrenceDate(\DateTime $hetkezdet)
    {
        if ($this->ismetlodo) {
            if (!$this->nap || !$this->kezdetido) {
                return null;
            }
            $d = clone $hetkezdet;
            return $d->add(new \DateInterval('P' . ($this->nap - 1) . 'D'));
        }
        if (!$this->kezdet) {
            return null;
        }
        $het = clone $hetkezdet;
        $hetvege = (clone $hetkezdet)->add(new \DateInterval('P6D'));
        $datum = new \DateTime($this->kezdet->format(\mkw\store::$SQLDateFormat));
        if ($datum < $het || $datum > $hetvege) {
            return null;
        }
        return $datum;
    }

    /**
     * Foglalható-e ez a nap ezen az időponton – ismétlődőnél a hét napjának egyeznie kell,
     * egyszerinél a saját dátumának.
     */
    public function isValidOccurrenceDate($datum)
    {
        if (!$datum instanceof \DateTime) {
            return false;
        }
        if ($this->ismetlodo) {
            return (int)$datum->format('N') === (int)$this->nap;
        }
        return $this->kezdet
            && $datum->format(\mkw\store::$SQLDateFormat) === $this->kezdet->format(\mkw\store::$SQLDateFormat);
    }

    /**
     * A megjelenítő és a foglalás mentése is ezt kérdezi, ezért nem a betöltött kollekcióból
     * számol: a foglalások lapozott listán is helyesek maradjanak. A foglalás mindig egy konkrét
     * naptári napra szól, ezért az ismétlődő időpontnál naponként külön telik be a hely.
     */
    public function getBookedCount($datum = null)
    {
        return (int)\mkw\store::getEm()->getRepository(Idopontfoglalas::class)
            ->getCountForIdopont($this->id, $datum ?: $this->kezdet);
    }

    public function getFreePlaces($datum = null)
    {
        return $this->maxresztvevo - $this->getBookedCount($datum);
    }

    public function isBookable($datum = null)
    {
        return !$this->inaktiv && $this->getFreePlaces($datum) > 0;
    }

    /**
     * Elfogadja a datetime-local (2026-08-20T10:00) és a magyar (2026.08.20 10:00) alakot is.
     */
    private static function toDateTime($value)
    {
        if ($value instanceof \DateTime) {
            return $value;
        }
        $value = trim((string)$value);
        if ($value === '') {
            return null;
        }
        $value = preg_replace('/^(\d{4})\.(\d{1,2})\.(\d{1,2})\.?/', '$1-$2-$3', $value);
        return new \DateTime($value);
    }

    private static function toTime($value)
    {
        if ($value instanceof \DateTime) {
            return $value;
        }
        $value = trim((string)$value);
        if ($value === '') {
            return null;
        }
        return new \DateTime(\mkw\store::convTime($value));
    }

}
