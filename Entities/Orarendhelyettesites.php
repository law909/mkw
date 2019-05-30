<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="Entities\OrarendhelyettesitesRepository")
 * @ORM\Table(name="orarendhelyettesites",
 * options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"},
 * indexes={
 * 		@ORM\index(name="orarendhelyettesitesdatumorarend_idx",columns={"datum", "orarend_id"})
 * })
 * @ORM\HasLifecycleCallbacks
 */
class Orarendhelyettesites {

    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id = 0;

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

    /** @ORM\Column(type="date",nullable=false) */
    private $datum;

    /**
     * @ORM\ManyToOne(targetEntity="Orarend")
     * @ORM\JoinColumn(name="orarend_id",referencedColumnName="id",nullable=false,onDelete="restrict")
     */
    private $orarend;

    /**
     * @ORM\ManyToOne(targetEntity="Dolgozo")
     * @ORM\JoinColumn(name="helyettesito_id",referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $helyettesito;

    /** @ORM\Column(type="boolean", nullable=false) */
    private $inaktiv = false;

    /** @ORM\Column(type="boolean", nullable=false) */
    private $elmarad = false;

    public function getOrarend() {
        return $this->orarend;
    }

    public function getOrarendNev() {
        if ($this->orarend) {
            return $this->orarend->getNevTanar();
        }
        return '';
    }

    public function getOrarendId() {
        if ($this->orarend) {
            return $this->orarend->getId();
        }
        return '';
    }

    public function setOrarend($orarend) {
        $this->orarend = $orarend;
    }

    public function getHelyettesito() {
        return $this->helyettesito;
    }

    public function getHelyettesitoNev() {
        if ($this->helyettesito) {
            return $this->helyettesito->getNev();
        }
        return '';
    }

    public function getHelyettesitoId() {
        if ($this->helyettesito) {
            return $this->helyettesito->getId();
        }
        return '';
    }

    public function setHelyettesito($helyettesito) {
        $this->helyettesito = $helyettesito;
    }

    /**
     * @return mixed
     */
    public function getDatum() {
        return $this->datum;
    }

    public function getDatumStr() {
        if ($this->getDatum()) {
            return $this->getDatum()->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    /**
     * @param mixed $datum
     */
    public function setDatum($datum) {
        if (is_a($datum, 'DateTime')) {
            $this->datum = $datum;
        }
        else {
            if ($datum == '') {
                $datum = date(\mkw\store::$DateFormat);
            }
            $this->datum = new \DateTime(\mkw\store::convDate($datum));
        }
    }

    /**
     * @return mixed
     */
    public function getInaktiv() {
        return $this->inaktiv;
    }

    /**
     * @param mixed $inaktiv
     */
    public function setInaktiv($inaktiv) {
        $this->inaktiv = $inaktiv;
    }

    public function getId() {
        return $this->id;
    }

    public function getHelyettesitoUrl() {
        if ($this->helyettesito) {
            return $this->helyettesito->getUrl();
        }
        return '';
    }

    /**
     * @return mixed
     */
    public function getElmarad() {
        return $this->elmarad;
    }

    /**
     * @param mixed $elmarad
     */
    public function setElmarad($elmarad) {
        $this->elmarad = $elmarad;
    }
}