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

    /**
     * Whether the munkakor may open the URL: true when a menu item pointing at it is ticked for the munkakor, null
     * when no menu item points at it.
     */
    public function isMunkakorAllowedByUrl(string $url, ?int $munkakorId): ?bool
    {
        $row = $this->_em->getConnection()->fetchNumeric(
            'SELECT COUNT(DISTINCT m.id), COUNT(mm.munkakor_id) FROM menu m'
            . ' LEFT JOIN menu_munkakorok mm ON mm.menu_id = m.id AND mm.munkakor_id = ?'
            . ' WHERE m.url = ?',
            [(int)$munkakorId, $url]
        );
        return $row[0] ? $row[1] > 0 : null;
    }

    public function getWithJoins($filter, $order, $offset = 0, $elemcount = 0): mixed
    {
        // no paging here: a fetch-joined collection would be cut by the row limit
        return $this->_em->createQuery(
            'SELECT _xx, m, mk'
            . ' FROM Entities\Menu _xx'
            . ' LEFT JOIN _xx.menucsoport m'
            . ' LEFT JOIN _xx.munkakorok mk'
            . $this->getFilterString($filter)
            . $this->getOrderString($order)
        )
            ->setParameters($this->getQueryParameters($filter))
            ->getResult();
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