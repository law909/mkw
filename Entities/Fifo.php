<?php
namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\FifoRepository")
 * @ORM\Table(name="fifo")
 */
class Fifo {

	/**
	 * @ORM\Id @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Raktar")
     * @ORM\JoinColumn(name="raktar_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $raktar;

    /**
     * @ORM\ManyToOne(targetEntity="Termek")
     * @ORM\JoinColumn(name="termek_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $termek;

    /**
     * @ORM\ManyToOne(targetEntity="TermekValtozat")
     * @ORM\JoinColumn(name="termekvaltozat_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $termekvaltozat;

    /**
     * @ORM\ManyToOne(targetEntity="Bizonylatfej")
     * @ORM\JoinColumn(name="kibizonylatfej_id", referencedColumnName="id",nullable=true,onDelete="cascade")
     */
    private $kibizonylatfej;

    /**
     * @ORM\ManyToOne(targetEntity="Bizonylattetel")
     * @ORM\JoinColumn(name="kibizonylattetel_id", referencedColumnName="id",nullable=true,onDelete="cascade")
     */
    private $kibizonylattetel;

    /**
     * @ORM\ManyToOne(targetEntity="Bizonylatfej")
     * @ORM\JoinColumn(name="bebizonylatfej_id", referencedColumnName="id",nullable=true,onDelete="cascade")
     */
    private $bebizonylatfej;

    /**
     * @ORM\ManyToOne(targetEntity="Bizonylattetel")
     * @ORM\JoinColumn(name="bebizonylattetel_id", referencedColumnName="id",nullable=true,onDelete="cascade")
     */
    private $bebizonylattetel;

    /** @ORM\Column(type="decimal",precision=14,scale=2,nullable=true) */
    private $mennyiseg = 0;


	public function getId() {
		return $this->id;
	}

    public function getRaktar() {
        return $this->raktar;
    }

    public function getTermek() {
        return $this->termek;
    }

    public function getTermekvaltozat() {
        return $this->termekvaltozat;
    }

    public function getKibizonylatfej() {
		return $this->kibizonylatfej;
	}


    public function getKibizonylattetel() {
		return $this->kibizonylattetel;
	}

    public function getBebizonylatfej() {
		return $this->bebizonylatfej;
	}

    public function getBebizonylattetel() {
		return $this->bebizonylattetel;
	}

    public function getMennyiseg() {
        return $this->mennyiseg;
    }
}
