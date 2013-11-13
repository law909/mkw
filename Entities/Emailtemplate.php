<?php

namespace Entities;

/**
 * @Entity(repositoryClass="Entities\EmailtemplateRepository")
 * @Table(name="emailtemplate")
 */
class Emailtemplate {

    /**
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Column(type="string",length=255)
     */
    private $nev;

    /**
     * @Column(type="string",length=255)
     */
    private $almafa;

    /**
     * @Column(type="string",length=255)
     */
    private $targy;

    /** @Column(type="text",nullable=true) */
    private $szoveg;

    public function getId() {
        return $this->id;
    }

    public function getNev() {
        return $this->nev;
    }

    public function setNev($nev) {
        $this->nev = $nev;
    }

    public function getSzoveg() {
        return $this->szoveg;
    }

    public function setSzoveg($adat) {
        $this->szoveg = $adat;
    }

    public function getTargy() {
        return $this->targy;
    }

    public function setTargy($t) {
        $this->targy = $t;
    }

}
