<?php
// TODO wordpress

namespace Entities;

use Automattic\WooCommerce\HttpClient\HttpClientException;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Query\ResultSetMapping;
use mkw\store;
use mkwhelpers\FilterDescriptor;

/**
 * @ORM\Entity(repositoryClass="Entities\TermekValtozatRepository")
 * @ORM\Table(name="termekvaltozat",
 * options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"},
 * indexes={
 *      @ORM\index(name="termekvaltozatvonalkod_idx",columns={"vonalkod"}),
 *      @ORM\index(name="termekvaltozatidegencikkszam_idx",columns={"idegencikkszam"})
 * })
 * @ORM\HasLifecycleCallbacks
 */
class TermekValtozat
{

    private $keszletinfo = false;
    private $foglalasinfo = false;
    public $dontUploadToWC = false;

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
     * @ORM\ManyToOne(targetEntity="Termek",inversedBy="valtozatok",cascade={"persist"})
     * @ORM\JoinColumn(name="termek_id",referencedColumnName="id",onDelete="cascade")
     */
    private $termek;

    /** @ORM\Column(type="boolean") */
    private $lathato = true;

    /** @ORM\Column(type="boolean") */
    private $lathato2 = true;

    /** @ORM\Column(type="boolean") */
    private $lathato3 = true;

    /** @ORM\Column(type="boolean") */
    private $lathato4 = true;
    /** @ORM\Column(type="boolean") */
    private $lathato5 = true;
    /** @ORM\Column(type="boolean") */
    private $lathato6 = true;
    /** @ORM\Column(type="boolean") */
    private $lathato7 = true;
    /** @ORM\Column(type="boolean") */
    private $lathato8 = true;
    /** @ORM\Column(type="boolean") */
    private $lathato9 = true;
    /** @ORM\Column(type="boolean") */
    private $lathato10 = true;
    /** @ORM\Column(type="boolean") */
    private $lathato11 = true;
    /** @ORM\Column(type="boolean") */
    private $lathato12 = true;
    /** @ORM\Column(type="boolean") */
    private $lathato13 = true;
    /** @ORM\Column(type="boolean") */
    private $lathato14 = true;
    /** @ORM\Column(type="boolean") */
    private $lathato15 = true;

    /** @ORM\Column(type="boolean") */
    private $elerheto = true;

    /** @ORM\Column(type="boolean") */
    private $elerheto2 = true;

    /** @ORM\Column(type="boolean") */
    private $elerheto3 = true;

    /** @ORM\Column(type="boolean") */
    private $elerheto4 = true;
    /** @ORM\Column(type="boolean") */
    private $elerheto5 = true;
    /** @ORM\Column(type="boolean") */
    private $elerheto6 = true;
    /** @ORM\Column(type="boolean") */
    private $elerheto7 = true;
    /** @ORM\Column(type="boolean") */
    private $elerheto8 = true;
    /** @ORM\Column(type="boolean") */
    private $elerheto9 = true;
    /** @ORM\Column(type="boolean") */
    private $elerheto10 = true;
    /** @ORM\Column(type="boolean") */
    private $elerheto11 = true;
    /** @ORM\Column(type="boolean") */
    private $elerheto12 = true;
    /** @ORM\Column(type="boolean") */
    private $elerheto13 = true;
    /** @ORM\Column(type="boolean") */
    private $elerheto14 = true;
    /** @ORM\Column(type="boolean") */
    private $elerheto15 = true;

    /** @ORM\Column(type="boolean") */
    private $termekfokep = false;

    /**
     * @ORM\ManyToOne(targetEntity="TermekValtozatAdatTipus",inversedBy="valtozatok1")
     * @ORM\JoinColumn(name="adattipus1_id",referencedColumnName="id",onDelete="restrict")
     */
    private $adattipus1;

