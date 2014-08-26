<?php
namespace Entities;

use mkw\store;

/**
 * @Entity(repositoryClass="Entities\TermekKepRepository")
 * @Table(name="termekkep")
 */
class TermekKep {
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
	 * @ManyToOne(targetEntity="Termek",inversedBy="termekkepek")
	 * @JoinColumn(name="termek_id",referencedColumnName="id",onDelete="cascade")
	 */
	private $termek;
	/** @Column(type="text",nullable=true) */
	private $url;
	/** @Column(type="text",nullable=true) */
	private $leiras;
	/** @OneToMany(targetEntity="TermekValtozat",mappedBy="kep",cascade={"persist"}) */
	private $valtozatok;

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
//		$termek->addTermekKep($this);
	}

	public function removeTermek() {
		if ($this->termek !== null) {
			$termek = $this->termek;
			$this->termek = null;
			$termek->removeTermekKep($this);
		}
	}

	public function getUrl($pre='/')
	{
		if ($this->url) {
			if ($this->url[0]!==$pre) {
				return $pre.$this->url;
			}
			else {
				return $this->url;
			}
		}
		return '';
	}

	public function getUrlMini($pre='/') {
		$url=$this->getUrl($pre);
		if ($url) {
			$t=explode('.',$url);
			$ext=array_pop($t);
			return implode('.',$t).store::getParameter(\mkw\consts::Miniimgpost,'').'.'.$ext;
		}
		return '';
	}

	public function getUrlSmall($pre='/') {
		$url=$this->getUrl($pre);
		if ($url) {
			$t=explode('.',$url);
			$ext=array_pop($t);
			return implode('.',$t).store::getParameter(\mkw\consts::Smallimgpost,'').'.'.$ext;
		}
		return '';
	}

	public function getUrlMedium($pre='/') {
		$url=$this->getUrl($pre);
		if ($url) {
			$t=explode('.',$url);
			$ext=array_pop($t);
			return implode('.',$t).store::getParameter(\mkw\consts::Mediumimgpost,'').'.'.$ext;
		}
		return '';
	}

	public function getUrlLarge($pre='/') {
		$url=$this->getUrl($pre);
		if ($url) {
			$t=explode('.',$url);
			$ext=array_pop($t);
			return implode('.',$t).store::getParameter(\mkw\consts::Bigimgpost,'').'.'.$ext;
		}
		return '';
	}

	public function setUrl($url)
	{
		$this->url = $url;
		if (!$url) {
			$this->setLeiras(null);
		}
	}

	public function getLeiras()
	{
		return $this->leiras;
	}

	public function setLeiras($leiras)
	{
		$this->leiras = $leiras;
	}

	public function getLastmod() {
		return $this->lastmod;
	}

	public function getCreated() {
		return $this->created;
	}
}