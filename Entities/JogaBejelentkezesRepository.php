<?php
namespace Entities;

use mkwhelpers\FilterDescriptor;

class JogaBejelentkezesRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname(JogaBejelentkezes::class);
        $this->setOrders(array(
            '1' => array('caption' => 'dátum és tanár szerint csökkenő', 'order' => array('_xx.datum' => 'DESC', 'ta.nev' => 'ASC', 'pa.nev' => 'ASC')),
            '2' => array('caption' => 'dátum és tanár szerint növekvő', 'order' => array('_xx.datum' => 'ASC', 'ta.nev' => 'ASC', 'pa.nev' => 'ASC')),
            '3' => array('caption' => 'tanár és dátum szerint növekvő', 'order' => array('ta.nev' => 'ASC', '_xx.datum' => 'ASC', 'pa.nev' => 'ASC'))
        ));
    }

    public function getWithJoins($filter, $order = array(), $offset = 0, $elemcount = 0) {
        $q = $this->_em->createQuery('SELECT _xx,o '
            . ' FROM Entities\JogaBejelentkezes _xx'
            . ' LEFT JOIN _xx.orarend o'
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
            . ' FROM Entities\JogaBejelentkezes _xx'
            . ' LEFT JOIN _xx.orarend o'
            . $this->getFilterString($filter));
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getSingleScalarResult();
    }

    public function getAdottOraCount($nap, $orarend) {
        $hf = new FilterDescriptor();
        $hf->addFilter('datum', '=', $nap);
        $hf->addFilter('orarend', '=', $orarend);
        $r = $this->getCount($hf);
        return $r;
    }

}