<?php
namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Entities\KuponRepository")
 * @ORM\Table(name="kupon")
 */
class Kupon {

    private static $lejaratlista = array(
        0 => 'érvényes',
        1 => 'felhasznált',
        2 => 'lejárt'
    );

    private static $tipuslista = array(
        1 => 'ingyen szállítás',
        2 => 'vásárlási utalvány'
    );

    /**
     * @ORM\Id @ORM\Column(type="string",length=30,nullable=false)
     */
    private $id;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $created;

	/**
	 * @ORM\Column(type="integer")
	 */
	private $tipus = 1;

    /**
     * @ORM\Column(type="integer")
     */
    private $lejart = 0;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $osszeg = 0;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $minimumosszeg = 0;

    public function toLista() {
        $ret = array();
        $ret['id'] = $this->getId();
        $ret['tipus'] = $this->getTipus();
        $ret['tipusstr'] = $this->getTipusStr();
        $ret['osszeg'] = $this->getOsszeg();
        $ret['ervenyes'] = $this->isErvenyes();
        $ret['minimumosszeg'] = $this->getMinimumosszeg();
        return $ret;
    }

	public function getId() {
		return $this->id;
	}

    public function setId($id) {
        $this->id = $id;
    }

    public function generateId() {
        if (!$this->id) {
            $this->id = uniqid(\mkw\store::getParameter(\mkw\consts::KuponElotag, 'MKW'));
        }
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getCreated() {
        return $this->created;
    }

    public function getCreatedStr() {
        if ($this->getCreated()) {
            return $this->getCreated()->format(\mkw\store::$DateTimeFormat);
        }
        return '';
    }

    public function clearCreated() {
        $this->created = null;
    }

    /**
     * @return mixed
     */
    public function getLejart() {
        return $this->lejart;
    }

    public function getLejartStr() {
        if (array_key_exists($this->getLejart(), self::$lejaratlista)) {
            return self::$lejaratlista[$this->getLejart()];
        }
        return 'ismeretlen';
    }

    /**
     * @param mixed $lejart
     */
    public function setLejart($lejart) {
        $this->lejart = $lejart;
    }

    public function isErvenyes() {
        return $this->getLejart() === 0;
    }

    /**
     * @return mixed
     */
    public function getTipus() {
        return $this->tipus;
    }

    public function getTipusStr() {
        if (array_key_exists($this->getTipus(), self::$tipuslista)) {
            return self::$tipuslista[$this->getTipus()];
        }
        return 'ismeretlen';
    }

    /**
     * @param mixed $tipus
     */
    public function setTipus($tipus) {
        $this->tipus = $tipus;
    }

    public static function getTipusLista() {
        return self::$tipuslista;
    }

    public static function getLejaratLista() {
        return self::$lejaratlista;
    }

    public function isIngyenSzallitas() {
        return $this->getTipus() === 1;
    }

    public function isVasarlasiUtalvany() {
        return $this->getTipus() === 2;
    }

    public function doFelhasznalt() {
        $this->setLejart(1);
    }

    /**
     * @return mixed
     */
    public function getOsszeg() {
        return $this->osszeg;
    }

    /**
     * @param mixed $osszeg
     */
    public function setOsszeg($osszeg) {
        $this->osszeg = $osszeg;
    }

    public function getReportfilename() {
        return 'rep_kupon' . $this->getTipus() . '.tpl';
    }

    /**
     * @return int
     */
    public function getMinimumosszeg() {
        return $this->minimumosszeg;
    }

    /**
     * @param int $minimumosszeg
     */
    public function setMinimumosszeg($minimumosszeg): void {
        $this->minimumosszeg = $minimumosszeg;
    }

    public function isMinimumosszegMegvan($osszeg) {
        return $osszeg >= $this->getMinimumosszeg();
    }
}
