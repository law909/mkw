<?php

namespace Entities;

use mkwhelpers\FilterDescriptor;

class TermekValtozatErtekRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname(TermekValtozatErtek::class);
        $this->setOrders([
            '1' => ['caption' => 'név szerint növekvő', 'order' => ['_xx.nev' => 'ASC']]
        ]);
    }

    public function getAllColors()
    {
        $filter = new FilterDescriptor();
        $filter->addFilter('adattipus', '=', \mkw\store::getParameter(\mkw\consts::ValtozatTipusSzin));
        return $this->getAll($filter, ['ertek' => 'ASC']);
    }

    public function translate($tipus, $ertek)
    {
        $x = $this->findOneBy([
            'adattipus' => $tipus,
            'ertek' => $ertek,
        ]);
        if ($x) {
            return $x->getCharkod();
        }
        return $ertek;
    }

    public function translateColor($ertek)
    {
        return $this->translate(\mkw\store::getParameter(\mkw\consts::ValtozatTipusSzin), $ertek);
    }

    public function translateSize($ertek)
    {
        return $this->translate(\mkw\store::getParameter(\mkw\consts::ValtozatTipusMeret), $ertek);
    }

}