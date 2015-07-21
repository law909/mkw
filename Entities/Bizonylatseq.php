<?php
namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\BizonylatseqRepository")
 * @ORM\Table(name="bizonylatseq")
 */
class Bizonylatseq {
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