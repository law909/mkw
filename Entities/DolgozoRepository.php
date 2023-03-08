<?php

namespace Entities;

class DolgozoRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\Dolgozo');
        $this->setOrders([
            '1' => ['caption' => 'név szerint növekvő', 'order' => ['_xx.nev' => 'ASC']]
        ]);
        $btch = [];
        $btch['sendemailsablon'] = 'Email sablon küldés';
        $this->setBatches($btch);
    }

    public function getAllForSelectList($filter, $order, $offset = 0, $elemcount = 0)
    {
        $q = $this->_em->createQuery(
            'SELECT _xx.id,_xx.nev,_xx.email '
            . ' FROM Entities\Dolgozo _xx'
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
        return $q->getScalarResult();
    }

    public function getWithJoins($filter, $order, $offset = 0, $elemcount = 0)
    {
        $q = $this->_em->createQuery(
            'SELECT _xx,mk'
            . ' FROM Entities\Dolgozo _xx'
            . ' LEFT JOIN _xx.munkakor mk'
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
            . ' FROM Entities\Dolgozo _xx'
            . ' LEFT JOIN _xx.munkakor mk'
            . $this->getFilterString($filter)
        );
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getSingleScalarResult();
    }

    public function checkloggedin()
    {
        if (isset(\mkw\store::getPubAdminSession()->pk)) {
            $users = $this->findById(\mkw\store::getPubAdminSession()->pk);
            return count($users) == 1;
        }
        return false;
    }

    public function getLoggedInUser()
    {
        if ($this->checkloggedin()) {
            return $this->find(\mkw\store::getPubAdminSession()->pk);
        }
        return null;
    }


}
