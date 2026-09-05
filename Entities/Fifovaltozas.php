<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Egy készletcsoport, amit újra kell számolni: a bizonylatmentés ide ír egy sort
 * (`Listeners\BizonylattetelListener`), az éjszakai cron pedig ezeket dolgozza fel.
 *
 * Szándékosan nincs benne reláció és idegen kulcs, az oszlopok `NOT NULL DEFAULT 0`-k:
 *  - a UNIQUE index csak így deduplikál (MySQL-ben a NULL-ok különbözőek), enélkül az
 *    `INSERT IGNORE` sem védene;
 *  - idegen kulcs nélkül az insert olcsó, márpedig ez a bizonylatmentés forró útján fut;
 *  - idegen kulccsal egy törölt termék cascade-elné a jelölést – épp azt veszítenénk el,
 *    amiért felvettük.
 * A 0 ↔ NULL fordítást a `Services\FifoService` végzi.
 *
 * @ORM\Entity
 * @ORM\Table(name="fifovaltozas",
 * options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"},
 * uniqueConstraints={
 *      @ORM\UniqueConstraint(name="fifovaltozascsoport_uidx",columns={"raktar_id","termek_id","termekvaltozat_id"})
 * })
 */
class Fifovaltozas
{

    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /** @ORM\Column(type="integer",nullable=false,options={"default":0}) */
    private $raktar_id = 0;

    /** @ORM\Column(type="integer",nullable=false,options={"default":0}) */
    private $termek_id = 0;

    /** @ORM\Column(type="integer",nullable=false,options={"default":0}) */
    private $termekvaltozat_id = 0;

    /** @ORM\Column(type="datetime",nullable=true) */
    private $created;

    public function getId()
    {
        return $this->id;
    }

    public function getRaktarId()
    {
        return $this->raktar_id;
    }

    public function getTermekId()
    {
        return $this->termek_id;
    }

    public function getTermekvaltozatId()
    {
        return $this->termekvaltozat_id;
    }

    public function getCreated()
    {
        return $this->created;
    }

}
