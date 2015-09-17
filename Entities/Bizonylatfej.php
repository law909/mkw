<?php
namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use mkw\store;

/** @ORM\Entity(repositoryClass="Entities\BizonylatfejRepository")
 *  @ORM\Table(name="bizonylatfej")
 *  @ORM\HasLifecycleCallbacks
 * */
class Bizonylatfej {

    /**
     * @ORM\Id @ORM\Column(type="string",length=30,nullable=false)
     */
    private $id;

    /**
	 * @ORM\Column(type="integer")
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

    /** @ORM\Column(type="boolean",nullable=false) */
    private $fix = false;

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
     * @ORM\ManyToOne(targetEntity="Bizonylattipus", inversedBy="bizonylatfejek")
     * @ORM\JoinColumn(name="bizonylattipus_id", referencedColumnName="id",nullable=true,onDelete="restrict")
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
    private $erbizonylatszam;

    /** @ORM\Column(type="string",length=100,nullable=true) */
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

    /**
     * @ORM\ManyToOne(targetEntity="Fizmod",inversedBy="bizonylatfejek")
     * @ORM\JoinColumn(name="fizmod_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $fizmod;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $fizmodnev;

    /**
     * @ORM\ManyToOne(targetEntity="Szallitasimod",inversedBy="bizonylatfejek")
     * @ORM\JoinColumn(name="szallitasimod_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $szallitasimod;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $szallitasimodnev;

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

    /** @ORM\Column(type="string",length=10,nullable=true) */
    private $partnerirszam;

    /** @ORM\Column(type="string",length=40,nullable=true) */
    private $partnervaros;

    /** @ORM\Column(type="string",length=60,nullable=true) */
    private $partnerutca;

    /** @ORM\Column(type="string",length=10,nullable=true) */
    private $partnerlirszam;

    /** @ORM\Column(type="string",length=40,nullable=true) */
    private $partnerlvaros;

    /** @ORM\Column(type="string",length=60,nullable=true) */
    private $partnerlutca;

    /** @ORM\Column(type="string",length=100,nullable=true) */
    private $partneremail = '';

    /** @ORM\Column(type="string",length=40,nullable=true) */
    private $partnertelefon = '';

    /**
     * @ORM\ManyToOne(targetEntity="Bankszamla",inversedBy="bizonylatfejek")
     * @ORM\JoinColumn(name="bankszamla_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $bankszamla;

	/** @ORM\Column(type="string",length=50) */
	private $tulajbanknev;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $tulajbankszamlaszam;

    /** @ORM\Column(type="string",length=20,nullable=true) */
    private $tulajswift;

    /** @ORM\Column(type="string",length=20) */
	private $tulajiban;

    /**
     * @ORM\ManyToOne(targetEntity="Uzletkoto",inversedBy="bizonylatfejek")
     * @ORM\JoinColumn(name="uzletkoto_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $uzletkoto;

    /** @ORM\Column(type="string",length=50,nullable=true) */
    private $uzletkotonev;

	/** @ORM\Column(type="string",length=100,nullable=true) */
	private $uzletkotoemail;

    /**
     * @ORM\ManyToOne(targetEntity="Raktar",inversedBy="bizonylatfejek")
     * @ORM\JoinColumn(name="raktar_id", referencedColumnName="id",nullable=true,onDelete="restrict")
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

    /** @ORM\Column(type="string",length=32,nullable=true) */
    private $ip;

    /** @ORM\Column(type="text",nullable=true) */
    private $referrer;

    /**
     * @ORM\ManyToOne(targetEntity="Bizonylatstatusz",inversedBy="bizonylatfejek")
     * @ORM\JoinColumn(name="bizonylatstatusz_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $bizonylatstatusz;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $bizonylatstatusznev;

    /** @ORM\Column(type="string",length=255, nullable=true) */
    private $bizonylatstatuszcsoport;


    /**
     * @ORM\ManyToOne(targetEntity="Bizonylatfej",inversedBy="szulobizonylatfejek")
     * @ORM\JoinColumn(name="parbizonylatfej_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $parbizonylatfej;

    /** @ORM\OneToMany(targetEntity="Bizonylatfej", mappedBy="parbizonylatfej",cascade={"persist"}) */
    private $szulobizonylatfejek;

    /** @ORM\Column(type="integer") */
    private $partnerszamlatipus;

    /**
     * @ORM\ManyToOne(targetEntity="FoxpostTerminal",inversedBy="bizonylatfejek")
     * @ORM\JoinColumn(name="foxpostterminal_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $foxpostterminal;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $traceurl;

    /** @ORM\Column(type="string",length=10,nullable=true) */
    private $bizonylatnyelv;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $reportfile;

