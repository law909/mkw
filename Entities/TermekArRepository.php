<?php

namespace Entities;

use Doctrine\ORM\Query\ResultSetMapping;

class TermekArRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\TermekAr');
        $this->setOrders(array(
            '1' => array('caption' => 'azonosító szerint növekvő', 'order' => array('_xx.azonosito' => 'ASC'))
        ));
    }

    public function getByTermek($termek) {
        $filter = array();
        $filter['fields'][] = 'termek';
        $filter['clauses'][] = '=';
        $filter['values'][] = $termek;
        return $this->getAll($filter, array('created' => 'ASC'));
    }

    public function getExistingAzonositok() {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('azonosito', 'azonosito');
        $q = $this->_em->createNativeQuery('SELECT DISTINCT azonosito FROM termekar ORDER BY azonosito', $rsm);
        return $q->getScalarResult();
    }

    public function getExistingArsavok() {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('azonosito', 'azonosito');
        $rsm->addScalarResult('nev', 'valutanem');
        $rsm->addScalarResult('valutanem_id', 'valutanemid');
        $q = $this->_em->createNativeQuery('SELECT DISTINCT v.nev, azonosito, valutanem_id FROM termekar t LEFT OUTER JOIN valutanem v ON (t.valutanem_id=v.id) ORDER BY azonosito', $rsm);
        return $q->getScalarResult();
    }

    public function getArsav($termek, $valutanem = null, $azonosito = null) {
        if (!$azonosito) {
            $azonosito = \mkw\Store::getParameter(\mkw\consts::Arsav);
        }
        if (!$valutanem) {
            $valutanem = \mkw\Store::getEm()->getRepository('Entities\Valutanem')->find(\mkw\Store::getParameter(\mkw\consts::Valutanem));
        }
        $filter = array();
        $filter['fields'][] = 'termek';
        $filter['clauses'][] = '=';
        $filter['values'][] = $termek;
        $filter['fields'][] = 'valutanem';
        $filter['clauses'][] = '=';
        $filter['values'][] = $valutanem;
        $filter['fields'][] = 'azonosito';
        $filter['clauses'][] = '=';
        $filter['values'][] = $azonosito;
        $sav = $this->getAll($filter, array());
        if (is_array($sav)) {
            return $sav[0];
        }
        return $sav;
    }

    /* Ha van JOIN, ezek akkor kellenek
      public function getWithJoins($filter,$order,$offset=0,$elemcount=0) {
      $a=$this->alias;
      $q=$this->_em->createQuery('SELECT '.$a
      .' FROM '.$this->entityname.' '.$a
      .$this->getFilterString($filter)
      .$this->getOrderString($order));
      $q->setParameters($this->getQueryParameters($filter));
      if ($offset>0) {
      $q->setFirstResult($offset);
      }
      if ($elemcount>0) {
      $q->setMaxResults($elemcount);
      }
      return $q->getResult();
      }

      public function getCount($filter) {
      $a=$this->alias;
      $q=$this->_em->createQuery('SELECT COUNT('.$a.') FROM '.$this->entityname.' '.$a
      .$this->getFilterString($filter));
      $q->setParameters($this->getQueryParameters($filter));
      return $q->getSingleScalarResult();
      }
     */
}
