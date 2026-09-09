<?php

namespace Entities;

use mkwhelpers\FilterDescriptor;

class DolgozoszabadsagRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(Dolgozoszabadsag::class);
        $this->setOrders([
            '1' => ['caption' => 'dátum szerint csökkenő', 'order' => ['_xx.datumtol' => 'DESC', 'd.nev' => 'ASC']],
            '2' => ['caption' => 'dátum szerint növekvő', 'order' => ['_xx.datumtol' => 'ASC', 'd.nev' => 'ASC']],
            '3' => ['caption' => 'dolgozó és dátum szerint', 'order' => ['d.nev' => 'ASC', '_xx.datumtol' => 'ASC']],
        ]);
    }

    public function getWithJoins($filter, $order, $offset = 0, $elemcount = 0): mixed
    {
        $q = $this->_em->createQuery(
            'SELECT _xx,d'
            . ' FROM Entities\Dolgozoszabadsag _xx'
            . ' LEFT JOIN _xx.dolgozo d'
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
            . ' FROM Entities\Dolgozoszabadsag _xx'
            . ' LEFT JOIN _xx.dolgozo d'
            . $this->getFilterString($filter)
        );
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getSingleScalarResult();
    }

    /**
     * A dolgozó azon távollétei, amelyek belelógnak az időszakba.
     *
     * @return \Entities\Dolgozoszabadsag[]
     */
    public function getByDolgozoAndIdoszak($dolgozo, \DateTime $tol, \DateTime $ig)
    {
        $filter = new FilterDescriptor();
        $filter->addFilter('dolgozo', '=', $dolgozo);
        $filter->addFilter('datumtol', '<=', $ig->format(\mkw\store::$SQLDateFormat));
        $filter->addFilter('datumig', '>=', $tol->format(\mkw\store::$SQLDateFormat));
        return $this->getAll($filter, ['datumtol' => 'ASC']);
    }
}
