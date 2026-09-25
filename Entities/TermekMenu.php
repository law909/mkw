<?php

namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use mkw\store;
use Traits\GetsFieldValue;

/**
 * @ORM\Entity(repositoryClass="Entities\TermekMenuRepository")
 * @ORM\Table(name="termekmenu",
 * options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"},
 * uniqueConstraints={@ORM\UniqueConstraint(name="termekmenufaslug_uniq",columns={"termekmenufa_id","slug"})},
 * indexes={
 *      @ORM\index(name="termekmenuslug_idx",columns={"slug"}),
 *      @ORM\index(name="termekmenunevparent_idx",columns={"nev","parent_id"}),
 *      @ORM\index(name="termekmenuidegenkod_idx",columns={"idegenkod"})
 * })
 */
class TermekMenu
{

    use GetsFieldValue;

    private $gtnev;

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
     * @ORM\OneToMany(targetEntity="TermekMenu", mappedBy="parent")
     * @ORM\OrderBy({"sorrend"="ASC","nev"="ASC"})
     */
    private $children;

    /**
     * @ORM\ManyToOne(targetEntity="TermekMenu", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id",onDelete="restrict")
     */
    private $parent;

    /**
     * The menu of the node: a root gets it once, the rest inherit it from their parent.
     *
     * @ORM\ManyToOne(targetEntity="TermekMenuFa")
     * @ORM\JoinColumn(name="termekmenufa_id", referencedColumnName="id",nullable=false,onDelete="restrict")
     */
    private $termekmenufa;

    /** @ORM\Column(type="string",length=255,nullable=false) */
    private $nev;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $nev_l1;

    /** @ORM\Column(type="integer",nullable=true) */
    private $sorrend;

    /**
     * @Gedmo\Slug(fields={"nev"}, unique_base="termekmenufa")
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    private $slug;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $rovidleiras = '';

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $rovidleiras_l1 = '';

    /** @ORM\Column(type="text",nullable=true) */
    private $leiras;
    /** @ORM\Column(type="text",nullable=true) */
    private $leiras_l1;

    /** @ORM\Column(type="text",nullable=true) */
    private $leiras2;
    /** @ORM\Column(type="text",nullable=true) */
    private $leiras2_l1;

    /** @ORM\Column(type="text",nullable=true) */
    private $leiras3;
    /** @ORM\Column(type="text",nullable=true) */
    private $leiras3_l1;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $oldalcim;

    /** @ORM\Column(type="text",nullable=true) */
    private $seodescription;

    /** @ORM\Column(type="text",nullable=true) */
    private $kepurl;

    /** @ORM\Column(type="text",nullable=true) */
    private $kepleiras;

    /** @ORM\Column(type="boolean",nullable=true) */
    private $inaktiv = false;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $idegenkod = '';

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $arukeresoid;

    public function __toString()
    {
        return (string)$this->id . ' - ' . $this->nev;
    }

    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function toA2a()
    {
        $x = [];
        $x['id'] = $this->getId();
        $x['nev'] = $this->getNev();
        $x['nev_l1'] = $this->getNevL1();
        $x['nev_en'] = $this->getNevL1();
        $x['rovidleiras'] = $this->getRovidleiras();
        $x['rovidleiras_l1'] = $this->getRovidleirasL1();
        $x['rovidleiras_en'] = $this->getRovidleirasL1();
        $x['leiras'] = $this->getLeiras();
        $x['leiras_l1'] = $this->getLeirasL1();
        $x['leiras_en'] = $this->getLeirasL1();
        $x['leiras2'] = $this->getLeiras2();
        $x['leiras2_l1'] = $this->getLeiras2L1();
        $x['leiras2_en'] = $this->getLeiras2L1();
        $x['leiras3'] = $this->getLeiras3();
        $x['leiras3_l1'] = $this->getLeiras3L1();
        $x['leiras3_en'] = $this->getLeiras3L1();
        $x['sorrend'] = $this->getSorrend();
        if ($this->getKepurl()) {
            $x['kepurl'] = \mkw\store::getFullUrl($this->getKepurlLarge());
            $x['kozepeskepurl'] = \mkw\store::getFullUrl($this->getKepurlMedium());
            $x['kiskepurl'] = \mkw\store::getFullUrl($this->getKepurlSmall());
            $x['minikepurl'] = \mkw\store::getFullUrl($this->getKepurlMini());
            $x['kepleiras'] = $this->getKepleiras();
        } else {
            $x['kepurl'] = null;
            $x['kozepeskepurl'] = null;
            $x['kiskepurl'] = null;
            $x['minikepurl'] = null;
            $x['kepleiras'] = null;
        }
        return $x;
    }

