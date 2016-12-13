<?php
namespace Entities;

use mkwhelpers\FilterDescriptor;

class KontaktRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\Kontakt');
    }

}