<?php
namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Query\ResultSetMapping;
use mkwhelpers\FilterDescriptor;

/**
 * @ORM\Entity(repositoryClass="Entities\TermekValtozatRepository")
 * @ORM\Table(name="termekvaltozat",
 * options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"},
 * indexes={
 *      @ORM\index(name="termekvaltozatvonalkod_idx",columns={"vonalkod"}),
 *      @ORM\index(name="termekvaltozatidegencikkszam_idx",columns={"idegencikkszam"})
 * })
 * @ORM\HasLifecycleCallbacks
*/
class TermekValtozat {

    private $keszletinfo;

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
	 * @ORM\ManyToOne(targetEntity="Termek",inversedBy="valtozatok",cascade={"persist"})
	 * @ORM\JoinColumn(name="termek_id",referencedColumnName="id",onDelete="cascade")
	 */
	private $termek;

    /** @ORM\Column(type="boolean") */
	private $lathato=true;

    /** @ORM\Column(type="boolean") */
    private $lathato2=true;

    /** @ORM\Column(type="boolean") */
    private $lathato3=true;

    /** @ORM\Column(type="boolean") */
	private $elerheto=true;

    /** @ORM\Column(type="boolean") */
    private $elerheto2=true;

    /** @ORM\Column(type="boolean") */
    private $elerheto3=true;

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

    /** @ORM\Column(type="date",nullable=true) */
    private $beerkezesdatum;

    /**
     * @ORM\PrePersist
     */
    public function generateVonalkod() {
        if (\mkw\store::getSetupValue('vonalkod') && !$this->vonalkod) {
            $conn = \mkw\store::getEm()->getConnection();
            $stmt = $conn->prepare('INSERT INTO vonalkodseq (data) VALUES (1)');
            $stmt->execute();
            $this->setVonalkod(\mkw\store::generateEAN13((string)$conn->lastInsertId()));
        }
    }

    public function __construct() {
		$this->kosarak=new \Doctrine\Common\Collections\ArrayCollection();
        $this->bizonylattetelek = new \Doctrine\Common\Collections\ArrayCollection();
	}

    protected function calcKeszletInfo($datum = null, $raktarid = null) {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('mennyiseg', 'mennyiseg');
        $rsm->addScalarResult('mozgasdb', 'mozgasdb');

        if (!$datum) {
            $datum = new \DateTime();
        }

        $filter = new FilterDescriptor();
        $filter->addFilter('bt.mozgat', '=', 1);
        $filter->addSql('((bt.rontott = 0) OR (bt.rontott IS NULL))');
        $filter->addFilter('bf.teljesites', '<=', $datum);
        if ($raktarid) {
            $filter->addFilter('bf.raktar_id', '=', $raktarid);
        }

        $q = $this->getEm()->createNativeQuery('SELECT SUM(bt.mennyiseg * bt.irany) AS mennyiseg, COUNT(*) AS mozgasdb'
            . 'FROM bizonylattetel bt'
            . 'LEFT OUTER JOIN bizonylatfej bf ON (bt.bizonylatfej_id=bf.id)'
            . $filter->getFilterString()
            , $rsm);

        $q->setParameters($filter->getQueryParameters());
        $d = $q->getScalarResult();

        $k = $d[0]['mennyiseg'];
        $db = $d[0]['mozgasdb'];

        /*
        $k = 0;
        $db = 0;
        /** @var \Entities\Bizonylattetel $bt */
        /*
        foreach($this->bizonylattetelek as $bt) {
            if ($bt->getMozgat() && (!$bt->getRontott()) && ($bt->getTeljesites() <= $datum) && (!$raktarid || ($raktarid && $raktarid == $bt->getRaktarId()))) {
                $k += ($bt->getMennyiseg() * $bt->getIrany());
                $db++;
            }
        }
        */
        $this->keszletinfo = array('keszlet' => $k, 'mozgasdb' => $db);
        return $this->keszletinfo;
    }

