<?php

namespace Proxies;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class EntitiesBizonylatfejProxy extends \Entities\Bizonylatfej implements \Doctrine\ORM\Proxy\Proxy
{
    private $_entityPersister;
    private $_identifier;
    public $__isInitialized__ = false;
    public function __construct($entityPersister, $identifier)
    {
        $this->_entityPersister = $entityPersister;
        $this->_identifier = $identifier;
    }
    /** @private */
    public function __load()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;

            if (method_exists($this, "__wakeup")) {
                // call this after __isInitialized__to avoid infinite recursion
                // but before loading to emulate what ClassMetadata::newInstance()
                // provides.
                $this->__wakeup();
            }

            if ($this->_entityPersister->load($this->_identifier, $this) === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            unset($this->_entityPersister, $this->_identifier);
        }
    }
    
    
    public function doStuffOnPrePersist()
    {
        $this->__load();
        return parent::doStuffOnPrePersist();
    }

    public function sendStatuszEmail($emailtpl, $bf = NULL)
    {
        $this->__load();
        return parent::sendStatuszEmail($emailtpl, $bf);
    }

    public function toLista()
    {
        $this->__load();
        return parent::toLista();
    }

    public function setPersistentData()
    {
        $this->__load();
        return parent::setPersistentData();
    }

    public function getId()
    {
        $this->__load();
        return parent::getId();
    }

    public function generateId()
    {
        $this->__load();
        return parent::generateId();
    }

    public function getBizonylattetelek()
    {
        $this->__load();
        return parent::getBizonylattetelek();
    }

    public function addBizonylattetel(\Entities\Bizonylattetel $val)
    {
        $this->__load();
        return parent::addBizonylattetel($val);
    }

    public function removeBizonylattetel(\Entities\Bizonylattetel $val)
    {
        $this->__load();
        return parent::removeBizonylattetel($val);
    }

    public function getIrany()
    {
        $this->__load();
        return parent::getIrany();
    }

    public function setIrany($val)
    {
        $this->__load();
        return parent::setIrany($val);
    }

    public function getBizonylattipus()
    {
        $this->__load();
        return parent::getBizonylattipus();
    }

    public function getBizonylattipusId()
    {
        $this->__load();
        return parent::getBizonylattipusId();
    }

    public function setBizonylattipus($val)
    {
        $this->__load();
        return parent::setBizonylattipus($val);
    }

    public function removeBizonylattipus()
    {
        $this->__load();
        return parent::removeBizonylattipus();
    }

    public function getBizonylatnev()
    {
        $this->__load();
        return parent::getBizonylatnev();
    }

    public function setBizonylatnev($val)
    {
        $this->__load();
        return parent::setBizonylatnev($val);
    }

    public function getNyomtatva()
    {
        $this->__load();
        return parent::getNyomtatva();
    }

    public function setNyomtatva($val)
    {
        $this->__load();
        return parent::setNyomtatva($val);
    }

    public function getStorno()
    {
        $this->__load();
        return parent::getStorno();
    }

    public function setStorno($val)
    {
        $this->__load();
        return parent::setStorno($val);
    }

    public function getStornozott()
    {
        $this->__load();
        return parent::getStornozott();
    }

    public function setStornozott($val)
    {
        $this->__load();
        return parent::setStornozott($val);
    }

    public function getMozgat()
    {
        $this->__load();
        return parent::getMozgat();
    }

    public function getPenztmozgat()
    {
        $this->__load();
        return parent::getPenztmozgat();
    }

    public function setPenztmozgat($val)
    {
        $this->__load();
        return parent::setPenztmozgat($val);
    }

    public function getTulajnev()
    {
        $this->__load();
        return parent::getTulajnev();
    }

    public function setTulajnev($val)
    {
        $this->__load();
        return parent::setTulajnev($val);
    }

    public function getTulajirszam()
    {
        $this->__load();
        return parent::getTulajirszam();
    }

    public function setTulajirszam($val)
    {
        $this->__load();
        return parent::setTulajirszam($val);
    }

    public function getTulajvaros()
    {
        $this->__load();
        return parent::getTulajvaros();
    }

    public function setTulajvaros($val)
    {
        $this->__load();
        return parent::setTulajvaros($val);
    }

    public function getTulajutca()
    {
        $this->__load();
        return parent::getTulajutca();
    }

    public function setTulajutca($val)
    {
        $this->__load();
        return parent::setTulajutca($val);
    }

    public function getTulajadoszam()
    {
        $this->__load();
        return parent::getTulajadoszam();
    }

    public function setTulajadoszam($val)
    {
        $this->__load();
        return parent::setTulajadoszam($val);
    }

    public function getTulajeuadoszam()
    {
        $this->__load();
        return parent::getTulajeuadoszam();
    }

    public function setTulajeuadoszam($val)
    {
        $this->__load();
        return parent::setTulajeuadoszam($val);
    }

    public function getKelt()
    {
        $this->__load();
        return parent::getKelt();
    }

    public function getKeltStr()
    {
        $this->__load();
        return parent::getKeltStr();
    }

    public function setKelt($adat)
    {
        $this->__load();
        return parent::setKelt($adat);
    }

    public function getTeljesites()
    {
        $this->__load();
        return parent::getTeljesites();
    }

    public function getTeljesitesStr()
    {
        $this->__load();
        return parent::getTeljesitesStr();
    }

    public function setTeljesites($adat)
    {
        $this->__load();
        return parent::setTeljesites($adat);
    }

    public function getEsedekesseg()
    {
        $this->__load();
        return parent::getEsedekesseg();
    }

    public function getEsedekessegStr()
    {
        $this->__load();
        return parent::getEsedekessegStr();
    }

    public function setEsedekesseg($adat)
    {
        $this->__load();
        return parent::setEsedekesseg($adat);
    }

    public function getHatarido()
    {
        $this->__load();
        return parent::getHatarido();
    }

    public function getHataridoStr()
    {
        $this->__load();
        return parent::getHataridoStr();
    }

    public function setHatarido($adat)
    {
        $this->__load();
        return parent::setHatarido($adat);
    }

    public function getFizmod()
    {
        $this->__load();
        return parent::getFizmod();
    }

    public function getFizmodnev()
    {
        $this->__load();
        return parent::getFizmodnev();
    }

    public function getFizmodId()
    {
        $this->__load();
        return parent::getFizmodId();
    }

    public function setFizmod($val)
    {
        $this->__load();
        return parent::setFizmod($val);
    }

    public function removeFizmod()
    {
        $this->__load();
        return parent::removeFizmod();
    }

    public function getSzallitasimod()
    {
        $this->__load();
        return parent::getSzallitasimod();
    }

    public function getSzallitasimodnev()
    {
        $this->__load();
        return parent::getSzallitasimodnev();
    }

    public function getSzallitasimodId()
    {
        $this->__load();
        return parent::getSzallitasimodId();
    }

    public function setSzallitasimod($val)
    {
        $this->__load();
        return parent::setSzallitasimod($val);
    }

    public function removeSzallitasimod()
    {
        $this->__load();
        return parent::removeSzallitasimod();
    }

    public function getNetto()
    {
        $this->__load();
        return parent::getNetto();
    }

    public function setNetto($val)
    {
        $this->__load();
        return parent::setNetto($val);
    }

    public function getAfa()
    {
        $this->__load();
        return parent::getAfa();
    }

    public function setAfa($val)
    {
        $this->__load();
        return parent::setAfa($val);
    }

    public function getBrutto()
    {
        $this->__load();
        return parent::getBrutto();
    }

    public function setBrutto($val)
    {
        $this->__load();
        return parent::setBrutto($val);
    }

    public function getFizetendo()
    {
        $this->__load();
        return parent::getFizetendo();
    }

    public function setFizetendo($val)
    {
        $this->__load();
        return parent::setFizetendo($val);
    }

    public function getValutanem()
    {
        $this->__load();
        return parent::getValutanem();
    }

    public function getValutanemnev()
    {
        $this->__load();
        return parent::getValutanemnev();
    }

    public function getValutanemId()
    {
        $this->__load();
        return parent::getValutanemId();
    }

    public function setValutanem($val)
    {
        $this->__load();
        return parent::setValutanem($val);
    }

    public function removeValutanem()
    {
        $this->__load();
        return parent::removeValutanem();
    }

    public function getNettohuf()
    {
        $this->__load();
        return parent::getNettohuf();
    }

    public function setNettohuf($val)
    {
        $this->__load();
        return parent::setNettohuf($val);
    }

    public function getAfahuf()
    {
        $this->__load();
        return parent::getAfahuf();
    }

    public function setAfahuf($val)
    {
        $this->__load();
        return parent::setAfahuf($val);
    }

    public function getBruttohuf()
    {
        $this->__load();
        return parent::getBruttohuf();
    }

    public function setBruttohuf($val)
    {
        $this->__load();
        return parent::setBruttohuf($val);
    }

    public function getArfolyam()
    {
        $this->__load();
        return parent::getArfolyam();
    }

    public function setArfolyam($val)
    {
        $this->__load();
        return parent::setArfolyam($val);
    }

    public function getPartner()
    {
        $this->__load();
        return parent::getPartner();
    }

    public function getPartnerId()
    {
        $this->__load();
        return parent::getPartnerId();
    }

    public function setPartner($val)
    {
        $this->__load();
        return parent::setPartner($val);
    }

    public function removePartner()
    {
        $this->__load();
        return parent::removePartner();
    }

    public function getPartnernev()
    {
        $this->__load();
        return parent::getPartnernev();
    }

    public function setPartnernev($val)
    {
        $this->__load();
        return parent::setPartnernev($val);
    }

    public function getPartnervezeteknev()
    {
        $this->__load();
        return parent::getPartnervezeteknev();
    }

    public function setPartnervezeteknev($val)
    {
        $this->__load();
        return parent::setPartnervezeteknev($val);
    }

    public function getPartnerkeresztnev()
    {
        $this->__load();
        return parent::getPartnerkeresztnev();
    }

    public function setPartnerkeresztnev($val)
    {
        $this->__load();
        return parent::setPartnerkeresztnev($val);
    }

    public function getPartneradoszam()
    {
        $this->__load();
        return parent::getPartneradoszam();
    }

    public function setPartneradoszam($val)
    {
        $this->__load();
        return parent::setPartneradoszam($val);
    }

    public function getPartnercjszam()
    {
        $this->__load();
        return parent::getPartnercjszam();
    }

    public function getPartnereuadoszam()
    {
        $this->__load();
        return parent::getPartnereuadoszam();
    }

    public function setPartnereuadoszam($val)
    {
        $this->__load();
        return parent::setPartnereuadoszam($val);
    }

    public function getPartnerfvmszam()
    {
        $this->__load();
        return parent::getPartnerfvmszam();
    }

    public function getPartnerirszam()
    {
        $this->__load();
        return parent::getPartnerirszam();
    }

    public function setPartnerirszam($val)
    {
        $this->__load();
        return parent::setPartnerirszam($val);
    }

    public function getPartnerjovengszam()
    {
        $this->__load();
        return parent::getPartnerjovengszam();
    }

    public function getPartnerlirszam()
    {
        $this->__load();
        return parent::getPartnerlirszam();
    }

    public function getPartnerlutca()
    {
        $this->__load();
        return parent::getPartnerlutca();
    }

    public function getPartnerlvaros()
    {
        $this->__load();
        return parent::getPartnerlvaros();
    }

    public function getPartnermukengszam()
    {
        $this->__load();
        return parent::getPartnermukengszam();
    }

    public function getPartnerostermszam()
    {
        $this->__load();
        return parent::getPartnerostermszam();
    }

    public function getPartnerstatszamjel()
    {
        $this->__load();
        return parent::getPartnerstatszamjel();
    }

    public function getPartnerutca()
    {
        $this->__load();
        return parent::getPartnerutca();
    }

    public function setPartnerutca($val)
    {
        $this->__load();
        return parent::setPartnerutca($val);
    }

    public function getPartnervalligszam()
    {
        $this->__load();
        return parent::getPartnervalligszam();
    }

    public function getPartnervaros()
    {
        $this->__load();
        return parent::getPartnervaros();
    }

    public function setPartnervaros($val)
    {
        $this->__load();
        return parent::setPartnervaros($val);
    }

    public function getBankszamla()
    {
        $this->__load();
        return parent::getBankszamla();
    }

    public function getBankszamlanev()
    {
        $this->__load();
        return parent::getBankszamlanev();
    }

    public function getBankszamlaId()
    {
        $this->__load();
        return parent::getBankszamlaId();
    }

    public function setBankszamla($val = NULL)
    {
        $this->__load();
        return parent::setBankszamla($val);
    }

    public function removeBankszamla()
    {
        $this->__load();
        return parent::removeBankszamla();
    }

    public function getSwift()
    {
        $this->__load();
        return parent::getSwift();
    }

    public function getUzletkoto()
    {
        $this->__load();
        return parent::getUzletkoto();
    }

    public function getUzletkotonev()
    {
        $this->__load();
        return parent::getUzletkotonev();
    }

    public function getUzletkotoId()
    {
        $this->__load();
        return parent::getUzletkotoId();
    }

    public function setUzletkoto($val)
    {
        $this->__load();
        return parent::setUzletkoto($val);
    }

    public function removeUzletkoto()
    {
        $this->__load();
        return parent::removeUzletkoto();
    }

    public function getRaktar()
    {
        $this->__load();
        return parent::getRaktar();
    }

    public function getRaktarnev()
    {
        $this->__load();
        return parent::getRaktarnev();
    }

    public function getRaktarId()
    {
        $this->__load();
        return parent::getRaktarId();
    }

    public function setRaktar($val)
    {
        $this->__load();
        return parent::setRaktar($val);
    }

    public function removeRaktar()
    {
        $this->__load();
        return parent::removeRaktar();
    }

    public function getErbizonylatszam()
    {
        $this->__load();
        return parent::getErbizonylatszam();
    }

    public function setErbizonylatszam($val)
    {
        $this->__load();
        return parent::setErbizonylatszam($val);
    }

    public function getMegjegyzes()
    {
        $this->__load();
        return parent::getMegjegyzes();
    }

    public function setMegjegyzes($val)
    {
        $this->__load();
        return parent::setMegjegyzes($val);
    }

    public function getBelsomegjegyzes()
    {
        $this->__load();
        return parent::getBelsomegjegyzes();
    }

    public function setBelsomegjegyzes($val)
    {
        $this->__load();
        return parent::setBelsomegjegyzes($val);
    }

    public function getLastmod()
    {
        $this->__load();
        return parent::getLastmod();
    }

    public function getCreated()
    {
        $this->__load();
        return parent::getCreated();
    }

    public function getSzallnev()
    {
        $this->__load();
        return parent::getSzallnev();
    }

    public function setSzallnev($adat)
    {
        $this->__load();
        return parent::setSzallnev($adat);
    }

    public function getSzallirszam()
    {
        $this->__load();
        return parent::getSzallirszam();
    }

    public function setSzallirszam($adat)
    {
        $this->__load();
        return parent::setSzallirszam($adat);
    }

    public function getSzallvaros()
    {
        $this->__load();
        return parent::getSzallvaros();
    }

    public function setSzallvaros($adat)
    {
        $this->__load();
        return parent::setSzallvaros($adat);
    }

    public function getSzallutca()
    {
        $this->__load();
        return parent::getSzallutca();
    }

    public function setSzallutca($adat)
    {
        $this->__load();
        return parent::setSzallutca($adat);
    }

    public function getWebshopmessage()
    {
        $this->__load();
        return parent::getWebshopmessage();
    }

    public function setWebshopmessage($val)
    {
        $this->__load();
        return parent::setWebshopmessage($val);
    }

    public function getCouriermessage()
    {
        $this->__load();
        return parent::getCouriermessage();
    }

    public function setCouriermessage($val)
    {
        $this->__load();
        return parent::setCouriermessage($val);
    }

    public function getPartnertelefon()
    {
        $this->__load();
        return parent::getPartnertelefon();
    }

    public function setPartnertelefon($telefon)
    {
        $this->__load();
        return parent::setPartnertelefon($telefon);
    }

    public function getPartneremail()
    {
        $this->__load();
        return parent::getPartneremail();
    }

    public function setPartneremail($email)
    {
        $this->__load();
        return parent::setPartneremail($email);
    }

    public function getIp()
    {
        $this->__load();
        return parent::getIp();
    }

    public function setIp($val)
    {
        $this->__load();
        return parent::setIp($val);
    }

    public function getReferrer()
    {
        $this->__load();
        return parent::getReferrer();
    }

    public function setReferrer($val)
    {
        $this->__load();
        return parent::setReferrer($val);
    }

    public function getBizonylatstatusz()
    {
        $this->__load();
        return parent::getBizonylatstatusz();
    }

    public function getBizonylatstatusznev()
    {
        $this->__load();
        return parent::getBizonylatstatusznev();
    }

    public function getBizonylatstatuszId()
    {
        $this->__load();
        return parent::getBizonylatstatuszId();
    }

    public function setBizonylatstatusz($val)
    {
        $this->__load();
        return parent::setBizonylatstatusz($val);
    }

    public function removeBizonylatstatusz()
    {
        $this->__load();
        return parent::removeBizonylatstatusz();
    }

    public function getSzallitasiktgkell()
    {
        $this->__load();
        return parent::getSzallitasiktgkell();
    }

    public function setSzallitasiktgkell($adat)
    {
        $this->__load();
        return parent::setSzallitasiktgkell($adat);
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'id', 'created', 'lastmod', 'bizonylattipus', 'bizonylatnev', 'irany', 'nyomtatva', 'storno', 'stornozott', 'penztmozgat', 'tulajnev', 'tulajirszam', 'tulajvaros', 'tulajutca', 'tulajadoszam', 'tulajeuadoszam', 'erbizonylatszam', 'kelt', 'teljesites', 'esedekesseg', 'fizmod', 'fizmodnev', 'szallitasimod', 'szallitasimodnev', 'netto', 'afa', 'brutto', 'fizetendo', 'valutanem', 'valutanemnev', 'nettohuf', 'afahuf', 'bruttohuf', 'arfolyam', 'partner', 'partnernev', 'partnervezeteknev', 'partnerkeresztnev', 'partneradoszam', 'partnereuadoszam', 'partnermukengszam', 'partnerjovengszam', 'partnerostermszam', 'partnervalligszam', 'partnerfvmszam', 'partnercjszam', 'partnerstatszamjel', 'partnerirszam', 'partnervaros', 'partnerutca', 'partnerlirszam', 'partnerlvaros', 'partnerlutca', 'partneremail', 'partnertelefon', 'bankszamla', 'bankszamlanev', 'swift', 'uzletkoto', 'uzletkotonev', 'raktar', 'raktarnev', 'bizonylattetelek', 'megjegyzes', 'belsomegjegyzes', 'webshopmessage', 'couriermessage', 'hatarido', 'szallnev', 'szallirszam', 'szallvaros', 'szallutca', 'ip', 'referrer', 'bizonylatstatusz', 'szallitasiktgkell');
    }

    public function __clone()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;
            $class = $this->_entityPersister->getClassMetadata();
            $original = $this->_entityPersister->load($this->_identifier);
            if ($original === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            foreach ($class->reflFields AS $field => $reflProperty) {
                $reflProperty->setValue($this, $reflProperty->getValue($original));
            }
            unset($this->_entityPersister, $this->_identifier);
        }
        
    }
}