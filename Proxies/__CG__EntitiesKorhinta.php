<?php

namespace Proxies\__CG__\Entities;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class Korhinta extends \Entities\Korhinta implements \Doctrine\ORM\Proxy\Proxy
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
     * @see \Doctrine\Persistence\Proxy::__isInitialized
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
            return ['__isInitialized__', '' . "\0" . 'Entities\\Korhinta' . "\0" . 'id', '' . "\0" . 'Entities\\Korhinta' . "\0" . 'nev', '' . "\0" . 'Entities\\Korhinta' . "\0" . 'szoveg', '' . "\0" . 'Entities\\Korhinta' . "\0" . 'url', '' . "\0" . 'Entities\\Korhinta' . "\0" . 'kepurl', '' . "\0" . 'Entities\\Korhinta' . "\0" . 'kepleiras', '' . "\0" . 'Entities\\Korhinta' . "\0" . 'lathato', '' . "\0" . 'Entities\\Korhinta' . "\0" . 'sorrend'];
        }

        return ['__isInitialized__', '' . "\0" . 'Entities\\Korhinta' . "\0" . 'id', '' . "\0" . 'Entities\\Korhinta' . "\0" . 'nev', '' . "\0" . 'Entities\\Korhinta' . "\0" . 'szoveg', '' . "\0" . 'Entities\\Korhinta' . "\0" . 'url', '' . "\0" . 'Entities\\Korhinta' . "\0" . 'kepurl', '' . "\0" . 'Entities\\Korhinta' . "\0" . 'kepleiras', '' . "\0" . 'Entities\\Korhinta' . "\0" . 'lathato', '' . "\0" . 'Entities\\Korhinta' . "\0" . 'sorrend'];
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (Korhinta $proxy) {
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
    public function convertToArray()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'convertToArray', []);

        return parent::convertToArray();
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
    public function getSzoveg()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSzoveg', []);

        return parent::getSzoveg();
    }

    /**
     * {@inheritDoc}
     */
    public function setSzoveg($adat)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSzoveg', [$adat]);

        return parent::setSzoveg($adat);
    }

    /**
     * {@inheritDoc}
     */
    public function getUrl()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getUrl', []);

        return parent::getUrl();
    }

    /**
     * {@inheritDoc}
     */
    public function setUrl($adat)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setUrl', [$adat]);

        return parent::setUrl($adat);
    }

    /**
     * {@inheritDoc}
     */
    public function getKepurl($pre = '/')
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getKepurl', [$pre]);

        return parent::getKepurl($pre);
    }

    /**
     * {@inheritDoc}
     */
    public function getKepurlSmall($pre = '/')
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getKepurlSmall', [$pre]);

        return parent::getKepurlSmall($pre);
    }

    /**
     * {@inheritDoc}
     */
    public function getKepurlMedium($pre = '/')
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getKepurlMedium', [$pre]);

        return parent::getKepurlMedium($pre);
    }

    /**
     * {@inheritDoc}
     */
    public function getKepurlLarge($pre = '/')
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getKepurlLarge', [$pre]);

        return parent::getKepurlLarge($pre);
    }

    /**
     * {@inheritDoc}
     */
    public function setKepurl($kepurl)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setKepurl', [$kepurl]);

        return parent::setKepurl($kepurl);
    }

    /**
     * {@inheritDoc}
     */
    public function getKepleiras()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getKepleiras', []);

        return parent::getKepleiras();
    }

    /**
     * {@inheritDoc}
     */
    public function setKepleiras($kepleiras)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setKepleiras', [$kepleiras]);

        return parent::setKepleiras($kepleiras);
    }

    /**
     * {@inheritDoc}
     */
    public function getKepnev()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getKepnev', []);

        return parent::getKepnev();
    }

    /**
     * {@inheritDoc}
     */
    public function setKepnev($kepnev)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setKepnev', [$kepnev]);

        return parent::setKepnev($kepnev);
    }

    /**
     * {@inheritDoc}
     */
    public function getLathato()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLathato', []);

        return parent::getLathato();
    }

    /**
     * {@inheritDoc}
     */
    public function setLathato($adat)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setLathato', [$adat]);

        return parent::setLathato($adat);
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
    public function setSorrend($adat)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSorrend', [$adat]);

        return parent::setSorrend($adat);
    }

}
