<?php

namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use mkw\store,
    Doctrine\Common\Collections\ArrayCollection;

/** @ORM\Entity(repositoryClass="Entities\BizonylattetelRepository")
 * @ORM\Table(name="bizonylattetel",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 * @Gedmo\TranslationEntity(class="Entities\BizonylattetelTranslation")
 */
class Bizonylattetel {

    private $duplication;
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
     * @ORM\ManyToOne(targetEntity="Bizonylatfej",inversedBy="bizonylattetelek")
     * @ORM\JoinColumn(name="bizonylatfej_id", referencedColumnName="id",nullable=true,onDelete="cascade")
     * @var \Entities\Bizonylatfej
     */
    private $bizonylatfej;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $mozgat;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $foglal;

    /** @ORM\Column(type="integer") */
    private $irany;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $arvaltoztat = false;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $storno = false;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $stornozott = false;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $rontott = false;

    /**
     * @ORM\ManyToOne(targetEntity="Termek",inversedBy="bizonylattetelek")
     * @ORM\JoinColumn(name="termek_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Termek
     */
    private $termek;

    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="string",length=255,nullable=false)
     */
    private $termeknev;

    /** @ORM\Column(type="string",length=20,nullable=true) */
    private $me;

    /** @ORM\Column(type="decimal",precision=14,scale=2,nullable=true) */
    private $kiszereles;

    /** @ORM\Column(type="string",length=50,nullable=true) */
    private $cikkszam;

    /** @ORM\Column(type="string",length=50,nullable=true) */
    private $idegencikkszam;

    /** @ORM\Column(type="decimal",precision=14,scale=2,nullable=true) */
    private $ehparany;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $hparany;

    /** @ORM\Column(type="decimal",precision=14,scale=2,nullable=true) */
    private $szelesseg = 0;

    /** @ORM\Column(type="decimal",precision=14,scale=2,nullable=true) */
    private $magassag = 0;

    /** @ORM\Column(type="decimal",precision=14,scale=2,nullable=true) */
    private $hosszusag = 0;

    /** @ORM\Column(type="decimal",precision=14,scale=2,nullable=true) */
    private $suly = 0;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $osszehajthato = false;

    /**
     * @ORM\ManyToOne(targetEntity="Vtsz",inversedBy="bizonylattetelek")
     * @ORM\JoinColumn(name="vtsz_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Vtsz
     */
    private $vtsz;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $vtsznev;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $vtszszam;

    /**
     * @ORM\ManyToOne(targetEntity="Afa",inversedBy="bizonylattetelek")
     * @ORM\JoinColumn(name="afa_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Afa
     */
    private $afa;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $afanev;

    /** @ORM\Column(type="integer") */
    private $afakulcs;

    /** @ORM\Column(type="decimal",precision=14,scale=2,nullable=true) */
    private $gymennyiseg;

    /** @ORM\Column(type="decimal",precision=14,scale=2,nullable=true) */
    private $mennyiseg;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $nettoegysar;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $bruttoegysar;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $nettoegysarhuf;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $bruttoegysarhuf;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $kedvezmeny;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $enettoegysar;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $ebruttoegysar;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $enettoegysarhuf;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $ebruttoegysarhuf;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $netto;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $afaertek;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $brutto;

    /**
     * @ORM\ManyToOne(targetEntity="Valutanem",inversedBy="bizonylattetelek")
     * @ORM\JoinColumn(name="valutanem_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Valutanem
     */
    private $valutanem;

    /** @ORM\Column(type="string",length=6,nullable=true) */
    private $valutanemnev;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $nettohuf;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $afaertekhuf;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $bruttohuf;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $arfolyam;

    /**
     * @ORM\ManyToOne(targetEntity="Bizonylattetel",inversedBy="szulobizonylattetelek")
     * @ORM\JoinColumn(name="parbizonylattetel_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Bizonylattetel
     */
    private $parbizonylattetel;

    /** @ORM\OneToMany(targetEntity="Bizonylattetel", mappedBy="parbizonylattetel",cascade={"persist"}) */
    private $szulobizonylattetelek;

    /** @ORM\Column(type="date",nullable=true) */
    private $hatarido;

    /**
     * @ORM\ManyToOne(targetEntity="TermekValtozat",inversedBy="bizonylattetelek")
     * @ORM\JoinColumn(name="termekvaltozat_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\TermekValtozat
     */
    private $termekvaltozat;

    /**
     * @ORM\ManyToOne(targetEntity="TermekValtozatAdatTipus")
     * @ORM\JoinColumn(name="valtozatadattipus1_id",referencedColumnName="id",onDelete="restrict")
     * @var \Entities\TermekValtozatAdatTipus
     */
    private $valtozatadattipus1;

    /**
     * @ORM\ManyToOne(targetEntity="TermekValtozatAdatTipus")
     * @ORM\JoinColumn(name="valtozatadattipus2_id",referencedColumnName="id",onDelete="restrict")
     * @var \Entities\TermekValtozatAdatTipus
     */
    private $valtozatadattipus2;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $valtozatadattipus1nev;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $valtozatadattipus2nev;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $valtozatertek1;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $valtozatertek2;

    /** @ORM\OneToMany(targetEntity="BizonylattetelTranslation", mappedBy="object", cascade={"persist", "remove"}) */
    private $translations;

    /**
     * @ORM\ManyToOne(targetEntity="Termekcsoport")
     * @ORM\JoinColumn(name="termekcsoport_id",referencedColumnName="id",onDelete="restrict")
     * @var \Entities\Termekcsoport
     */
    private $termekcsoport;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $termekcsoportnev;

    /** @Gedmo\Locale */
    protected $locale;

    public function __construct() {
        $this->szulobizonylattetelek = new ArrayCollection();
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function toLista() {
        $ret = array();
        $termek = $this->getTermek();
        $ret = $ret + $termek->toKosar($this->getTermekvaltozat());
        $ret['mennyiseg'] = $this->getMennyiseg();
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
            if ($valt->getAdatTipus1()) {
                $v[] = array('nev' => $valt->getAdatTipus1Nev(), 'ertek' => $valt->getErtek1());
            }
            if ($valt->getAdatTipus2()) {
                $v[] = array('nev' => $valt->getAdatTipus2Nev(), 'ertek' => $valt->getErtek2());
            }
        }
        $ret['valtozatok'] = $v;
        return $ret;
    }

    public function setPersistentData() {
        $bf = $this->bizonylatfej;
        if ($bf) {
            $this->setValutanem($bf->getValutanem());
            $this->setArfolyam($bf->getArfolyam());
        }
    }

    public function calc() {
        $this->setNetto($this->getNettoegysar() * $this->getMennyiseg());
        $this->setBrutto($this->getBruttoegysar() * $this->getMennyiseg());
        $this->setAfaertek($this->getBrutto() - $this->getNetto());
        $this->setNettohuf($this->getNettoegysarhuf() * $this->getMennyiseg());
        $this->setBruttohuf($this->getBruttoegysarhuf() * $this->getMennyiseg());
        $this->setAfaertekhuf($this->getBruttohuf() - $this->getNettohuf());
    }

    public function getId() {
        return $this->id;
    }

    public function getTeljesites() {
        if ($this->bizonylatfej) {
            return $this->bizonylatfej->getTeljesites();
        }
        return 0;
    }

    /**
     * @return Raktar|int
     */
    public function getRaktar() {
        if ($this->bizonylatfej) {
            return $this->bizonylatfej->getRaktar();
        }
        return 0;
    }

    public function getRaktarId() {
        if ($this->bizonylatfej) {
            $raktar = $this->bizonylatfej->getRaktar();
            if ($raktar) {
                return $raktar->getId();
            }
        }
        return 0;
    }

    /**
     * @return Bizonylatfej
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
     * @param \Entities\Bizonylatfej $val
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

    public function getMozgat() {
        return $this->mozgat;
    }

    public function setMozgat() {
        $bf = $this->bizonylatfej;
        $par = $this->getParbizonylattetel();
        if ($par && !$this->getStorno() && !$this->getStornozott()) {
            if (($par->getIrany() == $this->getIrany()) && $par->getMozgat()) {
                $this->mozgat = false;
                return true;
            }
        }
        $t = $this->termek;
        if ($bf && $t) {
            $this->mozgat = $bf->getMozgat() && $t->getMozgat();
        }
        else {
            $this->mozgat = false;
        }
    }

    public function getFoglal() {
        return $this->foglal;
    }

    public function setFoglal() {
        $this->foglal = false;
        if (\mkw\Store::isFoglalas()) {
            $bf = $this->bizonylatfej;
            $t = $this->termek;
            if ($bf && $t) {
                $bs = $bf->getBizonylatstatusz();
                if ($bs) {
                    $this->foglal = $bf->getFoglal() && $t->getMozgat() && $bs->getFoglal();
                }
                else {
                    $this->foglal = $bf->getFoglal() && $t->getMozgat();
                }
            }
        }
    }

    public function getArvaltoztat() {
        return $this->arvaltoztat;
    }

    public function setArvaltoztat($val) {
        $this->arvaltoztat = $val;
    }

    public function getStorno() {
        return $this->storno;
    }

    public function setStorno($val) {
        $this->storno = $val;
        if ($this->storno) {
            $this->setStornozott(false);
        }
    }

    public function getStornozott() {
        return $this->stornozott;
    }

    public function setStornozott($val) {
        $this->stornozott = $val;
        if ($this->stornozott) {
            $this->setStorno(false);
        }
    }

    public function getHosszusag() {
        return $this->hosszusag;
    }

    public function setHosszusag($adat) {
        $this->hosszusag = $adat;
    }

    public function getEhparany() {
        return $this->ehparany;
    }

    public function setEhparany($adat) {
        $this->ehparany = $adat;
    }

    public function getKiszereles() {
        return $this->kiszereles;
    }

    public function setKiszereles($adat) {
        $this->kiszereles = $adat;
    }

    public function getMagassag() {
        return $this->magassag;
    }

    public function setMagassag($adat) {
        $this->magassag = $adat;
    }

    public function getOsszehajthato() {
        return $this->osszehajthato;
    }

    public function setOsszehajthato($adat) {
        $this->osszehajthato = $adat;
    }

    public function getSuly() {
        return $this->suly;
    }

    public function setSuly($adat) {
        $this->suly = $adat;
    }

    public function getSzelesseg() {
        return $this->szelesseg;
    }

    public function setSzelesseg($adat) {
        $this->szelesseg = $adat;
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
                if (!$this->duplication) {
                    $this->setTermeknev($val->getNev());
                    $this->translations->clear();
                    foreach ($val->getTranslations() as $trans) {
                        $uj = new BizonylattetelTranslation($trans->getLocale(), 'termeknev', $trans->getContent());
                        $this->addTranslation($uj);
                    }
                    $this->setCikkszam($val->getCikkszam());
                    $this->setHosszusag($val->getHosszusag());
                    $this->setEhparany($val->getHparany());
                    $this->setIdegencikkszam($val->getIdegencikkszam());
                    $this->setKiszereles($val->getKiszereles());
                    $this->setMagassag($val->getMagassag());
                    $this->setME($val->getMe());
                    $this->setOsszehajthato($val->getOsszehajthato());
                    $this->setSuly($val->getSuly());
                    $this->setSzelesseg($val->getSzelesseg());
                    $csoport = $val->getTermekcsoport();
                    if ($csoport) {
                        $this->setTermekcsoport($csoport);
                    }
                    else {
                        $this->setTermekcsoport(null);
                    }
                    $vtsz = $val->getVtsz();
                    if ($vtsz) {
                        $this->setVtsz($vtsz);
                    }
                    else {
                        $this->setVtsz(null);
                    }
                    $afa = $val->getAfa();
                    if ($afa) {
                        $this->setAfa($afa);
                    }
                    else {
                        $this->setAfa(null);
                    }
                    $this->setMozgat();
                    $this->setFoglal();
                }
            }
        }
    }

    public function removeTermek() {
        if ($this->termek !== null) {
            $this->termek = null;
            if (!$this->duplication) {
                $this->termeknev = '';
                $this->cikkszam = '';
                $this->hosszusag = 0;
                $this->ehparany = 0;
                $this->idegencikkszam = '';
                $this->kiszereles = 0;
                $this->magassag = 0;
                $this->me = '';
                $this->osszehajthato = false;
                $this->suly = 0;
                $this->szelesseg = 0;
                $this->setMozgat();
                $this->setFoglal();
            }
        }
    }

    public function getTermeknev() {
        return $this->termeknev;
    }

    public function setTermeknev($val) {
        $this->termeknev = $val;
    }

    public function getCikkszam() {
        return $this->cikkszam;
    }

    public function setCikkszam($val) {
        $this->cikkszam = $val;
    }

    public function getIdegencikkszam() {
        return $this->idegencikkszam;
    }

    public function setIdegencikkszam($val) {
        $this->idegencikkszam = $val;
    }

    public function getME() {
        return $this->me;
    }

    public function setME($val) {
        $this->me = $val;
    }

    public function getVtsz() {
        return $this->vtsz;
    }

    public function getVtszszam() {
        return $this->vtszszam;
    }

    public function getVtsznev() {
        return $this->vtsznev;
    }

    public function getVtszId() {
        if ($this->vtsz) {
            return $this->vtsz->getId();
        }
        return '';
    }

    /**
     * @param \Entities\Vtsz $val
     */
    public function setVtsz($val) {
        if (!is_object($val)) {
            $val = \mkw\Store::getEm()->getRepository('Entities\Vtsz')->find($val);
        }
        if (!$val) {
            $this->removeVtsz();
        }
        else {
            if ($this->vtsz !== $val) {
                $this->vtsz = $val;
                $this->vtsznev = $val->getNev();
                $this->vtszszam = $val->getSzam();
                if (!$this->duplication) {
                    $afa = $val->getAfa();
                    if ($afa) {
                        $this->setAfa($afa);
                    }
                }
            }
        }
    }

    public function removeVtsz() {
        if ($this->vtsz !== null) {
            $this->vtsz = null;
            $this->vtsznev = '';
            $this->vtszszam = '';
        }
    }

    public function getAfa() {
        return $this->afa;
    }

    public function getAfanev() {
        return $this->afanev;
    }

    public function getAfakulcs() {
        return $this->afakulcs;
    }

    public function getAfaId() {
        if ($this->afa) {
            return $this->afa->getId();
        }
        return '';
    }

    /**
     * @param \Entities\Afa|int $val
     */
    public function setAfa($val) {
        if (!is_object($val)) {
            $val = \mkw\Store::getEm()->getRepository('Entities\Afa')->find($val);
        }
        if (!$val) {
            $this->removeAfa();
        }
        else {
            if ($this->afa !== $val) {
                $this->afa = $val;
                $this->setAfanev($val->getNev());
                $this->setAfakulcs($val->getErtek());
            }
        }
    }

    public function removeAfa() {
        if ($this->afa !== null) {
            $this->afa = null;
            $this->setAfanev('');
            $this->setAfakulcs(0);
        }
    }

    public function getGymennyiseg() {
        return $this->gymennyiseg;
    }

    public function setGymennyiseg($val) {
        $this->gymennyiseg = $val;
    }

    public function getMennyiseg() {
        return $this->mennyiseg;
    }

    public function setMennyiseg($val) {
        $this->mennyiseg = $val;
    }

    public function getNettoegysar() {
        return $this->nettoegysar;
    }

    public function setNettoegysar($val) {
        $this->nettoegysar = $val;
        if (!$this->duplication && $this->getAfa()) {
            $this->bruttoegysar = $this->getAfa()->calcBrutto($val);
        }
    }

    public function getBruttoegysar() {
        return $this->bruttoegysar;
    }

    public function setBruttoegysar($val) {
        $this->bruttoegysar = $val;
        if (!$this->duplication && $this->getAfa()) {
            $this->nettoegysar = $this->getAfa()->calcNetto($val);
        }
    }

    public function getNettoegysarhuf() {
        return $this->nettoegysarhuf;
    }

    public function setNettoegysarhuf($val) {
        $this->nettoegysarhuf = $val;
        if (!$this->duplication && $this->getAfa()) {
            $this->bruttoegysarhuf = $this->getAfa()->calcBrutto($val);
        }
    }

    public function getBruttoegysarhuf() {
        return $this->bruttoegysarhuf;
    }

    public function setBruttoegysarhuf($val) {
        $this->bruttoegysarhuf = $val;
        if (!$this->duplication && $this->getAfa()) {
            $this->nettoegysarhuf = $this->getAfa()->calcNetto($val);
        }
    }

    public function getEnettoegysar() {
        return $this->enettoegysar;
    }

    public function getEbruttoegysar() {
        return $this->ebruttoegysar;
    }

    public function getEnettoegysarhuf() {
        return $this->enettoegysarhuf;
    }

    public function getEbruttoegysarhuf() {
        return $this->ebruttoegysarhuf;
    }

    public function getNetto() {
        return $this->netto;
    }

    public function setNetto($val) {
        $this->netto = $val;
    }

    public function getAfaertek() {
        return $this->afaertek;
    }

    public function setAfaertek($val) {
        $this->afaertek = $val;
    }

    public function getBrutto() {
        return $this->brutto;
    }

    public function setBrutto($val) {
        $this->brutto = $val;
    }

    public function getValutanem() {
        return $this->valutanem;
    }

    public function getValutanemnev() {
        return $this->valutanemnev;
    }

    public function getValutanemId() {
        if ($this->valutanem) {
            return $this->valutanem->getId();
        }
        return '';
    }

    /**
     * @param \Entities\Valutanem $val
     */
    public function setValutanem($val) {
        if ($this->valutanem !== $val) {
            if (!$val) {
                $this->removeValutanem();
            }
            else {
                $this->valutanem = $val;
                $this->valutanemnev = $val->getNev();
            }
        }
    }

    public function removeValutanem() {
        if ($this->valutanem !== null) {
            $this->valutanem = null;
            $this->valutanemnev = '';
        }
    }

    public function getNettohuf() {
        return $this->nettohuf;
    }

    public function setNettohuf($val) {
        $this->nettohuf = $val;
    }

    public function getAfaertekhuf() {
        return $this->afaertekhuf;
    }

    public function setAfaertekhuf($val) {
        $this->afaertekhuf = $val;
    }

    public function getBruttohuf() {
        return $this->bruttohuf;
    }

    public function setBruttohuf($val) {
        $this->bruttohuf = $val;
    }

    public function getArfolyam() {
        return $this->arfolyam;
    }

    public function setArfolyam($val) {
        $this->arfolyam = $val;
    }

    public function getParbizonylattetel() {
        return $this->parbizonylattetel;
    }

    public function getParbizonylattetelId() {
        if ($this->parbizonylattetel) {
            return $this->parbizonylattetel->getId();
        }
        return '';
    }

    /**
     * @param \Entities\Bizonylattetel $val
     */
    public function setParbizonylattetel($val) {
        if ($this->parbizonylattetel !== $val) {
            $this->parbizonylattetel = $val;
            $val->addSzulobizonylattetel($this);
        }
    }

    public function removeParbizonylattetel() {
        if ($this->parbizonylattetel !== null) {
            $val = $this->parbizonylattetel;
            $this->parbizonylattetel = null;
            $val->removeSzulobizonylattetel($this);
        }
    }

    public function getSzulobizonylattetelek() {
        return $this->szulobizonylattetelek;
    }

    /**
     * @param \Entities\Bizonylattetel $val
     */
    public function addSzulobizonylattetel($val) {
        if (!$this->szulobizonylattetelek->contains($val)) {
            $this->szulobizonylattetelek->add($val);
            $val->setParbizonylattetel($this);
        }
    }

    /**
     * @param \Entities\Bizonylattetel $val
     */
    public function removeSzulobizonylattetel($val) {
        if ($this->szulobizonylattetelek->removeElement($val)) {
            $val->removeParbizonylattetel();
            return true;
        }
        return false;
    }

    public function getHatarido() {
        return $this->hatarido;
    }

    public function getHataridoStr() {
        if ($this->getHatarido()) {
            return $this->getHatarido()->format(store::$DateFormat);
        }
        return '';
    }

    public function setHatarido($adat) {
        if (is_a($adat, 'DateTime') || is_a($adat, 'DateTimeImmutable')) {
            $this->hatarido = $adat;
        }
        else {
            if ($adat == '') {
                $adat = date(store::$DateFormat);
            }
            $this->hatarido = new \DateTime(store::convDate($adat));
        }
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

    public function getValtozatertek1() {
        return $this->valtozatertek1;
    }

    public function setValtozatertek1($val) {
        $this->valtozatertek1 = $val;
    }

    public function getValtozatertek2() {
        return $this->valtozatertek2;
    }

    public function setValtozatertek2($val) {
        $this->valtozatertek2 = $val;
    }

    public function getValtozatadattipus1() {
        return $this->valtozatadattipus1;
    }

    /**
     * @param \Entities\TermekValtozatAdatTipus $adat
     */
    public function setValtozatadattipus1($adat) {
        $this->valtozatadattipus1 = $adat;
        if ($adat) {
            $this->valtozatadattipus1nev = $adat->getNev();
        }
        else {
            $this->valtozatadattipus1nev = '';
        }
    }

    public function getValtozatadattipus2() {
        return $this->valtozatadattipus2;
    }

    /**
     * @param \Entities\TermekValtozatAdatTipus $adat
     */
    public function setValtozatadattipus2($adat) {
        $this->valtozatadattipus2 = $adat;
        if ($adat) {
            $this->valtozatadattipus2nev = $adat->getNev();
        }
        else {
            $this->valtozatadattipus2nev = '';
        }
    }

    public function getValtozatadattipus1nev() {
        return $this->valtozatadattipus1nev;
    }

    public function setValtozatadattipus1nev($adat) {
        $this->valtozatadattipus1nev = $adat;
    }

    public function getValtozatadattipus2nev() {
        return $this->valtozatadattipus2nev;
    }

    public function setValtozatadattipus2nev($adat) {
        $this->valtozatadattipus2nev = $adat;
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
            if (!$this->duplication) {
                $this->setValtozatertek1($val->getErtek1());
                $this->setValtozatertek2($val->getErtek2());
                $this->setValtozatadattipus1($val->getAdattipus1());
                $this->setValtozatadattipus2($val->getAdattipus2());
            }
        }
    }

    public function removeTermekvaltozat() {
        if ($this->termekvaltozat !== null) {
            $this->termekvaltozat = null;
            if (!$this->duplication) {
                $this->setValtozatertek1('');
                $this->setValtozatertek2('');
                $this->setValtozatadattipus1(null);
                $this->setValtozatadattipus2(null);
            }
        }
    }

    public function getIrany() {
        return $this->irany;
    }

    public function setIrany($val) {
        $this->irany = $val;
    }

    public function getRontott() {
        return $this->rontott;
    }

    public function setRontott($adat) {
        $this->rontott = $adat;
    }

    public function getTranslations() {
        return $this->translations;
    }

    public function addTranslation(BizonylattetelTranslation $t) {
        if (!$this->translations->contains($t)) {
            $this->translations[] = $t;
            $t->setObject($this);
        }
    }

    public function removeTranslation(BizonylattetelTranslation $t) {
        $this->translations->removeElement($t);
    }

    public function getTermekcsoport() {
        return $this->termekcsoport;
    }

    /**
     * @param \Entities\Termekcsoport $adat
     */
    public function setTermekcsoport($adat) {
        $this->termekcsoport = $adat;
        if ($adat) {
            $this->termekcsoportnev = $adat->getNev();
        }
        else {
            $this->termekcsoportnev = '';
        }
    }

    public function getLocale() {
        return $this->locale;
    }

    public function setLocale($locale) {
        $this->locale = $locale;
    }

    public function duplicateFrom($entityB) {
        $this->duplication = true;
        $kivetel = array('setBizonylatfej');
        $methods = get_class_methods($this);
        foreach ($methods as $v) {
            if ((strpos($v, 'set') > -1) && (!in_array($v, $kivetel))) {
                $get = str_replace('set', 'get', $v);
                if (in_array($get, $methods)) {
                    $this->$v($entityB->$get());
                }
            }
        }
        $this->duplication = false;
    }

    /**
     * @return mixed
     */
    public function getHparany() {
        return $this->hparany;
    }

    /**
     * @param mixed $hparany
     */
    public function setHparany($hparany) {
        $this->hparany = $hparany;
    }

    /**
     * @return mixed
     */
    public function getTermekcsoportnev() {
        return $this->termekcsoportnev;
    }

    /**
     * @param mixed $termekcsoportnev
     */
    public function setTermekcsoportnev($termekcsoportnev) {
        $this->termekcsoportnev = $termekcsoportnev;
    }

    /**
     * @param mixed $afakulcs
     */
    public function setAfakulcs($afakulcs) {
        $this->afakulcs = $afakulcs;
    }

    /**
     * @param mixed $afanev
     */
    public function setAfanev($afanev) {
        $this->afanev = $afanev;
    }

    /**
     * @param mixed $ebruttoegysar
     */
    public function setEbruttoegysar($ebruttoegysar) {
        $this->ebruttoegysar = $ebruttoegysar;
    }

    /**
     * @param mixed $ebruttoegysarhuf
     */
    public function setEbruttoegysarhuf($ebruttoegysarhuf) {
        $this->ebruttoegysarhuf = $ebruttoegysarhuf;
    }

    /**
     * @param mixed $enettoegysar
     */
    public function setEnettoegysar($enettoegysar) {
        $this->enettoegysar = $enettoegysar;
    }

    /**
     * @param mixed $enettoegysarhuf
     */
    public function setEnettoegysarhuf($enettoegysarhuf) {
        $this->enettoegysarhuf = $enettoegysarhuf;
    }

    /**
     * @param mixed $valutanemnev
     */
    public function setValutanemnev($valutanemnev) {
        $this->valutanemnev = $valutanemnev;
    }

    /**
     * @param mixed $vtsznev
     */
    public function setVtsznev($vtsznev) {
        $this->vtsznev = $vtsznev;
    }

    /**
     * @param mixed $vtszszam
     */
    public function setVtszszam($vtszszam) {
        $this->vtszszam = $vtszszam;
    }

    /**
     * @return mixed
     */
    public function getKedvezmeny() {
        return $this->kedvezmeny;
    }

    /**
     * @param mixed $kedvezmeny
     */
    public function setKedvezmeny($kedvezmeny) {
        $this->kedvezmeny = $kedvezmeny;
    }


}
