<!DOCTYPE html>
<html lang="{$shortlocale|default:'hu'}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{$seodescription|default|escape}">
    {block "meta"}{/block}
    <title>{$pagetitle|default:$globaltitle|escape}</title>
    <link rel="icon" href="/themes/main/lampion/favicon.svg" type="image/svg+xml">
    <link type="application/rss+xml" rel="alternate" title="{$feedhirtitle|default}" href="/feed/hir">
    <link type="application/rss+xml" rel="alternate" title="{$feedtermektitle|default}" href="/feed/termek">
    <link rel="stylesheet" href="/themes/main/lampion/style.css">
    {block "css"}{/block}
    <script src="/js/main/lampion/lampion.js" defer></script>
    {block "script"}{/block}
</head>
<body>
{block "stonebody"}{/block}
</body>
</html>
