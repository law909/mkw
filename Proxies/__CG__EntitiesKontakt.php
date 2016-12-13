<?php

namespace Proxies\__CG__\Entities;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class Kontakt extends \Entities\Kontakt implements \Doctrine\ORM\Proxy\Proxy
{
    /**
     * @var \Closure the callback responsible for loading properties in the proxy object. This callback is called with
     *      three parameters, being respectively the proxy object to be initialized, the method that triggered the
     *      initialization process and an array of ordered parameters that were passed to that method.
     *
     * @see \Doctrine\Common\Persistence\Proxy::__setInitializer
     */
    public $__initializer__;

    /**
     * @var \Closure the callback responsible of loading properties that need to be copied in the cloned object
     *
     * @see \Doctrine\Common\Persistence\Proxy::__setCloner
     */
    public $__cloner__;

    /**
     * @var boolean flag indicating if this object was already initialized
     *
     * @see \Doctrine\Common\Persistence\Proxy::__isInitialized
     */
    public $__isInitialized__ = false;

    /**
     * @var array properties to be lazy loaded, with keys being the property
     *            names and values being their default values
     *
     * @see \Doctrine\Common\Persistence\Proxy::__getLazyProperties
     */
    public static $lazyPropertiesDefaults = [];



    /**
     * @param \Closure $initializer
     * @param \Closure $cloner
     */
    public function __construct($initializer = null, $cloner = null)
    {

        $this->__initializer__ = $initializer;
        $this->__cloner__      = $cloner;
    }







