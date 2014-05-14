<?php
namespace Entities;

/**
 * @Entity(repositoryClass="Entities\Rewrite301Repository")
 * @Table(name="rewrite301")
 */
class Rewrite301 {
	/**
	 * @Id @Column(type="integer")
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/**
	 * @Column(type="text")
	 */
	private $fromurl;
	/**
	 * @Column(type="text")
	 */
	private $tourl;

	public function getId() {
		return $this->id;
	}

	public function getFromurl() {
		return $this->fromurl;
	}

	public function setFromurl($nev) {
		$this->fromurl = $nev;
	}

    public function getTourl() {
		return $this->tourl;
	}

	public function setTourl($nev) {
		$this->tourl = $nev;
	}
}
