<?php
namespace Entities;

use mkwhelpers\FilterDescriptor;

class OrszagRepository extends \mkwhelpers\Repository {

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\Orszag');
    }

    public function getAllLathato() {
        $filter = new FilterDescriptor();
        switch (\mkw\store::getWebshopNum()) {
            case 1:
                $filter->addFilter('lathato', '=', true);
                break;
            case 2:
                $filter->addFilter('lathato2', '=', true);
                break;
            case 3:
                $filter->addFilter('lathato3', '=', true);
                break;
            default:
                $filter->addFilter('lathato', '=', true);
                break;
        }
        return $this->getAll($filter, array('nev' => 'ASC'));
    }

}