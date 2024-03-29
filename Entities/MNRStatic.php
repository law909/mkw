<?php

namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection;
use mkw\store;

/** @ORM\Entity(repositoryClass="Entities\MNRStaticRepository")
 * @ORM\Table(name="mnrstatic",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 * @Gedmo\TranslationEntity(class="Entities\MNRStaticTranslation")
 * */
class MNRStatic
{

    private static $translatedFields = [
        'szlogen1' => ['caption' => 'Szlogen 1', 'type' => 1],
        'szlogen2' => ['caption' => 'Szlogen 2', 'type' => 1]
    ];

    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $tipus = 1;

    /**
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    private $nev = '';

    /**
     * @Gedmo\Slug(fields={"nev"})
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    private $slug;

    /** @ORM\Column(type="text",nullable=true) */
    private $kepurl = '';

    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    private $szlogen1 = '';

    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    private $szlogen2 = '';

    /** @Gedmo\Locale */
    protected $locale;

    /** @ORM\OneToMany(targetEntity="MNRStaticPage", mappedBy="mnrstatic",cascade={"persist"}) */
    private $mnrstaticpages;

    /** @ORM\OneToMany(targetEntity="MNRStaticTranslation", mappedBy="object", cascade={"persist", "remove"}) */
    private $translations;

    public function __construct()
    {
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->mnrstaticpages = new \Doctrine\Common\Collections\ArrayCollection();
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

    public function toPublic()
    {
        $ret = [];
        $ret['id'] = $this->getId();
        $ret['nev'] = $this->getNev();
        $ret['szlogen1'] = $this->getSzlogen1();
        $ret['szlogen2'] = $this->getSzlogen2();
        $ret['kepurl'] = $this->getKepurl();
        $ret['tipus'] = $this->getTipus();
        $ret['translations'] = $this->getTranslationsArray();
        /** @var MNRStaticPage $page */
        foreach ($this->getMNRStaticPages() as $page) {
            $ret['pages'][] = $page->toPublic();
        }
        return $ret;
    }

    public function toLista()
    {
        $ret = [];
        $ret['id'] = $this->getId();
        $ret['nev'] = $this->getNev();
        $ret['szlogen1'] = $this->getSzlogen1();
        $ret['szlogen2'] = $this->getSzlogen2();
        $ret['kepurl'] = $this->getKepurl();
        $ret['tipus'] = $this->getTipus();
        return $ret;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getKepurl($pre = '/')
    {
        if ($this->kepurl) {
            if ($this->kepurl[0] !== $pre) {
                return $pre . $this->kepurl;
            } else {
                return $this->kepurl;
            }
        }
        return '';
    }

    public function getKepurlMini($pre = '/')
    {
        $kepurl = $this->getKepurl($pre);
        if ($kepurl) {
            $t = explode('.', $kepurl);
            $ext = array_pop($t);
            return implode('.', $t) . \mkw\store::getParameter(\mkw\consts::Miniimgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function getKepurlSmall($pre = '/')
    {
        $kepurl = $this->getKepurl($pre);
        if ($kepurl) {
            $t = explode('.', $kepurl);
            $ext = array_pop($t);
            return implode('.', $t) . \mkw\store::getParameter(\mkw\consts::Smallimgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function getKepurlMedium($pre = '/')
    {
        $kepurl = $this->getKepurl($pre);
        if ($kepurl) {
            $t = explode('.', $kepurl);
            $ext = array_pop($t);
            return implode('.', $t) . \mkw\store::getParameter(\mkw\consts::Mediumimgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function getKepurlLarge($pre = '/')
    {
        $kepurl = $this->getKepurl($pre);
        if ($kepurl) {
            $t = explode('.', $kepurl);
            $ext = array_pop($t);
            return implode('.', $t) . \mkw\store::getParameter(\mkw\consts::Bigimgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function setKepurl($kepurl)
    {
        $this->kepurl = $kepurl;
    }

    public function getMNRStaticPages()
    {
        return $this->mnrstaticpages;
    }

    public function addMNRStaticPage(MNRStaticPage $page)
    {
        if (!$this->mnrstaticpages->contains($page)) {
            $this->mnrstaticpages[] = $page;
            $page->setMNRStatic($this);
        }
    }

    public function removeMNRStaticPage(MNRStaticPage $page)
    {
        $this->mnrstaticpages->removeElement($page);
    }

    public function getTranslations()
    {
        return $this->translations;
    }

    public function getTranslationsArray()
    {
        $r = [];
        /** @var \Entities\MNRStaticTranslation $tr */
        foreach ($this->translations as $tr) {
            $r[$tr->getLocale()][$tr->getField()] = $tr->getContent();
        }
        return $r;
    }

    public function addTranslation(MNRStaticTranslation $t)
    {
        if (!$this->translations->contains($t)) {
            $this->translations[] = $t;
            $t->setObject($this);
        }
    }

    public function removeTranslation(MNRStaticTranslation $t)
    {
        $this->translations->removeElement($t);
    }

    public function getLocale()
    {
        return $this->locale;
    }

    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * @return string
     */
    public function getSzlogen1()
    {
        return $this->szlogen1;
    }

    /**
     * @param string $szlogen1
     */
    public function setSzlogen1($szlogen1): void
    {
        $this->szlogen1 = $szlogen1;
    }

    /**
     * @return string
     */
    public function getSzlogen2()
    {
        return $this->szlogen2;
    }

    /**
     * @param string $szlogen2
     */
    public function setSzlogen2($szlogen2): void
    {
        $this->szlogen2 = $szlogen2;
    }

    /**
     * @return string
     */
    public function getNev()
    {
        return $this->nev;
    }

    /**
     * @param string $nev
     */
    public function setNev($nev): void
    {
        $this->nev = $nev;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug($adat)
    {
        $this->slug = $adat;
    }

    /**
     * @return int
     */
    public function getTipus()
    {
        return $this->tipus;
    }

    /**
     * @param int $tipus
     */
    public function setTipus($tipus): void
    {
        $this->tipus = $tipus;
    }

}