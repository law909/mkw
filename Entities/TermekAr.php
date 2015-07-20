<?php
namespace Entities;

/**
 * @Entity(repositoryClass="Entities\TermekArRepository")
 * @Table(name="termekar",indexes={
 *	@index(name="termekarazonosito_idx",columns={"termek_id","valutanem_id","azonosito"})
 * })
 */
class TermekAr {
	/**
	 * @Id @Column(type="integer")
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @gedmo:Timestampable(on="create")
	 * @Column(type="datetime",nullable=true)
	 */
	private $created;

	/**
	 * @gedmo:Timestampable(on="create")
	 * @gedmo:Timestampable(on="update")
	 * @Column(type="datetime",nullable=true)
	 */
	private $lastmod;

	/**
	 * @ManyToOne(targetEntity="Termek",inversedBy="termekarak")
	 * @JoinColumn(name="termek_id",referencedColumnName="id",onDelete="cascade")
	 */
	private $termek;

	/** @Column(type="string",length=255,nullable=true) */
	private $azonosito;

    /** @Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $netto;

    /** @Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $brutto;


    /**
     * @ManyToOne(targetEntity="Valutanem",inversedBy="bizonylatfejek")
     * @JoinColumn(name="valutanem_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     */
    private $valutanem;

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
//		$termek->addTermekKep($this);
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

    public function getAzonosito() {
        return $this->azonosito;
    }

    public function setAzonosito($val) {
        $this->azonosito = $val;
    }

    public function getValutanem() {
        return $this->valutanem;
    }

    public function getValutanemId() {
        $vn = $this->getValutanem();
        if ($vn) {
            return $vn->getId();
        }
        return '';
    }

    public function setValutanem($val) {
        if (!($val instanceof \Entities\Valutanem)) {
            $val = \mkw\Store::getEm()->getRepository('Entities\Valutanem')->find($val);
        }
        if ($this->valutanem !== $val) {
            $this->valutanem = $val;
        }
    }

    public function removeValutanem() {
        if ($this->valutanem !== null) {
            $this->valutanem = null;
        }
    }

    public function getNetto() {
        return $this->netto;
    }

    public function setNetto($val) {
        $this->netto = $val;
        $this->brutto = $this->getTermek()->getAfa()->calcBrutto($val);
    }

    public function getBrutto() {
        return $this->brutto;
    }

    public function setBrutto($val) {
        $this->brutto = $val;
        $this->netto = $this->getTermek()->getAfa()->calcNetto($val);
    }
}