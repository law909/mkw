<?php

namespace Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Meghirdetett alkalom: a korábbi rendezvény és időpont modul egyesítve. A `tipus` csak azt mondja
 * meg, melyik felületen jelenik meg (órarend export és regisztrációs űrlap, vagy a heti foglalónézet),
 * az adatmodell közös.
 *
 * @ORM\Entity(repositoryClass="Entities\IdopontRepository")
 * @ORM\Table(name="idopont",
 * options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"},
 * indexes={
 *      @ORM\index(name="idopontkezdet_idx",columns={"kezdet"})
 * })
 */
class Idopont
{

    const TIPUS_RENDEZVENY = 'rendezveny';
    const TIPUS_IDOPONT = 'idopont';

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
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="Dolgozo")
     * @ORM\JoinColumn(name="createdby", referencedColumnName="id")
     */
    private $createdby;

    /**
     * @Gedmo\Blameable(on="update")
     * @ORM\ManyToOne(targetEntity="Dolgozo")
     * @ORM\JoinColumn(name="updatedby", referencedColumnName="id")
     */
    private $updatedby;

    /** @ORM\Column(type="string",length=20,nullable=false,options={"default":"idopont"}) */
    private $tipus = self::TIPUS_IDOPONT;

    /** @ORM\Column(type="string",length=255,nullable=false) */
    private $nev = '';

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
     * @ORM\ManyToOne(targetEntity="Idopontallapot")
     * @ORM\JoinColumn(name="idopontallapot_id",referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $idopontallapot;

    /**
     * @ORM\ManyToOne(targetEntity="Jogahelyszin")
     * @ORM\JoinColumn(name="jogahelyszin_id",referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $jogahelyszin;

    /**
     * @ORM\ManyToOne(targetEntity="Termek")
     * @ORM\JoinColumn(name="termek_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $termek;

    /** A publikus regisztrációs link tokenje – ügyféloldali beágyazásokban él, nem képezhető újra. */
    /** @ORM\Column(type="string", length=23, nullable=false) */
    private $uid = '';

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $url;

    /** @ORM\Column(type="string",length=255,nullable=false) */
    private $onlineurl = '';

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

    /** @ORM\Column(type="date",nullable=true) */
    private $earlybirdvege;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $earlybirdar;

    /** 0 vagy NULL = nincs létszámkorlát (a rendezvény oldali maxferohely jelentése). */
    /** @ORM\Column(type="integer",nullable=true) */
    private $maxresztvevo = 0;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $varolistavan = true;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $csomag = false;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $kellszamlazasiadat = true;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $orarendbenszerepel = true;

    /** @ORM\Column(type="boolean", nullable=false) */
    private $inaktiv = false;

    /** @ORM\OneToMany(targetEntity="IdopontDok", mappedBy="idopont", cascade={"persist", "remove"}) */
    private $idopontdokok;

    /** @ORM\OneToMany(targetEntity="Idopontfoglalas", mappedBy="idopont",cascade={"persist"}) */
    private $foglalasok;

    public function __construct()
    {
        $this->foglalasok = new ArrayCollection();
        $this->idopontdokok = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getFoglalasok()
    {
        return $this->foglalasok;
    }

    public function generateUId()
    {
        $this->uid = uniqid('', true);
        return $this->uid;
    }

    public function getUid()
    {
        return $this->uid;
    }

    public function getTipus()
    {
        return $this->tipus;
    }

    public function setTipus($tipus)
    {
        $this->tipus = ($tipus === self::TIPUS_RENDEZVENY) ? self::TIPUS_RENDEZVENY : self::TIPUS_IDOPONT;
    }

    public function isRendezveny()
    {
        return $this->tipus === self::TIPUS_RENDEZVENY;
    }

    public function getNev()
    {
        return $this->nev;
    }

    public function setNev($nev)
    {
        $this->nev = (string)$nev;
    }

    /**
     * A bizonylattételek szövege és a levelek megszólítása is ezt használja, ezért időpontnál
     * (ahol nincs saját név) a témára esik vissza.
     */
    public function getTeljesNev()
    {
        $r = $this->nev !== '' ? $this->nev : $this->getIdoponttemaNev();
        if ($this->csomag) {
            return $r;
        }
        if ($this->getDatumStr()) {
            $r .= ' ' . $this->getDatumStr();
        } elseif ($this->getNapNev()) {
            $r .= ' ' . $this->getNapNev();
        }
        if ($this->getStartTimeStr()) {
            $r .= ' ' . $this->getStartTimeStr();
        }
        if ($this->getDolgozoNev()) {
            $r .= ' (' . $this->getDolgozoNev() . ')';
        }
        return $r;
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
     * @return Idopontallapot
     */
    public function getIdopontallapot()
    {
        return $this->idopontallapot;
    }

    public function getIdopontallapotNev()
    {
        if ($this->idopontallapot) {
            return $this->idopontallapot->getNev();
        }
        return '';
    }

    public function setIdopontallapot($idopontallapot)
    {
        $this->idopontallapot = $idopontallapot;
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

    public function getTermek()
    {
        return $this->termek;
    }

    public function getTermekNev()
    {
        if ($this->termek) {
            return $this->termek->getNev();
        }
        return '';
    }

    public function setTermek($val)
    {
        if ($val) {
            $this->termek = $val;
        } else {
            $this->termek = null;
        }
    }

    public function removeTermek()
    {
        $this->termek = null;
    }

    public function getCreatedby()
    {
        return $this->createdby;
    }

    public function getCreatedbyId()
    {
        if ($this->createdby) {
            return $this->createdby->getId();
        }
        return '';
    }

    public function getCreatedbyNev()
    {
        if ($this->createdby) {
            return $this->createdby->getNev();
        }
        return '';
    }

    public function getUpdatedby()
    {
        return $this->updatedby;
    }

    public function getUpdatedbyId()
    {
        if ($this->updatedby) {
            return $this->updatedby->getId();
        }
        return '';
    }

    public function getUpdatedbyNev()
    {
        if ($this->updatedby) {
            return $this->updatedby->getNev();
        }
        return '';
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getCreatedStr()
    {
        if ($this->created) {
            return $this->created->format(\mkw\store::$DateTimeFormat);
        }
        return '';
    }

    public function clearCreated()
    {
        $this->created = null;
    }

    public function getLastmod()
    {
        return $this->lastmod;
    }

    public function getLastmodStr()
    {
        if ($this->lastmod) {
            return $this->lastmod->format(\mkw\store::$DateTimeFormat);
        }
        return '';
    }

    public function clearLastmod()
    {
        $this->lastmod = null;
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
        if (!$this->ismetlodo) {
            return $this->kezdet ? (int)$this->kezdet->format('N') : 0;
        }
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

    public function getEarlybirdvege()
    {
        return $this->earlybirdvege;
    }

    public function getEarlybirdvegeStr()
    {
        if ($this->earlybirdvege) {
            return $this->earlybirdvege->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    public function setEarlybirdvege($adat = '')
    {
        if ($adat != '') {
            $this->earlybirdvege = new \DateTime(\mkw\store::convDate($adat));
        } else {
            $this->earlybirdvege = null;
        }
    }

    public function getEarlybirdar()
    {
        return $this->earlybirdar;
    }

    public function setEarlybirdar($earlybirdar): void
    {
        $this->earlybirdar = $earlybirdar;
    }

    public function getMaxresztvevo()
    {
        return $this->maxresztvevo;
    }

    public function setMaxresztvevo($maxresztvevo)
    {
        $this->maxresztvevo = $maxresztvevo;
    }

    /**
     * Van-e egyáltalán létszámkorlát: a rendezvényeknél a 0 azt jelenti, hogy nincs.
     */
    public function hasLetszamkorlat()
    {
        return (int)$this->maxresztvevo > 0;
    }

    public function isVarolistavan()
    {
        return $this->varolistavan;
    }

    public function setVarolistavan($varolistavan): void
    {
        $this->varolistavan = $varolistavan;
    }

    public function isCsomag()
    {
        return $this->csomag;
    }

    public function setCsomag($csomag): void
    {
        $this->csomag = $csomag;
    }

    public function getKellszamlazasiadat()
    {
        return $this->kellszamlazasiadat;
    }

    public function setKellszamlazasiadat($kellszamlazasiadat)
    {
        $this->kellszamlazasiadat = $kellszamlazasiadat;
    }

    public function getOrarendbenszerepel()
    {
        return $this->orarendbenszerepel;
    }

    public function setOrarendbenszerepel($orarendbenszerepel)
    {
        $this->orarendbenszerepel = $orarendbenszerepel;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function getOnlineurl()
    {
        return $this->onlineurl;
    }

    public function setOnlineurl($onlineurl)
    {
        $this->onlineurl = (string)$onlineurl;
    }

    public function getInaktiv()
    {
        return $this->inaktiv;
    }

    public function setInaktiv($inaktiv)
    {
        $this->inaktiv = $inaktiv;
    }

    public function getIdopontDokok()
    {
        return $this->idopontdokok;
    }

    public function addIdopontDok(IdopontDok $dok)
    {
        $this->idopontdokok->add($dok);
        $dok->setIdopont($this);
    }

    public function removeIdopontDok(IdopontDok $dok)
    {
        if ($this->idopontdokok->removeElement($dok)) {
            $dok->removeIdopont($this);
            return true;
        }
        return false;
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
        $nap = $this->getNap();
        return $nap ? \mkw\store::getDayname($nap) : '';
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

    /**
     * Korlát nélküli időpontnál (maxresztvevo 0 vagy NULL) nincs értelmezhető szabad helyszám.
     */
    public function getFreePlaces($datum = null)
    {
        if (!$this->hasLetszamkorlat()) {
            return null;
        }
        return max((int)$this->maxresztvevo - $this->getBookedCount($datum), 0);
    }

    public function isBookable($datum = null)
    {
        if ($this->inaktiv) {
            return false;
        }
        if (!$this->hasLetszamkorlat()) {
            return true;
        }
        return $this->getFreePlaces($datum) > 0;
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
