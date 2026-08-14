<?php

namespace mkw;

use Mpdf\Mpdf;
use Mpdf\Output\Destination;

/**
 * Lapozott bizonylat PDF. A fejléc/lábléc <htmlpageheader>/<htmlpagefooter>, tehát minden
 * oldalon megjelenik, és mivel az mPDF sort nem vág ketté, egy tételsor sem törik szét.
 *
 * A felső/alsó margót nem az mPDF setAutoTopMargin='stretch'-e állítja: az a 8.3-ban
 * eltörik, ha a fejlécben táblázat van (a mérő _getHtmlHeight() ág hibás típusra fut).
 * Helyette egy eldobható példánnyal magunk mérjük meg a fejléc és a lábléc magasságát,
 * és fix margóval indítjuk az igazi motort – a bizonylatonként változó fejlécmagasság
 * így is kijön, mert a mérés bizonylatonként történik.
 */
class mkwmpdf
{
    private const MARGIN_LEFT = 10;
    private const MARGIN_RIGHT = 10;
    private const MARGIN_HEADER = 8;
    private const MARGIN_FOOTER = 6;
    // a mért fejléc/lábléc és a törzs közti minimális köz
    private const MARGIN_PADDING = 3;
    // ha a mérés nem sikerül, ennyi mm-t hagyunk a fejlécnek/láblécnek
    private const FALLBACK_HEADER = 55;
    private const FALLBACK_FOOTER = 10;
    // egy 600 tételes bizonylat ~145 MB-ot és pár másodpercet kér; a php-fpm alapértéke ennél kevesebb
    private const MIN_MEMORY_LIMIT = 512;
    private const MIN_TIME_LIMIT = 180;

    private $engine;
    private $html;

    public function __construct($html)
    {
        $this->raiseLimits();
        $this->html = $html;
        [$headerheight, $footerheight] = $this->measureRunningBlocks($html);
        $this->engine = new Mpdf($this->getConfig([
            'margin_top' => self::MARGIN_HEADER + $headerheight + self::MARGIN_PADDING,
            'margin_bottom' => self::MARGIN_FOOTER + $footerheight + self::MARGIN_PADDING,
        ]));
        // a tételtáblák egymásba ágyazottak: enélkül a százalékos oszlopszélességek oldalanként elcsúsznak
        $this->engine->keep_table_proportions = true;
        // a bizonylat egyetlen táblázat, és az oldal első tábláját az mPDF alapból az egy oldalra
        // zsugorítás felé viszi – itt inkább lapdobás kell, betűzsugorítás nélkül
        $this->engine->shrink_tables_to_fit = 1;
        $this->engine->tableMinSizePriority = true;
        // sok tételes bizonylatnál ez a különbség a lefutás és a memóriakimerülés között
        $this->engine->packTableData = true;
        $this->engine->showImageErrors = (bool)store::getConfigValue('developer', false);
    }

    public function getEngine()
    {
        return $this->engine;
    }

    public function saveAs($filename)
    {
        $this->engine->WriteHTML($this->html);
        $this->engine->Output($filename, Destination::FILE);
        return true;
    }

    public function send($filename)
    {
        $this->engine->WriteHTML($this->html);
        $this->engine->Output($filename, Destination::DOWNLOAD);
    }

    // a nyomtatás gomb új fülön nyitja: a böngésző PDF nézője kell, nem letöltés
    public function inline($filename)
    {
        $this->engine->WriteHTML($this->html);
        $this->engine->Output($filename, Destination::INLINE);
    }

