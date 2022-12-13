<?php

namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection;
use mkw\store;


/** @ORM\Entity(repositoryClass="Entities\BizonylatfejRepository")
 * @ORM\Table(name="bizonylatfej",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 * @Gedmo\TranslationEntity(class="Entities\BizonylatfejTranslation")
 * */
class Bizonylatfej
{

    private $duplication;
    private $kellszallitasikoltsegetszamolni = true;
    private $szallitasikoltsegbrutto;
    private $simpleedit = false;

    /**
     * @ORM\Id @ORM\Column(type="string",length=30,nullable=false)
     */
    private $id;

    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $trxid;

    /**
     * @ORM\Column(type="bigint", options={"unsigned"=true},nullable=true)
     */
    private $otpayid;

    /**
     * @ORM\Column(type="string", length=30,nullable=true)
     */
    private $otpaymsisdn;

    /**
     * @ORM\Column(type="string", length=30,nullable=true)
     */
    private $otpaympid;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $otpayresult;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $otpayresulttext;

    /**
     * @ORM\Column(type="string", length=36,nullable=true)
     */
    private $masterpasscorrelationid;

    /**
     * @ORM\Column(type="string", length=100,nullable=true)
     */
    private $masterpassbanktrxid;

    /**
     * @ORM\Column(type="string", length=100,nullable=true)
     */
    private $masterpasstrxid;

    /**
     * @ORM\Column(type="string", length=100,nullable=true)
     */
    private $foxpostbarcode;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $glsparcelid;

