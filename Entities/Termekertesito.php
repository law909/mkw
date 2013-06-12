<?php
namespace Entities;

/**
 * @Entity(repositoryClass="Entities\TermekertesitoRepository")
 * @Table(name="termekertesito",indexes={
 *		@index(name="termek_idx",columns={"termekfa1karkod","termekfa2karkod","termekfa3karkod"}),
 * })
 * @HasLifecycleCallbacks
*/
class Termek {
	/**
	 * @Id @Column(type="integer")
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id=0;
	/**
	 * @gedmo:Timestampable(on="create")
	 * @Column(type="datetime",nullable=true)
	 */
	private $created;
	/** @Column(type="string",length=100,nullable=true) */
	private $email='';
	/**
	 * @ManyToOne(targetEntity="Termek",inversedBy="termekkepek")
	 * @JoinColumn(name="termek_id",referencedColumnName="id",onDelete="cascade")
	 */
	private $termek;
	/**
	 * @ManyToOne(targetEntity="Partner",inversedBy="kosarak")
	 * @JoinColumn(name="partner_id", referencedColumnName="id",nullable=true,onDelete="no action")
	 */
	private $partner;
	
}