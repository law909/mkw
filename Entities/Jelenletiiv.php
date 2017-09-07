<?php
namespace Entities;

use Doctrine\ORM\Mapping as ORM;
use mkw\store;

/**
 * @ORM\Entity(repositoryClass="Entities\JelenletiivRepository")
 * @ORM\Table(name="jelenletiiv",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class Jelenletiiv {
	/**
	 * @ORM\Id @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/** @ORM\Column(type="date") */
	private $datum;
    /** @ORM\Column(type="time",nullable=true) */
    private $belepes;
    /** @ORM\Column(type="time",nullable=true) */
    private $kilepes;
	/**
	 * @ORM\ManyToOne(targetEntity="Dolgozo",inversedBy="jelenletek")
	 * @ORM\JoinColumn(name="dolgozo_id", referencedColumnName="id",nullable=true,onDelete="cascade")
	 */
	private $dolgozo;
	/** @ORM\Column(type="integer",nullable=true) */
	private $munkaido;
	/**
	 * @ORM\ManyToOne(targetEntity="Jelenlettipus")
	 * @ORM\JoinColumn(name="jelenlettipus_id", referencedColumnName="id",nullable=true,onDelete="cascade")
	 */
	private $jelenlettipus;
	/** @ORM\Column(type="string",length=50,nullable=true) */
	private $beip;
    /** @ORM\Column(type="string",length=50,nullable=true) */
    private $kiip;


	public function getId() {
		return $this->id;
	}

	public function getDatum() {
		return $this->datum;
	}

	public function getDatumStr() {
		if ($this->getDatum()) {
			return $this->getDatum()->format(store::$DateFormat);
		}
		return '';
	}

	public function setDatum($adat) {
        if (is_a($adat, 'DateTime')) {
            $this->datum = $adat;
        }
        else {
            if ($adat == '') {
                $adat = date(store::$DateFormat);
            }
            $this->datum = new \DateTime(store::convDate($adat));
        }
	}

	public function getDolgozo(){
		return $this->dolgozo;
	}

	public function getDolgozoNev() {
		if ($this->dolgozo) {
			return $this->dolgozo->getNev();
		}
		return '';
	}

	public function getDolgozoId() {
		if ($this->dolgozo) {
			return $this->dolgozo->getId();
		}
		return '';
	}

	public function setDolgozo(Dolgozo $adat) {
		if ($this->dolgozo!==$adat) {
			$this->dolgozo=$adat;
			$adat->addJelenlet($this);
		}
	}

	public function removeDolgozo() {
		if ($this->dolgozo !== null) {
			$adat = $this->dolgozo;
			$this->dolgozo = null;
			$adat->removeJelenlet($this);
		}
	}

	public function getMunkaido() {
		return $this->munkaido;
	}

	public function setMunkaido($adat) {
		$this->munkaido=$adat;
	}

	public function getJelenlettipus(){
		return $this->jelenlettipus;
	}

	public function getJelenlettipusNev() {
		if ($this->jelenlettipus) {
			return $this->jelenlettipus->getNev();
		}
		return '';
	}

	public function getJelenlettipusId() {
		if ($this->jelenlettipus) {
			return $this->jelenlettipus->getId();
		}
		return '';
	}

	public function setJelenlettipus(Jelenlettipus $adat) {
		if ($this->jelenlettipus!==$adat) {
			$this->jelenlettipus=$adat;
//			$adat->addJelenlet($this);
		}
	}

	public function removeJelenlettipus() {
		if ($this->jelenlettipus !== null) {
//			$adat = $this->jelenlettipus;
			$this->jelenlettipus= null;
//			$adat->removeJelenlet($this);
		}
	}

    /**
     * @return mixed
     */
    public function getBelepes() {
        return $this->belepes;
    }

    /**
     * @param mixed $belepes
     */
    public function setBelepes($belepes) {
        if (is_a($belepes, 'DateTime')) {
            $this->belepes = $belepes;
        }
        else {
            if ($belepes == '') {
                $belepes = date(store::$TimeFormat);
            }
            $this->belepes = new \DateTime(store::convTime($belepes));
        }
    }

    public function getBelepesStr() {
        if ($this->getBelepes()) {
            return $this->getBelepes()->format(\mkw\store::$TimeFormat);
        }
        return '';
    }

    /**
     * @return mixed
     */
    public function getKilepes() {
        return $this->kilepes;
    }

    /**
     * @param mixed $kilepes
     */
    public function setKilepes($kilepes) {
        if (is_a($kilepes, 'DateTime')) {
            $this->kilepes = $kilepes;
        }
        else {
            if ($kilepes == '') {
                $kilepes = date(store::$TimeFormat);
            }
            $this->kilepes = new \DateTime(store::convTime($kilepes));
        }
    }

    public function getKilepesStr() {
        if ($this->getKilepes()) {
            return $this->getKilepes()->format(\mkw\store::$TimeFormat);
        }
        return '';
    }

    /**
     * @return mixed
     */
    public function getBeip() {
        return $this->beip;
    }

    /**
     * @param mixed $beip
     */
    public function setBeip($beip) {
        $this->beip = $beip;
    }

    /**
     * @return mixed
     */
    public function getKiip() {
        return $this->kiip;
    }

    /**
     * @param mixed $kiip
     */
    public function setKiip($kiip) {
        $this->kiip = $kiip;
    }

}