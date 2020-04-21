<?php
namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\UnnepnapRepository")
 * @ORM\Table(name="unnepnap",
 * 	options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"},
 * 	uniqueConstraints={@ORM\UniqueConstraint(name="unnepnap_egyedi",columns={"datum"})})
 */
class Unnepnap {
	/**
	 * @ORM\Id @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/** @ORM\Column(type="date",nullable=false) */
	private $datum;

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

}