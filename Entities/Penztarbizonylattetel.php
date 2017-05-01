<?php

namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity(repositoryClass="Entities\PenztarbizonylattetelRepository")
 * @ORM\Table(name="penztarbizonylattetel",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class Penztarbizonylattetel {

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
     * @ORM\ManyToOne(targetEntity="Penztarbizonylatfej",inversedBy="bizonylattetelek")
     * @ORM\JoinColumn(name="penztarbizonylatfej_id", referencedColumnName="id",nullable=true,onDelete="cascade")
     * @var \Entities\Penztarbizonylatfej
     */
    private $bizonylatfej;

    /** @ORM\Column(type="date",nullable=false) */
    private $hivatkozottdatum;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $rontott = false;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $netto;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $afa;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $brutto;

    /**
     * @ORM\ManyToOne(targetEntity="Jogcim")
     * @ORM\JoinColumn(name="jogcim_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Jogcim
     */
    private $jogcim;

    /**
     * @ORM\Column(type="string",length=50)
     */
    private $jogcimnev;

    /**
     * @ORM\Column(type="string",length=30,nullable=true)
     */
    private $hivatkozottbizonylat;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $szoveg;

    /** @ORM\OneToMany(targetEntity="Folyoszamla", mappedBy="bizonylatfej",cascade={"persist"}) */
    private $folyoszamlak;

    public function __construct() {
        $this->folyoszamlak = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId() {
        return $this->id;
    }

    /**
     * @return \Entities\Penztarbizonylatfej
     */
    public function getBizonylatfej() {
        return $this->bizonylatfej;
    }

    public function getBizonylatfejId() {
        if ($this->bizonylatfej) {
            return $this->bizonylatfej->getId();
        }
        return '';
    }

    /**
     * @param \Entities\Penztarbizonylatfej $val
     */
    public function setBizonylatfej($val) {
        if ($this->bizonylatfej !== $val) {
            $this->bizonylatfej = $val;
            $val->addBizonylattetel($this);
        }
    }

    public function removeBizonylatfej() {
        if ($this->bizonylatfej !== null) {
            $val = $this->bizonylatfej;
            $this->bizonylatfej = null;
            $val->removeBizonylattetel($this);
        }
    }

    public function getFolyoszamlak() {
        return $this->folyoszamlak;
    }

    public function addFolyoszamla(Folyoszamla $val) {
        if (!$this->folyoszamlak->contains($val)) {
            $this->folyoszamlak->add($val);
            $val->setBizonylatfej($this);
        }
    }

    public function removeFolyoszamla(Folyoszamla $val) {
        if ($this->folyoszamlak->removeElement($val)) {
            $val->removeBizonylatfej();
            return true;
        }
        return false;
    }

    public function clearFolyoszamlak() {
        $this->folyoszamlak->clear();
    }

    /**
     * @return mixed
     */
    public function getHivatkozottbizonylat() {
        return $this->hivatkozottbizonylat;
    }

    /**
     * @param mixed $hivatkozottbizonylat
     */
    public function setHivatkozottbizonylat($hivatkozottbizonylat) {
        $this->hivatkozottbizonylat = $hivatkozottbizonylat;
    }

    /**
     * @return mixed
     */
    public function getAfa() {
        return $this->afa;
    }

    /**
     * @param mixed $afa
     */
    public function setAfa($afa) {
        $this->afa = $afa;
    }

    /**
     * @return mixed
     */
    public function getBrutto() {
        return $this->brutto;
    }

    /**
     * @param mixed $brutto
     */
    public function setBrutto($brutto) {
        $this->brutto = $brutto;
    }

    /**
     * @return mixed
     */
    public function getCreated() {
        return $this->created;
    }

    /**
     * @param mixed $created
     */
    public function clearCreated() {
        $this->created = null;;
    }

    /**
     * @return mixed
     */
    public function getLastmod() {
        return $this->lastmod;
    }

    /**
     * @param mixed $lastmod
     */
    public function clearLastmod() {
        $this->lastmod = null;
    }

    /**
     * @return mixed
     */
    public function getNetto() {
        return $this->netto;
    }

    /**
     * @param mixed $netto
     */
    public function setNetto($netto) {
        $this->netto = $netto;
    }

    /**
     * @return mixed
     */
    public function getRontott() {
        return $this->rontott;
    }

    /**
     * @param mixed $rontott
     */
    public function setRontott($rontott) {
        $this->rontott = $rontott;
    }

    /**
     * @return mixed
     */
    public function getJogcimnev() {
        return $this->jogcimnev;
    }

    /**
     * @return mixed
     */
    public function getJogcimId() {
        if ($this->jogcim) {
            return $this->jogcim->getId();
        }
        return 0;
    }

    /**
     * @param mixed $jogcimnev
     */
    public function setJogcimnev($jogcimnev) {
        $this->jogcimnev = $jogcimnev;
    }

    /**
     * @return mixed
     */
    public function getJogcim() {
        return $this->jogcim;
    }

    /**
     * @param mixed $jogcim
     */
    public function setJogcim($jogcim) {
        $this->jogcim = $jogcim;
        if (!$jogcim) {
            $this->setJogcimnev('');
        }
        else {
            $this->setJogcimnev($jogcim->getNev());
        }
    }

    public function getHivatkozottdatum() {
        if (!$this->id && !$this->hivatkozottdatum) {
            $this->hivatkozottdatum = new \DateTime(\mkw\store::convDate(date(\mkw\store::$DateFormat)));
        }
        return $this->hivatkozottdatum;
    }

    public function getHivatkozottdatumStr() {
        if ($this->getHivatkozottdatum()) {
            return $this->getHivatkozottdatum()->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    public function setHivatkozottdatum($adat = '') {
        if (is_a($adat, 'DateTime')) {
            $this->hivatkozottdatum = $adat;
        }
        else {
            if ($adat == '') {
                $adat = date(\mkw\store::$DateFormat);
            }
            $this->hivatkozottdatum = new \DateTime(\mkw\store::convDate($adat));
        }
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


}