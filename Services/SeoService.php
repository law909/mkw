<?php

namespace Services;

use mkw\store;

/**
 * A publikus oldalak keresőoptimalizálási segédei: kanonikus URL, robots meta,
 * és az abszolút URL képzés a strukturált adatokhoz (JSON-LD, OpenGraph).
 *
 * A kanonikus domaint a `canonicalbaseurl` paraméter adja (Beállítások → Web);
 * ha nincs kitöltve, a config.ini `mainurl`-je, végül az aktuális kérés
 * sémája + hosztja. Élesben mindenképp paraméterbe kell írni, különben a
 * www és a www nélküli változat két különböző canonicalt kapna.
 */
class SeoService
{

    /** Ezek a paraméterek nem rontják el az indexelhetőséget (a canonicalba is bekerülhetnek). */
    const INDEXABLEPARAMS = ['pageno'];

    /** Kampánykövető paraméterek: indexelhetők, de a canonicalból kimaradnak. */
    const TRACKINGPARAMS = [
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_content',
        'utm_term',
        'gclid',
        'fbclid',
        'msclkid',
    ];

    /** Ezeken az útvonalneveken sosem indexelünk (kereső, szűrő). */
    const NOINDEXROUTES = ['kereses', 'search', 'showszuro'];

    private static $baseUrl;

    /** A kanonikus séma + domain, záró / nélkül. */
    public static function getBaseUrl(): string
    {
        if (self::$baseUrl === null) {
            $url = trim((string)store::getParameter(\mkw\consts::CanonicalBaseUrl, ''));
            if (!$url) {
                $url = trim((string)store::getConfigValue('mainurl', ''));
            }
            if (!$url) {
                $scheme = store::isSSL() ? 'https' : 'http';
                $url = $scheme . '://' . ($_SERVER['HTTP_HOST'] ?? '');
            }
            self::$baseUrl = rtrim($url, '/');
        }
        return self::$baseUrl;
    }

    /** Helyi útvonalból (/termek/xy) abszolút URL; a kész abszolút URL-t nem bántja. */
    public static function absoluteUrl(?string $path): string
    {
        $path = trim((string)$path);
        if ($path === '') {
            return '';
        }
        if (preg_match('#^https?://#i', $path)) {
            return $path;
        }
        return self::getBaseUrl() . '/' . ltrim($path, '/');
    }

    /** Az aktuális kérés útvonala kisbetűsen, záró / nélkül. */
    public static function getPath(): string
    {
        $path = (string)parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        $path = '/' . trim(mb_strtolower($path), '/');
        return $path;
    }

    /**
     * Indexelhető-e az aktuális URL: csak a lapozó és a kampánykövető paraméterek
     * maradhatnak rajta. Bármi más (order, filter, arfilter, vt, elemperpage,
     * keresett, csakakcios) szűrt nézet, ami nem kerülhet az indexbe.
     */
    public static function isIndexable(): bool
    {
        if (in_array((string)store::getRouteName(), self::NOINDEXROUTES, true)) {
            return false;
        }
        $allowed = array_merge(self::INDEXABLEPARAMS, self::TRACKINGPARAMS);
        return !array_diff(array_keys($_GET), $allowed);
    }

    public static function getRobots(): string
    {
        return self::isIndexable() ? 'index,follow' : 'noindex,follow';
    }

    /**
     * Az aktuális oldal kanonikus URL-je: kanonikus domain + kisbetűs, záró / nélküli
     * útvonal, és legföljebb a `pageno` (2-től). Szűrt/rendezett nézeten a szűretlen
     * alapoldalra mutat.
     */
    public static function getCanonicalUrl(): string
    {
        $url = self::getBaseUrl() . (self::getPath() === '/' ? '/' : self::getPath());
        if (self::isIndexable()) {
            $pageno = (int)($_GET['pageno'] ?? 0);
            if ($pageno >= 2) {
                $url .= '?pageno=' . $pageno;
            }
        }
        return $url;
    }

    /**
     * Egységes morzsalánc: az első elem mindig a Főoldal, az utolsóé (ahol állunk) nem link.
     * A bejövő elemek `caption` + `url` vagy `link` kulcsot hozhatnak (a getMorzsa() `link`-et ad).
     *
     * @param array $items [['caption' => ..., 'url'|'link' => ...], ...]
     */
    public static function buildBreadcrumb(array $items): array
    {
        $chain = [['caption' => t('Főoldal'), 'url' => '/']];
        foreach ($items as $item) {
            $caption = trim((string)($item['caption'] ?? ''));
            if ($caption === '') {
                continue;
            }
            $chain[] = ['caption' => $caption, 'url' => (string)($item['url'] ?? $item['link'] ?? '')];
        }
        $chain[count($chain) - 1]['url'] = '';
        return $chain;
    }

    /** BreadcrumbList JSON-LD a buildBreadcrumb() láncából. */
    public static function breadcrumbJsonLd(array $chain): string
    {
        if (count($chain) < 2) {
            return '';
        }
        $elements = [];
        foreach (array_values($chain) as $i => $item) {
            $element = [
                '@type' => 'ListItem',
                'position' => $i + 1,
                // a nevek HTML-entitásokkal jönnek a törzsadatból, a JSON-LD-be tiszta szöveg kell
                'name' => self::plainText($item['caption'], 0),
            ];
            if ($item['url']) {
                $element['item'] = self::absoluteUrl($item['url']);
            }
            $elements[] = $element;
        }
        return self::jsonLd([
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $elements,
        ]);
    }

    /** Sablonba/JSON-LD-be szánt tiszta szöveg: HTML nélkül, egy sorban, hosszra vágva. */
    public static function plainText(?string $html, int $maxLength = 500): string
    {
        $text = html_entity_decode(strip_tags((string)$html), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = trim(preg_replace('/\s+/u', ' ', $text));
        if ($maxLength > 0 && mb_strlen($text) > $maxLength) {
            $text = rtrim(mb_substr($text, 0, $maxLength - 1)) . '…';
        }
        return $text;
    }

    /** JSON-LD blokk a <script> tagostul; üres tömbre üres stringet ad. */
    public static function jsonLd(array $data): string
    {
        if (!$data) {
            return '';
        }
        return '<script type="application/ld+json">'
            . json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
            . '</script>';
    }
}
