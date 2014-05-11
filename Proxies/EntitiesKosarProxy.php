<?php

namespace Proxies;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class EntitiesKosarProxy extends \Entities\Kosar implements \Doctrine\ORM\Proxy\Proxy
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
    
    
    public function toLista()
    {
        $this->__load();
        return parent::toLista();
    }

    public function getId()
    {
        $this->__load();
        return parent::getId();
    }

    public function getSessionid()
    {
        $this->__load();
        return parent::getSessionid();
    }

    public function setSessionid($adat)
    {
        $this->__load();
        return parent::setSessionid($adat);
    }

    public function getPartner()
    {
        $this->__load();
        return parent::getPartner();
    }

    public function getPartnerId()
    {
        $this->__load();
        return parent::getPartnerId();
    }

    public function getPartnerNev()
    {
        $this->__load();
        return parent::getPartnerNev();
    }

    public function setPartner($val)
    {
        $this->__load();
        return parent::setPartner($val);
    }

    public function removePartner()
    {
        $this->__load();
        return parent::removePartner();
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

    public function getMennyiseg()
    {
        $this->__load();
        return parent::getMennyiseg();
    }

    public function novelMennyiseg()
    {
        $this->__load();
        return parent::novelMennyiseg();
    }

    public function setMennyiseg($val)
    {
        $this->__load();
        return parent::setMennyiseg($val);
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

    public function getNettoegysar()
    {
        $this->__load();
        return parent::getNettoegysar();
    }

    public function setNettoegysar($netto)
    {
        $this->__load();
        return parent::setNettoegysar($netto);
    }

    public function getBruttoegysar()
    {
        $this->__load();
        return parent::getBruttoegysar();
    }

    public function setBruttoegysar($brutto)
    {
        $this->__load();
        return parent::setBruttoegysar($brutto);
    }

    public function getValutanem()
    {
        $this->__load();
        return parent::getValutanem();
    }

    public function setValutanem($valutanem)
    {
        $this->__load();
        return parent::setValutanem($valutanem);
    }

    public function getValutanemNev()
    {
        $this->__load();
        return parent::getValutanemNev();
    }

    public function getSorrend()
    {
        $this->__load();
        return parent::getSorrend();
    }

    public function setSorrend($s)
    {
        $this->__load();
        return parent::setSorrend($s);
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'id', 'created', 'lastmod', 'sessionid', 'partner', 'termek', 'termekvaltozat', 'mennyiseg', 'valutanem', 'nettoegysar', 'bruttoegysar', 'sorrend');
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