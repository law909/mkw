<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
{foreach $urls as $_url}
<url>
<loc>{$_url.url}</loc>
<lastmod>{$_url.lastmod}</lastmod>
<changefreq>{$_url.changefreq}</changefreq>
<priority>{$_url.priority}</priority>
</url>
{/foreach}
</urlset>