<?php

namespace Proxies;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class EntitiesTermekValtozatProxy extends \Entities\TermekValtozat implements \Doctrine\ORM\Proxy\Proxy
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
    
    
    public function getKeszlet()
    {
        $this->__load();
        return parent::getKeszlet();
    }

    public function getId()
    {
        $this->__load();
        return parent::getId();
    }

    public function getTermek()
    {
        $this->__load();
        return parent::getTermek();
    }

    public function setTermek($termek)
    {
        $this->__load();
        return parent::setTermek($termek);
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

    public function getElerheto()
    {
        $this->__load();
        return parent::getElerheto();
    }

    public function setElerheto($elerheto)
    {
        $this->__load();
        return parent::setElerheto($elerheto);
    }

    public function getTermekfokep()
    {
        $this->__load();
        return parent::getTermekfokep();
    }

    public function setTermekfokep($adat)
    {
        $this->__load();
        return parent::setTermekfokep($adat);
    }

    public function getAdatTipus1()
    {
        $this->__load();
        return parent::getAdatTipus1();
    }

    public function getAdatTipus1Id()
    {
        $this->__load();
        return parent::getAdatTipus1Id();
    }

    public function getAdatTipus1Nev()
    {
        $this->__load();
        return parent::getAdatTipus1Nev();
    }

    public function setAdatTipus1($at)
    {
        $this->__load();
        return parent::setAdatTipus1($at);
    }

    public function getErtek1()
    {
        $this->__load();
        return parent::getErtek1();
    }

    public function setErtek1($ertek)
    {
        $this->__load();
        return parent::setErtek1($ertek);
    }

    public function getAdatTipus2()
    {
        $this->__load();
        return parent::getAdatTipus2();
    }

    public function getAdatTipus2Id()
    {
        $this->__load();
        return parent::getAdatTipus2Id();
    }

    public function getAdatTipus2Nev()
    {
        $this->__load();
        return parent::getAdatTipus2Nev();
    }

    public function setAdatTipus2($at)
    {
        $this->__load();
        return parent::setAdatTipus2($at);
    }

    public function getErtek2()
    {
        $this->__load();
        return parent::getErtek2();
    }

    public function setErtek2($ertek)
    {
        $this->__load();
        return parent::setErtek2($ertek);
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

    public function getNetto()
    {
        $this->__load();
        return parent::getNetto();
    }

    public function setNetto($netto)
    {
        $this->__load();
        return parent::setNetto($netto);
    }

    public function getBrutto()
    {
        $this->__load();
        return parent::getBrutto();
    }

    public function setBrutto($brutto)
    {
        $this->__load();
        return parent::setBrutto($brutto);
    }

    public function getKepurl($pre = '/')
    {
        $this->__load();
        return parent::getKepurl($pre);
    }

    public function getKepurlMini($pre = '/')
    {
        $this->__load();
        return parent::getKepurlMini($pre);
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

    public function getKepleiras()
    {
        $this->__load();
        return parent::getKepleiras();
    }

    public function getKep()
    {
        $this->__load();
        return parent::getKep();
    }

    public function getKepId()
    {
        $this->__load();
        return parent::getKepId();
    }

    public function setKep($kep)
    {
        $this->__load();
        return parent::setKep($kep);
    }

    public function getIdegencikkszam()
    {
        $this->__load();
        return parent::getIdegencikkszam();
    }

    public function setIdegencikkszam($idegencikkszam)
    {
        $this->__load();
        return parent::setIdegencikkszam($idegencikkszam);
    }

    public function getCikkszam()
    {
        $this->__load();
        return parent::getCikkszam();
    }

    public function setCikkszam($cikkszam)
    {
        $this->__load();
        return parent::setCikkszam($cikkszam);
    }

    public function getNev()
    {
        $this->__load();
        return parent::getNev();
    }

    public function getVonalkod()
    {
        $this->__load();
        return parent::getVonalkod();
    }

    public function setVonalkod($vonalkod)
    {
        $this->__load();
        return parent::setVonalkod($vonalkod);
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'id', 'created', 'lastmod', 'termek', 'lathato', 'elerheto', 'termekfokep', 'adattipus1', 'ertek1', 'adattipus2', 'ertek2', 'kosarak', 'netto', 'brutto', 'kep', 'cikkszam', 'idegencikkszam', 'bizonylattetelek', 'vonalkod');
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