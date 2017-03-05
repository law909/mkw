<?php
namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\SzallitasimodRepository")
 * @ORM\Table(name="szallitasimod",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 * @Gedmo\TranslationEntity(class="Entities\SzallitasimodTranslation")
 */
class Szallitasimod {

    private static $translatedFields = array(
        'nev' => array('caption' => 'Név', 'type' => 1),
        'leiras' => array('caption' => 'Leírás', 'type' => 3)
    );

    /**
	 * @ORM\Id @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/**
     * @Gedmo\Translatable
	 * @ORM\Column(type="string",length=255)
	 */
	private $nev;
	/** @ORM\Column(type="boolean") */
	private $webes=true;
	/** @ORM\Column(type="boolean") */
	private $vanszallitasiktg=true;
	/**
     * @Gedmo\Translatable
	 * @ORM\Column(type="text",nullable=true)
	 */
	private $leiras;
	/**
	 * @ORM\Column(type="string",length=255)
	 */
	private $fizmodok;
	/** @ORM\OneToMany(targetEntity="Bizonylatfej", mappedBy="szallitasimod",cascade={"persist"}) */
	private $bizonylatfejek;
	/** @ORM\Column(type="integer") */
	private $sorrend=0;
	/** @ORM\OneToMany(targetEntity="Partner", mappedBy="szallitasimod",cascade={"persist"}) */
	private $partnerek;
	/** @ORM\OneToMany(targetEntity="SzallitasimodHatar", mappedBy="szallitasimod",cascade={"persist"}) */
	private $hatarok;

    /** @Gedmo\Locale */
    protected $locale;

    /** @ORM\OneToMany(targetEntity="SzallitasimodTranslation", mappedBy="object", cascade={"persist", "remove"}) */
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
		$this->bizonylatfejek = new \Doctrine\Common\Collections\ArrayCollection();
		$this->partnerek = new \Doctrine\Common\Collections\ArrayCollection();
		$this->hatarok = new \Doctrine\Common\Collections\ArrayCollection();
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
	}

	public function getId() {
		return $this->id;
	}

	public function getNev() {
		return $this->nev;
	}

	public function setNev($nev) {
		$this->nev = $nev;
	}

	public function getWebes() {
		return $this->webes;
	}

	public function setWebes($webes) {
		$this->webes = $webes;
	}

	public function getLeiras() {
		return $this->leiras;
	}

	public function setLeiras($leiras) {
		$this->leiras = $leiras;
	}

	public function getFizmodok() {
		return $this->fizmodok;
	}

	public function setFizmodok($fm) {
		$this->fizmodok = $fm;
	}

	public function getSorrend() {
		return $this->sorrend;
	}

	public function setSorrend($val) {
		$this->sorrend=$val;
	}

    public function getVanszallitasiktg() {
        return $this->vanszallitasiktg;
    }

    public function setVanszallitasiktg($adat) {
        $this->vanszallitasiktg = $adat;
    }

	public function getHatarok() {
		return $this->getHatarok();
	}

    public function getTranslations() {
        return $this->translations;
    }

    public function addTranslation(SzallitasimodTranslation $t) {
        if (!$this->translations->contains($t)) {
            $this->translations[] = $t;
            $t->setObject($this);
        }
    }

    public function removeTranslation(SzallitasimodTranslation $t) {
        $this->translations->removeElement($t);
    }

    public function getLocale() {
        return $this->locale;
    }

    public function setLocale($locale) {
        $this->locale = $locale;
    }
}