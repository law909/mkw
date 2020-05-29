<?php

namespace Proxies\__CG__\Entities;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class Partnercimketorzs extends \Entities\Partnercimketorzs implements \Doctrine\ORM\Proxy\Proxy
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
            return ['__isInitialized__', '' . "\0" . 'Entities\\Partnercimketorzs' . "\0" . 'nev', '' . "\0" . 'Entities\\Partnercimketorzs' . "\0" . 'slug', '' . "\0" . 'Entities\\Partnercimketorzs' . "\0" . 'partnerek', '' . "\0" . 'Entities\\Partnercimketorzs' . "\0" . 'kategoria'];
        }

        return ['__isInitialized__', '' . "\0" . 'Entities\\Partnercimketorzs' . "\0" . 'nev', '' . "\0" . 'Entities\\Partnercimketorzs' . "\0" . 'slug', '' . "\0" . 'Entities\\Partnercimketorzs' . "\0" . 'partnerek', '' . "\0" . 'Entities\\Partnercimketorzs' . "\0" . 'kategoria'];
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (Partnercimketorzs $proxy) {
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
    public function getKategoria()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getKategoria', []);

        return parent::getKategoria();
    }

    /**
     * {@inheritDoc}
     */
    public function getKategoriaId()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getKategoriaId', []);

        return parent::getKategoriaId();
    }

    /**
     * {@inheritDoc}
     */
    public function setKategoria(\Entities\Cimkekat $kategoria)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setKategoria', [$kategoria]);

        return parent::setKategoria($kategoria);
    }

    /**
     * {@inheritDoc}
     */
    public function removeKategoria()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeKategoria', []);

        return parent::removeKategoria();
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
    public function getSlug()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSlug', []);

        return parent::getSlug();
    }

    /**
     * {@inheritDoc}
     */
    public function setSlug($slug)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSlug', [$slug]);

        return parent::setSlug($slug);
    }

    /**
     * {@inheritDoc}
     */
    public function getPartnerek()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPartnerek', []);

        return parent::getPartnerek();
    }

    /**
     * {@inheritDoc}
     */
    public function addPartner(\Entities\Partner $partner)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addPartner', [$partner]);

        return parent::addPartner($partner);
    }

    /**
     * {@inheritDoc}
     */
    public function removePartner(\Entities\Partner $partner)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removePartner', [$partner]);

        return parent::removePartner($partner);
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
    public function getMenu1lathato()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMenu1lathato', []);

        return parent::getMenu1lathato();
    }

    /**
     * {@inheritDoc}
     */
    public function setMenu1lathato($menu1lathato)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMenu1lathato', [$menu1lathato]);

        return parent::setMenu1lathato($menu1lathato);
    }

    /**
     * {@inheritDoc}
     */
    public function getMenu2lathato()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMenu2lathato', []);

        return parent::getMenu2lathato();
    }

    /**
     * {@inheritDoc}
     */
    public function setMenu2lathato($menu2lathato)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMenu2lathato', [$menu2lathato]);

        return parent::setMenu2lathato($menu2lathato);
    }

    /**
     * {@inheritDoc}
     */
    public function getMenu3lathato()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMenu3lathato', []);

        return parent::getMenu3lathato();
    }

    /**
     * {@inheritDoc}
     */
    public function setMenu3lathato($menu3lathato)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMenu3lathato', [$menu3lathato]);

        return parent::setMenu3lathato($menu3lathato);
    }

    /**
     * {@inheritDoc}
     */
    public function getMenu4lathato()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMenu4lathato', []);

        return parent::getMenu4lathato();
    }

    /**
     * {@inheritDoc}
     */
    public function setMenu4lathato($menu4lathato)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMenu4lathato', [$menu4lathato]);

        return parent::setMenu4lathato($menu4lathato);
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
    public function getOldalcim()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getOldalcim', []);

        return parent::getOldalcim();
    }

    /**
     * {@inheritDoc}
     */
    public function setOldalcim($oldalcim)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setOldalcim', [$oldalcim]);

        return parent::setOldalcim($oldalcim);
    }

    /**
     * {@inheritDoc}
     */
    public function getKepurl($pre = '/')
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getKepurl', [$pre]);

        return parent::getKepurl($pre);
    }

    /**
     * {@inheritDoc}
     */
    public function getKepurlSmall($pre = '/')
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getKepurlSmall', [$pre]);

        return parent::getKepurlSmall($pre);
    }

    /**
     * {@inheritDoc}
     */
    public function getKepurlMedium($pre = '/')
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getKepurlMedium', [$pre]);

        return parent::getKepurlMedium($pre);
    }

    /**
     * {@inheritDoc}
     */
    public function getKepurlLarge($pre = '/')
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getKepurlLarge', [$pre]);

        return parent::getKepurlLarge($pre);
    }

    /**
     * {@inheritDoc}
     */
    public function setKepurl($kepurl)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setKepurl', [$kepurl]);

        return parent::setKepurl($kepurl);
    }

    /**
     * {@inheritDoc}
     */
    public function getKepleiras()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getKepleiras', []);

        return parent::getKepleiras();
    }

    /**
     * {@inheritDoc}
     */
    public function setKepleiras($kepleiras)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setKepleiras', [$kepleiras]);

        return parent::setKepleiras($kepleiras);
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

}
