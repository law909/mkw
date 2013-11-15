<?php

namespace Entities;

/**
 * @Entity(repositoryClass="Entities\StatlapRepository")
 * @Table(name="statlap")
 */
class Statlap {

    /**
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @gedmo:Sluggable
     * @Column(type="string",length=255,nullable=true)
     */
    private $oldalcim;

    /**
     * @gedmo:Slug
     * @Column(type="string",length=255,nullable=true)
     */
    private $slug;

    /** @Column(type="text",nullable=true) */
    private $szoveg;

    /** @Column(type="text",nullable=true) */
    private $seodescription;

    /**
     * @gedmo:Timestampable(on="create")
     * @Column(type="datetime",nullable=true)
     */
    private $created;

    /**
     * @gedmo:Timestampable(on="create")
     * @gedmo:Timestampable(on="update")
     * @Column(type="datetime",nullable=true)
     */
    private $lastmod;
    
    /** @Column(type="string", length=255, nullable=true) */
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
