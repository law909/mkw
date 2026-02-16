<?php

namespace Entities;

use Automattic\WooCommerce\Client;
use Automattic\WooCommerce\HttpClient\HttpClientException;
use Doctrine\ORM\Query\ResultSetMapping;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use mkw\store;
use mkwhelpers\FilterDescriptor;

/**
 * @ORM\Entity(repositoryClass="Entities\TermekRepository")
 * @ORM\Table(name="termek",
 * options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"},
 * indexes={
 * 		@ORM\index(name="termekfakarkod_idx",columns={"termekfa1karkod","termekfa2karkod","termekfa3karkod"}),
 * 		@ORM\index(name="termekmenukarkod_idx",columns={"termekmenu1karkod"}),
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
class Termek
{

    public $dontUploadToWC = false;

    private static $translatedFields = [
        'nev' => ['caption' => 'Név', 'type' => 1],
        'leiras' => ['caption' => 'Leírás', 'type' => 2],
        'rovidleiras' => ['caption' => 'Rövid leírás', 'type' => 1],
        'oldalcim' => ['caption' => 'Oldalcím', 'type' => 1]
    ];

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

    /**
     * @ORM\Column(type="string",length=255,nullable=false)
     */
    private $nev2 = '';
    /**
     * @ORM\Column(type="string",length=255,nullable=false)
     */
    private $nev3 = '';
    /**
     * @ORM\Column(type="string",length=255,nullable=false)
     */
    private $nev4 = '';
    /**
     * @ORM\Column(type="string",length=255,nullable=false)
     */
    private $nev5 = '';

    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="string",length=255,nullable=false)
     */
    private $kiirtnev = '';

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
     * @ORM\ManyToOne(targetEntity="ME")
     * @ORM\JoinColumn(name="me_id",referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $mekod;

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

    /**
     * @ORM\ManyToMany(targetEntity="Blogposzt",inversedBy="termekek")
     * @ORM\OrderBy({"megjelenesdatum" = "DESC"})
     * @ORM\JoinTable(name="termek_blogposztok",
     *  joinColumns={@ORM\JoinColumn(name="termek_id",referencedColumnName="id",onDelete="cascade")},
     *  inverseJoinColumns={@ORM\JoinColumn(name="blogposzt_id",referencedColumnName="id",onDelete="cascade")}
     *  )
     */
    private $blogposztok;

    /** @ORM\Column(type="string",length=50,nullable=true) */
    private $cikkszam = '';

    /** @ORM\Column(type="string",length=50,nullable=true) */
    private $idegencikkszam = '';

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $vonalkod = '';

    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="text",nullable=true)
     */
    private $leiras = '';

    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    private $rovidleiras = '';

    /**
     * @Gedmo\Translatable
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
    private $lathato = 0;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $lathato2 = 0;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $lathato3 = 0;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $lathato4 = 0;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $lathato5 = 0;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $lathato6 = 0;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $lathato7 = 0;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $lathato8 = 0;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $lathato9 = 0;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $lathato10 = 0;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $lathato11 = 0;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $lathato12 = 0;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $lathato13 = 0;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $lathato14 = 0;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $lathato15 = 0;

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

    /**
     * @ORM\ManyToOne(targetEntity="TermekMenu",inversedBy="termekek1")
     * @ORM\JoinColumn(name="termekmenu1_id",referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $termekmenu1;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $termekmenu1karkod = '';

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

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $suruseg = 0;

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

    /** @ORM\OneToMany(targetEntity="Leltartetel", mappedBy="termek",cascade={"persist"}) */
    private $leltartetelek;

    /** @ORM\OneToMany(targetEntity="Kosar", mappedBy="termek",cascade={"persist"}) */
    private $kosarak;

    /** @ORM\Column(type="integer",nullable=true) */
    private $megtekintesdb;

    /** @ORM\Column(type="integer",nullable=true) */
    private $megvasarlasdb;

    /** @ORM\Column(type="integer",nullable=true) */
    private $nepszeruseg;

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
     * @var Partner
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

    /**
     * @ORM\ManyToOne(targetEntity="Termekcsoport")
     * @ORM\JoinColumn(name="termekcsoport_id",referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $termekcsoport;

    /** @Gedmo\Locale */
    protected $locale;

    /** @ORM\Column(type="boolean") */
    private $kozvetitett = 0;

    /** @ORM\Column(type="integer", nullable=true) */
    private $migrid;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $egyprodukcios = 1;

    /** @ORM\Column(type="integer", nullable=true) */
    private $valutameszorzo;

    /** @ORM\Column(type="integer",nullable=true) */
    private $jogaalkalom;

    /** @ORM\Column(type="integer",nullable=true) */
    private $jogaervenyesseg;

    /** @ORM\Column(type="decimal",precision=14,scale=2,nullable=true) */
    private $jogaelszamolasalap;

    /** @ORM\Column(type="integer",nullable=true) */
    private $jogaervenyessegnap;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $eladhato = 1;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $emagtiltva = 0;

    /** @ORM\OneToMany(targetEntity="TermekDok", mappedBy="termek", cascade={"persist", "remove"}) */
    private $termekdokok;

    /** @ORM\Column(type="decimal",precision=14,scale=2,nullable=true) */
    private $minboltikeszlet;

    /** @ORM\Column(type="integer",nullable=true) */
    private $garancia;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $arukeresofanev;

    /**
     * @ORM\OneToMany(targetEntity="TermekErtekeles", mappedBy="termek",cascade={"persist"})
     * @ORM\OrderBy({"created" = "DESC"})
     */
    private $termekertekelesek;
    /** @ORM\Column(type="integer", nullable=true) */
    private $wcid;
    /** @ORM\Column(type="datetime", nullable=true) */
    private $wcdate;
    /** @ORM\Column(type="integer", nullable=true) */
    private $kepwcid;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $wctiltva = 0;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $feltoltheto = 0;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $feltoltheto2 = 0;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $feltoltheto3 = 0;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $feltoltheto4 = 0;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $feltoltheto5 = 0;

    /** @ORM\Column(type="integer", nullable=true) */
    private $prestaid;

    /** @ORM\Column(type="datetime", nullable=true) */
    private $prestadate;

    /** @ORM\Column(type="boolean", nullable=false) */
    private $prestatiltva = 0;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $uj = 0;

    public function __toString()
    {
        return (string)$this->id . ' - ' . $this->nev;
    }

    public static function getTranslatedFields()
    {
        return self::$translatedFields;
    }

    public static function getTranslatedFieldsSelectList($sel = null)
    {
        $ret = [];
        foreach (self::$translatedFields as $k => $v) {
            $ret[] = [
                'id' => $k,
                'caption' => $v['caption'],
                'selected' => ($k === $sel)
            ];
        }
        return $ret;
    }

    /**
     * @ORM\PrePersist
     */
    public function generateVonalkod()
    {
        if (\mkw\store::getSetupValue('vonalkodgeneration') && !$this->vonalkod) {
            $conn = \mkw\store::getEm()->getConnection();
            $stmt = $conn->prepare('INSERT INTO vonalkodseq (data) VALUES (1)');
            $stmt->executeStatement();
            $this->setVonalkod(\mkw\store::generateEAN13((string)$conn->lastInsertId()));
        }
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function doStuffOnPrePersist()
    {
        $res = [];
        foreach ($this->cimkek as $cimke) {
            $res[] = $cimke->getNev();
        }
        $this->cimkenevek = implode('; ', $res);
    }

    public function __construct()
    {
        $this->cimkek = new \Doctrine\Common\Collections\ArrayCollection();
        $this->termekkepek = new \Doctrine\Common\Collections\ArrayCollection();
        $this->valtozatok = new \Doctrine\Common\Collections\ArrayCollection();
        $this->termekreceptek = new \Doctrine\Common\Collections\ArrayCollection();
        $this->altermekreceptek = new \Doctrine\Common\Collections\ArrayCollection();
        $this->bizonylattetelek = new \Doctrine\Common\Collections\ArrayCollection();
        $this->leltartetelek = new \Doctrine\Common\Collections\ArrayCollection();
        $this->kosarak = new \Doctrine\Common\Collections\ArrayCollection();
        $this->termekkapcsolodok = new \Doctrine\Common\Collections\ArrayCollection();
        $this->altermekkapcsolodok = new \Doctrine\Common\Collections\ArrayCollection();
        $this->termekertesitok = new \Doctrine\Common\Collections\ArrayCollection();
        $this->termekarak = new \Doctrine\Common\Collections\ArrayCollection();
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->termekdokok = new \Doctrine\Common\Collections\ArrayCollection();
        $this->blogposztok = new \Doctrine\Common\Collections\ArrayCollection();
        $this->termekertekelesek = new \Doctrine\Common\Collections\ArrayCollection();

        $this->dontUploadToWC = false;
    }

    public function getUjTermek($min)
    {
        return $this->id >= $min;
    }

    public function getTop10($top10min)
    {
        return $this->megvasarlasdb >= $top10min;
    }

    public function getKeszlet($datum = null, $raktarid = null, $nonegativ = false)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('mennyiseg', 'mennyiseg');
        $rsm->addScalarResult('mozgasdb', 'mozgasdb');

        if (!$datum) {
            $datum = new \DateTime();
        }

        $filter = new FilterDescriptor();
        $filter->addFilter('bt.termek_id', '=', $this->getId());
        $filter->addFilter('bt.mozgat', '=', 1);
        $filter->addSql('((bt.rontott = 0) OR (bt.rontott IS NULL))');
        if ($datum) {
            $filter->addFilter('bf.teljesites', '<=', $datum);
        }
        if ($raktarid) {
            $filter->addFilter('bf.raktar_id', '=', $raktarid);
        }

        $q = \mkw\store::getEm()->createNativeQuery(
            'SELECT SUM(bt.mennyiseg * bt.irany) AS mennyiseg, COUNT(*) AS mozgasdb'
            . ' FROM bizonylattetel bt'
            . ' LEFT OUTER JOIN bizonylatfej bf ON (bt.bizonylatfej_id=bf.id)'
            . $filter->getFilterString()
            ,
            $rsm
        );

        $q->setParameters($filter->getQueryParameters());
        $d = $q->getScalarResult();

        $k = $d[0]['mennyiseg'];
        if (is_null($k)) {
            $k = 0;
        }
        if ($nonegativ) {
            if ($k < 0) {
                $k = 0;
            }
        }
        $db = $d[0]['mozgasdb'];
        if (is_null($db)) {
            $db = 0;
        }

        /*if (!$datum) {
            $datum = new \DateTime();
        }
        $k = 0;
        /** @var \Entities\Bizonylattetel $bt */
        /*
        foreach($this->bizonylattetelek as $bt) {
            if ($bt->getMozgat() && (!$bt->getRontott()) && ($bt->getTeljesites() <= $datum) && (!$raktarid || ($raktarid && $raktarid == $bt->getRaktarId()))) {
                $k += ($bt->getMennyiseg() * $bt->getIrany());
            }
        }
        */
        return $k;
    }

    public function getFoglaltMennyiseg($kivevebiz = null, $datum = null, $raktarid = null)
    {
        if (\mkw\store::isFoglalas()) {
            $rsm = new ResultSetMapping();
            $rsm->addScalarResult('mennyiseg', 'mennyiseg');
            $rsm->addScalarResult('mozgasdb', 'mozgasdb');

            if (!$datum) {
                $datum = new \DateTime();
            }

            $filter = new FilterDescriptor();
            $filter->addFilter('bt.termek_id', '=', $this->getId());
            $filter->addFilter('bt.foglal', '=', 1);
            $filter->addSql('((bt.rontott = 0) OR (bt.rontott IS NULL))');
            if ($datum) {
                $filter->addFilter('bf.teljesites', '<=', $datum);
            }
            $filter->addFilter('bf.bizonylattipus_id', '=', 'megrendeles');
            if ($kivevebiz) {
                $filter->addFilter('bf.id', '<>', $kivevebiz);
            }
            if ($raktarid) {
                $filter->addFilter('bf.raktar_id', '=', $raktarid);
            }

            $q = \mkw\store::getEm()->createNativeQuery(
                'SELECT SUM(bt.mennyiseg * bt.irany) AS mennyiseg, COUNT(*) AS mozgasdb'
                . ' FROM bizonylattetel bt'
                . ' LEFT OUTER JOIN bizonylatfej bf ON (bt.bizonylatfej_id=bf.id)'
                . $filter->getFilterString()
                ,
                $rsm
            );

            $q->setParameters($filter->getQueryParameters());
            $d = $q->getScalarResult();

            $k = $d[0]['mennyiseg'];
            if (is_null($k)) {
                $k = 0;
            }
            $k = $k * -1;
            $db = $d[0]['mozgasdb'];
            if (is_null($db)) {
                $db = 0;
            }

            return $k;

            /*
            if (is_a($kivevebiz, 'Bizonylatfej')) {
                $kivevebiz = $kivevebiz->getId();
            }
            $k = 0;
            /** @var \Entities\Bizonylattetel $bt */
            /*
            foreach($this->bizonylattetelek as $bt) {
                $bf = $bt->getBizonylatfej();
                if ($bf->getBizonylattipusId() === 'megrendeles') {
                    $nemkivetel = true;
                    if ($kivevebiz) {
                        $nemkivetel = $bt->getBizonylatfejId() != $kivevebiz;
                    }
                    if ($bt->getFoglal() && !$bt->getRontott() && ($nemkivetel)) {
                        $k += ($bt->getMennyiseg() * $bt->getIrany());
                    }
                }
            }
            return -1 * $k;
            */
        }
        return 0;
    }


    public function toEmag()
    {
        $x = [];
        $x['id'] = $this->getId();
        $x['category_id'] = $this->getTermekfa1()->getEmagid();
        // $x['part_number_key'] = ??
        $x['name'] = $this->getNev();
        $x['part_number'] = 'MKWT' . $this->getId();
        $x['description'] = $this->getLeiras();

        /** @var Termekcimketorzs $marka */
        $marka = $this->getCimkeByCategory(\mkw\store::getParameter(\mkw\consts::MarkaCs));
        if ($marka) {
            $x['brand'] = $marka->getNev();
        } else {
            $x['brand'] = 'Noname';
        }

        $images = [];
        $disptype = 1;
        if ($this->getKepurl()) {
            $images[] = [
                'display_type' => $disptype,
                'url' => \mkw\store::getFullUrl($this->getKepurl())
            ];
            $disptype = 2;
        }
        /** @var TermekKep $kep */
        foreach ($this->getTermekKepek(true) as $kep) {
            $images[] = [
                'display_type' => $disptype,
                'url' => \mkw\store::getFullUrl($kep->getUrl())
            ];
            if ($disptype == 1) {
                $disptype = 2;
            } else {
                $disptype = 0;
            }
        }
        $x['images'] = $images;

        $charac = [];
        /** @var Termekcimketorzs $item */
        foreach ($this->getCimkek() as $item) {
            $kat = $item->getKategoria();
            if ($kat->getEmagid()) {
                $charac[] = [
                    'id' => $kat->getEmagid(),
                    'value' => $item->getNev()
                ];
            }
        }
        $x['characteristics'] = $charac;
        if ($this->getGarancia()) {
            $x['warranty'] = $this->getGarancia();
        } else {
            $x['warranty'] = 12;
        }
        $x['ean'] = $this->getVonalkod();
        if ($this->getInaktiv()) {
            $x['status'] = 0;
        } else {
            $x['status'] = 1;
        }
        $x['sale_price'] = $this->getNettoAr() * 110 / 100;
        $x['min_sale_price'] = $x['sale_price'];
        $x['max_sale_price'] = $x['min_sale_price'] * 120 / 100;
        $x['stock'] = [
            [
                'warehouse_id' => 1,
                'value' => $this->getKeszlet()
            ]
        ];
        $x['handling_time'] = [
            [
                'warehouse_id' => 1,
                'value' => 1
            ]
        ];
        $x['supply_lead_time'] = 7;
        $x['vat_id'] = $this->getAfa()->getEmagid();

        return $x;
    }

    public function toA2a($partner = null)
    {
        $x = [];
        $ford = $this->getTranslationsArray();
        $x['id'] = $this->getId();
        $x['image_url'] = \mkw\store::getFullUrl($this->getKepurlLarge());
        //$x['link'] = \mkw\store::getRouter()->generate('showtermek', true, ['slug' => $this->getSlug()]);
        $x['name'] = $this->getNev();
        $x['name_en'] = $ford['en_us']['nev'];
        $x['sku'] = $this->getCikkszam();
        $x['short_description'] = $this->getRovidLeiras();
        $x['description'] = $this->getLeiras();
        $x['description_en'] = $ford['en_us']['leiras'] ?: '';
        $x['category_id'] = $this->getTermekfa1Id();
        $x['category_name'] = $this->getTermekfa1()->getTeljesNev();
        $vtt = [];
        $valtozatok = $this->getValtozatok();
        if ($valtozatok) {
            /** @var \Entities\TermekValtozat $valt */
            foreach ($valtozatok as $valt) {
                if ($valt->getLathato() && $valt->getElerheto()) {
                    $valtadat = [];
                    $valtadat['id'] = $valt->getId();
                    $valtadat['EAN'] = $valt->getVonalkod();
                    $keszlet = max($valt->getKeszlet() - $valt->getFoglaltMennyiseg() - $valt->calcMinboltikeszlet(), 0);
                    $valtadat['stock'] = $keszlet;
                    $valtadat['retail_price'] = $this->getKedvezmenynelkuliNettoAr($valt, $partner);
                    $valtadat['discount_price'] = $this->getNettoAr($valt, $partner);
                    if ($valt->getAdatTipus1Id() == \mkw\store::getParameter(\mkw\consts::ValtozatTipusSzin)) {
                        $valtadat['color'] = $valt->getErtek1();
                        $valtadat['size'] = $valt->getErtek2();
                    } elseif ($valt->getAdatTipus2Id() == \mkw\store::getParameter(\mkw\consts::ValtozatTipusSzin)) {
                        $valtadat['color'] = $valt->getErtek2();
                        $valtadat['size'] = $valt->getErtek1();
                    }
                    $vtt[] = $valtadat;
                }
            }
            $x['variations'] = $vtt;
        }
        $altomb = [];
        foreach ($this->getTermekKepek(true) as $kep) {
            $egyed = [];
            $egyed['img_url'] = \mkw\store::getFullUrl($kep->getUrlLarge());
            $egyed['medium_img_url'] = \mkw\store::getFullUrl($kep->getUrlMedium());
            $egyed['small_img_url'] = \mkw\store::getFullUrl($kep->getUrlSmall());
            $egyed['mini_img_url'] = \mkw\store::getFullUrl($kep->getUrlMini());
            $egyed['caption'] = $kep->getLeiras();
            $altomb[] = $egyed;
        }
        $x['images'] = $altomb;
        return $x;
    }

    /**
     * @param TermekValtozat|null $valtozat
     * @param null $ujtermekid
     * @param null $top10min
     *
     * @return array
     * @throws \Exception
     */
    public function toTermekLista($valtozat = null, $ujtermekid = null, $top10min = null)
    {
        $x = [];
        $x['ujtermek'] = $this->getUjTermek($ujtermekid);
        $x['top10'] = $this->getTop10($top10min);
        $x['id'] = $this->getId();
        $x['nagykepurl'] = $this->getKepurlLarge();
        $x['kozepeskepurl'] = $this->getKepurlMedium();
        $x['kiskepurl'] = $this->getKepurlSmall();
        $x['minikepurl'] = $this->getKepurlMini();
        $x['kepurl400'] = $this->getKepurl400();
        $x['kepurl2000'] = $this->getKepurl2000();
        $x['kepurl'] = $this->getKepurlLarge();
        $x['slug'] = $this->getSlug();
        $x['link'] = \mkw\store::getRouter()->generate('showtermek', false, ['slug' => $this->getSlug()]);
        $x['caption'] = $this->getNev();
        $x['cikkszam'] = $this->getCikkszam();
        $x['rovidleiras'] = $this->getRovidLeiras();
        $x['akcios'] = $this->getAkcios();
        $x['akciotipus'] = $this->getAkcioTipus();
        $x['akciostart'] = $this->getAkciostartStr();
        $x['akciostop'] = $this->getAkciostopStr();
        $x['minboltikeszlet'] = $this->getMinboltikeszlet();
        $ert = $this->getErtekelesAtlag();
        $x['ertekelesatlag'] = $ert['ertekelesatlag'];
        $x['ertekelesdb'] = $ert['ertekelesdb'];
        $x['kiemelt'] = $this->getKiemelt();
        $x['ajanlott'] = $this->getAjanlott();
        $x['uj'] = $this->getUj();
        switch (true) {
            case \mkw\store::isMugenrace2026():
            case \mkw\store::isMugenrace():
                $x['valutanemnev'] = \mkw\store::getMainValutanemNev();
                $x['brutto'] = $this->calcSalePrice(
                    \mkw\store::getMainValutanemId(),
                    $valtozat
                );
                $x['bruttohuf'] = $x['brutto'];
                $x['eredetibrutto'] = $this->calcRegularPrice(\mkw\store::getMainValutanemId(), $valtozat);
                $x['eredetibruttohuf'] = $x['eredetibrutto'];
                $x['akcios'] = (boolean)$x['eredetibrutto'];
                break;
            case \mkw\store::isMugenrace2021():
                $x['valutanemnev'] = \mkw\store::getMainValutanemNev();
                $x['brutto'] = $this->getBruttoAr(
                    $valtozat,
                    \mkw\store::getLoggedInUser(),
                    \mkw\store::getMainValutanemId(),
                    \mkw\store::getParameter(\mkw\consts::Webshop4Price)
                );
                $x['bruttohuf'] = $x['brutto'];
                $x['eredetibrutto'] = $this->getEredetiBruttoAr($valtozat);
                $x['eredetibruttohuf'] = $x['eredetibrutto'];
                break;
            default:
                $x['bruttohuf'] = $this->getBruttoAr($valtozat, \mkw\store::getLoggedInUser());
                $x['eredetibruttohuf'] = $this->getEredetiBruttoAr($valtozat);
                break;
        }
        $x['nemkaphato'] = $this->getNemkaphato() || $this->getFuggoben();
        $x['ingyenszallitas'] = (\mkw\store::calcSzallitasiKoltseg($x['bruttohuf']) == 0);

        $x['szallitasiido'] = $this->calcSzallitasiido($valtozat);
        $x['minszallitasiido'] = intdiv($x['szallitasiido'], 2);

        $listaban = [];
        foreach ($this->getCimkek() as $cimke) {
            $kat = $cimke->getKategoria();
            if ($kat->getTermeklistabanlathato()) {
                $listaban[] = $cimke->toLista();
            }
            if ($kat->getId() == \mkw\store::getParameter(\mkw\consts::MarkaCs, 0)) {
                $x['marka'] = $cimke->getNev();
            }
        }
        $x['cimkelistaban'] = $listaban;
        if (!is_null($valtozat)) {
            if ($valtozat->getKepurlSmall()) {
                $x['nagykepurl'] = $valtozat->getKepurlLarge();
                $x['kozepeskepurl'] = $valtozat->getKepurlMedium();
                $x['kiskepurl'] = $valtozat->getKepurlSmall();
                $x['kepurl400'] = $valtozat->getKepurl400();
                $x['kepurl2000'] = $valtozat->getKepurl2000();
                $x['minikepurl'] = $valtozat->getKepurlMini();
                $x['kepurl'] = $valtozat->getKepurlLarge();
            }
            $x['valtozatid'] = $valtozat->getId();
            if ($this->getValtozatadattipusId() == $valtozat->getAdatTipus1Id()) {
                $ertek = $valtozat->getErtek1();
                $x['valtozatok']['fixname'] = $valtozat->getAdatTipus1Nev();
                $x['valtozatok']['fixvalue'] = $ertek;
                $x['valtozatok']['name'] = $valtozat->getAdatTipus2Nev();
            } elseif ($this->getValtozatadattipusId() == $valtozat->getAdatTipus2Id()) {
                $ertek = $valtozat->getErtek2();
                $x['valtozatok']['fixname'] = $valtozat->getAdatTipus2Nev();
                $x['valtozatok']['fixvalue'] = $ertek;
                $x['valtozatok']['name'] = $valtozat->getAdatTipus1Nev();
            }
            $adatt = [];
            $valtozatok = $this->getValtozatok();
            foreach ($valtozatok as $valt) {
                if ($valt->getXElerheto()) {
                    if ($this->getValtozatadattipusId() == $valt->getAdatTipus1Id() && $valt->getErtek1() == $ertek &&
                        $valt->getAdatTipus2Id()) {
                        $adatt[] = ['id' => $valt->getId(), 'value' => $valt->getErtek2(), 'selected' => $valt->getId() == $valtozat->getId()];
                    } elseif ($this->getValtozatadattipusId() == $valt->getAdatTipus2Id() && $valt->getErtek2() == $ertek &&
                        $valt->getAdatTipus1Id()) {
                        $adatt[] = ['id' => $valt->getId(), 'value' => $valt->getErtek1(), 'selected' => $valt->getId() == $valtozat->getId()];
                    }
                }
            }
            $x['valtozatok']['data'] = $adatt;
        } else {
            if (\mkw\store::isMugenrace() || \mkw\store::isMugenrace2026()) {
                $vtt = [];
                $valtozatok = $this->getValtozatok();
                foreach ($valtozatok as $valt) {
                    if ($valt->getXElerheto()) {
                        if ($valt->getAdatTipus1Id() == \mkw\store::getParameter(\mkw\consts::ValtozatTipusSzin)) {
                            $vtt[$valt->getErtek1()] = $valt->getErtek1();
                        } elseif ($valt->getAdatTipus2Id() == \mkw\store::getParameter(\mkw\consts::ValtozatTipusSzin)) {
                            $vtt[$valt->getErtek2()] = $valt->getErtek2();
                        }
                    }
                }
                \mkw\store::writelog(print_r($vtt, true));
                $x['szinek'] = $vtt;
            } else {
                $vtt = [];
                $valtozatok = $this->getValtozatok();
                $db = 0;
                foreach ($valtozatok as $valt) {
                    if ($valt->getXElerheto()) {
                        $db++;
                    }
                }
                foreach ($valtozatok as $valt) {
                    if ($valt->getXElerheto()) {
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
                        if ($db === 1) {
                            if ($valt->getKeszlet() > 0) {
                                $x['szallitasiido'] = 1;
                            }
                        }
                    }
                }
                $x['mindenvaltozat'] = $vtt;
            }
        }
        return $x;
    }

    public function toKiemeltLista($valtozat = null)
    {
        $x = [];
        $x['id'] = $this->getId();
        $x['kozepeskepurl'] = $this->getKepurlMedium();
        $x['kiskepurl'] = $this->getKepurlSmall();
        $x['minikepurl'] = $this->getKepurlMini();
        $x['kepurl400'] = $this->getKepurl400();
        $x['kepurl2000'] = $this->getKepurl2000();
        $x['kepurl'] = $this->getKepurlLarge();
        $x['slug'] = $this->getSlug();
        $x['caption'] = $this->getNev();
        $x['cikkszam'] = $this->getCikkszam();
        $x['link'] = \mkw\store::getRouter()->generate('showtermek', false, ['slug' => $this->getSlug()]);
        $x['rovidleiras'] = $this->getRovidLeiras();
        $x['akcios'] = $this->getAkcios();
        $x['akciotipus'] = $this->getAkcioTipus();
        $x['akciostart'] = $this->getAkciostartStr();
        $x['akciostop'] = $this->getAkciostopStr();
        $x['minboltikeszlet'] = $this->getMinboltikeszlet();
        if (\mkw\store::isMugenrace() || \mkw\store::isMugenrace2026()) {
            $x['valutanemnev'] = \mkw\store::getMainSession()->valutanemnev;
            $x['brutto'] = $this->calcSalePrice(
                \mkw\store::getMainSession()->valutanem,
                $valtozat
            );
            $x['bruttohuf'] = $x['brutto'];
            $x['eredetibrutto'] = $this->calcRegularPrice(\mkw\store::getMainSession()->valutanem, $valtozat);
            $x['eredetibruttohuf'] = $x['eredetibrutto'];
            $x['akcios'] = (boolean)$x['eredetibrutto'];
        } else {
            $x['bruttohuf'] = $this->getBruttoAr($valtozat, \mkw\store::getLoggedInUser());
            $x['eredetibruttohuf'] = $this->getEredetiBruttoAr($valtozat);
        }
        $x['nemkaphato'] = $this->getNemkaphato() || $this->getFuggoben();

        $listaban = [];
        foreach ($this->getCimkek() as $cimke) {
            $kat = $cimke->getKategoria();
            if ($kat->getTermeklistabanlathato()) {
                $listaban[] = $cimke->toLista();
            }
        }
        $x['cimkelistaban'] = $listaban;

        return $x;
    }

    public function toTermekLap($valtozat = null, $ujtermekid = null, $top10min = null)
    {
        $x = [];

        $x['ujtermek'] = $this->getUjTermek($ujtermekid);
        $x['top10'] = $this->getTop10($top10min);
        $x['id'] = $this->getId();
        $x['caption'] = $this->getNev();
        $x['slug'] = $this->getSlug();
        $x['eredetikepurl'] = $this->getKepurl();
        $x['kepurl'] = $this->getKepurlLarge();
        $x['fullkepurl'] = \mkw\store::getFullUrl($this->getKepurlLarge());
        $x['kozepeskepurl'] = $this->getKepUrlMedium();
        $x['minikepurl'] = $this->getKepurlMini();
        $x['kepurl400'] = $this->getKepurl400();
        $x['kepurl2000'] = $this->getKepurl2000();
        $x['rovidleiras'] = $this->getRovidleiras();
        $x['leiras'] = $this->getLeiras();
        $x['cikkszam'] = $this->getCikkszam();
        $x['me'] = $this->getMe();
        $x['hozzaszolas'] = $this->getHozzaszolas();
        $x['akcios'] = $this->getAkcios();
        $x['akciotipus'] = $this->getAkcioTipus();
        $x['akciostart'] = $this->getAkciostartStr();
        $x['akciostop'] = $this->getAkciostopStr();
        $x['minboltikeszlet'] = $this->getMinboltikeszlet();
        $ert = $this->getErtekelesAtlag();
        $x['ertekelesatlag'] = $ert['ertekelesatlag'];
        $x['ertekelesdb'] = $ert['ertekelesdb'];
        $x['kiemelt'] = $this->getKiemelt();
        $x['ajanlott'] = $this->getAjanlott();
        $x['uj'] = $this->getUj();
        $erts = [];
        /** @var TermekErtekeles $ertekeles */
        foreach ($this->getTermekErtekelesek() as $ertekeles) {
            if (!$ertekeles->isElutasitva()) {
                $erts[] = $ertekeles->toLista();
            }
        }
        $x['ertekelesek'] = $erts;
        switch (true) {
            case \mkw\store::isMugenrace2026():
            case \mkw\store::isMugenrace():
                $x['valutanemnev'] = \mkw\store::getMainValutanemNev();
                $x['brutto'] = $this->calcSalePrice(
                    \mkw\store::getMainValutanemId(),
                    $valtozat
                );
                $x['bruttohuf'] = $x['brutto'];
                $x['eredetibrutto'] = $this->calcRegularPrice(
                    \mkw\store::getMainValutanemId(),
                    $valtozat
                );
                $x['eredetibruttohuf'] = $x['eredetibrutto'];
                $x['akcios'] = (boolean)$x['eredetibrutto'];
                break;
            case \mkw\store::isMugenrace2021():
                $x['valutanemnev'] = \mkw\store::getMainValutanemNev();
                $x['brutto'] = $this->getBruttoAr(
                    $valtozat,
                    \mkw\store::getLoggedInUser(),
                    \mkw\store::getMainValutanemId(),
                    \mkw\store::getParameter(\mkw\consts::Webshop4Price)
                );
                $x['bruttohuf'] = $x['brutto'];
                $x['eredetibrutto'] = $this->getEredetiBruttoAr($valtozat);
                $x['eredetibruttohuf'] = $x['eredetibrutto'];
                break;
            default:
                $x['bruttohuf'] = $this->getBruttoAr($valtozat, \mkw\store::getLoggedInUser());
                $x['eredetibruttohuf'] = $this->getEredetiBruttoAr($valtozat);
                break;
        }
        $x['nemkaphato'] = $this->getNemkaphato() || $this->getFuggoben();
        $x['ingyenszallitas'] = (\mkw\store::calcSzallitasiKoltseg($x['bruttohuf']) == 0);
        $x['husegpont'] = floor($x['bruttohuf'] * $this->getHparany() / 100);

        $x['szallitasiido'] = $this->calcSzallitasiido($valtozat);
        $x['minszallitasiido'] = intdiv($x['szallitasiido'], 2);

        $altomb = [];
        foreach ($this->getTermekKepek(true) as $kep) {
            $egyed = [];
            $egyed['eredetikepurl'] = $kep->getUrl();
            $egyed['kepurl'] = $kep->getUrlLarge();
            $egyed['kozepeskepurl'] = $kep->getUrlMedium();
            $egyed['kiskepurl'] = $kep->getUrlSmall();
            $egyed['minikepurl'] = $kep->getUrlMini();
            $egyed['kepurl400'] = $kep->getUrl400();
            $egyed['kepurl2000'] = $kep->getUrl2000();
            $egyed['leiras'] = $kep->getLeiras();
            $altomb[] = $egyed;
        }
        $x['kepek'] = $altomb;

        $altomb = [];
        foreach ($this->getTermekKapcsolodok() as $kapcsolodo) {
            $altomb[] = $kapcsolodo->getAlTermek()->toKapcsolodo();
        }
        $x['kapcsolodok'] = $altomb;

        $lapon = [];
        $akciodobozban = [];
        foreach ($this->getCimkek() as $cimke) {
            $kat = $cimke->getKategoria();
            if ($kat->getTermeklaponlathato()) {
                $lapon[] = $cimke->toLista();
            }
            if ($kat->getTermekakciodobozbanlathato()) {
                $akciodobozban[] = $cimke->toLista();
            }
            if ($kat->getId() == \mkw\store::getParameter(\mkw\consts::MarkaCs, 0)) {
                $x['marka'] = $cimke->getNev();
            }
        }
        $x['cimkelapon'] = $lapon;
        $x['cimkeakciodobozban'] = $akciodobozban;

        $vtt = [];
        $valtozatok = $this->getValtozatok();
        foreach ($valtozatok as $valt) {
            if ($valt->getXElerheto()) {
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

        if (\mkw\store::isMugenrace() || \mkw\store::isMugenrace2026()) {
            $vtt = [];
            $valtozatok = $this->getValtozatok();
            foreach ($valtozatok as $valt) {
                if ($valt->getXElerheto()) {
                    if ($valt->getAdatTipus1Id() == \mkw\store::getParameter(\mkw\consts::ValtozatTipusSzin)) {
                        $vtt[$valt->getErtek1()] = $valt->getErtek1();
                    } elseif ($valt->getAdatTipus2Id() == \mkw\store::getParameter(\mkw\consts::ValtozatTipusSzin)) {
                        $vtt[$valt->getErtek2()] = $valt->getErtek2();
                    }
                }
            }
            $x['szinek'] = $vtt;
        }

        $hasontomb = [];
        $r = \mkw\store::getEm()->getRepository('Entities\Termek');
        $hason = $r->getHasonloTermekek(
            $this,
            \mkw\store::getParameter(\mkw\consts::Hasonlotermekdb, 3),
            \mkw\store::getParameter(\mkw\consts::Hasonlotermekarkulonbseg, 10)
        );
        foreach ($hason as $has) {
            $hasontomb[] = $has->toKapcsolodo();
        }
        $x['hasonlotermekek'] = $hasontomb;

        $bpt = [];
        $blogposztok = $this->getBlogposztok();
        /** @var \Entities\Blogposzt $poszt */
        foreach ($blogposztok as $poszt) {
            $bpt[] = $poszt->convertToArray();
        }
        $x['blogposztok'] = $bpt;

        return $x;
    }

    public function toKapcsolodo($valtozat = null)
    {
        $x = [];
        $x['id'] = $this->getId();
        $x['kozepeskepurl'] = $this->getKepUrlMedium();
        $x['kiskepurl'] = $this->getKepUrlSmall();
        $x['minikepurl'] = $this->getKepurlMini();
        $x['kepurl400'] = $this->getKepurl400();
        $x['kepurl2000'] = $this->getKepurl2000();
        $x['kepurl'] = $this->getKepUrlLarge();
        $x['slug'] = $this->getSlug();
        $x['caption'] = $this->getNev();
        $x['cikkszam'] = $this->getCikkszam();
        $x['rovidleiras'] = $this->getRovidLeiras();
        $x['akcios'] = $this->getAkcios();
        $ert = $this->getErtekelesAtlag();
        $x['ertekelesatlag'] = $ert['ertekelesatlag'];
        $x['ertekelesdb'] = $ert['ertekelesdb'];
        if (\mkw\store::isMugenrace() || \mkw\store::isMugenrace2026()) {
            $x['valutanemnev'] = \mkw\store::getMainSession()->valutanemnev;
            $x['brutto'] = $this->calcSalePrice(
                \mkw\store::getMainSession()->valutanem,
                $valtozat
            );
            $x['bruttohuf'] = $x['brutto'];
            $x['eredetibrutto'] = $this->calcRegularPrice(
                \mkw\store::getMainSession()->valutanem,
                $valtozat
            );
            $x['eredetibruttohuf'] = $x['eredetibrutto'];
            $x['akcios'] = (boolean)$x['eredetibrutto'];
        } else {
            $x['bruttohuf'] = $this->getBruttoAr($valtozat, \mkw\store::getLoggedInUser());
            $x['eredetibruttohuf'] = $this->getEredetiBruttoAr($valtozat);
        }
        $x['link'] = \mkw\store::getRouter()->generate('showtermek', false, ['slug' => $this->getSlug()]);
        return $x;
    }

    public function toKosar($valtozat)
    {
        $x = [];
        $x['id'] = $this->getId();
        $x['termekid'] = $this->getId();
        $x['kozepeskepurl'] = $this->getKepUrlMedium();
        $x['kiskepurl'] = $this->getKepUrlSmall();
        $x['minikepurl'] = $this->getKepurlMini();
        $x['kepurl400'] = $this->getKepurl400();
        $x['kepurl2000'] = $this->getKepurl2000();
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
        $x['link'] = \mkw\store::getRouter()->generate('showtermek', false, ['slug' => $this->getSlug()]);
        return $x;
    }

    public function toMenu()
    {
        $x = [];
        $x['id'] = $this->getId();
        $x['kozepeskepurl'] = $this->getKepurlMedium();
        $x['kiskepurl'] = $this->getKepurlSmall();
        $x['minikepurl'] = $this->getKepurlMini();
        $x['kepurl400'] = $this->getKepurl400();
        $x['kepurl2000'] = $this->getKepurl2000();
        $x['kepurl'] = $this->getKepurlLarge();
        $x['slug'] = $this->getSlug();
        $x['link'] = \mkw\store::getRouter()->generate('showtermek', false, ['slug' => $this->getSlug()]);
        $x['caption'] = $this->getNev();
        $x['cikkszam'] = $this->getCikkszam();
        $x['rovidleiras'] = $this->getRovidLeiras();
        $x['nemkaphato'] = $this->getNemkaphato() || $this->getFuggoben();
        $x['szallitasiido'] = $this->calcSzallitasiido();
        $x['minszallitasiido'] = intdiv($x['szallitasiido'], 2);
        return $x;
    }

    public function toRiport($valtozat)
    {
        $x = [];
        $x['id'] = $this->getId();
        $x['nev'] = $this->getNev();
        $x['cikkszam'] = $this->getCikkszam();
        if ($valtozat) {
            $x['valtozatid'] = $valtozat->getId();
            $x['valtozatnev'] = $valtozat->getNev();
        } else {
            $x['valtozatid'] = 0;
            $x['valtozatnev'] = '';
        }
        return $x;
    }

    public function calcRegularPrice($valutanem, $valtozat = null)
    {
        return $this->getBruttoAr(
            $valtozat,
            null,
            $valutanem,
            \mkw\store::getParameter(\mkw\consts::getWebshopPriceConst(\mkw\store::getWebshopNum()))
        );
    }

    public function calcSalePrice($valutanem, $valtozat = null)
    {
        return $this->getBruttoAr(
            $valtozat,
            null,
            $valutanem,
            \mkw\store::getParameter(\mkw\consts::getWebshopDiscountConst(\mkw\store::getWebshopNum()))
        );
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTranslatedNev($locale)
    {
        $ta = $this->getTranslationsArray();
        return $ta[$locale]['nev'];
    }

    public function getNev()
    {
        return $this->nev;
    }

    public function setNev($nev)
    {
        $this->nev = $nev;
    }

    public function getMekod()
    {
        return $this->mekod;
    }

    public function getMekodId()
    {
        if ($this->mekod) {
            return $this->mekod->getId();
        }
        return '';
    }

    public function getMekodNev()
    {
        if ($this->mekod) {
            return $this->mekod->getNev();
        }
        return '';
    }

    public function setMekod($mekod)
    {
        $this->mekod = $mekod;
        if ($mekod) {
            $this->me = $this->mekod->getNev();
        }
    }

    public function getMe()
    {
        return $this->me;
    }

    public function setMe($me)
    {
        $this->me = $me;
    }

    public function getVtsz()
    {
        return $this->vtsz;
    }

    public function getVtszNev()
    {
        if ($this->vtsz) {
            return $this->vtsz->getNev();
        }
        return '';
    }

    public function getVtszId()
    {
        if ($this->vtsz) {
            return $this->vtsz->getId();
        }
        return '';
    }

    public function setVtsz($vtsz)
    {
        $this->vtsz = $vtsz;
        if ($vtsz) {
            $afa = $vtsz->getAfa();
            if ($afa) {
                $this->setAfa($afa);
            }
        }
    }

    public function getAfa()
    {
        return $this->afa;
    }

    public function getAfaNev()
    {
        if ($this->afa) {
            return $this->afa->getNev();
        }
        return '';
    }

    public function getAfaId()
    {
        if ($this->afa) {
            return $this->afa->getId();
        }
        return '';
    }

    public function setAfa($afa)
    {
        $this->afa = $afa;
    }

    public function getCikkszam()
    {
        return $this->cikkszam;
    }

    public function setCikkszam($cikkszam)
    {
        $this->cikkszam = $cikkszam;
    }

    public function getIdegencikkszam()
    {
        return $this->idegencikkszam;
    }

    public function setIdegencikkszam($idegencikkszam)
    {
        $this->idegencikkszam = $idegencikkszam;
    }

    public function getVonalkod()
    {
        return $this->vonalkod;
    }

    public function setVonalkod($vonalkod)
    {
        $this->vonalkod = $vonalkod;
    }

    public function getLeiras()
    {
        return $this->leiras;
    }

    public function setLeiras($leiras)
    {
        $this->leiras = $leiras;
    }

    public function getRovidleiras()
    {
        return $this->rovidleiras;
    }

    public function setRovidleiras($rovidleiras)
    {
        $this->rovidleiras = $rovidleiras;
    }

    public function getOldalcim()
    {
        return $this->oldalcim;
    }

    public function getShowOldalcim()
    {
        if ($this->oldalcim) {
            return $this->oldalcim;
        } else {
            $result = \mkw\store::getParameter(\mkw\consts::Termekoldalcim);
            if ($result) {
                $result = str_replace('[termeknev]', $this->getNev(), $result);
                $result = str_replace('[kategorianev]', $this->getTermekfa1Nev(), $result);
                $result = str_replace('[global]', \mkw\store::getParameter(\mkw\consts::Oldalcim), $result);
                $result = str_replace('[bruttoar]', number_format($this->getBruttoAr(null, \mkw\store::getLoggedInUser()), 0, ',', ''), $result);
                $result = str_replace('[rovidleiras]', $this->getRovidleiras(), $result);
                return $result;
            } else {
                return \mkw\store::getParameter(\mkw\consts::Oldalcim);
            }
        }
    }

    public function setOldalcim($oldalcim)
    {
        $this->oldalcim = $oldalcim;
    }

    public function getSeodescription()
    {
        return $this->seodescription;
    }

    public function getShowSeodescription()
    {
        if ($this->seodescription) {
            return $this->seodescription;
        } else {
            $result = \mkw\store::getParameter(\mkw\consts::Termekseodescription);
            if ($result) {
                $result = str_replace('[termeknev]', $this->getNev(), $result);
                $result = str_replace('[kategorianev]', $this->getTermekfa1Nev(), $result);
                $result = str_replace('[global]', \mkw\store::getParameter(\mkw\consts::Seodescription), $result);
                $result = str_replace('[bruttoar]', number_format($this->getBruttoAr(null, \mkw\store::getLoggedInUser()), 0, ',', ''), $result);
                $result = str_replace('[rovidleiras]', $this->getRovidleiras(), $result);
                return $result;
            } else {
                return \mkw\store::getParameter(\mkw\consts::Seodescription);
            }
        }
    }

    public function setSeodescription($seodescription)
    {
        $this->seodescription = $seodescription;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    public function getLathato()
    {
        return $this->lathato;
    }

    public function setLathato($lathato)
    {
        $this->lathato = $lathato;
    }

    public function getHozzaszolas()
    {
        return $this->hozzaszolas;
    }

    public function setHozzaszolas($hozzaszolas)
    {
        $this->hozzaszolas = $hozzaszolas;
    }

    public function getAjanlott()
    {
        return $this->ajanlott;
    }

    public function setAjanlott($ajanlott)
    {
        $this->ajanlott = $ajanlott;
    }

    public function getMozgat()
    {
        return $this->mozgat;
    }

    public function setMozgat($mozgat)
    {
        $this->mozgat = $mozgat;
    }

    public function getInaktiv()
    {
        return $this->inaktiv;
    }

    public function setInaktiv($inaktiv)
    {
        $this->inaktiv = $inaktiv;
    }

    public function getTermekexportbanszerepel()
    {
        return $this->termekexportbanszerepel;
    }

    public function setTermekexportbanszerepel($adat)
    {
        $this->termekexportbanszerepel = $adat;
    }

    public function getHparany()
    {
        return $this->hparany;
    }

    public function setHparany($hparany)
    {
        $this->hparany = $hparany;
    }

    public function getNetto()
    {
        return $this->netto;
    }

    public function setNetto($netto)
    {
        $this->netto = $netto;
        $this->brutto = $this->getAfa()->calcBrutto($netto);
    }

    public function getBrutto()
    {
        return $this->brutto;
    }

    public function setBrutto($brutto)
    {
        $this->brutto = $brutto;
        $this->netto = $this->getAfa()->calcNetto($brutto);
    }

    public function getAkcios()
    {
        $ma = date(\mkw\store::$DateFormat);
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

    public function getAkcioTipus()
    {
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

    public function getAkciosnetto()
    {
        return $this->akciosnetto;
    }

    public function setAkciosnetto($netto)
    {
        $this->akciosnetto = $netto;
        $this->akciosbrutto = $this->getAfa()->calcBrutto($netto);
    }

    public function getAkciosbrutto()
    {
        return $this->akciosbrutto;
    }

    public function setAkciosbrutto($brutto)
    {
        $this->akciosbrutto = $brutto;
        $this->akciosnetto = $this->getAfa()->calcNetto($brutto);
    }

    public function getAkciostart()
    {
        return $this->akciostart;
    }

    public function getAkciostartStr()
    {
        if ($this->getAkciostart()) {
            return $this->getAkciostart()->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    public function setAkciostart($adat = '')
    {
        if ($adat != '') {
            $this->akciostart = new \DateTime(\mkw\store::convDate($adat));
        } else {
            $this->akciostart = null;
        }
    }

    public function getAkciostop()
    {
        return $this->akciostop;
    }

    public function getAkciostopStr()
    {
        if ($this->getAkciostop()) {
            return $this->getAkciostop()->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    public function setAkciostop($adat = '')
    {
        if ($adat != '') {
            $this->akciostop = new \DateTime(\mkw\store::convDate($adat));
        } else {
            $this->akciostop = null;
        }
    }

    /**
     *
     * @return ArrayCollection
     */
    public function getCimkek()
    {
        return $this->cimkek;
    }

    public function getAllCimkeId()
    {
        $res = [];
        foreach ($this->cimkek as $cimke) {
            $res[] = $cimke->getId();
        }
        return $res;
    }

    public function setCimkeNevek($cimkenevek)
    {
        $this->cimkenevek = $cimkenevek;
    }

    public function getCimkeNevek()
    {
        return $this->cimkenevek;
    }

    public function addCimke(Cimketorzs $cimke)
    {
        if (!$this->cimkek->contains($cimke)) {
            $this->cimkek->add($cimke);
            $cimke->addTermek($this);
            $this->setCimkeNevek('');
        }
    }

    public function removeCimke(Cimketorzs $cimke)
    {
        if ($this->cimkek->removeElement($cimke)) {
            //$cimke->removeTermek($this);  // deleted for speed
            return true;
        }
        return false;
    }

    public function removeAllCimke()
    {
//		$this->cimkek->clear();
//		$this->setCimkeNevek('');
        foreach ($this->cimkek as $cimke) {
            $this->removeCimke($cimke);
        }
    }

    public function getCimkeByCategory($cat)
    {
        $ret = null;
        foreach ($this->getCimkek() as $cimke) {
            if ($cat == $cimke->getKategoriaId()) {
                $ret = $cimke;
                break;
            }
        }
        return $ret;
    }

    public function getIdegenkod()
    {
        return $this->idegenkod;
    }

    public function setIdegenkod($idegenkod)
    {
        $this->idegenkod = $idegenkod;
    }

    public function getKiszereles()
    {
        return $this->kiszereles;
    }

    public function setKiszereles($kiszereles)
    {
        $this->kiszereles = $kiszereles;
    }

    public function getTermekfa1()
    {
        return $this->termekfa1;
    }

    public function getTermekfa1Nev()
    {
        if ($this->termekfa1) {
            if ($this->termekfa1->getId() > 1) {
                return $this->termekfa1->getNev();
            }
        }
        return '';
    }

    public function getTermekfa1Id()
    {
        if ($this->termekfa1) {
            return $this->termekfa1->getId();
        }
        return 1;
    }

    public function setTermekfa1($termekfa)
    {
        $this->termekfa1 = $termekfa;
        if ($termekfa) {
            $this->termekfa1karkod = $termekfa->getKarkod();
//            $termekfa->addTermek1($this);
        } else {
            $this->termekfa1karkod = '';
        }
    }

    public function getTermekfa2()
    {
        return $this->termekfa2;
    }

    public function getTermekfa2Nev()
    {
        if ($this->termekfa2) {
            if ($this->termekfa2->getId() > 1) {
                return $this->termekfa2->getNev();
            }
        }
        return '';
    }

    public function getTermekfa2Id()
    {
        if ($this->termekfa2) {
            return $this->termekfa2->getId();
        }
        return 1;
    }

    public function setTermekfa2($termekfa)
    {
        $this->termekfa2 = $termekfa;
        if ($termekfa) {
            $this->termekfa2karkod = $termekfa->getKarkod();
//            $termekfa->addTermek2($this);
        } else {
            $this->termekfa2karkod = '';
        }
    }

    public function getTermekfa3()
    {
        return $this->termekfa3;
    }

    public function getTermekfa3Nev()
    {
        if ($this->termekfa3) {
            if ($this->termekfa3->getId() > 1) {
                return $this->termekfa3->getNev();
            }
        }
        return '';
    }

    public function getTermekfa3Id()
    {
        if ($this->termekfa3) {
            return $this->termekfa3->getId();
        }
        return 1;
    }

    public function setTermekfa3($termekfa)
    {
        $this->termekfa3 = $termekfa;
        if ($termekfa) {
            $this->termekfa3karkod = $termekfa->getKarkod();
//            $termekfa->addTermek3($this);
        } else {
            $this->termekfa3karkod = '';
        }
    }

    /**
     * @return TermekMenu
     */
    public function getTermekmenu1()
    {
        return $this->termekmenu1;
    }

    public function getTermekmenu1Nev()
    {
        if ($this->termekmenu1) {
            if ($this->termekmenu1->getId() > 1) {
                return $this->termekmenu1->getNev();
            }
        }
        return '';
    }

    public function getTermekmenu1Id()
    {
        if ($this->termekmenu1) {
            return $this->termekmenu1->getId();
        }
        return 1;
    }

    public function getTermekmenu1Path()
    {
        if ($this->termekmenu1) {
            if ($this->termekmenu1->getId() > 1) {
                return $this->termekmenu1->getPath($this->termekmenu1);
            }
        }
        return [];
    }

    public function setTermekmenu1($termekmenu)
    {
        $this->termekmenu1 = $termekmenu;
        if ($termekmenu) {
            $this->termekmenu1karkod = $termekmenu->getKarkod();
//            $termekmenu->addTermek1($this);
        } else {
            $this->termekmenu1karkod = '';
        }
    }

    public function getTermekKepek($csaklathato = false)
    {
        if ($csaklathato) {
            $r = [];
            foreach ($this->termekkepek as $kep) {
                if (!$kep->getRejtett()) {
                    $r[] = $kep;
                }
            }
            return $r;
        }
        return $this->termekkepek;
    }

    public function addTermekKep(TermekKep $kep)
    {
//		if (!$this->termekkepek->contains($kep)) {
        $this->termekkepek->add($kep);
        $kep->setTermek($this);
//		}
    }

    public function removeTermekKep(TermekKep $kep)
    {
        if ($this->termekkepek->removeElement($kep)) {
            $kep->removeTermek($this);
            return true;
        }
        return false;
    }

    public function getKepurl($pre = '/')
    {
        if ($this->kepurl) {
            if ($this->kepurl[0] !== $pre) {
                return $pre . $this->kepurl;
            } else {
                return $this->kepurl;
            }
        }
        return '';
    }

    public function getKepurlMini($pre = '/')
    {
        $kepurl = $this->getKepurl($pre);
        if ($kepurl) {
            $t = explode('.', $kepurl);
            $ext = array_pop($t);
            return implode('.', $t) . \mkw\store::getParameter(\mkw\consts::Miniimgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function getKepurlSmall($pre = '/')
    {
        $kepurl = $this->getKepurl($pre);
        if ($kepurl) {
            $t = explode('.', $kepurl);
            $ext = array_pop($t);
            return implode('.', $t) . \mkw\store::getParameter(\mkw\consts::Smallimgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function getKepurlMedium($pre = '/')
    {
        $kepurl = $this->getKepurl($pre);
        if ($kepurl) {
            $t = explode('.', $kepurl);
            $ext = array_pop($t);
            return implode('.', $t) . \mkw\store::getParameter(\mkw\consts::Mediumimgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function getKepurlLarge($pre = '/')
    {
        $kepurl = $this->getKepurl($pre);
        if ($kepurl) {
            $t = explode('.', $kepurl);
            $ext = array_pop($t);
            return implode('.', $t) . \mkw\store::getParameter(\mkw\consts::Bigimgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function getKepurl400($pre = '/')
    {
        $kepurl = $this->getKepurl($pre);
        if ($kepurl) {
            $t = explode('.', $kepurl);
            $ext = array_pop($t);
            return implode('.', $t) . \mkw\store::getParameter(\mkw\consts::I400imgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function getKepurl2000($pre = '/')
    {
        $kepurl = $this->getKepurl($pre);
        if ($kepurl) {
            $t = explode('.', $kepurl);
            $ext = array_pop($t);
            return implode('.', $t) . \mkw\store::getParameter(\mkw\consts::I2000imgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function setKepurl($kepurl)
    {
        $this->kepurl = $kepurl;
        if (!$kepurl) {
            $this->setKepleiras(null);
        }
    }

    public function getKepleiras()
    {
        return $this->kepleiras;
    }

    public function setKepleiras($kepleiras)
    {
        $this->kepleiras = $kepleiras;
    }

    public function getSzelesseg()
    {
        return $this->szelesseg;
    }

    public function setSzelesseg($szelesseg)
    {
        $this->szelesseg = $szelesseg;
    }

    public function getMagassag()
    {
        return $this->magassag;
    }

    public function setMagassag($magassag)
    {
        $this->magassag = $magassag;
    }

    public function getHosszusag()
    {
        return $this->hosszusag;
    }

    public function setHosszusag($hosszusag)
    {
        $this->hosszusag = $hosszusag;
    }

    public function getOsszehajthato()
    {
        return $this->osszehajthato;
    }

    public function setOsszehajthato($osszehajthato)
    {
        $this->osszehajthato = $osszehajthato;
    }

    public function getSuly()
    {
        return $this->suly;
    }

    public function setSuly($suly)
    {
        $this->suly = $suly;
    }

    public function getValtozatok()
    {
        switch (true) {
            case \mkw\store::isMindentkapni():
                return $this->valtozatok;
            case \mkw\store::isMugenrace2026():
            case \mkw\store::isMugenrace():
            case \mkw\store::isSuperzoneB2B():
            case \mkw\store::isMugenrace2021():
                $s = \mkw\store::getParameter(\mkw\consts::ValtozatSorrend);
                $rendezendo = \mkw\store::getParameter(\mkw\consts::RendezendoValtozat);
                $sorrend = explode(',', $s);
                $a = $this->valtozatok->toArray();
                uasort($a, function ($e, $f) use ($sorrend, $rendezendo) {
                    if ($e->getAdatTipus1Id() == $rendezendo) {
                        $ertek = $e->getErtek1();
                        $eszin = $e->getErtek2();
                    } elseif ($e->getAdatTipus2Id() == $rendezendo) {
                        $ertek = $e->getErtek2();
                        $eszin = $e->getErtek1();
                    } else {
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
                    } elseif ($f->getAdatTipus2Id() == $rendezendo) {
                        $ertek = $f->getErtek2();
                        $fszin = $f->getErtek1();
                    } else {
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
            case \mkw\store::isKisszamlazo():
            case \mkw\store::isMIJSZ():
            case \mkw\store::isDarshan():
                return null;
            default :
                throw new \Exception('ISMERETLEN THEME: ' . \mkw\store::getTheme());
        }
    }

    public function addValtozat(TermekValtozat $valt)
    {
//		if (!$this->valtozatok->contains($valt)) {
        $this->valtozatok->add($valt);
        $valt->setTermek($this);
//		}
    }

    public function removeValtozat(TermekValtozat $valt)
    {
        if ($this->valtozatok->removeElement($valt)) {
            $valt->setTermek(null);
            return true;
        }
        return false;
    }

    public function getTermekReceptek()
    {
        return $this->termekreceptek;
    }

    public function addTermekRecept(TermekRecept $recept)
    {
//		if (!$this->termekreceptek->contains($recept)) {
        $this->termekreceptek->add($recept);
        $recept->setTermek($this);
//		}
    }

    public function removeTermekRecept(TermekRecept $recept)
    {
        if ($this->termekreceptek->removeElement($recept)) {
            $recept->removeTermek($this);
            return true;
        }
        return false;
    }

    public function getAlTermekReceptek()
    {
        return $this->altermekreceptek;
    }

    public function addAlTermekRecept(TermekRecept $recept)
    {
//		if (!$this->altermekreceptek->contains($recept)) {
        $this->altermekreceptek->add($recept);
        $recept->setAlTermek($this);
//		}
    }

    public function removeAlTermekRecept(TermekRecept $recept)
    {
        if ($this->altermekreceptek->removeElement($recept)) {
            $recept->removeAlTermek($this);
            return true;
        }
        return false;
    }

    public function getMegtekintesdb()
    {
        return $this->megtekintesdb;
    }

    public function setMegtekintesdb($adat)
    {
        $this->megtekintesdb = $adat;
    }

    public function incMegtekintesdb()
    {
        $this->megtekintesdb++;
    }

    public function getMegvasarlasdb()
    {
        return $this->megvasarlasdb;
    }

    public function setMegvasarlasdb($adat)
    {
        $this->megvasarlasdb = $adat;
    }

    public function incMegvasarlasdb()
    {
        $this->megvasarlasdb++;
    }

    public function getKiemelt()
    {
        return $this->kiemelt;
    }

    public function setKiemelt($adat)
    {
        $this->kiemelt = $adat;
    }

    public function getTermekKapcsolodok()
    {
        return $this->termekkapcsolodok;
    }

    public function addTermekKapcsolodo(TermekKapcsolodo $adat)
    {
//		if (!$this->termekreceptek->contains($adat)) {
        $this->termekkapcsolodok->add($adat);
        $adat->setTermek($this);
//		}
    }

    public function removeTermekKapcsolodo(TermekKapcsolodo $adat)
    {
        if ($this->termekkapcsolodok->removeElement($adat)) {
            $adat->removeTermek($this);
            return true;
        }
        return false;
    }

    public function getAlTermekKapcsolodok()
    {
        return $this->altermekkapcsolodok;
    }

    public function addAlTermekKapcsolodo(TermekKapcsolodo $adat)
    {
//		if (!$this->altermekkapcsolodok->contains($adat)) {
        $this->altermekkapcsolodok->add($adat);
        $adat->setAlTermek($this);
//		}
    }

    public function removeAlTermekKapcsolodo(TermekKapcsolodo $adat)
    {
        if ($this->altermekkapcsolodok->removeElement($adat)) {
            $adat->removeAlTermek($this);
            return true;
        }
        return false;
    }

    public function getLastmod()
    {
        return $this->lastmod;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getValtozatadattipus()
    {
        return $this->valtozatadattipus;
    }

    public function getValtozatadattipusNev()
    {
        if ($this->valtozatadattipus) {
            if ($this->valtozatadattipus->getId() > 1) {
                return $this->valtozatadattipus->getNev();
            }
        }
        return '';
    }

    public function getValtozatadattipusId()
    {
        if ($this->valtozatadattipus) {
            return $this->valtozatadattipus->getId();
        }
        return 0;
    }

    public function setValtozatadattipus($a)
    {
        $this->valtozatadattipus = $a;
    }

    /**
     * @param \Entities\Partner $partner
     */
    public function getTermekcsoportKedvezmeny($partner = null)
    {
        $kedvezmeny = 0;
        if ($partner) {
            $tcs = $this->getTermekcsoport();
            if ($tcs) {
                $kdv = \mkw\store::getEm()->getRepository('Entities\PartnerTermekcsoportKedvezmeny')->getByPartnerTermekcsoport($partner, $tcs);
                if ($kdv) {
                    $kedvezmeny = $kdv->getKedvezmeny();
                }
            }
        }
        return $kedvezmeny * 1;
    }

    /**
     * @param \Entities\Partner $partner
     */
    public function getTermekKedvezmeny($partner = null)
    {
        $kedvezmeny = 0;
        if ($partner) {
            $kdv = \mkw\store::getEm()->getRepository('Entities\PartnerTermekKedvezmeny')->getByPartnerTermek($partner, $this);
            if ($kdv) {
                $kedvezmeny = $kdv->getKedvezmeny();
            }
        }
        return $kedvezmeny * 1;
    }

    public function getKedvezmeny($partner = null)
    {
        $kedvezmeny = $this->getTermekKedvezmeny($partner);
        if (!$kedvezmeny) {
            $kedvezmeny = $this->getTermekcsoportKedvezmeny($partner);
        }
        return $kedvezmeny;
    }

    /**
     * @param \Entities\TermekValtozat $valtozat
     * @param \Entities\Partner $partner
     * @param \Entities\Valutanem $valutanem
     * @param \Entities\Arsav | int | null $arsav
     *
     * @return float
     */
    public function getNettoAr($valtozat = null, $partner = null, $valutanem = null, $arsav = null)
    {
        $netto = $this->getKedvezmenynelkuliNettoAr($valtozat, $partner, $valutanem, $arsav);

        $kdv = $this->getKedvezmeny($partner);
        if ($kdv) {
            $netto = $netto * (100 - $kdv) / 100;
        }

        return $netto;
    }

    /**
     * @param \Entities\TermekValtozat $valtozat
     * @param \Entities\Partner $partner
     * @param \Entities\Valutanem $valutanem
     * @param \Entities\Arsav | int | null $arsav
     *
     * @return float
     */
    public function getKedvezmenynelkuliNettoAr($valtozat = null, $partner = null, $valutanem = null, $arsav = null)
    {
        if (!\mkw\store::isArsavok()) {
            $netto = $this->getNetto();
            if ($this->getAkcios()) {
                $netto = $this->getAkciosnetto();
            }
            if (!is_null($valtozat)) {
                if (!is_a($valtozat, TermekValtozat::class)) {
                    $valtozat = \mkw\store::getEm()->getRepository(TermekValtozat::class)->find($valtozat);
                }
                return $netto + $valtozat->getNetto();
            }
            return $netto;
        } else {
            if ($partner && !$arsav) {
                $arsav = $partner->getArsav();
            }
            if ($partner && !$valutanem) {
                $valutanem = $partner->getValutanem();
            }
            $netto = 0;
            $arsavAr = \mkw\store::getEm()->getRepository(TermekAr::class)->getArsavAr($this, $valutanem, $arsav);
            if ($arsavAr) {
                $netto = $arsavAr->getNetto();
            }

            return $netto * 1;
        }
    }

    public function getNettoUtolsoBeszar($valtozatid = null, $datum = null)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('teljesites', 'teljesites');
        $rsm->addScalarResult('arfolyam', 'arfolyam');
        $rsm->addScalarResult('nettoegysarhuf', 'nettoegysarhuf');
        $rsm->addScalarResult('bruttoegysarhuf', 'bruttoegysarhuf');
        $rsm->addScalarResult('nettoegysar', 'nettoegysar');
        $rsm->addScalarResult('bruttoegysar', 'bruttoegysar');

        $filter = new FilterDescriptor();
        $filter->addFilter('bf.bizonylattipus_id', '=', 'bevet');
        $filter->addSql('bf.partner_id NOT IN (8390,12291)');
        $filter->addFilter('bf.rontott', '=', false);
        $filter->addFilter('bf.storno', '=', false);
        $filter->addFilter('bf.stornozott', '=', false);
        if ($datum) {
            $filter->addFilter('bf.teljesites', '<=', $datum);
        }
        if ($valtozatid) {
            $filter->addFilter('bt.termekvaltozat_id', '=', $valtozatid);
        } else {
            $filter->addFilter('bt.termek_id', '=', $this->getId());
        }
        $q = \mkw\store::getEm()->createNativeQuery(
            'SELECT bf.id,bf.teljesites,bf.arfolyam,bt.nettoegysarhuf,bt.bruttoegysarhuf,bt.nettoegysar,bt.bruttoegysar '
            . 'FROM bizonylattetel bt '
            . 'LEFT OUTER JOIN bizonylatfej bf ON (bt.bizonylatfej_id=bf.id)'
            . $filter->getFilterString()
            . ' ORDER BY bf.teljesites DESC',
            $rsm
        );
        $q->setParameters($filter->getQueryParameters());
        $res = $q->getScalarResult();
        $ret = [
            'id' => $res[0]['id']
        ];
        if ($res[0]['nettoegysarhuf'] == 0) {
            $ret['ertek'] = $res[0]['nettoegysar'] * $res[0]['arfolyam'];
        } else {
            $ret['ertek'] = $res[0]['nettoegysarhuf'];
        }
        return $ret;
    }

    public function getBruttoUtolsoBeszar($valtozatid = null, $datum = null)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('teljesites', 'teljesites');
        $rsm->addScalarResult('arfolyam', 'arfolyam');
        $rsm->addScalarResult('nettoegysarhuf', 'nettoegysarhuf');
        $rsm->addScalarResult('bruttoegysarhuf', 'bruttoegysarhuf');
        $rsm->addScalarResult('nettoegysar', 'nettoegysar');
        $rsm->addScalarResult('bruttoegysar', 'bruttoegysar');

        $filter = new FilterDescriptor();
        $filter->addFilter('bf.irany', '>', 0);
        $filter->addFilter('bf.rontott', '=', false);
        $filter->addFilter('bf.storno', '=', false);
        $filter->addFilter('bf.stornozott', '=', false);
        if ($datum) {
            $filter->addFilter('bf.teljesites', '<=', $datum);
        }
        if ($valtozatid) {
            $filter->addFilter('bt.termekvaltozat_id', '=', $valtozatid);
        } else {
            $filter->addFilter('bt.termek_id', '=', $this->getId());
        }
        $q = \mkw\store::getEm()->createNativeQuery(
            'SELECT bf.id,bf.teljesites,bf.arfolyam,bt.nettoegysarhuf,bt.bruttoegysarhuf,bt.nettoegysar,bt.bruttoegysar '
            . 'FROM bizonylattetel bt '
            . 'LEFT OUTER JOIN bizonylatfej bf ON (bt.bizonylatfej_id=bf.id)'
            . $filter->getFilterString()
            . ' ORDER BY bf.teljesites DESC',
            $rsm
        );
        $q->setParameters($filter->getQueryParameters());
        $res = $q->getScalarResult();
        $ret = [
            'id' => $res[0]['id']
        ];
        if ($res[0]['bruttoegysarhuf'] == 0) {
            $ret['ertek'] = $res[0]['bruttoegysar'] * $res[0]['arfolyam'];
        } else {
            $ret['ertek'] = $res[0]['bruttoegysarhuf'];
        }
        return $ret;
    }

    /**
     * @param \Entities\TermekValtozat $valtozat
     * @param \Entities\Partner $partner
     * @param \Entities\Valutanem $valutanem
     * @param \Entities\Arsav | int | null $arsav
     *
     * @return float
     */
    public function getBruttoAr($valtozat = null, $partner = null, $valutanem = null, $arsav = null)
    {
        $brutto = $this->getKedvezmenynelkuliBruttoAr($valtozat, $partner, $valutanem, $arsav);

        $kdv = $this->getKedvezmeny($partner);
        if ($kdv) {
            $brutto = $brutto * (100 - $kdv) / 100;
        }

        return $brutto;
    }

    /**
     * @param \Entities\TermekValtozat $valtozat
     * @param \Entities\Valutanem $valutanem
     * @param \Entities\Arsav | int | null $arsav
     *
     * @return float
     */
    public function getBruttoArByArsav($valtozat = null, $arsav = null, $valutanem = null)
    {
        if (!\mkw\store::isArsavok()) {
            return $this->getBrutto();
        }
        $arsavAr = \mkw\store::getEm()->getRepository(TermekAr::class)->getArsavAr($this, $valutanem, $arsav);
        if ($arsavAr) {
            $brutto = $arsavAr->getBrutto();
        } else {
            $brutto = 0;
        }
        return $brutto * 1;
    }

    /**
     * @param \Entities\TermekValtozat $valtozat
     * @param \Entities\Partner $partner
     * @param \Entities\Valutanem $valutanem
     * @param \Entities\Arsav | int | null $arsav
     *
     * @return float
     */
    public function getKedvezmenynelkuliBruttoAr($valtozat = null, $partner = null, $valutanem = null, $arsav = null)
    {
        // Nincsenek ársávok
        if (!\mkw\store::isArsavok()) {
            $brutto = $this->getBrutto();
            if ($this->getAkcios()) {
                $brutto = $this->getAkciosbrutto();
            }
            if (!is_null($valtozat)) {
                if (!is_a($valtozat, TermekValtozat::class)) {
                    $valtozat = \mkw\store::getEm()->getRepository(TermekValtozat::class)->find($valtozat);
                }
                return $brutto + $valtozat->getBrutto();
            }
            return $brutto;
        } // Vannak ársávok
        else {
            if ($partner && !$arsav) {
                $arsav = $partner->getArsav();
            }
            if ($partner && !$valutanem) {
                $valutanem = $partner->getValutanem();
            }
            $brutto = 0;
            $arsavAr = \mkw\store::getEm()->getRepository(TermekAr::class)->getArsavAr($this, $valutanem, $arsav);
            if ($arsavAr) {
                $brutto = $arsavAr->getBrutto();
            }

            return $brutto * 1;
        }
    }

    public function getEredetiBruttoAr($valtozat)
    {
        // Nincsenek ársávok
        if (!\mkw\store::isArsavok()) {
            $brutto = $this->getBrutto();
            if (!is_null($valtozat)) {
                if (!is_a($valtozat, TermekValtozat::class)) {
                    $valtozat = \mkw\store::getEm()->getRepository(TermekValtozat::class)->find($valtozat);
                }
                return $brutto + $valtozat->getBrutto();
            }
            return $brutto;
        } // Vannak ársávok
        else {
            return 0;
        }
    }

    public function getEredetiNettoAr($valtozat)
    {
        // Nincsenek ársávok
        if (!\mkw\store::isArsavok()) {
            $brutto = $this->getNetto();
            if (!is_null($valtozat)) {
                if (!is_a($valtozat, TermekValtozat::class)) {
                    $valtozat = \mkw\store::getEm()->getRepository(TermekValtozat::class)->find($valtozat);
                }
                return $brutto + $valtozat->getNetto();
            }
            return $brutto;
        } // Vannak ársávok
        else {
            return 0;
        }
    }

    public function getArValutanem($valtozat = null, $partner = null, $valutanem = null)
    {
        if ($partner) {
            if (!$valutanem) {
                $valutanem = $partner->getValutanem();
            }
        }
        if (!$valutanem) {
            $valutanem = \mkw\store::getEm()->getRepository('Entities\Valutanem')->find(\mkw\store::getParameter(\mkw\consts::Valutanem));
        }
        return $valutanem;
    }

    public function getNemkaphato()
    {
        return $this->nemkaphato;
    }

    public function setNemkaphato($val)
    {
        $this->nemkaphato = $val;
    }

    public function getGyarto()
    {
        return $this->gyarto;
    }

    public function getGyartoNev()
    {
        if ($this->gyarto) {
            return $this->gyarto->getNev();
        }
        return '';
    }

    public function getGyartoId()
    {
        if ($this->gyarto) {
            return $this->gyarto->getId();
        }
        return '';
    }

    public function setGyarto($gyarto)
    {
        $this->gyarto = $gyarto;
    }

    public function getFuggoben()
    {
        return $this->fuggoben;
    }

    public function setFuggoben($d)
    {
        $this->fuggoben = $d;
    }

    public function getSzallitasiido()
    {
        return $this->szallitasiido;
    }

    public function setSzallitasiido($adat)
    {
        $this->szallitasiido = $adat;
    }

    public function getRegikepurl()
    {
        return $this->regikepurl;
    }

    public function setRegikepurl($adat)
    {
        $this->regikepurl = $adat;
    }

    public function getTermekArak()
    {
        return $this->termekarak;
    }

    public function addTermekAr(TermekAr $adat)
    {
        $this->termekarak->add($adat);
        $adat->setTermek($this);
    }

    public function removeTermekAr(TermekAr $adat)
    {
        if ($this->termekarak->removeElement($adat)) {
            $adat->removeTermek($this);
            return true;
        }
        return false;
    }

    public function getTranslations()
    {
        return $this->translations;
    }

    public function getTranslationsArray()
    {
        $r = [];
        /** @var \Entities\TermekTranslation $tr */
        foreach ($this->translations as $tr) {
            $r[$tr->getLocale()][$tr->getField()] = $tr->getContent();
        }
        return $r;
    }

    public function addTranslation(TermekTranslation $t)
    {
        if (!$this->translations->contains($t)) {
            $this->translations[] = $t;
            $t->setObject($this);
        }
    }

    public function removeTranslation(TermekTranslation $t)
    {
        $this->translations->removeElement($t);
    }

    public function getLocale()
    {
        return $this->locale;
    }

    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * @return \Entities\Termekcsoport
     */
    public function getTermekcsoport()
    {
        return $this->termekcsoport;
    }

    public function getTermekcsoportNev()
    {
        if ($this->termekcsoport) {
            return $this->termekcsoport->getNev();
        }
        return '';
    }

    public function getTermekcsoportId()
    {
        if ($this->termekcsoport) {
            return $this->termekcsoport->getId();
        }
        return 0;
    }

    /**
     * @param \Entities\Termekcsoport $termekcsoport
     */
    public function setTermekcsoport($termekcsoport)
    {
        $this->termekcsoport = $termekcsoport;
    }

    public function getKozvetitett()
    {
        return $this->kozvetitett;
    }

    public function setKozvetitett($kozvetitett)
    {
        $this->kozvetitett = $kozvetitett;
    }

    /**
     * @return mixed
     */
    public function getNepszeruseg()
    {
        return $this->nepszeruseg;
    }

    /**
     * @param mixed $nepszeruseg
     */
    public function setNepszeruseg($nepszeruseg)
    {
        $this->nepszeruseg = $nepszeruseg;
    }

    public function incNepszeruseg()
    {
        $this->nepszeruseg++;
    }

    /**
     * @return mixed
     */
    public function getMigrid()
    {
        return $this->migrid;
    }

    /**
     * @param mixed $migrid
     */
    public function setMigrid($migrid)
    {
        $this->migrid = $migrid;
    }

    /**
     * @return mixed
     */
    public function getNev2()
    {
        return $this->nev2;
    }

    /**
     * @param mixed $nev2
     */
    public function setNev2($nev2)
    {
        $this->nev2 = $nev2;
    }

    /**
     * @return mixed
     */
    public function getNev3()
    {
        return $this->nev3;
    }

    /**
     * @param mixed $nev3
     */
    public function setNev3($nev3)
    {
        $this->nev3 = $nev3;
    }

    /**
     * @return mixed
     */
    public function getNev4()
    {
        return $this->nev4;
    }

    /**
     * @param mixed $nev4
     */
    public function setNev4($nev4)
    {
        $this->nev4 = $nev4;
    }

    /**
     * @return mixed
     */
    public function getNev5()
    {
        return $this->nev5;
    }

    /**
     * @param mixed $nev5
     */
    public function setNev5($nev5)
    {
        $this->nev5 = $nev5;
    }

    /**
     * @return mixed
     */
    public function getSuruseg()
    {
        return $this->suruseg;
    }

    /**
     * @param mixed $suruseg
     */
    public function setSuruseg($suruseg)
    {
        $this->suruseg = $suruseg;
    }

    /**
     * @return mixed
     */
    public function getEgyprodukcios()
    {
        return $this->egyprodukcios;
    }

    /**
     * @param mixed $egyprodukcios
     */
    public function setEgyprodukcios($egyprodukcios)
    {
        $this->egyprodukcios = $egyprodukcios;
    }

    /**
     * @return mixed
     */
    public function getValutameszorzo()
    {
        return $this->valutameszorzo;
    }

    /**
     * @param mixed $valutameszorzo
     */
    public function setValutameszorzo($valutameszorzo)
    {
        $this->valutameszorzo = $valutameszorzo;
    }

    /**
     * @param \Entities\TermekValtozat $valtozat
     * @param int $mennyiseg
     *
     * @return int
     */
    public function calcSzallitasiido($valtozat = null, $mennyiseg = 0, $kivevebizonylat = null)
    {
        switch (true) {
            case \mkw\store::isMindentkapni():
                $szallitasiido = 0;
                if (!is_null($valtozat)) {
                    $keszlet = $valtozat->getKeszlet() - $valtozat->getFoglaltMennyiseg($kivevebizonylat);
                    if ((($mennyiseg === 0) && ($keszlet > 0)) || (($mennyiseg !== 0) && ($keszlet >= $mennyiseg))) {
                        $szallitasiido = 1;
                    }
                } else {
                    $keszlet = $this->getKeszlet() - $this->getFoglaltMennyiseg($kivevebizonylat);
                    if ((($mennyiseg === 0) && ($keszlet > 0)) || (($mennyiseg !== 0) && ($keszlet >= $mennyiseg))) {
                        $szallitasiido = 1;
                    }
                }
                if ($szallitasiido === 0) {
                    if ($this->szallitasiido) {
                        $szallitasiido = $this->szallitasiido;
                    } else {
                        if ($this->gyarto && $this->gyarto->getSzallitasiido()) {
                            $szallitasiido = $this->gyarto->getSzallitasiido();
                        }
                    }
                }
                break;

            default:
                $szallitasiido = 0;
        }
        return $szallitasiido;
    }

    /**
     * @return mixed
     */
    public function getJogaalkalom()
    {
        return $this->jogaalkalom;
    }

    /**
     * @param mixed $jogaalkalom
     */
    public function setJogaalkalom($jogaalkalom)
    {
        $this->jogaalkalom = $jogaalkalom;
    }

    /**
     * @return mixed
     */
    public function getJogaervenyesseg()
    {
        return $this->jogaervenyesseg;
    }

    /**
     * @param mixed $jogaervenyesseg
     */
    public function setJogaervenyesseg($jogaervenyesseg)
    {
        $this->jogaervenyesseg = $jogaervenyesseg;
    }

    /**
     * @return mixed
     */
    public function getLathato2()
    {
        return $this->lathato2;
    }

    /**
     * @param mixed $lathato2
     */
    public function setLathato2($lathato2)
    {
        $this->lathato2 = $lathato2;
    }

    /**
     * @return mixed
     */
    public function getLathato3()
    {
        return $this->lathato3;
    }

    /**
     * @param mixed $lathato3
     */
    public function setLathato3($lathato3)
    {
        $this->lathato3 = $lathato3;
    }

    /**
     * @return mixed
     */
    public function getEladhato()
    {
        return $this->eladhato;
    }

    /**
     * @param mixed $eladhato
     */
    public function setEladhato($eladhato)
    {
        $this->eladhato = $eladhato;
    }

    public function getNLathato($n)
    {
        switch ($n) {
            case 1:
                return $this->getLathato();
            case 2:
                return $this->getLathato2();
            case 3:
                return $this->getLathato3();
            case 4:
                return $this->getLathato4();
            case 5:
                return $this->getLathato5();
            case 6:
                return $this->getLathato6();
            case 7:
                return $this->getLathato7();
            case 8:
                return $this->getLathato8();
            case 9:
                return $this->getLathato9();
            case 10:
                return $this->getLathato10();
            case 11:
                return $this->getLathato11();
            case 12:
                return $this->getLathato12();
            case 13:
                return $this->getLathato13();
            case 14:
                return $this->getLathato14();
            case 15:
                return $this->getLathato15();
            default:
                return $this->getLathato();
        }
    }

    public function getXLathato()
    {
        return $this->getNLathato(\mkw\store::getSetupValue('webshopnum', 1));
    }

    /**
     * @return mixed
     */
    public function getEmagtiltva()
    {
        return $this->emagtiltva;
    }

    /**
     * @param mixed $emagtiltva
     */
    public function setEmagtiltva($emagtiltva)
    {
        $this->emagtiltva = $emagtiltva;
    }

    /**
     * @return mixed
     */
    public function getKiirtnev()
    {
        return $this->kiirtnev;
    }

    /**
     * @param mixed $kiirtnev
     */
    public function setKiirtnev($kiirtnev)
    {
        $this->kiirtnev = $kiirtnev;
    }

    public function getTermekDokok()
    {
        return $this->termekdokok;
    }

    public function addTermekDok(TermekDok $dok)
    {
        $this->termekdokok->add($dok);
        $dok->setTermek($this);
    }

    public function removeTermekDok(TermekDok $dok)
    {
        if ($this->termekdokok->removeElement($dok)) {
            $dok->removeTermek($this);
            return true;
        }
        return false;
    }

    /**
     *
     * @return ArrayCollection
     */
    public function getBlogposztok()
    {
        return $this->blogposztok;
    }

    public function getAllBlogposztId()
    {
        $res = [];
        foreach ($this->blogposztok as $bp) {
            $res[] = $bp->getId();
        }
        return $res;
    }

    public function addBlogposzt(Blogposzt $blogposzt)
    {
        if (!$this->blogposztok->contains($blogposzt)) {
            $this->blogposztok->add($blogposzt);
            $blogposzt->addTermek($this);
        }
    }

    public function removeBlogposzt(Blogposzt $blogposzt)
    {
        if ($this->blogposztok->removeElement($blogposzt)) {
            $blogposzt->removeTermek($this);  // deleted for speed
            return true;
        }
        return false;
    }

    public function removeAllBlogposzt()
    {
//		$this->blogposztok->clear();
        foreach ($this->blogposztok as $bp) {
            $this->removeBlogposzt($bp);
        }
    }

    /**
     * @return mixed
     */
    public function getLathato4()
    {
        return $this->lathato4;
    }

    /**
     * @param mixed $lathato4
     */
    public function setLathato4($lathato4)
    {
        $this->lathato4 = $lathato4;
    }

    /**
     * @return mixed
     */
    public function getLathato5()
    {
        return $this->lathato5;
    }

    /**
     * @param mixed $lathato5
     */
    public function setLathato5($lathato5)
    {
        $this->lathato5 = $lathato5;
    }

    /**
     * @return mixed
     */
    public function getLathato6()
    {
        return $this->lathato6;
    }

    /**
     * @param mixed $lathato6
     */
    public function setLathato6($lathato6)
    {
        $this->lathato6 = $lathato6;
    }

    /**
     * @return mixed
     */
    public function getLathato7()
    {
        return $this->lathato7;
    }

    /**
     * @param mixed $lathato7
     */
    public function setLathato7($lathato7)
    {
        $this->lathato7 = $lathato7;
    }

    /**
     * @return mixed
     */
    public function getLathato8()
    {
        return $this->lathato8;
    }

    /**
     * @param mixed $lathato8
     */
    public function setLathato8($lathato8)
    {
        $this->lathato8 = $lathato8;
    }

    /**
     * @return mixed
     */
    public function getLathato9()
    {
        return $this->lathato9;
    }

    /**
     * @param mixed $lathato9
     */
    public function setLathato9($lathato9)
    {
        $this->lathato9 = $lathato9;
    }

    /**
     * @return mixed
     */
    public function getLathato10()
    {
        return $this->lathato10;
    }

    /**
     * @param mixed $lathato10
     */
    public function setLathato10($lathato10)
    {
        $this->lathato10 = $lathato10;
    }

    /**
     * @return mixed
     */
    public function getLathato11()
    {
        return $this->lathato11;
    }

    /**
     * @param mixed $lathato11
     */
    public function setLathato11($lathato11)
    {
        $this->lathato11 = $lathato11;
    }

    /**
     * @return mixed
     */
    public function getLathato12()
    {
        return $this->lathato12;
    }

    /**
     * @param mixed $lathato12
     */
    public function setLathato12($lathato12)
    {
        $this->lathato12 = $lathato12;
    }

    /**
     * @return mixed
     */
    public function getLathato13()
    {
        return $this->lathato13;
    }

    /**
     * @param mixed $lathato13
     */
    public function setLathato13($lathato13)
    {
        $this->lathato13 = $lathato13;
    }

    /**
     * @return mixed
     */
    public function getLathato14()
    {
        return $this->lathato14;
    }

    /**
     * @param mixed $lathato14
     */
    public function setLathato14($lathato14)
    {
        $this->lathato14 = $lathato14;
    }

    /**
     * @return mixed
     */
    public function getLathato15()
    {
        return $this->lathato15;
    }

    /**
     * @param mixed $lathato15
     */
    public function setLathato15($lathato15)
    {
        $this->lathato15 = $lathato15;
    }

    /**
     * @return mixed
     */
    public function getMinboltikeszlet()
    {
        return $this->minboltikeszlet;
    }

    /**
     * @param mixed $minboltikeszlet
     */
    public function setMinboltikeszlet($minboltikeszlet)
    {
        $this->minboltikeszlet = $minboltikeszlet;
    }

    /**
     * @return mixed
     */
    public function getGarancia()
    {
        return $this->garancia;
    }

    /**
     * @param mixed $garancia
     */
    public function setGarancia($garancia)
    {
        $this->garancia = $garancia;
    }

    /**
     * @return mixed
     */
    public function getArukeresofanev()
    {
        return $this->arukeresofanev;
    }

    /**
     * @param mixed $arukeresofanev
     */
    public function setArukeresofanev($arukeresofanev)
    {
        $this->arukeresofanev = $arukeresofanev;
    }

    /**
     * @return false|Termekcimketorzs
     */
    public function getMarka()
    {
        $marka = $this->getCimkeByCategory(\mkw\store::getParameter(\mkw\consts::MarkaCs));
        if ($marka) {
            return $marka;
        }
        return false;
    }

    public function getErtekelesAtlag()
    {
        $ert = \mkw\store::getEm()->getRepository(TermekErtekeles::class)->getAtlagByTermek($this);
        if ($ert[0][1]) {
            return [
                'ertekelesatlag' => \mkw\store::quarterRound($ert[0][2] / $ert[0][1]),
                'ertekelesdb' => $ert[0][1]
            ];
        } else {
            return [
                'ertekelesatlag' => 0,
                'ertekelesdb' => 0
            ];
        }
    }

    public function getTermekErtekelesek()
    {
        return $this->termekertekelesek;
    }

    public function isInTermekKategoria($kat)
    {
        return str_starts_with($this->termekfa1karkod, $kat) ||
            str_starts_with($this->termekfa2karkod, $kat) ||
            str_starts_with($this->termekfa3karkod, $kat);
    }

    /**
     * @return mixed
     */
    public function getJogaelszamolasalap()
    {
        return $this->jogaelszamolasalap;
    }

    /**
     * @param mixed $jogaelszamolasalap
     */
    public function setJogaelszamolasalap($jogaelszamolasalap): void
    {
        $this->jogaelszamolasalap = $jogaelszamolasalap;
    }

    /**
     * @return mixed
     */
    public function getJogaervenyessegnap()
    {
        return $this->jogaervenyessegnap;
    }

    /**
     * @param mixed $jogaervenyessegnap
     */
    public function setJogaervenyessegnap($jogaervenyessegnap): void
    {
        $this->jogaervenyessegnap = $jogaervenyessegnap;
    }

    /**
     * @return mixed
     */
    public function getWcid()
    {
        return $this->wcid;
    }

    /**
     * @param mixed $wcid
     */
    public function setWcid($wcid): void
    {
        $this->wcid = $wcid;
    }

    /**
     * @return mixed
     */
    public function getWcdate()
    {
        return $this->wcdate;
    }

    public function getWcdateStr($wcdate)
    {
        return $this->wcdate->format(\mkw\store::$DateTimeFormat);
    }

    /**
     * @param mixed $wcdate
     */
    public function setWcdate($adat = null): void
    {
        if (is_a($adat, 'DateTime')) {
            $this->wcdate = $adat;
        } else {
            if ($adat == '') {
                $adat = date(\mkw\store::$sqlDateTimeFormat);
            }
            $this->wcdate = new \DateTime(\mkw\store::convDate($adat));
        }
    }

    public function clearWcdate()
    {
        $this->wcdate = null;
    }

    public function getNevForditas($ford, $locale)
    {
        if ($ford[$locale]['nev']) {
            return $ford[$locale]['nev'];
        }
        return $this->getNev();
    }

    public function getLeirasForditas($ford, $locale)
    {
        if ($ford[$locale]['leiras']) {
            return $ford[$locale]['leiras'];
        }
        return $this->getLeiras();
    }

    /**
     * @return mixed
     */
    public function getKepwcid()
    {
        return $this->kepwcid;
    }

    /**
     * @param mixed $kepwcid
     */
    public function setKepwcid($kepwcid): void
    {
        $this->kepwcid = $kepwcid;
    }

    public function findTermekKepBy($vmi)
    {
        $pcs = explode('-', $vmi);
        $id = $pcs[2];
        $result = null;
        if ($id) {
            /** @var TermekKep $kep */
            foreach ($this->getTermekKepek() as $kep) {
                if ($kep->getId() == $id) {
                    $result = $kep;
                    break;
                }
            }
        }
        return $result;
    }

    /**
     * @return int
     */
    public function getWctiltva()
    {
        return $this->wctiltva;
    }

    /**
     * @param int $wctiltva
     */
    public function setWctiltva($wctiltva): void
    {
        $this->wctiltva = $wctiltva;
    }

    public function shouldUploadToWc()
    {
        return $this->getWcdate()?->getTimestamp() - $this->getLastmod()?->getTimestamp() < -1;
    }

    public function calcStockForWC()
    {
        $vkeszlet = $this->getKeszlet() - $this->getFoglaltMennyiseg();
        if ($vkeszlet < 0) {
            $vkeszlet = 0;
        }
        return $vkeszlet;
    }

    public function getStockInfoForWC($needid = false)
    {
        $keszlet = $this->calcStockForWC();
        $data = [
            'stock_quantity' => $keszlet,
            'stock_status' => $keszlet > 0 ? 'instock' : 'outofstock',
        ];
        if ($needid) {
            $data['id'] = $this->getWcid();
        }
        return $data;
    }


    public function sendKeszletToWC()
    {
        if (\mkw\store::isWoocommerceOn()) {
            return;
        }
        if ($this->getWctiltva()) {
            return;
        }
        if (!$this->getNFeltoltheto(\mkw\store::getWcWebshopNum())) {
            return;
        }
        if ($this->getWcid()) {
            return;
        }
        $data = $this->getStockInfoForWC();
        if ($data) {
            $wc = store::getWcClient();
            try {
                \mkw\store::writelog($this->getId() . ':Termek->sendKeszletToWC(): ' . json_encode($data));
                $wc->put('products/' . $this->getWcid(), $data);
            } catch (HttpClientException $e) {
                \mkw\store::writelog($this->getId() . ':Termek->sendKeszletToWC():HIBA: ' . $e->getResponse()->getBody());
            }
        }
    }

    public function uploadToWC($doFlush = true)
    {
        if (!\mkw\store::isWoocommerceOn()) {
            return;
        }
        if ($this->getWctiltva()) {
            return;
        }
        if (!$this->getNFeltoltheto(\mkw\store::getWcWebshopNum())) {
            return;
        }
        if ($this->getWcid() && !$this->shouldUploadToWc()) {
            return;
        }
        /** @var Client $wc */
        $wc = store::getWcClient();
        $eur = \mkw\store::getEm()->getRepository(Valutanem::class)->findOneBy(['nev' => 'EUR']);

        \mkw\store::writelog($this->getId() . ': termék adatgyűjtés start');

        $ford = $this->getTranslationsArray();
        $nev = $this->getNevForditas($ford, 'en_us');
        $leiras = $this->getLeirasForditas($ford, 'en_us');
        $meta = [];
        if ($this->getCikkszam()) {
            $meta[] = [
                'key' => 'partnumber',
                'value' => $this->getCikkszam(),
            ];
        }
        $cats = [];
        if ($this->getTermekmenu1() && $this->getTermekmenu1()->getWcid()) {
            $cats[] = [
                'id' => $this->getTermekmenu1()->getWcid(),
            ];
        }
        $tags = [];
        /** @var Termekcimketorzs $cimke */
        foreach ($this->getCimkek() as $cimke) {
            $tags[] = [
                'id' => $cimke->getWcid()
            ];
        }
        $valtozatertekek = [];
        /** @var TermekValtozat $valtozat */
        foreach ($this->getValtozatok() as $valtozat) {
            if (
                (
                    $valtozatertekek[$valtozat->getAdatTipus1Id()]['options'] &&
                    !in_array($valtozat->getErtek1(), $valtozatertekek[$valtozat->getAdatTipus1Id()]['options'])
                ) ||
                !$valtozatertekek[$valtozat->getAdatTipus1Id()]['options']
            ) {
                $valtozatertekek[$valtozat->getAdatTipus1Id()]['wcid'] = $valtozat->getAdatTipus1()->getWcid();
                $valtozatertekek[$valtozat->getAdatTipus1Id()]['name'] = $valtozat->getAdatTipus1Nev();
                $valtozatertekek[$valtozat->getAdatTipus1Id()]['options'][] = $valtozat->getErtek1();
            }
            if (
                (
                    $valtozatertekek[$valtozat->getAdatTipus2Id()]['options'] &&
                    !in_array($valtozat->getErtek2(), $valtozatertekek[$valtozat->getAdatTipus2Id()]['options'])
                ) ||
                !$valtozatertekek[$valtozat->getAdatTipus2Id()]['options']
            ) {
                $valtozatertekek[$valtozat->getAdatTipus2Id()]['wcid'] = $valtozat->getAdatTipus2()->getWcid();
                $valtozatertekek[$valtozat->getAdatTipus2Id()]['name'] = $valtozat->getAdatTipus2Nev();
                $valtozatertekek[$valtozat->getAdatTipus2Id()]['options'][] = $valtozat->getErtek2();
            }
        }
        $attrs = [];
        foreach ($valtozatertekek as $valtozat) {
            $attrs[] = [
                'id' => $valtozat['wcid'],
                'name' => $valtozat['name'],
                'options' => $valtozat['options'],
                'position' => 0,
                'visible' => true,
                'variation' => true
            ];
        }
        $images = [];
        if ($this->getKepurl()) {
            if ($this->getKepwcid()) {
                $images[] = [
                    'id' => $this->getKepwcid()
                ];
            } else {
                $images[] = [
                    'src' => \mkw\store::getWcImageUrlPrefix() . $this->getKepurl(),
                    'name' => 'T-' . $this->getId() . '-',
                    'alt' => $nev . ' - ' . $this->getCikkszam()
                ];
            }
        }
        /** @var TermekKep $kep */
        foreach ($this->getTermekKepek() as $kep) {
            if ($kep->getUrl()) {
                if ($kep->getWcid()) {
                    $images[] = [
                        'id' => $kep->getWcid()
                    ];
                } else {
                    $images[] = [
                        'src' => \mkw\store::getWcImageUrlPrefix() . $kep->getUrl(),
                        'name' => 'T-' . $this->getId() . '-' . $kep->getId(),
                        'alt' => $nev . ' - ' . $this->getCikkszam()
                    ];
                }
            }
        }
        $data = array_merge_recursive(
            [
                'name' => $nev,
                'sku' => 'T-' . $this->getId(),
                'type' => 'variable',
                'status' => $this->getInaktiv() ? 'draft' : 'publish',
                'catalog_visibility' =>
                    !$this->getNLathato(\mkw\store::getWcWebshopNum()) ||
                    $this->getNemkaphato() ||
                    $this->getFuggoben() ? 'hidden' : 'visible',
                'description' => preg_replace("/(\t|\n|\r)+/", "", $leiras),
                'short_description' => mb_substr(preg_replace("/(\t|\n|\r)+/", "", $leiras), 0, 100) . '...',
                'manage_stock' => true,
                'weight' => (string)$this->getSuly(),
                'dimensions' => [
                    'length' => (string)$this->getHosszusag(),
                    'width' => (string)$this->getSzelesseg(),
                    'height' => (string)$this->getMagassag(),
                ],
                'featured' => $this->getAjanlott(),
                'categories' => $cats,
                'tags' => $tags,
                'attributes' => $attrs,
                'images' => $images,
                'meta_data' => $meta,
            ],
            $this->getStockInfoForWC()
        );

        \mkw\store::writelog($this->getId() . ': stop: ' . json_encode($data));

        if (!$this->getWcid()) {
            \mkw\store::writelog($this->getId() . ': termék POST start');
            try {
                $result = $wc->post('products', $data);
            } catch (HttpClientException $e) {
                \mkw\store::writelog($this->getId() . ':HIBA: ' . $e->getResponse()->getBody());
            }
            \mkw\store::writelog($this->getId() . ': termék POST stop');

            foreach ($result->images as $image) {
                $tkep = $this->findTermekKepBy($image->name);
                if ($tkep) {
                    $tkep->setWcid($image->id);
                    $tkep->setWcdate('');
                    \mkw\store::getEm()->persist($tkep);
                } else {
                    $this->setKepwcid($image->id);
                }
            }

            $this->setWcid($result->id);
            $this->setWcdate('');
            $this->dontUploadToWC = true;
            \mkw\store::getEm()->persist($this);
            if ($doFlush) {
                \mkw\store::getEm()->flush();
            }
        } elseif ($this->shouldUploadToWc()) {
            \mkw\store::writelog($this->getId() . ': termék PUT start');
            try {
                $result = $wc->put('products/' . $this->getWcid(), $data);
            } catch (HttpClientException $e) {
                \mkw\store::writelog($this->getId() . ':HIBA: ' . $e->getResponse()->getBody());
            }
            \mkw\store::writelog($this->getId() . ': termék PUT stop');

            foreach ($result->images as $image) {
                $tkep = $this->findTermekKepBy($image->name);
                if ($tkep) {
                    $tkep->setWcid($image->id);
                    $tkep->setWcdate('');
                    \mkw\store::getEm()->persist($tkep);
                } else {
                    $this->setKepwcid($image->id);
                }
            }

            $this->setWcdate('');
            $this->dontUploadToWC = true;
            \mkw\store::getEm()->persist($this);
            if ($doFlush) {
                \mkw\store::getEm()->flush();
            }
        }
        \mkw\store::writelog($this->getId() . ': változat adatgyűjtés start');
        $allvariations = [];
        /** @var TermekValtozat $valtozat */
        foreach ($this->getValtozatok() as $valtozat) {
            $variation = $valtozat->toWC($eur, $nev);
            if (!$valtozat->getWcid()) {
                $variation['__jobtype'] = 'create';
                $allvariations[] = $variation;
            } elseif ($valtozat->shouldUploadToWc()) {
                $variation['__jobtype'] = 'update';
                $allvariations[] = $variation;
            }
        }
        \mkw\store::writelog($this->getId() . ': stop');
        if ($allvariations) {
            $tosend = [];
            foreach ($allvariations as $index => $variation) {
                if ($variation['__jobtype'] == 'create') {
                    unset($variation['__jobtype']);
                    $tosend['create'][] = $variation;
                } elseif ($variation['__jobtype'] == 'update') {
                    unset($variation['__jobtype']);
                    $tosend['update'][] = $variation;
                }
                if (($index + 1) % 100 == 0 || $index + 1 == count($allvariations)) {
                    \mkw\store::writelog($this->getId() . ': változat BATCH POST start');
                    try {
                        $result = $wc->post('products/' . $this->getWcid() . '/variations/batch', $tosend);
                    } catch (HttpClientException $e) {
                        \mkw\store::writelog($this->getId() . ':HIBA: ' . $e->getResponse()->getBody());
                    }
                    $tosend = [];
                    \mkw\store::writelog($this->getId() . ': stop');
                    foreach ($result->create as $res) {
                        $valtozat = \mkw\store::getEm()->getRepository(TermekValtozat::class)->find(substr($res->sku, 3));
                        if ($valtozat) {
                            $valtozat->setWcid($res->id);
                            $valtozat->setWcdate('');
                            \mkw\store::getEm()->persist($valtozat);
                        }
                    }
                    foreach ($result->update as $res) {
                        $valtozat = \mkw\store::getEm()->getRepository(TermekValtozat::class)->findOneBy(['wcid' => $res->id]);
                        if ($valtozat) {
                            $valtozat->setWcdate('');
                            \mkw\store::getEm()->persist($valtozat);
                        }
                    }
                }
            }
            if ($doFlush) {
                \mkw\store::getEm()->flush();
            }
        }
    }

    public function deleteFromWC()
    {
        if (!\mkw\store::isWoocommerceOn() || !$this->getWcid()) {
            return;
        }
        \mkw\store::writelog('DELETE products/' . $this->getWcid());
        $wc = store::getWcClient();
        try {
            $result = $wc->delete('products/' . $this->getWcid());
        } catch (HttpClientException $e) {
            \mkw\store::writelog('DELETE Termek:HIBA: ' . $e->getResponse()->getBody());
        }
    }

    public function sendArToWC($wcclient = null, $eur = null)
    {
        if (!\mkw\store::isWoocommerceOn()) {
            return;
        }
        if ($this->getWctiltva()) {
            return;
        }
        if (!$this->getNFeltoltheto(\mkw\store::getWcWebshopNum())) {
            return;
        }
        if (!$this->getWcid()) {
            return;
        }
        if (!$wcclient) {
            /** @var Client $wc */
            $wc = store::getWcClient();
        } else {
            $wc = $wcclient;
        }
        if (!$eur) {
            $eur = \mkw\store::getEm()->getRepository(Valutanem::class)->findOneBy(['nev' => 'EUR']);
        }
        $variations = [];
        /** @var TermekValtozat $valtozat */
        foreach ($this->getValtozatok() as $index => $valtozat) {
            $variations['update'][] = [
                'id' => $valtozat->getWcid(),
                'regular_price' => $valtozat->calcRegularPrice($eur),
                'sale_price' => $valtozat->calcSalePrice($eur),
            ];
            if (($index + 1) % 100 == 0 || $index + 1 == count($this->getValtozatok())) {
                \mkw\store::writelog($this->getId() . ':SendArToWC:változat BATCH POST start: ' . json_encode($variations));
                try {
                    $result = $wc->post('products/' . $this->getWcid() . '/variations/batch', $variations);
                } catch (HttpClientException $e) {
                    \mkw\store::writelog($this->getId() . ':HIBA: ' . $e->getResponse()->getBody());
                }
                $variations = [];
                \mkw\store::writelog($this->getId() . ': stop');
                foreach ($result->update as $res) {
                    $valtozat = \mkw\store::getEm()->getRepository(TermekValtozat::class)->findOneBy(['wcid' => $res->id]);
                    if ($valtozat) {
                        $valtozat->setWcdate('');
                        \mkw\store::getEm()->persist($valtozat);
                    }
                }
            }
        }
    }

    public function getNFeltoltheto($n)
    {
        switch ($n) {
            case 1:
                return $this->getFeltoltheto();
            case 2:
                return $this->getFeltoltheto2();
            case 3:
                return $this->getFeltoltheto3();
            case 4:
                return $this->getFeltoltheto4();
            case 5:
                return $this->getFeltoltheto5();
            default:
                return $this->getLathato();
        }
    }

    /**
     * @return int
     */
    public function getFeltoltheto()
    {
        return $this->feltoltheto;
    }

    /**
     * @param int $feltoltheto
     */
    public function setFeltoltheto($feltoltheto): void
    {
        $this->feltoltheto = $feltoltheto;
    }

    /**
     * @return int
     */
    public function getFeltoltheto2()
    {
        return $this->feltoltheto2;
    }

    /**
     * @param int $feltoltheto2
     */
    public function setFeltoltheto2($feltoltheto2): void
    {
        $this->feltoltheto2 = $feltoltheto2;
    }

    /**
     * @return int
     */
    public function getFeltoltheto3()
    {
        return $this->feltoltheto3;
    }

    /**
     * @param int $feltoltheto3
     */
    public function setFeltoltheto3($feltoltheto3): void
    {
        $this->feltoltheto3 = $feltoltheto3;
    }

    /**
     * @return int
     */
    public function getFeltoltheto4()
    {
        return $this->feltoltheto4;
    }

    /**
     * @param int $feltoltheto4
     */
    public function setFeltoltheto4($feltoltheto4): void
    {
        $this->feltoltheto4 = $feltoltheto4;
    }

    /**
     * @return int
     */
    public function getFeltoltheto5()
    {
        return $this->feltoltheto5;
    }

    /**
     * @param int $feltoltheto5
     */
    public function setFeltoltheto5($feltoltheto5): void
    {
        $this->feltoltheto5 = $feltoltheto5;
    }

    /**
     * Upload termek entity with its termekvaltozats to PrestaShop
     */
    public function uploadToPresta($doFlush = true)
    {
        if (!$this->isPrestaShopEnabled()) {
            return;
        }
        if ($this->getPrestaTiltva()) {
            return;
        }
        if (!$this->getNFeltoltheto(\mkw\store::getPrestaWebshopNum())) {
            return;
        }
        if ($this->getPrestaId() && !$this->shouldUploadToPresta()) {
            return;
        }

        \mkw\store::writelog($this->getId() . ': PrestaShop termék adatgyűjtés start');

        $prestaClient = $this->getPrestaClient();
        $eur = \mkw\store::getEm()->getRepository(Valutanem::class)->findOneBy(['nev' => 'EUR']);

        $ford = $this->getTranslationsArray();
        $nev = $this->getNevForditas($ford, 'en_us');
        if (!$nev) {
            $nev = $this->getNev();
        }
        $leiras = $this->getLeirasForditas($ford, 'en_us');
        if (!$leiras) {
            $leiras = $this->getLeiras();
        }

        // Prepare product data for PrestaShop format
        $productData = [
            'name' => [
                ['id' => 1, 'value' => $nev], // Language ID 1 for default language
            ],
            'description' => [
                ['id' => 1, 'value' => preg_replace("/(\t|\n|\r)+/", "", $leiras)],
            ],
            'description_short' => [
                ['id' => 1, 'value' => mb_substr(preg_replace("/(\t|\n|\r)+/", "", $leiras), 0, 100) . '...'],
            ],
            'reference' => 'T-' . $this->getId(),
            'ean13' => $this->getVonalkod() ?: '',
            'price' => $this->getNettoAr(null, null, $eur, null),
            'active' => $this->getInaktiv() ? '0' : '1',
            'show_price' => '1',
            'online_only' => '0',
            'minimal_quantity' => '1',
            'weight' => (string)$this->getSuly(),
            'width' => (string)$this->getSzelesseg(),
            'height' => (string)$this->getMagassag(),
            'depth' => (string)$this->getHosszusag(),
            'available_for_order' => !$this->getNemkaphato() ? '1' : '0',
            'show_condition' => '0',
            'condition' => 'new',
            'visibility' => $this->getNLathato(\mkw\store::getPrestaWebshopNum()) ? 'both' : 'none',
        ];

        // Add category if exists
        if ($this->getTermekmenu1() && $this->getTermekmenu1()->getPrestaId()) {
            $productData['id_category_default'] = $this->getTermekmenu1()->getPrestaId();
            $productData['associations']['categories']['category'] = [
                ['id' => $this->getTermekmenu1()->getPrestaId()]
            ];
        }

        // Handle product creation or update
        if (!$this->getPrestaId()) {
            $this->createProductInPresta($prestaClient, $productData, $doFlush);
        } elseif ($this->shouldUploadToPresta()) {
            $this->updateProductInPresta($prestaClient, $productData, $doFlush);
        }

        // Handle variants upload
        $this->uploadVariantsToPresta($prestaClient, $eur, $doFlush);

        \mkw\store::writelog($this->getId() . ': PrestaShop termék feltöltés befejezve');
    }

    /**
     * Create product in PrestaShop
     */
    private function createProductInPresta($prestaClient, $doFlush)
    {
        \mkw\store::writelog($this->getId() . ': PrestaShop termék POST start');

        try {
            $blankProduct = $prestaClient->get(['url' => '/products?schema=blank']);

            $product = $blankProduct->product->children;

            $ford = $this->getTranslationsArray();
            $nev = $this->getNevForditas($ford, 'en_us');
            if (!$nev) {
                $nev = $this->getNev();
            }
            $leiras = $this->getLeirasForditas($ford, 'en_us');
            if (!$leiras) {
                $leiras = $this->getLeiras();
            }

            $product->name->language = $this->getNev();
            $product->description = $this->getLeiras();
            $product->description_short = $this->getRovidleiras();
            $product->new = $productData['new'];
            $product->reference = $productData['reference'];
            $product->ean13 = $this->getVonalkod() ?: '';
            $product->active = $this->getInaktiv();
            $product->visibility = $this->getLathato();


            $product->price = $productData['price'];
            $product->show_price = $productData['show_price'];
            $product->online_only = $productData['online_only'];
            $product->weight = $productData['weight'];
            $product->width = $productData['width'];
            $product->height = $productData['height'];
            $product->available_for_order = $productData['available_for_order'];
            $product->show_condition = $productData['show_condition'];
            $product->condition = $productData['condition'];
            $product->id_category_default = $productData['id_category_default'];
            $product->associations = $productData['associations'];

            $xml = $this->arrayToPrestaXml($productData, 'product');
            $response = $prestaClient->add([
                'resource' => 'products',
                'postXml' => $xml
            ]);

            $responseXml = simplexml_load_string($response);
            $this->setPrestaId((int)$responseXml->product->id);
            $this->setPrestaDate('');
            $this->dontUploadToPresta = true;
            \mkw\store::getEm()->persist($this);

            if ($doFlush) {
                \mkw\store::getEm()->flush();
            }

            \mkw\store::writelog($this->getId() . ': PrestaShop termék POST sikeres, ID: ' . $this->getPrestaId());
        } catch (\Exception $e) {
            \mkw\store::writelog($this->getId() . ': PrestaShop termék POST HIBA: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update product in PrestaShop
     */
    private function updateProductInPresta($prestaClient, $productData, $doFlush)
    {
        \mkw\store::writelog($this->getId() . ': PrestaShop termék PUT start');

        try {
            $xml = $this->arrayToPrestaXml($productData, 'product');
            $response = $prestaClient->edit([
                'resource' => 'products',
                'id' => $this->getPrestaId(),
                'putXml' => $xml
            ]);

            $this->setPrestaDate('');
            $this->dontUploadToPresta = true;
            \mkw\store::getEm()->persist($this);

            if ($doFlush) {
                \mkw\store::getEm()->flush();
            }

            \mkw\store::writelog($this->getId() . ': PrestaShop termék PUT sikeres');
        } catch (\Exception $e) {
            \mkw\store::writelog($this->getId() . ': PrestaShop termék PUT HIBA: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Upload variants to PrestaShop
     */
    private function uploadVariantsToPresta($prestaClient, $eur, $doFlush)
    {
        if (!$this->getPrestaId()) {
            return;
        }

        \mkw\store::writelog($this->getId() . ': PrestaShop változatok feltöltése kezdés');

        /** @var TermekValtozat $valtozat */
        foreach ($this->getValtozatok() as $valtozat) {
            if (!$valtozat->getPrestaId() || $valtozat->shouldUploadToPresta()) {
                $valtozat->uploadToPresta($prestaClient, $this->getPrestaId(), $eur, $doFlush);
            }
        }

        \mkw\store::writelog($this->getId() . ': PrestaShop változatok feltöltése befejezve');
    }

    /**
     * Convert array to PrestaShop XML format
     */
    private function arrayToPrestaXml($data, $rootElement)
    {
        $xml = new \SimpleXMLElement("<?xml version='1.0' encoding='UTF-8'?><prestashop><$rootElement></$rootElement></prestashop>");
        $productNode = $xml->$rootElement;

        $this->arrayToXmlRecursive($data, $productNode);

        return $xml->asXML();
    }

    /**
     * Recursively convert array to XML
     */
    private function arrayToXmlRecursive($data, $xmlNode)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                if (is_numeric($key)) {
                    $subNode = $xmlNode;
                } else {
                    $subNode = $xmlNode->addChild($key);
                }

                if (isset($value[0]) && is_array($value[0])) {
                    // Handle multilang or association arrays
                    foreach ($value as $item) {
                        if (isset($item['id']) && isset($item['value'])) {
                            // Multilang field
                            $langNode = $subNode->addChild('language', htmlspecialchars($item['value']));
                            $langNode->addAttribute('id', $item['id']);
                        } elseif (isset($item['id'])) {
                            // Association field
                            $subNode->addChild('id', $item['id']);
                        } else {
                            $this->arrayToXmlRecursive($item, $subNode);
                        }
                    }
                } else {
                    $this->arrayToXmlRecursive($value, $subNode);
                }
            } else {
                $xmlNode->addChild($key, htmlspecialchars($value));
            }
        }
    }

    /**
     * Get PrestaShop webservice client
     */
    private function getPrestaClient()
    {
        return false;
    }

    /**
     * Check if PrestaShop integration is enabled
     */
    private function isPrestaShopEnabled()
    {
        return \mkw\store::getPrestaKey() && \mkw\store::getPrestaUrl();
    }

    /**
     * Check if product should be uploaded to PrestaShop
     */
    private function shouldUploadToPresta()
    {
        // Implement logic similar to shouldUploadToWc()
        return !isset($this->dontUploadToPresta) || !$this->dontUploadToPresta;
    }

    /**
     * Get PrestaShop product ID
     */
    public function getPrestaId()
    {
        return $this->prestaid ?? null;
    }

    /**
     * Set PrestaShop product ID
     */
    public function setPrestaId($prestaid): void
    {
        $this->prestaid = $prestaid;
    }

    /**
     * Get PrestaShop upload date
     */
    public function getPrestaDate()
    {
        return $this->prestadate;
    }

    /**
     * Set PrestaShop upload date
     */
    public function setPrestaDate($prestadate): void
    {
        if ($prestadate) {
            if (is_string($prestadate)) {
                $this->prestadate = new \DateTime($prestadate);
            } else {
                $this->prestadate = $prestadate;
            }
        } else {
            $this->prestadate = new \DateTime();
        }
    }

    /**
     * Check if product is banned from PrestaShop
     */
    private function getPrestaTiltva()
    {
        return $this->prestatiltva ?? false;
    }

    /**
     * @return int
     */
    public function getUj()
    {
        return $this->uj;
    }

    /**
     * @param int $uj
     */
    public function setUj($uj): void
    {
        $this->uj = $uj;
    }


}