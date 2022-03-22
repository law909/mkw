<?php

namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection;

/** @ORM\Entity(repositoryClass="Entities\MNRStaticPageRepository")
 * @ORM\Table(name="mnrstaticpage",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 * @Gedmo\TranslationEntity(class="Entities\MNRStaticPageTranslation")
 */
class MNRStaticPage {

    private static $translatedFields = array(
        'szlogen1' => array('caption' => 'Szlogen 1', 'type' => 1),
        'szlogen2' => array('caption' => 'Szlogen 2', 'type' => 1),
        'tartalom' => array('caption' => 'Tartalom', 'type' => 2),
        'szoveg1' => array('caption' => 'Szöveg 1', 'type' => 1),
        'szoveg2' => array('caption' => 'Szöveg 2', 'type' => 1),
        'szoveg3' => array('caption' => 'Szöveg 3', 'type' => 1)
    );

    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    private $nev = '';

    /**
     * @ORM\ManyToOne(targetEntity="MNRStatic",inversedBy="mnrstaticpages")
     * @ORM\JoinColumn(name="mnrstatic_id", referencedColumnName="id",nullable=true,onDelete="cascade")
     * @var \Entities\MNRStatic
     */
    private $mnrstatic;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $hidden = false;

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

    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="text",nullable=true)
     */
    private $tartalom;

    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    private $szoveg1 = '';
    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    private $szoveg2 = '';
    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    private $szoveg3 = '';

    /** @Gedmo\Locale */
    protected $locale;

    /** @ORM\OneToMany(targetEntity="MNRStaticTranslation", mappedBy="object", cascade={"persist", "remove"}) */
    private $translations;

    public function __construct() {
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
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

    public function toLista() {
        $ret = array();
        $ret['hidden'] = $this->isHidden();
        $ret['szlogen1'] = $this->getSzlogen1();
        $ret['szlogen2'] = $this->getSzlogen2();
        $ret['tartalom'] = $this->getTartalom();
        $ret['szoveg1'] = $this->getSzoveg1();
        $ret['szoveg2'] = $this->getSzoveg2();
        $ret['szoveg3'] = $this->getSzoveg3();
        $ret['kepurl'] = $this->getKepurl();
       return $ret;
    }

    public function getId() {
        return $this->id;
    }

    public function getKepurl($pre = '/') {
        if ($this->kepurl) {
            if ($this->kepurl[0] !== $pre) {
                return $pre . $this->kepurl;
            }
            else {
                return $this->kepurl;
            }
        }
        return '';
    }

    public function getKepurlMini($pre = '/') {
        $kepurl = $this->getKepurl($pre);
        if ($kepurl) {
            $t = explode('.', $kepurl);
            $ext = array_pop($t);
            return implode('.', $t) . \mkw\store::getParameter(\mkw\consts::Miniimgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function getKepurlSmall($pre = '/') {
        $kepurl = $this->getKepurl($pre);
        if ($kepurl) {
            $t = explode('.', $kepurl);
            $ext = array_pop($t);
            return implode('.', $t) . \mkw\store::getParameter(\mkw\consts::Smallimgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function getKepurlMedium($pre = '/') {
        $kepurl = $this->getKepurl($pre);
        if ($kepurl) {
            $t = explode('.', $kepurl);
            $ext = array_pop($t);
            return implode('.', $t) . \mkw\store::getParameter(\mkw\consts::Mediumimgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function getKepurlLarge($pre = '/') {
        $kepurl = $this->getKepurl($pre);
        if ($kepurl) {
            $t = explode('.', $kepurl);
            $ext = array_pop($t);
            return implode('.', $t) . \mkw\store::getParameter(\mkw\consts::Bigimgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function setKepurl($kepurl) {
        $this->kepurl = $kepurl;
    }

    /**
     * @return MNRStatic
     */
    public function getMNRStatic() {
        return $this->mnrstatic;
    }

    public function getMNRStaticId() {
        if ($this->mnrstatic) {
            return $this->mnrstatic->getId();
        }
        return '';
    }

    /**
     * @param \Entities\MNRStatic $val
     */
    public function setMNRStatic($val) {
        if ($this->mnrstatic !== $val) {
            $this->mnrstatic = $val;
            $val->addMNRStaticPage($this);
        }
    }

    public function removeMNRStatic() {
        if ($this->mnrstatic !== null) {
            $val = $this->mnrstatic;
            $this->mnrstatic = null;
            $val->removeMNRStaticPage($this);
        }
    }

    /**
     * @return bool
     */
    public function isHidden() {
        return $this->hidden;
    }

    /**
     * @param bool $hidden
     */
    public function setHidden($hidden): void {
        $this->hidden = $hidden;
    }

    /**
     * @return string
     */
    public function getSzlogen1() {
        return $this->szlogen1;
    }

    /**
     * @param string $szlogen1
     */
    public function setSzlogen1($szlogen1): void {
        $this->szlogen1 = $szlogen1;
    }

    /**
     * @return string
     */
    public function getSzlogen2() {
        return $this->szlogen2;
    }

    /**
     * @param string $szlogen2
     */
    public function setSzlogen2($szlogen2): void {
        $this->szlogen2 = $szlogen2;
    }

    /**
     * @return mixed
     */
    public function getTartalom() {
        return $this->tartalom;
    }

    /**
     * @param mixed $tartalom
     */
    public function setTartalom($tartalom): void {
        $this->tartalom = $tartalom;
    }

    /**
     * @return string
     */
    public function getSzoveg1() {
        return $this->szoveg1;
    }

    /**
     * @param string $szoveg1
     */
    public function setSzoveg1($szoveg1): void {
        $this->szoveg1 = $szoveg1;
    }

    /**
     * @return string
     */
    public function getSzoveg2() {
        return $this->szoveg2;
    }

    /**
     * @param string $szoveg2
     */
    public function setSzoveg2($szoveg2): void {
        $this->szoveg2 = $szoveg2;
    }

    /**
     * @return string
     */
    public function getSzoveg3() {
        return $this->szoveg3;
    }

    /**
     * @param string $szoveg3
     */
    public function setSzoveg3($szoveg3): void {
        $this->szoveg3 = $szoveg3;
    }

    public function getTranslations() {
        return $this->translations;
    }

    public function getTranslationsArray() {
        $r = array();
        /** @var \Entities\MNRStaticPageTranslation $tr */
        foreach ($this->translations as $tr) {
            $r[$tr->getLocale()][$tr->getField()] = $tr->getContent();
        }
        return $r;
    }

    public function addTranslation(MNRStaticPageTranslation $t) {
        if (!$this->translations->contains($t)) {
            $this->translations[] = $t;
            $t->setObject($this);
        }
    }

    public function removeTranslation(MNRStaticPageTranslation $t) {
        $this->translations->removeElement($t);
    }

    public function getLocale() {
        return $this->locale;
    }

    public function setLocale($locale) {
        $this->locale = $locale;
    }

    /**
     * @return string
     */
    public function getNev() {
        return $this->nev;
    }

    /**
     * @param string $nev
     */
    public function setNev($nev): void {
        $this->nev = $nev;
    }

}