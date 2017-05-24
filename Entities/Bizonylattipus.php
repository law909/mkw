<?php
namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity(repositoryClass="Entities\BizonylattipusRepository")
 * @ORM\Table(name="bizonylattipus",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 **/
class Bizonylattipus {
    /**
     * @ORM\Id @ORM\Column(type="string",length=30)
     */
    private $id;
    /** @ORM\Column(type="string",length=100) */
    private $nev;
    /** @ORM\Column(type="integer") */
    private $irany = -1;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $nyomtatni = true;
    /** @ORM\Column(type="string",length=10,nullable=true) */
    private $azonosito;
    /** @ORM\Column(type="integer") */
    private $kezdosorszam = 0;
    /** @ORM\Column(type="integer") */
    private $peldanyszam = 1;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $mozgat = true;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $foglal = false;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $penztmozgat = true;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $editprinted = false;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $showteljesites = false;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $showesedekesseg = false;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $showhatarido = false;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $showbizonylatstatuszeditor = false;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $showszamlabutton = false;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $showszallitobutton = false;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $showkivetbutton = false;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $showkeziszamlabutton = false;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $showbevetbutton = false;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $showuzenet = false;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $showszallitasicim = false;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $showerbizonylatszam = false;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $showfuvarlevelszam = false;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $showhaszonszazalek = false;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $showstorno = false;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $showbackorder = false;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $showmesebutton = false;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $showcsomagbutton = false;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $showfeketelistabutton = false;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $showkupon = false;
    /** @ORM\OneToMany(targetEntity="Bizonylatfej", mappedBy="bizonylattipus",cascade={"persist"}) */
    private $bizonylatfejek;
    /** @ORM\Column(type="string",length=200,nullable=true) */
    private $tplname;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $showfoxpostterminaleditor = false;
    /** @ORM\Column(type="boolean",nullable=false) */
    private $showfelhasznalo = false;

    public function __construct() {
        $this->bizonylatfejek = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function setTemplateVars($view) {
        $view->setVar('showteljesites', $this->getShowteljesites());
        $view->setVar('showesedekesseg', $this->getShowesedekesseg());
        $view->setVar('showhatarido', $this->getShowhatarido());
        $view->setVar('showvalutanem', \mkw\store::isMultiValuta());
        $view->setVar('showbizonylatstatuszeditor', $this->getShowbizonylatstatuszeditor());
        $view->setVar('showszamlabutton', $this->getShowszamlabutton());
        $view->setVar('showkeziszamlabutton', $this->getShowkeziszamlabutton());
        $view->setVar('showszallitobutton', $this->getShowszallitobutton());
        $view->setVar('showkivetbutton', $this->getShowkivetbutton());
        $view->setVar('showbevetbutton', $this->getShowbevetbutton());
        $view->setVar('showuzenet', $this->getShowuzenet());
        $view->setVar('showszallitasicim', $this->getShowszallitasicim());
        $view->setVar('showerbizonylatszam', $this->getShowerbizonylatszam());
        $view->setVar('showfuvarlevelszam', $this->getShowfuvarlevelszam());
        $view->setVar('showhaszonszazalek', $this->getShowhaszonszazalek());
        $view->setVar('showstorno', $this->getShowstorno());
        $view->setVar('showbackorder', $this->getShowbackorder());
        $view->setVar('showmesebutton', $this->getShowmesebutton());
        $view->setVar('showcsomagbutton', $this->getShowcsomagbutton());
        $view->setVar('showfeketelistabutton', $this->getShowfeketelistabutton());
        $view->setVar('showkupon', $this->getShowkupon());
        $view->setVar('showfoxpostterminaleditor', $this->getShowfoxpostterminaleditor());
        $view->setVar('showfelhasznalo', $this->getShowfelhasznalo());
    }

    public function getId() {
        return $this->id;
    }

    public function getNev() {
        return $this->nev;
    }

    public function setNev($val) {
        $this->nev = $val;
    }

    public function getIrany() {
        return $this->irany;
    }

    public function setIrany($val) {
        $this->irany = $val;
    }

    public function getNyomtatni() {
        return $this->nyomtatni;
    }

    public function setNyomtatni($val) {
        $this->nyomtatni = $val;
    }

    public function getAzonosito() {
        return $this->azonosito;
    }

    public function setAzonosito($val) {
        $this->azonosito = $val;
    }

    public function getKezdosorszam() {
        return $this->kezdosorszam;
    }

    public function setKezdosorszam($val) {
        $this->kezdosorszam = $val;
    }

    public function getPeldanyszam() {
        return $this->peldanyszam;
    }

    public function setPeldanyszam($val) {
        $this->peldanyszam = $val;
    }

    public function getMozgat() {
        return $this->mozgat;
    }

    public function setMozgat($val) {
        $this->mozgat = $val;
    }

    public function getPenztmozgat() {
        return $this->penztmozgat;
    }

    public function setPenztmozgat($val) {
        $this->penztmozgat = $val;
    }

    public function getEditprinted() {
        return $this->editprinted;
    }

    public function setEditprinted($val) {
        $this->editprinted = $val;
    }

    public function getShowteljesites() {
        return $this->showteljesites;
    }

    public function setShowteljesites($show) {
        $this->showteljesites = $show;
    }

    public function getShowesedekesseg() {
        return $this->showesedekesseg;
    }

    public function setShowesedekesseg($show) {
        $this->showesedekesseg = $show;
    }

    public function getShowhatarido() {
        return $this->showhatarido;
    }

    public function setShowhatarido($show) {
        $this->showhatarido = $show;
    }

    public function getShowvalutanem() {
        return \mkw\store::isMultiValuta();
    }

    public function getTplname() {
        return $this->tplname;
    }

    public function setTplname($d) {
        $this->tplname = $d;
    }

    public function getShowbizonylatstatuszeditor() {
        return $this->showbizonylatstatuszeditor;
    }

    public function setShowbizonylatstatuszeditor($val) {
        $this->showbizonylatstatuszeditor = $val;
    }

    public function getShowszamlabutton() {
        return $this->showszamlabutton;
    }

    public function setShowszamlabutton($val) {
        $this->showszamlabutton = $val;
    }

    public function getShowkeziszamlabutton() {
        return $this->showkeziszamlabutton;
    }

    public function setShowkeziszamlabutton($val) {
        $this->showkeziszamlabutton = $val;
    }

    public function getShowkivetbutton() {
        return $this->showkivetbutton;
    }

    public function setShowkivetbutton($val) {
        $this->showkivetbutton = $val;
    }

    public function getShowbevetbutton() {
        return $this->showbevetbutton;
    }

    public function setShowbevetbutton($val) {
        $this->showbevetbutton = $val;
    }

    public function getShowszallitobutton() {
        return $this->showszallitobutton;
    }

    public function setShowszallitobtn($val) {
        $this->showszallitobutton = $val;
    }

    public function getShowuzenet() {
        return $this->showuzenet;
    }

    public function setShowuzenet($val) {
        $this->showuzenet = $val;
    }

    public function getShowszallitasicim() {
        return $this->showszallitasicim;
    }

    public function setShowszallitasicim($val) {
        $this->showszallitasicim = $val;
    }

    public function getShowerbizonylatszam() {
        return $this->showerbizonylatszam;
    }

    public function setShowerbizonylatszam($val) {
        $this->showerbizonylatszam = $val;
    }

    public function getShowfuvarlevelszam() {
        return $this->showfuvarlevelszam;
    }

    public function setShowfuvarlevelszam($val) {
        $this->showfuvarlevelszam = $val;
    }

    public function getShowhaszonszazalek() {
        return $this->showhaszonszazalek;
    }

    public function setShowhaszonszazalek($val) {
        $this->showhaszonszazalek = $val;
    }

    public function getShowstorno() {
        return $this->showstorno;
    }

    public function setShowstorno($adat) {
        $this->showstorno = $adat;
    }

    public function getShowbackorder() {
        return $this->showbackorder;
    }

    public function setShowbackorder($adat) {
        $this->showbackorder = $adat;
    }

    public function getFoglal() {
        return $this->foglal;
    }

    public function setFoglal($adat) {
        $this->foglal = $adat;
    }

    /**
     * @return mixed
     */
    public function getShowmesebutton() {
        return $this->showmesebutton;
    }

    /**
     * @param mixed $showmesebutton
     */
    public function setShowmesebutton($showmesebutton) {
        $this->showmesebutton = $showmesebutton;
    }

    /**
     * @return mixed
     */
    public function getShowcsomagbutton() {
        return $this->showcsomagbutton;
    }

    /**
     * @param mixed $showcsomagbutton
     */
    public function setShowcsomagbutton($showcsomagbutton) {
        $this->showcsomagbutton = $showcsomagbutton;
    }

    /**
     * @return mixed
     */
    public function getShowfeketelistabutton() {
        return $this->showfeketelistabutton;
    }

    /**
     * @param mixed $showfeketelistabutton
     */
    public function setShowfeketelistabutton($showfeketelistabutton) {
        $this->showfeketelistabutton = $showfeketelistabutton;
    }

    /**
     * @return mixed
     */
    public function getShowkupon() {
        return $this->showkupon;
    }

    /**
     * @param mixed $showkupon
     */
    public function setShowkupon($showkupon) {
        $this->showkupon = $showkupon;
    }

    /**
     * @return mixed
     */
    public function getShowfoxpostterminaleditor() {
        return $this->showfoxpostterminaleditor;
    }

    /**
     * @param mixed $showfoxpostterminaleditor
     */
    public function setShowfoxpostterminaleditor($showfoxpostterminaleditor) {
        $this->showfoxpostterminaleditor = $showfoxpostterminaleditor;
    }

    /**
     * @return mixed
     */
    public function getShowfelhasznalo() {
        return $this->showfelhasznalo;
    }

    /**
     * @param mixed $showfelhasznalo
     */
    public function setShowfelhasznalo($showfelhasznalo) {
        $this->showfelhasznalo = $showfelhasznalo;
    }

}
