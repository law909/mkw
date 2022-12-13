<?php

namespace Proxies\__CG__\Entities;


/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class TermekErtekeles extends \Entities\TermekErtekeles implements \Doctrine\ORM\Proxy\Proxy
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
            return ['__isInitialized__', '' . "\0" . 'Entities\\TermekErtekeles' . "\0" . 'id', '' . "\0" . 'Entities\\TermekErtekeles' . "\0" . 'created', '' . "\0" . 'Entities\\TermekErtekeles' . "\0" . 'lastmod', '' . "\0" . 'Entities\\TermekErtekeles' . "\0" . 'szoveg', '' . "\0" . 'Entities\\TermekErtekeles' . "\0" . 'elony', '' . "\0" . 'Entities\\TermekErtekeles' . "\0" . 'hatrany', '' . "\0" . 'Entities\\TermekErtekeles' . "\0" . 'valasz', '' . "\0" . 'Entities\\TermekErtekeles' . "\0" . 'ertekeles', '' . "\0" . 'Entities\\TermekErtekeles' . "\0" . 'termek', '' . "\0" . 'Entities\\TermekErtekeles' . "\0" . 'partner', '' . "\0" . 'Entities\\TermekErtekeles' . "\0" . 'elutasitva'];
        }

        return ['__isInitialized__', '' . "\0" . 'Entities\\TermekErtekeles' . "\0" . 'id', '' . "\0" . 'Entities\\TermekErtekeles' . "\0" . 'created', '' . "\0" . 'Entities\\TermekErtekeles' . "\0" . 'lastmod', '' . "\0" . 'Entities\\TermekErtekeles' . "\0" . 'szoveg', '' . "\0" . 'Entities\\TermekErtekeles' . "\0" . 'elony', '' . "\0" . 'Entities\\TermekErtekeles' . "\0" . 'hatrany', '' . "\0" . 'Entities\\TermekErtekeles' . "\0" . 'valasz', '' . "\0" . 'Entities\\TermekErtekeles' . "\0" . 'ertekeles', '' . "\0" . 'Entities\\TermekErtekeles' . "\0" . 'termek', '' . "\0" . 'Entities\\TermekErtekeles' . "\0" . 'partner', '' . "\0" . 'Entities\\TermekErtekeles' . "\0" . 'elutasitva'];
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (TermekErtekeles $proxy) {
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
    public function getCreatedDateStr()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCreatedDateStr', []);

        return parent::getCreatedDateStr();
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
    public function setPartner(\Entities\Partner $val)
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
    public function getTermek()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTermek', []);

        return parent::getTermek();
    }

    /**
     * {@inheritDoc}
     */
    public function getTermekId()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTermekId', []);

        return parent::getTermekId();
    }

    /**
     * {@inheritDoc}
     */
    public function getTermekNev()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTermekNev', []);

        return parent::getTermekNev();
    }

    /**
     * {@inheritDoc}
     */
    public function getTermekSlug()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTermekSlug', []);

        return parent::getTermekSlug();
    }

    /**
     * {@inheritDoc}
     */
    public function setTermek(\Entities\Termek $val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTermek', [$val]);

        return parent::setTermek($val);
    }

    /**
     * {@inheritDoc}
     */
    public function removeTermek()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeTermek', []);

        return parent::removeTermek();
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
    public function setSzoveg($szoveg): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSzoveg', [$szoveg]);

        parent::setSzoveg($szoveg);
    }

    /**
     * {@inheritDoc}
     */
    public function getElony()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getElony', []);

        return parent::getElony();
    }

    /**
     * {@inheritDoc}
     */
    public function setElony($elony): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setElony', [$elony]);

        parent::setElony($elony);
    }

    /**
     * {@inheritDoc}
     */
    public function getHatrany()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getHatrany', []);

        return parent::getHatrany();
    }

    /**
     * {@inheritDoc}
     */
    public function setHatrany($hatrany): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setHatrany', [$hatrany]);

        parent::setHatrany($hatrany);
    }

    /**
     * {@inheritDoc}
     */
    public function getValasz()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getValasz', []);

        return parent::getValasz();
    }

    /**
     * {@inheritDoc}
     */
    public function setValasz($valasz): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setValasz', [$valasz]);

        parent::setValasz($valasz);
    }

    /**
     * {@inheritDoc}
     */
    public function getErtekeles()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getErtekeles', []);

        return parent::getErtekeles();
    }

    /**
     * {@inheritDoc}
     */
    public function setErtekeles($ertekeles): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setErtekeles', [$ertekeles]);

        parent::setErtekeles($ertekeles);
    }

    /**
     * {@inheritDoc}
     */
    public function isElutasitva()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'isElutasitva', []);

        return parent::isElutasitva();
    }

    /**
     * {@inheritDoc}
     */
    public function setElutasitva($elutasitva): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setElutasitva', [$elutasitva]);

        parent::setElutasitva($elutasitva);
    }

}
