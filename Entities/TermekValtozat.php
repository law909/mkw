<?php
namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\TermekValtozatRepository")
 * @ORM\Table(name="termekvaltozat",indexes={
 *      @ORM\index(name="termekvaltozatvonalkod_idx",columns={"vonalkod"}),
 *      @ORM\index(name="termekvaltozatidegencikkszam_idx",columns={"idegencikkszam"})
 * })
 * @ORM\HasLifecycleCallbacks
*/
class TermekValtozat {
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
	 * @Gedmo\Timestampable(on="create")
	 * @Gedmo\Timestampable(on="update")
	 * @ORM\Column(type="datetime",nullable=true)
	 */
	private $lastmod;
	/**
	 * @ORM\ManyToOne(targetEntity="Termek",inversedBy="valtozatok")
	 * @ORM\JoinColumn(name="termek_id",referencedColumnName="id",onDelete="cascade")
	 */
	private $termek;
	/** @ORM\Column(type="boolean") */
	private $lathato=true;
	/** @ORM\Column(type="boolean") */
	private $elerheto=true;
	/** @ORM\Column(type="boolean") */
	private $termekfokep=false;
	/**
	 * @ORM\ManyToOne(targetEntity="TermekValtozatAdatTipus",inversedBy="valtozatok1")
	 * @ORM\JoinColumn(name="adattipus1_id",referencedColumnName="id",onDelete="restrict")
	 */
	private $adattipus1;
	/**
	 * @ORM\Column(type="string",length=255,nullable=true)
	 */
	private $ertek1;
	/**
	 * @ORM\ManyToOne(targetEntity="TermekValtozatAdatTipus",inversedBy="valtozatok2")
	 * @ORM\JoinColumn(name="adattipus2_id",referencedColumnName="id",onDelete="restrict")
	 */
	private $adattipus2;
	/**
	 * @ORM\Column(type="string",length=255,nullable=true)
	 */
	private $ertek2;
	/** @ORM\OneToMany(targetEntity="Kosar", mappedBy="termekvaltozat",cascade={"persist"}) */
	private $kosarak;
	/**
	 * @ORM\Column(type="decimal",precision=14,scale=4,nullable=true)
	 */
	private $netto=0;
	/**
	 * @ORM\Column(type="decimal",precision=14,scale=4,nullable=true)
	 */
	private $brutto=0;
	/**
	 * @ORM\ManyToOne(targetEntity="TermekKep",inversedBy="valtozatok")
	 * @ORM\JoinColumn(name="termekkep_id",referencedColumnName="id",nullable=true,onDelete="restrict")
	 */
	private $kep;
	/** @ORM\Column(type="string",length=50,nullable=true) */
	private $cikkszam='';
	/** @ORM\Column(type="string",length=50,nullable=true) */
	private $idegencikkszam='';
    /** @ORM\OneToMany(targetEntity="Bizonylattetel", mappedBy="termekvaltozat",cascade={"persist"}) */
    private $bizonylattetelek;
	/** @ORM\Column(type="string",length=255,nullable=true) */
	private $vonalkod;

    /**
     * @ORM\PrePersist
     */
    public function generateVonalkod() {
        if (\mkw\Store::getSetupValue('vonalkod') && !$this->vonalkod) {
            $conn = \mkw\Store::getEm()->getConnection();
            $stmt = $conn->prepare('INSERT INTO vonalkodseq (data) VALUES (1)');
            $stmt->execute();
            $this->setVonalkod((string)$conn->lastInsertId());
        }
    }

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

    public function getFoglaltMennyiseg($kivevebiz) {
        if (is_a($kivevebiz, 'Bizonylatfej')) {
            $kivevebiz = $kivevebiz->getId();
        }
        $k = 0;
        foreach($this->bizonylattetelek as $bt) {
            if ($bt->getFoglal() && ($kivevebiz != $bt->getBizonylatfejId())) {
                $k += ($bt->getMennyiseg() * $bt->getIrany());
            }
        }
        return -1 * $k;
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

    public function getVonalkod() {
        return $this->vonalkod;
    }

    public function setVonalkod($vonalkod) {
        $this->vonalkod = $vonalkod;
    }
}