<?php
namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\FizmodRepository")
 * @ORM\Table(name="fizmod",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 * @Gedmo\TranslationEntity(class="Entities\FizmodTranslation")
 */
class Fizmod {

    private static $translatedFields = array(
        'nev' => array('caption' => 'Név', 'type' => 1),
        'leiras' => array('caption' => 'Leírás', 'type' => 2)
    );

	/**
	 * @ORM\Id @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/**
     * @Gedmo\Translatable
     * @ORM\Column(type="string",length=255,nullable=false)
     */
	private $nev;
	/** @ORM\Column(type="string",length=1,nullable=false) */
	private $tipus='B';
	/** @ORM\Column(type="integer") */
	private $haladek=0;
	/** @ORM\Column(type="boolean") */
	private $webes=true;
	/** @ORM\Column(type="boolean") */
	private $rugalmas = true;
    /** @ORM\Column(type="string",length=20,nullable=false) */
    private $navtipus;
	/** @ORM\OneToMany(targetEntity="Bizonylatfej", mappedBy="fizmod",cascade={"persist"}) */
	private $bizonylatfejek;
	/**
     * @Gedmo\Translatable
     * @ORM\Column(type="text",nullable=true)
     */
	private $leiras;
	/** @ORM\Column(type="integer") */
	private $sorrend=0;
    /** @ORM\Column(type="integer",nullable=true) */
    private $osztotthaladek1;
    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $osztottszazalek1;
    /** @ORM\Column(type="integer",nullable=true) */
    private $osztotthaladek2;
    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $osztottszazalek2;
    /** @ORM\Column(type="integer",nullable=true) */
    private $osztotthaladek3;
    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $osztottszazalek3;
    /** @ORM\Column(type="integer",nullable=true) */
    private $osztotthaladek4;
    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $osztottszazalek4;
    /** @ORM\Column(type="integer",nullable=true) */
    private $osztotthaladek5;
    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $osztottszazalek5;
    /** @ORM\Column(type="integer",nullable=true) */
    private $migrid;
    /** @ORM\Column(type="boolean") */
    private $nincspenzmozgas = false;
    /** @ORM\Column(type="integer",nullable=true) */
    private $emagid;

    /** @Gedmo\Locale */
    protected $locale;

    /** @ORM\OneToMany(targetEntity="FizmodTranslation", mappedBy="object", cascade={"persist", "remove"}) */
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
		$this->bizonylatfejek=new \Doctrine\Common\Collections\ArrayCollection();
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

	public function getTipus() {
	    return $this->tipus;
	}

	public function setTipus($tipus) {
	    $this->tipus = $tipus;
	}

	public function getHaladek() {
	    return $this->haladek;
	}

	public function setHaladek($haladek) {
    	$this->haladek = $haladek;
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

	public function getSorrend() {
		return $this->sorrend;
	}

	public function setSorrend($val) {
		$this->sorrend=$val;
	}

    public function getOsztotthaladek1() {
        return $this->osztotthaladek1;
    }

    public function setOsztotthaladek1($adat) {
        $this->osztotthaladek1 = $adat;
    }

    public function getOsztottszazalek1() {
        return $this->osztottszazalek1;
    }

    public function setOsztottszazalek1($adat) {
        $this->osztottszazalek1 = $adat;
    }

    public function getOsztotthaladek2() {
        return $this->osztotthaladek2;
    }

    public function setOsztotthaladek2($adat) {
        $this->osztotthaladek2 = $adat;
    }

    public function getOsztottszazalek2() {
        return $this->osztottszazalek2;
    }

    public function setOsztottszazalek2($adat) {
        $this->osztottszazalek2 = $adat;
    }

    public function getOsztotthaladek3() {
        return $this->osztotthaladek3;
    }

    public function setOsztotthaladek3($adat) {
        $this->osztotthaladek3 = $adat;
    }

    public function getOsztottszazalek3() {
        return $this->osztottszazalek3;
    }

    public function setOsztottszazalek3($adat) {
        $this->osztottszazalek3 = $adat;
    }

	/**
	 * @return mixed
	 */
	public function getRugalmas() {
		return $this->rugalmas;
	}

	/**
	 * @param mixed $rugalmas
	 */
	public function setRugalmas($rugalmas) {
		$this->rugalmas = $rugalmas;

	}

    /**
     * @return mixed
     */
    public function getOsztotthaladek4() {
        return $this->osztotthaladek4;
    }

    /**
     * @param mixed $osztotthaladek4
     */
    public function setOsztotthaladek4($osztotthaladek4) {
        $this->osztotthaladek4 = $osztotthaladek4;
    }

    /**
     * @return mixed
     */
    public function getOsztottszazalek4() {
        return $this->osztottszazalek4;
    }

    /**
     * @param mixed $osztottszazalek4
     */
    public function setOsztottszazalek4($osztottszazalek4) {
        $this->osztottszazalek4 = $osztottszazalek4;
    }

    /**
     * @return mixed
     */
    public function getOsztotthaladek5() {
        return $this->osztotthaladek5;
    }

    /**
     * @param mixed $osztotthaladek5
     */
    public function setOsztotthaladek5($osztotthaladek5) {
        $this->osztotthaladek5 = $osztotthaladek5;
    }

    /**
     * @return mixed
     */
    public function getOsztottszazalek5() {
        return $this->osztottszazalek5;
    }

    /**
     * @param mixed $osztottszazalek5
     */
    public function setOsztottszazalek5($osztottszazalek5) {
        $this->osztottszazalek5 = $osztottszazalek5;
    }

    /**
     * @return mixed
     */
    public function getMigrid() {
        return $this->migrid;
    }

    /**
     * @param mixed $migrid
     */
    public function setMigrid($migrid) {
        $this->migrid = $migrid;
    }

    public function getTranslations() {
        return $this->translations;
    }

    public function addTranslation(FizmodTranslation $t) {
        if (!$this->translations->contains($t)) {
            $this->translations[] = $t;
            $t->setObject($this);
        }
    }

    public function removeTranslation(FizmodTranslation $t) {
        $this->translations->removeElement($t);
    }

    public function getLocale() {
        return $this->locale;
    }

    public function setLocale($locale) {
        $this->locale = $locale;
    }

    /**
     * @return mixed
     */
    public function getNincspenzmozgas() {
        return $this->nincspenzmozgas;
    }

    /**
     * @param mixed $nincspenzmozgas
     */
    public function setNincspenzmozgas($nincspenzmozgas) {
        $this->nincspenzmozgas = $nincspenzmozgas;
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

    /**
     * @return mixed
     */
    public function getNavtipus() {
        return $this->navtipus;
    }

    /**
     * @param mixed $navtipus
     */
    public function setNavtipus($navtipus) {
        $this->navtipus = $navtipus;
    }

}