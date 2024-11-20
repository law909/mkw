<?php

namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use mkw\store;

/**
 * @ORM\Entity(repositoryClass="Entities\TermekMenuRepository")
 * @ORM\Table(name="termekmenu",
 * options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"},
 * indexes={
 *      @ORM\index(name="termekmenuslug_idx",columns={"slug"}),
 *      @ORM\index(name="termekmenunevparent_idx",columns={"nev","parent_id"}),
 *      @ORM\index(name="termekmenuidegenkod_idx",columns={"idegenkod"})
 * })
 * @Gedmo\TranslationEntity(class="Entities\TermekMenuTranslation")
 */
class TermekMenu
{

    private static $translatedFields = [
        'nev' => ['caption' => 'Név', 'type' => 1],
        'rovidleiras' => ['caption' => 'Rövid leírás', 'type' => 1],
        'leiras' => ['caption' => 'Leírás', 'type' => 2],
        'leiras2' => ['caption' => 'Leírás 2', 'type' => 2],
        'leiras3' => ['caption' => 'Leírás 3', 'type' => 2]
    ];

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
     * @ORM\OneToMany(targetEntity="TermekMenu", mappedBy="parent")
     * @ORM\OrderBy({"sorrend"="ASC","nev"="ASC"})
     */
    private $children;

    /**
     * @ORM\ManyToOne(targetEntity="TermekMenu", inversedBy="children")
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

    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="text",nullable=true)
     */
    private $leiras3;

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

    /** @ORM\Column(type="integer",nullable=true) */
    private $kepwcid;

    /**
     * @ORM\OneToMany(targetEntity="Termek",mappedBy="termekmenu1")
     */
    private $termekek1;

    /** @ORM\Column(type="boolean",nullable=true) */
    private $inaktiv = false;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $idegenkod = '';

    /** @ORM\OneToMany(targetEntity="TermekMenuTranslation", mappedBy="object", cascade={"persist", "remove"}) */
    private $translations;

    /** @ORM\Column(type="integer",nullable=true) */
    private $emagid;

    /**
     * @Gedmo\Locale
     */
    private $locale;

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

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $arukeresoid;

    /** @ORM\Column(type="integer", nullable=true) */
    private $wcid;

    /** @ORM\Column(type="datetime", nullable=true) */
    private $wcdate;

    public function __toString()
    {
        return (string)$this->id . ' - ' . $this->nev;
    }

    public static function getTranslatedFields()
    {
        return self::$translatedFields;
    }

    public static function getTranslatedFieldsSelectList($sel = null)
    {
        $ret = [];
        foreach (self::$translatedFields as $k => $v) {
            $ret[] = [
                'id' => $k,
                'caption' => $v['caption'],
                'selected' => ($k === $sel)
            ];
        }
        return $ret;
    }

    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        $this->termekek1 = new \Doctrine\Common\Collections\ArrayCollection();
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function toA2a()
    {
        $ford = $this->getTranslationsArray();
        $x = [];
        $x['id'] = $this->getId();
        $x['nev'] = $this->getNev();
        $x['nev_en'] = $ford['en_us']['nev'];
        $x['nev_it'] = $ford['it_it']['nev'];
        $x['rovidleiras'] = $this->getRovidleiras();
        $x['rovidleiras_en'] = $ford['en_us']['rovidleiras'];
        $x['rovidleiras_it'] = $ford['it_it']['rovidleiras'];
        $x['leiras'] = $this->getLeiras();
        $x['leiras_en'] = $ford['en_us']['leiras'];
        $x['leiras_it'] = $ford['it_it']['leiras'];
        $x['leiras2'] = $this->getLeiras2();
        $x['leiras2_en'] = $ford['en_us']['leiras2'];
        $x['leiras2_it'] = $ford['it_it']['leiras2'];
        $x['leiras3'] = $this->getLeiras3();
        $x['leiras3_en'] = $ford['en_us']['leiras3'];
        $x['leiras3_it'] = $ford['it_it']['leiras3'];
        $x['karkod'] = $this->getKarkod();
        $x['sorrend'] = $this->getSorrend();
        if ($this->getKepurl()) {
            $x['kepurl'] = \mkw\store::getFullUrl($this->getKepurlLarge());
            $x['kozepeskepurl'] = \mkw\store::getFullUrl($this->getKepurlMedium());
            $x['kiskepurl'] = \mkw\store::getFullUrl($this->getKepurlSmall());
            $x['minikepurl'] = \mkw\store::getFullUrl($this->getKepurlMini());
            $x['kepleiras'] = $this->getKepleiras();
        } else {
            $x['kepurl'] = null;
            $x['kozepeskepurl'] = null;
            $x['kiskepurl'] = null;
            $x['minikepurl'] = null;
            $x['kepleiras'] = null;
        }
        return $x;
    }

