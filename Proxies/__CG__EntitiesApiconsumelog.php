<?php

namespace Proxies\__CG__\Entities;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class Apiconsumelog extends \Entities\Apiconsumelog implements \Doctrine\ORM\Proxy\Proxy
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
            return ['__isInitialized__', '' . "\0" . 'Entities\\Apiconsumelog' . "\0" . 'id', '' . "\0" . 'Entities\\Apiconsumelog' . "\0" . 'created', '' . "\0" . 'Entities\\Apiconsumelog' . "\0" . 'ip', '' . "\0" . 'Entities\\Apiconsumelog' . "\0" . 'apiconsumer', '' . "\0" . 'Entities\\Apiconsumelog' . "\0" . 'apiconsumernev', '' . "\0" . 'Entities\\Apiconsumelog' . "\0" . 'query', '' . "\0" . 'Entities\\Apiconsumelog' . "\0" . 'result'];
        }

        return ['__isInitialized__', '' . "\0" . 'Entities\\Apiconsumelog' . "\0" . 'id', '' . "\0" . 'Entities\\Apiconsumelog' . "\0" . 'created', '' . "\0" . 'Entities\\Apiconsumelog' . "\0" . 'ip', '' . "\0" . 'Entities\\Apiconsumelog' . "\0" . 'apiconsumer', '' . "\0" . 'Entities\\Apiconsumelog' . "\0" . 'apiconsumernev', '' . "\0" . 'Entities\\Apiconsumelog' . "\0" . 'query', '' . "\0" . 'Entities\\Apiconsumelog' . "\0" . 'result'];
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (Apiconsumelog $proxy) {
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
    public function getCreated()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCreated', []);

        return parent::getCreated();
    }

    /**
     * {@inheritDoc}
     */
    public function getCreatedStr()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCreatedStr', []);

        return parent::getCreatedStr();
    }

    /**
     * {@inheritDoc}
     */
    public function clearCreated()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'clearCreated', []);

        return parent::clearCreated();
    }

    /**
     * {@inheritDoc}
     */
    public function getApiconsumer()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getApiconsumer', []);

        return parent::getApiconsumer();
    }

    /**
     * {@inheritDoc}
     */
    public function setApiconsumer($apiconsumer)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setApiconsumer', [$apiconsumer]);

        return parent::setApiconsumer($apiconsumer);
    }

    /**
     * {@inheritDoc}
     */
    public function removeApiconsumer()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeApiconsumer', []);

        return parent::removeApiconsumer();
    }

    /**
     * {@inheritDoc}
     */
    public function getApiconsumernev()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getApiconsumernev', []);

        return parent::getApiconsumernev();
    }

    /**
     * {@inheritDoc}
     */
    public function setApiconsumernev($apiconsumernev)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setApiconsumernev', [$apiconsumernev]);

        return parent::setApiconsumernev($apiconsumernev);
    }

    /**
     * {@inheritDoc}
     */
    public function getQuery()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getQuery', []);

        return parent::getQuery();
    }

    /**
     * {@inheritDoc}
     */
    public function setQuery($query)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setQuery', [$query]);

        return parent::setQuery($query);
    }

    /**
     * {@inheritDoc}
     */
    public function getResult()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getResult', []);

        return parent::getResult();
    }

    /**
     * {@inheritDoc}
     */
    public function setResult($result)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setResult', [$result]);

        return parent::setResult($result);
    }

    /**
     * {@inheritDoc}
     */
    public function getIp()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getIp', []);

        return parent::getIp();
    }

    /**
     * {@inheritDoc}
     */
    public function setIp($ip)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setIp', [$ip]);

        return parent::setIp($ip);
    }

}
