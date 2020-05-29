<?php

namespace Proxies\__CG__\Entities;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class PartnerMIJSZTanitas extends \Entities\PartnerMIJSZTanitas implements \Doctrine\ORM\Proxy\Proxy
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
            return ['__isInitialized__', '' . "\0" . 'Entities\\PartnerMIJSZTanitas' . "\0" . 'id', '' . "\0" . 'Entities\\PartnerMIJSZTanitas' . "\0" . 'partner', '' . "\0" . 'Entities\\PartnerMIJSZTanitas' . "\0" . 'helyszin', '' . "\0" . 'Entities\\PartnerMIJSZTanitas' . "\0" . 'mikor', '' . "\0" . 'Entities\\PartnerMIJSZTanitas' . "\0" . 'nap', '' . "\0" . 'Entities\\PartnerMIJSZTanitas' . "\0" . 'szint', '' . "\0" . 'Entities\\PartnerMIJSZTanitas' . "\0" . 'szintegyeb', '' . "\0" . 'Entities\\PartnerMIJSZTanitas' . "\0" . 'created', '' . "\0" . 'Entities\\PartnerMIJSZTanitas' . "\0" . 'lastmod', '' . "\0" . 'Entities\\PartnerMIJSZTanitas' . "\0" . 'createdby', '' . "\0" . 'Entities\\PartnerMIJSZTanitas' . "\0" . 'updatedby'];
        }

        return ['__isInitialized__', '' . "\0" . 'Entities\\PartnerMIJSZTanitas' . "\0" . 'id', '' . "\0" . 'Entities\\PartnerMIJSZTanitas' . "\0" . 'partner', '' . "\0" . 'Entities\\PartnerMIJSZTanitas' . "\0" . 'helyszin', '' . "\0" . 'Entities\\PartnerMIJSZTanitas' . "\0" . 'mikor', '' . "\0" . 'Entities\\PartnerMIJSZTanitas' . "\0" . 'nap', '' . "\0" . 'Entities\\PartnerMIJSZTanitas' . "\0" . 'szint', '' . "\0" . 'Entities\\PartnerMIJSZTanitas' . "\0" . 'szintegyeb', '' . "\0" . 'Entities\\PartnerMIJSZTanitas' . "\0" . 'created', '' . "\0" . 'Entities\\PartnerMIJSZTanitas' . "\0" . 'lastmod', '' . "\0" . 'Entities\\PartnerMIJSZTanitas' . "\0" . 'createdby', '' . "\0" . 'Entities\\PartnerMIJSZTanitas' . "\0" . 'updatedby'];
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (PartnerMIJSZTanitas $proxy) {
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
    public function toLista()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'toLista', []);

        return parent::toLista();
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
    public function getHelyszin()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getHelyszin', []);

        return parent::getHelyszin();
    }

    /**
     * {@inheritDoc}
     */
    public function setHelyszin($helyszin)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setHelyszin', [$helyszin]);

        return parent::setHelyszin($helyszin);
    }

    /**
     * {@inheritDoc}
     */
    public function getMikor()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMikor', []);

        return parent::getMikor();
    }

    /**
     * {@inheritDoc}
     */
    public function setMikor($mikor)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMikor', [$mikor]);

        return parent::setMikor($mikor);
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
    public function setNap($nap)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setNap', [$nap]);

        return parent::setNap($nap);
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
    public function getSzintNev()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSzintNev', []);

        return parent::getSzintNev();
    }

    /**
     * {@inheritDoc}
     */
    public function getSzintId()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSzintId', []);

        return parent::getSzintId();
    }

    /**
     * {@inheritDoc}
     */
    public function getSzint()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSzint', []);

        return parent::getSzint();
    }

    /**
     * {@inheritDoc}
     */
    public function setSzint($szint)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSzint', [$szint]);

        return parent::setSzint($szint);
    }

    /**
     * {@inheritDoc}
     */
    public function getSzintegyeb()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSzintegyeb', []);

        return parent::getSzintegyeb();
    }

    /**
     * {@inheritDoc}
     */
    public function setSzintegyeb($szintegyeb)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSzintegyeb', [$szintegyeb]);

        return parent::setSzintegyeb($szintegyeb);
    }

}
