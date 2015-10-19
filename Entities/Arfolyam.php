<?php
namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\ArfolyamRepository")
 * @ORM\Table(name="arfolyam",
 * 	options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"},
 * 	uniqueConstraints={@ORM\UniqueConstraint(name="arfolyam_egyedi",columns={"datum","valutanem_id"})})
 */
class Arfolyam {
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
	/** @ORM\Column(type="date",nullable=false) */
	private $datum;
	/**
	 * @ORM\ManyToOne(targetEntity="Valutanem")
	 * @ORM\JoinColumn(name="valutanem_id", referencedColumnName="id",onDelete="cascade")
	 */
	private $valutanem;
	/** @ORM\Column(type="decimal",precision=14,scale=4) */
	private $arfolyam=0;

	public function getId() {
	    return $this->id;
	}

	public function getDatum()
	{
	    return $this->datum;
	}

	public function getDatumString() {
		if ($this->datum) {
			return $this->datum->format('Y-m-d');
		}
		return '';
	}

	public function setDatum($datum)
	{
	    $this->datum = $datum;
	}

	public function getValutanem()
	{
	    return $this->valutanem;
	}

	public function getValutanemNev() {
		if ($this->valutanem) {
			return $this->valutanem->getNev();
		}
		return '';
	}

	public function setValutanem($valutanem)
	{
	    $this->valutanem = $valutanem;
	}

	public function getArfolyam()
	{
	    return $this->arfolyam;
	}

	public function setArfolyam($arfolyam)
	{
	    $this->arfolyam = $arfolyam;
	}

	public function getLastmod() {
		return $this->lastmod;
	}

	public function getCreated() {
		return $this->created;
	}
}