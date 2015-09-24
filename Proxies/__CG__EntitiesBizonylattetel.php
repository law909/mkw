<?php

namespace Proxies\__CG__\Entities;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class Bizonylattetel extends \Entities\Bizonylattetel implements \Doctrine\ORM\Proxy\Proxy
{
    /**
     * @var \Closure the callback responsible for loading properties in the proxy object. This callback is called with
     *      three parameters, being respectively the proxy object to be initialized, the method that triggered the
     *      initialization process and an array of ordered parameters that were passed to that method.
     *
     * @see \Doctrine\Common\Persistence\Proxy::__setInitializer
     */
    public $__initializer__;

    /**
     * @var \Closure the callback responsible of loading properties that need to be copied in the cloned object
     *
     * @see \Doctrine\Common\Persistence\Proxy::__setCloner
     */
    public $__cloner__;

    /**
     * @var boolean flag indicating if this object was already initialized
     *
     * @see \Doctrine\Common\Persistence\Proxy::__isInitialized
     */
    public $__isInitialized__ = false;

    /**
     * @var array properties to be lazy loaded, with keys being the property
     *            names and values being their default values
     *
     * @see \Doctrine\Common\Persistence\Proxy::__getLazyProperties
     */
    public static $lazyPropertiesDefaults = array();



