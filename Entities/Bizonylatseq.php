<?php
namespace Entities;

/**
 * @Entity(repositoryClass="Entities\BizonylatseqRepository")
 * @Table(name="bizonylatseq")
 */
class Bizonylatseq {
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