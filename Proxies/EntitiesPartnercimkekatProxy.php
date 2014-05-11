<?php

namespace Proxies;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class EntitiesPartnercimkekatProxy extends \Entities\Partnercimkekat implements \Doctrine\ORM\Proxy\Proxy
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
    
    
    public function getCimkek()
    {
        $this->__load();
        return parent::getCimkek();
    }

    public function AddCimke(\Entities\Cimketorzs $cimke)
    {
        $this->__load();
        return parent::AddCimke($cimke);
    }

    public function removeCimke(\Entities\Cimketorzs $cimke)
    {
        $this->__load();
        return parent::removeCimke($cimke);
    }

    public function getNev()
    {
        $this->__load();
        return parent::getNev();
    }

    public function setNev($nev)
    {
        $this->__load();
        return parent::setNev($nev);
    }

    public function getSlug()
    {
        $this->__load();
        return parent::getSlug();
    }

    public function setSlug($slug)
    {
        $this->__load();
        return parent::setSlug($slug);
    }

    public function getId()
    {
        $this->__load();
        return parent::getId();
    }

    public function getLathato()
    {
        $this->__load();
        return parent::getLathato();
    }

    public function setLathato($lathato)
    {
        $this->__load();
        return parent::setLathato($lathato);
    }

    public function getTermeklaponlathato()
    {
        $this->__load();
        return parent::getTermeklaponlathato();
    }

    public function setTermeklaponlathato($lathato)
    {
        $this->__load();
        return parent::setTermeklaponlathato($lathato);
    }

    public function getTermekakciodobozbanlathato()
    {
        $this->__load();
        return parent::getTermekakciodobozbanlathato();
    }

    public function setTermekakciodobozbanlathato($lathato)
    {
        $this->__load();
        return parent::setTermekakciodobozbanlathato($lathato);
    }

    public function getTermeklistabanlathato()
    {
        $this->__load();
        return parent::getTermeklistabanlathato();
    }

    public function setTermeklistabanlathato($lathato)
    {
        $this->__load();
        return parent::setTermeklistabanlathato($lathato);
    }

    public function getTermekszurobenlathato()
    {
        $this->__load();
        return parent::getTermekszurobenlathato();
    }

    public function setTermekszurobenlathato($lathato)
    {
        $this->__load();
        return parent::setTermekszurobenlathato($lathato);
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
        return array('__isInitialized__', 'id', 'lathato', 'termeklaponlathato', 'termekszurobenlathato', 'termeklistabanlathato', 'termekakciodobozbanlathato', 'sorrend', 'nev', 'slug', 'cimkek');
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