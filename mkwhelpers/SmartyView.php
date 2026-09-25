<?php

namespace mkwhelpers;

class SmartyView extends View
{

    // a webgyökér (index.php itt van), amihez a /js, /css hivatkozások abszolút útjai szólnak
    private static $assetRoot = null;
    // fájlonkénti módosítási idő gyorsítótár (kéréseN belül), hogy ne stat-eljünk feleslegesen
    private static $assetMtime = [];
    private static $imageSize = [];
    // a képeket kiszolgáló külön hoszt előtagja (config.ini main.imagepath), ha van
    private static $imagePrefix = null;

    public function __construct($compiledtplpath, $tplpath, $tplfilename, $configdir = '', $cachedir = '')
    {
        $this->tplengine = new \Smarty();
        $this->registerPlugins();
        $this->registerOutputFilters();
        $this->tplengine->setTemplateDir($tplpath);
        $this->tplengine->setCompileDir($compiledtplpath);
        $this->tplengine->setConfigDir($configdir);
        $this->tplengine->setCacheDir($cachedir);
        $this->tplfile = $tplfilename;
    }

    public function setVar($variable, $data)
    {
        $this->tplengine->assign($variable, $data);
    }

    public function getVar($variable)
    {
        return $this->tplengine->getTemplateVars($variable);
    }

    public function getTemplateResult()
    {
        return $this->tplengine->fetch($this->tplfile);
    }

    public function printTemplateResult($storePrevUri = false)
    {
        $this->tplengine->display($this->tplfile);
        if ($storePrevUri) {
            \mkw\store::storePrevUri();
        }
    }

    private function registerPlugins(): void
    {
        $this->tplengine->registerPlugin('modifier', 't', '\t');
        $this->tplengine->registerPlugin('modifier', 'at', '\at');
        $this->tplengine->registerPlugin('modifier', 'haveJog', '\haveJog');
        $this->tplengine->registerPlugin('modifier', 'bizformat', '\bizformat');
        $this->tplengine->registerPlugin('modifier', 'number_format', '\number_format');
        $this->tplengine->registerPlugin('modifier', 'prefixUrl', '\prefixUrl');
        $this->tplengine->registerPlugin('modifier', 'trim', '\trim');
        // szerkesztőből érkező HTML-ben a h1 a lap saját h1-ével versenyezne
        $this->tplengine->registerPlugin('modifier', 'demoteh1', ['\mkwhelpers\SmartyView', 'demoteH1']);

        // sablonban használt PHP függvények: regisztráció nélkül a Smarty 4 deprecated-et naplóz.
        // Szándékosan \ nélkül: így a fordított kód közvetlen hívás lesz, nem call_user_func_array.
        foreach (['min', 'floor', 'intdiv', 'implode', 'array_key_exists', 'htmlentities', 'strpos', 'date'] as $f) {
            $this->tplengine->registerPlugin('modifier', $f, $f);
        }

        // karbantartó mezőelrendezés táblázat helyett, lásd mezocsoportBlock() / mezoBlock()
        $this->tplengine->registerPlugin('block', 'mezocsoport', ['\mkwhelpers\SmartyView', 'mezocsoportBlock']);
        $this->tplengine->registerPlugin('block', 'mezo', ['\mkwhelpers\SmartyView', 'mezoBlock']);

        $this->tplengine->registerPlugin('function', 't', function (array $params) {
            return \t($params['msgid'] ?? $params['text'] ?? $params['value'] ?? '');
        });

        $this->tplengine->registerPlugin('function', 'at', function (array $params) {
            return \at($params['msgid'] ?? $params['text'] ?? $params['value'] ?? '');
        });

        $this->tplengine->registerPlugin('function', 'haveJog', function (array $params) {
            return \haveJog($params['jog'] ?? $params['value'] ?? '');
        });

        $this->tplengine->registerPlugin('function', 'bizformat', function (array $params) {
            return \bizformat(
                $params['mit'] ?? $params['value'] ?? $params['num'] ?? 0,
                $params['mire'] ?? $params['decimals'] ?? false
            );
        });

        $this->tplengine->registerPlugin('function', 'number_format', function (array $params) {
            return \number_format(
                $params['num'] ?? $params['number'] ?? $params['value'] ?? 0,
                (int)($params['decimals'] ?? 0),
                $params['decimal_separator'] ?? $params['dec_point'] ?? '.',
                $params['thousands_separator'] ?? $params['thousands_sep'] ?? ','
            );
        });

        $this->tplengine->registerPlugin('function', 'prefixUrl', function (array $params) {
            return \prefixUrl(
                $params['prefix'] ?? $params['value'] ?? '',
                $params['url'] ?? ''
            );
        });
    }

