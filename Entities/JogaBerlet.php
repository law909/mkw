<?php

namespace Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;


/** @ORM\Entity(repositoryClass="Entities\JogaBerletRepository")
 * @ORM\Table(name="jogaberlet",options={"collate"="utf8_hungarian_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class JogaBerlet {

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
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="Dolgozo")
     * @ORM\JoinColumn(name="createdby", referencedColumnName="id")
     */
    private $createdby;

    /**
     * @Gedmo\Blameable(on="update")
     * @ORM\ManyToOne(targetEntity="Dolgozo")
     * @ORM\JoinColumn(name="updatedby", referencedColumnName="id")
     */
    private $updatedby;

    /**
     * @ORM\ManyToOne(targetEntity="Termek",inversedBy="jogaberletek")
     * @ORM\JoinColumn(name="termek_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Termek
     */
    private $termek;

    /**
     * @ORM\ManyToOne(targetEntity="Partner")
     * @ORM\JoinColumn(name="partner_id", referencedColumnName="id",nullable=true,onDelete="restrict")
     * @var \Entities\Partner
     */
    private $partner;

    /** @ORM\Column(type="date",nullable=false) */
    private $vasarlasnapja;

    /** @ORM\Column(type="date",nullable=true) */
    private $lejaratdatum;

    /** @ORM\Column(type="integer",nullable=true) */
    private $alkalom;

    /** @ORM\Column(type="integer",nullable=true) */
    private $ervenyesseg;

    /** @ORM\Column(type="integer",nullable=true) */
    private $elfogyottalkalom;

    /** @ORM\Column(type="boolean") */
    private $lejart = false;

    /** @ORM\Column(type="integer",nullable=true) */
    private $offlineelfogyottalkalom;

    /** @ORM\Column(type="boolean") */
    private $nincsfizetve = false;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $nettoegysar;

    /** @ORM\Column(type="decimal",precision=14,scale=4,nullable=true) */
    private $bruttoegysar;

    public function sendEmail($sablonid) {
        $emailtpl = \mkw\store::getEm()->getRepository('Entities\Emailtemplate')->find($sablonid);
        if (\mkw\store::isSendableEmail($this->getPartneremail()) && $emailtpl) {

            $subject = \mkw\store::getTemplateFactory()->createMainView('string:' . $emailtpl->getTargy());
            $body = \mkw\store::getTemplateFactory()->createMainView('string:' . str_replace('&#39;', '\'', html_entity_decode($emailtpl->getHTMLSzoveg())));
            $body->setVar('partnernev', $this->getPartnernev());
            $body->setVar('datum', $this->getVasarlasnapjaStr());
            $body->setVar('berlet', $this->getNev());
            $body->setVar('ar', $this->getBruttoegysar());

            $mailer = \mkw\store::getMailer();

            $mailer->addTo($this->getPartneremail());
            $mailer->setSubject($subject->getTemplateResult());
            $mailer->setMessage($body->getTemplateResult());

            $mailer->send();
        }
    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    public function getNev() {
        return $this->getTermeknev();
    }

    public function getFullNev() {
        return $this->getNev() . '('
            . $this->getVasarlasnapjaStr() . ', '
            . ($this->getElfogyottalkalom() + $this->getOfflineelfogyottalkalom()) . ' alkalom, '
            . $this->getLejaratdatumStr() . ')';
    }
    public function getLastmod() {
        return $this->lastmod;
    }

    public function clearLastmod() {
        $this->lastmod = null;
    }

    public function getCreated() {
        return $this->created;
    }

    public function clearCreated() {
        $this->created = null;
    }
    /**
     * @return mixed
     */
    public function getCreatedby() {
        return $this->createdby;
    }

    public function getCreatedbyId() {
        if ($this->createdby) {
            return $this->createdby->getId();
        }
        return null;
    }

    public function getCreatedbyNev() {
        if ($this->createdby) {
            return $this->createdby->getNev();
        }
        return null;
    }

    /**
     * @return mixed
     */
    public function getUpdatedby() {
        return $this->updatedby;
    }

    public function getUpdatedbyId() {
        if ($this->updatedby) {
            return $this->updatedby->getId();
        }
        return null;
    }

    public function getUpdatedbyNev() {
        if ($this->updatedby) {
            return $this->updatedby->getNev();
        }
        return null;
    }

    public function getTermek() {
        return $this->termek;
    }

    public function getTermekId() {
        if ($this->termek) {
            return $this->termek->getId();
        }
        return '';
    }

    /**
     * @param \Entities\Termek $val
     */
    public function setTermek($val) {
        if ($this->termek !== $val) {
            if (!$val) {
                $this->removeTermek();
                $this->alkalom = 0;
                $this->ervenyesseg = 0;
            }
            else {
                $this->termek = $val;
                $this->alkalom = $val->getJogaalkalom();
                $this->ervenyesseg = $val->getJogaervenyesseg();
                $this->calcLejaratDatum();
            }
        }
    }

    public function removeTermek() {
        if ($this->termek !== null) {
            $this->termek = null;
        }
    }

    public function getTermeknev() {
        if ($this->termek) {
            return $this->termek->getNev();
        }
        return '';
    }

    /**
     * @return \Entities\Partner
     */
    public function getPartner() {
        return $this->partner;
    }

    public function getPartnerId() {
        if ($this->partner) {
            return $this->partner->getId();
        }
        return '';
    }

    /**
     * @param \Entities\Partner $val
     */
    public function setPartner($val) {
        if ($this->partner !== $val) {
            if (!$val) {
                $this->removePartner();
            }
            else {
                $this->partner = $val;
            }
        }
    }

    public function removePartner() {
        if ($this->partner !== null) {
            $this->partner = null;
        }
    }

    public function getPartnernev() {
        if ($this->partner) {
            return $this->partner->getNev();
        }
        return '';
    }

    public function getPartneremail() {
        if ($this->partner) {
            return $this->partner->getEmail();
        }
        return '';
    }

    public function getVasarlasnapja() {
        return $this->vasarlasnapja;
    }

    public function getVasarlasnapjaStr() {
        if ($this->getVasarlasnapja()) {
            return $this->getVasarlasnapja()->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    public function setVasarlasnapja($adat = '') {
        if (is_a($adat, 'DateTime')) {
            $this->vasarlasnapja = $adat;
        }
        else {
            if ($adat == '') {
                $adat = date(\mkw\store::$DateFormat);
            }
            $this->vasarlasnapja = new \DateTime(\mkw\store::convDate($adat));
        }
        $this->calcLejaratDatum();
    }

    /**
     * @return mixed
     */
    public function getAlkalom() {
        return $this->alkalom;
    }

    /**
     * @param mixed $alkalom
     */
    public function setAlkalom($alkalom) {
        $this->alkalom = $alkalom;
    }

    /**
     * @return mixed
     */
    public function getErvenyesseg() {
        return $this->ervenyesseg;
    }

    /**
     * @param mixed $ervenyesseg
     */
    public function setErvenyesseg($ervenyesseg) {
        $this->ervenyesseg = $ervenyesseg;
    }

    /**
     * @return mixed
     */
    public function getElfogyottalkalom() {
        return $this->elfogyottalkalom;
    }

    /**
     * @param mixed $elfogyottalkalom
     */
    public function setElfogyottalkalom($elfogyottalkalom) {
        $this->elfogyottalkalom = $elfogyottalkalom;
    }

    public function getLejaratdatum() {
        return $this->lejaratdatum;
    }

    public function getLejaratdatumStr() {
        if ($this->getLejaratdatum()) {
            return $this->getLejaratdatum()->format(\mkw\store::$DateFormat);
        }
        return '';
    }

    public function setLejaratdatum($adat = '') {
        if (is_a($adat, 'DateTime')) {
            $this->lejaratdatum = $adat;
        }
        else {
            if ($adat == '') {
                $adat = date(\mkw\store::$DateFormat);
            }
            $this->lejaratdatum = new \DateTime(\mkw\store::convDate($adat));
        }
    }

    public function calcLejaratDatum() {
        if ($this->getVasarlasnapja()) {
            $x = clone $this->getVasarlasnapja();
            $x->add(new \DateInterval('P' . $this->getErvenyesseg() . 'W'));
            $this->setLejaratdatum($x);
        }
    }

    /**
     * @return bool
     */
    public function isLejart() {
        return $this->lejart;
    }

    /**
     * @param bool $lejart
     */
    public function setLejart($lejart) {
        $this->lejart = $lejart;
    }

    /**
     * @return mixed
     */
    public function getOfflineelfogyottalkalom() {
        return $this->offlineelfogyottalkalom;
    }

    /**
     * @param mixed $offlineelfogyottalkalom
     */
    public function setOfflineelfogyottalkalom($offlineelfogyottalkalom) {
        $this->offlineelfogyottalkalom = $offlineelfogyottalkalom;
    }

    /**
     * @return bool
     */
    public function isNincsfizetve() {
        return $this->nincsfizetve;
    }

    /**
     * @param bool $nincsfizetve
     */
    public function setNincsfizetve($nincsfizetve) {
        $this->nincsfizetve = $nincsfizetve;
    }

    public function calcLejart($num = 0) {
        $jrrepo = \mkw\store::getEm()->getRepository('Entities\JogaReszvetel');
        $y = $jrrepo->getCountByBerlet($this->getId());
        $this->setElfogyottalkalom($y + $num);
        $this->setLejart($this->getAlkalom() <= $this->getElfogyottalkalom() + $this->getOfflineelfogyottalkalom());

        if ($num > 0) {
            if ($this->isNincsfizetve() && ($this->getElfogyottalkalom() + $this->getOfflineelfogyottalkalom() > 1)) {
                $this->sendEmail(\mkw\store::getParameter(\mkw\consts::JogaBerletFelszolitoSablon));
            }

            if ($this->isUtolsoElottiAlkalom()) {
                $this->sendEmail(\mkw\store::getParameter(\mkw\consts::JogaBerletLefogjarniSablon));
            }
            elseif ($this->isUtolsoAlkalom()) {
                $this->sendEmail(\mkw\store::getParameter(\mkw\consts::JogaBerletLejartSablon));
            }
        }
    }

    public function isUtolsoElottiAlkalom() {
        \mkw\store::writelog('UE: getAlkalom: ' . $this->getAlkalom());
        \mkw\store::writelog('UE: getElfogyottAlkalom: ' . $this->getElfogyottalkalom());
        \mkw\store::writelog('UE: getOfflinelfogyottAlkalom: ' . $this->getOfflineelfogyottalkalom());
        \mkw\store::writelog('UE: vege: ' . $this->getAlkalom() - $this->getElfogyottalkalom() - $this->getOfflineelfogyottalkalom());
        return $this->getAlkalom() - $this->getElfogyottalkalom() - $this->getOfflineelfogyottalkalom() == 1;
    }

    public function isUtolsoAlkalom() {
        \mkw\store::writelog('U: getAlkalom: ' . $this->getAlkalom());
        \mkw\store::writelog('U: getElfogyottAlkalom: ' . $this->getElfogyottalkalom());
        \mkw\store::writelog('U: getOfflinelfogyottAlkalom: ' . $this->getOfflineelfogyottalkalom());
        \mkw\store::writelog('U: vege: ' . $this->getAlkalom() == ($this->getElfogyottalkalom() + $this->getOfflineelfogyottalkalom()));
        return $this->getAlkalom() == ($this->getElfogyottalkalom() + $this->getOfflineelfogyottalkalom());
    }

    /**
     * @return mixed
     */
    public function getNettoegysar() {
        return $this->nettoegysar;
    }

    /**
     * @param mixed $nettoegysar
     */
    public function setNettoegysar($nettoegysar) {
        $this->nettoegysar = $nettoegysar;
    }

    /**
     * @return mixed
     */
    public function getBruttoegysar() {
        return $this->bruttoegysar;
    }

    /**
     * @param mixed $bruttoegysar
     */
    public function setBruttoegysar($bruttoegysar) {
        $this->bruttoegysar = $bruttoegysar;
    }
}