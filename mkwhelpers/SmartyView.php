<?php

namespace mkwhelpers;

class SmartyView extends View
{

    // a webgyökér (index.php itt van), amihez a /js, /css hivatkozások abszolút útjai szólnak
    private static $assetRoot = null;
    // fájlonkénti módosítási idő gyorsítótár (kéréseN belül), hogy ne stat-eljünk feleslegesen
    private static $assetMtime = [];

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

    private function registerOutputFilters(): void
    {
        // a kirenderelt HTML helyi .js/.css hivatkozásaihoz automatikusan ?v=<mtime> kerül
        $this->tplengine->registerFilter('output', ['\mkwhelpers\SmartyView', 'versionAssets']);
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