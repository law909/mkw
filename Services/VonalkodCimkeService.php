<?php

namespace Services;

use Entities\Bizonylatfej;
use Entities\Bizonylattetel;
use Entities\Termek;
use Entities\TermekValtozat;
use Entities\Valutanem;
use Mpdf\Barcode;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;

/**
 * Termékcímke PDF: egy címke egy oldal, rajta a vonalkód, alatta a vonalkód számjegyei, a
 * cikkszám, a bruttó ár és legalul a termék neve (változatnál a színnel és a mérettel). A méret és a felépítés a docs/galadvonalkod1.pdf mintacímkéé
 * (141 x 70 pont fekvő = 49,75 x 24,69 mm), hogy a meglévő címkenyomtatóba változtatás
 * nélkül menjen.
 *
 * A 13 jegyű vonalkódot az mPDF <barcode> tagje rajzolja, szabványos EAN-13-ként: őrjegyek,
 * a bal szélen a nyitó számjegy, a számsor a vonalak alján. Minden más kódot Code 128-cal,
 * egyenletes magasságú SVG vonalakkal rajzolunk, a számsorral külön sorban alattuk.
 *
 * Több helyről hívjuk: egy termék(változat) címkéje a termék felől, és egy bizonylat összes
 * tételéé egyetlen PDF-ben a bizonylatlistáról.
 */
class VonalkodCimkeService
{
    // a mintacímke 141 x 70 pont
    private const SZELESSEG = 49.75;
    private const MAGASSAG = 24.69;
    private const MARGO = 1.5;
    // a vonalkód helye a címkén, a mintacímke arányai szerint
    private const VONALKOD_SZELESSEG = 22;
    private const VONALKOD_MAGASSAG = 7.4;
    // az EAN-13 a névleges 0,33 mm-es modulszélességgel (37,3 mm széles), a magasság a címkéhez szabva
    private const EAN13_SIZE = 1;
    private const EAN13_HEIGHT = 0.45;
    private const FONT = 'dejavusanscondensed';
    // the name gets one line at the bottom; the label size is fixed, so a longer name is cut
    private const NEV_PT = 6;

    /**
     * Egy termék(változat) címkeadata. A vonalkód és a cikkszám a változaté, ha van neki,
     * különben a terméké – ugyanaz a visszalépés, mint a bizonylatellenőrzésnél.
     *
     * @return array{vonalkod: string, cikkszam: string, ar: string, nev: string, valtozat: string}
     */
    public function getCimkeAdat(Termek $termek, ?TermekValtozat $valtozat = null)
    {
        $szinmeret = '';
        if ($valtozat) {
            // the Szín / Méret törzs if the variant has one, else the old free values
            $szinmeret = implode(' ', array_filter([
                trim((string)($valtozat->getSzinNev() ?: $valtozat->getSzin())),
                trim((string)($valtozat->getMeretNev() ?: $valtozat->getMeret())),
            ], fn($e) => $e !== ''));
        }
        return [
            'vonalkod' => (string)(($valtozat && $valtozat->getVonalkod()) ? $valtozat->getVonalkod() : $termek->getVonalkod()),
            'cikkszam' => (string)(($valtozat && $valtozat->getCikkszam()) ? $valtozat->getCikkszam() : $termek->getCikkszam()),
            'ar' => $this->formatAr($termek->getBruttoAr($valtozat)),
            // some names are stored HTML-encoded ("&amp;")
            'nev' => html_entity_decode((string)$termek->getNev(), ENT_QUOTES | ENT_HTML5),
            'valtozat' => html_entity_decode($szinmeret, ENT_QUOTES | ENT_HTML5),
        ];
    }

    /**
     * Egy termék(változat) címkéi.
     *
     * @param int $db hány példány
     *
     * @return array<int, array{vonalkod: string, cikkszam: string, ar: string, nev: string, valtozat: string}>
     */
    public function getTermekCimkek(Termek $termek, ?TermekValtozat $valtozat = null, $db = 1)
    {
        return array_fill(0, max(1, (int)$db), $this->getCimkeAdat($termek, $valtozat));
    }

