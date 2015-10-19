<?php
namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\TermekKapcsolodoRepository")
 * @ORM\Table(name="termekkapcsolodo",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class TermekKapcsolodo {
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
	 * @ORM\ManyToOne(targetEntity="Termek",inversedBy="termekkapcsolodok")
	 * @ORM\JoinColumn(name="termek_id",referencedColumnName="id",onDelete="cascade")
	 */
	private $termek;
	/**
	 * @ORM\ManyToOne(targetEntity="Termek",inversedBy="altermekkapcsolodok")
	 * @ORM\JoinColumn(name="altermek_id",referencedColumnName="id",onDelete="cascade")
	 */
	private $altermek;

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

	public function getLastmod() {
		return $this->lastmod;
	}

	public function getCreated() {
		return $this->created;
	}
}