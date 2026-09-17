<?xml version="1.0" encoding="UTF-8"?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
{foreach $sitemaps as $_sitemap}
<sitemap>
<loc>{$_sitemap.url}</loc>
</sitemap>
{/foreach}
</sitemapindex>
