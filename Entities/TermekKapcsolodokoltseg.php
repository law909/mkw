<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Egy termékhez rendelt kapcsolódó költség. Azért kapcsolóentitás és nem sima ManyToMany, mert
 * a hozzárendelésnek saját adata is van: a `mennyiseg`, amivel a költség a terméken számol.
 *
 * A kulcs a termék és a költség párosa, tehát egy költség egy terméken csak egyszer szerepelhet.
 *
 * @ORM\Entity(repositoryClass="Entities\TermekKapcsolodokoltsegRepository")
 * @ORM\Table(name="termek_kapcsolodokoltsegek",
 *  options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class TermekKapcsolodokoltseg
{

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Termek",inversedBy="kapcsolodokoltsegek")
     * @ORM\JoinColumn(name="termek_id",referencedColumnName="id",nullable=false,onDelete="cascade")
     */
    private $termek;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Kapcsolodokoltseg",inversedBy="termekek")
     * @ORM\JoinColumn(name="kapcsolodokoltseg_id",referencedColumnName="id",nullable=false,onDelete="cascade")
     */
    private $kapcsolodokoltseg;

    /**
     * A költség számításának alapja ezen a terméken. Kitöltve kiváltja a törzs `szamitasalap`
     * mezőjét (ma a termék súlyát) – több csomagolóanyagnál a termék teljes súlya nem jó alap.
     * Üresen hagyva a törzs szerinti alap érvényes; a 0 viszont valódi 0, nem "nincs kitöltve".
     *
     * @ORM\Column(type="decimal",precision=12,scale=4,nullable=true)
     */
    private $mennyiseg;

    public function __construct(?Termek $termek = null, ?Kapcsolodokoltseg $koltseg = null, $mennyiseg = null)
    {
        $this->termek = $termek;
        $this->kapcsolodokoltseg = $koltseg;
        $this->mennyiseg = $mennyiseg;
    }

    public function getTermek()
    {
        return $this->termek;
    }

    public function setTermek($val)
    {
        $this->termek = $val;
    }

    public function getKapcsolodokoltseg()
    {
        return $this->kapcsolodokoltseg;
    }

    public function setKapcsolodokoltseg($val)
    {
        $this->kapcsolodokoltseg = $val;
    }

    public function getKapcsolodokoltsegId()
    {
        return $this->kapcsolodokoltseg?->getId();
    }

    public function getMennyiseg()
    {
        return $this->mennyiseg;
    }

    public function setMennyiseg($val)
    {
        $this->mennyiseg = ($val === '' || $val === null) ? null : $val;
    }

    /** a terméken érvényes érték: a törzs ára szorozva az itteni (vagy a törzs szerinti) alappal */
    public function calcErtek(): float
    {
        return $this->kapcsolodokoltseg
            ? $this->kapcsolodokoltseg->calcErtek($this->termek, $this->mennyiseg)
            : 0;
    }

}
