<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;
use mkw\store;

/**
 * @ORM\Entity(repositoryClass="Entities\BizonylatDokRepository")
 */
class BizonylatDok extends Dokumentumtar {

    /**
     * @ORM\ManyToOne(targetEntity="bizonylatfej",inversedBy="bizonylatdokok")
     * @ORM\JoinColumn(name="bizonylat_id",referencedColumnName="id",onDelete="cascade")
     */
    private $bizonylat;

    public function getBizonylat() {
        return $this->bizonylat;
    }

    public function getBizonylatNev() {
        if ($this->bizonylat) {
            return $this->bizonylat->getId();
        }
        return '';
    }

    public function setBizonylat(Bizonylatfej $r) {
        $this->bizonylat = $r;
    }

    public function removeBizonylat() {
        if ($this->bizonylat !== null) {
            $r = $this->bizonylat;
            $this->bizonylat = null;
            $r->removeBizonylatDok($this);
        }
    }

}