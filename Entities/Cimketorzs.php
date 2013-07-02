<?php
namespace Entities;

use mkw\store;

/**
 * @Entity
 * @Table(name="cimketorzs",indexes={
 *		@index(name="cimketorzsslug_idx",columns={"slug"})
 * })
 * @InheritanceType("SINGLE_TABLE")
 * @DiscriminatorColumn(name="osztaly", type="string", length=30)
 * @DiscriminatorMap({"kontakt"="Kontaktcimketorzs", "partner"="Partnercimketorzs", "termek"="Termekcimketorzs"})
 */

class Cimketorzs {
	/**
	 * @Id @Column(type="integer")
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/** @Column(type="boolean") */
	private $menu1lathato=true;
	/** @Column(type="boolean") */
	private $menu2lathato=false;
	/** @Column(type="boolean") */
	private $menu3lathato=false;
	/** @Column(type="boolean") */
	private $menu4lathato=false;
	/** @Column(type="text",nullable=true) */
	private $leiras;
	/** @Column(type="string",length=255,nullable=true) */
	private $oldalcim;
	/** @Column(type="text",nullable=true) */
	private $kepurl;
	/** @Column(type="text",nullable=true) */
	private $kepleiras;
	/** @Column(type="integer",nullable=true) */
	private $sorrend;

	public function getId() {
		return $this->id;
	}

	public function getMenu1lathato()
	{
	    return $this->menu1lathato;
	}

	public function setMenu1lathato($menu1lathato)
	{
	    $this->menu1lathato = $menu1lathato;
	}

	public function getMenu2lathato()
	{
	    return $this->menu2lathato;
	}

	public function setMenu2lathato($menu2lathato)
	{
	    $this->menu2lathato = $menu2lathato;
	}

	public function getMenu3lathato()
	{
	    return $this->menu3lathato;
	}

	public function setMenu3lathato($menu3lathato)
	{
	    $this->menu3lathato = $menu3lathato;
	}

	public function getMenu4lathato()
	{
	    return $this->menu4lathato;
	}

	public function setMenu4lathato($menu4lathato)
	{
	    $this->menu4lathato = $menu4lathato;
	}

	public function getLeiras()
	{
	    return $this->leiras;
	}

	public function setLeiras($leiras)
	{
	    $this->leiras = $leiras;
	}

	public function getOldalcim()
	{
	    return $this->oldalcim;
	}

	public function setOldalcim($oldalcim)
	{
	    $this->oldalcim = $oldalcim;
	}

	public function getKepurl($pre='/')
	{
		if ($this->kepurl) {
			if ($this->kepurl[0]!==$pre) {
				return $pre.$this->kepurl;
			}
			else {
				return $this->kepurl;
			}
		}
		return '';
	}

	public function getKepurlSmall($pre='/') {
		$kepurl=$this->getKepurl($pre);
		if ($kepurl) {
			$t=explode('.',$kepurl);
			$ext=array_pop($t);
			return implode('.',$t).store::getParameter('smallimgpost','').'.'.$ext;
		}
		return '';
	}

	public function getKepurlMedium($pre='/') {
		$kepurl=$this->getKepurl($pre);
		if ($kepurl) {
			$t=explode('.',$kepurl);
			$ext=array_pop($t);
			return implode('.',$t).store::getParameter('mediumimgpost','').'.'.$ext;
		}
		return '';
	}

	public function getKepurlLarge($pre='/') {
		$kepurl=$this->getKepurl($pre);
		if ($kepurl) {
			$t=explode('.',$kepurl);
			$ext=array_pop($t);
			return implode('.',$t).store::getParameter('bigimgpost','').'.'.$ext;
		}
		return '';
	}

	public function setKepurl($kepurl)
	{
		$this->kepurl = $kepurl;
		if (!$kepurl) {
			$this->setKepleiras(null);
		}
	}

	public function getKepleiras()
	{
		return $this->kepleiras;
	}

	public function setKepleiras($kepleiras)
	{
		$this->kepleiras = $kepleiras;
	}

	public function getSorrend() {
		return $this->sorrend;
	}

	public function setSorrend($sorrend) {
		$this->sorrend=$sorrend;
	}
}