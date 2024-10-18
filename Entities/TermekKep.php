<?php
// TODO wordpress
namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use mkw\store;

/**
 * @ORM\Entity(repositoryClass="Entities\TermekKepRepository")
 * @ORM\Table(name="termekkep",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class TermekKep
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
     * @ORM\ManyToOne(targetEntity="Termek",inversedBy="termekkepek")
     * @ORM\JoinColumn(name="termek_id",referencedColumnName="id",onDelete="cascade")
     */
    private $termek;
    /** @ORM\Column(type="text",nullable=true) */
    private $url;
    /** @ORM\Column(type="text",nullable=true) */
    private $leiras;
    /** @ORM\OneToMany(targetEntity="TermekValtozat",mappedBy="kep",cascade={"persist"}) */
    private $valtozatok;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $rejtett = false;
    /** @ORM\Column(type="integer", nullable=true) */
    private $wcid;
    /** @ORM\Column(type="datetime", nullable=true) */
    private $wcdate;

    public function getId()
    {
        return $this->id;
    }

    public function getTermek()
    {
        return $this->termek;
    }

    public function getTermekNev()
    {
        if ($this->termek) {
            return $this->termek->getNev();
        }
        return '';
    }

    public function setTermek(Termek $termek)
    {
        $this->termek = $termek;
//		$termek->addTermekKep($this);
    }

    public function removeTermek()
    {
        if ($this->termek !== null) {
            $termek = $this->termek;
            $this->termek = null;
            $termek->removeTermekKep($this);
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