<?php

namespace Entities;

class BizonylatvaltozasnaploRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(Bizonylatvaltozasnaplo::class);
        $this->setOrders([
            '1' => ['caption' => 'dátum szerint', 'order' => ['_xx.created' => 'DESC']]
        ]);
    }

    /**
     * Egy bizonylathoz tartozó változásnapló-sorok időrendi (növekvő) sorrendben.
     *
     * @param string $bizonylatfejid
     * @return \Entities\Bizonylatvaltozasnaplo[]
     */
    public function getByBizonylatfej($bizonylatfejid)
    {
        $q = $this->_em->createQuery(
            'SELECT _xx FROM Entities\Bizonylatvaltozasnaplo _xx'
            . ' WHERE _xx.bizonylatfej = :bizfej'
            . ' ORDER BY _xx.created ASC, _xx.id ASC'
        );
        $q->setParameter('bizfej', $bizonylatfejid);
        return $q->getResult();
    }

}
