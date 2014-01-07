<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
{foreach $urls as $_url}
<url>
<loc>{$_url.url}</loc>
{if ($_url.images|default)}
{foreach $_url.images as $_image}
<image:image>
<image:loc>{$_image.url}</image:loc>
{if ($_image.title|default)}
<image:title>{$_image.title}</image:title>
{/if}
</image:image>
{/foreach}
{/if}
<lastmod>{$_url.lastmod}</lastmod>
<changefreq>{$_url.changefreq}</changefreq>
<priority>{$_url.priority}</priority>
</url>
{/foreach}
</urlset>