<?php

namespace Entities;

use mkwhelpers\FilterDescriptor;

class KosarRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\Kosar');
        $this->setOrders(array(
            '1' => array('caption' => 'létrehozás dátuma szerint csökkenő', 'order' => array('_xx.created' => 'DESC', '_xx.sessionid' => 'ASC')),
            '2' => array('caption' => 'létrehozás dátuma szerint növekvő', 'order' => array('_xx.created' => 'ASC', '_xx.sessionid' => 'ASC')),
            '3' => array('caption' => 'session szerint növekvő', 'order' => array('_xx.sessionid' => 'ASC')),
        ));
    }

    public function getWithJoins($filter, $order, $offset = 0, $elemcount = 0) {
        $q = $this->_em->createQuery('SELECT _xx,p,t '
            . ' FROM Entities\Kosar _xx'
            . ' LEFT OUTER JOIN _xx.partner p'
            . ' LEFT JOIN _xx.termek t'
            . $this->getFilterString($filter)
            . $this->getOrderString($order));
        $q->setParameters($this->getQueryParameters($filter));
        if ($offset > 0) {
            $q->setFirstResult($offset);
        }
        if ($elemcount > 0) {
            $q->setMaxResults($elemcount);
        }
        if (\mkw\Store::isMainMode()) {
            \mkw\Store::setTranslationHint($q, \mkw\Store::getParameter(\mkw\consts::Locale));
        }
        return $q->getResult();
    }

    public function getCount($filter) {
        $q = $this->_em->createQuery('SELECT COUNT(_xx)'
            . ' FROM Entities\Kosar _xx'
            . ' LEFT OUTER JOIN _xx.partner p'
            . ' LEFT JOIN _xx.termek t'
            . $this->getFilterString($filter));
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getSingleScalarResult();
    }

    public function getMiniDataBySessionId($sessionid) {
        $szktid = \mkw\Store::getParameter(\mkw\consts::SzallitasiKtgTermek);

        $filter = new FilterDescriptor();
        $filter->addFilter('sessionid', '=', $sessionid);
        if ($szktid) {
            $filter->addFilter('termek', '<>', $szktid);
        }

        $q = $this->_em->createQuery('SELECT SUM(_xx.mennyiseg),'
            . ' SUM(_xx.bruttoegysar * _xx.mennyiseg),'
            . ' SUM(_xx.nettoegysar * _xx.mennyiseg)'
            . ' FROM Entities\Kosar _xx'
            . $this->getFilterString($filter));
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getScalarResult();
    }

    public function getDataBySessionId($sessionid) {
        $filter = new FilterDescriptor();
        $filter->addFilter('sessionid', '=', $sessionid);

        return $this->getWithJoins($filter, array('_xx.sorrend' => 'ASC'));
    }

    public function getDataByPartner($partner) {
        $filter = new FilterDescriptor();
        $filter->addFilter('partner', '=', $partner);

        return $this->getWithJoins($filter, array('_xx.sorrend' => 'ASC'));
    }

    public function calcSumBySessionId($sessionid) {
        $szktid = \mkw\Store::getParameter(\mkw\consts::SzallitasiKtgTermek);

        $filter = new FilterDescriptor();
        $filter->addFilter('sessionid', '=', $sessionid);
        if ($szktid) {
            $filter->addFilter('termek', '<>', $szktid);
        }

        $q = $this->_em->createQuery('SELECT SUM(_xx.bruttoegysar * _xx.mennyiseg),'
            . ' SUM(_xx.nettoegysar * _xx.mennyiseg),'
            . ' SUM(_xx.mennyiseg),'
            . ' COUNT(_xx)'
            . ' FROM Entities\Kosar _xx'
            . $this->getFilterString($filter));
        $q->setParameters($this->getQueryParameters($filter));
        $res = $q->getScalarResult();
        if (count($res)) {
            return array(
                'sum' => $res[0][1],
                'nettosum' => $res[0][2],
                'mennyisegsum' => $res[0][3],
                'count' => $res[0][4]
            );
        }
        return 0;
    }

    public function getTetelsor($sessionid, $partnerid, $termekid, $valtozatid = null, $valutanem = null) {
        $filter = new FilterDescriptor();
        if ($sessionid) {
            $filter->addFilter('sessionid', '=', $sessionid);
        }
        if ($partnerid) {
            $filter->addFilter('partner', '=', $partnerid);
        }
        if ($termekid) {
            $filter->addFilter('termek', '=', $termekid);
        }
        if ($valtozatid) {
            $filter->addFilter('termekvaltozat', '=', $valtozatid);
        }
        if ($valutanem) {
            $filter->addFilter('valutanem', '=', $valutanem);
        }
        if (count($filter) == 0) {
            $filter->addFilter('id', '<', 0);
        }

        $q = $this->_em->createQuery('SELECT _xx'
            . ' FROM Entities\Kosar _xx'
            . $this->getFilterString($filter));
        $q->setParameters($this->getQueryParameters($filter));
        $r = $q->getResult();
        if (count($r) > 0) {
            return $r[0];
        }
        return null;
    }

    // MKW, egyesével lehet a kosárba rakni; a kosárban lehet mennyiséget módosítani
    public function add($termekid, $vid = null, $bruttoegysar = null, $mennyiseg = null) {
        $sessionid = \Zend_Session::getId();

        $partnerid = null;
        $partner = $this->getRepo('Entities\Partner')->getLoggedInUser();
        if ($partner) {
            $partnerid = $partner->getId();
            if ($partner->getSzamlatipus() > 0) {
                $nullasafa = $this->getRepo('Entities\Afa')->find(\mkw\Store::getParameter(\mkw\consts::NullasAfa));
            }
        }

        $valutanemid = \mkw\Store::getParameter(\mkw\consts::Valutanem);

        $k = $this->getTetelsor($sessionid, $partnerid, $termekid, $vid, $valutanemid);
        if ($termekid == \mkw\Store::getParameter(\mkw\consts::SzallitasiKtgTermek)) {
            if ($k) {
                $k->setMennyiseg(1);
                if ($nullasafa) {
                    $k->setAfa($nullasafa);
                }
                $k->setBruttoegysar($bruttoegysar);
            }
            else {
                $termek = $this->getRepo('Entities\Termek')->find($termekid);
                if ($termek) {
                    $valutanem = $this->getRepo('Entities\Valutanem')->find($valutanemid);
                    $k = new \Entities\Kosar();
                    $k->setTermek($termek);
                    $k->setSessionid($sessionid);
                    $k->setPartner($partner);
                    $k->setValutanem($valutanem);
                    if ($nullasafa) {
                        $k->setAfa($nullasafa);
                    }
                    $k->setBruttoegysar($bruttoegysar);
                    $k->setEbruttoegysar($k->getBruttoegysar());
                    $k->setEnettoegysar($k->getNettoegysar());
                    $k->setMennyiseg(1);
                    $k->setSorrend(100);
                }
            }
        }
        else {
            if ($k) {
                if ($mennyiseg) {
                    $k->setMennyiseg($mennyiseg);
                }
                else {
                    $k->novelMennyiseg();
                }
            }
            else {
                /** @var \Entities\Termek $termek */
                $termek = $this->getRepo('Entities\Termek')->find($termekid);
                if ($termek) {
                    $valutanem = $this->getRepo('Entities\Valutanem')->find($valutanemid);
                    if ($vid) {
                        $termekvaltozat = $this->getRepo('Entities\TermekValtozat')->find($vid);
                    }
                    $k = new \Entities\Kosar();
                    $k->setTermek($termek);
                    if ($vid) {
                        $k->setTermekvaltozat($termekvaltozat);
                    }
                    $k->setSessionid($sessionid);
                    $k->setPartner($partner);
                    $k->setValutanem($valutanem);
                    if ($nullasafa) {
                        $k->setAfa($nullasafa);
                        $k->setNettoegysar($termek->getNettoAr($termekvaltozat, $partner));
                        $k->setEnettoegysar($termek->getKedvezmenynelkuliNettoAr($termekvaltozat, $partner));
                        $k->setEbruttoegysar($k->getEnettoegysar());
                    }
                    else {
                        $k->setBruttoegysar($termek->getBruttoAr($termekvaltozat, $partner));
                        $k->setEbruttoegysar($termek->getKedvezmenynelkuliBruttoAr($termekvaltozat, $partner));
                        $k->setEnettoegysar($termek->getKedvezmenynelkuliNettoAr($termekvaltozat, $partner));
                    }

                    $k->setKedvezmeny($termek->getTermekcsoportKedvezmeny($partner));
                    if ($mennyiseg) {
                        $k->setMennyiseg($mennyiseg);
                    }
                    else {
                        $k->setMennyiseg(1);
                    }
                }
            }
        }
        if ($k) {
            $this->_em->persist($k);
            $this->_em->flush();
        }
    }

    // Superzone, terméklistából is lehet mennyiséget megadni
    public function addTo($termekid, $vid = null, $bruttoegysar = null, $mennyiseg = null, $kedvezmeny = null) {
        $sessionid = \Zend_Session::getId();

        $partnerid = null;
        $partner = $this->getRepo('Entities\Partner')->getLoggedInUser();
        if ($partner) {
            $partnerid = $partner->getId();
            if ($partner->getSzamlatipus() > 0) {
                $nullasafa = $this->getRepo('Entities\Afa')->find(\mkw\Store::getParameter(\mkw\consts::NullasAfa));
            }
        }

        $valutanemid = \mkw\Store::getParameter(\mkw\consts::Valutanem);

        $k = $this->getTetelsor($sessionid, $partnerid, $termekid, $vid, $valutanemid);
        if ($termekid == \mkw\Store::getParameter(\mkw\consts::SzallitasiKtgTermek)) {
            if ($k) {
                $k->setMennyiseg(1);
                if ($nullasafa) {
                    $k->setAfa($nullasafa);
                }
                $k->setBruttoegysar($bruttoegysar);
            }
            else {
                $termek = $this->getRepo('Entities\Termek')->find($termekid);
                if ($termek) {
                    $valutanem = $this->getRepo('Entities\Valutanem')->find($valutanemid);
                    $k = new \Entities\Kosar();
                    $k->setTermek($termek);
                    $k->setSessionid($sessionid);
                    $k->setPartner($partner);
                    $k->setValutanem($valutanem);
                    if ($nullasafa) {
                        $k->setAfa($nullasafa);
                    }
                    $k->setBruttoegysar($bruttoegysar);
                    $k->setEbruttoegysar($k->getBruttoegysar());
                    $k->setEnettoegysar($k->getNettoegysar());
                    $k->setMennyiseg(1);
                    $k->setSorrend(100);
                }
            }
        }
        else {
            if ($k) {
                $k->novelMennyiseg($mennyiseg);
            }
            else {
                /** @var \Entities\Termek $termek */
                $termek = $this->getRepo('Entities\Termek')->find($termekid);
                if ($termek) {
                    $valutanem = $this->getRepo('Entities\Valutanem')->find($valutanemid);
                    if ($vid) {
                        $termekvaltozat = $this->getRepo('Entities\TermekValtozat')->find($vid);
                    }
                    $k = new \Entities\Kosar();
                    $k->setTermek($termek);
                    if ($vid) {
                        $k->setTermekvaltozat($termekvaltozat);
                    }
                    $k->setSessionid($sessionid);
                    $k->setPartner($partner);
                    $k->setValutanem($valutanem);
                    if ($nullasafa) {
                        $k->setAfa($nullasafa);
                        $eredetinetto = $termek->getKedvezmenynelkuliNettoAr($termekvaltozat, $partner);
                        $k->setEnettoegysar($eredetinetto);
                        $k->setEbruttoegysar($eredetinetto);
                    }
                    else {
                        $k->setEbruttoegysar($termek->getKedvezmenynelkuliBruttoAr($termekvaltozat, $partner));
                        $k->setEnettoegysar($termek->getKedvezmenynelkuliNettoAr($termekvaltozat, $partner));
                    }

                    if (!$kedvezmeny) {
                        $kedvezmeny = $termek->getTermekcsoportKedvezmeny($partner);
                    }
                    $k->setKedvezmeny($kedvezmeny);

                    $k->setBruttoegysar($k->getEbruttoegysar() * (100 - $kedvezmeny) / 100);

                    if ($mennyiseg) {
                        $k->setMennyiseg($mennyiseg);
                    }
                    else {
                        $k->setMennyiseg(1);
                    }
                }
            }
        }
        if ($k) {
            $this->_em->persist($k);
            $this->_em->flush();
        }
    }

    public function remove($termekid, $vid = null) {
        $sessionid = \Zend_Session::getId();

        $partnerid = null;
        $partner = $this->getRepo('Entities\Partner')->getLoggedInUser();
        if ($partner) {
            $partnerid = $partner->getId();
        }

        $valutanemid = \mkw\Store::getParameter(\mkw\consts::Valutanem);

        $sor = $this->getTetelsor($sessionid, $partnerid, $termekid, $vid, $valutanemid);
        if ($sor) {
//            $termekid = $sor->getTermekId();
            $this->_em->remove($sor);
            $this->_em->flush();
        }
    }

    public function del($id) {
        $sessionid = \Zend_Session::getId();
        $sor = $this->find($id);
        if ($sor && $sor->getSessionid() == $sessionid) {
            $termekid = $sor->getTermekId();
            $this->_em->remove($sor);
            $this->_em->flush();
            return true;
        }
        return false;
    }

    public function edit($id, $mennyiseg, $kedvezmeny = false) {
        $sessionid = \Zend_Session::getId();
        /** @var \Entities\Kosar $sor */
        $sor = $this->find($id);
        if ($sor && $sor->getSessionid() == $sessionid) {
            $termekid = $sor->getTermekId();
            if ($kedvezmeny !== false) {
                $sor->setKedvezmeny($kedvezmeny);
                $sor->setBruttoegysar($sor->getEbruttoegysar() * (100 - $kedvezmeny) / 100);
            }
            if ($mennyiseg !== false) {
                $sor->setMennyiseg($mennyiseg);
            }
            $this->_em->persist($sor);
            $this->_em->flush();
            return true;
        }
        return false;
    }

    public function clear($partnerid = false) {
        if ($partnerid) {
            $partner = $this->getRepo('Entities\Partner')->find($partnerid);
        }
        else {
            $partner = $this->getRepo('Entities\Partner')->getLoggedInUser();
        }
        if ($partner) {
            $k = $this->getDataByPartner($partner);
        }
        else {
            if ($partnerid) {
                $k = false;
            }
            else {
                $k = $this->getDataBySessionId(\Zend_Session::getId());
            }
        }
        foreach ($k as $sor) {
            $this->_em->remove($sor);
        }
        if ($k) {
            $this->_em->flush();
        }
    }

    public function createSzallitasiKtg($szallmod = null) {
        $szamol = true;
        if ($szallmod) {
            $szm = $this->getRepo('Entities\Szallitasimod')->find($szallmod);
            $szamol = $szm->getVanszallitasiktg();
        }
        $termekid = \mkw\Store::getParameter(\mkw\consts::SzallitasiKtgTermek);
        $termek = $this->getRepo('Entities\Termek')->find($termekid);

        if ($termekid && $termek) {
            if ($szamol) {
                $e = $this->calcSumBySessionId(\Zend_Session::getId());
                $ertek = $e['sum'];
                $cnt = $e['count'];
                if ($cnt != 0) {
                    $partner = \mkw\Store::getLoggedInUser();
                    $ktg = $this->getRepo('Entities\SzallitasimodHatar')->getBySzallitasimodValutanemHatar($szallmod,
                        \mkw\Store::getPartnerValutanem($partner), $ertek);
                    $this->add($termekid, null, $ktg ? $ktg->getOsszeg() : 0);
                }
                else {
                    $this->remove($termek);
                }
            }
            else {
                $this->remove($termek);
            }
        }
    }

    public function getHash() {
        $sorok = $this->getDataBySessionId(\Zend_Session::getId());
        $s = array();
        foreach ($sorok as $sor) {
            $s[] = $sor->toLista();
        }
        return array(
            'value' => md5(json_encode($s)),
            'cnt' => count($sorok)
        );
    }
}
