<?php

namespace Entities;

use Doctrine\ORM\Query\ResultSetMapping;
use mkwhelpers\FilterDescriptor;

class TermekArRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\TermekAr');
        $this->setOrders(array(
            '1' => array('caption' => 'azonosító szerint növekvő', 'order' => array('_xx.azonosito' => 'ASC'))
        ));
    }

    public function getByTermek($termek)
    {
        $filter = new FilterDescriptor();
        $filter->addFilter('termek', '=', $termek);
        return $this->getAll($filter, array('created' => 'ASC'));
    }

    public function getExistingAzonositok()
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('azonosito', 'azonosito');
        $q = $this->_em->createNativeQuery('SELECT DISTINCT azonosito FROM termekar ORDER BY azonosito', $rsm);
        return $q->getScalarResult();
    }

    public function getExistingArsavok()
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('azonosito', 'azonosito');
        $rsm->addScalarResult('nev', 'valutanem');
        $rsm->addScalarResult('valutanem_id', 'valutanemid');
        $q = $this->_em->createNativeQuery('SELECT DISTINCT v.nev, azonosito, valutanem_id FROM termekar t LEFT OUTER JOIN valutanem v ON (t.valutanem_id=v.id) ORDER BY azonosito',
            $rsm);
        return $q->getScalarResult();
    }

    public function getArsav($termek, $valutanem = null, $azonosito = null)
    {
        if (!$azonosito) {
            $azonosito = \mkw\store::getParameter(\mkw\consts::Arsav);
        }
        if (!$valutanem) {
            $valutanem = \mkw\store::getEm()->getRepository('Entities\Valutanem')->find(\mkw\store::getParameter(\mkw\consts::Valutanem));
        }
        $filter = new FilterDescriptor();
        $filter
            ->addFilter('termek', '=', $termek)
            ->addFilter('valutanem', '=', $valutanem)
            ->addFilter('azonosito', '=', $azonosito);

        $sav = $this->getAll($filter, array());
        if ($sav && is_array($sav)) {
            return $sav[0];
        }
        return $sav;
    }

}
