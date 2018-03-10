<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;
use mkw\store;

/**
 * @ORM\Entity(repositoryClass="Entities\RendezvenyDokRepository")
 */
class RendezvenyDok extends Dokumentumtar {

    /**
     * @ORM\ManyToOne(targetEntity="Rendezveny",inversedBy="rendezvenydokok")
     * @ORM\JoinColumn(name="rendezveny_id",referencedColumnName="id",onDelete="cascade")
     */
    private $rendezveny;

    public function getRendezveny() {
        return $this->rendezveny;
    }

    public function getRendezvenyNev() {
        if ($this->rendezveny) {
            return $this->rendezveny->getNev();
        }
        return '';
    }

    public function setRendezveny(Rendezveny $r) {
        $this->rendezveny = $r;
    }

    public function removeRendezveny() {
        if ($this->rendezveny !== null) {
            $r = $this->rendezveny;
            $this->rendezveny = null;
            $r->removeRendezvenyDok($this);
        }
    }

}