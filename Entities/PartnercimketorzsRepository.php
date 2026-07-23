<?php

namespace Entities;

use Doctrine\ORM\Query\ResultSetMapping;
use mkwhelpers\FilterDescriptor;

class PartnercimketorzsRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\Partnercimketorzs');
        $this->setOrders([
            '1' => ['caption' => 'név szerint növekvő', 'order' => ['_xx.nev' => 'ASC']],
            '2' => ['caption' => 'csoport szerint növekvő', 'order' => ['ck.nev' => 'ASC', '_xx.nev' => 'ASC']]
        ]);
        $this->setBatches([
            '1' => 'áthelyezés másik címkecsoportba'
        ]);
    }

    public function getWithJoins($filter, $order, $offset = 0, $elemcount = 0): mixed
    {
        $q = $this->_em->createQuery(
            'SELECT _xx,ck'
            . ' FROM Entities\Partnercimketorzs _xx'
            . ' JOIN _xx.kategoria ck '
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
            . ' FROM Entities\Partnercimketorzs _xx'
            . ' JOIN _xx.kategoria ck '
            . $this->getFilterString($filter)
        );
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getSingleScalarResult();
    }

    public function getAllNative()
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('partner_id', 'partner_id');
        $rsm->addScalarResult('cimketorzs_id', 'cimketorzs_id');
        $q = $this->_em->createNativeQuery('SELECT * FROM partner_cimkek', $rsm);
        $res = $q->getScalarResult();
        $ret = $res;
        return $ret;
    }

    public function getPartnerIdsWithCimke($cimkekodok)
    {
        $filter = new FilterDescriptor();
        $filter->addFilter('id', null, $cimkekodok);
        $q = $this->_em->createQuery(
            'SELECT t.id'
            . ' FROM Entities\Partnercimketorzs _xx'
            . ' JOIN _xx.partnerek t '
            . $this->getFilterString($filter)
        );
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getScalarResult();
    }

    public function getPartnerIdsWithCimkeAnd($cimkekodok)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('partner_id', 'partner_id');
        $r = [];
        foreach ($cimkekodok as $cimkefej) {
            $cimkestr = implode(',', $cimkefej);
            $q = $this->_em->createNativeQuery(
                'SELECT tc.partner_id FROM partner_cimkek tc '
                . 'LEFT OUTER JOIN partner t ON (t.id=tc.partner_id) '
                . 'WHERE (tc.cimketorzs_id IN (' . $cimkestr . ')) AND (t.inaktiv=0) AND (t.lathato=1)',
                $rsm
            );
            $res = $q->getScalarResult();
            foreach ($res as $sor) {
                if (array_key_exists($sor['partner_id'], $r) && $r[$sor['partner_id']] > 0) {
                    $r[$sor['partner_id']]++;
                } else {
                    $r[$sor['partner_id']] = 1;
                }
            }
        }
        $kelldb = count($cimkekodok);
        $ret = [];
        foreach ($r as $tid => $db) {
            if ($db == $kelldb) {
                $ret[] = ['partner_id' => $tid];
            }
        }
        unset($r);
        unset($res);
        return $ret;
    }

    public function getByNevAndKategoria($nev, $kat)
    {
        $q = $this->_em->createQuery(
            'SELECT _xx'
            . ' FROM Entities\Partnercimketorzs _xx'
            . ' WHERE _xx.nev=?1 AND _xx.kategoria=?2'
        );
        $q->setParameter(1, $nev);
        $q->setParameter(2, $kat);
        $t = $q->getResult();
        if ($t) {
            return $t[0];
        }
        return false;
    }

    /**
     * Kötegelt címkenév-lekérdezés sok partnerre egyetlen query-vel.
     * A Partner::getCimkeNevek() soronkénti (N+1) hívása helyett — a partnerlista számára.
     *
     * A visszaadott alak megegyezik a Partner::getCimkeNevek() alakjával, hogy a
     * loadVars() ugyanúgy tudja használni.
     *
     * @param int[] $partnerids
     *
     * @return array [ partnerid => [ ['nev' => ...], ... ] ]
     */
    public function getNevekByPartnerIds(array $partnerids)
    {
        $result = [];
        $ids = array_values(array_unique(array_map('intval', array_filter($partnerids))));
        if (empty($ids)) {
            return $result;
        }
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('partner_id', 'partner_id');
        $rsm->addScalarResult('nev', 'nev');
        $q = $this->_em->createNativeQuery(
            'SELECT pc.partner_id, c.nev'
            . ' FROM partner_cimkek pc JOIN cimketorzs c ON c.id = pc.cimketorzs_id'
            . ' WHERE pc.partner_id IN (' . implode(',', $ids) . ')'
            . ' ORDER BY pc.partner_id, pc.cimketorzs_id',
            $rsm
        );
        foreach ($q->getScalarResult() as $sor) {
            $result[(int)$sor['partner_id']][] = ['nev' => $sor['nev']];
        }
        return $result;
    }

    public function getCimkeNevek($cimkefilter)
    {
        $cimkenevek = [];
        if ($cimkefilter) {
            if (is_array($cimkefilter)) {
                $cimkekodok = implode(',', $cimkefilter);
            } else {
                $cimkekodok = $cimkefilter;
            }
            if ($cimkekodok) {
                $q = \mkw\store::getEm()->createQuery('SELECT pc.nev FROM Entities\Partnercimketorzs pc WHERE pc.id IN (' . $cimkekodok . ')');
                $res = $q->getScalarResult();
                foreach ($res as $sor) {
                    $cimkenevek[] = $sor['nev'];
                }
            }
        }
        return $cimkenevek;
    }

}
