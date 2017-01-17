<?php
namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\TermekReceptRepository")
 * @ORM\Table(name="termekrecept",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class TermekRecept {
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
	 * @ORM\ManyToOne(targetEntity="Termek",inversedBy="termekreceptek")
	 * @ORM\JoinColumn(name="termek_id",referencedColumnName="id",onDelete="cascade")
	 */
	private $termek;
	/**
	 * @ORM\ManyToOne(targetEntity="Termek",inversedBy="altermekreceptek")
	 * @ORM\JoinColumn(name="altermek_id",referencedColumnName="id",onDelete="cascade")
	 */
	private $altermek;
	/**
	 * @ORM\Column(type="decimal",precision=14,scale=4,nullable=true)
	 */
	private $mennyiseg;
	/** @ORM\Column(type="boolean") */
	private $kotelezo=false;
    /**
     * @ORM\ManyToOne(targetEntity="TermekReceptTipus")
     * @ORM\JoinColumn(name="termekrecepttipus_id",referencedColumnName="id",onDelete="cascade")
     */
    private $tipus;

	public function getId() {
		return $this->id;
	}

	public function getTermek() {
		return $this->termek;
	}

	public function getTermekNev() {
		if ($this->termek) {
			return $this->termek->getNev();
		}
		return '';
	}

	public function setTermek(Termek $termek) {
		$this->termek = $termek;
//		$termek->addTermekRecept($this);
	}

	public function removeTermek() {
		if ($this->termek !== null) {
			$termek = $this->termek;
			$this->termek = null;
			$termek->removeTermekRecept($this);
		}
	}

	public function getAlTermek() {
		return $this->altermek;
	}

	public function getAlTermekNev() {
		if ($this->altermek) {
			return $this->altermek->getNev();
		}
		return '';
	}

	public function setAlTermek(Termek $altermek) {
		$this->altermek = $altermek;
//		$altermek->addAlTermekRecept($this);
	}

	public function removeAlTermek() {
		if ($this->altermek !== null) {
			$altermek = $this->altermek;
			$this->altermek = null;
			$altermek->removeAlTermekRecept($this);
		}
	}

	public function getMennyiseg() {
		return $this->mennyiseg;
	}

	public function setMennyiseg($mennyiseg) {
		$this->mennyiseg=$mennyiseg;
	}

	public function getKotelezo() {
		return $this->kotelezo;
	}

	public function setKotelezo($kotelezo) {
		$this->kotelezo=$kotelezo;
	}

	public function getLastmod() {
		return $this->lastmod;
	}

	public function getCreated() {
		return $this->created;
	}

    /**
     * @return mixed
     */
    public function getTipus() {
        return $this->tipus;
    }

    public function getTipusNev() {
        if ($this->getTipus()) {
            return $this->getTipus()->getNev();
        }
        return null;
    }

    public function getTipusId() {
        if ($this->getTipus()) {
            return $this->getTipus()->getId();
        }
        return null;
    }

    /**
     * @param mixed $tipus
     */
    public function setTipus($tipus) {
        $this->tipus = $tipus;
    }

}