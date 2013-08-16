<!DOCTYPE html>
<html lang="hu">
	<head>
		<meta charset="utf-8">
		<meta name="description" content="{$seodescription|default}">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<title>{$pagetitle|default}</title>
		<link type="application/rss+xml" rel="alternate" title="{$feedhirtitle|default}" href="/feed/hir">
		<link type="application/rss+xml" rel="alternate" title="{$feedtermektitle|default}" href="/feed/termek">
		<link type="text/css" rel="stylesheet" href="/themes/main/mkwcansas/bootstrap.min.css">
		<link type="text/css" rel="stylesheet" href="/themes/main/mkwcansas/bootstrap-responsive.min.css">
		<link type="text/css" rel="stylesheet" href="/themes/main/mkwcansas/jquery.slider.min.css">
		<link type="text/css" rel="stylesheet" href="/themes/main/mkwcansas/magnific-popup.css">
		<link type="text/css" rel="stylesheet" href="/themes/main/mkwcansas/style.css">
		{block "css"}{/block}
		<script src="/js/main/mkwcansas/jquery-1.10.2.min.js"></script>
		<script src="/js/main/mkwcansas/jquery-ui-1.10.3.custom.min.js"></script>

		<script src="/js/main/mkwcansas/jquery-migrate-1.2.1.js"></script>

		<script src="/js/main/mkwcansas/jquery.magnific-popup.min.js"></script>
		<script src="/js/main/mkwcansas/jquery.slider.min.js"></script>
		<script src="/js/main/mkwcansas/bootstrap-transition.js"></script>
		<script src="/js/main/mkwcansas/bootstrap-modal.js"></script>
		<script src="/js/main/mkwcansas/bootstrap-tab.js"></script>
		<script src="/js/main/mkwcansas/bootstrap-typeahead.js"></script>
		<script src="/js/main/mkwcansas/bootstrap-tooltip.js"></script>
		<script src="/js/main/mkwcansas/jquery.ui.rcarousel.min.js"></script>
		<script src="/js/main/mkwcansas/h5f.js"></script>
		<script src="/js/main/mkwcansas/matt-accordion.js"></script>
		{block "script"}{/block}
		<script src="/js/main/mkwcansas/mkw.js"></script>
		<script src="/js/main/mkwcansas/checks.js"></script>
		<script src="/js/main/mkwcansas/checkout.js"></script>
		<script src="/js/main/mkwcansas/cart.js"></script>
		<script src="/js/main/mkwcansas/mkwcansas.js"></script>
	</head>
	<body>
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