<?php

namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity(repositoryClass="Entities\PartnerRepository")
 * @ORM\Table(name="partner",
 *    options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"},
 * indexes={
 *      @ORM\Index(name="partneremail_idx",columns={"email"})
 * })
 * */
class Partner
{

    private $skipListener;

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

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $sessionid = '';

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $jelszo;

    /** @ORM\Column(type="smallint",nullable=true) */
    private $inaktiv = 0;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $nev = '';

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $vezeteknev = '';

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $keresztnev = '';

    /** @ORM\Column(type="string",length=13,nullable=true) */
    private $adoszam = '';

    /** @ORM\Column(type="string",length=50,nullable=true) */
    private $csoportosadoszam = '';

    /** @ORM\Column(type="string",length=30,nullable=true) */
    private $euadoszam = '';

    /** @ORM\Column(type="string",length=50,nullable=true) */
    private $thirdadoszam = '';

    /** @ORM\Column(type="string",length=20,nullable=true) */
    private $mukengszam = '';

    /** @ORM\Column(type="string",length=20,nullable=true) */
    private $jovengszam = '';

    /** @ORM\Column(type="string",length=20,nullable=true) */
    private $ostermszam = '';

    /** @ORM\Column(type="string",length=20,nullable=true) */
    private $valligszam = '';

    /** @ORM\Column(type="string",length=20,nullable=true) */
    private $fvmszam = '';

    /** @ORM\Column(type="string",length=20,nullable=true) */
    private $cjszam = '';

    /** @ORM\Column(type="string",length=20,nullable=true) */
    private $statszamjel = '';

    /** @ORM\Column(type="string",length=10,nullable=true) */
    private $irszam = '';

    /** @ORM\Column(type="string",length=40,nullable=true) */
    private $varos = '';

    /** @ORM\Column(type="string",length=60,nullable=true) */
    private $utca = '';

    /** @ORM\Column(type="string",length=40,nullable=true) */
    private $hazszam = '';

    /** @ORM\Column(type="string",length=10,nullable=true) */
    private $lirszam = '';

    /** @ORM\Column(type="string",length=40,nullable=true) */
    private $lvaros = '';

    /** @ORM\Column(type="string",length=60,nullable=true) */
    private $lutca = '';

    /** @ORM\Column(type="string",length=40,nullable=true) */
    private $lhazszam = '';

    /** @ORM\Column(type="string",length=60,nullable=true) */
    private $telefon = '';

    /** @ORM\Column(type="string",length=60,nullable=true) */
    private $mobil = '';

    /** @ORM\Column(type="string",length=6,nullable=true) */
    private $telkorzet;

    /** @ORM\Column(type="string",length=40,nullable=true) */
    private $telszam = '';

    /** @ORM\Column(type="string",length=40,nullable=true) */
    private $fax = '';

    /** @ORM\Column(type="string",length=100,nullable=true) */
    private $email = '';

    /** @ORM\Column(type="string",length=200,nullable=true) */
    private $honlap = '';

    /** @ORM\Column(type="text",nullable=true) */
    private $megjegyzes = '';

    /** @ORM\Column(type="string",length=36,nullable=true) */
    private $syncid = '';

    /**
     * @ORM\ManyToOne(targetEntity="Uzletkoto",inversedBy="partnerek")
     * @ORM\JoinColumn(name="uzletkoto_id", referencedColumnName="id",nullable=true,onDelete="set null")
     */
    private $uzletkoto;

    /** @ORM\OneToMany(targetEntity="Teendo", mappedBy="partner", cascade={"persist"}) */
    private $teendok;

    /** @ORM\OneToMany(targetEntity="Esemeny", mappedBy="partner", cascade={"persist"}) */
    private $esemenyek;

    /**
     * @ORM\ManyToMany(targetEntity="Partnercimketorzs",inversedBy="partnerek")
     * @ORM\JoinTable(name="partner_cimkek",
     *  joinColumns={@ORM\JoinColumn(name="partner_id",referencedColumnName="id",onDelete="cascade")},
     *  inverseJoinColumns={@ORM\JoinColumn(name="cimketorzs_id",referencedColumnName="id",onDelete="cascade")}
     *  )
     */
    private $cimkek;

    /**
     * @ORM\ManyToOne(targetEntity="Fizmod")
     * @ORM\JoinColumn(name="fizmod_id",referencedColumnName="id",nullable=true,onDelete="set null")
     */
    private $fizmod;

    /** @ORM\OneToMany(targetEntity="Bizonylatfej", mappedBy="partner",cascade={"persist"}) */
    private $bizonylatfejek;

    /** @ORM\OneToMany(targetEntity="MPTFolyoszamla", mappedBy="partner",cascade={"persist"})
     * @ORM\OrderBy({"vonatkozoev" = "DESC", "irany" = "ASC"})
     * */
    private $mptfolyoszamlak;

    /** @ORM\OneToMany(targetEntity="Bankbizonylatfej", mappedBy="partner",cascade={"persist"}) */
    private $bankbizonylatfejek;

    /** @ORM\OneToMany(targetEntity="Bankbizonylattetel", mappedBy="partner",cascade={"persist"}) */
    private $bankbizonylattetelek;

    /** @ORM\OneToMany(targetEntity="Penztarbizonylatfej", mappedBy="partner",cascade={"persist"}) */
    private $penztarbizonylatfejek;

    /** @ORM\OneToMany(targetEntity="Kosar", mappedBy="partner",cascade={"persist"}) */
    private $kosarak;

    /** @ORM\Column(type="integer",nullable=true) */
    private $fizhatido = 0;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $szlanev = '';

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

    /** @ORM\Column(type="integer",nullable=true) */
    private $nem;

    /** @ORM\Column(type="date",nullable=true) */
    private $szuletesiido;

    /** @ORM\Column(type="boolean") */
    private $akcioshirlevelkell = false;

    /** @ORM\Column(type="boolean") */
    private $ujdonsaghirlevelkell = false;

    /** @ORM\Column(type="datetime",nullable=true) */
    private $utolsoklikk;

    /** @ORM\OneToMany(targetEntity="TermekErtesito", mappedBy="partner",cascade={"persist"}) */
    private $termekertesitok;

    /** @ORM\Column(type="boolean") */
    private $vendeg = false;

    /** @ORM\Column(type="string",length=32,nullable=true) */
    private $ip;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $referrer;

    /** @ORM\Column(type="boolean") */
    private $szallito = false;

    /** @ORM\Column(type="boolean") */
    private $exportbacsakkeszlet = false;

    /** @ORM\Column(type="string",length=64,nullable=true) */
    private $passwordreminder;

    /** @ORM\Column(type="string",length=64,nullable=true) */
    private $oldloginname;

    /** @ORM\Column(type="integer",nullable=true) */
    private $szallitasiido;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $banknev;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $bankcim;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $iban;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $swift;

    /** @ORM\Column(type="integer",nullable=false) */
    private $szamlatipus = 0;

    /** @ORM\Column(type="string",length=10,nullable=true) */
    private $bizonylatnyelv;

    /**
     * @ORM\ManyToOne(targetEntity="Valutanem",inversedBy="partnerek")
     * @ORM\JoinColumn(name="valutanem_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $valutanem;

    /**
     * @ORM\ManyToOne(targetEntity="Szallitasimod",inversedBy="partnerek")
     * @ORM\JoinColumn(name="szallitasimod_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $szallitasimod;

    /** @ORM\OneToMany(targetEntity="PartnerTermekcsoportKedvezmeny", mappedBy="partner", cascade={"persist", "remove"}) */
    private $termekcsoportkedvezmenyek;

    /** @ORM\OneToMany(targetEntity="PartnerTermekKedvezmeny", mappedBy="partner", cascade={"persist", "remove"}) */
    private $termekkedvezmenyek;

    /**
     * @ORM\ManyToOne(targetEntity="Partnertipus",inversedBy="partnerek")
     * @ORM\JoinColumn(name="partnertipus_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $partnertipus;

    /**
     * @ORM\ManyToOne(targetEntity="Orszag",inversedBy="partnerek")
     * @ORM\JoinColumn(name="orszag_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $orszag;

    /**
     * @ORM\ManyToOne(targetEntity="Orszag")
     * @ORM\JoinColumn(name="szallorszag_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $szallorszag;

    /** @ORM\Column(type="integer",nullable=true) */
    private $mijszmiotajogazik = 0;

    /** @ORM\Column(type="integer",nullable=true) */
    private $mijszmiotatanit = 0;

    /**
     * @ORM\Column(type="boolean")
     */
    private $ezuzletkoto = false;

    /** @ORM\OneToMany(targetEntity="PartnerMIJSZOklevel", mappedBy="partner", cascade={"persist", "remove"}) */
    private $mijszoklevelek;

    /** @ORM\OneToMany(targetEntity="PartnerMIJSZPune", mappedBy="partner", cascade={"persist", "remove"}) */
    private $mijszpune;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $mijszmembershipbesideshu;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $mijszbusiness;

    /** @ORM\Column(type="boolean") */
    private $mijszexporttiltva = false;

    /** @ORM\Column(type="integer", nullable=true) */
    private $migrid;

    /** @ORM\Column(type="boolean") */
    private $ktdatvallal = false;

    /** @ORM\Column(type="boolean") */
    private $ktdatalany = false;

    /** @ORM\Column(type="string",length=20,nullable=true) */
    private $ktdszerzszam;

    /** @ORM\OneToMany(targetEntity="Kontakt", mappedBy="partner",cascade={"persist"}) */
    private $kontaktok;

    /** @ORM\Column(type="integer", nullable=true) */
    private $minicrmprojectid;

