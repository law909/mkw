<?php

namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\TermekRepository")
 * @ORM\Table(name="termek",indexes={
 * 		@ORM\index(name="termekfakarkod_idx",columns={"termekfa1karkod","termekfa2karkod","termekfa3karkod"}),
 * 		@ORM\index(name="termekfacounter_idx",columns={"inaktiv","lathato"}),
 * 		@ORM\index(name="termekslug_idx",columns={"slug"}),
 *      @ORM\index(name="termekvonalkod_idx",columns={"vonalkod"}),
 * 		@ORM\index(name="termekcikkszamgyarto_idx",columns={"cikkszam","gyarto_id"}),
 * 		@ORM\index(name="termekidegencikkszamgyarto_idx",columns={"idegencikkszam","gyarto_id"}),
 *      @ORM\index(name="termekidegenkod_idx",columns={"idegenkod"})
 * })
 * @Gedmo\TranslationEntity(class="Entities\TermekTranslation")
 * @ORM\HasLifecycleCallbacks
 */
class Termek {

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

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $idegenkod = '';

    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="string",length=255,nullable=false)
     */
    private $nev = '';

    /** @ORM\Column(type="string",length=20,nullable=true) */
    private $me = '';

    /** @ORM\Column(type="decimal",precision=14,scale=2,nullable=true) */
    private $kiszereles = 0;

    /**
     * @ORM\ManyToOne(targetEntity="Vtsz")
     * @ORM\JoinColumn(name="vtsz_id",referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $vtsz;

    /**
     * @ORM\ManyToOne(targetEntity="Afa")
     * @ORM\JoinColumn(name="afa_id",referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $afa;

    /**
     * @ORM\ManyToMany(targetEntity="Termekcimketorzs",inversedBy="termekek")
     * @ORM\JoinTable(name="termek_cimkek",
     *  joinColumns={@ORM\JoinColumn(name="termek_id",referencedColumnName="id",onDelete="cascade")},
     *  inverseJoinColumns={@ORM\JoinColumn(name="cimketorzs_id",referencedColumnName="id",onDelete="cascade")}
     *  )
     */
    private $cimkek;

    /** @ORM\Column(type="text",nullable=true) */
    private $cimkenevek = '';

    /** @ORM\Column(type="string",length=50,nullable=true) */
    private $cikkszam = '';

    /** @ORM\Column(type="string",length=50,nullable=true) */
    private $idegencikkszam = '';

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $vonalkod = '';

    /**
     * @ORM\Column(type="text",nullable=true)
     */
    private $leiras = '';

    /**
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    private $rovidleiras = '';

    /**
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    private $oldalcim = '';

    /**
     * @ORM\Column(type="text",nullable=true)
     */
    private $seodescription = '';

    /**
     * @Gedmo\Slug(fields={"nev"})
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    private $slug;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $lathato = 1;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $hozzaszolas = 1;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $ajanlott = 0;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $kiemelt = 0;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $mozgat = 1;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $inaktiv = 0;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $fuggoben = 0;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $termekexportbanszerepel = true;

    /** @ORM\Column(type="decimal",precision=14,scale=2,nullable=true) */
    private $hparany = 0;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $netto = 0;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $brutto = 0;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $akciosnetto = 0;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $akciosbrutto = 0;

    /** @ORM\Column(type="date",nullable=true) */
    private $akciostart;

    /** @ORM\Column(type="date",nullable=true) */
    private $akciostop;

    /**
     * @ORM\ManyToOne(targetEntity="TermekFa",inversedBy="termekek1")
     * @ORM\JoinColumn(name="termekfa1_id",referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $termekfa1;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $termekfa1karkod = '';

    /**
     * @ORM\ManyToOne(targetEntity="TermekFa",inversedBy="termekek2")
     * @ORM\JoinColumn(name="termekfa2_id",referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $termekfa2;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $termekfa2karkod = '';

    /**
     * @ORM\ManyToOne(targetEntity="TermekFa",inversedBy="termekek3")
     * @ORM\JoinColumn(name="termekfa3_id",referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $termekfa3;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $termekfa3karkod = '';

    /** @ORM\Column(type="text",nullable=true) */
    private $kepurl = '';

    /** @ORM\Column(type="text",nullable=true) */
    private $kepleiras = '';

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

    /** @ORM\OneToMany(targetEntity="TermekKep", mappedBy="termek", cascade={"persist"}) */
    private $termekkepek;

    /** @ORM\OneToMany(targetEntity="TermekValtozat",mappedBy="termek",cascade={"persist"}) */
    private $valtozatok;

    /** @ORM\OneToMany(targetEntity="TermekRecept", mappedBy="termek", cascade={"persist"}) */
    private $termekreceptek;

    /** @ORM\OneToMany(targetEntity="TermekRecept", mappedBy="altermek", cascade={"persist"}) */
    private $altermekreceptek;

    /** @ORM\OneToMany(targetEntity="Bizonylattetel", mappedBy="termek",cascade={"persist"}) */
    private $bizonylattetelek;

    /** @ORM\OneToMany(targetEntity="Kosar", mappedBy="termek",cascade={"persist"}) */
    private $kosarak;

    /** @ORM\Column(type="integer",nullable=true) */
    private $megtekintesdb;

    /** @ORM\Column(type="integer",nullable=true) */
    private $megvasarlasdb;

    /** @ORM\OneToMany(targetEntity="TermekKapcsolodo", mappedBy="termek", cascade={"persist"}) */
    private $termekkapcsolodok;

    /** @ORM\OneToMany(targetEntity="TermekKapcsolodo", mappedBy="altermek", cascade={"persist"}) */
    private $altermekkapcsolodok;

    /**
     * @ORM\ManyToOne(targetEntity="TermekValtozatAdatTipus")
     * @ORM\JoinColumn(name="valtozatadattipus_id",referencedColumnName="id",onDelete="restrict")
     */
    private $valtozatadattipus;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $nemkaphato = false;

    /** @ORM\OneToMany(targetEntity="TermekErtesito", mappedBy="termek",cascade={"persist"}) */
    private $termekertesitok;

    /**
     * @ORM\ManyToOne(targetEntity="Partner")
     * @ORM\JoinColumn(name="gyarto_id",referencedColumnName="id",nullable=true,onDelete="set null")
     */
    private $gyarto;

    /** @ORM\Column(type="integer",nullable=true) */
    private $szallitasiido;

    /** @ORM\Column(type="text",nullable=true) */
    private $regikepurl = '';

    /** @ORM\OneToMany(targetEntity="TermekAr", mappedBy="termek", cascade={"persist", "remove"}) */
    private $termekarak;

    /** @ORM\OneToMany(targetEntity="TermekTranslation", mappedBy="object", cascade={"persist", "remove"}) */
    private $translations;

    /** @Gedmo\Locale */
    protected $locale;

    /**
     * @ORM\PrePersist
     */
    public function generateVonalkod() {
        if (\mkw\Store::getSetupValue('vonalkod') && !$this->vonalkod) {
            $conn = \mkw\Store::getEm()->getConnection();
            $stmt = $conn->prepare('INSERT INTO vonalkodseq (data) VALUES (1)');
            $stmt->execute();
            $this->setVonalkod((string)$conn->lastInsertId());
        }
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function doStuffOnPrePersist() {
        $res = array();
        foreach ($this->cimkek as $cimke) {
            $res[] = $cimke->getNev();
        }
        $this->cimkenevek = implode('; ', $res);
    }

    public function __construct() {
        $this->cimkek = new \Doctrine\Common\Collections\ArrayCollection();
        $this->termekkepek = new \Doctrine\Common\Collections\ArrayCollection();
        $this->valtozatok = new \Doctrine\Common\Collections\ArrayCollection();
        $this->termekreceptek = new \Doctrine\Common\Collections\ArrayCollection();
        $this->altermekreceptek = new \Doctrine\Common\Collections\ArrayCollection();
        $this->bizonylattetelek = new \Doctrine\Common\Collections\ArrayCollection();
        $this->kosarak = new \Doctrine\Common\Collections\ArrayCollection();
        $this->termekkapcsolodok = new \Doctrine\Common\Collections\ArrayCollection();
        $this->altermekkapcsolodok = new \Doctrine\Common\Collections\ArrayCollection();
        $this->termekertesitok = new \Doctrine\Common\Collections\ArrayCollection();
        $this->termekarak = new \Doctrine\Common\Collections\ArrayCollection();
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getUjTermek($min) {
        return $this->id >= $min;
    }

    public function getTop10($top10min) {
        return $this->megvasarlasdb >= $top10min;
    }

    public function getKeszlet() {
        $k = 0;
        foreach($this->bizonylattetelek as $bt) {
            if ($bt->getMozgat()) {
                $k += ($bt->getMennyiseg() * $bt->getIrany());
            }
        }
        return $k;
    }

    public function toTermekLista($valtozat = null, $ujtermekid = null, $top10min = null) {
        $x = array();
        $x['ujtermek'] = $this->getUjTermek($ujtermekid);
        $x['top10'] = $this->getTop10($top10min);
        $x['id'] = $this->getId();
        $x['kozepeskepurl'] = $this->getKepurlMedium();
        $x['kiskepurl'] = $this->getKepurlSmall();
        $x['minikepurl'] = $this->getKepurlMini();
        $x['kepurl'] = $this->getKepurlLarge();
        $x['slug'] = $this->getSlug();
        $x['link'] = \mkw\Store::getRouter()->generate('showtermek', false, array('slug' => $this->getSlug()));
        $x['caption'] = $this->getNev();
        $x['rovidleiras'] = $this->getRovidLeiras();
        $x['akcios'] = $this->getAkcios();
        $x['akciotipus'] = $this->getAkcioTipus();
        $x['akciostart'] = $this->getAkciostartStr();
        $x['akciostop'] = $this->getAkciostopStr();
        $x['bruttohuf'] = $this->getBruttoAr($valtozat, \mkw\Store::getLoggedInUser());
        $x['eredetibruttohuf'] = $this->getEredetiBruttoAr($valtozat);
        $x['nemkaphato'] = $this->getNemkaphato() || $this->getFuggoben();
        $x['ingyenszallitas'] = (\mkw\Store::calcSzallitasiKoltseg($x['bruttohuf']) == 0);
        if ($this->szallitasiido) {
            $x['szallitasiido'] = $this->szallitasiido;
        }
        else {
            if ($this->gyarto && $this->gyarto->getSzallitasiido()) {
                $x['szallitasiido'] = $this->gyarto->getSzallitasiido();
            }
            else {
                $x['szallitasiido'] = 0;
            }
        }

        $listaban = array();
        foreach ($this->getCimkek() as $cimke) {
            $kat = $cimke->getKategoria();
            if ($kat->getTermeklistabanlathato()) {
                $listaban[] = $cimke->toLista();
            }
        }
        $x['cimkelistaban'] = $listaban;

        if (!is_null($valtozat)) {
            if ($valtozat->getKepurlSmall()) {
                $x['kozepeskepurl'] = $valtozat->getKepurlMedium();
                $x['kiskepurl'] = $valtozat->getKepurlSmall();
                $x['minikepurl'] = $valtozat->getKepurlMini();
                $x['kepurl'] = $valtozat->getKepurlLarge();
            }
            $x['valtozatid'] = $valtozat->getId();
            if ($this->getValtozatadattipusId() == $valtozat->getAdatTipus1Id()) {
                $ertek = $valtozat->getErtek1();
                $x['valtozatok']['fixname'] = $valtozat->getAdatTipus1Nev();
                $x['valtozatok']['fixvalue'] = $ertek;
                $x['valtozatok']['name'] = $valtozat->getAdatTipus2Nev();
            }
            elseif ($this->getValtozatadattipusId() == $valtozat->getAdatTipus2Id()) {
                $ertek = $valtozat->getErtek2();
                $x['valtozatok']['fixname'] = $valtozat->getAdatTipus2Nev();
                $x['valtozatok']['fixvalue'] = $ertek;
                $x['valtozatok']['name'] = $valtozat->getAdatTipus1Nev();
            }
            $adatt = array();
            $valtozatok = $this->getValtozatok();
            foreach ($valtozatok as $valt) {
                if ($valt->getElerheto()) {
                    if ($this->getValtozatadattipusId() == $valt->getAdatTipus1Id() && $valt->getErtek1() == $ertek &&
                            $valt->getAdatTipus2Id()) {
                        $adatt[] = array('id' => $valt->getId(), 'value' => $valt->getErtek2(), 'selected' => $valt->getId() == $valtozat->getId());
                    }
                    elseif ($this->getValtozatadattipusId() == $valt->getAdatTipus2Id() && $valt->getErtek2() == $ertek &&
                            $valt->getAdatTipus1Id()) {
                        $adatt[] = array('id' => $valt->getId(), 'value' => $valt->getErtek1(), 'selected' => $valt->getId() == $valtozat->getId());
                    }
                }
            }
            $x['valtozatok']['data'] = $adatt;
        }
        else {
            $vtt = array();
            $valtozatok = $this->getValtozatok();
            $db = 0;
            foreach ($valtozatok as $valt) {
                if ($valt->getElerheto()) {
                    $db++;
                }
            }
            foreach ($valtozatok as $valt) {
                if ($valt->getElerheto()) {
                    if ($valt->getAdatTipus1Id() && $valt->getAdatTipus1Nev()) {
                        $vtt[$valt->getAdatTipus1Id()]['tipusid'] = $valt->getAdatTipus1Id();
                        $vtt[$valt->getAdatTipus1Id()]['name'] = $valt->getAdatTipus1Nev();
                        $vtt[$valt->getAdatTipus1Id()]['value'][$valt->getErtek1()] = $valt->getErtek1();
                        $vtt[$valt->getAdatTipus1Id()]['selected'][$valt->getErtek1()] = $db === 1;
                    }
                    if ($valt->getAdatTipus2Id() && $valt->getAdatTipus2Nev()) {
                        $vtt[$valt->getAdatTipus2Id()]['tipusid'] = $valt->getAdatTipus2Id();
                        $vtt[$valt->getAdatTipus2Id()]['name'] = $valt->getAdatTipus2Nev();
                        $vtt[$valt->getAdatTipus2Id()]['value'][$valt->getErtek2()] = $valt->getErtek2();
                        $vtt[$valt->getAdatTipus2Id()]['selected'][$valt->getErtek2()] = $db === 1;
                    }
                }
            }
            $x['mindenvaltozat'] = $vtt;
        }
        return $x;
    }

    public function toKiemeltLista($valtozat = null) {
        $x = array();
        $x['id'] = $this->getId();
        $x['kozepeskepurl'] = $this->getKepurlMedium();
        $x['kiskepurl'] = $this->getKepurlSmall();
        $x['minikepurl'] = $this->getKepurlMini();
        $x['kepurl'] = $this->getKepurlLarge();
        $x['slug'] = $this->getSlug();
        $x['caption'] = $this->getNev();
        $x['link'] = \mkw\Store::getRouter()->generate('showtermek', false, array('slug' => $this->getSlug()));
        $x['rovidleiras'] = $this->getRovidLeiras();
        $x['akcios'] = $this->getAkcios();
        $x['akciotipus'] = $this->getAkcioTipus();
        $x['akciostart'] = $this->getAkciostartStr();
        $x['akciostop'] = $this->getAkciostopStr();
        $x['bruttohuf'] = $this->getBruttoAr($valtozat, \mkw\Store::getLoggedInUser());
        $x['eredetibruttohuf'] = $this->getEredetiBruttoAr($valtozat);
        $x['nemkaphato'] = $this->getNemkaphato() || $this->getFuggoben();

        $listaban = array();
        foreach ($this->getCimkek() as $cimke) {
            $kat = $cimke->getKategoria();
            if ($kat->getTermeklistabanlathato()) {
                $listaban[] = $cimke->toLista();
            }
        }
        $x['cimkelistaban'] = $listaban;

        return $x;
    }

    public function toTermekLap($valtozat = null, $ujtermekid = null, $top10min = null) {
        $x = array();

        $x['ujtermek'] = $this->getUjTermek($ujtermekid);
        $x['top10'] = $this->getTop10($top10min);
        $x['id'] = $this->getId();
        $x['caption'] = $this->getNev();
        $x['slug'] = $this->getSlug();
        $x['kepurl'] = $this->getKepurlLarge();
        $x['fullkepurl'] = \mkw\Store::getFullUrl($this->getKepurlLarge());
        $x['kozepeskepurl'] = $this->getKepUrlMedium();
        $x['minikepurl'] = $this->getKepurlMini();
        $x['rovidleiras'] = $this->getRovidleiras();
        $x['leiras'] = $this->getLeiras();
        $x['cikkszam'] = $this->getCikkszam();
        $x['me'] = $this->getMe();
        $x['hozzaszolas'] = $this->getHozzaszolas();
        $x['akcios'] = $this->getAkcios();
        $x['akciotipus'] = $this->getAkcioTipus();
        $x['akciostart'] = $this->getAkciostartStr();
        $x['akciostop'] = $this->getAkciostopStr();
        $x['bruttohuf'] = $this->getBruttoAr($valtozat, \mkw\Store::getLoggedInUser());
        $x['eredetibruttohuf'] = $this->getEredetiBruttoAr($valtozat);
        $x['nemkaphato'] = $this->getNemkaphato() || $this->getFuggoben();
        $x['ingyenszallitas'] = (\mkw\Store::calcSzallitasiKoltseg($x['bruttohuf']) == 0);
        $x['husegpont'] = floor($x['bruttohuf'] * $this->getHparany() / 100);
        if ($this->szallitasiido) {
            $x['szallitasiido'] = $this->szallitasiido;
        }
        else {
            if ($this->gyarto && $this->gyarto->getSzallitasiido()) {
                $x['szallitasiido'] = $this->gyarto->getSzallitasiido();
            }
            else {
                $x['szallitasiido'] = 0;
            }
        }

        $altomb = array();
        foreach ($this->getTermekKepek() as $kep) {
            $egyed = array();
            $egyed['kepurl'] = $kep->getUrlLarge();
            $egyed['kozepeskepurl'] = $kep->getUrlMedium();
            $egyed['kiskepurl'] = $kep->getUrlSmall();
            $egyed['minikepurl'] = $kep->getUrlMini();
            $egyed['leiras'] = $kep->getLeiras();
            $altomb[] = $egyed;
        }
        $x['kepek'] = $altomb;

        $altomb = array();
        foreach ($this->getTermekKapcsolodok() as $kapcsolodo) {
            $altomb[] = $kapcsolodo->getAlTermek()->toKapcsolodo();
        }
        $x['kapcsolodok'] = $altomb;

        $lapon = array();
        $akciodobozban = array();
        foreach ($this->getCimkek() as $cimke) {
            $kat = $cimke->getKategoria();
            if ($kat->getTermeklaponlathato()) {
                $lapon[] = $cimke->toLista();
            }
            if ($kat->getTermekakciodobozbanlathato()) {
                $akciodobozban[] = $cimke->toLista();
            }
        }
        $x['cimkelapon'] = $lapon;
        $x['cimkeakciodobozban'] = $akciodobozban;

        $vtt = array();
        $valtozatok = $this->getValtozatok();
        foreach ($valtozatok as $valt) {
            if ($valt->getElerheto()) {
                if ($valt->getAdatTipus1Id() && $valt->getAdatTipus1Nev()) {
                    $vtt[$valt->getAdatTipus1Id()]['tipusid'] = $valt->getAdatTipus1Id();
                    $vtt[$valt->getAdatTipus1Id()]['name'] = $valt->getAdatTipus1Nev();
                    $vtt[$valt->getAdatTipus1Id()]['value'][$valt->getErtek1()] = $valt->getErtek1();
                }
                if ($valt->getAdatTipus2Id() && $valt->getAdatTipus2Nev()) {
                    $vtt[$valt->getAdatTipus2Id()]['tipusid'] = $valt->getAdatTipus2Id();
                    $vtt[$valt->getAdatTipus2Id()]['name'] = $valt->getAdatTipus2Nev();
                    $vtt[$valt->getAdatTipus2Id()]['value'][$valt->getErtek2()] = $valt->getErtek2();
                }
            }
        }
        $x['valtozatok'] = $vtt;

        $hasontomb = array();
        $r = \mkw\Store::getEm()->getRepository('Entities\Termek');
        $hason = $r->getHasonloTermekek($this,
                \mkw\Store::getParameter(\mkw\consts::Hasonlotermekdb, 3),
                \mkw\Store::getParameter(\mkw\consts::Hasonlotermekarkulonbseg, 10));
        foreach($hason as $has) {
            $hasontomb[] = $has->toKapcsolodo();
        }
        $x['hasonlotermekek'] = $hasontomb;

        return $x;
    }

    public function toKapcsolodo($valtozat = null) {
        $x = array();
        $x['id'] = $this->getId();
        $x['kozepeskepurl'] = $this->getKepUrlMedium();
        $x['kiskepurl'] = $this->getKepUrlSmall();
        $x['minikepurl'] = $this->getKepurlMini();
        $x['kepurl'] = $this->getKepUrlLarge();
        $x['slug'] = $this->getSlug();
        $x['caption'] = $this->getNev();
        $x['rovidleiras'] = $this->getRovidLeiras();
        $x['akcios'] = $this->getAkcios();
        $x['bruttohuf'] = $this->getBruttoAr($valtozat, \mkw\Store::getLoggedInUser());
        $x['eredetibruttohuf'] = $this->getEredetiBruttoAr($valtozat);
        $x['link'] = \mkw\Store::getRouter()->generate('showtermek', false, array('slug' => $this->getSlug()));
        return $x;
    }

    public function toKosar($valtozat) {
        $x = array();
        $x['id'] = $this->getId();
        $x['kozepeskepurl'] = $this->getKepUrlMedium();
        $x['kiskepurl'] = $this->getKepUrlSmall();
        $x['minikepurl'] = $this->getKepurlMini();
        $x['kepurl'] = $this->getKepUrlLarge();
        $x['slug'] = $this->getSlug();
        $x['caption'] = $this->getNev();
        $x['rovidleiras'] = $this->getRovidLeiras();
        $x['cikkszam'] = $this->getCikkszam();
        $x['me'] = $this->getMe();
        if ($valtozat) {
            if ($valtozat->getKepUrlMedium()) {
                $x['kozepeskepurl'] = $valtozat->getKepUrlMedium();
                $x['kiskepurl'] = $valtozat->getKepurlSmall();
                $x['kepurl'] = $valtozat->getKepurlLarge();
                $x['minikepurl'] = $valtozat->getKepurlMini();
            }
        }
        $x['link'] = \mkw\Store::getRouter()->generate('showtermek', false, array('slug' => $this->getSlug()));
        return $x;
    }

    public function toMenu() {
        $x = array();
        $x['id'] = $this->getId();
        $x['kozepeskepurl'] = $this->getKepurlMedium();
        $x['kiskepurl'] = $this->getKepurlSmall();
        $x['minikepurl'] = $this->getKepurlMini();
        $x['kepurl'] = $this->getKepurlLarge();
        $x['slug'] = $this->getSlug();
        $x['link'] = \mkw\Store::getRouter()->generate('showtermek', false, array('slug' => $this->getSlug()));
        $x['caption'] = $this->getNev();
        $x['cikkszam'] = $this->getCikkszam();
        $x['rovidleiras'] = $this->getRovidLeiras();
        $x['nemkaphato'] = $this->getNemkaphato() || $this->getFuggoben();
        if ($this->szallitasiido) {
            $x['szallitasiido'] = $this->szallitasiido;
        }
        else {
            if ($this->gyarto && $this->gyarto->getSzallitasiido()) {
                $x['szallitasiido'] = $this->gyarto->getSzallitasiido();
            }
            else {
                $x['szallitasiido'] = 0;
            }
        }
        return $x;
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

    public function getMe() {
        return $this->me;
    }

    public function setMe($me) {
        $this->me = $me;
    }

    public function getVtsz() {
        return $this->vtsz;
    }

    public function getVtszNev() {
        if ($this->vtsz) {
            return $this->vtsz->getNev();
        }
        return '';
    }

    public function getVtszId() {
        if ($this->vtsz) {
            return $this->vtsz->getId();
        }
        return '';
    }

    public function setVtsz($vtsz) {
        $this->vtsz = $vtsz;
        if ($vtsz) {
            $afa = $vtsz->getAfa();
            if ($afa) {
                $this->setAfa($afa);
            }
        }
    }

    public function getAfa() {
        return $this->afa;
    }

    public function getAfaNev() {
        if ($this->afa) {
            return $this->afa->getNev();
        }
        return '';
    }

    public function getAfaId() {
        if ($this->afa) {
            return $this->afa->getId();
        }
        return '';
    }

    public function setAfa($afa) {
        $this->afa = $afa;
    }

    public function getCikkszam() {
        return $this->cikkszam;
    }

    public function setCikkszam($cikkszam) {
        $this->cikkszam = $cikkszam;
    }

    public function getIdegencikkszam() {
        return $this->idegencikkszam;
    }

    public function setIdegencikkszam($idegencikkszam) {
        $this->idegencikkszam = $idegencikkszam;
    }

    public function getVonalkod() {
        return $this->vonalkod;
    }

    public function setVonalkod($vonalkod) {
        $this->vonalkod = $vonalkod;
    }

    public function getLeiras() {
        return $this->leiras;
    }

    public function setLeiras($leiras) {
        $this->leiras = $leiras;
    }

    public function getRovidleiras() {
        return $this->rovidleiras;
    }

    public function setRovidleiras($rovidleiras) {
        $this->rovidleiras = $rovidleiras;
    }

    public function getOldalcim() {
        return $this->oldalcim;
    }

    public function getShowOldalcim() {
        if ($this->oldalcim) {
            return $this->oldalcim;
        }
        else {
            $result = \mkw\Store::getParameter(\mkw\consts::Termekoldalcim);
            if ($result) {
                $result = str_replace('[termeknev]', $this->getNev(), $result);
                $result = str_replace('[kategorianev]', $this->getTermekfa1Nev(), $result);
                $result = str_replace('[global]', \mkw\Store::getParameter(\mkw\consts::Oldalcim), $result);
                $result = str_replace('[bruttoar]', number_format($this->getBruttoAr(null, \mkw\Store::getLoggedInUser()), 0, ',', ''), $result);
                return $result;
            }
            else {
                return \mkw\Store::getParameter(\mkw\consts::Oldalcim);
            }
        }
    }

    public function setOldalcim($oldalcim) {
        $this->oldalcim = $oldalcim;
    }

    public function getSeodescription() {
        return $this->seodescription;
    }

    public function getShowSeodescription() {
        if ($this->seodescription) {
            return $this->seodescription;
        }
        else {
            $result = \mkw\Store::getParameter(\mkw\consts::Termekseodescription);
            if ($result) {
                $result = str_replace('[termeknev]', $this->getNev(), $result);
                $result = str_replace('[kategorianev]', $this->getTermekfa1Nev(), $result);
                $result = str_replace('[global]', \mkw\Store::getParameter(\mkw\consts::Seodescription), $result);
                $result = str_replace('[bruttoar]', number_format($this->getBruttoAr(null, \mkw\Store::getLoggedInUser()), 0, ',', ''), $result);
                return $result;
            }
            else {
                return \mkw\Store::getParameter(\mkw\consts::Seodescription);
            }
        }
    }

    public function setSeodescription($seodescription) {
        $this->seodescription = $seodescription;
    }

    public function getSlug() {
        return $this->slug;
    }

    public function setSlug($slug) {
        $this->slug = $slug;
    }

    public function getLathato() {
        return $this->lathato;
    }

    public function setLathato($lathato) {
        $this->lathato = $lathato;
    }

    public function getHozzaszolas() {
        return $this->hozzaszolas;
    }

    public function setHozzaszolas($hozzaszolas) {
        $this->hozzaszolas = $hozzaszolas;
    }

    public function getAjanlott() {
        return $this->ajanlott;
    }

    public function setAjanlott($ajanlott) {
        $this->ajanlott = $ajanlott;
    }

    public function getMozgat() {
        return $this->mozgat;
    }

    public function setMozgat($mozgat) {
        $this->mozgat = $mozgat;
    }

    public function getInaktiv() {
        return $this->inaktiv;
    }

    public function setInaktiv($inaktiv) {
        $this->inaktiv = $inaktiv;
    }

    public function getTermekexportbanszerepel() {
        return $this->termekexportbanszerepel;
    }

    public function setTermekexportbanszerepel($adat) {
        $this->termekexportbanszerepel = $adat;
    }

    public function getHparany() {
        return $this->hparany;
    }

    public function setHparany($hparany) {
        $this->hparany = $hparany;
    }

    public function getNetto() {
        return $this->netto;
    }

    public function setNetto($netto) {
        $this->netto = $netto;
        $this->brutto = $this->getAfa()->calcBrutto($netto);
    }

    public function getBrutto() {
        return $this->brutto;
    }

    public function setBrutto($brutto) {
        $this->brutto = $brutto;
        $this->netto = $this->getAfa()->calcNetto($brutto);
    }

    public function getAkcios() {
        $ma = date(\mkw\Store::$DateFormat);
        return
            (
                ($this->getAkciostartStr() != '') || ($this->getAkciostopStr() != '')
            ) &&
            (
                ($this->getAkciostartStr() <= $ma && $this->getAkciostopStr() >= $ma) ||
                ($this->getAkciostartStr() <= $ma && $this->getAkciostopStr() == '') ||
                ($this->getAkciostartStr() == '' && $this->getAkciostopStr() >= $ma)
            );
    }

    public function getAkcioTipus() {
        if ($this->getAkciostartStr() && $this->getAkciostopStr()) { // tol-ig
            return 1;
        }
        if ($this->getAkciostopStr()) { // ig
            return 2;
        }
        if ($this->getAkciostartStr()) { // keszlet erejeig
            return 3;
        }
        return 0;
    }

    public function getAkciosnetto() {
        return $this->akciosnetto;
    }

    public function setAkciosnetto($netto) {
        $this->akciosnetto = $netto;
        $this->akciosbrutto = $this->getAfa()->calcBrutto($netto);
    }

    public function getAkciosbrutto() {
        return $this->akciosbrutto;
    }

    public function setAkciosbrutto($brutto) {
        $this->akciosbrutto = $brutto;
        $this->akciosnetto = $this->getAfa()->calcNetto($brutto);
    }

    public function getAkciostart() {
        return $this->akciostart;
    }

    public function getAkciostartStr() {
        if ($this->getAkciostart()) {
            return $this->getAkciostart()->format(\mkw\Store::$DateFormat);
        }
        return '';
    }

    public function setAkciostart($adat = '') {
        if ($adat != '') {
            $this->akciostart = new \DateTime(\mkw\Store::convDate($adat));
        }
        else{
            $this->akciostart = null;
        }
    }

    public function getAkciostop() {
        return $this->akciostop;
    }

    public function getAkciostopStr() {
        if ($this->getAkciostop()) {
            return $this->getAkciostop()->format(\mkw\Store::$DateFormat);
        }
        return '';
    }

    public function setAkciostop($adat = '') {
        if ($adat != '') {
            $this->akciostop = new \DateTime(\mkw\Store::convDate($adat));
        }
        else {
            $this->akciostop = null;
        }
    }

    /**
     *
     * @return ArrayCollection
     */
    public function getCimkek() {
        return $this->cimkek;
    }

    public function getAllCimkeId() {
        $res = array();
        foreach ($this->cimkek as $cimke) {
            $res[] = $cimke->getId();
        }
        return $res;
    }

    public function setCimkeNevek($cimkenevek) {
        $this->cimkenevek = $cimkenevek;
    }

    public function getCimkeNevek() {
        return $this->cimkenevek;
    }

    public function addCimke(Cimketorzs $cimke) {
        if (!$this->cimkek->contains($cimke)) {
            $this->cimkek->add($cimke);
            $cimke->addTermek($this);
            $this->setCimkeNevek('');
        }
    }

    public function removeCimke(Cimketorzs $cimke) {
        if ($this->cimkek->removeElement($cimke)) {
            //$cimke->removeTermek($this);  // deleted for speed
            return true;
        }
        return false;
    }

    public function removeAllCimke() {
//		$this->cimkek->clear();
//		$this->setCimkeNevek('');
        foreach ($this->cimkek as $cimke) {
            $this->removeCimke($cimke);
        }
    }

    public function getCimkeByCategory($cat) {
        $ret = null;
        foreach ($this->getCimkek() as $cimke) {
            if ($cat == $cimke->getKategoriaId()) {
                $ret = $cimke;
                break;
            }
        }
        return $ret;
    }

    public function getIdegenkod() {
        return $this->idegenkod;
    }

    public function setIdegenkod($idegenkod) {
        $this->idegenkod = $idegenkod;
    }

    public function getKiszereles() {
        return $this->kiszereles;
    }

    public function setKiszereles($kiszereles) {
        $this->kiszereles = $kiszereles;
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

    public function getTermekAr($valtozat) {
        // Ezt mintha senki nem használná
        $ret = array('netto' => $this->getNettoAr($valtozat), 'brutto' => $this->getBruttoAr($valtozat));
        return $ret;
    }

    public function getTermekKepek() {
        return $this->termekkepek;
    }

    public function addTermekKep(TermekKep $kep) {
//		if (!$this->termekkepek->contains($kep)) {
        $this->termekkepek->add($kep);
        $kep->setTermek($this);
//		}
    }

    public function removeTermekKep(TermekKep $kep) {
        if ($this->termekkepek->removeElement($kep)) {
            $kep->removeTermek($this);
            return true;
        }
        return false;
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
            return implode('.', $t) . \mkw\Store::getParameter(\mkw\consts::Miniimgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function getKepurlSmall($pre = '/') {
        $kepurl = $this->getKepurl($pre);
        if ($kepurl) {
            $t = explode('.', $kepurl);
            $ext = array_pop($t);
            return implode('.', $t) . \mkw\Store::getParameter(\mkw\consts::Smallimgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function getKepurlMedium($pre = '/') {
        $kepurl = $this->getKepurl($pre);
        if ($kepurl) {
            $t = explode('.', $kepurl);
            $ext = array_pop($t);
            return implode('.', $t) . \mkw\Store::getParameter(\mkw\consts::Mediumimgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function getKepurlLarge($pre = '/') {
        $kepurl = $this->getKepurl($pre);
        if ($kepurl) {
            $t = explode('.', $kepurl);
            $ext = array_pop($t);
            return implode('.', $t) . \mkw\Store::getParameter(\mkw\consts::Bigimgpost, '') . '.' . $ext;
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

    public function getSzelesseg() {
        return $this->szelesseg;
    }

    public function setSzelesseg($szelesseg) {
        $this->szelesseg = $szelesseg;
    }

    public function getMagassag() {
        return $this->magassag;
    }

    public function setMagassag($magassag) {
        $this->magassag = $magassag;
    }

    public function getHosszusag() {
        return $this->hosszusag;
    }

    public function setHosszusag($hosszusag) {
        $this->hosszusag = $hosszusag;
    }

    public function getOsszehajthato() {
        return $this->osszehajthato;
    }

    public function setOsszehajthato($osszehajthato) {
        $this->osszehajthato = $osszehajthato;
    }

    public function getSuly() {
        return $this->suly;
    }

    public function setSuly($suly) {
        $this->suly = $suly;
    }

    public function getValtozatok() {
        switch (\mkw\Store::getTheme()) {
            case 'mkwcansas':
                return $this->valtozatok;
            case 'superzone':
                $s = \mkw\Store::getParameter(\mkw\consts::ValtozatSorrend);
                $rendezendo = \mkw\Store::getParameter(\mkw\consts::RendezendoValtozat);
                $sorrend = explode(',', $s);
                $a = $this->valtozatok->toArray();
                uasort($a, function($e, $f) use ($sorrend, $rendezendo) {
                    if ($e->getAdatTipus1Id() == $rendezendo) {
                        $ertek = $e->getErtek1();
                        $eszin = $e->getErtek2();
                    }
                    elseif ($e->getAdatTipus2Id() == $rendezendo) {
                        $ertek = $e->getErtek2();
                        $eszin = $e->getErtek1();
                    }
                    else {
                        $ertek = false;
                        $eszin = false;
                    }
                    $ve = array_search($ertek, $sorrend);
                    if ($ve === false) {
                        $ve = 0;
                    }
                    $ve = $eszin . str_pad((string)$ve, 6, '0', STR_PAD_LEFT);

                    if ($f->getAdatTipus1Id() == $rendezendo) {
                        $ertek = $f->getErtek1();
                        $fszin = $f->getErtek2();
                    }
                    elseif ($f->getAdatTipus2Id() == $rendezendo) {
                        $ertek = $f->getErtek2();
                        $fszin = $f->getErtek1();
                    }
                    else {
                        $ertek = false;
                        $fszin = false;
                    }
                    $vf = array_search($ertek, $sorrend);
                    if ($vf === false) {
                        $vf = 0;
                    }
                    $vf = $fszin . str_pad((string)$vf, 6, '0', STR_PAD_LEFT);

                    if ($ve === $vf) {
                        return 0;
                    }
                    return ($ve < $vf) ? -1 : 1;
                });
                return new \Doctrine\Common\Collections\ArrayCollection($a);
        }
    }

    public function addValtozat(TermekValtozat $valt) {
//		if (!$this->valtozatok->contains($valt)) {
        $this->valtozatok->add($valt);
        $valt->setTermek($this);
//		}
    }

    public function removeValtozat(TermekValtozat $valt) {
        if ($this->valtozatok->removeElement($valt)) {
            $valt->setTermek(null);
            return true;
        }
        return false;
    }

    public function getTermekReceptek() {
        return $this->termekreceptek;
    }

    public function addTermekRecept(TermekRecept $recept) {
//		if (!$this->termekreceptek->contains($recept)) {
        $this->termekreceptek->add($recept);
        $recept->setTermek($this);
//		}
    }

    public function removeTermekRecept(TermekRecept $recept) {
        if ($this->termekreceptek->removeElement($recept)) {
            $recept->removeTermek($this);
            return true;
        }
        return false;
    }

    public function getAlTermekReceptek() {
        return $this->altermekreceptek;
    }

    public function addAlTermekRecept(TermekRecept $recept) {
//		if (!$this->altermekreceptek->contains($recept)) {
        $this->altermekreceptek->add($recept);
        $recept->setAlTermek($this);
//		}
    }

    public function removeAlTermekRecept(TermekRecept $recept) {
        if ($this->altermekreceptek->removeElement($recept)) {
            $recept->removeAlTermek($this);
            return true;
        }
        return false;
    }

    public function getMegtekintesdb() {
        return $this->megtekintesdb;
    }

    public function setMegtekintesdb($adat) {
        $this->megtekintesdb = $adat;
    }

    public function incMegtekintesdb() {
        $this->megtekintesdb++;
    }

    public function getMegvasarlasdb() {
        return $this->megvasarlasdb;
    }

    public function setMegvasarlasdb($adat) {
        $this->megvasarlasdb = $adat;
    }

    public function incMegvasarlasdb() {
        $this->megvasarlasdb++;
    }

    public function getKiemelt() {
        return $this->kiemelt;
    }

    public function setKiemelt($adat) {
        $this->kiemelt = $adat;
    }

    public function getTermekKapcsolodok() {
        return $this->termekkapcsolodok;
    }

    public function addTermekKapcsolodo(TermekKapcsolodo $adat) {
//		if (!$this->termekreceptek->contains($adat)) {
        $this->termekkapcsolodok->add($adat);
        $adat->setTermek($this);
//		}
    }

    public function removeTermekKapcsolodo(TermekKapcsolodo $adat) {
        if ($this->termekkapcsolodok->removeElement($adat)) {
            $adat->removeTermek($this);
            return true;
        }
        return false;
    }

    public function getAlTermekKapcsolodok() {
        return $this->altermekkapcsolodok;
    }

    public function addAlTermekKapcsolodo(TermekKapcsolodo $adat) {
//		if (!$this->altermekkapcsolodok->contains($adat)) {
        $this->altermekkapcsolodok->add($adat);
        $adat->setAlTermek($this);
//		}
    }

    public function removeAlTermekKapcsolodo(TermekKapcsolodo $adat) {
        if ($this->altermekkapcsolodok->removeElement($adat)) {
            $adat->removeAlTermek($this);
            return true;
        }
        return false;
    }

    public function getLastmod() {
        return $this->lastmod;
    }

    public function getCreated() {
        return $this->created;
    }

    public function getValtozatadattipus() {
        return $this->valtozatadattipus;
    }

    public function getValtozatadattipusNev() {
        if ($this->valtozatadattipus) {
            if ($this->valtozatadattipus->getId() > 1) {
                return $this->valtozatadattipus->getNev();
            }
        }
        return '';
    }

    public function getValtozatadattipusId() {
        if ($this->valtozatadattipus) {
            return $this->valtozatadattipus->getId();
        }
        return 0;
    }

    public function setValtozatadattipus($a) {
        $this->valtozatadattipus = $a;
    }

    public function getNettoAr($valtozat = null, $partner = null, $valutanem = null) {
        // Nincsenek ársávok
        if (!\mkw\Store::isArsavok()) {
            $netto = $this->getNetto();
            if ($this->getAkcios()) {
                $netto = $this->getAkciosnetto();
            }
            if (!is_null($valtozat)) {
                return $netto + $valtozat->getNetto();
            }
            return $netto;
        }
        // Vannak ársávok
        else {
            $arsavazon = false;
            if ($partner) {
                $arsavazon = $partner->getTermekarazonosito();
                if (!$valutanem) {
                    $valutanem = $partner->getValutanem();
                }
            }
            $arsav = \mkw\Store::getEm()->getRepository('Entities\TermekAr')->getArsav($this, $valutanem, $arsavazon);
            if (!$arsav) {
                $arsav = \mkw\Store::getEm()->getRepository('Entities\TermekAr')->getArsav($this, $valutanem, \mkw\Store::getParameter(\mkw\consts::Arsav));
                if ($arsav) {
                    return $arsav->getNetto();
                }
                return 0;
            }
            return $arsav->getNetto();
        }
    }

    public function getArValutanem($valtozat = null, $partner = null, $valutanem = null) {
        if ($partner) {
            if (!$valutanem) {
                $valutanem = $partner->getValutanem();
            }
        }
        if (!$valutanem) {
            $valutanem = \mkw\Store::getEm()->getRepository('Entities\Valutanem')->find(\mkw\Store::getParameter(\mkw\consts::Valutanem));
        }
        return $valutanem;
    }

    public function getBruttoAr($valtozat = null, $partner = null, $valutanem = null) {
        // Nincsenek ársávok
        if (!\mkw\Store::isArsavok()) {
            $brutto = $this->getBrutto();
            if ($this->getAkcios()) {
                $brutto = $this->getAkciosbrutto();
            }
            if (!is_null($valtozat)) {
                return $brutto + $valtozat->getBrutto();
            }
            return $brutto;
        }
        // Vannak ársávok
        else {
            $arsavazon = false;
            if ($partner) {
                $arsavazon = $partner->getTermekarazonosito();
                if (!$valutanem) {
                    $valutanem = $partner->getValutanem();
                }
            }
            $arsav = \mkw\Store::getEm()->getRepository('Entities\TermekAr')->getArsav($this, $valutanem, $arsavazon);
            if (!$arsav) {
                $arsav = \mkw\Store::getEm()->getRepository('Entities\TermekAr')->getArsav($this, $valutanem, \mkw\Store::getParameter(\mkw\consts::Arsav));
                if ($arsav) {
                    return $arsav->getBrutto();
                }
                return 0;
            }
            return $arsav->getBrutto();
        }
    }

    public function getEredetiBruttoAr($valtozat) {
        // Nincsenek ársávok
        if (!\mkw\Store::isArsavok()) {
            $brutto = $this->getBrutto();
            if (!is_null($valtozat)) {
                return $brutto + $valtozat->getBrutto();
            }
            return $brutto;
        }
        // Vannak ársávok
        else {
            return 0;
        }
    }

    public function getNemkaphato() {
        return $this->nemkaphato;
    }

    public function setNemkaphato($val) {
        $this->nemkaphato = $val;
    }

    public function getGyarto() {
        return $this->gyarto;
    }

    public function getGyartoNev() {
        if ($this->gyarto) {
            return $this->gyarto->getNev();
        }
        return '';
    }

    public function getGyartoId() {
        if ($this->gyarto) {
            return $this->gyarto->getId();
        }
        return '';
    }

    public function setGyarto($gyarto) {
        $this->gyarto = $gyarto;
    }

    public function getFuggoben() {
        return $this->fuggoben;
    }

    public function setFuggoben($d) {
        $this->fuggoben = $d;
    }

    public function getSzallitasiido() {
        return $this->szallitasiido;
    }

    public function setSzallitasiido($adat) {
        $this->szallitasiido = $adat;
    }

    public function getRegikepurl() {
        return $this->regikepurl;
    }

    public function setRegikepurl($adat) {
        $this->regikepurl = $adat;
    }

    public function getTermekArak() {
        return $this->termekarak;
    }

    public function addTermekAr(TermekAr $adat) {
        $this->termekarak->add($adat);
        $adat->setTermek($this);
    }

    public function removeTermekAr(TermekAr $adat) {
        if ($this->termekarak->removeElement($adat)) {
            $adat->removeTermek($this);
            return true;
        }
        return false;
    }

    public function getTranslations() {
        return $this->translations;
    }

    public function addTranslation(TermekTranslation $t) {
        if (!$this->translations->contains($t)) {
            $this->translations[] = $t;
            $t->setObject($this);
        }
    }

    public function removeTranslation(TermekTranslation $t) {
        $this->translations->removeElement($t);
    }

    public function getLocale() {
        return $this->locale;
    }

    public function setLocale($locale) {
        $this->locale = $locale;
    }
}
