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

    /**
     * Ezeken az útvonalneveken sosem indexelünk: belső kereső, szűrő, és a vásárlási
     * folyamat lapjai. Utóbbiakon tartalom nincs, viszont a crawl budgetet viszik, és a
     * keresőben megjelenve (üres kosár, bejelentkezés) rontják a találati képet.
     */
    const NOINDEXROUTES = [
        'kereses',
        'search',
        'showszuro',
        'showcheckout',
        'showcheckoutfizetes',
        'checkoutkoszonjuk',
        'checkoutbarionerror',
        'showlogin',
        'showregistration',
        'showaccount',
        'showpassreminder',
        'showertekelesform',
        'termekertekeleskoszonjuk',
        'kosarget',
        'kosaredit',
        'kapcsolatkosz',
    ];

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

    /**
     * A kanonikus hoszt kikényszerítése 301-gyel. A `canonicalbaseurl` paraméter dönt: ha az
     * üres (fejlesztői gép, még be nem állított telepítés), nem történik semmi.
     *
     * Csak a hosztot igazítja, a sémát nem: a séma az app felől nem látszik megbízhatóan
     * (`setup.ssl` kapcsoló, proxy mögötti TLS), egy rossz tipp pedig végtelen 301-hurok lenne.
     * A http → https átirányítás ezért a `.htaccess`-ben, a FORCE_HTTPS környezeti változó mögött van.
     */
    public static function enforceCanonicalHost(): void
    {
        if (!in_array($_SERVER['REQUEST_METHOD'] ?? 'GET', ['GET', 'HEAD'], true)) {
            return;
        }
        $canonicalHost = parse_url(trim((string)store::getParameter(\mkw\consts::CanonicalBaseUrl, '')), PHP_URL_HOST);
        $requestHost = strtok((string)($_SERVER['HTTP_HOST'] ?? ''), ':');
        if (!$canonicalHost || !$requestHost || strcasecmp($canonicalHost, $requestHost) === 0) {
            return;
        }
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: ' . self::getBaseUrl() . ($_SERVER['REQUEST_URI'] ?? '/'));
        exit;
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

    /**
     * A termék kanonikus útvonala. A mugenrace2026 fa a /product/ és a /categories/ prefixet
     * linkeli, a /termek/ és a /termekfa/ ugyanarra a tartalomra mutató, örökölt alias.
     */
    public static function termekPath(?string $slug): string
    {
        return (store::isMugenrace2026() || store::isSuperzoneHu() ? '/product/' : '/termek/') . $slug;
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
        if (self::isNoindexRoute()) {
            return false;
        }
        $allowed = array_merge(self::INDEXABLEPARAMS, self::TRACKINGPARAMS);
        return !array_diff(array_keys($_GET), $allowed);
    }

    /** Maga az oldaltípus nem indexelhető — nem csak a rákerült szűrőparaméterek miatt. */
    public static function isNoindexRoute(): bool
    {
        return in_array((string)store::getRouteName(), self::NOINDEXROUTES, true);
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
        // a kereső, a kosár és a vásárlási folyamat lapjai nem kapnak canonicalt: a noindex
        // az egyértelmű jelzés, a kettő együtt csak ellentmondana egymásnak
        if (self::isNoindexRoute()) {
            return '';
        }
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
     * A Beállítások → Tulajdonos adatai fülről a láblécnek és a strukturált adatoknak.
     * Egy helyen, hogy a látható cégadatok és a JSON-LD ne tudjanak elcsúszni egymástól.
     */
    public static function getOwnerData(): array
    {
        $sameas = array_values(array_filter(array_map(
            'trim',
            preg_split('/[\r\n,]+/', (string)store::getParameter(\mkw\consts::Tulajsameas, ''))
        ), 'strlen'));
        return [
            'markanev' => (string)store::getParameter(\mkw\consts::Tulajmarkanev, ''),
            'nev' => (string)store::getParameter(\mkw\consts::Tulajnev, ''),
            'irszam' => (string)store::getParameter(\mkw\consts::Tulajirszam, ''),
            'varos' => (string)store::getParameter(\mkw\consts::Tulajvaros, ''),
            'utca' => (string)store::getParameter(\mkw\consts::Tulajutca, ''),
            'adoszam' => (string)store::getParameter(\mkw\consts::Tulajadoszam, ''),
            'cegjegyzekszam' => (string)store::getParameter(\mkw\consts::Tulajcegjegyzekszam, ''),
            'email' => (string)store::getParameter(\mkw\consts::TulajKontaktEmail, ''),
            'telefon' => (string)store::getParameter(\mkw\consts::TulajKontaktTelefon, ''),
            'nyitvatartas' => (string)store::getParameter(\mkw\consts::Tulajnyitvatartas, ''),
            'alapitas' => (string)store::getParameter(\mkw\consts::Tulajalapitas, ''),
            'sameas' => $sameas,
            'visszakuldesnap' => (int)store::getParameter(\mkw\consts::Tulajvisszakuldesnap, 0),
            'visszakuldeskoltseg' => (string)store::getParameter(\mkw\consts::Tulajvisszakuldeskoltseg, 'vasarlo'),
        ];
    }

    /** A webáruház @id-je; erre hivatkozik a termékek Offer.seller-e és a WebSite.publisher. */
    public static function getOrganizationId(): string
    {
        return self::getBaseUrl() . '/#organization';
    }

    /**
     * OnlineStore + WebSite @graph. Minden oldal fejlécébe kikerül, hogy a lapon lévő
     * hivatkozások (`seller`, `publisher`) helyben feloldhatók legyenek.
     */
    public static function organizationJsonLd(): string
    {
        $o = self::getOwnerData();
        $name = $o['markanev'] ?: store::getParameter(\mkw\consts::Oldalcim, '');
        if (!$name) {
            return '';
        }
        $org = [
            '@type' => 'OnlineStore',
            '@id' => self::getOrganizationId(),
            'name' => self::plainText($name, 0),
            'url' => self::getBaseUrl() . '/',
        ];
        if ($o['nev']) {
            $org['legalName'] = $o['nev'];
        }
        $logo = store::getParameter(\mkw\consts::Logo, '');
        if ($logo) {
            $org['logo'] = self::absoluteUrl($logo);
        }
        if ($o['alapitas']) {
            $org['foundingDate'] = $o['alapitas'];
        }
        if ($o['telefon']) {
            $org['telephone'] = $o['telefon'];
        }
        if ($o['email']) {
            $org['email'] = $o['email'];
        }
        if ($o['adoszam']) {
            $org['taxID'] = $o['adoszam'];
        }
        if ($o['varos'] || $o['utca']) {
            $org['address'] = array_filter([
                '@type' => 'PostalAddress',
                'streetAddress' => $o['utca'],
                'postalCode' => $o['irszam'],
                'addressLocality' => $o['varos'],
                // a cég székhelye, nem a bolt piaca: a telepítések üzemeltetője magyar cég
                // (a Beállítások → Tulajdonos adatai fülön ma nincs ország mező)
                'addressCountry' => 'HU',
            ], 'strlen');
        }
        if ($o['sameas']) {
            $org['sameAs'] = $o['sameas'];
        }
        if ($o['visszakuldesnap'] > 0) {
            $org['hasMerchantReturnPolicy'] = [
                '@type' => 'MerchantReturnPolicy',
                'applicableCountry' => self::getCountryCode(),
                'returnPolicyCategory' => 'https://schema.org/MerchantReturnFiniteReturnWindow',
                'merchantReturnDays' => $o['visszakuldesnap'],
                'returnMethod' => 'https://schema.org/ReturnByMail',
                'returnFees' => $o['visszakuldeskoltseg'] === 'elado'
                    ? 'https://schema.org/FreeReturn'
                    : 'https://schema.org/ReturnFeesCustomerResponsibility',
            ];
        }
        return self::jsonLd([
            '@context' => 'https://schema.org',
            '@graph' => [
                $org,
                [
                    '@type' => 'WebSite',
                    '@id' => self::getBaseUrl() . '/#website',
                    'url' => self::getBaseUrl() . '/',
                    'name' => $org['name'],
                    'inLanguage' => self::getLanguageTag(),
                    'publisher' => ['@id' => self::getOrganizationId()],
                ],
            ],
        ]);
    }

    /** BCP 47 nyelvcímke (en-US alak) a hu_hu / en_us belső locale-ból. */
    public static function getLanguageTag(): string
    {
        $parts = explode('_', (string)store::getWebshopLongLocale() ?: 'hu_hu');
        return count($parts) === 2 ? strtolower($parts[0]) . '-' . strtoupper($parts[1]) : $parts[0];
    }

    /** OpenGraph locale (hu_HU alak). */
    public static function getOgLocale(): string
    {
        $locale = (string)store::getWebshopLongLocale() ?: 'hu_hu';
        $parts = explode('_', $locale);
        return count($parts) === 2 ? strtolower($parts[0]) . '_' . strtoupper($parts[1]) : $locale;
    }

    /**
     * Az oldal megosztási képe: az oldaltípus saját képe, ha van, különben a beállított
     * alapértelmezett megosztási kép.
     *
     * @return array{url: string, sajat: bool} a `sajat` false esetén az alapkép méretei ismertek (1200x630)
     */
    public static function ogImage(?string $sajatKep = null): array
    {
        $url = self::absoluteUrl(self::imageUrl($sajatKep ?: ''));
        if ($url) {
            return ['url' => $url, 'sajat' => true];
        }
        return ['url' => self::absoluteUrl(store::getParameter(\mkw\consts::Ogkep, '')), 'sajat' => false];
    }

    /**
     * A készlet a változatokból: ha egyetlen látható változat sem elérhető, a termék
     * elfogyott. Változat nélküli terméknél a termék saját "nem kapható" jelzője dönt.
     */
    private static function getAvailability(array $t): string
    {
        $valtozatok = $t['valtozatlista'] ?? [];
        if ($valtozatok) {
            foreach ($valtozatok as $valtozat) {
                if ($valtozat['elerheto']) {
                    return 'https://schema.org/InStock';
                }
            }
            return 'https://schema.org/OutOfStock';
        }
        return empty($t['nemkaphato'])
            ? 'https://schema.org/InStock'
            : 'https://schema.org/OutOfStock';
    }

    /**
     * A bolt alapértelmezett országa ISO 3166-1 alpha-2 kóddal. Szándékosan nem a látogató
     * által választott ország: a strukturált adat nem változhat munkamenetenként.
     */
    public static function getCountryCode(): string
    {
        $orszag = store::getEm()->getRepository(\Entities\Orszag::class)
            ->find(store::getParameter(\mkw\consts::Orszag, 0));
        return $orszag?->getIso3166() ?: 'HU';
    }

    /** A webshop pénznemének kódja a strukturált adatokhoz. */
    public static function getCurrencyCode(): string
    {
        return store::getWebshopValutanem()?->getNev() ?: 'HUF';
    }

    /**
     * Product JSON-LD a terméklap látható adataiból (a toTermekLap() tömbjéből).
     * A `gtin`, a `brand`, a súly és az értékelés csak akkor kerül bele, ha tényleg van adat —
     * kitalált mező a strukturált adatban kézzelfogható kockázat.
     *
     * @param array $t a `termek` sablonváltozó
     * @param string $category a morzsalánc kategóriaága, ' > '-vel fűzve
     * @param string $url a lap kanonikus URL-je; üresen az aktuális kérésé
     */
    public static function productJsonLd(array $t, string $category = '', string $url = ''): string
    {
        $url = $url ?: self::getCanonicalUrl();
        $images = [];
        foreach (array_merge([['kepurl' => $t['kepurl'] ?? '']], $t['kepek'] ?? []) as $kep) {
            $abs = self::absoluteUrl($kep['kepurl'] ?? '');
            if ($abs && !in_array($abs, $images, true)) {
                $images[] = $abs;
            }
        }

        $product = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            '@id' => $url . '#product',
            'name' => self::plainText($t['caption'] ?? '', 0),
            'url' => $url,
        ];
        if ($images) {
            $product['image'] = $images;
        }
        $description = self::plainText($t['leiras'] ?? '', 500) ?: self::plainText($t['rovidleiras'] ?? '', 500);
        if ($description) {
            $product['description'] = $description;
        }
        if (!empty($t['cikkszam'])) {
            $product['sku'] = $t['cikkszam'];
        }
        $gtin = preg_replace('/\D/', '', (string)($t['vonalkod'] ?? ''));
        if (in_array(strlen($gtin), [8, 12, 13, 14], true)) {
            $product['gtin'] = $gtin;
        }
        if (!empty($t['marka'])) {
            $product['brand'] = ['@type' => 'Brand', 'name' => self::plainText($t['marka'], 0)];
        }
        if ($category) {
            $product['category'] = $category;
        }
        $properties = [];
        foreach ($t['cimkelapon'] ?? [] as $cimke) {
            if (!empty($cimke['ismarka']) || empty($cimke['kategorianev']) || empty($cimke['caption'])) {
                continue;
            }
            $properties[] = [
                '@type' => 'PropertyValue',
                'name' => self::plainText($cimke['kategorianev'], 0),
                'value' => self::plainText($cimke['caption'], 0),
            ];
        }
        if ($properties) {
            $product['additionalProperty'] = $properties;
        }
        // a suly decimal, üresen "0.00"-ként jön: az empty() nem szűrné ki
        if ((float)($t['suly'] ?? 0) > 0) {
            $product['weight'] = [
                '@type' => 'QuantitativeValue',
                'value' => (float)$t['suly'],
                'unitCode' => 'KGM',
            ];
        }
        if ((int)($t['ertekelesdb'] ?? 0) > 0 && (float)($t['ertekelesatlag'] ?? 0) > 0) {
            $product['aggregateRating'] = [
                '@type' => 'AggregateRating',
                'ratingValue' => (float)$t['ertekelesatlag'],
                'reviewCount' => (int)$t['ertekelesdb'],
                'bestRating' => 5,
                'worstRating' => 1,
            ];
        }
        $offer = self::buildOffer($t, $url);
        if ($offer) {
            $product['offers'] = $offer;
        }

        $valtozatok = self::buildVariants($t, $url);
        if ($valtozatok) {
            // több változat esetén a Google a ProductGroup + hasVariant szerkezetet várja,
            // a GTIN pedig változatonként külön azonosít
            $product['@type'] = 'ProductGroup';
            $product['productGroupID'] = $t['cikkszam'] ?? '';
            $product['variesBy'] = self::variesBy($t);
            $product['hasVariant'] = $valtozatok;
        }
        return self::jsonLd($product);
    }

    /**
     * A termék változatai önálló Product-ként, saját cikkszámmal, GTIN-nel és ajánlattal.
     * Csak akkor ad vissza bármit, ha legalább két látható változat van: egyváltozatos
     * terméknél a ProductGroup felesleges réteg lenne.
     */
    private static function buildVariants(array $t, string $url): array
    {
        $valtozatok = $t['valtozatlista'] ?? [];
        if (count($valtozatok) < 2) {
            return [];
        }
        $elemek = [];
        foreach ($valtozatok as $valtozat) {
            $variant = [
                '@type' => 'Product',
                'name' => trim(self::plainText($t['caption'] ?? '', 0) . ' ' . $valtozat['szin'] . ' ' . $valtozat['meret']),
                'sku' => $valtozat['cikkszam'],
                'url' => $url,
            ];
            $gtin = preg_replace('/\D/', '', (string)$valtozat['vonalkod']);
            if (strlen($gtin) === 13) {
                $variant['gtin13'] = $gtin;
            } elseif (in_array(strlen($gtin), [8, 12, 14], true)) {
                $variant['gtin'] = $gtin;
            }
            if ($valtozat['szin']) {
                $variant['color'] = $valtozat['szin'];
            }
            if ($valtozat['meret']) {
                $variant['size'] = $valtozat['meret'];
            }
            $ar = (float)$valtozat['brutto'];
            if ($ar > 0) {
                $variant['offers'] = [
                    '@type' => 'Offer',
                    'url' => $url,
                    'priceCurrency' => self::getCurrencyCode(),
                    'price' => (string)round($ar),
                    'availability' => $valtozat['elerheto']
                        ? 'https://schema.org/InStock'
                        : 'https://schema.org/OutOfStock',
                    'itemCondition' => 'https://schema.org/NewCondition',
                    'seller' => ['@id' => self::getOrganizationId()],
                ];
            }
            $elemek[] = $variant;
        }
        return $elemek;
    }

    /** Mi különbözteti meg a változatokat: szín, méret, vagy mindkettő. */
    private static function variesBy(array $t): array
    {
        $tulajdonsagok = [];
        foreach ($t['valtozatlista'] ?? [] as $valtozat) {
            if ($valtozat['szin']) {
                $tulajdonsagok['color'] = 'color';
            }
            if ($valtozat['meret']) {
                $tulajdonsagok['size'] = 'size';
            }
        }
        return array_values($tulajdonsagok);
    }

    /**
     * A termék ajánlata. Nulla áron nem ad vissza semmit: a 0-s ár nem ajánlat, és
     * Offerként jelölve a Google is, a vásárló is félrevezetőnek látja.
     */
    private static function buildOffer(array $t, string $url): array
    {
        $ar = (float)($t['bruttohuf'] ?? 0);
        if ($ar <= 0) {
            return [];
        }
        $offer = [
            '@type' => 'Offer',
            'url' => $url,
            'priceCurrency' => self::getCurrencyCode(),
            'price' => (string)round($ar),
            'availability' => self::getAvailability($t),
            'itemCondition' => 'https://schema.org/NewCondition',
            'seller' => ['@id' => self::getOrganizationId()],
        ];

        $shipping = [
            '@type' => 'OfferShippingDetails',
            'shippingDestination' => ['@type' => 'DefinedRegion', 'addressCountry' => self::getCountryCode()],
            'shippingRate' => [
                '@type' => 'MonetaryAmount',
                // a szállítási költség kosárérték-sávos, itt a termék saját árához tartozó sáv díja
                'value' => (string)round(store::calcSzallitasiKoltseg($ar)),
                'currency' => self::getCurrencyCode(),
            ],
        ];
        $max = (int)($t['szallitasiido'] ?? 0);
        if ($max > 0) {
            $min = (int)($t['minszallitasiido'] ?? 0);
            $shipping['deliveryTime'] = [
                '@type' => 'ShippingDeliveryTime',
                // a terméklapon látható "max. X munkanap" a teljes átfutás, külön csomagolási idő nincs
                'handlingTime' => ['@type' => 'QuantitativeValue', 'minValue' => 0, 'maxValue' => 0, 'unitCode' => 'DAY'],
                'transitTime' => ['@type' => 'QuantitativeValue', 'minValue' => $min, 'maxValue' => $max, 'unitCode' => 'DAY'],
            ];
        }
        $offer['shippingDetails'] = $shipping;
        return $offer;
    }

    /**
     * Person JSON-LD a szponzorált versenyzőhöz. A `sponsor` köti a márka entitásához:
     * ez a weboldal legerősebb Experience-jelzése, ma viszont sehol nincs kimondva.
     *
     * @param array $versenyzo a `versenyzo` sablonváltozó
     */
    public static function personJsonLd(array $versenyzo, string $url): string
    {
        if (empty($versenyzo['nev'])) {
            return '';
        }
        $data = [
            '@context' => 'https://schema.org',
            '@type' => 'Person',
            '@id' => $url . '#person',
            'name' => self::plainText($versenyzo['nev'], 0),
            'url' => $url,
            'jobTitle' => t('Motorversenyző'),
            'sponsor' => ['@id' => self::getOrganizationId()],
        ];
        $kep = self::absoluteUrl(self::imageUrl($versenyzo['kepurl12000'] ?? $versenyzo['kepurl2000'] ?? ''));
        if ($kep) {
            $data['image'] = [$kep];
        }
        $leiras = self::plainText($versenyzo['rovidleiras'] ?? '', 500) ?: self::plainText($versenyzo['leiras'] ?? '', 500);
        if ($leiras) {
            $data['description'] = $leiras;
        }
        if (!empty($versenyzo['csapatnev'])) {
            $data['memberOf'] = ['@type' => 'SportsTeam', 'name' => self::plainText($versenyzo['csapatnev'], 0)];
        }
        if (!empty($versenyzo['versenysorozat'])) {
            $data['knowsAbout'] = self::plainText($versenyzo['versenysorozat'], 0);
        }
        return self::jsonLd($data);
    }

    /** SportsTeam JSON-LD a csapatoldalhoz, a márkához kötött szponzorációval. */
    public static function sportsTeamJsonLd(array $csapat, string $url): string
    {
        if (empty($csapat['nev'])) {
            return '';
        }
        $data = [
            '@context' => 'https://schema.org',
            '@type' => 'SportsTeam',
            '@id' => $url . '#sportsteam',
            'name' => self::plainText($csapat['nev'], 0),
            'url' => $url,
            'sport' => t('Motorsport'),
            'sponsor' => ['@id' => self::getOrganizationId()],
        ];
        $logo = self::absoluteUrl(self::imageUrl($csapat['logourllarge'] ?? ''));
        if ($logo) {
            $data['logo'] = $logo;
        }
        $kep = self::absoluteUrl(self::imageUrl($csapat['kepurl2000'] ?? ''));
        if ($kep) {
            $data['image'] = [$kep];
        }
        $leiras = self::plainText($csapat['leiras'] ?? '', 500);
        if ($leiras) {
            $data['description'] = $leiras;
        }
        $tagok = [];
        foreach ($csapat['versenyzok'] ?? [] as $versenyzo) {
            if (!empty($versenyzo['nev'])) {
                $tagok[] = ['@type' => 'Person', 'name' => self::plainText($versenyzo['nev'], 0)];
            }
        }
        if ($tagok) {
            $data['member'] = $tagok;
        }
        return self::jsonLd($data);
    }

    /**
     * Képhivatkozás abszolút URL-hez: a képeket külön hoszt is kiszolgálhatja
     * (config.ini `main.imagepath`), a sablonok is ezzel az előtaggal írják ki őket.
     */
    public static function imageUrl(?string $kepurl): string
    {
        $kepurl = (string)$kepurl;
        if ($kepurl === '' || preg_match('#^https?://#i', $kepurl)) {
            return $kepurl;
        }
        $prefix = rtrim((string)store::getConfigValue('main.imagepath', ''), '/');
        return $prefix . '/' . ltrim($kepurl, '/');
    }

    /** A blog szerzője a beállításokból; üres név esetén maga a webáruház a szerző. */
    public static function getBlogAuthor(): array
    {
        return [
            'nev' => (string)store::getParameter(\mkw\consts::Blogszerzo, ''),
            'leiras' => (string)store::getParameter(\mkw\consts::Blogszerzoleiras, ''),
        ];
    }

    /** BlogPosting JSON-LD a blogposzt látható adataiból. */
    public static function blogPostingJsonLd(array $poszt): string
    {
        $url = self::getCanonicalUrl();
        $szerzo = self::getBlogAuthor();
        $data = [
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            '@id' => $url . '#blogposting',
            'mainEntityOfPage' => $url,
            'url' => $url,
            'headline' => self::plainText($poszt['cim'] ?? '', 110),
            'author' => $szerzo['nev']
                ? array_filter([
                    '@type' => 'Person',
                    'name' => $szerzo['nev'],
                    'description' => $szerzo['leiras'],
                ], 'strlen')
                : ['@id' => self::getOrganizationId()],
            'publisher' => ['@id' => self::getOrganizationId()],
        ];
        $kep = self::absoluteUrl($poszt['kepurllarge'] ?? '');
        if ($kep) {
            $data['image'] = [$kep];
        }
        $kivonat = self::plainText($poszt['kivonat'] ?? '', 500);
        if ($kivonat) {
            $data['description'] = $kivonat;
        }
        if (!empty($poszt['megjelenesdatumiso'])) {
            $data['datePublished'] = $poszt['megjelenesdatumiso'];
        }
        if (!empty($poszt['lastmodiso'])) {
            $data['dateModified'] = $poszt['lastmodiso'];
        }
        return self::jsonLd($data);
    }

    /**
     * ItemList a listaoldalakhoz: a kártyákon lévő termékek sorrendje és URL-je.
     * A kártyákra nem való Product/Offer: az árat és a készletet a terméklap mondja meg.
     */
    public static function itemListJsonLd(array $termekek, int $offset = 0): string
    {
        $elements = [];
        $seen = [];
        foreach ($termekek as $termek) {
            $slug = $termek['slug'] ?? '';
            // a lista változatonként külön sort mutat, ugyanarra a termékre; az ItemList-ben egyszer szerepel
            if (!$slug || isset($seen[$slug])) {
                continue;
            }
            $seen[$slug] = true;
            $elements[] = [
                '@type' => 'ListItem',
                'position' => $offset + count($elements) + 1,
                'url' => self::absoluteUrl('/termek/' . $slug),
            ];
        }
        if (!$elements) {
            return '';
        }
        return self::jsonLd([
            '@context' => 'https://schema.org',
            '@type' => 'ItemList',
            'itemListElement' => $elements,
        ]);
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