    public function getMozgasDb($datum = null, $raktarid = null) {
        if (!$this->keszletinfo) {
            $this->calcKeszletInfo($datum, $raktarid);
        }
        return $this->keszletinfo['mozgasdb'];
    }

    public function getKeszlet($datum = null, $raktarid = null) {
        if (!$this->keszletinfo) {
            $this->calcKeszletInfo($datum, $raktarid);
        }
        $r = $this->keszletinfo['keszlet'];
        unset($this->keszletinfo);
        return $r;
    }

    public function getFoglaltMennyiseg($kivevebiz = null) {
        if (\mkw\store::isFoglalas()) {
            if (is_a($kivevebiz, 'Bizonylatfej')) {
                $kivevebiz = $kivevebiz->getId();
            }
            $k = 0;
            foreach($this->bizonylattetelek as $bt) {
                $nemkivetel = true;
                if ($kivevebiz) {
                    $nemkivetel = $bt->getBizonylatfejId() != $kivevebiz;
                }
                if ($bt->getFoglal() && ($nemkivetel)) {
                    $k += ($bt->getMennyiseg() * $bt->getIrany());
                }
            }
            return -1 * $k;
        }
        return 0;
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

    public function getBeerkezesdatum() {
        return $this->beerkezesdatum;
    }

    public function getBeerkezesdatumStr() {
        if ($this->getBeerkezesdatum()) {
            return $this->getBeerkezesdatum()->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    public function setBeerkezesdatum($adat = '') {
        if (is_a($adat, 'DateTime')) {
            $this->beerkezesdatum = $adat;
        }
        else {
            if ($adat == '') {
                $this->beerkezesdatum = null;
            }
            else {
                $this->beerkezesdatum = new \DateTime(\mkw\store::convDate($adat));
            }
        }
    }

    public function getSzin() {
        if ($this->getAdatTipus1Id() == \mkw\store::getParameter(\mkw\consts::ValtozatTipusSzin)) {
            return $this->getErtek1();
        }
        if ($this->getAdatTipus2Id() == \mkw\store::getParameter(\mkw\consts::ValtozatTipusSzin)) {
            return $this->getErtek2();
        }
        return null;
    }

    public function getMeret() {
        if ($this->getAdatTipus1Id() == \mkw\store::getParameter(\mkw\consts::ValtozatTipusMeret)) {
            return $this->getErtek1();
        }
        if ($this->getAdatTipus2Id() == \mkw\store::getParameter(\mkw\consts::ValtozatTipusMeret)) {
            return $this->getErtek2();
        }
        return null;
    }

    /**
     * @return mixed
     */
    public function getElerheto2() {
        return $this->elerheto2;
    }

    /**
     * @param mixed $elerheto2
     */
    public function setElerheto2($elerheto2) {
        $this->elerheto2 = $elerheto2;
    }

    /**
     * @return mixed
     */
    public function getElerheto3() {
        return $this->elerheto3;
    }

    /**
     * @param mixed $elerheto3
     */
    public function setElerheto3($elerheto3) {
        $this->elerheto3 = $elerheto3;
    }

    /**
     * @return mixed
     */
    public function getLathato2() {
        return $this->lathato2;
    }

    /**
     * @param mixed $lathato2
     */
    public function setLathato2($lathato2) {
        $this->lathato2 = $lathato2;
    }

    /**
     * @return mixed
     */
    public function getLathato3() {
        return $this->lathato3;
    }

    /**
     * @param mixed $lathato3
     */
    public function setLathato3($lathato3) {
        $this->lathato3 = $lathato3;
    }

    public function getXElerheto() {
        switch (\mkw\store::getSetupValue('webshopnum', 1)) {
            case 1:
                return $this->getElerheto();
            case 2:
                return $this->getElerheto2();
            case 3:
                return $this->getElerheto3();
            default:
                return $this->getElerheto();
        }
    }

    public function getXLathato() {
        switch (\mkw\store::getSetupValue('webshopnum', 1)) {
            case 1:
                return $this->getLathato();
            case 2:
                return $this->getLathato2();
            case 3:
                return $this->getLathato3();
            default:
                return $this->getLathato();
        }
    }
}