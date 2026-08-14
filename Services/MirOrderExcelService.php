<?php

namespace Services;

use Entities\Bizonylatfej;
use Entities\Bizonylattetel;
use Entities\Meret;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

/**
 * Szállítói megrendelés exportja a Mir rendelőlapjának formátumában – a minta a projekt
 * gyökerében lévő „Mir Order.xls”.
 *
 * A lap méret-mátrix: egy sor egy termék+szín párost ír le, az oszlopok a méretek. A
 * méretskálák a fejlécből derülnek ki: termékfánként egy sor, benne a termékfa termékeinek
 * a bizonylaton szereplő méretei, a Meret entitás sorrend mezője szerint rendezve. Ami már
 * nem fér a méretoszlopokba, az az N oszlopba kerül (az is benne van a darabszám-összegben),
 * a mérete pedig a névhez ragad, hogy a szállító lássa, mit rendeltünk.
 */
class MirOrderExcelService
{

    private const OSZLOPCIKKSZAM = 0;   // A
    private const OSZLOPNEV = 2;        // C
    private const OSZLOPSZIN = 3;       // D – a fejlécsorokban a termékfa neve
    private const OSZLOPELSOMERET = 4;  // E
    private const OSZLOPEGYEB = 13;     // N – ami már nem fért a méretoszlopokba
    private const OSZLOPDB = 14;        // O
    private const OSZLOPAR = 15;        // P
    private const OSZLOPOSSZ = 16;      // Q
    private const OSZLOPHATARIDO = 17;  // R

    /** ennyi méret fér el egy fejlécsorban (E–M) */
    private const MERETOSZLOPDB = self::OSZLOPEGYEB - self::OSZLOPELSOMERET;

    private const ELSOFEJLECSOR = 3;

    /**
     * @param Bizonylatfej $fej
     */
    public function export($fej): Spreadsheet
    {
        $excel = new Spreadsheet();
        $sheet = $excel->setActiveSheetIndex(0);
        $sheet->setTitle('Munka1');

        $sorok = $this->getSorok($fej);
        $meretoszlopok = $this->getMeretOszlopok($sorok, $this->getMeretSorrend($fej));

        $sor = $this->writeFejlec($sheet, $fej, $meretoszlopok);
        $elsoadat = null;
        $utolsoadat = null;
        $csoport = null;
        foreach ($sorok as $adat) {
            if ($adat['csoport'] !== $csoport) {
                $csoport = $adat['csoport'];
                if ($csoport !== '') {
                    $sheet->setCellValue(\mkw\store::getExcelCoordinate(self::OSZLOPCIKKSZAM, $sor), $csoport . ':');
                    $sor++;
                }
            }
            $this->writeSor($sheet, $sor, $adat, $meretoszlopok[$adat['csoport']] ?? [], $fej);
            $elsoadat = $elsoadat ?? $sor;
            $utolsoadat = $sor;
            $sor++;
        }

        if ($elsoadat) {
            $sheet->setCellValue(
                \mkw\store::getExcelCoordinate(self::OSZLOPDB, $sor),
                '=SUM(' . \mkw\store::getExcelCoordinate(self::OSZLOPDB, $elsoadat)
                . ':' . \mkw\store::getExcelCoordinate(self::OSZLOPDB, $utolsoadat) . ')'
            );
            $sheet->setCellValue(
                \mkw\store::getExcelCoordinate(self::OSZLOPOSSZ, $sor),
                '=SUM(' . \mkw\store::getExcelCoordinate(self::OSZLOPOSSZ, $elsoadat)
                . ':' . \mkw\store::getExcelCoordinate(self::OSZLOPOSSZ, $utolsoadat) . ')'
            );
        }
        return $excel;
    }

