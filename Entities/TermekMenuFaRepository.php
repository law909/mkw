<?php

namespace Entities;

class TermekMenuFaRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(TermekMenuFa::class);
    }

    /** @return TermekMenuFa[] */
    public function getAllSorted(): array
    {
        return $this->findBy([], ['sorrend' => 'ASC', 'nev' => 'ASC', 'id' => 'ASC']);
    }

    /** [id, caption, selected] rows for a select; $nincs adds an empty "no menu" first row. */
    public function getSelectList($selid = null, bool $nincs = false): array
    {
        $res = $nincs ? [['id' => '', 'caption' => t('nincs'), 'selected' => !$selid]] : [];
        foreach ($this->getAllSorted() as $fa) {
            $res[] = ['id' => $fa->getId(), 'caption' => $fa->getNev(), 'selected' => $fa->getId() == $selid];
        }
        return $res;
    }
}
