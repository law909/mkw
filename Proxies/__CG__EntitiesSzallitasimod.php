<?php

namespace Proxies\__CG__\Entities;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class Szallitasimod extends \Entities\Szallitasimod implements \Doctrine\ORM\Proxy\Proxy
{
    /**
     * @var \Closure the callback responsible for loading properties in the proxy object. This callback is called with
     *      three parameters, being respectively the proxy object to be initialized, the method that triggered the
     *      initialization process and an array of ordered parameters that were passed to that method.
     *
     * @see \Doctrine\Common\Proxy\Proxy::__setInitializer
     */
    public $__initializer__;

    /**
     * @var \Closure the callback responsible of loading properties that need to be copied in the cloned object
     *
     * @see \Doctrine\Common\Proxy\Proxy::__setCloner
     */
    public $__cloner__;

    /**
     * @var boolean flag indicating if this object was already initialized
     *
     * @see \Doctrine\Common\Persistence\Proxy::__isInitialized
     */
    public $__isInitialized__ = false;

    /**
     * @var array<string, null> properties to be lazy loaded, indexed by property name
     */
    public static $lazyPropertiesNames = array (
);

    /**
     * @var array<string, mixed> default values of properties to be lazy loaded, with keys being the property names
     *
     * @see \Doctrine\Common\Proxy\Proxy::__getLazyProperties
     */
    public static $lazyPropertiesDefaults = array (
);



