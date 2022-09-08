<?php
namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection;
use mkw\store;

/** @ORM\Entity(repositoryClass="Entities\MNRNavigationRepository")
 * @ORM\Table(name="mnrnavigation",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 * @Gedmo\TranslationEntity(class="Entities\MNRNavigationTranslation")
 * */
class MNRNavigation {

    private static $translatedFields = array(
        'nev' => array('caption' => 'Név', 'type' => 1),
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

    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    private $szam = '';


    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    private $szlogen = '';

    /** @Gedmo\Locale */
    protected $locale;

    /**
     * @ORM\ManyToOne(targetEntity="MNRStatic")
     * @ORM\JoinColumn(name="mnrstatic_id", referencedColumnName="id",nullable=true,onDelete="set null")
     * @var \Entities\MNRStatic
     */
    private $mnrstatic;

    /** @ORM\OneToMany(targetEntity="MNRNavigationTranslation", mappedBy="object", cascade={"persist", "remove"}) */
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
        $ret['szam'] = $this->getSzam();
        $ret['szlogen'] = $this->getSzlogen();
        $ret['translations'] = $this->getTranslationsArray();
        return $ret;
    }

    public function toLista() {
        $ret = array();
        $ret['id'] = $this->getId();
        $ret['nev'] = $this->getNev();
        $ret['szam'] = $this->getSzam();
        $ret['szlogen'] = $this->getSzlogen();
        return $ret;
    }

    public function getId() {
        return $this->id;
    }

    public function getTranslations() {
        return $this->translations;
    }

    public function getTranslationsArray() {
        $r = array();
        /** @var \Entities\MNRNavigationTranslation $tr */
        foreach ($this->translations as $tr) {
            $r[$tr->getLocale()][$tr->getField()] = $tr->getContent();
        }
        return $r;
    }

    public function addTranslation(MNRNavigationTranslation $t) {
        if (!$this->translations->contains($t)) {
            $this->translations[] = $t;
            $t->setObject($this);
        }
    }

    public function removeTranslation(MNRNavigationTranslation $t) {
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
    public function getSzam() {
        return $this->szam;
    }

    /**
     * @param string $szam
     */
    public function setSzam($szam): void {
        $this->szam = $szam;
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

    /**
     * @return MNRStatic
     */
    public function getMnrstatic()
    {
        return $this->mnrstatic;
    }

    public function getMnrstaticId()
    {
        if ($this->mnrstatic) {
            return $this->mnrstatic->getId();
        }
        return null;
    }

    /**
     * @param MNRStatic $mnrstatic
     */
    public function setMnrstatic($mnrstatic): void
    {
        $this->mnrstatic = $mnrstatic;
    }

}