<?php
namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection;

/** @ORM\Entity(repositoryClass="Entities\LeltarfejRepository")
 * @ORM\Table(name="leltarfej",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class Leltarfej {

    /**
     * @ORM\Id @ORM\Column(type = "integer")
     * @ORM\GeneratedValue(strategy = "AUTO")
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

    /**
     * @ORM\ManyToOne(targetEntity="Raktar",inversedBy="leltarfejek")
     * @ORM\JoinColumn(name="raktar_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Raktar
     */
    private $raktar;

    /** @ORM\Column(type="date",nullable=false) */
    private $nyitas;

    /** @ORM\Column(type="date",nullable=false) */
    private $zaras;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $zarva = false;

    /** @ORM\OneToMany(targetEntity="Leltartetel", mappedBy="bizonylatfej",cascade={"persist"}) */
    private $leltartetelek;

    public function __toString() {
        return (string)$this->id;
    }

    public function __construct() {
        $this->bizonylattetelek = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId() {
        return $this->id;
    }

    public function toLista() {
        $ret = array();
        $ret['id'] = $this->getId();
        $ret['lastmodstr'] = $this->getLastmodStr();
        $ret['createdstr'] = $this->getCreatedStr();
        $ret['updatedby'] = $this->getUpdatedbyNev();
        $ret['createdby'] = $this->getCreatedbyNev();
        $ret['nyitasstr'] = $this->getNyitasStr();
        $ret['zarasstr'] = $this->getZarasStr();
        $ret['raktarid'] = $this->getRaktarId();
        $ret['raktarnev'] = $this->getRaktarnev();
        $ret['zarva'] = $this->getZarva();
        return $ret;
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

    /**
     * @return \Entities\Raktar
     */
    public function getRaktar() {
        return $this->raktar;
    }

    public function getRaktarnev() {
        if ($this->raktar) {
            return $this->raktar->getNev();
        }
        return '';
    }

    public function getRaktarId() {
        if ($this->raktar) {
            return $this->raktar->getId();
        }
        return '';
    }

    /**
     * @param \Entities\Raktar $val
     */
    public function setRaktar($val) {
        if (!($val instanceof \Entities\Raktar)) {
            $val = \mkw\store::getEm()->getRepository('Entities\Raktar')->find($val);
        }
        if ($this->raktar !== $val) {
            if (!$val) {
                $this->removeRaktar();
            }
            else {
                $this->raktar = $val;
            }
        }
    }

    public function removeRaktar() {
        if ($this->raktar !== null) {
            $this->raktar = null;
        }
    }

    public function getNyitas() {
        return $this->nyitas;
    }

    public function getNyitasStr() {
        if ($this->getNyitas()) {
            return $this->getNyitas()->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    public function setNyitas($adat = '') {
        if (is_a($adat, 'DateTime')) {
            $this->nyitas = $adat;
        }
        else {
            if ($adat == '') {
                $adat = date(\mkw\store::$DateFormat);
            }
            $this->nyitas = new \DateTime(\mkw\store::convDate($adat));
        }
    }

    public function getZaras() {
        return $this->zaras;
    }

    public function getZarasStr() {
        if ($this->getZaras()) {
            return $this->getZaras()->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    public function setZaras($adat = '') {
        if (is_a($adat, 'DateTime')) {
            $this->zaras = $adat;
        }
        else {
            if ($adat == '') {
                $adat = date(\mkw\store::$DateFormat);
            }
            $this->zaras = new \DateTime(\mkw\store::convDate($adat));
        }
    }

    /**
     * @return mixed
     */
    public function getZarva() {
        return $this->zarva;
    }

    /**
     * @param mixed $zarva
     */
    public function setZarva($zarva) {
        $this->zarva = $zarva;
    }

    public function getLeltartetelek() {
        return $this->leltartetelek;
    }

    public function addLeltartetel(Leltartetel $val) {
        if (!$this->leltartetelek->contains($val)) {
            $this->leltartetelek->add($val);
            $val->setLeltarfej($this);
        }
    }

    public function removeLeltartetel(Leltartetel $val) {
        if ($this->leltartetelek->removeElement($val)) {
            $val->removeLeltarfej();
            return true;
        }
        return false;
    }

    public function clearLeltartetelek() {
        $this->leltartetelek->clear();
    }


}