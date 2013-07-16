<?php

namespace Proxies;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class EntitiesTermekErtesitoProxy extends \Entities\TermekErtesito implements \Doctrine\ORM\Proxy\Proxy
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
    
    
    public function getId()
    {
        $this->__load();
        return parent::getId();
    }

    public function getCreated()
    {
        $this->__load();
        return parent::getCreated();
    }

    public function getCreatedStr()
    {
        $this->__load();
        return parent::getCreatedStr();
    }

    public function getEmail()
    {
        $this->__load();
        return parent::getEmail();
    }

    public function setEmail($email)
    {
        $this->__load();
        return parent::setEmail($email);
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

    public function setPartner(\Entities\Partner $val)
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

    public function getTermekNev()
    {
        $this->__load();
        return parent::getTermekNev();
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

    public function getSent()
    {
        $this->__load();
        return parent::getSent();
    }

    public function getSentStr()
    {
        $this->__load();
        return parent::getSentStr();
    }

    public function setSent($val)
    {
        $this->__load();
        return parent::setSent($val);
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'id', 'created', 'email', 'termek', 'partner', 'sent');
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