    public function toLista()
    {
        $x = [];
        $x['id'] = $this->getId();
        $x['nev'] = $this->getNev();
        $x['slug'] = $this->getSlug();
        $x['rovidleiras'] = $this->getRovidleiras();
        $x['leiras'] = $this->getLeiras();
        $x['leiras2'] = $this->getLeiras2();
        $x['leiras3'] = $this->getLeiras3();
        $x['karkod'] = $this->getKarkod();
        $x['sorrend'] = $this->getSorrend();
        if ($this->getKepurl()) {
            $x['kepurl'] = \mkw\store::getFullUrl($this->getKepurlLarge());
            $x['kozepeskepurl'] = \mkw\store::getFullUrl($this->getKepurlMedium());
            $x['kiskepurl'] = \mkw\store::getFullUrl($this->getKepurlSmall());
            $x['minikepurl'] = \mkw\store::getFullUrl($this->getKepurlMini());
            $x['kepleiras'] = $this->getKepleiras();
        } else {
            $x['kepurl'] = null;
            $x['kozepeskepurl'] = null;
            $x['kiskepurl'] = null;
            $x['minikepurl'] = null;
            $x['kepleiras'] = null;
        }
        return $x;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function addChild(TermekMenu $child)
    {
        if (!$this->children->contains($child)) {
            $this->children->add($child);
            $child->setParent($this);
        }
    }

    public function removeChild(TermekMenu $child)
    {
        if ($this->children->removeElement($child)) {
            $child->removeParent();
            return true;
        }
        return false;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function getParentId()
    {
        if ($this->parent) {
            return $this->parent->getId();
        }
        return 0;
    }

    public function getParentNev()
    {
        if ($this->parent) {
            return $this->parent->getNev();
        }
        return '';
    }

    public function setParent(TermekMenu $parent)
    {
        if ($this->parent !== $parent) {
            $this->parent = $parent;
            $parent->addChild($this);
            $this->setKarkod($parent->getKarkod() . $this->getId());
        }
    }

    public function removeParent()
    {
        if ($this->parent !== null) {
            $parent = $this->parent;
            $this->parent = null;
            $parent->removeChild($this);
            $this->setKarkod(null);
        }
    }

    private function gtn($level, $elval)
    {
        if ($level && $level->getParent()) {
            $this->gtnev = $level->getNev() . $elval . $this->gtnev;
            $this->gtn($level->getParent(), $elval);
        }
    }

    public function getTeljesNev($elval = '|', $selfname = null)
    {
        if (!$selfname) {
            $selfname = $this->getNev();
        }
        $this->gtnev = $selfname;
        $this->gtn($this->getParent(), $elval);
        return $this->gtnev;
    }

    public function getNev()
    {
        return $this->nev;
    }

    public function setNev($nev)
    {
        $this->nev = $nev;
    }

    public function getSorrend()
    {
        return $this->sorrend;
    }

    public function setSorrend($sorrend)
    {
        $this->sorrend = $sorrend;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function getMenu1lathato()
    {
        return $this->menu1lathato;
    }

    public function setMenu1lathato($menu1lathato)
    {
        $this->menu1lathato = $menu1lathato;
    }

    public function getMenu2lathato()
    {
        return $this->menu2lathato;
    }

    public function setMenu2lathato($menu2lathato)
    {
        $this->menu2lathato = $menu2lathato;
    }

    public function getMenu3lathato()
    {
        return $this->menu3lathato;
    }

    public function setMenu3lathato($menu3lathato)
    {
        $this->menu3lathato = $menu3lathato;
    }

    public function getMenu4lathato()
    {
        return $this->menu4lathato;
    }

    public function setMenu4lathato($menu4lathato)
    {
        $this->menu4lathato = $menu4lathato;
    }

    public function getLeiras()
    {
        return $this->leiras;
    }

    public function setLeiras($leiras)
    {
        $this->leiras = $leiras;
    }

    public function getLeiras2()
    {
        return $this->leiras2;
    }

    public function setLeiras2($leiras)
    {
        $this->leiras2 = $leiras;
    }

    public function getLeiras3()
    {
        return $this->leiras3;
    }

    public function setLeiras3($leiras)
    {
        $this->leiras3 = $leiras;
    }

    public function getKarkod()
    {
        return $this->karkod;
    }

    public function setKarkod($karkod)
    {
        $this->karkod = $karkod;
    }

    public function getOldalcim()
    {
        return $this->oldalcim;
    }

    public function getShowOldalcim()
    {
        if ($this->oldalcim) {
            return $this->oldalcim;
        } else {
            $result = store::getParameter(\mkw\consts::Katoldalcim);
            if ($result) {
                $result = str_replace('[kategorianev]', $this->getNev(), $result);
                $result = str_replace('[global]', store::getParameter(\mkw\consts::Oldalcim), $result);
                return $result;
            } else {
                return store::getParameter(\mkw\consts::Oldalcim);
            }
        }
    }

    public function setOldalcim($oldalcim)
    {
        $this->oldalcim = $oldalcim;
    }

    public function getSeodescription()
    {
        return $this->seodescription;
    }

    public function getShowSeodescription()
    {
        if ($this->seodescription) {
            return $this->seodescription;
        } else {
            $result = store::getParameter(\mkw\consts::Katseodescription);
            if ($result) {
                $result = str_replace('[kategorianev]', $this->getNev(), $result);
                $result = str_replace('[global]', store::getParameter(\mkw\consts::Seodescription), $result);
                return $result;
            } else {
                return store::getParameter(\mkw\consts::Seodescription);
            }
        }
    }

    public function setSeodescription($seodescription)
    {
        $this->seodescription = $seodescription;
    }

    public function getKepurl($pre = '/')
    {
        if ($this->kepurl) {
            if ($this->kepurl[0] !== $pre) {
                return $pre . $this->kepurl;
            }
            return $this->kepurl;
        }
        return '';
    }

    public function getKepurlMini($pre = '/')
    {
        $kepurl = $this->getKepurl($pre);
        if ($kepurl) {
            $t = explode('.', $kepurl);
            $ext = array_pop($t);
            return implode('.', $t) . store::getParameter(\mkw\consts::Miniimgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function getKepurlSmall($pre = '/')
    {
        $kepurl = $this->getKepurl($pre);
        if ($kepurl) {
            $t = explode('.', $kepurl);
            $ext = array_pop($t);
            return implode('.', $t) . store::getParameter(\mkw\consts::Smallimgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function getKepurlMedium($pre = '/')
    {
        $kepurl = $this->getKepurl($pre);
        if ($kepurl) {
            $t = explode('.', $kepurl);
            $ext = array_pop($t);
            return implode('.', $t) . store::getParameter(\mkw\consts::Mediumimgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function getKepurlLarge($pre = '/')
    {
        $kepurl = $this->getKepurl($pre);
        if ($kepurl) {
            $t = explode('.', $kepurl);
            $ext = array_pop($t);
            return implode('.', $t) . store::getParameter(\mkw\consts::Bigimgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function setKepurl($kepurl)
    {
        $this->kepurl = $kepurl;
        if (!$kepurl) {
            $this->setKepleiras(null);
        }
    }

    public function getKepleiras()
    {
        return $this->kepleiras;
    }

    public function setKepleiras($kepleiras)
    {
        $this->kepleiras = $kepleiras;
    }

    public function getTermekek1()
    {
        return $this->termekek1;
    }

    public function addTermek1(Termek $termek)
    {
        if (!$this->termekek1->contains($termek)) {
            $this->termekek1->add($termek);
            $termek->setTermekmenu1($this);
        }
    }

    public function removeTermek1(Termek $termek)
    {
        if ($this->termekek1->removeElement($termek)) {
            $termek->setTermekmenu1(null);
            return true;
        }
        return false;
    }

    public function isDeletable()
    {
        return ($this->children->isEmpty()) && ($this->termekek1->isEmpty());
    }

    public function getLastmod()
    {
        return $this->lastmod;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getRovidleiras()
    {
        return $this->rovidleiras;
    }

    public function setRovidleiras($rovidleiras)
    {
        $this->rovidleiras = $rovidleiras;
    }

    public function getInaktiv()
    {
        return $this->inaktiv;
    }

    public function setInaktiv($i)
    {
        $this->inaktiv = $i;
    }

    public function getIdegenkod()
    {
        return $this->idegenkod;
    }

    public function setIdegenkod($idegenkod)
    {
        $this->idegenkod = $idegenkod;
    }

    public function setTranslatableLocale($l)
    {
        $this->locale = $l;
    }

    public function getTranslations()
    {
        return $this->translations;
    }

    public function getTranslationsArray()
    {
        $r = [];
        /** @var \Entities\TermekMenuTranslation $tr */
        foreach ($this->translations as $tr) {
            $r[$tr->getLocale()][$tr->getField()] = $tr->getContent();
        }
        return $r;
    }

    public function addTranslation(TermekMenuTranslation $t)
    {
        if (!$this->translations->contains($t)) {
            $this->translations[] = $t;
            $t->setObject($this);
        }
    }

    public function removeTranslation(TermekMenuTranslation $t)
    {
        $this->translations->removeElement($t);
    }

    /**
     * @return mixed
     */
    public function getEmagid()
    {
        return $this->emagid;
    }

    /**
     * @param mixed $emagid
     */
    public function setEmagid($emagid)
    {
        $this->emagid = $emagid;
    }

    /**
     * @return int
     */
    public function getLathato()
    {
        return $this->lathato;
    }

    /**
     * @param int $lathato
     */
    public function setLathato($lathato): void
    {
        $this->lathato = $lathato;
    }

    /**
     * @return int
     */
    public function getLathato2()
    {
        return $this->lathato2;
    }

    /**
     * @param int $lathato2
     */
    public function setLathato2($lathato2): void
    {
        $this->lathato2 = $lathato2;
    }

    /**
     * @return int
     */
    public function getLathato3()
    {
        return $this->lathato3;
    }

    /**
     * @param int $lathato3
     */
    public function setLathato3($lathato3): void
    {
        $this->lathato3 = $lathato3;
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
        return $this->getNLathato(\mkw\store::getSetupValue('webshopnum', 1));
    }

    public function getNLathato($n)
    {
        switch ($n) {
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

    /**
     * @return mixed
     */
    public function getArukeresoid()
    {
        return $this->arukeresoid;
    }

    /**
     * @param mixed $arukeresoid
     */
    public function setArukeresoid($arukeresoid)
    {
        $this->arukeresoid = $arukeresoid;
    }

    /**
     * @return mixed
     */
    public function getWcid()
    {
        return $this->wcid;
    }

    /**
     * @param mixed $wcid
     */
    public function setWcid($wcid): void
    {
        $this->wcid = $wcid;
    }

    /**
     * @return mixed
     */
    public function getWcdate()
    {
        return $this->wcdate;
    }

    public function getWcdateStr($wcdate)
    {
        return $this->wcdate->format(\mkw\store::$DateTimeFormat);
    }

    /**
     * @param mixed $wcdate
     */
    public function setWcdate($adat = null): void
    {
        if (is_a($adat, 'DateTime')) {
            $this->wcdate = $adat;
        } else {
            if ($adat == '') {
                $adat = date(\mkw\store::$sqlDateTimeFormat);
            }
            $this->wcdate = new \DateTime(\mkw\store::convDate($adat));
        }
    }

    /**
     * @return mixed
     */
    public function getKepwcid()
    {
        return $this->kepwcid;
    }

    /**
     * @param mixed $kepwcid
     */
    public function setKepwcid($kepwcid): void
    {
        $this->kepwcid = $kepwcid;
    }

    public function getNevForditas($ford, $locale)
    {
        if ($ford[$locale]['nev']) {
            return $ford[$locale]['nev'];
        }
        return $this->getNev();
    }

    public function getLeirasForditas($ford, $locale)
    {
        if ($ford[$locale]['leiras']) {
            return $ford[$locale]['leiras'];
        }
        return $this->getLeiras();
    }

}