<?php

namespace Entities;

use Doctrine\ORM\EntityRepository;

class BankbizonylatfejRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\Bankbizonylatfej');
        $this->setOrders([
            '1' => ['caption' => 'biz.szám szerint csökkenő', 'order' => ['_xx.id' => 'DESC']],
            '2' => ['caption' => 'biz.szám szerint növekvő', 'order' => ['_xx.id' => 'ASC']],
            '3' => ['caption' => 'kelt szerint csökkenő', 'order' => ['_xx.kelt' => 'DESC', '_xx.id' => 'DESC']],
            '4' => ['caption' => 'kelt szerint növekvő', 'order' => ['_xx.kelt' => 'DESC', '_xx.id' => 'DESC']],
            '5' => ['caption' => 'er.biz.szám szerint csökkenő', 'order' => ['_xx.erbizonylatszam' => 'DESC']],
            '6' => ['caption' => 'er.biz.szám szerint növekvő', 'order' => ['_xx.erbizonylatszam' => 'ASC']]
        ]);
    }

    public function findWithJoins($id)
    {
        return parent::findWithJoins((string)$id);
    }

    public function getWithJoins($filter, $order, $offset = 0, $elemcount = 0)
    {
        $q = $this->_em->createQuery(
            'SELECT _xx'
            . ' FROM Entities\Bankbizonylatfej _xx'
            . ' JOIN _xx.bizonylattetelek bt'
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
            . ' FROM Entities\Bankbizonylatfej _xx'
            . $this->getFilterString($filter)
        );
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getSingleScalarResult();
    }

}
