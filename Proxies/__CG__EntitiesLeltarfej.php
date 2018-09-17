<?php

namespace Proxies\__CG__\Entities;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class Leltarfej extends \Entities\Leltarfej implements \Doctrine\ORM\Proxy\Proxy
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
            return ['__isInitialized__', '' . "\0" . 'Entities\\Leltarfej' . "\0" . 'id', '' . "\0" . 'Entities\\Leltarfej' . "\0" . 'created', '' . "\0" . 'Entities\\Leltarfej' . "\0" . 'lastmod', '' . "\0" . 'Entities\\Leltarfej' . "\0" . 'createdby', '' . "\0" . 'Entities\\Leltarfej' . "\0" . 'updatedby', '' . "\0" . 'Entities\\Leltarfej' . "\0" . 'raktar', '' . "\0" . 'Entities\\Leltarfej' . "\0" . 'nyitas', '' . "\0" . 'Entities\\Leltarfej' . "\0" . 'zaras', '' . "\0" . 'Entities\\Leltarfej' . "\0" . 'zarva', '' . "\0" . 'Entities\\Leltarfej' . "\0" . 'leltartetelek'];
        }

        return ['__isInitialized__', '' . "\0" . 'Entities\\Leltarfej' . "\0" . 'id', '' . "\0" . 'Entities\\Leltarfej' . "\0" . 'created', '' . "\0" . 'Entities\\Leltarfej' . "\0" . 'lastmod', '' . "\0" . 'Entities\\Leltarfej' . "\0" . 'createdby', '' . "\0" . 'Entities\\Leltarfej' . "\0" . 'updatedby', '' . "\0" . 'Entities\\Leltarfej' . "\0" . 'raktar', '' . "\0" . 'Entities\\Leltarfej' . "\0" . 'nyitas', '' . "\0" . 'Entities\\Leltarfej' . "\0" . 'zaras', '' . "\0" . 'Entities\\Leltarfej' . "\0" . 'zarva', '' . "\0" . 'Entities\\Leltarfej' . "\0" . 'leltartetelek'];
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (Leltarfej $proxy) {
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
    public function __toString()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, '__toString', []);

        return parent::__toString();
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
    public function toLista()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'toLista', []);

        return parent::toLista();
    }

    /**
     * {@inheritDoc}
     */
    public function getLastmod()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLastmod', []);

        return parent::getLastmod();
    }

    /**
     * {@inheritDoc}
     */
    public function getLastmodStr()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLastmodStr', []);

        return parent::getLastmodStr();
    }

    /**
     * {@inheritDoc}
     */
    public function clearLastmod()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'clearLastmod', []);

        return parent::clearLastmod();
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
    public function getCreatedby()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCreatedby', []);

        return parent::getCreatedby();
    }

    /**
     * {@inheritDoc}
     */
    public function getCreatedbyId()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCreatedbyId', []);

        return parent::getCreatedbyId();
    }

    /**
     * {@inheritDoc}
     */
    public function getCreatedbyNev()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCreatedbyNev', []);

        return parent::getCreatedbyNev();
    }

    /**
     * {@inheritDoc}
     */
    public function getUpdatedby()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getUpdatedby', []);

        return parent::getUpdatedby();
    }

    /**
     * {@inheritDoc}
     */
    public function getUpdatedbyId()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getUpdatedbyId', []);

        return parent::getUpdatedbyId();
    }

    /**
     * {@inheritDoc}
     */
    public function getUpdatedbyNev()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getUpdatedbyNev', []);

        return parent::getUpdatedbyNev();
    }

    /**
     * {@inheritDoc}
     */
    public function getRaktar()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRaktar', []);

        return parent::getRaktar();
    }

    /**
     * {@inheritDoc}
     */
    public function getRaktarnev()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRaktarnev', []);

        return parent::getRaktarnev();
    }

    /**
     * {@inheritDoc}
     */
    public function getRaktarId()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRaktarId', []);

        return parent::getRaktarId();
    }

    /**
     * {@inheritDoc}
     */
    public function setRaktar($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setRaktar', [$val]);

        return parent::setRaktar($val);
    }

    /**
     * {@inheritDoc}
     */
    public function removeRaktar()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeRaktar', []);

        return parent::removeRaktar();
    }

    /**
     * {@inheritDoc}
     */
    public function getNyitas()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNyitas', []);

        return parent::getNyitas();
    }

    /**
     * {@inheritDoc}
     */
    public function getNyitasStr()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNyitasStr', []);

        return parent::getNyitasStr();
    }

    /**
     * {@inheritDoc}
     */
    public function setNyitas($adat = '')
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setNyitas', [$adat]);

        return parent::setNyitas($adat);
    }

    /**
     * {@inheritDoc}
     */
    public function getZaras()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getZaras', []);

        return parent::getZaras();
    }

    /**
     * {@inheritDoc}
     */
    public function getZarasStr()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getZarasStr', []);

        return parent::getZarasStr();
    }

    /**
     * {@inheritDoc}
     */
    public function setZaras($adat = '')
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setZaras', [$adat]);

        return parent::setZaras($adat);
    }

    /**
     * {@inheritDoc}
     */
    public function getZarva()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getZarva', []);

        return parent::getZarva();
    }

    /**
     * {@inheritDoc}
     */
    public function setZarva($zarva)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setZarva', [$zarva]);

        return parent::setZarva($zarva);
    }

    /**
     * {@inheritDoc}
     */
    public function getLeltartetelek()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLeltartetelek', []);

        return parent::getLeltartetelek();
    }

    /**
     * {@inheritDoc}
     */
    public function addLeltartetel(\Entities\Leltartetel $val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addLeltartetel', [$val]);

        return parent::addLeltartetel($val);
    }

    /**
     * {@inheritDoc}
     */
    public function removeLeltartetel(\Entities\Leltartetel $val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeLeltartetel', [$val]);

        return parent::removeLeltartetel($val);
    }

    /**
     * {@inheritDoc}
     */
    public function clearLeltartetelek()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'clearLeltartetelek', []);

        return parent::clearLeltartetelek();
    }

}
