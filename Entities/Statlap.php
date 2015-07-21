<?php

namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\StatlapRepository")
 * @ORM\Table(name="statlap")
 */
class Statlap {

    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    private $oldalcim;

    /**
     * @Gedmo\Slug(fields={"oldalcim"})
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    private $slug;

    /** @ORM\Column(type="text",nullable=true) */
    private $szoveg;

    /** @ORM\Column(type="text",nullable=true) */
    private $seodescription;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $created;

    /**
     * @Gedmo\Timestampable(on="create")
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $lastmod;

    /** @ORM\Column(type="string", length=255, nullable=true) */
    private $oldurl;

    public function getId() {
        return $this->id;
    }

    public function getOldalcim() {
        return $this->oldalcim;
    }

    public function getShowOldalcim() {
        return $this->oldalcim . ' - ' . \mkw\Store::getParameter(\mkw\consts::Oldalcim);
    }

    public function setOldalcim($adat) {
        $this->oldalcim = $adat;
    }

    public function getSlug() {
        return $this->slug;
    }

    public function setSlug($adat) {
        $this->slug = $adat;
    }

    public function getSzoveg() {
        return $this->szoveg;
    }

    public function setSzoveg($adat) {
        $this->szoveg = $adat;
    }

    public function getSeodescription() {
        return $this->seodescription;
    }

    public function getShowSeodescription() {
        if ($this->seodescription) {
            return $this->seodescription;
        }
        return $this->oldalcim . ' - ' . \mkw\Store::getParameter(\mkw\consts::Seodescription);
    }

    public function setSeodescription($adat) {
        $this->seodescription = $adat;
    }

    public function getLastmod() {
        return $this->lastmod;
    }

    public function getCreated() {
        return $this->created;
    }

    public function getOldurl() {
        return $this->oldurl;
    }

    public function setOldurl($u) {
        $this->oldurl = $u;
    }

}
