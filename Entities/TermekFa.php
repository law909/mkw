<?php

namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use mkw\store;

/**
 * @ORM\Entity(repositoryClass="Entities\TermekFaRepository")
 * @ORM\Table(name="termekfa",
 * options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"},
 * indexes={
 *      @ORM\index(name="termekfaslug_idx",columns={"slug"}),
 *      @ORM\index(name="termekfanevparent_idx",columns={"nev","parent_id"}),
 *      @ORM\index(name="termekfaidegenkod_idx",columns={"idegenkod"})
 * })
 * @Gedmo\TranslationEntity(class="Entities\TermekFaTranslation")
 */
class TermekFa {

    private static $translatedFields = array(
        'nev' => array('caption' => 'Név', 'type' => 1),
        'rovidleiras' => array('caption' => 'Rövid leírás', 'type' => 1),
        'leiras' => array('caption' => 'Leírás', 'type' => 2),
        'leiras2' => array('caption' => 'Leírás 2', 'type' => 2)
    );

    private $gtnev;
    public $m1lchanged = false;
    public $m2lchanged = false;
    public $m3lchanged = false;
    public $m4lchanged = false;

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
     * @ORM\OneToMany(targetEntity="TermekFa", mappedBy="parent")
     * @ORM\OrderBy({"sorrend"="ASC","nev"="ASC"})
     */
    private $children;