    public function toLista()
    {
        $x = [];
        $x['id'] = $this->getId();
        $x['slug'] = $this->getSlug();
        $x['nev'] = $this->getNev();
        $x['nev_l1'] = $this->getNevL1();
        $x['rovidleiras'] = $this->getRovidleiras();
        $x['rovidleiras_l1'] = $this->getRovidleirasL1();
        $x['leiras'] = $this->getLeiras();
        $x['leiras_l1'] = $this->getLeirasL1();
        $x['leiras2'] = $this->getLeiras2();
        $x['leiras2_l1'] = $this->getLeiras2L1();
        $x['leiras3'] = $this->getLeiras3();
        $x['leiras3_l1'] = $this->getLeiras3L1();
        $x['sorrend'] = $this->getSorrend();
        if ($this->getKepurl()) {
            $x['kepurl'] = \mkw\store::getFullUrl($this->getKepurlLarge());
            $x['kozepeskepurl'] = \mkw\store::getFullUrl($this->getKepurlMedium());
            $x['kiskepurl'] = \mkw\store::getFullUrl($this->getKepurlSmall());
            $x['minikepurl'] = \mkw\store::getFullUrl($this->getKepurlMini());
            $x['kepleiras'] = $this->getKepleiras();
        } else {
            $x['kepurl'] = null;
            $x['kozepeskepurl'] = null;
            $x['kiskepurl'] = null;
            $x['minikepurl'] = null;
            $x['kepleiras'] = null;
        }
        return $x;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function addChild(TermekMenu $child)
    {
        if (!$this->children->contains($child)) {
            $this->children->add($child);
            $child->setParent($this);
        }
    }

    public function removeChild(TermekMenu $child)
    {
        if ($this->children->removeElement($child)) {
            $child->removeParent();
            return true;
        }
        return false;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function getParentId()
    {
        if ($this->parent) {
            return $this->parent->getId();
        }
        return 0;
    }

    public function getParentNev()
    {
        if ($this->parent) {
            return $this->parent->getNev();
        }
        return '';
    }

    public function getParentNevLocale()
    {
        if ($this->parent) {
            return $this->parent->getLocalizedFieldValue('nev');
        }
        return '';
    }

    public function setParent(TermekMenu $parent)
    {
        if ($this->parent !== $parent) {
            $this->parent = $parent;
            $parent->addChild($this);
            $this->termekmenufa = $parent->getTermekmenufa();
        }
    }

    public function removeParent()
    {
        if ($this->parent !== null) {
            $parent = $this->parent;
            $this->parent = null;
            $parent->removeChild($this);
        }
    }

    public function getTermekmenufa(): ?TermekMenuFa
    {
        return $this->termekmenufa;
    }

    public function getTermekmenufaId()
    {
        return $this->termekmenufa?->getId();
    }

    /** Only a root is given its menu; a node below one takes it from setParent(). */
    public function setTermekmenufa(TermekMenuFa $termekmenufa)
    {
        if ($this->parent) {
            throw new \LogicException('A menü csak a gyökérnél állítható, a többi csomópont a szülőjétől örökli.');
        }
        $this->termekmenufa = $termekmenufa;
    }

    private function gtn($level, $elval)
    {
        if ($level && $level->getParent()) {
            $this->gtnev = $level->getNev() . $elval . $this->gtnev;
            $this->gtn($level->getParent(), $elval);
        }
    }

    public function getTeljesNev($elval = '|', $selfname = null)
    {
        if (!$selfname) {
            $selfname = $this->getNev();
        }
        $this->gtnev = $selfname;
        $this->gtn($this->getParent(), $elval);
        return $this->gtnev;
    }

    public function getNev()
    {
        return $this->nev;
    }

    public function setNev($nev)
    {
        $this->nev = $nev;
    }

    public function getNevL1()
    {
        return $this->nev_l1;
    }

    public function setNevL1($nev)
    {
        $this->nev_l1 = $nev;
    }

    public function getSorrend()
    {
        return $this->sorrend;
    }

    public function setSorrend($sorrend)
    {
        $this->sorrend = $sorrend;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function getLeiras()
    {
        return $this->leiras;
    }

    public function setLeiras($leiras)
    {
        $this->leiras = $leiras;
    }

    public function getLeirasL1()
    {
        return $this->leiras_l1;
    }

    public function setLeirasL1($leiras)
    {
        $this->leiras_l1 = $leiras;
    }

    public function getLeiras2()
    {
        return $this->leiras2;
    }

    public function setLeiras2($leiras)
    {
        $this->leiras2 = $leiras;
    }

    public function getLeiras2L1()
    {
        return $this->leiras2_l1;
    }

    public function setLeiras2L1($leiras)
    {
        $this->leiras2_l1 = $leiras;
    }

    public function getLeiras3()
    {
        return $this->leiras3;
    }

    public function setLeiras3($leiras)
    {
        $this->leiras3 = $leiras;
    }

    public function getLeiras3L1()
    {
        return $this->leiras3_l1;
    }

    public function setLeiras3L1($leiras)
    {
        $this->leiras3_l1 = $leiras;
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
            $result = store::getParameter(\mkw\consts::Katoldalcim);
            if ($result) {
                $result = str_replace('[kategorianev]', $this->getLocalizedFieldValueOrDefault('nev'), $result);
                $result = str_replace('[global]', store::getParameter(\mkw\consts::Oldalcim), $result);
                return $result;
            } else {
                return store::getParameter(\mkw\consts::Oldalcim);
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
            $result = store::getParameter(\mkw\consts::Katseodescription);
            if ($result) {
                $result = str_replace('[kategorianev]', $this->getLocalizedFieldValueOrDefault('nev'), $result);
                $result = str_replace('[global]', store::getParameter(\mkw\consts::Seodescription), $result);
                return $result;
            }
            // sablon híján a kategória saját rövid leírása egyedibb, mint a globális szöveg
            $rovid = \Services\SeoService::plainText($this->getLocalizedFieldValueOrDefault('rovidleiras'), 160);
            return $rovid ?: store::getParameter(\mkw\consts::Seodescription);
        }
    }

    public function setSeodescription($seodescription)
    {
        $this->seodescription = $seodescription;
    }

    public function getKepurl($pre = '/')
    {
        if ($this->kepurl) {
            if ($this->kepurl[0] !== $pre) {
                return $pre . $this->kepurl;
            }
            return $this->kepurl;
        }
        return '';
    }

    public function getKepurlMini($pre = '/')
    {
        $kepurl = $this->getKepurl($pre);
        if ($kepurl) {
            $t = explode('.', $kepurl);
            $ext = array_pop($t);
            return implode('.', $t) . store::getParameter(\mkw\consts::Miniimgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function getKepurlSmall($pre = '/')
    {
        $kepurl = $this->getKepurl($pre);
        if ($kepurl) {
            $t = explode('.', $kepurl);
            $ext = array_pop($t);
            return implode('.', $t) . store::getParameter(\mkw\consts::Smallimgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function getKepurlMedium($pre = '/')
    {
        $kepurl = $this->getKepurl($pre);
        if ($kepurl) {
            $t = explode('.', $kepurl);
            $ext = array_pop($t);
            return implode('.', $t) . store::getParameter(\mkw\consts::Mediumimgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function getKepurlLarge($pre = '/')
    {
        $kepurl = $this->getKepurl($pre);
        if ($kepurl) {
            $t = explode('.', $kepurl);
            $ext = array_pop($t);
            return implode('.', $t) . store::getParameter(\mkw\consts::Bigimgpost, '') . '.' . $ext;
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

    public function isDeletable()
    {
        return $this->children->isEmpty()
            && !store::getEm()->getRepository(TermekMenuTermek::class)->count(['termekmenu' => $this]);
    }

    public function getLastmod()
    {
        return $this->lastmod;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getRovidleiras()
    {
        return $this->rovidleiras;
    }

    public function setRovidleiras($rovidleiras)
    {
        $this->rovidleiras = $rovidleiras;
    }

    public function getRovidleirasL1()
    {
        return $this->rovidleiras_l1;
    }

    public function setRovidleirasL1($rovidleiras)
    {
        $this->rovidleiras_l1 = $rovidleiras;
    }

    public function getInaktiv()
    {
        return $this->inaktiv;
    }

    public function setInaktiv($i)
    {
        $this->inaktiv = $i;
    }

    public function getIdegenkod()
    {
        return $this->idegenkod;
    }

    public function setIdegenkod($idegenkod)
    {
        $this->idegenkod = $idegenkod;
    }

    /**
     * @return mixed
     */
    public function getArukeresoid()
    {
        return $this->arukeresoid;
    }

    /**
     * @param mixed $arukeresoid
     */
    public function setArukeresoid($arukeresoid)
    {
        $this->arukeresoid = $arukeresoid;
    }

    public function getPath($parent)
    {
        $navi = [$parent->getNev()];
        $szulo = $parent->getParent();
        while ($szulo) {
            $navi[] = $szulo->getNev();
            $szulo = $szulo->getParent();
        }
        return array_reverse($navi);
    }

}