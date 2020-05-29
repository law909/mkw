<?php

namespace Proxies\__CG__\Entities;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class JogaBejelentkezes extends \Entities\JogaBejelentkezes implements \Doctrine\ORM\Proxy\Proxy
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
            return ['__isInitialized__', '' . "\0" . 'Entities\\JogaBejelentkezes' . "\0" . 'id', '' . "\0" . 'Entities\\JogaBejelentkezes' . "\0" . 'created', '' . "\0" . 'Entities\\JogaBejelentkezes' . "\0" . 'lastmod', '' . "\0" . 'Entities\\JogaBejelentkezes' . "\0" . 'createdby', '' . "\0" . 'Entities\\JogaBejelentkezes' . "\0" . 'updatedby', '' . "\0" . 'Entities\\JogaBejelentkezes' . "\0" . 'orarend', '' . "\0" . 'Entities\\JogaBejelentkezes' . "\0" . 'partnernev', '' . "\0" . 'Entities\\JogaBejelentkezes' . "\0" . 'partneremail', '' . "\0" . 'Entities\\JogaBejelentkezes' . "\0" . 'datum'];
        }

        return ['__isInitialized__', '' . "\0" . 'Entities\\JogaBejelentkezes' . "\0" . 'id', '' . "\0" . 'Entities\\JogaBejelentkezes' . "\0" . 'created', '' . "\0" . 'Entities\\JogaBejelentkezes' . "\0" . 'lastmod', '' . "\0" . 'Entities\\JogaBejelentkezes' . "\0" . 'createdby', '' . "\0" . 'Entities\\JogaBejelentkezes' . "\0" . 'updatedby', '' . "\0" . 'Entities\\JogaBejelentkezes' . "\0" . 'orarend', '' . "\0" . 'Entities\\JogaBejelentkezes' . "\0" . 'partnernev', '' . "\0" . 'Entities\\JogaBejelentkezes' . "\0" . 'partneremail', '' . "\0" . 'Entities\\JogaBejelentkezes' . "\0" . 'datum'];
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (JogaBejelentkezes $proxy) {
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
    public function getOrarend()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getOrarend', []);

        return parent::getOrarend();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrarendId()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getOrarendId', []);

        return parent::getOrarendId();
    }

    /**
     * {@inheritDoc}
     */
    public function setOrarend($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setOrarend', [$val]);

        return parent::setOrarend($val);
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
    public function setPartnernev($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPartnernev', [$val]);

        return parent::setPartnernev($val);
    }

    /**
     * {@inheritDoc}
     */
    public function getPartneremail()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPartneremail', []);

        return parent::getPartneremail();
    }

    /**
     * {@inheritDoc}
     */
    public function setPartneremail($partneremail)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPartneremail', [$partneremail]);

        return parent::setPartneremail($partneremail);
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
    public function getDatumNapnev()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDatumNapnev', []);

        return parent::getDatumNapnev();
    }

    /**
     * {@inheritDoc}
     */
    public function setDatum($adat = '')
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDatum', [$adat]);

        return parent::setDatum($adat);
    }

}
