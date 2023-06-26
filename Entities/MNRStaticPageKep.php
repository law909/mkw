<?php

namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use mkw\store;

/**
 * @ORM\Entity(repositoryClass="Entities\MNRStaticPageKepRepository")
 * @ORM\Table(name="mnrstaticpagekep",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class MNRStaticPageKep
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
     * @ORM\ManyToOne(targetEntity="MNRStaticPage",inversedBy="mnrstaticpagekepek")
     * @ORM\JoinColumn(name="mnrstaticpage_id",referencedColumnName="id",onDelete="cascade")
     */
    private $mnrstaticpage;
    /** @ORM\Column(type="text",nullable=true) */
    private $url;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $rejtett = false;

    public function getId()
    {
        return $this->id;
    }

    public function getMnrstaticpage()
    {
        return $this->mnrstaticpage;
    }

    public function getMnrStaticPageNev()
    {
        if ($this->mnrstaticpage) {
            return $this->mnrstaticpage->getNev();
        }
        return '';
    }

    public function setMnrstaticpage(MNRStaticPage $mnrstaticpage)
    {
        $this->mnrstaticpage = $mnrstaticpage;
//		$termek->addTermekKep($this);
    }

    public function removeMnrstaticpage()
    {
        if ($this->mnrstaticpage !== null) {
            $page = $this->mnrstaticpage;
            $this->mnrstaticpage = null;
            $page->removeMnrstaticpageKep($this);
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