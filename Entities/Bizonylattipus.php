<?php
namespace Entities;
use mkw\store;
use Doctrine\Common\Collections\ArrayCollection;

/** @Entity(repositoryClass="Entities\BizonylattipusRepository")
 *  @Table(name="bizonylattipus")
 **/
class Bizonylattipus {
	/**
	 * @Id @Column(type="string",length=30)
	 */
	private $id;
	/** @Column(type="string",length=100) */
	private $nev;
	/** @Column(type="integer") */
	private $irany=-1;
	/** @Column(type="boolean",nullable=false) */
	private $nyomtatni=true;
	/** @Column(type="string",length=10,nullable=true) */
	private $azonosito;
	/** @Column(type="integer") */
	private $kezdosorszam=0;
	/** @Column(type="integer") */
	private $peldanyszam=1;
	/** @Column(type="boolean",nullable=false) */
	private $mozgat=true;
	/** @Column(type="boolean",nullable=false) */
	private $penztmozgat=true;
	/** @Column(type="boolean",nullable=false) */
	private $editprinted=false;
	/** @Column(type="boolean",nullable=false) */
	private $showteljesites=false;
	/** @Column(type="boolean",nullable=false) */
	private $showesedekesseg=false;
	/** @Column(type="boolean",nullable=false) */
	private $showhatarido=false;
	/** @Column(type="boolean",nullable=false) */
	private $showvalutanem=false;
	/** @Column(type="boolean",nullable=false) */
	private $showbizonylatstatuszeditor=false;
	/** @Column(type="boolean",nullable=false) */
	private $showszamlabutton=false;
	/** @Column(type="boolean",nullable=false) */
	private $showszallitobutton=false;
    /** @Column(type="boolean",nullable=false) */
	private $showkivetbutton=false;
	/** @Column(type="boolean",nullable=false) */
	private $showkeziszamlabutton=false;
    /** @Column(type="boolean",nullable=false) */
	private $showuzenet=false;
    /** @Column(type="boolean",nullable=false) */
	private $showszallitasicim=false;
    /** @Column(type="boolean",nullable=false) */
	private $showerbizonylatszam=false;
    /** @Column(type="boolean",nullable=false) */
	private $showfuvarlevelszam=false;
    /** @Column(type="boolean",nullable=false) */
	private $showhaszonszazalek=false;
	/** @OneToMany(targetEntity="Bizonylatfej", mappedBy="bizonylattipus",cascade={"persist"}) */
	private $bizonylatfejek;
    /** @Column(type="string",length="200",nullable=true) */
    private $tplname;

	public function __construct() {
		$this->bizonylatfejek=new \Doctrine\Common\Collections\ArrayCollection();
	}

    public function setTemplateVars($view) {
        $view->setVar('showteljesites', $this->getShowteljesites());
        $view->setVar('showesedekesseg', $this->getShowesedekesseg());
        $view->setVar('showhatarido', $this->getShowhatarido());
        $view->setVar('showvalutanem', $this->getShowvalutanem());
        $view->setVar('showbizonylatstatuszeditor', $this->getShowbizonylatstatuszeditor());
        $view->setVar('showszamlabutton', $this->getShowszamlabutton());
        $view->setVar('showkeziszamlabutton', $this->getShowkeziszamlabutton());
        $view->setVar('showszallitobutton', $this->getShowszallitobutton());
        $view->setVar('showkivetbutton', $this->getShowkivetbutton());
        $view->setVar('showuzenet', $this->getShowuzenet());
        $view->setVar('showszallitasicim', $this->getShowszallitasicim());
        $view->setVar('showerbizonylatszam', $this->getShowerbizonylatszam());
        $view->setVar('showfuvarlevelszam', $this->getShowfuvarlevelszam());
        $view->setVar('showhaszonszazalek', $this->getShowhaszonszazalek());
    }

	public function getId() {
		return $this->id;
	}

	public function getNev() {
		return $this->nev;
	}

	public function setNev($val) {
		$this->nev=$val;
	}

	public function getIrany() {
		return $this->irany;
	}

	public function setIrany($val) {
		$this->irany=$val;
	}

	public function getNyomtatni() {
		return $this->nyomtatni;
	}

	public function setNyomtatni($val) {
		$this->nyomtatni=$val;
	}

	public function getAzonosito() {
		return $this->azonosito;
	}

	public function setAzonosito($val) {
		$this->azonosito=$val;
	}

	public function getKezdosorszam() {
		return $this->kezdosorszam;
	}

	public function setKezdosorszam($val) {
		$this->kezdosorszam=$val;
	}

	public function getPeldanyszam() {
		return $this->peldanyszam;
	}

	public function setPeldanyszam($val) {
		$this->peldanyszam=$val;
	}

	public function getMozgat() {
		return $this->mozgat;
	}

	public function setMozgat($val) {
		$this->mozgat=$val;
	}

	public function getPenztmozgat() {
		return $this->penztmozgat;
	}

	public function setPenztmozgat($val) {
		$this->penztmozgat=$val;
	}

	public function getEditprinted() {
		return $this->editprinted;
	}

	public function setEditprinted($val) {
		$this->editprinted=$val;
	}

	public function getShowteljesites() {
		return $this->showteljesites;
	}

	public function setShowteljesites($show) {
		$this->showteljesites=$show;
	}

	public function getShowesedekesseg() {
		return $this->showesedekesseg;
	}

	public function setShowesedekesseg($show) {
		$this->showesedekesseg=$show;
	}

	public function getShowhatarido() {
		return $this->showhatarido;
	}

	public function setShowhatarido($show) {
		$this->showhatarido=$show;
	}

	public function getShowvalutanem() {
		return $this->showvalutanem;
	}

	public function setShowvalutanem($show) {
		$this->showvalutanem=$show;
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
}