    /**
     * @ORM\PrePersist
     */
    public function generateTrxId() {
        $conn = \mkw\Store::getEm()->getConnection();
        $stmt = $conn->prepare('INSERT INTO bizonylatseq (data) VALUES (1)');
        $stmt->execute();
        $this->trxid = $conn->lastInsertId();
        $this->setMasterPassCorrelationID(\mkw\Store::createGUID());
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function doStuffOnPrePersist() {
        $mincimlet = 0;
        $kerekit = false;
        $defamincimlet = 0;
        $defakerekit = false;
        if ($this->getValutanem()) {
            $mincimlet = $this->getValutanem()->getMincimlet();
            $kerekit = $this->getValutanem()->getKerekit();
        }
        $defavaluta = \mkw\Store::getEm()->getRepository('Entities\Valutanem')->find(\mkw\Store::getParameter(\mkw\consts::Valutanem));
        if ($defavaluta) {
            $defamincimlet = $defavaluta->getMincimlet();
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
            $bt->setMozgat();
            $bt->setFoglal();
            $this->netto += $bt->getNetto();
            $this->afa += $bt->getAfaertek();
            //$this->brutto += $bt->getBrutto();
            $this->nettohuf += $bt->getNettohuf();
            $this->afahuf += $bt->getAfaertekhuf();
            //$this->bruttohuf += $bt->getBruttohuf();
        }
        if ($kerekit) {
            $this->brutto = \mkw\Store::kerekit($this->netto + $this->afa, 2);
        }
        else {
            $this->brutto = $this->netto + $this->afa;
        }
        if ($mincimlet && ($fizmodtipus == 'P')) {
            $valosbrutto = $this->brutto;
            $this->brutto = \mkw\Store::kerekit($this->brutto, $mincimlet);
            $this->kerkul = $this->brutto - $valosbrutto;
        }
        $this->fizetendo = $this->brutto;
        if ($defakerekit) {
            $this->bruttohuf = \mkw\Store::kerekit($this->nettohuf + $this->afahuf, 2);
        }
        else {
            $this->bruttohuf = $this->nettohuf + $this->afahuf;
        }
        // superzone osztott fizetendo
        if (\mkw\Store::isOsztottFizmod()) {
            $eddigi = 0;
            $kelt = new \DateTimeImmutable(\mkw\Store::convDate($this->getKeltStr()));
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
                }
                else {
                    $this->setFizetendo2($this->fizetendo - $eddigi);
                    $eddigi = $this->fizetendo;
                }
            }
            if ($fizmod->getOsztotthaladek3()) {
                $this->setEsedekesseg3($kelt->add(new \DateInterval('P' . $fizmod->getOsztotthaladek3() . 'D')));
                $this->setFizetendo3($this->fizetendo - $eddigi);
            }
        }
    }

    public function __construct() {
        $this->szulobizonylatfejek = new \Doctrine\Common\Collections\ArrayCollection();
        $this->bizonylattetelek = new \Doctrine\Common\Collections\ArrayCollection();
        $this->setPersistentData();
    }

    public function sendStatuszEmail($emailtpl, $bf = null, $topartner = true) {
        if (!$bf) {
            $bf = $this;
        }
        if ($emailtpl) {
            $tpldata = $bf->toLista();
            $subject = \mkw\Store::getTemplateFactory()->createMainView('string:' . $emailtpl->getTargy());
            $subject->setVar('rendeles', $tpldata);
            $body = \mkw\Store::getTemplateFactory()->createMainView('string:' . $emailtpl->getHTMLSzoveg());
            $body->setVar('rendeles', $tpldata);
            $mailer = \mkw\Store::getMailer();
            if ($topartner) {
                $mailer->addTo($bf->getPartneremail());
                $mailer->addTo($bf->getUzletkotoemail());
            }
            $mailer->setSubject($subject->getTemplateResult());
            $mailer->setMessage($body->getTemplateResult());
            $mailer->send();
        }
    }

    public function toLista() {
        $ret = array();
        $ret['id'] = $this->getId();
        $ret['bizonylatnev'] = $this->getBizonylatnev();
        $ret['raktarnev'] = $this->getRaktarnev();
        $ret['kelt'] = $this->getKeltStr();
        $ret['keltstr'] = $this->getKeltStr();
        $ret['teljesitesstr'] = $this->getTeljesitesStr();
        $ret['esedekessegstr'] = $this->getEsedekessegStr();
        $ret['esedekesseg1str'] = $this->getEsedekesseg1Str();
        $ret['fizetendo1'] = $this->getFizetendo1();
        $ret['esedekesseg2str'] = $this->getEsedekesseg2Str();
        $ret['fizetendo2'] = $this->getFizetendo2();
        $ret['esedekesseg3str'] = $this->getEsedekesseg3Str();
        $ret['fizetendo3'] = $this->getFizetendo3();
        $ret['tulajnev'] = $this->getTulajnev();
        $ret['tulajirszam'] = $this->getTulajirszam();
        $ret['tulajvaros'] = $this->getTulajvaros();
        $ret['tulajutca'] = $this->getTulajutca();
        $ret['tulajadoszam'] = $this->getTulajadoszam();
        $ret['tulajeuadoszam'] = $this->getTulajeuadoszam();
        $ret['tulajeorinr'] = $this->getTulajeorinr();
        $ret['ertek'] = $this->getBrutto();
        $ret['nettohuf'] = $this->getNettohuf();
        $ret['afahuf'] = $this->getAfahuf();
        $ret['bruttohuf'] = $this->getBruttohuf();
        $ret['netto'] = $this->getNetto();
        $ret['afa'] = $this->getAfa();
        $ret['brutto'] = $this->getBrutto();
        $ret['fizetendo'] = $this->getFizetendo();
        $ret['fizetendokiirva'] = \mkw\Store::Num2Text($this->getFizetendo());
        $ret['fizmodnev'] = $this->getFizmodnev();
        $ret['szallitasimodnev'] = $this->getSzallitasimodnev();
        $ret['tulajbanknev'] = $this->getTulajbanknev();
        $ret['tulajbankszamlaszam'] = $this->getTulajbankszamlaszam();
        $ret['tulajiban'] = $this->getTulajiban();
        $ret['tulajswift'] = $this->getTulajswift();
        $ret['partneremail'] = $this->getPartneremail();
        $ret['partnertelefon'] = $this->getPartnertelefon();
        $ret['partnerkeresztnev'] = $this->getPartnerkeresztnev();
        $ret['partnervezeteknev'] = $this->getPartnervezeteknev();
        $ret['szamlanev'] = $this->getPartnernev();
        $ret['szamlairszam'] = $this->getPartnerirszam();
        $ret['szamlavaros'] = $this->getPartnervaros();
        $ret['szamlautca'] = $this->getPartnerutca();
        $ret['telefon'] = $this->getPartnertelefon();
        $ret['szallnev'] = $this->getSzallnev();
        $ret['szallirszam'] = $this->getSzallirszam();
        $ret['szallvaros'] = $this->getSzallvaros();
        $ret['szallutca'] = $this->getSzallutca();
        $ret['adoszam'] = $this->getPartneradoszam();
        $ret['webshopmessage'] = $this->getWebshopmessage();
        $ret['couriermessage'] = $this->getCouriermessage();
        $ret['megjegyzes'] = $this->getMegjegyzes();
        $ret['allapotnev'] = $this->getBizonylatstatusznev();
        $ret['fuvarlevelszam'] = $this->getFuvarlevelszam();
        $ret['erbizonylatszam'] = $this->getErbizonylatszam();
        $ret['valutanemnev'] = $this->getValutanemnev();
        $ret['arfolyam'] = $this->getArfolyam();
        $ret['foxpost'] = false;
        if ($this->foxpostterminal) {
            $ret['foxpost'] = true;
            $ret['foxpostterminal']['nev'] = $this->foxpostterminal->getNev();
            $ret['foxpostterminal']['cim'] = $this->foxpostterminal->getCim();
            $ret['foxpostterminal']['findme'] = $this->foxpostterminal->getFindme();
            $ret['foxpostterminal']['nyitva'] = $this->foxpostterminal->getNyitva();
        }
        $tetellist = array();
        foreach ($this->bizonylattetelek as $tetel) {
            $tetellist[] = $tetel->toLista();
        }
        $ret['tetellista'] = $tetellist;
        return $ret;
    }

    public function setPersistentData() {
        $this->setTulajData();
    }

    protected function setTulajData() {
        $this->setTulajnev(store::getParameter(\mkw\consts::Tulajnev));
        $this->setTulajirszam(store::getParameter(\mkw\consts::Tulajirszam));
        $this->setTulajvaros(store::getParameter(\mkw\consts::Tulajvaros));
        $this->setTulajutca(store::getParameter(\mkw\consts::Tulajutca));
        $this->setTulajadoszam(store::getParameter(\mkw\consts::Tulajadoszam));
        $this->setTulajeuadoszam(store::getParameter(\mkw\consts::Tulajeuadoszam));
        $this->setTulajeorinr(store::getParameter(\mkw\consts::Tulajeorinr));
    }

    public function calcEsedekesseg() {
        $this->esedekesseg = \mkw\Store::calcEsedekesseg($this->getKelt(), $this->getFizmod(), $this->getPartner());
    }

    public function getId() {
        return $this->id;
    }

    public function clearId() {
        $this->id = null;
    }

    public function getTrxId() {
        return $this->trxid;
    }

    public function getOTPayId() {
        return $this->otpayid;
    }

    public function setOTPayId($val) {
        $this->otpayid = $val;
    }

    public function generateId($from = null) {
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
                $q = store::getEm()->createQuery('SELECT COUNT(bf) FROM Entities\Bizonylatfej bf WHERE bf.bizonylattipus=:p');
                $q->setParameters(array('p' => $bt));
                if ($q->getSingleScalarResult() > 0) {
                    $kezdo = 1;
                }
                if (!$kezdo) {
                    $kezdo = 1;
                }
                $szam = $kezdo;
                $q = store::getEm()->createQuery('SELECT MAX(bf.id) FROM Entities\Bizonylatfej bf WHERE (bf.bizonylattipus=:p1) AND (YEAR(bf.kelt)=:p2)');
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
            }
            else {
                $szam = $from;
                $q = store::getEm()->createQuery('SELECT MAX(bf.id) FROM Entities\Bizonylatfej bf WHERE (bf.bizonylattipus=:p1) AND (YEAR(bf.kelt)=:p2)');
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
            $this->id = \mkw\Store::createBizonylatszam($azon, $ev, $szam);
        }
        return $szam;
    }

    public function getBizonylattetelek() {
        return $this->bizonylattetelek;
    }

    public function addBizonylattetel(Bizonylattetel $val) {
        if (!$this->bizonylattetelek->contains($val)) {
            $val->setIrany($this->getIrany());
            $this->bizonylattetelek->add($val);
            $val->setBizonylatfej($this);
        }
    }

    public function removeBizonylattetel(Bizonylattetel $val) {
        if ($this->bizonylattetelek->removeElement($val)) {
            $val->removeBizonylatfej();
            return true;
        }
        return false;
    }

    public function clearBizonylattetelek() {
        $this->bizonylattetelek->clear();
    }

    public function getIrany() {
        return $this->irany;
    }

    public function setIrany($val) {
        $this->irany = $val;
    }

    public function getBizonylattipus() {
        return $this->bizonylattipus;
    }

    public function getBizonylattipusId() {
        if ($this->bizonylattipus) {
            return $this->bizonylattipus->getId();
        }
        return '';
    }

    public function setBizonylattipus($val) {
        if ($this->bizonylattipus !== $val) {
            $this->bizonylattipus = $val;
            $this->setIrany($val->getIrany());
            $this->setBizonylatnev($val->getNev());
            $this->setPenztmozgat($val->getPenztmozgat());
            $this->setReportfile($val->getTplname());
//			$val->addBizonylat($this);
        }
    }

    public function removeBizonylattipus() {
        if ($this->bizonylattipus !== null) {
//			$val=$this->bizonylattipus;
            $this->bizonylattipus = null;
            $this->bizonylatnev = '';
            $this->setReportfile('');
//			$val->removeBizonylat($this);
        }
    }

    public function getBizonylatnev() {
        return $this->bizonylatnev;
    }

    public function setBizonylatnev($val) {
        $this->bizonylatnev = $val;
    }

    public function getNyomtatva() {
        return $this->nyomtatva;
    }

    public function setNyomtatva($val) {
        $this->nyomtatva = $val;
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
        foreach ($this->bizonylattetelek as $bt) {
            $bt->setStornozott($val);
            \mkw\Store::getEm()->persist($bt);
        }
    }

    public function getMozgat() {
        $bt = $this->getBizonylattipus();
        $raktar = $this->getRaktar();
        if ($bt && $raktar) {
            return $bt->getMozgat() && $raktar->getMozgat();
        }
        return false;
    }

    public function getFoglal() {
        $bt = $this->getBizonylattipus();
        if ($bt) {
            return $bt->getFoglal();
        }
        return false;
    }

    public function getPenztmozgat() {
        return $this->penztmozgat;
    }

    public function setPenztmozgat($val) {
        $this->penztmozgat = $val;
    }

    public function getTulajnev() {
        return $this->tulajnev;
    }

    public function setTulajnev($val) {
        $this->tulajnev = $val;
    }

    public function getTulajirszam() {
        return $this->tulajirszam;
    }

    public function setTulajirszam($val) {
        $this->tulajirszam = $val;
    }

    public function getTulajvaros() {
        return $this->tulajvaros;
    }

    public function setTulajvaros($val) {
        $this->tulajvaros = $val;
    }

    public function getTulajutca() {
        return $this->tulajutca;
    }

    public function setTulajutca($val) {
        $this->tulajutca = $val;
    }

    public function getTulajadoszam() {
        return $this->tulajadoszam;
    }

    public function setTulajadoszam($val) {
        $this->tulajadoszam = $val;
    }

    public function getTulajeuadoszam() {
        return $this->tulajeuadoszam;
    }

    public function setTulajeuadoszam($val) {
        $this->tulajeuadoszam = $val;
    }

    public function getTulajeorinr() {
        return $this->tulajeorinr;
    }

    public function setTulajeorinr($val) {
        $this->tulajeorinr = $val;
    }

    public function getKelt() {
        if (!$this->id && !$this->kelt) {
            $this->kelt = new \DateTime(\mkw\Store::convDate(date(\mkw\Store::$DateFormat)));
        }
        return $this->kelt;
    }

    public function getKeltStr() {
        if ($this->getKelt()) {
            return $this->getKelt()->format(store::$DateFormat);
        }
        return '';
    }

    public function setKelt($adat = '') {
        if (is_a($adat, 'DateTime')) {
            $this->kelt = $adat;
        }
        else {
            if ($adat == '')
                $adat = date(store::$DateFormat);
            $this->kelt = new \DateTime(store::convDate($adat));
        }
    }

    public function getTeljesites() {
        if (!$this->id && !$this->teljesites) {
            $this->teljesites = new \DateTime(\mkw\Store::convDate(date(\mkw\Store::$DateFormat)));
        }
        return $this->teljesites;
    }

    public function getTeljesitesStr() {
        if ($this->getTeljesites()) {
            return $this->getTeljesites()->format(store::$DateFormat);
        }
        return '';
    }

    public function setTeljesites($adat = '') {
        if (is_a($adat, 'DateTime')) {
            $this->teljesites = $adat;
        }
        else {
            if ($adat == '')
                $adat = date(store::$DateFormat);
            $this->teljesites = new \DateTime(store::convDate($adat));
        }
    }

    public function getEsedekesseg() {
        if (!$this->id && !$this->esedekesseg) {
            $this->esedekesseg = new \DateTime(\mkw\Store::convDate(date(\mkw\Store::$DateFormat)));
        }
        return $this->esedekesseg;
    }

    public function getEsedekessegStr() {
        if ($this->getEsedekesseg()) {
            return $this->getEsedekesseg()->format(store::$DateFormat);
        }
        return '';
    }

    public function setEsedekesseg($adat = '') {
        if (is_a($adat, 'DateTime')) {
            $this->esedekesseg = $adat;
        }
        else {
            if ($adat == '')
                $adat = date(store::$DateFormat);
            $this->esedekesseg = new \DateTime(store::convDate($adat));
        }
    }

    public function getHatarido() {
        if (!$this->id && !$this->hatarido) {
            $this->hatarido = new \DateTime(\mkw\Store::convDate(date(\mkw\Store::$DateFormat)));
        }
        return $this->hatarido;
    }

    public function getHataridoStr() {
        if ($this->getHatarido()) {
            return $this->getHatarido()->format(store::$DateFormat);
        }
        return '';
    }

    public function setHatarido($adat = '') {
        if (is_a($adat, 'DateTime')) {
            $this->hatarido = $adat;
        }
        else {
            if ($adat == '')
                $adat = date(store::$DateFormat);
            $this->hatarido = new \DateTime(store::convDate($adat));
        }
    }

    public function getFizmod() {
        if (!$this->id && !$this->fizmod) {
            $this->setFizmod(\mkw\Store::getParameter(\mkw\consts::Fizmod));
        }
        return $this->fizmod;
    }

    public function getFizmodnev() {
        return $this->fizmodnev;
    }

    public function getFizmodId() {
        $fm = $this->getFizmod();
        if ($fm) {
            return $fm->getId();
        }
        return '';
    }

    public function setFizmod($val) {
        if (!($val instanceof \Entities\Fizmod)) {
            $val = \mkw\Store::getEm()->getRepository('Entities\Fizmod')->find($val);
        }
        if ($this->fizmod !== $val) {
            $this->fizmod = $val;
            $this->fizmodnev = $val->getNev();
//			$val->addBizonylat($this);
        }
    }

    public function removeFizmod() {
        if ($this->fizmod !== null) {
//			$val=$this->fizmod;
            $this->fizmod = null;
            $this->fizmodnev = '';
//			$val->removeBizonylat($this);
        }
    }

    public function getSzallitasimod() {
        return $this->szallitasimod;
    }

    public function getSzallitasimodnev() {
        return $this->szallitasimodnev;
    }

    public function getSzallitasimodId() {
        if ($this->szallitasimod) {
            return $this->szallitasimod->getId();
        }
        return '';
    }

    public function setSzallitasimod($val) {
        if ($this->szallitasimod !== $val) {
            $this->szallitasimod = $val;
            $this->szallitasimodnev = $val->getNev();
//			$val->addBizonylat($this);
        }
    }

    public function removeSzallitasimod() {
        if ($this->szallitasimod !== null) {
//			$val=$this->szallitasimod;
            $this->szallitasimod = null;
            $this->szallitasimodnev = '';
//			$val->removeBizonylat($this);
        }
    }

    public function getNetto() {
        return $this->netto;
    }

    public function setNetto($val) {
        $this->netto = $val;
    }

    public function getAfa() {
        return $this->afa;
    }

    public function setAfa($val) {
        $this->afa = $val;
    }

    public function getBrutto() {
        return $this->brutto;
    }

    public function setBrutto($val) {
        $this->brutto = $val;
    }

    public function getFizetendo() {
        return $this->fizetendo;
    }

    public function setFizetendo($val) {
        $this->fizetendo = $val;
    }

    public function getValutanem() {
        if (!$this->id && !$this->valutanem) {
            $this->setValutanem(\mkw\Store::getParameter(\mkw\consts::Valutanem));
        }
        return $this->valutanem;
    }

    public function getValutanemnev() {
        return $this->valutanemnev;
    }

    public function getValutanemId() {
        $vn = $this->getValutanem();
        if ($vn) {
            return $vn->getId();
        }
        return '';
    }

    public function setValutanem($val) {
        if (!($val instanceof \Entities\Valutanem)) {
            $val = \mkw\Store::getEm()->getRepository('Entities\Valutanem')->find($val);
        }
        if ($this->valutanem !== $val) {
            $this->valutanem = $val;
            $this->valutanemnev = $val->getNev();
//			$val->addBizonylatfej($this);
        }
    }

    public function removeValutanem() {
        if ($this->valutanem !== null) {
//			$val=$this->valutanem;
            $this->valutanem = null;
            $this->valutanemnev = '';
            $this->setArfolyam(1);
//			$val->removeBizonylatfej($this);
        }
    }

    public function getNettohuf() {
        return $this->nettohuf;
    }

    public function setNettohuf($val) {
        $this->nettohuf = $val;
    }

    public function getAfahuf() {
        return $this->afahuf;
    }

    public function setAfahuf($val) {
        $this->afahuf = $val;
    }

    public function getBruttohuf() {
        return $this->bruttohuf;
    }

    public function setBruttohuf($val) {
        $this->bruttohuf = $val;
    }

    public function getArfolyam() {
        if (!$this->id && !$this->arfolyam) {
            if ($this->getValutanemId() == \mkw\Store::getParameter(\mkw\consts::Valutanem)) {
                $this->setArfolyam(1);
            }
            else {

            }
        }
        return $this->arfolyam;
    }

    public function setArfolyam($val) {
        $this->arfolyam = $val;
    }

    public function getPartner() {
        return $this->partner;
    }

    public function getPartnerId() {
        if ($this->partner) {
            return $this->partner->getId();
        }
        return '';
    }

    public function setPartner($val) {
        if ($this->partner !== $val) {
            $this->partner = $val;
            $this->partnernev = $val->getNev();
            $this->partnervezeteknev = $val->getVezeteknev();
            $this->partnerkeresztnev = $val->getKeresztnev();
            $this->partneradoszam = $val->getAdoszam();
            $this->partnercjszam = $val->getCjszam();
            $this->partnereuadoszam = $val->getEuadoszam();
            $this->partnerfvmszam = $val->getFvmszam();
            $this->partnerirszam = $val->getIrszam();
            $this->partnerjovengszam = $val->getJovengszam();
            $this->partnerlirszam = $val->getLirszam();
            $this->partnerlutca = $val->getLutca();
            $this->partnerlvaros = $val->getLvaros();
            $this->partnertelefon = $val->getTelefon();
            $this->partneremail = $val->getEmail();
            $this->partnermukengszam = $val->getMukengszam();
            $this->partnerostermszam = $val->getOstermszam();
            $this->partnerstatszamjel = $val->getStatszamjel();
            $this->partnerutca = $val->getUtca();
            $this->partnervalligszam = $val->getValligszam();
            $this->partnervaros = $val->getVaros();

            $this->szallnev = $val->getSzallnev();
            $this->szallirszam = $val->getSzallirszam();
            $this->szallvaros = $val->getSzallvaros();
            $this->szallutca = $val->getSzallutca();

            $this->partnerszamlatipus = $val->getSzamlatipus();
            $this->bizonylatnyelv = $val->getBizonylatnyelv();

            $uk = $val->getUzletkoto();
            if ($uk) {
                $this->setUzletkoto($uk);
            }
            else {
                $this->removeUzletkoto();
            }
            $fm = $val->getFizmod();
            if ($fm) {
                $this->setFizmod($fm);
            }
            else {
                $this->removeFizmod();
            }
            $v = $val->getValutanem();
            if ($v) {
                $this->setValutanem($v);
            }
            else {
                $this->removeValutanem();
            }
//			$val->addBizonylatfej($this);
        }
    }

    public function removePartner() {
        if ($this->partner !== null) {
//			$val=$this->partner;
            $this->partner = null;
            $this->partnernev = '';
            $this->partnervezeteknev = '';
            $this->partnerkeresztnev = '';
            $this->partneradoszam = '';
            $this->partnercjszam = '';
            $this->partnereuadoszam = '';
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
            $this->partnerszamlatipus = 0;
            $this->szallnev = '';
            $this->szallirszam = '';
            $this->szallvaros = '';
            $this->szallutca = '';
            $this->bizonylatnyelv = '';
            $this->removeUzletkoto();
            $this->removeFizmod();
            $this->removeValutanem();
//			$val->removeBizonylatfej($this);
        }
    }

    public function getPartnernev() {
        return $this->partnernev;
    }

    public function setPartnernev($val) {
        $this->partnernev = $val;
    }

    public function getPartnervezeteknev() {
        return $this->partnervezeteknev;
    }

    public function setPartnervezeteknev($val) {
        $this->partnervezeteknev = $val;
    }

    public function getPartnerkeresztnev() {
        return $this->partnerkeresztnev;
    }

    public function setPartnerkeresztnev($val) {
        $this->partnerkeresztnev = $val;
    }

    public function getPartneradoszam() {
        return $this->partneradoszam;
    }

    public function setPartneradoszam($val) {
        $this->partneradoszam = $val;
    }

    public function getPartnercjszam() {
        return $this->partnercjszam;
    }

    public function getPartnereuadoszam() {
        return $this->partnereuadoszam;
    }

    public function setPartnereuadoszam($val) {
        $this->partnereuadoszam = $val;
    }

    public function getPartnerfvmszam() {
        return $this->partnerfvmszam;
    }

    public function getPartnerirszam() {
        return $this->partnerirszam;
    }

    public function setPartnerirszam($val) {
        $this->partnerirszam = $val;
    }

    public function getPartnerjovengszam() {
        return $this->partnerjovengszam;
    }

    public function getPartnerlirszam() {
        return $this->partnerlirszam;
    }

    public function getPartnerlutca() {
        return $this->partnerlutca;
    }

    public function getPartnerlvaros() {
        return $this->partnerlvaros;
    }

    public function getPartnermukengszam() {
        return $this->partnermukengszam;
    }

    public function getPartnerostermszam() {
        return $this->partnerostermszam;
    }

    public function getPartnerstatszamjel() {
        return $this->partnerstatszamjel;
    }

    public function getPartnerutca() {
        return $this->partnerutca;
    }

    public function setPartnerutca($val) {
        $this->partnerutca = $val;
    }

    public function getPartnervalligszam() {
        return $this->partnervalligszam;
    }

    public function getPartnervaros() {
        return $this->partnervaros;
    }

    public function setPartnervaros($val) {
        $this->partnervaros = $val;
    }

    public function getBankszamla() {
        return $this->bankszamla;
    }

    public function getTulajbankszamlaszam() {
        return $this->tulajbankszamlaszam;
    }

    public function getBankszamlaId() {
        if ($this->bankszamla) {
            return $this->bankszamla->getId();
        }
        return '';
    }

    public function setBankszamla($val = null) {
        if ($this->bankszamla !== $val && $val) {
            $this->bankszamla = $val;
            $this->tulajbanknev = $val->getBanknev();
            $this->tulajbankszamlaszam = $val->getSzamlaszam();
            $this->tulajswift = $val->getSwift();
            $this->tulajiban = $val->getIban();
//			$val->addBizonylatfejek($this);
        }
    }

    public function removeBankszamla() {
        if ($this->bankszamla !== null) {
//			$val=$this->bankszamla;
            $this->bankszamla = null;
            $this->tulajbanknev = '';
            $this->tulajbankszamlaszam = '';
            $this->tulajswift = '';
            $this->tulajiban = '';
//			$val->removeBizonylatfejek($this);
        }
    }

    public function getTulajswift() {
        return $this->tulajswift;
    }

    public function getTulajbanknev() {
        return $this->tulajbanknev;
    }

    public function getUzletkoto() {
        return $this->uzletkoto;
    }

    public function getUzletkotonev() {
        return $this->uzletkotonev;
    }

    public function getUzletkotoemail() {
        return $this->uzletkotoemail;
    }

    public function getUzletkotoId() {
        if ($this->uzletkoto) {
            return $this->uzletkoto->getId();
        }
        return '';
    }

    public function setUzletkoto($val) {
        if ($this->uzletkoto !== $val) {
            $this->uzletkoto = $val;
            $this->uzletkotonev = $val->getNev();
            $this->uzletkotoemail = $val->getEmail();
//			$val->addBizonylatfejek($this);
        }
    }

    public function removeUzletkoto() {
        if ($this->uzletkoto !== null) {
//			$val=$this->uzletkoto;
            $this->uzletkoto = null;
            $this->uzletkotonev = '';
            $this->uzletkotoemail = '';
//			$val->removeBizonylatfejek($this);
        }
    }

    public function getRaktar() {
        return $this->raktar;
    }

    public function getRaktarnev() {
        return $this->raktarnev;
    }

    public function getRaktarId() {
        if ($this->raktar) {
            return $this->raktar->getId();
        }
        return '';
    }

    public function setRaktar($val) {
        if ($this->raktar !== $val) {
            $this->raktar = $val;
            $this->raktarnev = $val->getNev();
//			$val->addBizonylatfejek($this);
        }
    }

    public function removeRaktar() {
        if ($this->raktar !== null) {
//			$val=$this->raktar;
            $this->raktar = null;
            $this->raktarnev = '';
//			$val->removeBizonylatfejek($this);
        }
    }

    public function getErbizonylatszam() {
        return $this->erbizonylatszam;
    }

    public function setErbizonylatszam($val) {
        $this->erbizonylatszam = $val;
    }

    public function getMegjegyzes() {
        return $this->megjegyzes;
    }

    public function setMegjegyzes($val) {
        $this->megjegyzes = $val;
    }

    public function getBelsomegjegyzes() {
        return $this->belsomegjegyzes;
    }

    public function setBelsomegjegyzes($val) {
        $this->belsomegjegyzes = $val;
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

    public function getSzallnev() {
        return $this->szallnev;
    }

    public function setSzallnev($adat) {
        $this->szallnev = $adat;
    }

    public function getSzallirszam() {
        return $this->szallirszam;
    }

    public function setSzallirszam($adat) {
        $this->szallirszam = $adat;
    }

    public function getSzallvaros() {
        return $this->szallvaros;
    }

    public function setSzallvaros($adat) {
        $this->szallvaros = $adat;
    }

    public function getSzallutca() {
        return $this->szallutca;
    }

    public function setSzallutca($adat) {
        $this->szallutca = $adat;
    }

    public function getWebshopmessage() {
        return $this->webshopmessage;
    }

    public function setWebshopmessage($val) {
        $this->webshopmessage = $val;
    }

    public function getCouriermessage() {
        return $this->couriermessage;
    }

    public function setCouriermessage($val) {
        $this->couriermessage = $val;
    }

    public function getPartnertelefon() {
        return $this->partnertelefon;
    }

    public function setPartnertelefon($telefon) {
        $this->partnertelefon = $telefon;
    }

    public function getPartneremail() {
        return $this->partneremail;
    }

    public function setPartneremail($email) {
        $this->partneremail = $email;
    }

    public function getIp() {
        return $this->ip;
    }

    public function setIp($val) {
        $this->ip = $val;
    }

    public function getReferrer() {
        return $this->referrer;
    }

    public function setReferrer($val) {
        $this->referrer = $val;
    }

    public function getBizonylatstatusz() {
        return $this->bizonylatstatusz;
    }

    public function getBizonylatstatusznev() {
        if (!$this->bizonylatstatusznev) {
            $bs = $this->getBizonylatstatusz();
            if ($bs) {
                return $bs->getNev();
            }
            return '';
        }
        return $this->bizonylatstatusznev;
    }

    public function setBizonylatstatusznev($val) {
        $this->bizonylatstatusznev = $val;
    }

    public function getBizonylatstatuszcsoport() {
        return $this->bizonylatstatuszcsoport;
    }

    public function setBizonylatstatuszcsoport($val) {
        $this->bizonylatstatuszcsoport = $val;
    }

    public function getBizonylatstatuszId() {
        $fm = $this->getBizonylatstatusz();
        if ($fm) {
            return $fm->getId();
        }
        return '';
    }

    public function setBizonylatstatusz($val) {
        if (!($val instanceof \Entities\Bizonylatstatusz)) {
            $val = \mkw\Store::getEm()->getRepository('Entities\Bizonylatstatusz')->find($val);
        }
        if ($this->bizonylatstatusz !== $val) {
            $this->bizonylatstatusz = $val;
            $this->bizonylatstatusznev = $val->getNev();
            $this->bizonylatstatuszcsoport = $val->getCsoport();
//			$val->addBizonylat($this);
        }
    }

    public function removeBizonylatstatusz() {
        if ($this->bizonylatstatusz !== null) {
//			$val=$this->bizonylatstatusz;
            $this->bizonylatstatusz = null;
            $this->bizonylatstatusznev = '';
            $this->bizonylatstatuszcsoport = '';
//			$val->removeBizonylat($this);
        }
    }

    public function getFuvarlevelszam() {
        return $this->fuvarlevelszam;
    }

    public function setFuvarlevelszam($adat) {
        $this->fuvarlevelszam = $adat;
    }

    public function getParbizonylatfej() {
        return $this->parbizonylatfej;
    }

    public function getParbizonylatfejId() {
        if ($this->parbizonylatfej) {
            return $this->parbizonylatfej->getId();
        }
        return '';
    }

    public function setParbizonylatfej($val) {
        if ($this->parbizonylatfej !== $val) {
            $this->parbizonylatfej = $val;
            $val->addSzulobizonylatfej($this);
        }
    }

    public function removeParbizonylatfej() {
        if ($this->parbizonylatfej !== null) {
            $val = $this->parbizonylatfej;
            $this->parbizonylatfej = null;
            $val->removeSzulobizonylatfej($this);
        }
    }

    public function getSzulobizonylatfejek() {
        return $this->szulobizonylatfejek;
    }

    public function addSzulobizonylatfej($val) {
        if (!$this->szulobizonylatfejek->contains($val)) {
            $this->szulobizonylatfejek->add($val);
            $val->setParbizonylatfej($this);
        }
    }

    public function removeSzulobizonylatfej($val) {
        if ($this->szulobizonylatfejek->removeElement($val)) {
            $val->removeParbizonylatfej();
            return true;
        }
        return false;
    }

    public function getPartnerCim() {
        $a = array($this->partnerirszam, $this->partnervaros);
        $cim = implode(' ', $a);
        $a = array($cim, $this->partnerutca);
        return  implode(', ', $a);
    }

    public function getOTPayMSISDN() {
        return $this->otpaymsisdn;
    }

    public function setOTPayMSISDN($val) {
        $this->otpaymsisdn = $val;
    }

    public function getOTPayMPID() {
        return $this->otpaympid;
    }

    public function setOTPayMPID($val) {
        $this->otpaympid = $val;
    }

    public function getFizetve() {
        return $this->fizetve;
    }

    public function setFizetve($val) {
        $this->fizetve = $val;
    }

    public function getMasterPassCorrelationID() {
        return $this->masterpasscorrelationid;
    }

    public function setMasterPassCorrelationID($val) {
        $this->masterpasscorrelationid = $val;
    }

    public function getMasterPassBankTrxId() {
        return $this->masterpassbanktrxid;
    }

    public function setMaterPassBankTrxId($val) {
        $this->masterpassbanktrxid = $val;
    }

    public function getMasterPassTrxId() {
        return $this->masterpasstrxid;
    }

    public function setMaterPassTrxId($val) {
        $this->masterpasstrxid = $val;
    }

    public function getOTPayResult() {
        return $this->otpayresult;
    }

    public function setOTPayResult($val) {
        $this->otpayresult = $val;
    }

    public function getOTPayResultText() {
        return $this->otpayresulttext;
    }

    public function setOTPayResultText($val) {
        $this->otpayresulttext = $val;
    }

    public function getPartnerSzamlatipus() {
        return $this->partnerszamlatipus;
    }

    public function setPartnerSzamlatipus($val) {
        $this->partnerszamlatipus = $val;
    }

    public function getFoxpostterminal() {
        return $this->foxpostterminal;
    }

    public function getFoxpostterminalId() {
        if ($this->foxpostterminal) {
            return $this->foxpostterminal->getId();
        }
        return false;
    }

    public function setFoxpostterminal($val) {
        if ($this->foxpostterminal !== $val) {
            $this->foxpostterminal = $val;
        }
    }

    public function removeFoxpostterminal() {
        if ($this->foxpostterminal !== null) {
            $this->foxpostterminal = null;
        }
    }

    public function getFoxpostBarcode() {
        return $this->foxpostbarcode;
    }

    public function setFoxpostBarcode($adat) {
        $this->foxpostbarcode = $adat;
    }

    public function getTraceurl() {
        return $this->traceurl;
    }

    public function setTraceurl($adat) {
        $this->traceurl = $adat;
    }

    public function getRontott() {
        return $this->rontott;
    }

    public function setRontott($adat) {
        $this->rontott = $adat;
        foreach ($this->bizonylattetelek as $bt) {
            $bt->setRontott($adat);
            \mkw\Store::getEm()->persist($bt);
        }
    }

    public function getSysmegjegyzes() {
        return $this->sysmegjegyzes;
    }

    public function setSysmegjegyzes($adat) {
        $this->sysmegjegyzes = $adat;
    }

    public function getFix() {
        return $this->fix;
    }

    public function setFix($adat) {
        $this->fix = $adat;
    }

    public function getBizonylatnyelv() {
        return $this->bizonylatnyelv;
    }

    public function setBizonylatnyelv($adat) {
        $this->bizonylatnyelv = $adat;
    }

    public function getReportfile() {
        return $this->reportfile;
    }

    public function setReportfile($adat) {
        $this->reportfile = $adat;
    }

    public function getKerkul() {
        return $this->kerkul;
    }

    public function setKerkul($adat) {
        $this->kerkul = $adat;
    }

    public function getTulajiban() {
        return $this->tulajiban;
    }

    public function getEsedekesseg1() {
        return $this->esedekesseg1;
    }

    public function getEsedekesseg1Str() {
        if ($this->getEsedekesseg1()) {
            return $this->getEsedekesseg1()->format(store::$DateFormat);
        }
        return '';
    }

    public function setEsedekesseg1($adat = '') {
        if (is_a($adat,'DateTime') || is_a($adat,'DateTimeImmutable')) {
            $this->esedekesseg1 = $adat;
        }
        else {
            if ($adat != '') {
                $this->esedekesseg1 = new \DateTime(store::convDate($adat));
            }
        }
    }

    public function getFizetendo1() {
        return $this->fizetendo1;
    }

    public function setFizetendo1($val) {
        $this->fizetendo1 = $val;
    }

    public function getEsedekesseg2() {
        return $this->esedekesseg2;
    }

    public function getEsedekesseg2Str() {
        if ($this->getEsedekesseg2()) {
            return $this->getEsedekesseg2()->format(store::$DateFormat);
        }
        return '';
    }

    public function setEsedekesseg2($adat = '') {
        if (is_a($adat,'DateTime') || is_a($adat,'DateTimeImmutable')) {
            $this->esedekesseg2 = $adat;
        }
        else {
            if ($adat != '') {
                $this->esedekesseg2 = new \DateTime(store::convDate($adat));
            }
        }
    }

    public function getFizetendo2() {
        return $this->fizetendo2;
    }

    public function setFizetendo2($val) {
        $this->fizetendo2 = $val;
    }

    public function getEsedekesseg3() {
        return $this->esedekesseg3;
    }

    public function getEsedekesseg3Str() {
        if ($this->getEsedekesseg3()) {
            return $this->getEsedekesseg3()->format(store::$DateFormat);
        }
        return '';
    }

    public function setEsedekesseg3($adat = '') {
        if (is_a($adat,'DateTime') || is_a($adat,'DateTimeImmutable')) {
            $this->esedekesseg3 = $adat;
        }
        else {
            if ($adat != '') {
                $this->esedekesseg3 = new \DateTime(store::convDate($adat));
            }
        }
    }

    public function getFizetendo3() {
        return $this->fizetendo3;
    }

    public function setFizetendo3($val) {
        $this->fizetendo3 = $val;
    }

    public function duplicate($entityB){
        $methods = get_class_methods($this);
        foreach($methods as $v) {
            if (strpos($v, 'set') > -1) {
                $get = str_replace('set', 'get', $v);
                if (in_array($get, $methods)) {
                    $this->$v($entityB->$get());
                }
            }
        }
        /**
        foreach($entityB->getBizonylattetelek() as $bt) {
            $this->addBizonylattetel($bt);
        }
         */
    }
}