    /**
     * 
     * @return array
     */
    public function __sleep()
    {
        if ($this->__isInitialized__) {
            return ['__isInitialized__', '' . "\0" . 'Entities\\Kontakt' . "\0" . 'id', '' . "\0" . 'Entities\\Kontakt' . "\0" . 'nev', '' . "\0" . 'Entities\\Kontakt' . "\0" . 'beosztas', '' . "\0" . 'Entities\\Kontakt' . "\0" . 'telefon', '' . "\0" . 'Entities\\Kontakt' . "\0" . 'mobil', '' . "\0" . 'Entities\\Kontakt' . "\0" . 'fax', '' . "\0" . 'Entities\\Kontakt' . "\0" . 'email', '' . "\0" . 'Entities\\Kontakt' . "\0" . 'honlap', '' . "\0" . 'Entities\\Kontakt' . "\0" . 'megjegyzes', '' . "\0" . 'Entities\\Kontakt' . "\0" . 'partner', '' . "\0" . 'Entities\\Kontakt' . "\0" . 'migrid'];
        }

        return ['__isInitialized__', '' . "\0" . 'Entities\\Kontakt' . "\0" . 'id', '' . "\0" . 'Entities\\Kontakt' . "\0" . 'nev', '' . "\0" . 'Entities\\Kontakt' . "\0" . 'beosztas', '' . "\0" . 'Entities\\Kontakt' . "\0" . 'telefon', '' . "\0" . 'Entities\\Kontakt' . "\0" . 'mobil', '' . "\0" . 'Entities\\Kontakt' . "\0" . 'fax', '' . "\0" . 'Entities\\Kontakt' . "\0" . 'email', '' . "\0" . 'Entities\\Kontakt' . "\0" . 'honlap', '' . "\0" . 'Entities\\Kontakt' . "\0" . 'megjegyzes', '' . "\0" . 'Entities\\Kontakt' . "\0" . 'partner', '' . "\0" . 'Entities\\Kontakt' . "\0" . 'migrid'];
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (Kontakt $proxy) {
                $proxy->__setInitializer(null);
                $proxy->__setCloner(null);

                $existingProperties = get_object_vars($proxy);

                foreach ($proxy->__getLazyProperties() as $property => $defaultValue) {
                    if ( ! array_key_exists($property, $existingProperties)) {
                        $proxy->$property = $defaultValue;
                    }
                }
            };

        }
    }

    /**
     * 
     */
    public function __clone()
    {
        $this->__cloner__ && $this->__cloner__->__invoke($this, '__clone', []);
    }

    /**
     * Forces initialization of the proxy
     */
    public function __load()
    {
        $this->__initializer__ && $this->__initializer__->__invoke($this, '__load', []);
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitialized($initialized)
    {
        $this->__isInitialized__ = $initialized;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitializer(\Closure $initializer = null)
    {
        $this->__initializer__ = $initializer;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __getInitializer()
    {
        return $this->__initializer__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setCloner(\Closure $cloner = null)
    {
        $this->__cloner__ = $cloner;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific cloning logic
     */
    public function __getCloner()
    {
        return $this->__cloner__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     * @static
     */
    public function __getLazyProperties()
    {
        return self::$lazyPropertiesDefaults;
    }

    
    /**
     * {@inheritDoc}
     */
    public function getId()
    {
        if ($this->__isInitialized__ === false) {
            return (int)  parent::getId();
        }


        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getId', []);

        return parent::getId();
    }

    /**
     * {@inheritDoc}
     */
    public function getNev()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNev', []);

        return parent::getNev();
    }

    /**
     * {@inheritDoc}
     */
    public function setNev($nev)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setNev', [$nev]);

        return parent::setNev($nev);
    }

    /**
     * {@inheritDoc}
     */
    public function getBeosztas()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getBeosztas', []);

        return parent::getBeosztas();
    }

    /**
     * {@inheritDoc}
     */
    public function setBeosztas($beosztas)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setBeosztas', [$beosztas]);

        return parent::setBeosztas($beosztas);
    }

    /**
     * {@inheritDoc}
     */
    public function getTelefon()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTelefon', []);

        return parent::getTelefon();
    }

    /**
     * {@inheritDoc}
     */
    public function setTelefon($telefon)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTelefon', [$telefon]);

        return parent::setTelefon($telefon);
    }

    /**
     * {@inheritDoc}
     */
    public function getMobil()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMobil', []);

        return parent::getMobil();
    }

    /**
     * {@inheritDoc}
     */
    public function setMobil($mobil)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMobil', [$mobil]);

        return parent::setMobil($mobil);
    }

    /**
     * {@inheritDoc}
     */
    public function getFax()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getFax', []);

        return parent::getFax();
    }

    /**
     * {@inheritDoc}
     */
    public function setFax($fax)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setFax', [$fax]);

        return parent::setFax($fax);
    }

    /**
     * {@inheritDoc}
     */
    public function getEmail()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getEmail', []);

        return parent::getEmail();
    }

    /**
     * {@inheritDoc}
     */
    public function setEmail($email)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setEmail', [$email]);

        return parent::setEmail($email);
    }

    /**
     * {@inheritDoc}
     */
    public function getHonlap()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getHonlap', []);

        return parent::getHonlap();
    }

    /**
     * {@inheritDoc}
     */
    public function setHonlap($honlap)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setHonlap', [$honlap]);

        return parent::setHonlap($honlap);
    }

    /**
     * {@inheritDoc}
     */
    public function getMegjegyzes()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMegjegyzes', []);

        return parent::getMegjegyzes();
    }

    /**
     * {@inheritDoc}
     */
    public function setMegjegyzes($megjegyzes)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMegjegyzes', [$megjegyzes]);

        return parent::setMegjegyzes($megjegyzes);
    }

    /**
     * {@inheritDoc}
     */
    public function getPartner()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPartner', []);

        return parent::getPartner();
    }

    /**
     * {@inheritDoc}
     */
    public function getPartnerId()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPartnerId', []);

        return parent::getPartnerId();
    }

    /**
     * {@inheritDoc}
     */
    public function getPartnerNev()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPartnerNev', []);

        return parent::getPartnerNev();
    }

    /**
     * {@inheritDoc}
     */
    public function setPartner($partner)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPartner', [$partner]);

        return parent::setPartner($partner);
    }

    /**
     * {@inheritDoc}
     */
    public function getMigrid()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMigrid', []);

        return parent::getMigrid();
    }

    /**
     * {@inheritDoc}
     */
    public function setMigrid($migrid)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMigrid', [$migrid]);

        return parent::setMigrid($migrid);
    }

}
