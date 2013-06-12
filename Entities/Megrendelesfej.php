<?php
namespace Entities;
use SIIKerES\Store;

/**
 * @Entity(repositoryClass="Entities\BizonylatfejRepository")
 */
class Megrendelesfej extends Bizonylatfej {

	public function getBizonylattipus() {
		return $this->realgetBizonylattipus('megrendeles');
	}
}