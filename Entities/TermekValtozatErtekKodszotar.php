<?php
namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\TermekValtozatErtekKodszotarRepository")
 * @ORM\Table(name="termekvaltozatertekkodszotar",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class TermekValtozatErtekKodszotar {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /** @ORM\Column(type="string",length=255,nullable=true) */
    private $ertek;

    /**
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    private $kod;

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getErtek() {
        return $this->ertek;
    }

    /**
     * @param mixed $ertek
     */
    public function setErtek($ertek) {
        $this->ertek = $ertek;
    }

    /**
     * @return mixed
     */
    public function getKod() {
        return $this->kod;
    }

    /**
     * @param mixed $kod
     */
    public function setKod($kod) {
        $this->kod = $kod;
    }

}