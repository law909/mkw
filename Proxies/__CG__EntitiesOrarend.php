<?php

namespace Proxies\__CG__\Entities;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class Orarend extends \Entities\Orarend implements \Doctrine\ORM\Proxy\Proxy
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
            return ['__isInitialized__', '' . "\0" . 'Entities\\Orarend' . "\0" . 'id', '' . "\0" . 'Entities\\Orarend' . "\0" . 'created', '' . "\0" . 'Entities\\Orarend' . "\0" . 'lastmod', '' . "\0" . 'Entities\\Orarend' . "\0" . 'dolgozo', '' . "\0" . 'Entities\\Orarend' . "\0" . 'jogaterem', '' . "\0" . 'Entities\\Orarend' . "\0" . 'jogaoratipus', '' . "\0" . 'Entities\\Orarend' . "\0" . 'nev', '' . "\0" . 'Entities\\Orarend' . "\0" . 'maxferohely', '' . "\0" . 'Entities\\Orarend' . "\0" . 'atlagresztvevoszam', '' . "\0" . 'Entities\\Orarend' . "\0" . 'nap', '' . "\0" . 'Entities\\Orarend' . "\0" . 'kezdet', '' . "\0" . 'Entities\\Orarend' . "\0" . 'veg', '' . "\0" . 'Entities\\Orarend' . "\0" . 'inaktiv', '' . "\0" . 'Entities\\Orarend' . "\0" . 'multilang'];
        }

        return ['__isInitialized__', '' . "\0" . 'Entities\\Orarend' . "\0" . 'id', '' . "\0" . 'Entities\\Orarend' . "\0" . 'created', '' . "\0" . 'Entities\\Orarend' . "\0" . 'lastmod', '' . "\0" . 'Entities\\Orarend' . "\0" . 'dolgozo', '' . "\0" . 'Entities\\Orarend' . "\0" . 'jogaterem', '' . "\0" . 'Entities\\Orarend' . "\0" . 'jogaoratipus', '' . "\0" . 'Entities\\Orarend' . "\0" . 'nev', '' . "\0" . 'Entities\\Orarend' . "\0" . 'maxferohely', '' . "\0" . 'Entities\\Orarend' . "\0" . 'atlagresztvevoszam', '' . "\0" . 'Entities\\Orarend' . "\0" . 'nap', '' . "\0" . 'Entities\\Orarend' . "\0" . 'kezdet', '' . "\0" . 'Entities\\Orarend' . "\0" . 'veg', '' . "\0" . 'Entities\\Orarend' . "\0" . 'inaktiv', '' . "\0" . 'Entities\\Orarend' . "\0" . 'multilang'];
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (Orarend $proxy) {
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
    public function getDolgozo()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDolgozo', []);

        return parent::getDolgozo();
    }

    /**
     * {@inheritDoc}
     */
    public function getDolgozoNev()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDolgozoNev', []);

        return parent::getDolgozoNev();
    }

    /**
     * {@inheritDoc}
     */
    public function getDolgozoId()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDolgozoId', []);

        return parent::getDolgozoId();
    }

    /**
     * {@inheritDoc}
     */
    public function setDolgozo($dolgozo)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDolgozo', [$dolgozo]);

        return parent::setDolgozo($dolgozo);
    }

    /**
     * {@inheritDoc}
     */
    public function getJogaterem()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getJogaterem', []);

        return parent::getJogaterem();
    }

    /**
     * {@inheritDoc}
     */
    public function getJogateremNev()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getJogateremNev', []);

        return parent::getJogateremNev();
    }

    /**
     * {@inheritDoc}
     */
    public function getJogateremOrarendclass()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getJogateremOrarendclass', []);

        return parent::getJogateremOrarendclass();
    }

    /**
     * {@inheritDoc}
     */
    public function getJogateremId()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getJogateremId', []);

        return parent::getJogateremId();
    }

    /**
     * {@inheritDoc}
     */
    public function setJogaterem($jogaterem)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setJogaterem', [$jogaterem]);

        return parent::setJogaterem($jogaterem);
    }

    /**
     * {@inheritDoc}
     */
    public function getJogaoratipus()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getJogaoratipus', []);

        return parent::getJogaoratipus();
    }

    /**
     * {@inheritDoc}
     */
    public function getJogaoratipusNev()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getJogaoratipusNev', []);

        return parent::getJogaoratipusNev();
    }

    /**
     * {@inheritDoc}
     */
    public function getJogaoratipusId()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getJogaoratipusId', []);

        return parent::getJogaoratipusId();
    }

    /**
     * {@inheritDoc}
     */
    public function setJogaoratipus($jogaoratipus)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setJogaoratipus', [$jogaoratipus]);

        return parent::setJogaoratipus($jogaoratipus);
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
    public function getNevTanar()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNevTanar', []);

        return parent::getNevTanar();
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
    public function getMaxferohely()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMaxferohely', []);

        return parent::getMaxferohely();
    }

    /**
     * {@inheritDoc}
     */
    public function setMaxferohely($maxferohely)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMaxferohely', [$maxferohely]);

        return parent::setMaxferohely($maxferohely);
    }

    /**
     * {@inheritDoc}
     */
    public function getNap()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNap', []);

        return parent::getNap();
    }

    /**
     * {@inheritDoc}
     */
    public function getNapNev()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNapNev', []);

        return parent::getNapNev();
    }

    /**
     * {@inheritDoc}
     */
    public function setNap($nap)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setNap', [$nap]);

        return parent::setNap($nap);
    }

    /**
     * {@inheritDoc}
     */
    public function getKezdet()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getKezdet', []);

        return parent::getKezdet();
    }

    /**
     * {@inheritDoc}
     */
    public function getKezdetStr()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getKezdetStr', []);

        return parent::getKezdetStr();
    }

    /**
     * {@inheritDoc}
     */
    public function setKezdet($kezdet)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setKezdet', [$kezdet]);

        return parent::setKezdet($kezdet);
    }

    /**
     * {@inheritDoc}
     */
    public function getVeg()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getVeg', []);

        return parent::getVeg();
    }

    /**
     * {@inheritDoc}
     */
    public function getVegStr()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getVegStr', []);

        return parent::getVegStr();
    }

    /**
     * {@inheritDoc}
     */
    public function setVeg($veg)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setVeg', [$veg]);

        return parent::setVeg($veg);
    }

    /**
     * {@inheritDoc}
     */
    public function getInaktiv()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getInaktiv', []);

        return parent::getInaktiv();
    }

    /**
     * {@inheritDoc}
     */
    public function setInaktiv($inaktiv)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setInaktiv', [$inaktiv]);

        return parent::setInaktiv($inaktiv);
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
    public function getDolgozoUrl()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDolgozoUrl', []);

        return parent::getDolgozoUrl();
    }

    /**
     * {@inheritDoc}
     */
    public function getJogaoratipusUrl()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getJogaoratipusUrl', []);

        return parent::getJogaoratipusUrl();
    }

    /**
     * {@inheritDoc}
     */
    public function isDelelottKezdodik()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'isDelelottKezdodik', []);

        return parent::isDelelottKezdodik();
    }

    /**
     * {@inheritDoc}
     */
    public function getAtlagresztvevoszam()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getAtlagresztvevoszam', []);

        return parent::getAtlagresztvevoszam();
    }

    /**
     * {@inheritDoc}
     */
    public function setAtlagresztvevoszam($atlagresztvevoszam)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setAtlagresztvevoszam', [$atlagresztvevoszam]);

        return parent::setAtlagresztvevoszam($atlagresztvevoszam);
    }

    /**
     * {@inheritDoc}
     */
    public function getMultilang()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMultilang', []);

        return parent::getMultilang();
    }

    /**
     * {@inheritDoc}
     */
    public function setMultilang($multilang)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMultilang', [$multilang]);

        return parent::setMultilang($multilang);
    }

}
