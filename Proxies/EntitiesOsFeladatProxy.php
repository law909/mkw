<?php

namespace Proxies;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class EntitiesOsFeladatProxy extends \Entities\OsFeladat implements \Doctrine\ORM\Proxy\Proxy
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

    public function getBejegyzes()
    {
        $this->__load();
        return parent::getBejegyzes();
    }

    public function setBejegyzes($bejegyzes)
    {
        $this->__load();
        return parent::setBejegyzes($bejegyzes);
    }

    public function getLeiras()
    {
        $this->__load();
        return parent::getLeiras();
    }

    public function setLeiras($leiras)
    {
        $this->__load();
        return parent::setLeiras($leiras);
    }

    public function getLetrehozva()
    {
        $this->__load();
        return parent::getLetrehozva();
    }

    public function setLetrehozvaOnPreInsert()
    {
        $this->__load();
        return parent::setLetrehozvaOnPreInsert();
    }

    public function setLetrehozva($letrehozva)
    {
        $this->__load();
        return parent::setLetrehozva($letrehozva);
    }

    public function getEsedekes()
    {
        $this->__load();
        return parent::getEsedekes();
    }

    public function getEsedekesStr()
    {
        $this->__load();
        return parent::getEsedekesStr();
    }

    public function setEsedekes($esedekes = '')
    {
        $this->__load();
        return parent::setEsedekes($esedekes);
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'id', 'bejegyzes', 'leiras', 'letrehozva', 'esedekes');
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