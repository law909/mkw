<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\IdopontfoglalasRepository")
 * @ORM\Table(name="idopontfoglalas",
 * options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"},
 * uniqueConstraints={
 *      @ORM\UniqueConstraint(name="idopontfoglalas_uq",columns={"idopont_id","partner_id","datum"})
 * })
 */
class Idopontfoglalas
{

    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id = 0;

    /**
     * @ORM\ManyToOne(targetEntity="Idopont", inversedBy="foglalasok")
     * @ORM\JoinColumn(name="idopont_id",referencedColumnName="id",nullable=false,onDelete="cascade")
     */
    private $idopont;

    /**
     * @ORM\ManyToOne(targetEntity="Partner")
     * @ORM\JoinColumn(name="partner_id",referencedColumnName="id",nullable=false,onDelete="restrict")
     */
    private $partner;

    /** Az alkalom napja: ismétlődő időpontnál ez választja szét a heti alkalmakat. */
    /** @ORM\Column(type="date",nullable=false) */
    private $datum;

    /** @ORM\Column(type="datetime",nullable=false) */
    private $foglalasido;

    /** @ORM\Column(type="boolean", nullable=false) */
    private $online = false;

    /** @ORM\Column(type="boolean", nullable=false) */
    private $emailkoszono = false;

    /** @ORM\Column(type="boolean", nullable=false) */
    private $emailemlekezteto = false;

    /** @ORM\Column(type="date",nullable=true) */
    private $emailemlekeztetodatum;

    /** A lemondott foglalás nem foglal helyet – lásd IdopontfoglalasRepository::getCountForIdopont(). */
    /** @ORM\Column(type="boolean", nullable=false) */
    private $lemondva = false;

    /** @ORM\Column(type="date",nullable=true) */
    private $lemondasdatum;

    /** @ORM\Column(type="text",nullable=true) */
    private $lemondasoka;

    /**
     * A tanár jelölte, hogy a foglaló megérkezett – a pubadmin időpont-listájáról állítható.
     * A jelöléskor egy {@see Idopontreszvetel} sor is keletkezik: az elszámolás abból dolgozik,
     * ez a mező csak a képernyő állapotát tartja (ugyanígy van a JogaBejelentkezesnél is).
     *
     * @ORM\Column(type="boolean",nullable=false)
     */
    private $megjelent = false;

    /** @ORM\Column(type="datetime",nullable=true) */
    private $megjelentido;

    /**
     * A megjelöléskor létrejött részvétel azonosítója – a visszavonás ez alapján törli.
     *
     * @ORM\Column(type="integer",nullable=true)
     */
    private $idopontreszvetelid;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $fizetve = false;

    /** @ORM\Column(type="date",nullable=true) */
    private $fizetesdatum;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $fizetveosszeghuf;

    /**
     * @ORM\ManyToOne(targetEntity="Fizmod")
     * @ORM\JoinColumn(name="fizmod_id",referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $fizmod;

    /**
     * @ORM\ManyToOne(targetEntity="Penztar")
     * @ORM\JoinColumn(name="fizetvepenztar_id",referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $fizetvepenztar;

    /** @ORM\Column(type="string",length=30,nullable=true) */
    private $fizetvepenztarbizonylatszam;

    /** @ORM\Column(type="integer",nullable=true) */
    private $fizetvepenztartetelid;

    /**
     * @ORM\ManyToOne(targetEntity="Bankszamla")
     * @ORM\JoinColumn(name="fizetvebankszamla_id",referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $fizetvebankszamla;

    /** @ORM\Column(type="string",length=30,nullable=true) */
    private $fizetvebankbizonylatszam;

    /** @ORM\Column(type="integer",nullable=true) */
    private $fizetvebanktetelid;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $szamlazva = false;

    /** @ORM\Column(type="date",nullable=true) */
    private $szamlazasdatum;

    /** @ORM\Column(type="string",length=30,nullable=true) */
    private $szamlaszam;

    /** @ORM\Column(type="string",length=30,nullable=true) */
    private $szamlazvabizonylattipus;

    /** @ORM\Column(type="date",nullable=true) */
    private $szamlazvakelt;

    /** @ORM\Column(type="date",nullable=true) */
    private $szamlazvateljesites;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $szamlazvaosszeghuf;

