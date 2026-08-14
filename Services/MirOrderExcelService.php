<?php

namespace Services;

use Entities\Bizonylatfej;
use Entities\Bizonylattetel;
use Entities\Meret;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Szállítói megrendelés exportja a Mir rendelőlapjának formátumában – a minta a projekt
 * gyökerében lévő „Mir Order.xls”.
 *
 * A lap méret-mátrix: egy sor egy termék+szín párost ír le, az oszlopok a méretek. A
 * méretskálát minden termékfa a saját csoportsorában viszi (a tételei fölött, középre
 * rendezve): a termékfa termékeinek a bizonylaton szereplő méretei, a Meret entitás sorrend
 * mezője szerint rendezve. A méretoszlopok száma nincs korlátozva – a mögöttük álló oszlopok
 * (egyéb, TOT PCS, PRICE, TOTAL, CURRENCY, DELIVERY DATE) annyival csúsznak jobbra, amennyi kell.
 *
 * A második munkalap a megrendelésen szereplő termékek/változatok azonosítói (cikkszám, név,
 * változat, vonalkód).
 *
 * A terméknév és a termékfa neve a bizonylat nyelvén megy ki (`bizonylatnyelv`), ha van
 * fordítás; a szín és a méret a változat értéke, azt nem fordítjuk.
 */
class MirOrderExcelService
{

    private const OSZLOPCIKKSZAM = 0;   // A
    private const OSZLOPKEP = 1;        // B – a termék főképe
    private const OSZLOPPARTNER = 3;    // D – a 2. sorban a partner neve, az E-vel összevonva
    private const OSZLOPNEV = 2;        // C
    private const OSZLOPSZIN = 3;       // D
    private const OSZLOPELSOMERET = 4;  // E

    /** a minta űrlapján E–M a méretskála: ennyi méretoszlop akkor is megvan, ha kevesebb kell */
    private const MINMERETOSZLOP = 9;

    /** a termékkép befoglaló négyzete képpontban (a sormagasság és a B oszlop ehhez igazodik) */
    private const KEPMERET = 60;

    /** az oszlopcímkék sora; alatta kezdődnek az adatok */
    private const CIMKESOR = 3;

