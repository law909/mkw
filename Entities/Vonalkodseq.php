<?php
namespace Entities;

/**
 * @Entity(repositoryClass="Entities\VonalkodseqRepository")
 * @Table(name="vonalkodseq")
 */
class Vonalkodseq {
	/**
	 * @Id @Column(type="integer")
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;

    /**
	 * @Column(type="smallint")
	 */
	private $data;

}