    /**
     * @param \Closure $initializer
     * @param \Closure $cloner
     */
    public function __construct($initializer = null, $cloner = null)
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
            return array('__isInitialized__', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'duplication', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'id', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'created', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'lastmod', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'bizonylatfej', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'mozgat', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'foglal', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'irany', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'arvaltoztat', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'storno', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'stornozott', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'rontott', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'termek', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'termeknev', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'me', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'kiszereles', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'cikkszam', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'idegencikkszam', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'ehparany', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'hparany', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'szelesseg', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'magassag', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'hosszusag', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'suly', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'osszehajthato', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'vtsz', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'vtsznev', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'vtszszam', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'afa', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'afanev', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'afakulcs', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'gymennyiseg', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'mennyiseg', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'nettoegysar', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'bruttoegysar', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'nettoegysarhuf', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'bruttoegysarhuf', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'enettoegysar', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'ebruttoegysar', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'enettoegysarhuf', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'ebruttoegysarhuf', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'netto', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'afaertek', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'brutto', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'valutanem', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'valutanemnev', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'nettohuf', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'afaertekhuf', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'bruttohuf', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'arfolyam', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'parbizonylattetel', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'szulobizonylattetelek', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'hatarido', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'termekvaltozat', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'translations', 'locale');
        }

        return array('__isInitialized__', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'duplication', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'id', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'created', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'lastmod', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'bizonylatfej', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'mozgat', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'foglal', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'irany', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'arvaltoztat', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'storno', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'stornozott', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'rontott', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'termek', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'termeknev', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'me', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'kiszereles', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'cikkszam', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'idegencikkszam', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'ehparany', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'hparany', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'szelesseg', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'magassag', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'hosszusag', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'suly', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'osszehajthato', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'vtsz', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'vtsznev', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'vtszszam', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'afa', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'afanev', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'afakulcs', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'gymennyiseg', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'mennyiseg', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'nettoegysar', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'bruttoegysar', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'nettoegysarhuf', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'bruttoegysarhuf', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'enettoegysar', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'ebruttoegysar', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'enettoegysarhuf', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'ebruttoegysarhuf', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'netto', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'afaertek', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'brutto', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'valutanem', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'valutanemnev', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'nettohuf', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'afaertekhuf', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'bruttohuf', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'arfolyam', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'parbizonylattetel', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'szulobizonylattetelek', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'hatarido', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'termekvaltozat', '' . "\0" . 'Entities\\Bizonylattetel' . "\0" . 'translations', 'locale');
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (Bizonylattetel $proxy) {
                $proxy->__setInitializer(null);
                $proxy->__setCloner(null);

                $existingProperties = get_object_vars($proxy);

                foreach ($proxy->__getLazyProperties() as $property => $defaultValue) {
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
        $this->__cloner__ && $this->__cloner__->__invoke($this, '__clone', array());
    }

    /**
     * Forces initialization of the proxy
     */
    public function __load()
    {
        $this->__initializer__ && $this->__initializer__->__invoke($this, '__load', array());
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

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'toLista', array());

        return parent::toLista();
    }

    /**
     * {@inheritDoc}
     */
    public function setPersistentData()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPersistentData', array());

        return parent::setPersistentData();
    }

    /**
     * {@inheritDoc}
     */
    public function calc()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'calc', array());

        return parent::calc();
    }

    /**
     * {@inheritDoc}
     */
    public function getId()
    {
        if ($this->__isInitialized__ === false) {
            return (int)  parent::getId();
        }


        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getId', array());

        return parent::getId();
    }

    /**
     * {@inheritDoc}
     */
    public function getBizonylatfej()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getBizonylatfej', array());

        return parent::getBizonylatfej();
    }

    /**
     * {@inheritDoc}
     */
    public function getBizonylatfejId()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getBizonylatfejId', array());

        return parent::getBizonylatfejId();
    }

    /**
     * {@inheritDoc}
     */
    public function getTeljesites()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTeljesites', array());

        return parent::getTeljesites();
    }

    /**
     * {@inheritDoc}
     */
    public function getRaktar()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRaktar', array());

        return parent::getRaktar();
    }

    /**
     * {@inheritDoc}
     */
    public function getRaktarId()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRaktarId', array());

        return parent::getRaktarId();
    }

    /**
     * {@inheritDoc}
     */
    public function setBizonylatfej($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setBizonylatfej', array($val));

        return parent::setBizonylatfej($val);
    }

    /**
     * {@inheritDoc}
     */
    public function removeBizonylatfej()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeBizonylatfej', array());

        return parent::removeBizonylatfej();
    }

    /**
     * {@inheritDoc}
     */
    public function getMozgat()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMozgat', array());

        return parent::getMozgat();
    }

    /**
     * {@inheritDoc}
     */
    public function setMozgat()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMozgat', array());

        return parent::setMozgat();
    }

    /**
     * {@inheritDoc}
     */
    public function getFoglal()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getFoglal', array());

        return parent::getFoglal();
    }

    /**
     * {@inheritDoc}
     */
    public function setFoglal()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setFoglal', array());

        return parent::setFoglal();
    }

    /**
     * {@inheritDoc}
     */
    public function getArvaltoztat()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getArvaltoztat', array());

        return parent::getArvaltoztat();
    }

    /**
     * {@inheritDoc}
     */
    public function setArvaltoztat($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setArvaltoztat', array($val));

        return parent::setArvaltoztat($val);
    }

    /**
     * {@inheritDoc}
     */
    public function getStorno()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getStorno', array());

        return parent::getStorno();
    }

    /**
     * {@inheritDoc}
     */
    public function setStorno($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setStorno', array($val));

        return parent::setStorno($val);
    }

    /**
     * {@inheritDoc}
     */
    public function getStornozott()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getStornozott', array());

        return parent::getStornozott();
    }

    /**
     * {@inheritDoc}
     */
    public function setStornozott($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setStornozott', array($val));

        return parent::setStornozott($val);
    }

    /**
     * {@inheritDoc}
     */
    public function getHosszusag()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getHosszusag', array());

        return parent::getHosszusag();
    }

    /**
     * {@inheritDoc}
     */
    public function setHosszusag($adat)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setHosszusag', array($adat));

        return parent::setHosszusag($adat);
    }

    /**
     * {@inheritDoc}
     */
    public function getEhparany()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getEhparany', array());

        return parent::getEhparany();
    }

    /**
     * {@inheritDoc}
     */
    public function setEhparany($adat)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setEhparany', array($adat));

        return parent::setEhparany($adat);
    }

    /**
     * {@inheritDoc}
     */
    public function getKiszereles()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getKiszereles', array());

        return parent::getKiszereles();
    }

    /**
     * {@inheritDoc}
     */
    public function setKiszereles($adat)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setKiszereles', array($adat));

        return parent::setKiszereles($adat);
    }

    /**
     * {@inheritDoc}
     */
    public function getMagassag()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMagassag', array());

        return parent::getMagassag();
    }

    /**
     * {@inheritDoc}
     */
    public function setMagassag($adat)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMagassag', array($adat));

        return parent::setMagassag($adat);
    }

    /**
     * {@inheritDoc}
     */
    public function getOsszehajthato()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getOsszehajthato', array());

        return parent::getOsszehajthato();
    }

    /**
     * {@inheritDoc}
     */
    public function setOsszehajthato($adat)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setOsszehajthato', array($adat));

        return parent::setOsszehajthato($adat);
    }

    /**
     * {@inheritDoc}
     */
    public function getSuly()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSuly', array());

        return parent::getSuly();
    }

    /**
     * {@inheritDoc}
     */
    public function setSuly($adat)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSuly', array($adat));

        return parent::setSuly($adat);
    }

    /**
     * {@inheritDoc}
     */
    public function getSzelesseg()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSzelesseg', array());

        return parent::getSzelesseg();
    }

    /**
     * {@inheritDoc}
     */
    public function setSzelesseg($adat)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSzelesseg', array($adat));

        return parent::setSzelesseg($adat);
    }

    /**
     * {@inheritDoc}
     */
    public function getTermek()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTermek', array());

        return parent::getTermek();
    }

    /**
     * {@inheritDoc}
     */
    public function getTermekId()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTermekId', array());

        return parent::getTermekId();
    }

    /**
     * {@inheritDoc}
     */
    public function setTermek($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTermek', array($val));

        return parent::setTermek($val);
    }

    /**
     * {@inheritDoc}
     */
    public function removeTermek()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeTermek', array());

        return parent::removeTermek();
    }

    /**
     * {@inheritDoc}
     */
    public function getTermeknev()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTermeknev', array());

        return parent::getTermeknev();
    }

    /**
     * {@inheritDoc}
     */
    public function setTermeknev($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTermeknev', array($val));

        return parent::setTermeknev($val);
    }

    /**
     * {@inheritDoc}
     */
    public function getCikkszam()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCikkszam', array());

        return parent::getCikkszam();
    }

    /**
     * {@inheritDoc}
     */
    public function setCikkszam($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCikkszam', array($val));

        return parent::setCikkszam($val);
    }

    /**
     * {@inheritDoc}
     */
    public function getIdegencikkszam()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getIdegencikkszam', array());

        return parent::getIdegencikkszam();
    }

    /**
     * {@inheritDoc}
     */
    public function setIdegencikkszam($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setIdegencikkszam', array($val));

        return parent::setIdegencikkszam($val);
    }

    /**
     * {@inheritDoc}
     */
    public function getME()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getME', array());

        return parent::getME();
    }

    /**
     * {@inheritDoc}
     */
    public function setME($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setME', array($val));

        return parent::setME($val);
    }

    /**
     * {@inheritDoc}
     */
    public function getVtsz()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getVtsz', array());

        return parent::getVtsz();
    }

    /**
     * {@inheritDoc}
     */
    public function getVtszszam()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getVtszszam', array());

        return parent::getVtszszam();
    }

    /**
     * {@inheritDoc}
     */
    public function getVtsznev()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getVtsznev', array());

        return parent::getVtsznev();
    }

    /**
     * {@inheritDoc}
     */
    public function getVtszId()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getVtszId', array());

        return parent::getVtszId();
    }

    /**
     * {@inheritDoc}
     */
    public function setVtsz($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setVtsz', array($val));

        return parent::setVtsz($val);
    }

    /**
     * {@inheritDoc}
     */
    public function removeVtsz()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeVtsz', array());

        return parent::removeVtsz();
    }

    /**
     * {@inheritDoc}
     */
    public function getAfa()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getAfa', array());

        return parent::getAfa();
    }

    /**
     * {@inheritDoc}
     */
    public function getAfanev()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getAfanev', array());

        return parent::getAfanev();
    }

    /**
     * {@inheritDoc}
     */
    public function getAfakulcs()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getAfakulcs', array());

        return parent::getAfakulcs();
    }

    /**
     * {@inheritDoc}
     */
    public function getAfaId()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getAfaId', array());

        return parent::getAfaId();
    }

    /**
     * {@inheritDoc}
     */
    public function setAfa($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setAfa', array($val));

        return parent::setAfa($val);
    }

    /**
     * {@inheritDoc}
     */
    public function removeAfa()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeAfa', array());

        return parent::removeAfa();
    }

    /**
     * {@inheritDoc}
     */
    public function getGymennyiseg()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getGymennyiseg', array());

        return parent::getGymennyiseg();
    }

    /**
     * {@inheritDoc}
     */
    public function setGymennyiseg($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setGymennyiseg', array($val));

        return parent::setGymennyiseg($val);
    }

    /**
     * {@inheritDoc}
     */
    public function getMennyiseg()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMennyiseg', array());

        return parent::getMennyiseg();
    }

    /**
     * {@inheritDoc}
     */
    public function setMennyiseg($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMennyiseg', array($val));

        return parent::setMennyiseg($val);
    }

    /**
     * {@inheritDoc}
     */
    public function getNettoegysar()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNettoegysar', array());

        return parent::getNettoegysar();
    }

    /**
     * {@inheritDoc}
     */
    public function setNettoegysar($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setNettoegysar', array($val));

        return parent::setNettoegysar($val);
    }

    /**
     * {@inheritDoc}
     */
    public function getBruttoegysar()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getBruttoegysar', array());

        return parent::getBruttoegysar();
    }

    /**
     * {@inheritDoc}
     */
    public function setBruttoegysar($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setBruttoegysar', array($val));

        return parent::setBruttoegysar($val);
    }

    /**
     * {@inheritDoc}
     */
    public function getNettoegysarhuf()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNettoegysarhuf', array());

        return parent::getNettoegysarhuf();
    }

    /**
     * {@inheritDoc}
     */
    public function setNettoegysarhuf($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setNettoegysarhuf', array($val));

        return parent::setNettoegysarhuf($val);
    }

    /**
     * {@inheritDoc}
     */
    public function getBruttoegysarhuf()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getBruttoegysarhuf', array());

        return parent::getBruttoegysarhuf();
    }

    /**
     * {@inheritDoc}
     */
    public function setBruttoegysarhuf($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setBruttoegysarhuf', array($val));

        return parent::setBruttoegysarhuf($val);
    }

    /**
     * {@inheritDoc}
     */
    public function getEnettoegysar()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getEnettoegysar', array());

        return parent::getEnettoegysar();
    }

    /**
     * {@inheritDoc}
     */
    public function getEbruttoegysar()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getEbruttoegysar', array());

        return parent::getEbruttoegysar();
    }

    /**
     * {@inheritDoc}
     */
    public function getEnettoegysarhuf()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getEnettoegysarhuf', array());

        return parent::getEnettoegysarhuf();
    }

    /**
     * {@inheritDoc}
     */
    public function getEbruttoegysarhuf()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getEbruttoegysarhuf', array());

        return parent::getEbruttoegysarhuf();
    }

    /**
     * {@inheritDoc}
     */
    public function getNetto()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNetto', array());

        return parent::getNetto();
    }

    /**
     * {@inheritDoc}
     */
    public function setNetto($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setNetto', array($val));

        return parent::setNetto($val);
    }

    /**
     * {@inheritDoc}
     */
    public function getAfaertek()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getAfaertek', array());

        return parent::getAfaertek();
    }

    /**
     * {@inheritDoc}
     */
    public function setAfaertek($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setAfaertek', array($val));

        return parent::setAfaertek($val);
    }

    /**
     * {@inheritDoc}
     */
    public function getBrutto()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getBrutto', array());

        return parent::getBrutto();
    }

    /**
     * {@inheritDoc}
     */
    public function setBrutto($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setBrutto', array($val));

        return parent::setBrutto($val);
    }

    /**
     * {@inheritDoc}
     */
    public function getValutanem()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getValutanem', array());

        return parent::getValutanem();
    }

    /**
     * {@inheritDoc}
     */
    public function getValutanemnev()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getValutanemnev', array());

        return parent::getValutanemnev();
    }

    /**
     * {@inheritDoc}
     */
    public function getValutanemId()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getValutanemId', array());

        return parent::getValutanemId();
    }

    /**
     * {@inheritDoc}
     */
    public function setValutanem($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setValutanem', array($val));

        return parent::setValutanem($val);
    }

    /**
     * {@inheritDoc}
     */
    public function removeValutanem()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeValutanem', array());

        return parent::removeValutanem();
    }

    /**
     * {@inheritDoc}
     */
    public function getNettohuf()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNettohuf', array());

        return parent::getNettohuf();
    }

    /**
     * {@inheritDoc}
     */
    public function setNettohuf($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setNettohuf', array($val));

        return parent::setNettohuf($val);
    }

    /**
     * {@inheritDoc}
     */
    public function getAfaertekhuf()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getAfaertekhuf', array());

        return parent::getAfaertekhuf();
    }

    /**
     * {@inheritDoc}
     */
    public function setAfaertekhuf($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setAfaertekhuf', array($val));

        return parent::setAfaertekhuf($val);
    }

    /**
     * {@inheritDoc}
     */
    public function getBruttohuf()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getBruttohuf', array());

        return parent::getBruttohuf();
    }

    /**
     * {@inheritDoc}
     */
    public function setBruttohuf($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setBruttohuf', array($val));

        return parent::setBruttohuf($val);
    }

    /**
     * {@inheritDoc}
     */
    public function getArfolyam()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getArfolyam', array());

        return parent::getArfolyam();
    }

    /**
     * {@inheritDoc}
     */
    public function setArfolyam($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setArfolyam', array($val));

        return parent::setArfolyam($val);
    }

    /**
     * {@inheritDoc}
     */
    public function getParbizonylattetel()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getParbizonylattetel', array());

        return parent::getParbizonylattetel();
    }

    /**
     * {@inheritDoc}
     */
    public function getParbizonylattetelId()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getParbizonylattetelId', array());

        return parent::getParbizonylattetelId();
    }

    /**
     * {@inheritDoc}
     */
    public function setParbizonylattetel($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setParbizonylattetel', array($val));

        return parent::setParbizonylattetel($val);
    }

    /**
     * {@inheritDoc}
     */
    public function removeParbizonylattetel()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeParbizonylattetel', array());

        return parent::removeParbizonylattetel();
    }

    /**
     * {@inheritDoc}
     */
    public function getSzulobizonylattetelek()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSzulobizonylattetelek', array());

        return parent::getSzulobizonylattetelek();
    }

    /**
     * {@inheritDoc}
     */
    public function addSzulobizonylattetel($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addSzulobizonylattetel', array($val));

        return parent::addSzulobizonylattetel($val);
    }

    /**
     * {@inheritDoc}
     */
    public function removeSzulobizonylattetel($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeSzulobizonylattetel', array($val));

        return parent::removeSzulobizonylattetel($val);
    }

    /**
     * {@inheritDoc}
     */
    public function getHatarido()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getHatarido', array());

        return parent::getHatarido();
    }

    /**
     * {@inheritDoc}
     */
    public function getHataridoStr()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getHataridoStr', array());

        return parent::getHataridoStr();
    }

    /**
     * {@inheritDoc}
     */
    public function setHatarido($adat)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setHatarido', array($adat));

        return parent::setHatarido($adat);
    }

    /**
     * {@inheritDoc}
     */
    public function getLastmod()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLastmod', array());

        return parent::getLastmod();
    }

    /**
     * {@inheritDoc}
     */
    public function clearLastmod()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'clearLastmod', array());

        return parent::clearLastmod();
    }

    /**
     * {@inheritDoc}
     */
    public function getCreated()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCreated', array());

        return parent::getCreated();
    }

    /**
     * {@inheritDoc}
     */
    public function clearCreated()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'clearCreated', array());

        return parent::clearCreated();
    }

    /**
     * {@inheritDoc}
     */
    public function getTermekvaltozat()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTermekvaltozat', array());

        return parent::getTermekvaltozat();
    }

    /**
     * {@inheritDoc}
     */
    public function getTermekvaltozatId()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTermekvaltozatId', array());

        return parent::getTermekvaltozatId();
    }

    /**
     * {@inheritDoc}
     */
    public function setTermekvaltozat($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTermekvaltozat', array($val));

        return parent::setTermekvaltozat($val);
    }

    /**
     * {@inheritDoc}
     */
    public function removeTermekvaltozat()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeTermekvaltozat', array());

        return parent::removeTermekvaltozat();
    }

    /**
     * {@inheritDoc}
     */
    public function getIrany()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getIrany', array());

        return parent::getIrany();
    }

    /**
     * {@inheritDoc}
     */
    public function setIrany($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setIrany', array($val));

        return parent::setIrany($val);
    }

    /**
     * {@inheritDoc}
     */
    public function getRontott()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRontott', array());

        return parent::getRontott();
    }

    /**
     * {@inheritDoc}
     */
    public function setRontott($adat)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setRontott', array($adat));

        return parent::setRontott($adat);
    }

    /**
     * {@inheritDoc}
     */
    public function getTranslations()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTranslations', array());

        return parent::getTranslations();
    }

    /**
     * {@inheritDoc}
     */
    public function addTranslation(\Entities\BizonylattetelTranslation $t)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addTranslation', array($t));

        return parent::addTranslation($t);
    }

    /**
     * {@inheritDoc}
     */
    public function removeTranslation(\Entities\BizonylattetelTranslation $t)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeTranslation', array($t));

        return parent::removeTranslation($t);
    }

    /**
     * {@inheritDoc}
     */
    public function getLocale()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLocale', array());

        return parent::getLocale();
    }

    /**
     * {@inheritDoc}
     */
    public function setLocale($locale)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setLocale', array($locale));

        return parent::setLocale($locale);
    }

    /**
     * {@inheritDoc}
     */
    public function duplicate($entityB)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'duplicate', array($entityB));

        return parent::duplicate($entityB);
    }

}
