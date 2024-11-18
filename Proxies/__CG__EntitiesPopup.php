<?php

namespace Proxies\__CG__\Entities;


/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class Popup extends \Entities\Popup implements \Doctrine\ORM\Proxy\Proxy
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
            return ['__isInitialized__', '' . "\0" . 'Entities\\Popup' . "\0" . 'id', '' . "\0" . 'Entities\\Popup' . "\0" . 'nev', '' . "\0" . 'Entities\\Popup' . "\0" . 'displaytime', '' . "\0" . 'Entities\\Popup' . "\0" . 'backgroundimageurl', '' . "\0" . 'Entities\\Popup' . "\0" . 'overlaybackgroundcolor', '' . "\0" . 'Entities\\Popup' . "\0" . 'overlayopacity', '' . "\0" . 'Entities\\Popup' . "\0" . 'headertext', '' . "\0" . 'Entities\\Popup' . "\0" . 'bodytext', '' . "\0" . 'Entities\\Popup' . "\0" . 'closebuttontext', '' . "\0" . 'Entities\\Popup' . "\0" . 'popuporder', '' . "\0" . 'Entities\\Popup' . "\0" . 'triggerafterprevious', '' . "\0" . 'Entities\\Popup' . "\0" . 'inaktiv', '' . "\0" . 'Entities\\Popup' . "\0" . 'contentwidth', '' . "\0" . 'Entities\\Popup' . "\0" . 'contentheight', '' . "\0" . 'Entities\\Popup' . "\0" . 'closebuttoncolor', '' . "\0" . 'Entities\\Popup' . "\0" . 'closebuttonbackgroundcolor', '' . "\0" . 'Entities\\Popup' . "\0" . 'contenttop'];
        }

        return ['__isInitialized__', '' . "\0" . 'Entities\\Popup' . "\0" . 'id', '' . "\0" . 'Entities\\Popup' . "\0" . 'nev', '' . "\0" . 'Entities\\Popup' . "\0" . 'displaytime', '' . "\0" . 'Entities\\Popup' . "\0" . 'backgroundimageurl', '' . "\0" . 'Entities\\Popup' . "\0" . 'overlaybackgroundcolor', '' . "\0" . 'Entities\\Popup' . "\0" . 'overlayopacity', '' . "\0" . 'Entities\\Popup' . "\0" . 'headertext', '' . "\0" . 'Entities\\Popup' . "\0" . 'bodytext', '' . "\0" . 'Entities\\Popup' . "\0" . 'closebuttontext', '' . "\0" . 'Entities\\Popup' . "\0" . 'popuporder', '' . "\0" . 'Entities\\Popup' . "\0" . 'triggerafterprevious', '' . "\0" . 'Entities\\Popup' . "\0" . 'inaktiv', '' . "\0" . 'Entities\\Popup' . "\0" . 'contentwidth', '' . "\0" . 'Entities\\Popup' . "\0" . 'contentheight', '' . "\0" . 'Entities\\Popup' . "\0" . 'closebuttoncolor', '' . "\0" . 'Entities\\Popup' . "\0" . 'closebuttonbackgroundcolor', '' . "\0" . 'Entities\\Popup' . "\0" . 'contenttop'];
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (Popup $proxy) {
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
    public function setNev($nev): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setNev', [$nev]);

        parent::setNev($nev);
    }

    /**
     * {@inheritDoc}
     */
    public function getDisplaytime()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDisplaytime', []);

        return parent::getDisplaytime();
    }

    /**
     * {@inheritDoc}
     */
    public function setDisplaytime($displaytime): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDisplaytime', [$displaytime]);

        parent::setDisplaytime($displaytime);
    }

    /**
     * {@inheritDoc}
     */
    public function getBackgroundimageurl()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getBackgroundimageurl', []);

        return parent::getBackgroundimageurl();
    }

    /**
     * {@inheritDoc}
     */
    public function setBackgroundimageurl($backgroundimageurl): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setBackgroundimageurl', [$backgroundimageurl]);

        parent::setBackgroundimageurl($backgroundimageurl);
    }

    /**
     * {@inheritDoc}
     */
    public function getOverlaybackgroundcolor()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getOverlaybackgroundcolor', []);

        return parent::getOverlaybackgroundcolor();
    }

    /**
     * {@inheritDoc}
     */
    public function setOverlaybackgroundcolor($overlaybackgroundcolor): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setOverlaybackgroundcolor', [$overlaybackgroundcolor]);

        parent::setOverlaybackgroundcolor($overlaybackgroundcolor);
    }

    /**
     * {@inheritDoc}
     */
    public function getOverlayopacity()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getOverlayopacity', []);

        return parent::getOverlayopacity();
    }

    /**
     * {@inheritDoc}
     */
    public function setOverlayopacity($overlayopacity): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setOverlayopacity', [$overlayopacity]);

        parent::setOverlayopacity($overlayopacity);
    }

    /**
     * {@inheritDoc}
     */
    public function getHeadertext()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getHeadertext', []);

        return parent::getHeadertext();
    }

    /**
     * {@inheritDoc}
     */
    public function setHeadertext($headertext): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setHeadertext', [$headertext]);

        parent::setHeadertext($headertext);
    }

    /**
     * {@inheritDoc}
     */
    public function getBodytext()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getBodytext', []);

        return parent::getBodytext();
    }

    /**
     * {@inheritDoc}
     */
    public function setBodytext($bodytext): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setBodytext', [$bodytext]);

        parent::setBodytext($bodytext);
    }

    /**
     * {@inheritDoc}
     */
    public function getClosebuttontext()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getClosebuttontext', []);

        return parent::getClosebuttontext();
    }

    /**
     * {@inheritDoc}
     */
    public function setClosebuttontext($closebuttontext): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setClosebuttontext', [$closebuttontext]);

        parent::setClosebuttontext($closebuttontext);
    }

    /**
     * {@inheritDoc}
     */
    public function getPopuporder()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPopuporder', []);

        return parent::getPopuporder();
    }

    /**
     * {@inheritDoc}
     */
    public function setPopuporder($popuporder): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPopuporder', [$popuporder]);

        parent::setPopuporder($popuporder);
    }

    /**
     * {@inheritDoc}
     */
    public function isTriggerafterprevious()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'isTriggerafterprevious', []);

        return parent::isTriggerafterprevious();
    }

    /**
     * {@inheritDoc}
     */
    public function setTriggerafterprevious($triggerafterprevious): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTriggerafterprevious', [$triggerafterprevious]);

        parent::setTriggerafterprevious($triggerafterprevious);
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
    public function setInaktiv($inaktiv): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setInaktiv', [$inaktiv]);

        parent::setInaktiv($inaktiv);
    }

    /**
     * {@inheritDoc}
     */
    public function getClosebuttoncolor()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getClosebuttoncolor', []);

        return parent::getClosebuttoncolor();
    }

    /**
     * {@inheritDoc}
     */
    public function setClosebuttoncolor($closebuttoncolor): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setClosebuttoncolor', [$closebuttoncolor]);

        parent::setClosebuttoncolor($closebuttoncolor);
    }

    /**
     * {@inheritDoc}
     */
    public function getClosebuttonbackgroundcolor()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getClosebuttonbackgroundcolor', []);

        return parent::getClosebuttonbackgroundcolor();
    }

    /**
     * {@inheritDoc}
     */
    public function setClosebuttonbackgroundcolor($closebuttonbackgroundcolor): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setClosebuttonbackgroundcolor', [$closebuttonbackgroundcolor]);

        parent::setClosebuttonbackgroundcolor($closebuttonbackgroundcolor);
    }

    /**
     * {@inheritDoc}
     */
    public function getContenttop()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getContenttop', []);

        return parent::getContenttop();
    }

    /**
     * {@inheritDoc}
     */
    public function setContenttop($contenttop): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setContenttop', [$contenttop]);

        parent::setContenttop($contenttop);
    }

    /**
     * {@inheritDoc}
     */
    public function getContentwidth()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getContentwidth', []);

        return parent::getContentwidth();
    }

    /**
     * {@inheritDoc}
     */
    public function setContentwidth($contentwidth): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setContentwidth', [$contentwidth]);

        parent::setContentwidth($contentwidth);
    }

    /**
     * {@inheritDoc}
     */
    public function getContentheight()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getContentheight', []);

        return parent::getContentheight();
    }

    /**
     * {@inheritDoc}
     */
    public function setContentheight($contentheight): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setContentheight', [$contentheight]);

        parent::setContentheight($contentheight);
    }

}