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
        1 => 'ingyen szállítás'
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

	public function getId() {
		return $this->id;
	}

    public function setId($id) {
        $this->id = $id;
    }

    public function generateId() {
        $this->id = uniqid(\mkw\store::getParameter(\mkw\consts::KuponElotag, 'MKW'));
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

    public function doFelhasznalt() {
        $this->setLejart(1);
    }
}
