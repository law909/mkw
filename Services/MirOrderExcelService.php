<?php

namespace Services;

use Entities\Bizonylatfej;
use Entities\Bizonylattetel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

/**
 * Szállítói megrendelés exportja a Mir rendelőlapjának formátumában – a minta a projekt
 * gyökerében lévő „Mir Order.xls”.
 *
 * A lap méret-mátrix: egy sor egy termék+szín párost ír le, az oszlopok a méretek. A két
 * méretskála (nadrág számozott, felsőrész betűs) a 3. és a 4. sor fejléce; ezek a formátum
 * részei, ezért fixek, nem a bizonylatból jönnek. Ami egyik skálába sem illik, az az N
 * oszlopba kerül (az is benne van a darabszám-összegben), a mérete pedig a névhez ragad,
 * hogy a szállító lássa, mit rendeltünk.
 */
class MirOrderExcelService
{

    /** nadrágméretek az F oszloptól */
    private const NADRAGMERETEK = ['29', '30', '32', '34', '36', '38', '40', '42'];
    private const NADRAGELSO = 5;

    /** felsőrész-méretek az E oszloptól */
    private const FELSOMERETEK = ['S', 'M', 'L', 'XL', 'XXL', '3XL', '4XL', '5XL', '6XL'];
    private const FELSOELSO = 4;

    private const OSZLOPCIKKSZAM = 0;   // A
    private const OSZLOPNEV = 2;        // C
    private const OSZLOPSZIN = 3;       // D
    private const OSZLOPELSOMERET = 4;  // E
    private const OSZLOPEGYEB = 13;     // N – a skálákba nem illő méretek
    private const OSZLOPDB = 14;        // O
    private const OSZLOPAR = 15;        // P
    private const OSZLOPOSSZ = 16;      // Q
    private const OSZLOPHATARIDO = 17;  // R

    private const ELSOADATSOR = 6;

    /**
     * @param Bizonylatfej $fej
     */
    public function export($fej): Spreadsheet
    {
        $excel = new Spreadsheet();
        $sheet = $excel->setActiveSheetIndex(0);
        $sheet->setTitle('Munka1');

        $this->writeFejlec($sheet, $fej);

        $meretoszlopok = $this->getMeretOszlopok();
        $sor = self::ELSOADATSOR;
        $elsoadat = null;
        $utolsoadat = null;
        $csoport = null;
        foreach ($this->getSorok($fej) as $adat) {
            if ($adat['csoport'] !== $csoport) {
                $csoport = $adat['csoport'];
                if ($csoport !== '') {
                    $sheet->setCellValue(\mkw\store::getExcelCoordinate(self::OSZLOPCIKKSZAM, $sor), $csoport . ':');
                    $sor++;
                }
            }
            $this->writeSor($sheet, $sor, $adat, $meretoszlopok, $fej);
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
     * @param Bizonylatfej $fej
     */
    private function writeFejlec($sheet, $fej): void
    {
        $sheet->setCellValue('F2', 'Order ' . $fej->getKeltStr());

        $sheet->setCellValue('D3', 'PANTS MEN size');
        foreach (self::NADRAGMERETEK as $i => $meret) {
            $sheet->setCellValueExplicit(
                \mkw\store::getExcelCoordinate(self::NADRAGELSO + $i, 3),
                $meret,
                \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
            );
        }
        $sheet->setCellValue('D4', 'LEATHER JACKET+SHIRT SIZES');
        foreach (self::FELSOMERETEK as $i => $meret) {
            $sheet->setCellValue(\mkw\store::getExcelCoordinate(self::FELSOELSO + $i, 4), $meret);
        }

        $sheet->setCellValue('A5', 'ART:');
        $sheet->setCellValue('C5', 'Name');
        $sheet->setCellValue('D5', 'color');
        $sheet->setCellValue(\mkw\store::getExcelCoordinate(self::OSZLOPDB, 5), 'TOT PCS');
        $sheet->setCellValue(\mkw\store::getExcelCoordinate(self::OSZLOPAR, 5), 'PRICE');
        $sheet->setCellValue(\mkw\store::getExcelCoordinate(self::OSZLOPOSSZ, 5), 'TOTAL');
        $sheet->setCellValue(\mkw\store::getExcelCoordinate(self::OSZLOPHATARIDO, 5), 'DELIVERY DATE');
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
            $oszlop = $meretoszlopok[mb_strtoupper((string)$meret)] ?? null;
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

    /** A csoportfejléc a legmélyebb kitöltött kategória – a gyökér („Termék kategóriák") semmit nem mond. */
    private function getCsoportNev($termek): string
    {
        if (!$termek) {
            return '';
        }
        return (string)($termek->getTermekfa3()?->getNev()
            ?: $termek->getTermekfa2()?->getNev()
                ?: $termek->getTermekfa1()?->getNev());
    }

    /** méretcímke => oszlopindex; a két skála ugyanazokon az oszlopokon osztozik */
    private function getMeretOszlopok(): array
    {
        $ret = [];
        foreach (self::FELSOMERETEK as $i => $meret) {
            $ret[$meret] = self::FELSOELSO + $i;
        }
        foreach (self::NADRAGMERETEK as $i => $meret) {
            $ret[$meret] = self::NADRAGELSO + $i;
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