    public function __construct()
    {
        $this->foglalasido = new \DateTime();
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Idopont
     */
    public function getIdopont()
    {
        return $this->idopont;
    }

    public function getIdopontId()
    {
        if ($this->idopont) {
            return $this->idopont->getId();
        }
        return '';
    }

    public function setIdopont($idopont)
    {
        $this->idopont = $idopont;
    }

    /**
     * @return Partner
     */
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

    public function getPartnerEmail()
    {
        if ($this->partner) {
            return $this->partner->getEmail();
        }
        return '';
    }

    public function getPartnerTelefon()
    {
        if ($this->partner) {
            return $this->partner->getTelefon();
        }
        return '';
    }

    public function setPartner($partner)
    {
        $this->partner = $partner;
    }

    public function getDatum()
    {
        return $this->datum;
    }

    public function getDatumStr()
    {
        if ($this->datum) {
            return $this->datum->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    public function getNapNev()
    {
        if ($this->datum) {
            return \mkw\store::getDayname($this->datum->format('N'));
        }
        return '';
    }

    public function setDatum($datum)
    {
        if ($datum instanceof \DateTime) {
            $this->datum = new \DateTime($datum->format(\mkw\store::$SQLDateFormat));
        } else {
            $datum = trim((string)$datum);
            $this->datum = $datum === '' ? null : new \DateTime(\mkw\store::convDate($datum));
        }
    }

    public function getFoglalasido()
    {
        return $this->foglalasido;
    }

    public function getFoglalasidoStr()
    {
        if ($this->foglalasido) {
            return $this->foglalasido->format(\mkw\store::$DateTimeFormat);
        }
        return '';
    }

    public function setFoglalasido($foglalasido)
    {
        if ($foglalasido instanceof \DateTime) {
            $this->foglalasido = $foglalasido;
        } else {
            $foglalasido = trim((string)$foglalasido);
            $this->foglalasido = $foglalasido === '' ? null : new \DateTime(\mkw\store::convDate($foglalasido));
        }
    }

    public function isOnline()
    {
        return $this->online;
    }

    public function setOnline($online)
    {
        $this->online = $online;
    }

    public function getEmailkoszono()
    {
        return $this->emailkoszono;
    }

    public function setEmailkoszono($emailkoszono)
    {
        $this->emailkoszono = $emailkoszono;
    }

    public function getEmailemlekezteto()
    {
        return $this->emailemlekezteto;
    }

    public function setEmailemlekezteto($emailemlekezteto)
    {
        $this->emailemlekezteto = $emailemlekezteto;
    }

    public function getEmailemlekeztetodatum()
    {
        return $this->emailemlekeztetodatum;
    }

    public function getEmailemlekeztetodatumStr()
    {
        if ($this->emailemlekeztetodatum) {
            return $this->emailemlekeztetodatum->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    /** Üres értékkel a mai napra áll – a levélküldés így nem ad át dátumot. */
    public function setEmailemlekeztetodatum($datum = '')
    {
        if ($datum instanceof \DateTime) {
            $this->emailemlekeztetodatum = $datum;
            return;
        }
        $datum = trim((string)$datum);
        if ($datum === '') {
            $datum = date(\mkw\store::$DateFormat);
        }
        $this->emailemlekeztetodatum = new \DateTime(\mkw\store::convDate($datum));
    }

    public function getLemondva()
    {
        return $this->lemondva;
    }

    public function setLemondva($lemondva)
    {
        $this->lemondva = $lemondva;
    }

    public function getLemondasdatum()
    {
        return $this->lemondasdatum;
    }

    public function getLemondasdatumStr()
    {
        if ($this->lemondasdatum) {
            return $this->lemondasdatum->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    /** Üres értékkel a mai napra áll, null-lal törlődik (visszaállításkor). */
    public function setLemondasdatum($datum = '')
    {
        if ($datum instanceof \DateTime) {
            $this->lemondasdatum = $datum;
            return;
        }
        if ($datum === null) {
            $this->lemondasdatum = null;
            return;
        }
        $datum = trim((string)$datum);
        if ($datum === '') {
            $datum = date(\mkw\store::$DateFormat);
        }
        $this->lemondasdatum = new \DateTime(\mkw\store::convDate($datum));
    }

    public function getLemondasoka()
    {
        return $this->lemondasoka;
    }

    public function setLemondasoka($lemondasoka)
    {
        $this->lemondasoka = $lemondasoka;
    }

    public function isMegjelent()
    {
        return $this->megjelent;
    }

    public function setMegjelent($megjelent)
    {
        $this->megjelent = (bool)$megjelent;
        $this->megjelentido = $this->megjelent ? new \DateTime() : null;
    }

    public function getMegjelentido()
    {
        return $this->megjelentido;
    }

    public function getMegjelentidoStr()
    {
        return $this->megjelentido ? $this->megjelentido->format(\mkw\store::$DateTimeFormat) : '';
    }

    public function getIdopontreszvetelid()
    {
        return $this->idopontreszvetelid;
    }

    public function setIdopontreszvetelid($idopontreszvetelid)
    {
        $this->idopontreszvetelid = $idopontreszvetelid;
    }

    /**
     * A megjelenés rögzítése külön táblába, a JogaBejelentkezes::createJogaReszvetel() mintájára.
     * A tanár az alkalomra beugró helyettes is lehet, ezért az időpont dolgozója kerül rá – az
     * időpontnak nincs helyettesítés-nyilvántartása.
     *
     * Az időpontokra nincs jutalékszabály (az órákkal ellentétben), ezért a jutalék 0 marad; a
     * mező azért van, hogy ha lesz szabály, csak ide kelljen beírni.
     */
    public function createIdopontreszvetel()
    {
        $idopont = $this->getIdopont();
        $r = new Idopontreszvetel();
        $r->setPartner($this->getPartner());
        $r->setDatum($this->getDatum());
        $r->setOnline($this->isOnline());
        if ($idopont) {
            $r->setIdopont($idopont);
            $r->setTanar($idopont->getDolgozo());
            $r->setIdoponttema($idopont->getIdoponttema());
            $r->setJogahelyszin($idopont->getJogahelyszin());
            $r->setBruttoegysar($idopont->getAr());
        }
        \mkw\store::getEm()->persist($r);
        \mkw\store::getEm()->flush();
        $this->setIdopontreszvetelid($r->getId());
        \mkw\store::getEm()->persist($this);
        \mkw\store::getEm()->flush();
    }

    public function delIdopontreszvetel()
    {
        if (!$this->getIdopontreszvetelid()) {
            return;
        }
        $obj = \mkw\store::getEm()->getRepository(Idopontreszvetel::class)->find($this->getIdopontreszvetelid());
        if ($obj) {
            \mkw\store::getEm()->remove($obj);
            \mkw\store::getEm()->flush();
        }
        $this->setIdopontreszvetelid(null);
        \mkw\store::getEm()->persist($this);
        \mkw\store::getEm()->flush();
    }

    public function getFizetve()
    {
        return $this->fizetve;
    }

    public function setFizetve($fizetve)
    {
        $this->fizetve = $fizetve;
    }

    public function getFizetesdatum()
    {
        return $this->fizetesdatum;
    }

    public function getFizetesdatumStr()
    {
        if ($this->fizetesdatum) {
            return $this->fizetesdatum->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    public function setFizetesdatum($datum = '')
    {
        $datum = $datum instanceof \DateTime ? $datum : trim((string)$datum);
        if ($datum instanceof \DateTime) {
            $this->fizetesdatum = $datum;
        } elseif ($datum === '') {
            $this->fizetesdatum = null;
        } else {
            $this->fizetesdatum = new \DateTime(\mkw\store::convDate($datum));
        }
    }

    public function getFizetveosszeghuf()
    {
        return $this->fizetveosszeghuf;
    }

    public function setFizetveosszeghuf($fizetveosszeghuf)
    {
        $this->fizetveosszeghuf = $fizetveosszeghuf;
    }

    /**
     * @return Fizmod
     */
    public function getFizmod()
    {
        return $this->fizmod;
    }

    public function getFizmodNev()
    {
        if ($this->fizmod) {
            return $this->fizmod->getNev();
        }
        return '';
    }

    public function setFizmod($fizmod)
    {
        $this->fizmod = $fizmod;
    }

    /**
     * @return Penztar
     */
    public function getFizetvepenztar()
    {
        return $this->fizetvepenztar;
    }

    public function setFizetvepenztar($fizetvepenztar)
    {
        $this->fizetvepenztar = $fizetvepenztar;
    }

    public function getFizetvepenztarbizonylatszam()
    {
        return $this->fizetvepenztarbizonylatszam;
    }

    public function setFizetvepenztarbizonylatszam($szam)
    {
        $this->fizetvepenztarbizonylatszam = $szam;
    }

    public function getFizetvepenztartetelid()
    {
        return $this->fizetvepenztartetelid;
    }

    public function setFizetvepenztartetelid($id)
    {
        $this->fizetvepenztartetelid = $id;
    }

    /**
     * @return Bankszamla
     */
    public function getFizetvebankszamla()
    {
        return $this->fizetvebankszamla;
    }

    public function setFizetvebankszamla($fizetvebankszamla)
    {
        $this->fizetvebankszamla = $fizetvebankszamla;
    }

    public function getFizetvebankbizonylatszam()
    {
        return $this->fizetvebankbizonylatszam;
    }

    public function setFizetvebankbizonylatszam($szam)
    {
        $this->fizetvebankbizonylatszam = $szam;
    }

    public function getFizetvebanktetelid()
    {
        return $this->fizetvebanktetelid;
    }

    public function setFizetvebanktetelid($id)
    {
        $this->fizetvebanktetelid = $id;
    }

    public function getSzamlazva()
    {
        return $this->szamlazva;
    }

    public function setSzamlazva($szamlazva)
    {
        $this->szamlazva = $szamlazva;
    }

    public function getSzamlazasdatumStr()
    {
        if ($this->szamlazasdatum) {
            return $this->szamlazasdatum->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    /** Üres értékkel a mai napra áll. */
    public function setSzamlazasdatum($datum = '')
    {
        $this->szamlazasdatum = self::toDate($datum, true);
    }

    public function getSzamlaszam()
    {
        return $this->szamlaszam;
    }

    public function setSzamlaszam($szamlaszam)
    {
        $this->szamlaszam = $szamlaszam;
    }

    public function getSzamlazvabizonylattipus()
    {
        return $this->szamlazvabizonylattipus;
    }

    public function setSzamlazvabizonylattipus($tipus)
    {
        $this->szamlazvabizonylattipus = $tipus;
    }

    public function getSzamlazvakeltStr()
    {
        if ($this->szamlazvakelt) {
            return $this->szamlazvakelt->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    public function setSzamlazvakelt($datum = '')
    {
        $this->szamlazvakelt = self::toDate($datum);
    }

    public function getSzamlazvateljesitesStr()
    {
        if ($this->szamlazvateljesites) {
            return $this->szamlazvateljesites->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    public function setSzamlazvateljesites($datum = '')
    {
        $this->szamlazvateljesites = self::toDate($datum);
    }

    public function getSzamlazvaosszeghuf()
    {
        return $this->szamlazvaosszeghuf;
    }

    public function setSzamlazvaosszeghuf($osszeg)
    {
        $this->szamlazvaosszeghuf = $osszeg;
    }

    /**
     * @param bool $uresmaiNap üres értékre a mai nap (a bejegyzés dátumánál kell), különben null
     *
     * @return \DateTime|null
     */
    private static function toDate($datum, $uresmaiNap = false)
    {
        if ($datum instanceof \DateTime) {
            return $datum;
        }
        $datum = trim((string)$datum);
        if ($datum === '') {
            if (!$uresmaiNap) {
                return null;
            }
            $datum = date(\mkw\store::$DateFormat);
        }
        return new \DateTime(\mkw\store::convDate($datum));
    }

    /**
     * A foglalás leveleinek Smarty adatai – a sablonokban a `foglalas` változó.
     */
    public function toLista()
    {
        $idopont = $this->getIdopont();
        return [
            'id' => $this->getId(),
            'datum' => $this->getDatumStr(),
            'napnev' => $this->getNapNev(),
            'foglalasido' => $this->getFoglalasidoStr(),
            'online' => $this->isOnline(),
            'emailkoszono' => $this->getEmailkoszono(),
            'emailemlekezteto' => $this->getEmailemlekezteto(),
            'emailemlekeztetodatum' => $this->getEmailemlekeztetodatumStr(),
            'lemondva' => $this->getLemondva(),
            'lemondasdatum' => $this->getLemondasdatumStr(),
            'lemondasoka' => $this->getLemondasoka(),
            'fizetve' => $this->getFizetve(),
            'fizetesdatum' => $this->getFizetesdatumStr(),
            'fizetveosszeghuf' => $this->getFizetveosszeghuf(),
            'fizmodnev' => $this->getFizmodNev(),
            'fizetvepenztarnev' => $this->getFizetvepenztar()?->getNev(),
            'fizetvepenztarbizonylatszam' => $this->getFizetvepenztarbizonylatszam(),
            'fizetvebankszamlaszam' => $this->getFizetvebankszamla()?->getSzamlaszam(),
            'fizetvebankbizonylatszam' => $this->getFizetvebankbizonylatszam(),
            'szamlazva' => $this->getSzamlazva(),
            'szamlazasdatum' => $this->getSzamlazasdatumStr(),
            'szamlaszam' => $this->getSzamlaszam(),
            'szamlazvakelt' => $this->getSzamlazvakeltStr(),
            'szamlazvateljesites' => $this->getSzamlazvateljesitesStr(),
            'szamlazvaosszeghuf' => $this->getSzamlazvaosszeghuf(),
            'partnerid' => $this->getPartnerId(),
            'partnernev' => $this->getPartnerNev(),
            'partneremail' => $this->getPartnerEmail(),
            'partnertelefon' => $this->getPartnerTelefon(),
            'idopontid' => $this->getIdopontId(),
            'idopontido' => $idopont?->getIdotartamStr(),
            'temanev' => $idopont?->getIdoponttemaNev(),
            'tanarnev' => $idopont?->getDolgozoNev(),
            'helyszinnev' => $idopont?->getJogahelyszinNev(),
            'helyszincim' => $idopont?->getJogahelyszinCim(),
            'ar' => $idopont?->getAr(),
        ];
    }

}
