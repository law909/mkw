<?php

namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\CsapatRepository")
 * @ORM\Table(name="csapat",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class Csapat
{
    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /** @ORM\Column(type="string",length=255,nullable=false) */
    private $nev;

    /**
     * @Gedmo\Slug(fields={"nev"})
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    private $slug;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $logourl;

    /** @ORM\Column(type="text",nullable=true) */
    private $leiras;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $kepurl;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $logoleiras;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $kepleiras;

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

    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    public function getLogourl($pre = '/')
    {
        if ($this->logourl) {
            if ($this->logourl[0] !== $pre) {
                return $pre . $this->logourl;
            } else {
                return $this->logourl;
            }
        }
        return '';
    }

    public function getLogourlMini($pre = '/')
    {
        $logourl = $this->getLogourl($pre);
        if ($logourl) {
            $t = explode('.', $logourl);
            $ext = array_pop($t);
            return implode('.', $t) . \mkw\store::getParameter(\mkw\consts::Miniimgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function getLogourlSmall($pre = '/')
    {
        $logourl = $this->getLogourl($pre);
        if ($logourl) {
            $t = explode('.', $logourl);
            $ext = array_pop($t);
            return implode('.', $t) . \mkw\store::getParameter(\mkw\consts::Smallimgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function getLogourlMedium($pre = '/')
    {
        $logourl = $this->getLogourl($pre);
        if ($logourl) {
            $t = explode('.', $logourl);
            $ext = array_pop($t);
            return implode('.', $t) . \mkw\store::getParameter(\mkw\consts::Mediumimgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function getLogourlLarge($pre = '/')
    {
        $logourl = $this->getLogourl($pre);
        if ($logourl) {
            $t = explode('.', $logourl);
            $ext = array_pop($t);
            return implode('.', $t) . \mkw\store::getParameter(\mkw\consts::Bigimgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function setLogourl($logo)
    {
        $this->logourl = $logo;
        if (!$logo) {
            $this->setLogoleiras(null);
        }
    }

    public function getLogoleiras()
    {
        return $this->logoleiras;
    }

    public function setLogoleiras($logoleiras)
    {
        $this->logoleiras = $logoleiras;
    }

    public function getLeiras()
    {
        return $this->leiras;
    }

    public function setLeiras($leiras)
    {
        $this->leiras = $leiras;
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

    public function setKepurl($kep)
    {
        $this->kepurl = $kep;
        if (!$kep) {
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
}
