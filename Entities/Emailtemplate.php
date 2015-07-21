<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\EmailtemplateRepository")
 * @ORM\Table(name="emailtemplate")
 */
class Emailtemplate {

    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string",length=255)
     */
    private $nev;

    /**
     * @ORM\Column(type="string",length=255)
     */
    private $targy;

    /** @ORM\Column(type="text",nullable=true) */
    private $szoveg;
	/** @ORM\OneToMany(targetEntity="Bizonylatstatusz", mappedBy="emailtemplate",cascade={"persist"}) */
	private $bizonylatstatuszok;

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
//        return html_entity_decode($this->szoveg, ENT_QUOTES || ENT_HTML5, 'UTF-8');
        return $this->szoveg;
    }

    public function getHTMLSzoveg() {
        return
            '<html>'.
            '<head>'.
            '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">'.
            '</head>'.
            '<body>' . $this->getSzoveg() . '</body>'.
            '</html>';
    }

    public function setSzoveg($adat) {
        $this->szoveg = $adat;
    }

    public function getTargy() {
//        return html_entity_decode($this->targy, ENT_QUOTES || ENT_HTML5, 'UTF-8');
        return $this->targy;
    }

    public function setTargy($t) {
        $this->targy = $t;
    }

}
