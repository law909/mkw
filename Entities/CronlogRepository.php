<?php

namespace Entities;

class CronlogRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(Cronlog::class);
        $this->setOrders([
            '1' => ['caption' => 'idő szerint csökkenő', 'order' => ['_xx.kezdet' => 'DESC', '_xx.id' => 'DESC']],
            '2' => ['caption' => 'idő szerint növekvő', 'order' => ['_xx.kezdet' => 'ASC', '_xx.id' => 'ASC']],
            '3' => ['caption' => 'feladat szerint', 'order' => ['_xx.feladat' => 'ASC', '_xx.kezdet' => 'DESC']],
            '4' => ['caption' => 'időtartam szerint csökkenő', 'order' => ['_xx.idotartam' => 'DESC']]
        ]);
    }

    /**
     * A szűrőben kínált feladatnevek: azok, amikről van napló. A regiszterben már nem szereplő
     * feladatnév is köztük van, különben a régi sorokra nem lehetne szűrni.
     */
    public function getFeladatList()
    {
        $q = $this->_em->createQuery(
            'SELECT DISTINCT _xx.feladat FROM Entities\Cronlog _xx ORDER BY _xx.feladat'
        );
        return array_column($q->getScalarResult(), 'feladat');
    }

    /**
     * @param int $napok ennél régebbi sorokat töröl
     *
     * @return int ahány sort elvitt
     */
    public function deleteOlderThan($napok)
    {
        $q = $this->_em->createQuery('DELETE FROM Entities\Cronlog _xx WHERE _xx.kezdet < :hatar');
        $q->setParameter('hatar', new \DateTime('-' . (int)$napok . ' days'));
        return (int)$q->execute();
    }

}
