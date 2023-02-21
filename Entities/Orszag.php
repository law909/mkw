<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\OrszagRepository")
 * @ORM\Table(name="orszag",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class Orszag
{
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
    /** @ORM\Column(type="boolean",nullable=false) */
    private $lathato4 = 1;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $lathato5 = 1;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $lathato6 = 1;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $lathato7 = 1;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $lathato8 = 1;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $lathato9 = 1;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $lathato10 = 1;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $lathato11 = 1;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $lathato12 = 1;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $lathato13 = 1;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $lathato14 = 1;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $lathato15 = 1;

    public function __construct()
    {
        $this->partnerek = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNev()
    {
        return $this->nev;
    }

    public function setNev($nev)
    {
        $this->nev = $nev;
    }

    /**
     * @return Valutanem
     */
    public function getValutanem()
    {
        return $this->valutanem;
    }

    public function getValutanemNev()
    {
        $v = $this->getValutanem();
        if ($v) {
            return $v->getNev();
        }
        return '';
    }

    public function getValutanemId()
    {
        $v = $this->getValutanem();
        if ($v) {
            return $v->getId();
        }
        return 0;
    }

    /**
     * @param \Entities\Valutanem $val
     */
    public function setValutanem($val)
    {
        if (!($val instanceof \Entities\Valutanem)) {
            $val = \mkw\store::getEm()->getRepository('Entities\Valutanem')->find($val);
        }
        $this->valutanem = $val;
    }

    /**
     * @return mixed
     */
    public function getLathato()
    {
        return $this->lathato;
    }

    /**
     * @param mixed $lathato
     */
    public function setLathato($lathato)
    {
        $this->lathato = $lathato;
    }

    /**
     * @return mixed
     */
    public function getLathato2()
    {
        return $this->lathato2;
    }

    /**
     * @param mixed $lathato2
     */
    public function setLathato2($lathato2)
    {
        $this->lathato2 = $lathato2;
    }

    /**
     * @return mixed
     */
    public function getLathato3()
    {
        return $this->lathato3;
    }

    /**
     * @param mixed $lathato3
     */
    public function setLathato3($lathato3)
    {
        $this->lathato3 = $lathato3;
    }

    /**
     * @return mixed
     */
    public function getIso3166()
    {
        return $this->iso3166;
    }

    /**
     * @param mixed $iso3166
     */
    public function setIso3166($iso3166)
    {
        $this->iso3166 = $iso3166;
    }

    public function isDefault()
    {
        if (\mkw\store::getParameter(\mkw\consts::Magyarorszag)) {
            return $this->getId() == \mkw\store::getParameter(\mkw\consts::Magyarorszag);
        } elseif (\mkw\store::getParameter(\mkw\consts::Orszag)) {
            return $this->getId() == \mkw\store::getParameter(\mkw\consts::Orszag);
        }
        return false;
    }

    /**
     * @return int
     */
    public function getLathato4()
    {
        return $this->lathato4;
    }

    /**
     * @param int $lathato4
     */
    public function setLathato4($lathato4): void
    {
        $this->lathato4 = $lathato4;
    }

    /**
     * @return int
     */
    public function getLathato5()
    {
        return $this->lathato5;
    }

    /**
     * @param int $lathato5
     */
    public function setLathato5($lathato5): void
    {
        $this->lathato5 = $lathato5;
    }

    /**
     * @return int
     */
    public function getLathato6()
    {
        return $this->lathato6;
    }

    /**
     * @param int $lathato6
     */
    public function setLathato6($lathato6): void
    {
        $this->lathato6 = $lathato6;
    }

    /**
     * @return int
     */
    public function getLathato7()
    {
        return $this->lathato7;
    }

    /**
     * @param int $lathato7
     */
    public function setLathato7($lathato7): void
    {
        $this->lathato7 = $lathato7;
    }

    /**
     * @return int
     */
    public function getLathato8()
    {
        return $this->lathato8;
    }

    /**
     * @param int $lathato8
     */
    public function setLathato8($lathato8): void
    {
        $this->lathato8 = $lathato8;
    }

    /**
     * @return int
     */
    public function getLathato9()
    {
        return $this->lathato9;
    }

    /**
     * @param int $lathato9
     */
    public function setLathato9($lathato9): void
    {
        $this->lathato9 = $lathato9;
    }

    /**
     * @return int
     */
    public function getLathato10()
    {
        return $this->lathato10;
    }

    /**
     * @param int $lathato10
     */
    public function setLathato10($lathato10): void
    {
        $this->lathato10 = $lathato10;
    }

    /**
     * @return int
     */
    public function getLathato11()
    {
        return $this->lathato11;
    }

    /**
     * @param int $lathato11
     */
    public function setLathato11($lathato11): void
    {
        $this->lathato11 = $lathato11;
    }

    /**
     * @return int
     */
    public function getLathato12()
    {
        return $this->lathato12;
    }

    /**
     * @param int $lathato12
     */
    public function setLathato12($lathato12): void
    {
        $this->lathato12 = $lathato12;
    }

    /**
     * @return int
     */
    public function getLathato13()
    {
        return $this->lathato13;
    }

    /**
     * @param int $lathato13
     */
    public function setLathato13($lathato13): void
    {
        $this->lathato13 = $lathato13;
    }

    /**
     * @return int
     */
    public function getLathato14()
    {
        return $this->lathato14;
    }

    /**
     * @param int $lathato14
     */
    public function setLathato14($lathato14): void
    {
        $this->lathato14 = $lathato14;
    }

    /**
     * @return int
     */
    public function getLathato15()
    {
        return $this->lathato15;
    }

    /**
     * @param int $lathato15
     */
    public function setLathato15($lathato15): void
    {
        $this->lathato15 = $lathato15;
    }

    public function getXLathato()
    {
        switch (\mkw\store::getSetupValue('webshopnum', 1)) {
            case 1:
                return $this->getLathato();
            case 2:
                return $this->getLathato2();
            case 3:
                return $this->getLathato3();
            case 4:
                return $this->getLathato4();
            case 5:
                return $this->getLathato5();
            case 6:
                return $this->getLathato6();
            case 7:
                return $this->getLathato7();
            case 8:
                return $this->getLathato8();
            case 9:
                return $this->getLathato9();
            case 10:
                return $this->getLathato10();
            case 11:
                return $this->getLathato11();
            case 12:
                return $this->getLathato12();
            case 13:
                return $this->getLathato13();
            case 14:
                return $this->getLathato14();
            case 15:
                return $this->getLathato15();
            default:
                return $this->getLathato();
        }
    }

}