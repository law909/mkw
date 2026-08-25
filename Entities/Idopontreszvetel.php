<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Egy megtartott időpont-alkalom egy résztvevője – a jóga órák {@see JogaReszvetel} párja.
 *
 * A foglalás (`Idopontfoglalas`) a szándékot rögzíti, ez pedig a tényt: akkor keletkezik, amikor
 * a tanár a pubadminban megjelöli, hogy a foglaló megérkezett, és törlődik, ha visszavonja. A
 * tanárelszámolás ebből a táblából dolgozik.
 *
 * A partner nevét és emailjét másoltan is tároljuk, ahogy a JogaReszvetel: az elszámolás akkor is
 * olvasható maradjon, ha a partnertörzs időközben változik.
 *
 * @ORM\Entity(repositoryClass="Entities\IdopontreszvetelRepository")
 * @ORM\Table(name="idopontreszvetel",
 *  options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"},
 *  indexes={@ORM\index(name="idopontreszveteldatum_idx",columns={"datum"})})
 */
class Idopontreszvetel
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

    /**
     * @ORM\ManyToOne(targetEntity="Partner")
     * @ORM\JoinColumn(name="partner_id",referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $partner;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $partnernev = '';

    /** @ORM\Column(type="string",length=100,nullable=true) */
    private $partneremail = '';

    /**
     * @ORM\ManyToOne(targetEntity="Dolgozo")
     * @ORM\JoinColumn(name="tanar_id",referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $tanar;

    /**
     * @ORM\ManyToOne(targetEntity="Idopont")
     * @ORM\JoinColumn(name="idopont_id",referencedColumnName="id",nullable=true,onDelete="set null")
     */
    private $idopont;

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

    /**
     * Az alkalom napja – ismétlődő időpontnál ez választja szét a heti alkalmakat.
     *
     * @ORM\Column(type="date",nullable=false)
     */
    private $datum;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $online = false;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $bruttoegysar = 0;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $jutalek = 0;

    public function getId()
    {
        return $this->id;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getLastmod()
    {
        return $this->lastmod;
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
     * A partnerrel együtt a nevét és az emailjét is elmentjük, hogy az elszámolás akkor is
     * olvasható legyen, ha a partnertörzs változik.
     */
    public function setPartner($partner)
    {
        $this->partner = $partner;
        if ($partner) {
            $this->partnernev = $partner->getNev();
            $this->partneremail = $partner->getEmail();
        }
    }

    public function getPartnernev()
    {
        return $this->partnernev;
    }

    public function setPartnernev($partnernev)
    {
        $this->partnernev = $partnernev;
    }

    public function getPartneremail()
    {
        return $this->partneremail;
    }

    public function setPartneremail($partneremail)
    {
        $this->partneremail = $partneremail;
    }

    /**
     * @return \Entities\Dolgozo
     */
    public function getTanar()
    {
        return $this->tanar;
    }

    public function getTanarId()
    {
        return $this->tanar?->getId();
    }

    public function getTanarNev()
    {
        return $this->tanar ? $this->tanar->getNev() : '';
    }

    public function setTanar($tanar)
    {
        $this->tanar = $tanar;
    }

    /**
     * @return \Entities\Idopont
     */
    public function getIdopont()
    {
        return $this->idopont;
    }

    public function getIdopontId()
    {
        return $this->idopont?->getId();
    }

    public function setIdopont($idopont)
    {
        $this->idopont = $idopont;
    }

    /**
     * @return \Entities\Idoponttema
     */
    public function getIdoponttema()
    {
        return $this->idoponttema;
    }

    public function getIdoponttemaNev()
    {
        return $this->idoponttema ? $this->idoponttema->getNev() : '';
    }

    public function setIdoponttema($idoponttema)
    {
        $this->idoponttema = $idoponttema;
    }

    /**
     * @return \Entities\Jogahelyszin
     */
    public function getJogahelyszin()
    {
        return $this->jogahelyszin;
    }

    public function getJogahelyszinNev()
    {
        return $this->jogahelyszin ? $this->jogahelyszin->getNev() : '';
    }

    public function setJogahelyszin($jogahelyszin)
    {
        $this->jogahelyszin = $jogahelyszin;
    }

    public function getDatum()
    {
        return $this->datum;
    }

    public function getDatumStr()
    {
        return $this->datum ? $this->datum->format(\mkw\store::$DateFormat) : '';
    }

    public function getDatumNapnev()
    {
        return $this->datum ? \mkw\store::getDayname($this->datum->format('N')) : '';
    }

    public function setDatum($datum)
    {
        if ($datum instanceof \DateTime) {
            $this->datum = $datum;
        } else {
            $this->datum = $datum ? new \DateTime(\mkw\store::convDate($datum)) : null;
        }
    }

    public function isOnline()
    {
        return $this->online;
    }

    public function setOnline($online)
    {
        $this->online = (bool)$online;
    }

    public function getBruttoegysar()
    {
        return $this->bruttoegysar;
    }

    public function setBruttoegysar($bruttoegysar)
    {
        $this->bruttoegysar = $bruttoegysar;
    }

    public function getJutalek()
    {
        return $this->jutalek;
    }

    public function setJutalek($jutalek)
    {
        $this->jutalek = $jutalek;
    }

}
