<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Egy dolgozó távolléte egy összefüggő időszakra. Egy napra egy sor is elég: olyankor a
 * datumig megegyezik a datumtol-lal.
 *
 * @ORM\Entity(repositoryClass="Entities\DolgozoszabadsagRepository")
 * @ORM\Table(name="dolgozoszabadsag",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class Dolgozoszabadsag
{

    const TIPUS_SZABADSAG = 'szabadsag';
    const TIPUS_BETEGSZABADSAG = 'betegszabadsag';
    const TIPUS_FIZETETLEN = 'fizetetlen';

    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Dolgozo")
     * @ORM\JoinColumn(name="dolgozo_id", referencedColumnName="id",nullable=false,onDelete="cascade")
     */
    private $dolgozo;

    /** @ORM\Column(type="date",nullable=false) */
    private $datumtol;

    /** @ORM\Column(type="date",nullable=false) */
    private $datumig;

    /** @ORM\Column(type="string",length=30,nullable=false) */
    private $tipus = self::TIPUS_SZABADSAG;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $megjegyzes;

    /**
     * A választható típusok a képernyőkre és a jelenléti ívre: kulcs = tárolt érték.
     */
    public static function getTipusok()
    {
        return [
            self::TIPUS_SZABADSAG => 'szabadság',
            self::TIPUS_BETEGSZABADSAG => 'betegszabadság',
            self::TIPUS_FIZETETLEN => 'fizetetlen szabadság',
        ];
    }

    public function getId()
    {
        return $this->id;
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
        return $this->dolgozo ? $this->dolgozo->getId() : null;
    }

    public function getDolgozoNev()
    {
        return $this->dolgozo ? $this->dolgozo->getNev() : '';
    }

    public function setDolgozo($dolgozo)
    {
        $this->dolgozo = $dolgozo;
    }

    public function getDatumtol()
    {
        return $this->datumtol;
    }

    public function getDatumtolStr()
    {
        return $this->datumtol ? $this->datumtol->format(\mkw\store::$DateFormat) : '';
    }

    public function setDatumtol($datumtol)
    {
        $this->datumtol = $this->toDate($datumtol);
    }

    public function getDatumig()
    {
        return $this->datumig;
    }

    public function getDatumigStr()
    {
        return $this->datumig ? $this->datumig->format(\mkw\store::$DateFormat) : '';
    }

    public function setDatumig($datumig)
    {
        $this->datumig = $this->toDate($datumig);
    }

    public function getTipus()
    {
        return $this->tipus;
    }

    public function getTipusNev()
    {
        $tipusok = self::getTipusok();
        return $tipusok[$this->tipus] ?? $this->tipus;
    }

    public function setTipus($tipus)
    {
        $this->tipus = array_key_exists($tipus, self::getTipusok()) ? $tipus : self::TIPUS_SZABADSAG;
    }

    public function getMegjegyzes()
    {
        return $this->megjegyzes;
    }

    public function setMegjegyzes($megjegyzes)
    {
        $this->megjegyzes = $megjegyzes;
    }

    /**
     * Beleesik-e a nap a távollétbe.
     */
    public function tartalmazzaNapot(\DateTime $nap)
    {
        if (!$this->datumtol || !$this->datumig) {
            return false;
        }
        $napstr = $nap->format('Y-m-d');
        return ($napstr >= $this->datumtol->format('Y-m-d')) && ($napstr <= $this->datumig->format('Y-m-d'));
    }

    private function toDate($adat)
    {
        if ($adat instanceof \DateTime) {
            return $adat;
        }
        if (!$adat) {
            return null;
        }
        return new \DateTime(\mkw\store::convDate($adat));
    }

}
