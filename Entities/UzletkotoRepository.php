<?php
namespace Entities;

use mkwhelpers\FilterDescriptor;

class UzletkotoRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\Uzletkoto');
        $this->setOrders(array(
            '1' => array('caption' => 'név szerint növekvő', 'order' => array('nev' => 'ASC')),
            '2' => array('caption' => 'cím szerint növekvő', 'order' => array('irszam' => 'ASC', 'varos' => 'ASC', 'utca' => 'ASC'))
        ));
        $this->setBatches(array(
            '1' => 'címke hozzáadás',
            '2' => 'címke törlés'
        ));
    }

    public function getWithJoins($filter, $order, $offset = 0, $elemcount = 0) {
        $q = $this->_em->createQuery('SELECT _xx'
            . ' FROM Entities\Uzletkoto _xx'
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
            . ' FROM Entities\Uzletkoto _xx'
            . $this->getFilterString($filter));
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getSingleScalarResult();
    }

    public function findByIdSessionid($id, $sessionid) {
        $filter = new FilterDescriptor();
        $filter
            ->addFilter('id', '=', $id)
            ->addFilter('sessionid', '=', $sessionid);
        return $this->getAll($filter, array());
    }

    public function checkloggedin() {
        if (isset(\mkw\store::getMainSession()->uk)) {
            $users = $this->findByIdSessionid(\mkw\store::getMainSession()->uk, \Zend_Session::getId());
            return count($users) == 1;
        }
        return false;
    }

    public function getLoggedInUK() {
        if ($this->checkloggedin()) {
            return $this->find(\mkw\store::getMainSession()->uk);
        }
        return null;
    }

}