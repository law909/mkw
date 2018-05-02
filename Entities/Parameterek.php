<?php
namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="parameterek",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class Parameterek {
	/**
	 * @ORM\Id @ORM\Column(type="string",length=255)
	 */
	private $id;
	/** @ORM\Column(type="text",nullable=true) */
	private $ertek;
    /** @ORM\Column(type="boolean") */
    private $specialchars = false;


	public function getId()
	{
	    return $this->id;
	}

	public function setId($id)
	{
	    $this->id = $id;
	}

	public function getErtek()
	{
	    if ($this->specialchars) {
	        return htmlspecialchars_decode($this->ertek);
        }
	    return $this->ertek;
	}

	public function setErtek($ertek)
	{
	    $this->ertek = $ertek;
	}

    /**
     * @return mixed
     */
    public function getSpecialchars() {
        return $this->specialchars;
    }

    /**
     * @param mixed $specialchars
     */
    public function setSpecialchars($specialchars) {
        $this->specialchars = $specialchars;
    }

}