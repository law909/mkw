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
 * a bizonylaton szereplő méretei, a Meret entitás sorrend mezője szerint rendezve. A
 * méretoszlopok száma nincs korlátozva – a mögöttük álló oszlopok (egyéb, TOT PCS, PRICE,
 * TOTAL, DELIVERY DATE) annyival csúsznak jobbra, amennyi kell.
 *
 * A terméknév és a termékfa neve a bizonylat nyelvén megy ki (`bizonylatnyelv`), ha van
 * fordítás; a szín és a méret a változat értéke, azt nem fordítjuk.
 */
class MirOrderExcelService
{

    private const OSZLOPCIKKSZAM = 0;   // A
    private const OSZLOPNEV = 2;        // C
    private const OSZLOPSZIN = 3;       // D – a fejlécsorokban a termékfa neve
    private const OSZLOPELSOMERET = 4;  // E

    /** a minta űrlapján E–M a méretskála: ennyi méretoszlop akkor is megvan, ha kevesebb kell */
    private const MINMERETOSZLOP = 9;

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
        $oszlopok = $this->getOszlopok($meretoszlopok);

        $sor = $this->writeFejlec($sheet, $fej, $meretoszlopok, $oszlopok);
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
            $this->writeSor($sheet, $sor, $adat, $meretoszlopok[$adat['csoport']] ?? [], $oszlopok, $fej);
            $elsoadat = $elsoadat ?? $sor;
            $utolsoadat = $sor;
            $sor++;
        }

        if ($elsoadat) {
            foreach (['db', 'ossz'] as $mezo) {
                $sheet->setCellValue(
                    \mkw\store::getExcelCoordinate($oszlopok[$mezo], $sor),
                    '=SUM(' . \mkw\store::getExcelCoordinate($oszlopok[$mezo], $elsoadat)
                    . ':' . \mkw\store::getExcelCoordinate($oszlopok[$mezo], $utolsoadat) . ')'
                );
            }
        }
        return $excel;
    }

    /**
     * A méretoszlopok mögötti oszlopok helye. A legtöbb méretet vivő termékfa szabja meg, hogy
     * meddig tart a méret-mátrix; ennél kevesebb méretnél a minta űrlapjának kiosztása marad.
     *
     * @param array[] $meretoszlopok
     */
    private function getOszlopok(array $meretoszlopok): array
    {
        $meretdb = self::MINMERETOSZLOP;
        foreach ($meretoszlopok as $oszlopok) {
            $meretdb = max($meretdb, count($oszlopok));
        }
        $egyeb = self::OSZLOPELSOMERET + $meretdb;
        return [
            'egyeb' => $egyeb,          // a méret nélküli tételek
            'db' => $egyeb + 1,
            'ar' => $egyeb + 2,
            'ossz' => $egyeb + 3,
            'hatarido' => $egyeb + 4,
        ];
    }

    /**
     * Termékfánként egy méretsor, majd az oszlopcímkék sora.
     *
     * @param Bizonylatfej $fej
     * @param array[] $meretoszlopok termékfa => [méretcímke => oszlopindex]
     * @param array $oszlopok a méretek mögötti oszlopok helye (getOszlopok)
     *
     * @return int az első adatsor száma
     */
    private function writeFejlec($sheet, $fej, array $meretoszlopok, array $oszlopok): int
    {
        $sheet->setCellValue('F2', 'Order ' . $fej->getKeltStr());

        $sor = self::ELSOFEJLECSOR;
        foreach ($meretoszlopok as $csoport => $meretek) {
            $sheet->setCellValue(
                \mkw\store::getExcelCoordinate(self::OSZLOPSZIN, $sor),
                $csoport === '' ? 'SIZES' : $csoport
            );
            foreach ($meretek as $meret => $oszlop) {
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
        $sheet->setCellValue(\mkw\store::getExcelCoordinate($oszlopok['db'], $sor), 'TOT PCS');
        $sheet->setCellValue(\mkw\store::getExcelCoordinate($oszlopok['ar'], $sor), 'PRICE');
        $sheet->setCellValue(\mkw\store::getExcelCoordinate($oszlopok['ossz'], $sor), 'TOTAL');
        $sheet->setCellValue(\mkw\store::getExcelCoordinate($oszlopok['hatarido'], $sor), 'DELIVERY DATE');

        return $sor + 1;
    }

    /**
     * @param array $meretoszlopok a sor termékfájának méretkiosztása: méretcímke => oszlopindex
     * @param array $oszlopok a méretek mögötti oszlopok helye (getOszlopok)
     * @param Bizonylatfej $fej
     */
    private function writeSor($sheet, $sor, array $adat, array $meretoszlopok, array $oszlopok, $fej): void
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
            $sheet->setCellValue(\mkw\store::getExcelCoordinate($oszlopok['egyeb'], $sor), $egyeb);
            $nev .= ' (' . implode(', ', $egyebmeretek) . ')';
        }

        $sheet->setCellValue(\mkw\store::getExcelCoordinate(self::OSZLOPCIKKSZAM, $sor), $adat['cikkszam']);
        $sheet->setCellValue(\mkw\store::getExcelCoordinate(self::OSZLOPNEV, $sor), $nev);
        $sheet->setCellValue(\mkw\store::getExcelCoordinate(self::OSZLOPSZIN, $sor), $adat['szin']);
        $sheet->setCellValue(
            \mkw\store::getExcelCoordinate($oszlopok['db'], $sor),
            '=SUM(' . \mkw\store::getExcelCoordinate(self::OSZLOPELSOMERET, $sor)
            . ':' . \mkw\store::getExcelCoordinate($oszlopok['egyeb'], $sor) . ')'
        );
        $sheet->setCellValue(\mkw\store::getExcelCoordinate($oszlopok['ar'], $sor), $adat['ar']);
        $sheet->setCellValue(
            \mkw\store::getExcelCoordinate($oszlopok['ossz'], $sor),
            '=' . \mkw\store::getExcelCoordinate($oszlopok['db'], $sor)
            . '*' . \mkw\store::getExcelCoordinate($oszlopok['ar'], $sor)
        );
        $sheet->setCellValue(\mkw\store::getExcelCoordinate($oszlopok['hatarido'], $sor), $fej->getHataridoStr());
    }

    /**
     * A terméknév a bizonylat nyelvén. A tételen tárolt fordítás az első, mert az a bizonylat
     * saját szövege – de az régi tételeken üres, ezért utána a termék aktuális fordítása jön,
     * és csak legvégül a tétel eredeti neve.
     *
     * @param Bizonylattetel $tetel
     */
    private function getTermekNev($tetel, $termek, $nyelv): string
    {
        return (string)($tetel->getLocalizedFieldValue('termeknev', $nyelv)
            ?: $termek?->getLocalizedFieldValue('nev', $nyelv)
                ?: $tetel->getTermeknev());
    }

    /**
     * A csoportfejléc a legmélyebb kitöltött kategória, a bizonylat nyelvén. A gyökér („Termék
     * kategóriák") semmit nem mond, és a kitöltetlen fa-mezők jellemzően rá mutatnak, nem
     * üresek – ezért a szülő nélküli kategória nem számít kitöltöttnek.
     */
    private function getCsoportNev($termek, $nyelv): string
    {
        if (!$termek) {
            return '';
        }
        foreach ([$termek->getTermekfa3(), $termek->getTermekfa2(), $termek->getTermekfa1()] as $fa) {
            if ($fa && $fa->getParent()) {
                return (string)($fa->getLocalizedFieldValue('nev', $nyelv) ?: $fa->getNev());
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
            foreach ($cimkek as $i => $cimke) {
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
     * A tételek termék+szín szerint összevonva, a bizonylaton lévő sorrendben. A terméknév és a
     * csoport neve a bizonylat nyelvén, fordítás híján az eredetin – ugyanaz a fallback, mint a
     * bizonylat nyomtatásában.
     *
     * @param Bizonylatfej $fej
     *
     * @return array[]
     */
    private function getSorok($fej): array
    {
        $nyelv = $fej->getBizonylatnyelv();
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
                    'nev' => $this->getTermekNev($tetel, $termek, $nyelv),
                    'szin' => $szin,
                    'csoport' => $this->getCsoportNev($termek, $nyelv),
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
