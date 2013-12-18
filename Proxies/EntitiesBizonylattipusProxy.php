<?php

namespace Proxies;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class EntitiesBizonylattipusProxy extends \Entities\Bizonylattipus implements \Doctrine\ORM\Proxy\Proxy
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

    public function getNev()
    {
        $this->__load();
        return parent::getNev();
    }

    public function setNev($val)
    {
        $this->__load();
        return parent::setNev($val);
    }

    public function getIrany()
    {
        $this->__load();
        return parent::getIrany();
    }

    public function setIrany($val)
    {
        $this->__load();
        return parent::setIrany($val);
    }

    public function getNyomtatni()
    {
        $this->__load();
        return parent::getNyomtatni();
    }

    public function setNyomtatni($val)
    {
        $this->__load();
        return parent::setNyomtatni($val);
    }

    public function getAzonosito()
    {
        $this->__load();
        return parent::getAzonosito();
    }

    public function setAzonosito($val)
    {
        $this->__load();
        return parent::setAzonosito($val);
    }

    public function getKezdosorszam()
    {
        $this->__load();
        return parent::getKezdosorszam();
    }

    public function setKezdosorszam($val)
    {
        $this->__load();
        return parent::setKezdosorszam($val);
    }

    public function getPeldanyszam()
    {
        $this->__load();
        return parent::getPeldanyszam();
    }

    public function setPeldanyszam($val)
    {
        $this->__load();
        return parent::setPeldanyszam($val);
    }

    public function getMozgat()
    {
        $this->__load();
        return parent::getMozgat();
    }

    public function setMozgat($val)
    {
        $this->__load();
        return parent::setMozgat($val);
    }

    public function getPenztmozgat()
    {
        $this->__load();
        return parent::getPenztmozgat();
    }

    public function setPenztmozgat($val)
    {
        $this->__load();
        return parent::setPenztmozgat($val);
    }

    public function getEditprinted()
    {
        $this->__load();
        return parent::getEditprinted();
    }

    public function setEditprinted($val)
    {
        $this->__load();
        return parent::setEditprinted($val);
    }

    public function getShowteljesites()
    {
        $this->__load();
        return parent::getShowteljesites();
    }

    public function setShowteljesites($show)
    {
        $this->__load();
        return parent::setShowteljesites($show);
    }

    public function getShowesedekesseg()
    {
        $this->__load();
        return parent::getShowesedekesseg();
    }

    public function setShowesedekesseg($show)
    {
        $this->__load();
        return parent::setShowesedekesseg($show);
    }

    public function getShowhatarido()
    {
        $this->__load();
        return parent::getShowhatarido();
    }

    public function setShowhatarido($show)
    {
        $this->__load();
        return parent::setShowhatarido($show);
    }

    public function getShowvalutanem()
    {
        $this->__load();
        return parent::getShowvalutanem();
    }

    public function setShowvalutanem($show)
    {
        $this->__load();
        return parent::setShowvalutanem($show);
    }

    public function getTplname()
    {
        $this->__load();
        return parent::getTplname();
    }

    public function setTplname($d)
    {
        $this->__load();
        return parent::setTplname($d);
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'id', 'nev', 'irany', 'nyomtatni', 'azonosito', 'kezdosorszam', 'peldanyszam', 'mozgat', 'penztmozgat', 'editprinted', 'showteljesites', 'showesedekesseg', 'showhatarido', 'showvalutanem', 'bizonylatfejek', 'tplname');
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