    /** @ORM\Column(type="text",nullable=true) */
    private $glsparcellabelurl;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $fix = false;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $mese = false;

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
     * @ORM\ManyToOne(targetEntity="Bizonylattipus", inversedBy="bizonylatfejek")
     * @ORM\JoinColumn(name="bizonylattipus_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Bizonylattipus
     */
    private $bizonylattipus;

    /** @ORM\Column(type="string",length=100,nullable=true) */
    private $bizonylatnev;

    /** @ORM\Column(type="integer") */
    private $irany;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $nyomtatva = false;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $storno = false;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $stornozott = false;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $rontott = false;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $penztmozgat;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $gyujtoszamla = false;

    /** @ORM\Column(type="date",nullable=true) */
    private $gyujtoidoszakeleje;

    /** @ORM\Column(type="date",nullable=true) */
    private $gyujtoidoszakvege;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $fizetve = false;

    /** @ORM\Column(type="string",length=255,nullable=false) */
    private $tulajnev;

    /** @ORM\Column(type="string",length=10,nullable=false) */
    private $tulajirszam;

    /** @ORM\Column(type="string",length=40,nullable=true) */
    private $tulajvaros;

    /** @ORM\Column(type="string",length=60,nullable=true) */
    private $tulajutca;

    /** @ORM\Column(type="string",length=13,nullable=false) */
    private $tulajadoszam;

    /** @ORM\Column(type="string",length=30,nullable=true) */
    private $tulajeuadoszam;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $tulajeorinr;

    /** @ORM\Column(type="string",length=30,nullable=true) */
    private $tulajjovengszam;

    /** @ORM\Column(type="string",length=30,nullable=true) */
    private $erbizonylatszam;

    /** @ORM\Column(type="text",nullable=true) */
    private $fuvarlevelszam;

    /** @ORM\Column(type="date",nullable=false) */
    private $kelt;

    /** @ORM\Column(type="date",nullable=false) */
    private $teljesites;

    /** @ORM\Column(type="date",nullable=true) */
    private $esedekesseg;

    /** @ORM\Column(type="date",nullable=true) */
    private $esedekesseg1;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $fizetendo1;

    /** @ORM\Column(type="date",nullable=true) */
    private $esedekesseg2;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $fizetendo2;

    /** @ORM\Column(type="date",nullable=true) */
    private $esedekesseg3;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $fizetendo3;

    /** @ORM\Column(type="date",nullable=true) */
    private $esedekesseg4;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $fizetendo4;

    /** @ORM\Column(type="date",nullable=true) */
    private $esedekesseg5;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $fizetendo5;

    /**
     * @ORM\ManyToOne(targetEntity="Fizmod",inversedBy="bizonylatfejek")
     * @ORM\JoinColumn(name="fizmod_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Fizmod
     */
    private $fizmod;

    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    private $fizmodnev;

    /**
     * @ORM\ManyToOne(targetEntity="Szallitasimod",inversedBy="bizonylatfejek")
     * @ORM\JoinColumn(name="szallitasimod_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Szallitasimod
     */
    private $szallitasimod;

    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    private $szallitasimodnev;

    /** @ORM\Column(type="integer",nullable=true) */
    private $szallitasiido;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $netto;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $afa;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $brutto;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $fizetendo;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $kerkul;

    /**
     * @ORM\ManyToOne(targetEntity="Valutanem",inversedBy="bizonylatfejek")
     * @ORM\JoinColumn(name="valutanem_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Valutanem
     */
    private $valutanem;

    /** @ORM\Column(type="string",length=6,nullable=true) */
    private $valutanemnev;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $nettohuf;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $afahuf;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $bruttohuf;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $arfolyam;

    /**
     * @ORM\ManyToOne(targetEntity="Partner",inversedBy="bizonylatfejek")
     * @ORM\JoinColumn(name="partner_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Partner
     */
    private $partner;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $partnernev;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $partnervezeteknev = '';

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $partnerkeresztnev = '';

    /** @ORM\Column(type="string",length=13,nullable=true) */
    private $partneradoszam;

    /** @ORM\Column(type="string",length=30,nullable=true) */
    private $partnereuadoszam;

    /** @ORM\Column(type="string",length=50,nullable=true) */
    private $partnerthirdadoszam = '';

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $partnerszamlaegyeb = '';

    /** @ORM\Column(type="string",length=20,nullable=true) */
    private $partnermukengszam;

    /** @ORM\Column(type="string",length=20,nullable=true) */
    private $partnerjovengszam;

    /** @ORM\Column(type="string",length=20,nullable=true) */
    private $partnerostermszam;

    /** @ORM\Column(type="string",length=20,nullable=true) */
    private $partnervalligszam;

    /** @ORM\Column(type="string",length=20,nullable=true) */
    private $partnerfvmszam;

    /** @ORM\Column(type="string",length=20,nullable=true) */
    private $partnercjszam;

    /** @ORM\Column(type="string",length=20,nullable=true) */
    private $partnerstatszamjel;

    /**
     * @ORM\ManyToOne(targetEntity="Orszag")
     * @ORM\JoinColumn(name="partnerorszag_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $partnerorszag;

    /** @ORM\Column(type="string",length=255, nullable=true) */
    private $partnerorszagnev;

    /** @ORM\Column(type="string",length=5, nullable=true) */
    private $partnerorszagiso3166;

    /** @ORM\Column(type="string",length=10,nullable=true) */
    private $partnerirszam;

    /** @ORM\Column(type="string",length=40,nullable=true) */
    private $partnervaros;

    /** @ORM\Column(type="string",length=60,nullable=true) */
    private $partnerutca;

    /** @ORM\Column(type="string",length=40,nullable=true) */
    private $partnerhazszam = '';

    /** @ORM\Column(type="string",length=10,nullable=true) */
    private $partnerlirszam;

    /** @ORM\Column(type="string",length=40,nullable=true) */
    private $partnerlvaros;

    /** @ORM\Column(type="string",length=60,nullable=true) */
    private $partnerlutca;

    /** @ORM\Column(type="string",length=40,nullable=true) */
    private $partnerlhazszam = '';

    /** @ORM\Column(type="string",length=100,nullable=true) */
    private $partneremail = '';

    /** @ORM\Column(type="string",length=40,nullable=true) */
    private $partnertelefon = '';

    /** @ORM\Column(type="boolean",nullable=false) */
    private $partnerfeketelistas = false;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $partnerfeketelistaok;

    /** @ORM\Column(type="integer",nullable=true) */
    private $partnervatstatus = 0;

    /**
     * @ORM\ManyToOne(targetEntity="Bankszamla",inversedBy="bizonylatfejek")
     * @ORM\JoinColumn(name="bankszamla_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Bankszamla
     */
    private $bankszamla;

    /** @ORM\Column(type="string",length=50,nullable=true) */
    private $tulajbanknev;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $tulajbankszamlaszam;

    /** @ORM\Column(type="string",length=20,nullable=true) */
    private $tulajswift;

    /** @ORM\Column(type="string",length=20,nullable=true) */
    private $tulajiban;

    /**
     * @ORM\ManyToOne(targetEntity="Uzletkoto",inversedBy="bizonylatfejek")
     * @ORM\JoinColumn(name="uzletkoto_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Uzletkoto
     */
    private $uzletkoto;

    /** @ORM\Column(type="string",length=50,nullable=true) */
    private $uzletkotonev;

    /** @ORM\Column(type="string",length=100,nullable=true) */
    private $uzletkotoemail;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $uzletkotojutalek;

    /**
     * @ORM\ManyToOne(targetEntity="Uzletkoto",inversedBy="belsobizonylatfejek")
     * @ORM\JoinColumn(name="belsouzletkoto_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Uzletkoto
     */
    private $belsouzletkoto;

    /** @ORM\Column(type="string",length=50,nullable=true) */
    private $belsouzletkotonev;

    /** @ORM\Column(type="string",length=100,nullable=true) */
    private $belsouzletkotoemail;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $belsouzletkotojutalek;

    /**
     * @ORM\ManyToOne(targetEntity="Raktar",inversedBy="bizonylatfejek")
     * @ORM\JoinColumn(name="raktar_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Raktar
     */
    private $raktar;

    /** @ORM\Column(type="string",length=50,nullable=true) */
    private $raktarnev;

    /** @ORM\OneToMany(targetEntity="Bizonylattetel", mappedBy="bizonylatfej",cascade={"persist"}) */
    private $bizonylattetelek;

    /** @ORM\Column(type="text",nullable=true) */
    private $megjegyzes;

    /** @ORM\Column(type="text",nullable=true) */
    private $belsomegjegyzes;

    /** @ORM\Column(type="text",nullable=true) */
    private $webshopmessage;

    /** @ORM\Column(type="text",nullable=true) */
    private $couriermessage;

    /** @ORM\Column(type="text",nullable=true) */
    private $sysmegjegyzes;

    /** @ORM\Column(type="date",nullable=true) */
    private $hatarido;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $szallnev = '';

    /** @ORM\Column(type="string",length=10,nullable=true) */
    private $szallirszam = '';

    /** @ORM\Column(type="string",length=40,nullable=true) */
    private $szallvaros = '';

    /** @ORM\Column(type="string",length=60,nullable=true) */
    private $szallutca = '';

    /** @ORM\Column(type="string",length=40,nullable=true) */
    private $szallhazszam = '';

    /** @ORM\Column(type="string",length=32,nullable=true) */
    private $ip;

    /** @ORM\Column(type="text",nullable=true) */
    private $referrer;

    /**
     * @ORM\ManyToOne(targetEntity="Bizonylatstatusz",inversedBy="bizonylatfejek")
     * @ORM\JoinColumn(name="bizonylatstatusz_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Bizonylatstatusz
     */
    private $bizonylatstatusz;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $bizonylatstatusznev;

    /** @ORM\Column(type="string",length=255, nullable=true) */
    private $bizonylatstatuszcsoport;


    /**
     * @ORM\ManyToOne(targetEntity="Bizonylatfej",inversedBy="szulobizonylatfejek")
     * @ORM\JoinColumn(name="parbizonylatfej_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Bizonylatfej
     */
    private $parbizonylatfej;

    /** @ORM\OneToMany(targetEntity="Bizonylatfej", mappedBy="parbizonylatfej",cascade={"persist"}) */
    private $szulobizonylatfejek;

    /** @ORM\Column(type="integer") */
    private $partnerszamlatipus;

    /**
     * @ORM\ManyToOne(targetEntity="CsomagTerminal",inversedBy="bizonylatfejek")
     * @ORM\JoinColumn(name="csomagterminal_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\CsomagTerminal
     */
    private $csomagterminal;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $traceurl;

    /** @ORM\Column(type="string",length=10,nullable=true) */
    private $bizonylatnyelv;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $reportfile;

    /** @ORM\OneToMany(targetEntity="Folyoszamla", mappedBy="bizonylatfej",cascade={"persist"}) */
    private $folyoszamlak;

    /** @ORM\Column(type="integer",nullable=true) */
    private $regmode;

    /** @ORM\Column(type="integer",nullable=true) */
    private $stornotipus;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $tulajkisadozo = false;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $tulajegyenivallalkozo = false;

    /** @ORM\Column(type="string",length=100,nullable=true) */
    private $tulajevnev;

    /** @ORM\Column(type="string",length=100,nullable=true) */
    private $tulajevnyilvszam;

    /** @ORM\Column(type="string",length=30,nullable=true) */
    private $kupon;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $fakekintlevoseg = false;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $fakekifizetve = false;

    /** @ORM\Column(type="date",nullable=true) */
    private $fakekifizetesdatum;

    /** @ORM\Column(type="boolean") */
    private $partnerktdatvallal = false;

    /** @ORM\Column(type="boolean") */
    private $partnerktdatalany = false;

    /** @ORM\Column(type="string",length=20,nullable=true) */
    private $partnerktdszerzszam;

    /** @Gedmo\Locale */
    protected $locale;

    /** @ORM\OneToMany(targetEntity="BizonylatfejTranslation", mappedBy="object", cascade={"persist", "remove"}) */
    private $translations;

    /**
     * @ORM\ManyToOne(targetEntity="Dolgozo")
     * @ORM\JoinColumn(name="felhasznalo_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Dolgozo
     */
    private $felhasznalo;

    /** @ORM\Column(type="string",length=255, nullable=true) */
    private $felhasznalonev;

    /** @ORM\Column(type="string",length=30, nullable=true) */
    private $szepkartyaszam;

    /** @ORM\Column(type="string",length=255, nullable=true) */
    private $szepkartyanev;

    /** @ORM\Column(type="date",nullable=true) */
    private $szepkartyaervenyesseg;

    /** @ORM\Column(type="integer", nullable=true) */
    private $szepkartyatipus;

    /** @ORM\Column(type="boolean") */
    private $szepkartyakifizetve = false;

    /** @ORM\Column(type="boolean") */
    private $nincspenzmozgas = true;

    /** @ORM\Column(type="integer",nullable=true) */
    private $emagid;

    /** @ORM\Column(type="string", length=100,nullable=true) */
    private $programnev;

    /** @ORM\Column(type="string", length=100,nullable=true) */
    private $barionpaymentid;

    /** @ORM\Column(type="string", length=100,nullable=true) */
    private $barionpaymentstatus;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $navbekuldendo = false;

    /** @ORM\Column(type="string", length=30,nullable=true) */
    private $naveredmeny;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $forditottadozas = false;

    /** @ORM\OneToMany(targetEntity="BizonylatDok", mappedBy="bizonylat", cascade={"persist", "remove"}) */
    private $bizonylatdokok;

    /** @ORM\Column(type="string", length=32,nullable=true) */
    private $termekertekelesid;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $termekertekeleskikuldve = false;

    public function __toString()
    {
        return (string)$this->id;
    }

    public function calcNAVBekuldendo()
    {
        if (!$this->isNavbekuldve()) {
            $nov = store::getParameter(\mkw\consts::NAVOnlineVersion, '2_0');
            if ($nov == '2_0') {
                $this->setNavbekuldendo(
                    $this->getBizonylattipusNavbekuldendo()
                    && $this->getPartneradoszam()
                    && $this->getPartner()->isDefaultOrszag()
                );
            } else {
                $this->setNavbekuldendo($this->getBizonylattipusNavbekuldendo());
            }
        }
    }

    public function calcOsszesen()
    {
        $mincimlet = 0;
        $kerekit = false;
        $defakerekit = false;
        if ($this->getValutanem()) {
            $mincimlet = $this->getValutanem()->getMincimlet();
            $kerekit = $this->getValutanem()->getKerekit();
        }
        $defavaluta = \mkw\store::getEm()->getRepository('Entities\Valutanem')->find(\mkw\store::getParameter(\mkw\consts::Valutanem));
        if ($defavaluta) {
            $defakerekit = $defavaluta->getKerekit();
        }
        $fizmodtipus = 'B';
        $fizmod = $this->getFizmod();
        if ($fizmod) {
            $fizmodtipus = $fizmod->getTipus();
        }
        $this->netto = 0;
        $this->afa = 0;
        $this->brutto = 0;
        $this->nettohuf = 0;
        $this->afahuf = 0;
        $this->bruttohuf = 0;
        foreach ($this->bizonylattetelek as $bt) {
            $this->netto += $bt->getNetto();
            $this->afa += $bt->getAfaertek();
            //$this->brutto += $bt->getBrutto();
            $this->nettohuf += $bt->getNettohuf();
            $this->afahuf += $bt->getAfaertekhuf();
            //$this->bruttohuf += $bt->getBruttohuf();
        }
        if ($kerekit) {
            $this->brutto = round($this->netto + $this->afa);
        } else {
            $this->brutto = $this->netto + $this->afa;
        }
        if ($mincimlet && ($fizmodtipus == 'P')) {
            $valosbrutto = $this->brutto;
            $this->brutto = \mkw\store::kerekit($this->brutto, $mincimlet);
            $this->kerkul = $this->brutto - $valosbrutto;
        }
        $this->fizetendo = $this->brutto;
        if ($defakerekit) {
            $this->bruttohuf = round($this->nettohuf + $this->afahuf);
        } else {
            $this->bruttohuf = $this->nettohuf + $this->afahuf;
        }
    }

    public function calcRugalmasFizmod()
    {
        $regifizmod = $this->getFizmod();
        if ($regifizmod && $regifizmod->getRugalmas()) {
            $fh = \mkw\store::getEm()->getRepository('Entities\FizmodHatar')->getByValutanemHatar($this->getValutanem(), $this->getFizetendo());
            if ($fh) {
                $this->setFizmod($fh->getFizmod());
            }
        }
    }

    public function calcOsztottFizetendo()
    {
        // superzone osztott fizetendo
        if (\mkw\store::isOsztottFizmod()) {
            $this->setEsedekesseg1();
            $this->setFizetendo1(0);
            $this->setEsedekesseg2();
            $this->setFizetendo2(0);
            $this->setEsedekesseg3();
            $this->setFizetendo3(0);
            $this->setEsedekesseg4();
            $this->setFizetendo4(0);
            $this->setEsedekesseg5();
            $this->setFizetendo5(0);
            $eddigi = 0;
            $fizmod = $this->getFizmod();
            $kelt = new \DateTimeImmutable(\mkw\store::convDate($this->getKeltStr()));
            if ($fizmod->getOsztotthaladek1() && ($fizmod->getOsztottszazalek1() * 1 > 0)) {
                $this->setEsedekesseg1($kelt->add(new \DateInterval('P' . $fizmod->getOsztotthaladek1() . 'D')));
                $fiz = round($this->fizetendo * $fizmod->getOsztottszazalek1() / 100, 2);
                $this->setFizetendo1($fiz);
                $eddigi = $eddigi + $fiz;
            }
            if ($fizmod->getOsztotthaladek2()) {
                $this->setEsedekesseg2($kelt->add(new \DateInterval('P' . $fizmod->getOsztotthaladek2() . 'D')));
                if ($fizmod->getOsztottszazalek2() * 1 > 0) {
                    $fiz = round($this->fizetendo * $fizmod->getOsztottszazalek2() / 100, 2);
                    $this->setFizetendo2($fiz);
                    $eddigi = $eddigi + $fiz;
                } else {
                    $this->setFizetendo2($this->fizetendo - $eddigi);
                    $eddigi = $this->fizetendo;
                }
            }
            if ($fizmod->getOsztotthaladek3()) {
                $this->setEsedekesseg3($kelt->add(new \DateInterval('P' . $fizmod->getOsztotthaladek3() . 'D')));
                if ($fizmod->getOsztottszazalek3() * 1 > 0) {
                    $fiz = round($this->fizetendo * $fizmod->getOsztottszazalek3() / 100, 2);
                    $this->setFizetendo3($fiz);
                    $eddigi = $eddigi + $fiz;
                } else {
                    $this->setFizetendo3($this->fizetendo - $eddigi);
                    $eddigi = $this->fizetendo;
                }
            }
            if ($fizmod->getOsztotthaladek4()) {
                $this->setEsedekesseg4($kelt->add(new \DateInterval('P' . $fizmod->getOsztotthaladek4() . 'D')));
                if ($fizmod->getOsztottszazalek4() * 1 > 0) {
                    $fiz = round($this->fizetendo * $fizmod->getOsztottszazalek4() / 100, 2);
                    $this->setFizetendo4($fiz);
                    $eddigi = $eddigi + $fiz;
                } else {
                    $this->setFizetendo4($this->fizetendo - $eddigi);
                    $eddigi = $this->fizetendo;
                }
            }
            if ($fizmod->getOsztotthaladek5()) {
                $this->setEsedekesseg5($kelt->add(new \DateInterval('P' . $fizmod->getOsztotthaladek5() . 'D')));
                $this->setFizetendo5($this->fizetendo - $eddigi);
            }
        }
    }

    public function __construct()
    {
        $this->szulobizonylatfejek = new \Doctrine\Common\Collections\ArrayCollection();
        $this->bizonylattetelek = new \Doctrine\Common\Collections\ArrayCollection();
        $this->folyoszamlak = new \Doctrine\Common\Collections\ArrayCollection();
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->bizonylatdokok = new \Doctrine\Common\Collections\ArrayCollection();
        $this->setPersistentData();
    }

    public function getEgyenleg()
    {
        if ($this->getStorno() || $this->getStornozott() || $this->getRontott() || !$this->getPenztmozgat()) {
            return 0;
        }
        return \mkw\store::getEm()->getRepository('Entities\Folyoszamla')->getSumByHivatkozottBizonylat($this->getId());
    }

    public function getOsztottEgyenleg()
    {
        if ($this->getStorno() || $this->getStornozott() || $this->getRontott() || !$this->getPenztmozgat()) {
            return 0;
        }
        return \mkw\store::getEm()->getRepository('Entities\Folyoszamla')->getSumByHivatkozottBizonylatDatum($this->getId());
    }

    public function getKedvezmenyCount()
    {
        /** @var \Entities\Bizonylattetel $item */
        $db = 0;
        foreach ($this->bizonylattetelek as $item) {
            $f = $item->getKedvezmeny() * 1;
            if ((float)$f !== 0.0) {
                $db++;
            }
        }
        return $db;
    }

    /**
     * @param \Entities\Emailtemplate $emailtpl
     * @param \Entities\Bizonylatfej|null $bf
     * @param bool|true $topartner
     */
    public function sendStatuszEmail($emailtpl, $bf = null, $topartner = true)
    {
        if (!$bf) {
            $bf = $this;
        }
        if ($emailtpl) {
            $tpldata = $bf->toLista();
            $subject = \mkw\store::getTemplateFactory()->createMainView('string:' . $emailtpl->getTargy());
            $subject->setVar('rendeles', $tpldata);
            $body = \mkw\store::getTemplateFactory()->createMainView('string:' . str_replace('&#39;', '\'', html_entity_decode($emailtpl->getHTMLSzoveg())));
            $body->setVar('rendeles', $tpldata);
            if (\mkw\store::getConfigValue('developer')) {
                \mkw\store::writelog($subject->getTemplateResult(), 'bizstatuszemail.html');
                \mkw\store::writelog($body->getTemplateResult(), 'bizstatuszemail.html');
            } else {
                $mailer = \mkw\store::getMailer();
                if ($topartner) {
                    $mailer->addTo($bf->getPartneremail());
                    $m = explode(',', $bf->getUzletkotoemail());
                    $mailer->addTo($m);
                }
                $mailer->setSubject($subject->getTemplateResult());
                $mailer->setMessage($body->getTemplateResult());
                if ($bf->getBizonylatstatuszId() == \mkw\store::getParameter(\mkw\consts::BizonylatStatuszFuggoben)) {
                    $mailer->send();
                } else {
                    $mailer->send(true);
                }
            }
        }
    }

    /**
     * @param \Entities\Emailtemplate $emailtpl
     * @param \Entities\Bizonylatfej|null $bf
     * @param bool|true $topartner
     */
    public function sendEmailSablon($emailtpl, $bf = null, $topartner = true)
    {
        if (!$bf) {
            $bf = $this;
        }
        if ($emailtpl) {
            $tpldata = $bf->toLista();
            $subject = \mkw\store::getTemplateFactory()->createMainView('string:' . $emailtpl->getTargy());
            $subject->setVar('rendeles', $tpldata);
            $body = \mkw\store::getTemplateFactory()->createMainView('string:' . str_replace('&#39;', '\'', html_entity_decode($emailtpl->getHTMLSzoveg())));
            $body->setVar('rendeles', $tpldata);
            $body->setVar('szktgtermek', \mkw\store::getParameter(\mkw\consts::SzallitasiKtgTermek));
            $body->setVar('mainurl', \mkw\store::getConfigValue('mainurl'));
            if (\mkw\store::getConfigValue('developer')) {
                \mkw\store::writelog($subject->getTemplateResult(), 'bizstatuszemail.html');
                \mkw\store::writelog($body->getTemplateResult(), 'bizstatuszemail.html');
            } else {
                $mailer = \mkw\store::getMailer();
                if ($topartner) {
                    $mailer->addTo($bf->getPartneremail());
                    $m = explode(',', $bf->getUzletkotoemail());
                    $mailer->addTo($m);
                }
                if ($emailtpl->isAszfcsatolaskell()) {
                    $mailer->setAttachment('aszf.pdf');
                }
                $mailer->setSubject($subject->getTemplateResult());
                $mailer->setMessage($body->getTemplateResult());
                $mailer->send();
            }
        }
    }

    public function toFoxpostv2API()
    {
        $fields = array(
            'recipientName' => (\mkw\store::getConfigValue('developer') ? 'teszt' : $this->getSzallnev()),
            'recipientPhone' => $this->getPartnertelefon(),
            'recipientEmail' => $this->getPartneremail(),
            'refCode' => $this->getId(),
            'size' => 'M'
        );
        if ($this->getCsomagterminalIdegenId()) {
            $fields['destination'] = $this->getCsomagterminalIdegenId();
        } else {
            $fields['recipientZip'] = $this->getSzallirszam();
            $fields['recipientCity'] = $this->getSzallvaros();
            $fields['recipientAddress'] = $this->getSzallutca();
        }
        if ($this->getCouriermessage()) {
            $fields['deliveryNote'] = mb_substr($this->getCouriermessage(), 0, 50);
        }
        if (\mkw\store::isUtanvetFizmod($this->getFizmodId())) {
            $fields['cod'] = (int)$this->getBrutto();
        }
        return $fields;
    }

    public function toGLSAPI()
    {
        if (\mkw\store::isUtanvetFizmod($this->getFizmodId())) {
            $codamount = $this->getBruttohuf();
            $szamlaszam = false;
            /** @var Bizonylatfej $gy */
            foreach ($this->szulobizonylatfejek as $gy) {
                if (!$szamlaszam && $gy->getBizonylattipusId() == 'szamla') {
                    $szamlaszam = $gy->getId();
                }
            }
            if ($szamlaszam) {
                $codref = $szamlaszam;
            } else {
                $codref = $this->getId();
            }
        } else {
            $codamount = 0;
            $codref = $this->getId();
        }
        $fdspar = new \stdClass();
        $fdspar->StringValue = $this->getPartneremail();
        $sm2par = new \stdClass();
        $sm2par->StringValue = $this->getPartnertelefon();

        $result = [
            'ClientNumber' => \mkw\store::getParameter(\mkw\consts::GLSClientNumber),
            'ClientReference' => $this->getId(),
            'CODAmount' => $codamount,
            'CODReference' => $codref,
            'Content' => $this->getCouriermessage(),
            'Count' => 1,
            'DeliveryAddress' => [
                'Name' => $this->getSzallnev(),
                'Street' => $this->getSzallutca(),
                'City' => $this->getSzallvaros(),
                'ZipCode' => $this->getSzallirszam(),
                'CountryIsoCode' => 'HU',
                'ContactName' => $this->getPartnernev(),
                'ContactPhone' => $this->getPartnertelefon(),
                'ContactEmail' => $this->getPartneremail()
            ],
            'PickupAddress' => [
                'Name' => $this->getTulajnev(),
                'Street' => $this->getTulajutca(),
                'City' => $this->getTulajvaros(),
                'ZipCode' => $this->getTulajirszam(),
                'CountryIsoCode' => 'HU',
                'ContactName' => \mkw\store::getParameter(\mkw\consts::TulajKontaktNev),
                'ContactPhone' => \mkw\store::getParameter(\mkw\consts::TulajKontaktTelefon),
                'ContactEmail' => \mkw\store::getParameter(\mkw\consts::TulajKontaktEmail)
            ],
            'PickupDate' => "/Date(" . (time() * 1000) . ")/",
            'ServiceList' => [
                [
                    'Code' => 'SM2',
                    'SM2Parameter' => $sm2par
                ]
            ]
        ];
        if (\mkw\store::isGLSSzallitasimod($this->getSzallitasimodId())) {
            $psdpar = new \stdClass();
            $psdpar->StringValue = $this->getCsomagterminalIdegenId();
            $result['ServiceList'][] = [
                'Code' => 'PSD',
                'PSDParameter' => $psdpar
            ];
        } else {
            $result['ServiceList'][] = [
                'Code' => 'FDS',
                'FDSParameter' => $fdspar
            ];
        }
        return $result;
    }

    public function toBarionModel()
    {
        require_once "busvendor/Barion/library/BarionClient.php";

        $trans = new \PaymentTransactionModel();
        $trans->Payee = \mkw\store::getParameter(\mkw\consts::BarionPayeeEmail);
        $trans->POSTransactionId = $this->getId();
        $trans->Total = number_format($this->getBrutto(), 2, '.', '');
        /** @var \Entities\Bizonylattetel $bt */
        foreach ($this->bizonylattetelek as $bt) {
            $trans->AddItem($bt->toBarionModel());
        }
        $ppr = new \PreparePaymentRequestModel();
        $ppr->GuestCheckout = true;
        $ppr->PaymentType = \PaymentType::Immediate;
        $ppr->FundingSources = array(\FundingSourceType::All);
        $ppr->PaymentRequestId = $this->getId();
        $ppr->PayerHint = $this->getPartneremail();
        switch ($this->getBizonylatnyelv()) {
            case 'en_us':
                $ppr->Locale = \UILocale::EN;
                break;
            case 'hu_hu':
                $ppr->Locale = \UILocale::HU;
                break;
            default:
                $partner = $this->getPartner();
                if (!\mkw\store::getIntParameter(\mkw\consts::Magyarorszag)) {
                    $ppr->Locale = \UILocale::HU;
                } else {
                    if ($partner->getOrszagId() === \mkw\store::getIntParameter(\mkw\consts::Magyarorszag)) {
                        $ppr->Locale = \UILocale::HU;
                    } else {
                        $ppr->Locale = \UILocale::EN;
                    }
                }
                break;
        }
        $ppr->OrderNumber = $this->getId();
        $ppr->Currency = $this->getValutanemnev();
        $ppr->AddTransaction($trans);

        return $ppr;
    }

    public function toLista()
    {
        $ret = array();
        $ret['id'] = $this->getId();
        $ret['lastmodstr'] = $this->getLastmodStr();
        $ret['createdstr'] = $this->getCreatedStr();
        $ret['updatedby'] = $this->getUpdatedbyNev();
        $ret['createdby'] = $this->getCreatedbyNev();
        $ret['kedvezmenycount'] = $this->getKedvezmenyCount();
        $ret['editprinted'] = $this->getBizonylattipus() ? $this->getBizonylattipus()->getEditprinted() : false;
        $ret['bizonylatnev'] = $this->getBizonylatnev();
        $ret['programnev'] = $this->getProgramnev();
        $ret['nyomtatva'] = $this->getNyomtatva();
        $ret['raktarnev'] = $this->getRaktarnev();
        $ret['kelt'] = $this->getKeltStr();
        $ret['keltstr'] = $this->getKeltStr();
        $ret['teljesitesstr'] = $this->getTeljesitesStr();
        $ret['esedekessegstr'] = $this->getEsedekessegStr();
        $ret['hataridostr'] = $this->getHataridoStr();
        $ret['esedekesseg1str'] = $this->getEsedekesseg1Str();
        $ret['fizetendo1'] = $this->getFizetendo1();
        $ret['esedekesseg2str'] = $this->getEsedekesseg2Str();
        $ret['fizetendo2'] = $this->getFizetendo2();
        $ret['esedekesseg3str'] = $this->getEsedekesseg3Str();
        $ret['fizetendo3'] = $this->getFizetendo3();
        $ret['esedekesseg4str'] = $this->getEsedekesseg4Str();
        $ret['fizetendo4'] = $this->getFizetendo4();
        $ret['esedekesseg5str'] = $this->getEsedekesseg5Str();
        $ret['fizetendo5'] = $this->getFizetendo5();
        $ret['tulajnev'] = $this->getTulajnev();
        $ret['tulajirszam'] = $this->getTulajirszam();
        $ret['tulajvaros'] = $this->getTulajvaros();
        $ret['tulajutca'] = $this->getTulajutca();
        $ret['tulajadoszam'] = $this->getTulajadoszam();
        $ret['tulajeuadoszam'] = $this->getTulajeuadoszam();
        $ret['tulajeorinr'] = $this->getTulajeorinr();
        $ret['tulajjovengszam'] = $this->getTulajjovengszam();
        $ret['ertek'] = $this->getBrutto();
        $ret['nettohuf'] = $this->getNettohuf();
        $ret['afahuf'] = $this->getAfahuf();
        $ret['bruttohuf'] = $this->getBruttohuf();
        $ret['netto'] = $this->getNetto();
        $ret['afa'] = $this->getAfa();
        $ret['brutto'] = $this->getBrutto();
        $ret['fizetendo'] = $this->getFizetendo();
        $ret['fizetendokiirva'] = \mkw\store::Num2Text($this->getFizetendo());
        $ret['fizmodnev'] = $this->getFizmodnev();
        $ret['szallitasimodnev'] = $this->getSzallitasimodnev();
        $ret['tulajbanknev'] = $this->getTulajbanknev();
        $ret['tulajbankszamlaszam'] = $this->getTulajbankszamlaszam();
        $ret['tulajiban'] = $this->getTulajiban();
        $ret['tulajswift'] = $this->getTulajswift();
        $ret['tulajkisadozo'] = $this->getTulajkisadozo();
        $ret['tulajegyenivallalkozo'] = $this->getTulajegyenivallalkozo();
        $ret['tulajevnyilvszam'] = $this->getTulajevnyilvszam();
        $ret['tulajevnev'] = $this->getTulajevnev();
        $ret['partnerid'] = $this->getPartnerId();
        $ret['partneremail'] = $this->getPartneremail();
        $ret['partnertelefon'] = $this->getPartnertelefon();
        $ret['partnerkeresztnev'] = $this->getPartnerkeresztnev();
        $ret['partnervezeteknev'] = $this->getPartnervezeteknev();
        $ret['partnerfeketelistas'] = $this->getPartnerfeketelistas();
        $ret['partnerfeketelistaok'] = $this->getPartnerfeketelistaok();
        $ret['partnerorszag'] = $this->getPartnerorszagnev();
        $ret['szamlanev'] = $this->getPartnernev();
        $ret['szamlairszam'] = $this->getPartnerirszam();
        $ret['szamlavaros'] = $this->getPartnervaros();
        $ret['szamlautca'] = $this->getPartnerutca();
        $ret['szamlahazszam'] = $this->getPartnerhazszam();
        $ret['telefon'] = $this->getPartnertelefon();
        $ret['szallnev'] = $this->getSzallnev();
        $ret['szallirszam'] = $this->getSzallirszam();
        $ret['szallvaros'] = $this->getSzallvaros();
        $ret['szallutca'] = $this->getSzallutca();
        $ret['szallhazszam'] = $this->getSzallhazszam();
        $ret['szallitasiido'] = $this->getSzallitasiido();
        $ret['szallitasiidodatum'] = $this->getSzallitasiidoDatumStr();
        $ret['adoszam'] = $this->getPartneradoszam();
        $ret['euadoszam'] = $this->getPartnereuadoszam();
        $ret['thirdadoszam'] = $this->getPartnerthirdadoszam();
        $ret['partneradoszam'] = $this->getPartneradoszam();
        $ret['partnereuadoszam'] = $this->getPartnereuadoszam();
        $ret['partnerthirdadoszam'] = $this->getPartnerthirdadoszam();
        $ret['partnerszamlaegyeb'] = $this->getPartnerszamlaegyeb();
        $ret['partnervatstatus'] = $this->getPartnervatstatus();
        $ret['webshopmessage'] = $this->getWebshopmessage();
        $ret['couriermessage'] = $this->getCouriermessage();
        $ret['megjegyzes'] = $this->getMegjegyzes();
        $ret['sysmegjegyzes'] = $this->getSysmegjegyzes();
        $ret['allapotnev'] = $this->getBizonylatstatusznev();
        $ret['fuvarlevelszam'] = $this->getFuvarlevelszam();
        $ret['foxpostbarcode'] = $this->getFoxpostBarcode();
        if (\mkw\store::isFoxpostSzallitasimod($this->getSzallitasimodId())) {
            $ret['csomagkovetolink'] = 'https://www.foxpost.hu/csomagkovetes/?code=' . $this->getFuvarlevelszam();
        }
        $ret['erbizonylatszam'] = $this->getErbizonylatszam();
        $ret['valutanemnev'] = $this->getValutanemnev();
        $ret['valutasszamla'] = $this->getValutanemId() != \mkw\store::getParameter(\mkw\consts::Valutanem, 0);
        $ret['arfolyam'] = $this->getArfolyam();
        $ret['partnerszamlatipus'] = $this->getPartnerSzamlatipus();
        $ret['uzletkotonev'] = $this->getUzletkotonev();
        $ret['uzletkotoemail'] = $this->getUzletkotoemail();
        $ret['uzletkotojutalek'] = $this->getUzletkotojutalek();
        $ret['belsouzletkotonev'] = $this->getBelsouzletkotonev();
        $ret['belsouzletkotoemail'] = $this->getBelsouzletkotoemail();
        $ret['belsouzletkotojutalek'] = $this->getBelsouzletkotojutalek();
        $ret['stornotipus'] = $this->getStornotipus();
        $ret['storno'] = $this->getStorno();
        $ret['stornozott'] = $this->getStornozott();
        $ret['rontott'] = $this->getRontott();
        $ret['kupon'] = $this->getKupon();
        $ret['fakekintlevoseg'] = $this->getFakekintlevoseg();
        $ret['fakekifizetve'] = $this->getFakekifizetve();
        $ret['partnerktdatalany'] = $this->getPartnerktdatalany();
        $ret['partnerktdatvallal'] = $this->getPartnerktdatvallal();
        $ret['partnerktdszerzszam'] = $this->getPartnerktdszerzszam();
        $ret['szepkartyanev'] = $this->getSzepkartyanev();
        $ret['szepkartyaszam'] = $this->getSzepkartyaszam();
        $ret['szepkartyaervenyesseg'] = $this->getSzepkartyaervenyessegStr();
        $ret['szepkartyatipus'] = $this->getSzepkartyatipusNev();
        $ret['szepkartyakifizetve'] = $this->getSzepkartyakifizetve();
        $ret['barionpaymentstatus'] = $this->getBarionpaymentstatus();
        $ret['termekertekelesid'] = $this->getTermekertekelesid();
        $ret['termekertekelesurl'] = '/termekertekeles?b=' . $this->getId() . '&id=' . $this->getTermekertekelesid();
        $ret['termekertekeleskikuldve'] = $this->isTermekertekeleskikuldve();
        if (\mkw\store::getConfigValue('admin', false)) {
            $ret['printurl'] = \mkw\store::getRouter()->generate('admin' . $this->getBizonylattipusId() . 'fejprint', false, array(), array(
                'id' => $this->getId()
            ));
            if (!$this->getNyomtatva()) {
                $ret['editurl'] = \mkw\store::getRouter()->generate('admin' . $this->getBizonylattipusId() . 'fejviewkarb', false, array(), array(
                    'id' => $this->getId(),
                    'oper' => 'edit'
                ));
            } else {
                $ret['editurl'] = $ret['printurl'];
            }
        }
        $ret['foxpost'] = false;
        $ret['gls'] = false;
        if ($this->csomagterminal) {
            switch ($this->csomagterminal->getTipus()) {
                case 'foxpost':
                    $ret['foxpost'] = true;
                    $ret['foxpostterminal']['nev'] = $this->csomagterminal->getNev();
                    $ret['foxpostterminal']['cim'] = $this->csomagterminal->getCim();
                    $ret['foxpostterminal']['findme'] = $this->csomagterminal->getFindme();
                    $ret['foxpostterminal']['nyitva'] = $this->csomagterminal->getNyitva();
                    break;
                case 'gls':
                    $ret['gls'] = true;
                    $ret['glspont']['nev'] = $this->csomagterminal->getNev();
                    $ret['glspont']['cim'] = $this->csomagterminal->getCsoport() . ' ' . $this->csomagterminal->getCim();
                    $ret['glspont']['findme'] = $this->csomagterminal->getFindme();
                    break;
            }
        }
        $ret['vanmitertekelni'] = false;
        $tetellist = array();
        /** @var Bizonylattetel $tetel */
        foreach ($this->bizonylattetelek as $tetel) {
            $_x = $tetel->toLista();
            if (!$_x['marertekelt'] && !\mkw\store::isSzallitasiKtgTermek($tetel->getTermekId())) {
                $ret['vanmitertekelni'] = true;
            }
            $tetellist[] = $_x;
        }
        if ($this->getBizonylatstatusz() && $this->getBizonylatstatusz()->getNemertekelheto()) {
            $ret['vanmitertekelni'] = false;
        }
        switch (true) {
            case \mkw\store::isSuperzoneB2B():
                $s = \mkw\store::getParameter(\mkw\consts::ValtozatSorrend);
                $rendezendo = \mkw\store::getParameter(\mkw\consts::RendezendoValtozat);
                $sorrend = explode(',', $s);
                $a = $tetellist;
                uasort($a, function ($e, $f) use ($sorrend, $rendezendo) {
                    if ($e['valtadattipus1id'] == $rendezendo) {
                        $ertek = $e['valtertek1'];
                        $eszin = $e['valtertek2'];
                    } elseif ($e['valtadattipus2id'] == $rendezendo) {
                        $ertek = $e['valtertek2'];
                        $eszin = $e['valtertek1'];
                    } else {
                        $ertek = false;
                        $eszin = false;
                    }
                    $ve = array_search($ertek, $sorrend);
                    if ($ve === false) {
                        $ve = 0;
                    }
                    $ve = str_pad($e['cikkszam'], 50, ' ', STR_PAD_RIGHT)
                        . str_pad($e['termeknev'], 255, ' ', STR_PAD_RIGHT)
                        . $eszin
                        . str_pad((string)$ve, 6, '0', STR_PAD_LEFT);

                    if ($f['valtadattipus1id'] == $rendezendo) {
                        $ertek = $f['valtertek1'];
                        $fszin = $f['valtertek2'];
                    } elseif ($f['valtadattipus2id'] == $rendezendo) {
                        $ertek = $f['valtertek2'];
                        $fszin = $f['valtertek1'];
                    } else {
                        $ertek = false;
                        $fszin = false;
                    }
                    $vf = array_search($ertek, $sorrend);
                    if ($vf === false) {
                        $vf = 0;
                    }
                    $vf = str_pad($f['cikkszam'], 50, ' ', STR_PAD_RIGHT)
                        . str_pad($f['termeknev'], 255, ' ', STR_PAD_RIGHT)
                        . $fszin
                        . str_pad((string)$vf, 6, '0', STR_PAD_LEFT);

                    if ($ve === $vf) {
                        return 0;
                    }
                    return ($ve < $vf) ? -1 : 1;
                });
                $ret['tetellista'] = array_values($a);
                break;
            default:
                $ret['tetellista'] = $tetellist;
                break;
        }
        return $ret;
    }

    public function toNAVOnlineXML($rawreturn = false)
    {
        $nover = store::getParameter(\mkw\consts::NAVOnlineVersion, '2_0');
        switch ($nover) {
            case '2_0':
                return $this->toNAVOnlineXML2_0($rawreturn);
            case '3_0':
                return $this->toNAVOnlineXML3_0($rawreturn);
            default:
                return $this->toNAVOnlineXML2_0($rawreturn);
        }
    }

    private function toNAVOnlineXML3_0($rawreturn = false)
    {
        $result = '<?xml version="1.0" encoding="UTF-8"?>';
        $result = $result . '<InvoiceData xmlns="http://schemas.nav.gov.hu/OSA/3.0/data" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:common="http://schemas.nav.gov.hu/NTCA/1.0/common" xmlns:base="http://schemas.nav.gov.hu/OSA/3.0/base" xsi:schemaLocation="http://schemas.nav.gov.hu/OSA/3.0/data invoiceData.xsd">';
        $result = $result . '<invoiceNumber>' . $this->getId() . '</invoiceNumber>';
        $result = $result . '<invoiceIssueDate>' . $this->getKelt()->format(\mkw\store::$SQLDateFormat) . '</invoiceIssueDate>';
        $result = $result . '<completenessIndicator>false</completenessIndicator>';
        $result = $result . '<invoiceMain>';
        $result = $result . '<invoice>';
        if ($this->getStorno()) {
            $result = $result . '<invoiceReference>';
            $result = $result . '<originalInvoiceNumber>' . $this->getParbizonylatfejId() . '</originalInvoiceNumber>';
            $pb = $this->getParbizonylatfej();
            if ($pb->getNaveredmeny() === 'DONE') {
                $result = $result . '<modifyWithoutMaster>false</modifyWithoutMaster>';
            } else {
                $result = $result . '<modifyWithoutMaster>true</modifyWithoutMaster>';
            }
            $result = $result . '<modificationIndex>1</modificationIndex>';
            $result = $result . '</invoiceReference>';
        }
        $result = $result . '<invoiceHead>';

        $result = $result . '<supplierInfo>';
        $s = explode('-', $this->getTulajadoszam());
        $result = $result . '<supplierTaxNumber><base:taxpayerId>' . $s[0] . '</base:taxpayerId><base:vatCode>' . $s[1] . '</base:vatCode><base:countyCode>' . $s[2] . '</base:countyCode></supplierTaxNumber>';
        $result = $result . '<supplierName>' . \mkw\store::CData($this->getTulajnev()) . '</supplierName>';
        $result = $result . '<supplierAddress><base:simpleAddress>';
        $result = $result . '<base:countryCode>HU</base:countryCode><base:postalCode>' . \mkw\store::CData(
                $this->getTulajirszam()
            ) . '</base:postalCode><base:city>' . \mkw\store::CData($this->getTulajvaros()) . '</base:city><base:additionalAddressDetail>' . \mkw\store::CData(
                $this->getTulajutca()
            ) . '</base:additionalAddressDetail>';
        $result = $result . '</base:simpleAddress></supplierAddress>';
        if ($this->getTulajjovengszam()) {
            $result = $result . '<exciseLicenceNum>' . $this->getTulajjovengszam() . '</exciseLicenceNum>';
        }
        $result = $result . '</supplierInfo>';

        $result = $result . '<customerInfo>';
        if ($this->getPartnervatstatus() == 1) {
            $result = $result . '<customerVatStatus>DOMESTIC</customerVatStatus>';
            $result = $result . '<customerVatData>';
            $s = explode('-', $this->getPartneradoszam());
            if ($s) {
                $result = $result . '<customerTaxNumber><base:taxpayerId>' . $s[0] . '</base:taxpayerId><base:vatCode>' . $s[1] . '</base:vatCode><base:countyCode>' . $s[2] . '</base:countyCode></customerTaxNumber>';
            }
            $result = $result . '</customerVatData>';
            $result = $result . '<customerName>' . \mkw\store::CData($this->getPartnernev()) . '</customerName>';
            $result = $result . '<customerAddress><base:simpleAddress>';
            $result = $result . '<base:countryCode>';
            $result = $result . $this->getPartnerorszagiso3166();
            $result = $result . '</base:countryCode>';
            $result = $result . '<base:postalCode>';
            if (trim($this->getPartnerirszam())) {
                $result = $result . \mkw\store::CData(trim($this->getPartnerirszam()));
            } else {
                $result = $result . '0000';
            }
            $result = $result . '</base:postalCode>';
            $result = $result . '<base:city>' . \mkw\store::CData($this->getPartnervaros()) . '</base:city>';
            $result = $result . '<base:additionalAddressDetail>' . \mkw\store::CData(
                    implode(' ', array($this->getPartnerutca(), $this->getPartnerhazszam()))
                ) . '</base:additionalAddressDetail>';
            $result = $result . '</base:simpleAddress></customerAddress>';
        } elseif ($this->getPartnervatstatus() == 2) {
            $result = $result . '<customerVatStatus>PRIVATE_PERSON</customerVatStatus>';
        } elseif ($this->getPartnervatstatus() == 3) {
            $result = $result . '<customerVatStatus>OTHER</customerVatStatus>';
            if ($this->getPartnereuadoszam() || ($this->getPartnerSzamlatipus() == 1)) { // EUn beluli
                if ($this->getPartnereuadoszam()) {
                    $result = $result . '<customerVatData>';
                    $result = $result . '<communityVatNumber>' . $this->getPartnereuadoszam() . '</communityVatNumber>';
                    $result = $result . '</customerVatData>';
                }
                $result = $result . '<customerName>' . \mkw\store::CData($this->getPartnernev()) . '</customerName>';
                $result = $result . '<customerAddress><base:simpleAddress>';
                $result = $result . '<base:countryCode>';
                $result = $result . $this->getPartnerorszagiso3166();
                $result = $result . '</base:countryCode>';
                $result = $result . '<base:postalCode>';
                if (trim($this->getPartnerirszam())) {
                    $result = $result . \mkw\store::CData(trim($this->getPartnerirszam()));
                } else {
                    $result = $result . '0000';
                }
                $result = $result . '</base:postalCode>';
                $result = $result . '<base:city>' . \mkw\store::CData($this->getPartnervaros()) . '</base:city>';
                $result = $result . '<base:additionalAddressDetail>' . \mkw\store::CData(
                        implode(' ', array($this->getPartnerutca(), $this->getPartnerhazszam()))
                    ) . '</base:additionalAddressDetail>';
                $result = $result . '</base:simpleAddress></customerAddress>';
            } elseif ($this->getPartnerthirdadoszam() || ($this->getPartnerSzamlatipus() == 2)) { // EUn kivuli
                if ($this->getPartnerthirdadoszam()) {
                    $result = $result . '<customerVatData>';
                    $result = $result . '<thirdStateTaxId>' . $this->getPartnerthirdadoszam() . '</thirdStateTaxId>';
                    $result = $result . '</customerVatData>';
                }
                $result = $result . '<customerName>' . \mkw\store::CData($this->getPartnernev()) . '</customerName>';
                $result = $result . '<customerAddress><base:simpleAddress>';
                $result = $result . '<base:countryCode>';
                $result = $result . $this->getPartnerorszagiso3166();
                $result = $result . '</base:countryCode>';
                $result = $result . '<base:postalCode>';
                if (trim($this->getPartnerirszam())) {
                    $result = $result . \mkw\store::CData(trim($this->getPartnerirszam()));
                } else {
                    $result = $result . '0000';
                }
                $result = $result . '</base:postalCode>';
                $result = $result . '<base:city>' . \mkw\store::CData($this->getPartnervaros()) . '</base:city>';
                $result = $result . '<base:additionalAddressDetail>' . \mkw\store::CData(
                        implode(' ', array($this->getPartnerutca(), $this->getPartnerhazszam()))
                    ) . '</base:additionalAddressDetail>';
                $result = $result . '</base:simpleAddress></customerAddress>';
            }
        }
        $result = $result . '</customerInfo>';

        $result = $result . '<invoiceDetail>';
        $result = $result . '<invoiceCategory>';
        if ($this->getGyujtoszamla()) {
            $result = $result . 'AGGREGATE';
        } else {
            $result = $result . 'NORMAL';
        }
        $result = $result . '</invoiceCategory>';
        $result = $result . '<invoiceDeliveryDate>' . $this->getTeljesites()->format(\mkw\store::$SQLDateFormat) . '</invoiceDeliveryDate>';
        if ($this->getGyujtoszamla()) {
            $result = $result . '<invoiceDeliveryPeriodStart>' . $this->getGyujtoidoszakeleje()->format(
                    \mkw\store::$SQLDateFormat
                ) . '</invoiceDeliveryPeriodStart>' .
                '<invoiceDeliveryPeriodEnd>' . $this->getGyujtoidoszakvege()->format(\mkw\store::$SQLDateFormat) . '</invoiceDeliveryPeriodEnd>';
        }
        $result = $result . '<currencyCode>' . $this->getValutanemnev() . '</currencyCode>';
        if (\mkw\store::getParameter(\mkw\consts::Valutanem) != $this->getValutanemId()) {
            $result = $result . '<exchangeRate>' . \mkw\store::NAVNum($this->getArfolyam()) . '</exchangeRate>';
        } else {
            $result = $result . '<exchangeRate>1</exchangeRate>';
        }
        $result = $result . '<paymentMethod>' . $this->getFizmod()->getNavtipus() . '</paymentMethod>';
        $result = $result . '<paymentDate>' . $this->getEsedekesseg()->format(\mkw\store::$SQLDateFormat) . '</paymentDate>';
        $result = $result . '<invoiceAppearance>PAPER</invoiceAppearance>';
        $result = $result . '</invoiceDetail>';
        $result = $result . '</invoiceHead>';

        $result = $result . '<invoiceLines>';
        $result = $result . '<mergedItemIndicator>false</mergedItemIndicator>';
        $tetelsorszam = 1;
        /** @var \Entities\Bizonylattetel $bt */
        foreach ($this->getBizonylattetelek() as $bt) {
            $result = $result . '<line>';
            $result = $result . '<lineNumber>' . $tetelsorszam . '</lineNumber>';
            if ($this->getStorno()) {
                $result = $result . '<lineModificationReference>';
                $result = $result . '<lineNumberReference>' . $tetelsorszam . '</lineNumberReference>';
                $result = $result . '<lineOperation>MODIFY</lineOperation>';
                $result = $result . '</lineModificationReference>';
            }
            if (str_replace(array('.', ' ', '-', '_', ','), '', $bt->getVtszszam())) {
                $result = $result . '<productCodes><productCode>';
                $result = $result . '<productCodeCategory>VTSZ</productCodeCategory>';
                $result = $result . '<productCodeValue>' . str_replace(array('.', ' ', '-', '_', ','), '', $bt->getVtszszam()) . '</productCodeValue>';
                $result = $result . '</productCode></productCodes>';
            }
            $result = $result . '<lineExpressionIndicator>true</lineExpressionIndicator>';
            $result = $result . '<lineDescription>' . \mkw\store::CData($bt->getTermeknev()) . '</lineDescription>';
            $result = $result . '<quantity>' . \mkw\store::NAVNum($bt->getMennyiseg()) . '</quantity>';

            if ($bt->getMekodNavtipus()) {
                $result = $result . '<unitOfMeasure>' . \mkw\store::CData($bt->getMekodNavtipus()) . '</unitOfMeasure>';
                $result = $result . '<unitOfMeasureOwn>' . \mkw\store::CData($bt->getME()) . '</unitOfMeasureOwn>';
            } else {
                $result = $result . '<unitOfMeasure>' . \mkw\store::CData('OWN') . '</unitOfMeasure>';
                if ($bt->getME()) {
                    $result = $result . '<unitOfMeasureOwn>' . \mkw\store::CData($bt->getME()) . '</unitOfMeasureOwn>';
                } else {
                    $result = $result . '<unitOfMeasureOwn>' . \mkw\store::CData(1) . '</unitOfMeasureOwn>';
                }
            }
            $result = $result . '<unitPrice>' . \mkw\store::NAVNum($bt->getNettoegysar()) . '</unitPrice>';
            $result = $result . '<unitPriceHUF>' . \mkw\store::NAVNum($bt->getNettoegysarhuf()) . '</unitPriceHUF>';
            $result = $result . '<lineAmountsNormal>';

            $result = $result . '<lineNetAmountData>';
            $result = $result . '<lineNetAmount>' . \mkw\store::NAVNum($bt->getNetto()) . '</lineNetAmount>';
            $result = $result . '<lineNetAmountHUF>' . \mkw\store::NAVNum($bt->getNettohuf()) . '</lineNetAmountHUF>';
            $result = $result . '</lineNetAmountData>';

            $result = $result . '<lineVatRate>';
            if ($this->isForditottadozas()) {
                $result = $result . '<vatDomesticReverseCharge>true</vatDomesticReverseCharge>';
            } else {
                $afak = $bt->getAfa();
                if ($afak->getErtek() == 0) {
                    $result = $result . '<vatExemption>';
                    $result = $result . '<case>' . $afak->getNavcase() . '</case>';
                    $result = $result . '<reason>' . \mkw\store::CData(
                            \mkw\store::getEm()->getRepository(Afa::class)->getNavReason($afak->getNavcase())
                        ) . '</reason>';
                    $result = $result . '</vatExemption>';
                } else {
                    $result = $result . '<vatPercentage>' . \mkw\store::NAVNum($bt->getAfakulcs() / 100) . '</vatPercentage>';
                }
            }
            $result = $result . '</lineVatRate>';

            $result = $result . '<lineVatData>';
            $result = $result . '<lineVatAmount>' . \mkw\store::NAVNum($bt->getAfaertek()) . '</lineVatAmount>';
            $result = $result . '<lineVatAmountHUF>' . \mkw\store::NAVNum($bt->getAfaertekhuf()) . '</lineVatAmountHUF>';
            $result = $result . '</lineVatData>';

            $result = $result . '<lineGrossAmountData>';
            $result = $result . '<lineGrossAmountNormal>' . \mkw\store::NAVNum($bt->getBrutto()) . '</lineGrossAmountNormal>';
            $result = $result . '<lineGrossAmountNormalHUF>' . \mkw\store::NAVNum($bt->getBruttohuf()) . '</lineGrossAmountNormalHUF>';
            $result = $result . '</lineGrossAmountData>';

            $result = $result . '</lineAmountsNormal>';
            if ($this->getGyujtoszamla()) {
                $result = $result . '<aggregateInvoiceLineData>';
                $result = $result . '<lineExchangeRate>' . \mkw\store::NAVNum($this->getArfolyam()) . '</lineExchangeRate>';
                $result = $result . '<lineDeliveryDate>' . $bt->getTeljesites()->format(\mkw\store::$SQLDateFormat) . '</lineDeliveryDate>';
                $result = $result . '</aggregateInvoiceLineData>';
            }

            // KTD

            if ($this->getGyujtoszamla()) {
                /*
                $result = $result . '<additionalLineData>';
                $result = $result . '<dataName>X00001_SZSZ</dataName>';
                $result = $result . '<dataDescription>' . \mkw\store::CData('Szállítólevél száma') . '</dataDescription>';
                $result = $result . '<dataValue>' . \mkw\store::CData() . '</dataValue>';
                $result = $result . '</additionalLineData>';
                */
            }
            $result = $result . '</line>';
            $tetelsorszam++;
        }
        $result = $result . '</invoiceLines>';

        // KTD SUMMARY

        $result = $result . '<invoiceSummary>';
        $result = $result . '<summaryNormal>';
        $afasum = \mkw\store::getEm()->getRepository('Entities\Bizonylatfej')->getAFAOsszesito($this);
        foreach ($afasum as $as) {
            $result = $result . '<summaryByVatRate>';
            $result = $result . '<vatRate>';
            if ($this->isForditottadozas()) {
                $result = $result . '<vatDomesticReverseCharge>true</vatDomesticReverseCharge>';
            } else {
                if ($as['afakulcs'] == 0) {
                    $result = $result . '<vatExemption>';
                    $result = $result . '<case>' . $as['navcase'] . '</case>';
                    $result = $result . '<reason>' . \mkw\store::CData(
                            \mkw\store::getEm()->getRepository(Afa::class)->getNavReason($as['navcase'])
                        ) . '</reason>';
                    $result = $result . '</vatExemption>';
                } else {
                    $result = $result . '<vatPercentage>' . \mkw\store::NAVNum($as['afakulcs'] / 100) . '</vatPercentage>';
                }
            }
            $result = $result . '</vatRate>';

            $result = $result . '<vatRateNetData>';
            $result = $result . '<vatRateNetAmount>' . \mkw\store::NAVNum($as['netto']) . '</vatRateNetAmount>';
            $result = $result . '<vatRateNetAmountHUF>' . \mkw\store::NAVNum($as['nettohuf']) . '</vatRateNetAmountHUF>';
            $result = $result . '</vatRateNetData>';

            $result = $result . '<vatRateVatData>';
            $result = $result . '<vatRateVatAmount>' . \mkw\store::NAVNum($as['afa']) . '</vatRateVatAmount>';
            $result = $result . '<vatRateVatAmountHUF>' . \mkw\store::NAVNum($as['afahuf']) . '</vatRateVatAmountHUF>';
            $result = $result . '</vatRateVatData>';

            $result = $result . '<vatRateGrossData>';
            $result = $result . '<vatRateGrossAmount>' . \mkw\store::NAVNum($as['brutto']) . '</vatRateGrossAmount>';
            $result = $result . '<vatRateGrossAmountHUF>' . \mkw\store::NAVNum($as['bruttohuf']) . '</vatRateGrossAmountHUF>';
            $result = $result . '</vatRateGrossData>';

            $result = $result . '</summaryByVatRate>';
        }
        $result = $result . '<invoiceNetAmount>' . \mkw\store::NAVNum($this->getNetto()) . '</invoiceNetAmount>';
        $result = $result . '<invoiceNetAmountHUF>' . \mkw\store::NAVNum($this->getNettohuf()) . '</invoiceNetAmountHUF>';
        $result = $result . '<invoiceVatAmount>' . \mkw\store::NAVNum($this->getAfa()) . '</invoiceVatAmount>';
        $result = $result . '<invoiceVatAmountHUF>' . \mkw\store::NAVNum($this->getAfahuf()) . '</invoiceVatAmountHUF>';
        $result = $result . '</summaryNormal>';
        $result = $result . '<summaryGrossData>';
        $result = $result . '<invoiceGrossAmount>' . \mkw\store::NAVNum($this->getBrutto()) . '</invoiceGrossAmount>';
        $result = $result . '<invoiceGrossAmountHUF>' . \mkw\store::NAVNum($this->getBruttohuf()) . '</invoiceGrossAmountHUF>';
        $result = $result . '</summaryGrossData>';
        $result = $result . '</invoiceSummary>';
        $result = $result . '</invoice>';
        $result = $result . '</invoiceMain>';
        $result = $result . '</InvoiceData>';

        if ($rawreturn) {
            return $result;
        }

        $b64 = str_replace('+', '$', base64_encode($result)); // a Delphi miatt az API igy várja az adatot

        if ($this->getStorno()) {
            $result = 'STORNO' . $b64;
        } else {
            $result = 'CREATE' . $b64;
        }
        return $result;
    }

    private function toNAVOnlineXML2_0($rawreturn = false)
    {
        $result = '<?xml version="1.0" encoding="UTF-8"?>';
        $result = $result . '<InvoiceData xmlns="http://schemas.nav.gov.hu/OSA/2.0/data" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://schemas.nav.gov.hu/OSA/2.0/data invoiceData.xsd">';
        $result = $result . '<invoiceNumber>' . $this->getId() . '</invoiceNumber>';
        $result = $result . '<invoiceIssueDate>' . $this->getKelt()->format(\mkw\store::$SQLDateFormat) . '</invoiceIssueDate>';
        $result = $result . '<invoiceMain>';
        $result = $result . '<invoice>';
        if ($this->getStorno()) {
            $result = $result . '<invoiceReference>';
            $result = $result . '<originalInvoiceNumber>' . $this->getParbizonylatfejId() . '</originalInvoiceNumber>';
            $pb = $this->getParbizonylatfej();
            if ($pb->getNaveredmeny() === 'DONE') {
                $result = $result . '<modifyWithoutMaster>false</modifyWithoutMaster>';
            } else {
                $result = $result . '<modifyWithoutMaster>true</modifyWithoutMaster>';
            }
            $result = $result . '<modificationIndex>1</modificationIndex>';
            $result = $result . '</invoiceReference>';
        }
        $result = $result . '<invoiceHead>';

        $result = $result . '<supplierInfo>';
        $s = explode('-', $this->getTulajadoszam());
        $result = $result . '<supplierTaxNumber><taxpayerId>' . $s[0] . '</taxpayerId><vatCode>' . $s[1] . '</vatCode><countyCode>' . $s[2] . '</countyCode></supplierTaxNumber>';
        $result = $result . '<supplierName>' . \mkw\store::CData($this->getTulajnev()) . '</supplierName>';
        $result = $result . '<supplierAddress><simpleAddress>';
        $result = $result . '<countryCode>HU</countryCode><postalCode>' . \mkw\store::CData(
                $this->getTulajirszam()
            ) . '</postalCode><city>' . \mkw\store::CData($this->getTulajvaros()) . '</city><additionalAddressDetail>' . \mkw\store::CData(
                $this->getTulajutca()
            ) . '</additionalAddressDetail>';
        $result = $result . '</simpleAddress></supplierAddress>';
        if ($this->getTulajjovengszam()) {
            $result = $result . '<exciseLicenceNum>' . $this->getTulajjovengszam() . '</exciseLicenceNum>';
        }
        $result = $result . '</supplierInfo>';

        $result = $result . '<customerInfo>';
        $s = explode('-', $this->getPartneradoszam());
        if ($s) {
            $result = $result . '<customerTaxNumber><taxpayerId>' . $s[0] . '</taxpayerId><vatCode>' . $s[1] . '</vatCode><countyCode>' . $s[2] . '</countyCode></customerTaxNumber>';
        }
        $result = $result . '<customerName>' . \mkw\store::CData($this->getPartnernev()) . '</customerName>';
        $result = $result . '<customerAddress><simpleAddress>';
        $result = $result . '<countryCode>';
        $result = $result . $this->getPartnerorszagiso3166();
        $result = $result . '</countryCode>';
        $result = $result . '<postalCode>';
        if (trim($this->getPartnerirszam())) {
            $result = $result . \mkw\store::CData(trim($this->getPartnerirszam()));
        } else {
            $result = $result . '0000';
        }
        $result = $result . '</postalCode>';
        $result = $result . '<city>' . \mkw\store::CData($this->getPartnervaros()) . '</city>';
        $result = $result . '<additionalAddressDetail>' . \mkw\store::CData(
                implode(' ', array($this->getPartnerutca(), $this->getPartnerhazszam()))
            ) . '</additionalAddressDetail>';
        $result = $result . '</simpleAddress></customerAddress>';
        $result = $result . '</customerInfo>';

        $result = $result . '<invoiceDetail>';
        $result = $result . '<invoiceCategory>';
        if ($this->getGyujtoszamla()) {
            $result = $result . 'AGGREGATE';
        } else {
            $result = $result . 'NORMAL';
        }
        $result = $result . '</invoiceCategory>';
        $result = $result . '<invoiceDeliveryDate>' . $this->getTeljesites()->format(\mkw\store::$SQLDateFormat) . '</invoiceDeliveryDate>';
        if ($this->getGyujtoszamla()) {
            $result = $result . '<invoiceDeliveryPeriodStart>' . $this->getGyujtoidoszakeleje()->format(
                    \mkw\store::$SQLDateFormat
                ) . '</invoiceDeliveryPeriodStart>' .
                '<invoiceDeliveryPeriodEnd>' . $this->getGyujtoidoszakvege()->format(\mkw\store::$SQLDateFormat) . '</invoiceDeliveryPeriodEnd>';
        }
        $result = $result . '<currencyCode>' . $this->getValutanemnev() . '</currencyCode>';
        if (\mkw\store::getParameter(\mkw\consts::Valutanem) != $this->getValutanemId()) {
            $result = $result . '<exchangeRate>' . \mkw\store::NAVNum($this->getArfolyam()) . '</exchangeRate>';
        } else {
            $result = $result . '<exchangeRate>1</exchangeRate>';
        }
        $result = $result . '<paymentMethod>' . $this->getFizmod()->getNavtipus() . '</paymentMethod>';
        $result = $result . '<paymentDate>' . $this->getEsedekesseg()->format(\mkw\store::$SQLDateFormat) . '</paymentDate>';
        $result = $result . '<invoiceAppearance>PAPER</invoiceAppearance>';
        $result = $result . '</invoiceDetail>';
        $result = $result . '</invoiceHead>';

        $result = $result . '<invoiceLines>';
        $tetelsorszam = 1;
        /** @var \Entities\Bizonylattetel $bt */
        foreach ($this->getBizonylattetelek() as $bt) {
            $result = $result . '<line>';
            $result = $result . '<lineNumber>' . $tetelsorszam . '</lineNumber>';
            if ($this->getStorno()) {
                $result = $result . '<lineModificationReference>';
                $result = $result . '<lineNumberReference>' . $tetelsorszam . '</lineNumberReference>';
                $result = $result . '<lineOperation>MODIFY</lineOperation>';
                $result = $result . '</lineModificationReference>';
            }
            if (str_replace(array('.', ' ', '-', '_', ','), '', $bt->getVtszszam())) {
                $result = $result . '<productCodes><productCode>';
                $result = $result . '<productCodeCategory>VTSZ</productCodeCategory>';
                $result = $result . '<productCodeValue>' . str_replace(array('.', ' ', '-', '_', ','), '', $bt->getVtszszam()) . '</productCodeValue>';
                $result = $result . '</productCode></productCodes>';
            }
            $result = $result . '<lineExpressionIndicator>true</lineExpressionIndicator>';
            $result = $result . '<lineDescription>' . \mkw\store::CData($bt->getTermeknev()) . '</lineDescription>';
            $result = $result . '<quantity>' . \mkw\store::NAVNum($bt->getMennyiseg()) . '</quantity>';

            if ($bt->getMekodNavtipus()) {
                $result = $result . '<unitOfMeasure>' . \mkw\store::CData($bt->getMekodNavtipus()) . '</unitOfMeasure>';
                $result = $result . '<unitOfMeasureOwn>' . \mkw\store::CData($bt->getME()) . '</unitOfMeasureOwn>';
            } else {
                $result = $result . '<unitOfMeasure>' . \mkw\store::CData('OWN') . '</unitOfMeasure>';
                if ($bt->getME()) {
                    $result = $result . '<unitOfMeasureOwn>' . \mkw\store::CData($bt->getME()) . '</unitOfMeasureOwn>';
                } else {
                    $result = $result . '<unitOfMeasureOwn>' . \mkw\store::CData(1) . '</unitOfMeasureOwn>';
                }
            }
            $result = $result . '<unitPrice>' . \mkw\store::NAVNum($bt->getNettoegysar()) . '</unitPrice>';
            $result = $result . '<unitPriceHUF>' . \mkw\store::NAVNum($bt->getNettoegysarhuf()) . '</unitPriceHUF>';
            $result = $result . '<lineAmountsNormal>';

            $result = $result . '<lineNetAmountData>';
            $result = $result . '<lineNetAmount>' . \mkw\store::NAVNum($bt->getNetto()) . '</lineNetAmount>';
            $result = $result . '<lineNetAmountHUF>' . \mkw\store::NAVNum($bt->getNettohuf()) . '</lineNetAmountHUF>';
            $result = $result . '</lineNetAmountData>';

            $result = $result . '<lineVatRate>';
            if ($this->isForditottadozas()) {
                $result = $result . '<vatDomesticReverseCharge>true</vatDomesticReverseCharge>';
            } else {
                if ($bt->getAfakulcs() == 0) {
                    $result = $result . '<vatExemption>AAM</vatExemption>';
                } else {
                    $result = $result . '<vatPercentage>' . \mkw\store::NAVNum($bt->getAfakulcs() / 100) . '</vatPercentage>';
                }
            }
            $result = $result . '</lineVatRate>';

            $result = $result . '<lineVatData>';
            $result = $result . '<lineVatAmount>' . \mkw\store::NAVNum($bt->getAfaertek()) . '</lineVatAmount>';
            $result = $result . '<lineVatAmountHUF>' . \mkw\store::NAVNum($bt->getAfaertekhuf()) . '</lineVatAmountHUF>';
            $result = $result . '</lineVatData>';

            $result = $result . '<lineGrossAmountData>';
            $result = $result . '<lineGrossAmountNormal>' . \mkw\store::NAVNum($bt->getBrutto()) . '</lineGrossAmountNormal>';
            $result = $result . '<lineGrossAmountNormalHUF>' . \mkw\store::NAVNum($bt->getBruttohuf()) . '</lineGrossAmountNormalHUF>';
            $result = $result . '</lineGrossAmountData>';

            $result = $result . '</lineAmountsNormal>';
            if ($this->getGyujtoszamla()) {
                $result = $result . '<aggregateInvoiceLineData>';
                $result = $result . '<lineExchangeRate>' . \mkw\store::NAVNum($this->getArfolyam()) . '</lineExchangeRate>';
                $result = $result . '<lineDeliveryDate>' . $bt->getTeljesites()->format(\mkw\store::$SQLDateFormat) . '</lineDeliveryDate>';
                $result = $result . '</aggregateInvoiceLineData>';
            }

            // KTD

            if ($this->getGyujtoszamla()) {
                /*
                $result = $result . '<additionalLineData>';
                $result = $result . '<dataName>X00001_SZSZ</dataName>';
                $result = $result . '<dataDescription>' . \mkw\store::CData('Szállítólevél száma') . '</dataDescription>';
                $result = $result . '<dataValue>' . \mkw\store::CData() . '</dataValue>';
                $result = $result . '</additionalLineData>';
                */
            }
            $result = $result . '</line>';
            $tetelsorszam++;
        }
        $result = $result . '</invoiceLines>';

        // KTD SUMMARY

        $result = $result . '<invoiceSummary>';
        $result = $result . '<summaryNormal>';
        $afasum = \mkw\store::getEm()->getRepository('Entities\Bizonylatfej')->getAFAOsszesito($this);
        foreach ($afasum as $as) {
            $result = $result . '<summaryByVatRate>';
            $result = $result . '<vatRate>';
            if ($this->isForditottadozas()) {
                $result = $result . '<vatDomesticReverseCharge>true</vatDomesticReverseCharge>';
            } else {
                if ($as['afakulcs'] == 0) {
                    $result = $result . '<vatExemption>AAM</vatExemption>';
                } else {
                    $result = $result . '<vatPercentage>' . \mkw\store::NAVNum($as['afakulcs'] / 100) . '</vatPercentage>';
                }
            }
            $result = $result . '</vatRate>';

            $result = $result . '<vatRateNetData>';
            $result = $result . '<vatRateNetAmount>' . \mkw\store::NAVNum($as['netto']) . '</vatRateNetAmount>';
            $result = $result . '<vatRateNetAmountHUF>' . \mkw\store::NAVNum($as['nettohuf']) . '</vatRateNetAmountHUF>';
            $result = $result . '</vatRateNetData>';

            $result = $result . '<vatRateVatData>';
            $result = $result . '<vatRateVatAmount>' . \mkw\store::NAVNum($as['afa']) . '</vatRateVatAmount>';
            $result = $result . '<vatRateVatAmountHUF>' . \mkw\store::NAVNum($as['afahuf']) . '</vatRateVatAmountHUF>';
            $result = $result . '</vatRateVatData>';

            $result = $result . '<vatRateGrossData>';
            $result = $result . '<vatRateGrossAmount>' . \mkw\store::NAVNum($as['brutto']) . '</vatRateGrossAmount>';
            $result = $result . '<vatRateGrossAmountHUF>' . \mkw\store::NAVNum($as['bruttohuf']) . '</vatRateGrossAmountHUF>';
            $result = $result . '</vatRateGrossData>';

            $result = $result . '</summaryByVatRate>';
        }
        $result = $result . '<invoiceNetAmount>' . \mkw\store::NAVNum($this->getNetto()) . '</invoiceNetAmount>';
        $result = $result . '<invoiceNetAmountHUF>' . \mkw\store::NAVNum($this->getNettohuf()) . '</invoiceNetAmountHUF>';
        $result = $result . '<invoiceVatAmount>' . \mkw\store::NAVNum($this->getAfa()) . '</invoiceVatAmount>';
        $result = $result . '<invoiceVatAmountHUF>' . \mkw\store::NAVNum($this->getAfahuf()) . '</invoiceVatAmountHUF>';
        $result = $result . '</summaryNormal>';
        $result = $result . '<summaryGrossData>';
        $result = $result . '<invoiceGrossAmount>' . \mkw\store::NAVNum($this->getBrutto()) . '</invoiceGrossAmount>';
        $result = $result . '<invoiceGrossAmountHUF>' . \mkw\store::NAVNum($this->getBruttohuf()) . '</invoiceGrossAmountHUF>';
        $result = $result . '</summaryGrossData>';
        $result = $result . '</invoiceSummary>';
        $result = $result . '</invoice>';
        $result = $result . '</invoiceMain>';
        $result = $result . '</InvoiceData>';

        if ($rawreturn) {
            return $result;
        }

        $b64 = str_replace('+', '$', base64_encode($result)); // a Delphi miatt az API igy várja az adatot

        if ($this->getStorno()) {
            $result = 'STORNO' . $b64;
        } else {
            $result = 'CREATE' . $b64;
        }
        return $result;
    }

    public function setPersistentData()
    {
        $this->setTulajData();
    }

    protected function setTulajData()
    {
        $this->setTulajnev(\mkw\store::getParameter(\mkw\consts::Tulajnev));
        $this->setTulajirszam(\mkw\store::getParameter(\mkw\consts::Tulajirszam));
        $this->setTulajvaros(\mkw\store::getParameter(\mkw\consts::Tulajvaros));
        $this->setTulajutca(\mkw\store::getParameter(\mkw\consts::Tulajutca));
        $this->setTulajadoszam(\mkw\store::getParameter(\mkw\consts::Tulajadoszam));
        $this->setTulajeuadoszam(\mkw\store::getParameter(\mkw\consts::Tulajeuadoszam));
        $this->setTulajeorinr(\mkw\store::getParameter(\mkw\consts::Tulajeorinr));
        $this->setTulajkisadozo(\mkw\store::getParameter(\mkw\consts::Tulajkisadozo, false));
        $this->setTulajegyenivallalkozo(\mkw\store::getParameter(\mkw\consts::Tulajegyenivallalkozo, false));
        $this->setTulajevnev(\mkw\store::getParameter(\mkw\consts::Tulajevnev));
        $this->setTulajevnyilvszam(\mkw\store::getParameter(\mkw\consts::Tulajevnyilvszam));
        $this->setTulajjovengszam(\mkw\store::getParameter(\mkw\consts::Tulajjovengszam));
        $this->setProgramnev(\mkw\store::getParameter(\mkw\consts::ProgramNev));
    }

    public function calcEsedekesseg()
    {
        $this->esedekesseg = \mkw\store::calcEsedekesseg($this->getKelt(), $this->getFizmod(), $this->getPartner());
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($val)
    {
        if (!$this->id) {
            $this->id = $val;
        }
    }

    public function clearId()
    {
        $this->id = null;
    }

    public function getSanitizedId()
    {
        return str_replace(['/', ' ', '.', '-'], '_', $this->getId());
    }

    public function getTrxId()
    {
        return $this->trxid;
    }

    public function getOTPayId()
    {
        return $this->otpayid;
    }

    public function setOTPayId($val)
    {
        $this->otpayid = $val;
    }

    public static function getNextId($bizszam)
    {
        $szam = explode('/', $bizszam);
        if (is_array($szam)) {
            $szam[1] = $szam[1] + 1;
            return self::createBizonylatszam($szam[0], null, $szam[1]);
        }
        return $bizszam;
    }

    public static function getPrevId($bizszam)
    {
        $szam = explode('/', $bizszam);
        if (is_array($szam)) {
            $szam[1] = $szam[1] - 1;
            return self::createBizonylatszam($szam[0], null, $szam[1]);
        }
        return $bizszam;
    }

    public static function createBizonylatszam($azon = '', $ev = 0, $szam = 0)
    {
        return $azon . $ev . '/' . sprintf('%06d', $szam * 1);
    }

    public function generateId($from = null)
    {
        if ($this->getId()) {
            return $this->getId();
        }
        $bt = $this->getBizonylattipus();
        $szam = 0;
        if ($bt && is_null($this->id)) {
            $azon = $bt->getAzonosito();
            if (is_null($azon)) {
                $azon = '';
            }
            $kezdo = $bt->getKezdosorszam();
            $ev = $this->getKelt()->format('Y');
            if (!$from) {
                $q = \mkw\store::getEm()->createQuery('SELECT COUNT(bf) FROM Entities\Bizonylatfej bf WHERE bf.bizonylattipus=:p');
                $q->setParameters(array('p' => $bt));
                if ($q->getSingleScalarResult() > 0) {
                    $kezdo = 1;
                }
                if (!$kezdo) {
                    $kezdo = 1;
                }
                $szam = $kezdo;
                $q = \mkw\store::getEm()->createQuery('SELECT MAX(bf.id) FROM Entities\Bizonylatfej bf WHERE (bf.bizonylattipus=:p1) AND (YEAR(bf.kelt)=:p2)');
                $q->setParameters(array(
                                      'p1' => $bt,
                                      'p2' => $ev
                                  ));
                $max = $q->getSingleScalarResult();
                if ($max) {
                    $szam = explode('/', $max);
                    if (is_array($szam)) {
                        $szam = $szam[1] + 1;
                    }
                }
            } else {
                $szam = $from;
                $q = \mkw\store::getEm()->createQuery('SELECT MAX(bf.id) FROM Entities\Bizonylatfej bf WHERE (bf.bizonylattipus=:p1) AND (YEAR(bf.kelt)=:p2)');
                $q->setParameters(array(
                                      'p1' => $bt,
                                      'p2' => $ev
                                  ));
                $max = $q->getSingleScalarResult();
                if ($max) {
                    $szam = explode('/', $max);
                    if (is_array($szam)) {
                        $szam = $szam[1] + 1;
                    }
                }
                if ($szam < $from) {
                    $szam = $from;
                }
            }
            $this->id = self::createBizonylatszam($azon, $ev, $szam);
        }
        return $szam;
    }

    public function getBizonylattetelek()
    {
        return $this->bizonylattetelek;
    }

    public function addBizonylattetel(Bizonylattetel $val)
    {
        if (!$this->bizonylattetelek->contains($val)) {
            $val->setIrany($this->getIrany());
            $this->bizonylattetelek->add($val);
            $val->setBizonylatfej($this);
        }
    }

    public function removeBizonylattetel(Bizonylattetel $val)
    {
        if ($this->bizonylattetelek->removeElement($val)) {
            $val->removeBizonylatfej();
            return true;
        }
        return false;
    }

    public function clearBizonylattetelek()
    {
        $this->bizonylattetelek->clear();
    }

    public function getFolyoszamlak()
    {
        return $this->folyoszamlak;
    }

    public function addFolyoszamla(Folyoszamla $val)
    {
        if (!$this->folyoszamlak->contains($val)) {
            $this->folyoszamlak->add($val);
            $val->setBizonylatfej($this);
        }
    }

    public function removeFolyoszamla(Folyoszamla $val)
    {
        if ($this->folyoszamlak->removeElement($val)) {
            $val->removeBizonylatfej();
            return true;
        }
        return false;
    }

    public function clearFolyoszamlak()
    {
        $this->folyoszamlak->clear();
    }

    public function getIrany()
    {
        return $this->irany;
    }

    public function setIrany($val)
    {
        $this->irany = $val;
    }

    /**
     * @return \Entities\Bizonylattipus
     */
    public function getBizonylattipus()
    {
        return $this->bizonylattipus;
    }

    public function getBizonylattipusNavbekuldendo()
    {
        if ($this->bizonylattipus) {
            return $this->bizonylattipus->getNavbekuldendo();
        }
        return false;
    }

    public function getBizonylattipusId()
    {
        if ($this->bizonylattipus) {
            return $this->bizonylattipus->getId();
        }
        return '';
    }

    /**
     * @param \Entities\Bizonylattipus $val
     */
    public function setBizonylattipus($val)
    {
        if ($this->bizonylattipus !== $val) {
            if (!$val) {
                $this->removeBizonylattipus();
            } else {
                $this->bizonylattipus = $val;
                if (!$this->duplication) {
                    $this->setIrany($val->getIrany());
                    $this->setBizonylatnev($val->getNev());
                    $this->setPenztmozgat($val->getPenztmozgat());
                    $this->setReportfile($val->getTplname());
                }
            }
        }
    }

    public function removeBizonylattipus()
    {
        if ($this->bizonylattipus !== null) {
            $this->bizonylattipus = null;
            if (!$this->duplication) {
                $this->bizonylatnev = '';
                $this->setReportfile('');
            }
        }
    }

    public function getBizonylatnev()
    {
        return $this->bizonylatnev;
    }

    public function setBizonylatnev($val)
    {
        $this->bizonylatnev = $val;
    }

    public function getNyomtatva()
    {
        return $this->nyomtatva;
    }

    public function setNyomtatva($val)
    {
        $this->nyomtatva = $val;
    }

    public function getStorno()
    {
        return $this->storno;
    }

    public function setStorno($val)
    {
        $this->storno = $val;
        if ($this->storno && !$this->duplication) {
            $this->setStornozott(false);
        }
    }

    public function getStornozott()
    {
        return $this->stornozott;
    }

    public function setStornozott($val)
    {
        $this->stornozott = $val;
        if ($this->stornozott && !$this->duplication) {
            $this->setStorno(false);
        }
        if (!$this->duplication) {
            foreach ($this->bizonylattetelek as $bt) {
                $bt->setStornozott($val);
                \mkw\store::getEm()->persist($bt);
            }
        }
    }

    public function getStornoStr()
    {
        if ($this->storno) {
            return 'Storno';
        }
        if ($this->stornozott) {
            return 'Stornozott';
        }
        return '';
    }

    public function getMozgat()
    {
        $bt = $this->getBizonylattipus();
        $raktar = $this->getRaktar();
        if ($bt && $raktar) {
            return $bt->getMozgat() && $raktar->getMozgat();
        }
        return false;
    }

    public function getFoglal()
    {
        $bt = $this->getBizonylattipus();
        if ($bt) {
            return $bt->getFoglal();
        }
        return false;
    }

    public function getPenztmozgat()
    {
        return $this->penztmozgat;
    }

    public function setPenztmozgat($val)
    {
        $this->penztmozgat = $val;
    }

    public function getTulajnev()
    {
        return $this->tulajnev;
    }

    public function setTulajnev($val)
    {
        $this->tulajnev = $val;
    }

    public function getTulajirszam()
    {
        return $this->tulajirszam;
    }

    public function setTulajirszam($val)
    {
        $this->tulajirszam = $val;
    }

    public function getTulajvaros()
    {
        return $this->tulajvaros;
    }

    public function setTulajvaros($val)
    {
        $this->tulajvaros = $val;
    }

    public function getTulajutca()
    {
        return $this->tulajutca;
    }

    public function setTulajutca($val)
    {
        $this->tulajutca = $val;
    }

    public function getTulajadoszam()
    {
        return $this->tulajadoszam;
    }

    public function setTulajadoszam($val)
    {
        $this->tulajadoszam = $val;
    }

    public function getTulajeuadoszam()
    {
        return $this->tulajeuadoszam;
    }

    public function setTulajeuadoszam($val)
    {
        $this->tulajeuadoszam = $val;
    }

    public function getTulajeorinr()
    {
        return $this->tulajeorinr;
    }

    public function setTulajeorinr($val)
    {
        $this->tulajeorinr = $val;
    }

    public function getKelt()
    {
        if (!$this->id && !$this->kelt) {
            $this->kelt = new \DateTime(\mkw\store::convDate(date(\mkw\store::$DateFormat)));
        }
        return $this->kelt;
    }

    public function getKeltStr()
    {
        if ($this->getKelt()) {
            return $this->getKelt()->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    public function setKelt($adat = '')
    {
        if (is_a($adat, 'DateTime')) {
            $this->kelt = $adat;
        } else {
            if ($adat == '') {
                $adat = date(\mkw\store::$DateFormat);
            }
            $this->kelt = new \DateTime(\mkw\store::convDate($adat));
        }
    }

    public function getTeljesites()
    {
        if (!$this->id && !$this->teljesites) {
            $this->teljesites = new \DateTime(\mkw\store::convDate(date(\mkw\store::$DateFormat)));
        }
        return $this->teljesites;
    }

    public function getTeljesitesStr()
    {
        if ($this->getTeljesites()) {
            return $this->getTeljesites()->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    public function setTeljesites($adat = '')
    {
        if (is_a($adat, 'DateTime')) {
            $this->teljesites = $adat;
        } else {
            if ($adat == '') {
                $adat = date(\mkw\store::$DateFormat);
            }
            $this->teljesites = new \DateTime(\mkw\store::convDate($adat));
        }
    }

    public function getEsedekesseg()
    {
        if (!$this->id && !$this->esedekesseg) {
            $this->esedekesseg = new \DateTime(\mkw\store::convDate(date(\mkw\store::$DateFormat)));
        }
        return $this->esedekesseg;
    }

    public function getEsedekessegStr()
    {
        if ($this->getEsedekesseg()) {
            return $this->getEsedekesseg()->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    public function setEsedekesseg($adat = '')
    {
        if (is_a($adat, 'DateTime')) {
            $this->esedekesseg = $adat;
        } else {
            if ($adat == '') {
                $adat = date(\mkw\store::$DateFormat);
            }
            $this->esedekesseg = new \DateTime(\mkw\store::convDate($adat));
        }
    }

    public function getHatarido()
    {
        if (!$this->id && !$this->hatarido) {
            $this->hatarido = new \DateTime(\mkw\store::convDate(date(\mkw\store::$DateFormat)));
        }
        return $this->hatarido;
    }

    public function getHataridoStr()
    {
        if ($this->getHatarido()) {
            return $this->getHatarido()->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    public function setHatarido($adat = '')
    {
        if (is_a($adat, 'DateTime')) {
            $this->hatarido = $adat;
        } else {
            if ($adat == '') {
                $adat = date(\mkw\store::$DateFormat);
            }
            $this->hatarido = new \DateTime(\mkw\store::convDate($adat));
        }
    }

    /**
     * @return \Entities\Fizmod
     */
    public function getFizmod()
    {
        if (!$this->id && !$this->fizmod) {
            $this->setFizmod(\mkw\store::getParameter(\mkw\consts::Fizmod));
        }
        return $this->fizmod;
    }

    public function getFizmodnev()
    {
        return $this->fizmodnev;
    }

    public function getFizmodId()
    {
        $fm = $this->getFizmod();
        if ($fm) {
            return $fm->getId();
        }
        return '';
    }

    /**
     * @param \Entities\Fizmod $val
     */
    public function setFizmod($val)
    {
        if (!($val instanceof \Entities\Fizmod)) {
            $val = \mkw\store::getEm()->getRepository('Entities\Fizmod')->find($val);
        }
        if ($this->fizmod !== $val) {
            if (!$val) {
                $this->removeFizmod();
            } else {
                $this->fizmod = $val;
                if (!$this->duplication) {
                    $this->fizmodnev = $val->getNev();
                    $this->setNincspenzmozgas($val->getNincspenzmozgas());
                }
            }
        }
    }

    public function removeFizmod()
    {
        if ($this->fizmod !== null) {
            $this->fizmod = null;
            if (!$this->duplication) {
                $this->fizmodnev = '';
                $this->setNincspenzmozgas(false);
            }
        }
    }

    /**
     * @return \Entities\Szallitasimod
     */
    public function getSzallitasimod()
    {
        return $this->szallitasimod;
    }

    public function getSzallitasimodnev()
    {
        return $this->szallitasimodnev;
    }

    public function getSzallitasimodId()
    {
        if ($this->szallitasimod) {
            return $this->szallitasimod->getId();
        }
        return '';
    }

    /**
     * @param \Entities\Szallitasimod $val
     */
    public function setSzallitasimod($val)
    {
        if (!($val instanceof \Entities\Szallitasimod)) {
            $val = \mkw\store::getEm()->getRepository('Entities\Szallitasimod')->find($val);
        }
        if ($this->szallitasimod !== $val) {
            if (!$val) {
                $this->removeSzallitasimod();
            } else {
                $this->szallitasimod = $val;
                if (!$this->duplication) {
                    $this->szallitasimodnev = $val->getNev();
                }
            }
        }
    }

    public function removeSzallitasimod()
    {
        if ($this->szallitasimod !== null) {
            $this->szallitasimod = null;
            if (!$this->duplication) {
                $this->szallitasimodnev = '';
            }
        }
    }

    public function getNetto()
    {
        return $this->netto;
    }

    public function setNetto($val)
    {
        $this->netto = $val;
    }

    public function getAfa()
    {
        return $this->afa;
    }

    public function setAfa($val)
    {
        $this->afa = $val;
    }

    public function getBrutto()
    {
        return $this->brutto;
    }

    public function setBrutto($val)
    {
        $this->brutto = $val;
    }

    public function getFizetendo()
    {
        return $this->fizetendo;
    }

    public function setFizetendo($val)
    {
        $this->fizetendo = $val;
    }

    /**
     * @return \Entities\Valutanem
     */
    public function getValutanem()
    {
        if (!$this->id && !$this->valutanem) {
            $this->setValutanem(\mkw\store::getParameter(\mkw\consts::Valutanem));
        }
        return $this->valutanem;
    }

    public function getValutanemnev()
    {
        return $this->valutanemnev;
    }

    public function getValutanemId()
    {
        $vn = $this->getValutanem();
        if ($vn) {
            return $vn->getId();
        }
        return '';
    }

    /**
     * @param \Entities\Valutanem $val
     */
    public function setValutanem($val)
    {
        if (!($val instanceof \Entities\Valutanem)) {
            $val = \mkw\store::getEm()->getRepository('Entities\Valutanem')->find($val);
        }
        if ($this->valutanem !== $val) {
            if (!$val) {
                $this->removeValutanem();
            } else {
                $this->valutanem = $val;
                if (!$this->duplication) {
                    $this->valutanemnev = $val->getNev();
                }
            }
        }
    }

    public function removeValutanem()
    {
        if ($this->valutanem !== null) {
            $this->valutanem = null;
            if (!$this->duplication) {
                $this->valutanemnev = '';
                $this->setArfolyam(1);
            }
        }
    }

    public function getNettohuf()
    {
        return $this->nettohuf;
    }

    public function setNettohuf($val)
    {
        $this->nettohuf = $val;
    }

    public function getAfahuf()
    {
        return $this->afahuf;
    }

    public function setAfahuf($val)
    {
        $this->afahuf = $val;
    }

    public function getBruttohuf()
    {
        return $this->bruttohuf;
    }

    public function setBruttohuf($val)
    {
        $this->bruttohuf = $val;
    }

    public function getArfolyam()
    {
        if (!$this->id && !$this->arfolyam) {
            if ($this->getValutanemId() == \mkw\store::getParameter(\mkw\consts::Valutanem)) {
                $this->setArfolyam(1);
            } else {
            }
        }
        return $this->arfolyam;
    }

    public function setArfolyam($val)
    {
        $this->arfolyam = $val;
    }

    /**
     * @return \Entities\Partner
     */
    public function getPartner()
    {
        return $this->partner;
    }

    public function getPartnerId()
    {
        if ($this->partner) {
            return $this->partner->getId();
        }
        return '';
    }

    /**
     * @param \Entities\Partner $val
     */
    public function setPartnerLeiroadat($val)
    {
        $this->setPartnernev($val->getNev());
        $this->setPartnervezeteknev($val->getVezeteknev());
        $this->setPartnerkeresztnev($val->getKeresztnev());
        $this->setPartneradoszam($val->getAdoszam());
        $this->setPartnercjszam($val->getCjszam());
        $this->setPartnereuadoszam($val->getEuadoszam());
        $this->setPartnerthirdadoszam($val->getThirdadoszam());
        $this->setPartnerfvmszam($val->getFvmszam());
        $this->setPartnerirszam($val->getIrszam());
        $this->setPartnerjovengszam($val->getJovengszam());
        $this->setPartnerlirszam($val->getLirszam());
        $this->setPartnerlutca($val->getLutca());
        $this->setPartnerlvaros($val->getLvaros());
        $this->setPartnertelefon($val->getTelefon());
        $this->setPartneremail($val->getEmail());
        $this->setPartnermukengszam($val->getMukengszam());
        $this->setPartnerostermszam($val->getOstermszam());
        $this->setPartnerstatszamjel($val->getStatszamjel());
        $this->setPartnerutca($val->getUtca());
        $this->setPartnervalligszam($val->getValligszam());
        $this->setPartnervaros($val->getVaros());
        $this->setPartnerhazszam($val->getHazszam());
        $this->setPartnervatstatus($val->getVatstatus());
        $this->setPartnerszamlaegyeb($val->getSzamlaegyeb());

        $this->setSzallnev($val->getSzallnev());
        $this->setSzallirszam($val->getSzallirszam());
        $this->setSzallvaros($val->getSzallvaros());
        $this->setSzallutca($val->getSzallutca());
        $this->setSzallhazszam($val->getSzallhazszam());

        $this->setPartnerszamlatipus($val->getSzamlatipus());
        $this->setBizonylatnyelv($val->getBizonylatnyelv());

        $this->setPartnerktdatalany($val->getKtdatalany());
        $this->setPartnerktdatvallal($val->getKtdatvallal());
        $this->setPartnerktdszerzszam($val->getKtdszerzszam());
    }

    /**
     * @param \Entities\Partner $val
     */
    public function setPartner($val)
    {
        if (!$val) {
            $this->removePartner();
        } else {
            $this->partner = $val;
            if (!$this->duplication) {
                $this->setPartnerLeiroadat($val);

                $uk = $val->getUzletkoto();
                if ($uk) {
                    $this->setUzletkoto($uk);
                } else {
                    $this->removeUzletkoto();
                }
                $fm = $val->getFizmod();
                if ($fm) {
                    $this->setFizmod($fm);
                } else {
                    $this->removeFizmod();
                }
                $v = $val->getValutanem();
                if ($v) {
                    $this->setValutanem($v);
                } else {
                    $this->removeValutanem();
                }
            }
        }
    }

    public function removePartner()
    {
        if ($this->partner !== null) {
            $this->partner = null;
            if (!$this->duplication) {
                $this->partnernev = '';
                $this->partnervezeteknev = '';
                $this->partnerkeresztnev = '';
                $this->partneradoszam = '';
                $this->partnercjszam = '';
                $this->partnereuadoszam = '';
                $this->partnerthirdadoszam = '';
                $this->partnerfvmszam = '';
                $this->partnerirszam = '';
                $this->partnerjovengszam = '';
                $this->partnerlirszam = '';
                $this->partnerlutca = '';
                $this->partnerlvaros = '';
                $this->partnermukengszam = '';
                $this->partnerostermszam = '';
                $this->partnerstatszamjel = '';
                $this->partnerutca = '';
                $this->partnervalligszam = '';
                $this->partnervaros = '';
                $this->partnerhazszam = '';
                $this->partnerszamlatipus = 0;
                $this->partnerfeketelistas = false;
                $this->partnerfeketelistaok = '';
                $this->szallnev = '';
                $this->szallirszam = '';
                $this->szallvaros = '';
                $this->szallutca = '';
                $this->szallhazszam = '';
                $this->bizonylatnyelv = '';
                $this->partnerktdatalany = false;
                $this->partnerktdatvallal = false;
                $this->partnerktdszerzszam = '';
                $this->partnervatstatus = 0;
                $this->partnerszamlaegyeb = '';
                $this->removeUzletkoto();
                $this->removeFizmod();
                $this->removeValutanem();
            }
        }
    }

    public function getPartnernev()
    {
        return $this->partnernev;
    }

    public function setPartnernev($val)
    {
        $this->partnernev = $val;
    }

    public function getPartnervezeteknev()
    {
        return $this->partnervezeteknev;
    }

    public function setPartnervezeteknev($val)
    {
        $this->partnervezeteknev = $val;
    }

    public function getPartnerkeresztnev()
    {
        return $this->partnerkeresztnev;
    }

    public function setPartnerkeresztnev($val)
    {
        $this->partnerkeresztnev = $val;
    }

    public function getPartneradoszam()
    {
        return $this->partneradoszam;
    }

    public function setPartneradoszam($val)
    {
        $this->partneradoszam = $val;
    }

    public function getPartnercjszam()
    {
        return $this->partnercjszam;
    }

    public function setPartnercjszam($val)
    {
        $this->partnercjszam = $val;
    }

    public function getPartnereuadoszam()
    {
        return $this->partnereuadoszam;
    }

    public function setPartnereuadoszam($val)
    {
        $this->partnereuadoszam = $val;
    }

    public function getPartnerfvmszam()
    {
        return $this->partnerfvmszam;
    }

    public function setPartnerfvmszam($val)
    {
        $this->partnerfvmszam = $val;
    }

    public function getPartnerirszam()
    {
        return $this->partnerirszam;
    }

    public function setPartnerirszam($val)
    {
        $this->partnerirszam = $val;
    }

    public function getPartnerjovengszam()
    {
        return $this->partnerjovengszam;
    }

    public function setPartnerjovengszam($val)
    {
        $this->partnerjovengszam = $val;
    }

    public function getPartnerlirszam()
    {
        return $this->partnerlirszam;
    }

    public function setPartnerlirszam($val)
    {
        $this->partnerlirszam = $val;
    }

    public function getPartnerlutca()
    {
        return $this->partnerlutca;
    }

    public function setPartnerlutca($val)
    {
        $this->partnerlutca = $val;
    }

    public function getPartnerlvaros()
    {
        return $this->partnerlvaros;
    }

    public function setPartnerlvaros($val)
    {
        $this->partnerlvaros = $val;
    }

    public function getPartnermukengszam()
    {
        return $this->partnermukengszam;
    }

    public function setPartnermukengszam($val)
    {
        $this->partnermukengszam = $val;
    }

    public function getPartnerostermszam()
    {
        return $this->partnerostermszam;
    }

    public function setPartnerostermszam($val)
    {
        $this->partnerostermszam = $val;
    }

    public function getPartnerstatszamjel()
    {
        return $this->partnerstatszamjel;
    }

    public function setPartnerstatszamjel($val)
    {
        $this->partnerstatszamjel = $val;
    }

    public function getPartnerutca()
    {
        return $this->partnerutca;
    }

    public function setPartnerutca($val)
    {
        $this->partnerutca = $val;
    }

    public function getPartnervalligszam()
    {
        return $this->partnervalligszam;
    }

    public function setPartnervalligszam($val)
    {
        $this->partnervalligszam = $val;
    }

    public function getPartnervaros()
    {
        return $this->partnervaros;
    }

    public function setPartnervaros($val)
    {
        $this->partnervaros = $val;
    }

    /**
     * @return \Entities\Bankszamla
     */
    public function getBankszamla()
    {
        return $this->bankszamla;
    }

    public function getTulajbankszamlaszam()
    {
        return $this->tulajbankszamlaszam;
    }

    public function getBankszamlaId()
    {
        if ($this->bankszamla) {
            return $this->bankszamla->getId();
        }
        return '';
    }

    /**
     * @param \Entities\Bankszamla|null $val
     */
    public function setBankszamla($val = null)
    {
        if ($this->bankszamla !== $val) {
            if (!$val) {
                $this->removeBankszamla();
            } else {
                $this->bankszamla = $val;
                if (!$this->duplication) {
                    $this->tulajbanknev = $val->getBanknev();
                    $this->tulajbankszamlaszam = $val->getSzamlaszam();
                    $this->tulajswift = $val->getSwift();
                    $this->tulajiban = $val->getIban();
                }
            }
        }
    }

    public function removeBankszamla()
    {
        if ($this->bankszamla !== null) {
            $this->bankszamla = null;
            if (!$this->duplication) {
                $this->tulajbanknev = '';
                $this->tulajbankszamlaszam = '';
                $this->tulajswift = '';
                $this->tulajiban = '';
            }
        }
    }

    public function getTulajswift()
    {
        return $this->tulajswift;
    }

    public function getTulajbanknev()
    {
        return $this->tulajbanknev;
    }

    /**
     * @return \Entities\Uzletkoto
     */
    public function getUzletkoto()
    {
        return $this->uzletkoto;
    }

    public function getUzletkotonev()
    {
        return $this->uzletkotonev;
    }

    public function getUzletkotoemail()
    {
        return $this->uzletkotoemail;
    }

    public function getUzletkotoId()
    {
        if ($this->uzletkoto) {
            return $this->uzletkoto->getId();
        }
        return '';
    }

    /**
     * @param \Entities\Uzletkoto $val
     */
    public function setUzletkoto($val)
    {
        if ($this->uzletkoto !== $val) {
            if (!$val) {
                $this->removeUzletkoto();
            } else {
                $this->uzletkoto = $val;
                if (!$this->duplication) {
                    $this->uzletkotonev = $val->getNev();
                    $this->uzletkotoemail = $val->getEmail();
                    $this->uzletkotojutalek = $val->getJutalek();
                }
            }
        }
    }

    public function removeUzletkoto()
    {
        if ($this->uzletkoto !== null) {
            $this->uzletkoto = null;
            if (!$this->duplication) {
                $this->uzletkotonev = '';
                $this->uzletkotoemail = '';
                $this->uzletkotojutalek = 0;
            }
        }
    }

    /**
     * @return \Entities\Uzletkoto
     */
    public function getBelsouzletkoto()
    {
        return $this->belsouzletkoto;
    }

    public function getBelsouzletkotonev()
    {
        return $this->belsouzletkotonev;
    }

    public function getBelsouzletkotoemail()
    {
        return $this->belsouzletkotoemail;
    }

    public function getBelsouzletkotoId()
    {
        if ($this->belsouzletkoto) {
            return $this->belsouzletkoto->getId();
        }
        return '';
    }

    /**
     * @param \Entities\Uzletkoto $val
     */
    public function setBelsouzletkoto($val)
    {
        if ($this->belsouzletkoto !== $val) {
            if (!$val) {
                $this->removeBelsozletkoto();
            } else {
                $this->belsouzletkoto = $val;
                if (!$this->duplication) {
                    $this->belsouzletkotonev = $val->getNev();
                    $this->belsouzletkotoemail = $val->getEmail();
                    $this->belsouzletkotojutalek = $val->getJutalek();
                }
            }
        }
    }

    public function removeBelsouzletkoto()
    {
        if ($this->belsouzletkoto !== null) {
            $this->belsouzletkoto = null;
            if (!$this->duplication) {
                $this->belsouzletkotonev = '';
                $this->belsouzletkotoemail = '';
                $this->belsouzletkotojutalek = 0;
            }
        }
    }

    /**
     * @return \Entities\Raktar
     */
    public function getRaktar()
    {
        return $this->raktar;
    }

    public function getRaktarnev()
    {
        return $this->raktarnev;
    }

    public function getRaktarId()
    {
        if ($this->raktar) {
            return $this->raktar->getId();
        }
        return '';
    }

    /**
     * @param \Entities\Raktar $val
     */
    public function setRaktar($val)
    {
        if (!($val instanceof \Entities\Raktar)) {
            $val = \mkw\store::getEm()->getRepository('Entities\Raktar')->find($val);
        }
        if ($this->raktar !== $val) {
            if (!$val) {
                $this->removeRaktar();
            } else {
                $this->raktar = $val;
                if (!$this->duplication) {
                    $this->raktarnev = $val->getNev();
                }
            }
        }
    }

    public function removeRaktar()
    {
        if ($this->raktar !== null) {
            $this->raktar = null;
            if (!$this->duplication) {
                $this->raktarnev = '';
            }
        }
    }

    public function getErbizonylatszam()
    {
        return $this->erbizonylatszam;
    }

    public function setErbizonylatszam($val)
    {
        $this->erbizonylatszam = $val;
    }

    public function getMegjegyzes()
    {
        return $this->megjegyzes;
    }

    public function setMegjegyzes($val)
    {
        $this->megjegyzes = $val;
    }

    public function getBelsomegjegyzes()
    {
        return $this->belsomegjegyzes;
    }

    public function setBelsomegjegyzes($val)
    {
        $this->belsomegjegyzes = $val;
    }

    public function getLastmod()
    {
        return $this->lastmod;
    }

    public function getLastmodStr()
    {
        if ($this->getLastmod()) {
            return $this->getLastmod()->format(\mkw\store::$DateTimeFormat);
        }
        return '';
    }

    public function clearLastmod()
    {
        $this->lastmod = null;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getCreatedStr()
    {
        if ($this->getCreated()) {
            return $this->getCreated()->format(\mkw\store::$DateTimeFormat);
        }
        return '';
    }

    public function clearCreated()
    {
        $this->created = null;
    }

    public function getSzallnev()
    {
        return $this->szallnev;
    }

    public function setSzallnev($adat)
    {
        $this->szallnev = $adat;
    }

    public function getSzallirszam()
    {
        return $this->szallirszam;
    }

    public function setSzallirszam($adat)
    {
        $this->szallirszam = $adat;
    }

    public function getSzallvaros()
    {
        return $this->szallvaros;
    }

    public function setSzallvaros($adat)
    {
        $this->szallvaros = $adat;
    }

    public function getSzallutca()
    {
        return $this->szallutca;
    }

    public function setSzallutca($adat)
    {
        $this->szallutca = $adat;
    }

    public function getWebshopmessage()
    {
        return $this->webshopmessage;
    }

    public function setWebshopmessage($val)
    {
        $this->webshopmessage = $val;
    }

    public function getCouriermessage()
    {
        return $this->couriermessage;
    }

    public function setCouriermessage($val)
    {
        $this->couriermessage = $val;
    }

    public function getPartnertelefon()
    {
        return $this->partnertelefon;
    }

    public function setPartnertelefon($telefon)
    {
        $this->partnertelefon = $telefon;
    }

    public function getPartneremail()
    {
        return $this->partneremail;
    }

    public function setPartneremail($email)
    {
        $this->partneremail = $email;
    }

    public function getIp()
    {
        return $this->ip;
    }

    public function setIp($val)
    {
        $this->ip = $val;
    }

    public function getReferrer()
    {
        return $this->referrer;
    }

    public function setReferrer($val)
    {
        $this->referrer = $val;
    }

    /**
     * @return \Entities\Bizonylatstatusz
     */
    public function getBizonylatstatusz()
    {
        return $this->bizonylatstatusz;
    }

    public function getBizonylatstatusznev()
    {
        if (!$this->bizonylatstatusznev) {
            $bs = $this->getBizonylatstatusz();
            if ($bs) {
                return $bs->getNev();
            }
            return '';
        }
        return $this->bizonylatstatusznev;
    }

    public function setBizonylatstatusznev($val)
    {
        $this->bizonylatstatusznev = $val;
    }

    public function getBizonylatstatuszcsoport()
    {
        return $this->bizonylatstatuszcsoport;
    }

    public function setBizonylatstatuszcsoport($val)
    {
        $this->bizonylatstatuszcsoport = $val;
    }

    public function getBizonylatstatuszId()
    {
        $fm = $this->getBizonylatstatusz();
        if ($fm) {
            return $fm->getId();
        }
        return '';
    }

    /**
     * @param \Entities\Bizonylatstatusz $val
     */
    public function setBizonylatstatusz($val)
    {
        if (!($val instanceof \Entities\Bizonylatstatusz)) {
            $val = \mkw\store::getEm()->getRepository('Entities\Bizonylatstatusz')->find($val);
        }
        if ($this->bizonylatstatusz !== $val) {
            if (!$val) {
                $this->removeBizonylatstatusz();
            } else {
                $this->bizonylatstatusz = $val;
                if (!$this->duplication) {
                    $this->bizonylatstatusznev = $val->getNev();
                    $this->bizonylatstatuszcsoport = $val->getCsoport();
                }
            }
        }
    }

    public function removeBizonylatstatusz()
    {
        if ($this->bizonylatstatusz !== null) {
            $this->bizonylatstatusz = null;
            if (!$this->duplication) {
                $this->bizonylatstatusznev = '';
                $this->bizonylatstatuszcsoport = '';
            }
        }
    }

    public function getFuvarlevelszam()
    {
        return $this->fuvarlevelszam;
    }

    public function setFuvarlevelszam($adat)
    {
        $this->fuvarlevelszam = $adat;
    }

    /**
     * @return \Entities\Bizonylatfej
     */
    public function getParbizonylatfej()
    {
        return $this->parbizonylatfej;
    }

    public function getParbizonylatfejId()
    {
        if ($this->parbizonylatfej) {
            return $this->parbizonylatfej->getId();
        }
        return '';
    }

    /**
     * @param \Entities\Bizonylatfej $val
     */
    public function setParbizonylatfej($val)
    {
        if ($this->parbizonylatfej !== $val) {
            $this->parbizonylatfej = $val;
            $val->addSzulobizonylatfej($this);
        }
    }

    public function removeParbizonylatfej()
    {
        if ($this->parbizonylatfej !== null) {
            $val = $this->parbizonylatfej;
            $this->parbizonylatfej = null;
            $val->removeSzulobizonylatfej($this);
        }
    }

    public function getSzulobizonylatfejek()
    {
        return $this->szulobizonylatfejek;
    }

    /**
     * @param \Entities\Bizonylatfej $val
     */
    public function addSzulobizonylatfej($val)
    {
        if (!$this->szulobizonylatfejek->contains($val)) {
            $this->szulobizonylatfejek->add($val);
            $val->setParbizonylatfej($this);
        }
    }

    /**
     * @param \Entities\Bizonylatfej $val
     * @return bool
     */
    public function removeSzulobizonylatfej($val)
    {
        if ($this->szulobizonylatfejek->removeElement($val)) {
            $val->removeParbizonylatfej();
            return true;
        }
        return false;
    }

    public function getPartnerCim()
    {
        $a = array($this->partnerirszam, $this->partnervaros);
        $cim = implode(' ', $a);
        $a = array($cim, $this->partnerutca);
        return implode(', ', $a);
    }

    public function getOTPayMSISDN()
    {
        return $this->otpaymsisdn;
    }

    public function setOTPayMSISDN($val)
    {
        $this->otpaymsisdn = $val;
    }

    public function getOTPayMPID()
    {
        return $this->otpaympid;
    }

    public function setOTPayMPID($val)
    {
        $this->otpaympid = $val;
    }

    public function getFizetve()
    {
        return $this->fizetve;
    }

    public function setFizetve($val)
    {
        $this->fizetve = $val;
    }

    public function getMasterPassCorrelationID()
    {
        return $this->masterpasscorrelationid;
    }

    public function setMasterPassCorrelationID($val)
    {
        $this->masterpasscorrelationid = $val;
    }

    public function getMasterPassBankTrxId()
    {
        return $this->masterpassbanktrxid;
    }

    public function setMaterPassBankTrxId($val)
    {
        $this->masterpassbanktrxid = $val;
    }

    public function getMasterPassTrxId()
    {
        return $this->masterpasstrxid;
    }

    public function setMaterPassTrxId($val)
    {
        $this->masterpasstrxid = $val;
    }

    public function getOTPayResult()
    {
        return $this->otpayresult;
    }

    public function setOTPayResult($val)
    {
        $this->otpayresult = $val;
    }

    public function getOTPayResultText()
    {
        return $this->otpayresulttext;
    }

    public function setOTPayResultText($val)
    {
        $this->otpayresulttext = $val;
    }

    public function getPartnerSzamlatipus()
    {
        return $this->partnerszamlatipus;
    }

    public function setPartnerSzamlatipus($val)
    {
        $this->partnerszamlatipus = $val;
    }

    public function getCsomagterminal()
    {
        return $this->csomagterminal;
    }

    public function getCsomagterminalId()
    {
        if ($this->csomagterminal) {
            return $this->csomagterminal->getId();
        }
        return false;
    }

    public function getCsomagterminalIdegenId()
    {
        if ($this->csomagterminal) {
            return $this->csomagterminal->getIdegenid();
        }
        return false;
    }

    public function setCsomagterminal($val)
    {
        if ($this->csomagterminal !== $val) {
            $this->csomagterminal = $val;
        }
    }

    public function removeCsomagterminal()
    {
        if ($this->csomagterminal !== null) {
            $this->csomagterminal = null;
        }
    }

    public function getFoxpostBarcode()
    {
        return $this->foxpostbarcode;
    }

    public function setFoxpostBarcode($adat)
    {
        $this->foxpostbarcode = $adat;
    }

    public function getTraceurl()
    {
        return $this->traceurl;
    }

    public function setTraceurl($adat)
    {
        $this->traceurl = $adat;
    }

    public function getRontott()
    {
        return $this->rontott;
    }

    public function setRontott($adat)
    {
        $this->rontott = $adat;
        if (!$this->duplication) {
            foreach ($this->bizonylattetelek as $bt) {
                $bt->setRontott($adat);
                \mkw\store::getEm()->persist($bt);
            }
        }
    }

    public function getSysmegjegyzes()
    {
        return $this->sysmegjegyzes;
    }

    public function setSysmegjegyzes($adat)
    {
        $this->sysmegjegyzes = $adat;
    }

    public function getFix()
    {
        return $this->fix;
    }

    public function setFix($adat)
    {
        $this->fix = $adat;
    }

    public function getBizonylatnyelv()
    {
        return $this->bizonylatnyelv;
    }

    public function setBizonylatnyelv($adat)
    {
        $this->bizonylatnyelv = $adat;
    }

    public function getReportfile()
    {
        return $this->reportfile;
    }

    public function setReportfile($adat)
    {
        $this->reportfile = $adat;
    }

    public function getKerkul()
    {
        return $this->kerkul;
    }

    public function setKerkul($adat)
    {
        $this->kerkul = $adat;
    }

    public function getTulajiban()
    {
        return $this->tulajiban;
    }

    public function getEsedekesseg1()
    {
        return $this->esedekesseg1;
    }

    public function getEsedekesseg1Str()
    {
        if ($this->getEsedekesseg1()) {
            return $this->getEsedekesseg1()->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    public function setEsedekesseg1($adat = '')
    {
        if (is_a($adat, 'DateTime') || is_a($adat, 'DateTimeImmutable')) {
            $this->esedekesseg1 = $adat;
        } else {
            if ($adat != '') {
                $this->esedekesseg1 = new \DateTime(\mkw\store::convDate($adat));
            } else {
                $this->esedekesseg1 = null;
            }
        }
    }

    public function getFizetendo1()
    {
        return $this->fizetendo1;
    }

    public function setFizetendo1($val)
    {
        $this->fizetendo1 = $val;
    }

    public function getEsedekesseg2()
    {
        return $this->esedekesseg2;
    }

    public function getEsedekesseg2Str()
    {
        if ($this->getEsedekesseg2()) {
            return $this->getEsedekesseg2()->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    public function setEsedekesseg2($adat = '')
    {
        if (is_a($adat, 'DateTime') || is_a($adat, 'DateTimeImmutable')) {
            $this->esedekesseg2 = $adat;
        } else {
            if ($adat != '') {
                $this->esedekesseg2 = new \DateTime(\mkw\store::convDate($adat));
            } else {
                $this->esedekesseg2 = null;
            }
        }
    }

    public function getFizetendo2()
    {
        return $this->fizetendo2;
    }

    public function setFizetendo2($val)
    {
        $this->fizetendo2 = $val;
    }

    public function getEsedekesseg3()
    {
        return $this->esedekesseg3;
    }

    public function getEsedekesseg3Str()
    {
        if ($this->getEsedekesseg3()) {
            return $this->getEsedekesseg3()->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    public function setEsedekesseg3($adat = '')
    {
        if (is_a($adat, 'DateTime') || is_a($adat, 'DateTimeImmutable')) {
            $this->esedekesseg3 = $adat;
        } else {
            if ($adat != '') {
                $this->esedekesseg3 = new \DateTime(\mkw\store::convDate($adat));
            } else {
                $this->esedekesseg3 = null;
            }
        }
    }

    public function getFizetendo3()
    {
        return $this->fizetendo3;
    }

    public function setFizetendo3($val)
    {
        $this->fizetendo3 = $val;
    }

    public function getEsedekesseg4()
    {
        return $this->esedekesseg4;
    }

    public function getEsedekesseg4Str()
    {
        if ($this->getEsedekesseg4()) {
            return $this->getEsedekesseg4()->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    public function setEsedekesseg4($adat = '')
    {
        if (is_a($adat, 'DateTime') || is_a($adat, 'DateTimeImmutable')) {
            $this->esedekesseg4 = $adat;
        } else {
            if ($adat != '') {
                $this->esedekesseg4 = new \DateTime(\mkw\store::convDate($adat));
            } else {
                $this->esedekesseg4 = null;
            }
        }
    }

    public function getFizetendo4()
    {
        return $this->fizetendo4;
    }

    public function setFizetendo4($val)
    {
        $this->fizetendo4 = $val;
    }

    public function getEsedekesseg5()
    {
        return $this->esedekesseg5;
    }

    public function getEsedekesseg5Str()
    {
        if ($this->getEsedekesseg5()) {
            return $this->getEsedekesseg5()->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    public function setEsedekesseg5($adat = '')
    {
        if (is_a($adat, 'DateTime') || is_a($adat, 'DateTimeImmutable')) {
            $this->esedekesseg5 = $adat;
        } else {
            if ($adat != '') {
                $this->esedekesseg5 = new \DateTime(\mkw\store::convDate($adat));
            } else {
                $this->esedekesseg5 = null;
            }
        }
    }

    public function getFizetendo5()
    {
        return $this->fizetendo5;
    }

    public function setFizetendo5($val)
    {
        $this->fizetendo5 = $val;
    }

    public function duplicateFrom($entityB)
    {
        $this->duplication = true;
        $kivetel = array('setParbizonylatfej');
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
        /**
         * foreach($entityB->getBizonylattetelek() as $bt) {
         * $this->addBizonylattetel($bt);
         * }
         */
    }

    /**
     * @param mixed $masterpassbanktrxid
     */
    public function setMasterpassbanktrxid($masterpassbanktrxid)
    {
        $this->masterpassbanktrxid = $masterpassbanktrxid;
    }

    /**
     * @param mixed $masterpasstrxid
     */
    public function setMasterpasstrxid($masterpasstrxid)
    {
        $this->masterpasstrxid = $masterpasstrxid;
    }

    /**
     * @param mixed $raktarnev
     */
    public function setRaktarnev($raktarnev)
    {
        $this->raktarnev = $raktarnev;
    }

    /**
     * @param mixed $szallitasimodnev
     */
    public function setSzallitasimodnev($szallitasimodnev)
    {
        $this->szallitasimodnev = $szallitasimodnev;
    }

    /**
     * @param mixed $trxid
     */
    public function setTrxid($trxid)
    {
        $this->trxid = $trxid;
    }

    /**
     * @param mixed $tulajbanknev
     */
    public function setTulajbanknev($tulajbanknev)
    {
        $this->tulajbanknev = $tulajbanknev;
    }

    /**
     * @param mixed $tulajbankszamlaszam
     */
    public function setTulajbankszamlaszam($tulajbankszamlaszam)
    {
        $this->tulajbankszamlaszam = $tulajbankszamlaszam;
    }

    /**
     * @param mixed $tulajiban
     */
    public function setTulajiban($tulajiban)
    {
        $this->tulajiban = $tulajiban;
    }

    /**
     * @param mixed $tulajswift
     */
    public function setTulajswift($tulajswift)
    {
        $this->tulajswift = $tulajswift;
    }

    /**
     * @param mixed $uzletkotoemail
     */
    public function setUzletkotoemail($uzletkotoemail)
    {
        $this->uzletkotoemail = $uzletkotoemail;
    }

    /**
     * @param mixed $uzletkotojutalek
     */
    public function setUzletkotojutalek($uzletkotojutalek)
    {
        $this->uzletkotojutalek = $uzletkotojutalek;
    }

    /**
     * @param mixed $uzletkotonev
     */
    public function setUzletkotonev($uzletkotonev)
    {
        $this->uzletkotonev = $uzletkotonev;
    }

    /**
     * @param mixed $valutanemnev
     */
    public function setValutanemnev($valutanemnev)
    {
        $this->valutanemnev = $valutanemnev;
    }

    /**
     * @param mixed $fizmodnev
     */
    public function setFizmodnev($fizmodnev)
    {
        $this->fizmodnev = $fizmodnev;
    }

    public function getUzletkotojutalek()
    {
        return $this->uzletkotojutalek;
    }

    /**
     * @return mixed
     */
    public function getMese()
    {
        return $this->mese;
    }

    /**
     * @param mixed $mese
     */
    public function setMese($mese)
    {
        $this->mese = $mese;
    }

    /**
     * @return mixed
     */
    public function getRegmode()
    {
        return $this->regmode;
    }

    /**
     * @param mixed $regmode
     */
    public function setRegmode($regmode)
    {
        $this->regmode = $regmode;
    }

    /**
     * @return mixed
     */
    public function getStornotipus()
    {
        return $this->stornotipus;
    }

    /**
     * @param mixed $stornotipus
     */
    public function setStornotipus($stornotipus)
    {
        $this->stornotipus = $stornotipus;
    }

    /**
     * @return mixed
     */
    public function getTulajegyenivallalkozo()
    {
        return $this->tulajegyenivallalkozo;
    }

    /**
     * @param mixed $tulajegyenivallalkozo
     */
    public function setTulajegyenivallalkozo($tulajegyenivallalkozo)
    {
        $this->tulajegyenivallalkozo = $tulajegyenivallalkozo;
    }

    /**
     * @return mixed
     */
    public function getTulajevnev()
    {
        return $this->tulajevnev;
    }

    /**
     * @param mixed $tulajevnev
     */
    public function setTulajevnev($tulajevnev)
    {
        $this->tulajevnev = $tulajevnev;
    }

    /**
     * @return mixed
     */
    public function getTulajevnyilvszam()
    {
        return $this->tulajevnyilvszam;
    }

    /**
     * @param mixed $tulajevnyilvszam
     */
    public function setTulajevnyilvszam($tulajevnyilvszam)
    {
        $this->tulajevnyilvszam = $tulajevnyilvszam;
    }

    /**
     * @return mixed
     */
    public function getTulajkisadozo()
    {
        return $this->tulajkisadozo;
    }

    /**
     * @param mixed $tulajkisadozo
     */
    public function setTulajkisadozo($tulajkisadozo)
    {
        $this->tulajkisadozo = $tulajkisadozo;
    }

    /**
     * @return boolean
     */
    public function isKellszallitasikoltsegetszamolni()
    {
        if ($this->kellszallitasikoltsegetszamolni) {
            if ($this->kupon) {
                $k = $this->getKuponObject();
                if ($k && $k->isIngyenSzallitas() && $k->isErvenyes()) {
                    return false;
                }
            }
        }
        return $this->kellszallitasikoltsegetszamolni;
    }

    /**
     * @param boolean $kellszallitasikoltsegetszamolni
     */
    public function setKellszallitasikoltsegetszamolni($kellszallitasikoltsegetszamolni)
    {
        $this->kellszallitasikoltsegetszamolni = $kellszallitasikoltsegetszamolni;
    }

    /**
     * @return mixed
     */
    public function getSzallitasikoltsegbrutto()
    {
        return $this->szallitasikoltsegbrutto;
    }

    /**
     * @param mixed $szallitasikoltsegbrutto
     */
    public function setSzallitasikoltsegbrutto($szallitasikoltsegbrutto)
    {
        $this->szallitasikoltsegbrutto = $szallitasikoltsegbrutto;
    }

    /**
     * @return mixed
     */
    public function getPartnerfeketelistas()
    {
        return $this->partnerfeketelistas;
    }

    /**
     * @param mixed $partnerfeketelistas
     */
    public function setPartnerfeketelistas($partnerfeketelistas)
    {
        $this->partnerfeketelistas = $partnerfeketelistas;
    }

    /**
     * @return mixed
     */
    public function getPartnerfeketelistaok()
    {
        return $this->partnerfeketelistaok;
    }

    /**
     * @param mixed $partnerfeketelistaok
     */
    public function setPartnerfeketelistaok($partnerfeketelistaok)
    {
        $this->partnerfeketelistaok = $partnerfeketelistaok;
    }

    /**
     * @return mixed
     */
    public function getKupon()
    {
        return $this->kupon;
    }

    /**
     * @param mixed $kupon
     */
    public function setKupon($kupon)
    {
        $this->kupon = $kupon;
    }

    public function getKuponObject()
    {
        if ($this->kupon) {
            return \mkw\store::getEm()->getRepository('\Entities\Kupon')->find($this->kupon);
        }
        return false;
    }

    /**
     * @return mixed
     */
    public function getFakekifizetve()
    {
        return $this->fakekifizetve;
    }

    /**
     * @param mixed $fakekifizetve
     */
    public function setFakekifizetve($fakekifizetve)
    {
        $this->fakekifizetve = $fakekifizetve;
    }

    /**
     * @return mixed
     */
    public function getFakekintlevoseg()
    {
        return $this->fakekintlevoseg;
    }

    /**
     * @param mixed $fakekintlevoseg
     */
    public function setFakekintlevoseg($fakekintlevoseg)
    {
        $this->fakekintlevoseg = $fakekintlevoseg;
    }

    public function getFakekifizetesdatum()
    {
        return $this->fakekifizetesdatum;
    }

    public function getFakekifizetesdatumStr()
    {
        if ($this->getFakekifizetesdatum()) {
            return $this->getFakekifizetesdatum()->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    public function setFakekifizetesdatum($adat = '')
    {
        if (is_a($adat, 'DateTime')) {
            $this->fakekifizetesdatum = $adat;
        } else {
            if (!$adat) {
                $this->fakekifizetesdatum = null;
            } else {
                $this->fakekifizetesdatum = new \DateTime(\mkw\store::convDate($adat));
            }
        }
    }

    /**
     * @return mixed
     */
    public function getBelsouzletkotojutalek()
    {
        return $this->belsouzletkotojutalek;
    }

    /**
     * @param mixed $belsouzletkotojutalek
     */
    public function setBelsouzletkotojutalek($belsouzletkotojutalek)
    {
        $this->belsouzletkotojutalek = $belsouzletkotojutalek;
    }

    /**
     * @return mixed
     */
    public function getPartnerktdatvallal()
    {
        return $this->partnerktdatvallal;
    }

    /**
     * @param mixed $partnerktdatvallal
     */
    public function setPartnerktdatvallal($partnerktdatvallal)
    {
        $this->partnerktdatvallal = $partnerktdatvallal;
    }

    /**
     * @return mixed
     */
    public function getPartnerktdatalany()
    {
        return $this->partnerktdatalany;
    }

    /**
     * @param mixed $partnerktdatalany
     */
    public function setPartnerktdatalany($partnerktdatalany)
    {
        $this->partnerktdatalany = $partnerktdatalany;
    }

    /**
     * @return mixed
     */
    public function getPartnerktdszerzszam()
    {
        return $this->partnerktdszerzszam;
    }

    /**
     * @param mixed $partnerktdszerzszam
     */
    public function setPartnerktdszerzszam($partnerktdszerzszam)
    {
        $this->partnerktdszerzszam = $partnerktdszerzszam;
    }

    public function getTranslations()
    {
        return $this->translations;
    }

    public function addTranslation(BizonylatfejTranslation $t)
    {
        if (!$this->translations->contains($t)) {
            $this->translations[] = $t;
            $t->setObject($this);
        }
    }

    public function removeTranslation(BizonylatfejTranslation $t)
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
     * @return \Entities\Felhasznalo
     */
    public function getFelhasznalo()
    {
        return $this->felhasznalo;
    }

    public function getFelhasznalonev()
    {
        return $this->felhasznalonev;
    }

    public function getFelhasznaloId()
    {
        $fm = $this->getFelhasznalo();
        if ($fm) {
            return $fm->getId();
        }
        return '';
    }

    /**
     * @param \Entities\Felhasznalo $val
     */
    public function setFelhasznalo($val)
    {
        if (!($val instanceof \Entities\Dolgozo)) {
            $val = \mkw\store::getEm()->getRepository('Entities\Dolgozo')->find($val);
        }
        if ($this->felhasznalo !== $val) {
            if (!$val) {
                $this->removeFelhasznalo();
            } else {
                $this->felhasznalo = $val;
                if (!$this->duplication) {
                    $this->felhasznalonev = $val->getNev();
                }
            }
        }
    }

    public function removeFelhasznalo()
    {
        if ($this->felhasznalo !== null) {
            $this->felhasznalo = null;
            if (!$this->duplication) {
                $this->felhasznalonev = '';
            }
        }
    }

    /**
     * @return mixed
     */
    public function getCreatedby()
    {
        return $this->createdby;
    }

    public function getCreatedbyId()
    {
        if ($this->createdby) {
            return $this->createdby->getId();
        }
        return null;
    }

    public function getCreatedbyNev()
    {
        if ($this->createdby) {
            return $this->createdby->getNev();
        }
        return null;
    }

    /**
     * @return mixed
     */
    public function getUpdatedby()
    {
        return $this->updatedby;
    }

    public function getUpdatedbyId()
    {
        if ($this->updatedby) {
            return $this->updatedby->getId();
        }
        return null;
    }

    public function getUpdatedbyNev()
    {
        if ($this->updatedby) {
            return $this->updatedby->getNev();
        }
        return null;
    }

    public function getSzepkartyaervenyesseg()
    {
        return $this->szepkartyaervenyesseg;
    }

    public function getSzepkartyaervenyessegStr()
    {
        if ($this->getSzepkartyaervenyesseg()) {
            return $this->getSzepkartyaervenyesseg()->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    public function setSzepkartyaervenyesseg($adat = '')
    {
        if (is_a($adat, 'DateTime')) {
            $this->szepkartyaervenyesseg = $adat;
        } else {
            if ($adat == '') {
                $adat = date(\mkw\store::$DateFormat);
            }
            $this->szepkartyaervenyesseg = new \DateTime(\mkw\store::convDate($adat));
        }
    }

    /**
     * @return mixed
     */
    public function getPartnerorszagnev()
    {
        return $this->partnerorszagnev;
    }

    /**
     * @param mixed $partnerorszagnev
     */
    public function setPartnerorszagnev($partnerorszagnev)
    {
        $this->partnerorszagnev = $partnerorszagnev;
    }

    /**
     * @return mixed
     */
    public function getSzepkartyaszam()
    {
        return $this->szepkartyaszam;
    }

    /**
     * @param mixed $szepkartyaszam
     */
    public function setSzepkartyaszam($szepkartyaszam)
    {
        $this->szepkartyaszam = $szepkartyaszam;
    }

    /**
     * @return mixed
     */
    public function getSzepkartyatipus()
    {
        return $this->szepkartyatipus;
    }

    public function getSzepkartyatipusNev()
    {
        $szp = array(1 => 'OTP', 2 => 'MKB', 3 => 'K&H');
        if ($this->szepkartyatipus >= 1 && $this->szepkartyatipus <= 3) {
            return $szp[$this->szepkartyatipus];
        }
        return '';
    }

    /**
     * @param mixed $szepkartyatipus
     */
    public function setSzepkartyatipus($szepkartyatipus)
    {
        $this->szepkartyatipus = $szepkartyatipus;
    }

    /**
     * @return \Entities\Orszag
     */
    public function getPartnerorszag()
    {
        return $this->partnerorszag;
    }

    public function getPartnerorszagId()
    {
        $fm = $this->getPartnerorszag();
        if ($fm) {
            return $fm->getId();
        }
        return '';
    }

    /**
     * @param \Entities\Orszag $val
     */
    public function setPartnerorszag($val)
    {
        if (!($val instanceof \Entities\Orszag)) {
            $val = \mkw\store::getEm()->getRepository('Entities\Orszag')->find($val);
        }
        if ($this->partnerorszag !== $val) {
            if (!$val) {
                $this->removePartnerorszag();
            } else {
                $this->partnerorszag = $val;
                if (!$this->duplication) {
                    $this->partnerorszagnev = $val->getNev();
                    $this->partnerorszagiso3166 = $val->getIso3166();
                }
            }
        }
    }

    public function removePartnerorszag()
    {
        if ($this->partnerorszag !== null) {
            $this->partnerorszag = null;
            if (!$this->duplication) {
                $this->partnerorszagnev = '';
                $this->partnerorszagiso3166 = '';
            }
        }
    }

    /**
     * @return mixed
     */
    public function getSzepkartyanev()
    {
        return $this->szepkartyanev;
    }

    /**
     * @param mixed $szepkartyanev
     */
    public function setSzepkartyanev($szepkartyanev)
    {
        $this->szepkartyanev = $szepkartyanev;
    }

    /**
     * @return mixed
     */
    public function getNincspenzmozgas()
    {
        return $this->nincspenzmozgas;
    }

    /**
     * @param mixed $nincspenzmozgas
     */
    public function setNincspenzmozgas($nincspenzmozgas)
    {
        $this->nincspenzmozgas = $nincspenzmozgas;
    }

    /**
     * @return mixed
     */
    public function getEmagid()
    {
        return $this->emagid;
    }

    /**
     * @param mixed $emagid
     */
    public function setEmagid($emagid)
    {
        $this->emagid = $emagid;
    }

    /**
     * @return mixed
     */
    public function getPartnerhazszam()
    {
        return $this->partnerhazszam;
    }

    /**
     * @param mixed $partnerhazszam
     */
    public function setPartnerhazszam($partnerhazszam)
    {
        $this->partnerhazszam = $partnerhazszam;
    }

    /**
     * @return mixed
     */
    public function getPartnerlhazszam()
    {
        return $this->partnerlhazszam;
    }

    /**
     * @param mixed $partnerlhazszam
     */
    public function setPartnerlhazszam($partnerlhazszam)
    {
        $this->partnerlhazszam = $partnerlhazszam;
    }

    public function getSzallitasiCim()
    {
        $a = array($this->szallirszam, $this->szallvaros);
        $cim = implode(' ', $a);
        $a = array($cim, $this->szallutca);
        return implode(', ', $a);
    }

    /**
     * @return mixed
     */
    public function getSzallhazszam()
    {
        return $this->szallhazszam;
    }

    /**
     * @param mixed $szallhazszam
     */
    public function setSzallhazszam($szallhazszam)
    {
        $this->szallhazszam = $szallhazszam;
    }

    /**
     * @return bool
     */
    public function isSimpleedit()
    {
        return $this->simpleedit;
    }

    /**
     * @param bool $simpleedit
     */
    public function setSimpleedit($simpleedit)
    {
        $this->simpleedit = $simpleedit;
    }

    /**
     * @return mixed
     */
    public function getSzepkartyakifizetve()
    {
        return $this->szepkartyakifizetve;
    }

    /**
     * @param mixed $szepkartyakifizetve
     */
    public function setSzepkartyakifizetve($szepkartyakifizetve)
    {
        $this->szepkartyakifizetve = $szepkartyakifizetve;
    }

    /**
     * @return mixed
     */
    public function getProgramnev()
    {
        return $this->programnev;
    }

    /**
     * @param mixed $programnev
     */
    public function setProgramnev($programnev)
    {
        $this->programnev = $programnev;
    }

    /**
     * @return mixed
     */
    public function getBarionpaymentid()
    {
        return $this->barionpaymentid;
    }

    /**
     * @param mixed $barionpaymentid
     */
    public function setBarionpaymentid($barionpaymentid)
    {
        $this->barionpaymentid = $barionpaymentid;
    }

    /**
     * @return mixed
     */
    public function getBarionpaymentstatus()
    {
        return $this->barionpaymentstatus;
    }

    /**
     * @param mixed $barionpaymentstatus
     */
    public function setBarionpaymentstatus($barionpaymentstatus)
    {
        $this->barionpaymentstatus = $barionpaymentstatus;
    }

    /**
     * @return mixed
     */
    public function getTulajjovengszam()
    {
        return $this->tulajjovengszam;
    }

    /**
     * @param mixed $tulajjovengszam
     */
    public function setTulajjovengszam($tulajjovengszam)
    {
        $this->tulajjovengszam = $tulajjovengszam;
    }

    /**
     * @return mixed
     */
    public function getGyujtoszamla()
    {
        return $this->gyujtoszamla;
    }

    /**
     * @param mixed $gyujtoszamla
     */
    public function setGyujtoszamla($gyujtoszamla)
    {
        $this->gyujtoszamla = $gyujtoszamla;
    }

    public function getGyujtoidoszakeleje()
    {
        return $this->gyujtoidoszakeleje;
    }

    public function getGyujtoidoszakelejeStr()
    {
        if ($this->getGyujtoidoszakeleje()) {
            return $this->getGyujtoidoszakeleje()->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    public function setGyujtoidoszakeleje($adat = '')
    {
        if (is_a($adat, 'DateTime')) {
            $this->gyujtoidoszakeleje = $adat;
        } else {
            if ($adat == '') {
                $adat = date(\mkw\store::$DateFormat);
            }
            $this->gyujtoidoszakeleje = new \DateTime(\mkw\store::convDate($adat));
        }
    }

    public function getGyujtoidoszakvege()
    {
        return $this->gyujtoidoszakvege;
    }

    public function getGyujtoidoszakvegeStr()
    {
        if ($this->getGyujtoidoszakvege()) {
            return $this->getGyujtoidoszakvege()->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    public function setGyujtoidoszakvege($adat = '')
    {
        if (is_a($adat, 'DateTime')) {
            $this->gyujtoidoszakvege = $adat;
        } else {
            if ($adat == '') {
                $adat = date(\mkw\store::$DateFormat);
            }
            $this->gyujtoidoszakvege = new \DateTime(\mkw\store::convDate($adat));
        }
    }

    /**
     * @return mixed
     */
    public function getPartnerorszagiso3166()
    {
        if ($this->partnerorszagiso3166) {
            return $this->partnerorszagiso3166;
        }
        if ($this->getPartnerorszag()) {
            return $this->getPartnerorszag()->getIso3166();
        }
        return 'HU';
    }

    /**
     * @param mixed $partnerorszagiso3166
     */
    public function setPartnerorszagiso3166($partnerorszagiso3166)
    {
        $this->partnerorszagiso3166 = $partnerorszagiso3166;
    }

    /**
     * @return mixed
     */
    public function getSzallitasiido()
    {
        return $this->szallitasiido;
    }

    public function getSzallitasiidoDatumStr()
    {
        if ($this->kelt && $this->getSzallitasiido()) {
            /** @var \DateTime $k */
            $k = clone $this->kelt;
            if ($k->format('w') == 6) {
                $k->add(new \DateInterval('P2D'));
            } elseif ($k->format('w') == 0) {
                $k->add(new \DateInterval('P1D'));
            }
            $v = $this->getSzallitasiido();
            for ($i = 1; $i <= $v; $i++) {
                $k->add(new \DateInterval('P1D'));
                if ($k->format('w') == 6) {
                    $k->add(new \DateInterval('P2D'));
                }
            }

            $ur = \mkw\store::getEm()->getRepository('Entities\Unnepnap');
            $unnep = $ur->countUnnepnap($this->getKelt(), $k);
            if ($unnep) {
                for ($i = 1; $i <= $unnep; $i++) {
                    $k->add(new \DateInterval('P1D'));
                    if ($k->format('w') == 6) {
                        $k->add(new \DateInterval('P2D'));
                    }
                }
            }

            if (\mkw\store::isFoxpostSzallitasimod($this->getSzallitasimodId())) {
                $r = $k->format(\mkw\store::$DateFormat);
                $k->add(new \DateInterval('P2D'));
                $r = $r . ' - ' . $k->format(\mkw\store::$DateFormat);
                return $r;
            }
            //$k->add(new \DateInterval('P' . $this->getSzallitasiido() . 'D'));
            return $k->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    /**
     * @param mixed $szallitasiido
     */
    public function setSzallitasiido($szallitasiido)
    {
        $this->szallitasiido = $szallitasiido;
    }

    public function calcSzallitasiido()
    {
        $szallido = 0;
        /** @var \Entities\Bizonylattetel $tetel */
        foreach ($this->getBizonylattetelek() as $tetel) {
            $termek = $tetel->getTermek();
            if ($termek) {
                $sorszallido = $termek->calcSzallitasiido($tetel->getTermekvaltozat(), $tetel->getMennyiseg(), $this->getId());
                if ($szallido < $sorszallido) {
                    $szallido = $sorszallido;
                }
            }
        }
        $szallido = $szallido + \mkw\store::calcSzallitasiidoAddition($this->getCreated());
        $this->setSzallitasiido($szallido);
    }

    /**
     * @return mixed
     */
    public function getGlsparcelid()
    {
        return $this->glsparcelid;
    }

    /**
     * @param mixed $glsparcelid
     */
    public function setGlsparcelid($glsparcelid)
    {
        $this->glsparcelid = $glsparcelid;
    }

    /**
     * @return mixed
     */
    public function getGlsparcellabelurl($pre = '/')
    {
        if ($this->glsparcellabelurl) {
            if ($this->glsparcellabelurl[0] !== $pre) {
                return $pre . $this->glsparcellabelurl;
            } else {
                return $this->glsparcellabelurl;
            }
        }
        return '';
    }

    /**
     * @param mixed $glsparcellabelurl
     */
    public function setGlsparcellabelurl($glsparcellabelurl)
    {
        $this->glsparcellabelurl = $glsparcellabelurl;
    }

    /**
     * @return bool
     */
    public function isNavbekuldendo()
    {
        return $this->navbekuldendo;
    }

    /**
     * @param bool $navbekuldendo
     */
    public function setNavbekuldendo($navbekuldendo)
    {
        $this->navbekuldendo = $navbekuldendo;
    }

    /**
     * @return bool
     */
    public function isNavbekuldve()
    {
        return (bool)$this->getNaveredmeny();
    }

    /**
     * @return mixed
     */
    public function getNaveredmeny()
    {
        return $this->naveredmeny;
    }

    /**
     * @param mixed $naveredmeny
     */
    public function setNaveredmeny($naveredmeny)
    {
        $this->naveredmeny = $naveredmeny;
    }

    /**
     * @return bool
     */
    public function isForditottadozas()
    {
        return $this->forditottadozas;
    }

    /**
     * @param bool $forditottadozas
     */
    public function setForditottadozas($forditottadozas)
    {
        $this->forditottadozas = $forditottadozas;
    }

    /**
     * @return string
     */
    public function getPartnerthirdadoszam()
    {
        return $this->partnerthirdadoszam;
    }

    /**
     * @param string $partnerthirdadoszam
     */
    public function setPartnerthirdadoszam($partnerthirdadoszam)
    {
        $this->partnerthirdadoszam = $partnerthirdadoszam;
    }

    /**
     * @return int
     */
    public function getPartnervatstatus()
    {
        if (!$this->partnervatstatus) {
            if ($this->partner) {
                return $this->partner->getVatstatus();
            }
        }
        return $this->partnervatstatus;
    }

    /**
     * @param int $partnervatstatus
     */
    public function setPartnervatstatus($partnervatstatus): void
    {
        $this->partnervatstatus = $partnervatstatus;
    }

    public function getBizonylatDokok()
    {
        return $this->bizonylatdokok;
    }

    public function addBizonylatDok(BizonylatDok $dok)
    {
        $this->bizonylatdokok->add($dok);
        $dok->setBizonylat($this);
    }

    public function removeBizonylatDok(BizonylatDok $dok)
    {
        if ($this->bizonylatdokok->removeElement($dok)) {
            $dok->removeBizonylat($this);
            return true;
        }
        return false;
    }

    /**
     * @return string
     */
    public function getPartnerszamlaegyeb()
    {
        return $this->partnerszamlaegyeb;
    }

    /**
     * @param string $partnerszamlaegyeb
     */
    public function setPartnerszamlaegyeb($partnerszamlaegyeb): void
    {
        $this->partnerszamlaegyeb = $partnerszamlaegyeb;
    }

    /**
     * @return mixed
     */
    public function getTermekertekelesid()
    {
        return $this->termekertekelesid;
    }

    /**
     * @param mixed $termekertekelesid
     */
    public function setTermekertekelesid($termekertekelesid): void
    {
        $this->termekertekelesid = $termekertekelesid;
    }

    public function generateTermekertekelesid()
    {
        $this->setTermekertekelesid(md5(uniqid($this->getId(), true)));
    }

    public function isVanMitErtekelni()
    {
        $vanmit = false;
        if ($this->getBizonylatstatusz() && !$this->getBizonylatstatusz()->getNemertekelheto()) {
            /** @var Bizonylattetel $tetel */
            foreach ($this->bizonylattetelek as $tetel) {
                if (!$tetel->isMarErtekelt() &&
                    !\mkw\store::isSzallitasiKtgTermek($tetel->getTermekId())
                ) {
                    $vanmit = true;
                }
            }
        }
        return $vanmit;
    }

    /**
     * @return bool
     */
    public function isTermekertekeleskikuldve()
    {
        return $this->termekertekeleskikuldve;
    }

    /**
     * @param bool $termekertekeleskikuldve
     */
    public function setTermekertekeleskikuldve($termekertekeleskikuldve): void
    {
        $this->termekertekeleskikuldve = $termekertekeleskikuldve;
    }

}
