<?php

namespace Entities;

use Doctrine\ORM\EntityRepository;

class BlogposztRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\Blogposzt');
        $this->setOrders(array(
            '1' => array('caption' => 'megjelenés szerint csökkenő', 'order' => array('_xx.megjelenesdatum' => 'DESC','_xx.id' => 'ASC')),
            '2' => array('caption' => 'megjelenés szerint növekvő', 'order' => array('_xx.megjelenesdatum' => 'ASC','_xx.id' => 'ASC'))
        ));
    }

    public function findWithJoins($id) {
        return parent::findWithJoins((string)$id);
    }

    public function getWithJoins($filter, $order, $offset = 0, $elemcount = 0) {
        $q = $this->_em->createQuery('SELECT _xx'
            . ' FROM Entities\Blogposzt _xx'
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
            . ' FROM Entities\Blogposzt _xx'
            . $this->getFilterString($filter));
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getSingleScalarResult();
    }

    public function getFeedBlogposztok() {
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('lathato', '=', true);

        $order = array('_xx.megjelenesdatum' => 'DESC', '_xx.id' => 'DESC');

        $res = $this->getAll($filter, $order, 0, \mkw\store::getParameter(\mkw\consts::Feedblogdb, 20));
        return $res;
    }

    public function getByTermekfa($parent) {
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter(array('_xx.termekfa1', '_xx.termekfa2', '_xx.termekfa3'), '=', $parent->getId());
        $filter->addFilter('lathato', '=', true);
        $order = array('_xx.megjelenesdatum' => 'DESC');

        $res = $this->getAll($filter, $order);
        return $res;
    }
}
