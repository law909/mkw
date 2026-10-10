<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;
use Traits\GetsFieldValue;

/** @ORM\Entity(repositoryClass="Entities\BizonylattipusRepository")
 * @ORM\Table(name="bizonylattipus",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 **/
class Bizonylattipus
{
    use GetsFieldValue;

    /**
     * @ORM\Id @ORM\Column(type="string",length=30)
     */
    private $id;
    /** @ORM\Column(type="string",length=100) */
    private $nev;
    /** @ORM\Column(type="integer") */
    private $irany = -1;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $nyomtatni = true;
    /** @ORM\Column(type="string",length=10,nullable=true) */
    private $azonosito;
    /** @ORM\Column(type="integer") */
    private $kezdosorszam = 0;
    /** @ORM\Column(type="integer") */
    private $peldanyszam = 1;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $mozgat = true;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $foglal = false;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $penztmozgat = true;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $editprinted = false;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $showteljesites = false;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $showesedekesseg = false;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $showhatarido = false;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $showbeerkezes = false;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $showbizonylatstatuszeditor = false;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $showszamlabutton = false;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $showszallitobutton = false;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $showkivetbutton = false;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $showkeziszamlabutton = false;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $showbevetbutton = false;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $showszallmegrbutton = false;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $showboltieladasbutton = false;
    /** Gépjármű kísérő képezhető belőle. */
    /** @ORM\Column(type="boolean",nullable=false) */
    private $showautokiserobutton = false;
    /** Megrendelés képezhető belőle. */
    /** @ORM\Column(type="boolean",nullable=false) */
    private $showmegrendelesbutton = false;
    /** B2B rendelés képezhető belőle. */
    /** @ORM\Column(type="boolean",nullable=false) */
    private $showb2bmegrendelesbutton = false;
    /** Webshop rendelés képezhető belőle. */
    /** @ORM\Column(type="boolean",nullable=false) */
    private $showwebshopmegrendelesbutton = false;
    /** Megrendelésen: előlegszámla képezhető belőle (fej-only inherit, egyetlen előlegsorral). */
    /** @ORM\Column(type="boolean",nullable=false) */
    private $showelolegbutton = false;
    /** Számlán: az "Előleg beszámítása" választó a tételek fölött. */
    /** @ORM\Column(type="boolean",nullable=false) */
    private $showelolegbeszamitas = false;
    /** Előlegszámlán: hiteles eladás – az előleg stornója, a befizetése átvezetve a hitelező számlájára. */
    /** @ORM\Column(type="boolean",nullable=false,options={"default":0}) */
    private $showhiteleseladasbutton = false;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $sendemail = false;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $showuzenet = false;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $showszallitasicim = false;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $showerbizonylatszam = false;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $showfuvarlevelszam = false;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $showhaszonszazalek = false;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $showstorno = false;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $showbackorder = false;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $showslicemanufacturerbutton = false;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $showcsomagbutton = false;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $showfeketelistabutton = false;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $showkupon = false;
    /** @ORM\Column(type="boolean",nullable=false,options={"default":1}) */
    private $tetelkotelezo = true;
    /** @ORM\Column(type="boolean",nullable=false,options={"default":0}) */
    private $showpenztmozgat = false;
    /** @ORM\Column(type="boolean",nullable=false,options={"default":0}) */
    private $showcsomagolasilista = false;
    /** @ORM\Column(type="boolean",nullable=false,options={"default":1}) */
    private $megjegyzesnyomtatasban = true;
    /** @ORM\OneToMany(targetEntity="Bizonylatfej", mappedBy="bizonylattipus",cascade={"persist"}) */
    private $bizonylatfejek;
    /** @ORM\Column(type="string",length=200,nullable=true) */
    private $tplname;
    /** @ORM\Column(type="string",length=200,nullable=true) */
    private $tplname_l1;
    /** Második nyomtatási forma; ha ki van töltve, a listán külön nyomtatás gomb tartozik hozzá. */
    /** @ORM\Column(type="string",length=200,nullable=true) */
    private $tplname2;
    /** @ORM\Column(type="string",length=200,nullable=true) */
    private $tplname2_l1;
    /** A második nyomtatás gomb felirata (title). */
    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $tplcaption2;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $showfoxpostterminaleditor = false;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $showfelhasznalo = false;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $checkkelt = true;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $showpdf = false;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $navbekuldendo = false;
    /**
     * @ORM\Column(type="boolean",nullable=false)
     */
    private $autopenztarbizonylat = false;
    /**
     * Képződjenek-e a tételekhez a termék kapcsolódó költségei
     * (\Listeners\BizonylatfejListener::createKapcsolodoKoltseg()).
     *
     * @ORM\Column(type="boolean",nullable=false)
     */
    private $kellkapcsolodokoltsegetszamolni = false;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $showemailbutton = false;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $showeddigimegrendeleseiurl = false;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $showgarancialisadatok = false;
    /** A munkalap saját fejadatai: jármű, km óra, hiba, következő szerviz, munkalap státusz. */
    /** @ORM\Column(type="boolean",nullable=false) */
    private $showmunkalapadatok = false;
    /**
     * A "Küldés emailben" levele (a bizonylat PDF-je mellékletként); üresen a típus bizonylatai így nem küldhetők.
     *
     * @ORM\ManyToOne(targetEntity="Emailtemplate")
     * @ORM\JoinColumn(name="pdflevelsablon_id", referencedColumnName="id",nullable=true,onDelete="set null")
     */
    private $pdflevelsablon;
    /**
     * A típus saját "rögzítve", "teljesíthető" és "backorder" státusza; üresen a Beállítások
     * szerinti közös státusz érvényes (getRogzitveStatuszOrDefault() és társai).
     *
     * @ORM\ManyToOne(targetEntity="Bizonylatstatusz")
     * @ORM\JoinColumn(name="rogzitvestatusz_id", referencedColumnName="id",nullable=true,onDelete="set null")
     */
    private $rogzitvestatusz;
    /**
     * @ORM\ManyToOne(targetEntity="Bizonylatstatusz")
     * @ORM\JoinColumn(name="teljesithetostatusz_id", referencedColumnName="id",nullable=true,onDelete="set null")
     */
    private $teljesithetostatusz;
    /**
     * @ORM\ManyToOne(targetEntity="Bizonylatstatusz")
     * @ORM\JoinColumn(name="backorderstatusz_id", referencedColumnName="id",nullable=true,onDelete="set null")
     */
    private $backorderstatusz;

