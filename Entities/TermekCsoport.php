<?php
namespace Entities;
use matt;
/**
 * @Entity(repositoryClass="Entities\TermekCsoportRepository")
 * @Table(name="termekcsoport")
 */
class TermekCsoport {
	/**
	 * @Id @Column(type="integer")
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/** @Column(type="string",length=255,nullable=false) */
	private $nev;
	/** @Column(type="string",length=8,nullable=true) */
	private $keszletfkviszam='';
	/** @Column(type="string",length=8,nullable=true) */
	private $arbevetelfkviszam='';
	/** @Column(type="string",length=8,nullable=true) */
	private $elabefkviszam='';

	public function getId() {
	    return $this->id;
	}

	public function getNev() {
	    return $this->nev;
	}

	public function setNev($nev) {
	    $this->nev = $nev;
	}

	public function getKeszletfkviszam()
	{
	    return $this->keszletfkviszam;
	}

	public function setKeszletfkviszam($keszletfkviszam)
	{
	    $this->keszletfkviszam = $keszletfkviszam;
	}

	public function getArbevetelfkviszam()
	{
	    return $this->arbevetelfkviszam;
	}

	public function setArbevetelfkviszam($arbevetelfkviszam)
	{
	    $this->arbevetelfkviszam = $arbevetelfkviszam;
	}

	public function getElabefkviszam()
	{
	    return $this->elabefkviszam;
	}

	public function setElabefkviszam($elabefkviszam)
	{
	    $this->elabefkviszam = $elabefkviszam;
	}
}