    /**
     * Termékfánként egy méretsor, majd az oszlopcímkék sora.
     *
     * @param Bizonylatfej $fej
     * @param array[] $meretoszlopok termékfa => [méretcímke => oszlopindex]
     *
     * @return int az első adatsor száma
     */
    private function writeFejlec($sheet, $fej, array $meretoszlopok): int
    {
        $sheet->setCellValue('F2', 'Order ' . $fej->getKeltStr());

        $sor = self::ELSOFEJLECSOR;
        foreach ($meretoszlopok as $csoport => $oszlopok) {
            $sheet->setCellValue(
                \mkw\store::getExcelCoordinate(self::OSZLOPSZIN, $sor),
                $csoport === '' ? 'SIZES' : $csoport
            );
            foreach ($oszlopok as $meret => $oszlop) {
                // explicit szöveg, különben a számozott méretskála számként landol
                $sheet->setCellValueExplicit(
                    \mkw\store::getExcelCoordinate($oszlop, $sor),
                    (string)$meret,
                    \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
                );
            }
            $sor++;
        }

        $sheet->setCellValue(\mkw\store::getExcelCoordinate(self::OSZLOPCIKKSZAM, $sor), 'ART:');
        $sheet->setCellValue(\mkw\store::getExcelCoordinate(self::OSZLOPNEV, $sor), 'Name');
        $sheet->setCellValue(\mkw\store::getExcelCoordinate(self::OSZLOPSZIN, $sor), 'color');
        $sheet->setCellValue(\mkw\store::getExcelCoordinate(self::OSZLOPDB, $sor), 'TOT PCS');
        $sheet->setCellValue(\mkw\store::getExcelCoordinate(self::OSZLOPAR, $sor), 'PRICE');
        $sheet->setCellValue(\mkw\store::getExcelCoordinate(self::OSZLOPOSSZ, $sor), 'TOTAL');
        $sheet->setCellValue(\mkw\store::getExcelCoordinate(self::OSZLOPHATARIDO, $sor), 'DELIVERY DATE');

        return $sor + 1;
    }

    /**
     * @param Bizonylatfej $fej
     */
    private function writeSor($sheet, $sor, array $adat, array $meretoszlopok, $fej): void
    {
        $nev = $adat['nev'];
        $egyeb = 0;
        $egyebmeretek = [];
        foreach ($adat['mennyisegek'] as $meret => $mennyiseg) {
            $oszlop = $meretoszlopok[$meret] ?? null;
            if ($oszlop === null) {
                $egyeb += $mennyiseg;
                $egyebmeretek[] = ($meret === '' ? '?' : $meret) . ': ' . (string)(0 + $mennyiseg);
                continue;
            }
            $sheet->setCellValue(\mkw\store::getExcelCoordinate($oszlop, $sor), $mennyiseg);
        }
        if ($egyeb) {
            $sheet->setCellValue(\mkw\store::getExcelCoordinate(self::OSZLOPEGYEB, $sor), $egyeb);
            $nev .= ' (' . implode(', ', $egyebmeretek) . ')';
        }

        $sheet->setCellValue(\mkw\store::getExcelCoordinate(self::OSZLOPCIKKSZAM, $sor), $adat['cikkszam']);
        $sheet->setCellValue(\mkw\store::getExcelCoordinate(self::OSZLOPNEV, $sor), $nev);
        $sheet->setCellValue(\mkw\store::getExcelCoordinate(self::OSZLOPSZIN, $sor), $adat['szin']);
        $sheet->setCellValue(
            \mkw\store::getExcelCoordinate(self::OSZLOPDB, $sor),
            '=SUM(' . \mkw\store::getExcelCoordinate(self::OSZLOPELSOMERET, $sor)
            . ':' . \mkw\store::getExcelCoordinate(self::OSZLOPEGYEB, $sor) . ')'
        );
        $sheet->setCellValue(\mkw\store::getExcelCoordinate(self::OSZLOPAR, $sor), $adat['ar']);
        $sheet->setCellValue(
            \mkw\store::getExcelCoordinate(self::OSZLOPOSSZ, $sor),
            '=' . \mkw\store::getExcelCoordinate(self::OSZLOPDB, $sor)
            . '*' . \mkw\store::getExcelCoordinate(self::OSZLOPAR, $sor)
        );
        $sheet->setCellValue(\mkw\store::getExcelCoordinate(self::OSZLOPHATARIDO, $sor), $fej->getHataridoStr());
    }

    /**
     * A csoportfejléc a legmélyebb kitöltött kategória. A gyökér („Termék kategóriák") semmit
     * nem mond, és a kitöltetlen fa-mezők jellemzően rá mutatnak, nem üresek – ezért a szülő
     * nélküli kategória nem számít kitöltöttnek.
     */
    private function getCsoportNev($termek): string
    {
        if (!$termek) {
            return '';
        }
        foreach ([$termek->getTermekfa3(), $termek->getTermekfa2(), $termek->getTermekfa1()] as $fa) {
            if ($fa && $fa->getParent()) {
                return (string)$fa->getNev();
            }
        }
        return '';
    }

