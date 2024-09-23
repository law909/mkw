<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="cimkekat",
 * options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"},
 * indexes={
 *	@ORM\Index(name="cimkekatslug_idx",columns={"slug"})
 * })
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="osztaly", type="string", length=30)
 * @ORM\DiscriminatorMap({"partner"="Partnercimkekat", "termek"="Termekcimkekat"})
 */
abstract class Cimkekat
{
    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /** @ORM\Column(type="boolean") */
    private $lathato = true;
    /** @ORM\Column(type="boolean") */
    private $termeklaponlathato = false;
    /** @ORM\Column(type="boolean") */
    private $termekszurobenlathato = false;
    /** @ORM\Column(type="boolean") */
    private $termeklistabanlathato = false;
    /** @ORM\Column(type="boolean") */
    private $termekakciodobozbanlathato = false;
    /** @ORM\Column(type="integer",nullable=true) */
    private $sorrend;

    public function getId()
    {
        return $this->id;
    }

    public function getCimkek()
    {
        return $this->cimkek;
    }

    public function AddCimke(Cimketorzs $cimke)
    {
        if (!$this->cimkek->contains($cimke)) {
            $this->cimkek->add($cimke);
            $cimke->setKategoria($this);
        }
    }

    public function removeCimke(Cimketorzs $cimke)
    {
        if ($this->cimkek->removeElement($cimke)) {
            $cimke->removeKategoria();
            return true;
        }
        return false;
    }

    public function getLathato()
    {
        return $this->lathato;
    }

    public function setLathato($lathato)
    {
        $this->lathato = $lathato;
    }

    public function getTermeklaponlathato()
    {
        return $this->termeklaponlathato;
    }

    public function setTermeklaponlathato($lathato)
    {
        $this->termeklaponlathato = $lathato;
    }

    public function getTermekakciodobozbanlathato()
    {
        return $this->termekakciodobozbanlathato;
    }

    public function setTermekakciodobozbanlathato($lathato)
    {
        $this->termekakciodobozbanlathato = $lathato;
    }

    public function getTermeklistabanlathato()
    {
        return $this->termeklistabanlathato;
    }

    public function setTermeklistabanlathato($lathato)
    {
        $this->termeklistabanlathato = $lathato;
    }

    public function getTermekszurobenlathato()
    {
        return $this->termekszurobenlathato;
    }

    public function setTermekszurobenlathato($lathato)
    {
        $this->termekszurobenlathato = $lathato;
    }

    public function getSorrend()
    {
        return $this->sorrend;
    }

    public function setSorrend($sorrend)
    {
        $this->sorrend = $sorrend;
    }

}