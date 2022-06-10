<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="utf-8">
    <meta name="description" content="{$seodescription|default}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta property="og:site_name" content="mugenrace.com"/>
    {block "meta"}{/block}
    <title>{$pagetitle|default}</title>
    <link type="application/rss+xml" rel="alternate" title="{$feedhirtitle|default}" href="/feed/hir">
    <link type="application/rss+xml" rel="alternate" title="{$feedtermektitle|default}" href="/feed/termek">
    <link type="text/css" rel="stylesheet" href="/themes/main/mugenrace2021/style.css">
    {block "css"}{/block}
    {block "script"}{/block}
</head>
<body>
<div class="header">
    <div class="menu">
    <a href="" class="header-menu"><img src="/themes/main/mugenrace2021/menu_w.png" alt="Menu"></a>
    <a href="/" class="header-logo hcenter"><img src="/themes/main/mugenrace2021/logo_w.png" alt="Home"></a>
    <a href="" class="header-search"><img src="/themes/main/mugenrace2021/search_w.png" alt="Search"></a>
    <a href="" class="header-cart"><img src="/themes/main/mugenrace2021/cart_w.png" alt="Cart"></a>
    <a href="" class="header-lang">HU</a>
    </div>
    <div class="header-triangle">
    <svg class="header-triangle-size" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none">
        <polygon fill="black" points="0,0 0,100 100,0"/>
    </svg>
    </div>
</div>
{block "body"}
{/block}
{block "stonebody"}
{/block}
</body>
</html>