    /**
     * Egy bizonylat tételeinek címkéi: tételenként annyi, amennyi a tétel mennyisége. A tétel
     * árát szándékosan nem használjuk, a címkére a termék aktuális bolti ára kerül.
     *
     * @return array<int, array{vonalkod: string, cikkszam: string, ar: string, nev: string, valtozat: string}>
     */
    public function getBizonylatCimkek(Bizonylatfej $bizonylatfej)
    {
        $cimkek = [];
        /** @var Bizonylattetel $tetel */
        foreach ($bizonylatfej->getBizonylattetelek() as $tetel) {
            $termek = $tetel->getTermek();
            if (!$termek) {
                continue;
            }
            $db = (int)ceil(abs($tetel->getMennyiseg()));
            if ($db < 1) {
                continue;
            }
            $cimkek = array_merge($cimkek, $this->getTermekCimkek($termek, $tetel->getTermekvaltozat(), $db));
        }
        return $cimkek;
    }

    /**
     * A címkék PDF-je a böngésző nézőjébe. Semmit nem szabad kiírni előtte, mert fejlécet küld.
     *
     * @param array<int, array{vonalkod: string, cikkszam: string, ar: string, nev: string, valtozat: string}> $cimkek
     */
    public function output(array $cimkek, $filename)
    {
        $this->createPdf($cimkek)->Output($filename, Destination::INLINE);
    }

