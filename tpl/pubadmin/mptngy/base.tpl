<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="utf-8">
    <meta name="description" content="{$seodescription|default}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    {block "meta"}{/block}
    <title>{$pagetitle|default}</title>
    {block "precss"}{/block}
    <link type="text/css" rel="stylesheet" href="/themes/pubadmin/mptngy2023/style.css?v=1">
    {block "css"}{/block}
    <script src="/js/iframeresizer/iframeResizer.contentWindow.min.js"></script>
    <script defer src="/js/iodine/iodine.min.umd.js"></script>
    <script defer src="/js/mask/cdn.min.js?v=3.10.5"></script>
    {if ($dev)}
        <script defer src="/js/alpine/cdn.js?v=3.10.5"></script>
    {else}
        <script defer src="/js/alpine/cdn.min.js?v=3.10.5"></script>
    {/if}
    {block "prescript"}{/block}
    {block "script"}{/block}
</head>
<body>
{block "body"}
{/block}
{block "stonebody"}
{/block}
{block "endscript"}{/block}
</body>
</html>