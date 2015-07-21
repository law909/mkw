<?php
namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity @ORM\Table(name="projekt")
 */
class Projekt {
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
	/** @ORM\Column(type="string",length=255,nullable=true) */
	private $nev;


	public function getId()
	{
	    return $this->id;
	}

	public function getNev()
	{
	    return $this->nev;
	}

	public function setNev($nev)
	{
	    $this->nev = $nev;
	}

	public function getLastmod() {
		return $this->lastmod;
	}

	public function getCreated() {
		return $this->created;
	}
}