<?php

namespace Entities;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Doctrine\ORM\Mapping\Entity(repositoryClass="Entities\MPTSzekcioRepository")
 * @Doctrine\ORM\Mapping\Table(name="mptszekcio",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class MPTSzekcio {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string",length=255)
     */
    private $nev;

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getNev() {
        return $this->nev;
    }

    /**
     * @param mixed $nev
     */
    public function setNev($nev) {
        $this->nev = $nev;
    }

}