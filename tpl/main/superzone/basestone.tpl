<!DOCTYPE html>
<html lang="hu">
	<head>
		<meta charset="utf-8">
		<meta name="description" content="{$seodescription|default}">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        {block "meta"}{/block}
		<title>{$pagetitle|default}</title>
		<link type="application/rss+xml" rel="alternate" title="{$feedhirtitle|default}" href="/feed/hir">
		<link type="application/rss+xml" rel="alternate" title="{$feedtermektitle|default}" href="/feed/termek">
        <link href="/themes/main/superzone/css/bootstrap.min.css" rel="stylesheet">
        <link href="/themes/main/superzone/style.css" rel="stylesheet">
        {block "css"}{/block}
    </head>
	<body {block "bodyclass"}{/block}>
        {block "stonebody"}
        {/block}
	</body>
</html>