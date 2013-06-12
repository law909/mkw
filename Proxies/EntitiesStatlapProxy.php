<?php

namespace Proxies;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class EntitiesStatlapProxy extends \Entities\Statlap implements \Doctrine\ORM\Proxy\Proxy
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

    public function getOldalcim()
    {
        $this->__load();
        return parent::getOldalcim();
    }

    public function setOldalcim($adat)
    {
        $this->__load();
        return parent::setOldalcim($adat);
    }

    public function getSlug()
    {
        $this->__load();
        return parent::getSlug();
    }

    public function setSlug($adat)
    {
        $this->__load();
        return parent::setSlug($adat);
    }

    public function getSzoveg()
    {
        $this->__load();
        return parent::getSzoveg();
    }

    public function setSzoveg($adat)
    {
        $this->__load();
        return parent::setSzoveg($adat);
    }

    public function getSeodescription()
    {
        $this->__load();
        return parent::getSeodescription();
    }

    public function setSeodescription($adat)
    {
        $this->__load();
        return parent::setSeodescription($adat);
    }

    public function getSeokeywords()
    {
        $this->__load();
        return parent::getSeokeywords();
    }

    public function setSeokeywords($adat)
    {
        $this->__load();
        return parent::setSeokeywords($adat);
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


    public function __sleep()
    {
        return array('__isInitialized__', 'id', 'oldalcim', 'slug', 'szoveg', 'seodescription', 'seokeywords', 'created', 'lastmod');
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