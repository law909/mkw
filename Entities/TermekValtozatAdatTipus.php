<?php
namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\TermekValtozatAdatTipusRepository")
 * @ORM\Table(name="termekvaltozatadattipus",
 * options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"},
 * indexes={
 *      @ORM\index(name="termekvaltozatadattipusnev_idx",columns={"nev"})
 * })
*/
class TermekValtozatAdatTipus {
	/**
	 * @ORM\Id @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/** @ORM\Column(type="string",length=255,nullable=false) */
	private $nev;
	/** @ORM\OneToMany(targetEntity="TermekValtozat",mappedBy="adattipus1",cascade={"persist"}) */
	private $valtozatok1;
	/** @ORM\OneToMany(targetEntity="TermekValtozat",mappedBy="adattipus2",cascade={"persist"}) */
	private $valtozatok2;

	public function __construct() {
		$this->valtozatok1=new \Doctrine\Common\Collections\ArrayCollection();
		$this->valtozatok2=new \Doctrine\Common\Collections\ArrayCollection();
	}

	public function getId() {
		return $this->id;
	}

	public function getNev() {
		return $this->nev;
	}

	public function setNev($nev) {
		$this->nev = $nev;
	}

	public function getValtozatok1() {
		return $this->valtozatok1;
	}

	public function addValtozat1(TermekValtozat $valt) {
//		if (!$this->valtozatok1->contains($valt)) {
			$this->valtozatok1->add($valt);
			$valt->setAdatTipus($this);
//		}
	}

	public function removeValtozat1(TermekValtozat $valt) {
		if ($this->valtozatok1->removeElement($valt)) {
			$valt->setAdatTipus(null);
			return true;
		}
		return false;
	}

	public function getValtozatok2() {
		return $this->valtozatok2;
	}

	public function addValtozat2(TermekValtozat $valt) {
//		if (!$this->valtozatok2->contains($valt)) {
			$this->valtozatok2->add($valt);
			$valt->setAdatTipus($this);
//		}
	}

	public function removeValtozat2(TermekValtozat $valt) {
		if ($this->valtozatok2->removeElement($valt)) {
			$valt->setAdatTipus(null);
			return true;
		}
		return false;
	}
}