    /**
     * @param Bizonylatfej $fej
     */
    public function export($fej): Spreadsheet
    {
        $excel = new Spreadsheet();
        $sheet = $excel->setActiveSheetIndex(0);
        $sheet->setTitle('Order');

        $sorok = $this->getSorok($fej);
        $meretoszlopok = $this->getMeretOszlopok($sorok, $this->getMeretSorrend($fej));
        $oszlopok = $this->getOszlopok($meretoszlopok);

        $sor = $this->writeCimkeSor($sheet, $oszlopok);
        $elsoadat = null;
        $utolsoadat = null;
        $csoport = null;
        foreach ($sorok as $adat) {
            if ($adat['csoport'] !== $csoport) {
                $csoport = $adat['csoport'];
                $meretek = $meretoszlopok[$csoport] ?? [];
                if ($csoport !== '' || $meretek) {
                    $this->writeCsoportSor($sheet, $sor, $csoport, $meretek);
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
            $sheet->setCellValue(
                \mkw\store::getExcelCoordinate($oszlopok['valutanem'], $sor),
                $fej->getValutanemnev()
            );
            $this->setErtekKeret($sheet, $sor, $oszlopok);
            $this->setKozepre($sheet, $sor, $oszlopok);
        }

        $this->writeTermekLap($excel, $fej);
        $excel->setActiveSheetIndex(0);

        // Az oszlopszélesség a tartalomhoz igazodik. A megrendelő neve és a kelt viszont nem
        // szélesíthet oszlopot, ezért csak a szélességek kiszámolása után kerül a lapra.
        $this->autoSizeOszlopok($sheet);
        if (array_filter(array_column($sorok, 'kep'))) {
            // a képek nem cellaértékek, az automatikus méretezés üresnek látja a képoszlopot
            $sheet->getColumnDimension(Coordinate::stringFromColumnIndex(self::OSZLOPKEP + 1))
                ->setWidth((self::KEPMERET + 9) / 7);
        }
        $this->writeRendelesAdatok($sheet, $fej);
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
            'valutanem' => $egyeb + 4,
            'hatarido' => $egyeb + 5,
        ];
    }

    /**
     * A megrendelő neve és a kelt a 2. sorban – a hosszú nevük miatt csak az oszlopszélességek
     * kiszámolása után írjuk ki (lásd autoSizeOszlopok).
     *
     * @param Bizonylatfej $fej
     */
    private function writeRendelesAdatok($sheet, $fej): void
    {
        $partnercella = \mkw\store::getExcelCoordinate(self::OSZLOPPARTNER, 2);
        $sheet->setCellValue($partnercella, $fej->getPartnernev());
        // a hosszú név ne lógjon bele a kelt cellájába
        $sheet->mergeCells($partnercella . ':' . \mkw\store::getExcelCoordinate(self::OSZLOPPARTNER + 1, 2));
        $sheet->setCellValue('F2', 'ORDER ' . $fej->getKeltStr());
    }

    /**
     * Az oszlopok szélessége a tartalomhoz igazítva – ugyanaz, mint az oszlopszegélyre dupla
     * kattintani. A számolás után kikapcsoljuk az automatikus méretezést, hogy a mentés már ne
     * számolja újra (a 2. sor tartalmával együtt).
     */
    private function autoSizeOszlopok($sheet): void
    {
        $utolso = Coordinate::columnIndexFromString($sheet->getHighestColumn());
        for ($i = 1; $i <= $utolso; $i++) {
            $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($i))->setAutoSize(true);
        }
        $sheet->calculateColumnWidths();
        for ($i = 1; $i <= $utolso; $i++) {
            $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($i))->setAutoSize(false);
        }
    }

    /**
     * Az oszlopcímkék sora.
     *
     * @param array $oszlopok a méretek mögötti oszlopok helye (getOszlopok)
     *
     * @return int az első adatsor száma
     */
    private function writeCimkeSor($sheet, array $oszlopok): int
    {
        $sor = self::CIMKESOR;
        $sheet->setCellValue(\mkw\store::getExcelCoordinate(self::OSZLOPCIKKSZAM, $sor), 'ART:');
        $sheet->setCellValue(\mkw\store::getExcelCoordinate(self::OSZLOPNEV, $sor), 'NAME');
        $sheet->setCellValue(\mkw\store::getExcelCoordinate(self::OSZLOPSZIN, $sor), 'COLOR');
        $sheet->setCellValue(\mkw\store::getExcelCoordinate($oszlopok['db'], $sor), 'TOT PCS');
        $sheet->setCellValue(\mkw\store::getExcelCoordinate($oszlopok['ar'], $sor), 'PRICE');
        $sheet->setCellValue(\mkw\store::getExcelCoordinate($oszlopok['ossz'], $sor), 'TOTAL');
        $sheet->setCellValue(\mkw\store::getExcelCoordinate($oszlopok['valutanem'], $sor), 'CURRENCY');
        $sheet->setCellValue(\mkw\store::getExcelCoordinate($oszlopok['hatarido'], $sor), 'DELIVERY DATE');

        return $sor + 1;
    }

    /**
     * Csoportsor a tételei fölött: az A oszlopban a termékfa neve, a méretoszlopokban a
     * termékfa méretskálája – középre rendezve, hogy a mennyiségek fölött ez legyen a fejléc.
     *
     * @param array $meretek méretcímke => oszlopindex
     */
    private function writeCsoportSor($sheet, $sor, $csoport, array $meretek): void
    {
        if ($csoport !== '') {
            $cella = \mkw\store::getExcelCoordinate(self::OSZLOPCIKKSZAM, $sor);
            $sheet->setCellValue($cella, $csoport . ':');
            $sheet->getStyle($cella)->getFont()->setBold(true);
        }
        foreach ($meretek as $meret => $oszlop) {
            $cella = \mkw\store::getExcelCoordinate($oszlop, $sor);
            // explicit szöveg, különben a számozott méretskála számként landol
            $sheet->setCellValueExplicit($cella, (string)$meret, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->getStyle($cella)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($cella)->getFont()->setBold(true);
        }
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
        $sheet->setCellValue(\mkw\store::getExcelCoordinate($oszlopok['valutanem'], $sor), $fej->getValutanemnev());
        $sheet->setCellValue(\mkw\store::getExcelCoordinate($oszlopok['hatarido'], $sor), $fej->getHataridoStr());
        $this->writeKep($sheet, $sor, $adat['kep']);

        // a mátrix-rész rácsos (az üres méretcella is), az azon túli cellák csak akkor kapnak
        // keretet, ha van bennük adat – az elválasztó „egyéb" oszlop így üresen keret nélkül marad
        $this->setKeret(
            $sheet,
            \mkw\store::getExcelCoordinate(self::OSZLOPCIKKSZAM, $sor)
            . ':' . \mkw\store::getExcelCoordinate($oszlopok['egyeb'] - 1, $sor)
        );
        $this->setErtekKeret($sheet, $sor, $oszlopok);
        $this->setKozepre($sheet, $sor, $oszlopok);
    }

    /**
     * A termék főképe a B oszlopba, a sor magasságát a képhez igazítva. A kép a befoglaló
     * négyzetbe fér bele, az arányát megtartva.
     *
     * @param string $fajl a kép fájlja a lemezen, üresen nincs mit kitenni
     */
    private function writeKep($sheet, $sor, $fajl): void
    {
        if (!$fajl) {
            return;
        }
        $kep = new Drawing();
        $kep->setPath($fajl);
        $kep->setResizeProportional(true);
        $kep->setWidthAndHeight(self::KEPMERET, self::KEPMERET);
        $kep->setCoordinates(\mkw\store::getExcelCoordinate(self::OSZLOPKEP, $sor));
        $kep->setOffsetX(2);
        $kep->setWorksheet($sheet);
        // a sormagasság pontban értendő, a kép képpontban
        $magassagpt = self::KEPMERET * 0.75 + 3;
        $sheet->getRowDimension($sor)->setRowHeight($magassagpt);
        // az arányos kicsinyítéstől a kép alacsonyabb lehet a sornál: tegyük függőlegesen középre
        $kep->setOffsetY((int)max(0, round(($magassagpt / 0.75 - $kep->getHeight()) / 2)));
    }

    /**
     * Az egész sor vízszintesen és függőlegesen is középre – a cikkszámtól a határidőig. A
     * függőleges igazítás a képek miatt magas sorokban számít.
     *
     * @param array $oszlopok a méretek mögötti oszlopok helye (getOszlopok)
     */
    private function setKozepre($sheet, $sor, array $oszlopok): void
    {
        $sheet->getStyle(
            \mkw\store::getExcelCoordinate(self::OSZLOPCIKKSZAM, $sor)
            . ':' . \mkw\store::getExcelCoordinate($oszlopok['hatarido'], $sor)
        )->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);
    }

    /** @param string $tartomany egy cella vagy cellatartomány */
    private function setKeret($sheet, $tartomany): void
    {
        $sheet->getStyle($tartomany)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    }

    /**
     * A jobb oldali értékrész keretei. Csak az adattal kitöltött cella kap keretet – az összesítő
     * sorban pl. nincs ár és határidő, oda nem kell.
     *
     * @param array $oszlopok a méretek mögötti oszlopok helye (getOszlopok)
     */
    private function setErtekKeret($sheet, $sor, array $oszlopok): void
    {
        foreach (['egyeb', 'db', 'ar', 'ossz', 'valutanem', 'hatarido'] as $mezo) {
            $cella = \mkw\store::getExcelCoordinate($oszlopok[$mezo], $sor);
            if ($sheet->cellExists($cella) && (string)$sheet->getCell($cella)->getValue() !== '') {
                $this->setKeret($sheet, $cella);
            }
        }
    }

    /**
     * A második munkalap: a megrendelésen szereplő termékek/változatok azonosítói. Egy sor egy
     * termék+változat, a bizonylaton lévő sorrendben; a vonalkód a változaté, ha van neki saját.
     *
     * @param Bizonylatfej $fej
     */
    private function writeTermekLap(Spreadsheet $excel, $fej): void
    {
        $sheet = new Worksheet($excel, 'Products EAN list');
        $excel->addSheet($sheet);

        $sheet->setCellValue('A1', 'ART:');
        $sheet->setCellValue('B1', 'Name');
        $sheet->setCellValue('C1', 'Variant');
        $sheet->setCellValue('D1', 'Barcode');

        $nyelv = $fej->getBizonylatnyelv();
        $sor = 2;
        $latott = [];
        /** @var Bizonylattetel $tetel */
        foreach ($fej->getBizonylattetelek() as $tetel) {
            if ($tetel->getStorno() || $tetel->getStornozott()) {
                continue;
            }
            $valtozat = $tetel->getTermekvaltozat();
            $termek = $tetel->getTermek();
            $kulcs = $tetel->getCikkszam() . '|' . $tetel->getValtozatertek1() . '|' . $tetel->getValtozatertek2();
            if (isset($latott[$kulcs])) {
                continue;
            }
            $latott[$kulcs] = true;

            $sheet->setCellValueExplicit(
                'A' . $sor,
                (string)$tetel->getCikkszam(),
                \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
            );
            $sheet->setCellValue('B' . $sor, $this->getTermekNev($tetel, $termek, $nyelv));
            $sheet->setCellValue(
                'C' . $sor,
                trim($tetel->getValtozatertek1() . ' ' . $tetel->getValtozatertek2())
            );
            $sheet->setCellValueExplicit(
                'D' . $sor,
                (string)($valtozat?->getVonalkod() ?: $termek?->getVonalkod()),
                \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
            );
            $sor++;
        }

        $this->autoSizeOszlopok($sheet);
    }

    /**
     * A termék főképének fájlja a lemezen, vagy üres string, ha nincs használható kép. Először a
     * kicsinyített változatokat keressük: a rendelőlapra bőven elég, és nem hizlalja a fájlt.
     * A `kepurl` URL-kódolt és a dokumentumgyökérhez képest relatív.
     */
    private function getKepFajl($termek): string
    {
        if (!$termek) {
            return '';
        }
        $docroot = \Services\MediatarService::getDocRoot();
        foreach ([$termek->getKepurlSmall(), $termek->getKepurlMini(), $termek->getKepurl()] as $url) {
            if (!$url) {
                continue;
            }
            $fajl = $docroot . '/' . ltrim(rawurldecode($url), '/');
            // a getimagesize a formátumot is kiszűri: amit a PhpSpreadsheet nem tud, azt kihagyjuk
            if (is_file($fajl) && @getimagesize($fajl)) {
                return $fajl;
            }
        }
        return '';
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
            foreach (
                \mkw\store::getEm()->getRepository(Meret::class)
                    ->findBy(['nev' => array_keys($nevre)]) as $meret
            ) {
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
                    'kep' => $this->getKepFajl($termek),
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
