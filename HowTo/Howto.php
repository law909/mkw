<?php
namespace Entities;

/**
 * @Entity(repositoryClass="Entities\?Howto?Repository")
 * @Table(name="?howto?")
 */
class ?Howto? {
	/**
	 * @Id @Column(type="integer")
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/**
	 * @gedmo:Sluggable
	 * @Column(type="string",length=255)
	 */
	private $nev;

	public function getId() {
		return $this->id;
	}

	public function getNev() {
		return $this->nev;
	}

	public function setNev($nev) {
		$this->nev = $nev;
	}
}
