<?php
namespace Entities;

use mkw\store;

/**
 * @Entity(repositoryClass="Entities\TermekFaRepository")
 * @Table(name="termekfa",indexes={
 *	@index(name="termekfaslug_idx",columns={"slug"})
 * })
 */
class TermekFa {
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
	/**
	 * @OneToMany(targetEntity="TermekFa", mappedBy="parent")
	 * @OrderBy({"sorrend"="ASC","nev"="ASC"})
	 */
	private $children;
	/**
	 * @ManyToOne(targetEntity="TermekFa", inversedBy="children")
	 * @JoinColumn(name="parent_id", referencedColumnName="id",onDelete="no action")
	 */
	private $parent;
	/**
	 * @gedmo:Sluggable
	 * @Column(type="string",length=255,nullable=false)
	 */
	private $nev;
	/** @Column(type="integer",nullable=true) */
	private $sorrend;
	/**
	 * @gedmo:Slug
	 * @Column(type="string",length=255,nullable=true)
	 */
	private $slug;
	/** @Column(type="string",length=255) */
	private $karkod;
	/** @Column(type="string",length=255,nullable=true) */
	private $rovidleiras='';
	/** @Column(type="text",nullable=true) */
	private $leiras;
	/** @Column(type="boolean",nullable=true) */
	private $menu1lathato=true;
	/** @Column(type="boolean",nullable=true) */
	private $menu2lathato=false;
	/** @Column(type="boolean",nullable=true) */
	private $menu3lathato=false;
	/** @Column(type="boolean",nullable=true) */
	private $menu4lathato=false;
	/** @Column(type="string",length=255,nullable=true) */
	private $oldalcim;
	/** @Column(type="text",nullable=true) */
	private $seodescription;
	/** @Column(type="text",nullable=true) */
	private $kepurl;
	/** @Column(type="text",nullable=true) */
	private $kepleiras;
	/**
	 * @OneToMany(targetEntity="Termek",mappedBy="termekfa1")
	 */
	private $termekek1;
	/**
	 * @OneToMany(targetEntity="Termek",mappedBy="termekfa2")
	 */
	private $termekek2;
	/**
	 * @OneToMany(targetEntity="Termek",mappedBy="termekfa3")
	 */
	private $termekek3;

	public function __construct() {
		$this->children=new \Doctrine\Common\Collections\ArrayCollection();
		$this->termekek1=new \Doctrine\Common\Collections\ArrayCollection();
		$this->termekek2=new \Doctrine\Common\Collections\ArrayCollection();
		$this->termekek3=new \Doctrine\Common\Collections\ArrayCollection();
	}

	public function getId()
	{
	    return $this->id;
	}

	public function getChildren() {
		return $this->children;
	}

	public function addChild(TermekFa $child) {
		if (!$this->children->contains($child)) {
			$this->children->add($child);
			$child->setParent($this);
		}
	}

	public function removeChild(TermekFa $child) {
		if ($this->children->removeElement($child)) {
			$child->removeParent();
			return true;
		}
		return false;
	}

	public function getParent() {
		return $this->parent;
	}

	public function getParentId() {
		if ($this->parent) {
			return $this->parent->getId();
		}
		return 0;
	}

	public function getParentNev() {
		if ($this->parent) {
			return $this->parent->getNev();
		}
		return '';
	}

	public function setParent(TermekFa $parent) {
		if ($this->parent!==$parent) {
			$this->parent=$parent;
			$parent->addChild($this);
			$this->setKarkod($parent->getKarkod().$this->getId());
		}
	}

	public function removeParent() {
		if ($this->parent !== null) {
			$parent = $this->parent;
			$this->parent = null;
			$parent->removeChild($this);
			$this->setKarkod(null);
		}
	}

	public function getNev() {
		return $this->nev;
	}

	public function setNev($nev) {
		$this->nev = $nev;
	}

	public function getSorrend() {
		return $this->sorrend;
	}

	public function setSorrend($sorrend) {
		$this->sorrend=$sorrend;
	}

	public function getSlug() {
		return $this->slug;
	}

