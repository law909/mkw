<?php

namespace Proxies;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class EntitiesTermekProxy extends \Entities\Termek implements \Doctrine\ORM\Proxy\Proxy
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
    
    
    public function generateVonalkod()
    {
        $this->__load();
        return parent::generateVonalkod();
    }

    public function doStuffOnPrePersist()
    {
        $this->__load();
        return parent::doStuffOnPrePersist();
    }

    public function getUjTermek($min)
    {
        $this->__load();
        return parent::getUjTermek($min);
    }

    public function getTop10($top10min)
    {
        $this->__load();
        return parent::getTop10($top10min);
    }

    public function getKeszlet()
    {
        $this->__load();
        return parent::getKeszlet();
    }

    public function toTermekLista($valtozat = NULL, $ujtermekid = NULL, $top10min = NULL)
    {
        $this->__load();
        return parent::toTermekLista($valtozat, $ujtermekid, $top10min);
    }

    public function toKiemeltLista($valtozat = NULL)
    {
        $this->__load();
        return parent::toKiemeltLista($valtozat);
    }

    public function toTermekLap($valtozat = NULL, $ujtermekid = NULL, $top10min = NULL)
    {
        $this->__load();
        return parent::toTermekLap($valtozat, $ujtermekid, $top10min);
    }

    public function toKapcsolodo($valtozat = NULL)
    {
        $this->__load();
        return parent::toKapcsolodo($valtozat);
    }

    public function toKosar($valtozat)
    {
        $this->__load();
        return parent::toKosar($valtozat);
    }

    public function getId()
    {
        $this->__load();
        return parent::getId();
    }

    public function getNev()
    {
        $this->__load();
        return parent::getNev();
    }

    public function setNev($nev)
    {
        $this->__load();
        return parent::setNev($nev);
    }

    public function getMe()
    {
        $this->__load();
        return parent::getMe();
    }

    public function setMe($me)
    {
        $this->__load();
        return parent::setMe($me);
    }

    public function getVtsz()
    {
        $this->__load();
        return parent::getVtsz();
    }

    public function getVtszNev()
    {
        $this->__load();
        return parent::getVtszNev();
    }

    public function getVtszId()
    {
        $this->__load();
        return parent::getVtszId();
    }

    public function setVtsz($vtsz)
    {
        $this->__load();
        return parent::setVtsz($vtsz);
    }

    public function getAfa()
    {
        $this->__load();
        return parent::getAfa();
    }

    public function getAfaNev()
    {
        $this->__load();
        return parent::getAfaNev();
    }

    public function getAfaId()
    {
        $this->__load();
        return parent::getAfaId();
    }

    public function setAfa($afa)
    {
        $this->__load();
        return parent::setAfa($afa);
    }

    public function getCikkszam()
    {
        $this->__load();
        return parent::getCikkszam();
    }

    public function setCikkszam($cikkszam)
    {
        $this->__load();
        return parent::setCikkszam($cikkszam);
    }

    public function getIdegencikkszam()
    {
        $this->__load();
        return parent::getIdegencikkszam();
    }

    public function setIdegencikkszam($idegencikkszam)
    {
        $this->__load();
        return parent::setIdegencikkszam($idegencikkszam);
    }

    public function getVonalkod()
    {
        $this->__load();
        return parent::getVonalkod();
    }

    public function setVonalkod($vonalkod)
    {
        $this->__load();
        return parent::setVonalkod($vonalkod);
    }

    public function getLeiras()
    {
        $this->__load();
        return parent::getLeiras();
    }

    public function setLeiras($leiras)
    {
        $this->__load();
        return parent::setLeiras($leiras);
    }

    public function getRovidleiras()
    {
        $this->__load();
        return parent::getRovidleiras();
    }

    public function setRovidleiras($rovidleiras)
    {
        $this->__load();
        return parent::setRovidleiras($rovidleiras);
    }

    public function getOldalcim()
    {
        $this->__load();
        return parent::getOldalcim();
    }

    public function getShowOldalcim()
    {
        $this->__load();
        return parent::getShowOldalcim();
    }

    public function setOldalcim($oldalcim)
    {
        $this->__load();
        return parent::setOldalcim($oldalcim);
    }

    public function getSeodescription()
    {
        $this->__load();
        return parent::getSeodescription();
    }

    public function getShowSeodescription()
    {
        $this->__load();
        return parent::getShowSeodescription();
    }

    public function setSeodescription($seodescription)
    {
        $this->__load();
        return parent::setSeodescription($seodescription);
    }

    public function getSlug()
    {
        $this->__load();
        return parent::getSlug();
    }

    public function setSlug($slug)
    {
        $this->__load();
        return parent::setSlug($slug);
    }

    public function getLathato()
    {
        $this->__load();
        return parent::getLathato();
    }

    public function setLathato($lathato)
    {
        $this->__load();
        return parent::setLathato($lathato);
    }

    public function getHozzaszolas()
    {
        $this->__load();
        return parent::getHozzaszolas();
    }

    public function setHozzaszolas($hozzaszolas)
    {
        $this->__load();
        return parent::setHozzaszolas($hozzaszolas);
    }

    public function getAjanlott()
    {
        $this->__load();
        return parent::getAjanlott();
    }

    public function setAjanlott($ajanlott)
    {
        $this->__load();
        return parent::setAjanlott($ajanlott);
    }

    public function getMozgat()
    {
        $this->__load();
        return parent::getMozgat();
    }

    public function setMozgat($mozgat)
    {
        $this->__load();
        return parent::setMozgat($mozgat);
    }

    public function getInaktiv()
    {
        $this->__load();
        return parent::getInaktiv();
    }

    public function setInaktiv($inaktiv)
    {
        $this->__load();
        return parent::setInaktiv($inaktiv);
    }

    public function getTermekexportbanszerepel()
    {
        $this->__load();
        return parent::getTermekexportbanszerepel();
    }

    public function setTermekexportbanszerepel($adat)
    {
        $this->__load();
        return parent::setTermekexportbanszerepel($adat);
    }

    public function getHparany()
    {
        $this->__load();
        return parent::getHparany();
    }

    public function setHparany($hparany)
    {
        $this->__load();
        return parent::setHparany($hparany);
    }

    public function getNetto()
    {
        $this->__load();
        return parent::getNetto();
    }

    public function setNetto($netto)
    {
        $this->__load();
        return parent::setNetto($netto);
    }

    public function getBrutto()
    {
        $this->__load();
        return parent::getBrutto();
    }

    public function setBrutto($brutto)
    {
        $this->__load();
        return parent::setBrutto($brutto);
    }

    public function getAkcios()
    {
        $this->__load();
        return parent::getAkcios();
    }

    public function getAkcioTipus()
    {
        $this->__load();
        return parent::getAkcioTipus();
    }

    public function getAkciosnetto()
    {
        $this->__load();
        return parent::getAkciosnetto();
    }

    public function setAkciosnetto($netto)
    {
        $this->__load();
        return parent::setAkciosnetto($netto);
    }

    public function getAkciosbrutto()
    {
        $this->__load();
        return parent::getAkciosbrutto();
    }

    public function setAkciosbrutto($brutto)
    {
        $this->__load();
        return parent::setAkciosbrutto($brutto);
    }

    public function getAkciostart()
    {
        $this->__load();
        return parent::getAkciostart();
    }

    public function getAkciostartStr()
    {
        $this->__load();
        return parent::getAkciostartStr();
    }

    public function setAkciostart($adat = '')
    {
        $this->__load();
        return parent::setAkciostart($adat);
    }

    public function getAkciostop()
    {
        $this->__load();
        return parent::getAkciostop();
    }

    public function getAkciostopStr()
    {
        $this->__load();
        return parent::getAkciostopStr();
    }

    public function setAkciostop($adat = '')
    {
        $this->__load();
        return parent::setAkciostop($adat);
    }

    public function getCimkek()
    {
        $this->__load();
        return parent::getCimkek();
    }

    public function getAllCimkeId()
    {
        $this->__load();
        return parent::getAllCimkeId();
    }

    public function setCimkeNevek($cimkenevek)
    {
        $this->__load();
        return parent::setCimkeNevek($cimkenevek);
    }

    public function getCimkeNevek()
    {
        $this->__load();
        return parent::getCimkeNevek();
    }

    public function addCimke(\Entities\Cimketorzs $cimke)
    {
        $this->__load();
        return parent::addCimke($cimke);
    }

    public function removeCimke(\Entities\Cimketorzs $cimke)
    {
        $this->__load();
        return parent::removeCimke($cimke);
    }

    public function removeAllCimke()
    {
        $this->__load();
        return parent::removeAllCimke();
    }

    public function getCimkeByCategory($cat)
    {
        $this->__load();
        return parent::getCimkeByCategory($cat);
    }

    public function getIdegenkod()
    {
        $this->__load();
        return parent::getIdegenkod();
    }

    public function setIdegenkod($idegenkod)
    {
        $this->__load();
        return parent::setIdegenkod($idegenkod);
    }

    public function getKiszereles()
    {
        $this->__load();
        return parent::getKiszereles();
    }

    public function setKiszereles($kiszereles)
    {
        $this->__load();
        return parent::setKiszereles($kiszereles);
    }

    public function getTermekfa1()
    {
        $this->__load();
        return parent::getTermekfa1();
    }

    public function getTermekfa1Nev()
    {
        $this->__load();
        return parent::getTermekfa1Nev();
    }

    public function getTermekfa1Id()
    {
        $this->__load();
        return parent::getTermekfa1Id();
    }

    public function setTermekfa1($termekfa)
    {
        $this->__load();
        return parent::setTermekfa1($termekfa);
    }

    public function getTermekfa2()
    {
        $this->__load();
        return parent::getTermekfa2();
    }

    public function getTermekfa2Nev()
    {
        $this->__load();
        return parent::getTermekfa2Nev();
    }

    public function getTermekfa2Id()
    {
        $this->__load();
        return parent::getTermekfa2Id();
    }

    public function setTermekfa2($termekfa)
    {
        $this->__load();
        return parent::setTermekfa2($termekfa);
    }

    public function getTermekfa3()
    {
        $this->__load();
        return parent::getTermekfa3();
    }

    public function getTermekfa3Nev()
    {
        $this->__load();
        return parent::getTermekfa3Nev();
    }

    public function getTermekfa3Id()
    {
        $this->__load();
        return parent::getTermekfa3Id();
    }

    public function setTermekfa3($termekfa)
    {
        $this->__load();
        return parent::setTermekfa3($termekfa);
    }

    public function getTermekAr($valtozat)
    {
        $this->__load();
        return parent::getTermekAr($valtozat);
    }

    public function getTermekKepek()
    {
        $this->__load();
        return parent::getTermekKepek();
    }

    public function addTermekKep(\Entities\TermekKep $kep)
    {
        $this->__load();
        return parent::addTermekKep($kep);
    }

    public function removeTermekKep(\Entities\TermekKep $kep)
    {
        $this->__load();
        return parent::removeTermekKep($kep);
    }

    public function getKepurl($pre = '/')
    {
        $this->__load();
        return parent::getKepurl($pre);
    }

    public function getKepurlMini($pre = '/')
    {
        $this->__load();
        return parent::getKepurlMini($pre);
    }

    public function getKepurlSmall($pre = '/')
    {
        $this->__load();
        return parent::getKepurlSmall($pre);
    }

    public function getKepurlMedium($pre = '/')
    {
        $this->__load();
        return parent::getKepurlMedium($pre);
    }

    public function getKepurlLarge($pre = '/')
    {
        $this->__load();
        return parent::getKepurlLarge($pre);
    }

    public function setKepurl($kepurl)
    {
        $this->__load();
        return parent::setKepurl($kepurl);
    }

    public function getKepleiras()
    {
        $this->__load();
        return parent::getKepleiras();
    }

    public function setKepleiras($kepleiras)
    {
        $this->__load();
        return parent::setKepleiras($kepleiras);
    }

    public function getSzelesseg()
    {
        $this->__load();
        return parent::getSzelesseg();
    }

    public function setSzelesseg($szelesseg)
    {
        $this->__load();
        return parent::setSzelesseg($szelesseg);
    }

    public function getMagassag()
    {
        $this->__load();
        return parent::getMagassag();
    }

    public function setMagassag($magassag)
    {
        $this->__load();
        return parent::setMagassag($magassag);
    }

    public function getHosszusag()
    {
        $this->__load();
        return parent::getHosszusag();
    }

    public function setHosszusag($hosszusag)
    {
        $this->__load();
        return parent::setHosszusag($hosszusag);
    }

    public function getOsszehajthato()
    {
        $this->__load();
        return parent::getOsszehajthato();
    }

    public function setOsszehajthato($osszehajthato)
    {
        $this->__load();
        return parent::setOsszehajthato($osszehajthato);
    }

    public function getSuly()
    {
        $this->__load();
        return parent::getSuly();
    }

    public function setSuly($suly)
    {
        $this->__load();
        return parent::setSuly($suly);
    }

    public function getValtozatok()
    {
        $this->__load();
        return parent::getValtozatok();
    }

    public function addValtozat(\Entities\TermekValtozat $valt)
    {
        $this->__load();
        return parent::addValtozat($valt);
    }

    public function removeValtozat(\Entities\TermekValtozat $valt)
    {
        $this->__load();
        return parent::removeValtozat($valt);
    }

    public function getTermekReceptek()
    {
        $this->__load();
        return parent::getTermekReceptek();
    }

    public function addTermekRecept(\Entities\TermekRecept $recept)
    {
        $this->__load();
        return parent::addTermekRecept($recept);
    }

    public function removeTermekRecept(\Entities\TermekRecept $recept)
    {
        $this->__load();
        return parent::removeTermekRecept($recept);
    }

    public function getAlTermekReceptek()
    {
        $this->__load();
        return parent::getAlTermekReceptek();
    }

    public function addAlTermekRecept(\Entities\TermekRecept $recept)
    {
        $this->__load();
        return parent::addAlTermekRecept($recept);
    }

    public function removeAlTermekRecept(\Entities\TermekRecept $recept)
    {
        $this->__load();
        return parent::removeAlTermekRecept($recept);
    }

    public function getMegtekintesdb()
    {
        $this->__load();
        return parent::getMegtekintesdb();
    }

    public function setMegtekintesdb($adat)
    {
        $this->__load();
        return parent::setMegtekintesdb($adat);
    }

    public function incMegtekintesdb()
    {
        $this->__load();
        return parent::incMegtekintesdb();
    }

    public function getMegvasarlasdb()
    {
        $this->__load();
        return parent::getMegvasarlasdb();
    }

    public function setMegvasarlasdb($adat)
    {
        $this->__load();
        return parent::setMegvasarlasdb($adat);
    }

    public function incMegvasarlasdb()
    {
        $this->__load();
        return parent::incMegvasarlasdb();
    }

    public function getKiemelt()
    {
        $this->__load();
        return parent::getKiemelt();
    }

    public function setKiemelt($adat)
    {
        $this->__load();
        return parent::setKiemelt($adat);
    }

    public function getTermekKapcsolodok()
    {
        $this->__load();
        return parent::getTermekKapcsolodok();
    }

    public function addTermekKapcsolodo(\Entities\TermekKapcsolodo $adat)
    {
        $this->__load();
        return parent::addTermekKapcsolodo($adat);
    }

    public function removeTermekKapcsolodo(\Entities\TermekKapcsolodo $adat)
    {
        $this->__load();
        return parent::removeTermekKapcsolodo($adat);
    }

    public function getAlTermekKapcsolodok()
    {
        $this->__load();
        return parent::getAlTermekKapcsolodok();
    }

    public function addAlTermekKapcsolodo(\Entities\TermekKapcsolodo $adat)
    {
        $this->__load();
        return parent::addAlTermekKapcsolodo($adat);
    }

    public function removeAlTermekKapcsolodo(\Entities\TermekKapcsolodo $adat)
    {
        $this->__load();
        return parent::removeAlTermekKapcsolodo($adat);
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

    public function getValtozatadattipus()
    {
        $this->__load();
        return parent::getValtozatadattipus();
    }

    public function getValtozatadattipusNev()
    {
        $this->__load();
        return parent::getValtozatadattipusNev();
    }

    public function getValtozatadattipusId()
    {
        $this->__load();
        return parent::getValtozatadattipusId();
    }

    public function setValtozatadattipus($a)
    {
        $this->__load();
        return parent::setValtozatadattipus($a);
    }

    public function getNettoAr($valtozat = NULL)
    {
        $this->__load();
        return parent::getNettoAr($valtozat);
    }

    public function getBruttoAr($valtozat = NULL, $eredeti = false)
    {
        $this->__load();
        return parent::getBruttoAr($valtozat, $eredeti);
    }

    public function getNemkaphato()
    {
        $this->__load();
        return parent::getNemkaphato();
    }

    public function setNemkaphato($val)
    {
        $this->__load();
        return parent::setNemkaphato($val);
    }

    public function getGyarto()
    {
        $this->__load();
        return parent::getGyarto();
    }

    public function getGyartoNev()
    {
        $this->__load();
        return parent::getGyartoNev();
    }

    public function getGyartoId()
    {
        $this->__load();
        return parent::getGyartoId();
    }

    public function setGyarto($gyarto)
    {
        $this->__load();
        return parent::setGyarto($gyarto);
    }

    public function getFuggoben()
    {
        $this->__load();
        return parent::getFuggoben();
    }

    public function setFuggoben($d)
    {
        $this->__load();
        return parent::setFuggoben($d);
    }

    public function getSzallitasiido()
    {
        $this->__load();
        return parent::getSzallitasiido();
    }

    public function setSzallitasiido($adat)
    {
        $this->__load();
        return parent::setSzallitasiido($adat);
    }

    public function getRegikepurl()
    {
        $this->__load();
        return parent::getRegikepurl();
    }

    public function setRegikepurl($adat)
    {
        $this->__load();
        return parent::setRegikepurl($adat);
    }

    public function getTermekArak()
    {
        $this->__load();
        return parent::getTermekArak();
    }

    public function addTermekAr(\Entities\TermekAr $adat)
    {
        $this->__load();
        return parent::addTermekAr($adat);
    }

    public function removeTermekAr(\Entities\TermekAr $adat)
    {
        $this->__load();
        return parent::removeTermekAr($adat);
    }

    public function setTranslatableLocale($l)
    {
        $this->__load();
        return parent::setTranslatableLocale($l);
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'id', 'created', 'lastmod', 'idegenkod', 'nev', 'me', 'kiszereles', 'vtsz', 'afa', 'cimkek', 'cimkenevek', 'cikkszam', 'idegencikkszam', 'vonalkod', 'leiras', 'rovidleiras', 'oldalcim', 'seodescription', 'slug', 'lathato', 'hozzaszolas', 'ajanlott', 'kiemelt', 'mozgat', 'inaktiv', 'fuggoben', 'termekexportbanszerepel', 'hparany', 'netto', 'brutto', 'akciosnetto', 'akciosbrutto', 'akciostart', 'akciostop', 'termekfa1', 'termekfa1karkod', 'termekfa2', 'termekfa2karkod', 'termekfa3', 'termekfa3karkod', 'kepurl', 'kepleiras', 'szelesseg', 'magassag', 'hosszusag', 'suly', 'osszehajthato', 'termekkepek', 'valtozatok', 'termekreceptek', 'altermekreceptek', 'bizonylattetelek', 'kosarak', 'megtekintesdb', 'megvasarlasdb', 'termekkapcsolodok', 'altermekkapcsolodok', 'valtozatadattipus', 'nemkaphato', 'termekertesitok', 'gyarto', 'szallitasiido', 'regikepurl', 'termekarak');
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