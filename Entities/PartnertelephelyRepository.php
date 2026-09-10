<?php

namespace Entities;

use mkwhelpers\FilterDescriptor;

class PartnertelephelyRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(Partnertelephely::class);
    }

    public function getWithJoins($filter, $order = [], $offset = 0, $elemcount = 0): mixed
    {
        $q = $this->_em->createQuery(
            'SELECT _xx,partner,orszag'
            . ' FROM Entities\Partnertelephely _xx'
            . ' LEFT JOIN _xx.partner partner'
            . ' LEFT JOIN _xx.orszag orszag'
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

    /**
     * @return \Entities\Partnertelephely[]
     */
    public function getByPartner($partner)
    {
        if (!$partner) {
            return [];
        }
        $filter = new FilterDescriptor();
        $filter->addFilter('partner', '=', $partner);
        return $this->getWithJoins($filter, ['nev' => 'ASC']);
    }

}
