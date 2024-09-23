<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;
use mkw\store;

/**
 * @ORM\Entity
 * @ORM\Table(name="cimketorzs",
 * options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"},
 * indexes={
 *		@ORM\index(name="cimketorzsslug_idx",columns={"slug"})
 * })
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="osztaly", type="string", length=30)
 * @ORM\DiscriminatorMap({"partner"="Partnercimketorzs", "termek"="Termekcimketorzs"})
 */
abstract class Cimketorzs
{
    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /** @ORM\Column(type="boolean") */
    private $menu1lathato = true;
    /** @ORM\Column(type="boolean") */
    private $menu2lathato = false;
    /** @ORM\Column(type="boolean") */
    private $menu3lathato = false;
    /** @ORM\Column(type="boolean") */
    private $menu4lathato = false;
    /** @ORM\Column(type="text",nullable=true) */
    private $leiras;
    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $oldalcim;
    /** @ORM\Column(type="text",nullable=true) */
    private $kepurl;
    /** @ORM\Column(type="text",nullable=true) */
    private $kepleiras;
    /** @ORM\Column(type="integer",nullable=true) */
    private $sorrend;
    /** @ORM\Column(type="integer", nullable=true) */
    private $wcid;
    /** @ORM\Column(type="datetime", nullable=true) */
    private $wcdate;

    public function getId()
    {
        return $this->id;
    }

    public function getMenu1lathato()
    {
        return $this->menu1lathato;
    }

    public function setMenu1lathato($menu1lathato)
    {
        $this->menu1lathato = $menu1lathato;
    }

    public function getMenu2lathato()
    {
        return $this->menu2lathato;
    }

    public function setMenu2lathato($menu2lathato)
    {
        $this->menu2lathato = $menu2lathato;
    }

    public function getMenu3lathato()
    {
        return $this->menu3lathato;
    }

    public function setMenu3lathato($menu3lathato)
    {
        $this->menu3lathato = $menu3lathato;
    }

    public function getMenu4lathato()
    {
        return $this->menu4lathato;
    }

    public function setMenu4lathato($menu4lathato)
    {
        $this->menu4lathato = $menu4lathato;
    }

    public function getLeiras()
    {
        return $this->leiras;
    }

    public function setLeiras($leiras)
    {
        $this->leiras = $leiras;
    }

    public function getOldalcim()
    {
        return $this->oldalcim;
    }

    public function setOldalcim($oldalcim)
    {
        $this->oldalcim = $oldalcim;
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

    public function getSorrend()
    {
        return $this->sorrend;
    }

    public function setSorrend($sorrend)
    {
        $this->sorrend = $sorrend;
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

}