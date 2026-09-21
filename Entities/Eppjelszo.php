<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Egy WordPress oldal (External Page Passwords plugin) egyik jelszava. Egy oldalnak egyszerre
 * több érvényes jelszava lehet, mindegyik saját lejárattal; a nyers jelszót nem tároljuk.
 *
 * @ORM\Entity(repositoryClass="Entities\EppjelszoRepository")
 * @ORM\Table(name="eppjelszo",
 * options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"},
 * uniqueConstraints={
 *      @ORM\UniqueConstraint(name="eppjelszoazonosito_idx",columns={"azonosito"})
 * },
 * indexes={
 *      @ORM\index(name="eppjelszooldalid_idx",columns={"oldalid"})
 * })
 */
class Eppjelszo
{

    public const JELSZOHOSSZ = 12;

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

    /** a WP felé password_id: véletlen, nem sorszám, hogy ne legyen kitalálható
     * @ORM\Column(type="string",length=64,nullable=false) */
    private $azonosito;

    /** a WP post ID (page_id)
     * @ORM\Column(type="integer",nullable=false) */
    private $oldalid = 0;

    /** @ORM\Column(type="string",length=255,nullable=false) */
    private $jelszohash = '';

    /** @ORM\Column(type="datetime",nullable=false) */
    private $lejarat;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $megjegyzes;

    /** @ORM\Column(type="datetime",nullable=true) */
    private $visszavonvaon;

    /**
     * @ORM\ManyToOne(targetEntity="Dolgozo")
     * @ORM\JoinColumn(name="visszavonvaby", referencedColumnName="id")
     */
    private $visszavonvaby;

    public function __construct()
    {
        $this->azonosito = bin2hex(random_bytes(16));
    }

    /**
     * Új jelszót sorsol, és csak a hash-ét tartja meg.
     *
     * @return string a nyers jelszó – ez az egyetlen alkalom, amikor elérhető
     */
    public function generateJelszo(): string
    {
        $jelszo = \mkw\store::generatePassword(self::JELSZOHOSSZ);
        $this->jelszohash = password_hash($jelszo, PASSWORD_DEFAULT);
        return $jelszo;
    }

    public function checkJelszo(string $jelszo): bool
    {
        return password_verify($jelszo, $this->jelszohash);
    }

    public function isVisszavonva(): bool
    {
        return $this->visszavonvaon !== null;
    }

    public function isLejart(?\DateTimeInterface $most = null): bool
    {
        return $this->lejarat <= ($most ?? new \DateTime());
    }

    public function isAktiv(?\DateTimeInterface $most = null): bool
    {
        return !$this->isVisszavonva() && !$this->isLejart($most);
    }

    public function revoke(?Dolgozo $dolgozo = null)
    {
        if (!$this->visszavonvaon) {
            $this->visszavonvaon = new \DateTime();
            $this->visszavonvaby = $dolgozo;
        }
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
        return $this->created ? $this->created->format(\mkw\store::$DateTimeFormat) : '';
    }

    public function getLastmod()
    {
        return $this->lastmod;
    }

    public function getCreatedby()
    {
        return $this->createdby;
    }

    public function getCreatedbyNev()
    {
        return $this->createdby ? $this->createdby->getNev() : null;
    }

    public function getUpdatedby()
    {
        return $this->updatedby;
    }

    public function getAzonosito()
    {
        return $this->azonosito;
    }

    public function getOldalid()
    {
        return $this->oldalid;
    }

    public function setOldalid($oldalid)
    {
        $this->oldalid = (int)$oldalid;
    }

    /**
     * @return \DateTime|null
     */
    public function getLejarat()
    {
        return $this->lejarat;
    }

    public function getLejaratStr()
    {
        return $this->lejarat ? $this->lejarat->format(\mkw\store::$DateTimeFormat) : '';
    }

    /** a WP felé expires_at: Unix timestamp, UTC */
    public function getLejaratTimestamp(): int
    {
        return $this->lejarat ? $this->lejarat->getTimestamp() : 0;
    }

    /**
     * @param \DateTimeInterface|string $adat string esetén 'Y.m.d H:i' vagy 'Y-m-d H:i'
     */
    public function setLejarat($adat)
    {
        if ($adat instanceof \DateTimeInterface) {
            $this->lejarat = \DateTime::createFromInterface($adat);
        } else {
            $this->lejarat = new \DateTime(\mkw\store::convDate($adat));
        }
    }

    public function getMegjegyzes()
    {
        return $this->megjegyzes;
    }

    public function setMegjegyzes($megjegyzes)
    {
        $this->megjegyzes = $megjegyzes;
    }

    public function getVisszavonvaon()
    {
        return $this->visszavonvaon;
    }

    public function getVisszavonvaonStr()
    {
        return $this->visszavonvaon ? $this->visszavonvaon->format(\mkw\store::$DateTimeFormat) : '';
    }

    public function getVisszavonvaby()
    {
        return $this->visszavonvaby;
    }

    public function getVisszavonvabyNev()
    {
        return $this->visszavonvaby ? $this->visszavonvaby->getNev() : null;
    }

}
