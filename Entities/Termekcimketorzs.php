<?php

namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\TermekcimketorzsRepository")
 */
class Termekcimketorzs extends Cimketorzs {

    /**
     * @ORM\Column(type="string",length=255,nullable=false)
     */
    private $nev;

    /**
     * @Gedmo\Slug(fields={"nev"})
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    private $slug;

    /** @ORM\ManyToMany(targetEntity="Termek", mappedBy="cimkek", cascade={"persist"}) */
    private $termekek;

    /**
     * @ORM\ManyToOne(targetEntity="Termekcimkekat",inversedBy="cimkek")
     * @ORM\JoinColumn(name="cimkekat_id",referencedColumnName="id",onDelete="cascade")
     */
    private $kategoria;

    /** @ORM\Column(type="boolean") */
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
        $mk = \mkw\store::getParameter(\mkw\consts::MarkaCs);
        $x['dontshowcaption'] = $mk == $this->getKategoriaId();
        $x['ismarka'] = $mk == $this->getKategoriaId();
        $x['kiemelt'] = $this->getKiemelt();
        $x['termekfilter'] = $this->getTermekFilter();
        $x['termeklisturl'] = \mkw\store::getRouter()->generate('showmarka', true, array('slug' => $x['slug']));
        $x['leiras'] = $this->getLeiras();
        return $x;
    }

    public function getTermekFilter() {
        return 'szuro_' . $this->getKategoriaId() . '_' . $this->getId();
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

    public function getKategoriaNev() {
        if ($this->kategoria) {
            return $this->kategoria->getNev();
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
