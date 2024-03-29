<?php

namespace Proxies\__CG__\Entities;


/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class PartnerTermekcsoportKedvezmeny extends \Entities\PartnerTermekcsoportKedvezmeny implements \Doctrine\ORM\Proxy\Proxy
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
            return ['__isInitialized__', '' . "\0" . 'Entities\\PartnerTermekcsoportKedvezmeny' . "\0" . 'id', '' . "\0" . 'Entities\\PartnerTermekcsoportKedvezmeny' . "\0" . 'created', '' . "\0" . 'Entities\\PartnerTermekcsoportKedvezmeny' . "\0" . 'lastmod', '' . "\0" . 'Entities\\PartnerTermekcsoportKedvezmeny' . "\0" . 'partner', '' . "\0" . 'Entities\\PartnerTermekcsoportKedvezmeny' . "\0" . 'termekcsoport', '' . "\0" . 'Entities\\PartnerTermekcsoportKedvezmeny' . "\0" . 'kedvezmeny'];
        }

        return ['__isInitialized__', '' . "\0" . 'Entities\\PartnerTermekcsoportKedvezmeny' . "\0" . 'id', '' . "\0" . 'Entities\\PartnerTermekcsoportKedvezmeny' . "\0" . 'created', '' . "\0" . 'Entities\\PartnerTermekcsoportKedvezmeny' . "\0" . 'lastmod', '' . "\0" . 'Entities\\PartnerTermekcsoportKedvezmeny' . "\0" . 'partner', '' . "\0" . 'Entities\\PartnerTermekcsoportKedvezmeny' . "\0" . 'termekcsoport', '' . "\0" . 'Entities\\PartnerTermekcsoportKedvezmeny' . "\0" . 'kedvezmeny'];
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (PartnerTermekcsoportKedvezmeny $proxy) {
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
    public function toLista()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'toLista', []);

        return parent::toLista();
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
    public function getKedvezmeny()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getKedvezmeny', []);

        return parent::getKedvezmeny();
    }

    /**
     * {@inheritDoc}
     */
    public function setKedvezmeny($kedvezmeny)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setKedvezmeny', [$kedvezmeny]);

        return parent::setKedvezmeny($kedvezmeny);
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
    public function getTermekcsoport()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTermekcsoport', []);

        return parent::getTermekcsoport();
    }

    /**
     * {@inheritDoc}
     */
    public function getTermekcsoportId()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTermekcsoportId', []);

        return parent::getTermekcsoportId();
    }

    /**
     * {@inheritDoc}
     */
    public function getTermekcsoportNev()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTermekcsoportNev', []);

        return parent::getTermekcsoportNev();
    }

    /**
     * {@inheritDoc}
     */
    public function setTermekcsoport($termekcsoport)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTermekcsoport', [$termekcsoport]);

        return parent::setTermekcsoport($termekcsoport);
    }

}
