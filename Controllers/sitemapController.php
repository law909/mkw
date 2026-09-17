<?php

namespace Controllers;

use Entities\Parameterek;
use Services\SeoService;

/**
 * Sitemap index + szakaszonkénti sitemapok.
 *
 * Az egyetlen, több megabájtos sitemap.xml helyett típusonként külön fájl, és a termékekből
 * darabonként MAXURL URL. A `lastmod` a tartalom változását követi (a terméknél a
 * `contentmod`, lásd Listeners\TermekListener), nem a napi szinkron futását; a
 * `changefreq`/`priority` kimarad, mert a Google figyelmen kívül hagyja őket.
 *
 * Minden URL a kanonikus domainre mutat (Services\SeoService), és csak olyan oldal kerül be,
 * amelyik 200-at ad és indexelhető.
 */
class sitemapController extends \mkwhelpers\Controller
{

    /** URL / sitemap fájl. A szabvány 50 000-et enged, a feladat 10 000-nél vág. */
    const MAXURL = 10000;

    public function __construct()
    {
        $this->setEntityName(Parameterek::class);
        parent::__construct();
    }

    public function view()
    {
        $gd = new \mkw\generalDataLoader();
        $view = $this->createView('sitemap.tpl');
        $gd->loadData($view);
        $view->printTemplateResult(false);
    }

    /** A régi, mindent tartalmazó /sitemap.xml helyére az index lépett. */
    public function toBot()
    {
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: /sitemap_index.xml');
    }

    public function index()
    {
        $this->sendXml($this->buildIndex());
    }

    public function pages()
    {
        $this->sendXml($this->renderUrlset($this->buildPages()));
    }

    public function categories()
    {
        $this->sendXml($this->renderUrlset($this->buildCategories()));
    }

    public function brands()
    {
        $this->sendXml($this->renderUrlset($this->buildBrands()));
    }

    public function blog()
    {
        $this->sendXml($this->renderUrlset($this->buildBlog()));
    }

    public function products()
    {
        $num = max(1, $this->params->getIntParam('num', 1));
        if ($num > $this->getProductFileCount()) {
            \mkw\store::sendNotFoundHeaders();
            return;
        }
        $this->sendXml($this->renderUrlset($this->buildProducts($num)));
    }

    /** Az admin „Generál” gombja: az egész készlet kiírása fájlba. */
    public function create()
    {
        $path = \mkw\store::getConfigValue('mainpath');
        $files = ['sitemap_index.xml' => $this->buildIndex()];
        $files['sitemap-pages.xml'] = $this->renderUrlset($this->buildPages());
        $files['sitemap-categories.xml'] = $this->renderUrlset($this->buildCategories());
        $files['sitemap-brands.xml'] = $this->renderUrlset($this->buildBrands());
        $files['sitemap-blog.xml'] = $this->renderUrlset($this->buildBlog());
        for ($i = 1; $i <= $this->getProductFileCount(); $i++) {
            $files['sitemap-products-' . $i . '.xml'] = $this->renderUrlset($this->buildProducts($i));
        }

        $hiba = [];
        foreach ($files as $nev => $tartalom) {
            if (file_put_contents($path . $nev, $tartalom) === false) {
                $hiba[] = $nev;
            }
        }
        // a régi, egybefüggő fájl félrevezetné a keresőt, ha ott maradna a webgyökérben
        if (is_file($path . 'sitemap.xml')) {
            @unlink($path . 'sitemap.xml');
        }

        $gd = new \mkw\generalDataLoader();
        $view = $this->createView('sitemap.tpl');
        $gd->loadData($view);
        $view->setVar(
            'szoveg',
            $hiba
                ? 'Nem sikerült file-ba írni: ' . implode(', ', $hiba)
                : t('A sitemap kész.') . ' (' . count($files) . ' fájl)'
        );
        $view->printTemplateResult(false);
    }

    // -- összeállítás ------------------------------------------------------

    private function buildIndex()
    {
        $sitemaps = [
            ['url' => SeoService::absoluteUrl('/sitemap-pages.xml')],
            ['url' => SeoService::absoluteUrl('/sitemap-categories.xml')],
            ['url' => SeoService::absoluteUrl('/sitemap-brands.xml')],
            ['url' => SeoService::absoluteUrl('/sitemap-blog.xml')],
        ];
        for ($i = 1; $i <= $this->getProductFileCount(); $i++) {
            $sitemaps[] = ['url' => SeoService::absoluteUrl('/sitemap-products-' . $i . '.xml')];
        }
        $view = $this->createView('sitemapindexxml.tpl');
        $view->setVar('sitemaps', $sitemaps);
        return $view->getTemplateResult();
    }

