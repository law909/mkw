<?php
// TODO wordpress
namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use mkw\store;

/**
 * @ORM\Entity(repositoryClass="Entities\CsapatKepRepository")
 * @ORM\Table(name="csapatkep",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class CsapatKep
{
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
     * @ORM\ManyToOne(targetEntity="Csapat",inversedBy="csapatkepek")
     * @ORM\JoinColumn(name="csapat_id",referencedColumnName="id",onDelete="cascade")
     */
    private $csapat;
    /** @ORM\Column(type="text",nullable=true) */
    private $url;
    /** @ORM\Column(type="text",nullable=true) */
    private $leiras;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $rejtett = false;

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Csapat
     */
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

    public function setCsapat(Csapat $csapat)
    {
        $this->csapat = $csapat;
//		$termek->addTermekKep($this);
    }

    public function removeCsapat()
    {
        if ($this->csapat !== null) {
            $csapat = $this->csapat;
            $this->csapat = null;
            $csapat->removeCsapatKep($this);
        }
    }

    public function getUrl($pre = '/')
    {
        if ($this->url) {
            if ($this->url[0] !== $pre) {
                return $pre . $this->url;
            } else {
                return $this->url;
            }
        }
        return '';
    }

    public function getUrlMini($pre = '/')
    {
        $url = $this->getUrl($pre);
        if ($url) {
            $t = explode('.', $url);
            $ext = array_pop($t);
            return implode('.', $t) . store::getParameter(\mkw\consts::Miniimgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function getUrl400($pre = '/')
    {
        $url = $this->getUrl($pre);
        if ($url) {
            $t = explode('.', $url);
            $ext = array_pop($t);
            return implode('.', $t) . store::getParameter(\mkw\consts::I400imgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function getUrl2000($pre = '/')
    {
        $url = $this->getUrl($pre);
        if ($url) {
            $t = explode('.', $url);
            $ext = array_pop($t);
            return implode('.', $t) . store::getParameter(\mkw\consts::I2000imgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function getUrlSmall($pre = '/')
    {
        $url = $this->getUrl($pre);
        if ($url) {
            $t = explode('.', $url);
            $ext = array_pop($t);
            return implode('.', $t) . store::getParameter(\mkw\consts::Smallimgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function getUrlMedium($pre = '/')
    {
        $url = $this->getUrl($pre);
        if ($url) {
            $t = explode('.', $url);
            $ext = array_pop($t);
            return implode('.', $t) . store::getParameter(\mkw\consts::Mediumimgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function getUrlLarge($pre = '/')
    {
        $url = $this->getUrl($pre);
        if ($url) {
            $t = explode('.', $url);
            $ext = array_pop($t);
            return implode('.', $t) . store::getParameter(\mkw\consts::Bigimgpost, '') . '.' . $ext;
        }
        return '';
    }

    public function setUrl($url)
    {
        $this->url = $url;
        if (!$url) {
            $this->setLeiras(null);
        }
    }

    public function getLeiras()
    {
        return $this->leiras;
    }

    public function setLeiras($leiras)
    {
        $this->leiras = $leiras;
    }

    public function getLastmod()
    {
        return $this->lastmod;
    }

    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @return mixed
     */
    public function getRejtett()
    {
        return $this->rejtett;
    }

    /**
     * @param mixed $rejtett
     */
    public function setRejtett($rejtett)
    {
        $this->rejtett = $rejtett;
    }

}