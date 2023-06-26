<?php

namespace Proxies\__CG__\Entities;


/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class MNRStaticPageKep extends \Entities\MNRStaticPageKep implements \Doctrine\ORM\Proxy\Proxy
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
            return ['__isInitialized__', '' . "\0" . 'Entities\\MNRStaticPageKep' . "\0" . 'id', '' . "\0" . 'Entities\\MNRStaticPageKep' . "\0" . 'created', '' . "\0" . 'Entities\\MNRStaticPageKep' . "\0" . 'lastmod', '' . "\0" . 'Entities\\MNRStaticPageKep' . "\0" . 'mnrstaticpage', '' . "\0" . 'Entities\\MNRStaticPageKep' . "\0" . 'url', '' . "\0" . 'Entities\\MNRStaticPageKep' . "\0" . 'rejtett'];
        }

        return ['__isInitialized__', '' . "\0" . 'Entities\\MNRStaticPageKep' . "\0" . 'id', '' . "\0" . 'Entities\\MNRStaticPageKep' . "\0" . 'created', '' . "\0" . 'Entities\\MNRStaticPageKep' . "\0" . 'lastmod', '' . "\0" . 'Entities\\MNRStaticPageKep' . "\0" . 'mnrstaticpage', '' . "\0" . 'Entities\\MNRStaticPageKep' . "\0" . 'url', '' . "\0" . 'Entities\\MNRStaticPageKep' . "\0" . 'rejtett'];
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (MNRStaticPageKep $proxy) {
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
    public function toPublic()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'toPublic', []);

        return parent::toPublic();
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
    public function getMnrstaticpage()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMnrstaticpage', []);

        return parent::getMnrstaticpage();
    }

    /**
     * {@inheritDoc}
     */
    public function getMnrStaticPageNev()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMnrStaticPageNev', []);

        return parent::getMnrStaticPageNev();
    }

    /**
     * {@inheritDoc}
     */
    public function setMnrstaticpage(\Entities\MNRStaticPage $mnrstaticpage)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMnrstaticpage', [$mnrstaticpage]);

        return parent::setMnrstaticpage($mnrstaticpage);
    }

    /**
     * {@inheritDoc}
     */
    public function removeMnrstaticpage()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeMnrstaticpage', []);

        return parent::removeMnrstaticpage();
    }

    /**
     * {@inheritDoc}
     */
    public function getUrl($pre = '/')
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getUrl', [$pre]);

        return parent::getUrl($pre);
    }

    /**
     * {@inheritDoc}
     */
    public function getUrlMini($pre = '/')
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getUrlMini', [$pre]);

        return parent::getUrlMini($pre);
    }

    /**
     * {@inheritDoc}
     */
    public function getUrlSmall($pre = '/')
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getUrlSmall', [$pre]);

        return parent::getUrlSmall($pre);
    }

    /**
     * {@inheritDoc}
     */
    public function getUrlMedium($pre = '/')
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getUrlMedium', [$pre]);

        return parent::getUrlMedium($pre);
    }

    /**
     * {@inheritDoc}
     */
    public function getUrlLarge($pre = '/')
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getUrlLarge', [$pre]);

        return parent::getUrlLarge($pre);
    }

    /**
     * {@inheritDoc}
     */
    public function setUrl($url)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setUrl', [$url]);

        return parent::setUrl($url);
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

    /**
     * {@inheritDoc}
     */
    public function getRejtett()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRejtett', []);

        return parent::getRejtett();
    }

    /**
     * {@inheritDoc}
     */
    public function setRejtett($rejtett)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setRejtett', [$rejtett]);

        return parent::setRejtett($rejtett);
    }

}
