<?php

namespace Proxies\__CG__\Entities;


/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class Arfolyam extends \Entities\Arfolyam implements \Doctrine\ORM\Proxy\Proxy
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
            return ['__isInitialized__', '' . "\0" . 'Entities\\Arfolyam' . "\0" . 'id', '' . "\0" . 'Entities\\Arfolyam' . "\0" . 'created', '' . "\0" . 'Entities\\Arfolyam' . "\0" . 'lastmod', '' . "\0" . 'Entities\\Arfolyam' . "\0" . 'datum', '' . "\0" . 'Entities\\Arfolyam' . "\0" . 'valutanem', '' . "\0" . 'Entities\\Arfolyam' . "\0" . 'arfolyam'];
        }

        return ['__isInitialized__', '' . "\0" . 'Entities\\Arfolyam' . "\0" . 'id', '' . "\0" . 'Entities\\Arfolyam' . "\0" . 'created', '' . "\0" . 'Entities\\Arfolyam' . "\0" . 'lastmod', '' . "\0" . 'Entities\\Arfolyam' . "\0" . 'datum', '' . "\0" . 'Entities\\Arfolyam' . "\0" . 'valutanem', '' . "\0" . 'Entities\\Arfolyam' . "\0" . 'arfolyam'];
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (Arfolyam $proxy) {
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
    public function __load(): void
    {
        $this->__initializer__ && $this->__initializer__->__invoke($this, '__load', []);
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __isInitialized(): bool
    {
        return $this->__isInitialized__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitialized($initialized): void
    {
        $this->__isInitialized__ = $initialized;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitializer(\Closure $initializer = null): void
    {
        $this->__initializer__ = $initializer;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __getInitializer(): ?\Closure
    {
        return $this->__initializer__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setCloner(\Closure $cloner = null): void
    {
        $this->__cloner__ = $cloner;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific cloning logic
     */
    public function __getCloner(): ?\Closure
    {
        return $this->__cloner__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     * @deprecated no longer in use - generated code now relies on internal components rather than generated public API
     * @static
     */
    public function __getLazyProperties(): array
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
    public function getDatum()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDatum', []);

        return parent::getDatum();
    }

    /**
     * {@inheritDoc}
     */
    public function getDatumString()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDatumString', []);

        return parent::getDatumString();
    }

    /**
     * {@inheritDoc}
     */
    public function setDatum($datum)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDatum', [$datum]);

        return parent::setDatum($datum);
    }

    /**
     * {@inheritDoc}
     */
    public function getValutanem()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getValutanem', []);

        return parent::getValutanem();
    }

    /**
     * {@inheritDoc}
     */
    public function getValutanemNev()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getValutanemNev', []);

        return parent::getValutanemNev();
    }

    /**
     * {@inheritDoc}
     */
    public function setValutanem($valutanem)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setValutanem', [$valutanem]);

        return parent::setValutanem($valutanem);
    }

    /**
     * {@inheritDoc}
     */
    public function getArfolyam()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getArfolyam', []);

        return parent::getArfolyam();
    }

    /**
     * {@inheritDoc}
     */
    public function setArfolyam($arfolyam)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setArfolyam', [$arfolyam]);

        return parent::setArfolyam($arfolyam);
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
    public function getCreated()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCreated', []);

        return parent::getCreated();
    }

}
