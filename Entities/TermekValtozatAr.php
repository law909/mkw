<?php

namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * A változat saját ársávos ára; ha van benne ár, megelőzi a termék ársávos árát (TermekArRepository::getArsavAr).
 *
 * @ORM\Entity(repositoryClass="Entities\TermekValtozatArRepository")
 * @ORM\Table(name="termekvaltozatar",
 * options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"},
 * indexes={
 *	@ORM\index(name="termekvaltozatar_idx",columns={"termekvaltozat_id","valutanem_id","arsav_id"})
 * })
 */
class TermekValtozatAr
{
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
     * @ORM\ManyToOne(targetEntity="TermekValtozat",inversedBy="arak")
     * @ORM\JoinColumn(name="termekvaltozat_id",referencedColumnName="id",nullable=false,onDelete="cascade")
     */
    private $termekvaltozat;

    /**
     * @ORM\ManyToOne(targetEntity="Valutanem")
     * @ORM\JoinColumn(name="valutanem_id", referencedColumnName="id",nullable=false,onDelete="restrict")
     */
    private $valutanem;

    /**
     * @ORM\ManyToOne(targetEntity="Arsav")
     * @ORM\JoinColumn(name="arsav_id", referencedColumnName="id",nullable=false,onDelete="cascade")
     */
    private $arsav;

    /** @ORM\Column(type="decimal",precision=14,scale=2,nullable=true) */
    private $netto;

    /** @ORM\Column(type="decimal",precision=14,scale=2,nullable=true) */
    private $brutto;

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

    public function getTermekvaltozat()
    {
        return $this->termekvaltozat;
    }

    public function setTermekvaltozat(TermekValtozat $val)
    {
        $this->termekvaltozat = $val;
    }

    public function getValutanem()
    {
        return $this->valutanem;
    }

    public function getValutanemId()
    {
        return $this->valutanem?->getId();
    }

    public function setValutanem($val)
    {
        if (!($val instanceof Valutanem)) {
            $val = \mkw\store::getEm()->getRepository(Valutanem::class)->find($val);
        }
        $this->valutanem = $val;
    }

    public function getArsav()
    {
        return $this->arsav;
    }

    public function getArsavId()
    {
        return $this->arsav?->getId();
    }

    public function setArsav($val)
    {
        if (!($val instanceof Arsav)) {
            $val = \mkw\store::getEm()->getRepository(Arsav::class)->find($val);
        }
        $this->arsav = $val;
    }

    public function getNetto()
    {
        return $this->netto;
    }

    public function setNetto($val)
    {
        $this->netto = $val;
        $this->brutto = $this->termekvaltozat->getTermek()->getAfa()->calcBrutto($val);
    }

    public function getBrutto()
    {
        return $this->brutto;
    }

    public function setBrutto($val)
    {
        $this->brutto = $val;
        $this->netto = $this->termekvaltozat->getTermek()->getAfa()->calcNetto($val);
    }

    public function hasPrice(): bool
    {
        return (float)$this->netto != 0 || (float)$this->brutto != 0;
    }
}
