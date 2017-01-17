<?php
namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\PartnerTermekSzerzodesRepository")
 * @ORM\Table(name="partnertermekszerzodes",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class PartnerTermekSzerzodes {

	/**
	 * @ORM\Id @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @Gedmo\Timestampable(on="create")
	 * @ORM\Column(type="datetime",nullable=true)
	 */
	private $created;

	/**
	 * @Gedmo\Timestampable(on="update")
	 * @ORM\Column(type="datetime",nullable=true)
	 */
	private $lastmod;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $ervenyestol;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $ervenyesig;

	/**
	 * @ORM\ManyToOne(targetEntity="Termek",inversedBy="partnertermekszerzodesek")
	 * @ORM\JoinColumn(name="termek_id",referencedColumnName="id",nullable=false,onDelete="cascade")
	 */
	private $termek;

	/**
	 * @ORM\ManyToOne(targetEntity="Partner",inversedBy="partnertermekszerzodesek")
	 * @ORM\JoinColumn(name="partner_id",referencedColumnName="id",nullable=false,onDelete="cascade")
	 */
	private $partner;

    /**
     * @ORM\ManyToOne(targetEntity="Valutanem",inversedBy="bizonylattetelek")
     * @ORM\JoinColumn(name="valutanem_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Valutanem
     */
    private $valutanem;

    /** @ORM\OneToMany(targetEntity="PartnerTermekSzerzodesAr", mappedBy="partnertermekszerzodes",cascade={"persist"}) */
    private $arak;


    public function getId() {
		return $this->id;
	}

	public function getTermek() {
		return $this->termek;
	}

	public function getTermekNev() {
		if ($this->termek) {
			return $this->termek->getNev();
		}
		return '';
	}

	public function setTermek(Termek $termek) {
		$this->termek = $termek;
	}

	public function removeTermek() {
		if ($this->termek !== null) {
			$termek = $this->termek;
			$this->termek = null;
		}
	}

	public function getLastmod() {
		return $this->lastmod;
	}

	public function getCreated() {
		return $this->created;
	}

    /**
     * @return mixed
     */
    public function getPartner() {
        return $this->partner;
    }

    /**
     * @param mixed $partner
     */
    public function setPartner($partner) {
        $this->partner = $partner;
    }

    /**
     * @return Valutanem
     */
    public function getValutanem() {
        return $this->valutanem;
    }

    /**
     * @param Valutanem $valutanem
     */
    public function setValutanem($valutanem) {
        $this->valutanem = $valutanem;
    }

    /**
     * @return mixed
     */
    public function getErvenyestol() {
        return $this->ervenyestol;
    }

    /**
     * @param mixed $ervenyestol
     */
    public function setErvenyestol($ervenyestol) {
        $this->ervenyestol = $ervenyestol;
    }

    /**
     * @return mixed
     */
    public function getErvenyesig() {
        return $this->ervenyesig;
    }

    /**
     * @param mixed $ervenyesig
     */
    public function setErvenyesig($ervenyesig) {
        $this->ervenyesig = $ervenyesig;
    }

}