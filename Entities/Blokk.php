<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;
use mkw\store;

/**
 * @ORM\Entity(repositoryClass="Entities\BlokkRepository")
 * @ORM\Table(name="blokk",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class Blokk
{
    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /** @ORM\Column(type="string",length=255,nullable=false) */
    private $nev;

    /** @ORM\Column(type="integer",nullable=false) */
    private $tipus;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $cssclass;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $cssstyle;

    /** @ORM\Column(type="integer",nullable=true) */
    private $sorrend;

    /** @ORM\Column(type="boolean",nullable=false) */
    private $lathato = true;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $hatterkepurl;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $videourl;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $cim;

    /** @ORM\Column(type="text",nullable=true) */
    private $leiras;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $gombfelirat;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $gomburl;

    /** @ORM\Column(type="integer",nullable=true) */
    private $szovegigazitas;

    /** @ORM\Column(type="integer",nullable=true) */
    private $blokkmagassag;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $hatterkepurl2;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $videourl2;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $cim2;

    /** @ORM\Column(type="text",nullable=true) */
    private $leiras2;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $gombfelirat2;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $gomburl2;

    /** @ORM\Column(type="integer",nullable=true) */
    private $szovegigazitas2;

    public function getId()
    {
        return $this->id;
    }

    public function getNev()
    {
        return $this->nev;
    }

    public function setNev($nev)
    {
        $this->nev = $nev;
    }

    public function getTipus()
    {
        return $this->tipus;
    }

    public function setTipus($tipus)
    {
        $this->tipus = $tipus;
    }

    public function getCssclass()
    {
        return $this->cssclass;
    }

    public function setCssclass($cssclass)
    {
        $this->cssclass = $cssclass;
    }

    public function getCssstyle()
    {
        return $this->cssstyle;
    }

    public function setCssstyle($cssstyle)
    {
        $this->cssstyle = $cssstyle;
    }

    public function isLathato()
    {
        return $this->lathato;
    }

    public function setLathato($lathato)
    {
        $this->lathato = $lathato;
    }

    public function getSorrend()
    {
        return $this->sorrend;
    }

    public function setSorrend($sorrend)
    {
        $this->sorrend = $sorrend;
    }

    public function getHatterkepurl($pre = '/')
    {
        if ($this->hatterkepurl) {
            if ($this->hatterkepurl[0] !== $pre) {
                return $pre . $this->hatterkepurl;
            } else {
                return $this->hatterkepurl;
            }
        }
        return '';
    }

    public function getHatterkepurlMini($pre = '/')
    {
        $url = $this->getHatterkepurl($pre);
        if ($url) {
            $t = explode('.', $url);
            $ext = array_pop($t);
            return implode('.', $t) . store::getParameter(\mkw\consts::Miniimgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function getHatterkepurl400($pre = '/')
    {
        $url = $this->getHatterkepurl($pre);
        if ($url) {
            $t = explode('.', $url);
            $ext = array_pop($t);
            return implode('.', $t) . store::getParameter(\mkw\consts::I400imgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function getHatterkepurl2000($pre = '/')
    {
        $url = $this->getHatterkepurl($pre);
        if ($url) {
            $t = explode('.', $url);
            $ext = array_pop($t);
            return implode('.', $t) . store::getParameter(\mkw\consts::I2000imgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function getHatterkepurlSmall($pre = '/')
    {
        $url = $this->getHatterkepurl($pre);
        if ($url) {
            $t = explode('.', $url);
            $ext = array_pop($t);
            return implode('.', $t) . store::getParameter(\mkw\consts::Smallimgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function getHatterkepurlMedium($pre = '/')
    {
        $url = $this->getHatterkepurl($pre);
        if ($url) {
            $t = explode('.', $url);
            $ext = array_pop($t);
            return implode('.', $t) . store::getParameter(\mkw\consts::Mediumimgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function getHatterkepurlLarge($pre = '/')
    {
        $url = $this->getHatterkepurl($pre);
        if ($url) {
            $t = explode('.', $url);
            $ext = array_pop($t);
            return implode('.', $t) . store::getParameter(\mkw\consts::Bigimgpost, '') . '.' . $ext;
        }
        return '';
    }


    public function setHatterkepurl($hatterkepurl)
    {
        $this->hatterkepurl = $hatterkepurl;
    }

    public function getVideourl()
    {
        return $this->videourl;
    }

    public function setVideourl($videourl)
    {
        $this->videourl = $videourl;
    }

    public function getCim()
    {
        return $this->cim;
    }

    public function setCim($cim)
    {
        $this->cim = $cim;
    }

    public function getLeiras()
    {
        return $this->leiras;
    }

    public function setLeiras($leiras)
    {
        $this->leiras = $leiras;
    }

    public function getGombfelirat()
    {
        return $this->gombfelirat;
    }

    public function setGombfelirat($gombfelirat)
    {
        $this->gombfelirat = $gombfelirat;
    }

    public function getGomburl()
    {
        return $this->gomburl;
    }

    public function setGomburl($gomburl)
    {
        $this->gomburl = $gomburl;
    }

    public function getSzovegigazitas()
    {
        return $this->szovegigazitas;
    }

    public function setSzovegigazitas($szovegigazitas)
    {
        $this->szovegigazitas = $szovegigazitas;
    }

    public function getBlokkmagassag()
    {
        return $this->blokkmagassag;
    }

    public function setBlokkmagassag($blokkmagassag)
    {
        $this->blokkmagassag = $blokkmagassag;
    }

    public function getHatterkepurl2($pre = '/')
    {
        if ($this->hatterkepurl2) {
            if ($this->hatterkepurl2[0] !== $pre) {
                return $pre . $this->hatterkepurl2;
            } else {
                return $this->hatterkepurl2;
            }
        }
        return '';
    }

    public function getHatterkepurl2Mini($pre = '/')
    {
        $url = $this->getHatterkepurl2($pre);
        if ($url) {
            $t = explode('.', $url);
            $ext = array_pop($t);
            return implode('.', $t) . store::getParameter(\mkw\consts::Miniimgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function getHatterkepurl2400($pre = '/')
    {
        $url = $this->getHatterkepurl2($pre);
        if ($url) {
            $t = explode('.', $url);
            $ext = array_pop($t);
            return implode('.', $t) . store::getParameter(\mkw\consts::I400imgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function getHatterkepurl22000($pre = '/')
    {
        $url = $this->getHatterkepurl2($pre);
        if ($url) {
            $t = explode('.', $url);
            $ext = array_pop($t);
            return implode('.', $t) . store::getParameter(\mkw\consts::I2000imgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function getHatterkepurl2Small($pre = '/')
    {
        $url = $this->getHatterkepurl2($pre);
        if ($url) {
            $t = explode('.', $url);
            $ext = array_pop($t);
            return implode('.', $t) . store::getParameter(\mkw\consts::Smallimgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function getHatterkepurl2Medium($pre = '/')
    {
        $url = $this->getHatterkepurl2($pre);
        if ($url) {
            $t = explode('.', $url);
            $ext = array_pop($t);
            return implode('.', $t) . store::getParameter(\mkw\consts::Mediumimgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function getHatterkepurl2Large($pre = '/')
    {
        $url = $this->getHatterkepurl2($pre);
        if ($url) {
            $t = explode('.', $url);
            $ext = array_pop($t);
            return implode('.', $t) . store::getParameter(\mkw\consts::Bigimgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function setHatterkepurl2($hatterkepurl2)
    {
        $this->hatterkepurl2 = $hatterkepurl2;
    }

    public function getVideourl2()
    {
        return $this->videourl2;
    }

    public function setVideourl2($videourl2)
    {
        $this->videourl2 = $videourl2;
    }

    public function getCim2()
    {
        return $this->cim2;
    }

    public function setCim2($cim2)
    {
        $this->cim2 = $cim2;
    }

    public function getLeiras2()
    {
        return $this->leiras2;
    }

    public function setLeiras2($leiras2)
    {
        $this->leiras2 = $leiras2;
    }

    public function getGombfelirat2()
    {
        return $this->gombfelirat2;
    }

    public function setGombfelirat2($gombfelirat2)
    {
        $this->gombfelirat2 = $gombfelirat2;
    }

    public function getGomburl2()
    {
        return $this->gomburl2;
    }

    public function setGomburl2($gomburl2)
    {
        $this->gomburl2 = $gomburl2;
    }

    public function getSzovegigazitas2()
    {
        return $this->szovegigazitas2;
    }

    public function setSzovegigazitas2($szovegigazitas2)
    {
        $this->szovegigazitas2 = $szovegigazitas2;
    }
}