    /**
     * A php-fpm alapértelmezett 128M / 30s kevés egy több száz tételes bizonylathoz, és a
     * kimerülés néma, sérült PDF-et ad. Csak emelünk, sosem szorítunk: aki már többet enged
     * (vagy korlátlan, mint a CLI), az érintetlen marad.
     */
    private function raiseLimits()
    {
        $current = trim((string)ini_get('memory_limit'));
        if ($current !== '' && $current !== '-1') {
            $megabytes = (int)$current;
            switch (strtolower(substr($current, -1))) {
                case 'g': $megabytes *= 1024; break;
                case 'm': break;
                case 'k': $megabytes = (int)($megabytes / 1024); break;
                // utótag nélkül a php.ini bájtot ért alatta
                default: $megabytes = (int)($megabytes / 1048576); break;
            }
            if ($megabytes < self::MIN_MEMORY_LIMIT) {
                ini_set('memory_limit', self::MIN_MEMORY_LIMIT . 'M');
            }
        }
        $timelimit = (int)ini_get('max_execution_time');
        if ($timelimit > 0 && $timelimit < self::MIN_TIME_LIMIT) {
            @set_time_limit(self::MIN_TIME_LIMIT);
        }
    }

    private function getConfig(array $extra)
    {
        return array_merge([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'P',
            'default_font' => 'dejavusans',
            'default_font_size' => 8,
            'margin_left' => self::MARGIN_LEFT,
            'margin_right' => self::MARGIN_RIGHT,
            'margin_header' => self::MARGIN_HEADER,
            'margin_footer' => self::MARGIN_FOOTER,
            'tempDir' => $this->getTempDir(),
        ], $extra);
    }

    private function getTempDir()
    {
        $tmp = store::storagePath('mpdf');
        if (!is_dir($tmp)) {
            @mkdir($tmp, 0775, true);
        }
        // a storage/ alól az Apache kiszolgálja a létező fájlokat, a font- és képcache-nek
        // viszont semmi keresnivalója a weben
        if (is_dir($tmp) && !is_file($tmp . '/.htaccess')) {
            @file_put_contents($tmp . '/.htaccess', "Require all denied\n");
        }
        return is_writable($tmp) ? $tmp : sys_get_temp_dir();
    }

    /**
     * A fejléc és a lábléc magassága mm-ben, egy eldobható példányba renderelve. A mérés
     * ugyanazzal a szövegszélességgel és stíluslappal fut, mint az igazi dokumentum, különben
     * más sorokra tördelne. Hibánál óvatos becslés jön vissza: inkább maradjon üres hely,
     * mint hogy a fejléc a tételekre csússzon.
     *
     * @return array{0: float, 1: float}
     */
    private function measureRunningBlocks($html)
    {
        $header = $this->extractTag($html, 'htmlpageheader');
        $footer = $this->extractTag($html, 'htmlpagefooter');
        if ($header === '' && $footer === '') {
            return [0.0, 0.0];
        }
        try {
            $probe = new Mpdf($this->getConfig([
                'margin_top' => self::MARGIN_HEADER,
                'margin_bottom' => self::MARGIN_FOOTER,
            ]));
            $probe->keep_table_proportions = true;
            $css = $this->extractTag($html, 'style');
            // a {PAGENO}/{nbpg} csak fejlécben/láblécben él, a mérésnél sima szöveggé kell tenni
            $probe->WriteHTML(
                '<html><head><style>' . $css . '</style></head><body>'
                . str_replace(['{PAGENO}', '{nbpg}'], ['99', '99'], $header)
                . '</body></html>'
            );
            $headerheight = $probe->y - $probe->tMargin;
            $footerstart = $probe->y;
            if ($footer !== '') {
                $probe->WriteHTML(str_replace(['{PAGENO}', '{nbpg}'], ['99', '99'], $footer));
            }
            $footerheight = $probe->y - $footerstart;
            unset($probe);
        } catch (\Throwable $e) {
            store::writelog('mPDF fejlécmérés: ' . $e->getMessage(), 'mpdf_error.txt');
            return [(float)self::FALLBACK_HEADER, (float)self::FALLBACK_FOOTER];
        }
        return [
            $headerheight > 0 ? $headerheight : (float)self::FALLBACK_HEADER,
            $footerheight > 0 ? $footerheight : (float)self::FALLBACK_FOOTER,
        ];
    }

    private function extractTag($html, $tag)
    {
        if (preg_match('#<' . $tag . '\b[^>]*>(.*?)</' . $tag . '>#si', $html, $m)) {
            return $m[1];
        }
        return '';
    }
}