	public function getMenu1lathato() {
	    return $this->menu1lathato;
	}

	public function setMenu1lathato($menu1lathato) {
		$this->menu1lathato = $menu1lathato;
	}

	public function getMenu2lathato() {
		return $this->menu2lathato;
	}

	public function setMenu2lathato($menu2lathato) {
		$this->menu2lathato = $menu2lathato;
	}

	public function getMenu3lathato() {
		return $this->menu3lathato;
	}

	public function setMenu3lathato($menu3lathato) {
		$this->menu3lathato = $menu3lathato;
	}

	public function getMenu4lathato() {
		return $this->menu4lathato;
	}

	public function setMenu4lathato($menu4lathato) {
		$this->menu4lathato = $menu4lathato;
	}

	public function getLeiras() {
		return $this->leiras;
	}

	public function setLeiras($leiras) {
		$this->leiras = $leiras;
	}

	public function getKarkod() {
		return $this->karkod;
	}

	public function setKarkod($karkod) {
		$this->karkod=$karkod;
	}

	public function getOldalcim() {
		return $this->oldalcim;
	}

	public function getShowOldalcim() {
		if ($this->oldalcim) {
			return $this->oldalcim;
		}
		else {
			$result=store::getParameter('katoldalcim');
			if ($result) {
				$result=str_replace('[kategorianev]', $this->getNev(),$result);
				$result=str_replace('[global]', store::getParameter('oldalcim'), $result);
				return $result;
			}
			else {
				return store::getParameter('oldalcim');
			}
		}
	}

	public function setOldalcim($oldalcim)
	{
		$this->oldalcim = $oldalcim;
	}

	public function getSeodescription() {
		return $this->seodescription;
	}

	public function getShowSeodescription() {
		if ($this->seodescription) {
			return $this->seodescription;
		}
		else {
			$result=store::getParameter('katseodescription');
			if ($result) {
				$result=str_replace('[kategorianev]', $this->getNev(),$result);
				$result=str_replace('[global]', store::getParameter('seodescription'), $result);
				return $result;
			}
			else {
				return store::getParameter('seodescription');
			}
		}
	}

	public function setSeodescription($seodescription) {
		$this->seodescription = $seodescription;
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

	public function getTermekek1() {
		return $this->termekek1;
	}

	public function addTermek1(Termek $termek) {
		if (!$this->termekek1->contains($termek)) {
			$this->termekek1->add($termek);
			$termek->setTermekfa1($this);
		}
	}

	public function removeTermek1(Termek $termek) {
		if ($this->termekek1->removeElement($termek)) {
			$termek->setTermekfa1(null);
			return true;
		}
		return false;
	}

	public function getTermekek2() {
		return $this->termekek2;
	}

	public function addTermek2(Termek $termek) {
		if (!$this->termekek2->contains($termek)) {
			$this->termekek2->add($termek);
			$termek->setTermekfa2($this);
		}
	}

	public function removeTermek2(Termek $termek) {
		if ($this->termekek2->removeElement($termek)) {
			$termek->setTermekfa2(null);
			return true;
		}
		return false;
	}

	public function getTermekek3() {
		return $this->termekek3;
	}

	public function addTermek3(Termek $termek) {
		if (!$this->termekek3->contains($termek)) {
			$this->termekek3->add($termek);
			$termek->setTermekfa3($this);
		}
	}

	public function removeTermek3(Termek $termek) {
		if ($this->termekek3->removeElement($termek)) {
			$termek->setTermekfa3(null);
			return true;
		}
		return false;
	}

	public function isDeletable() {
		return ($this->children->isEmpty())&&($this->termekek1->isEmpty())&&($this->termekek2->isEmpty())&&($this->termekek3->isEmpty());
	}

	public function getLastmod() {
		return $this->lastmod;
	}

	public function getCreated() {
		return $this->created;
	}

	public function getRovidleiras() {
		return $this->rovidleiras;
	}

	public function setRovidleiras($rovidleiras) {
		$this->rovidleiras = $rovidleiras;
	}
}