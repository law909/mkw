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
		<!--link type="text/css" rel="stylesheet" href="/themes/main/mugenrace/bootstrap.min.css">
		<link type="text/css" rel="stylesheet" href="/themes/main/mugenrace/bootstrap-responsive.min.css">
		<link type="text/css" rel="stylesheet" href="/themes/main/mugenrace/jquery.slider.min.css">
		<link type="text/css" rel="stylesheet" href="/themes/main/mugenrace/magnific-popup.css"-->
        <link rel="stylesheet" href="/themes/main/mugenrace/royalslider/royalslider.css">
        <link rel="stylesheet" href="/themes/main/mugenrace/royalslider/skins/default-inverted/rs-default-inverted.css">
        <link type="text/css" rel="stylesheet" href="/themes/main/mugenrace/mgr.css">
        <link type="text/css" rel="stylesheet" href="/themes/main/mugenrace/style.css">
        <link type="text/css" rel="stylesheet" href="/themes/main/mugenrace/magnify.css">
		{block "css"}{/block}
        {if ($dev)}
        <script src="/js/main/mugenrace/jquery-1.11.1.min.js"></script>

		<script src="/js/main/mugenrace/jquery-migrate-1.2.1.js"></script>

        <script src="/js/main/mugenrace/mgrerrorlog.js"></script>

		<script src="/js/main/mugenrace/jquery.magnific-popup.min.js"></script>
		<script src="/js/main/mugenrace/jquery.slider.min.js"></script>
        <script src="/js/main/mugenrace/jquery.royalslider.min.js"></script>
        <script src="/js/main/mugenrace/jquery.debounce.min.js"></script>
        <script src="/js/main/mugenrace/jquery.magnify.js"></script>
        <script src="/js/main/mugenrace/jquery.magnify-mobile.js"></script>
        <script src="/js/main/mugenrace/bootstrap-transition.js"></script>
		<script src="/js/main/mugenrace/bootstrap-modal.js"></script>
		<script src="/js/main/mugenrace/bootstrap-tab.js"></script>
		<script src="/js/main/mugenrace/bootstrap-typeahead.js"></script>
		<script src="/js/main/mugenrace/bootstrap-tooltip.js"></script>
		<script src="/js/main/mugenrace/h5f.js"></script>
		<script src="/js/main/mugenrace/matt-accordion.js"></script>
        {else}
        <script src="/js/main/mugenrace/mgrbootstrap.js?v={$bootstrapjsversion}"></script>
        {/if}
		{block "script"}{/block}
        {if ($dev)}
		<script src="/js/main/mugenrace/mgrmsg.js"></script>
		<script src="/js/main/mugenrace/mgr.js"></script>
		<script src="/js/main/mugenrace/checks.js"></script>
		<script src="/js/main/mugenrace/checkout.js"></script>
		<script src="/js/main/mugenrace/cart.js"></script>
		<script src="/js/main/mugenrace/fiok.js"></script>
		<script src="/js/main/mugenrace/mugenrace.js"></script>
        {else}
        <script src="/js/main/mugenrace/mgrapp.js?v={$jsversion}"></script>
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
	<body class="bgimg">
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