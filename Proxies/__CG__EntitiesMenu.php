<?php

namespace Proxies\__CG__\Entities;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class Menu extends \Entities\Menu implements \Doctrine\ORM\Proxy\Proxy
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
            return ['__isInitialized__', '' . "\0" . 'Entities\\Menu' . "\0" . 'id', '' . "\0" . 'Entities\\Menu' . "\0" . 'menucsoport', '' . "\0" . 'Entities\\Menu' . "\0" . 'nev', '' . "\0" . 'Entities\\Menu' . "\0" . 'url', '' . "\0" . 'Entities\\Menu' . "\0" . 'routename', '' . "\0" . 'Entities\\Menu' . "\0" . 'jogosultsag', '' . "\0" . 'Entities\\Menu' . "\0" . 'lathato', '' . "\0" . 'Entities\\Menu' . "\0" . 'sorrend', '' . "\0" . 'Entities\\Menu' . "\0" . 'class'];
        }

        return ['__isInitialized__', '' . "\0" . 'Entities\\Menu' . "\0" . 'id', '' . "\0" . 'Entities\\Menu' . "\0" . 'menucsoport', '' . "\0" . 'Entities\\Menu' . "\0" . 'nev', '' . "\0" . 'Entities\\Menu' . "\0" . 'url', '' . "\0" . 'Entities\\Menu' . "\0" . 'routename', '' . "\0" . 'Entities\\Menu' . "\0" . 'jogosultsag', '' . "\0" . 'Entities\\Menu' . "\0" . 'lathato', '' . "\0" . 'Entities\\Menu' . "\0" . 'sorrend', '' . "\0" . 'Entities\\Menu' . "\0" . 'class'];
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (Menu $proxy) {
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
    public function getMenucsoport()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMenucsoport', []);

        return parent::getMenucsoport();
    }

    /**
     * {@inheritDoc}
     */
    public function getMenucsoportId()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMenucsoportId', []);

        return parent::getMenucsoportId();
    }

    /**
     * {@inheritDoc}
     */
    public function getMenucsoportNev()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMenucsoportNev', []);

        return parent::getMenucsoportNev();
    }

    /**
     * {@inheritDoc}
     */
    public function setMenucsoport($menucsoport)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMenucsoport', [$menucsoport]);

        return parent::setMenucsoport($menucsoport);
    }

    /**
     * {@inheritDoc}
     */
    public function getNev()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNev', []);

        return parent::getNev();
    }

    /**
     * {@inheritDoc}
     */
    public function setNev($nev)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setNev', [$nev]);

        return parent::setNev($nev);
    }

    /**
     * {@inheritDoc}
     */
    public function getJogosultsag()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getJogosultsag', []);

        return parent::getJogosultsag();
    }

    /**
     * {@inheritDoc}
     */
    public function setJogosultsag($jogosultsag)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setJogosultsag', [$jogosultsag]);

        return parent::setJogosultsag($jogosultsag);
    }

    /**
     * {@inheritDoc}
     */
    public function getLathato()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLathato', []);

        return parent::getLathato();
    }

    /**
     * {@inheritDoc}
     */
    public function setLathato($lathato)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setLathato', [$lathato]);

        return parent::setLathato($lathato);
    }

    /**
     * {@inheritDoc}
     */
    public function getUrl()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getUrl', []);

        return parent::getUrl();
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
    public function getRoutename()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRoutename', []);

        return parent::getRoutename();
    }

    /**
     * {@inheritDoc}
     */
    public function setRoutename($routename)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setRoutename', [$routename]);

        return parent::setRoutename($routename);
    }

    /**
     * {@inheritDoc}
     */
    public function getSorrend()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSorrend', []);

        return parent::getSorrend();
    }

    /**
     * {@inheritDoc}
     */
    public function setSorrend($sorrend)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSorrend', [$sorrend]);

        return parent::setSorrend($sorrend);
    }

    /**
     * {@inheritDoc}
     */
    public function isMenucsoportLathato($jog)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'isMenucsoportLathato', [$jog]);

        return parent::isMenucsoportLathato($jog);
    }

    /**
     * {@inheritDoc}
     */
    public function isLathato($jog = 0)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'isLathato', [$jog]);

        return parent::isLathato($jog);
    }

    /**
     * {@inheritDoc}
     */
    public function getClass()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getClass', []);

        return parent::getClass();
    }

    /**
     * {@inheritDoc}
     */
    public function setClass($class)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setClass', [$class]);

        return parent::setClass($class);
    }

}
