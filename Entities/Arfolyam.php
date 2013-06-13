<?php
namespace Entities;

/**
 * @Entity(repositoryClass="Entities\ArfolyamRepository")
 * @Table(name="arfolyam",uniqueConstraints={@UniqueConstraint(name="arfolyam_egyedi",columns={"datum","valutanem_id"})})
 */
class Arfolyam {
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
	/** @Column(type="date",nullable=false) */
	private $datum;
	/**
	 * @ManyToOne(targetEntity="Valutanem")
	 * @JoinColumn(name="valutanem_id", referencedColumnName="id",onDelete="cascade")
	 */
	private $valutanem;
	/** @Column(type="decimal",precision=14,scale=4) */
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