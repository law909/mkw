<?php
namespace Entities;

class KuponRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\Kupon');
        $this->setOrders(array(
            '1' => array('caption' => 'Létrehozás szerint csökkenő', 'order' => array('_xx.created' => 'DESC'))
        ));
    }
}
