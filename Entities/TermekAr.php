<?php

namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\TermekArRepository")
 * @ORM\Table(name="termekar",
 * options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"},
 * indexes={
 *	@ORM\index(name="termekarazonosito_idx",columns={"termek_id","valutanem_id","arsav_id"})
 * })
 */
class TermekAr
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
     * @ORM\ManyToOne(targetEntity="Termek",inversedBy="termekarak")
     * @ORM\JoinColumn(name="termek_id",referencedColumnName="id",onDelete="cascade")
     */
    private $termek;

    /** @ORM\Column(type="decimal",precision=14,scale=2,nullable=true) */
    private $netto;

    /** @ORM\Column(type="decimal",precision=14,scale=2,nullable=true) */
    private $brutto;

    /**
     * @ORM\ManyToOne(targetEntity="Valutanem",inversedBy="termekarak")
     * @ORM\JoinColumn(name="valutanem_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $valutanem;

    /**
     * @ORM\ManyToOne(targetEntity="Arsav")
     * @ORM\JoinColumn(name="arsav_id", referencedColumnName="id",nullable=true,onDelete="cascade")
     */
    private $arsav;

    public function getId()
    {
        return $this->id;
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

    public function setTermek(Termek $termek)
    {
        $this->termek = $termek;
//		$termek->addTermekKep($this);
    }

    public function removeTermek()
    {
        if ($this->termek !== null) {
            $termek = $this->termek;
            $this->termek = null;
        }
    }

    public function getLastmod()
    {
        return $this->lastmod;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getValutanem()
    {
        return $this->valutanem;
    }

    public function getValutanemnev()
    {
        return $this->valutanem?->getNev();
    }

    public function getValutanemId()
    {
        return $this->valutanem->getId();
    }

    public function setValutanem($val)
    {
        if (!($val instanceof \Entities\Valutanem)) {
            $val = \mkw\store::getEm()->getRepository('Entities\Valutanem')->find($val);
        }
        if ($this->valutanem !== $val) {
            $this->valutanem = $val;
        }
    }

    public function removeValutanem()
    {
        if ($this->valutanem !== null) {
            $this->valutanem = null;
        }
    }

    public function getNetto()
    {
        return $this->netto;
    }

    public function setNetto($val)
    {
        $this->netto = $val;
        $this->brutto = $this->getTermek()->getAfa()->calcBrutto($val);
    }

    public function getBrutto()
    {
        return $this->brutto;
    }

    public function setBrutto($val)
    {
        $this->brutto = $val;
        $this->netto = $this->getTermek()->getAfa()->calcNetto($val);
    }

    public function getArsav()
    {
        return $this->arsav;
    }

    public function setArsav($val)
    {
        if (!($val instanceof \Entities\Arsav)) {
            $val = \mkw\store::getEm()->getRepository(Arsav::class)->find($val);
        }
        if ($this->arsav !== $val) {
            $this->arsav = $val;
        }
    }

    public function removeArsav()
    {
        $this->arsav = null;
    }

}