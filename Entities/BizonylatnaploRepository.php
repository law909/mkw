<?php

namespace Entities;

class BizonylatnaploRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(Bizonylatnaplo::class);
        $this->setOrders([
            '1' => ['caption' => 'dátum szerint', 'order' => ['_xx.created' => 'DESC']]
        ]);
    }

    /**
     * Egy bizonylat naplója időrendi (növekvő) sorrendben.
     *
     * @param string $bizonylatfejid
     * @return \Entities\Bizonylatnaplo[]
     */
    public function getByBizonylatfej($bizonylatfejid)
    {
        $q = $this->_em->createQuery(
            'SELECT _xx FROM Entities\Bizonylatnaplo _xx'
            . ' WHERE _xx.bizonylatfej = :bizfej'
            . ' ORDER BY _xx.created ASC, _xx.id ASC'
        );
        $q->setParameter('bizfej', $bizonylatfejid);
        return $q->getResult();
    }

    /**
     * @return \Entities\Bizonylatnaplo[]
     */
    public function getByBankbizonylatfej($bizonylatfejid)
    {
        return $this->getByRelation('bankbizonylatfej', $bizonylatfejid);
    }

    /**
     * @return \Entities\Bizonylatnaplo[]
     */
    public function getByPenztarbizonylatfej($bizonylatfejid)
    {
        return $this->getByRelation('penztarbizonylatfej', $bizonylatfejid);
    }

    private function getByRelation(string $relation, $bizonylatfejid)
    {
        $q = $this->_em->createQuery(
            'SELECT _xx FROM Entities\Bizonylatnaplo _xx'
            . ' WHERE _xx.' . $relation . ' = :bizfej'
            . ' ORDER BY _xx.created ASC, _xx.id ASC'
        );
        $q->setParameter('bizfej', $bizonylatfejid);
        return $q->getResult();
    }

}
