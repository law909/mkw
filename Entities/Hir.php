<?php

namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\HirRepository")
 * @ORM\Table(name="hir",
 * options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"},
 * indexes={
 *		@ORM\index(name="hirslug_idx",columns={"slug"})
 * })
 */
class Hir
{
    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(type="string",length=255)
     */
    private $cim;
    /**
     * @Gedmo\Slug(fields={"cim"})
     * @ORM\Column(type="string",length=255)
     */
    private $slug;
    /** @ORM\Column(type="integer",nullable=true) */
    private $sorrend;
    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $forras;
    /** @ORM\Column(name="`lead`", type="text",nullable=true) */
    private $lead;
    /** @ORM\Column(type="date",nullable=true) */
    private $elsodatum;
    /** @ORM\Column(type="date",nullable=true) */
    private $utolsodatum;
    /** @ORM\Column(type="date",nullable=true) */
    private $datum;
    /** @ORM\Column(type="boolean") */
    private $lathato;
    /** @ORM\Column(type="text",nullable=true) */
    private $szoveg;
    /** @ORM\Column(type="text",nullable=true) */
    private $seodescription;
    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $kepurl;
    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $kepleiras;

    public function convertToArray()
    {
        $ret = [
            'slug' => $this->getSlug(),
            'cim' => $this->getCim(),
            'lead' => $this->getLead(),
            'datum' => $this->getDatumStr(),
            'szoveg' => $this->getSzoveg(),
            'url' => $this->getLink(),
            'kepurl' => $this->getKepurl(),
            'kepurlmini' => $this->getKepurlMini(),
            'kepurlsmall' => $this->getKepurlSmall(),
            'kepurlmedium' => $this->getKepurlMedium(),
            'kepurllarge' => $this->getKepurlLarge(),
            'kepleiras' => $this->getKepleiras()
        ];
        return $ret;
    }

    public function getLink()
    {
        return \mkw\store::getRouter()->generate('showhir', \mkw\store::getConfigValue('mainurl', true), ['hir' => $this->getSlug()]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCim()
    {
        return $this->cim;
    }

    public function getShowCim()
    {
        return $this->cim . ' - ' . \mkw\store::getParameter(\mkw\consts::Oldalcim);
    }

    public function setCim($cim)
    {
        $this->cim = $cim;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug($adat)
    {
        $this->slug = $adat;
    }

    public function getShowSeodescription()
    {
        if ($this->seodescription) {
            return $this->seodescription;
        }
        return $this->cim . ' - ' . \mkw\store::getParameter(\mkw\consts::Seodescription);
    }

    public function getSeodescription()
    {
        return $this->seodescription;
    }

    public function setSeodescription($adat)
    {
        $this->seodescription = $adat;
    }

    public function getElsodatum()
    {
        return $this->elsodatum;
    }

    public function getElsodatumStr()
    {
        if ($this->getElsodatum()) {
            return $this->getElsodatum()->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    public function setElsodatum($adat)
    {
        if ($adat == '') {
            $adat = date(\mkw\store::$DateFormat);
        }
        $this->elsodatum = new \DateTime(\mkw\store::convDate($adat));
    }

    public function getUtolsodatum()
    {
        return $this->utolsodatum;
    }

    public function getUtolsodatumStr()
    {
        if ($this->getUtolsodatum()) {
            return $this->getUtolsodatum()->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    public function setUtolsodatum($adat)
    {
        if ($adat == '') {
            $adat = date(\mkw\store::$DateFormat);
        }
        $this->utolsodatum = new \DateTime(\mkw\store::convDate($adat));
    }

    public function getDatum()
    {
        return $this->datum;
    }

    public function getDatumStr()
    {
        if ($this->getDatum()) {
            return $this->getDatum()->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    public function setDatum($adat)
    {
        if ($adat == '') {
            $adat = date(\mkw\store::$DateFormat);
        }
        $this->datum = new \DateTime(\mkw\store::convDate($adat));
    }

    public function getLathato()
    {
        return $this->lathato;
    }

    public function setLathato($adat)
    {
        $this->lathato = $adat;
    }

    public function getSzoveg()
    {
        return $this->szoveg;
    }

    public function setSzoveg($adat)
    {
        $this->szoveg = $adat;
    }

    public function getForras()
    {
        return $this->forras;
    }

    public function setForras($adat)
    {
        $this->forras = $adat;
    }

    public function getLead()
    {
        return $this->lead;
    }

    public function setLead($adat)
    {
        $this->lead = $adat;
    }

    public function getSorrend()
    {
        return $this->sorrend;
    }

    public function setSorrend($adat)
    {
        $this->sorrend = $adat;
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

    public function setKepurl($adat)
    {
        $this->kepurl = $adat;
    }

    public function getKepleiras()
    {
        return $this->kepleiras;
    }

    public function setKepleiras($adat)
    {
        $this->kepleiras = $adat;
    }
}