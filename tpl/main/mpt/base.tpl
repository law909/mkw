<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex,nofollow">
    <title>{$pagetitle|default}</title>
    <link type="text/css" rel="stylesheet" href="/themes/main/mpt/mpt.css">
    {if ($dev)}
        <script defer src="/js/alpine/cdn.js"></script>
    {else}
        <script defer src="/js/alpine/cdn.min.js"></script>
    {/if}
    {block "script"}{/block}
</head>
<body>
<div class="mpt-page">
    {block "body"}{/block}
</div>
</body>
</html>
