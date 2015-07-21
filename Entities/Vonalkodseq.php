<?php
namespace Entities;


use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity(repositoryClass="Entities\VonalkodseqRepository")
 * @ORM\Table(name="vonalkodseq")
 */
class Vonalkodseq {
	/**
	 * @ORM\Id @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

    /**
	 * @ORM\Column(type="smallint")
	 */
	private $data;

}
