<?php

namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity(repositoryClass="Entities\MPTNGYSzakmaianyagRepository")
 * @ORM\Table(name="mptngyszakmaianyag",
 *    options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 * */
class MPTNGYSzakmaianyag
{

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

    /**
     * @ORM\ManyToOne(targetEntity="Partner")
     * @ORM\JoinColumn(name="tulajdonos_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Partner
     */
    private $tulajdonos;

    /**
     * @ORM\ManyToOne(targetEntity="Partner")
     * @ORM\JoinColumn(name="szerzo1_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Partner
     */
    private $szerzo1;

    /**
     * @ORM\ManyToOne(targetEntity="Partner")
     * @ORM\JoinColumn(name="szerzo2_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Partner
     */
    private $szerzo2;

    /**
     * @ORM\ManyToOne(targetEntity="Partner")
     * @ORM\JoinColumn(name="szerzo3_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Partner
     */
    private $szerzo3;

    /**
     * @ORM\ManyToOne(targetEntity="Partner")
     * @ORM\JoinColumn(name="szerzo4_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Partner
     */
    private $szerzo4;

    /**
     * @ORM\ManyToOne(targetEntity="Partner")
     * @ORM\JoinColumn(name="szerzo5_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Partner
     */
    private $szerzo5;

    /**
     * @ORM\ManyToOne(targetEntity="Partner")
     * @ORM\JoinColumn(name="szerzo5_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Partner
     */
    private $szerzo6;
    /**
     * @ORM\ManyToOne(targetEntity="Partner")
     * @ORM\JoinColumn(name="szerzo5_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Partner
     */
    private $szerzo7;
    /**
     * @ORM\ManyToOne(targetEntity="Partner")
     * @ORM\JoinColumn(name="szerzo5_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Partner
     */
    private $szerzo8;
    /**
     * @ORM\ManyToOne(targetEntity="Partner")
     * @ORM\JoinColumn(name="szerzo5_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Partner
     */
    private $szerzo9;
    /**
     * @ORM\ManyToOne(targetEntity="Partner")
     * @ORM\JoinColumn(name="szerzo5_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Partner
     */
    private $szerzo10;

    /**
     * @ORM\ManyToOne(targetEntity="Partner")
     * @ORM\JoinColumn(name="szerzo5_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Partner
     */
    private $opponens;

    /**
     * @ORM\ManyToOne(targetEntity="Partner")
     * @ORM\JoinColumn(name="beszelgetopartner_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Partner
     */
    private $beszelgetopartner;

    /**
     * @ORM\ManyToOne(targetEntity="MPTNGYSzakmaianyagtipus")
     * @ORM\JoinColumn(name="tipus_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\MPTNGYSzakmaianyagtipus
     */
    private $tipus;

    /**
     * @ORM\ManyToOne(targetEntity="MPTNGYSzakmaianyag")
     * @ORM\JoinColumn(name="eloadas1_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\MPTNGYSzakmaianyag
     */
    private $eloadas1;

    /**
     * @ORM\ManyToOne(targetEntity="MPTNGYSzakmaianyag")
     * @ORM\JoinColumn(name="eloadas2_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\MPTNGYSzakmaianyag
     */
    private $eloadas2;

    /**
     * @ORM\ManyToOne(targetEntity="MPTNGYSzakmaianyag")
     * @ORM\JoinColumn(name="eloadas3_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\MPTNGYSzakmaianyag
     */
    private $eloadas3;

    /**
     * @ORM\ManyToOne(targetEntity="MPTNGYSzakmaianyag")
     * @ORM\JoinColumn(name="eloadas4_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\MPTNGYSzakmaianyag
     */
    private $eloadas4;
    /**
     * @ORM\ManyToOne(targetEntity="MPTNGYSzakmaianyag")
     * @ORM\JoinColumn(name="eloadas5_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\MPTNGYSzakmaianyag
     */
    private $eloadas5;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $cim;

    /**
     * @ORM\Column(type="text",nullable=true)
     */
    private $tartalom;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $kulcsszo1;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $kulcsszo2;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $kulcsszo3;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $kulcsszo4;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $kulcsszo5;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $biralatkesz = false;

    /**
     * @ORM\ManyToOne(targetEntity="Dolgozo",inversedBy="mptngyszakmaianyagok1")
     * @ORM\JoinColumn(name="biralo1_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Dolgozo
     */
    private $biralo1;

    /**
     * @ORM\ManyToOne(targetEntity="Dolgozo",inversedBy="mptngyszakmaianyagok2")
     * @ORM\JoinColumn(name="biralo2_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Dolgozo
     */
    private $biralo2;

    /**
     * @ORM\ManyToOne(targetEntity="Dolgozo",inversedBy="mptngyszakmaianyagok3")
     * @ORM\JoinColumn(name="biralo3_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Dolgozo
     */
    private $biralo3;

    /** @ORM\Column(type="integer",nullable=true) */
    private $kezdodatum;

    /** @ORM\Column(type="string",length=10,nullable=true) */
    private $kezdoido;

    /** @ORM\Column(type="string",length=10,nullable=true) */
    private $vegido;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $konferencianszerepelhet = false;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $szerzo1email;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $szerzo2email;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $szerzo3email;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $szerzo4email;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $szerzo5email;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $szerzo6email;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $szerzo7email;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $szerzo8email;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $szerzo9email;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $szerzo10email;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $opponensemail;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $beszelgetopartneremail;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $vegleges = false;

    /**
     * @ORM\ManyToOne(targetEntity="MPTNGYTemakor")
     * @ORM\JoinColumn(name="temakor1_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\MPTNGYTemakor
     */
    private $temakor1;

    /**
     * @ORM\ManyToOne(targetEntity="MPTNGYTemakor")
     * @ORM\JoinColumn(name="temakor2_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\MPTNGYTemakor
     */
    private $temakor2;

    /**
     * @ORM\ManyToOne(targetEntity="MPTNGYTemakor")
     * @ORM\JoinColumn(name="temakor3_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\MPTNGYTemakor
     */
    private $temakor3;

    /**
     * @ORM\ManyToOne(targetEntity="MPTNGYTema")
     * @ORM\JoinColumn(name="tema_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\MPTNGYTema
     */
    private $tema;

    /**
     * @ORM\Column(type="string", length=7,nullable=true)
     */
    private $konyvkiadasho = '';

    /**
     * @ORM\Column(type="text",nullable=true)
     */
    private $egyebszerzok;
    /**
     * @ORM\Column(type="text",nullable=true)
     */
    private $egyebszerzokorg;

    /** @ORM\Column(type="integer",nullable=true) */
    private $b1szempont1;
    /** @ORM\Column(type="integer",nullable=true) */
    private $b1szempont2;
    /** @ORM\Column(type="integer",nullable=true) */
    private $b1szempont3;
    /** @ORM\Column(type="integer",nullable=true) */
    private $b1szempont4;
    /** @ORM\Column(type="integer",nullable=true) */
    private $b1szempont5;
    /**
     * @ORM\Column(type="text",nullable=true)
     */
    private $b1szovegesertekeles;


    /** @ORM\Column(type="integer",nullable=true) */
    private $b2szempont1;
    /** @ORM\Column(type="integer",nullable=true) */
    private $b2szempont2;
    /** @ORM\Column(type="integer",nullable=true) */
    private $b2szempont3;
    /** @ORM\Column(type="integer",nullable=true) */
    private $b2szempont4;
    /** @ORM\Column(type="integer",nullable=true) */
    private $b2szempont5;
    /**
     * @ORM\Column(type="text",nullable=true)
     */
    private $b2szovegesertekeles;


    /** @ORM\Column(type="integer",nullable=true) */
    private $b3szempont1;
    /** @ORM\Column(type="integer",nullable=true) */
    private $b3szempont2;
    /** @ORM\Column(type="integer",nullable=true) */
    private $b3szempont3;
    /** @ORM\Column(type="integer",nullable=true) */
    private $b3szempont4;
    /** @ORM\Column(type="integer",nullable=true) */
    private $b3szempont5;
    /**
     * @ORM\Column(type="text",nullable=true)
     */
    private $b3szovegesertekeles;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $b1biralatkesz = false;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $b2biralatkesz = false;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $b3biralatkesz = false;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $pluszbiralokell = false;

    /**
     * @ORM\ManyToOne(targetEntity="Jogaterem")
     * @ORM\JoinColumn(name="terem_id",referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $terem;

    public function calcB1pont()
    {
        if ($this->getBiralo1()) {
            return $this->getB1szempont1()
                + $this->getB1szempont2()
                + $this->getB1szempont3()
                + $this->getB1szempont4()
                + $this->getB1szempont5();
        }
        return 0;
    }

    public function calcB2pont()
    {
        if ($this->getBiralo2()) {
            return $this->getB2szempont1()
                + $this->getB2szempont2()
                + $this->getB2szempont3()
                + $this->getB2szempont4()
                + $this->getB2szempont5();
        }
        return 0;
    }

    public function calcB3pont()
    {
        if ($this->getBiralo3()) {
            return $this->getB3szempont1()
                + $this->getB3szempont2()
                + $this->getB3szempont3()
                + $this->getB3szempont4()
                + $this->getB3szempont5();
        }
        return 0;
    }

    public function calcPont()
    {
        return $this->calcB1pont() + $this->calcB2pont() + $this->calcB3pont();
    }

    public function calcPluszBiraloKell()
    {
        if ($this->isB1biralatkesz() && $this->isB2biralatkesz()) {
            return ((abs($this->calcB1pont() - $this->calcB2pont()) >= 10)
                || ($this->calcB1pont() + $this->calcB2pont() < 25));
        }
        return false;
    }

    public function calcKonferencianszerepelhet()
    {
        if ($this->getBiralo3()) {
            if ($this->isB1biralatkesz() && $this->isB2biralatkesz() && $this->isB3biralatkesz()) {
                if (!$this->calcB3pont()) {
                    return false;
                }
                return $this->calcPont() >= 25;
            }
        } else {
            if ($this->isB1biralatkesz() && $this->isB2biralatkesz()) {
                return ($this->calcPont() >= 25) and (abs($this->calcB1pont() - $this->calcB2pont()) < 10);
            }
        }
        return false;
    }

    public function calcBiralatkesz()
    {
        if (!$this->getBiralo1() && !$this->getBiralo2() && !$this->getBiralo3()) {
            return false;
        }

        $ret = true;
        if ($this->getBiralo1()) {
            $ret = $ret && $this->isB1biralatkesz();
        }
        if ($this->getBiralo2()) {
            $ret = $ret && $this->isB2biralatkesz();
        }
        if ($this->getBiralo3()) {
            $ret = $ret && $this->isB3biralatkesz();
        }
        return $ret;
    }

    public function isSzerzoRegistered($num)
    {
        $f1 = "getSzerzo{$num}email";
        $f2 = "getSzerzo{$num}";

        if ($num === 6) {
            $f1 = 'getBeszelgetopartneremail';
            $f2 = 'getBeszelgetopartner';
        }

        if ($this->$f1()) {
            return !is_null($this->$f2());
        }
        return true;
    }

    public function isAllSzerzoRegistered()
    {
        $ret = $this->isSzerzoRegistered(1) &&
            $this->isSzerzoRegistered(2) &&
            $this->isSzerzoRegistered(3) &&
            $this->isSzerzoRegistered(4);

        if ($this->getTipusId() == \mkw\store::getParameter(\mkw\consts::MPTNGYSzimpoziumTipus)) {
            $ret = $ret && $this->isSzerzoRegistered(5);
        }
        if ($this->getTipusId() == \mkw\store::getParameter(\mkw\consts::MPTNGYKonyvbemutatoTipus)) {
            $ret = $ret && $this->isSzerzoRegistered(6);
        }
        return $ret;
    }

    public function getId()
    {
        return $this->id;
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

    /**
     * @return \Entities\Partner
     */
    public function getTulajdonos()
    {
        return $this->tulajdonos;
    }

    public function getTulajdonosId()
    {
        if ($this->tulajdonos) {
            return $this->tulajdonos->getId();
        }
        return '';
    }

    /**
     * @param \Entities\Partner $val
     */
    public function setTulajdonos($val)
    {
        if (!$val) {
            $this->removeTulajdonos();
        } else {
            $this->tulajdonos = $val;
        }
    }

    public function removeTulajdonos()
    {
        if ($this->tulajdonos !== null) {
            $this->tulajdonos = null;
        }
    }

    /**
     * @return \Entities\Partner
     */
    public function getSzerzo1()
    {
        return $this->szerzo1;
    }

    public function getSzerzo1Id()
    {
        if ($this->szerzo1) {
            return $this->szerzo1->getId();
        }
        return '';
    }

    public function getSzerzo1Nev()
    {
        if ($this->szerzo1) {
            return $this->szerzo1->getNev();
        }
        return '';
    }

    /**
     * @param \Entities\Partner $val
     */
    public function setSzerzo1($val)
    {
        if (!$val) {
            $this->removeSzerzo1();
        } else {
            $this->szerzo1 = $val;
        }
    }

    public function removeSzerzo1()
    {
        if ($this->szerzo1 !== null) {
            $this->szerzo1 = null;
        }
    }

    /**
     * @return \Entities\Partner
     */
    public function getSzerzo2()
    {
        return $this->szerzo2;
    }

    public function getSzerzo2Id()
    {
        if ($this->szerzo2) {
            return $this->szerzo2->getId();
        }
        return '';
    }

    public function getSzerzo2Nev()
    {
        if ($this->szerzo2) {
            return $this->szerzo2->getNev();
        }
        return '';
    }

    /**
     * @param \Entities\Partner $val
     */
    public function setSzerzo2($val)
    {
        if (!$val) {
            $this->removeSzerzo2();
        } else {
            $this->szerzo2 = $val;
        }
    }

    public function removeSzerzo2()
    {
        if ($this->szerzo2 !== null) {
            $this->szerzo2 = null;
        }
    }

    /**
     * @return \Entities\Partner
     */
    public function getSzerzo3()
    {
        return $this->szerzo3;
    }

    public function getSzerzo3Id()
    {
        if ($this->szerzo3) {
            return $this->szerzo3->getId();
        }
        return '';
    }

    public function getSzerzo3Nev()
    {
        if ($this->szerzo3) {
            return $this->szerzo3->getNev();
        }
        return '';
    }

    /**
     * @param \Entities\Partner $val
     */
    public function setSzerzo3($val)
    {
        if (!$val) {
            $this->removeSzerzo3();
        } else {
            $this->szerzo3 = $val;
        }
    }

    public function removeSzerzo3()
    {
        if ($this->szerzo3 !== null) {
            $this->szerzo3 = null;
        }
    }

    /**
     * @return \Entities\Partner
     */
    public function getSzerzo4()
    {
        return $this->szerzo4;
    }

    public function getSzerzo4Id()
    {
        if ($this->szerzo4) {
            return $this->szerzo4->getId();
        }
        return '';
    }

    public function getSzerzo4Nev()
    {
        if ($this->szerzo4) {
            return $this->szerzo4->getNev();
        }
        return '';
    }

    /**
     * @param \Entities\Partner $val
     */
    public function setSzerzo4($val)
    {
        if (!$val) {
            $this->removeSzerzo4();
        } else {
            $this->szerzo4 = $val;
        }
    }

    public function removeSzerzo4()
    {
        if ($this->szerzo4 !== null) {
            $this->szerzo4 = null;
        }
    }

    /**
     * @return \Entities\Partner
     */
    public function getSzerzo5()
    {
        return $this->szerzo5;
    }

    public function getSzerzo5Id()
    {
        if ($this->szerzo5) {
            return $this->szerzo5->getId();
        }
        return '';
    }

    public function getSzerzo5Nev()
    {
        if ($this->szerzo5) {
            return $this->szerzo5->getNev();
        }
        return '';
    }

    /**
     * @param \Entities\Partner $val
     */
    public function setSzerzo5($val)
    {
        if (!$val) {
            $this->removeSzerzo5();
        } else {
            $this->szerzo5 = $val;
        }
    }

    public function removeSzerzo5()
    {
        if ($this->szerzo5 !== null) {
            $this->szerzo5 = null;
        }
    }

    /**
     * @return \Entities\MPTNGYSzakmaianyagtipus
     */
    public function getTipus()
    {
        return $this->tipus;
    }

    public function getTipusId()
    {
        if ($this->tipus) {
            return $this->tipus->getId();
        }
        return '';
    }

    /**
     * @param \Entities\MPTNGYSzakmaianyagtipus $val
     */
    public function setTipus($val)
    {
        if (!$val) {
            $this->removeTipus();
        } else {
            $this->tipus = $val;
        }
    }

    public function removeTipus()
    {
        if ($this->tipus !== null) {
            $this->tipus = null;
        }
    }

    /**
     * @return \Entities\MPTNGYSzakmaianyag
     */
    public function getEloadas1()
    {
        return $this->eloadas1;
    }

    public function getEloadas1Id()
    {
        if ($this->eloadas1) {
            return $this->eloadas1->getId();
        }
        return '';
    }

    public function getEloadas1Cim()
    {
        if ($this->eloadas1) {
            return $this->eloadas1->getCim();
        }
        return '';
    }

    /**
     * @param \Entities\Partner $val
     */
    public function setEloadas1($val)
    {
        if (!$val) {
            $this->removeEloadas1();
        } else {
            $this->eloadas1 = $val;
        }
    }

    public function removeEloadas1()
    {
        if ($this->eloadas1 !== null) {
            $this->eloadas1 = null;
        }
    }

    /**
     * @return \Entities\MPTNGYSzakmaianyag
     */
    public function getEloadas2()
    {
        return $this->eloadas2;
    }

    public function getEloadas2Id()
    {
        if ($this->eloadas2) {
            return $this->eloadas2->getId();
        }
        return '';
    }

    public function getEloadas2Cim()
    {
        if ($this->eloadas2) {
            return $this->eloadas2->getCim();
        }
        return '';
    }

    /**
     * @param \Entities\Partner $val
     */
    public function setEloadas2($val)
    {
        if (!$val) {
            $this->removeEloadas2();
        } else {
            $this->eloadas2 = $val;
        }
    }

    public function removeEloadas2()
    {
        if ($this->eloadas2 !== null) {
            $this->eloadas2 = null;
        }
    }

    /**
     * @return \Entities\MPTNGYSzakmaianyag
     */
    public function getEloadas3()
    {
        return $this->eloadas3;
    }

    public function getEloadas3Id()
    {
        if ($this->eloadas3) {
            return $this->eloadas3->getId();
        }
        return '';
    }

    public function getEloadas3Cim()
    {
        if ($this->eloadas3) {
            return $this->eloadas3->getCim();
        }
        return '';
    }

    /**
     * @param \Entities\Partner $val
     */
    public function setEloadas3($val)
    {
        if (!$val) {
            $this->removeEloadas3();
        } else {
            $this->eloadas3 = $val;
        }
    }

    public function removeEloadas3()
    {
        if ($this->eloadas3 !== null) {
            $this->eloadas3 = null;
        }
    }

    /**
     * @return \Entities\MPTNGYSzakmaianyag
     */
    public function getEloadas4()
    {
        return $this->eloadas4;
    }

    public function getEloadas4Id()
    {
        if ($this->eloadas4) {
            return $this->eloadas4->getId();
        }
        return '';
    }

    public function getEloadas4Cim()
    {
        if ($this->eloadas4) {
            return $this->eloadas4->getCim();
        }
        return '';
    }

    /**
     * @param \Entities\Partner $val
     */
    public function setEloadas4($val)
    {
        if (!$val) {
            $this->removeEloadas4();
        } else {
            $this->eloadas4 = $val;
        }
    }

    public function removeEloadas4()
    {
        if ($this->eloadas4 !== null) {
            $this->eloadas4 = null;
        }
    }

    /**
     * @return \Entities\MPTNGYSzakmaianyag
     */
    public function getEloadas5()
    {
        return $this->eloadas5;
    }

    public function getEloadas5Id()
    {
        if ($this->eloadas5) {
            return $this->eloadas5->getId();
        }
        return '';
    }

    public function getEloadas5Cim()
    {
        if ($this->eloadas5) {
            return $this->eloadas5->getCim();
        }
        return '';
    }

    /**
     * @param \Entities\Partner $val
     */
    public function setEloadas5($val)
    {
        if (!$val) {
            $this->removeEloadas5();
        } else {
            $this->eloadas5 = $val;
        }
    }

    public function removeEloadas5()
    {
        if ($this->eloadas5 !== null) {
            $this->eloadas5 = null;
        }
    }

    /**
     * @return mixed
     */
    public function getCim()
    {
        return $this->cim;
    }

    /**
     * @param mixed $cim
     */
    public function setCim($cim): void
    {
        $this->cim = $cim;
    }

    /**
     * @return mixed
     */
    public function getTartalom()
    {
        return $this->tartalom;
    }

    /**
     * @param mixed $tartalom
     */
    public function setTartalom($tartalom): void
    {
        $this->tartalom = $tartalom;
    }

    /**
     * @return \Entities\Dolgozo
     */
    public function getBiralo1()
    {
        return $this->biralo1;
    }

    public function getBiralo1Id()
    {
        if ($this->biralo1) {
            return $this->biralo1->getId();
        }
        return '';
    }

    public function getBiralo1Nev()
    {
        if ($this->biralo1) {
            return $this->biralo1->getNev();
        }
        return '';
    }

    /**
     * @param \Entities\Dolgozo $val
     */
    public function setBiralo1($val)
    {
        if (!$val) {
            $this->removeBiralo1();
        } else {
            $this->biralo1 = $val;
        }
    }

    public function removeBiralo1()
    {
        if ($this->biralo1 !== null) {
            $this->biralo1 = null;
        }
    }

    /**
     * @return \Entities\Dolgozo
     */
    public function getBiralo2()
    {
        return $this->biralo2;
    }

    public function getBiralo2Id()
    {
        if ($this->biralo2) {
            return $this->biralo2->getId();
        }
        return '';
    }

    public function getBiralo2Nev()
    {
        if ($this->biralo2) {
            return $this->biralo2->getNev();
        }
        return '';
    }

    /**
     * @param \Entities\Dolgozo $val
     */
    public function setBiralo2($val)
    {
        if (!$val) {
            $this->removeBiralo2();
        } else {
            $this->biralo2 = $val;
        }
    }

    public function removeBiralo2()
    {
        if ($this->biralo2 !== null) {
            $this->biralo2 = null;
        }
    }

    /**
     * @return \Entities\Dolgozo
     */
    public function getBiralo3()
    {
        return $this->biralo3;
    }

    public function getBiralo3Id()
    {
        if ($this->biralo3) {
            return $this->biralo3->getId();
        }
        return '';
    }

    public function getBiralo3Nev()
    {
        if ($this->biralo3) {
            return $this->biralo3->getNev();
        }
        return '';
    }

    /**
     * @param \Entities\Dolgozo $val
     */
    public function setBiralo3($val)
    {
        if (!$val) {
            $this->removeBiralo3();
        } else {
            $this->biralo3 = $val;
        }
    }

    public function removeBiralo3()
    {
        if ($this->biralo3 !== null) {
            $this->biralo3 = null;
        }
    }

    /**
     * @return mixed
     */
    public function getKulcsszo1()
    {
        return $this->kulcsszo1;
    }

    /**
     * @param mixed $kulcsszo1
     */
    public function setKulcsszo1($kulcsszo1): void
    {
        $this->kulcsszo1 = $kulcsszo1;
    }

    /**
     * @return mixed
     */
    public function getKulcsszo2()
    {
        return $this->kulcsszo2;
    }

    /**
     * @param mixed $kulcsszo2
     */
    public function setKulcsszo2($kulcsszo2): void
    {
        $this->kulcsszo2 = $kulcsszo2;
    }

    /**
     * @return mixed
     */
    public function getKulcsszo3()
    {
        return $this->kulcsszo3;
    }

    /**
     * @param mixed $kulcsszo3
     */
    public function setKulcsszo3($kulcsszo3): void
    {
        $this->kulcsszo3 = $kulcsszo3;
    }

    /**
     * @return mixed
     */
    public function getKulcsszo4()
    {
        return $this->kulcsszo4;
    }

    /**
     * @param mixed $kulcsszo4
     */
    public function setKulcsszo4($kulcsszo4): void
    {
        $this->kulcsszo4 = $kulcsszo4;
    }

    /**
     * @return mixed
     */
    public function getKulcsszo5()
    {
        return $this->kulcsszo5;
    }

    /**
     * @param mixed $kulcsszo5
     */
    public function setKulcsszo5($kulcsszo5): void
    {
        $this->kulcsszo5 = $kulcsszo5;
    }

    /**
     * @return bool
     */
    public function isBiralatkesz()
    {
        return $this->biralatkesz;
    }

    /**
     * @param bool $biralatkesz
     */
    public function setBiralatkesz($biralatkesz): void
    {
        $this->biralatkesz = $biralatkesz;
    }

    /**
     * @return mixed
     */
    public function getKezdodatum()
    {
        return $this->kezdodatum;
    }

    public function getKezdodatumStr()
    {
        return \mkw\store::getMPTNGYDate($this->kezdodatum);
    }

    /**
     * @param mixed $kezdodatum
     */
    public function setKezdodatum($kezdodatum): void
    {
        $this->kezdodatum = $kezdodatum;
    }

    /**
     * @return mixed
     */
    public function getKezdoido()
    {
        return $this->kezdoido;
    }

    /**
     * @param mixed $kezdoido
     */
    public function setKezdoido($kezdoido): void
    {
        $this->kezdoido = $kezdoido;
    }

    /**
     * @return bool
     */
    public function isKonferencianszerepelhet()
    {
        return $this->konferencianszerepelhet;
    }

    /**
     * @param bool $konferencianszerepelhet
     */
    public function setKonferencianszerepelhet($konferencianszerepelhet): void
    {
        $this->konferencianszerepelhet = $konferencianszerepelhet;
    }

    /**
     * @return mixed
     */
    public function getSzerzo1email()
    {
        return $this->szerzo1email;
    }

    /**
     * @param mixed $szerzo1email
     */
    public function setSzerzo1email($szerzo1email): void
    {
        $this->szerzo1email = $szerzo1email;
    }

    /**
     * @return mixed
     */
    public function getSzerzo2email()
    {
        return $this->szerzo2email;
    }

    /**
     * @param mixed $szerzo2email
     */
    public function setSzerzo2email($szerzo2email): void
    {
        $this->szerzo2email = $szerzo2email;
    }

    /**
     * @return mixed
     */
    public function getSzerzo3email()
    {
        return $this->szerzo3email;
    }

    /**
     * @param mixed $szerzo3email
     */
    public function setSzerzo3email($szerzo3email): void
    {
        $this->szerzo3email = $szerzo3email;
    }

    /**
     * @return mixed
     */
    public function getSzerzo4email()
    {
        return $this->szerzo4email;
    }

    /**
     * @param mixed $szerzo4email
     */
    public function setSzerzo4email($szerzo4email): void
    {
        $this->szerzo4email = $szerzo4email;
    }

    /**
     * @return mixed
     */
    public function getSzerzo5email()
    {
        return $this->szerzo5email;
    }

    /**
     * @param mixed $szerzo5email
     */
    public function setSzerzo5email($szerzo5email): void
    {
        $this->szerzo5email = $szerzo5email;
    }

    /**
     * @return bool
     */
    public function isVegleges()
    {
        return $this->vegleges;
    }

    /**
     * @param bool $vegleges
     */
    public function setVegleges($vegleges): void
    {
        $this->vegleges = $vegleges;
    }

    /**
     * @return \Entities\MPTNGYTemakor
     */
    public function getTemakor1()
    {
        return $this->temakor1;
    }

    /**
     * @param \Entities\MPTNGYTemakor $val
     */
    public function setTemakor1($val)
    {
        if (!$val) {
            $this->removeTemakor1();
        } else {
            $this->temakor1 = $val;
        }
    }

    public function removeTemakor1()
    {
        if ($this->temakor1 !== null) {
            $this->temakor1 = null;
        }
    }

    /**
     * @return \Entities\MPTNGYTemakor
     */
    public function getTemakor2()
    {
        return $this->temakor2;
    }

    /**
     * @param \Entities\MPTNGYTemakor $val
     */
    public function setTemakor2($val)
    {
        if (!$val) {
            $this->removeTemakor2();
        } else {
            $this->temakor2 = $val;
        }
    }

    public function removeTemakor2()
    {
        if ($this->temakor2 !== null) {
            $this->temakor2 = null;
        }
    }

    /**
     * @return \Entities\MPTNGYTemakor
     */
    public function getTemakor3()
    {
        return $this->temakor3;
    }

    /**
     * @param \Entities\MPTNGYTemakor $val
     */
    public function setTemakor3($val)
    {
        if (!$val) {
            $this->removeTemakor3();
        } else {
            $this->temakor3 = $val;
        }
    }

    public function removeTemakor3()
    {
        if ($this->temakor3 !== null) {
            $this->temakor3 = null;
        }
    }

    /**
     * @return \Entities\Partner
     */
    public function getBeszelgetopartner()
    {
        return $this->beszelgetopartner;
    }

    public function getBeszelgetopartnerId()
    {
        if ($this->beszelgetopartner) {
            return $this->beszelgetopartner->getId();
        }
        return '';
    }

    public function getBeszelgetopartnerNev()
    {
        if ($this->beszelgetopartner) {
            return $this->beszelgetopartner->getNev();
        }
        return '';
    }

    /**
     * @param \Entities\Partner $val
     */
    public function setBeszelgetopartner($val)
    {
        if (!$val) {
            $this->removeBeszelgetopartner();
        } else {
            $this->beszelgetopartner = $val;
        }
    }

    public function removeBeszelgetopartner()
    {
        if ($this->beszelgetopartner !== null) {
            $this->beszelgetopartner = null;
        }
    }

    /**
     * @return mixed
     */
    public function getBeszelgetopartneremail()
    {
        return $this->beszelgetopartneremail;
    }

    /**
     * @param mixed $beszelgetopartneremail
     */
    public function setBeszelgetopartneremail($beszelgetopartneremail): void
    {
        $this->beszelgetopartneremail = $beszelgetopartneremail;
    }

    /**
     * @return mixed
     */
    public function getKonyvkiadasho()
    {
        return $this->konyvkiadasho;
    }

    /**
     * @param mixed $konyvkiadasho
     */
    public function setKonyvkiadasho($konyvkiadasho): void
    {
        $this->konyvkiadasho = $konyvkiadasho;
    }

    /**
     * @return mixed
     */
    public function getEgyebszerzok()
    {
        return $this->egyebszerzok;
    }

    /**
     * @param mixed $egyebszerzok
     */
    public function setEgyebszerzok($egyebszerzok): void
    {
        $this->egyebszerzok = $egyebszerzok;
    }

    /**
     * @return mixed
     */
    public function getB1szempont1()
    {
        return $this->b1szempont1;
    }

    /**
     * @param mixed $b1szempont1
     */
    public function setB1szempont1($b1szempont1): void
    {
        $this->b1szempont1 = $b1szempont1;
    }

    /**
     * @return mixed
     */
    public function getB1szempont2()
    {
        return $this->b1szempont2;
    }

    /**
     * @param mixed $b1szempont2
     */
    public function setB1szempont2($b1szempont2): void
    {
        $this->b1szempont2 = $b1szempont2;
    }

    /**
     * @return mixed
     */
    public function getB1szempont3()
    {
        return $this->b1szempont3;
    }

    /**
     * @param mixed $b1szempont3
     */
    public function setB1szempont3($b1szempont3): void
    {
        $this->b1szempont3 = $b1szempont3;
    }

    /**
     * @return mixed
     */
    public function getB1szempont4()
    {
        return $this->b1szempont4;
    }

    /**
     * @param mixed $b1szempont4
     */
    public function setB1szempont4($b1szempont4): void
    {
        $this->b1szempont4 = $b1szempont4;
    }

    /**
     * @return mixed
     */
    public function getB1szempont5()
    {
        return $this->b1szempont5;
    }

    /**
     * @param mixed $b1szempont5
     */
    public function setB1szempont5($b1szempont5): void
    {
        $this->b1szempont5 = $b1szempont5;
    }

    /**
     * @return mixed
     */
    public function getB1szovegesertekeles()
    {
        return $this->b1szovegesertekeles;
    }

    /**
     * @param mixed $b1szovegesertekeles
     */
    public function setB1szovegesertekeles($b1szovegesertekeles): void
    {
        $this->b1szovegesertekeles = $b1szovegesertekeles;
    }

    /**
     * @return mixed
     */
    public function getB2szempont1()
    {
        return $this->b2szempont1;
    }

    /**
     * @param mixed $b2szempont1
     */
    public function setB2szempont1($b2szempont1): void
    {
        $this->b2szempont1 = $b2szempont1;
    }

    /**
     * @return mixed
     */
    public function getB2szempont2()
    {
        return $this->b2szempont2;
    }

    /**
     * @param mixed $b2szempont2
     */
    public function setB2szempont2($b2szempont2): void
    {
        $this->b2szempont2 = $b2szempont2;
    }

    /**
     * @return mixed
     */
    public function getB2szempont3()
    {
        return $this->b2szempont3;
    }

    /**
     * @param mixed $b2szempont3
     */
    public function setB2szempont3($b2szempont3): void
    {
        $this->b2szempont3 = $b2szempont3;
    }

    /**
     * @return mixed
     */
    public function getB2szempont4()
    {
        return $this->b2szempont4;
    }

    /**
     * @param mixed $b2szempont4
     */
    public function setB2szempont4($b2szempont4): void
    {
        $this->b2szempont4 = $b2szempont4;
    }

    /**
     * @return mixed
     */
    public function getB2szempont5()
    {
        return $this->b2szempont5;
    }

    /**
     * @param mixed $b2szempont5
     */
    public function setB2szempont5($b2szempont5): void
    {
        $this->b2szempont5 = $b2szempont5;
    }

    /**
     * @return mixed
     */
    public function getB2szovegesertekeles()
    {
        return $this->b2szovegesertekeles;
    }

    /**
     * @param mixed $b2szovegesertekeles
     */
    public function setB2szovegesertekeles($b2szovegesertekeles): void
    {
        $this->b2szovegesertekeles = $b2szovegesertekeles;
    }

    /**
     * @return mixed
     */
    public function getB3szempont1()
    {
        return $this->b3szempont1;
    }

    /**
     * @param mixed $b3szempont1
     */
    public function setB3szempont1($b3szempont1): void
    {
        $this->b3szempont1 = $b3szempont1;
    }

    /**
     * @return mixed
     */
    public function getB3szempont2()
    {
        return $this->b3szempont2;
    }

    /**
     * @param mixed $b3szempont2
     */
    public function setB3szempont2($b3szempont2): void
    {
        $this->b3szempont2 = $b3szempont2;
    }

    /**
     * @return mixed
     */
    public function getB3szempont3()
    {
        return $this->b3szempont3;
    }

    /**
     * @param mixed $b3szempont3
     */
    public function setB3szempont3($b3szempont3): void
    {
        $this->b3szempont3 = $b3szempont3;
    }

    /**
     * @return mixed
     */
    public function getB3szempont4()
    {
        return $this->b3szempont4;
    }

    /**
     * @param mixed $b3szempont4
     */
    public function setB3szempont4($b3szempont4): void
    {
        $this->b3szempont4 = $b3szempont4;
    }

    /**
     * @return mixed
     */
    public function getB3szempont5()
    {
        return $this->b3szempont5;
    }

    /**
     * @param mixed $b3szempont5
     */
    public function setB3szempont5($b3szempont5): void
    {
        $this->b3szempont5 = $b3szempont5;
    }

    /**
     * @return mixed
     */
    public function getB3szovegesertekeles()
    {
        return $this->b3szovegesertekeles;
    }

    /**
     * @param mixed $b3szovegesertekeles
     */
    public function setB3szovegesertekeles($b3szovegesertekeles): void
    {
        $this->b3szovegesertekeles = $b3szovegesertekeles;
    }

    /**
     * @return bool
     */
    public function isB1biralatkesz()
    {
        return $this->b1biralatkesz;
    }

    /**
     * @param bool $b1biralatkesz
     */
    public function setB1biralatkesz($b1biralatkesz): void
    {
        $this->b1biralatkesz = $b1biralatkesz;
    }

    /**
     * @return bool
     */
    public function isB2biralatkesz()
    {
        return $this->b2biralatkesz;
    }

    /**
     * @param bool $b2biralatkesz
     */
    public function setB2biralatkesz($b2biralatkesz): void
    {
        $this->b2biralatkesz = $b2biralatkesz;
    }

    /**
     * @return bool
     */
    public function isB3biralatkesz()
    {
        return $this->b3biralatkesz;
    }

    /**
     * @param bool $b3biralatkesz
     */
    public function setB3biralatkesz($b3biralatkesz): void
    {
        $this->b3biralatkesz = $b3biralatkesz;
    }

    /**
     * @return bool
     */
    public function isPluszbiralokell()
    {
        return $this->pluszbiralokell;
    }

    /**
     * @param bool $pluszbiralokell
     */
    public function setPluszbiralokell($pluszbiralokell): void
    {
        $this->pluszbiralokell = $pluszbiralokell;
    }

    public function getTerem()
    {
        return $this->terem;
    }

    public function getTeremNev()
    {
        if ($this->terem) {
            return $this->terem->getNev();
        }
        return '';
    }

    public function getTeremId()
    {
        if ($this->terem) {
            return $this->terem->getId();
        }
        return '';
    }

    public function setTerem($terem)
    {
        $this->terem = $terem;
    }

    public function removeTerem()
    {
        $this->terem = null;
    }

    /**
     * @return mixed
     */
    public function getVegido()
    {
        return $this->vegido;
    }

    /**
     * @param mixed $vegido
     */
    public function setVegido($vegido): void
    {
        $this->vegido = $vegido;
    }

    /**
     * @return MPTNGYTema
     */
    public function getTema()
    {
        return $this->tema;
    }

    public function getTemaNev()
    {
        return $this->tema?->getNev();
    }

    /**
     * @param \Entities\MPTNGYTema $val
     */
    public function setTema($val)
    {
        if (!$val) {
            $this->removeTema();
        } else {
            $this->tema = $val;
        }
    }

    public function removeTema()
    {
        if ($this->tema !== null) {
            $this->tema = null;
        }
    }

    /**
     * @return mixed
     */
    public function getEgyebszerzokorg()
    {
        return $this->egyebszerzokorg;
    }

    /**
     * @param mixed $egyebszerzokorg
     */
    public function setEgyebszerzokorg($egyebszerzokorg): void
    {
        $this->egyebszerzokorg = $egyebszerzokorg;
    }

    /**
     * @return \Entities\Partner
     */
    public function getSzerzo6()
    {
        return $this->szerzo6;
    }

    public function getSzerzo6Id()
    {
        if ($this->szerzo6) {
            return $this->szerzo6->getId();
        }
        return '';
    }

    public function getSzerzo6Nev()
    {
        if ($this->szerzo6) {
            return $this->szerzo6->getNev();
        }
        return '';
    }

    /**
     * @param \Entities\Partner $val
     */
    public function setSzerzo6($val)
    {
        if (!$val) {
            $this->removeSzerzo6();
        } else {
            $this->szerzo6 = $val;
        }
    }

    public function removeSzerzo6()
    {
        if ($this->szerzo6 !== null) {
            $this->szerzo6 = null;
        }
    }

    /**
     * @return \Entities\Partner
     */
    public function getSzerzo7()
    {
        return $this->szerzo7;
    }

    public function getSzerzo7Id()
    {
        if ($this->szerzo7) {
            return $this->szerzo7->getId();
        }
        return '';
    }

    public function getSzerzo7Nev()
    {
        if ($this->szerzo7) {
            return $this->szerzo7->getNev();
        }
        return '';
    }

    /**
     * @param \Entities\Partner $val
     */
    public function setSzerzo7($val)
    {
        if (!$val) {
            $this->removeSzerzo7();
        } else {
            $this->szerzo7 = $val;
        }
    }

    public function removeSzerzo7()
    {
        if ($this->szerzo7 !== null) {
            $this->szerzo7 = null;
        }
    }

    /**
     * @return \Entities\Partner
     */
    public function getSzerzo8()
    {
        return $this->szerzo8;
    }

    public function getSzerzo8Id()
    {
        if ($this->szerzo8) {
            return $this->szerzo8->getId();
        }
        return '';
    }

    public function getSzerzo8Nev()
    {
        if ($this->szerzo8) {
            return $this->szerzo8->getNev();
        }
        return '';
    }

    /**
     * @param \Entities\Partner $val
     */
    public function setSzerzo8($val)
    {
        if (!$val) {
            $this->removeSzerzo8();
        } else {
            $this->szerzo8 = $val;
        }
    }

    public function removeSzerzo8()
    {
        if ($this->szerzo8 !== null) {
            $this->szerzo8 = null;
        }
    }

    /**
     * @return \Entities\Partner
     */
    public function getSzerzo9()
    {
        return $this->szerzo9;
    }

    public function getSzerzo9Id()
    {
        if ($this->szerzo9) {
            return $this->szerzo9->getId();
        }
        return '';
    }

    public function getSzerzo9Nev()
    {
        if ($this->szerzo9) {
            return $this->szerzo9->getNev();
        }
        return '';
    }

    /**
     * @param \Entities\Partner $val
     */
    public function setSzerzo9($val)
    {
        if (!$val) {
            $this->removeSzerzo9();
        } else {
            $this->szerzo9 = $val;
        }
    }

    public function removeSzerzo9()
    {
        if ($this->szerzo9 !== null) {
            $this->szerzo9 = null;
        }
    }

    /**
     * @return \Entities\Partner
     */
    public function getSzerzo10()
    {
        return $this->szerzo10;
    }

    public function getSzerzo10Id()
    {
        if ($this->szerzo10) {
            return $this->szerzo10->getId();
        }
        return '';
    }

    public function getSzerzo10Nev()
    {
        if ($this->szerzo10) {
            return $this->szerzo10->getNev();
        }
        return '';
    }

    /**
     * @param \Entities\Partner $val
     */
    public function setSzerzo10($val)
    {
        if (!$val) {
            $this->removeSzerzo10();
        } else {
            $this->szerzo10 = $val;
        }
    }

    public function removeSzerzo10()
    {
        if ($this->szerzo10 !== null) {
            $this->szerzo10 = null;
        }
    }

    /**
     * @return \Entities\Partner
     */
    public function getOpponens()
    {
        return $this->opponens;
    }

    public function getOpponensId()
    {
        if ($this->opponens) {
            return $this->opponens->getId();
        }
        return '';
    }

    public function getOpponensNev()
    {
        if ($this->opponens) {
            return $this->opponens->getNev();
        }
        return '';
    }

    /**
     * @param \Entities\Partner $val
     */
    public function setOpponens($val)
    {
        if (!$val) {
            $this->removeOpponens();
        } else {
            $this->opponens = $val;
        }
    }

    public function removeOpponens()
    {
        if ($this->opponens !== null) {
            $this->opponens = null;
        }
    }

    /**
     * @return mixed
     */
    public function getSzerzo6email()
    {
        return $this->szerzo6email;
    }

    /**
     * @param mixed $szerzo6email
     */
    public function setSzerzo6email($szerzo6email): void
    {
        $this->szerzo6email = $szerzo6email;
    }

    /**
     * @return mixed
     */
    public function getSzerzo7email()
    {
        return $this->szerzo7email;
    }

    /**
     * @param mixed $szerzo7email
     */
    public function setSzerzo7email($szerzo7email): void
    {
        $this->szerzo7email = $szerzo7email;
    }

    /**
     * @return mixed
     */
    public function getSzerzo8email()
    {
        return $this->szerzo8email;
    }

    /**
     * @param mixed $szerzo8email
     */
    public function setSzerzo8email($szerzo8email): void
    {
        $this->szerzo8email = $szerzo8email;
    }

    /**
     * @return mixed
     */
    public function getSzerzo9email()
    {
        return $this->szerzo9email;
    }

    /**
     * @param mixed $szerzo9email
     */
    public function setSzerzo9email($szerzo9email): void
    {
        $this->szerzo9email = $szerzo9email;
    }

    /**
     * @return mixed
     */
    public function getSzerzo10email()
    {
        return $this->szerzo10email;
    }

    /**
     * @param mixed $szerzo10email
     */
    public function setSzerzo10email($szerzo10email): void
    {
        $this->szerzo10email = $szerzo10email;
    }

    /**
     * @return mixed
     */
    public function getOpponensemail()
    {
        return $this->opponensemail;
    }

    /**
     * @param mixed $opponensemail
     */
    public function setOpponensemail($opponensemail): void
    {
        $this->opponensemail = $opponensemail;
    }


}