    /**
     * @param array<int, array{vonalkod: string, cikkszam: string, ar: string, nev: string, valtozat: string}> $cimkek
     */
    public function createPdf(array $cimkek)
    {
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => [self::SZELESSEG, self::MAGASSAG],
            'margin_left' => self::MARGO,
            'margin_right' => self::MARGO,
            'margin_top' => 1.5,
            'margin_bottom' => 0,
            'margin_header' => 0,
            'margin_footer' => 0,
            'default_font' => self::FONT,
            'tempDir' => $this->getTempDir(),
        ]);
        $mpdf->WriteHTML($this->getHtml($cimkek, $mpdf));
        return $mpdf;
    }

    /**
     * @param array<int, array{vonalkod: string, cikkszam: string, ar: string, nev: string, valtozat: string}> $cimkek
     */
    private function getHtml(array $cimkek, Mpdf $mpdf)
    {
        $html = '<style>'
            . '.cimke{text-align:center;}'
            . '.cimkeszam{font-size:6pt;line-height:2.4mm;}'
            . '.cimkecikkszam{font-size:8pt;line-height:3.4mm;}'
            . '.cimkear{font-size:12pt;font-weight:bold;line-height:4.5mm;}'
            . '.cimkenev{font-size:' . self::NEV_PT . 'pt;line-height:2.4mm;}'
            . '</style>';
        $elso = true;
        foreach ($cimkek as $cimke) {
            $html .= $elso ? '' : '<pagebreak />';
            $elso = false;
            $html .= '<div class="cimke">'
                . $this->getVonalkodKep($cimke['vonalkod'])
                . ($this->isEan13($cimke['vonalkod'])
                    ? ''
                    : '<div class="cimkeszam">' . htmlspecialchars($cimke['vonalkod']) . '</div>')
                . '<div class="cimkecikkszam">' . htmlspecialchars($cimke['cikkszam']) . '</div>'
                . '<div class="cimkear">' . htmlspecialchars($cimke['ar']) . '</div>'
                . '<div class="cimkenev">' . htmlspecialchars($this->fitNev($mpdf, $cimke['nev'] ?? '', $cimke['valtozat'] ?? '')) . '</div>'
                . '</div>';
        }
        return $html;
    }

    /**
     * The name and the variant in one line of the label's width. If it does not fit, the product name is cut, the colour
     * and the size stay whole: the variant is what tells two labels of the same product apart.
     */
    private function fitNev(Mpdf $mpdf, string $nev, string $valtozat): string
    {
        $max = self::SZELESSEG - 2 * self::MARGO;
        $mpdf->SetFont(self::FONT, '', self::NEV_PT);
        $nev = trim($nev);
        $teljes = trim($nev . ' ' . $valtozat);
        if ($mpdf->GetStringWidth($teljes) <= $max) {
            return $teljes;
        }
        $vege = $valtozat !== '' ? '… ' . $valtozat : '…';
        while ($nev !== '' && $mpdf->GetStringWidth($nev . $vege) > $max) {
            $nev = rtrim(mb_substr($nev, 0, -1));
        }
        $sor = $nev . $vege;
        // even the variant alone is too wide: then it is cut too
        while (mb_strlen($sor) > 1 && $mpdf->GetStringWidth($sor) > $max) {
            $sor = mb_substr($sor, 0, -2) . '…';
        }
        return $sor;
    }

    /**
     * A vonalkód képe: szabványos EAN-13, ha a kód az, különben Code 128.
     * Vonalkód nélküli terméknél csak üres hely marad a címke tetején.
     */
    private function getVonalkodKep($vonalkod)
    {
        if (!$vonalkod) {
            return '';
        }
        if ($this->isEan13($vonalkod)) {
            return '<barcode code="' . htmlspecialchars($vonalkod) . '" type="EAN13"'
                . ' size="' . self::EAN13_SIZE . '" height="' . self::EAN13_HEIGHT . '">';
        }
        return $this->getCode128Kep($vonalkod);
    }

    /**
     * Az mPDF EAN-13 rajzolója hibás ellenőrző jegyre kivételt dob, ezért itt előre eldöntjük,
     * hogy a kód szabványos EAN-13-e. Ha nem, Code 128 lesz belőle.
     */
    private function isEan13($vonalkod)
    {
        if (!preg_match('/^\d{13}$/', (string)$vonalkod)) {
            return false;
        }
        try {
            return (bool)(new Barcode())->getBarcodeArray($vonalkod, 'EAN13');
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Code 128 SVG-ként, egyenletes magasságú vonalakkal, a mintacímke arányaira nyújtva.
     */
    private function getCode128Kep($vonalkod)
    {
        try {
            $adat = (new Barcode())->getBarcodeArray($vonalkod, 'C128A');
        } catch (\Exception $e) {
            return '';
        }
        if (!$adat) {
            return '';
        }
        // a viewBox egysége egy vonalvastagság, a magasság csak a képarányt adja
        $magassag = 30;
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="' . $adat['maxw'] . '" height="' . $magassag
            . '" viewBox="0 0 ' . $adat['maxw'] . ' ' . $magassag . '">';
        $x = 0;
        foreach ($adat['bcode'] as $vonal) {
            if ($vonal['t']) {
                $svg .= '<rect x="' . $x . '" y="0" width="' . $vonal['w'] . '" height="' . $magassag . '" fill="#000"/>';
            }
            $x += $vonal['w'];
        }
        $svg .= '</svg>';
        return '<img src="data:image/svg+xml;base64,' . base64_encode($svg) . '"'
            . ' style="width:' . self::VONALKOD_SZELESSEG . 'mm;height:' . self::VONALKOD_MAGASSAG . 'mm">';
    }

    private function formatAr($ar)
    {
        /** @var Valutanem|null $valutanem */
        $valutanem = \mkw\store::getEm()->getRepository(Valutanem::class)
            ->find(\mkw\store::getParameter(\mkw\consts::Valutanem));
        return \mkw\store::bizformat($ar, 2) . ($valutanem ? ' ' . $valutanem->getNev() : '');
    }

    private function getTempDir()
    {
        $tmp = \mkw\store::storagePath('mpdf');
        if (!is_dir($tmp)) {
            @mkdir($tmp, 0775, true);
        }
        return is_writable($tmp) ? $tmp : sys_get_temp_dir();
    }
}