    /**
     * Karbantartó mezőcsoport: `{mezocsoport cim="Alapadatok"}…{/mezocsoport}`. A benne lévő
     * `{mezo}`-k rácsba rendeződnek (soronként két címke–mező pár, keskeny képernyőn egy).
     * A `cim` elhagyható; az at()-tal fordítódik, mint a sablonok többi felirata.
     */
    public static function mezocsoportBlock(array $params, $content, $template, &$repeat)
    {
        if ($repeat) {
            return '';
        }
        $cim = isset($params['cim']) && $params['cim'] !== ''
            ? '<div class="mattkarb-szakaszcim">' . htmlspecialchars(\at($params['cim'])) . '</div>'
            : '';
        return '<div class="mezocsoport">' . $cim . '<div class="mezok">' . $content . '</div></div>';
    }

    /**
     * Egy címke–mező pár: `{mezo cimke="Név" for="NevEdit" szeles=true}<input …>{/mezo}`.
     * `for` nélkül a címke nem kattintható (pl. több mezős sor), `szeles` = a mező a sor végéig ér,
     * `nyers` = a címke már kész szöveg (pl. webshop neve), nem fordítandó.
     */
    public static function mezoBlock(array $params, $content, $template, &$repeat)
    {
        if ($repeat) {
            return '';
        }
        $cimke = (string)($params['cimke'] ?? '');
        if ($cimke !== '' && empty($params['nyers'])) {
            $cimke = \at($cimke);
        }
        $cimke = htmlspecialchars($cimke) . ($cimke !== '' ? ':' : '');
        $label = !empty($params['for'])
            ? '<label class="mezo-cimke" for="' . htmlspecialchars($params['for']) . '">' . $cimke . '</label>'
            : '<span class="mezo-cimke">' . $cimke . '</span>';
        $class = 'mezo' . (!empty($params['szeles']) ? ' mezo-szeles' : '');
        return '<div class="' . $class . '">' . $label . '<div class="mezo-ertek">' . $content . '</div></div>';
    }

    /**
     * A CMS-mezőkben (termékleírás, statikus lap, hír) beírt `<h1>` `<h2>`-vé válik: a lap
     * címsorát a sablon adja, két h1 a lapon összezavarja a lap témáját.
     */
    public static function demoteH1($html)
    {
        return \preg_replace('#<(/?)h1(\s[^>]*)?>#i', '<$1h2$2>', (string)$html);
    }

    private function registerOutputFilters(): void
    {
        // a kirenderelt HTML helyi .js/.css hivatkozásaihoz automatikusan ?v=<mtime> kerül
        $this->tplengine->registerFilter('output', ['\mkwhelpers\SmartyView', 'versionAssets']);
        $this->tplengine->registerFilter('output', ['\mkwhelpers\SmartyView', 'improveImages']);
    }

