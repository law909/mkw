<?php
namespace Entities;

use mkwhelpers\FilterDescriptor;

class SzallitasimodRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\Szallitasimod');
        $this->setOrders(array(
            '1' => array('caption' => 'név szerint növekvő', 'order' => array('_xx.nev' => 'ASC'))
        ));
    }

    public function getAllWebes() {
        $filter = new FilterDescriptor();
        switch (\mkw\store::getWebshopNum()) {
            case 1:
                $filter->addFilter('webes', '=', true);
                break;
            case 2:
                $filter->addFilter('webes2', '=', true);
                break;
            case 3:
                $filter->addFilter('webes3', '=', true);
                break;
            default:
                $filter->addFilter('webes', '=', true);
                break;
        }
        return $this->getAll($filter, array('sorrend' => 'ASC', 'nev' => 'ASC'));
    }

    public function get() {
    }

}