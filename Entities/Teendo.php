<?php
namespace Entities;
use mkw\store;
/**
 * @Entity(repositoryClass="Entities\TeendoRepository")
 */
class Teendo extends OsFeladat {

	/** @Column(type="boolean") */
	private $elvegezve=false;
	/** @Column(type="datetime") */
	private $elvegezve_mikor;
	/**
	 * @ManyToOne(targetEntity="Partner",inversedBy="teendok")
	 * @JoinColumn(name="partner_id", referencedColumnName="id",onDelete="cascade")
	 */
	private $partner;

	public function getElvegezve() {
		return $this->elvegezve;
	}

	public function setElvegezve($elvegezve) {
		if (!$this->elvegezve) {
			$this->elvegezve = $elvegezve;
			$this->elvegezve_mikor = new \DateTime();
		}
	}

	public function getElvegezveMikor() {
		return $this->elvegezve_mikor;
	}

	public function getElvegezveMikorStr() {
		if ($this->getElvegezve()) {
			return $this->getElvegezveMikor()->format(store::$DateFormat);
		}
		return '';
	}

	public function setElvegezveMikor($elvegezve_mikor) {
		/* TODO Egyelore ures ld. setElvegezve() */
	}

	public function getPartner() {
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

	public function setPartner(Partner $partner) {
		if ($this->partner!==$partner) {
			$this->partner=$partner;
			$partner->addTeendo($this);
		}
	}

	public function removePartner() {
		if ($this->partner !== null) {
			$partner = $this->partner;
			$this->partner = null;
			$partner->removeTeendo($this);
		}
	}
}