    /**
     * Termékfánként a bizonylaton szereplő méretek oszlopkiosztása: termékfa =>
     * [méretcímke => oszlopindex]. A termékfák a bizonylaton lévő sorrendben követik
     * egymást, hogy a fejlécsorok a törzs csoportjaival egy sorrendben álljanak.
     *
     * @param array[] $sorok
     * @param array $sorrend méretcímke => a Meret sorrend mezője
     *
     * @return array[]
     */
    private function getMeretOszlopok(array $sorok, array $sorrend): array
    {
        $ret = [];
        foreach ($sorok as $adat) {
            foreach (array_keys($adat['mennyisegek']) as $meret) {
                if ((string)$meret !== '') {
                    $ret[$adat['csoport']][(string)$meret] = true;
                }
            }
        }
        foreach ($ret as $csoport => $meretek) {
            $cimkek = array_keys($meretek);
            usort($cimkek, function ($a, $b) use ($sorrend) {
                return [$sorrend[$a] ?? PHP_INT_MAX, (string)$a] <=> [$sorrend[$b] ?? PHP_INT_MAX, (string)$b];
            });
            $oszlopok = [];
            // ami már nem fér el, az az egyéb oszlopba megy (writeSor)
            foreach (array_slice($cimkek, 0, self::MERETOSZLOPDB) as $i => $cimke) {
                $oszlopok[$cimke] = self::OSZLOPELSOMERET + $i;
            }
            $ret[$csoport] = $oszlopok;
        }
        return $ret;
    }

    /**
     * Méretcímke => a Meret sorrend mezője. Elsősorban a tétel változatához kötött méretből;
     * amihez nincs (pl. változat nélküli tétel), azt a méret törzsből keressük név szerint.
     *
     * @param Bizonylatfej $fej
     */
    private function getMeretSorrend($fej): array
    {
        $ret = [];
        $nevre = [];
        /** @var Bizonylattetel $tetel */
        foreach ($fej->getBizonylattetelek() as $tetel) {
            $cimke = (string)$tetel->getValtozatertek2();
            if ($cimke === '' || isset($ret[$cimke])) {
                continue;
            }
            $meret = $tetel->getTermekvaltozat()?->getMeretObject();
            if ($meret) {
                $ret[$cimke] = (int)$meret->getSorrend();
            } else {
                $nevre[$cimke] = true;
            }
        }
        if ($nevre) {
            /** @var Meret $meret */
            foreach (\mkw\store::getEm()->getRepository(Meret::class)
                         ->findBy(['nev' => array_keys($nevre)]) as $meret) {
                $ret[$meret->getNev()] = (int)$meret->getSorrend();
            }
        }
        return $ret;
    }

    /**
     * A tételek termék+szín szerint összevonva, a bizonylaton lévő sorrendben.
     *
     * @param Bizonylatfej $fej
     *
     * @return array[]
     */
    private function getSorok($fej): array
    {
        $sorok = [];
        /** @var Bizonylattetel $tetel */
        foreach ($fej->getBizonylattetelek() as $tetel) {
            if ($tetel->getStorno() || $tetel->getStornozott()) {
                continue;
            }
            $cikkszam = $tetel->getCikkszam();
            $szin = (string)$tetel->getValtozatertek1();
            $meret = (string)$tetel->getValtozatertek2();
            $kulcs = $cikkszam . '|' . $szin;
            if (!isset($sorok[$kulcs])) {
                $termek = $tetel->getTermek();
                $sorok[$kulcs] = [
                    'cikkszam' => $cikkszam,
                    'nev' => $tetel->getTermeknev(),
                    'szin' => $szin,
                    'csoport' => $this->getCsoportNev($termek),
                    'ar' => $tetel->getNettoegysar(),
                    'mennyisegek' => [],
                ];
            }
            $sorok[$kulcs]['mennyisegek'][$meret] =
                ($sorok[$kulcs]['mennyisegek'][$meret] ?? 0) + $tetel->getMennyiseg();
        }
        return array_values($sorok);
    }

}
