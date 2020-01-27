<?php
namespace Entities;

class JogaReszvetelRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\JogaReszvetel');
        $this->setOrders(array(
            '1' => array('caption' => 'dátum és tanár szerint növekvő', 'order' => array('_xx.datum' => 'ASC', '_xx.tanarnev' => 'ASC')),
            '2' => array('caption' => 'tanár és dátum szerint növekvő', 'order' => array('_xx.tanarnev' => 'ASC', '_xx.datum' => 'ASC'))
        ));
    }

    public function getWithJoins($filter, $order = array(), $offset = 0, $elemcount = 0) {
        $q = $this->_em->createQuery('SELECT _xx,jt,jot,f,p,ta,pa,t '
            . ' FROM Entities\JogaReszvetel _xx'
            . ' LEFT JOIN _xx.jogaterem jt'
            . ' LEFT JOIN _xx.jogaoratipus jot'
            . ' LEFT JOIN _xx.fizmod f'
            . ' LEFT JOIN _xx.penztar p'
            . ' LEFT JOIN _xx.tanar ta'
            . ' LEFT JOIN _xx.partner pa'
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
        return $q->getResult();
    }

    public function getCount($filter) {
        $q = $this->_em->createQuery('SELECT COUNT(_xx)'
            . ' FROM Entities\JogaReszvetel _xx'
            . ' LEFT JOIN _xx.jogaterem jt'
            . ' LEFT JOIN _xx.jogaoratipus jot'
            . ' LEFT JOIN _xx.fizmod f'
            . ' LEFT JOIN _xx.penztar p'
            . ' LEFT JOIN _xx.tanar ta'
            . ' LEFT JOIN _xx.partner pa'
            . $this->getFilterString($filter));
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getSingleScalarResult();
    }

    public function getTanarOsszesito($filter, $honap = 1) {
        $q = $this->_em->createQuery('SELECT SUM(_xx.jutalek) AS jutalek,ta.nev,ta.id,ta.havilevonas*' . $honap . ' AS havilevonas'
            . ' FROM Entities\JogaReszvetel _xx'
            . ' LEFT JOIN _xx.tanar ta'
            . $this->getFilterString($filter)
            . ' GROUP BY ta.id'
            . ' ORDER BY ta.nev');
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getResult();
    }

    public function getTermekOsszesito($filter, $honap = 1) {
        $q = $this->_em->createQuery('SELECT COUNT(_xx) AS db,t.nev,t.id'
            . ' FROM Entities\JogaReszvetel _xx'
            . ' LEFT JOIN _xx.termek t'
            . $this->getFilterString($filter)
            . ' GROUP BY t.id'
            . ' ORDER BY t.nev');
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getResult();
    }
}