<?php

namespace Entities;

class IdopontreszvetelRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(Idopontreszvetel::class);
        $this->setOrders([
            '1' => ['caption' => 'dátum szerint csökkenő', 'order' => ['_xx.datum' => 'DESC', '_xx.id' => 'DESC']],
            '2' => ['caption' => 'dátum szerint növekvő', 'order' => ['_xx.datum' => 'ASC', '_xx.id' => 'ASC']],
            '3' => ['caption' => 'résztvevő szerint', 'order' => ['_xx.partnernev' => 'ASC']],
        ]);
    }

    public function getWithJoins($filter, $order = [], $offset = 0, $elemcount = 0): mixed
    {
        $q = $this->_em->createQuery(
            'SELECT _xx,partner,tanar,idopont,idoponttema,jogahelyszin'
            . ' FROM Entities\Idopontreszvetel _xx'
            . ' LEFT JOIN _xx.partner partner'
            . ' LEFT JOIN _xx.tanar tanar'
            . ' LEFT JOIN _xx.idopont idopont'
            . ' LEFT JOIN _xx.idoponttema idoponttema'
            . ' LEFT JOIN _xx.jogahelyszin jogahelyszin'
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

}
