<?php

namespace Entities;

use mkwhelpers\FilterDescriptor;

class UnasoutboxRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(Unasoutbox::class);
        $this->setOrders([
            '1' => ['caption' => 'azonosító szerint csökkenő', 'order' => ['_xx.id' => 'DESC']],
            '2' => ['caption' => 'azonosító szerint növekvő', 'order' => ['_xx.id' => 'ASC']],
            '3' => ['caption' => 'UNAS azonosító szerint', 'order' => ['_xx.unaskey' => 'ASC', '_xx.id' => 'ASC']],
        ]);
    }

    /**
     * A legrégebbi függő sorok. Sorrend azonosító szerint: egy rendelésre több sor is jöhet
     * (státusz, majd számla), és azoknak ebben a sorrendben kell kimenniük.
     *
     * @return Unasoutbox[]
     */
    public function getPending($limit = 50)
    {
        $filter = new FilterDescriptor();
        $filter->addFilter('allapot', '=', Unasoutbox::ALLAPOTFUGGO);
        return $this->getAll($filter, ['_xx.id' => 'ASC'], 0, $limit);
    }

    /**
     * Ugyanarra a rendelésre ugyanabból a típusból egyszerre egy függő sor: a visszaírás a
     * bizonylat AKKORI állapotát küldi, tehát a régi sor amúgy is fölösleges.
     *
     * @return Unasoutbox|null
     */
    public function findPending($unaskey, $tipus)
    {
        return $this->findOneBy([
            'unaskey' => $unaskey,
            'tipus' => $tipus,
            'allapot' => Unasoutbox::ALLAPOTFUGGO,
        ]);
    }

    /** @return array<string,int> állapot => darab */
    public function getCountByAllapot()
    {
        $rows = $this->_em->createQuery(
            'SELECT _xx.allapot AS allapot, COUNT(_xx.id) AS db FROM Entities\Unasoutbox _xx GROUP BY _xx.allapot'
        )->getScalarResult();
        $result = [];
        foreach ($rows as $row) {
            $result[$row['allapot']] = (int)$row['db'];
        }
        return $result;
    }

}