    /**
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    private $ertek1;
    /**
     * @ORM\ManyToOne(targetEntity="TermekValtozatAdatTipus",inversedBy="valtozatok2")
     * @ORM\JoinColumn(name="adattipus2_id",referencedColumnName="id",onDelete="restrict")
     */
    private $adattipus2;
    /**
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    private $ertek2;
    /** @ORM\OneToMany(targetEntity="Kosar", mappedBy="termekvaltozat",cascade={"persist"}) */
    private $kosarak;
    /**
     * @ORM\Column(type="decimal",precision=14,scale=4,nullable=true)
     */
    private $netto = 0;
    /**
     * @ORM\Column(type="decimal",precision=14,scale=4,nullable=true)
     */
    private $brutto = 0;
    /**
     * @ORM\ManyToOne(targetEntity="TermekKep",inversedBy="valtozatok")
     * @ORM\JoinColumn(name="termekkep_id",referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $kep;

    /** @ORM\Column(type="string",length=50,nullable=true) */
    private $cikkszam = '';

    /** @ORM\Column(type="string",length=50,nullable=true) */
    private $idegencikkszam = '';

    /** @ORM\OneToMany(targetEntity="Bizonylattetel", mappedBy="termekvaltozat",cascade={"persist"}) */
    private $bizonylattetelek;

    /** @ORM\OneToMany(targetEntity="Leltartetel", mappedBy="termekvaltozat",cascade={"persist"}) */
    private $leltartetelek;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $vonalkod;

    /** @ORM\Column(type="date",nullable=true) */
    private $beerkezesdatum;

    /** @ORM\Column(type="decimal",precision=14,scale=2,nullable=true) */
    private $minboltikeszlet;

    /** @ORM\Column(type="integer", nullable=true) */
    private $wcid;
    /** @ORM\Column(type="datetime", nullable=true) */
    private $wcdate;

    /**
     * @ORM\PrePersist
     */
    public function generateVonalkod()
    {
        if (\mkw\store::getSetupValue('vonalkod') && !$this->vonalkod) {
            $conn = \mkw\store::getEm()->getConnection();
            $stmt = $conn->prepare('INSERT INTO vonalkodseq (data) VALUES (1)');
            $stmt->executeStatement();
            $this->setVonalkod(\mkw\store::generateEAN13((string)$conn->lastInsertId()));
        }
    }

    public function __construct()
    {
        $this->kosarak = new \Doctrine\Common\Collections\ArrayCollection();
        $this->bizonylattetelek = new \Doctrine\Common\Collections\ArrayCollection();
        $this->leltartetelek = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function toEmag()
    {
        $termek = $this->getTermek();
        $x = $termek->toEmag();
        $x['id'] = $this->getId();
        $x['name'] = $x['name'] . ' ' . $this->getNev();
        $x['part_number'] = 'MKWV' . $this->getId();
        $x['ean'] = $this->getVonalkod();

        if (!$this->getTermekfokep()) {
            if ($this->getKep()) {
                foreach ($x['images'] as $key => $image) {
                    if ($image['display_type'] == 1) {
                        $x['images'][$key]['url'] = \mkw\store::getFullUrl($this->getKepurl());
                    }
                }
            }
        }

        if ($this->getLathato()) {
            $x['status'] = 1;
        } else {
            $x['status'] = 0;
        }
        $x['stock'] = [
            [
                'warehouse_id' => 1,
                'value' => $this->getKeszlet()
            ]
        ];
        return $x;
    }

    protected function calcKeszletInfo($datum = null, $raktarid = null)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('mennyiseg', 'mennyiseg');
        $rsm->addScalarResult('mozgasdb', 'mozgasdb');

        if (!$datum) {
            $datum = new \DateTime();
        }

        $filter = new FilterDescriptor();
        $filter->addFilter('bt.termekvaltozat_id', '=', $this->getId());
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
        $db = $d[0]['mozgasdb'];
        if (is_null($db)) {
            $db = 0;
        }

        /*
        $k = 0;
        $db = 0;
        /** @var \Entities\Bizonylattetel $bt */
        /*
        foreach($this->bizonylattetelek as $bt) {
            if ($bt->getMozgat() && (!$bt->getRontott()) && ($bt->getTeljesites() <= $datum) && (!$raktarid || ($raktarid && $raktarid == $bt->getRaktarId()))) {
                $k += ($bt->getMennyiseg() * $bt->getIrany());
                $db++;
            }
        }
        */
        $this->keszletinfo = ['keszlet' => $k, 'mozgasdb' => $db];
        return $this->keszletinfo;
    }

