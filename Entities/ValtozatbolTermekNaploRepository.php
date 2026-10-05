<?php

namespace Entities;

class ValtozatbolTermekNaploRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(ValtozatbolTermekNaplo::class);
        $this->setOrders([
            '1' => ['caption' => 'időpont szerint csökkenő', 'order' => ['_xx.created' => 'DESC', '_xx.id' => 'DESC']],
            '2' => ['caption' => 'időpont szerint növekvő', 'order' => ['_xx.created' => 'ASC', '_xx.id' => 'ASC']],
        ]);
    }

    private function getFrom()
    {
        return ' FROM Entities\ValtozatbolTermekNaplo _xx'
            . ' LEFT JOIN _xx.termek t'
            . ' LEFT JOIN _xx.ujtermek u';
    }

    public function getCount($filter)
    {
        return $this->_em->createQuery('SELECT COUNT(_xx)' . $this->getFrom() . $this->getFilterString($filter))
            ->setParameters($this->getQueryParameters($filter))
            ->getSingleScalarResult();
    }

    public function getAll($filter = [], $order = [], $offset = 0, $elemcount = 0)
    {
        $q = $this->_em->createQuery('SELECT _xx, t, u' . $this->getFrom() . $this->getFilterString($filter) . $this->getOrderString($order))
            ->setParameters($this->getQueryParameters($filter));
        if ($offset > 0) {
            $q->setFirstResult($offset);
        }
        if ($elemcount > 0) {
            $q->setMaxResults($elemcount);
        }
        return $q->getResult();
    }
}