    /** @ORM\Column(type="integer", nullable=true) */
    private $minicrmcontactid;
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
    private $munkahelyneve;
    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $foglalkozas;
    /** @ORM\OneToMany(targetEntity="PartnerMIJSZOralatogatas", mappedBy="partner", cascade={"persist", "remove"}) */
    private $mijszoralatogatas;
    /** @ORM\OneToMany(targetEntity="PartnerMIJSZOralatogatas", mappedBy="tanar", cascade={"persist", "remove"}) */
    private $mijszoralatogatastanar;
    /** @ORM\OneToMany(targetEntity="PartnerMIJSZTanitas", mappedBy="partner", cascade={"persist", "remove"}) */
    private $mijsztanitas;
    /** @ORM\OneToMany(targetEntity="PartnerDok", mappedBy="partner", cascade={"persist", "remove"}) */
    private $partnerdokok;
    /** @ORM\Column(type="integer",nullable=true) */
    private $emagid;
    /** @ORM\Column(type="boolean") */
    private $anonymizalnikell = false;
    /** @ORM\Column(type="date",nullable=true) */
    private $anonymkeresdatum;
    /** @ORM\Column(type="boolean") */
    private $anonym = false;
    /** @ORM\Column(type="date",nullable=true) */
    private $anonymdatum;
    /** @ORM\Column(type="boolean") */
    private $apireg = false;
    /**
     * @ORM\ManyToOne(targetEntity="Apiconsumer")
     * @ORM\JoinColumn(name="apiconsumer_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Apiconsumer
     */
    private $apiconsumer;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $apiconsumernev;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $szamlalevelmegszolitas;

    /** @ORM\Column(type="boolean") */
    private $kulsos = false;

    /** @ORM\Column(type="boolean") */
    private $mennyisegetlathat = false;

    /** @ORM\Column(type="integer",nullable=true) */
    private $vatstatus = 0;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $szamlaegyeb;

    /** @ORM\Column(type="string",length=55,nullable=true) */
    private $mpt_username;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $mpt_password;

    /** @ORM\Column(type="datetime",nullable=true) */
    private $mpt_registerdate;

    /** @ORM\Column(type="datetime",nullable=true) */
    private $mpt_lastvisit;

    /** @ORM\Column(type="datetime",nullable=true) */
    private $mpt_lastupdate;

    /** @ORM\Column(type="integer",nullable=true) */
    private $mpt_userid = 0;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $mpt_munkahelynev;

    /** @ORM\Column(type="string",length=10,nullable=true) */
    private $mpt_munkahelyirszam = '';

    /** @ORM\Column(type="string",length=40,nullable=true) */
    private $mpt_munkahelyvaros = '';

    /** @ORM\Column(type="string",length=60,nullable=true) */
    private $mpt_munkahelyutca = '';

    /** @ORM\Column(type="string",length=40,nullable=true) */
    private $mpt_munkahelyhazszam = '';

    /** @ORM\Column(type="string",length=10,nullable=true) */
    private $mpt_lakcimirszam = '';

    /** @ORM\Column(type="string",length=40,nullable=true) */
    private $mpt_lakcimvaros = '';

    /** @ORM\Column(type="string",length=60,nullable=true) */
    private $mpt_lakcimutca = '';

    /** @ORM\Column(type="string",length=40,nullable=true) */
    private $mpt_lakcimhazszam = '';

    /** @ORM\Column(type="string",length=4,nullable=true) */
    private $mpt_tagkartya = '';

    /**
     * @ORM\ManyToOne(targetEntity="MPTSzekcio")
     * @ORM\JoinColumn(name="mpt_szekcio1_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $mpt_szekcio1;

    /**
     * @ORM\ManyToOne(targetEntity="MPTSzekcio")
     * @ORM\JoinColumn(name="mpt_szekcio2_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $mpt_szekcio2;

    /**
     * @ORM\ManyToOne(targetEntity="MPTSzekcio")
     * @ORM\JoinColumn(name="mpt_szekcio3_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $mpt_szekcio3;

    /**
     * @ORM\ManyToOne(targetEntity="MPTTagozat")
     * @ORM\JoinColumn(name="mpt_tagozat_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $mpt_tagozat;

    /** @ORM\Column(type="string",length=20,nullable=true) */
    private $mpt_megszolitas = '';

    /** @ORM\Column(type="string",length=40,nullable=true) */
    private $mpt_fokozat = '';

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $mpt_vegzettseg = '';

    /**
     * @ORM\ManyToOne(targetEntity="MPTTagsagforma")
     * @ORM\JoinColumn(name="mpttagsagforma_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $mpt_tagsagforma;

    /** @ORM\Column(type="integer",nullable=true) */
    private $mpt_szuleteseve = 0;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $mpt_diplomahely = '';

    /** @ORM\Column(type="integer",nullable=true) */
    private $mpt_diplomaeve = 0;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $mpt_egyebdiploma = '';

    /** @ORM\Column(type="string",length=100,nullable=true) */
    private $mpt_privatemail = '';

    /** @ORM\Column(type="date",nullable=true) */
    private $mpt_tagsagdate;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $mpt_szamlazasinev = '';

    /**
     * @ORM\ManyToOne(targetEntity="MPTNGYSzerepkor")
     * @ORM\JoinColumn(name="mptngyszerepkor_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $mptngyszerepkor;

    /** @ORM\Column(type="boolean") */
    private $mptngynapreszvetel1 = false;

    /** @ORM\Column(type="boolean") */
    private $mptngynapreszvetel2 = false;

    /** @ORM\Column(type="boolean") */
    private $mptngynapreszvetel3 = false;

    /** @ORM\Column(type="text",nullable=true) */
    private $mptngycsoportosfizetes;

    /** @ORM\Column(type="boolean") */
    private $mptngyvipvacsora = false;

    /** @ORM\Column(type="boolean") */
    private $mptngybankett = false;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $mptngykapcsolatnev = false;

    /** @ORM\Column(type="string",length=50,nullable=true) */
    private $mptngybankszamlaszam = false;

    /** @ORM\Column(type="boolean") */
    private $mptngydiak = false;

    /** @ORM\Column(type="boolean") */
    private $mptngynyugdijas = false;

    /** @ORM\Column(type="boolean") */
    private $mptngympttag = false;

    /** @ORM\Column(type="date",nullable=true) */
    private $mptngybefizetesdatum;

    /**
     * @ORM\ManyToOne(targetEntity="Fizmod")
     * @ORM\JoinColumn(name="mptngyfizmod_id",referencedColumnName="id",nullable=true,onDelete="set null")
     */
    private $mptngybefizetesmod;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $mptngybefizetes;

    /** @ORM\Column(type="boolean") */
    private $mptngynemveszreszt = false;

    /** @ORM\Column(type="boolean") */
    private $nemrendelhet = false;

    /** @ORM\Column(type="boolean") */
    private $nemrendelhet2 = false;

    /** @ORM\Column(type="boolean") */
    private $nemrendelhet3 = false;

    /** @ORM\Column(type="boolean") */
    private $nemrendelhet4 = false;

    /** @ORM\Column(type="boolean") */
    private $nemrendelhet5 = false;

    /**
     * @ORM\ManyToOne(targetEntity="Arsav")
     * @ORM\JoinColumn(name="arsav_id", referencedColumnName="id",nullable=true,onDelete="set null")
     */
    private $arsav;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $termekarazonosito;
    /** @ORM\Column(type="integer", nullable=true) */
    private $wcid;
    /** @ORM\Column(type="datetime", nullable=true) */
    private $wcdate;


