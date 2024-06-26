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
            return ['__isInitialized__', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'id', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'nev', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'irszam', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'varos', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'utca', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'telefon', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'mobil', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'fax', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'email', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'honlap', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'pw', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'megjegyzes', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'jutalek', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'partnerek', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'felhasznalo', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'bizonylatfejek', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'belsobizonylatfejek', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'sessionid', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'partnervalutanem', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'partnerfizmod', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'partnerbizonylatnyelv', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'partnerszallitasimod', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'partnerszamlatipus', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'belso', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'fo', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'fouzletkoto', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'uzletkotok', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'arsav', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'partnertermekarazonosito'];
        }

        return ['__isInitialized__', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'id', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'nev', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'irszam', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'varos', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'utca', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'telefon', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'mobil', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'fax', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'email', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'honlap', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'pw', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'megjegyzes', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'jutalek', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'partnerek', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'felhasznalo', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'bizonylatfejek', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'belsobizonylatfejek', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'sessionid', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'partnervalutanem', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'partnerfizmod', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'partnerbizonylatnyelv', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'partnerszallitasimod', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'partnerszamlatipus', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'belso', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'fo', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'fouzletkoto', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'uzletkotok', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'arsav', '' . "\0" . 'Entities\\Uzletkoto' . "\0" . 'partnertermekarazonosito'];
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
    public function getCim()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCim', []);

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


        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getId', []);

        return parent::getId();
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
    public function getIrszam()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getIrszam', []);

        return parent::getIrszam();
    }

    /**
     * {@inheritDoc}
     */
    public function setIrszam($irszam)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setIrszam', [$irszam]);

        return parent::setIrszam($irszam);
    }

    /**
     * {@inheritDoc}
     */
    public function getVaros()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getVaros', []);

        return parent::getVaros();
    }

    /**
     * {@inheritDoc}
     */
    public function setVaros($varos)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setVaros', [$varos]);

        return parent::setVaros($varos);
    }

    /**
     * {@inheritDoc}
     */
    public function getUtca()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getUtca', []);

        return parent::getUtca();
    }

    /**
     * {@inheritDoc}
     */
    public function setUtca($utca)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setUtca', [$utca]);

        return parent::setUtca($utca);
    }

    /**
     * {@inheritDoc}
     */
    public function getTelefon()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTelefon', []);

        return parent::getTelefon();
    }

    /**
     * {@inheritDoc}
     */
    public function setTelefon($telefon)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTelefon', [$telefon]);

        return parent::setTelefon($telefon);
    }

    /**
     * {@inheritDoc}
     */
    public function getMobil()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMobil', []);

        return parent::getMobil();
    }

    /**
     * {@inheritDoc}
     */
    public function setMobil($mobil)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMobil', [$mobil]);

        return parent::setMobil($mobil);
    }

    /**
     * {@inheritDoc}
     */
    public function getFax()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getFax', []);

        return parent::getFax();
    }

    /**
     * {@inheritDoc}
     */
    public function setFax($fax)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setFax', [$fax]);

        return parent::setFax($fax);
    }

    /**
     * {@inheritDoc}
     */
    public function getEmail()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getEmail', []);

        return parent::getEmail();
    }

    /**
     * {@inheritDoc}
     */
    public function setEmail($email)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setEmail', [$email]);

        return parent::setEmail($email);
    }

    /**
     * {@inheritDoc}
     */
    public function getHonlap()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getHonlap', []);

        return parent::getHonlap();
    }

    /**
     * {@inheritDoc}
     */
    public function setHonlap($honlap)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setHonlap', [$honlap]);

        return parent::setHonlap($honlap);
    }

    /**
     * {@inheritDoc}
     */
    public function getPw()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPw', []);

        return parent::getPw();
    }

    /**
     * {@inheritDoc}
     */
    public function setPw($pw)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPw', [$pw]);

        return parent::setPw($pw);
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
    public function setFelhasznalo(\Entities\Felhasznalo $felhasznalo)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setFelhasznalo', [$felhasznalo]);

        return parent::setFelhasznalo($felhasznalo);
    }

    /**
     * {@inheritDoc}
     */
    public function removeFelhasznalo()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeFelhasznalo', []);

        return parent::removeFelhasznalo();
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
    public function setMegjegyzes($megjegyzes)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMegjegyzes', [$megjegyzes]);

        return parent::setMegjegyzes($megjegyzes);
    }

    /**
     * {@inheritDoc}
     */
    public function getJutalek()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getJutalek', []);

        return parent::getJutalek();
    }

    /**
     * {@inheritDoc}
     */
    public function setJutalek($jutalek)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setJutalek', [$jutalek]);

        return parent::setJutalek($jutalek);
    }

    /**
     * {@inheritDoc}
     */
    public function getSessionid()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSessionid', []);

        return parent::getSessionid();
    }

    /**
     * {@inheritDoc}
     */
    public function setSessionid($sessionid)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSessionid', [$sessionid]);

        return parent::setSessionid($sessionid);
    }

    /**
     * {@inheritDoc}
     */
    public function getPartnerszamlatipus()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPartnerszamlatipus', []);

        return parent::getPartnerszamlatipus();
    }

    /**
     * {@inheritDoc}
     */
    public function setPartnerszamlatipus($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPartnerszamlatipus', [$val]);

        return parent::setPartnerszamlatipus($val);
    }

    /**
     * {@inheritDoc}
     */
    public function getPartnervalutanem()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPartnervalutanem', []);

        return parent::getPartnervalutanem();
    }

    /**
     * {@inheritDoc}
     */
    public function getPartnervalutanemnev()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPartnervalutanemnev', []);

        return parent::getPartnervalutanemnev();
    }

    /**
     * {@inheritDoc}
     */
    public function getPartnervalutanemId()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPartnervalutanemId', []);

        return parent::getPartnervalutanemId();
    }

    /**
     * {@inheritDoc}
     */
    public function setPartnervalutanem($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPartnervalutanem', [$val]);

        return parent::setPartnervalutanem($val);
    }

    /**
     * {@inheritDoc}
     */
    public function removePartnervalutanem()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removePartnervalutanem', []);

        return parent::removePartnervalutanem();
    }

    /**
     * {@inheritDoc}
     */
    public function getPartnerszallitasimod()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPartnerszallitasimod', []);

        return parent::getPartnerszallitasimod();
    }

    /**
     * {@inheritDoc}
     */
    public function getPartnerszallitasimodNev()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPartnerszallitasimodNev', []);

        return parent::getPartnerszallitasimodNev();
    }

    /**
     * {@inheritDoc}
     */
    public function getPartnerszallitasimodId()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPartnerszallitasimodId', []);

        return parent::getPartnerszallitasimodId();
    }

    /**
     * {@inheritDoc}
     */
    public function setPartnerszallitasimod($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPartnerszallitasimod', [$val]);

        return parent::setPartnerszallitasimod($val);
    }

    /**
     * {@inheritDoc}
     */
    public function removePartnerszallitasimod()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removePartnerszallitasimod', []);

        return parent::removePartnerszallitasimod();
    }

    /**
     * {@inheritDoc}
     */
    public function getPartnerbizonylatnyelv()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPartnerbizonylatnyelv', []);

        return parent::getPartnerbizonylatnyelv();
    }

    /**
     * {@inheritDoc}
     */
    public function setPartnerbizonylatnyelv($adat)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPartnerbizonylatnyelv', [$adat]);

        return parent::setPartnerbizonylatnyelv($adat);
    }

    /**
     * {@inheritDoc}
     */
    public function getPartnerfizmod()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPartnerfizmod', []);

        return parent::getPartnerfizmod();
    }

    /**
     * {@inheritDoc}
     */
    public function getPartnerfizmodNev()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPartnerfizmodNev', []);

        return parent::getPartnerfizmodNev();
    }

    /**
     * {@inheritDoc}
     */
    public function getPartnerfizmodId()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPartnerfizmodId', []);

        return parent::getPartnerfizmodId();
    }

    /**
     * {@inheritDoc}
     */
    public function setPartnerfizmod(\Entities\Fizmod $fizmod)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPartnerfizmod', [$fizmod]);

        return parent::setPartnerfizmod($fizmod);
    }

    /**
     * {@inheritDoc}
     */
    public function getBelso()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getBelso', []);

        return parent::getBelso();
    }

    /**
     * {@inheritDoc}
     */
    public function setBelso($belso)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setBelso', [$belso]);

        return parent::setBelso($belso);
    }

    /**
     * {@inheritDoc}
     */
    public function getFo()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getFo', []);

        return parent::getFo();
    }

    /**
     * {@inheritDoc}
     */
    public function setFo($fo)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setFo', [$fo]);

        return parent::setFo($fo);
    }

    /**
     * {@inheritDoc}
     */
    public function getFouzletkoto()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getFouzletkoto', []);

        return parent::getFouzletkoto();
    }

    /**
     * {@inheritDoc}
     */
    public function getFouzletkotoId()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getFouzletkotoId', []);

        return parent::getFouzletkotoId();
    }

    /**
     * {@inheritDoc}
     */
    public function setFouzletkoto($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setFouzletkoto', [$val]);

        return parent::setFouzletkoto($val);
    }

    /**
     * {@inheritDoc}
     */
    public function removeFouzletkoto()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeFouzletkoto', []);

        return parent::removeFouzletkoto();
    }

    /**
     * {@inheritDoc}
     */
    public function getArsav()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getArsav', []);

        return parent::getArsav();
    }

    /**
     * {@inheritDoc}
     */
    public function setArsav($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setArsav', [$val]);

        return parent::setArsav($val);
    }

    /**
     * {@inheritDoc}
     */
    public function removeArsav()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeArsav', []);

        return parent::removeArsav();
    }

    /**
     * {@inheritDoc}
     */
    public function getPartnertermekarazonosito()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPartnertermekarazonosito', []);

        return parent::getPartnertermekarazonosito();
    }

    /**
     * {@inheritDoc}
     */
    public function setPartnertermekarazonosito($v)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPartnertermekarazonosito', [$v]);

        return parent::setPartnertermekarazonosito($v);
    }

}
