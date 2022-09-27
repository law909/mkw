<?php

namespace Proxies\__CG__\Entities;


/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class MPTFolyoszamla extends \Entities\MPTFolyoszamla implements \Doctrine\ORM\Proxy\Proxy
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
            return ['__isInitialized__', '' . "\0" . 'Entities\\MPTFolyoszamla' . "\0" . 'id', '' . "\0" . 'Entities\\MPTFolyoszamla' . "\0" . 'tipus', '' . "\0" . 'Entities\\MPTFolyoszamla' . "\0" . 'partner', '' . "\0" . 'Entities\\MPTFolyoszamla' . "\0" . 'irany', '' . "\0" . 'Entities\\MPTFolyoszamla' . "\0" . 'bizonylatszam', '' . "\0" . 'Entities\\MPTFolyoszamla' . "\0" . 'datum', '' . "\0" . 'Entities\\MPTFolyoszamla' . "\0" . 'megjegyzes', '' . "\0" . 'Entities\\MPTFolyoszamla' . "\0" . 'osszeg', '' . "\0" . 'Entities\\MPTFolyoszamla' . "\0" . 'vonatkozoev'];
        }

        return ['__isInitialized__', '' . "\0" . 'Entities\\MPTFolyoszamla' . "\0" . 'id', '' . "\0" . 'Entities\\MPTFolyoszamla' . "\0" . 'tipus', '' . "\0" . 'Entities\\MPTFolyoszamla' . "\0" . 'partner', '' . "\0" . 'Entities\\MPTFolyoszamla' . "\0" . 'irany', '' . "\0" . 'Entities\\MPTFolyoszamla' . "\0" . 'bizonylatszam', '' . "\0" . 'Entities\\MPTFolyoszamla' . "\0" . 'datum', '' . "\0" . 'Entities\\MPTFolyoszamla' . "\0" . 'megjegyzes', '' . "\0" . 'Entities\\MPTFolyoszamla' . "\0" . 'osszeg', '' . "\0" . 'Entities\\MPTFolyoszamla' . "\0" . 'vonatkozoev'];
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (MPTFolyoszamla $proxy) {
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
    public function getTipus()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTipus', []);

        return parent::getTipus();
    }

    /**
     * {@inheritDoc}
     */
    public function getTipusnev()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTipusnev', []);

        return parent::getTipusnev();
    }

    /**
     * {@inheritDoc}
     */
    public function setTipus($tipus): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTipus', [$tipus]);

        parent::setTipus($tipus);
    }

    /**
     * {@inheritDoc}
     */
    public function getIrany()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getIrany', []);

        return parent::getIrany();
    }

    /**
     * {@inheritDoc}
     */
    public function setIrany($irany): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setIrany', [$irany]);

        parent::setIrany($irany);
    }

    /**
     * {@inheritDoc}
     */
    public function getBizonylatszam()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getBizonylatszam', []);

        return parent::getBizonylatszam();
    }

    /**
     * {@inheritDoc}
     */
    public function setBizonylatszam($bizonylatszam): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setBizonylatszam', [$bizonylatszam]);

        parent::setBizonylatszam($bizonylatszam);
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
    public function setMegjegyzes($megjegyzes): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMegjegyzes', [$megjegyzes]);

        parent::setMegjegyzes($megjegyzes);
    }

    /**
     * {@inheritDoc}
     */
    public function getOsszeg()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getOsszeg', []);

        return parent::getOsszeg();
    }

    /**
     * {@inheritDoc}
     */
    public function setOsszeg($osszeg): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setOsszeg', [$osszeg]);

        parent::setOsszeg($osszeg);
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
    public function setPartner($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPartner', [$val]);

        return parent::setPartner($val);
    }

    /**
     * {@inheritDoc}
     */
    public function removePartner()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removePartner', []);

        return parent::removePartner();
    }

    /**
     * {@inheritDoc}
     */
    public function getPartnernev()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPartnernev', []);

        return parent::getPartnernev();
    }

    /**
     * {@inheritDoc}
     */
    public function getPartnervezeteknev()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPartnervezeteknev', []);

        return parent::getPartnervezeteknev();
    }

    /**
     * {@inheritDoc}
     */
    public function getPartnerkeresztnev()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPartnerkeresztnev', []);

        return parent::getPartnerkeresztnev();
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
    public function getDatumStr()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDatumStr', []);

        return parent::getDatumStr();
    }

    /**
     * {@inheritDoc}
     */
    public function setDatum($adat = '')
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDatum', [$adat]);

        return parent::setDatum($adat);
    }

    /**
     * {@inheritDoc}
     */
    public function getVonatkozoev()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getVonatkozoev', []);

        return parent::getVonatkozoev();
    }

    /**
     * {@inheritDoc}
     */
    public function setVonatkozoev($vonatkozoev): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setVonatkozoev', [$vonatkozoev]);

        parent::setVonatkozoev($vonatkozoev);
    }

}