    private function buildPages()
    {
        $urls = [];
        $this->addUrl($urls, '/', $this->getLastContentChange());

        $router = \mkw\store::getRouter();
        $urls2 = [];
        foreach ($this->getRepo(\Entities\Statlap::class)->getForSitemapXml() as $sor) {
            $this->addUrl($urls2, $router->generate('showstatlap', false, ['lap' => $sor['slug']]), $sor['lastmod']);
        }
        $this->addUrl($urls2, $router->generate('showblogposztlist'), $this->getMaxLastmod($this->buildBlog()));
        $this->addUrl($urls2, $router->generate('markak'), $this->getMaxLastmod($this->buildBrands()));
        return array_merge($urls, $urls2);
    }

    private function buildCategories()
    {
        $urls = [];
        $router = \mkw\store::getRouter();
        foreach ($this->getRepo(\Entities\TermekFa::class)->getForSitemapXml() as $sor) {
            $this->addUrl(
                $urls,
                $router->generate('showtermekfa', false, ['slug' => $sor['slug']]),
                $sor['lastmod'],
                $this->buildImages($sor['kepurl'], $sor['kepleiras'])
            );
        }
        return $urls;
    }

    private function buildBrands()
    {
        $urls = [];
        $router = \mkw\store::getRouter();
        foreach ($this->getRepo(\Entities\Termekcimketorzs::class)->getForSitemapXml() as $sor) {
            $this->addUrl($urls, $router->generate('showmarka', false, ['slug' => $sor['slug']]), $sor['lastmod']);
        }
        return $urls;
    }

    private function buildBlog()
    {
        $urls = [];
        $router = \mkw\store::getRouter();
        foreach ($this->getRepo(\Entities\Blogposzt::class)->getForSitemapXml() as $sor) {
            $this->addUrl($urls, $router->generate('showblogposzt', false, ['blogposzt' => $sor['slug']]), $sor['lastmod']);
        }
        return $urls;
    }

    private function buildProducts($fileNum)
    {
        $urls = [];
        $router = \mkw\store::getRouter();
        $kepekrepo = $this->getRepo(\Entities\TermekKep::class);
        $rec = $this->getRepo(\Entities\Termek::class)->getForSitemapXml(($fileNum - 1) * self::MAXURL, self::MAXURL);
        foreach ($rec as $sor) {
            $kepek = $this->buildImages($sor['kepurl'], $sor['kepleiras']);
            foreach ($kepekrepo->getByTermekForSitemapXml($sor['id']) as $kep) {
                $kepek = array_merge($kepek, $this->buildImages($kep['url'], $kep['leiras']));
            }
            $this->addUrl($urls, $router->generate('showtermek', false, ['slug' => $sor['slug']]), $sor['lastmod'], $kepek);
        }
        return $urls;
    }

    private function getProductFileCount()
    {
        $db = $this->getRepo(\Entities\Termek::class)->getSitemapXmlCount();
        return max(1, (int)ceil($db / self::MAXURL));
    }

    // -- segédek -----------------------------------------------------------

    /** Ugyanaz az URL egyszer szerepelhet; a lastmod nélküli sorból elmarad a tag. */
    private function addUrl(array &$urls, $path, $lastmod = null, array $images = [])
    {
        $url = SeoService::absoluteUrl($path);
        if (!$url || array_key_exists($url, $urls)) {
            return;
        }
        $u = ['url' => htmlspecialchars($url, ENT_XML1)];
        if ($lastmod) {
            $u['lastmod'] = (new \DateTime($lastmod))->format('Y-m-d');
        }
        if ($images) {
            $u['images'] = $images;
        }
        $urls[$url] = $u;
    }

    private function buildImages($kepurl, $leiras)
    {
        if (!$kepurl) {
            return [];
        }
        return [[
            // a sitemapba a nagy (1000 px-es) változat való, nem az eredeti feltöltött fájl
            'url' => htmlspecialchars(SeoService::absoluteUrl(\mkw\store::createBigImageUrl($kepurl)), ENT_XML1),
            'title' => htmlspecialchars((string)$leiras, ENT_XML1),
        ]];
    }

    /** A legfrissebb tartalmi változás a webshopon — ez a főoldal lastmod-ja, nem a mai nap. */
    private function getLastContentChange()
    {
        $termek = $this->getRepo(\Entities\Termek::class)->getMaxSitemapLastmod();
        $datumok = array_filter([
            $termek ? (new \DateTime($termek))->format('Y-m-d') : null,
            $this->getMaxLastmod($this->buildBlog()),
        ]);
        return $datumok ? max($datumok) : null;
    }

    private function getMaxLastmod(array $urls)
    {
        $datumok = array_column($urls, 'lastmod');
        return $datumok ? max($datumok) : null;
    }

    private function renderUrlset(array $urls)
    {
        $view = $this->createView('sitemapxml.tpl');
        $view->setVar('urls', array_values($urls));
        return $view->getTemplateResult();
    }

    private function sendXml($xml)
    {
        header('Content-type: application/xml; charset=utf-8');
        header('X-Robots-Tag: noindex');
        echo $xml;
    }
}
