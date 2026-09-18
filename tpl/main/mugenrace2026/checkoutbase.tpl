<!DOCTYPE html>
<html lang="{$shortlocale|default:'en'}">
	<head>
		<meta charset="utf-8">
		<meta name="robots" content="{$robots|default:'noindex,follow'}">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta property="og:site_name" content="{$cegadatok.markanev|default:'Mugen Race'|escape}">
        {block "meta"}{/block}
		<title>{$pagetitle|default:$globaltitle|default}</title>
		{include 'headtrackingcodes.tpl'}
		<link type="application/rss+xml" rel="alternate" title="{$feedhirtitle|default}" href="/feed/hir">
		<link type="application/rss+xml" rel="alternate" title="{$feedtermektitle|default}" href="/feed/termek">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Chicle&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap">
        <link type="text/css" rel="stylesheet" href="/themes/main/mugenrace2026/mgrall.css">
		{block "css"}{/block}
        {if ($dev)}
        <script defer src="/js/main/mugenrace2026/jquery-1.11.1.min.js"></script>

		<script defer src="/js/main/mugenrace2026/jquery-migrate-1.2.1.js"></script>

		<script defer src="/js/main/mugenrace2026/jquery.magnific-popup.min.js"></script>
		<script defer src="/js/main/mugenrace2026/jquery.slider.min.js"></script>
                <script defer src="/js/main/mugenrace2026/jquery.debounce.min.js"></script>
        <script defer src="/js/main/mugenrace2026/bootstrap-transition.js"></script>
		<script defer src="/js/main/mugenrace2026/bootstrap-modal.js"></script>
		<script defer src="/js/main/mugenrace2026/bootstrap-tab.js"></script>
		<script defer src="/js/main/mugenrace2026/bootstrap-typeahead.js"></script>
		<script defer src="/js/main/mugenrace2026/bootstrap-tooltip.js"></script>
		<script defer src="/js/main/mugenrace2026/h5f.js"></script>
		<script defer src="/js/main/mugenrace2026/matt-accordion.js"></script>
        {else}
        <script defer src="/js/main/mugenrace2026/mgrbootstrap.js?v={$bootstrapjsversion}"></script>
        {/if}
		{block "script"}{/block}
        {if ($dev)}
		<script defer src="/js/main/mugenrace2026/mgrmsg.js"></script>
		<script defer src="/js/main/mugenrace2026/mgr.js"></script>
		<script defer src="/js/main/mugenrace2026/checks.js"></script>
		<script defer src="/js/main/mugenrace2026/checkout.js"></script>
		<script defer src="/js/main/mugenrace2026/cart.js"></script>
		<script defer src="/js/main/mugenrace2026/fiok.js"></script>
		<script defer src="/js/main/mugenrace2026/mugenrace.js"></script>
        {else}
        <script defer src="/js/main/mugenrace2026/mgrapp.js?v={$jsversion}"></script>
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