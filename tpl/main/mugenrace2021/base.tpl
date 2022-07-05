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
<div class="menucontainer">
    <div class="menucloser">
        <a href="" class="menu-close"><img src="/themes/main/mugenrace2021/close_b.png" class="nav-img" alt="Close menu">CLOSE</a>
    </div>
    <svg class="menu-bottom-triangle" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none">
        <polygon fill="black" points="0,100 100,100 100,0"/>
    </svg>
    <div class="menu-bottom-text">NAVIGATION</div>
</div>
<div class="header">
    <div class="nav">
        <a href="" class="nav-menu"><img src="/themes/main/mugenrace2021/menu_w.png" class="nav-img" alt="Menu"></a>
        <div></div>
        <a href="/" class="nav-logo hcenter"><img src="/themes/main/mugenrace2021/logo_w.png" class="nav-logoimg" alt="Home"></a>
        <a href="" class="nav-search"><img src="/themes/main/mugenrace2021/search_w.png" class="nav-img" alt="Search"></a>
        <a href="" class="nav-cart"><img src="/themes/main/mugenrace2021/cart_w.png" class="nav-img" alt="Cart"></a>
        <a href="" class="nav-lang">HU</a>
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
{block "endscript"}{/block}
</body>
</html>