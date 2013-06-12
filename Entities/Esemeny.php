<?php
namespace Entities;

/**
 * @Entity(repositoryClass="Entities\EsemenyRepository")
 */
class Esemeny extends OsFeladat {

	/**
	 * @ManyToOne(targetEntity="Partner",inversedBy="esemenyek")
	 * @JoinColumn(name="partner_id", referencedColumnName="id",onDelete="cascade")
	 */
	private $partner;

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
			$partner->addEsemeny($this);
		}
	}

	public function removePartner() {
		if ($this->partner !== null) {
			$partner = $this->partner;
			$this->partner = null;
			$partner->removeEsemeny($this);
		}
	}
}