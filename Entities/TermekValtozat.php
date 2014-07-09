<?php
namespace Entities;

/**
 * @Entity(repositoryClass="Entities\TermekValtozatRepository")
 * @Table(name="termekvaltozat")
*/
class TermekValtozat {
	/**
	 * @Id @Column(type="integer")
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
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
	/**
	 * @ManyToOne(targetEntity="Termek",inversedBy="valtozatok")
	 * @JoinColumn(name="termek_id",referencedColumnName="id",onDelete="cascade")
	 */
	private $termek;
	/** @Column(type="boolean") */
	private $lathato=true;
	/** @Column(type="boolean") */
	private $elerheto=true;
	/** @Column(type="boolean") */
	private $termekfokep=false;
	/**
	 * @ManyToOne(targetEntity="TermekValtozatAdatTipus",inversedBy="valtozatok1")
	 * @JoinColumn(name="adattipus1_id",referencedColumnName="id",onDelete="no action")
	 */
	private $adattipus1;
	/**
	 * @Column(type="string",length=255,nullable=true)
	 */
	private $ertek1;
	/**
	 * @ManyToOne(targetEntity="TermekValtozatAdatTipus",inversedBy="valtozatok2")
	 * @JoinColumn(name="adattipus2_id",referencedColumnName="id",onDelete="no action")
	 */
	private $adattipus2;
	/**
	 * @Column(type="string",length=255,nullable=true)
	 */
	private $ertek2;
	/** @OneToMany(targetEntity="Kosar", mappedBy="termekvaltozat",cascade={"persist","remove"}) */
	private $kosarak;
	/**
	 * @Column(type="decimal",precision=14,scale=4,nullable=true)
	 */
	private $netto=0;
	/**
	 * @Column(type="decimal",precision=14,scale=4,nullable=true)
	 */
	private $brutto=0;
	/**
	 * @ManyToOne(targetEntity="TermekKep",inversedBy="valtozatok")
	 * @JoinColumn(name="termekkep_id",referencedColumnName="id",nullable=true,onDelete="restrict",onUpdate="cascade")
	 */
	private $kep;
	/** @Column(type="string",length=50,nullable=true) */
	private $cikkszam='';
	/** @Column(type="string",length=50,nullable=true) */
	private $idegencikkszam='';
    /** @OneToMany(targetEntity="Bizonylattetel", mappedBy="termekvaltozat",cascade={"persist","remove"}) */
    private $bizonylattetelek;

	public function __construct() {
		$this->kosarak=new \Doctrine\Common\Collections\ArrayCollection();
        $this->bizonylattetelek = new \Doctrine\Common\Collections\ArrayCollection();
	}

    public function getKeszlet() {
        $k = 0;
        foreach($this->bizonylattetelek as $bt) {
            if ($bt->getMozgat()) {
                $k += ($bt->getMennyiseg() * $bt->getIrany());
            }
        }
        return $k;
    }

	public function getId() {
		return $this->id;
	}

	public function getTermek() {
		return $this->termek;
	}

	public function setTermek($termek) {
		$this->termek = $termek;
//		$termek->addValtozat($this);
	}

	public function getLathato() {
		return $this->lathato;
	}

	public function setLathato($lathato) {
		$this->lathato=$lathato;
	}

	public function getElerheto() {
		return $this->elerheto;
	}

	public function setElerheto($elerheto) {
		$this->elerheto=$elerheto;
	}

	public function getTermekfokep() {
		return $this->termekfokep;
	}

	public function setTermekfokep($adat) {
		$this->termekfokep = $adat;
	}

	public function getAdatTipus1() {
		return $this->adattipus1;
	}

	public function getAdatTipus1Id() {
		if ($this->adattipus1) {
			return $this->adattipus1->getId();
		}
		return 0;
	}

	public function getAdatTipus1Nev() {
		if ($this->adattipus1) {
			return $this->adattipus1->getNev();
		}
		return '';
	}

	public function setAdatTipus1($at) {
		$this->adattipus1=$at;
//		$at->addValtozat($this);
	}

