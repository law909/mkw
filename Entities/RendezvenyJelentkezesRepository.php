<?php

namespace Entities;

class RendezvenyJelentkezesRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\RendezvenyJelentkezes');
        $this->setOrders([
            '1' => ['caption' => 'dátum szerint csökkenő', 'order' => ['_xx.datum' => 'DESC']],
            '2' => ['caption' => 'dátum szerint növekvő', 'order' => ['_xx.datum' => 'ASC']]
        ]);
    }

    public function getWithJoins($filter, $order, $offset = 0, $elemcount = 0): mixed
    {
        $q = $this->_em->createQuery(
            'SELECT _xx,p,f,r'
            . ' FROM Entities\RendezvenyJelentkezes _xx'
            . ' LEFT JOIN _xx.partner p'
            . ' LEFT JOIN _xx.fizmod f'
            . ' LEFT JOIN _xx.rendezveny r'
            . $this->getFilterString($filter)
            . $this->getOrderString($order)
        );
        $q->setParameters($this->getQueryParameters($filter));
        if ($offset > 0) {
            $q->setFirstResult($offset);
        }
        if ($elemcount > 0) {
            $q->setMaxResults($elemcount);
        }
        return $q->getResult();
    }

    public function getCount($filter)
    {
        $q = $this->_em->createQuery(
            'SELECT COUNT(_xx)'
            . ' FROM Entities\RendezvenyJelentkezes _xx'
            . $this->getFilterString($filter)
        );
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getSingleScalarResult();
    }

}
