<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\IdopontDokRepository")
 */
class IdopontDok extends Dokumentumtar {

    /**
     * @ORM\ManyToOne(targetEntity="Idopont",inversedBy="idopontdokok")
     * @ORM\JoinColumn(name="idopont_id",referencedColumnName="id",onDelete="cascade")
     */
    private $idopont;

    /**
     * Átmeneti: a rendezvény modul összevonása előtti kapcsolóoszlop. Csak azért van leképezve, hogy a
     * schema-tool ne dobja el, mielőtt a runonce 0148 átmásolja az idopont_id-be – utána elhagyható
     * (entitásból törölni + egy runonce blokk DROP COLUMN-nal).
     *
     * @ORM\Column(type="integer",name="rendezveny_id",nullable=true)
     */
    private $rendezvenyid;

    public function getIdopont() {
        return $this->idopont;
    }

    public function getIdopontNev() {
        if ($this->idopont) {
            return $this->idopont->getNev();
        }
        return '';
    }

    public function setIdopont(Idopont $r) {
        $this->idopont = $r;
    }

    public function removeIdopont() {
        if ($this->idopont !== null) {
            $r = $this->idopont;
            $this->idopont = null;
            $r->removeIdopontDok($this);
        }
    }

}
