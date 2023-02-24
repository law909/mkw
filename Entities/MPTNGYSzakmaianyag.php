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
     * @ORM\ManyToOne(targetEntity="Dolgozo")
     * @ORM\JoinColumn(name="biralo1_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Dolgozo
     */
    private $biralo1;

    /**
     * @ORM\ManyToOne(targetEntity="Dolgozo")
     * @ORM\JoinColumn(name="biralo2_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Dolgozo
     */
    private $biralo2;

    /**
     * @ORM\ManyToOne(targetEntity="Dolgozo")
     * @ORM\JoinColumn(name="biralo3_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Dolgozo
     */
    private $biralo3;

    /** @ORM\Column(type="integer",nullable=true) */
    private $kezdodatum;

    /** @ORM\Column(type="string",length=10,nullable=true) */
    private $kezdoido;

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
     * @ORM\Column(type="string", length=7,nullable=true)
     */
    private $konyvkiadasho;

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

}
