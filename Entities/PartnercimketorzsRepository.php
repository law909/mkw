<?php

namespace Entities;

use Doctrine\ORM\Query\ResultSetMapping;
use mkwhelpers\FilterDescriptor;

class PartnercimketorzsRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\Partnercimketorzs');
        $this->setOrders(array(
            '1' => array('caption' => 'név szerint növekvő', 'order' => array('_xx.nev' => 'ASC')),
            '2' => array('caption' => 'csoport szerint növekvő', 'order' => array('ck.nev' => 'ASC', '_xx.nev' => 'ASC'))
        ));
        $this->setBatches(array(
            '1' => 'áthelyezés másik címkecsoportba'
        ));
    }

    public function getWithJoins($filter, $order, $offset = 0, $elemcount = 0) {
        $q = $this->_em->createQuery('SELECT _xx,ck'
            . ' FROM Entities\Partnercimketorzs _xx'
            . ' JOIN _xx.kategoria ck '
            . $this->getFilterString($filter)
            . $this->getOrderString($order));
        $q->setParameters($this->getQueryParameters($filter));
        if ($offset > 0) {
            $q->setFirstResult($offset);
        }
        if ($elemcount > 0) {
            $q->setMaxResults($elemcount);
        }
        return $q->getResult();
    }

    public function getCount($filter) {
        $q = $this->_em->createQuery('SELECT COUNT(_xx)'
            . ' FROM Entities\Partnercimketorzs _xx'
            . ' JOIN _xx.kategoria ck '
            . $this->getFilterString($filter));
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getSingleScalarResult();
    }

    public function getAllNative() {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('partner_id', 'partner_id');
        $rsm->addScalarResult('cimketorzs_id', 'cimketorzs_id');
        $q = $this->_em->createNativeQuery('SELECT * FROM partner_cimkek', $rsm);
        $res = $q->getScalarResult();
        $ret = $res;
        return $ret;
    }

    public function getPartnerIdsWithCimke($cimkekodok) {
        $filter = new FilterDescriptor();
        $filter->addFilter('id', null, $cimkekodok);
        $q = $this->_em->createQuery('SELECT t.id'
            . ' FROM Entities\Partnercimketorzs _xx'
            . ' JOIN _xx.partnerek t '
            . $this->getFilterString($filter));
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getScalarResult();
    }

    public function getPartnerIdsWithCimkeAnd($cimkekodok) {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('partner_id', 'partner_id');
        $r = array();
        foreach ($cimkekodok as $cimkefej) {
            $cimkestr = implode(',', $cimkefej);
            $q = $this->_em->createNativeQuery('SELECT tc.partner_id FROM partner_cimkek tc '
                . 'LEFT OUTER JOIN partner t ON (t.id=tc.partner_id) '
                . 'WHERE (tc.cimketorzs_id IN (' . $cimkestr . ')) AND (t.inaktiv=0) AND (t.lathato=1)', $rsm);
            $res = $q->getScalarResult();
            foreach ($res as $sor) {
                if (array_key_exists($sor['partner_id'], $r) && $r[$sor['partner_id']] > 0) {
                    $r[$sor['partner_id']]++;
                }
                else {
                    $r[$sor['partner_id']] = 1;
                }
            }
        }
        $kelldb = count($cimkekodok);
        $ret = array();
        foreach ($r as $tid => $db) {
            if ($db == $kelldb) {
                $ret[] = array('partner_id' => $tid);
            }
        }
        unset($r);
        unset($res);
        return $ret;
    }

    public function getByNevAndKategoria($nev, $kat) {
        $q = $this->_em->createQuery('SELECT _xx'
            . ' FROM Entities\Partnercimketorzs _xx'
            . ' WHERE _xx.nev=?1 AND _xx.kategoria=?2');
        $q->setParameter(1, $nev);
        $q->setParameter(2, $kat);
        $t = $q->getResult();
        if ($t) {
            return $t[0];
        }
        return false;
    }

    public function getCimkeNevek($cimkefilter) {
        $cimkenevek = array();
        if ($cimkefilter) {
            if (is_array($cimkefilter)) {
                $cimkekodok = implode(',', $cimkefilter);
            }
            else {
                $cimkekodok = $cimkefilter;
            }
            if ($cimkekodok) {
                $q = \mkw\store::getEm()->createQuery('SELECT pc.nev FROM Entities\Partnercimketorzs pc WHERE pc.id IN (' . $cimkekodok . ')');
                $res = $q->getScalarResult();
                foreach ($res as $sor) {
                    $cimkenevek[] = $sor['nev'];
                }
            }
        }
        return $cimkenevek;
    }

}
