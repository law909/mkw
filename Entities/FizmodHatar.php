<?php

namespace Entities;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Doctrine\ORM\Mapping\Entity(repositoryClass="FizmodHatarRepository")
 * @Doctrine\ORM\Mapping\Table(name="fizmod_hatar",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class FizmodHatar {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="Fizmod")
     * @ORM\JoinColumn(name="fizmod_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Fizmod
     */
    private $fizmod;
    /**
     * @ORM\ManyToOne(targetEntity="Valutanem")
     * @ORM\JoinColumn(name="valutanem_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Valutanem
     */
    private $valutanem;
    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $hatarertek;

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getHatarertek() {
        return $this->hatarertek;
    }

    /**
     * @param mixed $hatarertek
     */
    public function setHatarertek($hatarertek) {
        $this->hatarertek = $hatarertek;
    }

    /**
     * @return Fizmod
     */
    public function getFizmod() {
        return $this->fizmod;
    }

    /**
     * @param \Entities\Fizmod $val
     */
    public function setFizmod($val) {
        if (!($val instanceof \Entities\Fizmod)) {
            $val = \mkw\store::getEm()->getRepository('Entities\Fizmod')->find($val);
        }
        $this->fizmod = $val;
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

}