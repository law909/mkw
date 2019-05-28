<?php

namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\MERepository")
 * @ORM\Table(name="me")
 */
class ME {
    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(type="string",length=255)
     */
    private $nev;
    /**
     * @ORM\Column(type="string",length=30, nullable=true)
     */
    private $navtipus;

    public function getId() {
        return $this->id;
    }

    public function getNev() {
        return $this->nev;
    }

    public function setNev($nev) {
        $this->nev = $nev;
    }

    /**
     * @return mixed
     */
    public function getNavtipus() {
        return $this->navtipus;
    }

    /**
     * @param mixed $navtipus
     */
    public function setNavtipus($navtipus) {
        $this->navtipus = $navtipus;
    }

}
