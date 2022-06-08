<?php

namespace Proxies\__CG__\Entities;


/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class Penztarbizonylatfej extends \Entities\Penztarbizonylatfej implements \Doctrine\ORM\Proxy\Proxy
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
            return ['__isInitialized__', '' . "\0" . 'Entities\\Penztarbizonylatfej' . "\0" . 'id', '' . "\0" . 'Entities\\Penztarbizonylatfej' . "\0" . 'created', '' . "\0" . 'Entities\\Penztarbizonylatfej' . "\0" . 'lastmod', '' . "\0" . 'Entities\\Penztarbizonylatfej' . "\0" . 'createdby', '' . "\0" . 'Entities\\Penztarbizonylatfej' . "\0" . 'updatedby', '' . "\0" . 'Entities\\Penztarbizonylatfej' . "\0" . 'bizonylattipus', '' . "\0" . 'Entities\\Penztarbizonylatfej' . "\0" . 'irany', '' . "\0" . 'Entities\\Penztarbizonylatfej' . "\0" . 'penztar', '' . "\0" . 'Entities\\Penztarbizonylatfej' . "\0" . 'penztarnev', '' . "\0" . 'Entities\\Penztarbizonylatfej' . "\0" . 'rontott', '' . "\0" . 'Entities\\Penztarbizonylatfej' . "\0" . 'erbizonylatszam', '' . "\0" . 'Entities\\Penztarbizonylatfej' . "\0" . 'megjegyzes', '' . "\0" . 'Entities\\Penztarbizonylatfej' . "\0" . 'kelt', '' . "\0" . 'Entities\\Penztarbizonylatfej' . "\0" . 'netto', '' . "\0" . 'Entities\\Penztarbizonylatfej' . "\0" . 'afa', '' . "\0" . 'Entities\\Penztarbizonylatfej' . "\0" . 'brutto', '' . "\0" . 'Entities\\Penztarbizonylatfej' . "\0" . 'valutanem', '' . "\0" . 'Entities\\Penztarbizonylatfej' . "\0" . 'valutanemnev', '' . "\0" . 'Entities\\Penztarbizonylatfej' . "\0" . 'arfolyam', '' . "\0" . 'Entities\\Penztarbizonylatfej' . "\0" . 'partner', '' . "\0" . 'Entities\\Penztarbizonylatfej' . "\0" . 'partnernev', '' . "\0" . 'Entities\\Penztarbizonylatfej' . "\0" . 'partnervezeteknev', '' . "\0" . 'Entities\\Penztarbizonylatfej' . "\0" . 'partnerkeresztnev', '' . "\0" . 'Entities\\Penztarbizonylatfej' . "\0" . 'partneradoszam', '' . "\0" . 'Entities\\Penztarbizonylatfej' . "\0" . 'partnereuadoszam', '' . "\0" . 'Entities\\Penztarbizonylatfej' . "\0" . 'partnerirszam', '' . "\0" . 'Entities\\Penztarbizonylatfej' . "\0" . 'partnervaros', '' . "\0" . 'Entities\\Penztarbizonylatfej' . "\0" . 'partnerutca', '' . "\0" . 'Entities\\Penztarbizonylatfej' . "\0" . 'partnerhazszam', '' . "\0" . 'Entities\\Penztarbizonylatfej' . "\0" . 'bizonylattetelek', '' . "\0" . 'Entities\\Penztarbizonylatfej' . "\0" . 'folyoszamlak'];
        }

        return ['__isInitialized__', '' . "\0" . 'Entities\\Penztarbizonylatfej' . "\0" . 'id', '' . "\0" . 'Entities\\Penztarbizonylatfej' . "\0" . 'created', '' . "\0" . 'Entities\\Penztarbizonylatfej' . "\0" . 'lastmod', '' . "\0" . 'Entities\\Penztarbizonylatfej' . "\0" . 'createdby', '' . "\0" . 'Entities\\Penztarbizonylatfej' . "\0" . 'updatedby', '' . "\0" . 'Entities\\Penztarbizonylatfej' . "\0" . 'bizonylattipus', '' . "\0" . 'Entities\\Penztarbizonylatfej' . "\0" . 'irany', '' . "\0" . 'Entities\\Penztarbizonylatfej' . "\0" . 'penztar', '' . "\0" . 'Entities\\Penztarbizonylatfej' . "\0" . 'penztarnev', '' . "\0" . 'Entities\\Penztarbizonylatfej' . "\0" . 'rontott', '' . "\0" . 'Entities\\Penztarbizonylatfej' . "\0" . 'erbizonylatszam', '' . "\0" . 'Entities\\Penztarbizonylatfej' . "\0" . 'megjegyzes', '' . "\0" . 'Entities\\Penztarbizonylatfej' . "\0" . 'kelt', '' . "\0" . 'Entities\\Penztarbizonylatfej' . "\0" . 'netto', '' . "\0" . 'Entities\\Penztarbizonylatfej' . "\0" . 'afa', '' . "\0" . 'Entities\\Penztarbizonylatfej' . "\0" . 'brutto', '' . "\0" . 'Entities\\Penztarbizonylatfej' . "\0" . 'valutanem', '' . "\0" . 'Entities\\Penztarbizonylatfej' . "\0" . 'valutanemnev', '' . "\0" . 'Entities\\Penztarbizonylatfej' . "\0" . 'arfolyam', '' . "\0" . 'Entities\\Penztarbizonylatfej' . "\0" . 'partner', '' . "\0" . 'Entities\\Penztarbizonylatfej' . "\0" . 'partnernev', '' . "\0" . 'Entities\\Penztarbizonylatfej' . "\0" . 'partnervezeteknev', '' . "\0" . 'Entities\\Penztarbizonylatfej' . "\0" . 'partnerkeresztnev', '' . "\0" . 'Entities\\Penztarbizonylatfej' . "\0" . 'partneradoszam', '' . "\0" . 'Entities\\Penztarbizonylatfej' . "\0" . 'partnereuadoszam', '' . "\0" . 'Entities\\Penztarbizonylatfej' . "\0" . 'partnerirszam', '' . "\0" . 'Entities\\Penztarbizonylatfej' . "\0" . 'partnervaros', '' . "\0" . 'Entities\\Penztarbizonylatfej' . "\0" . 'partnerutca', '' . "\0" . 'Entities\\Penztarbizonylatfej' . "\0" . 'partnerhazszam', '' . "\0" . 'Entities\\Penztarbizonylatfej' . "\0" . 'bizonylattetelek', '' . "\0" . 'Entities\\Penztarbizonylatfej' . "\0" . 'folyoszamlak'];
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (Penztarbizonylatfej $proxy) {
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
            return  parent::getId();
        }


        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getId', []);

        return parent::getId();
    }

    /**
     * {@inheritDoc}
     */
    public function setId($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setId', [$val]);

        return parent::setId($val);
    }

    /**
     * {@inheritDoc}
     */
    public function getBizonylattetelek()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getBizonylattetelek', []);

        return parent::getBizonylattetelek();
    }

    /**
     * {@inheritDoc}
     */
    public function addBizonylattetel(\Entities\Penztarbizonylattetel $val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addBizonylattetel', [$val]);

        return parent::addBizonylattetel($val);
    }

    /**
     * {@inheritDoc}
     */
    public function removeBizonylattetel(\Entities\Penztarbizonylattetel $val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeBizonylattetel', [$val]);

        return parent::removeBizonylattetel($val);
    }

    /**
     * {@inheritDoc}
     */
    public function clearBizonylattetelek()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'clearBizonylattetelek', []);

        return parent::clearBizonylattetelek();
    }

    /**
     * {@inheritDoc}
     */
    public function getFolyoszamlak()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getFolyoszamlak', []);

        return parent::getFolyoszamlak();
    }

    /**
     * {@inheritDoc}
     */
    public function addFolyoszamla(\Entities\Folyoszamla $val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addFolyoszamla', [$val]);

        return parent::addFolyoszamla($val);
    }

    /**
     * {@inheritDoc}
     */
    public function removeFolyoszamla(\Entities\Folyoszamla $val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeFolyoszamla', [$val]);

        return parent::removeFolyoszamla($val);
    }

    /**
     * {@inheritDoc}
     */
    public function clearFolyoszamlak()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'clearFolyoszamlak', []);

        return parent::clearFolyoszamlak();
    }

    /**
     * {@inheritDoc}
     */
    public function getIrany()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getIrany', []);

        return parent::getIrany();
    }

    /**
     * {@inheritDoc}
     */
    public function setIrany($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setIrany', [$val]);

        return parent::setIrany($val);
    }

    /**
     * {@inheritDoc}
     */
    public function getBizonylattipus()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getBizonylattipus', []);

        return parent::getBizonylattipus();
    }

    /**
     * {@inheritDoc}
     */
    public function getBizonylattipusId()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getBizonylattipusId', []);

        return parent::getBizonylattipusId();
    }

    /**
     * {@inheritDoc}
     */
    public function setBizonylattipus($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setBizonylattipus', [$val]);

        return parent::setBizonylattipus($val);
    }

    /**
     * {@inheritDoc}
     */
    public function removeBizonylattipus()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeBizonylattipus', []);

        return parent::removeBizonylattipus();
    }

    /**
     * {@inheritDoc}
     */
    public function getKelt()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getKelt', []);

        return parent::getKelt();
    }

    /**
     * {@inheritDoc}
     */
    public function getKeltStr()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getKeltStr', []);

        return parent::getKeltStr();
    }

    /**
     * {@inheritDoc}
     */
    public function setKelt($adat = '')
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setKelt', [$adat]);

        return parent::setKelt($adat);
    }

    /**
     * {@inheritDoc}
     */
    public function getNetto()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNetto', []);

        return parent::getNetto();
    }

    /**
     * {@inheritDoc}
     */
    public function setNetto($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setNetto', [$val]);

        return parent::setNetto($val);
    }

    /**
     * {@inheritDoc}
     */
    public function getAfa()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getAfa', []);

        return parent::getAfa();
    }

    /**
     * {@inheritDoc}
     */
    public function setAfa($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setAfa', [$val]);

        return parent::setAfa($val);
    }

    /**
     * {@inheritDoc}
     */
    public function getBrutto()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getBrutto', []);

        return parent::getBrutto();
    }

    /**
     * {@inheritDoc}
     */
    public function setBrutto($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setBrutto', [$val]);

        return parent::setBrutto($val);
    }

    /**
     * {@inheritDoc}
     */
    public function getValutanem()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getValutanem', []);

        return parent::getValutanem();
    }

    /**
     * {@inheritDoc}
     */
    public function getValutanemnev()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getValutanemnev', []);

        return parent::getValutanemnev();
    }

    /**
     * {@inheritDoc}
     */
    public function getValutanemId()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getValutanemId', []);

        return parent::getValutanemId();
    }

    /**
     * {@inheritDoc}
     */
    public function setValutanem($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setValutanem', [$val]);

        return parent::setValutanem($val);
    }

    /**
     * {@inheritDoc}
     */
    public function removeValutanem()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeValutanem', []);

        return parent::removeValutanem();
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
    public function setPartnerLeiroadat($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPartnerLeiroadat', [$val]);

        return parent::setPartnerLeiroadat($val);
    }

    /**
     * {@inheritDoc}
     */
    public function setPartner($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPartner', [$val]);

        return parent::setPartner($val);
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
    public function getPartnervezeteknev()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPartnervezeteknev', []);

        return parent::getPartnervezeteknev();
    }

    /**
     * {@inheritDoc}
     */
    public function setPartnervezeteknev($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPartnervezeteknev', [$val]);

        return parent::setPartnervezeteknev($val);
    }

    /**
     * {@inheritDoc}
     */
    public function getPartnerkeresztnev()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPartnerkeresztnev', []);

        return parent::getPartnerkeresztnev();
    }

    /**
     * {@inheritDoc}
     */
    public function setPartnerkeresztnev($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPartnerkeresztnev', [$val]);

        return parent::setPartnerkeresztnev($val);
    }

    /**
     * {@inheritDoc}
     */
    public function getPartneradoszam()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPartneradoszam', []);

        return parent::getPartneradoszam();
    }

    /**
     * {@inheritDoc}
     */
    public function setPartneradoszam($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPartneradoszam', [$val]);

        return parent::setPartneradoszam($val);
    }

    /**
     * {@inheritDoc}
     */
    public function getPartnereuadoszam()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPartnereuadoszam', []);

        return parent::getPartnereuadoszam();
    }

    /**
     * {@inheritDoc}
     */
    public function setPartnereuadoszam($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPartnereuadoszam', [$val]);

        return parent::setPartnereuadoszam($val);
    }

    /**
     * {@inheritDoc}
     */
    public function getPartnerirszam()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPartnerirszam', []);

        return parent::getPartnerirszam();
    }

    /**
     * {@inheritDoc}
     */
    public function setPartnerirszam($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPartnerirszam', [$val]);

        return parent::setPartnerirszam($val);
    }

    /**
     * {@inheritDoc}
     */
    public function getPartnerutca()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPartnerutca', []);

        return parent::getPartnerutca();
    }

    /**
     * {@inheritDoc}
     */
    public function setPartnerutca($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPartnerutca', [$val]);

        return parent::setPartnerutca($val);
    }

    /**
     * {@inheritDoc}
     */
    public function getPartnervaros()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPartnervaros', []);

        return parent::getPartnervaros();
    }

    /**
     * {@inheritDoc}
     */
    public function setPartnervaros($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPartnervaros', [$val]);

        return parent::setPartnervaros($val);
    }

    /**
     * {@inheritDoc}
     */
    public function getPenztar()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPenztar', []);

        return parent::getPenztar();
    }

    /**
     * {@inheritDoc}
     */
    public function getPenztarId()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPenztarId', []);

        return parent::getPenztarId();
    }

    /**
     * {@inheritDoc}
     */
    public function setPenztar($val = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPenztar', [$val]);

        return parent::setPenztar($val);
    }

    /**
     * {@inheritDoc}
     */
    public function removePenztar()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removePenztar', []);

        return parent::removePenztar();
    }

    /**
     * {@inheritDoc}
     */
    public function getPenztarnev()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPenztarnev', []);

        return parent::getPenztarnev();
    }

    /**
     * {@inheritDoc}
     */
    public function getErbizonylatszam()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getErbizonylatszam', []);

        return parent::getErbizonylatszam();
    }

    /**
     * {@inheritDoc}
     */
    public function setErbizonylatszam($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setErbizonylatszam', [$val]);

        return parent::setErbizonylatszam($val);
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
    public function setMegjegyzes($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMegjegyzes', [$val]);

        return parent::setMegjegyzes($val);
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
    public function clearCreated()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'clearCreated', []);

        return parent::clearCreated();
    }

    /**
     * {@inheritDoc}
     */
    public function getRontott()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRontott', []);

        return parent::getRontott();
    }

    /**
     * {@inheritDoc}
     */
    public function setRontott($adat)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setRontott', [$adat]);

        return parent::setRontott($adat);
    }

    /**
     * {@inheritDoc}
     */
    public function getArfolyam()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getArfolyam', []);

        return parent::getArfolyam();
    }

    /**
     * {@inheritDoc}
     */
    public function setArfolyam($arfolyam)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setArfolyam', [$arfolyam]);

        return parent::setArfolyam($arfolyam);
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
    public function getCreatedStr()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCreatedStr', []);

        return parent::getCreatedStr();
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
    public function getPartnerhazszam()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPartnerhazszam', []);

        return parent::getPartnerhazszam();
    }

    /**
     * {@inheritDoc}
     */
    public function setPartnerhazszam($partnerhazszam)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPartnerhazszam', [$partnerhazszam]);

        return parent::setPartnerhazszam($partnerhazszam);
    }

}
