<?php
namespace Entities;

/**
 * @Entity
 * @Table(name="parameterek")
 */
class Parameterek {
	/**
	 * @Id @Column(type="string",length=255)
	 */
	private $id;
	/** @Column(type="text",nullable=true) */
	private $ertek;

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
	    return $this->ertek;
	}

	public function setErtek($ertek)
	{
	    $this->ertek = $ertek;
	}
}