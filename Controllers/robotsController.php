<?php

namespace Controllers;

use Services\SeoService;

/**
 * A /robots.txt tartalma. Azért generált és nem statikus fájl, mert a `Sitemap:` sornak a
 * telepítés kanonikus domainjére kell mutatnia (`canonicalbaseurl`), és mert így minden
 * telepítés ugyanazt a tiltáslistát kapja.
 *
 * A webgyökérben lévő fizikai robots.txt-t az Apache szolgálja ki (`.htaccess`:
 * RewriteCond !-f), tehát ahol kézzel írt fájl van, az marad az érvényes.
 *
 * AI-crawlert (GPTBot, ClaudeBot, PerplexityBot, Google-Extended) szándékosan nem tiltunk:
 * a márkának érdeke, hogy a termékeit a saját boltjából tanulják meg, ne a viszonteladóktól.
 */
class robotsController extends \mkwhelpers\Controller
{

    /** Funkcionális útvonalak: se tartalmuk, se indexelnivalójuk nincs, de viszik a crawl budgetet. */
    const DISALLOWPATHS = [
        '/admin',
        '/pubadmin',
        '/kosar/',
        '/checkout',
        '/login',
        '/logout',
        '/fiok',
        '/regisztracio',
        '/passreminder/',
        '/search',
        '/kereses',
        '/szuro',
        '/termekertesito/',
        '/termekertekeles',
        '/valtozat',
        '/valtozatar',
        '/valtozatadatok',
        '/getmeretszinhez',
        '/irszam',
        '/varos',
    ];

    /** Csak duplikátumot hozó paraméterek. A lapozó (`pageno`) szándékosan nincs köztük. */
    const DISALLOWPARAMS = ['utm_', 'gclid', 'fbclid', 'msclkid', 'sessid'];

    public function show()
    {
        $sorok = ['User-agent: *', 'Allow: /', ''];
        foreach (self::DISALLOWPATHS as $path) {
            $sorok[] = 'Disallow: ' . $path;
        }
        $sorok[] = '';
        foreach (self::DISALLOWPARAMS as $param) {
            $sorok[] = 'Disallow: /*?' . $param;
        }
        $sorok[] = '';
        $sorok[] = 'Sitemap: ' . SeoService::absoluteUrl('/sitemap_index.xml');

        header('Content-type: text/plain; charset=utf-8');
        echo implode("\n", $sorok) . "\n";
    }
}
