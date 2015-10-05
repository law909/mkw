<?php

namespace Proxies\__CG__\Entities;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class Uzletkoto extends \Entities\Uzletkoto implements \Doctrine\ORM\Proxy\Proxy
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
            return array('__isInitialized__', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'id', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'nev', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'irszam', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'varos', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'utca', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'telefon', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'mobil', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'fax', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'email', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'honlap', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'pw', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'megjegyzes', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'jutalek', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'partnerek', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'felhasznalo', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'bizonylatfejek', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'sessionid', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'partnervalutanem', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'partnerfizmod', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'partnertermekarazonosito', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'partnerbizonylatnyelv', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'partnerszallitasimod', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'partnerszamlatipus');
        }

        return array('__isInitialized__', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'id', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'nev', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'irszam', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'varos', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'utca', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'telefon', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'mobil', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'fax', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'email', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'honlap', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'pw', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'megjegyzes', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'jutalek', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'partnerek', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'felhasznalo', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'bizonylatfejek', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'sessionid', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'partnervalutanem', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'partnerfizmod', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'partnertermekarazonosito', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'partnerbizonylatnyelv', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'partnerszallitasimod', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'partnerszamlatipus');
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (Uzletkoto $proxy) {
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
    public function getCim()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCim', array());

        return parent::getCim();
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
    public function getNev()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNev', array());

        return parent::getNev();
    }

    /**
     * {@inheritDoc}
     */
    public function setNev($nev)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setNev', array($nev));

        return parent::setNev($nev);
    }

    /**
     * {@inheritDoc}
     */
    public function getIrszam()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getIrszam', array());

        return parent::getIrszam();
    }

    /**
     * {@inheritDoc}
     */
    public function setIrszam($irszam)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setIrszam', array($irszam));

        return parent::setIrszam($irszam);
    }

    /**
     * {@inheritDoc}
     */
    public function getVaros()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getVaros', array());

        return parent::getVaros();
    }

    /**
     * {@inheritDoc}
     */
    public function setVaros($varos)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setVaros', array($varos));

        return parent::setVaros($varos);
    }

    /**
     * {@inheritDoc}
     */
    public function getUtca()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getUtca', array());

        return parent::getUtca();
    }

    /**
     * {@inheritDoc}
     */
    public function setUtca($utca)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setUtca', array($utca));

        return parent::setUtca($utca);
    }

    /**
     * {@inheritDoc}
     */
    public function getTelefon()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTelefon', array());

        return parent::getTelefon();
    }

    /**
     * {@inheritDoc}
     */
    public function setTelefon($telefon)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTelefon', array($telefon));

        return parent::setTelefon($telefon);
    }

    /**
     * {@inheritDoc}
     */
    public function getMobil()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMobil', array());

        return parent::getMobil();
    }

    /**
     * {@inheritDoc}
     */
    public function setMobil($mobil)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMobil', array($mobil));

        return parent::setMobil($mobil);
    }

    /**
     * {@inheritDoc}
     */
    public function getFax()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getFax', array());

        return parent::getFax();
    }

    /**
     * {@inheritDoc}
     */
    public function setFax($fax)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setFax', array($fax));

        return parent::setFax($fax);
    }

    /**
     * {@inheritDoc}
     */
    public function getEmail()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getEmail', array());

        return parent::getEmail();
    }

    /**
     * {@inheritDoc}
     */
    public function setEmail($email)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setEmail', array($email));

        return parent::setEmail($email);
    }

    /**
     * {@inheritDoc}
     */
    public function getHonlap()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getHonlap', array());

        return parent::getHonlap();
    }

    /**
     * {@inheritDoc}
     */
    public function setHonlap($honlap)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setHonlap', array($honlap));

        return parent::setHonlap($honlap);
    }

    /**
     * {@inheritDoc}
     */
    public function getPw()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPw', array());

        return parent::getPw();
    }

    /**
     * {@inheritDoc}
     */
    public function setPw($pw)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPw', array($pw));

        return parent::setPw($pw);
    }

    /**
     * {@inheritDoc}
     */
    public function getPartnerek()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPartnerek', array());

        return parent::getPartnerek();
    }

    /**
     * {@inheritDoc}
     */
    public function addPartner(\Entities\Partner $partner)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addPartner', array($partner));

        return parent::addPartner($partner);
    }

    /**
     * {@inheritDoc}
     */
    public function removePartner(\Entities\Partner $partner)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removePartner', array($partner));

        return parent::removePartner($partner);
    }

    /**
     * {@inheritDoc}
     */
    public function setFelhasznalo(\Entities\Felhasznalo $felhasznalo)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setFelhasznalo', array($felhasznalo));

        return parent::setFelhasznalo($felhasznalo);
    }

    /**
     * {@inheritDoc}
     */
    public function removeFelhasznalo()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeFelhasznalo', array());

        return parent::removeFelhasznalo();
    }

    /**
     * {@inheritDoc}
     */
    public function getMegjegyzes()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMegjegyzes', array());

        return parent::getMegjegyzes();
    }

    /**
     * {@inheritDoc}
     */
    public function setMegjegyzes($megjegyzes)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMegjegyzes', array($megjegyzes));

        return parent::setMegjegyzes($megjegyzes);
    }

    /**
     * {@inheritDoc}
     */
    public function getJutalek()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getJutalek', array());

        return parent::getJutalek();
    }

    /**
     * {@inheritDoc}
     */
    public function setJutalek($jutalek)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setJutalek', array($jutalek));

        return parent::setJutalek($jutalek);
    }

    /**
     * {@inheritDoc}
     */
    public function getSessionid()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSessionid', array());

        return parent::getSessionid();
    }

    /**
     * {@inheritDoc}
     */
    public function setSessionid($sessionid)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSessionid', array($sessionid));

        return parent::setSessionid($sessionid);
    }

    /**
     * {@inheritDoc}
     */
    public function getPartnerszamlatipus()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPartnerszamlatipus', array());

        return parent::getPartnerszamlatipus();
    }

    /**
     * {@inheritDoc}
     */
    public function setPartnerszamlatipus($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPartnerszamlatipus', array($val));

        return parent::setPartnerszamlatipus($val);
    }

    /**
     * {@inheritDoc}
     */
    public function getPartnervalutanem()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPartnervalutanem', array());

        return parent::getPartnervalutanem();
    }

    /**
     * {@inheritDoc}
     */
    public function getPartnervalutanemnev()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPartnervalutanemnev', array());

        return parent::getPartnervalutanemnev();
    }

    /**
     * {@inheritDoc}
     */
    public function getPartnervalutanemId()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPartnervalutanemId', array());

        return parent::getPartnervalutanemId();
    }

    /**
     * {@inheritDoc}
     */
    public function setPartnervalutanem($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPartnervalutanem', array($val));

        return parent::setPartnervalutanem($val);
    }

    /**
     * {@inheritDoc}
     */
    public function removePartnervalutanem()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removePartnervalutanem', array());

        return parent::removePartnervalutanem();
    }

    /**
     * {@inheritDoc}
     */
    public function getPartnertermekarazonosito()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPartnertermekarazonosito', array());

        return parent::getPartnertermekarazonosito();
    }

    /**
     * {@inheritDoc}
     */
    public function setPartnertermekarazonosito($v)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPartnertermekarazonosito', array($v));

        return parent::setPartnertermekarazonosito($v);
    }

    /**
     * {@inheritDoc}
     */
    public function getPartnerszallitasimod()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPartnerszallitasimod', array());

        return parent::getPartnerszallitasimod();
    }

    /**
     * {@inheritDoc}
     */
    public function getPartnerszallitasimodNev()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPartnerszallitasimodNev', array());

        return parent::getPartnerszallitasimodNev();
    }

    /**
     * {@inheritDoc}
     */
    public function getPartnerszallitasimodId()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPartnerszallitasimodId', array());

        return parent::getPartnerszallitasimodId();
    }

    /**
     * {@inheritDoc}
     */
    public function setPartnerszallitasimod($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPartnerszallitasimod', array($val));

        return parent::setPartnerszallitasimod($val);
    }

    /**
     * {@inheritDoc}
     */
    public function removePartnerszallitasimod()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removePartnerszallitasimod', array());

        return parent::removePartnerszallitasimod();
    }

    /**
     * {@inheritDoc}
     */
    public function getPartnerbizonylatnyelv()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPartnerbizonylatnyelv', array());

        return parent::getPartnerbizonylatnyelv();
    }

    /**
     * {@inheritDoc}
     */
    public function setPartnerbizonylatnyelv($adat)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPartnerbizonylatnyelv', array($adat));

        return parent::setPartnerbizonylatnyelv($adat);
    }

    /**
     * {@inheritDoc}
     */
    public function getPartnerfizmod()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPartnerfizmod', array());

        return parent::getPartnerfizmod();
    }

    /**
     * {@inheritDoc}
     */
    public function getPartnerfizmodNev()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPartnerfizmodNev', array());

        return parent::getPartnerfizmodNev();
    }

    /**
     * {@inheritDoc}
     */
    public function getPartnerfizmodId()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPartnerfizmodId', array());

        return parent::getPartnerfizmodId();
    }

    /**
     * {@inheritDoc}
     */
    public function setPartnerfizmod(\Entities\Fizmod $fizmod)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPartnerfizmod', array($fizmod));

        return parent::setPartnerfizmod($fizmod);
    }

}
