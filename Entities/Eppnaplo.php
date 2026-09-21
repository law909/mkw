<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Az External Page Passwords API sikertelen kérései. A /validate rate limitje is ebből számol.
 * A jelszó soha nem kerül bele.
 *
 * @ORM\Entity(repositoryClass="Entities\EppnaploRepository")
 * @ORM\Table(name="eppnaplo",
 * options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"},
 * indexes={
 *      @ORM\index(name="eppnaplokorlat_idx",columns={"oldalid","ip","created"}),
 *      @ORM\index(name="eppnaplocreated_idx",columns={"created"})
 * })
 */
class Eppnaplo
{

    public const VEGPONTVALIDATE = 'validate';
    public const VEGPONTSTATUS = 'status';

    /** hibás jelszó; a lejárt és a visszavont jelszót a /validate nem is próbálja */
    public const EREDMENYHIBAS = 'hibas';
    /** a rate limit miatt ellenőrzés nélkül utasítottuk el */
    public const EREDMENYKORLAT = 'korlat';
    public const EREDMENYNINCS = 'nincs';
    public const EREDMENYMASOLDAL = 'masoldal';
    public const EREDMENYLEJART = 'lejart';
    public const EREDMENYVISSZAVONVA = 'visszavonva';

    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /** @ORM\Column(type="datetime",nullable=false) */
    private $created;

    /** @ORM\Column(type="string",length=20,nullable=false) */
    private $vegpont;

    /** @ORM\Column(type="integer",nullable=false) */
    private $oldalid = 0;

    /** a /status-nál a kérdezett password_id
     * @ORM\Column(type="string",length=64,nullable=true) */
    private $azonosito;

    /** @ORM\Column(type="string",length=45,nullable=true) */
    private $ip;

    /** @ORM\Column(type="string",length=20,nullable=false) */
    private $eredmeny;

    public function __construct($vegpont, $oldalid, $eredmeny, $ip = null, $azonosito = null)
    {
        $this->created = new \DateTime();
        $this->vegpont = $vegpont;
        $this->oldalid = (int)$oldalid;
        $this->eredmeny = $eredmeny;
        $this->ip = $ip === null ? null : substr($ip, 0, 45);
        $this->azonosito = $azonosito === null ? null : substr($azonosito, 0, 64);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getVegpont()
    {
        return $this->vegpont;
    }

    public function getOldalid()
    {
        return $this->oldalid;
    }

    public function getAzonosito()
    {
        return $this->azonosito;
    }

    public function getIp()
    {
        return $this->ip;
    }

    public function getEredmeny()
    {
        return $this->eredmeny;
    }

}
