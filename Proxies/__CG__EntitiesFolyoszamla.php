<?php

namespace Proxies\__CG__\Entities;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class Folyoszamla extends \Entities\Folyoszamla implements \Doctrine\ORM\Proxy\Proxy
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
    public static $lazyPropertiesDefaults = [];



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
            return ['__isInitialized__', '' . "\0" . 'Entities\\Folyoszamla' . "\0" . 'id', '' . "\0" . 'Entities\\Folyoszamla' . "\0" . 'datum', '' . "\0" . 'Entities\\Folyoszamla' . "\0" . 'hivatkozottdatum', '' . "\0" . 'Entities\\Folyoszamla' . "\0" . 'hivatkozottbizonylat', '' . "\0" . 'Entities\\Folyoszamla' . "\0" . 'bizonylatfej', '' . "\0" . 'Entities\\Folyoszamla' . "\0" . 'bankbizonylatfej', '' . "\0" . 'Entities\\Folyoszamla' . "\0" . 'bankbizonylattetel', '' . "\0" . 'Entities\\Folyoszamla' . "\0" . 'penztarbizonylatfej', '' . "\0" . 'Entities\\Folyoszamla' . "\0" . 'penztarbizonylattetel', '' . "\0" . 'Entities\\Folyoszamla' . "\0" . 'bizonylattipus', '' . "\0" . 'Entities\\Folyoszamla' . "\0" . 'irany', '' . "\0" . 'Entities\\Folyoszamla' . "\0" . 'partner', '' . "\0" . 'Entities\\Folyoszamla' . "\0" . 'uzletkoto', '' . "\0" . 'Entities\\Folyoszamla' . "\0" . 'fizmod', '' . "\0" . 'Entities\\Folyoszamla' . "\0" . 'netto', '' . "\0" . 'Entities\\Folyoszamla' . "\0" . 'afa', '' . "\0" . 'Entities\\Folyoszamla' . "\0" . 'brutto', '' . "\0" . 'Entities\\Folyoszamla' . "\0" . 'valutanem', '' . "\0" . 'Entities\\Folyoszamla' . "\0" . 'nettohuf', '' . "\0" . 'Entities\\Folyoszamla' . "\0" . 'afahuf', '' . "\0" . 'Entities\\Folyoszamla' . "\0" . 'bruttohuf', '' . "\0" . 'Entities\\Folyoszamla' . "\0" . 'storno', '' . "\0" . 'Entities\\Folyoszamla' . "\0" . 'stornozott', '' . "\0" . 'Entities\\Folyoszamla' . "\0" . 'rontott'];
        }

        return ['__isInitialized__', '' . "\0" . 'Entities\\Folyoszamla' . "\0" . 'id', '' . "\0" . 'Entities\\Folyoszamla' . "\0" . 'datum', '' . "\0" . 'Entities\\Folyoszamla' . "\0" . 'hivatkozottdatum', '' . "\0" . 'Entities\\Folyoszamla' . "\0" . 'hivatkozottbizonylat', '' . "\0" . 'Entities\\Folyoszamla' . "\0" . 'bizonylatfej', '' . "\0" . 'Entities\\Folyoszamla' . "\0" . 'bankbizonylatfej', '' . "\0" . 'Entities\\Folyoszamla' . "\0" . 'bankbizonylattetel', '' . "\0" . 'Entities\\Folyoszamla' . "\0" . 'penztarbizonylatfej', '' . "\0" . 'Entities\\Folyoszamla' . "\0" . 'penztarbizonylattetel', '' . "\0" . 'Entities\\Folyoszamla' . "\0" . 'bizonylattipus', '' . "\0" . 'Entities\\Folyoszamla' . "\0" . 'irany', '' . "\0" . 'Entities\\Folyoszamla' . "\0" . 'partner', '' . "\0" . 'Entities\\Folyoszamla' . "\0" . 'uzletkoto', '' . "\0" . 'Entities\\Folyoszamla' . "\0" . 'fizmod', '' . "\0" . 'Entities\\Folyoszamla' . "\0" . 'netto', '' . "\0" . 'Entities\\Folyoszamla' . "\0" . 'afa', '' . "\0" . 'Entities\\Folyoszamla' . "\0" . 'brutto', '' . "\0" . 'Entities\\Folyoszamla' . "\0" . 'valutanem', '' . "\0" . 'Entities\\Folyoszamla' . "\0" . 'nettohuf', '' . "\0" . 'Entities\\Folyoszamla' . "\0" . 'afahuf', '' . "\0" . 'Entities\\Folyoszamla' . "\0" . 'bruttohuf', '' . "\0" . 'Entities\\Folyoszamla' . "\0" . 'storno', '' . "\0" . 'Entities\\Folyoszamla' . "\0" . 'stornozott', '' . "\0" . 'Entities\\Folyoszamla' . "\0" . 'rontott'];
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (Folyoszamla $proxy) {
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
    public function setDatum($datum)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDatum', [$datum]);

        return parent::setDatum($datum);
    }

    /**
     * {@inheritDoc}
     */
    public function getHivatkozottbizonylat()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getHivatkozottbizonylat', []);

        return parent::getHivatkozottbizonylat();
    }

    /**
     * {@inheritDoc}
     */
    public function setHivatkozottbizonylat($hivatkozottbizonylat)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setHivatkozottbizonylat', [$hivatkozottbizonylat]);

        return parent::setHivatkozottbizonylat($hivatkozottbizonylat);
    }

    /**
     * {@inheritDoc}
     */
    public function getBizonylatfej()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getBizonylatfej', []);

        return parent::getBizonylatfej();
    }

    /**
     * {@inheritDoc}
     */
    public function getBizonylatfejId()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getBizonylatfejId', []);

        return parent::getBizonylatfejId();
    }

    /**
     * {@inheritDoc}
     */
    public function setBizonylatfej($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setBizonylatfej', [$val]);

        return parent::setBizonylatfej($val);
    }

    /**
     * {@inheritDoc}
     */
    public function removeBizonylatfej()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeBizonylatfej', []);

        return parent::removeBizonylatfej();
    }

    /**
     * {@inheritDoc}
     */
    public function getBankbizonylatfej()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getBankbizonylatfej', []);

        return parent::getBankbizonylatfej();
    }

    /**
     * {@inheritDoc}
     */
    public function getBankbizonylatfejId()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getBankbizonylatfejId', []);

        return parent::getBankbizonylatfejId();
    }

    /**
     * {@inheritDoc}
     */
    public function setBankbizonylatfej($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setBankbizonylatfej', [$val]);

        return parent::setBankbizonylatfej($val);
    }

    /**
     * {@inheritDoc}
     */
    public function removeBankbizonylatfej()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeBankbizonylatfej', []);

        return parent::removeBankbizonylatfej();
    }

    /**
     * {@inheritDoc}
     */
    public function getBankbizonylattetel()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getBankbizonylattetel', []);

        return parent::getBankbizonylattetel();
    }

    /**
     * {@inheritDoc}
     */
    public function getBankbizonylattetelId()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getBankbizonylattetelId', []);

        return parent::getBankbizonylattetelId();
    }

    /**
     * {@inheritDoc}
     */
    public function setBankbizonylattetel($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setBankbizonylattetel', [$val]);

        return parent::setBankbizonylattetel($val);
    }

    /**
     * {@inheritDoc}
     */
    public function removeBankbizonylattetel()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeBankbizonylattetel', []);

        return parent::removeBankbizonylattetel();
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
    public function getAfa()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getAfa', []);

        return parent::getAfa();
    }

    /**
     * {@inheritDoc}
     */
    public function setAfa($afa)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setAfa', [$afa]);

        return parent::setAfa($afa);
    }

    /**
     * {@inheritDoc}
     */
    public function getAfahuf()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getAfahuf', []);

        return parent::getAfahuf();
    }

    /**
     * {@inheritDoc}
     */
    public function setAfahuf($afahuf)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setAfahuf', [$afahuf]);

        return parent::setAfahuf($afahuf);
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
    public function setBrutto($brutto)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setBrutto', [$brutto]);

        return parent::setBrutto($brutto);
    }

    /**
     * {@inheritDoc}
     */
    public function getBruttohuf()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getBruttohuf', []);

        return parent::getBruttohuf();
    }

    /**
     * {@inheritDoc}
     */
    public function setBruttohuf($bruttohuf)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setBruttohuf', [$bruttohuf]);

        return parent::setBruttohuf($bruttohuf);
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
    public function setIrany($irany)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setIrany', [$irany]);

        return parent::setIrany($irany);
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
    public function setNetto($netto)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setNetto', [$netto]);

        return parent::setNetto($netto);
    }

    /**
     * {@inheritDoc}
     */
    public function getNettohuf()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNettohuf', []);

        return parent::getNettohuf();
    }

    /**
     * {@inheritDoc}
     */
    public function setNettohuf($nettohuf)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setNettohuf', [$nettohuf]);

        return parent::setNettohuf($nettohuf);
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
    public function setRontott($rontott)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setRontott', [$rontott]);

        return parent::setRontott($rontott);
    }

    /**
     * {@inheritDoc}
     */
    public function getStorno()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getStorno', []);

        return parent::getStorno();
    }

    /**
     * {@inheritDoc}
     */
    public function setStorno($storno)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setStorno', [$storno]);

        return parent::setStorno($storno);
    }

    /**
     * {@inheritDoc}
     */
    public function getStornozott()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getStornozott', []);

        return parent::getStornozott();
    }

    /**
     * {@inheritDoc}
     */
    public function setStornozott($stornozott)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setStornozott', [$stornozott]);

        return parent::setStornozott($stornozott);
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
    public function getUzletkoto()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getUzletkoto', []);

        return parent::getUzletkoto();
    }

    /**
     * {@inheritDoc}
     */
    public function getUzletkotoId()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getUzletkotoId', []);

        return parent::getUzletkotoId();
    }

    /**
     * {@inheritDoc}
     */
    public function setUzletkoto($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setUzletkoto', [$val]);

        return parent::setUzletkoto($val);
    }

    /**
     * {@inheritDoc}
     */
    public function removeUzletkoto()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeUzletkoto', []);

        return parent::removeUzletkoto();
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
    public function getFizmodId()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getFizmodId', []);

        return parent::getFizmodId();
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
    public function getHivatkozottdatum()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getHivatkozottdatum', []);

        return parent::getHivatkozottdatum();
    }

    /**
     * {@inheritDoc}
     */
    public function getHivatkozottdatumStr()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getHivatkozottdatumStr', []);

        return parent::getHivatkozottdatumStr();
    }

    /**
     * {@inheritDoc}
     */
    public function setHivatkozottdatum($adat = '')
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setHivatkozottdatum', [$adat]);

        return parent::setHivatkozottdatum($adat);
    }

    /**
     * {@inheritDoc}
     */
    public function getPenztarbizonylatfej()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPenztarbizonylatfej', []);

        return parent::getPenztarbizonylatfej();
    }

    /**
     * {@inheritDoc}
     */
    public function getPenztarbizonylatfejId()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPenztarbizonylatfejId', []);

        return parent::getPenztarbizonylatfejId();
    }

    /**
     * {@inheritDoc}
     */
    public function setPenztarbizonylatfej($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPenztarbizonylatfej', [$val]);

        return parent::setPenztarbizonylatfej($val);
    }

    /**
     * {@inheritDoc}
     */
    public function removePaneztarbizonylatfej()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removePaneztarbizonylatfej', []);

        return parent::removePaneztarbizonylatfej();
    }

    /**
     * {@inheritDoc}
     */
    public function getPenztarbizonylattetel()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPenztarbizonylattetel', []);

        return parent::getPenztarbizonylattetel();
    }

    /**
     * {@inheritDoc}
     */
    public function getPenztarbizonylattetelId()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPenztarbizonylattetelId', []);

        return parent::getPenztarbizonylattetelId();
    }

    /**
     * {@inheritDoc}
     */
    public function setPenztarbizonylattetel($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPenztarbizonylattetel', [$val]);

        return parent::setPenztarbizonylattetel($val);
    }

    /**
     * {@inheritDoc}
     */
    public function removePenztarbizonylattetel()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removePenztarbizonylattetel', []);

        return parent::removePenztarbizonylattetel();
    }

}