    public function __construct(?\Closure $initializer = null, ?\Closure $cloner = null)
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
            return ['__isInitialized__', '' . "\0" . 'Entities\\Szallitasimod' . "\0" . 'id', '' . "\0" . 'Entities\\Szallitasimod' . "\0" . 'nev', '' . "\0" . 'Entities\\Szallitasimod' . "\0" . 'webes', '' . "\0" . 'Entities\\Szallitasimod' . "\0" . 'vanszallitasiktg', '' . "\0" . 'Entities\\Szallitasimod' . "\0" . 'leiras', '' . "\0" . 'Entities\\Szallitasimod' . "\0" . 'fizmodok', '' . "\0" . 'Entities\\Szallitasimod' . "\0" . 'bizonylatfejek', '' . "\0" . 'Entities\\Szallitasimod' . "\0" . 'sorrend', '' . "\0" . 'Entities\\Szallitasimod' . "\0" . 'partnerek', '' . "\0" . 'Entities\\Szallitasimod' . "\0" . 'hatarok', 'locale', '' . "\0" . 'Entities\\Szallitasimod' . "\0" . 'translations', '' . "\0" . 'Entities\\Szallitasimod' . "\0" . 'terminaltipus', '' . "\0" . 'Entities\\Szallitasimod' . "\0" . 'webes2', '' . "\0" . 'Entities\\Szallitasimod' . "\0" . 'webes3'];
        }

        return ['__isInitialized__', '' . "\0" . 'Entities\\Szallitasimod' . "\0" . 'id', '' . "\0" . 'Entities\\Szallitasimod' . "\0" . 'nev', '' . "\0" . 'Entities\\Szallitasimod' . "\0" . 'webes', '' . "\0" . 'Entities\\Szallitasimod' . "\0" . 'vanszallitasiktg', '' . "\0" . 'Entities\\Szallitasimod' . "\0" . 'leiras', '' . "\0" . 'Entities\\Szallitasimod' . "\0" . 'fizmodok', '' . "\0" . 'Entities\\Szallitasimod' . "\0" . 'bizonylatfejek', '' . "\0" . 'Entities\\Szallitasimod' . "\0" . 'sorrend', '' . "\0" . 'Entities\\Szallitasimod' . "\0" . 'partnerek', '' . "\0" . 'Entities\\Szallitasimod' . "\0" . 'hatarok', 'locale', '' . "\0" . 'Entities\\Szallitasimod' . "\0" . 'translations', '' . "\0" . 'Entities\\Szallitasimod' . "\0" . 'terminaltipus', '' . "\0" . 'Entities\\Szallitasimod' . "\0" . 'webes2', '' . "\0" . 'Entities\\Szallitasimod' . "\0" . 'webes3'];
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (Szallitasimod $proxy) {
                $proxy->__setInitializer(null);
                $proxy->__setCloner(null);

                $existingProperties = get_object_vars($proxy);

                foreach ($proxy::$lazyPropertiesDefaults as $property => $defaultValue) {
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
     * @deprecated no longer in use - generated code now relies on internal components rather than generated public API
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
    public function getWebes()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getWebes', []);

        return parent::getWebes();
    }

    /**
     * {@inheritDoc}
     */
    public function setWebes($webes)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setWebes', [$webes]);

        return parent::setWebes($webes);
    }

    /**
     * {@inheritDoc}
     */
    public function getLeiras()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLeiras', []);

        return parent::getLeiras();
    }

    /**
     * {@inheritDoc}
     */
    public function setLeiras($leiras)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setLeiras', [$leiras]);

        return parent::setLeiras($leiras);
    }

    /**
     * {@inheritDoc}
     */
    public function getFizmodok()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getFizmodok', []);

        return parent::getFizmodok();
    }

    /**
     * {@inheritDoc}
     */
    public function setFizmodok($fm)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setFizmodok', [$fm]);

        return parent::setFizmodok($fm);
    }

    /**
     * {@inheritDoc}
     */
    public function getSorrend()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSorrend', []);

        return parent::getSorrend();
    }

    /**
     * {@inheritDoc}
     */
    public function setSorrend($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSorrend', [$val]);

        return parent::setSorrend($val);
    }

    /**
     * {@inheritDoc}
     */
    public function getVanszallitasiktg()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getVanszallitasiktg', []);

        return parent::getVanszallitasiktg();
    }

    /**
     * {@inheritDoc}
     */
    public function setVanszallitasiktg($adat)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setVanszallitasiktg', [$adat]);

        return parent::setVanszallitasiktg($adat);
    }

    /**
     * {@inheritDoc}
     */
    public function getHatarok()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getHatarok', []);

        return parent::getHatarok();
    }

    /**
     * {@inheritDoc}
     */
    public function getTranslations()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTranslations', []);

        return parent::getTranslations();
    }

    /**
     * {@inheritDoc}
     */
    public function addTranslation(\Entities\SzallitasimodTranslation $t)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addTranslation', [$t]);

        return parent::addTranslation($t);
    }

    /**
     * {@inheritDoc}
     */
    public function removeTranslation(\Entities\SzallitasimodTranslation $t)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeTranslation', [$t]);

        return parent::removeTranslation($t);
    }

    /**
     * {@inheritDoc}
     */
    public function getLocale()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLocale', []);

        return parent::getLocale();
    }

    /**
     * {@inheritDoc}
     */
    public function setLocale($locale)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setLocale', [$locale]);

        return parent::setLocale($locale);
    }

    /**
     * {@inheritDoc}
     */
    public function getTerminaltipus()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTerminaltipus', []);

        return parent::getTerminaltipus();
    }

    /**
     * {@inheritDoc}
     */
    public function setTerminaltipus($terminaltipus)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTerminaltipus', [$terminaltipus]);

        return parent::setTerminaltipus($terminaltipus);
    }

    /**
     * {@inheritDoc}
     */
    public function getWebes2()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getWebes2', []);

        return parent::getWebes2();
    }

    /**
     * {@inheritDoc}
     */
    public function setWebes2($webes2)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setWebes2', [$webes2]);

        return parent::setWebes2($webes2);
    }

    /**
     * {@inheritDoc}
     */
    public function getWebes3()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getWebes3', []);

        return parent::getWebes3();
    }

    /**
     * {@inheritDoc}
     */
    public function setWebes3($webes3)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setWebes3', [$webes3]);

        return parent::setWebes3($webes3);
    }

}
