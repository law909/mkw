<!DOCTYPE html>
<html lang="hu">
	<head>
		<meta charset="utf-8">
		<meta name="description" content="{$seodescription}">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<title>{$pagetitle}</title>
		<link type="application/rss+xml" rel="alternate" title="{$feedhirtitle}" href="/feed/hir">
		<link type="application/rss+xml" rel="alternate" title="{$feedtermektitle}" href="/feed/termek">
		<link type="text/css" rel="stylesheet" href="/themes/main/mkwnew/bootstrap.css">
		<link type="text/css" rel="stylesheet" href="/themes/main/mkwnew/jquery.autocomplete.css">
		<link type="text/css" rel="stylesheet" href="/themes/main/mkwnew/style.css">
		<link type="text/css" rel="stylesheet" href="/themes/main/mkwnew/header.css">
		<link type="text/css" rel="stylesheet" href="/themes/main/mkwnew/footer.css">
		{block "css"}{/block}
		<script src="/js/main/mkwnew/jquery.js"></script>
		<script src="/js/main/mkwnew/jquery.autocomplete.min.js"></script>
		{block "script"}{/block}
		<script src="/js/main/mkwnew/mkwnew.js"></script>
	</head>
	<body>
		<header>
			{include 'header.tpl'}
		</header>
		<div class="container">
			{block "kozep"}
			{/block}
		</div>
		<footer>
			{include 'footer.tpl'}
		</footer>
	</body>
</html>