    /**
     * A számla jellegű bizonylattípusok id-i. A típuson nincs „ez számla" jelző, a
     * `navbekuldendo` pedig más kérdésre válaszol, ezért azonosítólista.
     */
    public const SZAMLATIPUSOK = ['szamla', 'esetiszamla', 'keziszamla'];

    /** A származtató gombok sablonváltozója => a létrehozott bizonylat típusa. */
    private const INHERITBUTTONS = [
        'showszamlabutton' => 'szamla',
        'showkeziszamlabutton' => 'keziszamla',
        'showszallitobutton' => 'szallito',
        'showkivetbutton' => 'kivet',
        'showbevetbutton' => 'bevet',
        'showszallmegrbutton' => 'szallmegr',
        'showboltieladasbutton' => 'boltieladas',
        'showautokiserobutton' => 'autokisero',
        'showmegrendelesbutton' => 'megrendeles',
        'showb2bmegrendelesbutton' => 'b2brendeles',
        'showwebshopmegrendelesbutton' => 'webshopbiz',
        'showelolegbutton' => 'elolegszamla',
        'showcsomagbutton' => 'csomag',
    ];

    /** A szállítói megrendelés típusa: az ezen szereplő áru még úton van. */
    public const SZALLITOIMEGRENDELES = 'szallmegr';

    /** @var string[]|null a getFoglalIdList() kérésen belüli cache-e */
    private static $foglalIdList;

