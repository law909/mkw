<?php

namespace Entities;

class MPTTagsagformaRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname(MPTTagsagforma::class);
    }

}
