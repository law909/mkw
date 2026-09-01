<?php

namespace Entities;

class IdopontRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(Idopont::class);
        // az ismétlődőknek nincs kezdet dátumuk, ezért a nap+idő is bekerül a rendezésbe
        $this->setOrders([
            '1' => ['caption' => 'kezdet szerint csökkenő', 'order' => ['_xx.kezdet' => 'DESC', '_xx.nap' => 'DESC', '_xx.kezdetido' => 'DESC']],
            '2' => ['caption' => 'kezdet szerint növekvő', 'order' => ['_xx.kezdet' => 'ASC', '_xx.nap' => 'ASC', '_xx.kezdetido' => 'ASC']],
            '3' => ['caption' => 'név szerint növekvő', 'order' => ['_xx.nev' => 'ASC']],
            '4' => ['caption' => 'tanár, majd kezdet szerint', 'order' => ['dolgozo.nev' => 'ASC', '_xx.kezdet' => 'ASC', '_xx.nap' => 'ASC', '_xx.kezdetido' => 'ASC']],
            '5' => ['caption' => 'téma, majd kezdet szerint', 'order' => ['idoponttema.nev' => 'ASC', '_xx.kezdet' => 'ASC', '_xx.nap' => 'ASC', '_xx.kezdetido' => 'ASC']],
            '6' => ['caption' => 'ismétlődő nap és idő szerint', 'order' => ['_xx.nap' => 'ASC', '_xx.kezdetido' => 'ASC']],
        ]);
    }

    public function getWithJoins($filter, $order = [], $offset = 0, $elemcount = 0): mixed
    {
        $q = $this->_em->createQuery(
            'SELECT _xx,dolgozo,idoponttema,jogahelyszin,termek,idopontallapot'
            . ' FROM Entities\Idopont _xx'
            . ' LEFT JOIN _xx.dolgozo dolgozo'
            . ' LEFT JOIN _xx.idoponttema idoponttema'
            . ' LEFT JOIN _xx.jogahelyszin jogahelyszin'
            . ' LEFT JOIN _xx.termek termek'
            . ' LEFT JOIN _xx.idopontallapot idopontallapot'
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

    public function findWithJoins($id)
    {
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('id', '=', $id);
        $rec = $this->getWithJoins($filter, []);
        if ($rec) {
            return $rec[0];
        }
        return null;
    }

    public function getCount($filter)
    {
        $q = $this->_em->createQuery(
            'SELECT COUNT(_xx)'
            . ' FROM Entities\Idopont _xx'
            . ' LEFT JOIN _xx.dolgozo dolgozo'
            . ' LEFT JOIN _xx.idoponttema idoponttema'
            . ' LEFT JOIN _xx.jogahelyszin jogahelyszin'
            . ' LEFT JOIN _xx.termek termek'
            . ' LEFT JOIN _xx.idopontallapot idopontallapot'
            . $this->getFilterString($filter)
        );
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getSingleScalarResult();
    }

}
