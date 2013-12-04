<?php

namespace Entities;

/**
 * @Entity(repositoryClass="Entities\TermekcimketorzsRepository")
 */
class Termekcimketorzs extends Cimketorzs {

    /**
     * @gedmo:Sluggable
     * @Column(type="string",length=255,nullable=false)
     */
    private $nev;

    /**
     * @gedmo:Slug
     * @Column(type="string",length=255,nullable=true)
     */
    private $slug;

    /** @ManyToMany(targetEntity="Termek", mappedBy="cimkek", cascade={"persist"}) */
    private $termekek;

    /**
     * @ManyToOne(targetEntity="Termekcimkekat",inversedBy="cimkek")
     * @JoinColumn(name="cimkekat_id",referencedColumnName="id",onDelete="cascade")
     */
    private $kategoria;

    /** @Column(type="boolean") */
    private $kiemelt = false;

    public function __construct() {
        $this->termekek = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function toLista() {
        $x = array();
        $x['caption'] = $this->getNev();
        $x['slug'] = $this->getSlug();
        $x['kepurl'] = $this->getKepUrlLarge();
        $x['kozepeskepurl'] = $this->getKepUrlMedium();
        $x['kiskepurl'] = $this->getKepUrlSmall();
        $x['kategorianev'] = $this->getKategoria()->getNev();
        $mk = \mkw\Store::getParameter(\mkw\consts::MarkaCs);
        $x['dontshowcaption'] = $mk == $this->getKategoriaId();
        $x['kiemelt'] = $this->getKiemelt();
        $x['termekfilter'] = 'szuro_' . $this->getKategoriaId() . '_' . $this->getId();
        return $x;
    }

    public function getKategoria() {
        return $this->kategoria;
    }

    public function getKategoriaId() {
        if ($this->kategoria) {
            return $this->kategoria->getId();
        }
        return '';
    }

    public function setKategoria(Cimkekat $kategoria) {
        if ($this->kategoria !== $kategoria) {
            $this->kategoria = $kategoria;
            $kategoria->addCimke($this);
        }
    }

    public function removeKategoria() {
        if ($this->kategoria !== null) {
            $kategoria = $this->kategoria;
            $this->kategoria = null;
            $kategoria->removeCimke($this);
        }
    }

    public function getNev() {
        return $this->nev;
    }

    public function setNev($nev) {
        $this->nev = $nev;
    }

    public function getSlug() {
        return $this->slug;
    }

    public function setSlug($slug) {
        $this->slug = $slug;
    }

    public function getTermekek() {
        return $this->termekek;
    }

    public function addTermek(Termek $termek) {
//		if (!$this->termekek->contains($termek)) {  // deleted for speed
        $this->termekek->add($termek);
        $termek->addCimke($this);
//		}
    }

    public function removeTermek(Termek $termek) {
        // TODO ha sok termeknek van ilyen cimkeje, akkor lassu lesz
        if ($this->termekek->removeElement($termek)) {
            $termek->removeCimke($this);
            return true;
        }
        return false;
    }

    public function getKiemelt() {
        return $this->kiemelt;
    }

    public function setKiemelt($adat) {
        $this->kiemelt = $adat;
    }
}
