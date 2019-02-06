<?php

namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use mkw\store;

/**
 * @ORM\Entity(repositoryClass="Entities\BlogposztRepository")
 * @ORM\Table(name="blogposzt",
 * options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"},
 * indexes={
 * 		@ORM\index(name="termekfakarkod_idx",columns={"termekfa1karkod","termekfa2karkod","termekfa3karkod"})
 * })
 */
class Blogposzt {

    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id = 0;

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
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="Dolgozo")
     * @ORM\JoinColumn(name="createdby", referencedColumnName="id")
     */
    private $createdby;

    /**
     * @Gedmo\Blameable(on="update")
     * @ORM\ManyToOne(targetEntity="Dolgozo")
     * @ORM\JoinColumn(name="updatedby", referencedColumnName="id")
     */
    private $updatedby;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $cim = '';

    /**
     * @Gedmo\Slug(fields={"cim"})
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="text",nullable=true)
     */
    private $kivonat = '';

    /**
     * @ORM\Column(type="text",nullable=false)
     */
    private $szoveg = '';

    /**
     * @ORM\ManyToOne(targetEntity="TermekFa",inversedBy="blogposztok1")
     * @ORM\JoinColumn(name="termekfa1_id",referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $termekfa1;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $termekfa1karkod = '';

    /**
     * @ORM\ManyToOne(targetEntity="TermekFa",inversedBy="blogposztok2")
     * @ORM\JoinColumn(name="termekfa2_id",referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $termekfa2;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $termekfa2karkod = '';

    /**
     * @ORM\ManyToOne(targetEntity="TermekFa",inversedBy="blogposztok3")
     * @ORM\JoinColumn(name="termekfa3_id",referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $termekfa3;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $termekfa3karkod = '';

    /** @ORM\Column(type="text",nullable=true) */
    private $kepurl = '';

    /** @ORM\Column(type="text",nullable=true) */
    private $kepleiras = '';

    /** @ORM\Column(type="date",nullable=false) */
    private $megjelenesdatum;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $lathato = 1;

    /** @ORM\Column(type="text",nullable=true) */
    private $seodescription;

    /** @ORM\ManyToMany(targetEntity="Termek", mappedBy="blogposztok", cascade={"persist"}) */
    private $termekek;

    public function __construct() {
        $this->termekek = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function convertToArray() {
        $ret = array(
            'slug' => $this->getSlug(),
            'cim' => $this->getCim(),
            'kivonat' => $this->getKivonat(),
            'kepurlsmall' => $this->getKepurlSmall(),
            'kepleiras' => $this->getKepleiras(),
            'szoveg' => $this->getSzoveg(),
            'url' => $this->getLink(),
            'seodescription' => $this->getShowSeodescription(),
            'megjelenesdatum' => $this->getMegjelenesdatumStr(),
            'megjelenesdatumstr' => $this->getMegjelenesdatumStr(),
            'showseodescription' => $this->getShowSeodescription(),
            'lathato' => $this->getLathato()
        );
        return $ret;
    }

    public function getLink() {
        return \mkw\store::getRouter()->generate('showblogposzt', \mkw\store::getConfigValue('mainurl', true), array('blogposzt' => $this->getSlug()));
    }

    public function getShowSeodescription() {
        if ($this->seodescription) {
            return $this->seodescription;
        }
        return $this->cim . ' - ' . \mkw\store::getParameter(\mkw\consts::Blogseodescription);
    }

    public function getId() {
        return $this->id;
    }

    public function getLastmod() {
        return $this->lastmod;
    }

    public function getLastmodStr() {
        if ($this->getLastmod()) {
            return $this->getLastmod()->format(\mkw\store::$DateTimeFormat);
        }
        return '';
    }

    public function clearLastmod() {
        $this->lastmod = null;
    }

    public function getCreated() {
        return $this->created;
    }

    public function getCreatedStr() {
        if ($this->getCreated()) {
            return $this->getCreated()->format(\mkw\store::$DateTimeFormat);
        }
        return '';
    }

    public function clearCreated() {
        $this->created = null;
    }

    /**
     * @return mixed
     */
    public function getCreatedby() {
        return $this->createdby;
    }

    public function getCreatedbyId() {
        if ($this->createdby) {
            return $this->createdby->getId();
        }
        return null;
    }

    public function getCreatedbyNev() {
        if ($this->createdby) {
            return $this->createdby->getNev();
        }
        return null;
    }

    /**
     * @return mixed
     */
    public function getUpdatedby() {
        return $this->updatedby;
    }

    public function getUpdatedbyId() {
        if ($this->updatedby) {
            return $this->updatedby->getId();
        }
        return null;
    }

    public function getUpdatedbyNev() {
        if ($this->updatedby) {
            return $this->updatedby->getNev();
        }
        return null;
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

    public function getMegjelenesdatum() {
        if (!$this->id && !$this->megjelenesdatum) {
            $this->megjelenesdatum = new \DateTime(\mkw\store::convDate(date(\mkw\store::$DateFormat)));
        }
        return $this->megjelenesdatum;
    }

    public function getMegjelenesdatumStr() {
        if ($this->getMegjelenesdatum()) {
            return $this->getMegjelenesdatum()->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    public function setMegjelenesdatum($adat = '') {
        if (is_a($adat, 'DateTime')) {
            $this->megjelenesdatum = $adat;
        }
        else {
            if ($adat == '') {
                $adat = date(\mkw\store::$DateFormat);
            }
            $this->megjelenesdatum = new \DateTime(\mkw\store::convDate($adat));
        }
    }

    public function getTermekfa1() {
        return $this->termekfa1;
    }

    public function getTermekfa1Nev() {
        if ($this->termekfa1) {
            if ($this->termekfa1->getId() > 1) {
                return $this->termekfa1->getNev();
            }
        }
        return '';
    }

    public function getTermekfa1Id() {
        if ($this->termekfa1) {
            return $this->termekfa1->getId();
        }
        return 1;
    }

    public function setTermekfa1($termekfa) {
        $this->termekfa1 = $termekfa;
        if ($termekfa) {
            $this->termekfa1karkod = $termekfa->getKarkod();
//            $termekfa->addTermek1($this);
        }
        else {
            $this->termekfa1karkod = '';
        }
    }

    public function getTermekfa2() {
        return $this->termekfa2;
    }

    public function getTermekfa2Nev() {
        if ($this->termekfa2) {
            if ($this->termekfa2->getId() > 1) {
                return $this->termekfa2->getNev();
            }
        }
        return '';
    }

    public function getTermekfa2Id() {
        if ($this->termekfa2) {
            return $this->termekfa2->getId();
        }
        return 1;
    }

    public function setTermekfa2($termekfa) {
        $this->termekfa2 = $termekfa;
        if ($termekfa) {
            $this->termekfa2karkod = $termekfa->getKarkod();
//            $termekfa->addTermek2($this);
        }
        else {
            $this->termekfa2karkod = '';
        }
    }

    public function getTermekfa3() {
        return $this->termekfa3;
    }

    public function getTermekfa3Nev() {
        if ($this->termekfa3) {
            if ($this->termekfa3->getId() > 1) {
                return $this->termekfa3->getNev();
            }
        }
        return '';
    }

    public function getTermekfa3Id() {
        if ($this->termekfa3) {
            return $this->termekfa3->getId();
        }
        return 1;
    }

    public function setTermekfa3($termekfa) {
        $this->termekfa3 = $termekfa;
        if ($termekfa) {
            $this->termekfa3karkod = $termekfa->getKarkod();
//            $termekfa->addTermek3($this);
        }
        else {
            $this->termekfa3karkod = '';
        }
    }

    public function getShowCim() {
        return $this->cim . ' - ' . \mkw\store::getParameter(\mkw\consts::Oldalcim);
    }

    /**
     * @return mixed
     */
    public function getCim() {
        return $this->cim;
    }

    /**
     * @param mixed $cim
     */
    public function setCim($cim) {
        $this->cim = $cim;
    }

    /**
     * @return mixed
     */
    public function getKivonat() {
        return $this->kivonat;
    }

    /**
     * @param mixed $kivonat
     */
    public function setKivonat($kivonat) {
        $this->kivonat = $kivonat;
    }

    /**
     * @return mixed
     */
    public function getSzoveg() {
        return $this->szoveg;
    }

    /**
     * @param mixed $szoveg
     */
    public function setSzoveg($szoveg) {
        $this->szoveg = $szoveg;
    }

    /**
     * @return mixed
     */
    public function getLathato() {
        return $this->lathato;
    }

    /**
     * @param mixed $lathato
     */
    public function setLathato($lathato) {
        $this->lathato = $lathato;
    }

    public function getSlug() {
        return $this->slug;
    }

    public function setSlug($slug) {
        $this->slug = $slug;
    }

    /**
     * @return mixed
     */
    public function getSeodescription() {
        return $this->seodescription;
    }

    /**
     * @param mixed $seodescription
     */
    public function setSeodescription($seodescription) {
        $this->seodescription = $seodescription;
    }

    public function getTermekek() {
        return $this->termekek;
    }

    public function addTermek(Termek $termek) {
//		if (!$this->termekek->contains($termek)) {  // deleted for speed
        $this->termekek->add($termek);
        $termek->addBlogposzt($this);
//		}
    }

    public function removeTermek(Termek $termek) {
        // TODO ha sok termeknek van ilyen cimkeje, akkor lassu lesz
        if ($this->termekek->removeElement($termek)) {
            $termek->removeBlogposzt($this);
            return true;
        }
        return false;
    }

}