<?php
namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\Rewrite301Repository")
 * @ORM\Table(name="rewrite301")
 */
class Rewrite301 {
	/**
	 * @ORM\Id @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/**
	 * @ORM\Column(type="text")
	 */
	private $fromurl;
	/**
	 * @ORM\Column(type="text")
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
