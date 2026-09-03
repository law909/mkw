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
 * cikkszám és a bruttó ár. A méret és a felépítés a docs/galadvonalkod1.pdf mintacímkéé
 * (141 x 70 pont fekvő = 49,75 x 24,69 mm), hogy a meglévő címkenyomtatóba változtatás
 * nélkül menjen.
 *
 * A vonalkód rajza SVG, nem az mPDF <barcode> tagje: az EAN-13-at az mPDF a szabvány szerinti
 * őrjegy-elrendezésben, saját számsorral rajzolja, a mintacímkén viszont egyenletes magasságú
 * vonalak vannak, a számsor pedig külön sorban alattuk.
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

    /**
     * Egy termék(változat) címkeadata. A vonalkód és a cikkszám a változaté, ha van neki,
     * különben a terméké – ugyanaz a visszalépés, mint a bizonylatellenőrzésnél.
     *
     * @return array{vonalkod: string, cikkszam: string, ar: string}
     */
    public function getCimkeAdat(Termek $termek, ?TermekValtozat $valtozat = null)
    {
        return [
            'vonalkod' => (string)(($valtozat && $valtozat->getVonalkod()) ? $valtozat->getVonalkod() : $termek->getVonalkod()),
            'cikkszam' => (string)(($valtozat && $valtozat->getCikkszam()) ? $valtozat->getCikkszam() : $termek->getCikkszam()),
            'ar' => $this->formatAr($termek->getBruttoAr($valtozat)),
        ];
    }

    /**
     * Egy termék(változat) címkéi.
     *
     * @param int $db hány példány
     *
     * @return array<int, array{vonalkod: string, cikkszam: string, ar: string}>
     */
    public function getTermekCimkek(Termek $termek, ?TermekValtozat $valtozat = null, $db = 1)
    {
        return array_fill(0, max(1, (int)$db), $this->getCimkeAdat($termek, $valtozat));
    }

    /**
     * Egy bizonylat tételeinek címkéi: tételenként annyi, amennyi a tétel mennyisége. A tétel
     * árát szándékosan nem használjuk, a címkére a termék aktuális bolti ára kerül.
     *
     * @return array<int, array{vonalkod: string, cikkszam: string, ar: string}>
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
     * @param array<int, array{vonalkod: string, cikkszam: string, ar: string}> $cimkek
     */
    public function output(array $cimkek, $filename)
    {
        $this->createPdf($cimkek)->Output($filename, Destination::INLINE);
    }

    /**
     * @param array<int, array{vonalkod: string, cikkszam: string, ar: string}> $cimkek
     */
    public function createPdf(array $cimkek)
    {
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => [self::SZELESSEG, self::MAGASSAG],
            'margin_left' => self::MARGO,
            'margin_right' => self::MARGO,
            'margin_top' => 2,
            'margin_bottom' => 0,
            'margin_header' => 0,
            'margin_footer' => 0,
            'default_font' => 'dejavusanscondensed',
            'tempDir' => $this->getTempDir(),
        ]);
        $mpdf->WriteHTML($this->getHtml($cimkek));
        return $mpdf;
    }

    /**
     * @param array<int, array{vonalkod: string, cikkszam: string, ar: string}> $cimkek
     */
    private function getHtml(array $cimkek)
    {
        $html = '<style>'
            . '.cimke{text-align:center;}'
            . '.cimkeszam{font-size:6pt;line-height:2.4mm;}'
            . '.cimkecikkszam{font-size:8pt;line-height:3.4mm;}'
            . '.cimkear{font-size:12pt;font-weight:bold;line-height:5mm;}'
            . '</style>';
        $elso = true;
        foreach ($cimkek as $cimke) {
            $html .= $elso ? '' : '<pagebreak />';
            $elso = false;
            $html .= '<div class="cimke">'
                . $this->getVonalkodKep($cimke['vonalkod'])
                . '<div class="cimkeszam">' . htmlspecialchars($cimke['vonalkod']) . '</div>'
                . '<div class="cimkecikkszam">' . htmlspecialchars($cimke['cikkszam']) . '</div>'
                . '<div class="cimkear">' . htmlspecialchars($cimke['ar']) . '</div>'
                . '</div>';
        }
        return $html;
    }

    /**
     * A vonalkód SVG képe. A 13 jegyű kódot EAN-13-ként, a többit Code 128-cal kódoljuk;
     * vonalkód nélküli terméknél csak üres hely marad a címke tetején.
     */
    private function getVonalkodKep($vonalkod)
    {
        if (!$vonalkod) {
            return '';
        }
        $tipus = preg_match('/^\d{13}$/', $vonalkod) ? 'EAN13' : 'C128A';
        $adat = (new Barcode())->getBarcodeArray($vonalkod, $tipus);
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
