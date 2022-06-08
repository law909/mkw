<?php

namespace Proxies\__CG__\Entities;


/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class Rendezveny extends \Entities\Rendezveny implements \Doctrine\ORM\Proxy\Proxy
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
            return ['__isInitialized__', '' . "\0" . 'Entities\\Rendezveny' . "\0" . 'id', '' . "\0" . 'Entities\\Rendezveny' . "\0" . 'created', '' . "\0" . 'Entities\\Rendezveny' . "\0" . 'lastmod', '' . "\0" . 'Entities\\Rendezveny' . "\0" . 'createdby', '' . "\0" . 'Entities\\Rendezveny' . "\0" . 'updatedby', '' . "\0" . 'Entities\\Rendezveny' . "\0" . 'kezdodatum', '' . "\0" . 'Entities\\Rendezveny' . "\0" . 'kezdoido', '' . "\0" . 'Entities\\Rendezveny' . "\0" . 'nev', '' . "\0" . 'Entities\\Rendezveny' . "\0" . 'tanar', '' . "\0" . 'Entities\\Rendezveny' . "\0" . 'termek', '' . "\0" . 'Entities\\Rendezveny' . "\0" . 'ar', '' . "\0" . 'Entities\\Rendezveny' . "\0" . 'rendezvenyallapot', '' . "\0" . 'Entities\\Rendezveny' . "\0" . 'jogaterem', '' . "\0" . 'Entities\\Rendezveny' . "\0" . 'helyszin', '' . "\0" . 'Entities\\Rendezveny' . "\0" . 'todonaptar', '' . "\0" . 'Entities\\Rendezveny' . "\0" . 'todowebposzt', '' . "\0" . 'Entities\\Rendezveny' . "\0" . 'todourlap', '' . "\0" . 'Entities\\Rendezveny' . "\0" . 'todowebslider', '' . "\0" . 'Entities\\Rendezveny' . "\0" . 'todofbevent', '' . "\0" . 'Entities\\Rendezveny' . "\0" . 'todofbhirdetes', '' . "\0" . 'Entities\\Rendezveny' . "\0" . 'todoplakat', '' . "\0" . 'Entities\\Rendezveny' . "\0" . 'todofotobe', '' . "\0" . 'Entities\\Rendezveny' . "\0" . 'todoleirasbe', '' . "\0" . 'Entities\\Rendezveny' . "\0" . 'rendezvenydokok', '' . "\0" . 'Entities\\Rendezveny' . "\0" . 'uid', '' . "\0" . 'Entities\\Rendezveny' . "\0" . 'kellszamlazasiadat', '' . "\0" . 'Entities\\Rendezveny' . "\0" . 'orarendbenszerepel', '' . "\0" . 'Entities\\Rendezveny' . "\0" . 'url', '' . "\0" . 'Entities\\Rendezveny' . "\0" . 'onlineurl'];
        }

        return ['__isInitialized__', '' . "\0" . 'Entities\\Rendezveny' . "\0" . 'id', '' . "\0" . 'Entities\\Rendezveny' . "\0" . 'created', '' . "\0" . 'Entities\\Rendezveny' . "\0" . 'lastmod', '' . "\0" . 'Entities\\Rendezveny' . "\0" . 'createdby', '' . "\0" . 'Entities\\Rendezveny' . "\0" . 'updatedby', '' . "\0" . 'Entities\\Rendezveny' . "\0" . 'kezdodatum', '' . "\0" . 'Entities\\Rendezveny' . "\0" . 'kezdoido', '' . "\0" . 'Entities\\Rendezveny' . "\0" . 'nev', '' . "\0" . 'Entities\\Rendezveny' . "\0" . 'tanar', '' . "\0" . 'Entities\\Rendezveny' . "\0" . 'termek', '' . "\0" . 'Entities\\Rendezveny' . "\0" . 'ar', '' . "\0" . 'Entities\\Rendezveny' . "\0" . 'rendezvenyallapot', '' . "\0" . 'Entities\\Rendezveny' . "\0" . 'jogaterem', '' . "\0" . 'Entities\\Rendezveny' . "\0" . 'helyszin', '' . "\0" . 'Entities\\Rendezveny' . "\0" . 'todonaptar', '' . "\0" . 'Entities\\Rendezveny' . "\0" . 'todowebposzt', '' . "\0" . 'Entities\\Rendezveny' . "\0" . 'todourlap', '' . "\0" . 'Entities\\Rendezveny' . "\0" . 'todowebslider', '' . "\0" . 'Entities\\Rendezveny' . "\0" . 'todofbevent', '' . "\0" . 'Entities\\Rendezveny' . "\0" . 'todofbhirdetes', '' . "\0" . 'Entities\\Rendezveny' . "\0" . 'todoplakat', '' . "\0" . 'Entities\\Rendezveny' . "\0" . 'todofotobe', '' . "\0" . 'Entities\\Rendezveny' . "\0" . 'todoleirasbe', '' . "\0" . 'Entities\\Rendezveny' . "\0" . 'rendezvenydokok', '' . "\0" . 'Entities\\Rendezveny' . "\0" . 'uid', '' . "\0" . 'Entities\\Rendezveny' . "\0" . 'kellszamlazasiadat', '' . "\0" . 'Entities\\Rendezveny' . "\0" . 'orarendbenszerepel', '' . "\0" . 'Entities\\Rendezveny' . "\0" . 'url', '' . "\0" . 'Entities\\Rendezveny' . "\0" . 'onlineurl'];
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (Rendezveny $proxy) {
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
    public function generateUId()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'generateUId', []);

        return parent::generateUId();
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
    public function getUid()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getUid', []);

        return parent::getUid();
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
    public function getTeljesNev()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTeljesNev', []);

        return parent::getTeljesNev();
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
    public function getKezdodatum()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getKezdodatum', []);

        return parent::getKezdodatum();
    }

    /**
     * {@inheritDoc}
     */
    public function getKezdodatumStr()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getKezdodatumStr', []);

        return parent::getKezdodatumStr();
    }

    /**
     * {@inheritDoc}
     */
    public function setKezdodatum($adat = '')
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setKezdodatum', [$adat]);

        return parent::setKezdodatum($adat);
    }

    /**
     * {@inheritDoc}
     */
    public function getNap()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNap', []);

        return parent::getNap();
    }

    /**
     * {@inheritDoc}
     */
    public function getTanar()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTanar', []);

        return parent::getTanar();
    }

    /**
     * {@inheritDoc}
     */
    public function getTanarNev()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTanarNev', []);

        return parent::getTanarNev();
    }

    /**
     * {@inheritDoc}
     */
    public function getTanarId()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTanarId', []);

        return parent::getTanarId();
    }

    /**
     * {@inheritDoc}
     */
    public function getTanarUrl()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTanarUrl', []);

        return parent::getTanarUrl();
    }

    /**
     * {@inheritDoc}
     */
    public function setTanar($tanar)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTanar', [$tanar]);

        return parent::setTanar($tanar);
    }

    /**
     * {@inheritDoc}
     */
    public function getTermek()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTermek', []);

        return parent::getTermek();
    }

    /**
     * {@inheritDoc}
     */
    public function getTermekId()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTermekId', []);

        return parent::getTermekId();
    }

    /**
     * {@inheritDoc}
     */
    public function getTermekNev()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTermekNev', []);

        return parent::getTermekNev();
    }

    /**
     * {@inheritDoc}
     */
    public function setTermek($val)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTermek', [$val]);

        return parent::setTermek($val);
    }

    /**
     * {@inheritDoc}
     */
    public function removeTermek()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeTermek', []);

        return parent::removeTermek();
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
    public function getRendezvenyallapot()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRendezvenyallapot', []);

        return parent::getRendezvenyallapot();
    }

    /**
     * {@inheritDoc}
     */
    public function getRendezvenyallapotNev()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRendezvenyallapotNev', []);

        return parent::getRendezvenyallapotNev();
    }

    /**
     * {@inheritDoc}
     */
    public function getRendezvenyallapotId()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRendezvenyallapotId', []);

        return parent::getRendezvenyallapotId();
    }

    /**
     * {@inheritDoc}
     */
    public function setRendezvenyallapot($ra)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setRendezvenyallapot', [$ra]);

        return parent::setRendezvenyallapot($ra);
    }

    /**
     * {@inheritDoc}
     */
    public function getJogaterem()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getJogaterem', []);

        return parent::getJogaterem();
    }

    /**
     * {@inheritDoc}
     */
    public function getJogateremNev()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getJogateremNev', []);

        return parent::getJogateremNev();
    }

    /**
     * {@inheritDoc}
     */
    public function getJogateremOrarendclass()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getJogateremOrarendclass', []);

        return parent::getJogateremOrarendclass();
    }

    /**
     * {@inheritDoc}
     */
    public function getJogateremId()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getJogateremId', []);

        return parent::getJogateremId();
    }

    /**
     * {@inheritDoc}
     */
    public function setJogaterem($ra)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setJogaterem', [$ra]);

        return parent::setJogaterem($ra);
    }

    /**
     * {@inheritDoc}
     */
    public function getHelyszin()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getHelyszin', []);

        return parent::getHelyszin();
    }

    /**
     * {@inheritDoc}
     */
    public function getHelyszinNev()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getHelyszinNev', []);

        return parent::getHelyszinNev();
    }

    /**
     * {@inheritDoc}
     */
    public function getHelyszinId()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getHelyszinId', []);

        return parent::getHelyszinId();
    }

    /**
     * {@inheritDoc}
     */
    public function setHelyszin($ra)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setHelyszin', [$ra]);

        return parent::setHelyszin($ra);
    }

    /**
     * {@inheritDoc}
     */
    public function getTodonaptar()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTodonaptar', []);

        return parent::getTodonaptar();
    }

    /**
     * {@inheritDoc}
     */
    public function setTodonaptar($todonaptar)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTodonaptar', [$todonaptar]);

        return parent::setTodonaptar($todonaptar);
    }

    /**
     * {@inheritDoc}
     */
    public function getTodowebposzt()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTodowebposzt', []);

        return parent::getTodowebposzt();
    }

    /**
     * {@inheritDoc}
     */
    public function setTodowebposzt($todowebposzt)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTodowebposzt', [$todowebposzt]);

        return parent::setTodowebposzt($todowebposzt);
    }

    /**
     * {@inheritDoc}
     */
    public function getTodourlap()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTodourlap', []);

        return parent::getTodourlap();
    }

    /**
     * {@inheritDoc}
     */
    public function setTodourlap($todourlap)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTodourlap', [$todourlap]);

        return parent::setTodourlap($todourlap);
    }

    /**
     * {@inheritDoc}
     */
    public function getTodowebslider()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTodowebslider', []);

        return parent::getTodowebslider();
    }

    /**
     * {@inheritDoc}
     */
    public function setTodowebslider($todowebslider)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTodowebslider', [$todowebslider]);

        return parent::setTodowebslider($todowebslider);
    }

    /**
     * {@inheritDoc}
     */
    public function getTodofbevent()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTodofbevent', []);

        return parent::getTodofbevent();
    }

    /**
     * {@inheritDoc}
     */
    public function setTodofbevent($todofbevent)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTodofbevent', [$todofbevent]);

        return parent::setTodofbevent($todofbevent);
    }

    /**
     * {@inheritDoc}
     */
    public function getTodofbhirdetes()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTodofbhirdetes', []);

        return parent::getTodofbhirdetes();
    }

    /**
     * {@inheritDoc}
     */
    public function setTodofbhirdetes($todofbhirdetes)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTodofbhirdetes', [$todofbhirdetes]);

        return parent::setTodofbhirdetes($todofbhirdetes);
    }

    /**
     * {@inheritDoc}
     */
    public function getTodoplakat()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTodoplakat', []);

        return parent::getTodoplakat();
    }

    /**
     * {@inheritDoc}
     */
    public function setTodoplakat($todoplakat)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTodoplakat', [$todoplakat]);

        return parent::setTodoplakat($todoplakat);
    }

    /**
     * {@inheritDoc}
     */
    public function getTodofotobe()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTodofotobe', []);

        return parent::getTodofotobe();
    }

    /**
     * {@inheritDoc}
     */
    public function setTodofotobe($todofotobe)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTodofotobe', [$todofotobe]);

        return parent::setTodofotobe($todofotobe);
    }

    /**
     * {@inheritDoc}
     */
    public function getTodoleirasbe()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTodoleirasbe', []);

        return parent::getTodoleirasbe();
    }

    /**
     * {@inheritDoc}
     */
    public function setTodoleirasbe($todoleirasbe)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTodoleirasbe', [$todoleirasbe]);

        return parent::setTodoleirasbe($todoleirasbe);
    }

    /**
     * {@inheritDoc}
     */
    public function getRendezvenyDokok()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRendezvenyDokok', []);

        return parent::getRendezvenyDokok();
    }

    /**
     * {@inheritDoc}
     */
    public function addRendezvenyDok(\Entities\RendezvenyDok $dok)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addRendezvenyDok', [$dok]);

        return parent::addRendezvenyDok($dok);
    }

    /**
     * {@inheritDoc}
     */
    public function removeRendezvenyDok(\Entities\RendezvenyDok $dok)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeRendezvenyDok', [$dok]);

        return parent::removeRendezvenyDok($dok);
    }

    /**
     * {@inheritDoc}
     */
    public function getKezdoido()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getKezdoido', []);

        return parent::getKezdoido();
    }

    /**
     * {@inheritDoc}
     */
    public function setKezdoido($kezdoido)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setKezdoido', [$kezdoido]);

        return parent::setKezdoido($kezdoido);
    }

    /**
     * {@inheritDoc}
     */
    public function getAr()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getAr', []);

        return parent::getAr();
    }

    /**
     * {@inheritDoc}
     */
    public function setAr($ar)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setAr', [$ar]);

        return parent::setAr($ar);
    }

    /**
     * {@inheritDoc}
     */
    public function getKellszamlazasiadat()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getKellszamlazasiadat', []);

        return parent::getKellszamlazasiadat();
    }

    /**
     * {@inheritDoc}
     */
    public function setKellszamlazasiadat($kellszamlazasiadat)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setKellszamlazasiadat', [$kellszamlazasiadat]);

        return parent::setKellszamlazasiadat($kellszamlazasiadat);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrarendbenszerepel()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getOrarendbenszerepel', []);

        return parent::getOrarendbenszerepel();
    }

    /**
     * {@inheritDoc}
     */
    public function setOrarendbenszerepel($orarendbenszerepel)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setOrarendbenszerepel', [$orarendbenszerepel]);

        return parent::setOrarendbenszerepel($orarendbenszerepel);
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
    public function getOnlineurl()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getOnlineurl', []);

        return parent::getOnlineurl();
    }

    /**
     * {@inheritDoc}
     */
    public function setOnlineurl($onlineurl)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setOnlineurl', [$onlineurl]);

        return parent::setOnlineurl($onlineurl);
    }

}
