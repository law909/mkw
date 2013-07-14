<?php

namespace Proxies;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class EntitiesBizonylattetelProxy extends \Entities\Bizonylattetel implements \Doctrine\ORM\Proxy\Proxy
{
    private $_entityPersister;
    private $_identifier;
    public $__isInitialized__ = false;
    public function __construct($entityPersister, $identifier)
    {
        $this->_entityPersister = $entityPersister;
        $this->_identifier = $identifier;
    }
    /** @private */
    public function __load()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;

            if (method_exists($this, "__wakeup")) {
                // call this after __isInitialized__to avoid infinite recursion
                // but before loading to emulate what ClassMetadata::newInstance()
                // provides.
                $this->__wakeup();
            }

            if ($this->_entityPersister->load($this->_identifier, $this) === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            unset($this->_entityPersister, $this->_identifier);
        }
    }
    
    
    public function setPersistentData()
    {
        $this->__load();
        return parent::setPersistentData();
    }

    public function getId()
    {
        $this->__load();
        return parent::getId();
    }

    public function getBizonylatfej()
    {
        $this->__load();
        return parent::getBizonylatfej();
    }

    public function getBizonylatfejId()
    {
        $this->__load();
        return parent::getBizonylatfejId();
    }

    public function setBizonylatfej(\Entities\Bizonylatfej $val)
    {
        $this->__load();
        return parent::setBizonylatfej($val);
    }

    public function removeBizonylatfej()
    {
        $this->__load();
        return parent::removeBizonylatfej();
    }

    public function getMozgat()
    {
        $this->__load();
        return parent::getMozgat();
    }

    public function setMozgat()
    {
        $this->__load();
        return parent::setMozgat();
    }

    public function getArvaltoztat()
    {
        $this->__load();
        return parent::getArvaltoztat();
    }

    public function setArvaltoztat($val)
    {
        $this->__load();
        return parent::setArvaltoztat($val);
    }

    public function getStorno()
    {
        $this->__load();
        return parent::getStorno();
    }

    public function setStorno($val)
    {
        $this->__load();
        return parent::setStorno($val);
    }

    public function getStornozott()
    {
        $this->__load();
        return parent::getStornozott();
    }

    public function setStornozott($val)
    {
        $this->__load();
        return parent::setStornozott($val);
    }

    public function getTermek()
    {
        $this->__load();
        return parent::getTermek();
    }

    public function getTermekId()
    {
        $this->__load();
        return parent::getTermekId();
    }

    public function setTermek(\Entities\Termek $val)
    {
        $this->__load();
        return parent::setTermek($val);
    }

    public function removeTermek()
    {
        $this->__load();
        return parent::removeTermek();
    }

    public function getTermeknev()
    {
        $this->__load();
        return parent::getTermeknev();
    }

    public function setTermeknev($val)
    {
        $this->__load();
        return parent::setTermeknev($val);
    }

    public function getCikkszam()
    {
        $this->__load();
        return parent::getCikkszam();
    }

    public function setCikkszam($val)
    {
        $this->__load();
        return parent::setCikkszam($val);
    }

    public function getIdegencikkszam()
    {
        $this->__load();
        return parent::getIdegencikkszam();
    }

    public function setIdegencikkszam($val)
    {
        $this->__load();
        return parent::setIdegencikkszam($val);
    }

    public function getME()
    {
        $this->__load();
        return parent::getME();
    }

    public function setME($val)
    {
        $this->__load();
        return parent::setME($val);
    }

    public function getVtsz()
    {
        $this->__load();
        return parent::getVtsz();
    }

    public function getVtsznev()
    {
        $this->__load();
        return parent::getVtsznev();
    }

    public function getVtszId()
    {
        $this->__load();
        return parent::getVtszId();
    }

    public function setVtsz(\Entities\Vtsz $val)
    {
        $this->__load();
        return parent::setVtsz($val);
    }

    public function removeVtsz()
    {
        $this->__load();
        return parent::removeVtsz();
    }

    public function getAfa()
    {
        $this->__load();
        return parent::getAfa();
    }

    public function getAfanev()
    {
        $this->__load();
        return parent::getAfanev();
    }

    public function getAfakulcs()
    {
        $this->__load();
        return parent::getAfakulcs();
    }

    public function getAfaId()
    {
        $this->__load();
        return parent::getAfaId();
    }

    public function setAfa(\Entities\Afa $val)
    {
        $this->__load();
        return parent::setAfa($val);
    }

    public function removeAfa()
    {
        $this->__load();
        return parent::removeAfa();
    }

    public function getGymennyiseg()
    {
        $this->__load();
        return parent::getGymennyiseg();
    }

    public function setGymennyiseg($val)
    {
        $this->__load();
        return parent::setGymennyiseg($val);
    }

    public function getMennyiseg()
    {
        $this->__load();
        return parent::getMennyiseg();
    }

    public function setMennyiseg($val)
    {
        $this->__load();
        return parent::setMennyiseg($val);
    }

    public function getNettoegysar()
    {
        $this->__load();
        return parent::getNettoegysar();
    }

    public function setNettoegysar($val)
    {
        $this->__load();
        return parent::setNettoegysar($val);
    }

    public function getBruttoegysar()
    {
        $this->__load();
        return parent::getBruttoegysar();
    }

    public function setBruttoegysar($val)
    {
        $this->__load();
        return parent::setBruttoegysar($val);
    }

    public function getNettoegysarhuf()
    {
        $this->__load();
        return parent::getNettoegysarhuf();
    }

    public function setNettoegysarhuf($val)
    {
        $this->__load();
        return parent::setNettoegysarhuf($val);
    }

    public function getBruttoegysarhuf()
    {
        $this->__load();
        return parent::getBruttoegysarhuf();
    }

    public function setBruttoegysarhuf($val)
    {
        $this->__load();
        return parent::setBruttoegysarhuf($val);
    }

    public function getEnettoegysar()
    {
        $this->__load();
        return parent::getEnettoegysar();
    }

    public function getEbruttoegysar()
    {
        $this->__load();
        return parent::getEbruttoegysar();
    }

    public function getEnettoegysarhuf()
    {
        $this->__load();
        return parent::getEnettoegysarhuf();
    }

    public function getEbruttoegysarhuf()
    {
        $this->__load();
        return parent::getEbruttoegysarhuf();
    }

    public function getNetto()
    {
        $this->__load();
        return parent::getNetto();
    }

    public function setNetto($val)
    {
        $this->__load();
        return parent::setNetto($val);
    }

    public function getAfaertek()
    {
        $this->__load();
        return parent::getAfaertek();
    }

    public function setAfaertek($val)
    {
        $this->__load();
        return parent::setAfaertek($val);
    }

    public function getBrutto()
    {
        $this->__load();
        return parent::getBrutto();
    }

    public function setBrutto($val)
    {
        $this->__load();
        return parent::setBrutto($val);
    }

    public function getValutanem()
    {
        $this->__load();
        return parent::getValutanem();
    }

    public function getValutanemnev()
    {
        $this->__load();
        return parent::getValutanemnev();
    }

    public function getValutanemId()
    {
        $this->__load();
        return parent::getValutanemId();
    }

    public function setValutanem(\Entities\Valutanem $val)
    {
        $this->__load();
        return parent::setValutanem($val);
    }

    public function removeValutanem()
    {
        $this->__load();
        return parent::removeValutanem();
    }

    public function getNettohuf()
    {
        $this->__load();
        return parent::getNettohuf();
    }

    public function setNettohuf($val)
    {
        $this->__load();
        return parent::setNettohuf($val);
    }

    public function getAfaertekhuf()
    {
        $this->__load();
        return parent::getAfaertekhuf();
    }

    public function setAfaertekhuf($val)
    {
        $this->__load();
        return parent::setAfaertekhuf($val);
    }

    public function getBruttohuf()
    {
        $this->__load();
        return parent::getBruttohuf();
    }

    public function setBruttohuf($val)
    {
        $this->__load();
        return parent::setBruttohuf($val);
    }

    public function getArfolyam()
    {
        $this->__load();
        return parent::getArfolyam();
    }

    public function setArfolyam($val)
    {
        $this->__load();
        return parent::setArfolyam($val);
    }

    public function getParbizonylattetel()
    {
        $this->__load();
        return parent::getParbizonylattetel();
    }

    public function getParbizonylattetelId()
    {
        $this->__load();
        return parent::getParbizonylattetelId();
    }

    public function setParbizonylattetel(\Entities\Bizonylattetel $val)
    {
        $this->__load();
        return parent::setParbizonylattetel($val);
    }

    public function removeParbizonylattetel()
    {
        $this->__load();
        return parent::removeParbizonylattetel();
    }

    public function getSzulobizonylattetelek()
    {
        $this->__load();
        return parent::getSzulobizonylattetelek();
    }

    public function addSzulobizonylattetel(\Entities\Bizonylattetel $val)
    {
        $this->__load();
        return parent::addSzulobizonylattetel($val);
    }

    public function removeSzulobizonylattetel(\Entities\Bizonylattetel $val)
    {
        $this->__load();
        return parent::removeSzulobizonylattetel($val);
    }

    public function getHatarido()
    {
        $this->__load();
        return parent::getHatarido();
    }

    public function getHataridoStr()
    {
        $this->__load();
        return parent::getHataridoStr();
    }

    public function setHatarido($adat)
    {
        $this->__load();
        return parent::setHatarido($adat);
    }

    public function getLastmod()
    {
        $this->__load();
        return parent::getLastmod();
    }

    public function getCreated()
    {
        $this->__load();
        return parent::getCreated();
    }

    public function getTermekvaltozat()
    {
        $this->__load();
        return parent::getTermekvaltozat();
    }

    public function getTermekvaltozatId()
    {
        $this->__load();
        return parent::getTermekvaltozatId();
    }

    public function setTermekvaltozat(\Entities\TermekValtozat $val)
    {
        $this->__load();
        return parent::setTermekvaltozat($val);
    }

    public function removeTermekvaltozat()
    {
        $this->__load();
        return parent::removeTermekvaltozat();
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'id', 'created', 'lastmod', 'bizonylatfej', 'mozgat', 'arvaltoztat', 'storno', 'stornozott', 'termek', 'termeknev', 'me', 'kiszereles', 'cikkszam', 'idegencikkszam', 'ehparany', 'hparany', 'szelesseg', 'magassag', 'hosszusag', 'suly', 'osszehajthato', 'vtsz', 'vtsznev', 'afa', 'afanev', 'afakulcs', 'gymennyiseg', 'mennyiseg', 'nettoegysar', 'bruttoegysar', 'nettoegysarhuf', 'bruttoegysarhuf', 'enettoegysar', 'ebruttoegysar', 'enettoegysarhuf', 'ebruttoegysarhuf', 'netto', 'afaertek', 'brutto', 'valutanem', 'valutanemnev', 'nettohuf', 'afaertekhuf', 'bruttohuf', 'arfolyam', 'parbizonylattetel', 'szulobizonylattetelek', 'hatarido', 'termekvaltozat');
    }

    public function __clone()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;
            $class = $this->_entityPersister->getClassMetadata();
            $original = $this->_entityPersister->load($this->_identifier);
            if ($original === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            foreach ($class->reflFields AS $field => $reflProperty) {
                $reflProperty->setValue($this, $reflProperty->getValue($original));
            }
            unset($this->_entityPersister, $this->_identifier);
        }
        
    }
}