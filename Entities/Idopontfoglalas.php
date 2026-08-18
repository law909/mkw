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
