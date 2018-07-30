<?php
namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\OrszagRepository")
 * @ORM\Table(name="orszag",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class Orszag {
    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */

    private $id;
    /**
     * @ORM\Column(type="string",length=255)
     */
    private $nev;

    /**
     * @ORM\Column(type="string",length=5)
     */
    private $iso3166;

    /** @ORM\OneToMany(targetEntity="Partner", mappedBy="szallitasimod",cascade={"persist"}) */
    private $partnerek;

    /**
     * @ORM\ManyToOne(targetEntity="Valutanem")
     * @ORM\JoinColumn(name="valutanem_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Valutanem
     */
    private $valutanem;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $lathato = 1;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $lathato2 = 1;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $lathato3 = 1;

    public function __construct() {
        $this->partnerek = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId() {
        return $this->id;
    }

    public function getNev() {
        return $this->nev;
    }

    public function setNev($nev) {
        $this->nev = $nev;
    }

    /**
     * @return Valutanem
     */
    public function getValutanem() {
        return $this->valutanem;
    }

    public function getValutanemNev() {
        $v = $this->getValutanem();
        if ($v) {
            return $v->getNev();
        }
        return '';
    }

    public function getValutanemId() {
        $v = $this->getValutanem();
        if ($v) {
            return $v->getId();
        }
        return 0;
    }

    /**
     * @param \Entities\Valutanem $val
     */
    public function setValutanem($val) {
        if (!($val instanceof \Entities\Valutanem)) {
            $val = \mkw\store::getEm()->getRepository('Entities\Valutanem')->find($val);
        }
        $this->valutanem = $val;
    }

    /**
     * @return mixed
     */
    public function getLathato() {
        return $this->lathato;
    }

    /**
     * @param mixed $lathato
     */
    public function setLathato($lathato) {
        $this->lathato = $lathato;
    }

    /**
     * @return mixed
     */
    public function getLathato2() {
        return $this->lathato2;
    }

    /**
     * @param mixed $lathato2
     */
    public function setLathato2($lathato2) {
        $this->lathato2 = $lathato2;
    }

    /**
     * @return mixed
     */
    public function getLathato3() {
        return $this->lathato3;
    }

    /**
     * @param mixed $lathato3
     */
    public function setLathato3($lathato3) {
        $this->lathato3 = $lathato3;
    }

    /**
     * @return mixed
     */
    public function getIso3166() {
        return $this->iso3166;
    }

    /**
     * @param mixed $iso3166
     */
    public function setIso3166($iso3166) {
        $this->iso3166 = $iso3166;
    }


}