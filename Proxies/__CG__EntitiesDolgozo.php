<?php

namespace Proxies\__CG__\Entities;


/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class Dolgozo extends \Entities\Dolgozo implements \Doctrine\ORM\Proxy\Proxy
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
            return ['__isInitialized__', '' . "\0" . 'Entities\\Dolgozo' . "\0" . 'id', '' . "\0" . 'Entities\\Dolgozo' . "\0" . 'nev', '' . "\0" . 'Entities\\Dolgozo' . "\0" . 'jelszo', '' . "\0" . 'Entities\\Dolgozo' . "\0" . 'irszam', '' . "\0" . 'Entities\\Dolgozo' . "\0" . 'varos', '' . "\0" . 'Entities\\Dolgozo' . "\0" . 'utca', '' . "\0" . 'Entities\\Dolgozo' . "\0" . 'telefon', '' . "\0" . 'Entities\\Dolgozo' . "\0" . 'email', '' . "\0" . 'Entities\\Dolgozo' . "\0" . 'url', '' . "\0" . 'Entities\\Dolgozo' . "\0" . 'munkakor', '' . "\0" . 'Entities\\Dolgozo' . "\0" . 'szulido', '' . "\0" . 'Entities\\Dolgozo' . "\0" . 'szulhely', '' . "\0" . 'Entities\\Dolgozo' . "\0" . 'evesmaxszabi', '' . "\0" . 'Entities\\Dolgozo' . "\0" . 'munkaviszonykezdete', '' . "\0" . 'Entities\\Dolgozo' . "\0" . 'jelenletek', '' . "\0" . 'Entities\\Dolgozo' . "\0" . 'uitheme', '' . "\0" . 'Entities\\Dolgozo' . "\0" . 'havilevonas', '' . "\0" . 'Entities\\Dolgozo' . "\0" . 'napilevonas', '' . "\0" . 'Entities\\Dolgozo' . "\0" . 'szamlatad', '' . "\0" . 'Entities\\Dolgozo' . "\0" . 'inaktiv', '' . "\0" . 'Entities\\Dolgozo' . "\0" . 'oraelmaradaskonyvelonek', '' . "\0" . 'Entities\\Dolgozo' . "\0" . 'fizmod'];
        }

        return ['__isInitialized__', '' . "\0" . 'Entities\\Dolgozo' . "\0" . 'id', '' . "\0" . 'Entities\\Dolgozo' . "\0" . 'nev', '' . "\0" . 'Entities\\Dolgozo' . "\0" . 'jelszo', '' . "\0" . 'Entities\\Dolgozo' . "\0" . 'irszam', '' . "\0" . 'Entities\\Dolgozo' . "\0" . 'varos', '' . "\0" . 'Entities\\Dolgozo' . "\0" . 'utca', '' . "\0" . 'Entities\\Dolgozo' . "\0" . 'telefon', '' . "\0" . 'Entities\\Dolgozo' . "\0" . 'email', '' . "\0" . 'Entities\\Dolgozo' . "\0" . 'url', '' . "\0" . 'Entities\\Dolgozo' . "\0" . 'munkakor', '' . "\0" . 'Entities\\Dolgozo' . "\0" . 'szulido', '' . "\0" . 'Entities\\Dolgozo' . "\0" . 'szulhely', '' . "\0" . 'Entities\\Dolgozo' . "\0" . 'evesmaxszabi', '' . "\0" . 'Entities\\Dolgozo' . "\0" . 'munkaviszonykezdete', '' . "\0" . 'Entities\\Dolgozo' . "\0" . 'jelenletek', '' . "\0" . 'Entities\\Dolgozo' . "\0" . 'uitheme', '' . "\0" . 'Entities\\Dolgozo' . "\0" . 'havilevonas', '' . "\0" . 'Entities\\Dolgozo' . "\0" . 'napilevonas', '' . "\0" . 'Entities\\Dolgozo' . "\0" . 'szamlatad', '' . "\0" . 'Entities\\Dolgozo' . "\0" . 'inaktiv', '' . "\0" . 'Entities\\Dolgozo' . "\0" . 'oraelmaradaskonyvelonek', '' . "\0" . 'Entities\\Dolgozo' . "\0" . 'fizmod'];
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (Dolgozo $proxy) {
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
    public function getSzulido()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSzulido', []);

        return parent::getSzulido();
    }

    /**
     * {@inheritDoc}
     */
    public function getSzulidoStr()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSzulidoStr', []);

        return parent::getSzulidoStr();
    }

    /**
     * {@inheritDoc}
     */
    public function setSzulido($adat)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSzulido', [$adat]);

        return parent::setSzulido($adat);
    }

    /**
     * {@inheritDoc}
     */
    public function getSzulhely()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSzulhely', []);

        return parent::getSzulhely();
    }

    /**
     * {@inheritDoc}
     */
    public function setSzulhely($adat)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSzulhely', [$adat]);

        return parent::setSzulhely($adat);
    }

    /**
     * {@inheritDoc}
     */
    public function getEvesmaxszabi()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getEvesmaxszabi', []);

        return parent::getEvesmaxszabi();
    }

    /**
     * {@inheritDoc}
     */
    public function setEvesmaxszabi($eves)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setEvesmaxszabi', [$eves]);

        return parent::setEvesmaxszabi($eves);
    }

    /**
     * {@inheritDoc}
     */
    public function getMunkaviszonykezdete()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMunkaviszonykezdete', []);

        return parent::getMunkaviszonykezdete();
    }

    /**
     * {@inheritDoc}
     */
    public function getMunkaviszonykezdeteStr()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMunkaviszonykezdeteStr', []);

        return parent::getMunkaviszonykezdeteStr();
    }

    /**
     * {@inheritDoc}
     */
    public function setMunkaviszonykezdete($adat)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMunkaviszonykezdete', [$adat]);

        return parent::setMunkaviszonykezdete($adat);
    }

    /**
     * {@inheritDoc}
     */
    public function getMunkakor()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMunkakor', []);

        return parent::getMunkakor();
    }

    /**
     * {@inheritDoc}
     */
    public function getMunkakorNev()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMunkakorNev', []);

        return parent::getMunkakorNev();
    }

    /**
     * {@inheritDoc}
     */
    public function getMunkakorId()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMunkakorId', []);

        return parent::getMunkakorId();
    }

    /**
     * {@inheritDoc}
     */
    public function setMunkakor(\Entities\Munkakor $munkakor)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMunkakor', [$munkakor]);

        return parent::setMunkakor($munkakor);
    }

    /**
     * {@inheritDoc}
     */
    public function removeMunkakor()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeMunkakor', []);

        return parent::removeMunkakor();
    }

    /**
     * {@inheritDoc}
     */
    public function getJog()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getJog', []);

        return parent::getJog();
    }

    /**
     * {@inheritDoc}
     */
    public function getJelenletek()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getJelenletek', []);

        return parent::getJelenletek();
    }

    /**
     * {@inheritDoc}
     */
    public function addJelenlet(\Entities\Jelenletiiv $adat)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addJelenlet', [$adat]);

        return parent::addJelenlet($adat);
    }

    /**
     * {@inheritDoc}
     */
    public function removeJelenlet(\Entities\Jelenletiiv $adat)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeJelenlet', [$adat]);

        return parent::removeJelenlet($adat);
    }

    /**
     * {@inheritDoc}
     */
    public function getJelszo()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getJelszo', []);

        return parent::getJelszo();
    }

    /**
     * {@inheritDoc}
     */
    public function setPlainJelszo($adat)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPlainJelszo', [$adat]);

        return parent::setPlainJelszo($adat);
    }

    /**
     * {@inheritDoc}
     */
    public function checkPlainJelszo($adat)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'checkPlainJelszo', [$adat]);

        return parent::checkPlainJelszo($adat);
    }

    /**
     * {@inheritDoc}
     */
    public function setJelszo($adat)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setJelszo', [$adat]);

        return parent::setJelszo($adat);
    }

    /**
     * {@inheritDoc}
     */
    public function checkJelszo($adat)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'checkJelszo', [$adat]);

        return parent::checkJelszo($adat);
    }

    /**
     * {@inheritDoc}
     */
    public function getUitheme()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getUitheme', []);

        return parent::getUitheme();
    }

    /**
     * {@inheritDoc}
     */
    public function setUitheme($uitheme)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setUitheme', [$uitheme]);

        return parent::setUitheme($uitheme);
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
    public function getHavilevonas()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getHavilevonas', []);

        return parent::getHavilevonas();
    }

    /**
     * {@inheritDoc}
     */
    public function setHavilevonas($havilevonas)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setHavilevonas', [$havilevonas]);

        return parent::setHavilevonas($havilevonas);
    }

    /**
     * {@inheritDoc}
     */
    public function getSzamlatad()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSzamlatad', []);

        return parent::getSzamlatad();
    }

    /**
     * {@inheritDoc}
     */
    public function setSzamlatad($szamlatad)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSzamlatad', [$szamlatad]);

        return parent::setSzamlatad($szamlatad);
    }

    /**
     * {@inheritDoc}
     */
    public function isInaktiv()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'isInaktiv', []);

        return parent::isInaktiv();
    }

    /**
     * {@inheritDoc}
     */
    public function setInaktiv($inaktiv)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setInaktiv', [$inaktiv]);

        return parent::setInaktiv($inaktiv);
    }

    /**
     * {@inheritDoc}
     */
    public function isOraelmaradaskonyvelonek()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'isOraelmaradaskonyvelonek', []);

        return parent::isOraelmaradaskonyvelonek();
    }

    /**
     * {@inheritDoc}
     */
    public function setOraelmaradaskonyvelonek($oraelmaradaskonyvelonek)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setOraelmaradaskonyvelonek', [$oraelmaradaskonyvelonek]);

        return parent::setOraelmaradaskonyvelonek($oraelmaradaskonyvelonek);
    }

    /**
     * {@inheritDoc}
     */
    public function getFizmod()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getFizmod', []);

        return parent::getFizmod();
    }

    /**
     * {@inheritDoc}
     */
    public function getFizmodnev()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getFizmodnev', []);

        return parent::getFizmodnev();
    }

    /**
     * {@inheritDoc}
     */
    public function getFizmodId()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getFizmodId', []);

        return parent::getFizmodId();
    }

    /**
     * {@inheritDoc}
     */
    public function getFizmodTipus()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getFizmodTipus', []);

        return parent::getFizmodTipus();
    }

    /**
     * {@inheritDoc}
     */
    public function setFizmod($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setFizmod', [$val]);

        return parent::setFizmod($val);
    }

    /**
     * {@inheritDoc}
     */
    public function removeFizmod()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeFizmod', []);

        return parent::removeFizmod();
    }

    /**
     * {@inheritDoc}
     */
    public function getNapilevonas()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNapilevonas', []);

        return parent::getNapilevonas();
    }

    /**
     * {@inheritDoc}
     */
    public function setNapilevonas($napilevonas): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setNapilevonas', [$napilevonas]);

        parent::setNapilevonas($napilevonas);
    }

}
