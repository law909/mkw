<?php

namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\StatlapRepository")
 * @ORM\Table(name="statlap",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 * @Gedmo\TranslationEntity(class="Entities\StatlapTranslation")
 */
class Statlap {

    private static $translatedFields = array(
        'oldalcim' => array('caption' => 'Oldalcím', 'type' => 1),
        'szoveg' => array('caption' => 'Szöveg', 'type' => 2)
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
    private $oldalcim;

    /**
     * @Gedmo\Slug(fields={"oldalcim"})
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    private $slug;

    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="text",nullable=true)
     */
    private $szoveg;

    /** @ORM\Column(type="text",nullable=true) */
    private $seodescription;

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

    /** @ORM\Column(type="string", length=255, nullable=true) */
    private $oldurl;

    /** @Gedmo\Locale */
    protected $locale;

    /** @ORM\OneToMany(targetEntity="StatlapTranslation", mappedBy="object", cascade={"persist", "remove"}) */
    private $translations;


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
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId() {
        return $this->id;
    }

    public function getOldalcim() {
        return $this->oldalcim;
    }

    public function getShowOldalcim() {
        return $this->oldalcim . ' - ' . \mkw\store::getParameter(\mkw\consts::Oldalcim);
    }

    public function setOldalcim($adat) {
        $this->oldalcim = $adat;
    }

    public function getSlug() {
        return $this->slug;
    }

    public function setSlug($adat) {
        $this->slug = $adat;
    }

    public function getSzoveg() {
        return $this->szoveg;
    }

    public function setSzoveg($adat) {
        $this->szoveg = $adat;
    }

    public function getSeodescription() {
        return $this->seodescription;
    }

    public function getShowSeodescription() {
        if ($this->seodescription) {
            return $this->seodescription;
        }
        return $this->oldalcim . ' - ' . \mkw\store::getParameter(\mkw\consts::Seodescription);
    }

    public function setSeodescription($adat) {
        $this->seodescription = $adat;
    }

    public function getLastmod() {
        return $this->lastmod;
    }

    public function getCreated() {
        return $this->created;
    }

    public function getOldurl() {
        return $this->oldurl;
    }

    public function setOldurl($u) {
        $this->oldurl = $u;
    }

    public function getTranslations() {
        return $this->translations;
    }

    public function addTranslation(StatlapTranslation $t) {
        if (!$this->translations->contains($t)) {
            $this->translations[] = $t;
            $t->setObject($this);
        }
    }

    public function removeTranslation(StatlapTranslation $t) {
        $this->translations->removeElement($t);
    }

    public function getLocale() {
        return $this->locale;
    }

    public function setLocale($locale) {
        $this->locale = $locale;
    }

}