    protected function calcFoglalasInfo($kivevebiz = null, $datum = null, $raktarid = null)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('mennyiseg', 'mennyiseg');
        $rsm->addScalarResult('mozgasdb', 'mozgasdb');

        if (!$datum) {
            $datum = new \DateTime();
        }

        $filter = new FilterDescriptor();
        $filter->addFilter('bt.termekvaltozat_id', '=', $this->getId());
        $filter->addFilter('bt.foglal', '=', 1);
        $filter->addSql('((bt.rontott = 0) OR (bt.rontott IS NULL))');
        if ($datum) {
            $filter->addFilter('bf.teljesites', '<=', $datum);
        }
        $filter->addFilter('bf.bizonylattipus_id', 'IN', ['megrendeles', 'webshopbiz']);
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

        $k = $d[0]['mennyiseg'] * -1;
        $db = $d[0]['mozgasdb'];

        $this->foglalasinfo = ['foglalas' => $k, 'mozgasdb' => $db];
        return $this->foglalasinfo;
    }

    public function getMozgasDb($datum = null, $raktarid = null)
    {
        if (!$this->keszletinfo) {
            $this->calcKeszletInfo($datum, $raktarid);
        }
        return $this->keszletinfo['mozgasdb'];
    }

    public function getKeszlet($datum = null, $raktarid = null, $nonegativ = false)
    {
        if (!$this->keszletinfo) {
            $this->calcKeszletInfo($datum, $raktarid);
        }
        $r = $this->keszletinfo['keszlet'];
        unset($this->keszletinfo);
        if ($nonegativ) {
            if ($r < 0) {
                $r = 0;
            }
        }
        return $r;
    }

    public function getFoglaltMennyiseg($kivevebiz = null)
    {
        if (\mkw\store::isFoglalas()) {
            if (is_a($kivevebiz, 'Bizonylatfej')) {
                $kivevebiz = $kivevebiz->getId();
            }
            $k = 0;
            if (!$this->foglalasinfo) {
                $this->calcFoglalasInfo($kivevebiz);
            }
            return $this->foglalasinfo['foglalas'];

            /** @var \Entities\Bizonylattetel $bt */
            /*
            foreach($this->bizonylattetelek as $bt) {
                $bf = $bt->getBizonylatfej();
                if ($bf->getBizonylattipusId() === 'megrendeles') {
                    $nemkivetel = true;
                    if ($kivevebiz) {
                        $nemkivetel = $bt->getBizonylatfejId() != $kivevebiz;
                    }
                    if ($bt->getFoglal() && ($nemkivetel)) {
                        $k += ($bt->getMennyiseg() * $bt->getIrany());
                    }
                }
            }
            return -1 * $k;
            */
        }
        return 0;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTermek()
    {
        return $this->termek;
    }

    public function setTermek($termek)
    {
        $this->termek = $termek;
//		$termek->addValtozat($this);
    }

    public function getLathato()
    {
        return $this->lathato;
    }

    public function setLathato($lathato)
    {
        $this->lathato = $lathato;
    }

    public function getElerheto()
    {
        return $this->elerheto;
    }

    public function setElerheto($elerheto)
    {
        $this->elerheto = $elerheto;
    }

    public function getTermekfokep()
    {
        return $this->termekfokep;
    }

    public function setTermekfokep($adat)
    {
        $this->termekfokep = $adat;
    }

    public function getAdatTipus1()
    {
        return $this->adattipus1;
    }

    public function getAdatTipus1Id()
    {
        if ($this->adattipus1) {
            return $this->adattipus1->getId();
        }
        return 0;
    }

    public function getAdatTipus1Nev()
    {
        if ($this->adattipus1) {
            return $this->adattipus1->getNev();
        }
        return '';
    }

    public function setAdatTipus1($at)
    {
        $this->adattipus1 = $at;
//		$at->addValtozat($this);
    }

    public function getErtek1()
    {
        return $this->ertek1;
    }

    public function setErtek1($ertek)
    {
        $this->ertek1 = $ertek;
    }

    public function getAdatTipus2()
    {
        return $this->adattipus2;
    }

    public function getAdatTipus2Id()
    {
        if ($this->adattipus2) {
            return $this->adattipus2->getId();
        }
        return 0;
    }

    public function getAdatTipus2Nev()
    {
        if ($this->adattipus2) {
            return $this->adattipus2->getNev();
        }
        return '';
    }

    public function setAdatTipus2($at)
    {
        $this->adattipus2 = $at;
//		$at->addValtozat($this);
    }

    public function getErtek2()
    {
        return $this->ertek2;
    }

    public function setErtek2($ertek)
    {
        $this->ertek2 = $ertek;
    }

    public function getLastmod()
    {
        return $this->lastmod;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getNetto()
    {
        return $this->netto;
    }

    public function setNetto($netto)
    {
        $this->netto = $netto;
        $this->brutto = $this->termek->getAfa()->calcBrutto($netto);
    }

    public function getBrutto()
    {
        return $this->brutto;
    }

    public function setBrutto($brutto)
    {
        $this->brutto = $brutto;
        $this->netto = $this->termek->getAfa()->calcNetto($brutto);
    }

    public function getKepurl($pre = '/')
    {
        if (!$this->termekfokep) {
            if ($this->getKep()) {
                return $this->getKep()->getUrl($pre);
            }
        } else {
            if ($this->getTermek()) {
                return $this->getTermek()->getKepurl($pre);
            }
        }
        return '';
    }

    public function getKepwcid()
    {
        if (!$this->termekfokep) {
            if ($this->getKep()) {
                return $this->getKep()->getWcid();
            }
        } else {
            if ($this->getTermek()) {
                return $this->getTermek()->getKepwcid();
            }
        }
        return '';
    }

    public function getKepurlMini($pre = '/')
    {
        if (!$this->termekfokep) {
            if ($this->getKep()) {
                return $this->getKep()->getUrlMini($pre);
            }
        } else {
            if ($this->getTermek()) {
                return $this->getTermek()->getKepurlMini($pre);
            }
        }
        return '';
    }

    public function getKepurlSmall($pre = '/')
    {
        if (!$this->termekfokep) {
            if ($this->getKep()) {
                return $this->getKep()->getUrlSmall($pre);
            }
        } else {
            if ($this->getTermek()) {
                return $this->getTermek()->getKepurlSmall($pre);
            }
        }
        return '';
    }

    public function getKepurlMedium($pre = '/')
    {
        if (!$this->termekfokep) {
            if ($this->getKep()) {
                return $this->getKep()->getUrlMedium($pre);
            }
        } else {
            if ($this->getTermek()) {
                return $this->getTermek()->getKepurlMedium($pre);
            }
        }
        return '';
    }

    public function getKepurlLarge($pre = '/')
    {
        if (!$this->termekfokep) {
            if ($this->getKep()) {
                return $this->getKep()->getUrlLarge($pre);
            }
        } else {
            if ($this->getTermek()) {
                return $this->getTermek()->getKepurlLarge($pre);
            }
        }
        return '';
    }

    public function getKepleiras()
    {
        if (!$this->termekfokep) {
            if ($this->getKep()) {
                return $this->getKep()->getLeiras();
            }
        } else {
            if ($this->getTermek()) {
                return $this->getTermek()->getKepleiras();
            }
        }
        return '';
    }

    public function getKep()
    {
        if ($this->termekfokep) {
            return false;
        }
        return $this->kep;
    }

    public function getKepId()
    {
        if (!$this->termekfokep && $this->getKep()) {
            return $this->kep->getId();
        }
        return '';
    }

    public function setKep($kep)
    {
        $this->kep = $kep;
//		$kep->addValtozat($this);
    }

    public function getIdegencikkszam()
    {
        return $this->idegencikkszam;
    }

    public function setIdegencikkszam($idegencikkszam)
    {
        $this->idegencikkszam = $idegencikkszam;
    }

    public function getCikkszam()
    {
        return $this->cikkszam;
    }

    public function setCikkszam($cikkszam)
    {
        $this->cikkszam = $cikkszam;
    }

    public function getNev()
    {
        return implode(' - ', [$this->getErtek1(), $this->getErtek2()]);
    }

    public function getVonalkod()
    {
        return $this->vonalkod;
    }

    public function setVonalkod($vonalkod)
    {
        $this->vonalkod = $vonalkod;
    }

    public function getBeerkezesdatum()
    {
        return $this->beerkezesdatum;
    }

    public function getBeerkezesdatumStr()
    {
        if ($this->getBeerkezesdatum()) {
            return $this->getBeerkezesdatum()->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    public function setBeerkezesdatum($adat = '')
    {
        if (is_a($adat, 'DateTime')) {
            $this->beerkezesdatum = $adat;
        } else {
            if ($adat == '') {
                $this->beerkezesdatum = null;
            } else {
                $this->beerkezesdatum = new \DateTime(\mkw\store::convDate($adat));
            }
        }
    }

    public function getSzin()
    {
        if ($this->getAdatTipus1Id() == \mkw\store::getParameter(\mkw\consts::ValtozatTipusSzin)) {
            return $this->getErtek1();
        }
        if ($this->getAdatTipus2Id() == \mkw\store::getParameter(\mkw\consts::ValtozatTipusSzin)) {
            return $this->getErtek2();
        }
        return null;
    }

    public function getMeret()
    {
        if ($this->getAdatTipus1Id() == \mkw\store::getParameter(\mkw\consts::ValtozatTipusMeret)) {
            return $this->getErtek1();
        }
        if ($this->getAdatTipus2Id() == \mkw\store::getParameter(\mkw\consts::ValtozatTipusMeret)) {
            return $this->getErtek2();
        }
        return null;
    }

    /**
     * @return mixed
     */
    public function getElerheto2()
    {
        return $this->elerheto2;
    }

    /**
     * @param mixed $elerheto2
     */
    public function setElerheto2($elerheto2)
    {
        $this->elerheto2 = $elerheto2;
    }

    /**
     * @return mixed
     */
    public function getElerheto3()
    {
        return $this->elerheto3;
    }

    /**
     * @param mixed $elerheto3
     */
    public function setElerheto3($elerheto3)
    {
        $this->elerheto3 = $elerheto3;
    }

    /**
     * @return mixed
     */
    public function getElerheto4()
    {
        return $this->elerheto4;
    }

    /**
     * @param mixed $elerheto4
     */
    public function setElerheto4($elerheto4)
    {
        $this->elerheto4 = $elerheto4;
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

    public function getXElerheto()
    {
        switch (\mkw\store::getSetupValue('webshopnum', 1)) {
            case 1:
                return $this->getElerheto();
            case 2:
                return $this->getElerheto2();
            case 3:
                return $this->getElerheto3();
            case 4:
                return $this->getElerheto4();
            case 5:
                return $this->getElerheto5();
            case 6:
                return $this->getElerheto6();
            case 7:
                return $this->getElerheto7();
            case 8:
                return $this->getElerheto8();
            case 9:
                return $this->getElerheto9();
            case 10:
                return $this->getElerheto10();
            case 11:
                return $this->getElerheto11();
            case 12:
                return $this->getElerheto12();
            case 13:
                return $this->getElerheto13();
            case 14:
                return $this->getElerheto14();
            case 15:
                return $this->getElerheto15();
            default:
                return $this->getElerheto();
        }
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
    public function getMinboltikeszlet()
    {
        return $this->minboltikeszlet;
    }

    public function calcMinBoltiKeszlet()
    {
        $tmbk = $this->getTermek()->getMinboltikeszlet() * 1;
        if ($tmbk) {
            return $tmbk;
        }
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
     * @return bool
     */
    public function getLathato5()
    {
        return $this->lathato5;
    }

    /**
     * @param bool $lathato5
     */
    public function setLathato5($lathato5): void
    {
        $this->lathato5 = $lathato5;
    }

    /**
     * @return bool
     */
    public function getLathato6()
    {
        return $this->lathato6;
    }

    /**
     * @param bool $lathato6
     */
    public function setLathato6($lathato6): void
    {
        $this->lathato6 = $lathato6;
    }

    /**
     * @return bool
     */
    public function getLathato7()
    {
        return $this->lathato7;
    }

    /**
     * @param bool $lathato7
     */
    public function setLathato7($lathato7): void
    {
        $this->lathato7 = $lathato7;
    }

    /**
     * @return bool
     */
    public function getLathato8()
    {
        return $this->lathato8;
    }

    /**
     * @param bool $lathato8
     */
    public function setLathato8($lathato8): void
    {
        $this->lathato8 = $lathato8;
    }

    /**
     * @return bool
     */
    public function getLathato9()
    {
        return $this->lathato9;
    }

    /**
     * @param bool $lathato9
     */
    public function setLathato9($lathato9): void
    {
        $this->lathato9 = $lathato9;
    }

    /**
     * @return bool
     */
    public function getLathato10()
    {
        return $this->lathato10;
    }

    /**
     * @param bool $lathato10
     */
    public function setLathato10($lathato10): void
    {
        $this->lathato10 = $lathato10;
    }

    /**
     * @return bool
     */
    public function getLathato11()
    {
        return $this->lathato11;
    }

    /**
     * @param bool $lathato11
     */
    public function setLathato11($lathato11): void
    {
        $this->lathato11 = $lathato11;
    }

    /**
     * @return bool
     */
    public function getLathato12()
    {
        return $this->lathato12;
    }

    /**
     * @param bool $lathato12
     */
    public function setLathato12($lathato12): void
    {
        $this->lathato12 = $lathato12;
    }

    /**
     * @return bool
     */
    public function getLathato13()
    {
        return $this->lathato13;
    }

    /**
     * @param bool $lathato13
     */
    public function setLathato13($lathato13): void
    {
        $this->lathato13 = $lathato13;
    }

    /**
     * @return bool
     */
    public function getLathato14()
    {
        return $this->lathato14;
    }

    /**
     * @param bool $lathato14
     */
    public function setLathato14($lathato14): void
    {
        $this->lathato14 = $lathato14;
    }

    /**
     * @return bool
     */
    public function getLathato15()
    {
        return $this->lathato15;
    }

    /**
     * @param bool $lathato15
     */
    public function setLathato15($lathato15): void
    {
        $this->lathato15 = $lathato15;
    }

    /**
     * @return bool
     */
    public function getElerheto5()
    {
        return $this->elerheto5;
    }

    /**
     * @param bool $elerheto5
     */
    public function setElerheto5($elerheto5): void
    {
        $this->elerheto5 = $elerheto5;
    }

    /**
     * @return bool
     */
    public function getElerheto6()
    {
        return $this->elerheto6;
    }

    /**
     * @param bool $elerheto6
     */
    public function setElerheto6($elerheto6): void
    {
        $this->elerheto6 = $elerheto6;
    }

    /**
     * @return bool
     */
    public function getElerheto7()
    {
        return $this->elerheto7;
    }

    /**
     * @param bool $elerheto7
     */
    public function setElerheto7($elerheto7): void
    {
        $this->elerheto7 = $elerheto7;
    }

    /**
     * @return bool
     */
    public function getElerheto8()
    {
        return $this->elerheto8;
    }

    /**
     * @param bool $elerheto8
     */
    public function setElerheto8($elerheto8): void
    {
        $this->elerheto8 = $elerheto8;
    }

    /**
     * @return bool
     */
    public function getElerheto9()
    {
        return $this->elerheto9;
    }

    /**
     * @param bool $elerheto9
     */
    public function setElerheto9($elerheto9): void
    {
        $this->elerheto9 = $elerheto9;
    }

    /**
     * @return bool
     */
    public function getElerheto10()
    {
        return $this->elerheto10;
    }

    /**
     * @param bool $elerheto10
     */
    public function setElerheto10($elerheto10): void
    {
        $this->elerheto10 = $elerheto10;
    }

    /**
     * @return bool
     */
    public function getElerheto11()
    {
        return $this->elerheto11;
    }

    /**
     * @param bool $elerheto11
     */
    public function setElerheto11($elerheto11): void
    {
        $this->elerheto11 = $elerheto11;
    }

    /**
     * @return bool
     */
    public function getElerheto12()
    {
        return $this->elerheto12;
    }

    /**
     * @param bool $elerheto12
     */
    public function setElerheto12($elerheto12): void
    {
        $this->elerheto12 = $elerheto12;
    }

    /**
     * @return bool
     */
    public function getElerheto13()
    {
        return $this->elerheto13;
    }

    /**
     * @param bool $elerheto13
     */
    public function setElerheto13($elerheto13): void
    {
        $this->elerheto13 = $elerheto13;
    }

    /**
     * @return bool
     */
    public function getElerheto14()
    {
        return $this->elerheto14;
    }

    /**
     * @param bool $elerheto14
     */
    public function setElerheto14($elerheto14): void
    {
        $this->elerheto14 = $elerheto14;
    }

    /**
     * @return bool
     */
    public function getElerheto15()
    {
        return $this->elerheto15;
    }

    /**
     * @param bool $elerheto15
     */
    public function setElerheto15($elerheto15): void
    {
        $this->elerheto15 = $elerheto15;
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

    public function shouldUploadToWc()
    {
        return $this->getWcdate()?->getTimestamp() - $this->getLastmod()?->getTimestamp() < -1;
    }

    public function toWC($eur = null, $termeknev = null)
    {
        if (!$termeknev) {
            $ford = $this->getTermek()->getTranslationsArray();
            $termeknev = $this->getTermek()->getNevForditas($ford, 'en_us');
        }
        if (!$eur) {
            $eur = \mkw\store::getEm()->getRepository(Valutanem::class)->findOneBy(['nev' => 'EUR']);
        }
        $vkeszlet = $this->getKeszlet() - $this->getFoglaltMennyiseg();
        if ($vkeszlet < 0) {
            $vkeszlet = 0;
        }
        $variation = [
            'sku' => 'TV-' . $this->getId(),
            //'date_on_sale_from' => '2025-01-01 00:00:00',
            //'date_on_sale_to' => '2025-03-01 00:00:00',
            'stock_quantity' => $vkeszlet,
            'stock_status' => $vkeszlet > 0 ? 'instock' : 'outofstock',
            'status' => !$this->getNLathato(\mkw\store::getWcWebshopNum()) ? 'draft' : 'publish',
            'manage_stock' => true,
        ];
        if ($this->getTermek()) {
            $variation['regular_price'] = (string)$this->getTermek()->getBruttoAr($this, null, $eur, \mkw\store::getParameter(\mkw\consts::Webshop4Price));
            $variation['sale_price'] = (string)$this->getTermek()->getNettoAr($this, null, $eur, \mkw\store::getParameter(\mkw\consts::Webshop4Discount));
        }
        if ($this->getKepwcid()) {
            $variation['image'] = [
                'id' => $this->getKepwcid()
            ];
        } elseif ($this->getKepurl()) {
            $variation['image'] = [
                'src' => \mkw\store::getWcImageUrlPrefix() . $this->getKepurl(),
                'alt' => $termeknev . ' - ' . $this->getCikkszam()
            ];
        }
        if ($this->getAdatTipus1()?->getWcid()) {
            $variation['attributes'][] = [
                'id' => $this->getAdatTipus1()->getWcid(),
                'option' => $this->getErtek1(),
            ];
        }
        if ($this->getAdatTipus2()?->getWcid()) {
            $variation['attributes'][] = [
                'id' => $this->getAdatTipus2()->getWcid(),
                'option' => $this->getErtek2(),
            ];
        }
        return $variation;
    }

    public function uploadToWC($doflush = true)
    {
        if (\mkw\store::isWoocommerceOn() && !$this->dontUploadToWC) {
            $wc = store::getWcClient();
            $data = $this->toWC();
            if (!$this->getWcid()) {
                \mkw\store::writelog($this->getId() . ': változat POST start');
                \mkw\store::writelog('products/' . $this->getTermek()->getWcid() . '/variations');
                try {
                    $result = $wc->post('products/' . $this->getTermek()->getWcid() . '/variations', $data);
                    $this->dontUploadToWC = true;
                    $this->setWcid($result['id']);
                    $this->setWcdate();
                    \mkw\store::getEm()->persist($this);
                } catch (HttpClientException $e) {
                    \mkw\store::writelog($this->getId() . ':HIBA: ' . $e->getResponse()->getBody());
                    throw $e;
                }
            } elseif ($this->shouldUploadToWc()) {
                \mkw\store::writelog($this->getId() . ': változat PUT start');
                try {
                    $result = $wc->put('products/' . $this->getTermek()->getWcid() . '/variations/' . $this->getWcid(), $data);
                    $this->dontUploadToWC = true;
                    $this->setWcdate();
                    \mkw\store::getEm()->persist($this);
                } catch (HttpClientException $e) {
                    \mkw\store::writelog($this->getId() . ':HIBA: ' . $e->getResponse()->getBody());
                    throw $e;
                }
            }
            if ($doflush) {
                \mkw\store::getEm()->flush();
            }
        }
    }

    public function sendKeszletToWC()
    {
        if (\mkw\store::isWoocommerceOn() && !$this->dontUploadToWC && $this->getWcid()) {
            $data = $this->getKeszletToWC();
            if ($data) {
                $wc = store::getWcClient();
                try {
                    \mkw\store::writelog($this->getId() . ':wcid:' . $this->getWcid() . ':TermekValtozat->sendKeszletToWC() START: ' . json_encode($data));
                    $result = $wc->put('products/' . $this->getTermek()?->getWcid() . '/variations/' . $this->getWcid(), $data);
                    \mkw\store::writelog($this->getId() . ':wcid:' . $this->getWcid() . ':TermekValtozat->sendKeszletToWC() STOP');
                } catch (HttpClientException $e) {
                    \mkw\store::writelog($this->getId() . ':TermekValtozat->sendKeszletToWC():HIBA: ' . $e->getResponse()->getBody());
                }
            }
        }
    }

    public function getKeszletToWC($needid = false)
    {
        if ($this->getWcid()) {
            $vkeszlet = $this->getKeszlet() - $this->getFoglaltMennyiseg();
            if ($vkeszlet < 0) {
                $vkeszlet = 0;
            }
            $variation = [
                'stock_quantity' => $vkeszlet,
                'stock_status' => $vkeszlet > 0 ? 'instock' : 'outofstock',
            ];
            if ($needid) {
                $variation['id'] = $this->getWcid();
            }
            return $variation;
        }
        return false;
    }

    public function sendArToWC()
    {
        if ($this->getWcid() && \mkw\store::isWoocommerceOn() && !$this->dontUploadToWC) {
            $eur = \mkw\store::getEm()->getRepository(Valutanem::class)->findOneBy(['nev' => 'EUR']);
            $variation = [
                'regular_price' => (string)$this->getTermek()?->getBruttoAr($this, null, $eur, \mkw\store::getParameter(\mkw\consts::Webshop4Price)),
                'sale_price' => (string)$this->getTermek()?->getNettoAr($this, null, $eur, \mkw\store::getParameter(\mkw\consts::Webshop4Discount)),
            ];
            $wc = store::getWcClient();
            try {
                \mkw\store::writelog($this->getId() . ':TermekValtozat->sendArToWC() START');
                $result = $wc->put('products/' . $this->getTermek()?->getWcid() . '/variations/' . $this->getWcid(), $variation);
                \mkw\store::writelog($this->getId() . ':TermekValtozat->sendArToWC() STOP');
            } catch (HttpClientException $e) {
                \mkw\store::writelog($this->getId() . ':TermekValtozat->sendKeszletToWC():HIBA: ' . $e->getResponse()->getBody());
            }
        }
    }

}