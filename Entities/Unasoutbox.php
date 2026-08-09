<?php

namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * Az UNAS-ba visszaírandó változások sora. A visszaírás HTTP hívás, azt nem lehet `onFlush`-ban
 * elvégezni (blokkol, hibázhat és bezárja az EntityManagert), ezért a listener csak sort ír, a
 * cron pedig lehúzza – lásd `Listeners\UnasOutboxListener` és `Services\UnasSetOrderService`.
 *
 * @ORM\Entity(repositoryClass="Entities\UnasoutboxRepository")
 * @ORM\Table(name="unasoutbox",
 * options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"},
 * indexes={
 *      @ORM\index(name="unasoutboxallapot_idx",columns={"allapot","id"}),
 *      @ORM\index(name="unasoutboxunaskey_idx",columns={"unaskey"})
 * })
 */
class Unasoutbox
{

    public const TIPUSSTATUSZ = 'statusz';
    public const TIPUSSZAMLA = 'szamla';
    public const TIPUSCSOMAG = 'csomag';

    public const ALLAPOTFUGGO = 'fuggo';
    public const ALLAPOTKESZ = 'kesz';
    public const ALLAPOTHIBA = 'hiba';

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
     * @ORM\ManyToOne(targetEntity="Bizonylatfej")
     * @ORM\JoinColumn(name="bizonylatfej_id", referencedColumnName="id",nullable=true,onDelete="set null")
     * @var \Entities\Bizonylatfej
     */
    private $bizonylatfej;

    /** A rendelés UNAS-beli azonosítója. Denormalizált, hogy a bizonylat törlése után is elmenjen
     * a visszaírás – ugyanaz a jelentés, mint a `Bizonylatfej.unaskey`-nek.
     * @ORM\Column(type="string",length=50,nullable=false) */
    private $unaskey;

    /** Third-party rendelésnél a piactér azonosítója, egyébként üres – lásd
     * `Bizonylatfej.unaskulsokey`. A setOrder ezzel szólítja meg a rendelést, ha ki van töltve.
     * @ORM\Column(type="string",length=50,nullable=true) */
    private $unaskulsokey;

    /** @ORM\Column(type="string",length=20,nullable=false) */
    private $tipus = self::TIPUSSTATUSZ;

    /** @ORM\Column(type="string",length=20,nullable=false) */
    private $allapot = self::ALLAPOTFUGGO;

    /** @ORM\Column(type="integer",nullable=false) */
    private $probalkozas = 0;

    /** @ORM\Column(type="text",nullable=true) */
    private $utolsohiba;

    /** @ORM\Column(type="datetime",nullable=true) */
    private $feldolgozva;

    public function toLista()
    {
        return [
            'id' => $this->getId(),
            'created' => $this->getCreatedStr(),
            'bizonylatfej' => $this->getBizonylatfejId(),
            'unaskey' => $this->getUnaskey(),
            'unaskulsokey' => $this->getUnaskulsokey(),
            'tipus' => $this->getTipus(),
            'allapot' => $this->getAllapot(),
            'probalkozas' => $this->getProbalkozas(),
            'utolsohiba' => $this->getUtolsohiba(),
            'feldolgozva' => $this->getFeldolgozvaStr(),
        ];
    }

    public function getId()
    {
        return $this->id;
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

    /** A listener a UnitOfWork-ön dolgozik, ahol a Gedmo Timestampable már nem fut le rá. */
    public function setCreated($val)
    {
        $this->created = $val;
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
        return '';
    }

    public function setBizonylatfej($val)
    {
        $this->bizonylatfej = $val;
    }

    public function getUnaskey()
    {
        return $this->unaskey;
    }

    public function setUnaskey($val)
    {
        $this->unaskey = $val;
    }

    public function getUnaskulsokey()
    {
        return $this->unaskulsokey;
    }

    public function setUnaskulsokey($val)
    {
        $this->unaskulsokey = $val;
    }

    /** Az azonosító, amivel a setOrder megszólítja a rendelést (a `Key` szűrő). */
    public function getUnasApikey()
    {
        return trim((string)$this->unaskulsokey) !== '' ? $this->unaskulsokey : $this->unaskey;
    }

    public function getTipus()
    {
        return $this->tipus;
    }

    public function setTipus($val)
    {
        $this->tipus = $val;
    }

    public function getAllapot()
    {
        return $this->allapot;
    }

    public function setAllapot($val)
    {
        $this->allapot = $val;
    }

    public function getProbalkozas()
    {
        return $this->probalkozas;
    }

    public function setProbalkozas($val)
    {
        $this->probalkozas = $val;
    }

    public function getUtolsohiba()
    {
        return $this->utolsohiba;
    }

    public function setUtolsohiba($val)
    {
        $this->utolsohiba = $val;
    }

    public function getFeldolgozva()
    {
        return $this->feldolgozva;
    }

    public function getFeldolgozvaStr()
    {
        if ($this->feldolgozva) {
            return $this->feldolgozva->format(\mkw\store::$DateTimeFormat);
        }
        return '';
    }

    public function setFeldolgozva($val)
    {
        $this->feldolgozva = $val;
    }

}
