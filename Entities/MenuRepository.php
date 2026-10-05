<?php

namespace Entities;

class MenuRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->entityname = Menu::class;
        $this->setOrders([
            '1' => ['caption' => 'menücsoport és sorrend szerint', 'order' => ['m.sorrend' => 'ASC', '_xx.sorrend' => 'ASC']],
            '2' => ['caption' => 'név szerint növekvő', 'order' => ['_xx.nev' => 'ASC']],
            '3' => ['caption' => 'URL szerint növekvő', 'order' => ['_xx.url' => 'ASC']],
        ]);
    }

    /** The lowest right of the menu items pointing at the URL, null when no menu item does. */
    public function getJogosultsagByUrl(string $url): ?int
    {
        $jog = $this->_em->getConnection()->fetchOne('SELECT MIN(jogosultsag) FROM menu WHERE url = ?', [$url]);
        return $jog === null || $jog === false ? null : (int)$jog;
    }

    public function getAll($filter = [], $order = [], $offset = 0, $elemcount = 0)
    {
        $q = $this->_em->createQuery(
            'SELECT _xx, m'
            . ' FROM Entities\Menu _xx'
            . ' LEFT JOIN _xx.menucsoport m'
            . $this->getFilterString($filter)
            . $this->getOrderString($order)
        )
            ->setParameters($this->getQueryParameters($filter));
        if ($offset > 0) {
            $q->setFirstResult($offset);
        }
        if ($elemcount > 0) {
            $q->setMaxResults($elemcount);
        }
        return $q->getResult();
    }
}