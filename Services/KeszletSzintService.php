<?php

namespace Services;

use Entities\Termek;
use Entities\TermekMinkeszlet;
use Entities\TermekOptkeszlet;
use Entities\TermekValtozat;
use Entities\TermekValtozatMinkeszlet;
use Entities\TermekValtozatOptkeszlet;

/**
 * A raktáras min./opt. készlet sorok takarítása a termék és a változat törlésekor. A négy tábla
 * FK-ja ON DELETE CASCADE, de a törlés nem múlhat a séma állapotán: egy régebbi telepítésen a
 * kényszer hiányozhat, és akkor a sorok árván maradnának.
 *
 * A sorok az EntityManageren keresztül tűnnek el, tehát ugyanabban a tranzakcióban, mint maga a
 * termék – ha a törlés hivatkozás miatt elhasal, a beállítások is megmaradnak.
 */
class KeszletSzintService
{

    private const TERMEKENTITASOK = [TermekMinkeszlet::class, TermekOptkeszlet::class];
    private const VALTOZATENTITASOK = [TermekValtozatMinkeszlet::class, TermekValtozatOptkeszlet::class];

    /**
     * A termék saját raktáras sorai és a változatainak sorai.
     *
     * @param Termek $termek
     */
    public static function removeByTermek($termek): void
    {
        if (!$termek || !$termek->getId()) {
            return;
        }
        $em = \mkw\store::getEm();
        foreach (self::TERMEKENTITASOK as $entity) {
            foreach ($em->getRepository($entity)->getRowsByTermek($termek->getId()) as $sor) {
                $em->remove($sor);
            }
        }
        foreach ($termek->getValtozatok() ?? [] as $valtozat) {
            self::removeByTermekValtozat($valtozat);
        }
    }

    /**
     * Egy változat raktáras sorai.
     *
     * @param TermekValtozat $valtozat
     */
    public static function removeByTermekValtozat($valtozat): void
    {
        if (!$valtozat || !$valtozat->getId()) {
            return;
        }
        $em = \mkw\store::getEm();
        foreach (self::VALTOZATENTITASOK as $entity) {
            $sorok = $em->getRepository($entity)->getRowsByTermekValtozatIds([$valtozat->getId()]);
            foreach ($sorok[$valtozat->getId()] ?? [] as $sor) {
                $em->remove($sor);
            }
        }
    }

}