    public function __construct()
    {
        $this->cimkek = new \Doctrine\Common\Collections\ArrayCollection();
        $this->bizonylatfejek = new \Doctrine\Common\Collections\ArrayCollection();
        $this->mptfolyoszamlak = new \Doctrine\Common\Collections\ArrayCollection();
        $this->bankbizonylatfejek = new \Doctrine\Common\Collections\ArrayCollection();
        $this->bankbizonylattetelek = new \Doctrine\Common\Collections\ArrayCollection();
        $this->penztarbizonylatfejek = new \Doctrine\Common\Collections\ArrayCollection();
        $this->kosarak = new \Doctrine\Common\Collections\ArrayCollection();
        $this->termekertesitok = new \Doctrine\Common\Collections\ArrayCollection();
        $this->termekcsoportkedvezmenyek = new \Doctrine\Common\Collections\ArrayCollection();
        $this->termekkedvezmenyek = new \Doctrine\Common\Collections\ArrayCollection();
        $this->mijszoklevelek = new \Doctrine\Common\Collections\ArrayCollection();
        $this->kontaktok = new \Doctrine\Common\Collections\ArrayCollection();
        $this->mijszpune = new \Doctrine\Common\Collections\ArrayCollection();
        $this->mijszoralatogatas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->mijszoralatogatastanar = new \Doctrine\Common\Collections\ArrayCollection();
        $this->mijsztanitas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->partnerdokok = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
    {
        return $this->getId() . ': ' . $this->getNev() . '_' . $this->getEmail();
    }

    public function toLista()
    {
        $ret = [];
        $ret['keresztnev'] = $this->getKeresztnev();
        $ret['vezeteknev'] = $this->getVezeteknev();
        $ret['nev'] = $this->getNev();
        $ret['szamlanev'] = $this->getSzlanev();
        return $ret;
    }

    public function calcVatstatus()
    {
    }

    public function doAnonym()
    {
        $this->vezeteknev = \mkw\store::generateRandomStr(strlen($this->vezeteknev));
        $this->keresztnev = \mkw\store::generateRandomStr(strlen($this->keresztnev));
        $this->nev = implode(' ', [$this->vezeteknev, $this->keresztnev]);
        $this->adoszam = \mkw\store::generateRandomStr(strlen($this->adoszam), '1234567890');
        $this->euadoszam = \mkw\store::generateRandomStr(strlen($this->euadoszam), '1234567890');
        $this->mukengszam = \mkw\store::generateRandomStr(strlen($this->mukengszam));
        $this->jovengszam = \mkw\store::generateRandomStr(strlen($this->jovengszam));
        $this->ostermszam = \mkw\store::generateRandomStr(strlen($this->ostermszam));
        $this->valligszam = \mkw\store::generateRandomStr(strlen($this->valligszam));
        $this->fvmszam = \mkw\store::generateRandomStr(strlen($this->fvmszam));
        $this->cjszam = \mkw\store::generateRandomStr(strlen($this->cjszam));
        $this->statszamjel = \mkw\store::generateRandomStr(strlen($this->statszamjel));
        $this->irszam = \mkw\store::generateRandomStr(strlen($this->irszam), '1234567890');
        $this->varos = \mkw\store::generateRandomStr(strlen($this->varos));
        $this->utca = \mkw\store::generateRandomStr(strlen($this->utca));
        $this->hazszam = \mkw\store::generateRandomStr(strlen($this->hazszam), '1234567890');
        $this->clearGDPRData();
    }

    public function clearGDPRData()
    {
        $this->lirszam = '';
        $this->lvaros = '';
        $this->lutca = '';
        $this->lhazszam = '';
        $this->telefon = '';
        $this->mobil = '';
        $this->fax = '';
        $this->email = uniqid($this->vezeteknev . '.' . $this->keresztnev, true) . '@mail.local';
        $this->honlap = '';
        $this->szallnev = '';
        $this->szallirszam = '';
        $this->szallvaros = '';
        $this->szallutca = '';
        $this->szallhazszam = '';
        $this->nem = null;
        $this->szuletesiido = null;
        $this->ip = '';
        $this->referrer = '';
        $this->banknev = '';
        $this->bankcim = '';
        $this->iban = '';
        $this->swift = '';
        $this->minicrmprojectid = 0;
        $this->minicrmcontactid = 0;
        $this->munkahelyneve = '';
        $this->foglalkozas = '';
        $this->szamlaegyeb = '';
    }

    public function toA2a()
    {
        $x = [];
        $x['id'] = $this->getId();
        $x['nev'] = $this->getNev();
        $x['vezeteknev'] = $this->getVezeteknev();
        $x['keresztnev'] = $this->getKeresztnev();
        $x['irszam'] = $this->getIrszam();
        $x['varos'] = $this->getVaros();
        $x['utca'] = $this->getUtca();
        $x['hazszam'] = $this->getHazszam();
        $x['adoszam'] = $this->getAdoszam();
        $x['euadoszam'] = $this->getEuadoszam();
        $x['email'] = $this->getEmail();
        $x['telefon'] = $this->getTelefon();
        $x['szallnev'] = $this->getSzallnev();
        $x['szallirszam'] = $this->getSzallirszam();
        $x['szallvaros'] = $this->getSzallvaros();
        $x['szallutca'] = $this->getSzallutca();
        $x['szallhazszam'] = $this->getSzallhazszam();
        $x['vendeg'] = $this->getVendeg();
        $x['szamlaegyeb'] = $this->getSzamlaegyeb();
        return $x;
    }

    public function toWc()
    {
        $x = [
            'email' => $this->getEmail(),
            'first_name' => $this->getKeresztnev(),
            'last_name' => $this->getVezeteknev(),
        ];
        $x['billing'] = [
            'first_name' => $this->getKeresztnev(),
            'last_name' => $this->getVezeteknev(),
            'email' => $this->getEmail(),
            'company' => $this->getNev(),
            'address_1' => $this->getUtca(),
            'address_2' => $this->getHazszam(),
            'city' => $this->getVaros(),
            'postcode' => $this->getIrszam(),
            'country' => $this->getOrszag()?->getIso3166() ? $this->getOrszag()->getIso3166() : '',
            'phone' => $this->getTelefon(),
        ];
        $x['shipping'] = [
            'email' => '',
            'first_name' => '',
            'last_name' => '',
            'company' => $this->getSzallnev(),
            'address_1' => $this->getSzallutca(),
            'address_2' => $this->getSzallhazszam(),
            'city' => $this->getSzallvaros(),
            'postcode' => $this->getSzallirszam(),
            'country' => $this->getSzallorszag()?->getIso3166() ? $this->getSzallorszag()?->getIso3166() : '',
            'phone' => $this->getTelefon(),
        ];
        return $x;
    }

    public function sendToWc()
    {
        $wc = \mkw\store::getWcClient();
        $data = $this->toWc();

        if ($this->getWcid()) {
            \mkw\store::writelog($this->getId() . ': partner adat a woocommerceBE: ' . json_encode($data));
            \mkw\store::writelog($this->getId() . ': partner adat PUT start');
            $result = $wc->put('customers/' . $this->getWcid(), $data);
            \mkw\store::writelog($this->getId() . ': partner adat PUT stop');
            \mkw\store::writelog($this->getId() . ': partner adat a woocommerceBŐL: ' . json_encode($result));
            $this->setWcdate();
        }
    }

    public function getCim()
    {
        $cim = $this->irszam;
        if (($cim !== '') && ($this->varos !== '')) {
            $cim .= ' ';
        }
        $cim .= $this->varos;
        if (($cim !== '') && ($this->utca !== '')) {
            $cim .= ', ';
        }
        $cim .= $this->utca;
        if (($cim !== '') && ($this->hazszam !== '')) {
            $cim .= ' ';
        }
        $cim .= $this->hazszam;
        return $cim;
    }

    public function getSzallcim()
    {
        $cim = $this->szallirszam;
        if (($cim !== '') && ($this->szallvaros !== '')) {
            $cim .= ' ';
        }
        $cim .= $this->szallvaros;
        if (($cim !== '') && ($this->szallutca !== '')) {
            $cim .= ', ';
        }
        $cim .= $this->szallutca;
        if (($cim !== '') && ($this->szallhazszam !== '')) {
            $cim .= ' ';
        }
        $cim .= $this->szallhazszam;
        return $cim;
    }

    public function getLCim()
    {
        $cim = $this->lirszam;
        if (($cim !== '') && ($this->lvaros !== '')) {
            $cim .= ' ';
        }
        $cim .= $this->lvaros;
        if (($cim !== '') && ($this->lutca !== '')) {
            $cim .= ', ';
        }
        $cim .= $this->lutca;
        if (($cim !== '') && ($this->lhazszam !== '')) {
            $cim .= ' ';
        }
        $cim .= $this->lhazszam;
        return $cim;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getInaktiv()
    {
        return $this->inaktiv;
    }

    public function setInaktiv($inaktiv)
    {
        $this->inaktiv = $inaktiv;
    }

    public function getNev()
    {
        return $this->nev;
    }

    public function getMonogram()
    {
        if ($this->keresztnev || $this->vezeteknev) {
            return mb_substr($this->vezeteknev, 0, 1) . '.'
                . mb_substr($this->keresztnev, 0, 1) . '.';
        } else {
            $nevek = explode(' ', $this->nev);
            return mb_substr($nevek, 0, 1) . '.'
                . mb_substr($nevek, 0, 1) . '.';
        }
    }

    public function setNev($nev)
    {
        $this->nev = $nev;
    }

    public function getVezeteknev()
    {
        return $this->vezeteknev;
    }

    public function setVezeteknev($adat)
    {
        $this->vezeteknev = $adat;
    }

    public function getKeresztnev()
    {
        return $this->keresztnev;
    }

    public function setKeresztnev($adat)
    {
        $this->keresztnev = $adat;
    }

    public function getAdoszam()
    {
        return $this->adoszam;
    }

    public function setAdoszam($adoszam)
    {
        $this->adoszam = $adoszam;
    }

    public function getEuadoszam()
    {
        return $this->euadoszam;
    }

    public function setEuadoszam($euadoszam)
    {
        $this->euadoszam = $euadoszam;
    }

    public function getMukengszam()
    {
        return $this->mukengszam;
    }

    public function setMukengszam($mukengszam)
    {
        $this->mukengszam = $mukengszam;
    }

    public function getJovengszam()
    {
        return $this->jovengszam;
    }

    public function setJovengszam($jovengszam)
    {
        $this->jovengszam = $jovengszam;
    }

    public function getOstermszam()
    {
        return $this->ostermszam;
    }

    public function setOstermszam($ostermszam)
    {
        $this->ostermszam = $ostermszam;
    }

    public function getValligszam()
    {
        return $this->valligszam;
    }

    public function setValligszam($valligszam)
    {
        $this->valligszam = $valligszam;
    }

    public function getFvmszam()
    {
        return $this->fvmszam;
    }

    public function setFvmszam($fvmszam)
    {
        $this->fvmszam = $fvmszam;
    }

    public function getCjszam()
    {
        return $this->cjszam;
    }

    public function setCjszam($cjszam)
    {
        $this->cjszam = $cjszam;
    }

    public function getIrszam()
    {
        return $this->irszam;
    }

    public function setIrszam($irszam)
    {
        $this->irszam = $irszam;
    }

    public function getVaros()
    {
        return $this->varos;
    }

    public function setVaros($varos)
    {
        $this->varos = $varos;
    }

    public function getUtca()
    {
        return $this->utca;
    }

    public function setUtca($utca)
    {
        $this->utca = $utca;
    }

    public function getLirszam()
    {
        return $this->lirszam;
    }

    public function setLirszam($lirszam)
    {
        $this->lirszam = $lirszam;
    }

    public function getLvaros()
    {
        return $this->lvaros;
    }

    public function setLvaros($lvaros)
    {
        $this->lvaros = $lvaros;
    }

    public function getLutca()
    {
        return $this->lutca;
    }

    public function setLutca($lutca)
    {
        $this->lutca = $lutca;
    }

    public function getTelefon()
    {
        return $this->telefon;
    }

    public function setTelefon($telefon)
    {
        $this->telefon = $telefon;
    }

    public function getMobil()
    {
        return $this->mobil;
    }

    public function setMobil($mobil)
    {
        $this->mobil = $mobil;
    }

    public function getFax()
    {
        return $this->fax;
    }

    public function setFax($fax)
    {
        $this->fax = $fax;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getHonlap()
    {
        return $this->honlap;
    }

    public function setHonlap($honlap)
    {
        $this->honlap = $honlap;
    }

    public function getMegjegyzes()
    {
        return $this->megjegyzes;
    }

    public function setMegjegyzes($megjegyzes)
    {
        $this->megjegyzes = $megjegyzes;
    }

    public function getSyncid()
    {
        return $this->syncid;
    }

    public function setSyncid($syncid)
    {
        $this->syncid = $syncid;
    }

    /**
     *
     * @ORM\return Uzletkoto
     */
    public function getUzletkoto()
    {
        return $this->uzletkoto;
    }

    public function getUzletkotoNev()
    {
        if ($this->uzletkoto) {
            return $this->uzletkoto->getNev();
        }
        return '';
    }

    public function getUzletkotoId()
    {
        if ($this->uzletkoto) {
            return $this->uzletkoto->getId();
        }
        return '';
    }

    public function setUzletkoto($uzletkoto)
    {
        if ($this->uzletkoto !== $uzletkoto) {
            $this->uzletkoto = $uzletkoto;
            $uzletkoto->addPartner($this);
        }
    }

    public function removeUzletkoto()
    {
        if ($this->uzletkoto !== null) {
            $uzletkoto = $this->uzletkoto;
            $this->uzletkoto = null;
            $uzletkoto->removePartner($this);
        }
    }

    public function addEsemeny($esemeny)
    {
        if (!$this->esemenyek->contains($esemeny)) {
            $this->esemenyek->add($esemeny);
            $esemeny->setPartner($this);
        }
    }

    public function getEsemenyek()
    {
        return $this->esemenyek;
    }

    public function removeEsemeny($esemeny)
    {
        if ($this->esemenyek->removeElement($esemeny)) {
            $esemeny->removePartner();
        }
    }

    public function addTeendo($teendo)
    {
        if (!$this->teendok->contains($teendo)) {
            $this->teendok->add($teendo);
            $teendo->setPartner($this);
        }
    }

    public function getTeendok()
    {
        return $this->teendok;
    }

    public function removeTeendo($teendo)
    {
        if ($this->teendok->removeElement($teendo)) {
            $teendo->removePartner();
            return true;
        }
        return false;
    }

    /**
     *
     * @ORM\return ArrayCollection
     */
    public function getCimkek()
    {
        return $this->cimkek;
    }

    public function getCimkeNevek()
    {
        $res = [];
        foreach ($this->cimkek as $cimke) {
            $res[] = ['nev' => $cimke->getNev()];
        }
        return $res;
    }

    public function addCimke(Cimketorzs $cimke)
    {
        if (!$this->cimkek->contains($cimke)) {
            $this->cimkek->add($cimke);
            $cimke->addPartner($this);
        }
    }

    public function removeCimke(Cimketorzs $cimke)
    {
        if ($this->cimkek->removeElement($cimke)) {
//			$cimke->removePartner($this);  // deleted for speed
            return true;
        }
        return false;
    }

    public function removeAllCimke()
    {
        foreach ($this->cimkek as $cimke) {
            $this->removeCimke($cimke);
        }
    }

    /**
     *
     * @ORM\return Fizmod
     */
    public function getFizmod()
    {
        return $this->fizmod;
    }

    public function getFizmodNev()
    {
        if ($this->fizmod) {
            return $this->fizmod->getNev();
        }
        return '';
    }

    public function getFizmodId()
    {
        if ($this->fizmod) {
            return $this->fizmod->getId();
        }
        return '';
    }

    public function setFizmod($fizmod)
    {
        $this->fizmod = $fizmod;
    }

    public function getIdegenkod()
    {
        return $this->idegenkod;
    }

    public function setIdegenkod($idegenkod)
    {
        $this->idegenkod = $idegenkod;
    }

    public function getStatszamjel()
    {
        return $this->statszamjel;
    }

    public function setStatszamjel($statszamjel)
    {
        $this->statszamjel = $statszamjel;
    }

    public function getFizhatido()
    {
        return $this->fizhatido;
    }

    public function setFizhatido($val)
    {
        $this->fizhatido = $val;
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

    public function getNem()
    {
        return $this->nem;
    }

    public function setNem($adat)
    {
        $this->nem = $adat;
    }

    public function getSzuletesiido()
    {
        return $this->szuletesiido;
    }

    public function getSzuletesiidoStr()
    {
        if ($this->getSzuletesiido()) {
            return $this->getSzuletesiido()->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    public function setSzuletesiido($adat = '')
    {
        if ($adat != '') {
            $this->szuletesiido = new \DateTime(\mkw\store::convDate($adat));
        }
    }

    public function getAkcioshirlevelkell()
    {
        return $this->akcioshirlevelkell;
    }

    public function setAkcioshirlevelkell($adat)
    {
        $this->akcioshirlevelkell = $adat;
    }

    public function getUjdonsaghirlevelkell()
    {
        return $this->ujdonsaghirlevelkell;
    }

    public function setUjdonsaghirlevelkell($adat)
    {
        $this->ujdonsaghirlevelkell = $adat;
    }

    public function getSessionid()
    {
        return $this->sessionid;
    }

    public function setSessionid($adat)
    {
        $this->sessionid = $adat;
    }

    public function getJelszo()
    {
        return $this->jelszo;
    }

    public function setMkwJelszo($adat)
    {
        $this->jelszo = sha1($adat . \mkw\store::getSalt());
    }

    public function setJelszo($adat)
    {
        $this->jelszo = sha1(strtoupper(md5($adat)) . \mkw\store::getSalt());
    }

    public function checkJelszo($adat)
    {
        $so = \mkw\store::getSalt();
        $v = sha1(strtoupper(md5($adat)) . $so);
        return $this->jelszo === $v;
    }

    public function getUtolsoklikk()
    {
        return $this->utolsoklikk;
    }

    public function setUtolsoklikk()
    {
        $this->utolsoklikk = new \DateTime();
    }

    public function getVendeg()
    {
        return $this->vendeg;
    }

    public function setVendeg($val)
    {
        $this->vendeg = $val;
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

    public function getSzallito()
    {
        return $this->szallito;
    }

    public function setSzallito($val)
    {
        $this->szallito = $val;
    }

    public function getPasswordreminder()
    {
        return $this->passwordreminder;
    }

    public function setPasswordreminder()
    {
        $this->passwordreminder = sha1(md5(time() . \mkw\store::getSalt()) . \mkw\store::getSalt());
        return $this->passwordreminder;
    }

    public function clearPasswordreminder()
    {
        unset($this->passwordreminder);
    }

    public function getOldloginname()
    {
        return $this->oldloginname;
    }

    public function setOldloginname($name)
    {
        $this->oldloginname = $name;
    }

    public function getSzallitasiido()
    {
        return $this->szallitasiido;
    }

    public function setSzallitasiido($adat)
    {
        $this->szallitasiido = $adat;
    }

    public function getBanknev()
    {
        return $this->banknev;
    }

    public function setBanknev($val)
    {
        $this->banknev = $val;
    }

    public function getBankcim()
    {
        return $this->bankcim;
    }

    public function setBankcim($val)
    {
        $this->bankcim = $val;
    }

    public function getIban()
    {
        return $this->iban;
    }

    public function setIban($val)
    {
        $this->iban = $val;
    }

    public function getSwift()
    {
        return $this->swift;
    }

    public function setSwift($val)
    {
        $this->swift = $val;
    }

    public function getSzamlatipus()
    {
        return $this->szamlatipus;
    }

    public function setSzamlatipus($val)
    {
        $this->szamlatipus = $val;
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
        $vn = $this->getValutanem();
        if ($vn) {
            return $vn->getNev();
        }
        return '';
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
            $this->valutanem = $val;
        }
    }

    public function removeValutanem()
    {
        if ($this->valutanem !== null) {
//			$val=$this->valutanem;
            $this->valutanem = null;
//			$val->removeBizonylatfej($this);
        }
    }

    public function getSzallitasimod()
    {
        return $this->szallitasimod;
    }

    public function getSzallitasimodNev()
    {
        if ($this->szallitasimod) {
            return $this->szallitasimod->getNev();
        }
        return '';
    }

    public function getSzallitasimodId()
    {
        if ($this->szallitasimod) {
            return $this->szallitasimod->getId();
        }
        return '';
    }

    public function setSzallitasimod($val)
    {
        if ($this->szallitasimod !== $val) {
            $this->szallitasimod = $val;
        }
    }

    public function removeSzallitasimod()
    {
        if ($this->szallitasimod !== null) {
            $this->szallitasimod = null;
        }
    }

    public function getBizonylatnyelv()
    {
        return $this->bizonylatnyelv;
    }

    public function setBizonylatnyelv($adat)
    {
        $this->bizonylatnyelv = $adat;
    }

    /**
     * @return mixed
     */
    public function getEzuzletkoto()
    {
        return $this->ezuzletkoto;
    }

    /**
     * @param mixed $ezuzletkoto
     */
    public function setEzuzletkoto($ezuzletkoto)
    {
        $this->ezuzletkoto = $ezuzletkoto;
    }

    /**
     * @return \Entities\PartnerTermekcsoportKedvezmeny
     */
    public function getTermekcsoportkedvezmenyek()
    {
        return $this->termekcsoportkedvezmenyek;
    }

    /**
     * @return \Entities\PartnerTermekKedvezmeny
     */
    public function getTermekkedvezmenyek()
    {
        return $this->termekkedvezmenyek;
    }

    /**
     * @return \Entities\Partnertipus
     */
    public function getPartnertipus()
    {
        return $this->partnertipus;
    }

    public function getPartnertipusNev()
    {
        if ($this->partnertipus) {
            return $this->partnertipus->getNev();
        }
        return '';
    }

    public function getPartnertipusId()
    {
        if ($this->partnertipus) {
            return $this->partnertipus->getId();
        }
        return '';
    }

    public function setPartnertipus($val)
    {
        if ($this->partnertipus !== $val) {
            $this->partnertipus = $val;
        }
    }

    public function removePartnertipus()
    {
        if ($this->partnertipus !== null) {
            $this->partnertipus = null;
        }
    }

    public function isDefaultOrszag()
    {
        if ($this->orszag) {
            return $this->orszag->isDefault();
        }
        return true;
    }

    /**
     * @return Orszag | null
     */
    public function getOrszag()
    {
        return $this->orszag;
    }

    public function getOrszagNev()
    {
        if ($this->orszag) {
            return $this->orszag->getNev();
        }
        return '';
    }

    public function getOrszagId()
    {
        if ($this->orszag) {
            return $this->orszag->getId();
        }
        return '';
    }

    public function setOrszag($val)
    {
        if ($this->orszag !== $val) {
            $this->orszag = $val;
        }
    }

    public function removeOrszag()
    {
        if ($this->orszag !== null) {
            $this->orszag = null;
        }
    }

    /**
     * @return mixed
     */
    public function getMijszmiotajogazik()
    {
        return $this->mijszmiotajogazik;
    }

    /**
     * @param mixed $mijszmiotajogazik
     */
    public function setMijszmiotajogazik($mijszmiotajogazik)
    {
        $this->mijszmiotajogazik = $mijszmiotajogazik;
    }

    /**
     * @return mixed
     */
    public function getMijszmiotatanit()
    {
        return $this->mijszmiotatanit;
    }

    /**
     * @param mixed $mijszmiotatanit
     */
    public function setMijszmiotatanit($mijszmiotatanit)
    {
        $this->mijszmiotatanit = $mijszmiotatanit;
    }

    /**
     * @return \Entities\PartnerMIJSZOklevel
     */
    public function getMijszoklevelek()
    {
        return $this->mijszoklevelek;
    }

    /**
     * @return \Entities\PartnerMIJSZPune
     */
    public function getMijszpune()
    {
        return $this->mijszpune;
    }

    /**
     * @return \Entities\PartnerMIJSZOralatogatas
     */
    public function getMijszoralatogatas()
    {
        return $this->mijszoralatogatas;
    }

    /**
     * @return \Entities\PartnerMIJSZOralatogatas
     */
    public function getMijszoralatogatastanar()
    {
        return $this->mijszoralatogatastanar;
    }

    /**
     * @return \Entities\PartnerMIJSZTanitas
     */
    public function getMijsztanitas()
    {
        return $this->mijsztanitas;
    }

    /**
     * @return mixed
     */
    public function getMijszmembershipbesideshu()
    {
        return $this->mijszmembershipbesideshu;
    }

    /**
     * @param mixed $mijszmembershipbesideshu
     */
    public function setMijszmembershipbesideshu($mijszmembershipbesideshu)
    {
        $this->mijszmembershipbesideshu = $mijszmembershipbesideshu;
    }

    /**
     * @return mixed
     */
    public function getMijszbusiness()
    {
        return $this->mijszbusiness;
    }

    /**
     * @param mixed $mijszbusiness
     */
    public function setMijszbusiness($mijszbusiness)
    {
        $this->mijszbusiness = $mijszbusiness;
    }

    /**
     * @return mixed
     */
    public function getMijszexporttiltva()
    {
        return $this->mijszexporttiltva;
    }

    /**
     * @param mixed $mijszexporttiltva
     */
    public function setMijszexporttiltva($mijszexporttiltva)
    {
        $this->mijszexporttiltva = $mijszexporttiltva;
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
    public function getKtdatvallal()
    {
        return $this->ktdatvallal;
    }

    /**
     * @param mixed $ktdatvallal
     */
    public function setKtdatvallal($ktdatvallal)
    {
        $this->ktdatvallal = $ktdatvallal;
    }

    /**
     * @return mixed
     */
    public function getKtdatalany()
    {
        return $this->ktdatalany;
    }

    /**
     * @param mixed $ktdatalany
     */
    public function setKtdatalany($ktdatalany)
    {
        $this->ktdatalany = $ktdatalany;
    }

    /**
     * @return mixed
     */
    public function getKtdszerzszam()
    {
        return $this->ktdszerzszam;
    }

    /**
     * @param mixed $ktdszerzszam
     */
    public function setKtdszerzszam($ktdszerzszam)
    {
        $this->ktdszerzszam = $ktdszerzszam;
    }

    /**
     * @return mixed
     */
    public function getKontaktok()
    {
        return $this->kontaktok;
    }

    /**
     * @return mixed
     */
    public function getHazszam()
    {
        return $this->hazszam;
    }

    /**
     * @param mixed $hazszam
     */
    public function setHazszam($hazszam)
    {
        $this->hazszam = $hazszam;
    }

    /**
     * @return mixed
     */
    public function getLhazszam()
    {
        return $this->lhazszam;
    }

    /**
     * @param mixed $lhazszam
     */
    public function setLhazszam($lhazszam)
    {
        $this->lhazszam = $lhazszam;
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
     * @return mixed
     */
    public function getMinicrmprojectid()
    {
        return $this->minicrmprojectid;
    }

    /**
     * @param mixed $minicrmprojectid
     */
    public function setMinicrmprojectid($minicrmprojectid)
    {
        $this->minicrmprojectid = $minicrmprojectid;
    }

    /**
     * @return mixed
     */
    public function getMinicrmcontactid()
    {
        return $this->minicrmcontactid;
    }

    /**
     * @param mixed $minicrmcontactid
     */
    public function setMinicrmcontactid($minicrmcontactid)
    {
        $this->minicrmcontactid = $minicrmcontactid;
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
    public function getMunkahelyneve()
    {
        return $this->munkahelyneve;
    }

    /**
     * @param mixed $munkahelyneve
     */
    public function setMunkahelyneve($munkahelyneve)
    {
        $this->munkahelyneve = $munkahelyneve;
    }

    /**
     * @return mixed
     */
    public function getFoglalkozas()
    {
        return $this->foglalkozas;
    }

    /**
     * @param mixed $foglalkozas
     */
    public function setFoglalkozas($foglalkozas)
    {
        $this->foglalkozas = $foglalkozas;
    }

    public function getPartnerDokok()
    {
        return $this->partnerdokok;
    }

    public function addPartnerDok(PartnerDok $dok)
    {
        $this->partnerdokok->add($dok);
        $dok->setPartner($this);
    }

    public function removePartnerDok(PartnerDok $dok)
    {
        if ($this->partnerdokok->removeElement($dok)) {
            $dok->removePartner($this);
            return true;
        }
        return false;
    }

    /**
     * @return mixed
     */
    public function getAnonymizalnikell()
    {
        return $this->anonymizalnikell;
    }

    /**
     * @param mixed $anonymizalnikell
     */
    public function setAnonymizalnikell($anonymizalnikell)
    {
        $this->anonymizalnikell = $anonymizalnikell;
    }

    /**
     * @return mixed
     */
    public function getAnonymkeresdatum()
    {
        return $this->anonymkeresdatum;
    }

    public function getAnonymkeresdatumStr()
    {
        if ($this->getAnonymkeresdatum()) {
            return $this->getAnonymkeresdatum()->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    /**
     * @param mixed $anonymkeresdatum
     */
    public function setAnonymkeresdatum($anonymkeresdatum = '')
    {
        if ($anonymkeresdatum != '') {
            $this->anonymkeresdatum = new \DateTime(\mkw\store::convDate($anonymkeresdatum));
        }
    }

    /**
     * @return mixed
     */
    public function getAnonym()
    {
        return $this->anonym;
    }

    /**
     * @param mixed $anonym
     */
    public function setAnonym($anonym)
    {
        $this->anonym = $anonym;
    }

    /**
     * @return mixed
     */
    public function getAnonymdatum()
    {
        return $this->anonymdatum;
    }

    public function getAnonymdatumStr()
    {
        if ($this->getAnonymdatum()) {
            return $this->getAnonymdatum()->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    /**
     * @param mixed $anonymdatum
     */
    public function setAnonymdatum($anonymdatum = '')
    {
        if ($anonymdatum != '') {
            $this->anonymdatum = new \DateTime(\mkw\store::convDate($anonymdatum));
        }
    }

    /**
     * @return mixed
     */
    public function getBizonylatfejek()
    {
        return $this->bizonylatfejek;
    }

    /**
     * @return mixed
     */
    public function getMPTFolyoszamlak()
    {
        return $this->mptfolyoszamlak;
    }

    /**
     * @return mixed
     */
    public function getBankbizonylatfejek()
    {
        return $this->bankbizonylatfejek;
    }

    /**
     * @return mixed
     */
    public function getBankbizonylattetelek()
    {
        return $this->bankbizonylattetelek;
    }

    /**
     * @return mixed
     */
    public function getPenztarbizonylatfejek()
    {
        return $this->penztarbizonylatfejek;
    }

    /**
     * @return mixed
     */
    public function getApireg()
    {
        return $this->apireg;
    }

    /**
     * @param mixed $apireg
     */
    public function setApireg($apireg)
    {
        $this->apireg = $apireg;
    }

    /**
     * @return Apiconsumer
     */
    public function getApiconsumer()
    {
        return $this->apiconsumer;
    }

    /**
     * @param Apiconsumer $apiconsumer
     */
    public function setApiconsumer($apiconsumer)
    {
        if ($this->apiconsumer !== $apiconsumer) {
            if (!$apiconsumer) {
                $this->removeApiconsumer();
            } else {
                $this->apiconsumer = $apiconsumer;
                $this->setApiconsumernev($apiconsumer->getNev());
            }
        }
    }

    public function removeApiconsumer()
    {
        if (!is_null($this->apiconsumer)) {
            $this->apiconsumer = null;
            $this->setApiconsumernev(null);
        }
    }

    /**
     * @return mixed
     */
    public function getApiconsumernev()
    {
        return $this->apiconsumernev;
    }

    /**
     * @param mixed $apiconsumernev
     */
    public function setApiconsumernev($apiconsumernev)
    {
        $this->apiconsumernev = $apiconsumernev;
    }

    /**
     * @return mixed
     */
    public function getSzamlalevelmegszolitas()
    {
        return $this->szamlalevelmegszolitas;
    }

    /**
     * @param mixed $szamlalevelmegszolitas
     */
    public function setSzamlalevelmegszolitas($szamlalevelmegszolitas)
    {
        $this->szamlalevelmegszolitas = $szamlalevelmegszolitas;
    }

    /**
     * @return mixed
     */
    public function getTelkorzet()
    {
        return $this->telkorzet;
    }

    /**
     * @param mixed $telkorzet
     */
    public function setTelkorzet($telkorzet)
    {
        $this->telkorzet = $telkorzet;
    }

    /**
     * @return mixed
     */
    public function getTelszam()
    {
        return $this->telszam;
    }

    /**
     * @param mixed $telszam
     */
    public function setTelszam($telszam)
    {
        $this->telszam = $telszam;
    }

    /**
     * @return bool
     */
    public function isExportbacsakkeszlet()
    {
        return $this->exportbacsakkeszlet;
    }

    /**
     * @param bool $exportbacsakkeszlet
     */
    public function setExportbacsakkeszlet($exportbacsakkeszlet)
    {
        $this->exportbacsakkeszlet = $exportbacsakkeszlet;
    }

    /**
     * @return bool
     */
    public function getKulsos()
    {
        return $this->kulsos;
    }

    /**
     * @param bool $kulsos
     */
    public function setKulsos($kulsos)
    {
        $this->kulsos = $kulsos;
    }

    /**
     * @return bool
     */
    public function isMennyisegetlathat()
    {
        return $this->mennyisegetlathat;
    }

    /**
     * @param bool $mennyisegetlathat
     */
    public function setMennyisegetlathat($mennyisegetlathat)
    {
        $this->mennyisegetlathat = $mennyisegetlathat;
    }

    /**
     * @return int
     */
    public function getVatstatus()
    {
        return $this->vatstatus;
    }

    /**
     * @param int $vatstatus
     */
    public function setVatstatus($vatstatus)
    {
        $this->vatstatus = $vatstatus;
    }

    /**
     * @return string
     */
    public function getThirdadoszam()
    {
        return $this->thirdadoszam;
    }

    /**
     * @param string $thirdadoszam
     */
    public function setThirdadoszam($thirdadoszam)
    {
        $this->thirdadoszam = $thirdadoszam;
    }

    /**
     * @return mixed
     */
    public function getSzamlaegyeb()
    {
        return $this->szamlaegyeb;
    }

    /**
     * @param mixed $szamlaegyeb
     */
    public function setSzamlaegyeb($szamlaegyeb)
    {
        $this->szamlaegyeb = $szamlaegyeb;
    }

    /**
     * @return mixed
     */
    public function getMptUsername()
    {
        return $this->mpt_username;
    }

    /**
     * @param mixed $mpt_username
     */
    public function setMptUsername($mpt_username)
    {
        $this->mpt_username = $mpt_username;
    }

    /**
     * @return mixed
     */
    public function getMptPassword()
    {
        return $this->mpt_password;
    }

    /**
     * @param mixed $mpt_password
     */
    public function setMptPassword($mpt_password)
    {
        $this->mpt_password = $mpt_password;
    }

    /**
     * @return mixed
     */
    public function getMptRegisterdate()
    {
        return $this->mpt_registerdate;
    }

    public function getMptRegisterdateStr()
    {
        if ($this->getMptRegisterdate()) {
            return $this->getMptRegisterdate()->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    /**
     * @param mixed $mpt_registerdate
     */
    public function setMptRegisterdate($mpt_registerdate)
    {
        if ($mpt_registerdate != '') {
            $this->mpt_registerdate = new \DateTime(\mkw\store::convDate($mpt_registerdate));
        }
    }

    /**
     * @return mixed
     */
    public function getMptLastvisit()
    {
        return $this->mpt_lastvisit;
    }

    public function getMptLastvisitStr()
    {
        if ($this->getMptLastvisit()) {
            return $this->getMptLastvisit()->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    /**
     * @param mixed $mpt_lastvisit
     */
    public function setMptLastvisit($mpt_lastvisit)
    {
        if ($mpt_lastvisit != '') {
            $this->mpt_lastvisit = new \DateTime(\mkw\store::convDate($mpt_lastvisit));
        }
    }

    /**
     * @return mixed
     */
    public function getMptLastupdate()
    {
        return $this->mpt_lastupdate;
    }

    public function getMptLastupdateStr()
    {
        if ($this->getMptLastupdate()) {
            return $this->getMptLastupdate()->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    /**
     * @param mixed $mpt_lastupdate
     */
    public function setMptLastupdate($mpt_lastupdate)
    {
        if ($mpt_lastupdate != '') {
            $this->mpt_lastupdate = new \DateTime(\mkw\store::convDate($mpt_lastupdate));
        }
    }

    /**
     * @return int
     */
    public function getMptUserid()
    {
        return $this->mpt_userid;
    }

    /**
     * @param int $mpt_userid
     */
    public function setMptUserid($mpt_userid)
    {
        $this->mpt_userid = $mpt_userid;
    }

    /**
     * @return mixed
     */
    public function getMptMunkahelynev()
    {
        return $this->mpt_munkahelynev;
    }

    /**
     * @param mixed $mpt_munkahelynev
     */
    public function setMptMunkahelynev($mpt_munkahelynev)
    {
        $this->mpt_munkahelynev = $mpt_munkahelynev;
    }

    /**
     * @return string
     */
    public function getMptMunkahelyirszam()
    {
        return $this->mpt_munkahelyirszam;
    }

    /**
     * @param string $mpt_munkahelyirszam
     */
    public function setMptMunkahelyirszam($mpt_munkahelyirszam)
    {
        $this->mpt_munkahelyirszam = $mpt_munkahelyirszam;
    }

    /**
     * @return string
     */
    public function getMptMunkahelyvaros()
    {
        return $this->mpt_munkahelyvaros;
    }

    /**
     * @param string $mpt_munkahelyvaros
     */
    public function setMptMunkahelyvaros($mpt_munkahelyvaros): void
    {
        $this->mpt_munkahelyvaros = $mpt_munkahelyvaros;
    }

    /**
     * @return string
     */
    public function getMptMunkahelyutca()
    {
        return $this->mpt_munkahelyutca;
    }

    /**
     * @param string $mpt_munkahelyutca
     */
    public function setMptMunkahelyutca($mpt_munkahelyutca): void
    {
        $this->mpt_munkahelyutca = $mpt_munkahelyutca;
    }

    /**
     * @return string
     */
    public function getMptMunkahelyhazszam()
    {
        return $this->mpt_munkahelyhazszam;
    }

    /**
     * @param string $mpt_munkahelyhazszam
     */
    public function setMptMunkahelyhazszam($mpt_munkahelyhazszam): void
    {
        $this->mpt_munkahelyhazszam = $mpt_munkahelyhazszam;
    }

    /**
     * @return string
     */
    public function getMptLakcimirszam()
    {
        return $this->mpt_lakcimirszam;
    }

    /**
     * @param string $mpt_lakcimirszam
     */
    public function setMptLakcimirszam($mpt_lakcimirszam): void
    {
        $this->mpt_lakcimirszam = $mpt_lakcimirszam;
    }

    /**
     * @return string
     */
    public function getMptLakcimvaros()
    {
        return $this->mpt_lakcimvaros;
    }

    /**
     * @param string $mpt_lakcimvaros
     */
    public function setMptLakcimvaros($mpt_lakcimvaros): void
    {
        $this->mpt_lakcimvaros = $mpt_lakcimvaros;
    }

    /**
     * @return string
     */
    public function getMptLakcimutca()
    {
        return $this->mpt_lakcimutca;
    }

    /**
     * @param string $mpt_lakcimutca
     */
    public function setMptLakcimutca($mpt_lakcimutca): void
    {
        $this->mpt_lakcimutca = $mpt_lakcimutca;
    }

    /**
     * @return string
     */
    public function getMptLakcimhazszam()
    {
        return $this->mpt_lakcimhazszam;
    }

    /**
     * @param string $mpt_lakcimhazszam
     */
    public function setMptLakcimhazszam($mpt_lakcimhazszam): void
    {
        $this->mpt_lakcimhazszam = $mpt_lakcimhazszam;
    }

    /**
     * @return string
     */
    public function getMptTagkartya()
    {
        return $this->mpt_tagkartya;
    }

    /**
     * @param string $mpt_tagkartya
     */
    public function setMptTagkartya($mpt_tagkartya): void
    {
        $this->mpt_tagkartya = $mpt_tagkartya;
    }

    /**
     * @return string
     */
    public function getMptMegszolitas()
    {
        return $this->mpt_megszolitas;
    }

    /**
     * @param string $mpt_megszolitas
     */
    public function setMptMegszolitas($mpt_megszolitas): void
    {
        $this->mpt_megszolitas = $mpt_megszolitas;
    }

    /**
     * @return string
     */
    public function getMptFokozat()
    {
        return $this->mpt_fokozat;
    }

    /**
     * @param string $mpt_fokozat
     */
    public function setMptFokozat($mpt_fokozat): void
    {
        $this->mpt_fokozat = $mpt_fokozat;
    }

    /**
     * @return string
     */
    public function getMptVegzettseg()
    {
        return $this->mpt_vegzettseg;
    }

    /**
     * @param string $mpt_vegzettseg
     */
    public function setMptVegzettseg($mpt_vegzettseg): void
    {
        $this->mpt_vegzettseg = $mpt_vegzettseg;
    }

    /**
     * @return int
     */
    public function getMptSzuleteseve()
    {
        return $this->mpt_szuleteseve;
    }

    /**
     * @param int $mpt_szuleteseve
     */
    public function setMptSzuleteseve($mpt_szuleteseve): void
    {
        $this->mpt_szuleteseve = $mpt_szuleteseve;
    }

    /**
     * @return string
     */
    public function getMptDiplomahely()
    {
        return $this->mpt_diplomahely;
    }

    /**
     * @param string $mpt_diplomahely
     */
    public function setMptDiplomahely($mpt_diplomahely): void
    {
        $this->mpt_diplomahely = $mpt_diplomahely;
    }

    /**
     * @return int
     */
    public function getMptDiplomaeve()
    {
        return $this->mpt_diplomaeve;
    }

    /**
     * @param int $mpt_diplomaeve
     */
    public function setMptDiplomaeve($mpt_diplomaeve): void
    {
        $this->mpt_diplomaeve = $mpt_diplomaeve;
    }

    /**
     * @return string
     */
    public function getMptEgyebdiploma()
    {
        return $this->mpt_egyebdiploma;
    }

    /**
     * @param string $mpt_egyebdiploma
     */
    public function setMptEgyebdiploma($mpt_egyebdiploma): void
    {
        $this->mpt_egyebdiploma = $mpt_egyebdiploma;
    }

    /**
     * @return string
     */
    public function getMptPrivatemail()
    {
        return $this->mpt_privatemail;
    }

    /**
     * @param string $mpt_privatemail
     */
    public function setMptPrivatemail($mpt_privatemail): void
    {
        $this->mpt_privatemail = $mpt_privatemail;
    }

    public function getMptSzekcio1()
    {
        return $this->mpt_szekcio1;
    }

    public function getMptSzekcio1Id()
    {
        if ($this->mpt_szekcio1) {
            return $this->mpt_szekcio1->getId();
        }
        return false;
    }

    public function getMptSzekcio1Nev()
    {
        if ($this->mpt_szekcio1) {
            return $this->mpt_szekcio1->getNev();
        }
        return false;
    }

    public function setMptSzekcio1($val)
    {
        if ($this->mpt_szekcio1 !== $val) {
            if (!$val) {
                $this->mpt_szekcio1 = null;
            } else {
                $this->mpt_szekcio1 = $val;
            }
        }
    }

    public function getMptSzekcio2()
    {
        return $this->mpt_szekcio2;
    }

    public function getMptSzekcio2Id()
    {
        if ($this->mpt_szekcio2) {
            return $this->mpt_szekcio2->getId();
        }
        return false;
    }

    public function getMptSzekcio2Nev()
    {
        if ($this->mpt_szekcio2) {
            return $this->mpt_szekcio2->getNev();
        }
        return false;
    }

    public function setMptSzekcio2($val)
    {
        if ($this->mpt_szekcio2 !== $val) {
            if (!$val) {
                $this->mpt_szekcio2 = null;
            } else {
                $this->mpt_szekcio2 = $val;
            }
        }
    }

    public function getMptSzekcio3()
    {
        return $this->mpt_szekcio3;
    }

    public function getMptSzekcio3Id()
    {
        if ($this->mpt_szekcio3) {
            return $this->mpt_szekcio3->getId();
        }
        return false;
    }

    public function getMptSzekcio3Nev()
    {
        if ($this->mpt_szekcio3) {
            return $this->mpt_szekcio3->getNev();
        }
        return false;
    }

    public function setMptSzekcio3($val)
    {
        if ($this->mpt_szekcio3 !== $val) {
            if (!$val) {
                $this->mpt_szekcio3 = null;
            } else {
                $this->mpt_szekcio3 = $val;
            }
        }
    }

    public function getMptTagozat()
    {
        return $this->mpt_tagozat;
    }

    public function getMptTagozatId()
    {
        if ($this->mpt_tagozat) {
            return $this->mpt_tagozat->getId();
        }
        return false;
    }

    public function getMptTagozatNev()
    {
        if ($this->mpt_tagozat) {
            return $this->mpt_tagozat->getNev();
        }
        return false;
    }

    public function setMptTagozat($val)
    {
        if ($this->mpt_tagozat !== $val) {
            if (!$val) {
                $this->mpt_tagozat = null;
            } else {
                $this->mpt_tagozat = $val;
            }
        }
    }

    public function getMptTagsagforma()
    {
        return $this->mpt_tagsagforma;
    }

    public function getMptTagsagformaId()
    {
        if ($this->mpt_tagsagforma) {
            return $this->mpt_tagsagforma->getId();
        }
        return false;
    }

    public function getMptTagsagformaNev()
    {
        if ($this->mpt_tagsagforma) {
            return $this->mpt_tagsagforma->getNev();
        }
        return false;
    }

    public function setMptTagsagforma($val)
    {
        if ($this->mpt_tagsagforma !== $val) {
            if (!$val) {
                $this->mpt_tagsagforma = null;
            } else {
                $this->mpt_tagsagforma = $val;
            }
        }
    }

    /**
     * @return mixed
     */
    public function getMptTagsagdate()
    {
        return $this->mpt_tagsagdate;
    }

    public function getMptTagsagdateStr()
    {
        if ($this->getMptTagsagdate()) {
            return $this->getMptTagsagdate()->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    /**
     * @param mixed $mpt_tagsagdate
     */
    public function setMptTagsagdate($mpt_tagsagdate): void
    {
        if ($mpt_tagsagdate != '') {
            $this->mpt_tagsagdate = new \DateTime(\mkw\store::convDate($mpt_tagsagdate));
        }
    }

    /**
     * @return string
     */
    public function getSzlanev()
    {
        return $this->szlanev;
    }

    /**
     * @param string $szlanev
     */
    public function setSzlanev($szlanev): void
    {
        $this->szlanev = $szlanev;
    }

    public function getMptngyszerepkor()
    {
        return $this->mptngyszerepkor;
    }

    public function getMptngyszerepkorNev()
    {
        if ($this->mptngyszerepkor) {
            return $this->mptngyszerepkor->getNev();
        }
        return '';
    }

    public function getMptngyszerepkorId()
    {
        if ($this->mptngyszerepkor) {
            return $this->mptngyszerepkor->getId();
        }
        return '';
    }

    public function setMptngyszerepkor($val)
    {
        if ($this->mptngyszerepkor !== $val) {
            $this->mptngyszerepkor = $val;
        }
    }

    public function removeMptngyszerepkor()
    {
        if ($this->mptngyszerepkor !== null) {
            $this->mptngyszerepkor = null;
        }
    }

    /**
     * @return bool
     */
    public function isMptngynapreszvetel1()
    {
        return $this->mptngynapreszvetel1;
    }

    /**
     * @param bool $mptngynapreszvetel1
     */
    public function setMptngynapreszvetel1($mptngynapreszvetel1): void
    {
        $this->mptngynapreszvetel1 = $mptngynapreszvetel1;
    }

    /**
     * @return bool
     */
    public function isMptngynapreszvetel2()
    {
        return $this->mptngynapreszvetel2;
    }

    /**
     * @param bool $mptngynapreszvetel2
     */
    public function setMptngynapreszvetel2($mptngynapreszvetel2): void
    {
        $this->mptngynapreszvetel2 = $mptngynapreszvetel2;
    }

    /**
     * @return bool
     */
    public function isMptngynapreszvetel3()
    {
        return $this->mptngynapreszvetel3;
    }

    /**
     * @param bool $mptngynapreszvetel3
     */
    public function setMptngynapreszvetel3($mptngynapreszvetel3): void
    {
        $this->mptngynapreszvetel3 = $mptngynapreszvetel3;
    }

    /**
     * @return mixed
     */
    public function getMptngycsoportosfizetes()
    {
        return $this->mptngycsoportosfizetes;
    }

    /**
     * @param mixed $mptngycsoportosfizetes
     */
    public function setMptngycsoportosfizetes($mptngycsoportosfizetes): void
    {
        $this->mptngycsoportosfizetes = $mptngycsoportosfizetes;
    }

    /**
     * @return bool
     */
    public function isMptngyvipvacsora()
    {
        return $this->mptngyvipvacsora;
    }

    /**
     * @param bool $mptngyvipvacsora
     */
    public function setMptngyvipvacsora($mptngyvipvacsora): void
    {
        $this->mptngyvipvacsora = $mptngyvipvacsora;
    }

    /**
     * @return bool
     */
    public function getMptngykapcsolatnev()
    {
        return $this->mptngykapcsolatnev;
    }

    /**
     * @param bool $mptngykapcsolatnev
     */
    public function setMptngykapcsolatnev($mptngykapcsolatnev): void
    {
        $this->mptngykapcsolatnev = $mptngykapcsolatnev;
    }

    /**
     * @return string
     */
    public function getMptngybankszamlaszam()
    {
        return $this->mptngybankszamlaszam;
    }

    /**
     * @param string $mptngybankszamlaszam
     */
    public function setMptngybankszamlaszam($mptngybankszamlaszam): void
    {
        $this->mptngybankszamlaszam = $mptngybankszamlaszam;
    }

    /**
     * @return bool
     */
    public function isMptngydiak()
    {
        return $this->mptngydiak;
    }

    /**
     * @param bool $mptngydiak
     */
    public function setMptngydiak($mptngydiak): void
    {
        $this->mptngydiak = $mptngydiak;
    }

    /**
     * @return bool
     */
    public function isMptngympttag()
    {
        return $this->mptngympttag;
    }

    /**
     * @param bool $mptngympttag
     */
    public function setMptngympttag($mptngympttag): void
    {
        $this->mptngympttag = $mptngympttag;
    }

    /**
     * @return bool
     */
    public function isMptngynyugdijas()
    {
        return $this->mptngynyugdijas;
    }

    /**
     * @param bool $mptngynyugdijas
     */
    public function setMptngynyugdijas($mptngynyugdijas): void
    {
        $this->mptngynyugdijas = $mptngynyugdijas;
    }

    /**
     * @return bool
     */
    public function isMptngybankett()
    {
        return $this->mptngybankett;
    }

    /**
     * @param bool $mptngybankett
     */
    public function setMptngybankett($mptngybankett): void
    {
        $this->mptngybankett = $mptngybankett;
    }

    public function getMptngybefizetesdatum()
    {
        if (!$this->id && !$this->mptngybefizetesdatum) {
            $this->mptngybefizetesdatum = new \DateTime(\mkw\store::convDate(date(\mkw\store::$DateFormat)));
        }
        return $this->mptngybefizetesdatum;
    }

    public function getMptngybefizetesdatumStr()
    {
        if ($this->getMptngybefizetesdatum()) {
            return $this->getMptngybefizetesdatum()->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    public function setMptngybefizetesdatum($adat = '')
    {
        if (is_a($adat, 'DateTime')) {
            $this->mptngybefizetesdatum = $adat;
        } else {
            if ($adat == '') {
                $adat = date(\mkw\store::$DateFormat);
            }
            $this->mptngybefizetesdatum = new \DateTime(\mkw\store::convDate($adat));
        }
    }

    public function getMptngybefizetesmod()
    {
        return $this->mptngybefizetesmod;
    }

    public function setMptngybefizetesmod($fizmod = null)
    {
        $this->mptngybefizetesmod = $fizmod;
    }

    /**
     * @return mixed
     */
    public function getMptngybefizetes()
    {
        return $this->mptngybefizetes;
    }

    /**
     * @param mixed $mptngybefizetes
     */
    public function setMptngybefizetes($mptngybefizetes): void
    {
        $this->mptngybefizetes = $mptngybefizetes;
    }

    /**
     * @param \Entities\Emailtemplate $emailtpl
     * @param \Entities\Partner|null $p
     */
    public function sendEmailSablon($emailtpl, $p = null)
    {
        if (!$p) {
            $p = $this;
        }
        if ($emailtpl) {
            $tpldata = $p->toLista();
            $subject = \mkw\store::getTemplateFactory()->createMainView('string:' . $emailtpl->getTargy());
            $subject->setVar('partner', $tpldata);
            $body = \mkw\store::getTemplateFactory()->createMainView('string:' . str_replace('&#39;', '\'', html_entity_decode($emailtpl->getHTMLSzoveg())));
            $body->setVar('partner', $tpldata);
            $body->setVar('mainurl', \mkw\store::getConfigValue('mainurl'));
            if (\mkw\store::getConfigValue('developer')) {
                \mkw\store::writelog($subject->getTemplateResult(), 'partneremail.html');
                \mkw\store::writelog($body->getTemplateResult(), 'partneremail.html');
            } else {
                $mailer = \mkw\store::getMailer();
                $mailer->addTo($p->getEmail());
                $mailer->setSubject($subject->getTemplateResult());
                $mailer->setMessage($body->getTemplateResult());
                $mailer->send();
            }
        }
    }

    /**
     * @return bool
     */
    public function isMptngynemveszreszt()
    {
        return $this->mptngynemveszreszt;
    }

    /**
     * @param bool $mptngynemveszreszt
     */
    public function setMptngynemveszreszt($mptngynemveszreszt): void
    {
        $this->mptngynemveszreszt = $mptngynemveszreszt;
    }

    /**
     * @return string
     */
    public function getCsoportosadoszam()
    {
        return $this->csoportosadoszam;
    }

    /**
     * @param string $csoportosadoszam
     */
    public function setCsoportosadoszam($csoportosadoszam): void
    {
        $this->csoportosadoszam = $csoportosadoszam;
    }

    /**
     * @return bool
     */
    public function isXNemrendelhet()
    {
        switch (\mkw\store::getSetupValue('webshopnum', 1)) {
            case 1:
                return $this->isNemrendelhet();
            case 2:
                return $this->isNemrendelhet2();
            case 3:
                return $this->isNemrendelhet3();
            case 4:
                return $this->isNemrendelhet4();
            case 5:
                return $this->isNemrendelhet5();
        }
    }

    /**
     * @return bool
     */
    public function isNemrendelhet()
    {
        return $this->nemrendelhet;
    }

    /**
     * @param bool $nemrendelhet
     */
    public function setNemrendelhet($nemrendelhet): void
    {
        $this->nemrendelhet = $nemrendelhet;
    }

    /**
     * @return bool
     */
    public function isNemrendelhet2()
    {
        return $this->nemrendelhet2;
    }

    /**
     * @param bool $nemrendelhet2
     */
    public function setNemrendelhet2($nemrendelhet2): void
    {
        $this->nemrendelhet2 = $nemrendelhet2;
    }

    /**
     * @return bool
     */
    public function isNemrendelhet3()
    {
        return $this->nemrendelhet3;
    }

    /**
     * @param bool $nemrendelhet3
     */
    public function setNemrendelhet3($nemrendelhet3): void
    {
        $this->nemrendelhet3 = $nemrendelhet3;
    }

    /**
     * @return bool
     */
    public function isNemrendelhet4()
    {
        return $this->nemrendelhet4;
    }

    /**
     * @param bool $nemrendelhet4
     */
    public function setNemrendelhet4($nemrendelhet4): void
    {
        $this->nemrendelhet4 = $nemrendelhet4;
    }

    /**
     * @return bool
     */
    public function isNemrendelhet5()
    {
        return $this->nemrendelhet5;
    }

    /**
     * @param bool $nemrendelhet5
     */
    public function setNemrendelhet5($nemrendelhet5): void
    {
        $this->nemrendelhet5 = $nemrendelhet5;
    }

    public function getArsav()
    {
        return $this->arsav;
    }

    public function setArsav($val)
    {
        if (!($val instanceof \Entities\Arsav)) {
            $val = \mkw\store::getEm()->getRepository(Arsav::class)->find($val);
        }
        if ($this->arsav !== $val) {
            $this->arsav = $val;
        }
    }

    public function removeArsav()
    {
        $this->arsav = null;
    }

    public function getTermekarazonosito()
    {
        return $this->termekarazonosito;
    }

    public function setTermekarazonosito($v)
    {
        $this->termekarazonosito = $v;
    }

    /**
     * @return string
     */
    public function getMptSzamlazasinev()
    {
        return $this->mpt_szamlazasinev;
    }

    /**
     * @param string $mpt_szamlazasinev
     */
    public function setMptSzamlazasinev($mpt_szamlazasinev): void
    {
        $this->mpt_szamlazasinev = $mpt_szamlazasinev;
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

    public function isDefaultSzallorszag()
    {
        if ($this->szallorszag) {
            return $this->szallorszag->isDefault();
        }
        return true;
    }

    public function getSzallorszag()
    {
        return $this->szallorszag;
    }

    public function getSzallorszagNev()
    {
        if ($this->szallorszag) {
            return $this->szallorszag->getNev();
        }
        return '';
    }

    public function getSzallorszagId()
    {
        if ($this->szallorszag) {
            return $this->szallorszag->getId();
        }
        return '';
    }

    public function setSzallorszag($val)
    {
        if ($this->szallorszag !== $val) {
            $this->szallorszag = $val;
        }
    }

    public function removeSzallorszag()
    {
        if ($this->szallorszag !== null) {
            $this->szallorszag = null;
        }
    }

    /**
     * @return mixed
     */
    public function shouldSkipListener()
    {
        return $this->skipListener;
    }

    /**
     * @param mixed $skipListener
     */
    public function setSkipListener($skipListener): void
    {
        $this->skipListener = $skipListener;
    }

}
