<?php

namespace Proxies\__CG__\Entities;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class Teendo extends \Entities\Teendo implements \Doctrine\ORM\Proxy\Proxy
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
            return ['__isInitialized__', '' . "\0" . 'Entities\\Teendo' . "\0" . 'elvegezve', '' . "\0" . 'Entities\\Teendo' . "\0" . 'elvegezve_mikor', '' . "\0" . 'Entities\\Teendo' . "\0" . 'partner'];
        }

        return ['__isInitialized__', '' . "\0" . 'Entities\\Teendo' . "\0" . 'elvegezve', '' . "\0" . 'Entities\\Teendo' . "\0" . 'elvegezve_mikor', '' . "\0" . 'Entities\\Teendo' . "\0" . 'partner'];
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (Teendo $proxy) {
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
    public function getElvegezve()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getElvegezve', []);

        return parent::getElvegezve();
    }

    /**
     * {@inheritDoc}
     */
    public function setElvegezve($elvegezve)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setElvegezve', [$elvegezve]);

        return parent::setElvegezve($elvegezve);
    }

    /**
     * {@inheritDoc}
     */
    public function getElvegezveMikor()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getElvegezveMikor', []);

        return parent::getElvegezveMikor();
    }

    /**
     * {@inheritDoc}
     */
    public function getElvegezveMikorStr()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getElvegezveMikorStr', []);

        return parent::getElvegezveMikorStr();
    }

    /**
     * {@inheritDoc}
     */
    public function setElvegezveMikor($elvegezve_mikor)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setElvegezveMikor', [$elvegezve_mikor]);

        return parent::setElvegezveMikor($elvegezve_mikor);
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
    public function setPartner(\Entities\Partner $partner)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPartner', [$partner]);

        return parent::setPartner($partner);
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
    public function getBejegyzes()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getBejegyzes', []);

        return parent::getBejegyzes();
    }

    /**
     * {@inheritDoc}
     */
    public function setBejegyzes($bejegyzes)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setBejegyzes', [$bejegyzes]);

        return parent::setBejegyzes($bejegyzes);
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
    public function getLetrehozva()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLetrehozva', []);

        return parent::getLetrehozva();
    }

    /**
     * {@inheritDoc}
     */
    public function setLetrehozvaOnPreInsert()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setLetrehozvaOnPreInsert', []);

        return parent::setLetrehozvaOnPreInsert();
    }

    /**
     * {@inheritDoc}
     */
    public function setLetrehozva($letrehozva)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setLetrehozva', [$letrehozva]);

        return parent::setLetrehozva($letrehozva);
    }

    /**
     * {@inheritDoc}
     */
    public function getEsedekes()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getEsedekes', []);

        return parent::getEsedekes();
    }

    /**
     * {@inheritDoc}
     */
    public function getEsedekesStr()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getEsedekesStr', []);

        return parent::getEsedekesStr();
    }

    /**
     * {@inheritDoc}
     */
    public function setEsedekes($esedekes = '')
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setEsedekes', [$esedekes]);

        return parent::setEsedekes($esedekes);
    }

}
