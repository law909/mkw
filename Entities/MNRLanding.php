<?php
namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection;
use mkw\store;

/** @ORM\Entity(repositoryClass="Entities\MNRLandingRepository")
 * @ORM\Table(name="mnrlanding",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 * @Gedmo\TranslationEntity(class="Entities\MNRLandingTranslation")
 * */
class MNRLanding {

    private static $translatedFields = array(
        'nev' => array('caption' => 'Szöveg', 'type' => 1),
        'szlogen' => array('caption' => 'Szlogen', 'type' => 1)
    );

    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    private $nev = '';

    /** @ORM\Column(type="text",nullable=true) */
    private $kepurl = '';

    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    private $szlogen = '';

    /** @Gedmo\Locale */
    protected $locale;

    /** @ORM\OneToMany(targetEntity="MNRLandingTranslation", mappedBy="object", cascade={"persist", "remove"}) */
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

    public function toPublic() {
        $ret = array();
        $ret['id'] = $this->getId();
        $ret['nev'] = $this->getNev();
        $ret['szlogen'] = $this->getSzlogen();
        $ret['kepurl'] = $this->getKepurl();
        $ret['translations'] = $this->getTranslationsArray();
        return $ret;
    }

    public function toLista() {
        $ret = array();
        $ret['id'] = $this->getId();
        $ret['nev'] = $this->getNev();
        $ret['szlogen'] = $this->getSzlogen();
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

    public function getTranslations() {
        return $this->translations;
    }

    public function getTranslationsArray() {
        $r = array();
        /** @var \Entities\MNRLandingTranslation $tr */
        foreach ($this->translations as $tr) {
            $r[$tr->getLocale()][$tr->getField()] = $tr->getContent();
        }
        return $r;
    }

    public function addTranslation(MNRLandingTranslation $t) {
        if (!$this->translations->contains($t)) {
            $this->translations[] = $t;
            $t->setObject($this);
        }
    }

    public function removeTranslation(MNRLandingTranslation $t) {
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
    public function getSzlogen() {
        return $this->szlogen;
    }

    /**
     * @param string $szlogen
     */
    public function setSzlogen($szlogen): void {
        $this->szlogen = $szlogen;
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