    public function __construct()
    {
        $this->bizonylatfejek = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function setTemplateVars($view)
    {
        foreach ($this->getTemplateVars() as $key => $value) {
            $view->setVar($key, $value);
        }
    }

    public function getTemplateVars()
    {
        $vars = [
            'showteljesites' => $this->getShowteljesites(),
            'showesedekesseg' => $this->getShowesedekesseg(),
            'showhatarido' => $this->getShowhatarido(),
            'showbeerkezes' => $this->getShowbeerkezes(),
            'showvalutanem' => \mkw\store::isMultiValuta(),
            'showbizonylatstatuszeditor' => $this->getShowbizonylatstatuszeditor(),
            'showszamlabutton' => $this->getShowszamlabutton(),
            'showkeziszamlabutton' => $this->getShowkeziszamlabutton(),
            'showszallitobutton' => $this->getShowszallitobutton(),
            'showkivetbutton' => $this->getShowkivetbutton(),
            'showbevetbutton' => $this->getShowbevetbutton(),
            'showszallmegrbutton' => $this->getShowszallmegrbutton(),
            'showboltieladasbutton' => $this->getShowboltieladasbutton(),
            'showautokiserobutton' => $this->getShowautokiserobutton(),
            'showmegrendelesbutton' => $this->getShowmegrendelesbutton(),
            'showb2bmegrendelesbutton' => $this->getShowb2bmegrendelesbutton(),
            'showwebshopmegrendelesbutton' => $this->getShowwebshopmegrendelesbutton(),
            'showelolegbutton' => $this->getShowelolegbutton(),
            'showelolegbeszamitas' => $this->getShowelolegbeszamitas(),
            'showhiteleseladasbutton' => $this->getShowhiteleseladasbutton(),
            'nyomtatni' => $this->getNyomtatni(),
            'tipuseditprinted' => $this->getEditprinted(),
            'sendemail' => $this->getSendemail(),
            'showuzenet' => $this->getShowuzenet(),
            'showszallitasicim' => $this->getShowszallitasicim(),
            'showerbizonylatszam' => $this->getShowerbizonylatszam(),
            'showfuvarlevelszam' => $this->getShowfuvarlevelszam(),
            'showhaszonszazalek' => $this->getShowhaszonszazalek(),
            'showstorno' => $this->getShowstorno(),
            'showbackorder' => $this->getShowbackorder(),
            'showslicemanufacturerbutton' => $this->getShowslicemanufacturerbutton(),
            'showcsomagbutton' => $this->getShowcsomagbutton(),
            'showfeketelistabutton' => $this->getShowfeketelistabutton(),
            'showkupon' => $this->getShowkupon(),
            'tetelkotelezo' => $this->getTetelkotelezo(),
            'showpenztmozgat' => $this->getShowpenztmozgat(),
            'showcsomagolasilista' => $this->getShowcsomagolasilista(),
            'showfoxpostterminaleditor' => $this->getShowfoxpostterminaleditor(),
            'showfelhasznalo' => $this->getShowfelhasznalo(),
            'showpdf' => $this->getShowpdf(),
            'showemailpdf' => $this->getShowpdf() && $this->pdflevelsablon,
            'shownavallapot' => $this->getNavbekuldendo(),
            'showforditottadozas' => $this->getId() === 'szamla' || $this->getId() === 'esetiszamla',
            'showrendszeres' => $this->getId() === 'bizsablon',
            'showprint2' => (bool)$this->getTplname2(),
            'tplcaption2' => $this->getTplcaption2(),
            'showemailbutton' => $this->getShowemailbutton(),
            'showeddigimegrendeleseiurl' => $this->getShoweddigimegrendeleseiurl(),
            'showgarancialisadatok' => $this->getShowgarancialisadatok(),
            'showmunkalapadatok' => $this->getShowmunkalapadatok(),
            // a pénztár csak az automatikus pénztárbizonylatot képző típusokon szerkeszthető
            'showpenztar' => $this->getAutopenztarbizonylat(),
        ];
        // szigorú módban a beküldetlen típusba származtató gomb sem látszik, ahogy az új felvétel sem
        $tiltott = \mkw\store::isSzigoru() ? (new \Services\BizonylatNAVService())->getBekuldetlenTipusok() : [];
        foreach (self::INHERITBUTTONS as $key => $tipusid) {
            $vars[$key] = $vars[$key] && !in_array($tipusid, $tiltott, true);
        }
        return $vars;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($val)
    {
        $this->id = $val;
    }

    public function getNev()
    {
        return $this->nev;
    }

    public function setNev($val)
    {
        $this->nev = $val;
    }

    public function getIrany()
    {
        return $this->irany;
    }

    public function setIrany($val)
    {
        $this->irany = $val;
    }

    public function getNyomtatni()
    {
        return $this->nyomtatni;
    }

    public function setNyomtatni($val)
    {
        $this->nyomtatni = $val;
    }

    public function getAzonosito()
    {
        return $this->azonosito;
    }

    public function getAzonositoForRegexp()
    {
        $result = '';

        for ($i = 0; $i < strlen($this->getAzonosito()); $i++) {
            $char = $this->getAzonosito()[$i];

            $lowercase = strtolower($char);
            $uppercase = strtoupper($char);

            $result .= '[' . $uppercase . $lowercase . ']?';
        }

        return $result;
    }

    public function setAzonosito($val)
    {
        $this->azonosito = $val;
    }

    public function getKezdosorszam()
    {
        return $this->kezdosorszam;
    }

    public function setKezdosorszam($val)
    {
        $this->kezdosorszam = $val;
    }

    public function getPeldanyszam()
    {
        return $this->peldanyszam;
    }

    public function setPeldanyszam($val)
    {
        $this->peldanyszam = $val;
    }

    public function getMozgat()
    {
        return $this->mozgat;
    }

    public function setMozgat($val)
    {
        $this->mozgat = $val;
    }

    public function getPenztmozgat()
    {
        return $this->penztmozgat;
    }

    public function setPenztmozgat($val)
    {
        $this->penztmozgat = $val;
    }

    public function getEditprinted()
    {
        return $this->editprinted;
    }

    public function setEditprinted($val)
    {
        $this->editprinted = $val;
    }

    public function getShowteljesites()
    {
        return $this->showteljesites;
    }

    public function setShowteljesites($show)
    {
        $this->showteljesites = $show;
    }

    public function getShowesedekesseg()
    {
        return $this->showesedekesseg;
    }

    public function setShowesedekesseg($show)
    {
        $this->showesedekesseg = $show;
    }

    public function getShowhatarido()
    {
        return $this->showhatarido;
    }

    public function setShowhatarido($show)
    {
        $this->showhatarido = $show;
    }

    public function getShowbeerkezes()
    {
        return $this->showbeerkezes;
    }

    public function setShowbeerkezes($show)
    {
        $this->showbeerkezes = $show;
    }

    public function getShowvalutanem()
    {
        return \mkw\store::isMultiValuta();
    }

    public function getTplname()
    {
        return $this->tplname;
    }

    public function setTplname($d)
    {
        $this->tplname = $d;
    }

    public function getTplnameL1()
    {
        return $this->tplname_l1;
    }

    public function setTplnameL1($d)
    {
        $this->tplname_l1 = $d;
    }

    public function getTplname2()
    {
        return $this->tplname2;
    }

    public function setTplname2($d)
    {
        $this->tplname2 = $d;
    }

    public function getTplname2L1()
    {
        return $this->tplname2_l1;
    }

    public function setTplname2L1($d)
    {
        $this->tplname2_l1 = $d;
    }

    public function getTplcaption2()
    {
        return $this->tplcaption2;
    }

    public function setTplcaption2($d)
    {
        $this->tplcaption2 = $d;
    }

    public function getShowbizonylatstatuszeditor()
    {
        return $this->showbizonylatstatuszeditor;
    }

    public function setShowbizonylatstatuszeditor($val)
    {
        $this->showbizonylatstatuszeditor = $val;
    }

    public function getShowszamlabutton()
    {
        return $this->showszamlabutton;
    }

    public function setShowszamlabutton($val)
    {
        $this->showszamlabutton = $val;
    }

    public function getShowkeziszamlabutton()
    {
        return $this->showkeziszamlabutton;
    }

    public function setShowkeziszamlabutton($val)
    {
        $this->showkeziszamlabutton = $val;
    }

    public function getShowkivetbutton()
    {
        return $this->showkivetbutton;
    }

    public function setShowkivetbutton($val)
    {
        $this->showkivetbutton = $val;
    }

    public function getShowbevetbutton()
    {
        return $this->showbevetbutton;
    }

    public function setShowbevetbutton($val)
    {
        $this->showbevetbutton = $val;
    }

    public function getShowszallmegrbutton()
    {
        return $this->showszallmegrbutton;
    }

    public function setShowszallmegrbutton($val)
    {
        $this->showszallmegrbutton = $val;
    }

    public function getShowboltieladasbutton()
    {
        return $this->showboltieladasbutton;
    }

    public function setShowboltieladasbutton($val)
    {
        $this->showboltieladasbutton = $val;
    }

    public function getShowautokiserobutton()
    {
        return $this->showautokiserobutton;
    }

    public function setShowautokiserobutton($val)
    {
        $this->showautokiserobutton = $val;
    }

    public function getShowmegrendelesbutton()
    {
        return $this->showmegrendelesbutton;
    }

    public function setShowmegrendelesbutton($val)
    {
        $this->showmegrendelesbutton = $val;
    }

    public function getShowb2bmegrendelesbutton()
    {
        return $this->showb2bmegrendelesbutton;
    }

    public function setShowb2bmegrendelesbutton($val)
    {
        $this->showb2bmegrendelesbutton = $val;
    }

    public function getShowwebshopmegrendelesbutton()
    {
        return $this->showwebshopmegrendelesbutton;
    }

    public function setShowwebshopmegrendelesbutton($val)
    {
        $this->showwebshopmegrendelesbutton = $val;
    }

    public function getShowelolegbutton()
    {
        return $this->showelolegbutton;
    }

    public function setShowelolegbutton($val)
    {
        $this->showelolegbutton = $val;
    }

    public function getShowelolegbeszamitas()
    {
        return $this->showelolegbeszamitas;
    }

    public function setShowelolegbeszamitas($val)
    {
        $this->showelolegbeszamitas = $val;
    }

    public function getSendemail()
    {
        return $this->sendemail;
    }

    public function setSendemail($val)
    {
        $this->sendemail = $val;
    }

    public function getShowszallitobutton()
    {
        return $this->showszallitobutton;
    }

    public function setShowszallitobtn($val)
    {
        $this->showszallitobutton = $val;
    }

    public function getShowuzenet()
    {
        return $this->showuzenet;
    }

    public function setShowuzenet($val)
    {
        $this->showuzenet = $val;
    }

    public function getShowszallitasicim()
    {
        return $this->showszallitasicim;
    }

    public function setShowszallitasicim($val)
    {
        $this->showszallitasicim = $val;
    }

    public function getShowerbizonylatszam()
    {
        return $this->showerbizonylatszam;
    }

    public function setShowerbizonylatszam($val)
    {
        $this->showerbizonylatszam = $val;
    }

    public function getShowfuvarlevelszam()
    {
        return $this->showfuvarlevelszam;
    }

    public function setShowfuvarlevelszam($val)
    {
        $this->showfuvarlevelszam = $val;
    }

    public function getShowhaszonszazalek()
    {
        return $this->showhaszonszazalek;
    }

    public function setShowhaszonszazalek($val)
    {
        $this->showhaszonszazalek = $val;
    }

    public function getShowstorno()
    {
        return $this->showstorno;
    }

    public function setShowstorno($adat)
    {
        $this->showstorno = $adat;
    }

    public function getShowbackorder()
    {
        return $this->showbackorder;
    }

    public function setShowbackorder($adat)
    {
        $this->showbackorder = $adat;
    }

    public function getShowslicemanufacturerbutton()
    {
        return $this->showslicemanufacturerbutton;
    }

    public function setShowslicemanufacturerbutton($adat)
    {
        $this->showslicemanufacturerbutton = $adat;
    }

    public function getFoglal()
    {
        return $this->foglal;
    }

    public function setFoglal($adat)
    {
        $this->foglal = $adat;
    }

    /**
     * A foglalást nyilvántartó bizonylattípusok id-i.
     *
     * @return string[]
     */
    public static function getFoglalIdList(): array
    {
        if (is_null(self::$foglalIdList)) {
            $rows = \mkw\store::getEm()->createQueryBuilder()
                ->select('bt.id')->from(self::class, 'bt')
                ->where('bt.foglal = 1')
                ->orderBy('bt.id', 'ASC')
                ->getQuery()->getScalarResult();
            self::$foglalIdList = array_column($rows, 'id');
        }
        return self::$foglalIdList;
    }

    public function getShowmunkalapadatok()
    {
        return $this->showmunkalapadatok;
    }

    public function setShowmunkalapadatok($showmunkalapadatok): void
    {
        $this->showmunkalapadatok = $showmunkalapadatok;
    }

    /** Számla jellegű-e a típus (a belőle képzett bizonylat lezárja az elődjét). */
    public function isSzamlajellegu(): bool
    {
        return in_array($this->id, self::SZAMLATIPUSOK, true);
    }

    public static function isRonthato($biztipusid)
    {
    }

    /**
     * @return mixed
     */
    public function getShowcsomagbutton()
    {
        return $this->showcsomagbutton;
    }

    /**
     * @param mixed $showcsomagbutton
     */
    public function setShowcsomagbutton($showcsomagbutton)
    {
        $this->showcsomagbutton = $showcsomagbutton;
    }

    /**
     * @return mixed
     */
    public function getShowfeketelistabutton()
    {
        return $this->showfeketelistabutton;
    }

    /**
     * @param mixed $showfeketelistabutton
     */
    public function setShowfeketelistabutton($showfeketelistabutton)
    {
        $this->showfeketelistabutton = $showfeketelistabutton;
    }

    /**
     * @return mixed
     */
    public function getShowkupon()
    {
        return $this->showkupon;
    }

    /**
     * @param mixed $showkupon
     */
    public function setShowkupon($showkupon)
    {
        $this->showkupon = $showkupon;
    }

    public function getTetelkotelezo()
    {
        return $this->tetelkotelezo;
    }

    public function setTetelkotelezo($tetelkotelezo)
    {
        $this->tetelkotelezo = $tetelkotelezo;
    }

    public function getShowpenztmozgat()
    {
        return $this->showpenztmozgat;
    }

    public function setShowpenztmozgat($val)
    {
        $this->showpenztmozgat = $val;
    }

    public function getShowcsomagolasilista()
    {
        return $this->showcsomagolasilista;
    }

    public function setShowcsomagolasilista($val)
    {
        $this->showcsomagolasilista = $val;
    }

    public function getMegjegyzesnyomtatasban()
    {
        return $this->megjegyzesnyomtatasban;
    }

    public function setMegjegyzesnyomtatasban($val)
    {
        $this->megjegyzesnyomtatasban = (bool)$val;
    }

    public function getShowhiteleseladasbutton()
    {
        return $this->showhiteleseladasbutton;
    }

    public function setShowhiteleseladasbutton($val)
    {
        $this->showhiteleseladasbutton = (bool)$val;
    }

    /**
     * @return mixed
     */
    public function getShowfoxpostterminaleditor()
    {
        return $this->showfoxpostterminaleditor;
    }

    /**
     * @param mixed $showfoxpostterminaleditor
     */
    public function setShowfoxpostterminaleditor($showfoxpostterminaleditor)
    {
        $this->showfoxpostterminaleditor = $showfoxpostterminaleditor;
    }

    /**
     * @return mixed
     */
    public function getShowfelhasznalo()
    {
        return $this->showfelhasznalo;
    }

    /**
     * @param mixed $showfelhasznalo
     */
    public function setShowfelhasznalo($showfelhasznalo)
    {
        $this->showfelhasznalo = $showfelhasznalo;
    }

    /**
     * @return mixed
     */
    public function getCheckkelt()
    {
        return $this->checkkelt;
    }

    /**
     * @param mixed $checkkelt
     */
    public function setCheckkelt($checkkelt)
    {
        $this->checkkelt = $checkkelt;
    }

    /**
     * @return mixed
     */
    public function getShowpdf()
    {
        return $this->showpdf;
    }

    /**
     * @param mixed $showpdf
     */
    public function setShowpdf($showpdf)
    {
        $this->showpdf = $showpdf;
    }

    /**
     * @return bool
     */
    public function getNavbekuldendo()
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
    public function getAutopenztarbizonylat()
    {
        return $this->autopenztarbizonylat;
    }

    /**
     * @param bool $autopenztarbizonylat
     */
    public function setAutopenztarbizonylat($autopenztarbizonylat)
    {
        $this->autopenztarbizonylat = $autopenztarbizonylat;
    }

    /**
     * @return bool
     */
    public function getKellkapcsolodokoltsegetszamolni()
    {
        return $this->kellkapcsolodokoltsegetszamolni;
    }

    /**
     * @param bool $kellkapcsolodokoltsegetszamolni
     */
    public function setKellkapcsolodokoltsegetszamolni($kellkapcsolodokoltsegetszamolni)
    {
        $this->kellkapcsolodokoltsegetszamolni = $kellkapcsolodokoltsegetszamolni;
    }

    /**
     * @return bool
     */
    public function getShowemailbutton()
    {
        return $this->showemailbutton;
    }

    /**
     * @param bool $showemailbutton
     */
    public function setShowemailbutton($showemailbutton): void
    {
        $this->showemailbutton = $showemailbutton;
    }

    /**
     * @return bool
     */
    public function getShoweddigimegrendeleseiurl()
    {
        return $this->showeddigimegrendeleseiurl;
    }

    /**
     * @param bool $showeddigimegrendeleseiurl
     */
    public function setShoweddigimegrendeleseiurl($showeddigimegrendeleseiurl): void
    {
        $this->showeddigimegrendeleseiurl = $showeddigimegrendeleseiurl;
    }

    /**
     * @return bool
     */
    public function getShowgarancialisadatok()
    {
        return $this->showgarancialisadatok;
    }

    /**
     * @param bool $showgarancialisadatok
     */
    public function setShowgarancialisadatok($showgarancialisadatok): void
    {
        $this->showgarancialisadatok = $showgarancialisadatok;
    }

    public function getPdflevelsablon(): ?Emailtemplate
    {
        return $this->pdflevelsablon;
    }

    public function getPdflevelsablonId()
    {
        return $this->pdflevelsablon?->getId();
    }

    public function setPdflevelsablon(?Emailtemplate $val): void
    {
        $this->pdflevelsablon = $val;
    }

    public function getRogzitvestatusz(): ?Bizonylatstatusz
    {
        return $this->rogzitvestatusz;
    }

    public function getRogzitvestatuszId()
    {
        return $this->rogzitvestatusz?->getId();
    }

    public function setRogzitvestatusz(?Bizonylatstatusz $val): void
    {
        $this->rogzitvestatusz = $val;
    }

    public function getTeljesithetostatusz(): ?Bizonylatstatusz
    {
        return $this->teljesithetostatusz;
    }

    public function getTeljesithetostatuszId()
    {
        return $this->teljesithetostatusz?->getId();
    }

    public function setTeljesithetostatusz(?Bizonylatstatusz $val): void
    {
        $this->teljesithetostatusz = $val;
    }

    public function getBackorderstatusz(): ?Bizonylatstatusz
    {
        return $this->backorderstatusz;
    }

    public function getBackorderstatuszId()
    {
        return $this->backorderstatusz?->getId();
    }

    public function setBackorderstatusz(?Bizonylatstatusz $val): void
    {
        $this->backorderstatusz = $val;
    }

    public function getRogzitveStatuszOrDefault(): ?Bizonylatstatusz
    {
        return $this->rogzitvestatusz ?? self::findDefaultStatusz(\mkw\consts::BizonylatStatuszFuggoben);
    }

    public function getTeljesithetoStatuszOrDefault(): ?Bizonylatstatusz
    {
        return $this->teljesithetostatusz ?? self::findDefaultStatusz(\mkw\consts::BizonylatStatuszTeljesitheto);
    }

    public function getBackorderStatuszOrDefault(): ?Bizonylatstatusz
    {
        return $this->backorderstatusz ?? self::findDefaultStatusz(\mkw\consts::BizonylatStatuszBackorder);
    }

    /** A Beállításokban megadott közös státusz; típus nélküli hívóknak is. */
    public static function findDefaultStatusz(string $parameter): ?Bizonylatstatusz
    {
        $id = \mkw\store::getParameter($parameter);
        return $id ? \mkw\store::getEm()->getRepository(Bizonylatstatusz::class)->find($id) : null;
    }

    /** A típus "rögzítve" státusza, típus nélkül a közös. */
    public static function rogzitveStatuszFor(?Bizonylattipus $tipus): ?Bizonylatstatusz
    {
        return $tipus ? $tipus->getRogzitveStatuszOrDefault() : self::findDefaultStatusz(\mkw\consts::BizonylatStatuszFuggoben);
    }

}
