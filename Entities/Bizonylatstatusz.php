<?php

namespace Entities;

/**
 * @Entity(repositoryClass="Entities\BizonylatstatuszRepository")
 * @Table(name="bizonylatstatusz")
 */
class Bizonylatstatusz {

    /**
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Column(type="string",length=255)
     */
    private $nev;

    /**
     * @ManyToOne(targetEntity="Emailtemplate",inversedBy="bizonylatstatuszok")
     * @JoinColumn(name="emailtemplate_id", referencedColumnName="id",nullable=true,onDelete="no action")
     */
    private $emailtemplate;
	/** @OneToMany(targetEntity="Bizonylatfej", mappedBy="bizonylatstatusz",cascade={"persist","remove"}) */
	private $bizonylatfejek;

	public function __construct() {
		$this->bizonylatfejek=new \Doctrine\Common\Collections\ArrayCollection();
	}

    public function getId() {
        return $this->id;
    }

    public function getNev() {
        return $this->nev;
    }

    public function setNev($nev) {
        $this->nev = $nev;
    }

    public function getEmailtemplate() {
        return $this->emailtemplate;
    }

    public function getEmailtemplateId() {
        if ($this->emailtemplate) {
            return $this->emailtemplate->getId();
        }
        return null;
    }

    public function getEmailtemplateNev() {
        if ($this->emailtemplate) {
            return $this->emailtemplate->getNev();
        }
        return null;
    }

    public function setEmailtemplate($val) {
        if ($this->emailtemplate !== $val) {
            $this->emailtemplate = $val;
        }
    }

    public function removeEmailtemplate() {
        if ($this->emailtemplate !== null) {
            $this->emailtemplate = null;
        }
    }

}
