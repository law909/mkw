<?php

namespace Proxies\__CG__\Entities;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class TermekFa extends \Entities\TermekFa implements \Doctrine\ORM\Proxy\Proxy
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
            return ['__isInitialized__', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'gtnev', 'm1lchanged', 'm2lchanged', 'm3lchanged', 'm4lchanged', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'id', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'created', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'lastmod', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'children', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'parent', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'nev', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'sorrend', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'slug', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'karkod', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'rovidleiras', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'leiras', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'leiras2', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'leiras3', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'menu1lathato', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'menu2lathato', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'menu3lathato', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'menu4lathato', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'oldalcim', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'seodescription', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'kepurl', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'kepleiras', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'termekek1', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'termekek2', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'termekek3', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'inaktiv', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'idegenkod', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'translations', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'emagid', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'locale', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'lathato', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'lathato2', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'lathato3', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'lathato4', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'lathato5', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'lathato6', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'lathato7', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'lathato8', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'lathato9', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'lathato10', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'lathato11', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'lathato12', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'lathato13', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'lathato14', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'lathato15', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'arukeresoid'];
        }

        return ['__isInitialized__', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'gtnev', 'm1lchanged', 'm2lchanged', 'm3lchanged', 'm4lchanged', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'id', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'created', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'lastmod', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'children', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'parent', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'nev', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'sorrend', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'slug', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'karkod', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'rovidleiras', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'leiras', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'leiras2', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'leiras3', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'menu1lathato', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'menu2lathato', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'menu3lathato', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'menu4lathato', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'oldalcim', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'seodescription', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'kepurl', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'kepleiras', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'termekek1', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'termekek2', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'termekek3', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'inaktiv', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'idegenkod', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'translations', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'emagid', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'locale', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'lathato', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'lathato2', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'lathato3', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'lathato4', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'lathato5', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'lathato6', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'lathato7', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'lathato8', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'lathato9', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'lathato10', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'lathato11', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'lathato12', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'lathato13', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'lathato14', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'lathato15', '' . "\0" . 'Entities\\TermekFa' . "\0" . 'arukeresoid'];
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (TermekFa $proxy) {
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
    public function __toString(): string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, '__toString', []);

        return parent::__toString();
    }

    /**
     * {@inheritDoc}
     */
    public function toA2a()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'toA2a', []);

        return parent::toA2a();
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
    public function getChildren()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getChildren', []);

        return parent::getChildren();
    }

    /**
     * {@inheritDoc}
     */
    public function addChild(\Entities\TermekFa $child)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addChild', [$child]);

        return parent::addChild($child);
    }

    /**
     * {@inheritDoc}
     */
    public function removeChild(\Entities\TermekFa $child)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeChild', [$child]);

        return parent::removeChild($child);
    }

    /**
     * {@inheritDoc}
     */
    public function getParent()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getParent', []);

        return parent::getParent();
    }

    /**
     * {@inheritDoc}
     */
    public function getParentId()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getParentId', []);

        return parent::getParentId();
    }

    /**
     * {@inheritDoc}
     */
    public function getParentNev()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getParentNev', []);

        return parent::getParentNev();
    }

    /**
     * {@inheritDoc}
     */
    public function setParent(\Entities\TermekFa $parent)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setParent', [$parent]);

        return parent::setParent($parent);
    }

    /**
     * {@inheritDoc}
     */
    public function removeParent()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeParent', []);

        return parent::removeParent();
    }

    /**
     * {@inheritDoc}
     */
    public function getTeljesNev($elval = '|', $selfname = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTeljesNev', [$elval, $selfname]);

        return parent::getTeljesNev($elval, $selfname);
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
    public function getSlug()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSlug', []);

        return parent::getSlug();
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
    public function getLeiras2()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLeiras2', []);

        return parent::getLeiras2();
    }

    /**
     * {@inheritDoc}
     */
    public function setLeiras2($leiras)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setLeiras2', [$leiras]);

        return parent::setLeiras2($leiras);
    }

    /**
     * {@inheritDoc}
     */
    public function getLeiras3()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLeiras3', []);

        return parent::getLeiras3();
    }

    /**
     * {@inheritDoc}
     */
    public function setLeiras3($leiras)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setLeiras3', [$leiras]);

        return parent::setLeiras3($leiras);
    }

    /**
     * {@inheritDoc}
     */
    public function getKarkod()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getKarkod', []);

        return parent::getKarkod();
    }

    /**
     * {@inheritDoc}
     */
    public function setKarkod($karkod)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setKarkod', [$karkod]);

        return parent::setKarkod($karkod);
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
    public function getShowOldalcim()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getShowOldalcim', []);

        return parent::getShowOldalcim();
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
    public function getSeodescription()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSeodescription', []);

        return parent::getSeodescription();
    }

    /**
     * {@inheritDoc}
     */
    public function getShowSeodescription()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getShowSeodescription', []);

        return parent::getShowSeodescription();
    }

    /**
     * {@inheritDoc}
     */
    public function setSeodescription($seodescription)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSeodescription', [$seodescription]);

        return parent::setSeodescription($seodescription);
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
    public function getKepurlMini($pre = '/')
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getKepurlMini', [$pre]);

        return parent::getKepurlMini($pre);
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
    public function getTermekek1()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTermekek1', []);

        return parent::getTermekek1();
    }

    /**
     * {@inheritDoc}
     */
    public function addTermek1(\Entities\Termek $termek)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addTermek1', [$termek]);

        return parent::addTermek1($termek);
    }

    /**
     * {@inheritDoc}
     */
    public function removeTermek1(\Entities\Termek $termek)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeTermek1', [$termek]);

        return parent::removeTermek1($termek);
    }

    /**
     * {@inheritDoc}
     */
    public function getTermekek2()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTermekek2', []);

        return parent::getTermekek2();
    }

    /**
     * {@inheritDoc}
     */
    public function addTermek2(\Entities\Termek $termek)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addTermek2', [$termek]);

        return parent::addTermek2($termek);
    }

    /**
     * {@inheritDoc}
     */
    public function removeTermek2(\Entities\Termek $termek)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeTermek2', [$termek]);

        return parent::removeTermek2($termek);
    }

    /**
     * {@inheritDoc}
     */
    public function getTermekek3()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTermekek3', []);

        return parent::getTermekek3();
    }

    /**
     * {@inheritDoc}
     */
    public function addTermek3(\Entities\Termek $termek)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addTermek3', [$termek]);

        return parent::addTermek3($termek);
    }

    /**
     * {@inheritDoc}
     */
    public function removeTermek3(\Entities\Termek $termek)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeTermek3', [$termek]);

        return parent::removeTermek3($termek);
    }

    /**
     * {@inheritDoc}
     */
    public function isDeletable()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'isDeletable', []);

        return parent::isDeletable();
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
    public function getRovidleiras()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRovidleiras', []);

        return parent::getRovidleiras();
    }

    /**
     * {@inheritDoc}
     */
    public function setRovidleiras($rovidleiras)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setRovidleiras', [$rovidleiras]);

        return parent::setRovidleiras($rovidleiras);
    }

    /**
     * {@inheritDoc}
     */
    public function getInaktiv()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getInaktiv', []);

        return parent::getInaktiv();
    }

    /**
     * {@inheritDoc}
     */
    public function setInaktiv($i)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setInaktiv', [$i]);

        return parent::setInaktiv($i);
    }

    /**
     * {@inheritDoc}
     */
    public function getIdegenkod()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getIdegenkod', []);

        return parent::getIdegenkod();
    }

    /**
     * {@inheritDoc}
     */
    public function setIdegenkod($idegenkod)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setIdegenkod', [$idegenkod]);

        return parent::setIdegenkod($idegenkod);
    }

    /**
     * {@inheritDoc}
     */
    public function setTranslatableLocale($l)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTranslatableLocale', [$l]);

        return parent::setTranslatableLocale($l);
    }

    /**
     * {@inheritDoc}
     */
    public function getTranslations()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTranslations', []);

        return parent::getTranslations();
    }

    /**
     * {@inheritDoc}
     */
    public function getTranslationsArray()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTranslationsArray', []);

        return parent::getTranslationsArray();
    }

    /**
     * {@inheritDoc}
     */
    public function addTranslation(\Entities\TermekFaTranslation $t)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addTranslation', [$t]);

        return parent::addTranslation($t);
    }

    /**
     * {@inheritDoc}
     */
    public function removeTranslation(\Entities\TermekFaTranslation $t)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeTranslation', [$t]);

        return parent::removeTranslation($t);
    }

    /**
     * {@inheritDoc}
     */
    public function getEmagid()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getEmagid', []);

        return parent::getEmagid();
    }

    /**
     * {@inheritDoc}
     */
    public function setEmagid($emagid)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setEmagid', [$emagid]);

        return parent::setEmagid($emagid);
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
    public function setLathato($lathato): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setLathato', [$lathato]);

        parent::setLathato($lathato);
    }

    /**
     * {@inheritDoc}
     */
    public function getLathato2()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLathato2', []);

        return parent::getLathato2();
    }

    /**
     * {@inheritDoc}
     */
    public function setLathato2($lathato2): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setLathato2', [$lathato2]);

        parent::setLathato2($lathato2);
    }

    /**
     * {@inheritDoc}
     */
    public function getLathato3()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLathato3', []);

        return parent::getLathato3();
    }

    /**
     * {@inheritDoc}
     */
    public function setLathato3($lathato3): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setLathato3', [$lathato3]);

        parent::setLathato3($lathato3);
    }

    /**
     * {@inheritDoc}
     */
    public function getLathato4()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLathato4', []);

        return parent::getLathato4();
    }

    /**
     * {@inheritDoc}
     */
    public function setLathato4($lathato4): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setLathato4', [$lathato4]);

        parent::setLathato4($lathato4);
    }

    /**
     * {@inheritDoc}
     */
    public function getLathato5()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLathato5', []);

        return parent::getLathato5();
    }

    /**
     * {@inheritDoc}
     */
    public function setLathato5($lathato5): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setLathato5', [$lathato5]);

        parent::setLathato5($lathato5);
    }

    /**
     * {@inheritDoc}
     */
    public function getLathato6()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLathato6', []);

        return parent::getLathato6();
    }

    /**
     * {@inheritDoc}
     */
    public function setLathato6($lathato6): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setLathato6', [$lathato6]);

        parent::setLathato6($lathato6);
    }

    /**
     * {@inheritDoc}
     */
    public function getLathato7()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLathato7', []);

        return parent::getLathato7();
    }

    /**
     * {@inheritDoc}
     */
    public function setLathato7($lathato7): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setLathato7', [$lathato7]);

        parent::setLathato7($lathato7);
    }

    /**
     * {@inheritDoc}
     */
    public function getLathato8()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLathato8', []);

        return parent::getLathato8();
    }

    /**
     * {@inheritDoc}
     */
    public function setLathato8($lathato8): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setLathato8', [$lathato8]);

        parent::setLathato8($lathato8);
    }

    /**
     * {@inheritDoc}
     */
    public function getLathato9()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLathato9', []);

        return parent::getLathato9();
    }

    /**
     * {@inheritDoc}
     */
    public function setLathato9($lathato9): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setLathato9', [$lathato9]);

        parent::setLathato9($lathato9);
    }

    /**
     * {@inheritDoc}
     */
    public function getLathato10()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLathato10', []);

        return parent::getLathato10();
    }

    /**
     * {@inheritDoc}
     */
    public function setLathato10($lathato10): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setLathato10', [$lathato10]);

        parent::setLathato10($lathato10);
    }

    /**
     * {@inheritDoc}
     */
    public function getLathato11()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLathato11', []);

        return parent::getLathato11();
    }

    /**
     * {@inheritDoc}
     */
    public function setLathato11($lathato11): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setLathato11', [$lathato11]);

        parent::setLathato11($lathato11);
    }

    /**
     * {@inheritDoc}
     */
    public function getLathato12()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLathato12', []);

        return parent::getLathato12();
    }

    /**
     * {@inheritDoc}
     */
    public function setLathato12($lathato12): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setLathato12', [$lathato12]);

        parent::setLathato12($lathato12);
    }

    /**
     * {@inheritDoc}
     */
    public function getLathato13()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLathato13', []);

        return parent::getLathato13();
    }

    /**
     * {@inheritDoc}
     */
    public function setLathato13($lathato13): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setLathato13', [$lathato13]);

        parent::setLathato13($lathato13);
    }

    /**
     * {@inheritDoc}
     */
    public function getLathato14()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLathato14', []);

        return parent::getLathato14();
    }

    /**
     * {@inheritDoc}
     */
    public function setLathato14($lathato14): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setLathato14', [$lathato14]);

        parent::setLathato14($lathato14);
    }

    /**
     * {@inheritDoc}
     */
    public function getLathato15()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLathato15', []);

        return parent::getLathato15();
    }

    /**
     * {@inheritDoc}
     */
    public function setLathato15($lathato15): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setLathato15', [$lathato15]);

        parent::setLathato15($lathato15);
    }

    /**
     * {@inheritDoc}
     */
    public function getXLathato()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getXLathato', []);

        return parent::getXLathato();
    }

    /**
     * {@inheritDoc}
     */
    public function getArukeresoid()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getArukeresoid', []);

        return parent::getArukeresoid();
    }

    /**
     * {@inheritDoc}
     */
    public function setArukeresoid($arukeresoid)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setArukeresoid', [$arukeresoid]);

        return parent::setArukeresoid($arukeresoid);
    }

}
