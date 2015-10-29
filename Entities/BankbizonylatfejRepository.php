<?php

namespace Entities;

use Doctrine\ORM\EntityRepository;

class BankbizonylatfejRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\Bankbizonylatfej');
        $this->setOrders(array(
            '1' => array('caption' => 'biz.szám szerint csökkenő', 'order' => array('_xx.id' => 'DESC')),
            '2' => array('caption' => 'biz.szám szerint növekvő', 'order' => array('_xx.id' => 'ASC')),
            '3' => array('caption' => 'kelt szerint csökkenő', 'order' => array('_xx.kelt' => 'DESC','_xx.id' => 'DESC')),
            '4' => array('caption' => 'kelt szerint növekvő', 'order' => array('_xx.kelt' => 'DESC','_xx.id' => 'DESC')),
            '5' => array('caption' => 'er.biz.szám szerint csökkenő', 'order' => array('_xx.erbizonylatszam' => 'DESC')),
            '6' => array('caption' => 'er.biz.szám szerint növekvő', 'order' => array('_xx.erbizonylatszam' => 'ASC'))
        ));
    }

    public function findWithJoins($id) {
        return parent::findWithJoins((string)$id);
    }

    public function getWithJoins($filter, $order, $offset = 0, $elemcount = 0) {
        $a = $this->alias;
        $q = $this->_em->createQuery('SELECT ' . $a
            . ' FROM ' . $this->getEntityname() . ' ' . $a
            . $this->getFilterString($filter)
            . $this->getOrderString($order));
        $q->setParameters($this->getQueryParameters($filter));
        if ($offset > 0) {
            $q->setFirstResult($offset);
        }
        if ($elemcount > 0) {
            $q->setMaxResults($elemcount);
        }
        return $q->getResult();
    }

    public function getCount($filter) {
        $a = $this->alias;
        $q = $this->_em->createQuery('SELECT COUNT(' . $a . ') FROM ' . $this->getEntityname() . ' ' . $a
            . $this->getFilterString($filter));
        $q->setParameters($this->getQueryParameters($filter));
        return $q->getSingleScalarResult();
    }

    /**
     * @param \Entities\Bankbizonylatfej $bizonylat
     */
    public function createFolyoszamla($bizonylat) {

        foreach($bizonylat->getFolyoszamlak() as $fsz) {
            $this->_em->remove($fsz);
        }
        $bizonylat->clearFolyoszamlak();

        /** @var \Entities\Bankbizonylattetel $tetel */
        foreach($bizonylat->getBizonylattetelek() as $tetel) {
            $fszla = new \Entities\Folyoszamla();
            $fszla->setDatum($tetel->getDatum());
            $fszla->setPartner($tetel->getPartner());
            $fszla->setBizonylattipus($tetel->getBizonylatfej()->getBizonylattipus());
            $fszla->setRontott($tetel->getRontott());
            $fszla->setStorno(false);
            $fszla->setStornozott(false);
            $fszla->setHivatkozottbizonylat($tetel->getHivatkozottbizonylat());
            $fszla->setValutanem($tetel->getValutanem());
            $fszla->setIrany($tetel->getIrany() * -1);
            $fszla->setNetto($tetel->getNetto());
            $fszla->setAfa($tetel->getAfa());
            $fszla->setBrutto($tetel->getBrutto());
            $fszla->setBankbizonylatfej($tetel->getBizonylatfej());
            $fszla->setBankbizonylattetel($tetel);

            $this->_em->persist($fszla);
        }
        $this->_em->flush();
    }
}
