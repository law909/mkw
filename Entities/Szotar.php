<?php
namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\SzotarRepository")
 * @ORM\Table(name="szotar",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class Szotar {
    /**
     * @ORM\Id @ORM\Column(type="string",length=255,nullable=false)
     */
    private $mit;
    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $mire;

    /**
     * @return mixed
     */
    public function getMit() {
        return $this->mit;
    }

    /**
     * @param mixed $mit
     */
    public function setMit($mit) {
        $this->mit = $mit;
    }

    /**
     * @return mixed
     */
    public function getMire() {
        return $this->mire;
    }

    /**
     * @param mixed $mire
     */
    public function setMire($mire) {
        $this->mire = $mire;
    }

}