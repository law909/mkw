<?php

namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\VersenyzoRepository")
 * @ORM\Table(name="versenyzo",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class Versenyzo
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
    private $versenysorozat;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $rovidleiras;

    /** @ORM\Column(type="text",nullable=true) */
    private $leiras;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $kepurl;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $kepleiras;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $kepurl1;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $kepleiras1;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $kepurl2;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $kepleiras2;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $kepurl3;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $kepleiras3;

    /**
     * @ORM\ManyToOne(targetEntity="Csapat", inversedBy="versenyzok")
     * @ORM\JoinColumn(name="csapat_id", referencedColumnName="id",nullable=true,onDelete="set null")
     */
    private $csapat;

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

    public function getVersenysorozat()
    {
        return $this->versenysorozat;
    }

    public function setVersenysorozat($versenysorozat)
    {
        $this->versenysorozat = $versenysorozat;
    }

    public function getRovidleiras()
    {
        return $this->rovidleiras;
    }

    public function setRovidleiras($rovidleiras)
    {
        $this->rovidleiras = $rovidleiras;
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

    public function getKepurl400($pre = '/')
    {
        $kepurl = $this->getKepurl($pre);
        if ($kepurl) {
            $t = explode('.', $kepurl);
            $ext = array_pop($t);
            return implode('.', $t) . \mkw\store::getParameter(\mkw\consts::I400imgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function getKepurl2000($pre = '/')
    {
        $kepurl = $this->getKepurl($pre);
        if ($kepurl) {
            $t = explode('.', $kepurl);
            $ext = array_pop($t);
            return implode('.', $t) . \mkw\store::getParameter(\mkw\consts::I2000imgpost, '') . '.' . $ext;
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

    public function getKepurl1($pre = '/')
    {
        if ($this->kepurl1) {
            if ($this->kepurl1[0] !== $pre) {
                return $pre . $this->kepurl1;
            } else {
                return $this->kepurl1;
            }
        }
        return '';
    }

    public function getKepurl1Mini($pre = '/')
    {
        $kepurl = $this->getKepurl1($pre);
        if ($kepurl) {
            $t = explode('.', $kepurl);
            $ext = array_pop($t);
            return implode('.', $t) . \mkw\store::getParameter(\mkw\consts::Miniimgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function getKepurl1Small($pre = '/')
    {
        $kepurl = $this->getKepurl1($pre);
        if ($kepurl) {
            $t = explode('.', $kepurl);
            $ext = array_pop($t);
            return implode('.', $t) . \mkw\store::getParameter(\mkw\consts::Smallimgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function getKepurl1Medium($pre = '/')
    {
        $kepurl = $this->getKepurl1($pre);
        if ($kepurl) {
            $t = explode('.', $kepurl);
            $ext = array_pop($t);
            return implode('.', $t) . \mkw\store::getParameter(\mkw\consts::Mediumimgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function getKepurl1Large($pre = '/')
    {
        $kepurl = $this->getKepurl1($pre);
        if ($kepurl) {
            $t = explode('.', $kepurl);
            $ext = array_pop($t);
            return implode('.', $t) . \mkw\store::getParameter(\mkw\consts::Bigimgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function getKepurl1400($pre = '/')
    {
        $kepurl = $this->getKepurl1($pre);
        if ($kepurl) {
            $t = explode('.', $kepurl);
            $ext = array_pop($t);
            return implode('.', $t) . \mkw\store::getParameter(\mkw\consts::I400imgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function getKepurl12000($pre = '/')
    {
        $kepurl = $this->getKepurl1($pre);
        if ($kepurl) {
            $t = explode('.', $kepurl);
            $ext = array_pop($t);
            return implode('.', $t) . \mkw\store::getParameter(\mkw\consts::I2000imgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function setKepurl1($kep)
    {
        $this->kepurl1 = $kep;
        if (!$kep) {
            $this->setKepleiras1(null);
        }
    }

    public function getKepleiras1()
    {
        return $this->kepleiras1;
    }

    public function setKepleiras1($kepleiras)
    {
        $this->kepleiras1 = $kepleiras;
    }

    public function getKepurl2($pre = '/')
    {
        if ($this->kepurl2) {
            if ($this->kepurl2[0] !== $pre) {
                return $pre . $this->kepurl2;
            } else {
                return $this->kepurl2;
            }
        }
        return '';
    }

    public function getKepurl2Mini($pre = '/')
    {
        $kepurl = $this->getKepurl2($pre);
        if ($kepurl) {
            $t = explode('.', $kepurl);
            $ext = array_pop($t);
            return implode('.', $t) . \mkw\store::getParameter(\mkw\consts::Miniimgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function getKepurl2Small($pre = '/')
    {
        $kepurl = $this->getKepurl2($pre);
        if ($kepurl) {
            $t = explode('.', $kepurl);
            $ext = array_pop($t);
            return implode('.', $t) . \mkw\store::getParameter(\mkw\consts::Smallimgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function getKepurl2Medium($pre = '/')
    {
        $kepurl = $this->getKepurl2($pre);
        if ($kepurl) {
            $t = explode('.', $kepurl);
            $ext = array_pop($t);
            return implode('.', $t) . \mkw\store::getParameter(\mkw\consts::Mediumimgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function getKepurl2Large($pre = '/')
    {
        $kepurl = $this->getKepurl2($pre);
        if ($kepurl) {
            $t = explode('.', $kepurl);
            $ext = array_pop($t);
            return implode('.', $t) . \mkw\store::getParameter(\mkw\consts::Bigimgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function getKepurl2400($pre = '/')
    {
        $kepurl = $this->getKepurl2($pre);
        if ($kepurl) {
            $t = explode('.', $kepurl);
            $ext = array_pop($t);
            return implode('.', $t) . \mkw\store::getParameter(\mkw\consts::I400imgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function getKepurl22000($pre = '/')
    {
        $kepurl = $this->getKepurl2($pre);
        if ($kepurl) {
            $t = explode('.', $kepurl);
            $ext = array_pop($t);
            return implode('.', $t) . \mkw\store::getParameter(\mkw\consts::I2000imgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function setKepurl2($kep)
    {
        $this->kepurl2 = $kep;
        if (!$kep) {
            $this->setKepleiras2(null);
        }
    }

    public function getKepleiras2()
    {
        return $this->kepleiras2;
    }

    public function setKepleiras2($kepleiras)
    {
        $this->kepleiras2 = $kepleiras;
    }

    public function getKepurl3($pre = '/')
    {
        if ($this->kepurl3) {
            if ($this->kepurl3[0] !== $pre) {
                return $pre . $this->kepurl3;
            } else {
                return $this->kepurl3;
            }
        }
        return '';
    }

    public function getKepurl3Mini($pre = '/')
    {
        $kepurl = $this->getKepurl3($pre);
        if ($kepurl) {
            $t = explode('.', $kepurl);
            $ext = array_pop($t);
            return implode('.', $t) . \mkw\store::getParameter(\mkw\consts::Miniimgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function getKepurl3Small($pre = '/')
    {
        $kepurl = $this->getKepurl3($pre);
        if ($kepurl) {
            $t = explode('.', $kepurl);
            $ext = array_pop($t);
            return implode('.', $t) . \mkw\store::getParameter(\mkw\consts::Smallimgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function getKepurl3Medium($pre = '/')
    {
        $kepurl = $this->getKepurl3($pre);
        if ($kepurl) {
            $t = explode('.', $kepurl);
            $ext = array_pop($t);
            return implode('.', $t) . \mkw\store::getParameter(\mkw\consts::Mediumimgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function getKepurl3Large($pre = '/')
    {
        $kepurl = $this->getKepurl3($pre);
        if ($kepurl) {
            $t = explode('.', $kepurl);
            $ext = array_pop($t);
            return implode('.', $t) . \mkw\store::getParameter(\mkw\consts::Bigimgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function getKepurl3400($pre = '/')
    {
        $kepurl = $this->getKepurl3($pre);
        if ($kepurl) {
            $t = explode('.', $kepurl);
            $ext = array_pop($t);
            return implode('.', $t) . \mkw\store::getParameter(\mkw\consts::I400imgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function getKepurl32000($pre = '/')
    {
        $kepurl = $this->getKepurl3($pre);
        if ($kepurl) {
            $t = explode('.', $kepurl);
            $ext = array_pop($t);
            return implode('.', $t) . \mkw\store::getParameter(\mkw\consts::I2000imgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function setKepurl3($kep)
    {
        $this->kepurl3 = $kep;
        if (!$kep) {
            $this->setKepleiras3(null);
        }
    }

    public function getKepleiras3()
    {
        return $this->kepleiras3;
    }

    public function setKepleiras3($kepleiras)
    {
        $this->kepleiras3 = $kepleiras;
    }

    public function getCsapat()
    {
        return $this->csapat;
    }

    public function getCsapatNev()
    {
        if ($this->csapat) {
            return $this->csapat->getNev();
        }
        return '';
    }

    public function getCsapatId()
    {
        if ($this->csapat) {
            return $this->csapat->getId();
        }
        return 0;
    }

    public function setCsapat($csapat)
    {
        $this->csapat = $csapat;
    }
}
