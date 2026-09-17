<!DOCTYPE html>
<html lang="hu">
	<head>
		<meta charset="utf-8">
		<meta name="description" content="{$seodescription|default}">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta property="og:site_name" content="Mindent Kapni Webáruház"/>
        {block "meta"}{/block}
		<title>{$pagetitle|default}</title>
		<link type="application/rss+xml" rel="alternate" title="{$feedhirtitle|default}" href="/feed/hir">
		<link type="application/rss+xml" rel="alternate" title="{$feedtermektitle|default}" href="/feed/termek">
        {block "vendorcss"}{/block}
        <link type="text/css" rel="stylesheet" href="/themes/main/mkwcansas/mkw.css">
        <link type="text/css" rel="stylesheet" href="/themes/main/mkwcansas/style.css">
		{block "css"}{/block}
        {if ($dev)}
        <script defer src="/js/main/mkwcansas/jquery-1.11.1.min.js"></script>

		<script defer src="/js/main/mkwcansas/jquery-migrate-1.2.1.js"></script>

		<script defer src="/js/main/mkwcansas/jquery.magnific-popup.min.js"></script>
		<script defer src="/js/main/mkwcansas/jquery.slider.min.js"></script>
        <script defer src="/js/main/mkwcansas/jquery.debounce.min.js"></script>
        <script defer src="/js/main/mkwcansas/jquery.inputmask.min.js"></script>
        <script defer src="/js/main/mkwcansas/bootstrap-transition.js"></script>
		<script defer src="/js/main/mkwcansas/bootstrap-modal.js"></script>
		<script defer src="/js/main/mkwcansas/bootstrap-tab.js"></script>
		<script defer src="/js/main/mkwcansas/bootstrap-typeahead.js"></script>
		<script defer src="/js/main/mkwcansas/bootstrap-tooltip.js"></script>
		<script defer src="/js/main/mkwcansas/h5f.js"></script>
		<script defer src="/js/main/mkwcansas/matt-accordion.js"></script>
        {else}
        <script defer src="/js/main/mkwcansas/mkwbootstrap.js?v={$bootstrapjsversion}"></script>
        {/if}
		{block "script"}{/block}
        {if ($dev)}
		<script defer src="/js/main/mkwcansas/mkwmsg.js"></script>
		<script defer src="/js/main/mkwcansas/mkw.js"></script>
		<script defer src="/js/main/mkwcansas/checks.js"></script>
		<script defer src="/js/main/mkwcansas/checkout.js"></script>
		<script defer src="/js/main/mkwcansas/cart.js"></script>
		<script defer src="/js/main/mkwcansas/fiok.js"></script>
		<script defer src="/js/main/mkwcansas/termekertekeles.js"></script>
		<script defer src="/js/main/mkwcansas/mkwcansas.js"></script>
        {else}
        <script defer src="/js/main/mkwcansas/mkwapp.js?v={$jsversion}"></script>
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
	<body>
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