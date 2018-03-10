<?php

namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use mkw\store;

/**
 * @ORM\Entity
 * @ORM\Table(name="dokumentumtar",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="osztaly", type="string", length=30)
 * @ORM\DiscriminatorMap({"rendezveny"="RendezvenyDok"})
 */
abstract class Dokumentumtar {
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
    /** @ORM\Column(type="text",nullable=true) */
    private $url;
    /** @ORM\Column(type="text",nullable=true) */
    private $path;
    /** @ORM\Column(type="text",nullable=true) */
    private $leiras;

    public function getId() {
        return $this->id;
    }

    public function getUrl() {
        if ($this->url) {
            return $this->url;
        }
        return '';
    }

    public function setUrl($url) {
        $this->url = \mkw\store::addHttp($url);
        if (!$url) {
            $this->setLeiras(null);
        }
    }

    public function getLeiras() {
        return $this->leiras;
    }

    public function setLeiras($leiras) {
        $this->leiras = $leiras;
    }

    public function getLastmod() {
        return $this->lastmod;
    }

    public function getCreated() {
        return $this->created;
    }

    /**
     * @return mixed
     */
    public function getPath() {
        return $this->path;
    }

    /**
     * @param mixed $path
     */
    public function setPath($path) {
        $this->path = $path;
    }

}