<?php

namespace Entities;

use mkwhelpers\FilterDescriptor;

class SzallitasimodRepository extends \mkwhelpers\Repository
{

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntityname('Entities\Szallitasimod');
        $this->setOrders([
            '1' => ['caption' => 'név szerint növekvő', 'order' => ['_xx.nev' => 'ASC']]
        ]);
    }

    public function getAllWebes()
    {
        $filter = new FilterDescriptor();
        switch (\mkw\store::getWebshopNum()) {
            case 2:
                $filter->addFilter('webes2', '=', true);
                break;
            case 3:
                $filter->addFilter('webes3', '=', true);
                break;
            case 4:
                $filter->addFilter('webes4', '=', true);
                break;
            case 1:
            default:
                $filter->addFilter('webes', '=', true);
                break;
        }
        return $this->getAll($filter, ['sorrend' => 'ASC', 'nev' => 'ASC']);
    }

    public function getSzallitasiKoltseg($szallmod, $orszag, $valutanem, $ertek)
    {
        $ktg = 0;
        switch (\mkw\store::getSzallitasiKoltsegMode()) {
            case 'normal':
                $ktg = \mkw\store::getEm()->getRepository(SzallitasimodHatar::class)->getBySzallitasimodValutanemHatar($szallmod, $valutanem, $ertek);
                $ktg = $ktg ? $ktg->getOsszeg() : 0;
                break;
            case 'orszagonkent':
                $ktg = \mkw\store::getEm()->getRepository(SzallitasimodOrszag::class)->getBySzallitasimodOrszagValutanemHatar(
                    $szallmod,
                    $orszag,
                    $valutanem,
                    $ertek
                );
                $ktg = $ktg ? $ktg->getOsszeg() : 0;
                break;
        }
        return $ktg;
    }

    public function getUtanvetKoltseg($szallmod, $fizmod, $ertek)
    {
        $ktg = 0;
        if ($szallmod && $fizmod) {
            /** @var SzallitasimodFizmodNovelo $n */
            $n = \mkw\store::getEm()->getRepository(SzallitasimodFizmodNovelo::class)->getBySzallitasimodFizmod($szallmod, $fizmod);
            if ($n) {
                if (is_array($n)) {
                    $n = $n[0];
                }
                if ($n->getErtekszazalek() != 0) {
                    $ktg = max(round($ertek * $n->getErtekszazalek() / 100), $n->getOsszeg());
                } else {
                    $ktg = $n->getOsszeg();
                }
            }
        }
        return $ktg;
    }

    public function getKezelesiKoltsegTermekek()
    {
        $szms = $this->getAll();
        $termekek = [];
        /** @var Szallitasimod $szm */
        foreach ($szms as $szm) {
            if ($szm->getTermek()) {
                $termekek[$szm->getTermekId()] = $szm->getTermekId();
            }
        }
        return $termekek;
    }

}