    /**
     * @ORM\ManyToOne(targetEntity="TermekFa", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id",onDelete="restrict")
     */
    private $parent;

    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="string",length=255,nullable=false)
     */
    private $nev;

    /** @ORM\Column(type="integer",nullable=true) */
    private $sorrend;

    /**
     * @Gedmo\Slug(fields={"nev"})
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    private $slug;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $karkod;

    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    private $rovidleiras = '';

    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="text",nullable=true)
     */
    private $leiras;

    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="text",nullable=true)
     */
    private $leiras2;

    /** @ORM\Column(type="boolean",nullable=true) */
    private $menu1lathato = true;

    /** @ORM\Column(type="boolean",nullable=true) */
    private $menu2lathato = false;

    /** @ORM\Column(type="boolean",nullable=true) */
    private $menu3lathato = false;

    /** @ORM\Column(type="boolean",nullable=true) */
    private $menu4lathato = false;

    /**
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    private $oldalcim;

    /** @ORM\Column(type="text",nullable=true) */
    private $seodescription;

    /** @ORM\Column(type="text",nullable=true) */
    private $kepurl;

    /** @ORM\Column(type="text",nullable=true) */
    private $kepleiras;

    /**
     * @ORM\OneToMany(targetEntity="Termek",mappedBy="termekfa1")
     */
    private $termekek1;

    /**
     * @ORM\OneToMany(targetEntity="Termek",mappedBy="termekfa2")
     */
    private $termekek2;

    /**
     * @ORM\OneToMany(targetEntity="Termek",mappedBy="termekfa3")
     */
    private $termekek3;

    /** @ORM\Column(type="boolean",nullable=true) */
    private $inaktiv = false;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $idegenkod = '';

    /** @ORM\OneToMany(targetEntity="TermekFaTranslation", mappedBy="object", cascade={"persist", "remove"}) */
    private $translations;

    /** @ORM\Column(type="integer",nullable=true) */
    private $emagid;

    /**
     * @Gedmo\Locale
     */
    private $locale;

    public function __toString() {
        return (string)$this->id . ' - ' . $this->nev;
    }

    public static function getTranslatedFields() {
        return self::$translatedFields;
    }

    public static function getTranslatedFieldsSelectList($sel = null) {
        $ret = array();
        foreach(self::$translatedFields as $k => $v) {
            $ret[] = array(
                'id' => $k,
                'caption' => $v['caption'],
                'selected' => ($k === $sel)
            );
        }
        return $ret;
    }

    public function __construct() {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        $this->termekek1 = new \Doctrine\Common\Collections\ArrayCollection();
        $this->termekek2 = new \Doctrine\Common\Collections\ArrayCollection();
        $this->termekek3 = new \Doctrine\Common\Collections\ArrayCollection();
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId() {
        return $this->id;
    }

    public function getChildren() {
        return $this->children;
    }

    public function addChild(TermekFa $child) {
        if (!$this->children->contains($child)) {
            $this->children->add($child);
            $child->setParent($this);
        }
    }

    public function removeChild(TermekFa $child) {
        if ($this->children->removeElement($child)) {
            $child->removeParent();
            return true;
        }
        return false;
    }

    public function getParent() {
        return $this->parent;
    }

    public function getParentId() {
        if ($this->parent) {
            return $this->parent->getId();
        }
        return 0;
    }

    public function getParentNev() {
        if ($this->parent) {
            return $this->parent->getNev();
        }
        return '';
    }

    public function setParent(TermekFa $parent) {
        if ($this->parent !== $parent) {
            $this->parent = $parent;
            $parent->addChild($this);
            $this->setKarkod($parent->getKarkod() . $this->getId());
        }
    }

    public function removeParent() {
        if ($this->parent !== null) {
            $parent = $this->parent;
            $this->parent = null;
            $parent->removeChild($this);
            $this->setKarkod(null);
        }
    }

    private function gtn($level, $elval) {
        if ($level && $level->getParent()) {
            $this->gtnev = $level->getNev() . $elval . $this->gtnev;
            $this->gtn($level->getParent(), $elval);
        }
    }

    public function getTeljesNev($elval = '|') {
        $this->gtnev = $this->getNev();
        $this->gtn($this->getParent(), $elval);
        return $this->gtnev;
    }

    public function getNev() {
        return $this->nev;
    }

    public function setNev($nev) {
        $this->nev = $nev;
    }

    public function getSorrend() {
        return $this->sorrend;
    }

    public function setSorrend($sorrend) {
        $this->sorrend = $sorrend;
    }

    public function getSlug() {
        return $this->slug;
    }

    public function getMenu1lathato() {
        return $this->menu1lathato;
    }

    public function setMenu1lathato($menu1lathato) {
        $this->menu1lathato = $menu1lathato;
    }

    public function getMenu2lathato() {
        return $this->menu2lathato;
    }

    public function setMenu2lathato($menu2lathato) {
        $this->menu2lathato = $menu2lathato;
    }

    public function getMenu3lathato() {
        return $this->menu3lathato;
    }

    public function setMenu3lathato($menu3lathato) {
        $this->menu3lathato = $menu3lathato;
    }

    public function getMenu4lathato() {
        return $this->menu4lathato;
    }

    public function setMenu4lathato($menu4lathato) {
        $this->menu4lathato = $menu4lathato;
    }

    public function getLeiras() {
        return $this->leiras;
    }

    public function setLeiras($leiras) {
        $this->leiras = $leiras;
    }

    public function getLeiras2() {
        return $this->leiras2;
    }

    public function setLeiras2($leiras) {
        $this->leiras2 = $leiras;
    }

    public function getKarkod() {
        return $this->karkod;
    }

    public function setKarkod($karkod) {
        $this->karkod = $karkod;
    }

    public function getOldalcim() {
        return $this->oldalcim;
    }

    public function getShowOldalcim() {
        if ($this->oldalcim) {
            return $this->oldalcim;
        }
        else {
            $result = store::getParameter(\mkw\consts::Katoldalcim);
            if ($result) {
                $result = str_replace('[kategorianev]', $this->getNev(), $result);
                $result = str_replace('[global]', store::getParameter(\mkw\consts::Oldalcim), $result);
                return $result;
            }
            else {
                return store::getParameter(\mkw\consts::Oldalcim);
            }
        }
    }

    public function setOldalcim($oldalcim) {
        $this->oldalcim = $oldalcim;
    }

    public function getSeodescription() {
        return $this->seodescription;
    }

    public function getShowSeodescription() {
        if ($this->seodescription) {
            return $this->seodescription;
        }
        else {
            $result = store::getParameter(\mkw\consts::Katseodescription);
            if ($result) {
                $result = str_replace('[kategorianev]', $this->getNev(), $result);
                $result = str_replace('[global]', store::getParameter(\mkw\consts::Seodescription), $result);
                return $result;
            }
            else {
                return store::getParameter(\mkw\consts::Seodescription);
            }
        }
    }

    public function setSeodescription($seodescription) {
        $this->seodescription = $seodescription;
    }

    public function getKepurl($pre = '/') {
        if ($this->kepurl) {
            if ($this->kepurl[0] !== $pre) {
                return $pre . $this->kepurl;
            }
            return $this->kepurl;
        }
        return '';
    }

    public function getKepurlMini($pre = '/') {
        $kepurl = $this->getKepurl($pre);
        if ($kepurl) {
            $t = explode('.', $kepurl);
            $ext = array_pop($t);
            return implode('.', $t) . store::getParameter(\mkw\consts::Miniimgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function getKepurlSmall($pre = '/') {
        $kepurl = $this->getKepurl($pre);
        if ($kepurl) {
            $t = explode('.', $kepurl);
            $ext = array_pop($t);
            return implode('.', $t) . store::getParameter(\mkw\consts::Smallimgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function getKepurlMedium($pre = '/') {
        $kepurl = $this->getKepurl($pre);
        if ($kepurl) {
            $t = explode('.', $kepurl);
            $ext = array_pop($t);
            return implode('.', $t) . store::getParameter(\mkw\consts::Mediumimgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function getKepurlLarge($pre = '/') {
        $kepurl = $this->getKepurl($pre);
        if ($kepurl) {
            $t = explode('.', $kepurl);
            $ext = array_pop($t);
            return implode('.', $t) . store::getParameter(\mkw\consts::Bigimgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function setKepurl($kepurl) {
        $this->kepurl = $kepurl;
        if (!$kepurl) {
            $this->setKepleiras(null);
        }
    }

    public function getKepleiras() {
        return $this->kepleiras;
    }

    public function setKepleiras($kepleiras) {
        $this->kepleiras = $kepleiras;
    }

    public function getTermekek1() {
        return $this->termekek1;
    }

    public function addTermek1(Termek $termek) {
        if (!$this->termekek1->contains($termek)) {
            $this->termekek1->add($termek);
            $termek->setTermekfa1($this);
        }
    }

    public function removeTermek1(Termek $termek) {
        if ($this->termekek1->removeElement($termek)) {
            $termek->setTermekfa1(null);
            return true;
        }
        return false;
    }

    public function getTermekek2() {
        return $this->termekek2;
    }

    public function addTermek2(Termek $termek) {
        if (!$this->termekek2->contains($termek)) {
            $this->termekek2->add($termek);
            $termek->setTermekfa2($this);
        }
    }

    public function removeTermek2(Termek $termek) {
        if ($this->termekek2->removeElement($termek)) {
            $termek->setTermekfa2(null);
            return true;
        }
        return false;
    }

    public function getTermekek3() {
        return $this->termekek3;
    }

    public function addTermek3(Termek $termek) {
        if (!$this->termekek3->contains($termek)) {
            $this->termekek3->add($termek);
            $termek->setTermekfa3($this);
        }
    }

    public function removeTermek3(Termek $termek) {
        if ($this->termekek3->removeElement($termek)) {
            $termek->setTermekfa3(null);
            return true;
        }
        return false;
    }

    public function isDeletable() {
        return ($this->children->isEmpty()) && ($this->termekek1->isEmpty()) && ($this->termekek2->isEmpty()) && ($this->termekek3->isEmpty());
    }

    public function getLastmod() {
        return $this->lastmod;
    }

    public function getCreated() {
        return $this->created;
    }

    public function getRovidleiras() {
        return $this->rovidleiras;
    }

    public function setRovidleiras($rovidleiras) {
        $this->rovidleiras = $rovidleiras;
    }

    public function getInaktiv() {
        return $this->inaktiv;
    }

    public function setInaktiv($i) {
        $this->inaktiv = $i;
    }

    public function getIdegenkod() {
        return $this->idegenkod;
    }

    public function setIdegenkod($idegenkod) {
        $this->idegenkod = $idegenkod;
    }

    public function setTranslatableLocale($l) {
        $this->locale = $l;
    }

    public function getTranslations() {
        return $this->translations;
    }

    public function addTranslation(TermekFaTranslation $t) {
        if (!$this->translations->contains($t)) {
            $this->translations[] = $t;
            $t->setObject($this);
        }
    }

    public function removeTranslation(TermekFaTranslation $t) {
        $this->translations->removeElement($t);
    }

    /**
     * @return mixed
     */
    public function getEmagid() {
        return $this->emagid;
    }

    /**
     * @param mixed $emagid
     */
    public function setEmagid($emagid) {
        $this->emagid = $emagid;
    }

}