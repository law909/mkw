<?php

namespace Entities;

use mkwhelpers\FilterDescriptor;

class PartnerGyartoKedvezmenyRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(PartnerGyartoKedvezmeny::class);
    }

    public function getWithJoins($filter, $order = [], $offset = 0, $elemcount = 0): mixed
    {
        $q = $this->_em->createQuery(
            'SELECT _xx,partner,gyarto'
            . ' FROM Entities\PartnerGyartoKedvezmeny _xx'
            . ' LEFT JOIN _xx.partner partner'
            . ' LEFT JOIN _xx.gyarto gyarto'
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
     * @return \Entities\PartnerGyartoKedvezmeny|null
     */
    public function getByPartnerGyarto($partner, $gyarto)
    {
        if (!$partner || !$gyarto) {
            return null;
        }
        $filter = new FilterDescriptor();
        $filter
            ->addFilter('partner', '=', $partner)
            ->addFilter('gyarto', '=', $gyarto);

        $kdv = $this->getWithJoins($filter, []);
        return $kdv ? $kdv[0] : null;
    }

    public function getByPartner($partner)
    {
        $filter = new FilterDescriptor();
        $filter->addFilter('partner', '=', $partner);
        return $this->getWithJoins($filter, []);
    }

}
