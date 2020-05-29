<?php

namespace Proxies\__CG__\Entities;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class Blogposzt extends \Entities\Blogposzt implements \Doctrine\ORM\Proxy\Proxy
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
            return ['__isInitialized__', '' . "\0" . 'Entities\\Blogposzt' . "\0" . 'id', '' . "\0" . 'Entities\\Blogposzt' . "\0" . 'created', '' . "\0" . 'Entities\\Blogposzt' . "\0" . 'lastmod', '' . "\0" . 'Entities\\Blogposzt' . "\0" . 'createdby', '' . "\0" . 'Entities\\Blogposzt' . "\0" . 'updatedby', '' . "\0" . 'Entities\\Blogposzt' . "\0" . 'cim', '' . "\0" . 'Entities\\Blogposzt' . "\0" . 'slug', '' . "\0" . 'Entities\\Blogposzt' . "\0" . 'kivonat', '' . "\0" . 'Entities\\Blogposzt' . "\0" . 'szoveg', '' . "\0" . 'Entities\\Blogposzt' . "\0" . 'termekfa1', '' . "\0" . 'Entities\\Blogposzt' . "\0" . 'termekfa1karkod', '' . "\0" . 'Entities\\Blogposzt' . "\0" . 'termekfa2', '' . "\0" . 'Entities\\Blogposzt' . "\0" . 'termekfa2karkod', '' . "\0" . 'Entities\\Blogposzt' . "\0" . 'termekfa3', '' . "\0" . 'Entities\\Blogposzt' . "\0" . 'termekfa3karkod', '' . "\0" . 'Entities\\Blogposzt' . "\0" . 'kepurl', '' . "\0" . 'Entities\\Blogposzt' . "\0" . 'kepleiras', '' . "\0" . 'Entities\\Blogposzt' . "\0" . 'megjelenesdatum', '' . "\0" . 'Entities\\Blogposzt' . "\0" . 'lathato', '' . "\0" . 'Entities\\Blogposzt' . "\0" . 'seodescription', '' . "\0" . 'Entities\\Blogposzt' . "\0" . 'termekek'];
        }

        return ['__isInitialized__', '' . "\0" . 'Entities\\Blogposzt' . "\0" . 'id', '' . "\0" . 'Entities\\Blogposzt' . "\0" . 'created', '' . "\0" . 'Entities\\Blogposzt' . "\0" . 'lastmod', '' . "\0" . 'Entities\\Blogposzt' . "\0" . 'createdby', '' . "\0" . 'Entities\\Blogposzt' . "\0" . 'updatedby', '' . "\0" . 'Entities\\Blogposzt' . "\0" . 'cim', '' . "\0" . 'Entities\\Blogposzt' . "\0" . 'slug', '' . "\0" . 'Entities\\Blogposzt' . "\0" . 'kivonat', '' . "\0" . 'Entities\\Blogposzt' . "\0" . 'szoveg', '' . "\0" . 'Entities\\Blogposzt' . "\0" . 'termekfa1', '' . "\0" . 'Entities\\Blogposzt' . "\0" . 'termekfa1karkod', '' . "\0" . 'Entities\\Blogposzt' . "\0" . 'termekfa2', '' . "\0" . 'Entities\\Blogposzt' . "\0" . 'termekfa2karkod', '' . "\0" . 'Entities\\Blogposzt' . "\0" . 'termekfa3', '' . "\0" . 'Entities\\Blogposzt' . "\0" . 'termekfa3karkod', '' . "\0" . 'Entities\\Blogposzt' . "\0" . 'kepurl', '' . "\0" . 'Entities\\Blogposzt' . "\0" . 'kepleiras', '' . "\0" . 'Entities\\Blogposzt' . "\0" . 'megjelenesdatum', '' . "\0" . 'Entities\\Blogposzt' . "\0" . 'lathato', '' . "\0" . 'Entities\\Blogposzt' . "\0" . 'seodescription', '' . "\0" . 'Entities\\Blogposzt' . "\0" . 'termekek'];
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (Blogposzt $proxy) {
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
    public function convertToArray()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'convertToArray', []);

        return parent::convertToArray();
    }

    /**
     * {@inheritDoc}
     */
    public function getLink()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLink', []);

        return parent::getLink();
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
    public function getMegjelenesdatum()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMegjelenesdatum', []);

        return parent::getMegjelenesdatum();
    }

    /**
     * {@inheritDoc}
     */
    public function getMegjelenesdatumStr()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMegjelenesdatumStr', []);

        return parent::getMegjelenesdatumStr();
    }

    /**
     * {@inheritDoc}
     */
    public function setMegjelenesdatum($adat = '')
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMegjelenesdatum', [$adat]);

        return parent::setMegjelenesdatum($adat);
    }

    /**
     * {@inheritDoc}
     */
    public function getTermekfa1()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTermekfa1', []);

        return parent::getTermekfa1();
    }

    /**
     * {@inheritDoc}
     */
    public function getTermekfa1Nev()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTermekfa1Nev', []);

        return parent::getTermekfa1Nev();
    }

    /**
     * {@inheritDoc}
     */
    public function getTermekfa1Id()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTermekfa1Id', []);

        return parent::getTermekfa1Id();
    }

    /**
     * {@inheritDoc}
     */
    public function setTermekfa1($termekfa)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTermekfa1', [$termekfa]);

        return parent::setTermekfa1($termekfa);
    }

    /**
     * {@inheritDoc}
     */
    public function getTermekfa2()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTermekfa2', []);

        return parent::getTermekfa2();
    }

    /**
     * {@inheritDoc}
     */
    public function getTermekfa2Nev()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTermekfa2Nev', []);

        return parent::getTermekfa2Nev();
    }

    /**
     * {@inheritDoc}
     */
    public function getTermekfa2Id()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTermekfa2Id', []);

        return parent::getTermekfa2Id();
    }

    /**
     * {@inheritDoc}
     */
    public function setTermekfa2($termekfa)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTermekfa2', [$termekfa]);

        return parent::setTermekfa2($termekfa);
    }

    /**
     * {@inheritDoc}
     */
    public function getTermekfa3()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTermekfa3', []);

        return parent::getTermekfa3();
    }

    /**
     * {@inheritDoc}
     */
    public function getTermekfa3Nev()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTermekfa3Nev', []);

        return parent::getTermekfa3Nev();
    }

    /**
     * {@inheritDoc}
     */
    public function getTermekfa3Id()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTermekfa3Id', []);

        return parent::getTermekfa3Id();
    }

    /**
     * {@inheritDoc}
     */
    public function setTermekfa3($termekfa)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTermekfa3', [$termekfa]);

        return parent::setTermekfa3($termekfa);
    }

    /**
     * {@inheritDoc}
     */
    public function getShowCim()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getShowCim', []);

        return parent::getShowCim();
    }

    /**
     * {@inheritDoc}
     */
    public function getCim()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCim', []);

        return parent::getCim();
    }

    /**
     * {@inheritDoc}
     */
    public function setCim($cim)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCim', [$cim]);

        return parent::setCim($cim);
    }

    /**
     * {@inheritDoc}
     */
    public function getKivonat()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getKivonat', []);

        return parent::getKivonat();
    }

    /**
     * {@inheritDoc}
     */
    public function setKivonat($kivonat)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setKivonat', [$kivonat]);

        return parent::setKivonat($kivonat);
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
    public function setSzoveg($szoveg)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSzoveg', [$szoveg]);

        return parent::setSzoveg($szoveg);
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
    public function getSeodescription()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSeodescription', []);

        return parent::getSeodescription();
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
    public function getTermekek()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTermekek', []);

        return parent::getTermekek();
    }

    /**
     * {@inheritDoc}
     */
    public function addTermek(\Entities\Termek $termek)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addTermek', [$termek]);

        return parent::addTermek($termek);
    }

    /**
     * {@inheritDoc}
     */
    public function removeTermek(\Entities\Termek $termek)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeTermek', [$termek]);

        return parent::removeTermek($termek);
    }

}
