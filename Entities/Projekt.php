<?php
namespace Entities;

/**
 * @Entity @Table(name="projekt")
 */
class Projekt {
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
	/** @Column(type="string",length=255,nullable=true) */
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