    /**
     * A kész HTML helyi <img> tagjait egészíti ki: valódi `width`/`height` (a fájl fejlécéből)
     * és `loading="lazy"`. Így nem kell húsz sablonban kézzel karbantartani, és a méretek
     * mindig a tényleges képhez tartoznak — a képgenerálás megtartja az arányt, tehát beégetett
     * magasságot nem lehetne írni.
     *
     * A hajtás feletti képeket a sablon `fetchpriority="high"`-jal jelöli: azokat nem lazyzza.
     * A már meglévő `loading` / `width` / `height` attribútumokat nem írja felül.
     *
     * Smarty output filter callback: ($output, $template) => string
     */
    public static function improveImages($output, $template = null)
    {
        if (self::$assetRoot === null) {
            self::$assetRoot = \dirname(__DIR__);
        }
        return \preg_replace_callback(
            '#<img\s[^>]*>#i',
            function ($m) {
                $tag = $m[0];
                $add = '';
                if (!\preg_match('#\sloading=#i', $tag) && !\preg_match('#\sfetchpriority=#i', $tag)) {
                    $add .= ' loading="lazy"';
                }
                if (!\preg_match('#\s(?:width|height)=#i', $tag)
                    && \preg_match('#\ssrc="([^"?]+)#i', $tag, $src)
                ) {
                    $size = self::localImageSize(self::toLocalPath($src[1]));
                    if ($size) {
                        $add .= ' width="' . $size[0] . '" height="' . $size[1] . '"';
                    }
                }
                return $add === '' ? $tag : \rtrim(\substr($tag, 0, -1), '/ ') . $add . '>';
            },
            $output
        );
    }

    /**
     * A képhivatkozás útvonala a webgyökérhez képest. Több telepítés külön hosztról szolgálja
     * ki a képeket (config.ini `main.imagepath`), a fájl viszont helyben van, tehát a méretét
     * akkor is ki tudjuk olvasni. Idegen hivatkozásra üres stringet ad.
     */
    private static function toLocalPath(string $src): string
    {
        if ($src !== '' && $src[0] === '/') {
            return $src;
        }
        if (self::$imagePrefix === null) {
            self::$imagePrefix = \rtrim((string)\mkw\store::getConfigValue('main.imagepath', ''), '/');
        }
        if (self::$imagePrefix !== '' && \str_starts_with($src, self::$imagePrefix . '/')) {
            return \substr($src, \strlen(self::$imagePrefix));
        }
        return '';
    }

    /** Egy helyi kép mérete a fájl fejlécéből, kérésenként egyszer beolvasva. */
    private static function localImageSize(string $path): ?array
    {
        if ($path === '') {
            return null;
        }
        if (!\array_key_exists($path, self::$imageSize)) {
            $file = self::$assetRoot . \rawurldecode($path);
            $size = \is_file($file) ? @\getimagesize($file) : false;
            self::$imageSize[$path] = ($size && $size[0] && $size[1]) ? [$size[0], $size[1]] : null;
        }
        return self::$imageSize[$path];
    }

    /**
     * Cache-busting: a kész HTML-ben minden helyi (vezető /-rel kezdődő) .js/.css
     * hivatkozáshoz hozzáfűzi a fájl módosítási idejét verzióként (?v=<mtime>).
     * Így soha nem kell sablonban verziót írni, és csak a ténylegesen megváltozott
     * fájlokra ürül a böngésző-cache. A nem létező (vagy külső) hivatkozásokat nem bántja.
     *
     * Smarty output filter callback: ($output, $template) => string
     */
    public static function versionAssets($output, $template = null)
    {
        if (self::$assetRoot === null) {
            self::$assetRoot = \dirname(__DIR__); // mkwhelpers/ szülője = repo/webgyökér
        }
        return \preg_replace_callback(
            '#(\s(?:src|href)=")(/[^"?]+\.(?:js|css))(\?[^"]*)?(")#i',
            function ($m) {
                $path = $m[2];
                if (!\array_key_exists($path, self::$assetMtime)) {
                    $file = self::$assetRoot . $path;
                    self::$assetMtime[$path] = \is_file($file) ? \filemtime($file) : null;
                }
                $mtime = self::$assetMtime[$path];
                if ($mtime === null) {
                    return $m[0]; // nincs ilyen helyi fájl – érintetlenül hagyjuk
                }
                $query = $m[3]; // esetleges meglévő query string (a ?-lel együtt)
                $sep = ($query === '' ? '?' : '&');
                return $m[1] . $path . $query . $sep . 'v=' . $mtime . $m[4];
            },
            $output
        );
    }
}