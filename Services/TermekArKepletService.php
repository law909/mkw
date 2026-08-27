<?php

namespace Services;

use Entities\Afa;
use Entities\Arsav;
use Entities\Kapcsolodokoltseg;
use Entities\Termek;

/**
 * A képlettel megadott termékárak kiszámítása.
 *
 * A képletes sor nettója: `forrás ársáv nettója * százalék / 100 + hozzáadandó + a kiválasztott
 * kapcsolódó költségek egy darabra eső értéke`. A hozzáadandó negatív is lehet, az a levonás. A forrás csak azonos valutanemű
 * ársáv lehet, és maga is lehet képletes – a feloldás ezért körökben megy, amíg van mit
 * kiszámolni. Az eredmény bruttóját az ársáv kerekítés mezője kerekíti, és a visszaadott nettó
 * már a kerekített bruttóból számolt érték.
 *
 * A számítást a karbantartó „Árak újraszámolása" gombja és a termék mentése is használja, ezért
 * a bemenet nem entitás, hanem a form soraiból összerakott tömb.
 */
class TermekArKepletService
{

    /**
     * @param array $sorok soronként:
     *   id, arsav (id), valutanem (id), netto (fix összeg), kepletes (bool),
     *   forrasarsav (id), szazalek, hozzaad, koltsegek (költség id-k tömbje)
     * @param Termek $termek a kapcsolódó költségek számítási alapjához
     * @param float|null $suly a termék súlya, ha a formról frissebb érték jött
     * @param Afa|null $afa a kerekítés bruttó-nettó váltásához, ha a formról frissebb érték jött
     *
     * @return array ['ertekek' => [sorid => netto], 'hibak' => [sorid => üzenet]] – a hibás sor
     *   nem kerül bele az `ertekek`-be, hogy a hívó a meglévő árat érintetlenül hagyhassa
     */
    public static function calc(array $sorok, Termek $termek, ?float $suly = null, ?Afa $afa = null): array
    {
        // a formon átírt súly is számítson; a terméken csak a számítás idejére állítjuk át, mert
        // a gomb nem ment (a mentés a maga útján úgyis beírja)
        $eredetiSuly = $termek->getSuly();
        if ($suly !== null) {
            $termek->setSuly($suly);
        }
        try {
            return self::calcSorok($sorok, $termek, $afa ?: $termek->getAfa());
        } finally {
            $termek->setSuly($eredetiSuly);
        }
    }

    private static function calcSorok(array $sorok, Termek $termek, ?Afa $afa): array
    {
        $ertekek = [];
        $hibak = [];
        $kepletesek = [];
        // ugyanaz az ársáv több valutanemben is szerepelhet a terméken, ezért a képlet forrását
        // az ársáv ÉS a valutanem együtt azonosítja
        $arsavErtek = [];   // ársáv id|valutanem id => nettó, ezt keresik a képletek
        $arsavValutanemek = [];   // ársáv id => a terméken szereplő valutanemei

        foreach ($sorok as $sor) {
            $arsavid = (int)($sor['arsav'] ?? 0);
            $valutanem = (int)($sor['valutanem'] ?? 0);
            $arsavValutanemek[$arsavid][$valutanem] = true;
            if (empty($sor['kepletes'])) {
                $ertekek[$sor['id']] = (float)($sor['netto'] ?? 0);
                $arsavErtek[$arsavid . '|' . $valutanem] = $ertekek[$sor['id']];
            } else {
                $kepletesek[] = $sor;
            }
        }

        // körönként legalább egy sor megoldódik, különben körkörös a hivatkozás
        $korok = count($kepletesek);
        for ($kor = 0; $kor < $korok && $kepletesek; $kor++) {
            $maradek = [];
            foreach ($kepletesek as $sor) {
                $forras = (int)($sor['forrasarsav'] ?? 0);
                $valutanem = (int)($sor['valutanem'] ?? 0);
                if (!$forras || !isset($arsavValutanemek[$forras])) {
                    $hibak[$sor['id']] = t('A képlet forrás ársávja nincs a termék árai között.');
                    continue;
                }
                if (!isset($arsavValutanemek[$forras][$valutanem])) {
                    $hibak[$sor['id']] = t('A forrás ársáv valutaneme eltér.');
                    continue;
                }
                if (!array_key_exists($forras . '|' . $valutanem, $arsavErtek)) {
                    $maradek[] = $sor;
                    continue;
                }
                $ertek = $arsavErtek[$forras . '|' . $valutanem] * (float)($sor['szazalek'] ?? 100) / 100
                    + (float)($sor['hozzaad'] ?? 0)
                    + self::koltsegOsszeg($termek, $sor['koltsegek'] ?? []);
                $ertek = self::kerekit($ertek, (int)($sor['arsav'] ?? 0), $afa);
                $ertekek[$sor['id']] = $ertek;
                $arsavErtek[(int)($sor['arsav'] ?? 0) . '|' . $valutanem] = $ertek;
            }
            $kepletesek = $maradek;
        }
        foreach ($kepletesek as $sor) {
            $hibak[$sor['id']] = t('A képlet forrás ársávja körkörösen hivatkozik.');
        }
        return ['ertekek' => $ertekek, 'hibak' => $hibak];
    }

    /**
     * A képlet eredményének bruttóját az ársáv kerekítés egységére kerekíti, és a kerekített
     * bruttóhoz tartozó nettót adja vissza.
     */
    private static function kerekit(float $netto, int $arsavid, ?Afa $afa): float
    {
        if (!$afa || !$arsavid) {
            return $netto;
        }
        /** @var Arsav|null $arsav */
        $arsav = \mkw\store::getEm()->getRepository(Arsav::class)->find($arsavid);
        $kerekites = (float)($arsav?->getKerekites() ?: 0);
        if ($kerekites <= 0) {
            return $netto;
        }
        return $afa->calcNetto(round($afa->calcBrutto($netto) / $kerekites) * $kerekites);
    }

    /**
     * A kiválasztott kapcsolódó költségek egy darab termékre eső összege. A törzsből olvasunk, nem
     * a termékhez rendelt halmazból: a karbantartón a hozzárendelés is lehet még mentetlen.
     *
     * @param int[] $koltsegIdk
     */
    private static function koltsegOsszeg(Termek $termek, array $koltsegIdk): float
    {
        $osszeg = 0;
        foreach ($koltsegIdk as $id) {
            /** @var Kapcsolodokoltseg|null $koltseg */
            $koltseg = \mkw\store::getEm()->getRepository(Kapcsolodokoltseg::class)->find($id);
            if ($koltseg) {
                $osszeg += $koltseg->calcErtek($termek);
            }
        }
        return $osszeg;
    }

}
