<!DOCTYPE html>
<html lang="hu">
	<head>
		<meta charset="utf-8">
		<meta name="description" content="{$seodescription|default}">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta property="og:site_name" content="Mugenrace webshop"/>
        {block "meta"}{/block}
		<title>{$pagetitle|default}</title>
		{include 'headtrackingcodes.tpl'}
		<link type="application/rss+xml" rel="alternate" title="{$feedhirtitle|default}" href="/feed/hir">
		<link type="application/rss+xml" rel="alternate" title="{$feedtermektitle|default}" href="/feed/termek">
        <link rel="stylesheet" href="/themes/main/mugenrace2026/royalslider/royalslider.css">
        <link rel="stylesheet" href="/themes/main/mugenrace2026/royalslider/skins/default-inverted/rs-default-inverted.css">
        <link type="text/css" rel="stylesheet" href="/themes/main/mugenrace2026/mgr.css">
        <link type="text/css" rel="stylesheet" href="/themes/main/mugenrace2026/style.css">
				<link type="text/css" rel="stylesheet" href="/themes/main/mugenrace2026/style-2.css">
		{block "css"}{/block}
        {if ($dev)}
        <script src="/js/main/mugenrace2026/jquery-1.11.1.min.js"></script>

		<script src="/js/main/mugenrace2026/jquery-migrate-1.2.1.js"></script>

		<script src="/js/main/mugenrace2026/jquery.magnific-popup.min.js"></script>
		<script src="/js/main/mugenrace2026/jquery.slider.min.js"></script>
        <script src="/js/main/mugenrace2026/jquery.royalslider.min.js"></script>
        <script src="/js/main/mugenrace2026/jquery.debounce.min.js"></script>
        <script src="/js/main/mugenrace2026/bootstrap-transition.js"></script>
		<script src="/js/main/mugenrace2026/bootstrap-modal.js"></script>
		<script src="/js/main/mugenrace2026/bootstrap-tab.js"></script>
		<script src="/js/main/mugenrace2026/bootstrap-typeahead.js"></script>
		<script src="/js/main/mugenrace2026/bootstrap-tooltip.js"></script>
		<script src="/js/main/mugenrace2026/h5f.js"></script>
		<script src="/js/main/mugenrace2026/matt-accordion.js"></script>
        {else}
        <script src="/js/main/mugenrace2026/mgrbootstrap.js?v={$bootstrapjsversion}"></script>
        {/if}
		{block "script"}{/block}
        {if ($dev)}
		<script src="/js/main/mugenrace2026/mgrmsg.js"></script>
		<script src="/js/main/mugenrace2026/mgr.js"></script>
		<script src="/js/main/mugenrace2026/checks.js"></script>
		<script src="/js/main/mugenrace2026/checkout.js"></script>
		<script src="/js/main/mugenrace2026/cart.js"></script>
		<script src="/js/main/mugenrace2026/fiok.js"></script>
		<script src="/js/main/mugenrace2026/mugenrace.js"></script>
        {else}
        <script src="/js/main/mugenrace2026/mgrapp.js?v={$jsversion}"></script>
        {/if}
        {if ($GAFollow)}
        <script type="text/javascript">
            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', '{$GAFollow}']);
            _gaq.push(['_trackPageview']);

            (function() {
              var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
              ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
              var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
            })();
        </script>
        {/if}
	</head>
	<body class="whitebg checkout-page">
		<header>
			{include 'header.tpl'}
		</header>
        {block "body"}
        {/block}
		{block "stonebody"}
		{/block}
		<div id="dialogcenter" class="modal hide fade" tabindex="-1" role="dialog">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
		  </div>
		  <div class="modal-body"></div>
		  <div class="modal-footer"></div>
		</div>
		<div id="messagecenter" class="mfp-hide"></div>
	</body>
</html>