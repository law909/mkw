<?php

namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection;

/** @ORM\Entity(repositoryClass="Entities\LeltartetelRepository")
 * @ORM\Table(name="leltartetel",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class Leltartetel {

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
     * @ORM\ManyToOne(targetEntity="Leltarfej",inversedBy="leltartetelek")
     * @ORM\JoinColumn(name="leltarfej_id", referencedColumnName="id",nullable=true,onDelete="cascade")
     * @var \Entities\Leltarfej
     */
    private $leltarfej;

    /**
     * @ORM\ManyToOne(targetEntity="Termek",inversedBy="leltartetelek")
     * @ORM\JoinColumn(name="termek_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Termek
     */
    private $termek;

    /**
     * @ORM\ManyToOne(targetEntity="TermekValtozat",inversedBy="leltartetelek")
     * @ORM\JoinColumn(name="termekvaltozat_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\TermekValtozat
     */
    private $termekvaltozat;

    /** @ORM\Column(type="decimal",precision=14,scale=2,nullable=true) */
    private $gepimennyiseg;

    /** @ORM\Column(type="decimal",precision=14,scale=2,nullable=true) */
    private $tenymennyiseg;

    public function getId() {
        return $this->id;
    }

    public function toLista() {
        $ret = array();
        $ret['gepimennyiseg'] = $this->getGepimennyiseg();
        $ret['tenymennyiseg'] = $this->getTenymennyiseg();
        $ret['termek'] = $this->getTermek();
        $ret['termeknev'] = $this->getTermeknev();
        $ret['valtozatnev'] = $this->getFullTermeknev();
        $ret['nettoegysarhuf'] = $this->getNettoegysarhuf();
        $ret['bruttoegysarhuf'] = $this->getBruttoegysarhuf();
        $ret['enettoegysarhuf'] = $this->getEnettoegysarhuf();
        $ret['ebruttoegysarhuf'] = $this->getEbruttoegysarhuf();
        $ret['nettohuf'] = $this->getNettohuf();
        $ret['afahuf'] = $this->getAfaertekhuf();
        $ret['bruttohuf'] = $this->getBruttohuf();
        $ret['nettoegysar'] = $this->getNettoegysar();
        $ret['bruttoegysar'] = $this->getBruttoegysar();
        $ret['enettoegysar'] = $this->getEnettoegysar();
        $ret['ebruttoegysar'] = $this->getEbruttoegysar();
        $ret['netto'] = $this->getNetto();
        $ret['afa'] = $this->getAfaertek();
        $ret['brutto'] = $this->getBrutto();
        $ret['kedvezmeny'] = $this->getKedvezmeny();
        $ret['termeknev'] = $this->getTermeknev();
        $ret['me'] = $this->getME();
        $ret['afanev'] = $this->getAfanev();
        $ret['vtszszam'] = $this->getVtszszam();
        $ret['vtsznev'] = $this->getVtsznev();
        $valt = $this->getTermekvaltozat();
        $v = array();
        if ($valt) {
            $ret['valtozatnev'] = $valt->getNev();
            if ($valt->getAdatTipus1()) {
                $v[] = array('nev' => $valt->getAdatTipus1Nev(), 'ertek' => $valt->getErtek1());
            }
            if ($valt->getAdatTipus2()) {
                $v[] = array('nev' => $valt->getAdatTipus2Nev(), 'ertek' => $valt->getErtek2());
            }
            $ret['valtadattipus1id'] = $valt->getAdatTipus1Id();
            $ret['valtertek1'] = $valt->getErtek1();
            $ret['valtadattipus2id'] = $valt->getAdatTipus2Id();
            $ret['valtertek2'] = $valt->getErtek2();
        }
        $ret['valtozatok'] = $v;
        return $ret;
    }

    public function getLastmod() {
        return $this->lastmod;
    }

    public function clearLastmod() {
        $this->lastmod = null;
    }

    public function getCreated() {
        return $this->created;
    }

    public function clearCreated() {
        $this->created = null;
    }

    /**
     * @return Leltarfej
     */
    public function getLeltarfej() {
        return $this->leltarfej;
    }

    public function getLeltarfejId() {
        if ($this->leltarfej) {
            return $this->leltarfej->getId();
        }
        return '';
    }

    /**
     * @param \Entities\Leltarfej $val
     */
    public function setLeltarfej($val) {
        if ($this->leltarfej !== $val) {
            $this->leltarfej = $val;
            $val->addLeltartetel($this);
        }
    }

    public function removeLeltarfej() {
        if ($this->leltarfej !== null) {
            $val = $this->leltarfej;
            $this->leltarfej = null;
            $val->removeLeltartetel($this);
        }
    }

    public function getTermek() {
        return $this->termek;
    }

    public function getTermekId() {
        if ($this->termek) {
            return $this->termek->getId();
        }
        return '';
    }

    /**
     * @param \Entities\Termek $val
     */
    public function setTermek($val) {
        if ($this->termek !== $val) {
            if (!$val) {
                $this->removeTermek();
            }
            else {
                $this->termek = $val;
            }
        }
    }

    public function removeTermek() {
        if ($this->termek !== null) {
            $this->termek = null;
        }
    }

    public function getFullTermeknev() {
        if ($this->getTermekvaltozat()) {
            $valtnev = $this->getTermekvaltozat()->getNev();
        }
        return implode(' ', array($this->getTermeknev(), $valtnev));
    }

    public function getTermeknev() {
        if ($this->getTermek()) {
            return $this->getTermek()->getNev();
        }
        return '';
    }

    public function getTermekvaltozat() {
        return $this->termekvaltozat;
    }

    public function getTermekvaltozatId() {
        if ($this->termekvaltozat) {
            return $this->termekvaltozat->getId();
        }
        return '';
    }

    /**
     * @param \Entities\TermekValtozat $val
     */
    public function setTermekvaltozat($val) {
        if (!$val) {
            $this->removeTermekvaltozat();
        }
        else {
            $this->termekvaltozat = $val;
        }
    }

    public function removeTermekvaltozat() {
        if ($this->termekvaltozat !== null) {
            $this->termekvaltozat = null;
        }
    }

    /**
     * @return mixed
     */
    public function getGepimennyiseg() {
        return $this->gepimennyiseg;
    }

    /**
     * @param mixed $gepimennyiseg
     */
    public function setGepimennyiseg($gepimennyiseg) {
        $this->gepimennyiseg = $gepimennyiseg;
    }

    /**
     * @return mixed
     */
    public function getTenymennyiseg() {
        return $this->tenymennyiseg;
    }

    /**
     * @param mixed $tenymennyiseg
     */
    public function setTenymennyiseg($tenymennyiseg) {
        $this->tenymennyiseg = $tenymennyiseg;
    }

}