	public function getErtek1() {
		return $this->ertek1;
	}

	public function setErtek1($ertek) {
		$this->ertek1=$ertek;
	}

	public function getAdatTipus2() {
		return $this->adattipus2;
	}

	public function getAdatTipus2Id() {
		if ($this->adattipus2) {
			return $this->adattipus2->getId();
		}
		return 0;
	}

	public function getAdatTipus2Nev() {
		if ($this->adattipus2) {
			return $this->adattipus2->getNev();
		}
		return '';
	}

	public function setAdatTipus2($at) {
		$this->adattipus2=$at;
//		$at->addValtozat($this);
	}

	public function getErtek2() {
		return $this->ertek2;
	}

	public function setErtek2($ertek) {
		$this->ertek2=$ertek;
	}

	public function getLastmod() {
		return $this->lastmod;
	}

	public function getCreated() {
		return $this->created;
	}

	public function getNetto() {
		return $this->netto;
	}

	public function setNetto($netto) {
		$this->netto=$netto;
		$this->brutto=$this->termek->getAfa()->calcBrutto($netto);
	}

	public function getBrutto() {
		return $this->brutto;
	}

	public function setBrutto($brutto) {
		$this->brutto=$brutto;
		$this->netto=$this->termek->getAfa()->calcNetto($brutto);
	}

	public function getKepurl($pre='/')	{
		if (!$this->termekfokep) {
			if ($this->getKep()) {
				return $this->getKep()->getUrl($pre);
			}
		}
		else {
			if ($this->getTermek()) {
				return $this->getTermek()->getKepurl($pre);
			}
		}
		return '';
	}

	public function getKepurlMini($pre='/') {
		if (!$this->termekfokep) {
			if ($this->getKep()) {
				return $this->getKep()->getUrlMini($pre);
			}
		}
		else {
			if ($this->getTermek()) {
				return $this->getTermek()->getKepurlMini($pre);
			}
		}
		return '';
	}

	public function getKepurlSmall($pre='/') {
		if (!$this->termekfokep) {
			if ($this->getKep()) {
				return $this->getKep()->getUrlSmall($pre);
			}
		}
		else {
			if ($this->getTermek()) {
				return $this->getTermek()->getKepurlSmall($pre);
			}
		}
		return '';
	}

	public function getKepurlMedium($pre='/') {
		if (!$this->termekfokep) {
			if ($this->getKep()) {
				return $this->getKep()->getUrlMedium($pre);
			}
		}
		else {
			if ($this->getTermek()) {
				return $this->getTermek()->getKepurlMedium($pre);
			}
		}
		return '';
	}

	public function getKepurlLarge($pre='/') {
		if (!$this->termekfokep) {
			if ($this->getKep()) {
				return $this->getKep()->getUrlLarge($pre);
			}
		}
		else {
			if ($this->getTermek()) {
				return $this->getTermek()->getKepurlLarge($pre);
			}
		}
		return '';
	}

	public function getKepleiras() {
		if (!$this->termekfokep) {
			if ($this->getKep()) {
				return $this->getKep()->getLeiras();
			}
		}
		else {
			if ($this->getTermek()) {
				return $this->getTermek()->getKepleiras();
			}
		}
		return '';
	}

	public function getKep() {
		if ($this->termekfokep) {
			return false;
		}
		return $this->kep;
	}

	public function getKepId() {
		if (!$this->termekfokep&&$this->getKep()) {
			return $this->kep->getId();
		}
		return '';
	}

	public function setKep($kep) {
		$this->kep = $kep;
//		$kep->addValtozat($this);
	}

	public function getIdegencikkszam() {
		return $this->idegencikkszam;
	}

	public function setIdegencikkszam($idegencikkszam) {
		$this->idegencikkszam = $idegencikkszam;
	}

	public function getCikkszam() {
		return $this->cikkszam;
	}

	public function setCikkszam($cikkszam) {
		$this->cikkszam = $cikkszam;
	}

	public function getNev() {
		return $this->getErtek1().' - '.$this->getErtek2();
	}
}