<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;
use mkw\store;

/**
 * @ORM\Entity(repositoryClass="Entities\TermekDokRepository")
 */
class TermekDok extends Dokumentumtar
{

    /**
     * @ORM\ManyToOne(targetEntity="termek",inversedBy="termekdokok")
     * @ORM\JoinColumn(name="termek_id",referencedColumnName="id",onDelete="cascade")
     */
    private $termek;

    /**
     * @return Termek
     */
    public function getTermek()
    {
        return $this->termek;
    }

    public function getTermekNev()
    {
        if ($this->termek) {
            return $this->termek->getNev();
        }
        return '';
    }

    public function setTermek(Termek $r)
    {
        $this->termek = $r;
    }

    public function removeTermek()
    {
        if ($this->termek !== null) {
            $r = $this->termek;
            $this->termek = null;
            $r->removeTermekDok($this);
        }
    }

}