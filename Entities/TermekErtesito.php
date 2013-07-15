<?php
namespace Entities;

/**
 * @Entity(repositoryClass="Entities\TermekErtesitoRepository")
 * @Table(name="termekertesito",indexes={
 *		@index(name="termekertesitoemail_idx",columns={"email","termek_id"}),
 *		@index(name="termekertesitotermek_idx",columns={"termek_id","email"}),
 *		@index(name="termekertesitopartner_idx",columns={"partner_id","termek_id"})
 * })
 * @HasLifecycleCallbacks
*/
class TermekErtesito {
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
	 * @ManyToOne(targetEntity="Termek",inversedBy="termekertesitok")
	 * @JoinColumn(name="termek_id",referencedColumnName="id",onDelete="cascade")
	 */
	private $termek;
	/**
	 * @ManyToOne(targetEntity="Partner",inversedBy="termekertesitok")
	 * @JoinColumn(name="partner_id", referencedColumnName="id",nullable=true,onDelete="no action")
	 */
	private $partner;
	/** @Column(type="datetime",nullable=true) */
	private $sent;

	public function getId() {
		return $this->id;
	}

	public function getCreated() {
		return $this->created;
	}

	public function getEmail() {
		return $this->email;
	}

	public function setEmail($email) {
		$this->email=$email;
	}

	public function getPartner(){
		return $this->partner;
	}

	public function getPartnerId() {
		if ($this->partner) {
			return $this->partner->getId();
		}
		return '';
	}

	public function getPartnerNev() {
		if ($this->partner) {
			return $this->partner->getNev();
		}
		return '';
	}

	public function setPartner(Partner $val) {
		if ($this->partner!==$val) {
			$this->partner=$val;
//			$val->addBizonylatfej($this);
		}
	}

	public function removePartner() {
		if ($this->partner!==null) {
//			$val=$this->partner;
			$this->partner=null;
//			$val->removeBizonylatfej($this);
		}
	}

	public function getTermek(){
		return $this->termek;
	}

	public function getTermekId() {
		if ($this->termek) {
			return $this->termek->getId();
		}
		return '';
	}

	public function setTermek(Termek $val) {
		if ($this->termek!==$val) {
			$this->termek=$val;
//			$val->addBizonylattetelek($this);
		}
	}

	public function removeTermek() {
		if ($this->termek !==null) {
//			$val=$this->termek;
			$this->termek=null;
//			$val->removeBizonylattetelek($this);
		}
	}

	public function getSent() {
		return $this->sent;
	}

	public function setSent($val) {
		$this->sent=$val;
	}
}