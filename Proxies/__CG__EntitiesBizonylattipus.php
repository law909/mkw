<?php

namespace Proxies\__CG__\Entities;


/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class Bizonylattipus extends \Entities\Bizonylattipus implements \Doctrine\ORM\Proxy\Proxy
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
            return ['__isInitialized__', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'id', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'nev', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'irany', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'nyomtatni', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'azonosito', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'kezdosorszam', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'peldanyszam', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'mozgat', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'foglal', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'penztmozgat', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'editprinted', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'showteljesites', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'showesedekesseg', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'showhatarido', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'showbizonylatstatuszeditor', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'showszamlabutton', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'showszallitobutton', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'showkivetbutton', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'showkeziszamlabutton', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'showbevetbutton', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'showuzenet', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'showszallitasicim', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'showerbizonylatszam', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'showfuvarlevelszam', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'showhaszonszazalek', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'showstorno', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'showbackorder', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'showmesebutton', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'showcsomagbutton', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'showfeketelistabutton', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'showkupon', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'bizonylatfejek', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'tplname', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'showfoxpostterminaleditor', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'showfelhasznalo', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'checkkelt', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'showpdf', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'navbekuldendo', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'showemailbutton'];
        }

        return ['__isInitialized__', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'id', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'nev', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'irany', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'nyomtatni', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'azonosito', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'kezdosorszam', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'peldanyszam', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'mozgat', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'foglal', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'penztmozgat', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'editprinted', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'showteljesites', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'showesedekesseg', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'showhatarido', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'showbizonylatstatuszeditor', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'showszamlabutton', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'showszallitobutton', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'showkivetbutton', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'showkeziszamlabutton', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'showbevetbutton', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'showuzenet', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'showszallitasicim', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'showerbizonylatszam', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'showfuvarlevelszam', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'showhaszonszazalek', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'showstorno', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'showbackorder', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'showmesebutton', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'showcsomagbutton', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'showfeketelistabutton', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'showkupon', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'bizonylatfejek', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'tplname', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'showfoxpostterminaleditor', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'showfelhasznalo', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'checkkelt', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'showpdf', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'navbekuldendo', '' . "\0" . 'Entities\\Bizonylattipus' . "\0" . 'showemailbutton'];
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (Bizonylattipus $proxy) {
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
    public function setTemplateVars($view)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTemplateVars', [$view]);

        return parent::setTemplateVars($view);
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
    public function getNev()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNev', []);

        return parent::getNev();
    }

    /**
     * {@inheritDoc}
     */
    public function setNev($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setNev', [$val]);

        return parent::setNev($val);
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
    public function getNyomtatni()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNyomtatni', []);

        return parent::getNyomtatni();
    }

    /**
     * {@inheritDoc}
     */
    public function setNyomtatni($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setNyomtatni', [$val]);

        return parent::setNyomtatni($val);
    }

    /**
     * {@inheritDoc}
     */
    public function getAzonosito()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getAzonosito', []);

        return parent::getAzonosito();
    }

    /**
     * {@inheritDoc}
     */
    public function setAzonosito($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setAzonosito', [$val]);

        return parent::setAzonosito($val);
    }

    /**
     * {@inheritDoc}
     */
    public function getKezdosorszam()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getKezdosorszam', []);

        return parent::getKezdosorszam();
    }

    /**
     * {@inheritDoc}
     */
    public function setKezdosorszam($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setKezdosorszam', [$val]);

        return parent::setKezdosorszam($val);
    }

    /**
     * {@inheritDoc}
     */
    public function getPeldanyszam()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPeldanyszam', []);

        return parent::getPeldanyszam();
    }

    /**
     * {@inheritDoc}
     */
    public function setPeldanyszam($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPeldanyszam', [$val]);

        return parent::setPeldanyszam($val);
    }

    /**
     * {@inheritDoc}
     */
    public function getMozgat()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMozgat', []);

        return parent::getMozgat();
    }

    /**
     * {@inheritDoc}
     */
    public function setMozgat($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMozgat', [$val]);

        return parent::setMozgat($val);
    }

    /**
     * {@inheritDoc}
     */
    public function getPenztmozgat()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPenztmozgat', []);

        return parent::getPenztmozgat();
    }

    /**
     * {@inheritDoc}
     */
    public function setPenztmozgat($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPenztmozgat', [$val]);

        return parent::setPenztmozgat($val);
    }

    /**
     * {@inheritDoc}
     */
    public function getEditprinted()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getEditprinted', []);

        return parent::getEditprinted();
    }

    /**
     * {@inheritDoc}
     */
    public function setEditprinted($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setEditprinted', [$val]);

        return parent::setEditprinted($val);
    }

    /**
     * {@inheritDoc}
     */
    public function getShowteljesites()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getShowteljesites', []);

        return parent::getShowteljesites();
    }

    /**
     * {@inheritDoc}
     */
    public function setShowteljesites($show)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setShowteljesites', [$show]);

        return parent::setShowteljesites($show);
    }

    /**
     * {@inheritDoc}
     */
    public function getShowesedekesseg()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getShowesedekesseg', []);

        return parent::getShowesedekesseg();
    }

    /**
     * {@inheritDoc}
     */
    public function setShowesedekesseg($show)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setShowesedekesseg', [$show]);

        return parent::setShowesedekesseg($show);
    }

    /**
     * {@inheritDoc}
     */
    public function getShowhatarido()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getShowhatarido', []);

        return parent::getShowhatarido();
    }

    /**
     * {@inheritDoc}
     */
    public function setShowhatarido($show)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setShowhatarido', [$show]);

        return parent::setShowhatarido($show);
    }

    /**
     * {@inheritDoc}
     */
    public function getShowvalutanem()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getShowvalutanem', []);

        return parent::getShowvalutanem();
    }

    /**
     * {@inheritDoc}
     */
    public function getTplname()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTplname', []);

        return parent::getTplname();
    }

    /**
     * {@inheritDoc}
     */
    public function setTplname($d)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTplname', [$d]);

        return parent::setTplname($d);
    }

    /**
     * {@inheritDoc}
     */
    public function getShowbizonylatstatuszeditor()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getShowbizonylatstatuszeditor', []);

        return parent::getShowbizonylatstatuszeditor();
    }

    /**
     * {@inheritDoc}
     */
    public function setShowbizonylatstatuszeditor($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setShowbizonylatstatuszeditor', [$val]);

        return parent::setShowbizonylatstatuszeditor($val);
    }

    /**
     * {@inheritDoc}
     */
    public function getShowszamlabutton()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getShowszamlabutton', []);

        return parent::getShowszamlabutton();
    }

    /**
     * {@inheritDoc}
     */
    public function setShowszamlabutton($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setShowszamlabutton', [$val]);

        return parent::setShowszamlabutton($val);
    }

    /**
     * {@inheritDoc}
     */
    public function getShowkeziszamlabutton()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getShowkeziszamlabutton', []);

        return parent::getShowkeziszamlabutton();
    }

    /**
     * {@inheritDoc}
     */
    public function setShowkeziszamlabutton($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setShowkeziszamlabutton', [$val]);

        return parent::setShowkeziszamlabutton($val);
    }

    /**
     * {@inheritDoc}
     */
    public function getShowkivetbutton()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getShowkivetbutton', []);

        return parent::getShowkivetbutton();
    }

    /**
     * {@inheritDoc}
     */
    public function setShowkivetbutton($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setShowkivetbutton', [$val]);

        return parent::setShowkivetbutton($val);
    }

    /**
     * {@inheritDoc}
     */
    public function getShowbevetbutton()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getShowbevetbutton', []);

        return parent::getShowbevetbutton();
    }

    /**
     * {@inheritDoc}
     */
    public function setShowbevetbutton($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setShowbevetbutton', [$val]);

        return parent::setShowbevetbutton($val);
    }

    /**
     * {@inheritDoc}
     */
    public function getShowszallitobutton()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getShowszallitobutton', []);

        return parent::getShowszallitobutton();
    }

    /**
     * {@inheritDoc}
     */
    public function setShowszallitobtn($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setShowszallitobtn', [$val]);

        return parent::setShowszallitobtn($val);
    }

    /**
     * {@inheritDoc}
     */
    public function getShowuzenet()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getShowuzenet', []);

        return parent::getShowuzenet();
    }

    /**
     * {@inheritDoc}
     */
    public function setShowuzenet($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setShowuzenet', [$val]);

        return parent::setShowuzenet($val);
    }

    /**
     * {@inheritDoc}
     */
    public function getShowszallitasicim()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getShowszallitasicim', []);

        return parent::getShowszallitasicim();
    }

    /**
     * {@inheritDoc}
     */
    public function setShowszallitasicim($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setShowszallitasicim', [$val]);

        return parent::setShowszallitasicim($val);
    }

    /**
     * {@inheritDoc}
     */
    public function getShowerbizonylatszam()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getShowerbizonylatszam', []);

        return parent::getShowerbizonylatszam();
    }

    /**
     * {@inheritDoc}
     */
    public function setShowerbizonylatszam($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setShowerbizonylatszam', [$val]);

        return parent::setShowerbizonylatszam($val);
    }

    /**
     * {@inheritDoc}
     */
    public function getShowfuvarlevelszam()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getShowfuvarlevelszam', []);

        return parent::getShowfuvarlevelszam();
    }

    /**
     * {@inheritDoc}
     */
    public function setShowfuvarlevelszam($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setShowfuvarlevelszam', [$val]);

        return parent::setShowfuvarlevelszam($val);
    }

    /**
     * {@inheritDoc}
     */
    public function getShowhaszonszazalek()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getShowhaszonszazalek', []);

        return parent::getShowhaszonszazalek();
    }

    /**
     * {@inheritDoc}
     */
    public function setShowhaszonszazalek($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setShowhaszonszazalek', [$val]);

        return parent::setShowhaszonszazalek($val);
    }

    /**
     * {@inheritDoc}
     */
    public function getShowstorno()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getShowstorno', []);

        return parent::getShowstorno();
    }

    /**
     * {@inheritDoc}
     */
    public function setShowstorno($adat)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setShowstorno', [$adat]);

        return parent::setShowstorno($adat);
    }

    /**
     * {@inheritDoc}
     */
    public function getShowbackorder()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getShowbackorder', []);

        return parent::getShowbackorder();
    }

    /**
     * {@inheritDoc}
     */
    public function setShowbackorder($adat)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setShowbackorder', [$adat]);

        return parent::setShowbackorder($adat);
    }

    /**
     * {@inheritDoc}
     */
    public function getFoglal()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getFoglal', []);

        return parent::getFoglal();
    }

    /**
     * {@inheritDoc}
     */
    public function setFoglal($adat)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setFoglal', [$adat]);

        return parent::setFoglal($adat);
    }

    /**
     * {@inheritDoc}
     */
    public function getShowmesebutton()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getShowmesebutton', []);

        return parent::getShowmesebutton();
    }

    /**
     * {@inheritDoc}
     */
    public function setShowmesebutton($showmesebutton)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setShowmesebutton', [$showmesebutton]);

        return parent::setShowmesebutton($showmesebutton);
    }

    /**
     * {@inheritDoc}
     */
    public function getShowcsomagbutton()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getShowcsomagbutton', []);

        return parent::getShowcsomagbutton();
    }

    /**
     * {@inheritDoc}
     */
    public function setShowcsomagbutton($showcsomagbutton)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setShowcsomagbutton', [$showcsomagbutton]);

        return parent::setShowcsomagbutton($showcsomagbutton);
    }

    /**
     * {@inheritDoc}
     */
    public function getShowfeketelistabutton()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getShowfeketelistabutton', []);

        return parent::getShowfeketelistabutton();
    }

    /**
     * {@inheritDoc}
     */
    public function setShowfeketelistabutton($showfeketelistabutton)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setShowfeketelistabutton', [$showfeketelistabutton]);

        return parent::setShowfeketelistabutton($showfeketelistabutton);
    }

    /**
     * {@inheritDoc}
     */
    public function getShowkupon()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getShowkupon', []);

        return parent::getShowkupon();
    }

    /**
     * {@inheritDoc}
     */
    public function setShowkupon($showkupon)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setShowkupon', [$showkupon]);

        return parent::setShowkupon($showkupon);
    }

    /**
     * {@inheritDoc}
     */
    public function getShowfoxpostterminaleditor()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getShowfoxpostterminaleditor', []);

        return parent::getShowfoxpostterminaleditor();
    }

    /**
     * {@inheritDoc}
     */
    public function setShowfoxpostterminaleditor($showfoxpostterminaleditor)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setShowfoxpostterminaleditor', [$showfoxpostterminaleditor]);

        return parent::setShowfoxpostterminaleditor($showfoxpostterminaleditor);
    }

    /**
     * {@inheritDoc}
     */
    public function getShowfelhasznalo()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getShowfelhasznalo', []);

        return parent::getShowfelhasznalo();
    }

    /**
     * {@inheritDoc}
     */
    public function setShowfelhasznalo($showfelhasznalo)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setShowfelhasznalo', [$showfelhasznalo]);

        return parent::setShowfelhasznalo($showfelhasznalo);
    }

    /**
     * {@inheritDoc}
     */
    public function getCheckkelt()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCheckkelt', []);

        return parent::getCheckkelt();
    }

    /**
     * {@inheritDoc}
     */
    public function setCheckkelt($checkkelt)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCheckkelt', [$checkkelt]);

        return parent::setCheckkelt($checkkelt);
    }

    /**
     * {@inheritDoc}
     */
    public function getShowpdf()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getShowpdf', []);

        return parent::getShowpdf();
    }

    /**
     * {@inheritDoc}
     */
    public function setShowpdf($showpdf)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setShowpdf', [$showpdf]);

        return parent::setShowpdf($showpdf);
    }

    /**
     * {@inheritDoc}
     */
    public function getNavbekuldendo()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNavbekuldendo', []);

        return parent::getNavbekuldendo();
    }

    /**
     * {@inheritDoc}
     */
    public function setNavbekuldendo($navbekuldendo)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setNavbekuldendo', [$navbekuldendo]);

        return parent::setNavbekuldendo($navbekuldendo);
    }

    /**
     * {@inheritDoc}
     */
    public function getShowemailbutton()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getShowemailbutton', []);

        return parent::getShowemailbutton();
    }

    /**
     * {@inheritDoc}
     */
    public function setShowemailbutton($showemailbutton): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setShowemailbutton', [$showemailbutton]);

        parent::setShowemailbutton($showemailbutton);
    }

}
