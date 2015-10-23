<?php

namespace Entities;

use Doctrine\ORM\EntityRepository;

class JogcimRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\Jogcim');
    }

}
