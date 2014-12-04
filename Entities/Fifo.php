<?php
namespace Entities;

/**
 * @Entity(repositoryClass="Entities\FifoRepository")
 * @Table(name="fifo")
 */
class Fifo {

	/**
	 * @Id @Column(type="integer")
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;

    /**
     * @ManyToOne(targetEntity="Raktar")
     * @JoinColumn(name="raktar_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $raktar;

    /**
     * @ManyToOne(targetEntity="Termek")
     * @JoinColumn(name="termek_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $termek;

    /**
     * @ManyToOne(targetEntity="TermekValtozat")
     * @JoinColumn(name="termekvaltozat_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $termekvaltozat;

    /**
     * @ManyToOne(targetEntity="Bizonylatfej")
     * @JoinColumn(name="kibizonylatfej_id", referencedColumnName="id",nullable=true,onUpdate="cascade",onDelete="cascade")
     */
    private $kibizonylatfej;

    /**
     * @ManyToOne(targetEntity="Bizonylattetel")
     * @JoinColumn(name="kibizonylattetel_id", referencedColumnName="id",nullable=true,onUpdate="cascade",onDelete="cascade")
     */
    private $kibizonylattetel;

    /**
     * @ManyToOne(targetEntity="Bizonylatfej")
     * @JoinColumn(name="bebizonylatfej_id", referencedColumnName="id",nullable=true,onUpdate="cascade",onDelete="cascade")
     */
    private $bebizonylatfej;

    /**
     * @ManyToOne(targetEntity="Bizonylattetel")
     * @JoinColumn(name="bebizonylattetel_id", referencedColumnName="id",nullable=true,onUpdate="cascade",onDelete="cascade")
     */
    private $bebizonylattetel;

    /** @Column(type="decimal",precision=14,scale=2,nullable=true) */
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
