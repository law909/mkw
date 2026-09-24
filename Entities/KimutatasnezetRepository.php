<?php

namespace Entities;

class KimutatasnezetRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(Kimutatasnezet::class);
    }

    /**
     * @return \Entities\Kimutatasnezet[]
     */
    public function getByKimutatas(string $kimutatas): array
    {
        return $this->findBy(['kimutatas' => $kimutatas], ['nev' => 'ASC']);
    }
}
