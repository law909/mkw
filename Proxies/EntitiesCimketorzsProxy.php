<?php

namespace Proxies;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class EntitiesCimketorzsProxy extends \Entities\Cimketorzs implements \Doctrine\ORM\Proxy\Proxy
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

    public function getMenu1lathato()
    {
        $this->__load();
        return parent::getMenu1lathato();
    }

    public function setMenu1lathato($menu1lathato)
    {
        $this->__load();
        return parent::setMenu1lathato($menu1lathato);
    }

    public function getMenu2lathato()
    {
        $this->__load();
        return parent::getMenu2lathato();
    }

    public function setMenu2lathato($menu2lathato)
    {
        $this->__load();
        return parent::setMenu2lathato($menu2lathato);
    }

    public function getMenu3lathato()
    {
        $this->__load();
        return parent::getMenu3lathato();
    }

    public function setMenu3lathato($menu3lathato)
    {
        $this->__load();
        return parent::setMenu3lathato($menu3lathato);
    }

    public function getMenu4lathato()
    {
        $this->__load();
        return parent::getMenu4lathato();
    }

    public function setMenu4lathato($menu4lathato)
    {
        $this->__load();
        return parent::setMenu4lathato($menu4lathato);
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

    public function getOldalcim()
    {
        $this->__load();
        return parent::getOldalcim();
    }

    public function setOldalcim($oldalcim)
    {
        $this->__load();
        return parent::setOldalcim($oldalcim);
    }

    public function getKepurl($pre = '/')
    {
        $this->__load();
        return parent::getKepurl($pre);
    }

    public function getKepurlSmall($pre = '/')
    {
        $this->__load();
        return parent::getKepurlSmall($pre);
    }

    public function getKepurlMedium($pre = '/')
    {
        $this->__load();
        return parent::getKepurlMedium($pre);
    }

    public function getKepurlLarge($pre = '/')
    {
        $this->__load();
        return parent::getKepurlLarge($pre);
    }

    public function setKepurl($kepurl)
    {
        $this->__load();
        return parent::setKepurl($kepurl);
    }

    public function getKepleiras()
    {
        $this->__load();
        return parent::getKepleiras();
    }

    public function setKepleiras($kepleiras)
    {
        $this->__load();
        return parent::setKepleiras($kepleiras);
    }

    public function getSorrend()
    {
        $this->__load();
        return parent::getSorrend();
    }

    public function setSorrend($sorrend)
    {
        $this->__load();
        return parent::setSorrend($sorrend);
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'id', 'menu1lathato', 'menu2lathato', 'menu3lathato', 'menu4lathato', 'leiras', 'oldalcim', 'kepurl', 'kepleiras', 'sorrend');
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